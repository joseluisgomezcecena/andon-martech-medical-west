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
$user_id    = $_GET['user_id'];
$machine_id = $_GET['id'];

asignarUsuarioMaquina();


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
                    <h4 class="modal-title" id="memberModalLabel">Asignación de equipos</h4>
                </div>
                <div style="border-radius: 0" class="modal-body">


                    <?php

                   

                    if($error == "false")
                    {
                        
                        echo "<div class='col-lg-12 bg-info text-center'><h2>Se ha asignado el usuario</h2></div>";
                    }
                    
                    elseif($error == "duplicated")
                    {
                        echo "<div class='col-lg-12 bg-danger text-danger text-center'><h2>Este equipo ya tiene un usuario</h2></div>";
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
                <div <?php echo $style ?> class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                            Aviso:
                        </h3>
                        <p>
                        Al asignar un equipo a una persona esta recibira notificaciones del estado del equipo
                        </p>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">


                        <h5>Se asignara el siguiente equipo al usuario</h5>

                        <?php 
                        
                        $get_maquina = "SELECT * FROM martech_maquinas WHERE id = $machine_id";
                        $result_get_mquina = mysqli_query($connection, $get_maquina);
                        $row_maquina = mysqli_fetch_array($result_get_mquina);

                        $encargado_query = mysqli_query($connection, "SELECT * FROM users WHERE user_id = $user_id");
                        $row_encargado = mysqli_fetch_array($encargado_query);

                        
                        ?>

                        <table class='table table-bordered table-hover table-striped'>
                            <tr>
                                <td><?php echo $row_maquina['nombre'] ?></td>
                                <td><?php echo $row_encargado['user_nombre']." ".$row_encargado['user_apellido']." ".$row_encargado['user_numero'] ?></td>
                            
<!--
<a href="index.php?page=asignar_maquina&success=true&liberar=true&id=<?php echo $machine_id ?>&user_id=<?php echo $user_id ?>">Liberar</a>
-->
                            </tr>
                        </table>
                
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

                    <input  type="submit" name="submit" class="btn btn-primary" value="Asignar">

                    <button  type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

</form>
