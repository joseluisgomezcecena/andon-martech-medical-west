<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<?php
$query = mysqli_query($connection,"SELECT * FROM users WHERE user_name= '$usuario'");
$row = mysqli_fetch_array($query);
editarPerfil();
?>

<section class="content-header">
    <h1>
        Mi perfil
        <small>Editar perfil</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Mi perfil</a></li>
        <li class="active">Editar perfil</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Datos de mi perfil</h3>
                </div>
                <!-- /.box-header -->


                <!-- form start ../para entrar al archivo register.php por que estamos en views/usuarios/ -->
                <form role="form" method="post">
                    <div class="box-body">


                        <div class="form-group col-lg-6">
                            <!-- the user name input field uses a HTML5 pattern check -->
                            <label for="login_input_username">Nombre de usuario (solo letras y numeros, de 2 a 64 caracteres)</label>
                            <input id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" value="<?php echo $row['user_name'] ?>" required disabled />
                        </div>

                        <div class="form-group col-lg-6">
                            <!-- the email input field uses a HTML5 email type check -->
                            <label for="login_input_email">Correo electronico</label>
                            <input id="login_input_email" class="login_input form-control" type="email" name="user_email" required value="<?php echo $row['user_email'] ?>" />
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="login_input_password_new">Contraseña (minimo 6 caracteres)</label>
                            <input id="login_input_password_new" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="login_input_password_repeat">Repetir contraseña</label>
                            <input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
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