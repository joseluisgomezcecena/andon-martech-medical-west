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
                    <h3 class="box-title">Reportes Planta 3  <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                    
                    
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
                            <th style="font-size:11px;">Fecha</th>
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
                            
                            $sql  = "SELECT * FROM CompletadasP3;";
                            $result = $database->query($sql);
                            while ($row = $result->fetch()): 
                            ?>

                            <tr>
                            
                                <td><?php echo $row["WorkOrder"]; ?></td>
                                <td><?php echo $row["Item"]; ?></td>
                                <td><?php echo $row["Quantity"]; ?></td>
                                <td><?php echo $row["Machine"]; ?></td>
                                <td><?php echo $row["Fecha"]; ?></td>
                                
                            
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


