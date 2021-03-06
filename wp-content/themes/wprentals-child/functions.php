<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 06/11/2021
 * Time: 17:58
 */

# constants
define("app_child_url", __DIR__);

# includes
include(app_child_url . "/Clases/Database.php");
# helpers
include(app_child_url . "/helpers/funciones.php");
include(app_child_url . "/helpers/funciones_wordpress.php");

# var
$db = "";
//$avantio_credential = "local_wordpress";
$avantio_credential = "servidor_tiendapisos_uno";
$services = "";
$actual_language = "";

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

# styles
if ( !function_exists( 'wpestate_chld_thm_cfg_parent_css' ) ):
    function wpestate_chld_thm_cfg_parent_css() {

        $parent_style = 'wpestate_style';
        wp_enqueue_style('bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
        wp_enqueue_style('bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
        wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css',array('bootstrap','bootstrap-theme'),'all' );
        wp_enqueue_style( 'wpestate-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            array( $parent_style ),
            wp_get_theme()->get('Version')
        );
        //wp_enqueue_style( 'select2-child-style', get_stylesheet_directory_uri() . '/css/select2.min.css', array(), wp_get_theme()->get('Version') );
        wp_enqueue_style( 'bootstrap-select-child-style', get_stylesheet_directory_uri() . '/css/bootstrap-select.min.css', array(), wp_get_theme()->get('Version') );
    }

endif;

add_action( 'wp_enqueue_scripts', 'wpestate_chld_thm_cfg_parent_css' );

# language
load_child_theme_textdomain('wprentals', get_stylesheet_directory().'/languages');
// END ENQUEUE PARENT ACTION


# connect to database function
function connect_two_db(){
    global $db , $avantio_credential;

    $connector = new Database();
    $connector->setCredential($avantio_credential);
    $db = $connector::getInstance();
}

# call connect to database function
connect_two_db();


# get actual_language
$actual_language = pll_current_language();


# our functions
# child theme

# javascript
add_action( 'wp_enqueue_scripts', 'wp_enqueue_scripts_brava' );
function wp_enqueue_scripts_brava() {
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ));
    //wp_enqueue_script('select-script', get_stylesheet_directory_uri() . '/js/select2.min.js', array( 'jquery' ));
    wp_enqueue_script('bootstrap-select', get_stylesheet_directory_uri() . '/js/bootstrap-select.min.js', array( 'jquery' ));
}


# get properties list by ajax
function load_properties(){

    $language = pll_current_language();

    $args = array(
        "post_type" => "estate_property",
        "lang" => $language,
        "numberposts" => -1
    );
    $properties = get_posts($args);
    //print_r($properties);
    $res = array("language" => $language , "properties" => $properties );
    echo json_encode($res,true);
    die();
}

add_action('wp_ajax_nopriv_load_properties', 'load_properties');
//add_action('wp_ajax_load_properties', 'load_properties');

add_action("init","add_footer");

function add_footer(){
    // $shortcode = "[elementor-template id=\"371106\"]";
    // add_shortcode($shortcode);
    // delete_all_terms( 'property_piscina');
    // unregister_taxonomy( 'property_piscina');
}



# add_action("init","delete_video");

# link de mostrar casa
# https://www.youtube.com/watch?v=AzJ6dyGZbRc
function delete_video(){
    $args = array(
        'post_type' => 'estate_property',
        "numberposts" => -1
    );
    $posts = get_posts($args);
    foreach ($posts as $post) {
        update_post_meta($post->ID, 'embed_video_type', 0, $prev_value = '');
        update_post_meta($post->ID, 'embed_video_id', "", $prev_value = '');
        update_post_meta($post->ID, 'virtual_tour', "", $prev_value = '');
    } // end foreach
}


add_action( 'init', 'delete_service' );

function delete_service(){
    //unregister_taxonomy( 'extra_services');
}



//add_action( 'init', 'delete_all' );

/*
function delete_all(){

    # delete posts and terms
    delete_posts_by_custom_post_type();
    delete_terms_of_taxonomies();

    # delete post type and taxonomies
    //delete_custom_post_type_estate();
    //delete_taxonomies();
}

function delete_posts_by_custom_post_type(){
    $args = array(
        'post_type' => 'estate_property',
        "numberposts" => -1
    );
    $posts = get_posts($args);
    $ids = array_column($posts,"ID");
    clean_object_term_cache( $ids, 'estate_property' );
    foreach ($posts as $post) {
        $delete = wp_delete_post($post->ID,true);
    } // end foreach
}

function delete_custom_post_type_estate(){
    unregister_post_type( 'estate_property' );
}

function delete_taxonomies(){
    unregister_taxonomy( 'property_category');
    unregister_taxonomy( 'property_action_category');
    unregister_taxonomy( 'property_city');
    unregister_taxonomy( 'property_area');
    unregister_taxonomy( 'property_features');
    unregister_taxonomy( 'property_status');
}

function delete_terms_of_taxonomies(){
    delete_all_terms( 'property_category');
    delete_all_terms( 'property_action_category');
    delete_all_terms( 'property_city');
    delete_all_terms( 'property_area');
    delete_all_terms( 'property_features');
    delete_all_terms( 'property_status');
}


function delete_all_terms($taxonomy_name){
    $terms = get_terms( array(
        'taxonomy' => $taxonomy_name,
        'hide_empty' => false
    ));
    foreach ( $terms as $term ) {
        wp_delete_term($term->term_id, $taxonomy_name);
    }
}
*/


// check the page to fill entorno y distancia
add_action( 'init', 'check_page' );


function check_page(){

    if (is_page_template()){
        echo "page_template: <br>";
    }

    if (is_page()){
        echo "page: <br>";
    }

    if (is_single()){
        echo "single: <br>";
    }

    if (is_singular()){
        echo "singular: <br>";
    }



    /*
    echo "el id: " . get_the_ID() . "<br>";

    if ( is_singular( 'estate_property' ) ) {
        echo "el id: " . get_the_ID() . "<br>";
        entorno_y_distancia();
    }
    */

    /*
    echo "el id es: ".get_the_ID()."<br>";
    if (is_page()){
        echo get_the_ID();
        //entorno_y_distancia();
    }
    */
}

function entorno_y_distancia(){

    $xml = load_xml();
    $post_id = get_the_ID();

    $vector_posts = pll_get_post($post_id);
    print_r($vector_posts);
    $vector_posts = pll_get_post_language($post_id);
    print_r($vector_posts);

    foreach($xml->Accommodation as $accommodation) {

        $avantio_accomodations = intval($accommodation->AccommodationId);

        echo "id: ".$avantio_accomodations."<br>";
        echo "post_id: ".$post_id."<br>";

        if (in_array($post_id,$vector_posts)) {

            echo "encontrado<br>";

            $text_title = $accommodation->AccommodationName;

            $loc_where = (string)$accommodation->Features->Location->LocationDescription->Where;
            $loc_howto = (string)$accommodation->Features->Location->LocationDescription->Howto;
            $loc_desc1 = (string)$accommodation->Features->Location->LocationDescription->Description1;
            $loc_desc2 = (string)$accommodation->Features->Location->LocationDescription->Description2;

            # playa
            $beach_name = (string)$accommodation->Features->Location->LocationDistances->BeachDistance->Name;
            $beach_dist = floatval($accommodation->Features->Location->LocationDistances->BeachDistance->Value);
            $beach_unit = (string)$accommodation->Features->Location->LocationDistances->BeachDistance->Unit;
            //$beach_dist=(strtoupper($beach_unit)=='KM')? intval( 1000*$beach_dist ) : intval($beach_dist);

            # golf
            $golf_name = (string)$accommodation->Features->Location->LocationDistances->GolfDistance->Name;
            $golf_dist = floatval($accommodation->Features->Location->LocationDistances->GolfDistance->Value);
            $golf_unit = (string)$accommodation->Features->Location->LocationDistances->GolfDistance->Unit;
            //$golf_dist=(strtoupper($golf_unit)=='KM')? intval( 1000*$golf_dist ) : intval($golf_dist);

            # ciudad
            $city_name = (string)$accommodation->Features->Location->LocationDistances->CityDistance->Name;
            $city_dist = floatval($accommodation->Features->Location->LocationDistances->CityDistance->Value);
            $city_unit = (string)$accommodation->Features->Location->LocationDistances->CityDistance->Unit;
            //$city_dist=(strtoupper($city_unit)=='KM')? intval( 1000*$city_dist ) : intval($city_dist);

            # super
            $super_name = (string)$accommodation->Features->Location->LocationDistances->SuperMarketDistance->Name;
            $super_dist = floatval($accommodation->Features->Location->LocationDistances->SuperMarketDistance->Value);
            $super_unit = (string)$accommodation->Features->Location->LocationDistances->SuperMarketDistance->Unit;
            //$super_dist=(strtoupper($super_unit)=='KM')? intval( 1000*$super_dist ) : intval($super_dist);

            # areopuerto
            $airport_name = (string)$accommodation->Features->Location->LocationDistances->AirportDistance->Name;
            $airport_dist = floatval($accommodation->Features->Location->LocationDistances->AirportDistance->Value);
            $airport_unit = (string)$accommodation->Features->Location->LocationDistances->AirportDistance->Unit;
            //$airport_dist=(strtoupper($airport_unit)=='KM')? intval( 1000*$airport_dist ) : intval($airport_dist);

            # tren
            $train_name = (string)$accommodation->Features->Location->LocationDistances->TrainStationDistance->Name;
            $train_dist = floatval($accommodation->Features->Location->LocationDistances->TrainStationDistance->Value);
            $train_unit = (string)$accommodation->Features->Location->LocationDistances->TrainStationDistance->Unit;
            //$train_dist=(strtoupper($train_unit)=='KM')? intval( 1000*$train_dist ) : intval($train_dist);

            # bus
            $bus_name = (string)$accommodation->Features->Location->LocationDistances->StopBusDistance->Name;
            $bus_dist = floatval($accommodation->Features->Location->LocationDistances->StopBusDistance->Value);
            $bus_unit = (string)$accommodation->Features->Location->LocationDistances->StopBusDistance->Unit;
            //$bus_dist=(strtoupper($bus_unit)=='KM')? intval( 1000*$bus_dist ) : intval($bus_dist);

            # vistas
            $view_to_beach = ((string)$accommodation->Features->Location->LocationViews->ViewToBeach == 'true') ? 1 : 0;
            $view_to_swimming_pool = ((string)$accommodation->Features->Location->LocationViews->ViewToSwimmingPool == 'true') ? 1 : 0;
            $view_to_golf = ((string)$accommodation->Features->Location->LocationViews->ViewToGolf == 'true') ? 1 : 0;
            $view_to_garden = ((string)$accommodation->Features->Location->LocationViews->ViewToGarden == 'true') ? 1 : 0;
            $view_to_river = ((string)$accommodation->Features->Location->LocationViews->ViewToRiver == 'true') ? 1 : 0;
            $view_to_mountain = ((string)$accommodation->Features->Location->LocationViews->ViewToMountain == 'true') ? 1 : 0;
            $view_to_lake = ((string)$accommodation->Features->Location->LocationViews->ViewToLake == 'true') ? 1 : 0;

            # primera linia
            $first_line_beach = ((string)$accommodation->Features->Location->FirstBeachLine == 'true') ? 1 : 0;
            $first_line_golf = ((string)$accommodation->Features->Location->FirstGolfLine == 'true') ? 1 : 0;
        }

    }// end foreach

}


function load_xml(){
    # read and save xml files
    # path
    $files_to_search = "c:\htdocs\brava_rentals\avantio_cron_cli\app\xmldata";
    //$files_to_search = "H:\htdocs\wordpress\avantio_cron_cli\app\xmldata";
    //$files_to_search = "/home/automocion/public_html/avantio_cron_cli/app/xmldata";
    //p_($files_to_search);
    $files = array();
    # read scandir
    $files_to_search_scan = scandir($files_to_search);
    foreach($files_to_search_scan as $value)
    {
        //p_($value);
        if($value === '.' || $value === '..') { continue; }
        //echo "fichero: ".$value. "<br>";
        if(is_file("$files_to_search/$value")) {
            if (strpos($value,"accommodations") !== false){
                $files["accomodations"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"availabilities") !== false){
                $files["availabilities"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"descriptions") !== false){
                $files["descriptions"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"geographicareas") !== false){
                $files["geographicareas"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"kinds") !== false){
                $files["kinds"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"occupational") !== false){
                $files["ocuppationalrules"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"pricemodificers") !== false){
                $files["pricemodificers"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"rates") !== false){
                $files["rates"] = $files_to_search . "/" . $value;
            }
            if (strpos($value,"services") !== false){
                $files["services"] = $files_to_search . "/" . $value;
            }
        } // end if
    } // end foreach
    //p_($files)
    $xml = simplexml_load_file($files["accomodations"]);
    return $xml;
}
?>