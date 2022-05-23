<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
$id = $_GET['id'];
eliminarLinea();
?>



<section class="content-header">
    <h1>
        Lineas
        <small>Eliminar lineas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?page=lista_linea"><i class="fa fa-edit"></i>Lista de Lineas</a></li>
        <li class="active">Eliminar lineas</li>
    </ol>
</section>

<?php 
$query = "SELECT * FROM martech_departamentos WHERE id = $id";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);

?>

<section  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos</h3>
                </div>

                <div class="box-body">

                <form method="post">


                    <div class="form-group col-lg-3">
                        <label>Planta</label>
                        <select class="form-control" name="planta_id" disabled>
                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_planta = mysqli_query($connection,"SELECT * FROM martech_plantas");
                            while($row_planta = mysqli_fetch_array($get_planta)):
                            ?>
                                <option value="<?php echo $row_planta['id'] ?>" <?php if($row['planta_id']==$row_planta['id']){echo "selected";}else{echo "";} ?>  ><?php echo $row_planta['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        
                    </div>

                    
                    <div class="form-group col-lg-6">
                        <label>Nombre de la linea</label>
                        <input disabled type="text" class="form-control" name="nombre" value="<?php echo $row['nombre']; ?>">
                        
                    </div>


                    
                    <div class="form-group col-lg-12">
                        <br><br>
                        <input type="submit" class="btn btn-danger" name="submit" value="Eliminar">
                    </div>




                </form>

                

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>



<!-- Modal -->
<div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div style="border-radius:0" class="modal-dialog">
        <div class="modal-content">
            <div style="background-color: #cc2f26; color: white;" class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="memberModalLabel"><i class="fa fa-warning"></i> Advertencia</h4>
            </div>
            <div style="border-radius: 0" class="modal-body">
                <p>Estas por eliminar una linea o celda, el registro se perdera y no se podran asignar equipos a esta linea o celda. Para eliminar haz click en el botón de <b>Confirmar</b></p>
                <p><b>Esta acción no se puede deshacer.</b></p>
            </div>
            <div class="modal-footer">
                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
