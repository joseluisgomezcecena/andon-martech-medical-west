<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
guardarLinea();

if(isset($_GET['repeated']))
{
    include("includes/system_modals/error/repetida_linea.php");
}
?>



<section class="content-header">
    <h1>
        Lineas
        <small>Registrar Linea</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Registrar Linea</li>
    </ol>
</section>




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
                        <select class="form-control" name="planta_id" required>
                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_planta = mysqli_query($connection,"SELECT * FROM martech_plantas");
                            while($row_planta = mysqli_fetch_array($get_planta)):
                            ?>
                                <option value="<?php echo $row_planta['id'] ?>" ><?php echo $row_planta['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small>Campo requerido</small>
                    </div>

                    
                    <div class="form-group col-lg-6">
                        <label>Nombre de la linea</label>
                        <input type="text" class="form-control" name="nombre" required>
                        <small>Campo requerido</small>
                    </div>


                    
                    <div class="form-group col-lg-12">
                        <br><br>
                        <input type="submit" class="btn btn-primary" name="submit" value="Guardar">
                    </div>




                </form>

                

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>




