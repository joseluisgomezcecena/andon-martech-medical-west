<?php

//Get data
if(isset($_GET['error']))
{
    $error = $_GET['error'];
}
else
{
    $error ="";
}
if(isset($_GET['op_error']))
{
    $op_error = $_GET['op_error'];
}
else
{
    $op_error ="";
}

//Get data
$error_id    = $_GET['id'];

$error_data = mysqli_query($connection, "SELECT * FROM martech_fallas WHERE id = $error_id");
$row_data = mysqli_fetch_array($error_data);
$tipo = $row_data['tipo_error'];

fixFalla();


?>


<form method="post" autocomplete="off">


    <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div style="border-radius:0" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="memberModalLabel">Atención de error en equipos</h4>
                </div>
                <div style="border-radius: 0" class="modal-body">


                    <?php
                    // MENSAJES DEL MODAL
                    $display = "";

                    if($error == "false")
                    {
                        $display = "style='display:none;'";
                        echo "<b>Se ha registrado la solución.</b>";
                    }
                    
                    elseif($error == "incomplete")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Debes llenar la descripcion</h2></div>";
                    }

                    elseif($error == "passwd")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Error en Contraseña</h2></div>";
                    }

                    elseif($error == "ocupado")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>No se puede antender 2 eventos al mismo tiempo.</h2></div>";
                    }

                    elseif($error == "true")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Error en query de bd</h2></div>";
                    }
                
                    else
                    {
                        echo "";
                    }
                    // MENSAJES DEL MODAL
                    ?>



                    <section <?php echo $display ?> class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    <div>
                                        <h3>
                                            Aviso:
                                        </h3>
                                        <p>
                                        La hora de resolución quedara registrada al enviar este reporte, favor de llenar lo antes posible.
                                        </p>
                                    </div>
                                    <!-- /.box-header -->
                                    <div>

                                        <?php if($tipo == "setup"){ ?>

                                        <div class="form-group" id="descripcion_atencion">
                                            <label>Contraseña privada de producción</label>
                                            <input class="form-control" type="password" name="contraproduccion" required autocomplete="new-password" >
                                            <small>Campo Obligatorio</small>
                                        </div>
                                        
                                        <?php } ?>



                                        <?php if($tipo == "setup"){  ?>
                                            <div class="form-group">
                                                <label>Quien atiende</label>
                                                <select class="form-control" name="resolvio" id="resolvio" required>
                                                    <?php 
                                                    $get_tecnicos = "SELECT * FROM tecnicos_moldeo";
                                                    $run_get = mysqli_query($connection, $get_tecnicos);
                                                    while($row = mysqli_fetch_array($run_get))
                                                    {
                                                        echo "<option>".$row['user_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <small>Campo opcional</small>
                                            </div>
                                        <?php } ?>


                                        <?php if($tipo == "ensamble"){  ?>
                                            <div class="form-group">
                                                <label>Quien atiende</label>
                                                <select class="form-control" name="resolvio" id="resolvio" required>
                                                    <?php
                                                    $get_tecnicos = "SELECT * FROM tecnicos_ensamble";
                                                    $run_get = mysqli_query($connection, $get_tecnicos);
                                                    while($row = mysqli_fetch_array($run_get))
                                                    {
                                                        echo "<option>".$row['user_name']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <small>Campo opcional</small>
                                            </div>
                                        <?php } ?>


                                        
                                        <div class="form-group" id="descripcion_atencion">
                                            <label>Descripción (Obligatorio)</label>
                                            <textarea name="descripcion" class="form-control" ></textarea>
                                            <small>Campo Obligatorio</small>
                                        </div>

                                    
                                
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                    </section>


    


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_falla" value="<?php echo $error_id ?>">

                    <input <?php echo $display ?> type="submit" class="btn btn-success" name="submit" value="Registrar Solución">
                   
                    <button  type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

</form>
