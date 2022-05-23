<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
$id = $_GET['id'];
editarLinea();
?>



<section class="content-header">
    <h1>
        Lineas
        <small>Editar lineas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?page=lista_linea"><i class="fa fa-edit"></i>Lista de Lineas</a></li>
        <li class="active">Editar lineas</li>
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
                        <select class="form-control" name="planta_id">
                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_planta = mysqli_query($connection,"SELECT * FROM martech_plantas");
                            while($row_planta = mysqli_fetch_array($get_planta)):
                            ?>
                                <option value="<?php echo $row_planta['id'] ?>" <?php if($row['planta_id']==$row_planta['id']){echo "selected";}else{echo "";} ?>  ><?php echo $row_planta['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small>Campo requerido</small>
                    </div>

                    
                    <div class="form-group col-lg-6">
                        <label>Nombre de la linea</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo $row['nombre']; ?>">
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




