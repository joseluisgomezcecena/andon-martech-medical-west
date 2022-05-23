<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<section class="content-header">
    <h1>
        Reportes de eficiencia
        <small>Hora por Hora</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Hora por Hora (Eficiencia) </li>
    </ol>
</section>



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
                            <button type="submit" name="busqueda_fecha" class="btn btn-flat btn-primary"><i class="fa fa-search"></i></button>
                        </div>

                        <div class="form-group">
                            <a href="index.php?page=horaxhoracomplete1" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i></a>
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






<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes Planta 1 (Moldeo) <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                    
                    
                </div>
                
                <!-- /.box-header -->


                <div class="box-body">

                    
                    
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:11px;">Work Order</th>
                            <th style="font-size:11px;">Item</th>
                            <th style="font-size:11px;">Quantity</th>
			                <th style="font-size:11px;">Machine</th>
                            <th style="font-size:11px;">PPH</th>
                            <th style="font-size:11px;">Fecha Inicio</th>
                            <th style="font-size:11px;">Fecha Fecha Final</th>
                            <th style="font-size:11px;">PPH Real</th>
                            <th style="font-size:11px;">Eficiencia</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            
                            
                            if(isset($_POST['busqueda_fecha']))
                            {

                                //si los campos de fecha estan llenos
                                $post_inicio = $_POST['inicio']." 00:00:00";
                                $post_fin    = $_POST['fin']." 23:59:59";
                                

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

                                $sql  = "SELECT * FROM eficiencia_report WHERE fecha_fin != '0000-00-00' $inicio $fin;";
                            }
                            else
                            {
                                $sql  = "SELECT * FROM eficiencia_report WHERE fecha_fin != '0000-00-00';";
                            }

                            
                            
                            $result = mysqli_query($connection,$sql);
                            while($row = mysqli_fetch_array($result)): 
                            ?>

                            <tr>
                            
                                <td><?php echo $row["workorder"]; ?></td>
                                <td><?php echo $row["item"]; ?></td>
                                <td><?php echo $row["quantity"]; ?></td>
                                <td><?php echo $row["machine"]; ?></td>
                                <td><?php echo $row["pph"]; ?></td>
                                <td><?php echo $row["fecha_inicio"]; ?></td>
                                <td><?php echo $row["fecha_fin"]; ?></td>
                                <td><?php echo $row["pph_real"]; ?></td>
                                <td><?php echo round(($row["pph_real"]*100 )/ $row["pph"],0);  ?> %</td>
                            </tr>

                            <?php endwhile; ?>

                            
                            
                                
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


