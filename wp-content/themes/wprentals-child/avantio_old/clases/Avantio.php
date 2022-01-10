<?phpclass Avantio{    // avantio    private $avantio_credentials;    private $avantio_files;    // Files Downloaded    private $files_downloaded      = array();    private $files_downloaded_temp = array();    // report    private $last_insert_id_report = 0;    // post_id    private $post_id = 0;    # Objects Nuevo and KindAlquiler    private $status = "";    private $kindAlquiler = "";    /**     * @return string     */    public function getStatus()    {        return $this->status;    }    /**     * @param string $status     */    public function setStatus($status)    {        $this->status = $status;    }    /**     * @return string     */    public function getKindAlquiler()    {        return $this->kindAlquiler;    }    /**     * @param string $kindAlquiler     */    public function setKindAlquiler($kindAlquiler)    {        $this->kindAlquiler = $kindAlquiler;    }    public function __construct($param = null)    {        //echo "parametro: " . $param;        $this->initialize($param);    } // end function    /**     * @param null $param     * @return string     */    /*    public function index($param = null)    {        echo "parametro: " . $param;        $this->initialize($param);        //return view('welcome_message');    }    */    /**     * @param null $param     */    private function initialize($param = null)    {        // default        $this->setAvantioCredentials(null, "production");        //$this->setAvantioCredentials(null, "test");        //$this->setAvantioFiles();        // download files        //$param = "all";        //$this->updateParameters($param);        //$this->deleteXmlFiles();        //$this->downloadXmlFiles();        // update        //$this->setFilesDownloadedTemp(null , "windows");        $this->update();    } // end function    /**     * @param string $tipo     */    private function updateParameters($tipo = "all")    {        if($tipo == 'all'){            foreach($this->avantio_files as $kind => $elementos){                foreach($elementos as $k => $data){                    $this->avantio_files[$kind][$k]['update'] = 1;                }            }        }elseif($tipo == 'static'){            foreach($this->avantio_files['static'] as $k => $data){                $this->avantio_files['static'][$k]['update']=1;            }        }elseif($tipo == 'dynamic'){            foreach($this->avantio_files['dynamic'] as $k => $data){                $this->avantio_files['dynamic'][$k]['update'] = 1;            }        }elseif($tipo == 'prices'){            foreach($this->avantio_files['prices'] as $k => $data){                $this->avantio_files['prices'][$k]['update'] = 1;            }        }elseif(isset($this->avantio_files['static'][$tipo])){            $this->avantio_files['static'][$tipo]['update']=1;        }elseif(isset($this->avantio_files['dynamic'][$tipo])){            $this->avantio_files['static'][$tipo]['update']=1;        }elseif(isset($this->avantio_files['prices'][$tipo])){            $this->avantio_files['static'][$tipo]['update']=1;        }        if($tipo=='view_xml')            $view_xml=true;        $this->write_report($tipo);    } // end function    private function deleteXmlFiles()    {        $ts_referencia = intval(date("YmdHis",time() - $this->avantio_credentials['DELETE_TIME']));        foreach(scandir(__DIR__.'/../xmldata/') as $file){            if(strpos($file,'.xml') === FALSE) continue;            list($name,$ts) = explode(".",$file);            //if($ts_referencia > intval($ts))            unlink(__DIR__.'/../xmldata/'.$file);        }    } // end function    private function downloadXmlFiles()    {        // Load class        $ch = new CurlRequest();        foreach($this->avantio_files as $tipo => $elementos){            foreach($elementos as $k => $data) {                if (!$data['update']) continue;                $url = $this->avantio_credentials['URL_FEEDS'] . $k . '/' . $this->avantio_credentials['PARTNER_CODE'];                echo "DOWNLOAD:" . $url . "\n";                $ts = date("YmdHis");                $fdata_zip = $ch->get($url);                if ($fdata_zip){                    $file_unzip = __DIR__ . '/../xmldata/' . $k . '.' . $ts . '.xml';                    $file_zip = $file_unzip . '.zip';                    $fw = fopen($file_zip, 'w');                    fwrite($fw, $fdata_zip);                    fclose($fw);                    $zip = new ZipArchive;                    if ($zip->open($file_zip) === true) {                        $filename = $zip->getNameIndex(0);                        //if (is_file($filename)){                        copy("zip://" . $file_zip . "#" . $filename, $file_unzip);                        $zip->close();                        unlink($file_zip);                        $this->files_downloaded[$tipo][$k] = $file_unzip;                        //}// end if                    }// end if                }// end if            }// end foreach        }// end foreach        //p_($this->files_downloaded);    } // end function    private function update()    {        # debug        $view_xml = false;        # read and save xml files        # path        $files_to_search = "c:\htdocs\brava_rentals\avantio_cron_cli\app\xmldata";        //$files_to_search = "/home/automocion/public_html/avantio_cron_cli/app/xmldata";        //p_($files_to_search);        $files = array();        # read scandir        $files_to_search_scan = scandir($files_to_search);        foreach($files_to_search_scan as $value)        {            //p_($value);            if($value === '.' || $value === '..') { continue; }            //echo "fichero: ".$value. "<br>";            if(is_file("$files_to_search/$value")) {                if (strpos($value,"accommodations") !== false){                    $files["accomodations"] = $files_to_search . "/" . $value;                }                if (strpos($value,"availabilities") !== false){                    $files["availabilities"] = $files_to_search . "/" . $value;                }                if (strpos($value,"descriptions") !== false){                    $files["descriptions"] = $files_to_search . "/" . $value;                }                if (strpos($value,"geographicareas") !== false){                    $files["geographicareas"] = $files_to_search . "/" . $value;                }                if (strpos($value,"kinds") !== false){                    $files["kinds"] = $files_to_search . "/" . $value;                }                if (strpos($value,"occupational") !== false){                    $files["ocuppationalrules"] = $files_to_search . "/" . $value;                }                if (strpos($value,"pricemodificers") !== false){                    $files["pricemodificers"] = $files_to_search . "/" . $value;                }                if (strpos($value,"rates") !== false){                    $files["rates"] = $files_to_search . "/" . $value;                }                if (strpos($value,"services") !== false){                    $files["services"] = $files_to_search . "/" . $value;                }            } // end if        } // end foreach        //p_($files);        /*        # KINDS        $xml = simplexml_load_file($files["kinds"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateKinds($xml);        # KINDSALQUILER (ACCIONES TIPOS DE ALQUILER)(WORDPRESS)        //$this->print_xml_avantio($view_xml,$xml);        $this->updateKindsAlquiler();        # STATUS (Estado de la propiedad)(WORDPRESS)        $this->updateStatus();        */        # ACCOMODATIONS        $xml = simplexml_load_file($files["accomodations"]);        //p_($xml);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateAccomodation($xml);        /*        # KINDS        $xml = simplexml_load_file($files["kinds"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateKinds($xml);        # KINDSALQUILER (ACCIONES TIPOS DE ALQUILER)(WORDPRESS)        //$this->print_xml_avantio($view_xml,$xml);        $this->updateKindsAlquiler();        # STATUS (Estado de la propiedad)(WORDPRESS)        $this->updateStatus();        # GEOGRAPHICAREAS        $xml = simplexml_load_file($files["geographicareas"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateGeographicAreas($xml);        # SERVICES        $xml = simplexml_load_file($files["services"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateServices($xml);        # GEOGRAPHICAREAS        $xml = simplexml_load_file($files["geographicareas"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateGeographicAreas($xml);        # ACCOMODATIONS        $xml = simplexml_load_file($files["accomodations"]);        //p_($xml);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateAccomodation($xml);        # DESCRIPTIONS # PICTURES        $xml = simplexml_load_file($files["descriptions"]);        //$this->print_xml_avantio($view_xml,$xml);        $this->updateDescription($xml);        $this->updatePicture($xml);        */        /*        $ts = time();        $view_xml = false;        $ts_referencia = intval(date("YmdHis",time() - $this->avantio_credentials['DELETE_TIME']));        # actived languages        $languages = new Language();        $actived_languages = $languages->getAll();        foreach($this->files_downloaded as $tipo => $elementos) {            foreach ($elementos as $k => $file) {                echo "PROCESS:" . $tipo . ">>" . $k . ">>" . $file . "\n";                $xml = simplexml_load_file($file);                switch ($k) {                    # SERVICES                    case 'services':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateServices($xml,$ts,$ts_referencia);                        break;                    # GEOGRAPHICAREAS                    case 'geographicareas':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateGeographicAreas($xml,$ts,$ts_referencia);                        break;                    # KINDS                    case 'kinds':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateKinds($xml,$ts,$ts_referencia);                        break;                    # OCCUPATION RULES                    case 'occupationalrules':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateOcupationRules($xml,$ts,$ts_referencia);                        break;                    # AVAILABILITIES                    case 'availabilities':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateAvalability($xml,$ts,$ts_referencia);                        break;                    # RATES                    case 'rates':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateRates($xml,$ts,$ts_referencia);                        break;                    # PRICEMODIFIERS                    case 'pricemodifiers':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updatePriceModify($xml,$ts,$ts_referencia);                        break;                    # ACCOMODATIONS                    case 'accommodations':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateAccomodation($xml,$ts,$ts_referencia,$actived_languages);                        break;                    # DESCRIPTIONS # PICTURES                    case 'descriptions':                        $this->print_xml_avantio($view_xml,$xml);                        $this->updateDescription($xml,$ts,$ts_referencia);                        $this->updatePicture($xml,$ts,$ts_referencia);                        break;                }// end switch            }// end foreach        }// end foreach        $this->delete_cache_files($server = "server");        */   } // end function    private function delete_cache_files($server = "local")    {        # get files        if($server == "local"){            $path = realpath(dirname(__FILE__) . "./../../cache") . '/';            $files = glob($path.'*'); // get all file names        }else if($server == "server"){            $path = "/home/tiendapisos/public_html/application/cache/";            $files = glob($path.'*'); // get all file names        }// end if        # delete all files        foreach($files as $file){ // iterate files            if(is_file($file)) {                unlink($file); // delete file            } // end if        } // end foreach        //p_($files);        //die();    } // end function    private function print_xml_avantio($view_xml,$xml)    {        if ($view_xml)            print_r($xml);    } // end function    /**     * @param $xml     */    private function updateServices($xml)    {        $service = new Service();        $service->insertServices($xml,$this->getAvantioCredentials());        //$this->write_report_historico("services");    } // end function    private function updateGeographicAreas($xml)    {        $geographicArea = new GeographicArea();        $geographicArea->insertGeographicAreas($xml,$this->getAvantioCredentials());        //$this->write_report_historico("geographicareas");    } // end function    private function updateKinds($xml)    {        $kinds = new Kind();        $kinds->insertKinds($xml,$this->getAvantioCredentials());        //$this->write_report_historico("kinds");    } // end function    private function updateKindsAlquiler()    {        $this->kindsAlquiler = new KindAlquiler();        $this->kindsAlquiler->insertKinds($this->getAvantioCredentials());        //$this->write_report_historico("kinds");    } // end function    private function updateStatus()    {        $this->status = new Status();        $this->status->insertStatus($this->getAvantioCredentials());        //$this->write_report_historico("kinds");    } // end function    private function updateOcupationRules($xml,$ts,$ts_referencia)    {        $ocupationRules = new OcuppationRule();        $ocupationRules->insertOcuppationRules($xml,$this->getAvantioCredentials(),$ts,$ts_referencia);        $this->write_report_historico("occupationalrules");    } // end function    private function updateAvalability($xml,$ts,$ts_referencia)    {        $availability = new Availability();        $availability->insertAvailabilities($xml,$this->getAvantioCredentials(),$ts,$ts_referencia);        $this->write_report_historico("availabilities");    } // end function    private function updateRates($xml,$ts,$ts_referencia)    {        $rate = new Rate();        $rate->insertRates($xml,$this->getAvantioCredentials(),$ts,$ts_referencia);        $this->write_report_historico("rates");    } // end function    private function updatePriceModify($xml,$ts,$ts_referencia)    {        $priceModify = new PriceModify();        $priceModify->insertPricesModify($xml,$this->getAvantioCredentials(),$ts,$ts_referencia);        $this->write_report_historico("pricemodifiers");    } // end function    private function updateAccomodation($xml)    {        $accomodation = new Accomodation();        # name of objects Nuevo and KindAlquiler        //$accomodation->setKindAlquiler($this->kindsAlquiler->getName());        //$accomodation->setStatus($this->status->getName());        $accomodation->insertAccomodations($xml,$this->getAvantioCredentials());        //$this->write_report_historico("accommodations");        $this->post_id = $accomodation->getPostId();    } // end function    private function updateDescription($xml)    {        $description = new Description();        $description->insertDescriptions($xml,$this->getAvantioCredentials());        //$this->write_report_historico("descriptions");    } // end function    private function updatePicture($xml)    {        $picture = new Picture();        $picture->insertPictures($xml,$this->getAvantioCredentials());        //$this->write_report_historico("descriptions");    } // end function    /**     * @param $tipo_report     */    private function write_report($tipo_report)    {        $db = \Config\Database::connect('tests');        /*        $id = null;        $fecha = date('Y-m-d H:i:s');        $sql="INSERT INTO avantio_report (id,parametro_report,fecha) VALUES ('".$id."' , '".$db->escapeString($tipo_report)."' ,'" . $fecha . "' ) ;";        if (!$db->query($sql)){            $error = $db->error();            echo "Error " . $error["code"] . " con la descripción " . $error["message"];            die();        }// end if        $this->setLastInsertIdReport($db->insertID());        */    } // end function    /**     * @param $fichero_key     */    private function write_report_historico($fichero_key)    {        /*        $ficheros = array(            "geographicareas"   => "geographicareas.xml",            "services"          => "services.xml",            "kinds"             => "kinds.xml",            "accommodations"    => "accommodations.xml",            "descriptions"      => "descriptions.xml",            "availabilities"    => "availabilities.xml",            "rates"             => "rates.xml",            "occupationalrules" => "occupationalrules.xml",            "pricemodifiers"    => "pricemodifiers.xml"        );        $db = \Config\Database::connect('tests');        $id = null;        $fichero = $ficheros[$fichero_key];        $id_avantio_report = $this->getLastInsertIdReport();        $sql="INSERT INTO avantio_report_historico (id,id_avantio_report,fichero) VALUES ('".$id."' , '" . $id_avantio_report . "', '".$db->escapeString($fichero)."'  ) ;";        if (!$db->query($sql)){            $error = $db->error();            echo "Error " . $error["code"] . " con la descripción " . $error["message"];            die();        }// end if        */    } // end function    /**     * @return array     */    public function getFilesDownloadedTemp()    {        return $this->files_downloaded_temp;    }    /**     * @param array $files_downloaded_temp     */    public function setFilesDownloadedTemp($files_downloaded_temp , $system)    {        if (!$files_downloaded_temp){            if ($system == "linux"){            }else if($system == "windows"){                $this->files_downloaded = array(                    "static" => Array(                        "geographicareas" => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/geographicareas.20210730115107.xml",                        "services"        => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/services.20210730115108.xml",                        "kinds"           => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/kinds.20210730115108.xml"                    ),                    "dynamic" => array(                        "accommodations" => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/accommodations.20210730115108.xml",                        //"descriptions"   => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/descriptions.20210711122105.xml",                        "descriptions"   => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/descriptions.20210711205936.xml"                    ),                    "prices" => array(                        "availabilities"    => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/availabilities.20210730115109.xml",                        "rates"             => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/rates.20210730115109.xml",                        "occupationalrules" => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/occupationalrules.20210730115109.xml",                        "pricemodifiers"    => "C:\htdocs\avantio_cron_cli\app\Controllers/../xmldata/pricemodifiers.20210730115109.xml",                    )                );            }// end if        }// end if        $this->files_downloaded_temp = $files_downloaded_temp;    }// end function    /**     * @return mixed     */    public function getAvantioCredentials()    {        return $this->avantio_credentials;    }    /**     * @param mixed $avantio_credentials     */    public function setAvantioCredentials($avantio_credentials = null, $mode)    {        # active languanges        $language = new Language();        # data        if(empty($avantio_credentials))            if($mode == "test"){                $avantio_credentials = array(                    'PARTNER_CODE'		        => '836efa4efbe7fa63f2ebbae30d7b965f',                    'WEBSERVICE_USER' 	        => 'itsatentoapi_test',                    'WEBSERVICE_PWD'	        => 'testapixml',                    'URL_FEEDS'                 => 'http://feeds.itsolutions.es/',                    'DELETE_TIME'               => 2*60*60, //2h                    'ACTIVED_LANGUAGES'         => $language->getAll(),                    'INSERT_COUNT'              => 1000,                    'INSERT_COUNT_IMAGES'       => 25,                    'MAP_DAYS'                  => array ('MONDAY'=>1,'TUESDAY'=>2,'WEDNESDAY'=>3,'THURSDAY'=>4,'FRIDAY'=>5,'SATURDAY'=>6,'SUNDAY'=>7),                );            }else if ($mode == "production"){                $avantio_credentials = array(                    'PARTNER_CODE'		        => 'b552a46e9ec985203668eebe7ab7879b',                    'WEBSERVICE_USER' 	        => 'home_sweet_home',                    'WEBSERVICE_PWD'	        => 'pWZP6HGYgCWxg6vF',                    'URL_FEEDS'                 => 'http://feeds.itsolutions.es/',                    'DELETE_TIME'               => 2*60*60, //2h                    'ACTIVED_LANGUAGES'         => $language->getAll(),                    'INSERT_COUNT'              => 1000,                    'INSERT_COUNT_IMAGES'       => 25,                    'MAP_DAYS'                  => array ('MONDAY'=>1,'TUESDAY'=>2,'WEDNESDAY'=>3,'THURSDAY'=>4,'FRIDAY'=>5,'SATURDAY'=>6,'SUNDAY'=>7),                );            }// end if        $this->avantio_credentials = $avantio_credentials;    }    /**     * @return mixed     */    public function getAvantioFiles()    {        return $this->avantio_files;    }    /**     * @param mixed $avantio_files     */    public function setAvantioFiles($avantio_files = null)    {        /*        if(empty($avantio_files))            $avantio_files = array(                'static'=>array(                    'geographicareas' => array('update'=>0),                    'services' => array('update'=>0),                    'kinds' => array('update'=>0),                ),                'dynamic'=>array(                    'accommodations' => array('update'=>1),                    'descriptions' 	=> array('update'=>1),                ),                'prices'=>array(                    'availabilities' => array('update'=>1),                    'rates' => array('update'=>1),                    'occupationalrules' => array('update'=>1),                    'pricemodifiers' => array('update'=>1),                )            );        */        if(empty($avantio_files))            $avantio_files = array(                'static'=>array(                    'geographicareas' => array('update'=>1),                    'services' => array('update'=>1),                    'kinds' => array('update'=>1),                ),                'dynamic'=>array(                    'accommodations' => array('update'=>1),                    'descriptions' 	=> array('update'=>1),                ),                'prices'=>array(                    'availabilities' => array('update'=>1),                    'rates' => array('update'=>1),                    'occupationalrules' => array('update'=>1),                    'pricemodifiers' => array('update'=>1),                )            );        $this->avantio_files = $avantio_files;    } // end function    /**     * @return int     */    public function getLastInsertIdReport()    {        return $this->last_insert_id_report;    }    /**     * @param int $last_insert_id_report     */    public function setLastInsertIdReport($last_insert_id_report)    {        $this->last_insert_id_report = $last_insert_id_report;    }}