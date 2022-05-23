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
        Metas
        <small>Metas por equipos </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Metas</a></li>
        <li class="active">Metas por equipos</li>
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
                            <th style="font-size:10px;"># Control</th>
                            <th style="font-size:10px;">Centro de trabajo</th>
                            <th style="font-size:10px;">Meta Actual</th>
                            <th style="font-size:10px;">Meta</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $query = mysqli_query($connection,"SELECT * FROM martech_maquinas ORDER BY planta_id, departamento_id");
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

                                    $id_maquina = $row['id']; 
                                    $query_meta = "SELECT meta FROM martech_meta_maquina WHERE maquina_id = $id_maquina AND end = 0";
                                    $r_meta     = mysqli_query($connection, $query_meta);
                                    $row_meta = mysqli_fetch_array($r_meta);
                                    
                                    if($meta = $row_meta['meta'] == 0)
                                    {
                                        $meta = "N/A";
                                    }
                                    else
                                    {
                                        $meta = $row_meta['meta'];
                                    }
                                    $tabla = <<<DELIMITER
                                    
                                    <tr>
                                        <td style="font-size:11px;">{$row['nombre']}</td>
                                        <td style="font-size:11px;">$planta $departamento</td>
                                        <td style="font-size:11px;">{$row['numero_control']}</td>
                                        <td style="font-size:11px;">{$row['centro_trabajo']}</td>
                                        <td style="font-size:11px; font-weight:bold; color: #3c8dbc;">$meta</td>
                                        <td style="font-size:11px;">
                                        <a style="font-size:11px;" class="btn btn-default" href="index.php?page=meta_maquina&id={$row['id']}">Cambiar</a>
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