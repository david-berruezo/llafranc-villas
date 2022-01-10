<?php


/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 11/07/2021
 * Time: 12:10
 */

class Status
{

    protected $table = 'dynamic_taxonomy';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language', 'text_title'];

    # name of status
    private $name = "Nuevo";

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    public function insertStatus($avantio_credentials)
    {

        # connect to database
        $wpdb = Database::getInstance();

        # term_vector
        $term_vector = array();

        # create taxonomy
        //create_taxonomy_property_status();

        $counter_element = 0;
        $max_elements = 0;

        foreach($avantio_credentials['ACTIVED_LANGUAGES'] as $lang){

            $counter_element = 0;

            $term_vector[$lang][$counter_element] = array();

            //echo "taxonomia: ".$mking_name."<br>";

            $mking_name = "";

            switch($lang){

                case "es": $mking_name = "Nuevo";
                break;

                case "en": $mking_name = "New";
                break;

                case "ca": $mking_name = "Nou";
                break;

                case "fr": $mking_name = "Nouveau";
                break;

            }// end switch

            # es | en  | others
            $my_cat = array(
                'description' => $mking_name,
                'slug' =>sanitize_title($mking_name),
                'parent' => 0
            );

            $term_vector[$lang][$counter_element] = term_exists($mking_name, 'property_status',$my_cat);
            if(!$term_vector[$lang][$counter_element] ){
                $term_vector[$lang][$counter_element] = wp_insert_term($mking_name, 'property_status',$my_cat);
                //my_print($term_vector[$lang][$counter_element]);
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }else{
                pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
            }


            //my_print($term_vector[$lang][$counter_element]);

            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "pagetax" => "",
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category_tax" => "property_status"
            );

            if (is_array($term_vector[$lang][$counter_element]))
                $taxonomy = "taxonomy_".$term_vector[$lang][$counter_element]["term_id"];

            if(is_object($term_vector[$lang][$counter_element]))
                $taxonomy = "taxonomy_".$term_vector[$lang][$counter_element]->term_id;

            $counter_element++;

            if($counter_element > $max_elements)
                $max_elements = $counter_element;

        }// end foreach avantio credentials

        for($i = 0; $i < $max_elements; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for

    }// en function




    private function create_taxonomy_property_status()
    {

        $slug = 'property_status';

        // add custom taxonomy
        register_taxonomy('property_status', 'estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Property Status', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add New Status', 'wprentals-core'),
                    'new_item_name' => esc_html__('New Status', 'wprentals-core')
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)

            )
        );

    } // end function



}// end class
?>