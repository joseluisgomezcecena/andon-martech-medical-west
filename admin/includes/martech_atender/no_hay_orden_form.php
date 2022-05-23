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

//atenderFalla();
reportarFalta();

?>


<form method="post">
    <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div style="border-radius:0;" class="modal-dialog">
            <div class="modal-content">
                <div style="background-color: red; color:darkred;" class="modal-header bg-danger text-light">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="memberModalLabel">Reportar Falta de Material en Almacen</h4>
                </div>
                <div style="border-radius: 0" class="modal-body">


                    <?php
                    //MENSAJES DEL MODAL
                    $display = "";

                    if($error == "false")
                    {
                        $display = "style='display:none;'";
                        echo "<b>Se ha registrado la atención.</b><p>Cuando el error sea solucionado, no olvide registrarlo</p>";
                    }
                    
                    elseif($error == "incomplete")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Debes llenar la descripcion</h2></div>";
                    }

                    elseif($error == "true")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Error en query de bd</h2></div>";
                    }
                
                    else
                    {
                        echo "";
                    }
                    //MENSAJES DEL MODAL
                    ?>



                    <section <?php echo $display ?> class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div>
                                    <div>
                                        <h3>
                                            Confirmar Falta de: 
                                            <?php 
                                                echo $row_data['orden'];
                                            ?>

                                            <?php 
                                                echo $row_data['parte'];
                                            ?>

                                        </h3>
                                        <p>
                                        Descripción del operador: <?php echo $row_data['descripcion_operador']; ?>
                                        </p>
                                    </div>

                                    <!-- /.box-header -->
                                    <div >


                                        <?php 
                                        if($tipo == "setup")
                                        {
                                        ?>
                                        <input type="hidden" name="error_operador" value="no">



                                        <?php
                                        }
                                        else
                                        { 
                                        ?>
                                        <div class="form-group">
                                            <label>Confirmar</label>
                                            <select class="form-control" name="confirmar_falta" id="confirmar_falta" required>
                                                <option value="no">Selecciona</option>
                                                <option value="si">Si</option>
                                                <option value="no">No</option>
                                            </select>
                                            <small>Campo obligatorio</small>
                                        </div>
                                        <?php
                                        } 
                                        ?>
                                        


                                        
                                        
                                        <div class="form-group" id="descripcion_atencion">
                                            <label>Mensaje (Opcional)</label>
                                            <textarea name="descripcion" class="form-control" ></textarea>
                                            <small>Campo opcional</small>
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

                    <input <?php echo $display ?> type="submit" class="btn btn-danger" name="submit_falta" value="Reportar Falta">
                   
                    <button  type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

</form>
