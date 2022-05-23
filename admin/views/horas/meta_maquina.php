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
$id_maquina = $_GET['id'];
$query = "SELECT * FROM martech_meta_maquina WHERE maquina_id = $id_maquina AND end = 0";
$run_query =  mysqli_query($connection, $query);
$row = mysqli_fetch_array($run_query);

agregarMetaMaquina();
?>

<section class="content-header">
    <h1>
        Metas
        <small>Asignar meta</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Metas</a></li>
        <li class="active">Asignar meta</li>
    </ol>
</section>


<section  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Meta del equipo</h3>
                </div>

                <div class="box-body">

                <form method="post" enctype="multipart/form-data">

                    

                    <div class="form-group col-lg-4">
                        <label>Piezas por hora</label>
                        <input type="number" class="form-control" name="meta" value="<?php echo $row['meta'] ?>" required>
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




