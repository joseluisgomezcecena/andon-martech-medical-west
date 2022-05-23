<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php editar_usuario(); ?>

<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            //echo $error;
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
                                                <p>{$error}</p>
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
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            //echo $message;
            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje de cms.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>{$message}</p>
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
?>

<?php 
$id_usuario = $_GET['id'];
$get_usuario = mysqli_query($connection, "SELECT * FROM users WHERE user_id = $id_usuario");
$data = mysqli_fetch_array($get_usuario);
?>

<section class="content-header">
    <h1>
        Usuarios
        <small>Editar usuario</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Usuarios</a></li>
        <li class="active">Editar usuario</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Datos de usuario</h3>
                </div>
                <!-- /.box-header -->


                <!-- form start ../para entrar al archivo register.php por que estamos en views/usuarios/ -->
                <form role="form" method="post"  name="registerform">
                    <div class="box-body">




                        <div class="form-group col-lg-4">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Usuario (Solo letras y numeros)</label>
                            <input value="<?php echo $data['user_name'] ?>" id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" readonly  required />
                        </div>

                        <div class="form-group col-lg-4">
                            <!-- the email input field uses a HTML5 email type check -->
                            <label for="login_input_email">Correo electronico</label>
                            <input value="<?php echo $data['user_email'] ?>" id="login_input_email" class="login_input form-control" type="email" name="user_email"  required />
                        </div>

                        <div class="form-group col-lg-4">
                            <!-- the email input field uses a HTML5 email type check -->
                            <label for="login_input_email">Numero de celular</label>
                            <input value="<?php echo $data['user_telefono'] ?>" type="text" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask name="user_telefono">
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="login_input_password_new">Contraseña ( min 6 )</label>
                            <input id="login_input_password_new" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}"  autocomplete="off" />
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Repetir contraseña</label>
                            <input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}"  autocomplete="off" />
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Nivel de usuario</label>
                            <select class="form-control" name="nivel" id="nivel" required>
                            
                            <?php 
                            $nivel = $data['user_puesto'];

                            ?>

                                <option value="">Seleccione nivel de usuario</option>
                                <optgroup label = "Usuarios de soporte">
                                    <option <?php if($nivel == "administrador" ){echo "selected";}else{echo"";} ?> value="administrador">Administrador</option>
                                    <option <?php if($nivel == "software" ){echo "selected";}else{echo"";} ?> value="software">Desarrollo de software / it</option>
                                    <option <?php if($nivel == "mantenimiento" ){echo "selected";}else{echo"";} ?> value="mantenimiento">Mantenimiento</option>
                                    <option <?php if($nivel == "maquinas" ){echo "selected";}else{echo"";} ?> value="maquinas">Maquinas (Equipo de Saul)</option>
                                    <option <?php if($nivel == "ingenieria" ){echo "selected";}else{echo"";} ?> value="ingenieria">Ingenieria</option>
                                    <option <?php if($nivel == "ingenieria calidad" ){echo "selected";}else{echo"";} ?> value="ingenieria calidad">Ingenieria de calidad</option>
                                    <option <?php if($nivel == "materiales" ){echo "selected";}else{echo"";} ?> value="materiales">Materiales</option>
                                </optgroup>
                                <optgroup label = "Usuarios de producción">
                                    <option <?php if($nivel == "lider" ){echo "selected";}else{echo"";} ?> value="lider">Lider</option>
                                    <option <?php if($nivel == "supervisor" ){echo "selected";}else{echo"";} ?> value="supervisor">Supervisor</option>
                                    <option <?php if($nivel == "gerente produccion" ){echo "selected";}else{echo"";} ?> value="gerente produccion">Gerente de producción</option>
                                    <option <?php if($nivel == "gerente operaciones" ){echo "selected";}else{echo"";} ?> value="gerente operaciones">Gerente de operaciones</option>
                                    <option <?php if($nivel == "gerente planta" ){echo "selected";}else{echo"";} ?> value="gerente planta">Gerente de planta</option>
                                </optgroup>
                            </select>
                        </div>


                        <div id="super" class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Lider o supervisor. Andon (Escala 1)</label>
                            <select class="form-control" name="super" id="super2" >
                                <option value="">Seleccione</option>
                                <option <?php if($data['super'] == "si"){echo "selected";}else{echo "";} ?> value="si">si</option>
                                <option <?php if($data['super'] == "no"){echo "selected";}else{echo "";} ?> value="no">no</option>
                            </select>
                            <small style="color:red;">Solo usuarios de soporte</small>
                        </div>

                        <div id="super3" class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Jefe o gerente de area. Andon (Escala 2)</label>
                            <select class="form-control" name="super2" id="super4" >
                                <option value="">Seleccione</option>
                                <option <?php if($data['super2'] == "si"){echo "selected";}else{echo "";} ?> value="si">si</option>
                                <option <?php if($data['super2'] == "no"){echo "selected";}else{echo "";} ?> value="no">no</option>
                            </select>
                            <small style="color:red;">Solo usuarios de soporte</small>
                        </div>

                        <div id="super5" class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Director o gerente. Andon (Escala 3)</label>
                            <select class="form-control" name="super3" id="super6" >
                                <option value="">Seleccione</option>
                                <option <?php if($data['super3'] == "si"){echo "selected";}else{echo "";} ?> value="si">si</option>
                                <option <?php if($data['super3'] == "no"){echo "selected";}else{echo "";} ?> value="no">no</option>
                            </select>
                            <small style="color:red;">Solo usuarios de soporte</small>
                        </div>
                        

                        <div class="form-group col-lg-3">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Nombre(s)</label>
                            <input value="<?php echo $data['user_nombre'] ?>" id="login_input_username" class="login_input form-control" type="text"  name="nombre" required />
                        </div>


                        <div class="form-group col-lg-3">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Apellido(s)</label>
                            <input value="<?php echo $data['user_apellido'] ?>" id="login_input_username" class="login_input form-control" type="text"  name="apellido" required />
                        </div>


                        <div class="form-group col-lg-3">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username"># de Empleado</label>
                            <input value="<?php echo $data['user_numero'] ?>" id="login_input_username" class="login_input form-control" type="text"  name="numero" required />
                        </div>

                        <div class="form-group col-lg-3">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Puesto</label>
                            <input value="<?php echo $data['puesto'] ?>" id="login_input_username" class="login_input form-control" type="text"  name="puesto" required />
                        </div>


                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <div class="form-group col-lg-12">
                            <input type="submit" class="btn btn-primary" name="submit" value="Editar usuario" />
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>