<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php 
if(isset($_GET['error'])){
    if($_GET['error']== 'false'){
        include ("includes/system_modals/success/meta_maquina.php");
    }
}
if(isset($_GET['error']) ){
    if($_GET['error']== 'int'){
        include ("includes/system_modals/error/int_meta_maquina.php");
    }
}

if(isset($_GET['error']) ){
    if($_GET['error']== 'query'){
        include ("includes/system_modals/error/int_meta_maquina.php");
    }
}
?>
<?php
agregarMetaMaquina();
?>

<section class="content-header">
    <h1>
        Semanas
        <small>Semanas de trabajo</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Semanas de trabajo</li>
    </ol>
</section>


<section  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Agregar semanas de trabajo</h3>
                </div>

                <div class="box-body">
                    <div class="col-lg-4">
                        <form method="post" enctype="multipart/form-data">

                                <br><br>
                            
                            
                                <div class="form-group col-lg-12">
                                    <label>Numero de semana</label>
                                    <input type="number" class="form-control" min="1" max="53" name="meta" value="" required>
                                    <small>Campo requerido</small>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Inicio</label>
                                    <input type="text" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd"  autocomplete="off"  required>
                                    <small>Campo requerido</small>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Fin</label>
                                    <input type="text" class="form-control" data-provide="datepicker" data-date-format="yyyy-mm-dd"  autocomplete="off"  required>
                                    <small>Campo requerido</small>
                                </div>

                                
                                
                                <div class="form-group col-lg-12">
                                    <br><br>
                                    <input type="submit" class="btn btn-primary" name="submit" value="Guardar">
                                </div>

                        </form>
                    </div>

                    <div class="col-lg-8">
                    

                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:10px;">Semana</th>
                            <th style="font-size:10px;">Inicio</th>
                            <th style="font-size:10px;">Fin</th>
                            <th style="font-size:10px;">Detalles</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $query = mysqli_query($connection,"SELECT * FROM produccion_semanas ORDER BY desde");
                                while($row = mysqli_fetch_array($query))
                                {

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
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>




