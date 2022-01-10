<?php

class Rate{

    protected $table      = 'avantio_occupation_rules_tmp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language','text_title'];


    public function insertRates($xml,$avantio_credentials,$ts,$ts_referencia)
    {

        $db = \Config\Database::connect('tests');

        $sql="DROP TABLE IF EXISTS avantio_rates_tmp;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        $sql="CREATE TABLE avantio_rates_tmp LIKE avantio_rates;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        $inserts=array();
        $anyo_actual=intval(date("Y"));
        $date_3_meses_vista=intval(date("Ymd",mktime(0,0,0,date("m")+12,date("d"),date("Y"))));
        $precios_minimos=array();
        $uniq_insert=array();
        foreach($xml->AccommodationList->Accommodation as $accommodation){
            $accommodation_id=intval($accommodation->AccommodationId);
            $capacity=intval($accommodation->Capacity);
            foreach($accommodation->Rates->RatePeriod as $period){
                $date_start=(string)$period->StartDate;
                $date_end=(string)$period->EndDate;
                $price=intval($period->RoomOnly->Price);
                $num_dias = inteval_days($date_start,$date_end);
                list($iAnyo,$iMes,$iDia)=explode("-",$date_start);
                for($i=0;$i<=$num_dias;$i++){
                    $ts=mktime(0,0,0,$iMes,$iDia+$i,$iAnyo);
                    if(intval(date("Y",$ts)) > $anyo_actual+2) continue;
                    if(intval(date("Ymd",$ts)) < $date_3_meses_vista) {
                        $precios_minimos[$accommodation_id]=(!isset($precios_minimos[$accommodation_id])) ? $price : min ($price,$precios_minimos[$accommodation_id]);
                    }
                    $fecha=date("Y-m-d",$ts);
                    if(isset($uniq_insert[md5($accommodation_id."|".$capacity."|".$fecha)]) && $uniq_insert[md5($accommodation_id."|".$capacity."|".$fecha)]) continue;
                    $uniq_insert[md5($accommodation_id."|".$capacity."|".$fecha)]=1;
                    $inserts[]="('".$accommodation_id."','".$capacity."','".$fecha."','".$price."')";
                    if(count($inserts)>=$avantio_credentials['INSERT_COUNT']){
                        $sql="INSERT INTO avantio_rates_tmp (accommodation_id,capacity,fecha,price) VALUES ".implode(",",$inserts).";";
                        if (!$db->query($sql)){
                            $error = $db->error();
                            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                            die();
                        }// end if
                        $inserts=array();
                    }
                }
            }
        }
        if(!empty($inserts)){
            $sql="INSERT INTO avantio_rates_tmp (accommodation_id,capacity,fecha,price) VALUES ".implode(",",$inserts).";";
            if (!$db->query($sql)){
                $error = $db->error();
                echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                die();
            }// end if
            $inserts=array();
        }
        $sql="RENAME TABLE avantio_rates TO avantio_rates_back, avantio_rates_tmp TO avantio_rates, avantio_rates_back TO avantio_rates_tmp;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        foreach($precios_minimos as $accommodation_id=>$precio){
            $sql="UPDATE dynamic_rooms SET number_minprecio='".$precio."' WHERE id='".$accommodation_id."' ;";
            if (!$db->query($sql)){
                $error = $db->error();
                echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                die();
            }// end if
        }


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