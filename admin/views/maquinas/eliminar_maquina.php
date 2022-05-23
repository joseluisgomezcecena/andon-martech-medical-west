<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
eliminarMaquina();
$get_data = mysqli_query($connection, "SELECT * FROM martech_maquinas WHERE id = {$_GET['id']}");
$row = mysqli_fetch_array($get_data);
?>



<section class="content-header">
    <h1>
        Equipo
        <small>Eliminar equipo</small>
    </h1>
    <ol class="breadcrumb">
    <li><a href="index.php?page=lista_maquina"><i class="fa fa-cogs"></i> Lista de equipos</a></li>
        <li class="active">Eliminar equipo</li>
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
                        <label>Planta1</label>
                        <select class="form-control" name="planta_id" disabled>

                           <?php 
                           $planta_id = $row['planta_id']
                           ?>

                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_planta = mysqli_query($connection,"SELECT * FROM martech_plantas");
                            while($row_planta = mysqli_fetch_array($get_planta)):
                            ?>
                                <option value="<?php echo $row_planta['id'] ?>" <?php if($planta_id == $row_planta['id'] ){echo 'selected';}else{echo'nada';} ?>  ><?php echo $row_planta['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small>Campo requerido</small>
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Deprtamento</label>
                        <select class="form-control" name="departamento_id" disabled>

                           <?php 
                           $departamento_id = $row['departamento_id']
                           ?>

                            <option>Seleccione una opcion</option>
                            <?php 
                            $get_departamento = mysqli_query($connection,"SELECT * FROM martech_departamentos");
                            while($row_departamento = mysqli_fetch_array($get_departamento)):
                            ?>
                                <option value="<?php echo $row_departamento['id'] ?>" <?php if($departamento_id == $row_departamento['id'] ){echo 'selected';}else{echo'nada';}?> ><?php echo $row_departamento['nombre'] ?></option>
                            <?php endwhile; ?>
                        </select>
                        <small>Campo requerido</small>
                    </div>

                    <div class="form-group col-lg-6">
                        <label>Nombre del equipo</label>
                        <input disabled type="text" class="form-control" name="nombre" value="<?php echo $row['nombre'] ?>">
                        <small>Campo requerido</small>
                    </div>


                   


                    <div class="form-group col-lg-4">
                        <label>Centro de trabajo</label>
                        <input disabled type="text" class="form-control" name="centro_trabajo" value="<?php echo $row['centro_trabajo'] ?>">
                        <small>Campo requerido</small>
                    </div>


                    <div class="form-group col-lg-4">
                        <label>Numero de control</label>
                        <input disabled type="text" class="form-control" name="numero_control" value="<?php echo $row['numero_control'] ?>">
                        <small>Campo requerido</small>
                    </div>

                    <div class="form-group col-lg-4">
                        <label>Numero de serie</label>
                        <input disabled type="text" class="form-control" name="serie" value="<?php echo $row['serie'] ?>">
                        <small>Campo requerido</small>
                    </div>

                    
                    <div class="form-group col-lg-3">
                        <br><br>
                        <input type="submit" class="btn btn-danger" name="submit" value="Confirmar">
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
                <p>Estas por eliminar un equipo, el registro se perdera y no pordra ser asignado a ninguna planta, linea o celda. Para eliminar haga click en el boton de <b>Confirmar</b></p>
                <p><b>Esta acción no se puede deshacer.</b></p>
            </div>
            <div class="modal-footer">
                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>