<?php
global $wpestate_curent_fav;
global $wpestate_currency;
global $wpestate_where_currency;
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $is_widget;
global $wpestate_row_number_col;
global $wpestate_full_page;
global $wpestate_listing_type;
global $wpestate_property_unit_slider;
global $wpestate_book_from;
global $wpestate_book_to;
global $wpestate_guest_no;

$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';

$col_class  =   'col-md-6';
$col_org    =   4;
 $title=get_the_title($post->ID);

if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.esc_attr($wpestate_row_number_col).' shortcode-col';
}

if(isset($is_widget) && $is_widget==1 ){
    $col_class='col-md-12';
    $col_org    =   12;
}

if(isset($wpestate_full_page) && $wpestate_full_page==1 ){
    $col_class='col-md-4 ';
    $col_org    =   3;
    if(isset($is_shortcode) && $is_shortcode==1 && $wpestate_row_number_col==''){
        $col_class='col-md-'.esc_attr($wpestate_row_number_col).' shortcode-col';
    }
}

$link                   =   esc_url ( get_permalink());
$wprentals_is_per_hour  =   wprentals_return_booking_type($post->ID);
$booking_type           =   wprentals_return_booking_type($post->ID);
$rental_type            =   wprentals_get_option('wp_estate_item_rental_type');
$link = wprentals_card_link_autocomplete($post->ID,$link,$wprentals_is_per_hour);

$preview        =   array();
$preview[0]     =   '';
$favorite_class =   'icon-fav-off';
$fav_mes        =   esc_html__( 'add to favorites','wprentals');
if($wpestate_curent_fav){
    if ( in_array ($post->ID,$wpestate_curent_fav) ){
    $favorite_class =   'icon-fav-on';
    $fav_mes        =   esc_html__( 'remove from favorites','wprentals');
    }
}

$listing_type_class='property_unit_v3';


global $schema_flag;

if( $schema_flag==1) {
    $schema_data='itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ';
}else{
    $schema_data=' itemscope itemtype="http://schema.org/Product" ';
}

$actual_language = pll_current_language();

//echo "actual language".$actual_language. "<br>";

/*
function descuentosPorTemporadas(){

    $my_wpdb = new wpdb("tiendapi_user","Perretin771","tiendapi_inmobiliaria",'localhost');

    $my_post = pll_get_post_translations($post->ID);
    $my_post = $my_post["es"];

    $fecha_entrada = new DateTime();
    $fecha_salida = new DateTime();
    $one_year_ago = new DateInterval( "P1Y" );
    $fecha_salida->add($one_year_ago);
    $fecha_saldia_formato = $fecha_salida->format('Y-m-d');
    $fecha_entrada_formato = $fecha_entrada->format('Y-m-d');
    $language = "es";

    $sql = " SELECT a.id , a.text_title , ap.name,ap.fecha,ap.type,ap.amount , ap.season , ap.id
FROM avantio_accomodations as a
JOIN avantio_pricemodifiers as ap on ap.id = a.avantio_pricemodifiers
where a.id = $my_post and language = 'es' and ap.id <> 1625122 ";

    $datos = $my_wpdb->get_results($sql);
    //p_($datos);

    // Por temporadas
    $season = -1;
    $vector_discounts = array();
    foreach ($datos as $my_dato){
        if ($season == -1){
            $temp = array(
                "fecha_entrada" => $my_dato->fecha,
                "fecha_salida"  => "",
                "amount"        => str_replace("-","",$my_dato->amount)
            );
            $season = $my_dato->season;
        }else if ($season != $my_dato->season && $season != -1){
            array_push($vector_discounts,$temp);
            $temp = array(
                "fecha_entrada" => $my_dato->fecha,
                "fecha_salida"  => "",
                "amount"        => str_replace("-","",$my_dato->amount)
            );
            $season = $my_dato->season;
        }else if ($season == $my_dato->season){
            $temp["fecha_salida"] = $my_dato->fecha;
        }
    }// end foreach

    array_push($vector_discounts,$temp);


    $isOrdered = false;
    while(!$isOrdered) {
        $isOrdered = true;
        for ($i = 0; $i < count($vector_discounts); $i++) {
            for ($j = 0; $j < count($vector_discounts); $j++) {
                if ($vector_discounts[$i]["amount"] < $vector_discounts[$j]["amount"] && ($vector_discounts[$i]["fecha_entrada"] == $vector_discounts[$j]["fecha_entrada"] && $vector_discounts[$i]["fecha_salida"] == $vector_discounts[$j]["fecha_salida"])) {
                    $vector_discounts[$i]["amount"] = $vector_discounts[$j]["amount"];
                    unset($vector_discounts[$j]);
                    $vector_discounts = array_values($vector_discounts);
                    $isOrdered = false;
                }
            }
        }
    }
}
*/
?>

<div <?php print trim($schema_data);?> class="listing_wrapper <?php print esc_attr($col_class.' '.$listing_type_class); ?>  property_flex propety_unit_3" data-org="<?php print esc_attr($col_org);?>" data-listid="<?php print esc_attr($post->ID);?>" >
    <?php if( $schema_flag==1) {?>
        <meta itemprop="position" content="<?php print esc_html($prop_selection->current_post);?>" />
    <?php } ?>

    <div class="property_listing" >
        <?php

            $featured           =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $agent_id           =   wpsestate_get_author($post->ID);
            $agent_id           =   get_user_meta($agent_id, 'user_agent_id', true);
            $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
            $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
            $preview_agent_img  =   '';
            
            if(isset($preview_agent[0])){
                $preview_agent_img  =   $preview_agent[0];
            }
            
            if($preview_agent_img   ==  ''){
                $preview_agent_img    =   get_stylesheet_directory_uri().'/img/default_user_small.png';
            }

            $agent_link         =   esc_url(get_permalink($agent_id));

            $price              =   intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $property_action    =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');
            $property_categ     =   get_the_term_list($post->ID, 'property_category', '', ', ', '');
            ?>

            <?php wpestate_print_property_unit_slider($post->ID,$wpestate_property_unit_slider,$wpestate_listing_type,$wpestate_currency,$wpestate_where_currency,$link,''); ?>

            <div class="property_unit_action">
                <!--
                <span class="icon-fav <?php print esc_attr($favorite_class); ?>" data-original-title="<?php print esc_attr($fav_mes); ?>" data-postid="<?php print intval($post->ID); ?>"><i class="fas fa-heart"></i></span>
                -->
            </div>

            <?php
            if($featured == 1){

                $my_wpdb = new wpdb("root","Berruezin23","tiendapi_inmobiliaria_online",'localhost');
                // $my_wpdb = new wpdb("automocion_usuario","Avantio777","automocion_inmobiliaria",'localhost');

                $my_post = pll_get_post_translations($post->ID);
                $my_post = $my_post["es"];

                $fecha_entrada = new DateTime();
                $fecha_salida = new DateTime();
                $one_year_ago = new DateInterval( "P1Y" );
                $fecha_salida->add($one_year_ago);
                $fecha_saldia_formato = $fecha_salida->format('Y-m-d');
                $fecha_entrada_formato = $fecha_entrada->format('Y-m-d');
                $language = "es";

                $sql = " SELECT a.id , a.text_title , ap.name,ap.fecha,ap.type,ap.amount , ap.season , ap.id as id_descuento
FROM avantio_accomodations as a
JOIN avantio_pricemodifiers as ap on ap.id = a.avantio_pricemodifiers
where a.id = $my_post and language = 'es' and ap.id <> 1625122 ";

                $datos = $my_wpdb->get_results($sql);
                //p_($datos);

                // Por temporadas
                $id_descuento = -1;
                $vector_discounts = array();
                foreach ($datos as $my_dato){
                    if ($id_descuento  == -1){
                        $temp = array(
                            "fecha_entrada" => $my_dato->fecha,
                            "fecha_salida"  => "",
                            "amount"        => str_replace("-","",$my_dato->amount)
                        );
                        $id_descuento = $my_dato->id_descuento;
                    }else if ($id_descuento != $my_dato->id_descuento && $id_descuento != -1){
                        array_push($vector_discounts,$temp);
                        $temp = array(
                            "fecha_entrada" => $my_dato->fecha,
                            "fecha_salida"  => "",
                            "amount"        => str_replace("-","",$my_dato->amount)
                        );
                        $id_descuento = $my_dato->id_descuento;
                    }else if ($id_descuento == $my_dato->id_descuento){
                        $temp["fecha_salida"] = $my_dato->fecha;
                    }
                }// end foreach

                array_push($vector_discounts,$temp);
                

                $isOrdered = false;
                while(!$isOrdered) {
                    $isOrdered = true;
                    for ($i = 0; $i < count($vector_discounts); $i++) {
                        for ($j = 0; $j < count($vector_discounts); $j++) {
                            //echo "tipo objeto: " . gettype($vector_discounts[$i]) . "<br>";
                            if (!is_object($vector_discounts[$i])){
                                if ($vector_discounts[$i]["amount"] < $vector_discounts[$j]["amount"] && ($vector_discounts[$i]["fecha_entrada"] == $vector_discounts[$j]["fecha_entrada"] && $vector_discounts[$i]["fecha_salida"] == $vector_discounts[$j]["fecha_salida"])) {
                                    $vector_discounts[$i]["amount"] = $vector_discounts[$j]["amount"];
                                    unset($vector_discounts[$j]);
                                    $vector_discounts = array_values($vector_discounts);
                                    $isOrdered = false;
                                }
                            }
                        }
                    }
                }




                print '<div class="featured_div">'.esc_html__( 'featured','wprentals').'</div>';
                if (!is_object($vector_discounts[0]) && is_array($vector_discounts[0])) {
                    if (count($vector_discounts) == 1) {
                        $descuento = ($vector_discounts[0]["amount"]) ? intval($vector_discounts[0]["amount"]) . " %" : "";
                        print '<div class="property_status_wrapper"><div class="property_status status_nuevo">-' . $descuento . '</div></div>';
                    }
                }

            }

            //echo wpestate_return_property_status($post->ID);
            ?>

            <div class="title-container">

                <?php
                if(wpestate_has_some_review($post->ID)!==0){
                    print wpestate_display_property_rating( $post->ID );
                }else{
                    print '<div class=rating_placeholder></div>';
                }
                ?>

                <div class="category_name">
                    <?php
                        include(locate_template('templates/property_card_templates/property_card_title.php'));
                    ?>

                    <div class="category_tagline actions_icon">
                        <?php //print trim($property_categ.' / '.$property_action);?>
                        <?php print trim($property_categ);?>
                    </div>

                    <div class="category_tagline custom_details">
                       <?php
                        $options_array=array(
                            0   =>  esc_html__('Single Fee','wprentals'),
                            1   =>  wpestate_show_labels('per_night',$rental_type,$booking_type),
                            2   =>  esc_html__('Per Guest','wprentals'),
                            3   =>  wpestate_show_labels('per_night_per_guest',$rental_type,$booking_type)
                        );


                       $custom_listing_fields = wprentals_get_option( 'wp_estate_custom_listing_fields');

                       foreach ($custom_listing_fields as $field){
                            if($field[2]!='none'){

                                if( $field[2]=='property_category' || $field[2]=='property_action_category' ||  $field[2]=='property_city' ||  $field[2]=='property_area' ){
                                    $value=   get_the_term_list($post->ID, $field[2], '', ', ', '');
                                }else{

                                    $slug       =   wpestate_limit45(sanitize_title( $field[2] ));
                                    $slug       =   sanitize_key($slug);
                                    $value      =   esc_html(get_post_meta($post->ID, $slug, true));

                                }


                                if($value!=''){
                                    //  Guests
                                    //echo "campo: ".esc_html($field[0])."<br>";
                                    print '<div class="custom_listing_data">';
                                    if($field[0]!=''){
                                        $language = pll_current_language();
                                        switch($language){
                                            case "es":
                                                if ($field[0] == "Guests")
                                                    print '<span class="custom_listing_data_label">Huéspedes:</span>';
                                                else
                                                    print '<span class="custom_listing_data_label">'.stripslashes(esc_html($field[0])).':</span>';
                                            break;
                                            default: print '<span class="custom_listing_data_label">'.stripslashes(esc_html($field[0])).':</span>';
                                            break;
                                        }

                                    }else{
                                        if($field[1]!=''){
                                            print '<i class="'.esc_attr($field[1]).'"></i>';
                                        }
                                    }


                                    $price_items =array('property_price','city_fee','cleaning_fee','price_per_weekeend','property_price_per_week','property_price_per_month','extra_price_per_guest','security_deposit');

                                    if( $value!=0 && in_array($field[2], $price_items) ){
                                        if( $field[2]=='property_price'){
                                            print get_post_meta($post->ID, 'property_price_before_label', true).' ';
                                        }
                                        print wpestate_show_price_booking($value,$wpestate_currency,$wpestate_where_currency,1);
                                        if( $field[2]=='cleaning_fee' ){
                                            $cleaning_fee_per_day           =   floatval  ( get_post_meta($post->ID,  'cleaning_fee_per_day', true) );
                                            print ' '. trim($options_array[ intval($cleaning_fee_per_day) ]);
                                        }

                                        if(   $field[2]=='city_fee' ){
                                            $city_fee_per_day      =   floatval  ( get_post_meta($post->ID,  'city_fee_per_day', true) );
                                            print ' '.trim($options_array[ intval($city_fee_per_day) ]);
                                        }

                                        if( $field[2]=='property_price'){
                                            print ' '.get_post_meta($post->ID, 'property_price_after_label', true);
                                        }
                                    }else if( $field[2]=='property_size'){

                                        $measure_sys    =   esc_html (wprentals_get_option('wp_estate_measure_sys',''));
                                        if(is_numeric($value)){
                                            print number_format(floatval($value)) . ' '.esc_html($measure_sys).'<sup>2</sup>';
                                        }
                                    }else if( $field[2]=='property_taxes'){
                                        print '%';
                                    }else{
                                        print trim($value);
                                    }

                                    print '</div>';
                                }
                            }
                       }

                    ?>
                    </div>

                </div>

                <?php
                if($featured == 1) {
                    if ($actual_language == "es") {
                        $titulo_descuento = "Descuento";
                        $titulo_hasta_descuento = "Hasta";
                    }else if ($actual_language == "en"){
                        $titulo_descuento = "Discount";
                        $titulo_hasta_descuento = "Until";
                    }else if ($actual_language == "fr"){
                        $titulo_descuento = "Rabais";
                        $titulo_hasta_descuento = "Jusqu'à";
                    }else if ($actual_language == "ca"){
                        $titulo_descuento = "Descompte";
                        $titulo_hasta_descuento = "Fins";
                    } // end if
                    foreach ($vector_discounts as $discount){
                        if (!is_object($discount) && is_array($discount)) {
                            $fecha_entrada = ($discount["fecha_entrada"]) ? $discount["fecha_entrada"] : "" ;
                            $fecha_salida = ($discount["fecha_salida"]) ? $discount["fecha_salida"] : "" ;
                            $descuento = ($discount["amount"]) ? intval($discount["amount"]) . " %" :  "" ;
                        }
                    ?>
                        <div class="category_name">
                            <?php if(count($vector_discounts) > 1){ ?>
                                <?php $fecha_salida = ($fecha_salida instanceof DateTime) ? $fecha_salida->format("Y-m-d") : ""; ?>
                                <?php $fecha_entrada = ($fecha_entrada instanceof DateTime) ? $fecha_entrada->format("Y-m-d") : ""; ?>
                                <?php print '<span class="pernight" style="background:#00518f;font-size:14px;padding:3px;top:5px;position:relative;">'.$titulo_descuento. ': ' . $fecha_entrada . ' '. $titulo_hasta_descuento . ' ' .  $fecha_salida . '</span>' ?>
                            <?php }else{ ?>
                                <?php $fecha_salida = ($fecha_salida instanceof DateTime) ? $fecha_salida->format("Y-m-d") : "" ?>
                                <?php $fecha_entrada = ($fecha_entrada instanceof DateTime) ? $fecha_entrada->format("Y-m-d") : ""; ?>
                                <?php print '<span class="pernight" style="background:#00518f;font-size:14px;padding:3px;top:5px;position:relative;">'.$titulo_descuento. ': ' . $fecha_entrada . ' '. $titulo_hasta_descuento . ' ' . $fecha_salida . '</span>' ?>
                            <?php } ?>
                        </div>
                        <div style="position:relative;height:5px;margin-top:5px;"><br style="clear:both;"></div>
                        
                    <?php }// end foreach ?>

                <?php } ?>

            </div>
        <?php
        if ( isset($show_remove_fav) && $show_remove_fav==1 ) {
            print '<span class="icon-fav icon-fav-on-remove" data-postid="'.intval($post->ID).'"> '.esc_html($fav_mes).'</span>';
        }
        ?>
    </div>
</div>
