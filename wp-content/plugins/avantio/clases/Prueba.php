<?php

class Accomodation{


    # post_id
    private $post_id;

    # tipo de villa
    private $tipo_de_villa = "villa";

    # name of objects Nuevo and KindAlquiler
    private $status = "Nuevo";
    private $kindAlquiler = "Alquiler toda la Villa";



    public function insertAccomodations($xml,$avantio_credentials)
    {

        # connect to database
        $wpdb = Database::getInstance();

        $inserts = array();
        $anyo_actual = intval(date("Y"));
        $counter_property = 0;

        $xml = simplexml_load_file($xml);

        foreach($xml->Accommodation as $accommodation){

            //if($counter_property < 2) {

            # villa o villa de lujo
            $found_villa_de_lujo = false;
            if ($accommodation->Labels){
                foreach($accommodation->Labels->children()  as $mylabel) {
                    if (stripos($mylabel, "villa_de_lujo") !== false) {
                        //$this->tipo_de_villa = "villa_de_lujo";
                        $found_villa_de_lujo = true;
                    }// end if
                }// end foreach
            }// end if
            if (!$found_villa_de_lujo){
                //$this->tipo_de_villa = "villa";
            }// end if

            # normal fields
            $id = intval($accommodation->AccommodationId);
            //$text_title = (string)$accommodation->AccommodationName;
            $text_title = (string)$accommodation->AccommodationName;
            $text_referencia = $id;
            $text_userid = (string)$accommodation->UserId;
            $text_company = (string)$accommodation->Company;
            $number_companyid = intval($accommodation->CompanyId);
            $text_numero_registro_turistico = (string)$accommodation->TouristicRegistrationNumber;

            // sanitize_title($text_title);
            $new_status = 'publish';
            $current_user = wp_get_current_user();
            $new_user_id = $current_user->ID;

            $language = get_locale();

            if (get_post_status($id)) {
                echo "actualiza post<br>";
                # languages
                $language = "es";
                updatePost($id,$text_title,$language);
                /*
                $language = "es";
                updatePost($id,$text_title,$language);
                $language = "ca";
                updatePost($id,$text_title,$language);
                $language = "en";
                updatePost($id,$text_title,$language);
                $language = "fr";
                updatePost($id,$text_title,$language);
                */
            } else {
                echo "inserta post<br>";
                # languages
                $language = "es";
                insertPost($id,$text_title,$language);
                /*
                $language = "ca";
                insertPoset($id,$text_title,$language);
                $language = "en";
                insertPoset($id,$text_title,$language);
                $language = "fr";
                insertPoset($id,$text_title,$language);
                */
            }// end if


            # kind | taxonomy
            $dynamic_taxonomy = intval($accommodation->MasterKind->MasterKindCode);
            $dynamic_taxonomy_group = intval($accommodation->MasterKind->MasterKindCode);
            $taxonomy_name = (string)$accommodation->MasterKind->MasterKindName;

            # villa de lujo or other
            /*
            if ($this->tipo_de_villa == "villa_de_lujo"){
                $name = "Villa de lujo";
            }else{
                $name = $taxonomy_name;
            }// end if
            */
            $name = $taxonomy_name;
            $actions = array(
                $name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_category', $my_cat);
                $term = get_term_by( $my_term_id["term_id"], '', 'property_category', $output = OBJECT, $filter = 'raw' );
            }// end foreach
            //echo "identificador proerty category es: ". (int)$my_term_id["term_id"] . "<br>";
            # keep information
            //wp_set_object_terms( $this->post_id , "", 'property_category', false);
            wp_set_object_terms( $id , (int)$my_term_id["term_id"], 'property_category', true);
            wp_set_post_terms( $id, "villa", 'property_category', true );


            # what do you rent | kindAlquiler
            //$mking_name = (string)$this->getKindAlquiler();
            $mking_name = "";
            $actions = array(
                $mking_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_action_category', $my_cat);
                $term = get_term_by( $my_term_id["term_id"], '', 'property_action_category', $output = OBJECT, $filter = 'raw' );
            }// end foreach
            # keep information
            //echo "identificador proerty action category es: ". (int)$my_term_id["term_id"] . "<br>";
            //wp_set_object_terms( $this->post_id, "", 'property_action_category', false);
            wp_set_object_terms( $id, 145, 'property_action_category', true);
            //wp_set_object_terms( $id, 272, 'property_action_category', true);
            wp_set_post_terms($id, "alquiler toda la villa", 'property_action_category', true );

            # status
            # what do you rent | kindAlquiler
            //$mking_name = (string)$this->getStatus();
            $mking_name = "nuevo";
            $actions = array(
                $mking_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_status', $my_cat);
                $term = get_term_by( $my_term_id["term_id"], '', 'property_status', $output = OBJECT, $filter = 'raw' );
            }// end foreach
            # keep information
            //echo "identificador property_status es: ". (int)$my_term_id["term_id"] . "<br>";
            //wp_set_object_terms( $this->post_id, "", 'property_status', false);
            wp_set_object_terms( $id, (int)$my_term_id["term_id"], 'property_status', true);
            wp_set_post_terms( $id, "nuevo", 'property_status', true );


            # general details
            # country list
            $countries = wpestate_country_list_only_array();
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
            $actions = array(
                $dynamic_georegion_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_city', $my_cat);
            }// end foreach
            $my_parent = (int)$my_term_id["term_id"];
            //wp_set_object_terms( $id, "", 'property_city', false);
            //wp_set_object_terms( $id, (int)$my_term_id["term_id"], 'property_city', false);
            # geocity
            $actions = array(
                $dynamic_geocity_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => $my_parent
                );
                $my_term_id = term_exists($key, 'property_city', $my_cat);
            }// end foreach
            wp_set_object_terms( $id, "", 'property_city', false);
            wp_set_object_terms( $id, array($my_parent,(int)$my_term_id["term_id"]), 'property_city', false);
            # geolocality
            $actions = array(
                $dynamic_geolocality_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_area', $my_cat);
            }// end foreach
            $my_parent = (int)$my_term_id["term_id"];
            //wp_set_object_terms( $id, "", 'property_area', false);
            //wp_set_object_terms( $id, (int)$my_term_id["term_id"], 'property_area', false);
            # geodistrict
            $actions = array(
                $dynamic_geodistrict_name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_area', $my_cat);
            }// end foreach
            wp_set_object_terms( $id, "", 'property_area', false);
            wp_set_object_terms( $id, array($my_parent,(int)$my_term_id["term_id"]), 'property_area', false);


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
            update_post_meta($id, 'property_rooms', intval($accommodation->Features->Distribution->Bedrooms), $prev_value = '');
            update_post_meta($id, 'property_bedrooms', intval($accommodation->Features->Distribution->Bedrooms), $prev_value = '');
            update_post_meta($id, 'property_bathrooms', intval($accommodation->Features->Distribution->Toilets), $prev_value = '');
            update_post_meta($id, 'cancellation_policy', "texto de política de cancelación", $prev_value = '');
            update_post_meta($id, 'other_rules', "otras reglas", $prev_value = '');
            update_post_meta($id, 'smoking_allowed', "yes", $prev_value = '');
            update_post_meta($id, 'party_allowed', "yes", $prev_value = '');
            update_post_meta($id, 'pets_allowed', "yes", $prev_value = '');
            update_post_meta($id, 'children_allowed', "yes", $prev_value = '');
            # detalles_especificos | custom details
            $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');
            $i = 0;
            while ($i < count($custom_fields)) {
                $name = $custom_fields[$i][0];
                $slug = wpestate_limit45(sanitize_title($name));
                $slug = sanitize_key($slug);
                $meta = get_post_meta(35576, $slug, true);
                // echo $meta;
            }// end while
            update_post_meta($id, 'check-in-hour', "12:00", $prev_value = '');
            update_post_meta($id, 'check-out-hour', "12:00", $prev_value = '');
            update_post_meta($id, 'late-check-in', "12:00", $prev_value = '');
            update_post_meta($id, 'private-bathroom', "Yes", $prev_value = '');
            update_post_meta($id, 'private-entrance', "Yes", $prev_value = '');
            update_post_meta($id, 'optional-services', "masajes", $prev_value = '');
            update_post_meta($id, 'familyfriendly', "Yes", $prev_value = '');
            update_post_meta($id, 'outdoor-facilities', "jardin", $prev_value = '');
            update_post_meta($id, 'extra-people', "10", $prev_value = '');
            update_post_meta($id, 'cancellation', "si", $prev_value = '');
            # map_propiedad
            update_post_meta($id, 'property_latitude', (string)$accommodation->LocalizationData->GoogleMaps->Latitude, $prev_value = '');
            update_post_meta($id, 'property_longitude', (string)$accommodation->LocalizationData->GoogleMaps->Longitude, $prev_value = '');
            update_post_meta($id, 'google_camera_angle', "1", $prev_value = '');
            update_post_meta($id, 'page_custom_zoom', intval($accommodation->LocalizationData->GoogleMaps->Zoom), $prev_value = '');

            # avantio
            $avantio_occupation_rules=intval($accommodation->OccupationalRuleId);
            $avantio_pricemodifiers=intval($accommodation->PriceModifierId);
            $avantio_gallery=intval($accommodation->IdGallery);


            # calle piso bloque
            $text_geo_tipo_calle = (string)$accommodation->LocalizationData->KindOfWay;
            $text_geo_calle = (string)$accommodation->LocalizationData->Way;
            $text_geo_numero = (string)$accommodation->LocalizationData->Number;
            $text_geo_bloque = (string)$accommodation->LocalizationData->Block;
            $text_geo_puerta = (string)$accommodation->LocalizationData->Door;
            $text_geo_piso = (string)$accommodation->LocalizationData->Floor;
            $text_cocina_clase = (string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenClass;
            $text_cocina_tipo = (string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenType;
            # geo latitud longitud
            $text_geo_latitud = (string)$accommodation->LocalizationData->GoogleMaps->Latitude;
            $text_geo_longitud = (string)$accommodation->LocalizationData->GoogleMaps->Longitude;
            $number_geo_zoom = intval($accommodation->LocalizationData->GoogleMaps->Zoom);
            # distribución
            $number_metros_cuadrados_utiles = intval($accommodation->Features->Distribution->AreaHousing->Area);
            $number_metros_cuadrados = intval($accommodation->Features->Distribution->AreaPlot->Area);
            $number_capacidad_maxima = intval($accommodation->Features->Distribution->PeopleCapacity);
            $number_capacidad_minima = intval($accommodation->Features->Distribution->MinimumOccupation);
            $number_capacidad_sin_suplemento = intval($accommodation->Features->Distribution->OccupationWithoutSupplement);
            # doormitorios
            // crear nueva programación
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

            # creation featues
            $features_titulos = textosFeature("es");
            foreach($features_titulos as $feature_titulo){
                insertFeature($feature_titulo);
            }// end foreach

            # features xml
            $features_values = textosFeatureXml("es");

            # features wordpress
            ((string)$accommodation->Features->HouseCharacteristics->Sauna == 'true') ? $features_values["sauna"] = 1 : $features_values["sauna"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Jacuzzi == 'true') ? $features_values["jacuzzi"] = 1 : $features_values["jacuzzi"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->HairDryer == 'true') ? $features_values["secador de pelo"] = 1 : $features_values["secador de pelo"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Elevator == 'true') ? $features_values["ascensor"] =  1 : $features_values["secador de pelo"] = 0;
            ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? $features_values["grupos de personas"] = 1 : $features_values["grupos de personas"] = 0;
            isset($accommodation->Features->HouseCharacteristics->SwimmingPool) ? $features_values["piscina"] = 1 : $features_values["piscina"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->TV == 'true') ? $features_values["tv"] = 1 : $features_values["tv"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Garden == 'true') ? $features_values["jardin"] = 1 : $features_values["jardin"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->GardenFurniture == 'true') ? $features_values["muebles de jardin"] = 1 : $features_values["muebles de jardin"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Iron == 'true') ? $features_values["plancha"] = 1 : $features_values["plancha"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->FirePlace == 'true') ? $features_values["chimenea"] = 1 : $features_values["chimenea"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Barbacue == 'true') ? $features_values["barbacoa"] = 1 : $features_values["barbacoa"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Radio == 'true') ? $features_values["radio"] = 1 : $features_values["radio"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->MiniBar == 'true') ? $features_values["minibar"] = 1 : $features_values["minibar"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Terrace == 'true') ? $features_values["terraza"] = 1 : $features_values["terraza"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->FencedPlot == 'true') ? $features_values["parcela vallada"] = 1 : $features_values["parcela vallada"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? $features_values["caja de seguridad"] = 1 : $features_values["caja de seguridad"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->DVD == 'true') ? $features_values["dvd"] = 1 : $features_values["dvd"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Balcony == 'true') ? $features_values["balcon"] = 1 : $features_values["balcon"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->JuiceSqueazer == 'true') ? $features_values["exprimidor"] = 1 : $features_values["exprimidor"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->ElectricKettle == 'true') ? $features_values["hervidor electrico"] = 1 : $features_values["hervidor electrico"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->ChildrenArea == 'true') ? $features_values["zona para niños"] = 1 : $features_values["zona para niños"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Gym == 'true') ? $features_values["gimnasio"] = 1 : $features_values["gimnasio"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Alarm == 'true') ? $features_values["alarma"] = 1 : $features_values["alarma"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Tennis == 'true') ? $features_values["tennis"] = 1 : $features_values["tennis"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Squash == 'true') ? $features_values["squash"] = 1 : $features_values["squash"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Paddel == 'true') ? $features_values["paddel"] = 1 : $features_values["paddel"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->HandicappedFacilities == 'apta-discapacitados') ? $features_values["apta para discapacitados"] = 1 : $features_values["apta para discapacitados"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Fridge == 'true') ? $features_values["nevera"] = 1 : $features_values["nevera"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Freezer == 'true') ? $features_values["congelador"] = 1 : $features_values["congelador"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dishwasher == 'true') ? $features_values["lavavajillas"] = 1 : $features_values["lavavajillas"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->WashingMachine == 'true') ? $features_values["lavadora"] = 1 : $features_values["lavadora"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dryer == 'true') ? $features_values["secadora"] = 1 : $features_values["secadora"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->CoffeeMachine == 'true') ? $features_values["cafetera"] = 1 : $features_values["cafetaera"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Toaster == 'true') ? $features_values["tostadora"] = 1 : $features_values["tostadora"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Microwave == 'true') ? $features_values["microondas"] = 1 : $features_values["microondas"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Oven == 'true') ? $features_values["horno"] = 1 : $features_values["horno"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->TableWare == 'true') ? $features_values["vajilla"] = 1 : $features_values["vajilla"] = 0;
            ((string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenUtensils == 'true') ? $features_values["utensilios de cocina"] = 1 : $features_values["utensilios de cocina"] = 0;

            # write features values
            $vector_features_seleccionados = array();
            foreach ($features_values as $index => $feature_value) {
                if ($feature_value)
                    array_push($vector_features_seleccionados , $index);
            } // end foreach
            //my_print($vector_features_seleccionados);
            insertFeatureValue($vector_features_seleccionados,$post_id);

            # features checkbox
            $checkbox_sauna = ((string)$accommodation->Features->HouseCharacteristics->Sauna == 'true') ? 1 : 0;
            $checkbox_jacuzzi = ((string)$accommodation->Features->HouseCharacteristics->Jacuzzi == 'true') ? 1 : 0;
            $checkbox_secador_pelo = ((string)$accommodation->Features->HouseCharacteristics->HairDryer == 'true') ? 1 : 0;
            $checkbox_ascensor = ((string)$accommodation->Features->HouseCharacteristics->Elevator == 'true') ? 1 : 0;
            $checkbox_grups = ((string)$accommodation->Features->Distribution->AcceptYoungsters == 'true') ? 1 : 0;
            $checkbox_piscina = isset($accommodation->Features->HouseCharacteristics->SwimmingPool) ? 1 : 0;
            $checkbox_tv = ((string)$accommodation->Features->HouseCharacteristics->TV == 'true') ? 1 : 0;
            $checkbox_jardin = ((string)$accommodation->Features->HouseCharacteristics->Garden == 'true') ? 1 : 0;
            $checkbox_muebles_jardin = ((string)$accommodation->Features->HouseCharacteristics->GardenFurniture == 'true') ? 1 : 0;
            $checkbox_plancha = ((string)$accommodation->Features->HouseCharacteristics->Iron == 'true') ? 1 : 0;
            $checkbox_chimenea = ((string)$accommodation->Features->HouseCharacteristics->FirePlace == 'true') ? 1 : 0;
            $checkbox_barbacoa = ((string)$accommodation->Features->HouseCharacteristics->Barbacue == 'true') ? 1 : 0;
            $checkbox_radio = ((string)$accommodation->Features->HouseCharacteristics->Radio == 'true') ? 1 : 0;
            $checkbox_minibar = ((string)$accommodation->Features->HouseCharacteristics->MiniBar == 'true') ? 1 : 0;
            $checkbox_terraza = ((string)$accommodation->Features->HouseCharacteristics->Terrace == 'true') ? 1 : 0;
            $checkbox_parcela_vallada = ((string)$accommodation->Features->HouseCharacteristics->FencedPlot == 'true') ? 1 : 0;
            $checkbox_caja_seguridad = ((string)$accommodation->Features->HouseCharacteristics->SecurityBox == 'true') ? 1 : 0;
            $checkbox_dvd = ((string)$accommodation->Features->HouseCharacteristics->DVD == 'true') ? 1 : 0;
            $checkbox_balcon = ((string)$accommodation->Features->HouseCharacteristics->Balcony == 'true') ? 1 : 0;
            $checkbox_exprimidor = ((string)$accommodation->Features->HouseCharacteristics->JuiceSqueazer == 'true') ? 1 : 0;
            $checkbox_hervidor_electrico = ((string)$accommodation->Features->HouseCharacteristics->ElectricKettle == 'true') ? 1 : 0;
            $checkbox_zona_ninos = ((string)$accommodation->Features->HouseCharacteristics->ChildrenArea == 'true') ? 1 : 0;
            $checkbox_gimnasio = ((string)$accommodation->Features->HouseCharacteristics->Gym == 'true') ? 1 : 0;
            $checkbox_alarma = ((string)$accommodation->Features->HouseCharacteristics->Alarm == 'true') ? 1 : 0;
            $checkbox_tennis = ((string)$accommodation->Features->HouseCharacteristics->Tennis == 'true') ? 1 : 0;
            $checkbox_squash = ((string)$accommodation->Features->HouseCharacteristics->Squash == 'true') ? 1 : 0;
            $checkbox_paddel = ((string)$accommodation->Features->HouseCharacteristics->Paddel == 'true') ? 1 : 0;
            $checkbox_apta_discapacitados = ((string)$accommodation->Features->HouseCharacteristics->HandicappedFacilities == 'apta-discapacitados') ? 1 : 0;
            //$checkbox_fumadores=((string)$accommodation->Features->HouseCharacteristics->SmokingAllowed == 'true')?1:0;
            $checkbox_nevera = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Fridge == 'true') ? 1 : 0;
            $checkbox_congelador = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Freezer == 'true') ? 1 : 0;
            $checkbox_lavavajillas = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dishwasher == 'true') ? 1 : 0;
            $checkbox_lavadora = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->WashingMachine == 'true') ? 1 : 0;
            $checkbox_secadora = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Dryer == 'true') ? 1 : 0;
            $checkbox_cafetera = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->CoffeeMachine == 'true') ? 1 : 0;
            $checkbox_tostadora = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Toaster == 'true') ? 1 : 0;
            $checkbox_microondas = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Microwave == 'true') ? 1 : 0;
            $checkbox_horno = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->Oven == 'true') ? 1 : 0;
            $checkbox_vajilla = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->TableWare == 'true') ? 1 : 0;
            $checkbox_utensilios_cocina = ((string)$accommodation->Features->HouseCharacteristics->Kitchen->KitchenUtensils == 'true') ? 1 : 0;


            # tipo piscina
            $text_tipo_piscina = (string)$accommodation->Features->HouseCharacteristics->SwimmingPool->PoolType;
            $text_tipo_piscina = (strlen($text_tipo_piscina) == 0) ? 'comunitaria' : $text_tipo_piscina;
            $text_tipo_piscina = ($checkbox_piscina) ? $text_tipo_piscina : '';
            $text_dimensiones_piscina = isset($accommodation->Features->HouseCharacteristics->SwimmingPool->Dimensions) ? $accommodation->Features->HouseCharacteristics->SwimmingPool->Dimensions : "";


            //}// end if

            $counter_property++;

        }// end foreach accomodations

    } // end function



    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
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
    public function getKindAlquiler()
    {
        return $this->kindAlquiler;
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

        # insert post | wp_insert_post($post);

        # es
        $title_es  =   'Villa última es';
        $description_es = 'Descripcion villa última es';

        $post = create_post($title_es , $description_es);
        $post_id_es =  wp_insert_post($post);
        pll_set_post_language($post_id_es, "es");

        # en
        $title_en  =   'Villa última en';
        $description_en = 'Descripcion villa última en';

        $post = create_post($title_en , $description_en);
        $post_id_en =  wp_insert_post($post);
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

        $post = create_post($title_es , $description_es);
        $post["ID"] = 312;
        $post_id_es = wp_update_post( $post );
        pll_set_post_language($post_id_es, "es");

        # en
        $title_en  =   'Villa estate en';
        $description_en = 'Descripcion villa estate en';

        $post = create_post($title_en , $description_en);
        $post["ID"] = 313;
        $post_id_en =  wp_update_post( $post );
        pll_set_post_language($post_id_en, "en");

        # relationship languages between posts
        pll_save_post_translations(array('en' => $post_id_en, 'es' => $post_id_es));


    }



    private function create_post($title,$description){

        $current_user  =   wp_get_current_user();
        $new_user_id   = $current_user->ID;
        $new_status = 'publish';

        $post = array(
            'post_title'	=> $title,
            'post_status'	=> $new_status,
            'post_type'     => 'estate_property',
            'post_author'   => $new_user_id ,
            'post_content'  => $description
        );

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
            )
        );
        return $textos[$language];
    }


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
            )
        );
        return $textos[$language];
    }


    private function insertFeature($name_service)
    {
        $actions = array(
            $name_service
        );
        foreach ($actions as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' =>sanitize_title($key)
            );
            if(!term_exists($key, 'property_features', $my_cat) ){
                $return = wp_insert_term($key, 'property_features', $my_cat);
            }
        }
    } // end function


    private function insertFeatureValue($vector_names)
    {

        $vector_term_ids = array();

        foreach ($vector_names as $name) {
            # feature selected
            $actions = array(
                $name
            );
            foreach ($actions as $key) {
                $my_cat = array(
                    'description' => $key,
                    'slug' =>sanitize_title($key),
                    'parent' => 0
                );
                $my_term_id = term_exists($key, 'property_features', $my_cat);
                if ($my_term_id){
                    array_push($vector_term_ids,(int)$my_term_id["term_id"]);
                }
            }// end foreach
        } // end foreach
        wp_set_object_terms( $this->post_id, "", 'property_features', false);
        wp_set_object_terms( $this->post_id, $vector_term_ids, 'property_features', false);

    } // end function



    private function add_two_primary_keys()
    {

        $forge = \Config\Database::forge();
        $forge->addKey('id', TRUE);
        $forge->addKey('language', TRUE);

    } // end function



    private function insertar_actualizar()
    {

        $data = [
            'id'            => "",
            'language'      => "",
            'text_title'    => ""
        ];

        $this->save($data);

    } // end function



}// end class
?>