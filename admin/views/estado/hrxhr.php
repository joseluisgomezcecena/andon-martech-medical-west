<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

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
                            <th style="font-size:11px;">Machine</th>
                            <th style="font-size:11px;">6:00 am</th>
                            <th style="font-size:11px;">7:00 am</th>
			                <th style="font-size:11px;">8:00 am</th>
                            <th style="font-size:11px;">9:00 am</th>
                            <th style="font-size:11px;">10:00 am</th>
                            <th style="font-size:11px;">11:00 am</th>
                            <th style="font-size:11px;">12:00 pm</th>
                            <th style="font-size:11px;">1:00 pm</th>
                            <th style="font-size:11px;">2:00 pm</th>
                            <th style="font-size:11px;">3:00 pm</th>
                            <th style="font-size:11px;">4:00 pm</th>
                            <th style="font-size:11px;">5:00 pm</th>
                            <th style="font-size:11px;">6:00 pm</th>
                            <th style="font-size:11px;">7:00 pm</th>
                            <th style="font-size:11px;">8:00 pm</th>
                            <th style="font-size:11px;">9:00 pm</th>
                            <th style="font-size:11px;">10:00 pm</th>
                            <th style="font-size:11px;">11:00 pm</th>
                            <th style="font-size:11px;">12:00 am</th>
                            <th style="font-size:11px;">1:00 am</th>
                            <th style="font-size:11px;">2:00 am</th>
                            <th style="font-size:11px;">3:00 am</th>
                            <th style="font-size:11px;">4:00 am</th>
                            <th style="font-size:11px;">5:00 am</th>

                        </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                            
                            //base de datos ACCESS
                            $db_username = 'root'; //username
                            $db_password = ''; //password
                            $database_path = "C:\\BD-Andon\\HrxHr.accdb";

                            if (!file_exists($database_path)) 
                            {
                                die("No se encuentra base de datos !");
                            }
                            
                            $database = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$database_path; Uid=$db_username; Pwd=$db_password;");

                            if(!$database)
                            {
                                echo "No se pudo conectar a base de datos.";
                            }
                            
                            $sql  = "SELECT * FROM Planta1;";
                            $result = $database->query($sql);
                            while ($row = $result->fetch()): 
                            ?>

                            <tr>
                            
                                <td><?php echo $row["Maquina"]; ?></td>
                                <td><?php echo $row["6:00 AM"]; ?></td>
                                <td><?php echo $row["7:00 AM"]; ?></td>
                                <td><?php echo $row["8:00 AM"]; ?></td>
                                <td><?php echo $row["9:00 AM"]; ?></td>
                                <td><?php echo $row["10:00 AM"]; ?></td>
                                <td><?php echo $row["11:00 AM"]; ?></td>
                                <td><?php echo $row["12:00 PM"]; ?></td>
                                <td><?php echo $row["1:00 PM"]; ?></td>
                                <td><?php echo $row["2:00 PM"]; ?></td>
                                <td><?php echo $row["3:00 PM"]; ?></td>
                                <td><?php echo $row["4:00 PM"]; ?></td>
                                <td><?php echo $row["5:00 PM"]; ?></td>
                                <td><?php echo $row["6:00 PM"]; ?></td>
                                <td><?php echo $row["7:00 PM"]; ?></td>
                                <td><?php echo $row["8:00 PM"]; ?></td>
                                <td><?php echo $row["9:00 PM"]; ?></td>
                                <td><?php echo $row["10:00 PM"]; ?></td>
                                <td><?php echo $row["11:00 PM"]; ?></td>
                                <td><?php echo $row["12:00 AM"]; ?></td>
                                <td><?php echo $row["1:00 AM"]; ?></td>
                                <td><?php echo $row["2:00 AM"]; ?></td>
                                <td><?php echo $row["3:00 AM"]; ?></td>
                                <td><?php echo $row["4:00 AM"]; ?></td>
                                <td><?php echo $row["5:00 AM"]; ?></td>
                                
                            
                            </tr>

                            <?php endwhile; ?>

                            
                            
                                
                        </tbody>

                        <tfoot>
                        <tr>
                            <th style="font-size:11px;">Machine</th>
                            <th style="font-size:11px;">6:00 am</th>
                            <th style="font-size:11px;">7:00 am</th>
			                <th style="font-size:11px;">8:00 am</th>
                            <th style="font-size:11px;">9:00 am</th>
                            <th style="font-size:11px;">10:00 am</th>
                            <th style="font-size:11px;">11:00 am</th>
                            <th style="font-size:11px;">12:00 pm</th>
                            <th style="font-size:11px;">1:00 pm</th>
                            <th style="font-size:11px;">2:00 pm</th>
                            <th style="font-size:11px;">3:00 pm</th>
                            <th style="font-size:11px;">4:00 pm</th>
                            <th style="font-size:11px;">5:00 pm</th>
                            <th style="font-size:11px;">6:00 pm</th>
                            <th style="font-size:11px;">7:00 pm</th>
                            <th style="font-size:11px;">8:00 pm</th>
                            <th style="font-size:11px;">9:00 pm</th>
                            <th style="font-size:11px;">10:00 pm</th>
                            <th style="font-size:11px;">11:00 pm</th>
                            <th style="font-size:11px;">12:00 am</th>
                            <th style="font-size:11px;">1:00 am</th>
                            <th style="font-size:11px;">2:00 am</th>
                            <th style="font-size:11px;">3:00 am</th>
                            <th style="font-size:11px;">4:00 am</th>
                            <th style="font-size:11px;">5:00 am</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>


            </div>
            <!-- /.box -->
        </div>
    </div>
</section>


