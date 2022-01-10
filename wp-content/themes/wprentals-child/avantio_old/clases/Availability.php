<?php

class Availability{

    protected $table      = 'avantio_occupation_rules_tmp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language','text_title'];


    public function insertAvailabilities($xml,$avantio_credentials,$ts,$ts_referencia)
    {

        $db = \Config\Database::connect('tests');

        $sql="DROP TABLE IF EXISTS avantio_availabilities_tmp;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        $sql="CREATE TABLE avantio_availabilities_tmp LIKE avantio_availabilities;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        $inserts=array();
        $anyo_actual = intval(date("Y"));
        foreach($xml->AccommodationList->Accommodation as $accommodation){
            $accommodation_id = intval($accommodation->AccommodationId);
            $occupation_rule_id = intval($accommodation->OccupationalRuleId);
            $availabilities=array();
            foreach($accommodation->Availabilities->AvailabilityPeriod as $period){
                $date_start=(string)$period->StartDate;
                $date_end=(string)$period->EndDate;
                $status=( (string)$period->State == 'AVAILABLE' ) ? 1 : 0;
                $num_dias = inteval_days($date_start,$date_end);
                list($iAnyo,$iMes,$iDia)=explode("-",$date_start);
                for($i=0;$i<=$num_dias;$i++){
                    $ts=mktime(0,0,0,$iMes,$iDia+$i,$iAnyo);
                    if(intval(date("Y",$ts)) > $anyo_actual+1) continue;
                    $fecha=date("Y-m-d",$ts);
                    $availabilities[$fecha]=$status;
                }// end for
            }// end foreach
            foreach($availabilities as $fecha=>$status){
                $inserts[]="('".$accommodation_id."','".$occupation_rule_id."','".$fecha."','".$status."')";
                if(count($inserts)>= $avantio_credentials['INSERT_COUNT']){
                    $sql="INSERT INTO avantio_availabilities_tmp (accommodation_id,occupation_rule_id,fecha,status) VALUES ".implode(",",$inserts).";";
                    if (!$db->query($sql)){
                        $error = $db->error();
                        echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                        die();
                    }// end if
                    $inserts=array();
                }// end if
            }// end foreach
            if(!empty($inserts)){
                $sql="INSERT INTO avantio_availabilities_tmp (accommodation_id,occupation_rule_id,fecha,status) VALUES ".implode(",",$inserts).";";
                if (!$db->query($sql)){
                    $error = $db->error();
                    echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                    die();
                }// end if
                $inserts=array();
            }
        }

        $sql = "RENAME TABLE avantio_availabilities TO avantio_availabilities_back, avantio_availabilities_tmp TO avantio_availabilities, avantio_availabilities_back TO avantio_availabilities_tmp;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if


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