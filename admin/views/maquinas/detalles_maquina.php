<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php
$get_data = mysqli_query($connection, "SELECT * FROM martech_maquinas WHERE id = {$_GET['id']}");
$row = mysqli_fetch_array($get_data);
?>
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>


<section class="content-header">
    <h1>
        Equipo
        <small>Detalles del equipo</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?page=lista_maquina"><i class="fa fa-cogs"></i> Lista de equipos</a></li>
        <li class="active">Detalles del equipo</li>
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
                        
                    </div>

                    <div class="form-group col-lg-3">
                        <label>Deprtamento</label>
                        <select class="form-control" name="departamento_id" disabled>

                           <?php 
                           $departamento_id = $row['departamento_id']
                           ?>

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
                                <option value="<?php echo $row_departamento['id'] ?>" <?php if($departamento_id == $row_departamento['id'] ){echo 'selected';}else{echo'nada';}?> ><?php echo $row_departamento['nombre']." ".$op ?></option>
                            <?php endwhile; ?>
                        </select>
                        
                    </div>

                    <div class="form-group col-lg-6">
                        <label>Nombre del equipo</label>
                        <input disabled type="text" class="form-control" name="nombre" value="<?php echo $row['nombre'] ?>">
                        
                    </div>


                    


                    <div class="form-group col-lg-4">
                        <label>Centro de trabajo</label>
                        <input disabled type="text" class="form-control" name="centro_trabajo" value="<?php echo $row['centro_trabajo'] ?>">
                       
                    </div>


                    <div class="form-group col-lg-4">
                        <label>Numero de control</label>
                        <input disabled type="text" class="form-control" name="numero_control" value="<?php echo $row['numero_control'] ?>">
                        
                    </div>

                    <div class="form-group col-lg-4">
                        <label>Numero de serie</label>
                        <input disabled type="text" class="form-control" name="serie" value="<?php echo $row['serie'] ?>">
                       
                    </div>

                    

                </form>

                

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>




