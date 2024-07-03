<?php
global $wpestate_curent_fav;
$wpestate_currency              =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency        =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $wpestate_row_number_col;
global $type;
$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';
$col_class          =   'col-md-6';
$col_org            =   4;
$booking_type       =   wprentals_return_booking_type($post->ID);
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type');
if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.esc_attr($wpestate_row_number_col).' shortcode-col';
}

$link           =   esc_url(get_permalink());
$preview        =   array();
$preview[0]     =   '';

$actual_language = pll_current_language();

?>

<div class="listing_wrapper" data-org="12" data-listid="<?php print intval($post->ID);?>" >
    <div class="property_listing" data-link="<?php print esc_attr($link);?>">
        <?php
        if ( has_post_thumbnail() ):

            $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_listings');
            $extra= array(
                'data-original' =>  $preview[0],
                'class'         =>  'lazyload img-responsive',
            );

            $thumb_prop         =   get_the_post_thumbnail($post->ID, 'wpestate_property_listings',$extra);
            $thumb_id           =   get_post_thumbnail_id($post->ID);
            $thumb_prop_url     =   wp_get_attachment_image_src($thumb_id,'wpestate_property_featured');
            $featured           =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');

            $title = get_the_title();
            $title = mb_substr( html_entity_decode($title), 0, 40);
            if(strlen($title)>40){
                $title.= '...';
              }
            ?>

            <div class="listing-unit-img-wrapper_color">
              <div class="listing-hover-gradient"></div>
              <div class="listing-unit-img-wrapper" style="background-image:url('<?php echo esc_url($thumb_prop_url[0]);?>')"></div>
            </div>

            <?php
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


                $sql = " SELECT a.id , a.text_title , ap.name,ap.fecha, ap.type,ap.amount , ap.season , ap.id as id_descuento
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

            echo wpestate_return_property_status($post->ID);


            $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );

            if($price_per_guest_from_one==1){
                $price          =   floatval( get_post_meta($post->ID, 'extra_price_per_guest', true) );
            }else{
                $price          =   floatval( get_post_meta($post->ID, 'property_price', true) );
            }
            ?>
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

            <div class="category_name">
                <div class="price_unit">
                  <?php
                  wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0);
                  if($price!=0){
                    print '<span class="pernight"> '.wpestate_show_labels('per_night2',$rental_type,$booking_type).'</span>';
                  }
                  ?>
                </div>

                <?php
                if(wpestate_has_some_review($post->ID)!==0){
                    print wpestate_display_property_rating( $post->ID );
                }

                print '<a class="featured_listing_title" href="'.esc_url($link).'">'.esc_html($title).'</a>';

                print '<div class="category_tagline">';
                  if ($property_area != '') {
                      print trim($property_area).', ';
                  }
                  print trim($property_city);
                print '</div>';?>

          </div>
        <?php
        endif;
        ?>
    </div>
</div>
