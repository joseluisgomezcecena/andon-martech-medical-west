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


//Get data
$user_id    = $_GET['id'];


asignarUsuarioPlanta();


?>

<?php
//funciones
?>

<form method="post">


    <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div style="border-radius:0" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="memberModalLabel">Asignación de plantas</h4>
                </div>
                <div style="border-radius: 0" class="modal-body">


                    <?php

                    $style = "";

                    if($error == "false")
                    {
                        $style = "style='display:none;'";
                        echo "<div class='col-lg-12 bg-info text-center'><h2>Se ha asignado el usuario</h2></div>";
                    }
                    
                    elseif($error == "duplicated")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Esta planta ya esta asignada a este gerente.</h2></div>";
                    }

                    elseif($error == "true")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Error al asignar al usuario</h2></div>";
                    }
                
                    else
                    {
                        echo "";
                        
                    }
                    ?>



    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div <?php echo $style; ?> class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            Aviso:
                        </h3>
                        <p>
                        Al asignar una planta a una persona esta recibira notificaciones del estado de los equipos de la planta
                        </p>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">


                        <h5>Se asignara la siguiente planta al usuario</h5>

                        <div class="form-group">
                            <label>Planta</label>
                            <select class='form-control' name="id_planta" required>
                                <option value="">Seleccione una opción</option>
                                <?php 
                                $get_planta = "SELECT * FROM martech_plantas ";
                                $result_get_planta = mysqli_query($connection, $get_planta);
                                while($row_planta = mysqli_fetch_array($result_get_planta)):
                                ?>

                                <option value="<?php echo $row_planta['id'] ?>"><?php echo $row_planta['nombre'] ?></option>

                                <?php endwhile; ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <?php 
                                                
                            $encargado_query = mysqli_query($connection, "SELECT * FROM users WHERE user_id = $user_id");
                            $row_encargado = mysqli_fetch_array($encargado_query);    
                            
                            ?>

                            <label>Gerente</label>
                            <input class="form-control" value="<?php echo $row_encargado['user_nombre'] ?> <?php echo $row_encargado['user_apellido'] ?>" readonly>
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
                    <input type="hidden" name="id_maquina" value="<?php echo $machine_id ?>">
                    <input type="hidden" name="id_usuario" value="<?php echo $user_id ?>">

                    <input <?php echo $style; ?>  type="submit" name="asignar" class="btn btn-primary" value="Asignar">

                    <button  type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

</form>
