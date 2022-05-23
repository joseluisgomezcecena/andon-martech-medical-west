<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<?php
if(isset($_GET['edited']))
{
    $email = $_GET['email'];
    $password = $_GET['password'];
    $nivel = $_GET['role'];
    if($nivel =="si"){$user_role = "Administrador";}else{$user_role="Trabajador";}
    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Usuario editado con exito.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El usuario fue editado con exito! con las siguientes credenciales. Guarde sus datos en un lugar seguro.</p>
                                                <p><b>Correo electronico:</b> {$email}</p>
                                                <p><b>Contraseña:</b> {$password}</p>
                                                <p><b>Nivel:</b> {$user_role}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
    echo $modal;
}

if(isset($_GET['deleted']))
{
    $modal = <<< DELIMITER
                     <!-- Modal -->
                                <div style="border-radius: 0" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div style="border-radius:0" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="memberModalLabel">Usuario eliminado con exito.</h4>
                                            </div>
                                            <div style="border-radius: 0" class="modal-body">
                                                <p>El usuario fue eliminado la información no podra ser recuperada</p>
                                               
                                            </div>
                                            <div class="modal-footer">
                                                <button style="border-radius: 0" type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
DELIMITER;
    echo $modal;
}
?>




<section class="content-header">
    <h1>
        Reportes
        <small>Reportes de fallas por atender</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Reportes</a></li>
        <li class="active">Reportes de fallas por atender </li>
    </ol>
</section>


<!--filtrado por fechas-->

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-danger">
                <form class="form form-inline form-multiline" method="post" role="form">

                    <div class="box-header with-border">
                        <h3 class="box-title">Filtrado por fechas</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">

                        <div class="form-group">
                            <label for="InputFieldA">Desde</label>
                            <input type="text" class="form-control" id="InputFieldA" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="inicio" placeholder="Inicio" autocomplete="off" required>
                        </div>

                        <div class="form-group">
                            <label for="InputFieldB">Hasta</label>
                            <input type="text" class="form-control" id="InputFieldB" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="fin" placeholder="Final" autocomplete="off" required>
                        </div>



                        <div class="form-group">
                            <button type="submit" name="busqueda_fecha" class="btn btn-default btn-flat">Buscar</button>
                        </div>

                        <div class="form-group">
                            <a href="index.php?page=reporte_fallas" class="btn btn-primary btn-flat">Reiniciar</a>
                        </div>

                    </div>

                    <div class="box-footer">
                        <p>Puedes filtra por fecha usando esta opcion o puedes buscar por nombre en la parte de abajo</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!--filtrado por fechas-->




<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Falla</th>
                            <th>Nombre de maquina</th>
                            <th>Centro de trabajo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Tiempo Fuera</th>
                            <th>Responsable</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            
                            



                            
                            if(isset($_POST['busqueda_fecha']))
                            {

                                $inicio = $_POST['inicio']." 00:00:00";
                                $fin    = $_POST['fin']." 23:59:59";


                                if($_SESSION['admin_role'] == 'no')
                                {
                                                               
                                    if($puesto == 'maquinas')
                                    { 
                                        $error = "AND tipo_error = 'setup'";
                                    }
                                    elseif($puesto == 'mantenimiento')
                                    {
                                        $error = "AND (tipo_error = 'caida' OR tipo_error = 'agua' OR tipo_error = 'energia')";
                                    }
                                    elseif($puesto == 'materiales')
                                    {
                                        $error = "AND (tipo_error = 'falta_material' OR tipo_error = 'reemplazo')";
                                    }
                                    elseif($puesto == 'ingenieria')
                                    { 
                                        $error = "AND tipo_error = 'sop'";
                                    }
                                    elseif($puesto == 'ingenieria calidad')
                                    { 
                                        $error = "AND tipo_error = 'qap'";
                                    }
                                    else
                                    {
                                        $error = "";
                                    }
                                
                                     $query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin' $error";
                                     $query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin' $error ";
                                
                                }
                                else
                                {
                                    $query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin' ";
                                    $query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin'  ";
                                }

    
                               
                                //$query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin'";
                                //$query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin' ";

                            }
                            else
                            {

                                if($_SESSION['admin_role'] == 'no')
                                {
                                    
                                    //echo $_SESSION['admin_role'];
                                    
                                    if($puesto == 'maquinas')
                                    { 
                                        $error = "AND tipo_error = 'setup'";
                                    }
                                    elseif($puesto == 'mantenimiento')
                                    {
                                        $error = "AND (tipo_error = 'caida' OR tipo_error = 'agua' OR tipo_error = 'energia')";
                                    }
                                    elseif($puesto == 'materiales')
                                    {
                                        $error = "AND (tipo_error = 'falta_material' OR tipo_error = 'reemplazo')";
                                    }
                                    elseif($puesto == 'ingenieria')
                                    { 
                                        $error = "AND tipo_error = 'sop'";
                                    }
                                    elseif($puesto == 'ingenieria calidad')
                                    { 
                                        $error = "AND tipo_error = 'qap'";
                                    }
                                    else
                                    {
                                        $error = "";
                                    }

                                    //echo $error;

                                     $query = "SELECT * FROM martech_fallas WHERE 1 $error";
                                     $query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' $error ";

                                }
                                else
                                {
                                    $query = "SELECT * FROM martech_fallas ";
                                    $query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' ";
                                }
                                    
                            }
                            
                            $result = mysqli_query($connection, $query);
                            while($row = mysqli_fetch_array($result)):
                                                       
                            ?>

                            <tr>
                                <td><?php echo $row['tipo_error'] ?></td>
                                <td><?php echo $row['maquina_nombre'] ?></td>
                                <td><?php echo $row['maquina_centro_trabajo'] ?></td>
                                <td>
                                    <?php 
                                    $inicio = date_create($row['inicio']);
                                    echo date_format($inicio,"m/d/Y H:i:s");  
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if($row['atendido'] == '0000-00-00 00:00:00')
                                    {
                                        echo "<h4><span class='label label-danger'>Activo</span></h4>";
                                    }
                                    else
                                    {
                                        $fin = date_create($row['atendido']);
                                        echo date_format($fin, "m/d/Y H:i:s");
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                     $start_date = new DateTime($row['inicio']);
                                     $since_start = $start_date->diff(new DateTime($row['atendido']));
         
                                     $since_start->days.' days total<br>';
                                      $y = $since_start->y.' years<br>';
                                      $m = $since_start->m.' months<br>';
                                      $d = $since_start->d.' dias<br>';
                                      $h = $since_start->h.' horas<br>';
                                      $m = $since_start->i.' minutos<br>';
                                      $s = $since_start->s.' segundos<br>';

                                      if($row['atendido'] == '0000-00-00 00:00:00')
                                      {
                                        echo "<h4><span class='label label-warning'>En espera</span></h4>";
                                      }
                                      else
                                      {
                                        echo $d." ".$h." ".$m." ".$s;
                                      }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $get_responsable = mysqli_query($connection,"SELECT user_nombre, user_apellido, user_numero FROM users WHERE user_id = {$row['id']}");
                                    $row_responsable = mysqli_fetch_array($get_responsable);
                                    echo $row_responsable['user_nombre']." ".$row_responsable['user_apellido']." ".$row_responsable['user_numero'];
                                    ?>
                                </td>
                            </tr>

                            <?php endwhile; ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Falla</th>
                            <th>Nombre de maquina</th>
                            <th>Centro de trabajo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Tiempo Fuera</th>
                            <th>Responsable</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>



<!--filtrado por fechas-->

<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-danger">
                <form class="form form-inline form-multiline" method="post" role="form">

                    <div class="box-header with-border">
                        <h3 class="box-title">Tiempo Perdido En Atencion</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">

                        <?php 
                        $tiempo=0;

                        //$query2 = "SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' ";
                    
                    
                        $result2 = mysqli_query($connection, $query2);

                        while($row = mysqli_fetch_array($result2)):
                        
                            $datetime1 = new DateTime($row['atendido']);
                            $datetime2 = new DateTime($row['inicio']);
                            $interval = $datetime1->diff($datetime2);
                            $days    = $interval->format('%d');
                            $hours   = $interval->format('%h'); 
                            $minutes = $interval->format('%i');
                            #echo 'Diff. in minutes is: '.$mins = ($days*24*60+$hours*60+$minutes); 
                            #echo "<br/>";
                            #$ht = $mins/60;
                            #echo $ht."<br/>";
                            $mins = ($days*24*60+$hours*60+$minutes); 
                            $ht = $mins/60;
                            $tiempo += $ht;
                             
                        
                        ?> 

                        <?php endwhile; ?>

                        <h3><b class="text-danger">Horas Perdidas: <?php echo round($tiempo,2); ?> hrs</b></h3>
                    
                    
                    </div>

                    <div class="box-footer">
                        <p></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!--filtrado por fechas-->
