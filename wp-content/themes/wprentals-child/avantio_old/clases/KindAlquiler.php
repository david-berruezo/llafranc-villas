<?php


/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 11/07/2021
 * Time: 12:10
 */

class KindAlquiler
{

    protected $table = 'dynamic_taxonomy';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language', 'text_title'];

    # name of kindAlquiler
    private $name = "Alquiler toda la Villa";


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function insertKinds($avantio_credentials)
    {

        # connect to database
        $wpdb = Database::getInstance();

        # name and id
        $mking_name = (string)$this->getName();
        # insert term if not exist | update term if exist and parent
        $actions = array(
            $mking_name
        );
        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key),
                'parent' => 0
            );
            $my_term_id = term_exists($key, 'property_action_category', $my_cat);
            if(!$my_term_id){
                $taxonomy_term =  wp_insert_term($key, 'property_action_category', $my_cat);
            }else{
                $taxonomy_term =  get_term($my_term_id["term_id"],'property_action_category' , 'array');
            }
        }// end foreach

        # add option save cityparent category_fetured_image and others
        $term_data = array(
            "pagetax" => "",
            "category_featured_image" => "http://localhost/brava_rentals/wp-content/uploads/importedmedia/blogmedia-361789.jpg",
            "category_tax" => "property_action_category"
        );
        if(is_array($taxonomy_term))
            $taxonomy = "taxonomy_".$taxonomy_term["term_id"];

        if(is_object($taxonomy_term))
            $taxonomy = "taxonomy_".$taxonomy_term->term_id;

        $return = add_option($taxonomy, $term_data, $deprecated = '', $autoload = 'yes' );

    }// en function


}// end class
?>