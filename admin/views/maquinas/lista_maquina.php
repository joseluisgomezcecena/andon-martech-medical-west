<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<?php
if(isset($_GET['edited'])){
    if($_GET['error']== 'false'){
        include ("includes/system_modals/success/editar_maquina.php");
    }
}
if(isset($_GET['delete']) && isset($_GET['error'])){
    if($_GET['error']== 'false'){
        include ("includes/system_modals/success/eliminar_maquina.php");
    }
}
?>



<section class="content-header">
    <h1>
        Equipos
        <small>Lista de equipos </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Lista de equipos</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Equipos</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:10px;">Equipo</th>
                            <th style="font-size:10px;">Planta y Departamento</th>
                            <th style="font-size:10px;"># Serie</th>
                            <th style="font-size:10px;"># Control</th>
                            <th style="font-size:10px;">Centro de trabajo</th>
                            <th style="font-size:10px;">Detalles</th>
                            <th style="font-size:10px;">Editar</th>
                            <th style="font-size:10px;">Eliminar</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $query = mysqli_query($connection,"SELECT * FROM martech_maquinas ORDER BY planta_id, departamento_id ");
                                while($row = mysqli_fetch_array($query))
                                {

                                     $planta_id = $row['planta_id'];
                                     $departamento_id = $row['departamento_id'];

                                    $query_planta = mysqli_query($connection,"SELECT nombre FROM martech_plantas WHERE id = $planta_id");
                                    $row_planta = mysqli_fetch_array($query_planta);
                                    $planta = $row_planta['nombre'];
                                    
                                    $query_dep = mysqli_query($connection,"SELECT nombre FROM martech_departamentos WHERE id = $departamento_id");
                                    $row_dep = mysqli_fetch_array($query_dep);
                                    $departamento = $row_dep['nombre'];

                                   

                                    $tabla = <<<DELIMITER
                                    
                                    <tr>
                                        <td style="font-size:12px;">{$row['nombre']}</td>
                                        <td style="font-size:12px;">$planta $departamento</td>
                                        <td style="font-size:12px;">{$row['serie']}</td>
                                        <td style="font-size:12px;">{$row['numero_control']}</td>
                                        <td style="font-size:12px;">{$row['centro_trabajo']}</td>
                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-default" href="index.php?page=detalles_maquina&id={$row['id']}">Detalles</a>
                                        </td>
                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-info" href="index.php?page=editar_maquina&id={$row['id']}">Editar</a>
                                        </td>
                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-danger" href="index.php?page=eliminar_maquina&id={$row['id']}">Eliminar</a>
                                        </td>
                                    </tr>

DELIMITER;
echo $tabla;
                                }
                            ?>
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