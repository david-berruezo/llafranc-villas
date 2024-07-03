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
global $post;

$booking_type       =   wprentals_return_booking_type($post->ID);
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type');

if($wpestate_listing_type==3){
    include(locate_template('templates/property_unit_3.php') );
    return true;
}

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
$title      =   get_the_title($post->ID);

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

$link                       =   esc_url ( get_permalink());
$wprentals_is_per_hour      =   wprentals_return_booking_type($post->ID);
$link                       =   wprentals_card_link_autocomplete($post->ID,$link,$wprentals_is_per_hour);

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

$listing_type_class='property_unit_v2';
if($wpestate_listing_type==1){
    $listing_type_class='property_unit_v1';
}


global $schema_flag;
if( $schema_flag==1) {
   $schema_data='itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" ';
}else{
   $schema_data=' itemscope itemtype="http://schema.org/Product" ';
}

$actual_language = pll_current_language();
?>


<div <?php print trim($schema_data);?> class="listing_wrapper <?php print esc_attr($col_class).' '.esc_attr($listing_type_class); ?>  property_flex " data-org="<?php print esc_attr($col_org);?>" data-listid="<?php print esc_attr($post->ID);?>" >

    <?php if( $schema_flag==1) {?>
        <meta itemprop="position" content="<?php print esc_html($prop_selection->current_post);?>" />
    <?php } ?>

    <div class="property_listing " >
        <?php

            $featured           =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $price              =   intval( get_post_meta($post->ID, 'property_price', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
            $property_action    =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');
            $property_categ     =   get_the_term_list($post->ID, 'property_category', '', ', ', '');
            ?>


            <?php wpestate_print_property_unit_slider($post->ID,$wpestate_property_unit_slider,$wpestate_listing_type,$wpestate_currency,$wpestate_where_currency,$link,''); ?>

            <?php
            # pagina ofertas
            if($featured==1){

                //$my_wpdb = new wpdb("tiendapi_user","Perretin771","tiendapi_inmobiliaria",'localhost');
                $my_wpdb = new wpdb("automocion_usuario","Avantio777","automocion_inmobiliaria",'localhost');

                $my_post = pll_get_post_translations($post->ID);
                $my_post = $my_post["es"];

                $fecha_entrada = new DateTime();
                $fecha_salida = new DateTime();
                $one_year_ago = new DateInterval( "P1Y" );
                $fecha_salida->add($one_year_ago);
                $fecha_saldia_formato = $fecha_salida->format('Y-m-d');
                $fecha_entrada_formato = $fecha_entrada->format('Y-m-d');
                $language = "es";

                $sql = " SELECT a.id , a.text_title , ap.name,ap.fecha,ap.type,ap.amount , ap.season, ap.id as id_descuento
FROM avantio_accomodations as a
JOIN avantio_pricemodifiers as ap on ap.id = a.avantio_pricemodifiers
where a.id = $my_post and language = 'es' and ap.id <> 1625122 ";

                $datos = $my_wpdb->get_results($sql);

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
                            if ($vector_discounts[$i]["amount"] < $vector_discounts[$j]["amount"] && ($vector_discounts[$i]["fecha_entrada"] == $vector_discounts[$j]["fecha_entrada"] && $vector_discounts[$i]["fecha_salida"] == $vector_discounts[$j]["fecha_salida"])) {
                                $vector_discounts[$i]["amount"] = $vector_discounts[$j]["amount"];
                                unset($vector_discounts[$j]);
                                $vector_discounts = array_values($vector_discounts);
                                $isOrdered = false;
                            }
                        }
                    }
                }
                

                print '<div class="featured_div">'.esc_html__( 'featured','wprentals').'</div>';
                if(count($vector_discounts) == 1){
                    $descuento = ($datos[0]->amount) ? intval($datos[0]->amount) . " %" :  "" ;
                    print '<div class="property_status_wrapper"><div class="property_status status_nuevo">'.$descuento.'</div></div>';
                }
            }

            //echo wpestate_return_property_status($post->ID);
            ?>

            <div class="title-container">

                <?php
                if($wpestate_listing_type==1){
                    $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );

                    if($price_per_guest_from_one==1){
                        $price          =   floatval( get_post_meta($post->ID, 'extra_price_per_guest', true) );
                    }else{
                        $price          =   floatval( get_post_meta($post->ID, 'property_price', true) );
                    }
                    ?>

                    <div class="price_unit">
                        <?php
                            wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0);
                            if($price!=0){
                              echo '<span class="pernight"> '.wpestate_show_labels('per_night2',$rental_type,$booking_type).'</span>';
                            }
                        ?>
                    </div>

                    <?php
                }
                ?>

                <?php
                    if(wpestate_has_some_review($post->ID)!==0){
                        print wpestate_display_property_rating( $post->ID );
                    }else{
                        print '<div class=rating_placeholder></div>';
                    }
                ?>

                <?php echo wprentals_card_owner_image($post->ID); ?>


                <div class="category_name">
                    <?php   include(locate_template('templates/property_card_templates/property_card_title.php'));   ?>

                    <div class="category_tagline map_icon">
                        <?php
                        if ($property_area != '') {
                            print trim($property_area).', ';
                        }
                        print trim($property_city);?>
                    </div>

                    <div class="category_tagline actions_icon">
                        <?php print wp_kses_post($property_categ);?>
                        <?php //print wp_kses_post($property_categ.' / '.$property_action);?>
                    </div>
                </div>

                <div class="property_unit_action">
                    <span class="icon-fav <?php print esc_attr($favorite_class); ?>" data-original-title="<?php print esc_attr($fav_mes); ?>" data-postid="<?php print intval($post->ID); ?>"><i class="fas fa-heart"></i></span>
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
                        $fecha_entrada = ($discount["fecha_entrada"]) ? $discount["fecha_entrada"] : "" ;
                        $fecha_salida = ($discount["fecha_salida"]) ? $discount["fecha_salida"] : "" ;
                        $descuento = ($discount["amount"]) ? intval($discount["amount"]) . " %" :  "" ;
                        ?>
                        <div class="category_name">
                            <?php if(count($vector_discounts) > 1){ ?>
                                <?php print '<span class="pernight" style="background:#00518f;font-size:14px;padding:3px;top:5px;position:relative;">'.$titulo_descuento. ': ' . $fecha_entrada . ' '. $titulo_hasta_descuento . ' ' .  $fecha_salida . '</span>' ?>
                            <?php }else{ ?>
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
