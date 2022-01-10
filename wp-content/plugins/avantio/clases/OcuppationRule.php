<?php

class OcuppationRule{

    protected $table      = 'avantio_occupation_rules_tmp';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'language','text_title'];


    public function insertOcuppationRules($xml,$avantio_credentials,$ts,$ts_referencia)
    {

        $db = \Config\Database::connect('tests');

        $sql = "DROP TABLE IF EXISTS avantio_occupation_rules_tmp;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        $sql="CREATE TABLE avantio_occupation_rules_tmp LIKE avantio_occupation_rules;";
        if (!$db->query($sql)){
            $error = $db->error();
            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
            die();
        }// end if

        foreach($xml->OccupationalRule as $rule){
            $rule_id=intval($rule->Id);
            $rule_name=(string)$rule->Name;
            $sql="INSERT INTO avantio_occupation_rules_names (id,name) VALUES ('".$rule_id."','".$db->escapeString($rule_name)."') ON DUPLICATE KEY UPDATE name='".$db->escapeString($rule_name)."' ;";
            if (!$db->query($sql)){
                $error = $db->error();
                echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                die();
            }// end if
            foreach($rule->Season as $season){
                $date_start = (string)$season->StartDate;
                $date_end = (string)$season->EndDate;
                $min_nights = intval($season->MinimumNights);
                $min_nights_online = intval($season->MinimumNightsOnline);
                $checkin_week_days = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0);
                $checkin_month_days = array();
                $checkin_week_days_temp=$season->CheckInDays->WeekDay;
                $checkin_month_days_temp=$season->CheckInDays->MonthDay;
                foreach($checkin_week_days_temp as $day){
                    $day=trim((string)$day);
                    if($day=='') continue;
                    $checkin_week_days[$avantio_credentials['MAP_DAYS'][$day]]=1;
                }
                if(empty($checkin_week_days)) $checkin_week_days=array(1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1);
                foreach($checkin_month_days_temp as $day){
                    $day=(string)$day;
                    if(trim($day)=='') continue;
                    $checkin_month_days[$day]=1;
                }
                if(empty($checkin_month_days))
                    for($i=1;$i<32;$i++)
                        $checkin_month_days[$i]=1;

                $checkout_week_days=array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0);
                $checkout_month_days=array();
                $checkout_week_days_temp=$season->CheckOutDays->WeekDay;
                $checkout_month_days_temp=$season->CheckOutDays->MonthDay;
                foreach($checkout_week_days_temp as $day){
                    $day=trim((string)$day);
                    if($day=='') continue;
                    $checkout_week_days[$avantio_credentials['MAP_DAYS'][$day]]=1;
                }
                if(empty($checkout_week_days)) $checkout_week_days=array(1=>1,2=>1,3=>1,4=>1,5=>1,6=>1,7=>1);
                foreach($checkout_month_days_temp as $day){
                    $day=(string)$day;
                    if(trim($day)=='') continue;
                    $checkout_month_days[$day]=1;
                }
                if(empty($checkout_month_days))
                    for($i=1;$i<32;$i++)
                        $checkout_month_days[$i]=1;

                $inserts=array();
                $num_dias = inteval_days($date_start,$date_end);
                list($iAnyo,$iMes,$iDia)=explode("-",$date_start);
                for($i=0;$i<=$num_dias;$i++){
                    $ts = mktime(0,0,0,$iMes,$iDia + $i, $iAnyo);
                    $fecha = date("Y-m-d",$ts);
                    $dia_de_la_semana = date("N",$ts);
                    $dia_del_mes=date("j",$ts);
                    $checkin = intval($checkin_week_days[$dia_de_la_semana]);
                    $checkin = min($checkin , intval($checkin_month_days[$dia_del_mes]));
                    $checkout = intval($checkout_week_days[$dia_de_la_semana]);
                    $checkout = min($checkout,intval($checkout_month_days[$dia_del_mes]));
                    $inserts[] = "('".$rule_id."','".$fecha."','".$min_nights."','".$min_nights_online."','".$checkin."','".$checkout."')";
                    if(count($inserts)>=$avantio_credentials['INSERT_COUNT']){
                        $sql="INSERT INTO avantio_occupation_rules_tmp (id,fecha,min_nights,min_nights_online,checkin,checkout) VALUES ".implode(",",$inserts).";";
                        if (!$db->query($sql)){
                            $error = $db->error();
                            echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                            die();
                        }// end if
                        $inserts=array();
                    }
                }

                if(!empty($inserts)){
                    $sql="INSERT INTO avantio_occupation_rules_tmp (id,fecha,min_nights,min_nights_online,checkin,checkout) VALUES ".implode(",",$inserts).";";
                    if (!$db->query($sql)){
                        $error = $db->error();
                        echo "Error " . $error["code"] . " con la descripción " . $error["message"];
                        die();
                    }// end if
                    $inserts=array();
                }
            }
        }

        $sql = "RENAME TABLE avantio_occupation_rules TO avantio_occupation_rules_back, avantio_occupation_rules_tmp TO avantio_occupation_rules, avantio_occupation_rules_back TO avantio_occupation_rules_tmp;";
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