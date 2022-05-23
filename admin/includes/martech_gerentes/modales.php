<?php
if(isset($_GET['edited']))
{
    $email = $_GET['email'];
    $password = $_GET['password'];
    $nivel = $_GET['role'];
    if($nivel =="si"){$user_role = "Administrador";}else{$user_role="Trabajador";}
    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Usuario editado con exito.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El usuario fue editado con exito! con las siguientes credenciales. Guarde sus datos en un lugar seguro.</p>
                                                <p><b>Correo electronico:</b> {$email}</p>
                                                <p><b>Contraseña:</b> {$password}</p>
                                                <p><b>Nivel:</b> {$user_role}</p>
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

if(isset($_GET['deleted']))
{
    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Usuario eliminado con exito.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El usuario fue eliminado la información no podra ser recuperada</p>
                                               
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
?>
