<?php
// Page
// Wp Estate Pack
get_header();
$wpestate_options=wpestate_page_details($post->ID);
global $more;
$more       =   0;
$wide_page  =   get_post_meta($post->ID, 'wide_page', true);
$wide_class =   '';
?>


<div id="post" <?php post_class('row  '.$wide_class);?>>
    <?php   include(locate_template('templates/breadcrumbs.php'));?>
    <div class=" <?php print esc_attr($wpestate_options['content_class']);?> ">
        <?php  // include(locate_template('templates/ajax_container.php')); ?>

        <div class="single-content">
            <?php
            global $more;
            $more=0;
            while ( have_posts() ) : the_post();
            if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) != 'no') { ?>
                <h1 class="entry-title single-title" ><?php the_title(); ?></h1>
                <div class="meta-element-head">
                    <?php print the_date('', '', '', FALSE).' '.esc_html__( 'by', 'wprentals').' '.get_the_author();  ?>
                </div>

            <?php
            }

            if (has_post_thumbnail()){
                $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'wpestate_property_full_map');
            }

            the_content('Continue Reading');
            $args = array(
                       'before'           => '<p>' . esc_html__( 'Pages:','wprentals'),
                       'after'            => '</p>',
                       'link_before'      => '',
                       'link_after'       => '',
                       'next_or_number'   => 'number',
                       'nextpagelink'     => esc_html__( 'Next page','wprentals'),
                       'previouspagelink' => esc_html__( 'Previous page','wprentals'),
                       'pagelink'         => '%',
                       'echo'             => 1
              );
            wp_link_pages( $args );
            ?>
        </div>

        <!-- #comments start-->
        <?php
        if(!is_front_page()){
            if ( get_comments_number(get_the_ID() ) !==0 ) :
               // comments_template('', true);
            endif;
        }
        ?>
        <!-- end comments -->

        <?php endwhile; // end of the loop. ?>
    </div>

<?php  include(get_theme_file_path('sidebar.php')); ?>
</div>

<script>
    jQuery(document).ready(function($) {
        //console.log("estamos ready");
    });
</script>

<!--
<li role="presentation" data-taxonomy="extra_services" data-value="piscina-comunitaria-es" data-value="piscina-comunitaria-es">Piscina comunitaria</li>
<li role="presentation" data-taxonomy="property_features" data-value2="piscina-privada" data-value="piscina-privada">Piscina privada</li>
<li role="presentation" data-taxonomy="property_features" data-value2="no-piscina" data-value="no-piscina">Sin piscina</li>


<ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="a_filter_extra_services">
    <li role="presentation" data-taxonomy="extra_services" data-value2="aire-acondicionado-es" data-value="aire-acondicionado-es">Aire acondicionado</li>
    <li role="presentation" data-taxonomy="extra_services" data-value2="calefaccion-es" data-value="calefaccion-es">Calefacción</li>
    <li role="presentation" data-taxonomy="property_features" data-value2="lavadora-es" data-value="lavadora-es">Lavadora</li>
    <li role="presentation" data-taxonomy="property_features" data-value2="lavavajillas-es" data-value="lavavajillas-es">Lavavajillas</li>
    <li role="presentation" data-taxonomy="extra_services" data-value2="wifi-es" data-value="wifi-es">Wifi</li>
    <li role="presentation" data-taxonomy="extra_services" data-value2="mascotas-es2" data-value="mascotas-es">Mascotas</li>
</ul>
-->


<?php
// pagina de prueba
if ($post->ID == 371983){
    $vector_piscina_comunitaria_options = array(
        "es" => "Piscina comunitaria",
        "ca" => "Piscina comunitaria",
        "fr" => "",
        "it" => "",
    );
    $vector_piscina_priada_options = array(
        "es" => "Piscina comunitaria",
        "ca" => "Piscina comunitaria",
        "fr" => "",
        "it" => "",
    );
}// end if

//echo "la pagina es: ".$post->ID."<br>";
?>

<?php get_footer(); ?>
