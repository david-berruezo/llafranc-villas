<?php

class Piscina{

    protected $table      = 'dynamic_taxonomy';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language','text_title'];


    public function insertPiscina($xml,$avantio_credentials)
    {
        # connect to database
        $wpdb = Database::getInstance();

        # term_vector
        $term_vector = array();
        $max_elements = 0;
        $terms_luxury = array();

        # create taxonomy
        //$this->create_taxonomy_property_category();

        # taxonomias seleccionadas | apartamento
        $vector_id_taxonomias = array(1,2);

        # vectors
        $post_vector = array();

        # counters
        $counter_property = 0;
        $counter_property_create = 0;
        $counter_language = 0;


        foreach ($xml->Accommodation as $accommodation) {


            if ($counter_property < 10) {

                # normal fields
                $accion = "insert_or_update";
                $id = (int)$accommodation->AccommodationId;
                $text_title = (string)$accommodation->AccommodationName;
                # kind | taxonomy
                $dynamic_taxonomy = intval($accommodation->MasterKind->MasterKindCode);
                $dynamic_taxonomy_group = intval($accommodation->MasterKind->MasterKindCode);
                $taxonomy_name = (string)$accommodation->MasterKind->MasterKindName;
                // echo "identificador: " .$id. "<br>";

                # villa o villa de lujo
                if ($accommodation->Labels) {
                    foreach ($accommodation->Labels->children() as $mylabel) {
                        if (stripos($mylabel, "villa_de_lujo") !== false) {
                            $this->tipo_de_villa = "villa_de_lujo";
                        }// end if
                    }// end foreach
                }// end if

                # update
                if (get_post_status($id)) {

                # insert
                } else {



                }// // end if get_post_status

            }// end if counter 10

        }// end foreach accomodation

        /*
        foreach($xml->InternationalizedKinds as $kind){

            # language and term vector language
            $lang = (string)$kind->Language;

            $counter_element = 0;

            if(!in_array($lang,$avantio_credentials['ACTIVED_LANGUAGES'])) continue;
            foreach($kind->MasterKind as $mkind){

                # name and id
                $mkind_id = intval($mkind->MasterKindCode);
                $mking_name = (string)$mkind->MasterKindName  . "-" . $lang;

                if(in_array($mkind_id , $vector_id_taxonomias)){

                    $term_vector[$lang][$counter_element] = array();

                    //echo "taxonomia: ".$mking_name."<br>";

                    # es | en  | others
                    $my_cat = array(
                        'description' => $mking_name,
                        'slug' =>sanitize_title($mking_name),
                        'parent' => 0
                        //'name' => $mking_name . "-" . $lang
                    );

                    $term_vector[$lang][$counter_element] = term_exists($mking_name, 'property_category',$my_cat);
                    if(!$term_vector[$lang][$counter_element] ){
                        $term_vector[$lang][$counter_element] = wp_insert_term($mking_name, 'property_category',$my_cat);
                        //my_print($term_vector[$lang][$counter_element]);
                        pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                    }else{
                        pll_set_term_language($term_vector[$lang][$counter_element]["term_id"], $lang);
                    }

                    # change name
                    $word = "-" . $lang;
                    $final_name = str_replace($word, "",$mking_name);
                    $args = array('name' => $final_name , 'description' => $final_name);
                    //print_r($args);
                    wp_update_term( $term_vector[$lang][$counter_element]["term_id"], 'property_category', $args );


                    // my_print($term_vector[$lang][$counter_element]);

                    # add option save cityparent category_fetured_image and others
                    $term_data = array(
                        "pagetax" => "",
                        "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                        "category_tax" => "property_category"
                    );

                    if (is_array($term_vector[$lang][$counter_element]))
                        $taxonomy = "taxonomy_".$term_vector[$lang][$counter_element]["term_id"];

                    if(is_object($term_vector[$lang][$counter_element]))
                        $taxonomy = "taxonomy_".$term_vector[$lang][$counter_element]->term_id;

                    $counter_element++;

                }// end if in_array vector_id_taxonomias


            }// end foreach MasterKind

            if($counter_element > $max_elements)
                $max_elements = $counter_element;


            # luxury term
            switch($lang){
                case "es": $mking_name =  "Villa de lujo" .  "-" . $lang;
                    break;
                case "en": $mking_name =  "Luxury Village" .  "-" . $lang;
                    break;
                case "ca": $mking_name =  "Vila de luxe" .  "-" . $lang;
                    break;
                case "fr": $mking_name =  "Villa de luxe" .  "-" . $lang;
                    break;
            }

            # es | en  | others
            $my_cat = array(
                'description' => $mking_name,
                'slug' =>sanitize_title($mking_name),
                'parent' => 0
                //'name' => $mking_name . "-" . $lang
            );

            $terms_luxury[$lang][0] = term_exists($mking_name, 'property_category',$my_cat);
            if(!$terms_luxury[$lang][0] ){
                $terms_luxury[$lang][0] = wp_insert_term($mking_name, 'property_category',$my_cat);
                pll_set_term_language($terms_luxury[$lang][0]["term_id"], $lang);
            }else{
                pll_set_term_language($terms_luxury[$lang][0]["term_id"], $lang);
            }


            # change name
            $word = "-" . $lang;
            $final_name = str_replace($word, "",$mking_name);
            $args = array('name' => $final_name , 'description' => $final_name);
            //print_r($args);
            wp_update_term( $terms_luxury[$lang][0]["term_id"], 'property_category', $args );


            # add option save cityparent category_fetured_image and others
            $term_data = array(
                "pagetax" => "",
                "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
                "category_tax" => "property_category"
            );

            if (is_array($terms_luxury[$lang][0]))
                $taxonomy = "taxonomy_".$terms_luxury[$lang][0]["term_id"];

            if(is_object($terms_luxury[$lang][0]))
                $taxonomy = "taxonomy_".$terms_luxury[$lang][0]->term_id;



        }// end foreach InternationalizedKinds


        # save relationship polylang languages
        for($i = 0; $i < $max_elements; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                $vector_plantilla[$lang] = $term_vector[$lang][$i]["term_id"];
            }// end for
            pll_save_term_translations($vector_plantilla);
            //my_print($vector_plantilla);
        }// end for

        # save relationship luxury
        $vector_plantilla = array();
        for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
            $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
            $vector_plantilla[$lang] = $terms_luxury[$lang][0]["term_id"];
        }// end for
        pll_save_term_translations($vector_plantilla);
        //my_print($vector_plantilla);


        //$this->insert_term_taxonomy_multilanguage("property_category" , $terms_luxury);
        */

    } // end function


    function create_taxonomy_property_category()
    {

        $name_label = esc_html__('Categories', 'wprentals-core');
        $add_new_item_label = esc_html__('Add New Listing Category', 'wprentals-core');
        $new_item_name_label = esc_html__('New Listing Category', 'wprentals-core');

        $slug = 'property_category';


        register_taxonomy('property_category', 'estate_property', array(
                'labels' => array(
                    'name' => $name_label,
                    'add_new_item' => $add_new_item_label,
                    'new_item_name' => $new_item_name_label
                ),
                'hierarchical' => true,
                'query_var' => true,
                'rewrite' => array('slug' => $slug)
            )
        );

    } // end function


    public function insert_term_taxonomy_multilanguage($taxonomy , $terms)
    {
        $term_vector = array();
        $term_final_vector = array();

        foreach ($terms as $lang => $term_value) {
            $name = sanitize_title($term_value);
            $my_cat = array(
                'description' => $term_value,
                'slug' =>sanitize_title($term_value)
            );
            $term_vector[$lang] = term_exists($name, $taxonomy);
            if(!$term_vector ){
                $term_vector[$lang] = wp_insert_term($name, $taxonomy);
                pll_set_term_language($term_vector[$lang]["term_id"], $lang);
                $term_final_vector[$lang] = $term_vector[$lang]["term_id"];
            }else{
                pll_set_term_language($term_vector[$lang]["term_id"], $lang);
                $term_final_vector[$lang] = $term_vector[$lang]["term_id"];
            }// end if
        } // end foreach

        //my_print($term_final_vector);
        pll_save_term_translations($term_final_vector);

    } // end function




    private function add_two_primary_keys()
    {

        $forge = \Config\Database::forge();
        $forge->addKey('id', TRUE);
        $forge->addKey('language', TRUE);

    } // end function


    private function insertar_actualizar()
    {

        $data = [
            'id'            => "",
            'language'      => "",
            'text_title'    => ""
        ];
        $this->save($data);

    } // end function

}// end class
?>