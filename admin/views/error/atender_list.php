<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<section class="content-header">
    <h1>
        Lista de fallas por atender 
        <small>Atención inmediata </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Fallas por atender</li>
    </ol>
</section>



<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Atención Inmediata</h3>
                </div>
                <!-- /.box-header -->
                <div id="mitabla" class="box-body">
                    <table style="width: 100%;"  class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:12px;">Id</th>
                            <th style="font-size:12px;width: 90px;">Equipo</th>
                            <th style="font-size:12px;">Planta</th>
                            <th style="font-size:12px;">Ubicación</th>
                            <th style="font-size:12px;">Error</th>
                            <th style="font-size:12px;width: 90px;">Tipo</th>
                            <th style="font-size:12px;">Acción</th>
                            <th style="font-size:12px;">Tiempo de inicio</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cont = 0;

                        

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
                                $error = "AND tipo_error = 'produccion'";
                            }
                           
                            $query = mysqli_query($connection,"SELECT * FROM martech_fallas WHERE atendido_flag = 'no' $error LIMIT 50");    
                        
                        }
                        else
                        {
                            $query = mysqli_query($connection,"SELECT * FROM martech_fallas WHERE atendido_flag = 'no' LIMIT 50");    
                        }
                        
                        while($row = mysqli_fetch_array($query))
                        {
                           
                            //$start_date = new DateTime('2007-09-01 04:10:58');
                            //$since_start = $start_date->diff(new DateTime('2012-09-11 10:25:00'));

                            $start_date = new DateTime($row['inicio']);

                            $hoy = date("Y-m-d h:i:sa");

                            $since_start = $start_date->diff(new DateTime($hoy));

                            $since_start->days.' days total<br>';
                             $since_start->y.' years<br>';
                             $since_start->m.' months<br>';
                             $d = $since_start->d.' dias<br>';
                             $h = $since_start->h.' horas<br>';
                             $m = $since_start->i.' minutos<br>';
                             $s = $since_start->s.' segundos<br>';

                            $tabla = <<<DELIMITER
                        <tr>
                            <td style="font-size:12px;">{$row['id']}</td>
                            <td style="font-size:12px;">{$row['maquina_nombre']}<br/><br></td>
                            <td style="font-size:12px;">{$row['planta_nombre']}</td>
                            <td style="font-size:12px;">{$row['departamento_nombre']}</td>
                            <td style="font-size:12px;">{$row['tipo_error']}</td>
                            <td style="font-size:12px;">{$row['descripcion_operador']}</td>
                            <td style="font-size:12px;"><a class='btn btn-primary' href="index.php?page=error_atender&id={$row['id']}">Atender</a></td>
                            <td style="font-size:12px;">{$row['inicio']} <br/> {$d} {$h} {$m} {$s} </td>                            
                        </tr>
                            
DELIMITER;
                            echo $tabla;
                        }

                        ?>


                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="font-size:12px;">Id</th>
                            <th style="font-size:12px;width: 90px;">Equipo</th>
                            <th style="font-size:12px;">Planta</th>
                            <th style="font-size:12px;">Ubicación</th>
                            <th style="font-size:12px;">Error</th>
                            <th style="font-size:12px;width: 90px;">Tipo</th>
                            <th style="font-size:12px;">Acción</th>
                            <th style="font-size:12px;">Tiempo</th>
                        
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>