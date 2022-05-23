<?php
/**
 * Created by PhpStorm.
 * User: josel
 * Date: 8/10/2017
 * Time: 7:08 PM
 */

function user_list()
{

    $usuario_actual = $_SESSION['user_name'];

    global $connection;
    $query = mysqli_query($connection,"SELECT * FROM users WHERE user_name != '$usuario_actual' AND user_name != 'joseluisgomezcecegna'") ;
    while($row = mysqli_fetch_array($query))
    {
        $puesto = $row['user_puesto'];
        
        $tabla = <<<DELIMITER
                        <tr>
                            <td>{$row['user_name']}</td>
                            <td>{$row['user_email']}</td>
                            <td>{$puesto}</td>
                            <td><a href="index.php?page=usuario_editar&id={$row['user_id']}" class="btn btn-default">Editar</a></td>
                            <td><a href="index.php?page=usuario_borrar&id={$row['user_id']}" class="btn btn-danger">Eliminar</a></td>
                        </tr>
DELIMITER;
        echo $tabla;
    }

}




function editar_usuario()
{
    global $connection;

    if(isset($_POST['submit']))
    {
        $id             = $_GET['id'];
        $email          = $_POST['user_email'];
        $passnew        = $_POST['user_password_new'];
        $passrep        = $_POST['user_password_repeat'];
        $user_telefono  = $_POST['user_telefono'];
        $nivel          = $_POST['nivel'];
        $super          = $_POST['super'];
        $super2         = $_POST['super2'];
        $super3         = $_POST['super3'];
        $nombre         = $_POST['nombre'];
        $apellido       = $_POST['apellido'];
        $numero         = $_POST['numero'];
        $puesto         = $_POST['puesto'];

        if($passnew == $passrep)
        {
            $query = mysqli_query($connection,"SELECT user_email FROM users WHERE user_id != $id AND user_email = '$email'") ;
            if(mysqli_num_rows($query)>0)
            {
                $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El correo electronico ya esta registrado por otro usuario.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
                echo $modal;
            }
            else
            {
                //check para ver si los campos de contraseña estan vacios.
                if(strlen($passnew) > 0 && strlen($passrep) > 0)
                {
                    //encriptar contraseña
                    $user_password_hash = password_hash($passnew, PASSWORD_DEFAULT);
                }
                
                //asignar admin role
                if($_POST['nivel'] == 'administrador'|| $_POST['nivel'] == 'software' || $_POST['nivel'] == 'gerente planta' || $_POST['nivel'] == 'gerente operaciones' || $_POST['nivel'] == 'gerente produccion')
                {
                    $admin_role = 'si';
                }
                else
                {
                    $admin_role = 'no';
                }

                
                //check para ver si los campos de contraseña estan vacios y adaptar el query.
                if(strlen($passnew) > 0 && strlen($passrep) > 0)
                {
                    $edit = "UPDATE users SET user_email = '$email', user_password_hash = '$user_password_hash', admin_role = '$admin_role', user_puesto = '$nivel', super = '$super', super2 = '$super2', super3 = '$super3', user_nombre = '$nombre', user_apellido = '$apellido', puesto = '$puesto', user_numero = '$numero', user_telefono = '$user_telefono' WHERE user_id = $id";
                }
                else
                {
                    $edit = "UPDATE users SET user_email = '$email', admin_role = '$admin_role', user_puesto = '$nivel', super = '$super', super2 = '$super2', super3 = '$super3', user_nombre = '$nombre', user_apellido = '$apellido', puesto = '$puesto', user_numero = '$numero', user_telefono = '$user_telefono' WHERE user_id = $id";
                }

                
                $result = mysqli_query($connection, $edit);
                if($result)
                {
                    header("Location: index.php?page=lista_usuarios&edited=si&email=$email&password=$passnew&role=$nivel&query=$edit");
                }
                else
                {
                    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Hubo un error al editar al usuario, intente de nuevo.</p>
                                                <p>Query = $edit</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
                    echo $modal;
                }
            }

        }
        else
        {
            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Las contraseñas no coinciden, intente de nuevo.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
            echo $modal;
        }




    }

}




function eliminar_usuario()
{
    global $connection;
    if(isset($_POST['submit']))
    {
        $id = $_GET['id'];
        $query = "DELETE FROM users WHERE user_id = $id";
        $result = mysqli_query($connection, $query);
        if(!$result)
        {
            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Hubo un error al eliminar al usuario. Intente de nuevo.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
            echo $modal;
        }
        else
        {
            header("Location: index.php?page=lista_usuarios&deleted=si");
        }
    }
}


/******************************************perfil******************************************/
function editarPerfil()
{
    global $connection;

    if(isset($_POST['submit']))
    {

        $usuario  = $_SESSION['user_name'];
        $email    = $_POST['user_email'];
        $passnew  = $_POST['user_password_new'];
        $passrep  = $_POST['user_password_repeat'];

        if($passnew == $passrep)
        {
            $query = mysqli_query($connection,"SELECT user_email FROM users WHERE user_name != '$usuario' AND user_email = '$email'") ;
            if(mysqli_num_rows($query)>0)
            {
                $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El correo electronico ya esta registrado por otro usuario.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
                echo $modal;
            }
            else
            {
                //encriptar contraseña
                $user_password_hash = password_hash($passnew, PASSWORD_DEFAULT);

                $edit = "UPDATE users SET user_email = '$email', user_password_hash = '$user_password_hash' WHERE user_name = '$usuario'";
                $result = mysqli_query($connection, $edit);
                if($result)
                {

                    #success!
                    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Tu perfil fue editado con exito.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
                    echo $modal;

                }
                else
                {
                    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Hubo un error al editar al usuario, intente de nuevo.</p>
                                                <p>Query = $edit</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
                    echo $modal;
                }
            }

        }
        else
        {
            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div style="background-color: #f23c14; color: white;" class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de mpw.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>Las contraseñas no coinciden, intente de nuevo.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
            echo $modal;
        }
    }

}


