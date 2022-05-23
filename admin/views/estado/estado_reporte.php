<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<section class="content-header">
    <h1>
        Estado de reportes <?php //echo $_SESSION['user_puesto'] ?>
        <small>Estado de la atención </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Estado de la atención</li>
    </ol>
</section>


<?php 
$puesto = $_SESSION['user_puesto'];
if($puesto == 'supervisor')
{

    $query_usuario = "SELECT user_id FROM users WHERE user_name = '{$_SESSION['user_name']}'";
    $result_usuario = mysqli_query($connection, $query_usuario);
    $row_usuario = mysqli_fetch_array($result_usuario);
    $id_usuario =  $row_usuario['user_id'];

}
else
{
    echo"No tienes permiso de ver estos datos";

    require_once ("includes/footer.php");

    exit();
}

toData();

?>



<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Seleccione Linea o celda</h3>
                    <form method="post">

                        <div class="form-group col-lg-3">
                            <br>
                            <label>Linea</label>
                            <select class="form-control" name="departamento">
                                <?php 
                                    $query_departamentos = "SELECT id_departamento FROM martech_usuario_departamento WHERE id_usuario = $id_usuario";
                                    $result_query_departamentos = mysqli_query($connection, $query_departamentos);
                                    while($row_departamentos = mysqli_fetch_array($result_query_departamentos))
                                    {
                                        $id_departamento = $row_departamentos['id_departamento'];
                                        $query_nombre_departamento = "SELECT nombre FROM martech_departamentos WHERE id = $id_departamento ";
                                        $run = mysqli_query($connection, $query_nombre_departamento);
                                        $row_depa = mysqli_fetch_array($run);
                                        $nombre_depa = $row_depa['nombre'];
                                        echo "<option value='$id_departamento'>$nombre_depa</option>";
                                    }
                                ?>
                            </select>
                            <br>
                            <input type="submit" name="submit" value="Buscar" class="btn btn-primary">

                        </div>
                        

                    </form>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>



