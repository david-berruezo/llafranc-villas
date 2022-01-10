<?php
global $current_adv_filter_search_label;
global $current_adv_filter_category_label;
global $current_adv_filter_city_label;
global $current_adv_filter_area_label;
$current_adv_filter_search_label_non    =   $current_adv_filter_search_label;
$current_adv_filter_category_label_non  =   $current_adv_filter_category_label;
$current_adv_filter_city_label_non      =   $current_adv_filter_city_label;
$current_adv_filter_area_label_non      =   $current_adv_filter_area_label;
$allowed_html                           =   array();

$allowed_html_list =    array(  'li' => array(
                                        'data-value'        =>array(),
                                        'role'              => array(),
                                        'data-parentcity'   =>array(),
                                        'data-value2'       =>array(),
                                    ),
                              
                            );
$current_name      =   '';
$current_slug      =   '';
$listings_list     =   '';
$show_filter_area  =   '';

if( isset($post->ID) ){
    $show_filter_area  =   get_post_meta($post->ID, 'show_filter_area', true);
}

if( is_tax() ){
    $show_filter_area = 'yes';
    $current_adv_filter_search_label        =   esc_html__( 'All Sizes','wprentals');
    $category_second_action_dropdown        =  stripslashes( esc_html(wprentals_get_option('wp_estate_category_second_dropdown', '')));
    
    if($category_second_action_dropdown!=''){
        $current_adv_filter_search_label=$category_second_action_dropdown;
    }
    $current_adv_filter_search_label_non    =   'all';
    $current_adv_filter_category_label      =   esc_html__( 'All Types','wprentals');
    $category_second_dropdown_label         =   stripslashes( esc_html(wprentals_get_option('wp_estate_category_main_dropdown', '')));
    if($category_second_dropdown_label!=''){
        $current_adv_filter_category_label=$category_second_dropdown_label;
    }
    $current_adv_filter_category_label_non  =   'all';
    $current_adv_filter_city_label          =   esc_html__( 'All Cities','wprentals');
    $current_adv_filter_city_label_non      =   'All Cities';
    $current_adv_filter_area_label          =   esc_html__( 'All Areas','wprentals');
    $current_adv_filter_area_label_non      =   'All Areas';    
    $taxonmy                                =   get_query_var('taxonomy');
    $term                                   =   single_cat_title('',false);
    
    if ($taxonmy == 'property_city'){
        $current_adv_filter_city_label =    $current_adv_filter_city_label_non  =   ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_area'){
        $current_adv_filter_area_label =   $current_adv_filter_area_label_non  =    ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_category'){
        $current_adv_filter_category_label =$current_adv_filter_category_label_non = ucwords( str_replace('-',' ',$term) );
    }
    if ($taxonmy == 'property_action_category'){
        $current_adv_filter_search_label = $current_adv_filter_search_label_non =   ucwords( str_replace('-',' ',$term) );
    }
    
}

$listing_filter         =   '';
$selected_order         = esc_html__( 'Sort by','wprentals');
if( isset($post->ID) ){
    $listing_filter         = get_post_meta($post->ID, 'listing_filter',true );
}
$listing_filter_array   = array(
                            "1"=>esc_html__( 'Price High to Low','wprentals'),
                            "2"=>esc_html__( 'Price Low to High','wprentals'),
                            "0"=>esc_html__( 'Default','wprentals')
                        );
    
$local_args_property_lsit_filters = wpestate_get_select_arguments();
foreach($listing_filter_array as $key=>$value){
    $listings_list.= '<li role="presentation" data-value="'.esc_attr($key).'">'.esc_html($value).'</li>';

    if($key==$listing_filter){
        $selected_order=$value;
    }
}   
      

$order_class='';
if( $show_filter_area != 'yes' ){
    $order_class=' order_filter_single ';  
}

        
if( $show_filter_area=='yes' ){
        if ( is_tax() ){
            $curent_term    =   get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            $current_slug   =   $curent_term->slug;
            $current_name   =   $curent_term->name;
            $current_tax    =   $curent_term->taxonomy; 
        }

    $action_select_list =   wpestate_get_action_select_list($local_args_property_lsit_filters);
    $categ_select_list  =   wpestate_get_category_select_list($local_args_property_lsit_filters);
    $select_city_list   =   wpestate_get_city_select_list($local_args_property_lsit_filters); 
  
    if(is_tax() && $taxonmy=='property_city' ){
        $select_area_list   =   wpestate_get_area_select_list($local_args_property_lsit_filters);
    }else{
        $select_area_list   =   wpestate_get_area_select_list($local_args_property_lsit_filters);
    }
        
}// end if show filter

$category_second_action_dropdown=  stripslashes( esc_html(wprentals_get_option('wp_estate_category_second_dropdown', '')));
if($category_second_action_dropdown==$current_adv_filter_search_label_non){
    $current_adv_filter_search_label_non='all';   
}

$category_second_dropdown_label = stripslashes( esc_html(wprentals_get_option('wp_estate_category_main_dropdown', '')));
if($category_second_dropdown_label == $current_adv_filter_category_label_non){
    $current_adv_filter_category_label_non='all';
}

?>

    <?php if( $show_filter_area=='yes' ){?>
    <div class="listing_filters_head row"> 
        <input type="hidden" id="page_idx" value="
            <?php 
            if(!is_tax() ) {
                print intval($post->ID);
            }
            ?>">

        <!--
        <div class="Huéspedes col-md-2">
            <i class="custom_icon_class_icon fas fa-users"></i>
            <div class="wpestate_guest_no_control_wraper">
                <div class="wpestate_guest_no_control_info form-control filter_menu_trigger">Huéspedes</div>
                <div class="wpestate_guest_no_buttons" data-max-guest="0" data-max-extra-guest-no="0" data-overload-guest="0">
                    <div class="max_guest_notice"></div>
                    <div class="wpestate_guest_no_buttons_item control_adults">
                        <div class="wpestate_guest_no_buttons_labels">
                            <div class="wpestate_guest_no_buttons_title_labels">Adults</div>
                            <div class="wpestate_guest_no_buttons_description_labels">Ages 13 or above</div>
                        </div>
                        <div class="wpestate_guest_no_buttons_steppers steper_adults">
                            <button class="wpestate_guest_no_button_minus  wpestate_guest_no_button_control">
                                <svg width="13" height="2" viewBox="0 0 13 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0V2H13V0H0Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <div class="wpestate_guest_no_button_value  steper_value_adults">0</div>
                            <button class="wpestate_guest_no_button_plus  wpestate_guest_no_button_control adults_control_plus ">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.6875 0V5.6875H0V7.3125H5.6875V13H7.3125V7.3125H13V5.6875H7.3125V0H5.6875Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <input type="hidden" class="placeholeder_search_val" name="adults_fvalue" value="0">
                        </div>
                    </div>
                    <div class="wpestate_guest_no_buttons_item control_childs">
                        <div class="wpestate_guest_no_buttons_labels">
                            <div class="wpestate_guest_no_buttons_title_labels">Children</div>
                            <div class="wpestate_guest_no_buttons_description_labels">Ages 2 to 12</div>
                        </div>
                        <div class="wpestate_guest_no_buttons_steppers steper_childs">
                            <button class="wpestate_guest_no_button_minus  wpestate_guest_no_button_control">
                                <svg width="13" height="2" viewBox="0 0 13 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0V2H13V0H0Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <div class="wpestate_guest_no_button_value  steper_value_childs">0</div>
                            <button class="wpestate_guest_no_button_plus  wpestate_guest_no_button_control childs_control_plus ">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.6875 0V5.6875H0V7.3125H5.6875V13H7.3125V7.3125H13V5.6875H7.3125V0H5.6875Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <input type="hidden" class="placeholeder_search_val" name="childs_fvalue" value="0">
                        </div>
                    </div>
                    <div class="wpestate_guest_no_buttons_item control_infants">
                        <div class="wpestate_guest_no_buttons_labels">
                            <div class="wpestate_guest_no_buttons_title_labels">Infants</div>
                            <div class="wpestate_guest_no_buttons_description_labels">Under 2 years</div>
                        </div>
                        <div class="wpestate_guest_no_buttons_steppers steper_infants">
                            <button class="wpestate_guest_no_button_minus  wpestate_guest_no_button_control">
                                <svg width="13" height="2" viewBox="0 0 13 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 0V2H13V0H0Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <div class="wpestate_guest_no_button_value  steper_value_infants">0</div>
                            <button class="wpestate_guest_no_button_plus  wpestate_guest_no_button_control infants_control_plus ">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.6875 0V5.6875H0V7.3125H5.6875V13H7.3125V7.3125H13V5.6875H7.3125V0H5.6875Z" fill="#5D6475"></path>
                                </svg>
                            </button>
                            <input type="hidden" class="placeholeder_search_val" name="infants_fvalue" value="0">
                        </div>
                    </div>
                    <a class="close_guest_control" href="#">Close</a>
                </div>
                <input type="hidden" name="guest_no" class="guest_no_hidden" value="0">
            </div>
            <script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                    wpestate_control_guest_no();
                });
                //]]&gt;
            </script>
        </div>
        -->


            <div class="col-md-2">
                <div class="dropdown custom_icon_class  form-control ">
                    <div data-toggle="dropdown" id="property_category_toogle" class=" filter_menu_trigger  " data-value="">Villas <span class="caret  caret_filter "></span></div>
                    <ul class="dropdown-menu filter_menu menu-properties" role="menu" aria-labelledby="property_category_toogle">
                    </ul>
                </div>
            </div>



            <div class="col-md-2 main_taxonomy_filter">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_categ" class="filter_menu_trigger" 
                        data-value="<?php print wp_kses($current_adv_filter_category_label_non,$allowed_html); ?>"> <?php  print wp_kses($current_adv_filter_category_label,$allowed_html); ?> <span class="caret caret_filter"></span> </div>           
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_categ">
                        <?php print  wp_kses($categ_select_list,$allowed_html_list);?>
                    </ul>        
                </div>      
            </div>
       
            <div class="col-md-2 city_taxonmy_filter">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_cities" class="filter_menu_trigger" data-value="<?php print wp_kses($current_adv_filter_city_label_non,$allowed_html); ?>"> <?php print wp_kses($current_adv_filter_city_label,$allowed_html);  ?> <span class="caret caret_filter"></span> </div>           
                    <ul id="filter_city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_cities">
                        <?php  print trim($select_city_list); ?>
                    </ul>        
                </div>
            </div>
        
            <div class="col-md-2 area_taxonomy_filter">
                <div class="dropdown form_control listing_filter_select" >
                    <div data-toggle="dropdown" id="a_filter_areas" class="filter_menu_trigger" data-value="<?php  print wp_kses($current_adv_filter_area_label_non,$allowed_html); ?>"> <?php print wp_kses($current_adv_filter_area_label,$allowed_html); ?> <span class="caret caret_filter"></span> </div>           
                    <ul id="filter_area" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_areas">
                        <?php   print  wp_kses($select_area_list,$allowed_html_list); ?>
                    </ul>        
                </div>
            </div>

            <!--
            <div class="col-md-2">
                <div class="dropdown custom_icon_class  form-control">
                    <div data-toggle="dropdown" id="property_category_toogle" class=" filter_menu_trigger  " data-value="">Piscina <span class="caret  caret_filter "></span></div>
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="property_category_toogle">
                        <li role="presentation" data-value="piscina-comunitaria">Piscina comunitaria</li>
                        <li role="presentation" data-value="piscina-privada">Piscina privada</li>
                        <li role="presentation" data-value="sin-piscina">Sin piscina</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-2">
                <div class="dropdown custom_icon_class  form-control">
                    <div data-toggle="dropdown" id="property_category_toogle" class=" filter_menu_trigger  " data-value="">Servicios <span class="caret  caret_filter "></span></div>
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="property_category_toogle">
                        <li role="presentation" data-value="wifi">Wifi</li>
                        <li role="presentation" data-value="aire-acondicionado">Aire acondicionado</li>
                        <li role="presentation" data-value="mascotas">Mascotas</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-2 order_filter">
                <div class="dropdown  listing_filter_select " >
                    <div data-toggle="dropdown" id="a_filter_order" class="filter_menu_trigger" data-value="0"> <?php //print wp_kses($selected_order,$allowed_html); ?> <span class="caret caret_filter"></span> </div>

                    <ul id="filter_order" class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_order">
                        <?php  //print  wp_kses($listings_list,$allowed_html_list);?>
                    </ul>        
                </div>
            </div>
            -->
    </div> 
    <?php } ?>      