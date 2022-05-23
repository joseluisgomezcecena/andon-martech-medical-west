<?php require_once ("includes/topmenu.php");?>
<?php require_once ("includes/sidebar.php");?>
<?php include ("includes/martech_asignar/asignar_modals.php"); ?>

<?php 
if(isset($_GET['asignar']))
{
    include ("includes/martech_asignar/asignar_form.php");
}
elseif(isset($_GET['liberar']))
{
    include ("includes/martech_asignar/liberar_form.php");
}
?>





<section class="content-header">
    <h1>
        Asignar
        <small>Asignar equipos</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Asignar</a></li>
        <li class="active">Asignar equipos</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Equipo</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                
                <?php 
                $id = $_GET['id'];
                $get_maquina = "SELECT * FROM martech_maquinas WHERE id = $id";
                $result_get_mquina = mysqli_query($connection, $get_maquina);
                $row_maquina = mysqli_fetch_array($result_get_mquina);

                $planta_query = mysqli_query($connection, "SELECT * FROM martech_plantas WHERE id = {$row_maquina['planta_id']}");
                $row_planta = mysqli_fetch_array($planta_query);

                $departamento_query = mysqli_query($connection, "SELECT * FROM martech_departamentos WHERE id = {$row_maquina['departamento_id']}");
                $row_depa = mysqli_fetch_array($departamento_query);


                $encargado_query = mysqli_query($connection, "SELECT * FROM users WHERE user_id = {$row_maquina['responsable_id']}");
                $row_encargado = mysqli_fetch_array($encargado_query);

                ?>

                <strong><h4 class="text-primary"><?php echo $row_maquina['nombre'] ?></h4></strong>
                #Serie: <?php echo $row_maquina['serie'] ?><br/>
                #Control: <?php echo $row_maquina['numero_control'] ?><br>
                Centro de trabajo: <?php echo $row_maquina['centro_trabajo'] ?><br>
                Planta: <?php echo $row_planta['nombre'] ?><br>
                Departamento: <?php echo $row_depa['nombre'] ?><br/>
                Encargado:
                <?php
                if($row_maquina['responsable_id']==0)
                {
                    echo "<b class='text-danger'>No asignado</b>";
                    $disable = "disabled";
                }
                else
                {
                    echo "<b class='text-success'>".$row_encargado['user_nombre']." ".$row_encargado['user_apellido']." ".$row_encargado['user_numero']."</b>";
                    $disable = "";
                }
                ?>
                <br/>
                <br>

                <a <?php echo $disable ?> href='index.php?page=asignar_maquina&id=<?php echo $id ?>&user_id=<?php echo $row_maquina['responsable_id'] ?>&liberar=true' class='btn btn-danger'>Liberar</a>
                
                </div>

            </div>
        </div>
    </div>
</section>                


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Empleados</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:10px;">Numero de Empleado</th>
                            <th style="font-size:10px;">Nombre y Apellido</th>
                            <th style="font-size:10px;">Puesto</th>
                            <th style="font-size:10px;">Equipos Asignados</th>
                            <th style="font-size:10px;">Asignar/Liberar</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $id = $_GET['id'];
                                $query = mysqli_query($connection,"SELECT * FROM users WHERE admin_role != 're' ");
                                while($row = mysqli_fetch_array($query)):
                                
                                    $user_id = $row['user_id'];
                                    $nombre  = $row['user_nombre']." ".$row['user_apellido']." ".$row['user_numero'];
                                    $puesto  = $row['user_puesto'];
                                    $numero  = $row['user_numero'];
                            ?>
                            <tr>
                                <td><?php echo $numero ?></td>
                                <td><?php echo $nombre ?></td>
                                <td><?php echo $puesto ?></td>
                                <td>
                                    <?php
                                    $query_asignados = mysqli_query($connection,"SELECT * FROM martech_maquinas WHERE responsable_id = $user_id");
                                    while($row_asignados = mysqli_fetch_array($query_asignados))
                                    {
                                        $asignados = <<<DELIMITER
                                        
                                        Equipo: {$row_asignados['nombre']}<br/>
                                        Centro: {$row_asignados['centro_trabajo']}<br/><br>
                                    
DELIMITER;
echo $asignados;
                                    }
                                    ?>
                                </td>
                                <td><a href="index.php?page=asignar_maquina&user_id=<?php echo $user_id ?>&id=<?php echo $id ?>&asignar=true" class="btn btn-primary">Opciones</a></td>
                            </tr>

                            <?php endwhile; ?>
                        </tbody>

                        <tfoot>
                        
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>