<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php 
if(isset($_GET['error'])){
    if($_GET['error']== 'false'){
        include ("includes/system_modals/success/alta_maquina.php");
    }
}
if(isset($_GET['error']) && isset($_GET['repeated'])){
    if($_GET['error']== 'true'){
        include ("includes/system_modals/error/repetida_maquina.php");
    }
}
?>
<?php
guardarMaquina();
?>

<section class="content-header">
    <h1>
        Equipo
        <small>Alta de equipo</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Equipo</a></li>
        <li class="active">Alta de equipo</li>
    </ol>
</section>


<section  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Datos del equipo</h3>
                </div>

                <div class="box-body">

                <form method="post" enctype="multipart/form-data">

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

                    <div class="form-group col-lg-3">
                        <label>Deprtamento</label>
                        <select class="form-control" name="departamento_id" required>
                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_departamento = mysqli_query($connection,"SELECT * FROM martech_departamentos WHERE activo = 1");
                            while($row_departamento = mysqli_fetch_array($get_departamento)):
                                if($row_departamento['planta_id']==1)
                                {
                                    $op = "(Planta 1)";
                                }
                                elseif($row_departamento['planta_id']==2)
                                {
                                    $op = "(Planta 2)";
                                }
                                elseif($row_departamento['planta_id']==3)
                                {
                                    $op = "(Planta 3)";
                                }
                            ?>

                                <option value="<?php echo $row_departamento['id'] ?>" ><?php  echo $row_departamento['nombre']." ".$op; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small>Campo requerido</small>
                    </div>

                    <div class="form-group col-lg-6">
                        <label>Nombre del equipo</label>
                        <input type="text" class="form-control" name="nombre" required>
                        <small>Campo requerido</small>
                    </div>


                    <div class="form-group col-lg-4">
                        <label>Centro de trabajo</label>
                        <input type="text" class="form-control" name="centro_trabajo" required>
                        <small>Campo requerido</small>
                    </div>


                    <div class="form-group col-lg-4">
                        <label>Numero de control</label>
                        <input type="text" class="form-control" name="numero_control" required>
                        <small>Campo requerido</small>
                    </div>

                    <div class="form-group col-lg-4">
                        <label>Numero de serie</label>
                        <input type="text" class="form-control" name="serie" required>
                        <small>Campo requerido</small>
                    </div>

                   
                    
                    <div class="form-group col-lg-4">
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




