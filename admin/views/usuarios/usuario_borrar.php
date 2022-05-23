<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<?php
$id = $_GET['id'];
$query = mysqli_query($connection,"SELECT * FROM users WHERE user_id = $id");
$row = mysqli_fetch_array($query);
eliminar_usuario();
?>

<section class="content-header">
    <h1>
        Usuarios
        <small>Eliminar usuarios</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Usuarios</a></li>
        <li class="active">Eliminar usuarios</li>
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
                <form role="form" method="post">
                    <div class="box-body">


                        <div class="form-group col-lg-4">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Nombre de usuario</label>
                            <input id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" value="<?php echo $row['user_name'] ?>"  disabled />
                        </div>

                        <div class="form-group col-lg-4">
                            <!-- the email input field uses a HTML5 email type check -->
                            <label for="login_input_email">Correo electronico</label>
                            <input id="login_input_email" class="login_input form-control" type="email" name="user_email"  value="<?php echo $row['user_email'] ?>" disabled/>
                        </div>


                        <div class="form-group col-lg-4">
                            <label for="login_input_password_repeat">Nivel de usuario</label>
                            <select class="form-control" name="nivel"  disabled>
                                <option value="">Seleccione nivel de usuario</option>
                                <option <?php $nivel = $row["admin_role"]; if($nivel == "no"){echo "selected";} ?> value="no">Trabajador</option>
                                <option <?php $nivel = $row["admin_role"]; if($nivel == "si"){echo "selected";} ?> value="si">Administrador</option>
                            </select>
                        </div>



                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <div class="form-group col-lg-12">
                            <input type="submit" class="btn btn-danger" name="submit" value="Confirmar" />
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>


<!-- Modal -->
<div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div style="border-radius:0" class="modal-dialog">
        <div class="modal-content">
            <div style="background-color: red; color: white;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="memberModalLabel"><i class="fa fa-warning"></i> Advertencia</h4>
            </div>
            <div style="border-radius: 0" class="modal-body">
                <p>Esta por borrar un usuario. Para eliminar haga click en el boton de <b>Confirmar</b></p>
                <p><b>Esta acción no se puede deshacer.</b></p>
            </div>
            <div class="modal-footer">
                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>