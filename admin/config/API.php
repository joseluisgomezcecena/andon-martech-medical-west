<?php
    ini_set('MAX_EXECUTION_TIME', '-1');

    define("DB_HOST", "localhost");
    define("DB_NAME", "smartstu_martech_dev");
    define("DB_USER", "root");
    define("DB_PASS", "");

    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $connection->set_charset("utf8");

    $fn = $_GET['fn'];

    switch($fn) {
        case "get_machines":
            $planta = $_POST['planta'];
            $query_machines = "SELECT id, nombre, numero_control FROM martech_maquinas WHERE planta_id = $planta";
            $result_machines = $connection->query($query_machines);
            if($result_machines) {
                $results = '{"ok":true,"items":[';
                while($rows = $result_machines->fetch_assoc()) {   
                    $results .= '{"id":"'. $rows['id'] .'","machine":"'. str_replace( array("\r", "\n", "\""), "", $rows['nombre'] ) .' '. str_replace( array("\r", "\n", "\""), "", $rows['numero_control'] ) .'"},';
                }
                $results = substr($results, 0, -1) . ']}';
                echo $results;
            }
            else {
                echo '{"ok":false, "error":"'. $connection->error .'"}';
            }
            break;
        case "get_data":
            $machine = $_POST['id'];
            $start   = $_POST['start'];
            $end     = $_POST['end'];

            $query = "SELECT `id`,`inicio`,`atendido`,`fin` FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND `maquina_id` = '$machine' AND `inicio` >= '$start 00:00:00' AND `fin` <= '$end 23:59:59' AND `tipo_error` IN ('caida', 'energia') ORDER BY `martech_fallas_backup`.`inicio` ASC";
            $result = $connection->query($query);
            if($result) {
                if($result->num_rows > 0) {
                    $count = 0;
                    $last_date;
                    $tbf_total = 0;
                    $tr_total = 0;
                    $items = "[";
                    $results = "";

                    while($row = $result->fetch_assoc()) {
                        $inicio = $row['inicio'];
                        $atendido = $row['atendido'];
                        $fin = $row['fin'];
                        $tbf = $count == 0 ? 0 : ROUND(abs(strtotime($inicio) - strtotime($last_date))/60,2);
                        $tr = ROUND(abs(strtotime($fin) - strtotime($atendido))/60, 2);
                        $tbf_total += $tbf;
                        $tr_total += $tr;
                        
                        $item = '{"inicio": "'. $inicio .'","atendido": "' .$atendido .'","fin": "'. $fin .'","tbf":"'. $tbf .'","tr":"'. $tr .'"},';
                        $items .= $item;

                        $last_date = $fin;
                        $count++;
                    }
                    
                    $results = '[{"tbf_total": '. $tbf_total .', "tr_total": '. $tr_total .', "count": '. $count .'}]';
                    echo '{"ok":true, "items":'. substr($items, 0, -1) .'], "results": '. $results .'}';
                    
                } else {
                    echo '{"ok":false, "error":"No se encuentran registros en este intervalo de fechas."}';
                }
            }
            break;
        case "get_full_data":
            $full       = $_POST['full'];
            $result_obj = '{"ok":true,"results":[';

            if($full == "true") {
                $query = "SELECT DISTINCT(`maquina_id`), maquina_nombre, planta_id FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND `tipo_error` IN ('caida', 'energia')";
            }
            else {
                $start   = $_POST['start'];
                $end     = $_POST['end'];
                
                $query = "SELECT DISTINCT(`maquina_id`), maquina_nombre, planta_id FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND `inicio` >= '$start 00:00:00' AND `fin` <= '$end 23:59:59' AND `tipo_error` IN ('caida', 'energia')";
            }
            
            $result_ids = $connection->query($query);
            if($result_ids) {
                if($result_ids->num_rows > 0) {
                    while($row_ids = $result_ids->fetch_assoc()) {
                        $maquina = $row_ids['maquina_nombre'];
                        $planta  = $row_ids['planta_id'];

                        if($full == "true")
                            $query = "SELECT `id`,`inicio`,`atendido`,`fin` FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND  `tipo_error` IN ('caida', 'energia') AND maquina_id = {$row_ids['maquina_id']} ORDER BY `martech_fallas_backup`.`inicio` ASC";
                        else 
                            $query = "SELECT `id`,`inicio`,`atendido`,`fin` FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND `inicio` >= '$start 00:00:00' AND `fin` <= '$end 23:59:59' AND `tipo_error` IN ('caida', 'energia') AND maquina_id = {$row_ids['maquina_id']} ORDER BY `martech_fallas_backup`.`inicio` ASC";
                        
                        $result = $connection->query($query);
                        if($result) {
                            if($result->num_rows > 0) {
                                $count = 0;
                                $last_date;
                                $tbf_total = 0;
                                $tr_total = 0;
            
                                while($row = $result->fetch_assoc()) {
                                    $inicio = $row['inicio'];
                                    $atendido = $row['atendido'];
                                    $fin = $row['fin'];
                                    $tbf = $count == 0 ? 0 : ROUND(abs(strtotime($inicio) - strtotime($last_date))/60,2);
                                    $tr = ROUND(abs(strtotime($fin) - strtotime($atendido))/60, 2);
                                    $tbf_total += $tbf;
                                    $tr_total += $tr;
                                    $last_date = $fin;
                                    $count++;
                                }

                                $tbf_total /= $count;
                                $tr_total /= $count;

                                $result_obj .= '{"maquina": "'. $maquina .'", "planta": '. $planta .', "tbf_total": '. $tbf_total .', "tr_total": '. $tr_total .', "count": '. $count .'},';
                            }
                        }
                    }

                    $result_obj = substr($result_obj, 0, -1) . "]}";
                    echo $result_obj;
                } else {
                    echo '{"ok":false, "error":"No se encuentran registros en este intervalo de fechas."}';
                }
            }
            break;
        case "get_data_for_chart":
            $machine = $_POST['id'];
            $start   = $_POST['start'];
            $end     = $_POST['end'];

            $query = "SELECT `id`,`inicio`,`atendido`,`fin` FROM `martech_fallas_backup` WHERE `atendido_flag` = 'si' AND `offline` = 'no' AND `maquina_id` = '$machine' AND `inicio` >= '$start 00:00:00' AND `fin` <= '$end 23:59:59' AND `tipo_error` IN ('caida', 'energia') ORDER BY `martech_fallas_backup`.`inicio` ASC";
            $result = $connection->query($query);
            if($result) {
                if($result->num_rows > 0) {
                    $count = 0;
                    $last_date;
                    $tbf_total = 0;
                    $tr_total = 0;
                    $labels = "";
                    $ttf    = "";
                    $ttr    = "";

                    while($row = $result->fetch_assoc()) {
                        $inicio = $row['inicio'];
                        $atendido = $row['atendido'];
                        $fin = $row['fin'];
                        $tbf = $count == 0 ? 0 : ROUND(abs(strtotime($inicio) - strtotime($last_date))/60,2);
                        $tr = ROUND(abs(strtotime($fin) - strtotime($atendido))/60, 2);
                        $tbf_total += $tbf;
                        $tr_total += $tr;
                        
                        $labels .= '"'. explode(" ", $inicio)[0] .'",';
                        $ttf    .= $tbf .",";
                        $ttr    .= $tr .",";


                        $last_date = $fin;
                        $count++;
                    }

                    echo '{"ok":true, "labels":['. substr($labels, 0, -1) .'], "ttf":['. substr($ttf, 0, -1) .'], "ttr":['. substr($ttr, 0, -1) .']}';
                    
                } else {
                    echo '{"ok":false, "error":"No se encuentran registros en este intervalo de fechas."}';
                }
            }
            break;
        default:
            echo '{"ok":false, "error":"La función no existe"}';
            break;
    }
?>    