<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
$id = $_GET['id'];
?>



<section class="content-header">
    <h1>
        Lineas
        <small>Detalles de la linea</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?page=lista_linea"><i class="fa fa-edit"></i>Lista de Lineas</a></li>
        <li class="active">Detalles de la linea</li>
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
                        <input readonly type="text" class="form-control" name="nombre" value="<?php echo $row['nombre']; ?>">
                    </div>

               
                    <div class="col-lg-12">
                        <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Maquina</th>
                                        <th>Centro de trabajo</th>
                                        <th>Serie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                    
                                    $get_machines = mysqli_query($connection,"SELECT * FROM martech_maquinas WHERE departamento_id = $id");
                                    while($row_machines = mysqli_fetch_array($get_machines)):
                                    ?>

                                    <tr>
                                        <td><?php echo $row_machines['nombre']; ?></td>
                                        <td><?php echo $row_machines['centro_trabajo']; ?></td>
                                        <td><?php echo $row_machines['serie']; ?></td>
                                    </tr>

                                    <?php endwhile; ?>
                                </tbody>
                        </table>
                    </div>

                  
                

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>




