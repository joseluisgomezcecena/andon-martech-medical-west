<?php
require_once ("includes/topmenu.php");
require_once ("includes/sidebar.php");
//anterior es horaxhora complete 1
//SELECT * FROM ordenes WHERE planta_id = 1 AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '2020-08-12' AND '2020-08-12')
//SELECT * FROM ordenes WHERE planta_id = 1 AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '2020-08-10' AND '2020-08-12' AND planta_id = 1 AND completado = 1) ORDER BY `fecha_final` DESC
error_reporting(0);
?>

<section class="content-header">
    <h1>
        Reportes
        <small>Hora por Hora</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Hora por Hora </li>
    </ol>
</section>



<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-lg-12">
            <!-- general form elements  form form-inline form-multiline-->
            <div class="box box-danger">
                <form class="" method="post" role="form">

                    <div class="box-header with-border">
                        <h3 class="box-title">Filtrado por fechas</h3>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">



                        <div class="form-group col-lg-3">
                            <label for="InputFieldA">Desde</label>
                            <input type="text" class="form-control" id="InputFieldA" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="inicio" placeholder="Inicio" autocomplete="off" required>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="InputFieldB">Hasta</label>
                            <input type="text" class="form-control" id="InputFieldB" data-provide="datepicker" data-date-format="yyyy-mm-dd" name="fin" placeholder="Final" autocomplete="off" required>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="InputFieldB">Planta</label>
                            <select  class="form-control"  name="planta_id" required> 
                                <option value="">Seleccione</option>
                                <option value="1">Planta 1</option>
                                <option value="2">Planta 2</option>
                                <option value="3">Planta 3</option>
                            </select>
                        </div>



                        <div class="form-group">
                            <button type="submit" name="busqueda_fecha" class="btn btn-flat btn-primary"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                        </div>

                        <div class="form-group">
                            <a href="index.php?page=horaxhoracomplete1" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i>&nbsp;Re-establecer</a>
                        </div>

                        

                    </div>

                    <div class="box-footer">
                        <p>Es necesario llenar esta información para obtener el reporte.</p>
                    </div>
                </form>
            </div>
        </div>
    


    <!--col data -->


    <div class="col-lg-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte de Eficiencia por maquina y W.O al:  <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                    
                    
                </div>
                
                <!-- /.box-header -->


                <div class="box-body">

                    
                    
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:11px;">Maquina</th>
                            <th style="font-size:11px;">Celda</th>
                            <th style="font-size:11px;">Orden/Parte</th>
                            <th style="font-size:11px;">Cantidad WO</th>
			                <th style="font-size:11px;">Ef 1</th>
                            <th style="font-size:11px;">Ef 2</th>
                            <th style="font-size:11px;">Ef 3</th>
                            <th style="font-size:11px;">Inicio</th>
                            <th style="font-size:11px;">Fin</th>
                            <th style="font-size:11px;">Prod T1</th>
                            <th style="font-size:11px;">Prod T2</th>
                            <th style="font-size:11px;">Prod T3</th>
                            <th style="font-size:11px;">PPH 1</th>
                            <th style="font-size:11px;">PPH 2</th>
                            <th style="font-size:11px;">PPH 3</th>
                            <th style="font-size:11px;">Tiempo total</th>
                            <th style="font-size:11px;">Interrupciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            if(isset($_POST['busqueda_fecha']))
                            {
                                $inicio     = $_POST['inicio']." 00:00:00";
                                $fin        = $_POST['fin']." 23:59:59";;
                                $planta_id  = $_POST['planta_id'];


                                    $query = "SELECT * FROM ordenes WHERE planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND completado = 1) ORDER BY `fecha_final` ASC";
                                    $result = mysqli_query($connection2, $query);
                                
                                //$query = "SELECT * FROM ordenes WHERE actual = 1 AND planta_id = $planta_id AND (fecha_final BETWEEN '$inicio' AND '$fin') ";
                                while($row = mysqli_fetch_array($result)):
                            
                            ?>        

                            <tr>
                                    <td><?php echo $row['maquina'] ?></td> 
                                    <td><?php echo $row['celda'] ?></td> 
                                    <td><?php echo $row['work_order'] ?>/<?php echo $row['item'] ?></td> 
                                    <td><?php echo $row['cantidad'] ?></td> 
                                    <td><?php if($row['EfTT1'] > 0){echo round($row['EfTT1'], 1);}else{echo "N/A";}?></td>
                                    <td><?php if($row['EfTT2'] > 0){echo round($row['EfTT2'], 1);}else{echo "N/A";}  ?></td>
                                    <td><?php if($row['EfTT3'] > 0){echo round($row['EfTT3'], 1);}else{echo "N/A";}  ?></td>
                                    <td><?php echo $row['fecha_inicio'] ?></td>
                                    <td><?php  if($row['fecha_final'] == "0000-00-00 00:00:00"){echo "En proceso";}else{echo $row['fecha_final']; } ?></td>
                                   
                                    <td>
                                    <?php echo $row['C1T']; ?>
                                    </td>

                                    <td>
                                    <?php echo $row['C2T']; ?>
                                    </td>

                                    <td>
                                    <?php echo $row['C3T']; ?>
                                    </td>
                                    
                                    <td>
                                    <?php echo $row['pphR1']; ?>
                                    </td>
                                    
                                    <td>
                                    <?php echo $row['pphR2']; ?>
                                    </td>
                                    
                                    <td>
                                    <?php echo $row['pphR3']; ?>
                                    </td>
                                   
                                    <td>
                                        <?php

                                        if($row['fecha_final'] == "0000-00-00 00:00:00")
                                        {
                                            echo "N/A";
                                        }
                                        else
                                        {

                                            $datetime1 = new DateTime($row['fecha_final']);
                                            $datetime2 = new DateTime($row['fecha_inicio']);
                                            $interval = $datetime1->diff($datetime2);
                                            $days    = $interval->format('%d');
                                            $hours   = $interval->format('%h'); 
                                            $minutes = $interval->format('%i');
                                        
                                            $mins = ($days*24*60+$hours*60+$minutes); 
                                            echo $horastotales = round($mins/60,1);

                                        }

                                        
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $total_int = 0;
                                        $interrupts = "SELECT * FROM ordenes_interrumpidas WHERE id_orden = {$row['id']}";
                                        $run_interrupts = mysqli_query($connection2, $interrupts);
                                        if($nums = mysqli_num_rows($run_interrupts)> 0)
                                        {
                                            while($row_interrupts = mysqli_fetch_array($run_interrupts))
                                            {
                                                $total_int += $row_interrupts['hrsTotal'];
                                            }
                                            echo round($total_int, 1);
                                        }
                                        else
                                        {
                                            echo "0";
                                        }
                                         
                                        ?>
                                    </td>
                            </tr>            

                            <?php        

                                endwhile;    
                            }
                            ?>
                            
                                
                        </tbody>

                        <tfoot>
                        
                        </tfoot>
                    </table>
                </div>


            </div>
        </div>
            <!-- /.box -->






    <!---col data end -->




</section>
























<section class="content">
    <div class="row">
        
        <div class="col-md-12">
            
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes de eficiencia por Celda y turno al: <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                    
                    
                </div>
                
                


                <div class="box-body">

                    
                    
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:11px;">Celda</th>
                            <th style="font-size:11px;">Cantidad T1</th>
                            <th style="font-size:11px;">Cantidad T2</th>
                            <th style="font-size:11px;">Cantidad T3</th>
			                <th style="font-size:11px;"> Eff 1er</th>
                            <th style="font-size:11px;"> Eff 2do</th>
                            <th style="font-size:11px;"> Eff 3er</th>
                            
                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            if(isset($_POST['busqueda_fecha']))
                            {

                                
                                $get_celdas = "SELECT DISTINCT celda FROM ordenes WHERE planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND completado = 1) ORDER BY `fecha_final` ASC";
                                //$get_celdas = "SELECT DISTINCT celda FROM ordenes WHERE planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_get_celdas = mysqli_query($connection2, $get_celdas);
                                while($row_celdas = mysqli_fetch_array($run_get_celdas)):

                                //$query = "SELECT * FROM ordenes WHERE planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin') GROUP BY celda";
                                //$result = mysqli_query($connection2, $query);
                                //while($row = mysqli_fetch_array($result)):
                            ?>
                            
                            <tr>
                            
                            
                            <td>
                                <?php echo $row_celdas['celda']; ?>
                            </td>
                            
                            <!--
                            <td>
                                <?php
                                $cant = 0;
                                $cant1 = 0;
                                $cant2 = 0;
                                $cant3 = 0;
                                $cant_acum = 0;

                                
                                $query_cant = "SELECT cantT1, cantT2, cantT3, cantidad FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1)";
                                //$query_cant = "SELECT cantT1, cantT2, cantT3, cantidad FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_cant = mysqli_query($connection2, $query_cant);
                                while($row_cant = mysqli_fetch_array($run_cant))
                                {
                                    
                                    $cant1 += $row_cant['cantT1'];
                                    $cant2 += $row_cant['cantT2'];
                                    $cant3 += $row_cant['cantT3'];

                                    $cant += $row_cant['cantidad'];

                                }

                                echo $cant1+$cant2+$cant3." / ".$cant;
                                
                                ?>
                            </td>
                            -->




                            <td>
                                <?php
                                $cant1 = 0;
                                $cant_acum = 0;
                                $query_cant = "SELECT C1T FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1)"; 
                                //$query_cant = "SELECT C1T FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_cant = mysqli_query($connection2, $query_cant);
                                while($row_cant = mysqli_fetch_array($run_cant))
                                {
                                    
                                    $cant1 += $row_cant['C1T'];
                                

                                }

                                echo $cant1;
                                
                                ?>
                            </td>


                            <td>
                                <?php
                                $cant2 = 0;
                                $cant_acum = 0;
                                $query_cant = "SELECT C2T FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1)";  
                                //$query_cant = "SELECT C2T FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_cant = mysqli_query($connection2, $query_cant);
                                while($row_cant = mysqli_fetch_array($run_cant))
                                {
                                    
                                    $cant2 += $row_cant['C2T'];
                                

                                }

                                echo $cant2;
                                
                                ?>
                            </td>





                            <td>
                                <?php
                                $cant3 = 0;
                                $cant_acum = 0; 
                                $query_cant = "SELECT C3T FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1)"; 
                                $run_cant = mysqli_query($connection2, $query_cant);
                                while($row_cant = mysqli_fetch_array($run_cant))
                                {
                                    
                                    $cant3 += $row_cant['C3T'];
                                

                                }

                                echo $cant3;
                                
                                ?>
                            </td>











                            <td>
                                <?php
                                $cont1 = 0;
                                $eff1_acum = 0;
                                $query_eff1 = "SELECT EfTT1 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT1 != 0 AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1 AND EfTT1 != 0)"; 
 
                                //$query_eff1 = "SELECT EfTT1 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT1 != 0 AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_eff1 = mysqli_query($connection2, $query_eff1);
                                while($row_eff1 = mysqli_fetch_array($run_eff1))
                                {
                                    $cont1++;
                                    $eff1_acum += $row_eff1['EfTT1'];
                                }
                                if($eff1_acum>0)
                                {
                                    echo $eff1 = round($eff1_acum/$cont1);
                                    $eficiencia1 = $eff1;
                                }
                                else
                                {
                                    echo "0";
                                    $eficiencia1 = 0;
                                }
                                
                                ?>
                            </td>
                            
                            

                            <td>
                            <?php
                                $cont1 = 0;
                                $eff1_acum = 0;
                                $query_eff1 = "SELECT EfTT2 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT2 != 0 AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1 AND EfTT2 != 0)";  
                                //$query_eff1 = "SELECT EfTT2 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT2 != 0 AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_eff1 = mysqli_query($connection2, $query_eff1);
                                while($row_eff1 = mysqli_fetch_array($run_eff1))
                                {
                                    $cont1++;
                                    $eff1_acum += $row_eff1['EfTT2'];
                                }
                                if($eff1_acum>0)
                                {
                                    echo $eff1 = round($eff1_acum/$cont1);
                                    $eficiencia2 = $eff1;

                                }
                                else
                                {
                                    echo "0";
                                    $eficiencia2 = 0;
                                }
                                
                                ?>
                            </td>



                            
                            


                            <td>
                            <?php
                                $cont1 = 0;
                                $eff1_acum = 0;
                                
                                $query_eff1 = "SELECT EfTT3 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT3 != 0 AND planta_id = $planta_id AND actual = 1 AND completado = 0 OR (fecha_final BETWEEN '$inicio' AND '$fin' AND planta_id = $planta_id AND celda = '{$row_celdas['celda']}' AND completado = 1 AND EfTT3 != 0)"; 
                                //$query_eff1 = "SELECT EfTT3 FROM ordenes WHERE celda = '{$row_celdas['celda']}' AND EfTT3 != 0 AND planta_id = $planta_id AND (fecha_inicio BETWEEN '$inicio' AND '$fin')";
                                $run_eff1 = mysqli_query($connection2, $query_eff1);
                                while($row_eff1 = mysqli_fetch_array($run_eff1))
                                {
                                    $cont1++;
                                    $eff1_acum += $row_eff1['EfTT3'];
                                }
                                if($eff1_acum>0)
                                {
                                    echo $eff1 = round($eff1_acum/$cont1);
                                    $eficiencia3 = $eff1;

                                }
                                else
                                {
                                    echo "0";
                                    $eficiencia1 = 0;
                                }
                                
                                ?>
                            </td>




                            
                        

                            </tr>
                            <?php
                                endwhile; 
                            }
                            ?>
                                
                        </tbody>

                        <tfoot>
                            <?php
                                if(isset($_POST['busqueda_fecha']))
                                {
                                    echo "Eficiencia Total:".($eficiencia1+$eficiencia2+$eficiencia3)/3;
                                }
                                 
                            ?>
                        </tfoot>
                    </table>
                </div>


            </div>
          
        </div>
    </div>
</section>










