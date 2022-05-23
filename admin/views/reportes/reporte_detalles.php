<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php $id = $_GET['id']; ?>
<style>
.dataTables_filter {
display: none; 
}
</style>





<section class="content-header">
    <h1>
        Reportes
        <small>Detalles</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Reportes</a></li>
        <li class="active">Detalles</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:10px;">Detalle</th>
                            <th style="font-size:10px;">Datos</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                                $query = mysqli_query($connection,"SELECT * FROM (SELECT * FROM martech_fallas WHERE id = $id UNION ALL SELECT * FROM martech_fallas_backup WHERE id = $id ) as l ");
                                $row = mysqli_fetch_array($query);
                            ?>
                            <tr>
                                <td>Id</td>
                                <td><?php echo $row['id']; ?></td>
                            </tr>
                            <tr>
                                <td>Tipo de error</td>
                                <td>
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
                            </tr>
                            <tr>
                                <td>Planta</td>
                                <td><?php echo $row['planta_nombre']; ?></td>
                            </tr>
                            <tr>
                                <td>Celda</td>
                                <td><?php echo $row['departamento_nombre']; ?></td>
                            </tr>
                            <tr>
                                <td>Info de producción</td>
                                <td><?php echo $row['info_departamento']; ?></td>
                            </tr>
                            <tr>
                                <td>Maquina /Equipo</td>
                                <td><?php echo $row['maquina_nombre']; ?></td>
                            </tr>
                            <tr>
                                <td>Centro de trabajo</td>
                                <td><?php echo $row['maquina_centro_trabajo']; ?></td>
                            </tr>
                            <tr>
                                <td>Numero de control</td>
                                <td><?php echo $row['maquina_numero_control']; ?></td>
                            </tr>
                            <tr>
                                <td>Atendido</td>
                                <td>
                                <?php
                                 if($row['atendido_flag'] == "si")
                                 {
                                     echo "Si"."&nbsp;&nbsp;<i style='color:green;' class='fa fa-check'></i>";
                                 }
                                 else
                                 {
                                    echo "No"."&nbsp;&nbsp;<i style='color:red;' class='fa fa-times'></i>";
                                 } 
                                 ?>
                                 </td>
                            </tr>
                            <tr>
                                 <td>Atendio</td>
                                 <td>
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
                            </tr>
                            <tr>
                                <td>Solucionado</td>
                                <td>
                                <?php
                                 if($row['offline'] == "no")
                                 {
                                     echo "Si"."&nbsp;&nbsp;<i style='color:green;' class='fa fa-check'></i>";
                                 }
                                 else
                                 {
                                    echo "No"."&nbsp;&nbsp;<i style='color:red;' class='fa fa-times'></i>";
                                 } 
                                 ?>
                                 </td>
                            </tr>
                            <tr>
                                 <td>Soluciono</td>
                                 <td>
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
                            </tr>
                            <tr>
                                <td>Fecha de inicio</td>
                                <td><?php $inicio = date_create($row['inicio']); echo date_format($inicio, "m/d/Y H:i:s"); ?></td>
                            </tr>
                            <tr>
                                <td>Fecha de atención</td>
                                <td>
                                <?php if($row['atendido_flag'] == "si")
                                {
                                    $inicio = date_create($row['atendido']);
                                    $fecha_atencion = date_format($inicio, "m/d/Y H:i:s");
                                    echo $fecha_atencion;
                                }
                                else
                                {
                                    echo "No ha sido atendido";
                                }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha de solución</td>
                                <td>
                                <?php
                                if($row['offline'] == "no")
                                {
                                    $inicio = date_create($row['fin']);
                                    echo date_format($inicio, "m/d/Y H:i:s");    
                                }
                                else
                                {
                                    echo "No ha sido solucionado";
                                }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripción del operador</td>
                                <td><?php echo $row['descripcion_operador']; ?></td>
                            </tr>
                            <tr>
                                <td>Descripción de atención</td>
                                <td><?php echo $row['descripcion_encargado']; ?></td>
                            </tr>
                            <tr>
                                <td>Descripción de solución</td>
                                <td><?php echo $row['descripcion_solucion']; ?></td>
                            </tr>
                            <tr>
                                <td>Horas perdidas en atención</td>
                                <td>
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

                                     echo round($horastotales,2);
                                    
                                 }
                                 else
                                 {
                                     echo round($horastotales,2);
                                 }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Horas perdidas en solución</td>
                                <td>
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

                                     echo round($horastotales,2);
                                    
                                 }
                                 else
                                 {
                                     echo round($horastotales,2);
                                 }
                                ?>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                        
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>