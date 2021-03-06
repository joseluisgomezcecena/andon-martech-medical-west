<?php

error_reporting(0);

use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//pass
function registrarArmadoMolde()
{
    global $connection;

    if(isset($_POST['submit_error']))
    {

        $desc = "";

        $info_departamento = "";

        $info_mantenimiento = "";

        $parte = "";

        $orden = "";

        $folio_mantenimiento = "";

        $capturado = $_POST['capturado'];

        $error     = $_POST['tipo_de_error'];



        if($error == 'armado')
        {

            $responsable = 'maquinas';

            $linea_manual = $_POST['linea_manual'];

            $parte        = $_POST['parte'];

            $orden        = $_POST['orden'];

            if(strlen($linea_manual)>0)
            {
                $linea_m = "Linea y maquina: ".$linea_manual;
            }
            else
            {
                $linea_m = "";
            }

            $info_departamento = " $linea_m \r\n Capturado por: $capturado ";
        }




        $mensaje                = $_POST['descripcion']." ".$desc;
        $maquina_id             = $_POST['third_level'];
        $inicio                 = date("Y-m-d H:i:s");


        echo $_POST['prioridad'];
        //para prioridad en MONTAR E INICIAR
        if($_POST['descripcion'] == 'MONTAR E INICIAR')
        {
            $prioridad = $_POST['prioridad'];
        }
        else
        {
            $prioridad = 0;
        }

        echo $prioridad;


        //informacion de la maquina query
        $select_machine_data = mysqli_query($connection, "SELECT nombre, planta_id, departamento_id, centro_trabajo, serie, numero_control FROM martech_maquinas WHERE id = $maquina_id");
        $row = mysqli_fetch_array($select_machine_data);

        /*****************************datos de base de datos*******************/
        $maquina_nombre         = $row['nombre'];

        $planta_id              = $row['planta_id'];

        $departamento_id        = $row['departamento_id'];//departamento para enviar el correo

        $maquina_centro_trabajo = $row['centro_trabajo'];

        $maquina_serie          = $row['serie'];

        $maquina_numero_control = $row['numero_control'];

        /*****************************datos de base de datos*******************/



        //plantas
        $get_plant = mysqli_query($connection,"SELECT nombre FROM martech_plantas WHERE id = $planta_id");
        $row_plant = mysqli_fetch_array($get_plant);

        $nombre_planta = $row_plant['nombre'];

        //departamentos
        $get_departamento = mysqli_query($connection,"SELECT nombre FROM martech_departamentos WHERE id = $departamento_id");
        $row_departamento = mysqli_fetch_array($get_departamento);

        $nombre_departamento = $row_departamento['nombre'];



        //insertando en base de datos antes de mandar correo evitando error a los 100 correos.

        $query = "INSERT INTO martech_fallas (inicio, offline, maquina_id, maquina_nombre, planta_id, departamento_id,  maquina_centro_trabajo, 
        maquina_serie, maquina_numero_control,  tipo_error, descripcion_operador, planta_nombre, departamento_nombre, escala_1, info_departamento, 
        folio_mantenimiento,  parte, orden, prioridad)
        VALUES ('$inicio', 'si', $maquina_id, '$maquina_nombre', $planta_id, $departamento_id,  '$maquina_centro_trabajo', '$maquina_serie', 
        '$maquina_numero_control',  '$error', '$mensaje', '$nombre_planta', '$nombre_departamento','si', '$info_departamento', 
        '$folio_mantenimiento',  '$parte', '$orden', '$prioridad')";

        $result = mysqli_query($connection, $query);

        if($result)
        {
            $folio = mysqli_insert_id($connection);//id del ultimo insert

            //si se puede insertar en bd se envia el correo electronico.

            if($responsable != "maquinas")
            {
                //responsables
                $get_responsable = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = '$responsable' AND  super = 'si'";
                $responsable_result = mysqli_query($connection, $get_responsable);
                /********Enviar correo electronico */
                /*

            while($row_responsable = mysqli_fetch_array($responsable_result))
            {

                $destino = $row_responsable['user_email'];

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                try {

                    //Server settings
                    $mail->SMTPDebug = 0;                                           // Enable verbose debug output con 2
                    $mail->isSMTP(false);                                                // Set mailer to use SMTP
                    $mail->Host = '192.168.2.8;192.168.2.8';    // Specify main and backup SMTP servers
                    $mail->SMTPAuth = false;                                         // Enable SMTP authentication
                    $mail->Username = 'jgomez@martechmedical.com';                    // SMTP username
                    $mail->Password = 'joseLuis15!';                              // SMTP password
                    $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 25;                                              // TCP port to connect to 587
                    //antes en 465
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                        )
                    );

                    //Recipients
                    $mail->setFrom('andon@martechsender.com', 'Error en Equipo');

                    $mail->addAddress($destino, 'Responsable');     // Add a recipient

                    $mail->addReplyTo('noreply@martechmedical.com', 'Information');


                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Falla en'.$maquina_nombre.'! ID: '.$folio;
                    $mail->Body    = 'Notificación '.$folio.': Se reporto un error en: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$nombre_planta.'</b><br/>Linea: <b>'.$nombre_departamento.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Tipo de error: <b>'.$error.'</b><br/>Mensaje de operador:'.$mensaje.'<br/>'.$info_departamento.'<br>'.$info_mantenimiento;
                    $mail->AltBody = 'Notificación '.$folio.': Se reporto un error en: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$nombre_planta.'</b><br/>Linea: <b>'.$nombre_departamento.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Tipo de error: <b>'.$error.'</b><br/>Mensaje de operador:'.$mensaje.'<br/>'.$info_departamento.'<br>'.$info_mantenimiento;

                    $mail->send();
                    //echo 'Se envio el mensaje';
                }
                catch (Exception $e)
                {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }

            }//termina while incluyendo direcciones y enviando correos
            */

                /********Enviar correo electronico */

            }
            /*
            else
            {

            }
            */



            $output = <<<DELIMITER

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
            
                <!-- Modal content-->
                    <div class="modal-content">
                        <div style="background-color: #28a745; color:#ffffff;" class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Enviado!</h4>
                    </div>
                
                    <div class="modal-body">
                    Reporte enviado con exito.
                    </div>
                
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            
                </div>
            </div>
            <!-- Termina Modal -->

DELIMITER;
            echo $output;

        }
        else
        {
            $output = <<<DELIMITER

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
            
                <!-- Modal content-->
                    <div class="modal-content">
                        <div style="background-color: #dc3545; color:#ffffff;" class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Error</h4>
                    </div>
                
                    <div class="modal-body">
                        No se pudo registrar el error asegurate de llenar los campos necesarios,
                        Solo asi podras capturar el error.<br/><br/>
                        Mensaje para desarrollo de software: <br>{$query}
                    </div>
                
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            
                </div>
            </div>
            <!-- Termina Modal -->

DELIMITER;
            echo $output;
        }
        //termina insert de fallas
    }
}


?>
