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

</div> <!-- close container -->


</div> <!-- close container -->

<?php
$actual_language = pll_current_language();


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
        //echo do_shortcode("[elementor-template id='371605']");
        echo do_shortcode("[elementor-template id='434073']");
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
        //echo do_shortcode("[elementor-template id='371143']");
        echo do_shortcode("[elementor-template id='36805183733']");
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
        break;

}// end switch

?>


</div> <!-- end class container -->


</div> <!-- end website wrapper -->


<?php wp_footer();  ?>
</body>
</html>