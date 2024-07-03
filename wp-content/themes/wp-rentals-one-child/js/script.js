// domain name
let domain  	    = location.protocol+'//'+document.domain;
let domain_cookie   = document.domain;
let protocol        = window.location.protocol;
let host            = window.location.host;
let pathname        = window.location.pathname;
let location_search = window.location.search;
let url             = "";

// pagina
let pagina = "";

jQuery(document).ready(function() {

    //console.log("ready");

    // slider home
    setTimeout(function(){
        jQuery(".elementor-background-overlay").css({
            'display' : 'block',
        });
    },1000);

    // change menu address
    jQuery(".pll-parent-menu-item a").each(function(index){
        //console.log("encontrado 1 anchor");
        let url = jQuery(this).attr("href");
        url = url.replace("avantio_cron_cli_wordpress/", "");
        jQuery(this).attr("href",url);
    });


    jQuery("#sm-16447664416749246-10 a").each(function(index){
        //console.log("encontrado 2 anchor");
        let url = jQuery(this).attr("href");
        url = url.replace("avantio_cron_cli_wordpress/", "");
        jQuery(this).attr("href",url);
    });

    // camibar
    let direccion = location.href;
    let encontrado_ingles_frances = direccion.search("destinations");
    let encontrado_catalan = direccion.search("destinacions");
    if(encontrado_ingles_frances != -1){
        jQuery("h1.heading_over_image").text("Destinations");
    }else if(encontrado_catalan != -1){
        jQuery("h1.heading_over_image").text("Destinacions");
    }// end if


    // menu destinos
    // disableMenuDestinos();

    // check images no background
    setImagesDestinationsNotBackground();

    // check images desinations home no background
    setImagesDestinationsHomeNotBackground();

    // check submit button
    checkSubmitSearch();

    // focus selector
    setFocusSelector();

    // change area for property_area in domain tiendapisosenmanresa
    if(domain.search("tiendapisosenmanresa") != -1){
        change_link_menu_destinations_tiendapisosenmanresa();
    }

});


function change_link_menu_destinations_tiendapisosenmanresa(){
    jQuery(".menu-item a").each(function(){
        let url = jQuery(this).attr("href");
        if (url.search("area") != -1){
            url = url.replace("area","property_area");
            jQuery(this).attr("href",url);
        }// end if
    });
}

function checkSubmitSearch(){
    
    jQuery(document).on("submit",function(){
        
        // form fields
        let property_area = jQuery("#property_area").val();
        let property_category = jQuery("#property_category").val();
        let guest_no = jQuery("#guest_no").val();
        
        if(property_area == "" && property_category == "" && guest_no == ""){
            let val = jQuery("#menu-properties").val();
            window.open(val , "_blank");
            return false;   
        }else{
            return true;
        }

    });
    
    
}

function setFocusSelector(){
    jQuery(window).focus(function() {
        jQuery("#menu-properties").focus();
        //console.log('Focus');
    });
}


function setImagesDestinationsNotBackground(){

    //console.log("dominio: "+domain);
    // address
    let direccion = location.href;
    
    // calella de palafrugell
    let encontrado = direccion.search("calella");
    let encontrado_palafrugell = false;
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Calella-de-Palafrugell-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Calella-de-Palafrugell-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
        encontrado_palafrugell = true;
    }

    // palafrugell
    encontrado = direccion.search("palafrugell");
    if(encontrado != -1 && encontrado_palafrugell == false){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/22-1-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/22-1-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // llafranc
    encontrado = direccion.search("llafranc");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/23-1-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/23-1-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // vidreres
    encontrado = direccion.search("vidre");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Vidreres-1-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Vidreres-1-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // torroella
    encontrado = direccion.search("torroella");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Torrella-de-Montgri-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Torrella-de-Montgri-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // tamariu
    encontrado = direccion.search("tamariu");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Tamariu-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Tamariu-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // pals
    encontrado = direccion.search("pals");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Pals-1-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Pals-1-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // lloret
    encontrado = direccion.search("lloret");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/LLoret-de-Mar-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/LLoret-de-Mar-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // esclanya
    encontrado = direccion.search("esclan");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Esclanyá-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Esclanyá-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // canyelles
    encontrado = direccion.search("canyelles");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') !=  'url("'+domain+'/wp-content/uploads/2021/10/Canyelles-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Canyelles-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

    // canyelles
    encontrado = direccion.search("blanes");
    if(encontrado != -1){
        if (jQuery('.listing_main_image').css('background-image') != 'url("'+domain+'/wp-content/uploads/2021/10/Blanes-scaled.jpg")'){
            let imageUrl = domain+"/wp-content/uploads/2021/10/Blanes-scaled.jpg";
            jQuery('.listing_main_image').css('background-image', 'url("' + imageUrl + '")');
        }
    }

}


function setImagesDestinationsHomeNotBackground(){

    let imageUrl = "";

    jQuery("div.featuredplace").each(function(index){
        switch (index){
            case 0:
                imageUrl = domain+"/wp-content/uploads/2021/10/Blanes-1170x921.jpg";
                jQuery(this).css('background-image', 'url("' + imageUrl + '")');
            break;
            case 1:
                imageUrl = domain+"/wp-content/uploads/2021/10/Calella-de-Palafrugell-1170x921.jpg";
                jQuery(this).css('background-image', 'url("' + imageUrl + '")');
            break;
            case 2:
                imageUrl = domain+"/wp-content/uploads/2021/10/23-1-1170x921.jpg";
                jQuery(this).css('background-image', 'url("' + imageUrl + '")');
            break;
            case 3:
                imageUrl = domain+"/wp-content/uploads/2021/10/LLoret-de-Mar-1170x921.jpg";
                jQuery(this).css('background-image', 'url("' + imageUrl + '")');
            break;
            case 4:
                imageUrl = domain+"/wp-content/uploads/2021/10/Pals-1-1170x921.jpg";
                jQuery(this).css('background-image', 'url("' + imageUrl + '")');
            break;
        }
    });
	
	jQuery("a.featured_listing_title").each(function(index){
        switch (index){
            case 0:
                jQuery(this).text("Blanes");
            break;
            case 1:
                jQuery(this).text("Calella de Palafrugell");
            break;
            case 2:
                jQuery(this).text("Llafranc");
            break;
            case 3:
                jQuery(this).text("Lloret de Mar");
            break;
            case 4:
                jQuery(this).text("Pals");
            break;
        }
    });

}

function disableMenuDestinos(){

    setTimeout(function(){
        let direccion = location.href;
        let encontrado = direccion.search("destinations");
        //console.log("encontrado"+encontrado);
        jQuery(".menu-item > a").each(function(){
            let direccion_menu = jQuery(this).attr("href");
            let encontrado_menu = direccion_menu.search("destinations");
            console.log("encontrado menu"+encontrado_menu);
            if (encontrado_menu != -1){
                jQuery(this).attr("href","#");
            }// end if
        });
        // set destinos content
        // destino_content();
        encontrado = direccion.search("destinacions");
        //console.log("encontrado"+encontrado);
        jQuery(".menu-item > a").each(function(){
            let direccion_menu = jQuery(this).attr("href");
            let encontrado_menu = direccion_menu.search("destinacions");
            //console.log("encontrado menu"+encontrado_menu);
            if (encontrado_menu != -1){
                jQuery(this).attr("href","#");
            }// end if
        });
        encontrado = direccion.search("destinos");
        //console.log("encontrado"+encontrado);
        jQuery(".menu-item > a").each(function(){
            let direccion_menu = jQuery(this).attr("href");
            let encontrado_menu = direccion_menu.search("destinos");
            //console.log("encontrado menu"+encontrado_menu);
            if (encontrado_menu != -1){
                jQuery(this).attr("href","#");
            }// end if
        });
    },1000);
}


function prueba(){
    setTimeout(function(){
        jQuery(".menu-properties > li").each(function(index){
            firstDigit = jQuery(this).val().match(/\d/) // will give you the first digit in the string
            indexed = jQuery(this).val().indexOf(firstDigit)
            console.log("dato: "+jQuery(this).val());
            if (indexed == 0){
                jQuery(this).remove();
                jQuery(this).empty();
            }
        });
    },1000);
}

function destino_content(){

    let cadena='<div className="elementor-widget-wrap elementor-element-populated">';
    cadena+='   <div className="elementor-element elementor-element-20141afb elementor-widget elementor-widget-Wprentals_Grids"';
    cadena+='         data-id="20141afb" data-element_type="widget" data-widget_type="Wprentals_Grids.default">';
    cadena+='        <div className="elementor-widget-container">';
    cadena+='            <div className="row elementor_wprentals_grid"></div>';
    cadena+='        </div>';
    cadena+='    </div>';
    cadena+='   <div className="elementor-element elementor-element-7ea30e8 elementor-widget elementor-widget-Wprentals_Grids" data-id="7ea30e8" data-element_type="widget" data-widget_type="Wprentals_Grids.default">';
    cadena+='        <div className="elementor-widget-container">';
    cadena+='            <div className="row elementor_wprentals_grid">';
    cadena+='                <div className="col-md-8 col-sm-12 elementor_rentals_grid">';
    cadena+='                   <div className="places_wrapper   type_2_class  " data-link="https://www.llvillas.com/area/blanes-es/">';
    cadena+='                        <div className="listing-hover-gradient"></div>';
    cadena+='                        <div className="listing-hover"></div>';
    cadena+='                       <div className="places1 featuredplace" style="background-image:url(https://www.llvillas.com/wp-content/uploads/2021/10/Blanes-1170x921.jpg);"></div>';
    cadena+='                       <div className="category_name"><a className="featured_listing_title" href="https://www.llvillas.com/property_area/blanes-es/">Blanes</a>';
    cadena+='                            <div className="category_tagline"></div>';
    cadena+='                        </div>';
    cadena+='                    </div>';
    cadena+='                </div>';
    cadena+='                <div className="col-md-4 col-sm-12 elementor_rentals_grid">';
    cadena+='                   <div className="places_wrapper   type_2_class  " data-link="https://www.llvillas.com/area/calella-de-palafrugell-es/">';
    cadena+='                        <div className="listing-hover-gradient"></div>';
    cadena+='                        <div className="listing-hover"></div>';
    cadena+='                       <div className="places1 featuredplace" style="background-image:url(https://www.llvillas.com/wp-content/uploads/2021/10/Calella-de-Palafrugell-1170x921.jpg);"></div>';
    cadena+='                       <div className="category_name"><a className="featured_listing_title" href="https://www.llvillas.com/property_area/calella-de-palafrugell-es/">Calella de Palafrugell</a>';
    cadena+='                            <div className="category_tagline"></div>';
    cadena+='                        </div>';
    cadena+='                    </div>';
    cadena+='                </div>';
    cadena+='                <div className="col-md-4 col-sm-12 elementor_rentals_grid">';
    cadena+='                   <div className="places_wrapper   type_2_class  " data-link="https://www.llvillas.com/area/llafranc-es/">';
    cadena+='                        <div className="listing-hover-gradient"></div>';
    cadena+='                        <div className="listing-hover"></div>';
    cadena+='                       <div className="places1 featuredplace" style="background-image:url(&quot;https://www.llvillas.com/wp-content/uploads/2021/10/23-1-1170x921.jpg&quot;);"></div>';
    cadena+='                       <div className="category_name"><a className="featured_listing_title" href="https://www.llvillas.com/property_area/llafranc-es/">Llafranc</a>';
    cadena+='                            <div className="category_tagline"></div>';
    cadena+='                        </div>';
    cadena+='                    </div>';
    cadena+='                </div>';
    cadena+='                <div className="col-md-4 col-sm-12 elementor_rentals_grid">';
    cadena+='                   <div className="places_wrapper   type_2_class  " data-link="https://www.llvillas.com/area/lloret-de-mar-es/">';
    cadena+='                        <div className="listing-hover-gradient"></div>';
    cadena+='                        <div className="listing-hover"></div>';
    cadena+='                       <div className="places1 featuredplace" style="background-image:url(https://www.llvillas.com/wp-content/uploads/2021/10/LLoret-de-Mar-1170x921.jpg);"></div>';
    cadena+='                       <div className="category_name"><a className="featured_listing_title" href="https://www.llvillas.com/property_area/lloret-de-mar-es/">Lloret de Mar</a>';
    cadena+='                            <div className="category_tagline"></div>';
    cadena+='                        </div>';
    cadena+='                    </div>';
    cadena+='                </div>';
    cadena+='                <div className="col-md-4 col-sm-12 elementor_rentals_grid">';
    cadena+='                   <div className="places_wrapper   type_2_class  " data-link="https://www.llvillas.com/area/pals-es/">';
    cadena+='                        <div className="listing-hover-gradient"></div>';
    cadena+='                        <div className="listing-hover"></div>';
    cadena+='                       <div className="places1 featuredplace" style="background-image:url(https://www.llvillas.com/wp-content/uploads/2021/10/Pals-1-1170x921.jpg);"></div>';
    cadena+='                       <div className="category_name"><a className="featured_listing_title" href="https://www.llvillas.com/property_area/pals-es/">Pals</a>';
    cadena+='                            <div className="category_tagline"></div>';
    cadena+='                        </div>';
    cadena+='                    </div>';
    cadena+='                </div>';
    cadena+='            </div>';
    cadena+='        </div>';
    cadena+='    </div>';
    cadena+='   <div className="elementor-element elementor-element-926aade elementor-widget elementor-widget-spacer"';
    cadena+='         data-id="926aade" data-element_type="widget" data-widget_type="spacer.default">';
    cadena+='        <div className="elementor-widget-container">';
    cadena+='            <div className="elementor-spacer">';
    cadena+='                <div className="elementor-spacer-inner"></div>';
    cadena+='            </div>';
    cadena+='        </div>';
    cadena+='    </div>';
    cadena+='   <div className="elementor-element elementor-element-5e2c0cbe elementor-widget elementor-widget-Wprentals_Grids"';
    cadena+='         data-id="5e2c0cbe" data-element_type="widget" data-widget_type="Wprentals_Grids.default">';
    cadena+='        <div className="elementor-widget-container">';
    cadena+='            <div className="row elementor_wprentals_grid"></div>';
    cadena+='        </div>';
    cadena+='    </div>';
    cadena+='</div>';

    jQuery("#post .single-content .elementor-widget-wrap").first().append(cadena);
    console.log(jQuery(".elementor-section"));

    //return cadena;
}

function delete_image_carousel(){

    jQuery(".carousel-inner").css("display","none");


    setTimeout(function(){
        //console.log("borrando imagenes");
        // delete images
        jQuery(".carousel-inner img").each(function(){
            console.log(jQuery(this).attr("src"));
            if (jQuery(this).attr("src") == "https://www.llvillas.com/wp-content/themes/wp-rentals-one-child/img/defaultimage_prop.jpg"){
                jQuery(this).parent().parent().next().addClass("active");
                jQuery(this).parent().parent().remove();
                jQuery(this).parent().parent().empty();

            }
        });
        jQuery(".carousel-inner").css("display","block");
    },0);

}