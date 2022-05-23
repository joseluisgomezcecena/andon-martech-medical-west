<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<section class="content-header">
    <h1>
        Reportes
        <small>Reporte de tiempos muertos</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Reporte de tiempos muertos. </li>
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
                            <label for="InputFieldD">Escalación</label>
                            <select  class="form-control" id="InputFieldD"  name="alert">
                                <option value="">Seleccione</option>
                                <option value="01">1 Hora </option>
                                <option value="02">2 Horas </option>
                                <option value="04">4 Horas </option>
                                <option value="24">24 Horas </option>

                            </select>
                        </div>


                        <div class="form-group">
                            <button type="submit" name="busqueda_fecha" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>

                        <div class="form-group">
                            <a href="index.php?page=reporte_fallas" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i></a>
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

                    <p><b style="color:#3c8dbc;">*n:</b> No atendido</p>
                    <p><b style="color:#3c8dbc;">*a:</b> Atendido pero no resuelto</p>
                    <p><b style="color:#3c8dbc;">*s:</b> Atendido y resuelto</p>
                    
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:11px;">Id</th>
                            <th style="font-size:11px;">Planta</th>
                            <th style="font-size:11px;">Falla</th>
			    <th style="font-size:11px;">Tipo</th>
                            <th style="font-size:11px;">Nombre de maquina</th>
                            <th style="font-size:11px;">Centro de trabajo</th>
                            <th style="font-size:11px;">Fecha de Inicio</th>
                            <th style="font-size:11px;">Fecha de Atención</th>
                            <th style="font-size:11px;">Fecha de Solución</th>
                            <th style="font-size:11px;">Tiempo Fuera Atención</th>
                            <th style="font-size:11px;">Tiempo Fuera Solución</th>
                            <th style="font-size:11px;">Atendio</th>
                            <th style="font-size:11px;">Soluciono</th>
                            <th style="font-size:11px;">Estado</th>
                            <th style="font-size:11px;">Detalles</th>
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
                                $alert       = $_POST['alert'];

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

                                //escalación
                                if(empty($alert))
                                {
                                    $alert = "";
                                }
                                else
                                {
                                    $alert   = "AND TIMEDIFF(atendido,inicio) > '$alert:00:00' " ;
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
                                
                                     $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell $alert $inicio $fin $error UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell $alert $inicio $fin $error ) as l ORDER BY id";
                                     $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 $cell $alert  $inicio $fin $error AND atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 $cell $alert $inicio $fin $error AND atendido != '0000-00-00 00:00:00') as l ORDER BY id";
                                     $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 $cell $alert $inicio $fin $error AND fin != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 $cell $alert $inicio $fin $error AND fin != '0000-00-00 00:00:00') as l ORDER BY id";

                                


                                }
                                else
                                {
                                    //si es admin
                                    $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 = 1 $cell $alert $inicio $fin UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 = 1 $cell $alert $inicio $fin ) as l ORDER BY id ";
                                    $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell $alert  $inicio $fin AND atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell $alert $inicio $fin AND atendido != '0000-00-00 00:00:00') as l ORDER BY id";
                                    $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell $alert  $inicio $fin AND fin != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell $alert $inicio $fin AND fin != '0000-00-00 00:00:00') as l ORDER BY id";

                                }

    
                               
                                //$query = "SELECT * FROM martech_fallas WHERE  inicio BETWEEN '$inicio' AND '$fin'";
                                //$query2 = "SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$inicio' AND '$fin' ";

                            }
                            else
                            {
                                $hoy = date("Y-m-d");
                                $hi  = $hoy." 00:00:00";
                                $hf  = $hoy." 23:59:59";

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

                                     $query = "SELECT * FROM ( SELECT * FROM martech_fallas WHERE 1 $error AND inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 $error AND inicio BETWEEN '$hi' AND '$hf' )as l ORDER BY id";
                                     $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' $error AND inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE atendido != '0000-00-00 00:00:00' $error AND inicio BETWEEN '$hi' AND '$hf' ) as l ORDER BY id  ";
                                     $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' $error AND inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE fin != '0000-00-00 00:00:00' $error AND inicio BETWEEN '$hi' AND '$hf' ) as l ORDER BY id ";

                                }
                                else
                                {
                                    $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE inicio BETWEEN '$hi' AND '$hf') as l ORDER BY id ";
                                    $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE atendido != '0000-00-00 00:00:00' AND inicio BETWEEN '$hi' AND '$hf' ) as l ORDER BY id ";
                                    $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' AND inicio BETWEEN '$hi' AND '$hf' UNION ALL SELECT * FROM martech_fallas_backup WHERE fin != '0000-00-00 00:00:00' AND inicio BETWEEN '$hi' AND '$hf' ) as l ORDER BY id ";
                                }
                                    
                            }
                            
                            $result = mysqli_query($connection, $query);
                            while($row = mysqli_fetch_array($result)):
                                                       
                            ?>

                            <tr>
                                <td style="font-size:11px;"><?php echo $row['id'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['planta_nombre'] ?></td>
                                <td style="font-size:11px;">
                                <?php 
                               
                                
                                if($row['tipo_error'] == 'falta_material' )
                                {
                                    $error = "Falta de Material";
                                }
                                elseif($row['tipo_error'] == 'reemplazo')
                                {
                                    $error = "Reemplazo de Material";
                                }
                                elseif($row['tipo_error'] == 'setup')
                                {
                                    $error = "SetUp";
                                }
                                elseif($row['tipo_error'] == 'caida')
                                {
                                    $error = "Maquina Caida";
                                }
                                elseif($row['tipo_error'] == 'agua')
                                {
                                    $error = "Falta de Agua";
                                }
                                elseif($row['tipo_error'] == 'energia')
                                {
                                    $error = "Falta de Energia";
                                } 
                                elseif($row['tipo_error'] == 'qap')
                                {
                                    $error = "QAP";
                                }
                                elseif($row['tipo_error'] == 'sop')
                                {
                                    $error = "SOP";
                                } 
                                elseif($row['tipo_error'] == 'producto')
                                {
                                    $error = "Calidad de Producto";
                                } 
                                echo $error;
                             
                                ?>
                                </td>
				<td style="font-size:11px;"><?php echo $row['descripcion_operador'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['maquina_nombre'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['maquina_centro_trabajo'] ?></td>
                                <td style="font-size:11px;">
                                    <?php 
                                    $inicio = date_create($row['inicio']);
                                    echo date_format($inicio,"m/d/Y H:i:s");  
                                    ?>
                                </td>
                                <td style="font-size:11px;">
                                    <?php 
                                    if($row['atendido'] == '0000-00-00 00:00:00')
                                    {
                                        echo "<h4 style='font-size:14px;'><span class='label label-danger'>Activo</span></h4>";
                                    }
                                    else
                                    {
                                        $fin = date_create($row['atendido']);
                                        echo date_format($fin, "m/d/Y H:i:s");
                                    }
                                    ?>
                                </td>
                                <td style="font-size:11px;">
                                    <?php 
                                    if($row['fin'] == '0000-00-00 00:00:00')
                                    {
                                        echo "<h4 style='font-size:14px;'><span class='label label-danger'>Activo</span></h4>";
                                    }
                                    else
                                    {
                                        $fin = date_create($row['fin']);
                                        echo date_format($fin, "m/d/Y H:i:s");
                                    }
                                    ?>
                                </td>
                                <td style="font-size:11px;">
                                    <?php
                                    $datetime1 = new DateTime($row['atendido']);
                                    $datetime2 = new DateTime($row['inicio']);
                                    $interval = $datetime1->diff($datetime2);
                                    $days    = $interval->format('%d');
                                    $hours   = $interval->format('%h'); 
                                    $minutes = $interval->format('%i');
                                
                                    $mins = ($days*24*60+$hours*60+$minutes); 
                                    $horastotales = $mins/60;

                                    if($row['atendido'] == "0000-00-00 00:00:00")
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
                                <td style="font-size:11px;">
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

                                <td style="font-size:11px;">
                                    <?php 
                                     if($row['atendio']== NULL)
                                     {
                                         echo "N/A";
                                     }
                                     else
                                     {
                                         echo $row['atendio'];    
                                     }         
                                    ?>
                                </td>

                                <td style="font-size:11px;">
                                     <?php 
                                     if($row['resolvio']== NULL)
                                     {
                                         echo "N/A";
                                     }
                                     else
                                     {
                                         echo $row['resolvio'];    
                                     }         
                                    ?>
                                </td>

                                <td style="font-size:11px;">
                                    <?php
                                     if($row['offline'] == "si" && $row['atendido_flag'] == "si")
                                     {
                                         echo "<a href='#' class='test' data-toggle='tooltip' data-placement='top' title='Atendido'>*a</a>";
                                     }
                                     elseif($row['atendido_flag'] == "no" && $row['offline'] == "si")
                                     {
                                         echo "<a href='#' class='test' data-toggle='tooltip' data-placement='top' title='No atendido'>*n</a>";
                                     }
                                     
                                     else
                                     {
                                        echo "<a href='#' class='test' data-toggle='tooltip' data-placement='top' title='Solucionado'>*s</a>";
                                     }
                                      
                                     ?>
                                </td>


                                <td>
                                    <a href="index.php?page=reporte_detalles&id=<?php echo $row['id']; ?>" class="btn btn-primary">Ver</a>
                                </td>
                            </tr>

                            <?php endwhile; ?>

                        </tbody>

                        <tfoot>
                        <tr>
                        <th style="font-size:11px;">Id</th>
                            <th style="font-size:11px;">Planta</th>
                            <th style="font-size:11px;">Falla</th>
			    <th style="font-size:11px;">Tipo</th>
                            <th style="font-size:11px;">Nombre de maquina</th>
                            <th style="font-size:11px;">Centro de trabajo</th>
                            <th style="font-size:11px;">Fecha de Inicio</th>
                            <th style="font-size:11px;">Fecha de Atención</th>
                            <th style="font-size:11px;">Fecha de Solución</th>
                            <th style="font-size:11px;">Tiempo Fuera Atención</th>
                            <th style="font-size:11px;">Tiempo Fuera Solución</th>
                            <th style="font-size:11px;">Atendio</th>
                            <th style="font-size:11px;">Soluciono</th>
                            <th style="font-size:11px;">Estado</th>
                            <th style="font-size:11px;">Detalles</th>
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
                        <h3 class="box-title">Tiempo Perdido En Atención</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">
                    <p>El indicador de tiempo perdido en atención solo toma en cuenta los incidentes que ya
                    han sido atendidos.</p>

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
                    <p>El indicador de tiempo perdido en solución solo toma en cuenta los incidentes que ya
                    han sido solucionados.</p>
                        <?php 
                        $tiempo=0;

                        //$query2 = "SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' ";
                    
                    
                        $result2 = mysqli_query($connection, $query3);

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
