<?phpclass DB {    protected static $instance;    protected function __construct() {}    public static function getInstance() {        if(empty(self::$instance)) {            # server            /*            $db_info = array(                "db_host" => "localhost",                "db_port" => "3306",                "db_user" => "automoci_user",                "db_pass" => "Berruezin007",                "db_name" => "automoci_inmobiliaria_dos",                "db_charset" => "UTF-8"            );            */            # local            $db_info = array(                "db_host" => "localhost",                "db_port" => "3306",                "db_user" => "root",                "db_pass" => "Berruezin23",                "db_name" => "pisosenm_inmobiliaria",                "db_charset" => "UTF-8"            );            try {                self::$instance = new wpdb($db_info['db_user'],$db_info['db_pass'],$db_info['db_name'],'localhost');                /*                self::$instance = new PDO("mysql:host=".$db_info['db_host'].';port='.$db_info['db_port'].';dbname='.$db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);                self::$instance->query('SET NAMES utf8');                self::$instance->query('SET CHARACTER SET utf8');                */            } catch(PDOException $error) {                echo $error->getMessage();            }        }        return self::$instance;    }// end function    public static function setCharsetEncoding() {        if (self::$instance == null) {            self::connect();        }        self::$instance->exec(            "SET NAMES 'utf8';            SET character_set_connection=utf8;            SET character_set_client=utf8;            SET character_set_results=utf8"        );    }// end function}// end class?>