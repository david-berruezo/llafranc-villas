<style>

    .my_footer.elementor-element-478c020f {
        background-color: #00518F;
    }


    .my_footer.elementor-element-478c020f {
        transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
        padding: 70px 0px 70px 0px;
    }

    .elementor-icon-list-text{
        color:#fff!important;
    }

    .my_mail{
        color:#fff!important;
        width:40px!important;
    }

    .elementor-widget.elementor-list-item-link-full_width a {
        width:unset!important;
    }

    .descripcion{
        color:#fff!important;
    }

    .my_footer .elementor-heading-title{
        color:#fff!important;
    }

    .elementor-icon-list-icon > i {
        color:#fff!important;
    }

</style>

<?php if ( !wp_is_mobile() ) { ?>

</div> <!-- close container -->

<?php }else{ ?>

</div> <!-- close container -->

</div> <!-- close container -->

<?php } ?>

<?php

# constants
define("app_child_url", __DIR__);

# includes
include(app_child_url . "/Clases/DB.php");

# var
$db = "";
//$avantio_credential = "local_wordpress";
$avantio_credential = "servidor_tiendapisos";
$services = "";
$actual_language = "";


# connect to database
function connect_db(){
    global $db , $avantio_credential;

    $connector = new Database();
    $connector->setCredential($avantio_credential);
    $db = $connector::getInstance();

}

# get actual_language
$actual_language = pll_current_language();


# get services
# 322 ,  14097 | piscina comunitaria
# 22156 piscina privada
# piscina comunitaria y piscina privada
function get_services($lang){
    global $db;
    $sql = " select * from dynamic_services where id IN(322,22156) AND language = '".$lang."'; ";
    $services = $db->get_results($sql);

    return ($services) ? $services : false;
}


# call to functions
connect_db();
$services = get_services($actual_language);
//p_($services);


switch($actual_language){
    case "es":
        # titulos
        $oficina_llafranc = "Oficina LLafranc";
        $oficina_lloret_de_mar = "Oficina Lloret de Mar";
        $informacion = "Información";
        $reservas = "Reservvas";
        # paginas titulos
        $condiciones = "Condiciones de alquiler";
        $propietarios = "Propietarios";
        $politica_cookies = "Política de cookies";
        $politica_privacidad = "Política de privacidad";
        $aviso_legal = "Aviso legal";
        # paginas urls
        $condiciones_pagina = get_page_link(35455);
        $propietarios_pagina = get_page_link(35486);
        $politica_cookies_pagina = get_page_link(35470);
        $politica_privacidad_pagina = get_page_link(35478);
        $aviso_legal_pagina = get_page_link(35462);
        # descripcion
        $desc = "Contamos con más de 50 años de experiencia y somos una de las agencias pioneras en los alquileres turísticos en diferentes poblaciones a lo largo de la Costa Brava.";
        echo do_shortcode("[elementor-template id='371605']");
        //echo do_shortcode("[elementor-template id='371106']");
    break;
    case "en":
        # titulos
        $oficina_llafranc = "LLafranc Office";
        $oficina_lloret_de_mar = "Lloret de Mar Office";
        $informacion = "Information";
        $reservas = "Bookings";
        # paginas titulos
        $condiciones = "Bookings conditions";
        $propietarios = "Property owners";
        $politica_cookies = "Cookies Policy";
        $politica_privacidad = "Privacy policy";
        $aviso_legal = "Legal notice";
        # paginas urls
        $condiciones_pagina = get_page_link(370486);
        $propietarios_pagina = get_page_link(370506);
        $politica_cookies_pagina = get_page_link(370521);
        $politica_privacidad_pagina = get_page_link(370573);
        $aviso_legal_pagina = get_page_link(370538);
        # descripcion
        $desc = "We have more than 50 years of experience and we are one of the pioneer agencies in tourist rentals in different towns along the Costa Brava.";
        echo do_shortcode("[elementor-template id='371143']");
    break;
    case "ca":
        # titulos
        $oficina_llafranc = "Oficina LLafranc";
        $oficina_lloret_de_mar = "Oficina Lloret de Mar";
        $informacion = "Informació";
        $reservas = "Reservvas";
        # paginas titulos
        $condiciones = "Condicions de lloguer";
        $propietarios = "Propietaris";
        $politica_cookies = "Política de cookies";
        $politica_privacidad = "Política de privacitat";
        $aviso_legal = "Avís legal";
        # paginas urls
        $condiciones_pagina = get_page_link(370487);
        $propietarios_pagina = get_page_link(370507);
        $politica_cookies_pagina = get_page_link(370522);
        $politica_privacidad_pagina = get_page_link(370548);
        $aviso_legal_pagina = get_page_link(370561);
        # descripcion
        $desc = "Comptem amb més de 50 anys d'experiència i som una de les agències pioneres als lloguers turístics a diferents poblacions al llarg de la Costa Brava.";
        echo do_shortcode("[elementor-template id='371201']");
    break;
    case "fr":
        # titulos
        $oficina_llafranc = "LLafranc Office";
        $oficina_lloret_de_mar = "Lloret de Mar Office";
        $informacion = "Information";
        $reservas = "Bookings";
        # paginas titulos
        $condiciones = "Conditions de réservation";
        $propietarios = "Propriétaires";
        $politica_cookies = "Politique aux cookies";
        $politica_privacidad = "Politique de confidentialité";
        $aviso_legal = "Mention légale";
        # paginas urls
        $condiciones_pagina = get_page_link(370488);
        $propietarios_pagina = get_page_link(370727);
        $politica_cookies_pagina = get_page_link(370523);
        $politica_privacidad_pagina = get_page_link(370556);
        $aviso_legal_pagina = get_page_link(370571);
        # descripcion
        $desc = "Nous avons plus de 50 ans d'expérience et nous sommes l'une des agences pionnières de la location touristique dans différentes villes de la Costa Brava.";
        echo do_shortcode("[elementor-template id='371178']");
    break;

}// end switch



?>


</div> <!-- end class container -->



</div> <!-- end website wrapper -->


<script>

    // domain name
    let domain  	    = location.protocol + '//' + document.domain;
    let domain_cookie   = document.domain;
    let protocol        = window.location.protocol;
    let host            = window.location.host;
    let pathname        = window.location.pathname;
    let location_search = window.location.search;

    // let buscador = document.getElementByClassName("adv-search-3");
    // document.getElementById("adv-search-3").style.display = "none";
    // document.querySelector("adv-search-3").style.display = "none";


    jQuery(document).ready(function($) {
        //show_hide_search($, 1);
        change_search($);
        load_properties($);
    });


    function show_hide_search($ , option){

        if (option == 1){
            $(".adv-search-3").hide();
        }else if (option == 2){
            $(".adv-search-3").show();
        }

    }


    function build_piscina(){
        let titulo_piscina = "";
        let titulo_sin_piscina = "";
        let codigo_sin_piscina = "000";
        let actual_langugae = "<?php echo $actual_language?>";
        let cadena = '<div class="col-md-2">';
        switch(actual_langugae) {
            case "es":
                titulo_sin_piscina = "Sin piscina";
                titulo_piscina = "Piscina"
                break;
            case "en":
                titulo_sin_piscina = "No pool";
                titulo_piscina = "Pool"
                break;
            case "fr":
                titulo_sin_piscina = "No bassin";
                titulo_piscina = "Bassin"
                break;
            case "ca":
                titulo_sin_piscina = "Sense piscina";
                titulo_piscina = "Piscina"
                break;
            default:
            // code block
        }
        cadena+= '<i class="custom_icon_class_icon fas fa-warehouse"></i>';
        cadena+= '<div class="dropdown custom_icon_class  form-control ">';
        cadena+= '<div data-toggle="dropdown" id="extra_services_toogle" class=" filter_menu_trigger  " data-value="">'+titulo_piscina+' <span class="caret  caret_filter "></span>';
        cadena+= '<input type="hidden" name="extra_services" id="extra_services" value="">';
        cadena+= '</div>';
        cadena+= '<ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="extra_services_toogle">';
        <?php foreach ($services as $service) { ?>
        cadena+='<li role="presentation" data-id="<?php echo $service->id; ?>" data-value="<?php echo $service->text_title; ?>"><?php echo $service->text_title; ?></li>';
        <?php } ?>
        cadena+='<li role="presentation" data-id="'+codigo_sin_piscina+'" data-value="'+titulo_sin_piscina+'">'+titulo_sin_piscina+'</li>';
        cadena+= '</ul>';
        cadena+= '</div>';
        cadena+= '</div>';

        return cadena;
    }


    function build_villas(){

        let cadena = '<div class="col-md-3">';
        cadena+= '<i class="custom_icon_class_icon fas fa-warehouse"></i>';
        cadena+= '<div class="dropdown custom_icon_class  form-control ">';
        cadena+= '<div data-toggle="dropdown" id="villas_directo_toogle" class=" filter_menu_trigger  " data-value="">Villas <span class="caret  caret_filter "></span>';
        cadena+= '</div>';
        cadena+= '<ul class="dropdown-menu filter_menu menu-properties" role="menu" aria-labelledby="villas_directo_toogle">';
        cadena+= '</ul>';
        cadena+= '</div>';
        cadena+= '</div>';

        return cadena;
    }


    function change_search($){

        // piscina
        /*
        let piscina = build_piscina();
        $(".adv-search-3").find(".col-md-3:last").before(piscina);
        */

        // change tags
        //$(".adv-search-3 .col-md-3:nth-child(1)").removeClass('col-md-3').addClass("col-md-2");
        $(".adv-search-3 .col-md-3:nth-child(2)").removeClass('col-md-3').addClass("col-md-2");
        $(".adv-search-3 .col-md-3:nth-child(3)").removeClass('col-md-3').addClass("col-md-2");
        $(".adv-search-3 .col-md-3:nth-child(4)").removeClass('col-md-3').addClass("col-md-2");
        $(".adv-search-3 .col-md-3:last").removeClass('col-md-3').addClass("col-md-2");

        //show_hide_search($, 1);



        // villas
        let villas = build_villas();
        $(".adv-search-3").find(".col-md-3:first").before(villas);


        // prevent form
        $(document).on("submit", "form", function(e){
            //e.preventDefault();
            //alert('it works!');
            //return  false;
        });

        $("div.buscador");

    }

    function load_properties($){

        // domain name
        let domain  	    = location.protocol+'//'+document.domain;
        let domain_cookie   = document.domain;
        let protocol        = window.location.protocol;
        let host            = window.location.host;
        let pathname        = window.location.pathname;
        let location_search = window.location.search;

        let ajaxurl = domain+"/wp-admin/admin-ajax.php";
        let name = "";
        let url = domain + pathname + "index.php";
        let language = "";
        let cadena = "";
        //let selector = '<select name="properties" id="properties" onClick="abrir_pagina(this)">';
        //console.log("url: "+url);
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action':'load_properties',
            },
            success: function (data) {
                language = data["language"];
                //console.log("datos: "+data["properties"]);
                for (let i=0; i < data["properties"].length; i++ ){
                    url = domain + pathname + "index.php";
                    name = data["properties"][i]["post_name"];
                    if(language == "es"){
                        url+= "/properties/"+name;
                    }else{
                        url+="/"+language+"/properties/"+name;
                    }
                    cadena+='<li role="presentation" data-value="'+url+'">'+name+'</li>';
                    for (const property in data["properties"][i]) {
                        //console.log("property"+property);
                        //console.log(property+":"+data[i][property]);
                    }
                }
                jQuery(".menu-properties").append(cadena);
                //show_hide_search(jQuery,2);


                jQuery(document).on('click', '.menu-properties li', function() {
                    // do something here
                    window.open(jQuery(this).data('value'), "_blank");
                });


                // convert to string
                /*
                let properties_string = JSON.stringify(data);
                console.log("dato:"+properties_string);
                console.log("tipo dato:"+typeof(properties_string));
                if(typeof properties === 'object'){
                    console.log("datos: "+properties);
                }else if (typeof properties === 'array'){

                }
                */
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("error: "+textStatus+" "+errorThrown);
            }
        });//end ajax
    }

    //$(".advanced_search_submit_button");
    //?property_service_piscina=piscina-privada&property_area=&property_category=&adults_fvalue=0&childs_fvalue=0&infants_fvalue=0&guest_no=0&wpestate_regular_search_nonce=dfe5f4cf5a&_wp_http_referer=%2F&submit=Buscar&piscina-es=1

</script>

<?php wp_footer();  ?>

</body>
</html>