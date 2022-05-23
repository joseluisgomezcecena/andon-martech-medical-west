<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<section class="content-header">
    <h1>
        Reportes 
        <small>Reporte hora por hora (historico)</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Reporte hora por hora (historico) </li>
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
                            <a href="index.php?page=hora_hora_reportes_maquina" class="btn btn-primary btn-flat">Reiniciar</a>
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
                    <h3 class="box-title">Reporte Generado <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:11px;">Id</th>
                            <th style="font-size:11px;">Fecha</th>
                            <th style="font-size:11px;">Maquina</th>
                            <th style="font-size:11px;">Ubicación</th>
                            <th style="font-size:11px;">P. Total</th>
                            <th style="font-size:11px;">12am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">1am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">2am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">3am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">4am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">5am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">6am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">7am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">8am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">9am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">10am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">11am</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">12pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">1pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">2pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">3pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">4pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">5pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">6pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">7pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">8pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">9pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">10pm</th>
                            <th style="font-size:11px;">Meta</th>
                            <th style="font-size:11px;">11pm</th>
                            <th style="font-size:11px;">Meta</th>
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




                                $query = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1 = 1 $cell $inicio $fin UNION ALL SELECT * FROM martech_fallas_backup WHERE 1 = 1 $cell $inicio $fin ) as l ORDER BY id ";
                                $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell  $inicio $fin AND atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell  $inicio $fin AND atendido != '0000-00-00 00:00:00') as l ORDER BY id";
                                $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE 1  $cell  $inicio $fin AND fin != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE 1  $cell  $inicio $fin AND fin != '0000-00-00 00:00:00') as l ORDER BY id";

                            }
                            else
                            {

                                    $query = "SELECT * FROM registro_hora";

                                    /*
                                    $query = "SELECT * FROM (SELECT * FROM martech_fallas UNION ALL SELECT * FROM martech_fallas_backup) as l ORDER BY id ";
                                    $query2 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE atendido != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE atendido != '0000-00-00 00:00:00' ) as l ORDER BY id ";
                                    $query3 = "SELECT * FROM (SELECT * FROM martech_fallas WHERE fin != '0000-00-00 00:00:00' UNION ALL SELECT * FROM martech_fallas_backup WHERE fin != '0000-00-00 00:00:00' ) as l ORDER BY id ";
                                    */
                                    
                            }
                            
                            $result = mysqli_query($connection, $query);
                            while($row = mysqli_fetch_array($result)):
                                                       
                            ?>

                            <tr>
                                <td style="font-size:11px;"><?php echo $row['id'] ?></td>
                                
                                <td style="font-size:11px;">
                                <?php 
                                    $fecha = date_create($row['fecha']);
                                    echo date_format($fecha,"m/d/Y");  
                                ?>
                                </td>
                                
                                <td style="font-size:11px;">
                                <?php
                                    $id_maquina = $row['maquina_id'];
                                    $search_maquina = "SELECT nombre FROM martech_maquinas WHERE id = $id_maquina ";
                                    $run_search = mysqli_query($connection, $search_maquina);
                                    $row_maquina = mysqli_fetch_array($run_search);
                                    echo $row_maquina['nombre'];
                                ?>
                                </td>


                                <td style="font-size:11px;">
                                <?php
                                    $id_planta = $row['planta_id'];
                                    $search_planta = "SELECT nombre FROM martech_plantas WHERE id = $id_planta";
                                    $run_search2 = mysqli_query($connection, $search_planta);
                                    $row_planta = mysqli_fetch_array($run_search2);
                                    echo $row_planta['nombre']." ";
                                ?>
                                
                                <?php
                                    $id_departamento = $row['departamento_id'];
                                    $search_dep = "SELECT nombre FROM martech_departamentos WHERE id = $id_departamento";
                                    $run_search3 = mysqli_query($connection, $search_dep);
                                    $row_dep = mysqli_fetch_array($run_search3);
                                    echo $row_dep['nombre'];
                                ?>
                                </td>


                                <td style="font-size:11px;"><?php echo $row['cantidad'] ?></td>
                                

                                <td style="font-size:11px;"><?php echo $row['0h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['0hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['1h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['1hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['2h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['2hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['3h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['3hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['4h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['4hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['5h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['5hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['6h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['6hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['7h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['7hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['8h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['8hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['9h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['9hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['10h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['10hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['11h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['11hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['12h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['12hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['13h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['13hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['14h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['14hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['15h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['15hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['16h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['16hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['17h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['17hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['18h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['18hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['19h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['19hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['20h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['20hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['21h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['21hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['22h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['22hm'] ?></td>

                                <td style="font-size:11px;"><?php echo $row['23h'] ?></td>
                                <td style="font-size:11px;"><?php echo $row['23hm'] ?></td>
                                

                                <td>
                                    <a href="index.php?page=hora_hora_reporte_detalles&id=<?php echo $row['id']; ?>" class="btn btn-primary">Ver</a>
                                </td>
                            </tr>

                            <?php endwhile; ?>

                        </tbody>

                        
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>





<!--filtrado por fechas-->
