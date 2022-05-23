<?php 
error_reporting(E_ALL);

use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;
use Twilio\Exceptions\HttpException;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function escalacion1()
{
    global $connection;

    $tiempo_actual = date("Y-m-d H:i:s");
    

            
    $get_fallas = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' AND escala_1 = 'si'  AND escala_2 = 'no'";
    
    $run_get_fallas = mysqli_query($connection, $get_fallas);
    
    while($row_fallas = mysqli_fetch_array($run_get_fallas))
    {
        
        $tiempo_reporte = $row_fallas['inicio'];
        
        $id_falla = $row_fallas['id'];
                
        
        $actual = strtotime($tiempo_actual);
        $report = strtotime($tiempo_reporte);
        $diferencia  = $actual-$report;

        echo "<div class='bg-danger'>reporte: <h2>Andon: 5 minutos</h2></div>";
        echo "<div class='bg-danger'>reporte: <h2>".$report."</h2></div>";
        echo "<div class='bg-danger'>actual: <h2>".$actual."</h2></div>";
        echo "<div class='bg-danger'>diferencia: <h2>".$diferencia."</h2></div>";

                
        if($diferencia > 50)//5 minutos 300 segs
        {
           
            /**************************Tipos de errores */
            $error     = $row_fallas['tipo_error'];//error sacado de base de datos

        
            if($error == 'setup')
            {
                $responsable = 'maquinas';
            }

            elseif($error == 'caida' || $error == 'agua' || $error == 'energia' )
            {
                $responsable = 'mantenimiento';
            }

            elseif($error == 'falta_material' || $error == 'reemplazo' )
            {
                $responsable = 'materiales';
            }

            elseif($error == 'qap'|| $error == 'producto' )
            {
                $responsable = 'ingenieria calidad';
            }

            elseif($error == 'sop' )
            {
                $responsable = 'ingenieria';
            }

            /****************************Tipos de errores */

            $maquina_nombre         = $row_fallas['maquina_nombre'];
        
            $planta_nombre          = $row_fallas['planta_nombre'];

            $departamento_id        = $row_fallas['departamento_id'];
            
            $departamento_nombre    = $row_fallas['departamento_nombre'];//departamento para enviar el correo
            
            $maquina_centro_trabajo = $row_fallas['maquina_centro_trabajo'];
            
            $maquina_serie          = $row_fallas['maquina_serie'];
            
            $maquina_numero_control = $row_fallas['maquina_numero_control'];

            $mensaje_operador       = $row_fallas['descripcion_operador'];

            $info_departamento      = $row_fallas['info_departamento'];
            

            //responsables del departamento

            $cont = 0;

            $get_responsable = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = '$responsable' AND super = 'si'";
            $responsable_result = mysqli_query($connection, $get_responsable);

            //numero de responsables

            $num_responsables = mysqli_num_rows($responsable_result);


            
             /***********************************Enviar correo electronico a responsables de departamento*/

            while($row_responsable = mysqli_fetch_array($responsable_result))
            {
                $cont++;
                
                $numero_telefono = $row_responsable['user_telefono'];
                $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);

                
                $destino = $row_responsable['user_email'];

                
                require 'mail/vendor/autoload.php';

               
                $mail = new PHPMailer(true);                                            // Passing `true` enables exceptions
                try {
                    
                    
                    $mail->SMTPDebug = 2;                                               // Enable verbose debug output
                    $mail->isSMTP();                                                    // Set mailer to use SMTP
                    $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';        // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                                             // Enable SMTP authentication
                    $mail->Username = 'smartsender@smartstudios.net';                   // SMTP username
                    $mail->Password = 'smartsender!';                                   // SMTP password
                    $mail->SMTPSecure = 'tls';                                          // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                                  // TCP port to connect to 587
                    //antes en 465

                    //Recipients
                    $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 5 minutos');
                    
                    $mail->addAddress($destino, 'Responsable');                         // Add a recipient
                                        
                    $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');
                    

                

                
                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                    $mail->Body    = 'Alerta de 5 minutos '.$cont.': </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                    $mail->AltBody = 'Alerta de 5 minutos: </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                    $mail->send();

                    echo 'Message has been sent';
                    
                } 

                catch (Exception $e) 
                {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
                

                

                echo "<div class='bg-danger'>reporte: <h2>Error $cont</h2></div>";

                /*****************************Termina Enviar correo electronico */

                /*****************************Enviar mensaje de texto*/   

                
                $config = [
                    'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                    'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                    'phone_number'=>'+14702803099'
                ];
                
    
                $numero  = "+52".$sms_number;
                $mensaje = "Alerta de 5 minutos, Error en: $maquina_nombre\r\n Centro de trabajo: $maquina_centro_trabajo\r\n #Control: $maquina_numero_control\r\n Planta: $planta_nombre\r\n Linea: $departamento_nombre\r\n Error: $error\r\n $info_departamento";
    
                if(strlen($numero)==13)
                {
                        /*
                        if(!isset($config))
                        {
                            header("Location: http://google.com");
                        }
                        */
                        

                        $client = new Client($config['account_sid'], $config['auth_token']);
                    
            
                   
                   try
                   {
                        $sms = $client->messages->create(
                            $numero,
                            array(
                                "from" => $config['phone_number'],
                                "body" => $mensaje
                            )
                        );
        
        
                        echo "mensaje enviado";
                   }
                   catch(RestException $e ) {
                        $ns = "INSERT INTO test (valor) VALUE ('fallo')";
                        $nsr = mysqli_query($connection, $ns);
                   }
                    
                   

                   
                }

                /*****************************Termina mensaje de texto */

            }//termina while 

            

            //sacando supervisores 
            $supervisores_query = "SELECT * FROM martech_usuario_departamento WHERE id_departamento = $departamento_id";
            
            $run_supervisores_query = mysqli_query($connection, $supervisores_query);
            

            
            while($row_supervisores = mysqli_fetch_array($run_supervisores_query))
            {
                $id_supervisor = $row_supervisores['id_usuario'];
                
                
                $get_responsable_s = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = 'supervisor' AND user_id = $id_supervisor";
                
                $responsable_result_s = mysqli_query($connection, $get_responsable_s);
                
                while($row_responsable_s = mysqli_fetch_array($responsable_result_s))
                {

                    $destino_s = $row_responsable_s['user_email'];
                    

                    require 'mail/vendor/autoload.php';

                    $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                    try {
                        
                        $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                        $mail->isSMTP();                                                // Set mailer to use SMTP
                        $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';    // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                        $mail->Username = 'smartsender@smartstudios.net';               // SMTP username
                        $mail->Password = 'smartsender!';                               // SMTP password
                        $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                                              // TCP port to connect to 587
                        //antes en 465

                        //Recipients
                        $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 5 minutos');
                        
                        $mail->addAddress($destino_s, 'Responsable');                   // Add a recipient
                                            
                        $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');
                        

                    

                        //Content
                        $mail->isHTML(true);                                  
                        $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                        $mail->Body    = 'Alerta de 5 minutos: </br>Supervisor : '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                        $mail->AltBody = 'Alerta de 5 minutos: </br>Supervisor : '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    }
                
                }//termina while
            }
        
        
            

            $update_escala = "UPDATE martech_fallas SET escala_2 ='si' WHERE id = $id_falla";
            $run_update_escala = mysqli_query($connection, $update_escala);
        
        }//termina if

    }//termina while loop
}





function escalacion2()
{
    global $connection;

    $tiempo_actual = date("Y-m-d H:i:s");
            


    $get_fallas = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' AND escala_2 = 'si' AND escala_3 = 'no'";
    
    $run_get_fallas = mysqli_query($connection, $get_fallas);
    
    while($row_fallas = mysqli_fetch_array($run_get_fallas))
    {

        $tiempo_reporte = $row_fallas['inicio'];
        $id_falla = $row_fallas['id'];
                
        $actual = strtotime($tiempo_actual);
        $report = strtotime($tiempo_reporte);
        $diferencia  = $actual-$report;

        echo "<div class='bg-danger'>reporte: <h2>Alerta de 1 hora</h2></div>";
        echo "<div class='bg-danger'>reporte: <h2>".$report."</h2></div>";
        echo "<div class='bg-danger'>actual: <h2>".$actual."</h2></div>";
        echo "<div class='bg-danger'>diferencia: <h2>".$diferencia."</h2></div>";

                
        if($diferencia > 200)//3900
        {
           
            /**************************Tipos de errores */
            $error     = $row_fallas['tipo_error'];//error sacado de base de datos

        
            if($error == 'setup')
            {
                $responsable = 'maquinas';
            }
            elseif($error == 'caida' || $error == 'agua' || $error == 'energia' )
            {
                $responsable = 'mantenimiento';
            }
            elseif($error == 'falta_material' || $error == 'reemplazo' )
            {
                $responsable = 'materiales';
            }
            elseif($error == 'qap' || $error == 'producto')
            {
                $responsable = 'ingenieria calidad';
            }
            elseif($error == 'sop' )
            {
                $responsable = 'ingenieria';
            }
            /****************************Tipos de errores */

            $maquina_nombre         = $row_fallas['maquina_nombre'];
        
            $planta_id          = $row_fallas['planta_id'];
            
            $planta_nombre          = $row_fallas['planta_nombre'];
            
            $departamento_id        = $row_fallas['departamento_id'];
            
            $departamento_nombre    = $row_fallas['departamento_nombre'];//departamento para enviar el correo
            
            $maquina_centro_trabajo = $row_fallas['maquina_centro_trabajo'];
            
            $maquina_serie          = $row_fallas['maquina_serie'];
            
            $maquina_numero_control = $row_fallas['maquina_numero_control'];
            
            $mensaje_operador       = $row_fallas['descripcion_operador'];

            $info_departamento      = $row_fallas['info_departamento'];

            
            
            //responsables del departamento
            $get_responsable = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = '$responsable' AND super2 = 'si'";
            $responsable_result = mysqli_query($connection, $get_responsable);


             /***********************************Enviar correo electronico a responsables de departamento*/

            
             while($row_responsable = mysqli_fetch_array($responsable_result))
            {
                //tomando el telefono y cambiando formato a puros numeros
                $numero_telefono = $row_responsable['user_telefono'];
                $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);

                //tomando el correo electronico del responsable
                $destino = $row_responsable['user_email'];

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                try {
                    
                    $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                    $mail->isSMTP();                                                // Set mailer to use SMTP
                    $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';    // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                    $mail->Username = 'smartsender@smartstudios.net';               // SMTP username
                    $mail->Password = 'smartsender!';                               // SMTP password
                    $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                              // TCP port to connect to 587
                    //antes en 465

                    //Recipients
                    $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 1 hora');
                    
                    $mail->addAddress($destino, 'Responsable');                     // Add a recipient
                                        
                    $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');
                    

                

                    //Content
                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                    $mail->Body    = 'Alerta de 1hr: </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                    $mail->AltBody = 'Alerta de 1hr: </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
                /*****************************Termina Enviar correo electronico */

                /*****************************Enviar mensaje de texto*/

               

                $config = [
                    'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                    'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                    'phone_number'=>'+14702803099'
                ];
    

                $numero  = "+52".$sms_number;
                $mensaje = "Alerta de 1hr a responsable, Error en: $maquina_nombre\r\n Centro de trabajo: $maquina_centro_trabajo\r\n #Control: $maquina_numero_control\r\n Planta: $planta_nombre\r\n Linea: $departamento_nombre\r\n Error: $error\r\n $info_departamento";
    
                if(strlen($numero)==13)
                {
                    
                    $client = new Client($config['account_sid'], $config['auth_token']);
                    $sms = $client->messages->create(
                        $numero,
                        array(
                            "from" => $config['phone_number'],
                            "body" => $mensaje
                        )
                    );
    
    
                    echo "mensaje enviado";
    
                }

                /*****************************Termina mensaje de texto */

            }//termina while 

            

            //sacando gerentes de produccion 
            $supervisores_query = "SELECT * FROM martech_usuario_planta WHERE id_planta = $planta_id";
            
            $run_supervisores_query = mysqli_query($connection, $supervisores_query);
            
            while($row_supervisores = mysqli_fetch_array($run_supervisores_query))
            {
                $id_supervisor = $row_supervisores['id_usuario'];
             


                $get_responsable_s = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = 'gerente produccion' AND user_id = $id_supervisor";
                
                $responsable_result_s = mysqli_query($connection, $get_responsable_s);
                
                while($row_responsable_s = mysqli_fetch_array($responsable_result_s))
                {

                    //tomando el telefono y cambiando formato a puros numeros
                    $numero_telefono = $row_responsable_s['user_telefono'];
                    $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);


                    $destino_s = $row_responsable_s['user_email'];



                    require 'mail/vendor/autoload.php';

                    $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                    try {
                        
                        $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                        $mail->isSMTP();                                                // Set mailer to use SMTP
                        $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';    // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                        $mail->Username = 'smartsender@smartstudios.net';               // SMTP username
                        $mail->Password = 'smartsender!';                               // SMTP password
                        $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                                              // TCP port to connect to 587
                        //antes en 465

                        //Recipients
                        $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 1 hora');
                        
                        $mail->addAddress($destino_s, 'Responsable');                   // Add a recipient
                                            
                        $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');

                        //Content
                        $mail->isHTML(true);                                  
                        $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                        $mail->Body    = 'Alerta de 1hr a Gerente de produccion: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                        $mail->AltBody = 'Alerta de 1hr a Gerente de produccion: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    }



                    /*****************************Enviar mensaje de texto*/

                    $config = [
                        'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                        'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                        'phone_number'=>'+14702803099'
                    ];
        
                    $numero  = "+52".$sms_number;
                    $mensaje = "Alerta de 1hr a Gerente de produccion, Error en: $maquina_nombre\r\n Centro de trabajo: $maquina_centro_trabajo\r\n #Control: $maquina_numero_control\r\n Planta: $planta_nombre\r\n Linea: $departamento_nombre\r\n Error: $error\r\n $info_departamento";
        
                    if(strlen($numero)==13)
                    {
                        
                        $client = new Client($config['account_sid'], $config['auth_token']);
                        $sms = $client->messages->create(
                            $numero,
                            array(
                                "from" => $config['phone_number'],
                                "body" => $mensaje
                            )
                        );
        
        
                        echo "mensaje enviado";
        
                    }

                    /*****************************Termina mensaje de texto */





                }//termina while

            }
        
        
            $update_escala = "UPDATE martech_fallas SET escala_3 ='si' WHERE id = $id_falla";
            $run_update_escala = mysqli_query($connection, $update_escala);
        
        }//termina if

    }//termina while loop
}




function escalacion3()
{
    global $connection;

    $tiempo_actual = date("Y-m-d H:i:s");
      
    


    $get_fallas = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' AND escala_3 = 'si' AND escala_4 = 'no'";
    
    $run_get_fallas = mysqli_query($connection, $get_fallas);
    
    while($row_fallas = mysqli_fetch_array($run_get_fallas))
    {

        $tiempo_reporte = $row_fallas['inicio'];
        $id_falla = $row_fallas['id'];
    
        
        $actual = strtotime($tiempo_actual);
        $report = strtotime($tiempo_reporte);
        $diferencia  = $actual-$report;


        echo "<div class='bg-danger'>reporte: <h2>Alerta de 2hrs</h2></div>";
        echo "<div class='bg-danger'>reporte: <h2>".$report."</h2></div>";
        echo "<div class='bg-danger'>actual: <h2>".$actual."</h2></div>";
        echo "<div class='bg-danger'>diferencia: <h2>".$diferencia."</h2></div>";

                
        if($diferencia > 300)//3900
        {
           
            /**************************Tipos de errores */
            $error     = $row_fallas['tipo_error'];//error sacado de base de datos

        
            if($error == 'setup')
            {
                $responsable = 'maquinas';
            }
            elseif($error == 'caida' || $error == 'agua' || $error == 'energia' )
            {
                $responsable = 'mantenimiento';
            }
            elseif($error == 'falta_material' || $error == 'reemplazo' )
            {
                $responsable = 'materiales';
            }
            elseif($error == 'qap' || $error == 'producto' )
            {
                $responsable = 'ingenieria calidad';
            }
            elseif($error == 'sop' )
            {
                $responsable = 'ingenieria';
            }
            /****************************Tipos de errores */

            $maquina_nombre         = $row_fallas['maquina_nombre'];
        
            $planta_id          = $row_fallas['planta_id'];
            
            $planta_nombre          = $row_fallas['planta_nombre'];
            
            $departamento_id        = $row_fallas['departamento_id'];
            
            $departamento_nombre    = $row_fallas['departamento_nombre'];//departamento para enviar el correo
            
            $maquina_centro_trabajo = $row_fallas['maquina_centro_trabajo'];
            
            $maquina_serie          = $row_fallas['maquina_serie'];
            
            $maquina_numero_control = $row_fallas['maquina_numero_control'];
            
            $mensaje_operador       = $row_fallas['descripcion_operador'];

            $info_departamento      = $row_fallas['info_departamento'];

            //responsables del departamento
            $get_responsable = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = '$responsable' AND super3 = 'si'";
            $responsable_result = mysqli_query($connection, $get_responsable);


             /***********************************Enviar correo electronico a responsables de departamento*/

            while($row_responsable = mysqli_fetch_array($responsable_result))
            {
                //tomando el telefono y cambiando formato a puros numeros
                $numero_telefono = $row_responsable['user_telefono'];
                $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);

                //tomando el correo electronico del responsable
                $destino = $row_responsable['user_email'];

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                try {
                    
                    
                    $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                    $mail->isSMTP();                                                // Set mailer to use SMTP
                    $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';    // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                    $mail->Username = 'smartsender@smartstudios.net';               // SMTP username
                    $mail->Password = 'smartsender!';                               // SMTP password
                    $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                              // TCP port to connect to 587
                    //antes en 465

                    //Recipients
                    $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 2 hrs');
                    
                    $mail->addAddress($destino, 'Responsable');                     // Add a recipient
                                        
                    $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');
                    

                

                    //Content
                    $mail->isHTML(true);                                            
                    $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                    $mail->Body    = 'Alerta de 2hr: </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                    $mail->AltBody = 'Alerta de 2hr: </br>Responsable : '.$destino.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
                /*****************************Termina Enviar correo electronico */

                /*****************************Enviar mensaje de texto*/
                $config = [
                    'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                    'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                    'phone_number'=>'+14702803099'
                ];
    
                $numero  = "+52".$sms_number;
                $mensaje = "Alerta de 2hr a responsable, Error en: $maquina_nombre\r\n Centro de trabajo: $maquina_centro_trabajo\r\n #Control: $maquina_numero_control\r\n Planta: $planta_nombre\r\n Linea: $departamento_nombre\r\n Error: $error\r\n $info_departamento";
    
                if(strlen($numero)==13)
                {
                    
                    $client = new Client($config['account_sid'], $config['auth_token']);
                    $sms = $client->messages->create(
                        $numero,
                        array(
                            "from" => $config['phone_number'],
                            "body" => $mensaje
                        )
                    );
    
    
                    echo "mensaje enviado";
    
                }

                /*****************************Termina mensaje de texto */

            }//termina while 

            

            
            
                $get_responsable_s = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = 'gerente operaciones'";
                $responsable_result_s = mysqli_query($connection, $get_responsable_s);
                
                while($row_responsable_s = mysqli_fetch_array($responsable_result_s))
                {

                     //tomando el telefono y cambiando formato a puros numeros
                    $numero_telefono = $row_responsable_s['user_telefono'];
                    $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);

                    $destino_s = $row_responsable_s['user_email'];

                    require 'mail/vendor/autoload.php';

                    $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                    try {
                        
                        $mail->SMTPDebug = 2;                                           // Enable verbose debug output
                        $mail->isSMTP();                                                // Set mailer to use SMTP
                        $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';    // Specify main and backup SMTP servers
                        $mail->SMTPAuth = true;                                         // Enable SMTP authentication
                        $mail->Username = 'smartsender@smartstudios.net';               // SMTP username
                        $mail->Password = 'smartsender!';                               // SMTP password
                        $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
                        $mail->Port = 587;                                              // TCP port to connect to 587
                        //antes en 465

                        //Recipients
                        $mail->setFrom('smartsender@smartstudios.net', 'Error en Equipo');
                        
                        $mail->addAddress($destino_s, 'Responsable');                   // Add a recipient
                                            
                        $mail->addReplyTo('noreply@martechmedical.com', 'Information');
                        

                        //Content
                        $mail->isHTML(true);                                  
                        $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                        $mail->Body    = 'Alerta de 2hr a Gerente de Operaciones: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                        $mail->AltBody = 'Alerta de 2hr a Gerente de Operaciones: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    }



                    /*****************************Enviar mensaje de texto*/
                    $config = [
                        'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                        'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                        'phone_number'=>'+14702803099'
                    ];
        
        
                    $numero  = "+52".$sms_number;
                    $mensaje = "Alerta de 2hr a Gerente de Operaciones, Error en: $maquina_nombre\r\n Centro de trabajo: $maquina_centro_trabajo\r\n #Control: $maquina_numero_control\r\n Planta: $planta_nombre\r\n Linea: $departamento_nombre\r\n Error: $error\r\n $info_departamento";
        
                    if(strlen($numero)==13)
                    {
                        
                        $client = new Client($config['account_sid'], $config['auth_token']);
                        $sms = $client->messages->create(
                            $numero,
                            array(
                                "from" => $config['phone_number'],
                                "body" => $mensaje
                            )
                        );
        
        
                        echo "mensaje enviado";
        
                    }

                    /*****************************Termina mensaje de texto */





                }//termina while

            //}
        
        
            $update_escala = "UPDATE martech_fallas SET escala_4 ='si' WHERE id = $id_falla";
            $run_update_escala = mysqli_query($connection, $update_escala);
        
        }//termina if


    }//termina while loop

}





function escalacion4()
{
    global $connection;

    $tiempo_actual = date("Y-m-d H:i:s");
            
    $get_fallas = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' AND escala_4 = 'si' AND escala_5 = 'no'";
    $run_get_fallas = mysqli_query($connection, $get_fallas);
    while($row_fallas = mysqli_fetch_array($run_get_fallas))
    {

        $tiempo_reporte = $row_fallas['inicio'];
        $id_falla = $row_fallas['id'];
                
        $actual = strtotime($tiempo_actual);
        $report = strtotime($tiempo_reporte);
        $diferencia  = $actual-$report;

        echo "<div class='bg-danger'>reporte: <h2>".$report."</h2></div>";
        echo "<div class='bg-danger'>actual: <h2>".$actual."</h2></div>";
        echo "<div class='bg-danger'>diferencia: <h2>".$diferencia."</h2></div>";

                
        if($diferencia > 400)//3900
        {
           
            /**************************Tipos de errores */
            $error     = $row_fallas['tipo_error'];//error sacado de base de datos

        
            if($error == 'setup')
            {
                $responsable = 'maquinas';
            }
            elseif($error == 'caida' || $error == 'agua' || $error == 'energia' )
            {
                $responsable = 'mantenimiento';
            }
            elseif($error == 'falta_material' || $error == 'reemplazo' )
            {
                $responsable = 'materiales';
            }
            elseif($error == 'qap' || $error == 'producto' )
            {
                $responsable = 'ingenieria calidad';
            }
            elseif($error == 'sop' )
            {
                $responsable = 'ingenieria';
            }
            /****************************Tipos de errores */

            $maquina_nombre         = $row_fallas['maquina_nombre'];
        
            $planta_id          = $row_fallas['planta_id'];
            
            $planta_nombre          = $row_fallas['planta_nombre'];
            
            $departamento_id        = $row_fallas['departamento_id'];
            
            $departamento_nombre    = $row_fallas['departamento_nombre'];//departamento para enviar el correo
            
            $maquina_centro_trabajo = $row_fallas['maquina_centro_trabajo'];
            
            $maquina_serie          = $row_fallas['maquina_serie'];
            
            $maquina_numero_control = $row_fallas['maquina_numero_control'];
            
            $mensaje_operador       = $row_fallas['descripcion_operador'];

            $info_departamento      = $row_fallas['info_departamento'];

            //responsables del departamento
            $get_responsable = "SELECT user_telefono, user_email, user_nombre FROM users WHERE user_puesto = 'gerente planta'";
            $responsable_result = mysqli_query($connection, $get_responsable);


             /***********************************Enviar correo electronico a responsables de departamento*/

            while($row_responsable = mysqli_fetch_array($responsable_result))
            {
                //tomando el telefono y cambiando formato a puros numeros
                $numero_telefono = $row_responsable['user_telefono'];
                $sms_number = preg_replace("/[^0-9]/", "", $numero_telefono);

                //tomando el correo electronico del responsable
                $destino = $row_responsable['user_email'];

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                                                    // Passing `true` enables exceptions
                try {
                    
                    
                    $mail->SMTPDebug = 2;                                                       // Enable verbose debug output
                    $mail->isSMTP();                                                            // Set mailer to use SMTP
                    $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';                // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                                                     // Enable SMTP authentication
                    $mail->Username = 'smartsender@smartstudios.net';                           // SMTP username
                    $mail->Password = 'smartsender!';                                           // SMTP password
                    $mail->SMTPSecure = 'tls';                                                  // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                                          // TCP port to connect to 587
                    //antes en 465

                    //Recipients
                    $mail->setFrom('smartsender@smartstudios.net', 'Alerta Andon 4hrs');
                    
                    $mail->addAddress($destino, 'Responsable');                                 // Add a recipient
                                        
                    $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');
                    

                

                    //Content
                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                    $mail->Body    = 'Alerta de 2hr a Gerente General: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';
                    $mail->AltBody = 'Alerta de 2hr a Gerente General: '.$destino_s.'<br/> Equipo: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br> Mensaje de produccion: '.$mensaje_operador.'<br/>'.$info_departamento.'.';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
                /*****************************Termina Enviar correo electronico */

                /*****************************Enviar mensaje de texto*/ 
    
                $config = [
                    'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                    'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                    'phone_number'=>'+14702803099'
                ];
                //+1 para usa
                $numero  = "+52".$sms_number;
                $mensaje = "Alerta a Gerente General: Error en $maquina_nombre\r\n estacion: $maquina_centro_trabajo\r\n #control: $maquina_numero_control\r\n planta: $planta_nombre\r\n departamento: $departamento_nombre \r\n error: $error \r\n Mensaje: $mensaje_operador \r\n $info_departamento";
    
                if(strlen($numero)==13)
                {
                    
                    $client = new Client($config['account_sid'], $config['auth_token']);
                    $sms = $client->messages->create(
                        $numero,
                        array(
                            "from" => $config['phone_number'],
                            "body" => $mensaje
                        )
                    );
    
    
                    echo "mensaje enviado";
    
                }

                /*****************************Termina mensaje de texto */

            }//termina while 

            
        
        
            $update_escala = "UPDATE martech_fallas SET escala_5 ='si' WHERE id = $id_falla";
            $run_update_escala = mysqli_query($connection, $update_escala);
        
        }//termina if


    }//termina while loop

}




function escalacion5()
{
    global $connection;

    $tiempo_actual = date("Y-m-d H:i:s");
            
    $get_fallas = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' AND escala_5 = 'si' AND escala_6 = 'no'";
    $run_get_fallas = mysqli_query($connection, $get_fallas);
    while($row_fallas = mysqli_fetch_array($run_get_fallas))
    {

        $tiempo_reporte = $row_fallas['inicio'];
        $id_falla = $row_fallas['id'];
                
        $actual = strtotime($tiempo_actual);
        $report = strtotime($tiempo_reporte);
        $diferencia  = $actual-$report;

        echo "<div class='bg-danger'>reporte: <h2>".$report."</h2></div>";
        echo "<div class='bg-danger'>actual: <h2>".$actual."</h2></div>";
        echo "<div class='bg-danger'>diferencia: <h2>".$diferencia."</h2></div>";

                
        if($diferencia > 450)//86400
        {
           
            /**************************Tipos de errores */
            $error     = $row_fallas['tipo_error'];//error sacado de base de datos

        
            if($error == 'setup')
            {
                $responsable = 'maquinas';
            }
            elseif($error == 'caida' || $error == 'agua' || $error == 'energia' )
            {
                $responsable = 'mantenimiento';
            }
            elseif($error == 'falta_material' || $error == 'reemplazo' )
            {
                $responsable = 'materiales';
            }
            elseif($error == 'qap' || $error == 'producto' )
            {
                $responsable = 'ingenieria calidad';
            }
            elseif($error == 'sop' )
            {
                $responsable = 'ingenieria';
            }
            /****************************Tipos de errores */

            $maquina_nombre         = $row_fallas['maquina_nombre'];
        
            $planta_id          = $row_fallas['planta_id'];
            
            $planta_nombre          = $row_fallas['planta_nombre'];
            
            $departamento_id        = $row_fallas['departamento_id'];
            
            $departamento_nombre    = $row_fallas['departamento_nombre'];//departamento para enviar el correo
            
            $maquina_centro_trabajo = $row_fallas['maquina_centro_trabajo'];
            
            $maquina_serie          = $row_fallas['maquina_serie'];
            
            $maquina_numero_control = $row_fallas['maquina_numero_control'];
            
            $mensaje_operador       = $row_fallas['descripcion_operador'];

            $info_departamento      = $row_fallas['info_departamento'];

            //responsables del departamento
            
                
                $sms_number = "+526862594318";

                //tomando el correo electronico del responsable
                $destino = "joseluisgomezcecegna@gmail.com";

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
                try {
                    //Server settings
                    //$destinatario = "info@mexicalipaginasweb.com";
                    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
                    $mail->isSMTP();                                      // Set mailer to use SMTP
                    $mail->Host = 'mail.smartstudios.net;mail.smartstudios.net';  // Specify main and backup SMTP servers
                    $mail->SMTPAuth = true;                               // Enable SMTP authentication
                    $mail->Username = 'smartsender@smartstudios.net';                 // SMTP username
                    $mail->Password = 'smartsender!';                           // SMTP password
                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                    $mail->Port = 587;                                    // TCP port to connect to 587
                    //antes en 465

                    //Recipients
                    $mail->setFrom('smartsender@smartstudios.net', 'Error en Equipo');
                    
                    $mail->addAddress($destino, 'Responsable');     // Add a recipient
                                        
                    $mail->addReplyTo('noreply@martechmedical.com', 'Information');
                    

                

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Falla en'.$maquina_nombre.'!';
                    $mail->Body    = 'Mensaje a VP : '.$destino.'<br/> Se reporto un error en: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br>Mensaje del operador: '.$mensaje_operador.'<br>'.$info_departamento;
                    $mail->AltBody = 'Mensaje a VP : '.$destino.'<br/> Se reporto un error en: <b>'.$maquina_nombre.'</b><br/>Planta: <b>'.$planta_nombre.'</b><br/>Linea: <b>'.$departamento_nombre.'</b><br/>Centro de trabajo: <b>'.$maquina_centro_trabajo.'</b><br/>Numero de control: <b>'.$maquina_numero_control.'</b><br/>Error:'.$error.'<br>Mensaje del operador: '.$mensaje_operador.'<br>'.$info_departamento;

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }
                /*****************************Termina Enviar correo electronico */

                /*****************************Enviar mensaje de texto */
                
    
                $config = [
                    'account_sid'=>'AC6a7b5e8ab4edb7ea1279d75b37213883',
                    'auth_token' =>'d6ff655b7e0856bb32e80478e82a5859',
                    'phone_number'=>'+14702803099'
                ];
                //+1 para usa
                $numero  = "+52"."6862594318";
                $mensaje = "VP: Error en $maquina_nombre\r\n estacion: $maquina_centro_trabajo\r\n #control: $maquina_numero_control\r\n planta: $planta_nombre\r\n departamento: $departamento_nombre \r\n error: $error \r\n Mensaje: $mensaje_operador \r\n $info_departamento";
    
                if(strlen($numero)==13)
                {
                    
                    $client = new Client($config['account_sid'], $config['auth_token']);
                    $sms = $client->messages->create(
                        $numero,
                        array(
                            "from" => $config['phone_number'],
                            "body" => $mensaje
                        )
                    );
    
    
                    echo "mensaje enviado";
    
                }

                /*****************************Termina mensaje de texto */

          
            
        
        
            $update_escala = "UPDATE martech_fallas SET escala_6 ='si' WHERE id = $id_falla";
            $run_update_escala = mysqli_query($connection, $update_escala);
        
        }//termina if


    }//termina while loop

}



function executeAndon()
{
    escalacion1();
    #escalacion2();
    #escalacion3();
    #escalacion4();
    #escalacion5();
}











/************************************************DEBUGGING credenciales de nueva cuenta de prueba de twilio */
function enviarMensaje1()
{
    if(isset($_POST['enviar']))
    {
        $config = [
            'account_sid'=>'ACba1567c6bfe34dec7e1aea509d333115',
            'auth_token' =>'6957e6f1165e7016f26ccf92b33c463c',
            'phone_number'=>'+17069141960'
        ];
    
        $numero  = "+526862594318";
        $mensaje = "mensaje nuevo!";
        
                    //$client = new Services_Twilio($config['account_sid'], $config['auth_token']);
                    //$client->account->messages->SendMessage($config['phone_number'],$numero,$mensaje);
        
                    $client = new Client($config['account_sid'], $config['auth_token']);
                    $sms = $client->messages->create(
                        $numero,
                        array(
                            "from" => $config['phone_number'],
                            "body" => $mensaje
                        )
                    );
        
        
                    echo "mensaje enviado";
    }

    
}



?>