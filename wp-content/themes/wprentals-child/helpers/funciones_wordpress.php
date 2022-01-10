<?php

###############################################################  services ##############################################################

//$services = get_services($actual_language);
//$vector_servicios = array(322,22156);



# get services
# 322 ,  14097 | piscina comunitaria
# 22156 piscina privada
# piscina comunitaria y piscina privada
function get_services_all($lang){
    global $db , $vector_servicios;
    $servicios_list = implode(",",$vector_servicios);
    $sql = " select * from dynamic_services where id IN($servicios_list) AND language = '".$lang."'; ";
    $services = $db->get_results($sql);

    return ($services) ? $services : false;
}


function get_service_name_by_code($service_code , $lang){
    global $db , $vector_servicios;
    //$servicios_list = implode(",",$vector_servicios);
    $sql = " select * from dynamic_services where id = $service_code AND language = '".$lang."'; ";
    $services = $db->get_row($sql);

    return ($services) ? $services : false;
}


/*
function get_term_by_language(){
    # name and id
    $mkind_id = intval($region->RegionCode);
    $mking_name = (String)$region->Name . "-" . $lang;
    $my_cat = array(
        'description' => $mking_name,
        'slug' => sanitize_title($mking_name),
        'parent' => 0
    );
    $my_term = term_exists($mking_name, 'property_city', $my_cat);
}
*/

###############################################################  features ##############################################################

# characteristics
// $checkbox_chimenea = get_term_by_language();
function getTextosFeature($language){
    $textos = array(
        "es" => array(
            "sauna",
            "jacuzzi",
            "secador de pelo",
            "ascensor",
            "grupos de personas",
            "piscina",
            "tv",
            "jardin",
            "muebles de jardin",
            "plancha",
            "chimenea",
            "barbacoa",
            "radio",
            "minibar",
            "terraza",
            "parcela vallada",
            "caja de seguridad",
            "dvd",
            "balcon",
            "exprimidor",
            "hervidor electrico",
            "zona para niños",
            "gimnasio",
            "alarma",
            "tennis",
            "squash",
            "paddel",
            "apta para discapacitados",
            "nevera",
            "congelador",
            "lavavajillas",
            "lavadora",
            "secadora",
            "cafetera",
            "tostadora",
            "microondas",
            "horno",
            "vajilla",
            "utensilios de cocina",
        ),
        "en" => array(
            "sauna",
            "jacuzzi",
            "hair dryer",
            "lift",
            "Groups of people",
            "pool",
            "TV",
            "yard",
            "garden furniture",
            "iron",
            "fireplace",
            "barbecue",
            "radio",
            "minibar",
            "Terrace",
            "fenced plot",
            "safe deposit box",
            "DVD",
            "balcony",
            "squeezer",
            "electric kettle",
            "children's area",
            "Gym",
            "alarm",
            "tennis",
            "squash",
            "paddle",
            "suitable for the disabled",
            "fridge",
            "freezer",
            "dishwasher",
            "washing machine",
            "drying machine",
            "coffee maker",
            "toaster",
            "microwave",
            "kiln",
            "crockery",
            "Cookware",
        ),
        "ca" => array(
            "sauna",
            "jacuzzi",
            "assecador de cabell",
            "ascensor",
            "grups de persones",
            "piscina",
            "tv",
            "jardí",
            "mobles de jardí",
            "planxa",
            "xemeneia",
            "barbacoa",
            "ràdio",
            "minibar",
            "terrassa",
            "parcel·la tancada",
            "caixa de seguretat",
            "dvd",
            "balcó",
            "espremedora",
            "bullidor elèctric",
            "zona per a nens",
            "gimnàs",
            "alarma",
            "tennis",
            "esquaix",
            "pàdel",
            "apta per a discapacitats",
            "nevera",
            "congelador",
            "rentavaixelles",
            "rentadora",
            "assecadora",
            "cafetera",
            "torradora",
            "microones",
            "forn",
            "vaixella",
            "estris de cuina",
        ),
        "fr" => array(
            "sauna",
            "jacuzzi",
            "sèche-cheveux",
            "ascenseur",
            "Des groupes de personnes",
            "bassin",
            "LA TÉLÉ",
            "Cour",
            "mobilier de jardin",
            "fer à repasser",
            "cheminée",
            "barbecue",
            "radio",
            "mini-bar",
            "Terrasse",
            "terrain clôturé",
            "coffre-fort",
            "DVD",
            "balcon",
            "presse-agrumes",
            "bouilloire électrique",
            "espace enfants",
            "Gym",
            "alarme",
            "tennis",
            "écraser",
            "pagayer",
            "adapté aux personnes handicapées",
            "réfrigérateur",
            "congélateur",
            "lave-vaisselle",
            "Machine à laver",
            "Sèche-linge",
            "machine à café",
            "grille-pain",
            "four micro onde",
            "four",
            "vaisselle",
            "ustensiles de cuisine",
        ),

    );
    return $textos[$language];
}
?>