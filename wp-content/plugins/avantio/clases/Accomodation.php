<?php
/**
 * Created by PhpStorm.
 * User: DAVID01
 * Date: 03/11/2021
 * Time: 19:10
 */

class Accomodation
{

    # post_id
    private $post_id;

    # tipo de villa
    private $tipo_de_villa = "villa";


    private $tipo_de_villa_languages = array(
        "es" => "Villa de lujo",
        "en" => "Luxury Village",
        "ca" => "Vila de luxe",
        "fr" => "Villa de luxe"
    );



    # name of objects Nuevo and KindAlquiler
    private $status = array(
        "es" => "Nuevo",
        "en" => "New",
        "ca" => "Nou",
        "fr" => "Nouveau"
    );

    private $kindAlquiler = array(
        "es" => "Alquiler toda la Villa",
        "en" => "Rent all Villa",
        "ca" => "Lloguer de tota la Villa",
        "fr" => "Louer toute la Villa"
    );


    public function insertAccomodations($xml,$avantio_credentials)
    {

        # create cutom post type and insert and update
        # $this->create_post_type_estate_property();
        //$this->insert_estate_property();
        //$this->update_estate_property();

        # vectors
        $post_vector = array();

        # counters
        $counter_property = 0;
        $counter_property_create = 0;
        $counter_language = 0;


        foreach ($xml->Accommodation as $accommodation) {

            if ($counter_property < 10) {

                //echo "entra aqui<br>";

                # normal fields
                $accion = "insert_or_update";
                $id = (int)$accommodation->AccommodationId;
                $text_title = (string)$accommodation->AccommodationName;
                # kind | taxonomy
                $dynamic_taxonomy = intval($accommodation->MasterKind->MasterKindCode);
                $dynamic_taxonomy_group = intval($accommodation->MasterKind->MasterKindCode);
                $taxonomy_name = (string)$accommodation->MasterKind->MasterKindName;
                // echo "identificador: " .$id. "<br>";

                # villa o villa de lujo
                if ($accommodation->Labels) {
                    foreach ($accommodation->Labels->children() as $mylabel) {
                        if (stripos($mylabel, "villa_de_lujo") !== false) {
                            $this->tipo_de_villa = "villa_de_lujo";
                        }// end if
                    }// end foreach
                }// end if

                # update
                if (get_post_status($id)) {

                    //echo "entra edit<br>";
                    $my_post_translation = pll_get_post_translations($id);
                    //my_print($my_post_translation);
                    foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
                        $post_vector[$lang][$counter_property]["post_id"] = $my_post_translation[$lang];
                    }// end foreach


                    $lang_property = array_search($id, $my_post_translation);
                    //p_($lang_property);
                    //echo "lang property " .$lang_property. "<br>";

                    $this->guardar_caracteristicas($taxonomy_name, $id, $avantio_credentials, $accommodation, $lang_property);

                    # insert
                } else {

                    //echo "entra insert<br>";

                    $accion = "insert";
                    $counter_language = 0;
                    $my_post_es = 0;

                    // insertar en todos los idiomas
                    foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {

                        # primer lenguage que llega se crea el post con el id del xml
                        if ($counter_language == 0) {
                            $post = $this->create_post($id, $text_title, $description = "<p>Sin contenido</p>", "insert");
                            # otros lenguages se crean con el id 0
                        } else {
                            $post = $this->create_post(0, $text_title, $description = "<p>Sin contenido</p>", "new");
                        }// end if

                        # insert post and save into vector
                        $post_id = wp_insert_post($post, true);
                        //my_print($post_id);

                        $post_vector[$lang][$counter_property]["post_id"] = $post_id;
                        //my_print($post_vector);

                        # save language
                        pll_set_post_language($post_vector[$lang][$counter_property]["post_id"], $lang);

                        if ($lang == "es")
                            $my_post_es = $post_id;

                        $counter_language++;
                        $counter_property_create++;

                        //echo "cotnador_create: ".$counter_property_create."<br>";

                        $this->guardar_caracteristicas($taxonomy_name, $post_id, $avantio_credentials, $accommodation, $lang);

                    }// end foreach actived languages


                }// end if get_post_status

                $counter_property++;

            }// end if counter 20

        }// end foreach acommodations


        for($i = 0; $i < $counter_property; $i++) {
            $vector_plantilla = array();
            for($j = 0; $j < count($avantio_credentials['ACTIVED_LANGUAGES']); $j++) {
                if($post_vector[$lang][$i]["post_id"]){
                    $lang = $avantio_credentials['ACTIVED_LANGUAGES'][$j];
                    $vector_plantilla[$lang] = $post_vector[$lang][$i]["post_id"];
                }
            }// end for
            # relationship languages between posts
            // pll_save_post_translations(array('en' => $post_id_en, 'es' => $post_id_es));
            //my_print($vector_plantilla);
            pll_save_post_translations($vector_plantilla);
        }// end for


    }// end function insertAcommodations



    private function guardar_caracteristicas($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # property_category
        $this->guardarPropertyCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # property_action_category
        $this->guardarPropertyActionCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # property_status
        $this->guardarPropertyStatus($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # geographic areas
        $this->guardarGeo($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # general details
        $this->guardarGeneralDetails($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

        # feature
        $this->guardarFeatures($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang);

    }// end function



    private function guardarPropertyCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # villa de lujo or other
        if ($this->tipo_de_villa == "villa_de_lujo") {
            $name = "Villa de lujo";
            $name = $this->getTipoDeVillaLanguages($lang);
        } else {
            $name = $taxonomy_name;
        }// end if

        # property_category
        $name = sanitize_title($taxonomy_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_category', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_category', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_category', true );
        } // end foreach

    }



    private function guardarPropertyActionCategory($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {
        # property_action_category
        $name_kind_alquiler = sanitize_title($this->getKindAlquiler($lang));
        $my_cat = array(
            'description' => $name_kind_alquiler,
            'slug' => $name_kind_alquiler . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name_kind_alquiler, 'property_action_category', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_action_category', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name_kind_alquiler, 'property_action_category', true );
            //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_action_category', true );
        } // end foreach

    } // end function




    private function guardarPropertyStatus($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang){

        # property_status
        $name_status = sanitize_title($this->getStatus($lang));
        $my_cat = array(
            'description' => $name_status,
            'slug' => $name_status . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name_status, 'property_status', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_status', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name_status, 'property_status', true );
            //wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_status', true );
        } // end foreach

    }



    private function guardarGeo($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        # gegraphic area
        $dynamic_geocountry = intval($accommodation->LocalizationData->Country->CountryCode);
        $dynamic_geocountry_name = (string)$accommodation->LocalizationData->Country->Name;
        $dynamic_georegion = intval($accommodation->LocalizationData->Region->RegionCode);
        $dynamic_georegion_name = (string)$accommodation->LocalizationData->Region->Name;
        $dynamic_geocity = intval($accommodation->LocalizationData->City->CityCode);
        $dynamic_geocity_name = (string)$accommodation->LocalizationData->City->Name;
        $dynamic_geolocality = intval($accommodation->LocalizationData->Locality->LocalityCode);
        $dynamic_geolocality_name = (string)$accommodation->LocalizationData->Locality->Name;
        $dynamic_geodistrict = intval($accommodation->LocalizationData->District->DistrictCode);
        $dynamic_geodistrict_name = (string)$accommodation->LocalizationData->District->Name;

        # georegion
        $name = sanitize_title($dynamic_georegion_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_city', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        $my_parent = array();
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_city', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true );
            $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        } // end foreach
        # geocity
        $name = sanitize_title($dynamic_geocity_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => $my_parent[$lang]
        );
        $my_term_id = term_exists($name, 'property_city', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_city', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_city', true);
        } // end foreach
        # geolocality
        $name = sanitize_title($dynamic_geolocality_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" . $lang,
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_area', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        $my_parent = array();
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_area', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true );
            $my_parent[$lang] = (int)$my_term_id_translation[$lang];
        } // end foreach
        # geodistrict
        $name = sanitize_title($dynamic_geodistrict_name);
        $my_cat = array(
            'description' => $name,
            'slug' => $name . "-" .$lang,
            'parent' => $my_parent[$lang]
        );
        $my_term_id = term_exists($name, 'property_area', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], array($my_parent[$lang],(int)$my_term_id_translation[$lang]), 'property_area', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_area', true);
        } // end foreach


    } // end function




    private function guardarGeneralDetails($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        $dynamic_georegion = intval($accommodation->LocalizationData->Region->RegionCode);

        # country list
        //$countries = wpestate_country_list_only_array();
        # data
        # calle piso bloque
        $text_geo_tipo_calle = (string)$accommodation->LocalizationData->KindOfWay;
        $text_geo_calle = (string)$accommodation->LocalizationData->Way;
        $text_geo_numero = (string)$accommodation->LocalizationData->Number;
        $text_geo_bloque = (string)$accommodation->LocalizationData->Block;
        $text_geo_puerta = (string)$accommodation->LocalizationData->Door;
        $text_geo_piso = (string)$accommodation->LocalizationData->Floor;
        $text_geo_cp = intval($accommodation->LocalizationData->District->PostalCode);
        $formato_calle = $text_geo_tipo_calle . " " . $text_geo_calle . " " . " " . $text_geo_numero . " " . $text_geo_bloque . " " . $text_geo_puerta . " " . $text_geo_piso;

        # save_data
        update_post_meta($id, 'guest_no', intval($accommodation->Features->Distribution->PeopleCapacity), $prev_value = '');
        update_post_meta($id, 'property_address', $formato_calle, $prev_value = '');
        update_post_meta($id, 'property_county', $dynamic_georegion, $prev_value = '');
        update_post_meta($id, 'property_state', "nuevo", $prev_value = '');
        update_post_meta($id, 'property_zip', $text_geo_cp, $prev_value = '');
        update_post_meta($id, 'property_country', "Spain", $prev_value = '');
        update_post_meta($id, 'prop_featured', 1, $prev_value = '');
        update_post_meta($id, 'property_affiliate', "http://davidberruezo.com", $prev_value = '');
        update_post_meta($id, 'private_notes', "Mis notas privadas", $prev_value = '');
        update_post_meta($id, 'instant_booking', 1, $prev_value = '');
        # precio de la propiedad
        update_post_meta($id, 'property_price', 50, $prev_value = '');
        update_post_meta($id, 'property_price_before_label', "0", $prev_value = '');
        update_post_meta($id, 'property_price_after_label', "0", $prev_value = '');
        update_post_meta($id, 'property_taxes', "21", $prev_value = '');
        update_post_meta($id, 'property_price_per_week', "0", $prev_value = '');
        update_post_meta($id, 'property_price_per_month', "0", $prev_value = '');
        update_post_meta($id, 'price_per_weekeend', "0", $prev_value = '');
        update_post_meta($id, 'cleaning_fee', "0", $prev_value = '');
        update_post_meta($id, 'cleaning_fee_per_day', 1, $prev_value = '');
        update_post_meta($id, 'city_fee', 0, $prev_value = '');
        update_post_meta($id, 'city_fee_per_day', 2, $prev_value = '');
        update_post_meta($id, 'min_days_booking', 3, $prev_value = '');
        update_post_meta($id, 'security_deposit', "si", $prev_value = '');
        update_post_meta($id, 'early_bird_percent', 10, $prev_value = '');
        update_post_meta($id, 'early_bird_days', 0, $prev_value = '');
        update_post_meta($id, 'extra_price_per_guest', 2, $prev_value = '');
        update_post_meta($id, 'overload_guest', 1, $prev_value = '');
        update_post_meta($id, 'price_per_guest_from_one', 1, $prev_value = '');
        update_post_meta($id, 'checkin_change_over', 7, $prev_value = '');
        update_post_meta($id, 'checkin_checkout_change_over', 7, $prev_value = '');
        # propiedad media
        update_post_meta($id, 'embed_video_type', 1, $prev_value = '');
        update_post_meta($id, 'embed_video_id', "mAtkPQO1FcA", $prev_value = '');
        update_post_meta($id, 'virtual_tour', "codigo tour virtual", $prev_value = '');
        # detalles_especificos
        update_post_meta($id, 'property_size', intval($accommodation->Features->Distribution->AreaHousing->Area), $prev_value = '');
        update_post_meta($id, 'property_size_parcela', intval($accommodation->Features->Distribution->AreaHousing->Area), $prev_value = '');
        update_post_meta($id, 'property_rooms', intval($accommodation->Features->Distribution->Bedrooms), $prev_value = '');
        update_post_meta($id, 'property_bedrooms', intval($accommodation->Features->Distribution->Bedrooms), $prev_value = '');
        update_post_meta($id, 'property_bathrooms', intval($accommodation->Features->Distribution->Toilets), $prev_value = '');
        update_post_meta($id, 'cancellation_policy', "texto de política de cancelación", $prev_value = '');
        update_post_meta($id, 'other_rules', "otras reglas", $prev_value = '');
        # caracteristicas adicionales
        $fumadores = ((string)$accommodation->Features->HouseCharacteristics->SmokingAllowed == 'true') ? "yes" : "no";
        $jovenes_fiestas = ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? "yes" : "no";
        $text_numero_registro_turistico = (string)$accommodation->TouristicRegistrationNumber;
        update_post_meta($id, 'smoking_allowed', $fumadores, $prev_value = '');
        update_post_meta($id, 'party_allowed', $jovenes_fiestas, $prev_value = '');
        update_post_meta($id, 'pets_allowed', "yes", $prev_value = '');
        update_post_meta($id, 'registro_turistico', "$text_numero_registro_turistico", $prev_value = '');

        update_post_meta($id, 'children_allowed', "yes", $prev_value = '');
        update_post_meta($id, 'property_bathrooms_banera', intval($accommodation->Features->Distribution->BathroomWithBathtub), $prev_value = '');
        update_post_meta($id, 'property_bathrooms_ducha', intval($accommodation->Features->Distribution->BathroomWithBathtub), $prev_value = '');
        update_post_meta($id, 'property_aseos', intval($accommodation->Features->Distribution->Toilets), $prev_value = '');
        # mapa
        # geo latitud longitud
        update_post_meta($id, 'property_latitude', (string)$accommodation->LocalizationData->GoogleMaps->Latitude, $prev_value = '');
        update_post_meta($id, 'property_longitude', (string)$accommodation->LocalizationData->GoogleMaps->Longitude, $prev_value = '');

        /*
        Tamaño propiedad    property_size
        Tamaño parcela      property_size_parcela
        Numero dormitorios  property_bedrooms
        Baño con bañera     property_bathrooms_banera
        Baño con ducha      property_bathrooms_ducha
        Numero aseos        property_aseos
        */

        # baños
        $number_banyos_banyera = intval($accommodation->Features->Distribution->BathroomWithBathtub);
        $number_banyos_ducha = intval($accommodation->Features->Distribution->BathroomWithShower);
        $number_aseos = intval($accommodation->Features->Distribution->Toilets);
        $number_unidades = intval($accommodation->AccommodationUnits);
        $number_habitaciones = intval($accommodation->Features->Distribution->Bedrooms);
        $number_camas_doble = intval($accommodation->Features->Distribution->DoubleBeds);
        $number_camas_individual = intval($accommodation->Features->Distribution->IndividualBeds);
        $number_sofas_cama = intval($accommodation->Features->Distribution->IndividualSofaBed);
        $number_sofas_cama_doble = intval($accommodation->Features->Distribution->DoubleSofaBed);
        $number_literas = intval($accommodation->Features->Distribution->Berths);
        $number_fun = intval($accommodation->Features->HouseCharacteristics->NumOfFans);
        $number_cocinas = intval($accommodation->Features->HouseCharacteristics->Kitchen->NumberOfKitchens);


    } // end function


    private function guardarFeatures($taxonomy_name, $id, $avantio_credentials , $accommodation , $lang)
    {

        # create plantilla  and get all id languages and get es
        $plantilla = range(0, 38);
        $my_post_translation = pll_get_post_translations($id);

        $this->textosFeatureXml("es");
        ((string)$accommodation->Features->HouseCharacteristics->Sauna == 'true') ? $plantilla[0] = 1 : $plantilla[0] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Jacuzzi == 'true') ? $plantilla[1] = 1 : $plantilla[1] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->HairDryer == 'true') ? $plantilla[2] = 1 : $plantilla[2] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Elevator == 'true') ? $plantilla[3] = 1 : $plantilla[3] = 0;
        ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? $plantilla[4] = 1 : $plantilla[4] = 0;
        isset($accommodation->Features->HouseCharacteristics->SwimmingPool) ? $plantilla[5] = 1 : $plantilla[5] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->TV == 'true') ? $plantilla[6] = 1 : $plantilla[6] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Garden == 'true') ? $plantilla[7] = 1 : $plantilla[7] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->GardenFurniture == 'true') ? $plantilla[8] = 1 : $plantilla[8] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Iron == 'true') ? $plantilla[9] = 1 : $plantilla[9] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->FirePlace == 'true') ? $plantilla[10] = 1 : $plantilla[10] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Barbacue == 'true') ? $plantilla[11] = 1 : $plantilla[11] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Radio == 'true') ? $plantilla[12] = 1 : $plantilla[12] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->MiniBar == 'true') ? $plantilla[13] = 1 : $plantilla[13] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Terrace == 'true') ? $plantilla[14] = 1 : $plantilla[14] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->FencedPlot == 'true') ? $plantilla[15] = 1 : $plantilla[15] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $plantilla[16] = 1 : $plantilla[16] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $plantilla[17] = 1 : $plantilla[17] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Balcony == 'true') ? $plantilla[18] = 1 : $plantilla[18] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->JuiceSqueazer == 'true') ? $plantilla[19] = 1 : $plantilla[19] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->ElectricKettle == 'true') ? $plantilla[20] = 1 : $plantilla[20] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->ChildrenArea == 'true') ? $plantilla[21] = 1 : $plantilla[21] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Gym == 'true') ? $plantilla[22] = 1 : $plantilla[22] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Alarm == 'true') ? $plantilla[23] = 1 : $plantilla[23] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Tennis == 'true') ? $plantilla[24] = 1 : $plantilla[24] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Squash == 'true') ? $plantilla[25] = 1 : $plantilla[25] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Paddel == 'true') ? $plantilla[26] = 1 : $plantilla[26] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->HandicappedFacilities == 'apta-discapacitados') ? $plantilla[27] = 1 : $plantilla[27] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Fridge == 'true') ? $plantilla[28] = 1 : $plantilla[28] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Freezer == 'true') ? $plantilla[29] = 1 : $plantilla[29] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dishwasher == 'true') ? $plantilla[30] = 1 : $plantilla[30] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->WashingMachine == 'true') ? $plantilla[31] = 1 : $plantilla[31] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dryer == 'true') ? $plantilla[32] = 1 : $plantilla[32] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->CoffeeMachine == 'true') ? $plantilla[33] = 1 : $plantilla[33] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Toaster == 'true') ? $plantilla[34] = 1 : $plantilla[34] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Microwave == 'true') ? $plantilla[35] = 1 : $plantilla[35] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Oven == 'true') ? $plantilla[36] = 1 : $plantilla[36] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->TableWare == 'true') ? $plantilla[37] = 1 : $plantilla[37] = 0;
        ((string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenUtensils == 'true') ? $plantilla[38] = 1 : $plantilla[38] = 0;

        $feature_names = $this->textosFeature("es");
        //my_print($feature_names);

        $vector_features_seleccionados = array();
        foreach($plantilla as $key => $valor){
            if($valor == 1){
                array_push($vector_features_seleccionados , $feature_names[$key]);
            }
        }

        //my_print($vector_features_seleccionados);
        foreach ($vector_features_seleccionados as $feature) {
            $this->insertFeatureValue($feature,$id,$avantio_credentials);
        } // end foreach


    } // end function




    /**
     * @return string
     */
    public function getStatus($lang)
    {
        return $this->status[$lang];
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getKindAlquiler($lang)
    {
        return $this->kindAlquiler[$lang];
    }

    /**
     * @param string $kindAlquiler
     */
    public function setKindAlquiler($kindAlquiler)
    {
        $this->kindAlquiler = $kindAlquiler;
    }



    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }


    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }


    /**
     * @return array
     */
    public function getTipoDeVillaLanguages($lang)
    {
        return $this->tipo_de_villa_languages[$lang];
    }


    /**
     * @param array $tipo_de_villa_languages
     */
    public function setTipoDeVillaLanguages($tipo_de_villa_languages)
    {
        $this->tipo_de_villa_languages = $tipo_de_villa_languages;
    }


    private function create_post_type_estate_property()
    {
        $slug = 'properties';

        register_post_type('estate_property', array(
                'labels' => array(
                    'name' => esc_html__('Listings', 'wprentals-core'),
                    'singular_name' => esc_html__('Listing', 'wprentals-core'),
                    'add_new' => esc_html__('Add New Listing', 'wprentals-core'),
                    'add_new_item' => esc_html__('Add Listing', 'wprentals-core'),
                    'edit' => esc_html__('Edit', 'wprentals-core'),
                    'edit_item' => esc_html__('Edit Listings', 'wprentals-core'),
                    'new_item' => esc_html__('New Listing', 'wprentals-core'),
                    'view' => esc_html__('View', 'wprentals-core'),
                    'view_item' => esc_html__('View Listings', 'wprentals-core'),
                    'search_items' => esc_html__('Search Listings', 'wprentals-core'),
                    'not_found' => esc_html__('No Listings found', 'wprentals-core'),
                    'not_found_in_trash' => esc_html__('No Listings found in Trash', 'wprentals-core'),
                    'parent' => esc_html__('Parent Listings', 'wprentals-core')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug),
                'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
                'can_export' => true,
                'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
                'menu_icon' => WPESTATE_PLUGIN_DIR_URL . '/img/properties.png'
            )
        );

    } // end function



    private function insert_estate_property()
    {

        # insert NEW post | wp_insert_post($post);

        # es
        $title_es  =   'Villa última es';
        $description_es = 'Descripcion villa última es';
        $id = 0;

        $post = $this->create_post($id, $title_es , $description_es , "new");
        $post_id_es =  wp_insert_post($post , true);
        pll_set_post_language($post_id_es, "es");

        # en
        $title_en  =   'Villa última en';
        $description_en = 'Descripcion villa última en';

        $post = $this->create_post($id, $title_en , $description_en , "new");
        $post_id_en =  wp_insert_post($post, true);
        pll_set_post_language($post_id_en, "en");

        # relationship languages between posts
        pll_save_post_translations(array('en' => $post_id_en, 'es' => $post_id_es));


    }



    private function update_estate_property()
    {

        # insert post | wp_insert_post($post);

        # es
        $title_es  =   'Villa estate es';
        $description_es = 'Descripcion villa estate es';
        $id = 370;

        $post = $this->create_post($id, $title_es , $description_es , "update");
        $post_id_es = wp_update_post( $post , true);
        pll_set_post_language($post_id_es, "es");

        # en
        $title_en  =   'Villa estate en';
        $description_en = 'Descripcion villa estate en';
        $id = 371;

        $post = $this->create_post($id,$title_en , $description_en , "update");
        $post_id_en =  wp_update_post( $post , true );
        pll_set_post_language($post_id_en, "en");

        # relationship languages between posts
        pll_save_post_translations(array('en' => $post_id_en, 'es' => $post_id_es));


    }



    private function create_post($id, $title , $description , $action){

        $current_user  =   wp_get_current_user();
        $new_user_id   = $current_user->ID;
        $new_status = 'publish';
        $post = "";

        if($action == "insert"){

            $post = array(
                'import_id'    => $id,
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );

        }else if($action == "update" || $action == "test" || $action == "new"){

            $post = array(
                'ID'            => $id,
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );
        }else if($action == "nada"){

            $post = array(
                'post_title'	=> sanitize_title($title),
                'post_status'	=> $new_status,
                'post_type'     => 'estate_property',
                'post_author'   => $new_user_id ,
                'post_content'  => $description
            );

        }// end if

        //my_print($post);
        return $post;
    }


    private function textosFeature($language){
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


    private function textosFeatureXmlPlantilla()
    {
        $plantilla = array(
            "sauna" => "",
            "jacuzzi" => "",
            "secador de pelo" => "",
            "ascensor" => "",
            "grupos de personas" => "",
            "piscina" => "",
            "tv" => "",
            "jardin" => "",
            "muebles de jardin" => "",
            "plancha" => "",
            "chimenea" => "",
            "barbacoa" => "",
            "radio" => "",
            "minibar" => "",
            "terraza" => "",
            "parcela vallada" => "",
            "caja de seguridad" => "",
            "dvd" => "",
            "balcon" => "",
            "exprimidor" => "",
            "hervidor electrico" => "",
            "zona para niños" => "",
            "gimnasio" => "",
            "alarma" => "",
            "tennis" => "",
            "squash" => "",
            "paddel" => "" ,
            "apta para discapacitados" => "",
            "nevera" => "",
            "congelador" => "",
            "lavavajillas" => "",
            "lavadora" => "",
            "secadora" => "",
            "cafetera" => "",
            "tostadora" => "",
            "microondas" => "",
            "horno" => "",
            "vajilla" => "",
            "utensilios de cocina" => "",
        );


    } // end function


    private function textosFeatureXml($language){
        $textos = array(
            "es" => array(
                "sauna" => "",
                "jacuzzi" => "",
                "secador de pelo" => "",
                "ascensor" => "",
                "grupos de personas" => "",
                "piscina" => "",
                "tv" => "",
                "jardin" => "",
                "muebles de jardin" => "",
                "plancha" => "",
                "chimenea" => "",
                "barbacoa" => "",
                "radio" => "",
                "minibar" => "",
                "terraza" => "",
                "parcela vallada" => "",
                "caja de seguridad" => "",
                "dvd" => "",
                "balcon" => "",
                "exprimidor" => "",
                "hervidor electrico" => "",
                "zona para niños" => "",
                "gimnasio" => "",
                "alarma" => "",
                "tennis" => "",
                "squash" => "",
                "paddel" => "" ,
                "apta para discapacitados" => "",
                "nevera" => "",
                "congelador" => "",
                "lavavajillas" => "",
                "lavadora" => "",
                "secadora" => "",
                "cafetera" => "",
                "tostadora" => "",
                "microondas" => "",
                "horno" => "",
                "vajilla" => "",
                "utensilios de cocina" => "",
            ),
            "en" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "hair dryer"=> "" ,
                "lift"=> "" ,
                "Groups of people"=> "" ,
                "pool"=> "" ,
                "TV"=> "" ,
                "yard"=> "" ,
                "garden furniture"=> "" ,
                "iron"=> "" ,
                "fireplace"=> "" ,
                "barbecue"=> "" ,
                "radio"=> "" ,
                "minibar"=> "" ,
                "Terrace"=> "" ,
                "fenced plot"=> "" ,
                "safe deposit box"=> "" ,
                "DVD"=> "" ,
                "balcony"=> "" ,
                "squeezer"=> "" ,
                "electric kettle"=> "" ,
                "children's area"=> "" ,
                "Gym"=> "" ,
                "alarm"=> "" ,
                "tennis"=> "" ,
                "squash"=> "" ,
                "paddle"=> "" ,
                "suitable for the disabled"=> "" ,
                "fridge"=> "" ,
                "freezer"=> "" ,
                "dishwasher"=> "" ,
                "washing machine"=> "" ,
                "drying machine"=> "" ,
                "coffee maker"=> "" ,
                "toaster"=> "" ,
                "microwave"=> "" ,
                "kiln"=> "" ,
                "crockery"=> "" ,
                "Cookware"=> "" ,
            ),
            "fr" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "sèche-cheveux"=> "" ,
                "ascenseur"=> "" ,
                "Des groupes de personnes"=> "" ,
                "bassin"=> "" ,
                "LA TÉLÉ"=> "" ,
                "Cour"=> "" ,
                "mobilier de jardin"=> "" ,
                "fer à repasser"=> "" ,
                "cheminée"=> "" ,
                "barbecue"=> "" ,
                "radio"=> "" ,
                "mini-bar"=> "" ,
                "Terrasse"=> "" ,
                "terrain clôturé"=> "" ,
                "coffre-fort"=> "" ,
                "DVD"=> "" ,
                "balcon"=> "" ,
                "presse-agrumes"=> "" ,
                "bouilloire électrique"=> "" ,
                "espace enfants"=> "" ,
                "Gym"=> "" ,
                "alarme"=> "" ,
                "tennis"=> "" ,
                "écraser"=> "" ,
                "pagayer"=> "" ,
                "adapté aux personnes handicapées"=> "" ,
                "réfrigérateur"=> "" ,
                "congélateur"=> "" ,
                "lave-vaisselle"=> "" ,
                "Machine à laver"=> "" ,
                "Sèche-linge"=> "" ,
                "machine à café"=> "" ,
                "grille-pain"=> "" ,
                "four micro onde"=> "" ,
                "four"=> "" ,
                "vaisselle"=> "" ,
                "ustensiles de cuisine"=> "" ,
            ),
            "ca" => array(
                "sauna"=> "" ,
                "jacuzzi"=> "" ,
                "assecador de cabell"=> "" ,
                "ascensor"=> "" ,
                "grups de persones"=> "" ,
                "piscina"=> "" ,
                "tv"=> "" ,
                "jardí"=> "" ,
                "mobles de jardí"=> "" ,
                "planxa"=> "" ,
                "xemeneia"=> "" ,
                "barbacoa"=> "" ,
                "ràdio"=> "" ,
                "minibar"=> "" ,
                "terrassa"=> "" ,
                "parcel·la tancada"=> "" ,
                "caixa de seguretat"=> "" ,
                "dvd"=> "" ,
                "balcó"=> "" ,
                "espremedora"=> "" ,
                "bullidor elèctric"=> "" ,
                "zona per a nens"=> "" ,
                "gimnàs"=> "" ,
                "alarma"=> "" ,
                "tennis"=> "" ,
                "esquaix"=> "" ,
                "pàdel"=> "" ,
                "apta per a discapacitats"=> "" ,
                "nevera"=> "" ,
                "congelador"=> "" ,
                "rentavaixelles"=> "" ,
                "rentadora"=> "" ,
                "assecadora"=> "" ,
                "cafetera"=> "" ,
                "torradora"=> "" ,
                "microones"=> "" ,
                "forn"=> "" ,
                "vaixella"=> "" ,
                "estris de cuina"=> "" ,
            )

        );
        return $textos[$language];
    }



    private function insertFeatureValue($name,$id,$avantio_credentials)
    {


        $lang = "es";

        $name_total_service = $name . "-" . $lang;

        # feature
        //$name = sanitize_title($name);
        $my_cat = array(
            'description' => $name,
            'slug' => sanitize_title($name_total_service),
            'parent' => 0
        );
        $my_term_id = term_exists($name, 'property_features', $my_cat);
        $my_term_id_translation = pll_get_term_translations($my_term_id["term_id"]);
        $my_post_translation = pll_get_post_translations($id);

        //my_print($my_term_id_translation);
        //my_print($my_post_translation);

        # lang
        foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {
            # lang
            wp_set_object_terms( (int)$my_post_translation[$lang], (int)$my_term_id_translation[$lang], 'property_features', true);
            wp_set_post_terms((int)$my_post_translation[$lang], $name, 'property_features', true);
        } // end foreach

    } // end function


    private function codigoDescatalogado()
    {

        /*
       # features
       foreach ($avantio_credentials['ACTIVED_LANGUAGES'] as $lang) {

           $features_values = $this->textosFeatureXml($lang);

           # features wordpress
           ((string)$accommodation->Features->HouseCharacteristics->Sauna == 'true') ? $features_values["sauna"] = 1 : $features_values["sauna"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Sauna == 'true') ? $plantilla[0] = 1 : $plantilla[0] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Jacuzzi == 'true') ? $features_values["jacuzzi"] = 1 : $features_values["jacuzzi"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Jacuzzi == 'true') ? $plantilla[1] = 1 : $plantilla[1] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->HairDryer == 'true') ? $features_values["secador de pelo"] = 1 : $features_values["secador de pelo"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->HairDryer == 'true') ? $plantilla[2] = 1 : $plantilla[2] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Elevator == 'true') ? $features_values["ascensor"] =  1 : $features_values["secador de pelo"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Elevator == 'true') ? $plantilla[3] = 1 : $plantilla[3] = 0;

           ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? $features_values["grupos de personas"] = 1 : $features_values["grupos de personas"] = 0;
           ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? $plantilla[4] = 1 : $plantilla[4] = 0;

           isset($accommodation->Features->HouseCharacteristics->SwimmingPool) ? $features_values["piscina"] = 1 : $features_values["piscina"] = 0;
           isset($accommodation->Features->HouseCharacteristics->SwimmingPool) ? $plantilla[5] = 1 : $plantilla[5] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->TV == 'true') ? $features_values["tv"] = 1 : $features_values["tv"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->TV == 'true') ? $plantilla[6] = 1 : $plantilla[6] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Garden == 'true') ? $features_values["jardin"] = 1 : $features_values["jardin"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Garden == 'true') ? $plantilla[7] = 1 : $plantilla[7] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->GardenFurniture == 'true') ? $features_values["muebles de jardin"] = 1 : $features_values["muebles de jardin"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->GardenFurniture == 'true') ? $plantilla[8] = 1 : $plantilla[8] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Iron == 'true') ? $features_values["plancha"] = 1 : $features_values["plancha"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Iron == 'true') ? $plantilla[9] = 1 : $plantilla[9] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->FirePlace == 'true') ? $features_values["chimenea"] = 1 : $features_values["chimenea"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->FirePlace == 'true') ? $plantilla[10] = 1 : $plantilla[10] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Barbacue == 'true') ? $features_values["barbacoa"] = 1 : $features_values["barbacoa"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Barbacue == 'true') ? $plantilla[11] = 1 : $plantilla[11] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Radio == 'true') ? $features_values["radio"] = 1 : $features_values["radio"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Radio == 'true') ? $plantilla[12] = 1 : $plantilla[12] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->MiniBar == 'true') ? $features_values["minibar"] = 1 : $features_values["minibar"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->MiniBar == 'true') ? $plantilla[13] = 1 : $plantilla[13] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Terrace == 'true') ? $features_values["terraza"] = 1 : $features_values["terraza"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Terrace == 'true') ? $plantilla[14] = 1 : $plantilla[14] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->FencedPlot == 'true') ? $features_values["parcela vallada"] = 1 : $features_values["parcela vallada"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->FencedPlot == 'true') ? $plantilla[15] = 1 : $plantilla[15] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $features_values["caja de seguridad"] = 1 : $features_values["caja de seguridad"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $plantilla[16] = 1 : $plantilla[16] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->DVD == 'true') ? $features_values["dvd"] = 1 : $features_values["dvd"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $plantilla[17] = 1 : $plantilla[17] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Balcony == 'true') ? $features_values["balcon"] = 1 : $features_values["balcon"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Balcony == 'true') ? $plantilla[18] = 1 : $plantilla[18] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->JuiceSqueazer == 'true') ? $features_values["exprimidor"] = 1 : $features_values["exprimidor"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->JuiceSqueazer == 'true') ? $plantilla[19] = 1 : $plantilla[19] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->ElectricKettle == 'true') ? $features_values["hervidor electrico"] = 1 : $features_values["hervidor electrico"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->ElectricKettle == 'true') ? $plantilla[20] = 1 : $plantilla[20] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->ChildrenArea == 'true') ? $features_values["zona para niños"] = 1 : $features_values["zona para niños"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->ChildrenArea == 'true') ? $plantilla[21] = 1 : $plantilla[21] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Gym == 'true') ? $features_values["gimnasio"] = 1 : $features_values["gimnasio"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Gym == 'true') ? $plantilla[22] = 1 : $plantilla[22] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Alarm == 'true') ? $features_values["alarma"] = 1 : $features_values["alarma"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Alarm == 'true') ? $plantilla[23] = 1 : $plantilla[23] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Tennis == 'true') ? $features_values["tennis"] = 1 : $features_values["tennis"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Tennis == 'true') ? $plantilla[24] = 1 : $plantilla[24] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Squash == 'true') ? $features_values["squash"] = 1 : $features_values["squash"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Squash == 'true') ? $plantilla[25] = 1 : $plantilla[25] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Paddel == 'true') ? $features_values["paddel"] = 1 : $features_values["paddel"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Paddel == 'true') ? $plantilla[26] = 1 : $plantilla[26] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->HandicappedFacilities == 'apta-discapacitados') ? $features_values["apta para discapacitados"] = 1 : $features_values["apta para discapacitados"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->HandicappedFacilities == 'apta-discapacitados') ? $plantilla[27] = 1 : $plantilla[27] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Fridge == 'true') ? $features_values["nevera"] = 1 : $features_values["nevera"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Fridge == 'true') ? $plantilla[28] = 1 : $plantilla[28] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Freezer == 'true') ? $features_values["congelador"] = 1 : $features_values["congelador"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Freezer == 'true') ? $plantilla[29] = 1 : $plantilla[29] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dishwasher == 'true') ? $features_values["lavavajillas"] = 1 : $features_values["lavavajillas"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dishwasher == 'true') ? $plantilla[30] = 1 : $plantilla[30] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->WashingMachine == 'true') ? $features_values["lavadora"] = 1 : $features_values["lavadora"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->WashingMachine == 'true') ? $plantilla[31] = 1 : $plantilla[31] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dryer == 'true') ? $features_values["secadora"] = 1 : $features_values["secadora"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dryer == 'true') ? $plantilla[32] = 1 : $plantilla[32] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->CoffeeMachine == 'true') ? $features_values["cafetera"] = 1 : $features_values["cafetera"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->CoffeeMachine == 'true') ? $plantilla[33] = 1 : $plantilla[33] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Toaster == 'true') ? $features_values["tostadora"] = 1 : $features_values["tostadora"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Toaster == 'true') ? $plantilla[34] = 1 : $plantilla[34] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Microwave == 'true') ? $features_values["microondas"] = 1 : $features_values["microondas"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Microwave == 'true') ? $plantilla[35] = 1 : $plantilla[35] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Oven == 'true') ? $features_values["horno"] = 1 : $features_values["horno"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Oven == 'true') ? $plantilla[36] = 1 : $plantilla[36] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->TableWare == 'true') ? $features_values["vajilla"] = 1 : $features_values["vajilla"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->TableWare == 'true') ? $plantilla[37] = 1 : $plantilla[37] = 0;

           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenUtensils == 'true') ? $features_values["utensilios de cocina"] = 1 : $features_values["utensilios de cocina"] = 0;
           ((string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenUtensils == 'true') ? $plantilla[38] = 1 : $plantilla[38] = 0;

           $feature_names = $this->textosFeature($lang);

           $vector_features_seleccionados = array();
           foreach($plantilla as $key => $valor){
               if($valor){
                   array_push($vector_features_seleccionados , $feature_names[$key]);
               }
           }

           $this->insertFeatureValue($vector_features_seleccionados,$id);

       }// end foreach language
       */

    } // end function



}// end class

?>