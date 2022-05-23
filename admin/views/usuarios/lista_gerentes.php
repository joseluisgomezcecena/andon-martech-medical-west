<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php include ("includes/martech_gerentes/modales.php")?>
<?php
if(isset($_GET['asignar']))
{
    include ("includes/martech_gerentes/asignar.php");
} 
unassignPlant();
?>






<section class="content-header">
    <h1>
        Usuarios
        <small>Lista de gerentes</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Lista de gerentes</li>
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
                            <th>Nivel</th>
                            <th>Planta</th>
                            <th>Asignar Planta</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                        <?php
                        $get_gerentes = mysqli_query($connection, "SELECT * FROM users WHERE user_puesto = 'gerente produccion'");
                        while($row = mysqli_fetch_array($get_gerentes)):
                        $id_usuario = $row['user_id'];
                        $get_planta = mysqli_query($connection, "SELECT * FROM martech_usuario_planta WHERE id_usuario = $id_usuario");

                        ?>

                        <tr>
                            <td><?php echo $row['user_nombre'] ?> <?php echo $row['user_apellido'] ?></td>
                            <td><?php echo $row['user_email'] ?> </td>
                            <td><?php echo $row['user_puesto'] ?> </td>
                            <td>
                            <form method="post">
                            <?php 
                            while($row2 = mysqli_fetch_array($get_planta))
                            {
                                $id_planta = $row2['id_planta'];
                                $select_planta = mysqli_query($connection, "SELECT * FROM martech_plantas WHERE id = $id_planta");
                                $row3 = mysqli_fetch_array($select_planta);
                                echo $row3['nombre']."&nbsp;&nbsp;<button class='btn btn-default' type='submit' name='unassign'><i style='color:red' class='fa fa-times'></i></button><br/>";    
                                echo "<input type='hidden' name='id_user' value='{$row['user_id']}'>";
                                echo "<input type='hidden' name='id_planta' value='{$row2['id_planta']}'>";
                            } 
                            ?> 
                            </form>
                            </td>
                            <td> <a class="btn btn-primary"  href="index.php?page=lista_gerentes&asignar=true&id=<?php echo $id_usuario ?>">Asignar</a> </td>
                        </tr>

                        <?php endwhile; ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo electronico</th>
                            <th>Nivel</th>
                            <th>Planta</th>
                            <th>Asignar Planta</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>