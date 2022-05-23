<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<?php
if(isset($_GET['add']))
{
    include("includes/system_modals/success/alta_linea.php");
}

if(isset($_GET['edited']))
{
    include("includes/system_modals/success/editar_linea.php");
}

if(isset($_GET['deleted']))
{
    include("includes/system_modals/success/eliminar_linea.php");
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
                                                <h4 class="modal-title" id="memberModalLabel">Linea eliminada con exito.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>La linea fue eliminada, si fue eliminada accidentalmente, contacte a desarrollo de software.</p>
                                               
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




<section class="content-header">
    <h1>
        Lineas
        <small>Lista de lineas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Lineas</a></li>
        <li class="active">Lista de lineas</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Lineas</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:10px;">Planta</th>
                            <th style="font-size:10px;">Linea</th>
                            <th style="font-size:10px;">Detalles</th>
                            <th style="font-size:10px;">Editar</th>
                           
                            <th style="font-size:10px;">Eliminar</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $query = mysqli_query($connection,"SELECT * FROM martech_departamentos WHERE activo = 1 ORDER BY planta_id");
                                while($row = mysqli_fetch_array($query))
                                {

                                    $id         = $row['id'];
                                    $planta_id  = $row['planta_id'];
                                    $nombre     = $row['nombre'];
                                    

                                    $query_planta = mysqli_query($connection,"SELECT nombre FROM martech_plantas WHERE id = $planta_id");
                                    $row_planta = mysqli_fetch_array($query_planta);
                                    $planta = $row_planta['nombre'];
                                    

                                    $tabla = <<<DELIMITER
                                    
                                    <tr>
                                        <td style="font-size:12px;">{$planta}</td>
                                        
                                        <td style="font-size:12px;">$nombre</td>
                                        
                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-default" href="index.php?page=detalles_linea&id={$row['id']}">Detalles</a>
                                        </td>
                                        
                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-info" href="index.php?page=editar_linea&id={$row['id']}">Editar</a>
                                        </td>
                                        

                                  




                                        <td style="font-size:12px;">
                                        <a style="font-size:12px;" class="btn btn-danger" href="index.php?page=eliminar_linea&id={$row['id']}">Eliminar</a>
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