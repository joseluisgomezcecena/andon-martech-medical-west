<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<section class="content-header">
    <h1>
        Reportes
        <small>Reportes de fallas por solucionar</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Reportes</a></li>
        <li class="active">Reportes de fallas por solucionar </li>
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
                            <input type="text" class="form-control" id="InputFieldA" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="inicio" placeholder="Inicio" autocomplete="off" >
                        </div>

                        <div class="form-group">
                            <label for="InputFieldB">Hasta</label>
                            <input type="text" class="form-control" id="InputFieldB" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="fin" placeholder="Final" autocomplete="off" >
                        </div>

                        <div class="form-group">
                            <label for="InputFieldC">Celda</label>
                            <select  class="form-control" id="InputFieldC"  name="cell">
                                <option value="">Seleccione</option>
                                <?php 
                                $query_cell = "SELECT * FROM martech_departamentos WHERE activo = 1";
                                $run = mysqli_query($connection, $query_cell);
                                while($row_cell = mysqli_fetch_array($run)):
                                    if($row_cell['planta_id']==1)
                                {
                                    $op = "(Planta 1)";
                                }
                                elseif($row_cell['planta_id']==2)
                                {
                                    $op = "(Planta 2)";
                                }
                                elseif($row_cell['planta_id']==3)
                                {
                                    $op = "(Planta 3)";
                                }
                                else
                                {
                                    $op = "Desconocido";
                                }
                                ?>

                                <option value="<?php echo $row_cell['id'] ?>"><?php echo $row_cell['nombre']." ".$op ?></option>

                                <?php endwhile; ?>
                            </select>
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
                    <h3 class="box-title">Reportes <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:12px;">Id</th>
                            <th style="font-size:12px;">Falla</th>
                            <th style="font-size:12px;">Nombre de maquina</th>
                            <th style="font-size:12px;">Centro de trabajo</th>
                            <th style="font-size:12px;">Fecha de Inicio</th>
                            <th style="font-size:12px;">Fecha de Solución</th>
                            <th style="font-size:12px;">Tiempo Fuera</th>
                            <th style="font-size:12px;">Tiempo Fuera (decimal)</th>
                            <th style="font-size:12px;">Estado</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            
                            



                            
                            if(isset($_POST['busqueda_fecha']))
                            {

                                //si los campos de fecha estan llenos
                                $post_inicio = $_POST['inicio']." 00:00:00";
                                $post_fin    = $_POST['fin']." 23:59:59";
                                $cell        = $_POST['cell'];

                                //si los campos de fecha estan vacios
                                if($post_inicio == " 00:00:00")
                                {
                                    $inicio = "";
                                }
                                else
                                {
                                    $inicio = "AND inicio BETWEEN '$post_inicio' AND";
                                }

                                if($post_fin == " 23:59:59")
                                {
                                    $fin = "";
                                }
                                else
                                {
                                    $fin = "'$post_fin'"; 
                                }

                                //campos de celda
                                if(empty($cell))
                                {
                                    $cell = "";
                                }
                                else
                                {
                                    $cell   = "AND departamento_id = $cell" ;
                                }





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
                                
                                     //$query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin' $error";
                                     //$query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin' $error ";
                                
                                     $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell $inicio $fin $error UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell $inicio $fin $error ) as l ORDER BY id ";
                                     $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 $cell  $inicio $fin $error AND atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 $cell  $inicio $fin $error AND atendido != '0000-00-00 00:00:00') as l ORDER BY id  ";
                                


                                }
                                else
                                {
                                    //$query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin' ";
                                    //$query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin'  ";

                                    $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 = 1 $cell $inicio $fin UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 = 1 $cell $inicio $fin ) as l ORDER BY id ";
                                    $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell  $inicio $fin AND atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell  $inicio $fin AND atendido != '0000-00-00 00:00:00' ) as l ORDER BY id ";
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

                                     $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 $error UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 $error) as l ORDER BY id  ";
                                     $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' $error UNION ALL SELECT * FROM martech_fallas_backup WHERE atendido != '0000-00-00 00:00:00' $error) as l ORDER BY id ";

                                }
                                else
                                {
                                    $query = "SELECT * FROM (SELECT * FROM martech_fallas UNION ALL SELECT * FROM martech_fallas_backup ) as l ORDER BY id ";
                                    $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE atendido != '0000-00-00 00:00:00') as l ORDER BY id  ";
                                }
                                    
                            }
                            
                            $result = mysqli_query($connection, $query);
                            while($row = mysqli_fetch_array($result)):
                                                       
                            ?>

                            <tr>
                                <td style="font-size:12px;"><?php echo $row['id'] ?></td>
                                <td style="font-size:12px;"><?php echo $row['tipo_error'] ?></td>
                                <td style="font-size:12px;"><?php echo $row['maquina_nombre'] ?></td>
                                <td style="font-size:12px;"><?php echo $row['maquina_centro_trabajo'] ?></td>
                                <td style="font-size:12px;">
                                    <?php 
                                    $inicio = date_create($row['inicio']);
                                    echo date_format($inicio,"m/d/Y H:i:s");  
                                    ?>
                                </td>
                                <td style="font-size:12px;">
                                    <?php 
                                    if($row['fin'] == '0000-00-00 00:00:00')
                                    {
                                        echo "<h4><span class='label label-danger'>Activo</span></h4>";
                                    }
                                    else
                                    {
                                        $fin = date_create($row['fin']);
                                        echo date_format($fin, "m/d/Y H:i:s");
                                    }
                                    ?>
                                </td>
                                <td style="font-size:12px;">
                                    <?php 
                                     $start_date = new DateTime($row['inicio']);
                                     $since_start = $start_date->diff(new DateTime($row['fin']));
         
                                     $since_start->days.' days total<br>';
                                      $y = $since_start->y.' years<br>';
                                      $m = $since_start->m.' months<br>';
                                      $d = $since_start->d.' dias<br>';
                                      $h = $since_start->h.' horas<br>';
                                      $m = $since_start->i.' minutos<br>';
                                      $s = $since_start->s.' segundos<br>';

                                      if($row['fin'] == '0000-00-00 00:00:00')
                                      {
                                        echo "<h4><span class='label label-warning'>En espera</span></h4>";
                                      }
                                      else
                                      {
                                        echo $d." ".$h." ".$m." ".$s;
                                      }
                                    ?>
                                </td>
                                <td style="font-size:12px;">
                                <?php
                                    $datetime1 = new DateTime($row['fin']);
                                    $datetime2 = new DateTime($row['inicio']);
                                    $interval = $datetime1->diff($datetime2);
                                    $days    = $interval->format('%d');
                                    $hours   = $interval->format('%h'); 
                                    $minutes = $interval->format('%i');
                                
                                    $mins = ($days*24*60+$hours*60+$minutes); 
                                    $horastotales = $mins/60;

                                    if($row['fin'] == "0000-00-00 00:00:00")
                                    {
                                        //echo "0";
                                        $datetime1 = new DateTime(date("Y-m-d H:i:s"));
                                        $datetime2 = new DateTime($row['inicio']);
                                        $interval = $datetime1->diff($datetime2);
                                        $days    = $interval->format('%d');
                                        $hours   = $interval->format('%h'); 
                                        $minutes = $interval->format('%i');

                                        $mins = ($days*24*60+$hours*60+$minutes); 
                                        $horastotales = $mins/60;

                                        echo "<p style='color:red'>".round($horastotales,2)."</p>";
                                
                                    }
                                    else
                                    {
                                        echo round($horastotales,2);
                                    }
                                    ?>
                                </td>
                                <td style="font-size:12px;">
                                    <?php
                                     if($row['offline'] == "si" && $row['atendido_flag'] == "si")
                                     {
                                         echo "*a";
                                     }
                                     elseif($row['atendido_flag'] == "no" && $row['offline'] == "si")
                                     {
                                         echo "*n";
                                     }
                                     
                                     else
                                     {
                                        echo "*s";
                                     }
                                      
                                     ?>
                                </td>


                            </tr>

                            <?php endwhile; ?>

                        </tbody>

                        <tfoot>
                        <tr>
                            <th style="font-size:12px;">Id</th>
                            <th style="font-size:12px;">Falla</th>
                            <th style="font-size:12px;">Nombre de maquina</th>
                            <th style="font-size:12px;">Centro de trabajo</th>
                            <th style="font-size:12px;">Fecha de Inicio</th>
                            <th style="font-size:12px;">Fecha de Solución</th>
                            <th style="font-size:12px;">Tiempo Fuera</th>
                            <th style="font-size:12px;">Tiempo Fuera (decimal)</th>
                            <th style="font-size:12px;">Estado</th>
                            
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
                        <h3 class="box-title">Tiempo Perdido En Solución</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">

                        <?php 
                        $tiempo=0;

                        //$query2 = "SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' ";
                    
                    
                        $result2 = mysqli_query($connection, $query2);

                        while($row = mysqli_fetch_array($result2)):
                        
                            $datetime1 = new DateTime($row['fin']);
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
