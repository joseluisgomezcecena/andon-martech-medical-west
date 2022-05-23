<!----------------------------------------------mensajes--------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
        <div style="text-align: center;">
                <?php
                // show potential errors / feedback (from login object)
                if (isset($login)) {
                    if ($login->errors) {
                        foreach ($login->errors as $error) {
                            #echo "<b class='bg-danger text-danger text-center'>".$error."</b>";

                            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Error en inicio de sesión.</h4>
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
                    if ($login->messages) {
                        foreach ($login->messages as $message) {
                            //  echo "<b class='bg-info text-primary text-center'>".$message."</b>";


                            $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Mensaje del sistema.</h4>
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
            </div>

<!----------------------------------------------mensajes--------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->





<body style="background-color: #40a9f5" class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a style="color: white;" href="#"><b>Automation</b>System</a>
        <!--
        <img style="" class="img-responsive center-block" src="img/martech.gif" width="150" height="150">
        -->
    </div>
    <!-- /.login-logo -->
    <div style="box-shadow: 0px 5px 15px #000000" class="login-box-body">

        <br/>

        <img style="" class="img-responsive center-block" src="img/martech.gif" width="150" height="150">

        <br/>

        <form method="post" action="index.php" name="loginform" autocomplete="off">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="user_name" placeholder="Nombre de usuario">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="user_password" placeholder="Contraseña" autocomplete="new-password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" name="login" class="btn btn-primary btn-block btn-flat btn-lg">Ingresar</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->

        <br/><br/>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

</body>



