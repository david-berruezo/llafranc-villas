<?php
# global variables

# load all features by language
$amenities_avantio_interior = array();
$amenities_avantio_exterior = array();


$languages = pll_languages_list();

if (isset($languages)){
    foreach($languages as $lang){
        $amenities_avantio_interior[$lang] = array();
        $amenities_avantio_exteior[$lang] = array();
    }// end foreach
}


# features vector names
$amenities_avantio_interior_textos = array(
    "alarma",
    "apta para discapacitados",
    "ascensor",
    "cafetera",
    "caja de seguridad",
    "chimenea",
    "congelador",
    "dvd",
    "exprimidor",
    "gimnasio",
    //"grupos de personas",
    "hervidor electrico",
    "horno",
    "jacuzzi",
    "lavadora",
    "lavavajillas",
    "microondas",
    "minibar",
    "nevera",
    "paddel",
    "plancha",
    "radio",
    "sauna",
    "secador de pelo",
    "secadora",
    "squash",
    "tennis",
    "tostadora",
    "tv",
    "utensilios de cocina",
    "vajilla",
    "zona para niños",
);



$amenities_avantio_exterior_textos = array(
    "balcon",
    "barbacoa",
    "jardin",
    "muebles de jardin",
    "paddel",
    "parcela vallada",
    "piscina",
    "squash",
    "tennis",
    "terraza",
);

# get terms by language es
$terms_default_language = get_terms(array(
    'taxonomy' => 'property_features',
    'hide_empty' => false,
    'lang' => "es"
));


foreach($terms_default_language as $tdl){
    $find_it = false;
    $my_term = array(
        $tdl->term_id => $tdl->name
    );
    $terms_languages = pll_get_term_translations($tdl->term_id);
    # check interior vector with term name and fill amenities_avantio_interior
    if(in_array($tdl->name,$amenities_avantio_interior_textos)){
            $amenities_avantio_interior["es"][$tdl->term_id] = $tdl->name;
            foreach($terms_languages as $key_lang => $tl_id){
                if ($key_lang != "es"){
                    $tl_name = get_term_by("ID" , $tl_id, "property_features" , "OBJECT");
                    $amenities_avantio_interior[$key_lang][$tl_id] = $tl_name->name;
                }
            }
            $find_it = true;
    }// end if
    # check exterior vector with term name and fill amenities_avantio_exterior
    if(in_array($tdl->name,$amenities_avantio_exterior_textos)){
        $amenities_avantio_exterior["es"][$tdl->term_id] = $tdl->name;
        foreach($terms_languages as $key_lang => $tl_id){
            if ($key_lang != "es"){
                $tl_name = get_term_by("ID" , $tl_id, "property_features"  , "OBJECT");
                $amenities_avantio_exterior[$key_lang][$tl_id] = $tl_name->name;
            }
        }
        $find_it = true;
    }// end if
    # if not found fill amenities_avantio_interior
    if(!$find_it && $tdl->name != "grupos de personas"){
        $amenities_avantio_interior["es"][$tdl->term_id] = $tdl->name;
        foreach($terms_languages as $key_lang => $tl_name){
            if ($key_lang != "es"){
                $tl_name = get_term_by("ID" , $tl_id, "property_features" , "OBJECT");
                $amenities_avantio_interior[$key_lang][$tl_id] = $tl_name->name;
            }
        }
    }

}// end foreach


if (!function_exists('wpestate_property_yelp_wrapper')):
function wpestate_property_yelp_wrapper($post_id)
{
    $return_string          =   '';
    $yelp_client_id         =   trim(wprentals_get_option('wp_estate_yelp_client_id', ''));
    $yelp_client_secret     =   trim(wprentals_get_option('wp_estate_yelp_client_secret', ''));

    if ($yelp_client_secret!=='' && $yelp_client_id!=='') {
        $return_string.='<div class="panel-wrapper yelp_wrapper">
                <a class="panel-title" id="yelp_details" data-toggle="collapse" data-parent="#yelp_details" href="#collapseFive"><span class="panel-title-arrow"></span>'.esc_html__('What\'s Nearby', 'wprentals').'</a>
                <div id="collapseFive" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">';
        $return_string.= wpestate_yelp_details($post_id);
        $return_string.='
                    </div>
                </div>

            </div>';
    }

    return $return_string;
}
endif;





// nueva funcion entorno y distancia
if (!function_exists('wpestate_property_entorno_y_distancia')):
    function wpestate_property_entorno_y_distancia($post_id)
    {

        $return_string='<div class="panel-wrapper" id="listing_entorno_y_distancia">
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFive"> <span class="panel-title-arrow"></span>';

        $return_string.=esc_html__('Property Entorno', 'wprentals');
        //$return_string.=esc_html__('Property Caracteristicas Adicionales', 'wprentals');
        $return_string.='</a>
            <div id="collapseFive" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border" itemprop="entorno_y_distancia" >';
        $return_string.=  estate_listing_entorno_y_distancia($post_id);
        $return_string.='
                </div>
            </div>
        </div>';

        return $return_string;
    }
endif;



if (!function_exists('wpestate_property_price')):
function wpestate_property_price($post_id, $wpestate_property_price_text)
{
    $return_string='<div class="panel-wrapper" id="listing_price">
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseOne"> <span class="panel-title-arrow"></span>';
    if ($wpestate_property_price_text!='') {
        $return_string.= esc_html($wpestate_property_price_text);
    } else {
        $return_string.= esc_html__('Property Price', 'wprentals');
    }
    $return_string.='</a>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border" itemprop="priceSpecification" >';
    $return_string.=  estate_listing_price($post_id);
    $return_string.=  wpestate_show_custom_details($post_id);
    $return_string.=  wpestate_show_custom_details_mobile($post_id);
    $return_string.='
                </div>
            </div>
        </div>';

    return $return_string;
}
endif;


if (!function_exists('wpestate_property_caracteristicas_adicionales_wrapper')):
    function wpestate_property_caracteristicas_adicionales_wrapper($post_id)
    {
        $return_string='
        <div class="panel-wrapper">
            <!-- property address   -->
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseSix"><span class="panel-title-arrow"></span>';
        $return_string.=esc_html__('Property Caracteristicas Adicionales', 'wprentals');
        $return_string.='
            </a>
            <div id="collapseSix" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                   '.estate_listing_caracteristicas_adicionales($post_id).'
                </div>
            </div>
        </div>';

        return $return_string;
    }
endif;



if (!function_exists('wpestate_property_address_wrapper')):
function wpestate_property_address_wrapper($post_id, $wpestate_property_adr_text)
{
    $return_string='
        <div class="panel-wrapper">
            <!-- property address   -->
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>';

    if ($wpestate_property_adr_text!='') {
        $return_string.=esc_html($wpestate_property_adr_text);
    } else {
        $return_string.=esc_html__('Property Address', 'wprentals');
    }

    $return_string.='
            </a>

            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    '.estate_listing_address($post_id).'
                </div>

            </div>
        </div>';

    return $return_string;
}
endif;



if (!function_exists('wpestate_property_details_wrapper')):
function wpestate_property_details_wrapper($post_id, $wpestate_property_details_text)
{
    $return_string='
    <!-- property details   -->
        <div class="panel-wrapper">';

    if ($wpestate_property_details_text=='') {
        $return_string.='<a class="panel-title" id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html__('Property Details', 'wprentals').'  </a>';
    } else {
        $return_string.='<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html($wpestate_property_details_text).'</a>';
    }

    $return_string.='
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">';
    $return_string.= estate_listing_details($post_id);
    $return_string.='
                </div>
            </div>
        </div>';

    return $return_string;
}
endif;


if (!function_exists('wpestate_features_and_ammenities_wrapper')):
function wpestate_features_and_ammenities_wrapper($post_id, $wpestate_property_features_text)
{


    $return_string='<div class="panel-wrapper features_wrapper">';

    $terms = get_terms(array(
         'taxonomy' => 'property_features',
         'hide_empty' => false,
    ));


    if (count($terms)!=0 && !count($terms)!=1) {
        if ($wpestate_property_features_text =='') {
            $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__('Amenities and Features', 'wprentals').'</a>';
        } else {
            $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $wpestate_property_features_text.'</a>';
        }
        $return_string.='
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        '.estate_listing_features($post_id).'
                    </div>
                </div>';
    } // end if are features and ammenties
    $return_string.='</div>';

    return $return_string;
}
endif;



if (!function_exists('wpestate_features_and_ammenities_wrapper_interior')):
    function wpestate_features_and_ammenities_wrapper_interior($post_id, $wpestate_property_features_text)
    {


        $return_string='<div class="panel-wrapper features_wrapper">';

        $terms = get_terms(array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
        ));


        if (count($terms)!=0 && !count($terms)!=1) {
            if ($wpestate_property_features_text =='') {
                $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__('Amenities and  Interir Features', 'wprentals').'</a>';
            } else {
                $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $wpestate_property_features_text.'</a>';
            }
            $return_string.='
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        '.estate_listing_features_interior($post_id).'
                    </div>
                </div>';
        } // end if are features and ammenties
        $return_string.='</div>';

        return $return_string;
    }
endif;


if (!function_exists('wpestate_features_and_ammenities_wrapper_exterior')):
    function wpestate_features_and_ammenities_wrapper_exterior($post_id, $wpestate_property_features_text)
    {

        $return_string='<div class="panel-wrapper features_wrapper">';

        $terms = get_terms(array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
        ));

        $terms_es = get_terms(array(
            'taxonomy' => 'property_features',
            'hide_empty' => false,
            'lang' => "es"
        ));


        if (count($terms)!=0 && !count($terms)!=1) {
            if ($wpestate_property_features_text =='') {
                $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__('Amenities and Exterior Features', 'wprentals').'</a>';
            } else {
                $return_string.= '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $wpestate_property_features_text.'</a>';
            }
            $return_string.='
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        '.estate_listing_features_exterior($post_id).'
                    </div>
                </div>';
        } // end if are features and ammenties
        $return_string.='</div>';

        return $return_string;
    }
endif;



if (!function_exists('wpestate_listing_terms_wrapper')):
function wpestate_listing_terms_wrapper($post_id, $wp_estate_terms_text)
{
    /*
    $do_we_show         =   trim(wprentals_get_option('wp_estate_show_terms_conditions', ''));
    if ($do_we_show=='no') {
        return;
    }

    $test = trim(esc_html(get_post_meta($post_id, 'smoking_allowed', true)));
    if ($test=='') {// terms were not saved until now - nothing to display
        return;
    }
    */

    $return_string='<!-- property termd   -->
         <div class="panel-wrapper">

            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_terns" href="#collapseTerms">  <span class="panel-title-arrow"></span>';
    if ($wp_estate_terms_text!='') {
        $return_string.= esc_html($wp_estate_terms_text);
    } else {
        $return_string.= esc_html__('Terms and Conditions', 'wprentals');
    }
    $return_string.='
            </a>

            <div id="collapseTerms" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">';
    $return_string.= wpestate_listing_terms($post_id);
    $return_string.='
                </div>

            </div>
        </div>';

    return $return_string;
}
endif;


if (!function_exists('wpestate_sleeping_situation_wrapper')):
function wpestate_sleeping_situation_wrapper($post_id, $wp_estate_sleeping_text)
{
    $do_we_show         =   trim(wprentals_get_option('wp_estate_show_sleeping_arrangements', ''));

    if ($do_we_show=='no') {
        return;
    }

    $property_bedrooms  =   intval(get_post_meta($post_id, 'property_bedrooms', true));
    $return_string      =   '';
    $beds_options=get_post_meta($post_id, 'property_bedrooms_details', true);

    if (!is_array($beds_options)) {
        return '';
    }

    if ($property_bedrooms!=0) {
        $return_string.='
            <div class="panel-wrapper">
                <!-- property address   -->
                <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_sleepibg" href="#collapseSleep">  <span class="panel-title-arrow"></span>';

        if ($wp_estate_sleeping_text!='') {
            $return_string.= esc_html($wp_estate_sleeping_text);
        } else {
            $return_string.= esc_html__('Sleeping Situation', 'wprentals');
        }

        $return_string.='</a>

                <div id="collapseSleep" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">';
        $return_string.=wpestate_sleeping_situation($post_id);
        $return_string.='
                    </div>

                </div>
            </div>';
    }

    return $return_string;
}
endif;










if (!function_exists('wprentals_card_owner_image')):
function wprentals_card_owner_image($post_id)
{
    $author_id          =   wpsestate_get_author($post_id);
    $agent_id           =   get_user_meta($author_id, 'user_agent_id', true);
    $thumb_id_agent     =   get_post_thumbnail_id($agent_id);
    $preview_agent      =   wp_get_attachment_image_src($thumb_id_agent, 'wpestate_user_thumb');
    $preview_agent_img  =   '';
    if( isset($preview_agent[0]) ){
        $preview_agent_img  =   $preview_agent[0];
    }
    $agent_link         =   esc_url(get_permalink($agent_id));

    if ($preview_agent_img   ==  '') {
        $preview_agent_img    =   get_stylesheet_directory_uri().'/img/default_user_small.png';
    }


    if ($thumb_id_agent=='') {
        $preview_agent_img   = get_the_author_meta('custom_picture', $agent_id);
        return '<div class="owner_thumb" style="background-image: url('. esc_url($preview_agent_img).')"></div>';
    } else {
        //return '<a href="'.esc_url($agent_link).'" class="owner_thumb" style="background-image: url('. esc_url($preview_agent_img).')"></a>';
    }
}
endif;






if (!function_exists('wprentals_icon_bar_designv')):
function wprentals_icon_bar_design()
{
    global $post;
    $custom_listing_fields = wprentals_get_option('wp_estate_property_page_header', '');

    if (is_array($custom_listing_fields)) {
        foreach ($custom_listing_fields as   $key=>$field) {
            if ($field[2]=='property_category' || $field[2]=='property_action_category' ||  $field[2]=='property_city' ||  $field[2]=='property_area') {
                $value  =   get_the_term_list($post->ID, $field[2], '', ', ', '');
            } else {
                $slug       =   wpestate_limit45(sanitize_title($field[2]));
                $slug       =   sanitize_key($slug);
                $value      =   esc_html(get_post_meta($post->ID, $slug, true));
            }


            if ($value!='') {
                print '<span class="no_link_details custom_prop_header">';

                if ($field[0]!='') {
                    print '<strong>'.esc_html(stripslashes($field[0])).'</strong> ';
                } elseif ($field[3]!='') {
                    print '<img src="'.esc_url($field[3]).'" alt="'.esc_html__('icon', 'wprentals').'">';
                } elseif ($field[1]!='') {
                    print '<i class="'.esc_attr($field[1]).'"></i>';
                }
                print '<span>';
                $measure_sys        =   esc_html(wprentals_get_option('wp_estate_measure_sys', ''));
                if ($field[2]=='property_size') {
                    print number_format($value) . ' '.$measure_sys.'<sup>2</sup>';
                } else {
                    print trim($value);
                }

                print '</span>';

                print '</span>';
            }
        }
    }
}
endif;




if (!function_exists('wprentals_icon_bar_classic')):
function wprentals_icon_bar_classic($property_action, $property_category, $rental_type, $guests, $bedrooms, $bathrooms)
{
    if ($property_action!='') {
        /*
        print '<div class="actions_icon category_details_wrapper_icon">'.trim($property_action).' <span class="property_header_separator">|</span></div>
        <div class="schema_div_noshow"  itemprop="actionStatus">'.strip_tags($property_action).'</div>';
        */
    }

    if ($property_category!='') {
        print'<div class="types_icon category_details_wrapper_icon">'. trim($property_category).'<span class="property_header_separator">|</span></div>
        <div class="schema_div_noshow"  itemprop="additionalType">'. strip_tags($property_category).'</div>';
    }


    if ($rental_type==0) {
        if ($guests==0) {
            //nothing
        } elseif ($guests==1) {
            print '<div class="no_link_details category_details_wrapper_icon guest_header_icon">'.$guests.' '. esc_html__('Guest', 'wprentals').'</div>';
        } else {
            print '<div class="no_link_details category_details_wrapper_icon guest_header_icon">'.$guests.' '. esc_html__('Guests', 'wprentals').'</div>';
        }

        print '<span class="property_header_separator">|</span>';

        if ($bedrooms==1) {
            print  '<span class="no_link_details category_details_wrapper_icon bedrooms_header_icon">'.$bedrooms.' '.esc_html__('Bedroom', 'wprentals').'</span>';
        } else {
            print  '<span class="no_link_details category_details_wrapper_icon bedrooms_header_icon">'.$bedrooms.' '.esc_html__('Bedrooms', 'wprentals').'</span>';
        }
        print '<span class="property_header_separator">|</span>';

    }
}
endif;





function wp_get_attachment($attachment_id)
{
    $attachment = get_post($attachment_id);
    return array(
        'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => esc_url(get_permalink($attachment->ID)),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}
///////////////////////////////////////////////////////////////////////////////////////////
// List features and ammenities
///////////////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_build_terms_array')):
    function wpestate_build_terms_array()
    {
        $parsed_features = wpestate_request_transient_cache('wpestate_get_features_array');
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $parsed_features=false;
        }
        if ($parsed_features===false) {
            $parsed_features=array();
            $terms = get_terms(array(
                    'taxonomy' => 'property_features',
                    'hide_empty' => false,
                    'parent'=> 0

                ));


            foreach ($terms as $key=>$term) {
                $temp_array=array();
                $child_terms = get_terms(array(
                        'taxonomy' => 'property_features',
                        'hide_empty' => false,
                        'parent'=> $term->term_id
                    ));

                $children=array();
                if (is_array($child_terms)) {
                    foreach ($child_terms as $child_key=>$child_term) {
                        $children[]=$child_term->name;
                    }
                }

                $temp_array['name']=$term->name;
                $temp_array['childs']=$children;

                $parsed_features[]=$temp_array;
            }
            if ( !defined( 'ICL_LANGUAGE_CODE' ) ) {
                wpestate_set_transient_cache('wpestate_get_features_array', $parsed_features, 60*60*4);
            }
                
        }

        return $parsed_features;
    }
endif;



if (!function_exists('estate_listing_features_exterior')):
    function estate_listing_features_exterior($post_id)
    {

        # global variables
        global $amenities_avantio_exterior;


        $actual_language = pll_current_language();

        $single_return_string   =   '';
        $multi_return_string    =   '';
        $show_no_features       =   esc_html(wprentals_get_option('wp_estate_show_no_features', ''));
        $property_features      =   get_the_terms($post_id, 'property_features');
        //p_($property_features);

        $parsed_features        =   wpestate_build_terms_array();

        if (is_array($parsed_features)) {
            foreach ($parsed_features as $key => $item) {
                if (count($item['childs']) > 0) {
                    $multi_return_string_part=  '<div class="listing_detail col-md-12 feature_block_'.$item['name'].' ">';
                    $multi_return_string_part.=  '<div class="feature_chapter_name col-md-12">'.$item['name'].'</div>';
                    $multi_return_string_part_check = '';
                    if (is_array($item['childs'])) {
                        if(in_array($item["name"],$amenities_avantio_exterior[$actual_language])){
                            foreach ($item['childs'] as $key_ch => $child) {
                                $temp   = wpestate_display_feature($show_no_features, $child, $post_id, $property_features);
                                $multi_return_string_part .=$temp;
                                $multi_return_string_part_check.=$temp;
                            }
                        }

                    }
                    $multi_return_string_part.=  '</div>';

                    if ($multi_return_string_part_check!='') {
                        $multi_return_string.=$multi_return_string_part;
                    }
                } else {

                    //p_($item);
                    if(in_array($item["name"],$amenities_avantio_exterior[$actual_language])){
                        $valor = "";
                        switch($item['name']){
                            case "piscina":
                                $valor = get_post_meta($post_id, 'property_tipo_piscina');
                            break;
                            case "pool":
                                $valor = get_post_meta($post_id, 'property_tipo_piscina');
                                $valor = str_replace("privada","private",$valor);
                                $valor = str_replace("comunitaria","communal",$valor);
                                $valor = str_replace("pool private","private pool",$valor);
                            break;
                            case "bassin":
                                $valor = get_post_meta($post_id, 'property_tipo_piscina');
                                $valor = str_replace("privada","privée",$valor);
                                $valor = str_replace("comunitaria","communautaire",$valor);
                                $item['name'] = "piscine";
                            break;
                        }// end switch

                        if (!$valor)
                            $single_return_string .= wpestate_display_feature($show_no_features, $item['name'], $post_id, $property_features);

                        if ($valor)
                            $single_return_string .=  '<div class="listing_detail col-md-6"><i class="fas fa-check checkon"></i>'. $item['name'] . " " .$valor[0].'</div>';
                    }

                }
            }
        }

        if (trim($single_return_string)!='') {
            $multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name col-md-12">'.esc_html__('Exterior Features ', 'wprentals').'</div>'.$single_return_string.'</div>';
            //$multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "></div>'.$single_return_string.'</div>';
        }

        return $multi_return_string;
    }
endif; // end   estate_listing_features



if (!function_exists('estate_listing_features_interior')):
    function estate_listing_features_interior($post_id)
    {

        # global variables
        global $amenities_avantio_interior;

        $actual_language = pll_current_language();

        $single_return_string   =   '';
        $multi_return_string    =   '';
        $show_no_features       =   esc_html(wprentals_get_option('wp_estate_show_no_features', ''));
        $property_features      =   get_the_terms($post_id, 'property_features');
        $parsed_features        =   wpestate_build_terms_array();

        //print_r($parsed_features);

        if (is_array($parsed_features)) {
            foreach ($parsed_features as $key => $item) {
                if (count($item['childs']) > 0) {
                    # check if is interior
                    if(in_array($item["name"],$amenities_avantio_interior[$actual_language])){
                        $multi_return_string_part=  '<div class="listing_detail col-md-12 feature_block_'.$item['name'].' ">';
                        $multi_return_string_part.=  '<div class="feature_chapter_name col-md-12">'.$item['name'].'</div>';

                        $multi_return_string_part_check='';
                        if (is_array($item['childs'])) {
                            foreach ($item['childs'] as $key_ch=>$child) {
                                $temp   = wpestate_display_feature($show_no_features, $child, $post_id, $property_features);
                                $multi_return_string_part .=$temp;
                                $multi_return_string_part_check.=$temp;
                            }
                        }
                        $multi_return_string_part.=  '</div>';

                        if ($multi_return_string_part_check!='') {
                            $multi_return_string.=$multi_return_string_part;
                        }
                    }
                } else {
                    # check if is interior
                    if(in_array($item["name"],$amenities_avantio_interior[$actual_language])){
                        $single_return_string .= wpestate_display_feature($show_no_features, $item['name'], $post_id, $property_features);
                    }
                }
            }
        }

        if (trim($single_return_string)!='') {
            $multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name col-md-12">'.esc_html__('Interior Features ', 'wprentals').'</div>'.$single_return_string.'</div>';
            //$multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "></div>'.$single_return_string.'</div>';
        }

        return $multi_return_string;
    }
endif; // end   estate_listing_features




if (!function_exists('estate_listing_features')):
    function estate_listing_features($post_id)
    {
        $single_return_string   =   '';
        $multi_return_string    =   '';
        $show_no_features       =   esc_html(wprentals_get_option('wp_estate_show_no_features', ''));
        $property_features      =   get_the_terms($post_id, 'property_features');
        $parsed_features        =   wpestate_build_terms_array();

        if (is_array($parsed_features)) {
            foreach ($parsed_features as $key => $item) {
                if (count($item['childs']) > 0) {
                    $multi_return_string_part=  '<div class="listing_detail col-md-12 feature_block_'.$item['name'].' ">';
                    $multi_return_string_part.=  '<div class="feature_chapter_name col-md-12">'.$item['name'].'</div>';

                    $multi_return_string_part_check='';
                    if (is_array($item['childs'])) {
                        foreach ($item['childs'] as $key_ch=>$child) {
                            $temp   = wpestate_display_feature($show_no_features, $child, $post_id, $property_features);
                            $multi_return_string_part .=$temp;
                            $multi_return_string_part_check.=$temp;
                        }
                    }
                    $multi_return_string_part.=  '</div>';

                    if ($multi_return_string_part_check!='') {
                        $multi_return_string.=$multi_return_string_part;
                    }
                } else {
                    $single_return_string .= wpestate_display_feature($show_no_features, $item['name'], $post_id, $property_features);
                }
            }
        }

        if (trim($single_return_string)!='') {
            $multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name col-md-12">'.esc_html__('Other Features ', 'wprentals').'</div>'.$single_return_string.'</div>';
            //$multi_return_string= $multi_return_string.'<div class="listing_detail col-md-12 feature_block_others "></div>'.$single_return_string.'</div>';
        }

        return $multi_return_string;
    }
endif; // end   estate_listing_features



if (!function_exists('wpestate_display_feature')):
    function wpestate_display_feature($show_no_features, $term_name, $post_id, $property_features)
    {
        $return_string  =   '';
        $term_object    =   get_term_by('name', $term_name, 'property_features');
        $term_meta      =   get_option("taxonomy_$term_object->term_id");
        $term_icon      =   '';

        if ($term_meta!='') {
            $term_icon =  '<img class="property_features_svg_icon" src="'.$term_meta['category_featured_image'].'" >';
            $term_icon_wp = wp_remote_get($term_meta['category_featured_image']);

            if (is_wp_error($term_icon_wp)) {
                $term_icon='';
            } else {
                $term_icon = wp_remote_retrieve_body($term_icon_wp);
            }
        }

        if ($show_no_features!='no') {
            if (is_array($property_features) && array_search($term_name, array_column($property_features, 'name')) !== false) {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-check checkon"></i>';
                }

                $return_string .= '<div class="listing_detail col-md-6">'.$term_icon. trim($term_name) . '</div>';
            } else {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-times"></i>';
                }
                $return_string  .=  '<div class="listing_detail not_present col-md-6">'.$term_icon. trim($term_name). '</div>';
            }
        } else {
            if (is_array($property_features) &&  array_search($term_name, array_column($property_features, 'name')) !== false) {
                if ($term_icon=='') {
                    $term_icon='<i class="fas fa-check checkon"></i>';
                }
                $return_string .=  '<div class="listing_detail col-md-6">'.$term_icon. trim($term_name) .'</div>';
            }
        }

        return $return_string;
    }
endif;





///////////////////////////////////////////////////////////////////////////////////////////
// dashboard price
///////////////////////////////////////////////////////////////////////////////////////////

if (!function_exists('estate_listing_price')):
    function estate_listing_price($post_id)
    {
        $return_string                  =   '';
        $property_price                 =   floatval(get_post_meta($post_id, 'property_price', true));
        $property_price_before_label    =   esc_html(get_post_meta($post_id, 'property_price_before_label', true));
        $property_price_after_label     =   esc_html(get_post_meta($post_id, 'property_price_after_label', true));
        $property_price_per_week        =   floatval(get_post_meta($post_id, 'property_price_per_week', true));
        $property_price_per_month       =   floatval(get_post_meta($post_id, 'property_price_per_month', true));
        $cleaning_fee                   =   floatval(get_post_meta($post_id, 'cleaning_fee', true));
        $city_fee                       =   floatval(get_post_meta($post_id, 'city_fee', true));
        $cleaning_fee_per_day           =   floatval(get_post_meta($post_id, 'cleaning_fee_per_day', true));
        $city_fee_percent               =   floatval(get_post_meta($post_id, 'city_fee_percent', true));
        $city_fee_per_day               =   floatval(get_post_meta($post_id, 'city_fee_per_day', true));
        $price_per_guest_from_one       =   floatval(get_post_meta($post_id, 'price_per_guest_from_one', true));
        $overload_guest                 =   floatval(get_post_meta($post_id, 'overload_guest', true));
        $checkin_change_over            =   floatval(get_post_meta($post_id, 'checkin_change_over', true));
        $checkin_checkout_change_over   =   floatval(get_post_meta($post_id, 'checkin_checkout_change_over', true));
        $min_days_booking               =   floatval(get_post_meta($post_id, 'min_days_booking', true));
        $extra_price_per_guest          =   floatval(get_post_meta($post_id, 'extra_price_per_guest', true));
        $price_per_weekeend             =   floatval(get_post_meta($post_id, 'price_per_weekeend', true));
        $security_deposit               =   floatval(get_post_meta($post_id, 'security_deposit', true));
        $early_bird_percent             =   floatval(get_post_meta($post_id, 'early_bird_percent', true));
        $early_bird_days                =   floatval(get_post_meta($post_id, 'early_bird_days', true));
        $rental_type                    =   esc_html(wprentals_get_option('wp_estate_item_rental_type'));
        $booking_type                   =   wprentals_return_booking_type($post_id);
        $max_extra_guest_no             =   floatval(get_post_meta($post_id, 'max_extra_guest_no', true));
        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );

        $wpestate_currency              = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        $th_separator   =   wprentals_get_option('wp_estate_prices_th_separator', '');
        $custom_fields  =   wprentals_get_option('wpestate_currency', '');

        $property_price_show                 =  wpestate_show_price_booking($property_price, $wpestate_currency, $wpestate_where_currency, 1);
        $property_price_per_week_show        =  wpestate_show_price_booking($property_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
        $property_price_per_month_show       =  wpestate_show_price_booking($property_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
        $cleaning_fee_show                   =  wpestate_show_price_booking($cleaning_fee, $wpestate_currency, $wpestate_where_currency, 1);
        $city_fee_show                       =  wpestate_show_price_booking($city_fee, $wpestate_currency, $wpestate_where_currency, 1);

        $price_per_weekeend_show             =  wpestate_show_price_booking($price_per_weekeend, $wpestate_currency, $wpestate_where_currency, 1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest, $wpestate_currency, $wpestate_where_currency, 1);
        $extra_price_per_guest_show          =  wpestate_show_price_booking($extra_price_per_guest, $wpestate_currency, $wpestate_where_currency, 1);
        $security_deposit_show               =  wpestate_show_price_booking($security_deposit, $wpestate_currency, $wpestate_where_currency, 1);

        $setup_weekend_status= esc_html(wprentals_get_option('wp_estate_setup_weekend', ''));
        $weekedn = array(
            0 => __("Sunday and Saturday", "wprentals"),
            1 => __("Friday and Saturday", "wprentals"),
            2 => __("Friday, Saturday and Sunday", "wprentals")
        );





        if ($price_per_guest_from_one!=1) {
            if ($property_price != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night col-md-6"><span class="item_head">'.wpestate_show_labels('price_label', $rental_type, $booking_type).':</span> ' .$property_price_before_label.' '. $property_price_show.' '.$property_price_after_label. '</div>';
            }

            if ($property_price_per_week != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_7d col-md-6"><span class="item_head">'.wpestate_show_labels('price_week_label', $rental_type, $booking_type).':</span> ' . $property_price_per_week_show . '</div>';
            }

            if ($property_price_per_month != 0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_30d col-md-6"><span class="item_head">'.wpestate_show_labels('price_month_label', $rental_type, $booking_type).':</span> ' . $property_price_per_month_show . '</div>';
            }

            if ($price_per_weekeend!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_weekend col-md-6"><span class="item_head">'.esc_html__('Price per weekend ', 'wprentals').'('.$weekedn[$setup_weekend_status].') '.':</span> ' . $price_per_weekeend_show . '</div>';
            }

            if ($extra_price_per_guest!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest col-md-6"><span class="item_head">'.esc_html__('Extra Price per guest', 'wprentals').':</span> ' . $extra_price_per_guest_show . '</div>';
            }
        } else {
            if ($extra_price_per_guest!=0) {
                $return_string.='<div class="listing_detail list_detail_prop_price_per_night_extra_guest_price col-md-6"><span class="item_head">'.esc_html__('Price per guest', 'wprentals').':</span> ' . $extra_price_per_guest_show . '</div>';
            }
        }

        $options_array=array(
            0   =>  esc_html__('Single Fee', 'wprentals'),
            1   =>  ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)),
            2   =>  esc_html__('Per Guest', 'wprentals'),
            3   =>  ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)).' '.esc_html__('per Guest', 'wprentals')
        );

        if ($cleaning_fee != 0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_cleaning_fee col-md-6"><span class="item_head">'.esc_html__('Cleaning Fee', 'wprentals').':</span> ' . $cleaning_fee_show ;
            $return_string .= ' '.$options_array[$cleaning_fee_per_day];

            $return_string.='</div>';
        }

        if ($city_fee != 0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_tax_fee col-md-6"><span class="item_head">'.esc_html__('City Tax Fee', 'wprentals').':</span> ' ;
            if ($city_fee_percent==0) {
                $return_string .= $city_fee_show.' '.$options_array[$city_fee_per_day];
            } else {
                $return_string .= $city_fee.'%'.' '.__('of price per night', 'wprentals');
            }
            $return_string.='</div>';
        }

        if ($min_days_booking!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_min_nights col-md-6"><span class="item_head">'.esc_html__('Minimum no of', 'wprentals').' '.wpestate_show_labels('nights', $rental_type, $booking_type) .':</span> ' . $min_days_booking . '</div>';
        }

        if ($overload_guest!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_price_overload_guest col-md-6"><span class="item_head">'.esc_html__('Allow more guests than the capacity: ', 'wprentals').' </span>'.esc_html__('yes', 'wprentals').'</div>';
        }



        if ($checkin_change_over!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts col-md-6"><span class="item_head">'.esc_html__('Booking starts only on', 'wprentals').':</span> ' . $week_days[$checkin_change_over ]. '</div>';
        }

        if ($security_deposit!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts col-md-6"><span class="item_head">'.esc_html__('Security deposit', 'wprentals').':</span> ' . $security_deposit_show. '</div>';
        }

        if ($checkin_checkout_change_over!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Booking starts/ends only on', 'wprentals').':</span> ' .$week_days[$checkin_checkout_change_over] . '</div>';
        }


        if ($early_bird_percent!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Early Bird Discount', 'wprentals').':</span> '.$early_bird_percent.'% '.esc_html__('discount', 'wprentals').' '.esc_html__('for bookings made', 'wprentals').' '.$early_bird_days.' '.esc_html__('nights in advance', 'wprentals').'</div>';
        }
        
        if ($max_extra_guest_no!=0) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-6"><span class="item_head">'.esc_html__('Maximum extra guests allowed', 'wprentals').':</span> ' .sprintf( _n( '%s Guest', '%s Guests', $max_extra_guest_no, 'wprentals' ), number_format_i18n( $max_extra_guest_no ) ).'</div>';
        }
        
        

        $extra_pay_options          =      (get_post_meta($post_id, 'extra_pay_options', true));

        if (is_array($extra_pay_options) && !empty($extra_pay_options)) {
            $return_string.='<div class="listing_detail list_detail_prop_book_starts_end col-md-12"><span class="item_head">'.esc_html__('Extra options', 'wprentals').':</span></div>';
            foreach ($extra_pay_options as $key=>$wpestate_options) {
                $return_string.='<div class="extra_pay_option"> ';
                $extra_option_price_show                       =  wpestate_show_price_booking($wpestate_options[1], $wpestate_currency, $wpestate_where_currency, 1);
                $return_string.= ''.$wpestate_options[0].': '. $extra_option_price_show.' '.$options_array[$wpestate_options[2]];

                $return_string.= '</div>';
            }
        }


        return $return_string;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
// custom details
///////////////////////////////////////////////////////////////////////////////////////////
if (!function_exists('wpestate_show_custom_details')):
    function wpestate_show_custom_details($edit_id, $is_dash=0)
    {
        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );
        $price_per_guest_from_one       =   floatval(get_post_meta($edit_id, 'price_per_guest_from_one', true));

        $wpestate_currency              = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        $mega                   =   wpml_mega_details_adjust($edit_id);
        $price_array            =   wpml_custom_price_adjust($edit_id);
        $rental_type            =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
        $booking_type           =   wprentals_return_booking_type($edit_id);
        $permited_fields        = wprentals_get_option('wp_estate_submission_page_fields', '');

        $table_fields= array('property_price',
            'property_price_per_week',
            'property_price_per_month',
            'min_days_booking',
            'extra_price_per_guest',
            'price_per_weekeend',
            'checkin_change_over',
            'checkin_checkout_change_over'
            );

        $fiels_no=0;

        foreach ($table_fields as $item) {
            if (in_array($item, $permited_fields)) {
                $fiels_no++;
            }
        }
        $size='';

        if ($fiels_no!=0) {
            $length=floatval(84/$fiels_no);
            if ($is_dash==1) {
                $length=floatval(68/$fiels_no);
            }
            $size= 'style="width:'.$length.'%;"';
        }


        if (is_array($mega)) {
            foreach ($mega as $key=>$Item) {
                $now_unix=time();
                if (($key+(24*60*60)) < $now_unix) {
                    unset($mega[$key]);
                }
            }
        }


        if (empty($mega) && empty($price_array)) {
            return;
        }

        $to_print_trigger   =   0;
        if (is_array($mega)) {
            // sort arry by key
            ksort($mega);


            $flag=0;
            $flag_price         ='';
            $flag_min_days      ='';
            $flag_guest         ='';
            $flag_price_week    ='';
            $flag_change_over   ='';
            $flag_checkout_over ='';

            $to_print           =   '';
            $to_print_trigger   =   0;

            $to_print.= '<div class="custom_day_wrapper';
            if ($is_dash==1) {
                $to_print.= ' custom_day_wrapper_dash ';
            }
            $to_print.= '">';

            $to_print.= '
            <div class="custom_day custom_day_header">
                <div class="custom_day_from_to">'.esc_html__('Period', 'wprentals').'</div>';



            if ($price_per_guest_from_one!=1) {
                if (in_array('property_price', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_night" '.$size.'>'.wpestate_show_labels('price_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('property_price_per_week', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_week" '.$size.'>'.wpestate_show_labels('price_week_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('property_price_per_month', $permited_fields)) {
                    $to_print.='<div class="custom_price_per_day custom_price_per_day_regular_month" '.$size.'>'.wpestate_show_labels('price_month_label', $rental_type, $booking_type).'</div>';
                }

                if (in_array('min_days_booking', $permited_fields)) {
                    $to_print.='<div class="custom_day_min_days" '.$size.'>'.wpestate_show_labels('min_unit', $rental_type, $booking_type).'</div>';
                }

                if (in_array('extra_price_per_guest', $permited_fields)) {
                    $to_print.='<div class="custom_day_name_price_per_guest" '.$size.'>'.esc_html__('Extra price per guest', 'wprentals').'</div>';
                }
                if (in_array('price_per_weekeend', $permited_fields)) {
                    $to_print.='<div class="custom_day_name_price_per_weekedn" '.$size.'>'.esc_html__('Price in weekends', 'wprentals').'</div>';
                }
            } else {
                $to_print.= '<div class="custom_day_name_price_per_guest" '.$size.'>'.esc_html__('Price per guest', 'wprentals').'</div>';
            }

            if (in_array('checkin_change_over', $permited_fields)) {
                $to_print.='<div class="custom_day_name_change_over" '.$size.'>'.esc_html__('Booking starts only on', 'wprentals').'</div>';
            }

            if (in_array('checkin_checkout_change_over', $permited_fields)) {
                $to_print.='<div class="custom_day_name_checkout_change_over" '.$size.'>'.esc_html__('Booking starts/ends only on', 'wprentals').'</div>';
            }


            if ($is_dash==1) {
                $to_print.= '<div class="delete delete_custom_period"></div>';
            }

            $to_print.='</div>';


            foreach ($mega as $day=>$data_day) {
                $checker            =   0;
                $from_date          =   new DateTime("@".$day);
                $to_date            =   new DateTime("@".$day);
                $tomorrrow_date     =   new DateTime("@".$day);

                $tomorrrow_date->modify('tomorrow');
                $tomorrrow_date     =   $tomorrrow_date->getTimestamp();

                //we set the flags
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ($flag==0) {
                    $flag=1;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days                  =   $data_day['period_min_days_booking'];
                    $flag_guest                     =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week                =   $data_day['period_price_per_weekeend'];
                    $flag_change_over               =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over             =   $data_day['period_checkin_checkout_change_over'];

                    if (isset($data_day['period_price_per_month'])) {
                        $flag_period_price_per_month    =   $data_day['period_price_per_month'];
                    }

                    if (isset($data_day['period_price_per_week'])) {
                        $flag_period_price_per_week     =   $data_day['period_price_per_week'];
                    }

                    $from_date_unix     =   $from_date->getTimestamp();
                    $to_print.=' <div class="custom_day">';
                    $to_print.=' <div class="custom_day_from_to"> '.esc_html__('From', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d'));
                    $to_print_trigger=1;
                }




                //we check period chane
                //////////////////////////////////////////////////////////////////////////////////////////////
                if (!array_key_exists($tomorrrow_date, $mega)) { // non consecutive days
                    $checker = 1;
                } else {
                    if (isset($price_array[$tomorrrow_date]) && $flag_price!=$price_array[$tomorrrow_date]) {
                        // IF PRICE DIFFRES FROM DAY TO DAY
                        $checker = 1;
                    }
                    if ($mega[$tomorrrow_date]['period_min_days_booking']                   !=  $flag_min_days ||
                        $mega[$tomorrrow_date]['period_extra_price_per_guest']              !=  $flag_guest ||
                        $mega[$tomorrrow_date]['period_price_per_weekeend']                 !=  $flag_price_week ||
                        (isset($mega[$tomorrrow_date]['period_price_per_month']) && $mega[$tomorrrow_date]['period_price_per_month']                    !=  $flag_period_price_per_month) ||
                        (isset($mega[$tomorrrow_date]['period_price_per_week']) && $mega[$tomorrrow_date]['period_price_per_week']                     !=  $flag_period_price_per_week) ||
                        $mega[$tomorrrow_date]['period_checkin_change_over']                !=  $flag_change_over ||
                        $mega[$tomorrrow_date]['period_checkin_checkout_change_over']       !=  $flag_checkout_over) {
                        // IF SOME DATA DIFFRES FROM DAY TO DAY

                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    // we have consecutive days, data stays the sa,e- do not print
                } else {
                    // no consecutive days - we CONSIDER print


                    if ($flag==1) {
                        $to_date_unix     =   $from_date->getTimestamp();
                        $to_print.= ' '.esc_html__('To', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d')).'</div>';

                        if ($price_per_guest_from_one!=1) {
                            if (in_array('property_price', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_price_per_day" '.$size.'>';
                                if (isset($price_array[$day])) {
                                    $to_print.=   wpestate_show_price_booking($price_array[$day], $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.='</div>';
                            }

                            if (in_array('property_price_per_week', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_day_name_price_per_week custom_price_per_day" '.$size.'>';
                                if (isset($flag_period_price_per_week) && $flag_period_price_per_week!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_period_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }



                            if (in_array('property_price_per_month', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_month custom_price_per_day" '.$size.'>';
                                if (isset($flag_period_price_per_month) && $flag_period_price_per_month!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_period_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }




                            if (in_array('min_days_booking', $permited_fields)) {
                                $to_print.='
                                    <div class="custom_day_min_days" '.$size.'>';
                                if ($flag_min_days!=0) {
                                    $to_print.= esc_html($flag_min_days);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }


                            if (in_array('extra_price_per_guest', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_guest" '.$size.'>';
                                if ($flag_guest!=0) {
                                    $to_print.= wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }

                            if (in_array('price_per_weekeend', $permited_fields)) {
                                $to_print.='<div class="custom_day_name_price_per_weekedn" '.$size.'>';
                                if ($flag_price_week!=0) {
                                    $to_print.=   wpestate_show_price_booking($flag_price_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    $to_print.= '-';
                                }
                                $to_print.= '</div>';
                            }
                        } else {
                            $to_print.= '<div class="custom_day_name_price_per_guest">'.wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1).'</div>';
                        }




                        if (in_array('checkin_change_over', $permited_fields)) {
                            $to_print.='
                                <div class="custom_day_name_change_over" '.$size.'>';
                            if (intval($flag_change_over) !=0) {
                                $to_print.= esc_html($week_days[ $flag_change_over ]);
                            } else {
                                $to_print.= esc_html__('All', 'wprentals');
                            }

                            $to_print.= '</div>';
                        }




                        if (in_array('checkin_checkout_change_over', $permited_fields)) {
                            $to_print.='<div class="custom_day_name_checkout_change_over" '.$size.'>';
                            if (intval($flag_checkout_over) !=0) {
                                $to_print.= esc_html($week_days[ $flag_checkout_over ]);
                            } else {
                                $to_print.=esc_html__('All', 'wprentals');
                            }

                            $to_print.= '</div>';
                        }


                        if ($is_dash==1) {
                            $to_print.= '<div class="delete delete_custom_period" data-editid="'.intval($edit_id).'"   data-fromdate="'.esc_attr($from_date_unix).'" data-todate="'.esc_attr($to_date_unix).'"><a href="#"> '.esc_html__('delete period', 'wprentals').'</a></div>';
                        }

                        $to_print.= '</div>';
                    }
                    $flag=0;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days      =   $data_day['period_min_days_booking'];
                    $flag_guest         =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week    =   $data_day['period_price_per_weekeend'];
                    $flag_change_over   =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over =   $data_day['period_checkin_change_over'];


                    $ajax_nonce = wp_create_nonce("wprentals_delete_custom_period_nonce");
                    $to_print.='<input type="hidden" id="wprentals_delete_custom_period_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                }
            }
            $to_print.= '</div>';
        }
        if ($to_print_trigger==1) {
            print trim($to_print);
        }
    }
endif;

if (!function_exists('wpestate_show_custom_details_mobile')):
    function wpestate_show_custom_details_mobile($edit_id, $is_dash=0)
    {
        $week_days=array(
            '0'=>esc_html__('All', 'wprentals'),
            '1'=>esc_html__('Monday', 'wprentals'),
            '2'=>esc_html__('Tuesday', 'wprentals'),
            '3'=>esc_html__('Wednesday', 'wprentals'),
            '4'=>esc_html__('Thursday', 'wprentals'),
            '5'=>esc_html__('Friday', 'wprentals'),
            '6'=>esc_html__('Saturday', 'wprentals'),
            '7'=>esc_html__('Sunday', 'wprentals')

            );
        $price_per_guest_from_one       =   floatval(get_post_meta($edit_id, 'price_per_guest_from_one', true));

        $wpestate_currency              = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency        = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));

        $mega           =   wpml_mega_details_adjust($edit_id);
        $price_array    =   wpml_custom_price_adjust($edit_id);
        $rental_type            =   esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
        $booking_type           =   wprentals_return_booking_type($edit_id);
        $permited_fields        =    wprentals_get_option('wp_estate_submission_page_fields', '');
        if (is_array($mega)) {
            foreach ($mega as $key=>$Item) {
                $now_unix=time();
                if (($key+(24*60*60)) < $now_unix) {
                    unset($mega[$key]);
                }
            }
        }


        if (empty($mega) && empty($price_array)) {
            return;
        }


        if (is_array($mega)) {
            // sort arry by key
            ksort($mega);


            $flag=0;
            $flag_price         ='';
            $flag_min_days      ='';
            $flag_guest         ='';
            $flag_price_week    ='';
            $flag_change_over   ='';
            $flag_checkout_over ='';

            print '<div class="custom_day_wrapper_mobile">';

            foreach ($mega as $day=>$data_day) {
                $checker            =   0;
                $from_date          =   new DateTime("@".$day);
                $to_date            =   new DateTime("@".$day);
                $tomorrrow_date     =   new DateTime("@".$day);

                $tomorrrow_date->modify('tomorrow');
                $tomorrrow_date     =   $tomorrrow_date->getTimestamp();

                //we set the flags
                //////////////////////////////////////////////////////////////////////////////////////////////
                if ($flag==0) {
                    $flag=1;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days                  =   $data_day['period_min_days_booking'];
                    $flag_guest                     =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week                =   $data_day['period_price_per_weekeend'];
                    $flag_change_over               =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over             =   $data_day['period_checkin_checkout_change_over'];

                    if (isset($data_day['period_price_per_month'])) {
                        $flag_period_price_per_month    =   $data_day['period_price_per_month'];
                    }

                    if (isset($data_day['period_price_per_week'])) {
                        $flag_period_price_per_week     =   $data_day['period_price_per_week'];
                    }

                    $from_date_unix     =   $from_date->getTimestamp();
                    print' <div class="custom_day"> ';
                    print' <div class="custom_day_from_to"><div class="custom_price_label">'.esc_html__('Period', 'wprentals').'</div> '.esc_html__('From', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d'));
                }




                //we check period chane
                //////////////////////////////////////////////////////////////////////////////////////////////
                if (!array_key_exists($tomorrrow_date, $mega)) { // non consecutive days
                    $checker = 1;
                } else {
                    if (isset($price_array[$tomorrrow_date]) && $flag_price!=$price_array[$tomorrrow_date]) {
                        // IF PRICE DIFFRES FROM DAY TO DAY
                        $checker = 1;
                    }
                    if ($mega[$tomorrrow_date]['period_min_days_booking']                   !=  $flag_min_days ||
                        $mega[$tomorrrow_date]['period_extra_price_per_guest']              !=  $flag_guest ||
                        $mega[$tomorrrow_date]['period_price_per_weekeend']                 !=  $flag_price_week ||
                        (isset($mega[$tomorrrow_date]['period_price_per_month']) && $mega[$tomorrrow_date]['period_price_per_month']                    !=  $flag_period_price_per_month) ||
                        (isset($mega[$tomorrrow_date]['period_price_per_week']) && $mega[$tomorrrow_date]['period_price_per_week']                     !=  $flag_period_price_per_week) ||
                        $mega[$tomorrrow_date]['period_checkin_change_over']                !=  $flag_change_over ||
                        $mega[$tomorrrow_date]['period_checkin_checkout_change_over']       !=  $flag_checkout_over) {
                        // IF SOME DATA DIFFRES FROM DAY TO DAY

                        $checker = 1;
                    }
                }

                if ($checker == 0) {
                    // we have consecutive days, data stays the sa,e- do not print
                } else {
                    // no consecutive days - we CONSIDER print


                    if ($flag==1) {
                        $to_date_unix     =   $from_date->getTimestamp();
                        print ' '.esc_html__('To', 'wprentals').' '. wpestate_convert_dateformat_reverse($from_date->format('Y-m-d')).'</div>';

                        if ($price_per_guest_from_one!=1) {
                            if (in_array('property_price', $permited_fields)) {
                                print'
                                    <div class="custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_label', $rental_type).'</div>';
                                if (isset($price_array[$day])) {
                                    echo   wpestate_show_price_booking($price_array[$day], $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print'</div>';
                            }

                            if (in_array('property_price_per_week', $permited_fields)) {
                                print'
                                    <div class="custom_day_name_price_per_week custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_week_label', $rental_type).'</div>';
                                if (isset($flag_period_price_per_week) && $flag_period_price_per_week!=0) {
                                    echo   wpestate_show_price_booking($flag_period_price_per_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }

                                print '</div>';
                            }

                            if (in_array('property_price_per_month', $permited_fields)) {
                                print'<div class="custom_day_name_price_per_month custom_price_per_day">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('price_month_label', $rental_type).'</div>';
                                if (isset($flag_period_price_per_month) && $flag_period_price_per_month!=0) {
                                    echo   wpestate_show_price_booking($flag_period_price_per_month, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }


                            if (in_array('min_days_booking', $permited_fields)) {
                                print'
                                    <div class="custom_day_min_days">';
                                print '<div class="custom_price_label">'.wpestate_show_labels('min_unit', $rental_type, $booking_type).'</div>';
                                if ($flag_min_days!=0) {
                                    print esC_html($flag_min_days);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }

                            if (in_array('extra_price_per_guest', $permited_fields)) {
                                print'<div class="custom_day_name_price_per_guest">';
                                print '<div class="custom_price_label">'.esc_html__('Extra price per guest', 'wprentals').'</div>';
                                if ($flag_guest!=0) {
                                    echo wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }

                            if (in_array('price_per_weekeend', $permited_fields)) {
                                print '<div class="custom_day_name_price_per_weekedn">';
                                print '<div class="custom_price_label">'.esc_html__('Price in weekends', 'wprentals').'</div>';
                                if ($flag_price_week!=0) {
                                    echo   wpestate_show_price_booking($flag_price_week, $wpestate_currency, $wpestate_where_currency, 1);
                                } else {
                                    echo '-';
                                }
                                print '</div>';
                            }
                        } else {
                            print '<div class="custom_day_name_price_per_guest">';
                            print '<div class="custom_price_label">'.wpestate_show_labels('price_label', $rental_type, $booking_type).'</div>';
                            print wpestate_show_price_booking($flag_guest, $wpestate_currency, $wpestate_where_currency, 1).'</div>';
                        }

                        if (in_array('checkin_change_over', $permited_fields)) {
                            print'
                                <div class="custom_day_name_change_over">';
                            print '<div class="custom_price_label">'.esc_html__('Booking starts only on', 'wprentals').'</div>';
                            if (intval($flag_change_over) !=0) {
                                print esc_html($week_days[ $flag_change_over ]);
                            } else {
                                esc_html_e('All', 'wprentals');
                            }
                            print '</div>';
                        }

                        if (in_array('checkin_checkout_change_over', $permited_fields)) {
                            print'<div class="custom_day_name_checkout_change_over">';
                            print '<div class="custom_price_label">'.esc_html__('Booking starts/ends only on', 'wprentals').'</div>';
                            if (intval($flag_checkout_over) !=0) {
                                print esc_html($week_days[ $flag_checkout_over ]);
                            } else {
                                esc_html_e('All', 'wprentals');
                            }

                            print '</div>';
                        }

                        if ($is_dash==1) {
                            print '<div class="delete delete_custom_period" data-editid="'.esc_attr($edit_id).'"   data-fromdate="'.esc_attr($from_date_unix).'" data-todate="'.esc_attr($to_date_unix).'"><a href="#"> '.esc_html__('delete period', 'wprentals').'</a></div>';
                        }

                        print '</div>';
                    }
                    $flag=0;
                    if (isset($price_array[$day])) {
                        $flag_price         =   $price_array[$day];
                    }
                    $flag_min_days      =   $data_day['period_min_days_booking'];
                    $flag_guest         =   $data_day['period_extra_price_per_guest'];
                    $flag_price_week    =   $data_day['period_price_per_weekeend'];
                    $flag_change_over   =   $data_day['period_checkin_change_over'];
                    $flag_checkout_over =   $data_day['period_checkin_change_over'];

                    $ajax_nonce = wp_create_nonce("wprentals_delete_custom_period_nonce");
                    print'<input type="hidden" id="wprentals_delete_custom_period_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                }
            }
            print '</div>';
        }
    }
endif;





if (!function_exists('wpestate_sleeping_situation')):
    function wpestate_sleeping_situation($post_id)
    {
        $return_string='';
        $return_string_second='';
        $beds_options=get_post_meta($post_id, 'property_bedrooms_details', true);
        if ($beds_options=='') {
            $beds_options=array();
        }


        $bed_types      =   esc_html(wprentals_get_option('wp_estate_bed_list', ''));
        $bed_types_array=   explode(',', $bed_types);
        $no_bedroms     =   intval(get_post_meta($post_id, 'property_bedrooms', true));
        $step           =   1;

        $return_string.='<div class="wpestate_front_bedrooms_wrapper">';
        while ($step<=$no_bedroms) {
            $return_string.='<div class="wpestate_front_bedrooms">';
            $return_string_second='';
            $images='';
            $return_string_second.='<strong>'.esc_html__('Bedroom', 'wprentals').' '.($step).'</strong>';


            foreach ($bed_types_array as $key_bed_types=>$label) {
                if (isset($beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1]) &&  $beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1] >0) {
                    $return_string_second.='<div class="">'. $beds_options[sanitize_key(wpestate_convert_cyrilic($label))][$step-1].' '.$label.'</div>';
                }
            }

            $return_string.=$images.$return_string_second.'</div>';
            $step++;
        }
        $return_string.='</div>';

        return $return_string;
    }
endif;







if (!function_exists('wpestate_listing_terms')):
    function wpestate_listing_terms($post_id)
    {
        $cancellation_policy    =   esc_html(get_post_meta($post_id, 'cancellation_policy', true));
        $other_rules            =   esc_html(get_post_meta($post_id, 'other_rules', true));
        $return_string          =   '';

        $items = array();

        /*
        $items = array(
            'smoking_allowed'   =>  esc_html__('Smoking Allowed', 'wprentals'),
            'pets_allowed'      =>  esc_html__('Pets Allowed', 'wprentals'),
            'party_allowed'     =>  esc_html__('Party Allowed', 'wprentals'),
            'children_allowed'  =>  esc_html__('Children Allowed', 'wprentals'),

        );



        foreach ($items as $key=>$name) {
            $value =    esc_html(get_post_meta($post_id, $key, true));
            if ($value!='') {
                $dismiss_class="";
                $icon = ' <i class="fas fa-check checkon"></i>';
                if ($value=='no') {
                    $dismiss_class=" not_present  ";
                    $icon = ' <i class="fas fa-times"></i> ';
                }
                $return_string.='<div class="listing_detail  col-md-6 '.$key.' '.$dismiss_class.'">'.$icon. $name.'</div>';
            }
        }
        */

        if (trim($cancellation_policy)!='') {
            //$return_string.='<div class="listing_detail  col-md-12 cancelation_policy"><label>'.esc_html__('Cancellation Policy', 'wprentals').'</label>'. $cancellation_policy.'</div>';
        }

        if (trim($other_rules)!='') {
            //$return_string.='<div class="listing_detail  col-md-12 other_rules"><label>'.esc_html__('Other Rules', 'wprentals').'</label>'. $other_rules.'</div>';
        }

        $actual_language = pll_current_language();
        switch($actual_language){

            case "es":
                # paginas urls
                $condiciones_pagina = get_page_link(35455);
                $contacto_pagina = get_page_link(35410);
                break;
            case "en":
                # paginas urls
                $condiciones_pagina = get_page_link(370486);
                $contacto_pagina = get_page_link(370464);
                break;
            case "ca":
                # paginas urls
                $condiciones_pagina = get_page_link(370487);
                $contacto_pagina = get_page_link(370465);
            break;
            case "fr":
                # paginas urls
                $condiciones_pagina = get_page_link(370488);
                $contacto_pagina = get_page_link(370480);
            break;

        }// end switch

        $return_string.='<div class="listing_detail  col-md-12 other_rules"><a href="'.$condiciones_pagina.'" target="_blank">'.esc_html__('Condiciones', 'wprentals').'</a></div>';
        $return_string.='<div class="listing_detail  col-md-12 other_rules"><a href="'.$contacto_pagina.'" target="_blank">'.esc_html__('Contactar', 'wprentals').'</a></div>';

        return $return_string;
    }
endif;




if (!function_exists('estate_listing_entorno_y_distancia')):
    function estate_listing_entorno_y_distancia($post_id)
    {
        //echo "el id que llega es" .$post_id. "<br>";

        $return_string='';

        //get_post_meta($post_id, 'property_county', true);

        //echo "el post id es: ".$post_id."<br>";
        $loc_where = get_post_meta($post_id, 'loc_where');
        $loc_where = $loc_where[0];
        $loc_howto = get_post_meta($post_id, 'loc_howto');
        $loc_howto = $loc_howto[0];
        $loc_desc1 = get_post_meta($post_id, 'loc_desc1');
        $loc_desc1 = $loc_desc1[0];
        $loc_desc2 = get_post_meta($post_id, 'loc_desc2');
        $loc_desc2 = $loc_desc2[0];

        if($loc_where)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Where', 'wprentals').':</span> ' .$loc_where. '</div>';

        if($loc_howto)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('How', 'wprentals').':</span> ' .$loc_howto. '</div>';

        if($loc_desc1)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Desc1', 'wprentals').':</span> ' .$loc_desc1. '</div>';

        if($loc_desc2)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Desc2', 'wprentals').':</span> ' .$loc_desc2. '</div>';

        $beach_name = get_post_meta($post_id, 'beach_name');
        $beach_name = $beach_name[0];
        $beach_dist = get_post_meta($post_id, 'beach_dist');
        $beach_dist = $beach_dist[0];
        $beach_unit = get_post_meta($post_id, 'beach_unit');
        $beach_unit = $beach_unit[0];

        if ($beach_dist < 1){
            $beach_dist = $beach_dist * 1000;
            $beach_unit = "M";
        }

        if ($beach_name != "" && $beach_name != "Distancia Playa")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Beach name', 'wprentals').':</span> ' .$beach_name. '</div>';

        if($beach_dist != 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Beach distance', 'wprentals').':</span> ' .$beach_dist. " ".$beach_unit. '</div>';

        $golf_name = get_post_meta($post_id, 'golf_name');
        $golf_name = $golf_name[0];

        $golf_dist = get_post_meta($post_id, 'golf_dist');
        $golf_dist = $golf_dist[0];

        $golf_unit = get_post_meta($post_id, 'golf_unit');
        $golf_unit = $golf_unit[0];

        if ($golf_dist < 1){
            $golf_dist = $golf_dist * 1000;
            $golf_unit = "M";
        }

        if ($golf_name != "" && $golf_name != "Distancia Golf")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Golf name', 'wprentals').':</span> ' .$golf_name. '</div>';

        if ($golf_dist != 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Golf distance', 'wprentals').':</span> ' .$golf_dist.$golf_unit. '</div>';

        $city_name = get_post_meta($post_id, 'city_name');
        $city_name = $city_name[0];

        $city_dist = get_post_meta($post_id, 'city_dist');
        $city_dist = $city_dist[0];

        $city_unit = get_post_meta($post_id, 'city_unit');
        $city_unit = $city_unit[0];

        if ($city_dist < 1){
            $city_dist = $city_dist * 1000;
            $city_unit = "M";
        }

        if ($city_name != "" && $city_name != "Distancia Ciudad")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('City name', 'wprentals').':</span> ' .$city_name. '</div>';

        if($city_dist != 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('City distance', 'wprentals').':</span> ' .$city_dist.$city_unit. '</div>';

        $super_name = get_post_meta($post_id, 'super_name');
        $super_name = $super_name[0];

        $super_dist = get_post_meta($post_id, 'super_dist');
        $super_dist = $super_dist[0];

        $super_unit = get_post_meta($post_id, 'super_unit');
        $super_unit = $super_unit[0];

        if ($super_dist < 1){
            $super_dist = $super_dist * 1000;
            $super_unit = "M";
        }

        if ($super_name != "" && $super_name != "Distancia Supermercado")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Super name', 'wprentals').':</span> ' .$super_name. '</div>';

        if ( $super_dist > 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Super distance', 'wprentals').':</span> ' .$super_dist. " ".$super_unit. '</div>';

        $airport_name = get_post_meta($post_id, 'airport_name');
        $airport_name = $airport_name[0];

        $airport_dist = get_post_meta($post_id, 'airport_dist');
        $airport_dist = $airport_dist[0];

        $airport_unit = get_post_meta($post_id, 'airport_unit');
        $airport_unit = $airport_unit[0];

        if ($airport_dist < 1){
            $airport_dist = $airport_dist * 1000;
            $airport_unit = "M";
        }

        if($airport_name != "" && $airport_name != "Distancia Areopuerto")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Airport name', 'wprentals').':</span> ' .$airport_name. '</div>';

        if($airport_dist > 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Airport distance', 'wprentals').':</span> ' .$airport_dist. " ".$airport_unit. '</div>';

        $train_name = get_post_meta($post_id, 'train_name');
        $train_name = $train_name[0];

        $train_dist = get_post_meta($post_id, 'train_dist');
        $train_dist = $train_dist[0];

        $train_unit = get_post_meta($post_id, 'train_unit');
        $train_unit = $train_unit[0];

        if ($train_dist < 1){
            $train_dist = $train_dist * 1000;
            $train_unit = "M";
        }

        if($train_name != "" && $train_name != "Distancia tren")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Train name', 'wprentals').':</span> ' .$train_name. '</div>';

        if($train_dist > 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Train distance', 'wprentals').':</span> ' .$train_dist. " ".$train_unit. '</div>';


        $bus_name = get_post_meta($post_id, 'bus_name');
        $bus_name = $bus_name[0];

        $bus_dist = get_post_meta($post_id, 'bus_dist');
        $bus_dist = $bus_dist[0];

        $bus_unit = get_post_meta($post_id, 'bus_unit');
        $bus_unit = $bus_unit[0];

        if ($bus_dist < 1){
            $bus_dist = $bus_dist * 1000;
            $bus_unit = "M";
        }

        if($bus_name != "" && $bus_name != "Distancia bus")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Bus name', 'wprentals').':</span> ' .$bus_name. '</div>';

        if($bus_dist > 0)
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Bus distance', 'wprentals').':</span> ' .$bus_dist. " ".$bus_unit. '</div>';

        $view_to_beach = get_post_meta($post_id, 'view_to_beach', true);
        $view_to_beach = $view_to_beach[0];
        $view_to_beach = ($view_to_beach)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_swimming_pool = get_post_meta($post_id, 'view_to_swimming_pool', true);
        $view_to_swimming_pool = $view_to_swimming_pool[0];
        $view_to_swimming_pool = ($view_to_swimming_pool)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_golf = get_post_meta($post_id, 'view_to_golf', true);
        $view_to_golf =  $view_to_golf[0];
        $view_to_golf = ($view_to_golf)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_garden = get_post_meta($post_id, 'view_to_garden', true);
        $view_to_garden =  $view_to_garden[0];
        $view_to_garden = ($view_to_garden)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_river = get_post_meta($post_id, 'view_to_river', true);
        $view_to_river =  $view_to_river[0];
        $view_to_river = ($view_to_river)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_mountain = get_post_meta($post_id, 'view_to_mountain', true);
        $view_to_mountain =  $view_to_mountain[0];
        $view_to_mountain = ($view_to_mountain)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        $view_to_lake = get_post_meta($post_id, 'view_to_lake',true);
        $view_to_lake =  $view_to_lake[0];
        $view_to_lake = ($view_to_lake)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        if($view_to_beach == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to_beach', 'wprentals').'</span></div>';

        // $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to_beach', 'wprentals').':</span> ' .$view_to_beach. '</div>';

        if($view_to_swimming_pool == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to swimmng pool', 'wprentals').'</span></div>';

        if($view_to_golf == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to golf', 'wprentals').'</span></div>';

        if($view_to_garden == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to garden', 'wprentals').'</span></div>';

        if($view_to_river == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to river', 'wprentals').'</span></div>';

        if($view_to_mountain == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to mountain', 'wprentals').'</span></div>';

        if($view_to_lake == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('view to lake', 'wprentals').'</span></div>';

        $first_line_beach = get_post_meta($post_id, 'first_line_beach', true);
        $first_line_beach =  $first_line_beach[0];
        $first_line_beach = ($first_line_beach)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');


        $first_line_golf = get_post_meta($post_id, 'first_line_golf', true);
        $first_line_golf =  $first_line_golf[0];
        $first_line_golf = ($first_line_golf)  ? esc_html__('entorno si', 'wprentals')  : esc_html__('entorno no', 'wprentals');

        if($first_line_beach == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('first line beach', 'wprentals').'</span></div>';

        if($first_line_golf == "Si")
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('first line golf', 'wprentals').'</span></div>';

        return $return_string;

    }// end function
endif;


if (!function_exists('estate_listing_caracteristicas_adicionales')):
    function estate_listing_caracteristicas_adicionales($post_id)
    {
        # database
        $my_wpdb = new wpdb("tiendapi_user","Perretin771","tiendapi_inmobiliaria",'localhost');

        # language
        $language = pll_current_language();

        # get translation of post
        $my_post = pll_get_post_translations($post_id);
        $my_post = $my_post["es"];

        $sql = "SELECT ds.* 
FROM `avantio_accomodations_extras` as aae 
JOIN dynamic_services as ds ON ds.id = aae.dynamic_services
where avantio_accomodations = $my_post and ds.language = '".$language."' and aae.dynamic_services IN(1,2,3) ";
        $services = $my_wpdb->get_results($sql);
        //p_($services);

        # grupos de jovenes
        $sql = " select checkbox_grups from avantio_accomodations where id = $my_post and language = 'es' ";
        $grupos = $my_wpdb->get_results($sql);
        
        switch($grupos[0]->checkbox_grups){
            case 0:
                switch($language){
                    case "es":$grupos_message = "No se aceptan grupos de jóvenes";
                    break;
                    case "ca":$grupos_message = "No s'acceptan grups de joves";
                    break;
                    case "fr":$grupos_message = "Les groupes de jeunes ne sont pas acceptés";
                    break;
                    case "en":$grupos_message = "Groups of young people are not accepted";
                    break;
                }
            break;
            case 1:
                switch($language){
                    case "es":$grupos_message = "Se aceptan grupos de jóvenes solo bajo petición";
                        break;
                    case "ca":$grupos_message = "S'acceptan grups de joves solsament sota petició";
                        break;
                    case "fr":$grupos_message = "Les groupes de jeunes sont acceptés uniquement sur demande";
                        break;
                    case "en":$grupos_message = "Groups of young people are not accepted only on request";
                        break;
                }
            break;

        }// end switch

        # mascotas
        $sql = "SELECT ds.* 
FROM `avantio_accomodations_extras` as aae 
JOIN dynamic_services as ds ON ds.id = aae.dynamic_services
where avantio_accomodations = $my_post and ds.language = '".$language."' and aae.dynamic_services IN(9) ";
        $mascotas = $my_wpdb->get_results($sql);

        if ($mascotas){
            switch($language){
                case "es":$mascotas_message = "Se aceptan mascotas bajo petición";
                    break;
                case "ca":$mascotas_message = "S'acceptan grups de joves sota petició";
                    break;
                case "fr":$mascotas_message = "les animaux sont admis uniquement sur demande";
                    break;
                case "en":$mascotas_message = "pets are allowed only on request";
                    break;
            }
        }else{
            switch($language){
                case "es":$mascotas_message = "No se aceptan mascotas";
                    break;
                case "ca":$mascotas_message = "No s'acceptan mascotas";
                    break;
                case "fr":$mascotas_message = "les animaux ne pas sont admis";
                    break;
                case "en":$mascotas_message = "pets are not allowed";
                    break;
            }
        }

        $return_string          =   '';
        
        # only for description
        $vector_negaciones_desc = array(
           1        => "calefacción",
           2        => "aire acondicionado",
           3        => "aparcamiento",
           4        => "cama supletoria",
           5        => "piscina climatizada",
           6        => "ropa de cama",
           7        => "toallas",
           8        => "toallas",
           9        => "mascota",
           10       => "limpieza final",
           11       => "fianza",
           18       => "cuna",
           109      => "llegada fuera de horario",
           127      => "tasa turística",
           1537     => "silla bebe",
           20129    => "limpieza y desinfección",
           20131    => "desinfección con certificación",
           20190    => "desinfección",
           24131    => "no smoking"
        );

        $vector_negaciones = array(9);
        $string_negaciones = implode("','" , $vector_negaciones);

        $sql = "SELECT * from dynamic_services where id IN('".$string_negaciones."') AND language = '".$language."' ";
        $no_services = $my_wpdb->get_results($sql);
        //p_($no_services);


        foreach ($services as $service) {
            $dismiss_class = "";
            $icon = ' <i class="fas fa-check checkon"></i>';
            $return_string.='<div class="listing_detail  col-md-6'.$dismiss_class.'">'.$icon. esc_html__($service->text_title, 'wprentals').'</div>';
        }

        # grupos jovenes permitidos
        if ($grupos[0]->checkbox_grups == 1){
            $dismiss_class = "";
            $icon = ' <i class="fas fa-check checkon"></i>';
            $return_string.='<div class="listing_detail  col-md-6'.$dismiss_class.'">'.$icon. esc_html__($grupos_message , 'wprentals').'</div>';
        } // end if

        # mascotas permitidas
        if ($mascotas){
            $dismiss_class = "";
            $icon = ' <i class="fas fa-check checkon"></i>';
            $return_string.='<div class="listing_detail  col-md-6'.$dismiss_class.'">'.$icon. esc_html__($mascotas_message , 'wprentals').'</div>';
        }// end if

        $return_string.= '<br style="clear:both;"><br>';

        if(!$mascotas){
            $return_string.='<div class="listing_detail not_present col-md-6"><i class="fas fa-times"></i>'.esc_html__($mascotas_message, 'wprentals').'</div>';
        }

        /*
        foreach ($no_services as $no_service) {
            $found_service = false;
            foreach ($services as $service) {
                if ($no_service->id == $service->id)
                    $found_service = true;
            }// end foreach
            if (!$found_service)
                $return_string.='<div class="listing_detail not_present col-md-6"><i class="fas fa-times"></i>'.esc_html__($mascotas_message, 'wprentals').'</div>';
                //$return_string.='<div class="listing_detail not_present col-md-6"><i class="fas fa-times"></i>'.esc_html__($no_service->text_title, 'wprentals').'</div>';
        }// end foreach
        */

        if ($grupos[0]->checkbox_grups == 0){
            $return_string.='<div class="listing_detail not_present col-md-6"><i class="fas fa-times"></i>'.esc_html__($grupos_message, 'wprentals').'</div>';
        }

        //$return_string.= '<br style="clear:both;"><br>';

        # registro turistico
        $registro = get_post_meta($post_id, 'registro_turistico');
        $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Registro turistico', 'wprentals').':</span> ' .$registro[0]. '</div>';


        return $return_string;
    }
endif; // send   estate_listing_address



if (!function_exists('estate_listing_address')):
    function estate_listing_address($post_id)
    {
        $property_address   = esc_html(get_post_meta($post_id, 'property_address', true));
        $property_city      = get_the_term_list($post_id, 'property_city', '', ', ', '');
        $property_area      = get_the_term_list($post_id, 'property_area', '', ', ', '');
        $property_county    = esc_html(get_post_meta($post_id, 'property_county', true));
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true));
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true));
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true));
        $property_country_tr   = wpestate_return_country_list_translated(strtolower($property_country)) ;

        $property_latitude = esc_html(get_post_meta($post_id, 'property_latitude', true));
        $property_longitude = esc_html(get_post_meta($post_id, 'property_longitude', true));

        if ($property_country_tr!='') {
            $property_country=$property_country_tr;
        }

        $return_string='';

        if ($property_address != '') {
            $return_string.='<div class="listing_detail list_detail_prop_address col-md-6"><span class="item_head">'.esc_html__('Address', 'wprentals').':</span> ';
            if (wpestate_check_show_address_user_rent_property()) {
                $return_string.= $property_address;
            } else {
                $return_string.=esc_html__('Exact location information is provided after a booking is confirmed.', 'wprentals');
            }
            $return_string.='</div>';
        }
        if ($property_city != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_city col-md-6"><span class="item_head">'.esc_html__('Region', 'wprentals').':</span> ' .$property_city. '</div>';
        }
        if ($property_area != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_area col-md-6"><span class="item_head">'.esc_html__('Area', 'wprentals').':</span> ' .$property_area. '</div>';
        }
        if ($property_county != '') {
            //$return_string.= '<div class="listing_detail list_detail_prop_county col-md-6"><span class="item_head">'.esc_html__('County', 'wprentals').':</span> ' . $property_county . '</div>';
        }
        if ($property_state != '') {
            //$return_string.= '<div class="listing_detail list_detail_prop_state col-md-6"><span class="item_head">'.esc_html__('State', 'wprentals').':</span> ' . $property_state . '</div>';
        }
        if ($property_zip != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_zip col-md-6"><span class="item_head">'.esc_html__('Zip', 'wprentals').':</span> ' . $property_zip . '</div>';
        }
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_contry col-md-6"><span class="item_head">'.esc_html__('Country', 'wprentals').':</span> ' . $property_country . '</div>';
        }

        $return_string.= '<div class="listing_detail list_detail_prop_contry col-md-6"><span class="item_head">'.esc_html__('Property latitude', 'wprentals').':</span> ' . $property_latitude . '</div>';
        $return_string.= '<div class="listing_detail list_detail_prop_contry col-md-6"><span class="item_head">'.esc_html__('Property longitude', 'wprentals').':</span> ' . $property_longitude . '</div>';

        return  $return_string;
    }
endif; // end   estate_listing_address



if (!function_exists('estate_listing_address_print_topage')):
    function estate_listing_address_print_topage($post_id)
    {
        $property_address   = esc_html(get_post_meta($post_id, 'property_address', true));
        $property_city      = strip_tags(get_the_term_list($post_id, 'property_city', '', ', ', ''));
        $property_area      = strip_tags(get_the_term_list($post_id, 'property_area', '', ', ', ''));
        $property_county    = esc_html(get_post_meta($post_id, 'property_county', true));
        $property_state     = esc_html(get_post_meta($post_id, 'property_state', true));
        $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true));
        $property_country   = esc_html(get_post_meta($post_id, 'property_country', true));

        $return_string='';

        if ($property_address != '') {
            $return_string.='<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Address', 'wprentals').':</span> ' . $property_address . '</div>';
        }
        if ($property_city != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('City', 'wprentals').':</span> ' .$property_city. '</div>';
        }
        if ($property_area != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Area', 'wprentals').':</span> ' .$property_area. '</div>';
        }
        if ($property_county != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('County', 'wprentals').':</span> ' . $property_county . '</div>';
        }
        if ($property_state != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('State', 'wprentals').':</span> ' . $property_state . '</div>';
        }
        if ($property_zip != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Zip', 'wprentals').':</span> ' . $property_zip . '</div>';
        }
        if ($property_country != '') {
            $return_string.= '<div class="listing_detail col-md-4"><span class="item_head">'.esc_html__('Country', 'wprentals').':</span> ' . $property_country . '</div>';
        }

        return  $return_string;
    }
endif; // end   estate_listing_address



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////




if (!function_exists('estate_listing_details')):
    function estate_listing_details($post_id)
    {
        $wpestate_currency  =   esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $wpestate_where_currency     =   esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $measure_sys        =   esc_html(wprentals_get_option('wp_estate_measure_sys', ''));
        $property_size      =   intval(get_post_meta($post_id, 'property_size', true));

        if ($property_size  != '') {
            $property_size  = number_format($property_size) . ' '.$measure_sys.'<sup>2</sup>';
        }

        $property_lot_size = intval(get_post_meta($post_id, 'property_lot_size', true));

        if ($property_lot_size != '') {
            $property_lot_size = number_format($property_lot_size) . ' '.$measure_sys.'<sup>2</sup>';
        }

        $property_rooms     = floatval(get_post_meta($post_id, 'property_rooms', true));
        $property_bedrooms  = floatval(get_post_meta($post_id, 'property_bedrooms', true));
        $property_bathrooms = floatval(get_post_meta($post_id, 'property_bathrooms', true));
        $property_status    = wpestate_return_property_status($post_id, 'pin');

        // nuevas
        $property_parcela = intval(get_post_meta($post_id, 'property_size_parcela', true));
        $property_parcela= ($property_parcela) ? $property_parcela . " " . $measure_sys .'<sup>2</sup>' : "";

        $property_banos_banyera = intval(get_post_meta($post_id, 'property_bathrooms_banera', true));
        $property_banos_ducha = intval(get_post_meta($post_id, 'property_bathrooms_ducha', true));
        $property_banos_aseos = intval(get_post_meta($post_id, 'property_bathrooms_aseos', true));

        /*
        Tamaño propiedad    property_size
        Tamaño parcela      property_size_parcela
        Numero dormitorios  property_bedrooms
        Baño con bañera     property_bathrooms_banera
        Baño con ducha      property_bathrooms_ducha
        Numero aseos        property_aseos
        */

        $return_string='';

        $property_status = apply_filters('wpml_translate_single_string', $property_status, 'wprentals', 'property_status_'.$property_status);
        if ($property_status != '' && $property_status != 'normal') {
            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                //$return_string.= '<div class="listing_detail list_detail_prop_status col-md-6"><span class="item_head">'.esc_html__('Property Status', 'wprentals').':</span> ' .' '. $property_status . '</div>';
            } else {
                //$return_string.= '<div class="listing_detail list_detail_prop_status col-md-6"><span class="item_head">'.esc_html__('Listing Status', 'wprentals').': </span> ' . $property_status . '</div>';
            }
        }
        if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
            //$return_string.= '<div  class="listing_detail list_detail_prop_id col-md-6"><span class="item_head">'.esc_html__('Property ID', 'wprentals').': </span> ' . $post_id . '</div>';
        } else {
            //$return_string.= '<div  class="listing_detail list_detail_prop_id col-md-6"><span class="item_head">'.esc_html__('Listing ID', 'wprentals').': </span> ' . $post_id . '</div>';
        }
        if ($property_size != '') {
            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__('Property Size', 'wprentals').':</span> ' . $property_size . '</div>';
            } else {
                $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__('Listing Size', 'wprentals').':</span> ' . $property_size . '</div>';
            }
        }

        $return_string.= '<div class="listing_detail list_detail_prop_size col-md-6"><span class="item_head">'.esc_html__('Property Size parcela', 'wprentals').':</span> ' . $property_parcela . '</div>';


        if ($property_lot_size != '') {
            if (wprentals_get_option('wp_estate_item_rental_type')!=1) {
                $return_string.= '<div class="listing_detail list_detail_prop_lot_size  col-md-6"><span class="item_head">'.esc_html__('Property Lot Size', 'wprentals').':</span> ' . $property_lot_size . '</div>';
            } else {
                $return_string.= '<div class="listing_detail list_detail_prop_lot_size  col-md-6"><span class="item_head">'.esc_html__('Listing Lot Size', 'wprentals').':</span> ' . $property_lot_size . '</div>';
            }
        }

        /*
        if ($property_rooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_rooms col-md-6"><span class="item_head">'.esc_html__('Rooms', 'wprentals').':</span> ' . $property_rooms . '</div>';
        }
        */

        if ($property_bedrooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_bedrooms col-md-6"><span class="item_head">'.esc_html__('Bedrooms', 'wprentals').':</span> ' . $property_bedrooms . '</div>';
        }

        /*
        if ($property_bathrooms != '') {
            $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__('Bathrooms', 'wprentals').':</span> ' . $property_bathrooms . '</div>';
        }
        */

        //$property_parcela = intval(get_post_meta($post_id, 'property_size_parcela', true));
        //$property_banos_banyera = intval(get_post_meta($post_id, 'property_bathrooms_banera', true));
        //$property_banos_ducha = intval(get_post_meta($post_id, 'property_bathrooms_ducha', true));
        //$property_banos_aseos = intval(get_post_meta($post_id, 'property_bathrooms_aseos', true));

        $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__('Property Baños Ducha', 'wprentals').':</span> ' . $property_banos_ducha . '</div>';
        $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__('Property Baños Bañera', 'wprentals').':</span> ' . $property_banos_banyera . '</div>';
        $return_string.= '<div class="listing_detail list_detail_prop_bathrooms col-md-6"><span class="item_head">'.esc_html__('Property Aseos', 'wprentals').':</span> ' . $property_banos_aseos . '</div>';


        // Custom Fields


        $i=0;
        $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');

        if (!empty($custom_fields)) {
            while ($i< count($custom_fields)) {
                $name =   $custom_fields[$i][0];
                $label=   $custom_fields[$i][1];
                $type =   $custom_fields[$i][2];
                //    $slug =   sanitize_key ( str_replace(' ','_',$name) );
                $slug         =   wpestate_limit45(sanitize_title($name));
                $slug         =   sanitize_key($slug);

                $value=esc_html(get_post_meta($post_id, $slug, true));
                if (function_exists('icl_translate')) {
                    $label     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$label, $label) ;
                    $value     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$value, $value) ;
                }

                $label = stripslashes($label);

                if ($label!='' && $value!='') {
                    $return_string.= '<div class="listing_detail list_detail_prop_'.(strtolower(str_replace(' ', '_', $label))).' col-md-6"><span class="item_head">'.ucwords($label).':</span> ';
                    $return_string.= stripslashes($value);
                    $return_string.='</div>';
                }
                $i++;
            }
        }

        //END Custom Fields
        $i=0;
        $custom_details = get_post_meta($post_id, 'property_custom_details', true);

        if (!empty($custom_details)) {
            foreach ($custom_details as $label=>$value) {
                if (function_exists('icl_translate')) {
                    $label     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$label, $label) ;
                    $value     =   icl_translate('wprentals', 'wp_estate_property_custom_'.$value, $value) ;
                }

                $label = stripslashes($label);

                if ($value!='') {
                    $return_string.= '<div class="listing_detail list_detail_prop_'.(strtolower(str_replace(' ', '_', $label))).' col-md-6"><span class="item_head">'.ucwords($label).':</span> ';
                    $return_string.= stripslashes($value);
                    $return_string.='</div>';
                }
                $i++;
            }
        }
        //END Custom Details

        return $return_string;
    }
endif; // end   estate_listing_details
