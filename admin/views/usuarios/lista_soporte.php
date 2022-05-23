<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php include ("includes/martech_gerentes/modales.php")?>
<?php
if(isset($_GET['asignar']))
{
    include ("includes/martech_trabajadores/asignar.php");
} 
?>






<section class="content-header">
    <h1>
        Usuarios
        <small>Lista de trabajadores de soporte</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Lista de trabajadores de soporte</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Usuarios</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo electronico</th>
                            <th>Departamento</th>
                            <th>Supervisor (5 mins)</th>
                            <th>Jefe de area (1 hr)</th>
                            <th>Gerente (2 hrs)</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                        <?php
                        $get_gerentes = mysqli_query($connection, "SELECT * FROM users WHERE user_puesto = 'maquinas' OR user_puesto = 'mantenimiento' OR user_puesto = 'materiales' OR user_puesto = 'ingenieria' OR user_puesto = 'ingenieria calidad' OR user_puesto = 'software'");
                        while($row = mysqli_fetch_array($get_gerentes)):
                        $id_usuario = $row['user_id'];
                        $get_planta = mysqli_query($connection, "SELECT * FROM martech_usuario_departamento WHERE id_usuario = $id_usuario");

                        ?>

                        <tr>
                            <td><?php echo $row['user_nombre'] ?> <?php echo $row['user_apellido'] ?></td>
                            <td><?php echo $row['user_email'] ?> </td>
                            <td><?php echo $row['user_puesto'] ?> </td>
                            <td><?php echo $row['super'] ?> </td>
                            <td><?php echo $row['super2'] ?> </td>
                            <td><?php echo $row['super3'] ?> </td>
                        </tr>

                        <?php endwhile; ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo electronico</th>
                            <th>Departamento</th>
                            <th>Supervisor (5 mins)</th>
                            <th>Jefe de area (1 hr)</th>
                            <th>Gerente (2 hrs)</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>