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

    .elementor-element-46a3c94d{
        background:#00518F;
    }


    .menu-properties:focus-visible {
        outline: none;

    }


    #menu-properties {
        margin-top: 1px;
        margin-left: 5px;
        position: relative;
        display: inline-block;
        width: 227px;
        border: 0px solid #eee;
        padding: 9px;
        cursor: pointer;
        background: #fff;
        color: #8A8F9A;
        /*font-family: fontAwesome;*/
        padding-left:40px;
        padding-top:13px;
        font-size: 15px;
        font-weight:initial;

    }

    .select_label {
        border: 1px solid #f1f3f7;
        overflow: hidden;
        height: 50px;
        width: 240px;
        position: relative;
        display: block;
    }

    
    .select_label:after {
        content: "\f078";
        position: absolute;
        right: 0px;
        font-family: FontAwesome;
        top: 13px;
        width: 20px;
        height: 20px;
        background-repeat: no-repeat;
        font-size: 11px;
        color: #8A8F9A;
    }


</style>


<?php if ( !wp_is_mobile() ) { ?>
    </div>

</div> <!-- close container -->

<?php }else{ ?>
        </div>

    </div>

</div>

<!-- close container -->

<?php } ?>

<?php

# constants
define("app_child_url", __DIR__);


# var
$db = "";
$avantio_credential = "servidor";
//$avantio_credential = "servidor_dani";
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
        # no funciona
        // echo do_shortcode("[elementor-template id='371106']");
        # no funciona
        // echo do_shortcode("[elementor-template id='371605']");
        echo do_shortcode("[elementor-template id='434073']");
        //echo do_shortcode("[elementor-template id='371051']");
        //echo do_shortcode("[elementor-template id='371048']");
        //echo do_shortcode("[elementor-template id='371605']");
        //[elementor-template id="371605"]
        //echo do_shortcode("[elementor-template id='371106']");
        //echo do_shortcode("[elementor-template id='371143']");
        $word_propiedad = "Propiedad";
        $word_ocultar = "Ocultar";
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
        //echo do_shortcode("[elementor-template id='371143']");
        echo do_shortcode("[elementor-template id='36805183733']");
        //[elementor-template id="371143"]
        # words
        $word_propiedad = "Property";
        $word_ocultar = "Hide";
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
        //echo do_shortcode("[elementor-template id='371201']");
        echo do_shortcode("[elementor-template id='36805183740']");
        $word_propiedad = "Propieatat";
        $word_ocultar = "Amagar";
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
        //echo do_shortcode("[elementor-template id='371178']");
        echo do_shortcode("[elementor-template id='36805183744']");
        $word_propiedad = "Propriété";
        $word_ocultar = "Cacher";
    break;

}// end switch
?>


</div> <!-- end class container -->



</div> <!-- end website wrapper -->


<script>


    jQuery(document).ready(function($) {
        //show_hide_search($, 1);
        change_search($);
        load_properties($);
        // listener_properties_menu($)
    });

    function listener_menu_properties($){

        // select focus
        $("#menu-properties").focus();


        $("#menu-properties").change(function(){
            $(this).click(function(){
                var val = $(this).val();
                //window.location.href = val;
                window.open(val , "_blank");
            });
        });

    }

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
        <?php //foreach ($services as $service) { ?>
         cadena+='<li role="presentation" data-id="<?php //echo $service->id; ?>" data-value="<?php //echo $service->text_title; ?>"><?php //echo $service->text_title; ?></li>';
        <?php //} ?>
        cadena+='<li role="presentation" data-id="'+codigo_sin_piscina+'" data-value="'+titulo_sin_piscina+'">'+titulo_sin_piscina+'</li>';
        cadena+= '</ul>';
        cadena+= '</div>';
        cadena+= '</div>';

        return cadena;
    }


    function build_villas(){
        let word_propiedad = "<?php echo $word_propiedad; ?>";
        let cadena = '<div class="col-md-3">';
        cadena+= '<i class="custom_icon_class_icon fas fa-warehouse"></i>';
        cadena+='<label class="select_label">';
        cadena+='<select name="menu-properties" id="menu-properties" class="menu-properties">';
        cadena+='</select>';
        cadena+='</label>';
        /*
        cadena+= '<div class="dropdown custom_icon_class  form-control " id="villas_button">';
        cadena+= '<div data-toggle="dropdown" id="villas_directo_toogle" class=" filter_menu_trigger  " data-value="">'+word_propiedad+' <span class="caret  caret_filter "></span>';
        cadena+= '</div>';
        cadena+= '<ul class="dropdown-menu filter_menu menu-properties" id="ulId" role="menu" aria-labelledby="villas_directo_toogle">';
        cadena+= '</ul>';
        cadena+= '</div>';
        */
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


    function load_properties($) {

        // domain name
        let domain = location.protocol + '//' + document.domain;
        let domain_cookie = document.domain;
        let protocol = window.location.protocol;
        let host = window.location.host;
        let pathname = window.location.pathname;
        let pathname_vector = pathname.split("/");
        let location_search = window.location.search;
        let ajaxurl = "";


        ajaxurl = domain+"/wp-admin/admin-ajax.php";
        let name = "";
        let url = domain + pathname + "index.php";
        let cadena = "";
        let language = "<?php echo pll_current_language() ?>";
        let abierto = false;

		/*console.log("url:" + url);
		console.log("domain:" + domain);
		console.log("pathname:" + pathname);
		console.log("host:" + host);*/
		
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            dataType: 'json',
            data: {
                'action':'load_properties',
                'lang': language
            },
            success: function (data) {
                language = data["language"];
                for (let i=0; i < data["properties"].length; i++ ){
                    url = domain + pathname + "index.php";
                    name = data["properties"][i]["post_title"];
                    name = name.replaceAll("-"," ");
                    url = data["properties"][i]["guid"];
					//cadena+='<li role="presentation" data-value="'+url+'">'+name+'</li>';
                    cadena+='<option value="'+url+'">'+name+'</option>';
                }// end for
                jQuery(".menu-properties").append(cadena);
                //show_hide_search(jQuery,2);

                // select focus
                $("#menu-properties").focus();


                $(document).on("change", "#menu-properties" , function(){

                    $(this).keypress(function(event){
                        if(event.which == 13){
                            let val = $(this).val();
                            window.open(val , "_blank");
                        }// end if
                    });
                    /*
                    $(this).click(function(){
                        var val = $(this).val();
                        window.open(val , "_blank");
                        //$(this).blur();
                    });
                    */
                });
                
                 

                if (mobileCheck()){
                    jQuery(document).on('change', '.menu-properties', function() {
                        // do something here
                        window.open(jQuery(this).val(), "_blank");
                    });
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("error: "+textStatus+" "+errorThrown);
            }
        });//end ajax
    }


    function mobileCheck() {
        let check = false;
        (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
        return check;
    }

    function convertToString(data){
        let properties_string = JSON.stringify(data);
        console.log("dato:"+properties_string);
        console.log("tipo dato:"+typeof(properties_string));
        if(typeof properties === 'object'){
            console.log("datos: "+properties);
        }else if (typeof properties === 'array'){

        }
    }

    function listener_properties_menu($){

        jQuery(document).on('keyup','.filter_menu_trigger', function(event){
            console.log("escribimos");
        });    

        // jQuery('#villas_directo_toogle').on('keyup', function(event){
        jQuery(document).on('keyup','#villas_directo_toogle', function(event){
            var key = String.fromCharCode(event.keyCode);
            var keyChar = "";
            console.log("tecla"+key);
            if(event.which !== 8) {
                keyChar += key;
            }
            if(event.which === 8) {
                keyChar = keyChar.slice(0, -1);
            }
            var regex = new RegExp('^' + keyChar, 'i');

            jQuery("#ulId li").each(function(){
                if(regex.test($(this).text())) {
                    jQuery(this).css("display", "block");
                    jQuery(this).addClass('active');
                }else{
                    jQuery(this).css("display", "none");
                }
            });
        });

        //$('#ulId').on('blur', function(){
        jQuery(document).on('blur', '#ulId', function(){
            keyChar = '';
        });
    }

    function change_status_select(){

        $("#myselect").on({
            "change": function() {
                $(this).blur();
            },
            'focus': function() {
                console.log("displayed");
            },
            "blur": function() {
                console.log("not displayed");
            },
            "keyup": function(e) {
                if (e.keyCode == 27)
                    console.log("displayed");
            }
        });


        jQuery(document).on('click touchstart', '.menu-properties', function() {
            window.open(jQuery(this).val(), "_blank");
            //window.open(jQuery(this).data('value'), "_blank");
            // do something here
            if(abierto == false){
                window.open(jQuery(this).data('value'), "_blank");
                abierto = true;
            }else if(abierto == true){
                abierto = false;
            }
        });

    }


    function check_submit_button(){
        //$(".advanced_search_submit_button");
        //?property_service_piscina=piscina-privada&property_area=&property_category=&adults_fvalue=0&childs_fvalue=0&infants_fvalue=0&guest_no=0&wpestate_regular_search_nonce=dfe5f4cf5a&_wp_http_referer=%2F&submit=Buscar&piscina-es=1
    }

    function check_pathname(){

        let domain = location.protocol + '//' + document.domain;
        let domain_cookie = document.domain;
        let protocol = window.location.protocol;
        let host = window.location.host;
        let pathname = window.location.pathname;
        let pathname_vector = pathname.split("/");
        let location_search = window.location.search;
        let ajaxurl = "";
        if (pathname_vector[2] != ""){

        }else if(pathname_vector[1] != ""){
            ajaxurl = domain+"/"+pathname_vector[1]+"/wp-admin/admin-ajax.php";
        }else{
            ajaxurl = domain+"/"+"wp-admin/admin-ajax.php";
        }
    }

</script>

<?php wp_footer();  ?>

</body>
</html>