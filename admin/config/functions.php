<?php
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function guardarMaquina()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $planta_id              = strtoupper($_POST['planta_id']);
        $departamento_id        = strtoupper($_POST['departamento_id']);
        $nombre                 = strtoupper($_POST['nombre']);
        $centro_trabajo         = strtoupper($_POST['centro_trabajo']);
        $numero_control         = strtoupper($_POST['numero_control']);
        $serie                  = strtoupper($_POST['serie']);
        
        //checar si la maquina ya esta registrada
        $check = "SELECT numero_control, nombre, serie FROM martech_maquinas WHERE numero_control = '$numero_control'";
        $run_check = mysqli_query($connection, $check);
        if(mysqli_num_rows($run_check)>0)
        {
            header("Location: index.php?page=alta_maquina&error=true&repeated=true");
        }
        else
        {
            $query = "INSERT INTO martech_maquinas (planta_id, departamento_id, nombre, centro_trabajo, 
            numero_control, serie) 
            VALUES ($planta_id, $departamento_id, '$nombre', '$centro_trabajo', '$numero_control', '$serie');";
                
            $result = mysqli_query($connection, $query);

            if($result)
            {
                header("Location: index.php?page=alta_maquina&error=false"); 
            }
            else
            {
                echo "<div class='alert alert-danger'> Ha ocurrido un error: ".$query."</div>";
                //header("Location: index.php?page=alta_maquina&error=insert");    
            }
        }
    }
}



function editarMaquina()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id_maquina             = $_GET['id'];
        $planta_id              = strtoupper($_POST['planta_id']);
        $departamento_id        = strtoupper($_POST['departamento_id']);
        $nombre                 = strtoupper($_POST['nombre']);
        $centro_trabajo         = strtoupper($_POST['centro_trabajo']);
        $numero_control         = strtoupper($_POST['numero_control']);
        $serie                  = strtoupper($_POST['serie']);
        

                    
        $query = "UPDATE martech_maquinas SET planta_id = $planta_id, departamento_id = $departamento_id, nombre = '$nombre',
        centro_trabajo = '$centro_trabajo', numero_control = '$numero_control', serie = '$serie' WHERE id = $id_maquina"; 
           
        $result = mysqli_query($connection, $query);

        if($result)
        {
            header("Location: index.php?page=lista_maquina&edited=true&error=false"); 
        }
        else
        {
            echo $query;
            //header("Location: index.php?page=lista_maquina&error=insert");    
        }
           

    }
}


function eliminarMaquina(){

    global $connection;

    if(isset($_POST['submit']))
    {
        $id = $_GET['id'];

        $query = "DELETE FROM martech_maquinas WHERE id = $id";
        $result = mysqli_query($connection, $query);
        if($result)
        {
            header("Location: index.php?page=lista_maquina&error=false&delete=true");
        }
        else
        {
            header("Location: index.php?page=lista_maquina&error=true&delte=true");
        }
    }
}



function liberarUsuarioMaquina(){

    global $connection;

    if(isset($_POST['submit']))
    {
        $id         = $_POST['id_maquina'];
        $id_usuario = $_POST['id_usuario'];

        $query = "UPDATE martech_maquinas SET responsable_id = 0, nombre_responsable = '' WHERE id = $id";
        $result = mysqli_query($connection, $query);
        if($result)
        {
            header("Location: index.php?page=asignar_maquina&id=$id&user_id=$id_usuario&liberar=true&error=false");
        }
        else
        {
            header("Location: index.php?page=asignar_maquina&id=$id&user_id=$id_usuario&liberar=true&error=true");

        }
    }
}



function asignarUsuarioMaquina(){

    global $connection;

    if(isset($_POST['submit']))
    {
        $id         = $_POST['id_maquina'];
        $id_usuario = $_POST['id_usuario'];


        $get_data = "SELECT * FROM martech_maquinas WHERE id = $id AND responsable_id != 0";
        $res_data = mysqli_query($connection, $get_data);
        if($numero = mysqli_num_rows($res_data) > 0)
        {
            header("Location: index.php?page=asignar_maquina&id=$id&user_id=$id_usuario&asignar=true&error=duplicated");
        }
        else
        {
            $query = "UPDATE martech_maquinas SET responsable_id = $id_usuario, nombre_responsable = '' WHERE id = $id";
            $result = mysqli_query($connection, $query);
            if($result)
            {
                header("Location: index.php?page=asignar_maquina&id=$id&user_id=$id_usuario&asignar=true&error=false");
            }
            else
            {
                header("Location: index.php?page=asignar_maquina&id=$id&user_id=$id_usuario&asignar=true&error=true");
    
            }
        }
    }
}





function atenderFalla()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id             = $_POST['id_falla'];
        $descripcion    = $_POST['descripcion'];
        $hora           = date("Y-m-d H:i:s");
        $error_operador = $_POST['error_operador'];

        $sesion        = $_SESSION['user_name'];
        
        $error_data = mysqli_query($connection, "SELECT * FROM martech_fallas WHERE id = $id");
        $row_data = mysqli_fetch_array($error_data);
        $tipo = $row_data['tipo_error'];
        
        if($tipo == "setup")
        {
            $atendio       = $_POST['atiende'];
        }
        else
        {
            $atendio       = $sesion;
        }

        
        if($error_operador == 'si')
        {
            $query = "UPDATE martech_fallas SET atendido = '$hora',  fin = '$hora',descripcion_encargado = '$descripcion', atendido_flag = 'si', offline = 'no', atendio = '$atendio', resolvio = '$atendio', sesion = '$sesion' WHERE id = $id;";
            $op_error = 'si';
        }
        else
        {
            $query = "UPDATE martech_fallas SET atendido = '$hora', descripcion_encargado = '$descripcion', atendido_flag = 'si', atendio = '$atendio' , sesion = '$sesion' WHERE id = $id;";    
            $op_error = 'no';
        }
        
        $result = mysqli_query($connection, $query);

        if($result)
        {
            header("Location: index.php?page=error_atender&id=$id&atencion=true&error=false&op_error=$op_error&tipo=$tipo");
        }
        else
        {
            //echo $query;
            header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
        }
    }
}



function fixFalla()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id                 = $_POST['id_falla'];
        $descripcion        = $_POST['descripcion'];
        $hora               = date("Y-m-d H:i:s");

        $resolvio           = $_POST['resolvio'];
        $sesion             = $_SESSION['user_name'];
        $contraproduccion   = $_POST['contraproduccion'];

        $error_data = mysqli_query($connection, "SELECT * FROM martech_fallas WHERE id = $id");
        $row_data = mysqli_fetch_array($error_data);
        $tipo = $row_data['tipo_error'];
        
        if($tipo == "setup" || $tipo == "ensamble")
        {
            $resolvio           = $_POST['resolvio'];
        }
        else
        {
            $resolvio           = $sesion;
        }



        if(strlen($descripcion)<1)
        {
            header("Location: index.php?page=error_atender_resolver&id=$id&atencion=true&error=incomplete");
        }
        else
        {

            $select_contra = mysqli_query($connection, "SELECT * FROM martech_produccion_password WHERE actual = 1");
            $row_contra = mysqli_fetch_array($select_contra);
            $passwd = $row_contra['password'];

            if($tipo != "setup")
            {
                $contraproduccion = $passwd;
                
            }

            if($passwd == $contraproduccion)
            {


                //si es de ensamble no puede atender 2 al mismo tiempo.
                if($tipo == "ensamble")
                {
                    $query_ocupado = "SELECT * FROM martech_fallas WHERE atendio = '$resolvio' AND offline = 'si'";
                    $run_ocupado = mysqli_query($connection, $query_ocupado);
                    if(mysqli_num_rows($connection))
                    {
                        header("Location: index.php?page=error_atender_resolver&id=$id&atencion=true&error=ocupado");
                    }
                }




                $query = "UPDATE martech_fallas SET fin = '$hora', descripcion_solucion = '$descripcion', offline = 'no', resolvio = '$resolvio',
                sesion = '$sesion' WHERE id = $id;";
                $result = mysqli_query($connection, $query);
    
                if($result)
                {
                    header("Location: index.php?page=error_atender_resolver&id=$id&atencion=true&error=false");
                }
                else
                {
                    echo $query;
                    //header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
                }

            }
            else
            {
                header("Location: index.php?page=error_atender_resolver&id=$id&atencion=true&error=passwd");
            }


           
        }
    }
}






function reportarFalta()
{

    if(true)
    {
        return;
    }

    global $connection;

    if(isset($_POST['submit_falta']))
    {
        $id             = $_POST['id_falla'];
        $descripcion    = $_POST['descripcion'];
        $confirmar      = $_POST['confirmar_falta'];

        $hora           = date("Y-m-d H:i:s");
        $sesion        = $_SESSION['user_name'];

        $error_data = mysqli_query($connection, "SELECT * FROM martech_fallas WHERE id = $id");
        $row_data = mysqli_fetch_array($error_data);

        $tipo = $row_data['tipo_error'];
        $orden = $row_data['orden'];
        $parte = $row_data['parte'];
        $descripcion_operador = $row_data['descripcion_operador'];
        $planta_id = $row_data['planta_id'];
        $departamento_id = $row_data['departamento_id'];

        if($confirmar == 'si')
        {
            /*
            $query = "UPDATE martech_fallas SET atendido = '$hora', descripcion_encargado = '$descripcion', atendido_flag = 'si', atendio = '$atendio' , sesion = '$sesion' WHERE id = $id;";
            $result = mysqli_query($connection, $query);
            if($result)
            {
                header("Location: index.php?page=error_atender&id=$id&atencion=true&error=false&op_error=$op_error&tipo=$tipo");
            }
            else
            {
                //echo $query;
                header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
            }
            */

            /*
            $get_responsible = "SELECT DISTINCT user_name, user_email, id_departamento, nombre FROM users
            LEFT JOIN martech_usuario_departamento ON users.user_id = martech_usuario_departamento.id_usuario
            LEFT JOIN martech_departamentos ON martech_usuario_departamento.id_departamento = martech_departamentos.id
            WHERE users.user_puesto = 'supervisor' AND martech_departamentos.planta_id = $planta_id";
            */

            /*
            $get_responsible = "SELECT DISTINCT user_name, user_email, id_departamento, nombre FROM users
            LEFT JOIN martech_usuario_departamento ON users.user_id = martech_usuario_departamento.id_usuario
            LEFT JOIN martech_departamentos ON martech_usuario_departamento.id_departamento = martech_departamentos.id
            WHERE users.user_puesto = 'supervisor' AND martech_departamentos.planta_id = $planta_id AND martech_departamentos.id = $departamento_id";
            */

            $get_responsible = "SELECT DISTINCT user_name, user_email, id_departamento FROM users 
            LEFT JOIN martech_usuario_departamento ON users.user_id = martech_usuario_departamento.id_usuario 
            LEFT JOIN martech_departamentos ON martech_usuario_departamento.id_departamento = martech_departamentos.id 
            LEFT JOIN martech_usuario_planta ON users.user_id = martech_usuario_planta.id_usuario 
            LEFT JOIN martech_plantas ON martech_usuario_planta.id_planta = martech_plantas.id 
            WHERE (users.user_puesto = 'supervisor' AND martech_departamentos.planta_id = $planta_id AND martech_departamentos.id = $departamento_id) 
            OR 
            (users.user_puesto = 'gerente produccion' AND martech_plantas.id = $planta_id);";


            $run = mysqli_query($connection, $get_responsible);
            while($row = mysqli_fetch_array($run))
            {
                echo $row['user_email'] . "<br><br/>";
                //echo $row['user_name'] . " " . $row['id_departamento']. " ".$row['nombre'] . "<br><br/>";


                $destino_s = $row['user_email'];
                //$destino_s = "jgomez@martechmedical.com";

                require 'mail/vendor/autoload.php';

                $mail = new PHPMailer(true);                                        // Passing `true` enables exceptions
                try {
                    $mail->SMTPDebug = 0;                                               // Enable verbose debug output
                    $mail->isSMTP(false);                                                    // Set mailer to use SMTP
                    $mail->Host = '192.168.2.8;192.168.2.8';        	// Specify main and backup SMTP servers
                    $mail->SMTPAuth = false;                                             // Enable SMTP authentication
                    $mail->Username = 'jgomez@martechmedical.com';               // SMTP username
                    $mail->Password = 'joseLuis15!';                               // SMTP password
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
                    $mail->setFrom('jgomez@martechmedical.com', 'Orden no esta en Almacen');

                    $mail->addAddress($destino_s, 'Responsable');                   // Add a recipient

                    $mail->addReplyTo('noreply@martechmedical.com', 'Andon System');




                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = "Falta Material Orden: $orden, #Parte: $parte";
                    $mail->Body    = "Falta Material Orden: $orden<br>#Parte: $parte<br><br>Mensaje Andon:$descripcion_operador<br><br>Mensaje de Almacen:$descripcion";
                    $mail->AltBody = "Falta Material Orden: $orden<br>#Parte: $parte<br><br/>Mensaje Andon:$descripcion_operador<br><br/>Mensaje de Almacen:$descripcion";

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                }



            }

            //$update = "UPDATE martech_fallas SET atendido = '$hora', descripcion_encargado = '$descripcion', atendido_flag = 'si', atendio = '$sesion' , sesion = '$sesion' WHERE id = $id;";
            $update = "UPDATE martech_fallas SET fin = '$hora', descripcion_solucion = '$descripcion', offline = 'no', resolvio = '$sesion' , sesion = '$sesion' WHERE id = $id;";
            $result_update = mysqli_query($connection, $update);


            //header("Location: index.php");

        }
        else
        {
            header("Location: index.php");

        }
    }
}





















function guardarLinea()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id     = $_POST['planta_id'];
        $nombre = $_POST['nombre'];
        

        $check = "SELECT  nombre, planta_id FROM martech_departamentos WHERE nombre = '$nombre' AND planta_id = $id";
        $run_check = mysqli_query($connection, $check);
        if(mysqli_num_rows($run_check)>0)
        {
            header("Location: index.php?page=alta_linea&error=true&repeated=true");
        }
        else
        {
            $query = "INSERT INTO martech_departamentos (planta_id, nombre) VALUES ($id, '$nombre')";
            $result = mysqli_query($connection, $query);
    
            if($result)
            {
                header("Location: index.php?page=lista_linea&add=true");
            }
            else
            {
                echo $query;
                //header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
            }
        }
    }
}



function editarLinea()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id            = $_GET['id'];
        $planta_id     = $_POST['planta_id'];
        $nombre        = $_POST['nombre'];
        

        
        $query = "UPDATE martech_departamentos SET planta_id = $planta_id, nombre = '$nombre' WHERE id = $id";
        $result = mysqli_query($connection, $query);

        if($result)
        {
            header("Location: index.php?page=lista_linea&edit=true&edited=true");
        }
        else
        {
            echo $query;
            //header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
        }

    }
}




function eliminarLinea()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id            = $_GET['id'];
        $planta_id     = $_POST['planta_id'];
        $nombre        = $_POST['nombre'];
        

        
        $query = "UPDATE martech_departamentos SET activo = 0 WHERE id = $id";
        $result = mysqli_query($connection, $query);

        if($result)
        {
            header("Location: index.php?page=lista_linea&deleted=true");
        }
        else
        {
            echo $query;
            //header("Location: index.php?page=atender&id=$id&atencion=true&error=query");
        }

    }
}




function asignarUsuarioPlanta()
{
    global $connection;

    if(isset($_POST['asignar']))
    {

        $id_planta      =   $_POST['id_planta'];
        
        $id_usuario     =   $_GET['id'];

        $select_gerentes = "SELECT * FROM martech_usuario_planta WHERE id_planta = $id_planta AND id_usuario = $id_usuario";
        $run_select = mysqli_query($connection, $select_gerentes);

        if(mysqli_num_rows($run_select)>0)
        {
            header("Location: index.php?page=lista_gerentes&asignar=true&id=$id_usuario&error=duplicated");
        }
        else
        {
            $query = "INSERT INTO martech_usuario_planta (id_usuario, id_planta) VALUES ($id_usuario, $id_planta)";
            $result = mysqli_query($connection, $query);

            if(!$result)
            {
                header("Location: index.php?page=lista_gerentes&asignar=true&id=$id_usuario&error=true");

            }
            else
            {
                header("Location: index.php?page=lista_gerentes&asignar=true&id=$id_usuario&error=false");
            }
        }

    }
}

function unassignPlant()
{
    global $connection;

    if(isset($_POST['unassign']))
    {
        $id_user    =   $_POST['id_user'];
        $id_planta  =   $_POST['id_planta'];

        $query = "DELETE FROM martech_usuario_planta WHERE id_usuario = $id_user AND id_planta = $id_planta; ";
        $result = mysqli_query($connection, $query);
        if($result)
        {
            header("Location: index.php?page=lista_gerentes&removed=true");
        }
        else
        {
            echo $query;
            //header("Location: index.php?page=lista_gerentes&removed=false");
        }
    }
}


function asignarUsuarioDepartamento()
{
    global $connection;

    if(isset($_POST['asignar']))
    {
        $id_departamento      =   $_POST['id_departamento'];
        
        $id_usuario           =   $_GET['id'];


        $select_usuarios = "SELECT * FROM martech_usuario_departamento WHERE id_departamento = $id_departamento AND id_usuario = $id_usuario";
        $run_select = mysqli_query($connection, $select_usuarios);

        if(mysqli_num_rows($run_select)>0)
        {
            header("Location: index.php?page=lista_trabajadores&asignar=true&id=$id_usuario&error=duplicated");
        }
        else
        {

            $query = "INSERT INTO martech_usuario_departamento (id_usuario, id_departamento) VALUES ($id_usuario, $id_departamento)";
            $result = mysqli_query($connection, $query);
    
            if(!$result)
            {
                header("Location: index.php?page=lista_trabajadores&asignar=true&id=$id_usuario&error=true");
    
            }
            else
            {
                header("Location: index.php?page=lista_trabajadores&asignar=true&id=$id_usuario&error=false");
            }

        }
    }
}



function unassignLine()
{
    global $connection;

    if(isset($_POST['unassign']))
    {
        $id_user          =   $_POST['id_user'];
        $id_departamento  =   $_POST['id_departamento'];

        $query = "DELETE FROM martech_usuario_departamento WHERE id_usuario = $id_user AND id_departamento = $id_departamento; ";
        $result = mysqli_query($connection, $query);
        if($result)
        {
            header("Location: index.php?page=lista_trabajadores&removed=true");
        }
        else
        {
            echo $query;
            //header("Location: index.php?page=lista_gerentes&removed=false");
        }
    }
}





function agregarMetaMaquina()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id_maquina =   $_GET['id'];
        $meta       =   $_POST['meta'];
        $fecha      =   date("Y-m-d H:i:s");
        $register   =   $_SESSION['user_name'];

        if($meta>2147483646)
        {
            header("Location: index.php?page=meta_maquina&id=$id_maquina&error=int");
        }
        else
        {
            $select = "SELECT * FROM martech_meta_maquina WHERE maquina_id = $id_maquina";
            $r_select = mysqli_query($connection, $select);
            if(mysqli_num_rows($r_select)>0)
            {
                $update = "UPDATE martech_meta_maquina SET end = 1 WHERE maquina_id = $id_maquina";
                $run_update = mysqli_query($connection, $update);
                if($run_update)
                {
                    $query = "INSERT INTO martech_meta_maquina (maquina_id, meta, fecha, register)
                     VALUES ($id_maquina, $meta, '$fecha', '$register'); ";
    
                }
                else
                {
                    die("Error en actualización de meta.");
                }
            }
            else
            {
                $query = "INSERT INTO martech_meta_maquina (maquina_id, meta, fecha, register)
                     VALUES ($id_maquina, $meta, '$fecha', '$register') ";
            }
    
                $result = mysqli_query($connection, $query);
    
                if($result)
                {
                    header("Location: index.php?page=meta_maquina&id=$id_maquina&error=false");
                }
                else
                {
                    header("Location: index.php?page=meta_maquina&id=$id_maquina&error=query");
                }
        }
       

    }
    
}



function toData()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $departamento   =   $_POST['departamento'];

        header("Location: index.php?page=estado_reporte_datos&id_departamento=$departamento");
    }

}




