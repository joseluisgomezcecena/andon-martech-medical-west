<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>
<?php 
error_reporting(0);
date_default_timezone_set("America/Tijuana");
$horaactual = date("H:i:s");
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
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Reportes Planta 2 (Ensamble) <?php echo date_format(date_create(date("Y-m-d H:i:s")),"m/d/Y H:i:s");  ?></h3>
                    
                    
                </div>
                
                <!-- /.box-header -->


                <div class="box-body">

                    
                    
                    <table style="width: 100%;" id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="text-align:center;">Maquina</th>
                            <th style="text-align:center;">Order No./Part No.</th>
                            <th style="text-align:center;">Siguiente</th>
                            <th style="text-align:center;">Meta</th>
                            <th style="text-align:center;">Por Hora</th>
                            <th style="text-align:center;">Acumulado</th>
                            <th style="text-align:center;">Rate Acumulado</th>
                            <th style="text-align:center;">Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        
                        <?php 
            /**************************************************SERVER CODE START */
            
            //mis variables
            $today = date("Y-m-d");
            $colorwo = "style = 'color:black;'";



            $sql  = "SELECT Planta2.[Maquina] FROM Planta2";
            $result = $database->query($sql);
            if(!$result)
            {
                echo "<br>query fallo:<br>".$sql;
            }

            while($row_maquina = $result->fetch()):
            
                $maquina = $row_maquina["Maquina"];

            ?>


                <tr>
                    <td>
                        <?php
                        //MAQUINA ACTUAL
                        echo "<b>".$maquina."</b>"; 
                        ?>
                    </td>
                    
                    
                    <td>
                        <?php
                            //WORK ORDER AND PART NUMBER ACTUAL

                            if($horaactual >= "06:00:00" && $horaactual < "07:00:00")
                            {

                            //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 1);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }
                                    //si hoy es igual al dia de inicio de la orden
                                    $acumulado_requerido = $pph * 1;
                                    $total_nc = 0;
                                    $info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }

                            }

                            if($horaactual >= "07:00:00" && $horaactual < "08:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 2);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    
                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}

                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "08:00:00" && $horaactual < "09:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 3);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "09:00:00" && $horaactual < "10:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 4);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "10:00:00" && $horaactual < "11:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 5);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            try{
                            if($horaactual >= "11:00:00" && $horaactual < "12:00:00")
                            {
                             
                            //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 6);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }
                                
                            }
                            } catch(Exception $e){
                            }  

                            if($horaactual >= "12:00:00" && $horaactual < "13:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 7);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "13:00:00" && $horaactual < "14:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 8);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "14:00:00" && $horaactual < "15:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}

                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 9);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    if($noplan == 1){$info_hrs = "";}else{
                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";}
                                    
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "15:00:00" && $horaactual < "16:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 10);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "16:00:00" && $horaactual < "17:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina' ORDER BY ActualP2.ID DESC; ";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 11);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "17:00:00" && $horaactual < "18:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 12);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "18:00:00" && $horaactual < "19:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 13);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "19:00:00" && $horaactual < "20:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 14);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "20:00:00" && $horaactual < "21:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 15);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "21:00:00" && $horaactual < "22:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 16);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "22:00:00" && $horaactual < "23:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 17);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";
                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "23:00:00" && $horaactual < "00:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 18);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "00:00:00" && $horaactual < "01:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 19);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "01:00:00" && $horaactual < "02:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 20);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "02:00:00" && $horaactual < "03:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 21);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }



                            }

                            if($horaactual >= "03:00:00" && $horaactual < "04:00:00")
                            {
                                
                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 22);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }

                            }

                            if($horaactual >= "04:00:00" && $horaactual < "05:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 23);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }


                            }

                            if($horaactual >= "05:00:00" && $horaactual < "06:00:00")
                            {
                                

                                //contando work orders asignadas a cada maquina
                            $count = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM ActualP2 WHERE ActualP2.Machine = '$maquina';";
                            $run_count = $database->query($count);
                            $numberofproducts = $run_count ->fetch();
                            //echo "#".$numberofproducts["NumberOfProducts"]; 

                            //no se encontro ninguna 
                            if($numberofproducts["NumberOfProducts"] == 0)
                            {
                                $colorwo = "style = 'color:orange;'";
                                $order = "<p style='color:white; background-color: orange; text-align:center; font-weight:bold;'>NO PLAN"; 
                                $part = "</p>";
                                $cantidad_producir = "0";
                                $pph = 100;
                                $acumulado_requerido = $cantidad_producir;
                                $total_nc = 0;
                                $noplan =1;
                            }
                            else
                            {
                                $noplan = 0;
                                $sql2 = "SELECT ActualP2.Item, ActualP2.Machine, ActualP2.Quantity,  ActualP2.WorkOrder, ActualP2.Fecha FROM ActualP2  WHERE  ActualP2.Machine = '$maquina';";
                                $result2 = $database->query($sql2);

                                
                                while($row2 = $result2 ->fetch())
                                {

                                    $order = $row2["WorkOrder"]; 
                                    $part = $row2["Item"];
                                    $cantidad_producir = $row2["Quantity"];
                                    

                                    //fecha de inicio en formato Y-m-d H:i:s
                                    $datestartdb = $row2["Fecha"];
                                    

                                    
                                    //dejando solo Y-m-d
                                    $datestartday = substr($datestartdb,0,10);//solo dia sin hora                                    
                                    $datestartday = strtotime($datestartday);
                                    $datestartday = date('Y-m-d',$datestartday);
                                    //datestart day es la fecha de inicio de BD solo el dia

                                }


                                //sacando el pph
                                $sqlpph = "SELECT database.[PPH (ea x hora)] FROM [database] WHERE database.[Facility] = '$maquina' AND database.[Routing identifier] = '$part' ;";
                                $runsqlpph = $database->query($sqlpph);
                                $rowpph = $runsqlpph ->fetch();

                                
                                $pph = round($rowpph["PPH (ea x hora)"],0);
                             

                                if($today > $datestartday)
                                {
                                    
                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    $info_hrs = "<br>Tiempo de orden: <b>".$hours."</b> Hrs.<br>";


                                    //contando no completadas
                                    $count_nc = "SELECT Count(ActualP2.WorkOrder) AS NumberOfProducts FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_count = $database->query($count);
                                    $numberofproducts = $run_count ->fetch();

                                    $query_nocompletadas = "SELECT * FROM NoCompletadasP2 WHERE NoCompletadasP2.[Machine] = '$maquina' AND NoCompletadasP2.[WorkOrder] = '$order';";
                                    $run_query = $database->query($query_nocompletadas);
                                    $row_nc = $run_query ->fetch();
                                    


                                    $total_nc = $row_nc["Total"];
                                    $fecha_nc = $row_nc["Fecha"];
                                    
                                    
                                    if($row_nc["Total"] =="")
                                    {
                                        $total_nc = 0;
                                    }
                                   
                                    

                                   if($pph == 0)
                                   {
                                        $pph = 100;
                                   }

                                    $acumulado_requerido = $pph * ($hours + 24);
                                }
                                else
                                {
                                    if($pph == 0)
                                    {
                                        $pph = 100;
                                    }

                                    $date1 = new DateTime(date("Y-m-d H:i:s"));
                                    $date2 = new DateTime($datestartdb);

                                    $diff = $date2->diff($date1);

                                    $hours = $diff->h;
                                    $hours = $hours + ($diff->days*24);

                                    if($hours < 1)
                                    {
                                        $hours = 1;
                                    }

                                    $info_hrs = "<br>Tiempo: <b>".$hours."</b> Hrs.<br>";
                                    if($noplan == 1){$info_hrs = "";}
                                    $acumulado_requerido = $pph * $hours;
                                    //si hoy es igual al dia de inicio de la orden
                                    //$acumulado_requerido = $pph * 10;
                                    //$total_nc = 0;
                                    //$info_hrs ="";

                                }
                            } 

                            
                            echo $order;
                            echo "/";
                            echo $part;
                            if($noplan == 0)
                            {
                                echo $info_hrs;
                            }




                            }



                            
                        ?>
                    </td>
                    
                    


                    <td>
                        <?php 
                        //NEXT PART NUMBER AND WORK ORDER


                            if($horaactual >= "06:00:00" && $horaactual < "07:00:00")
                            {

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {


                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }

                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 

                            }

                            if($horaactual >= "07:00:00" && $horaactual < "08:00:00")
                            {

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "08:00:00" && $horaactual < "09:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                
                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "09:00:00" && $horaactual < "10:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                
                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "10:00:00" && $horaactual < "11:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "11:00:00" && $horaactual < "12:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "12:00:00" && $horaactual < "13:00:00")
                            {
                                

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                     
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart;
                            }

                            if($horaactual >= "13:00:00" && $horaactual < "14:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "14:00:00" && $horaactual < "15:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "15:00:00" && $horaactual < "16:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "16:00:00" && $horaactual < "17:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "17:00:00" && $horaactual < "18:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "18:00:00" && $horaactual < "19:00:00")
                            {
                                

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "19:00:00" && $horaactual < "20:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "20:00:00" && $horaactual < "21:00:00")
                            {

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "21:00:00" && $horaactual < "22:00:00")
                            {
                                

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "22:00:00" && $horaactual < "23:00:00")
                            {

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {
                                

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 


                            }

                            if($horaactual >= "23:00:00" && $horaactual < "00:00:00")
                            {
                                

                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "00:00:00" && $horaactual < "01:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "01:00:00" && $horaactual < "02:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "02:00:00" && $horaactual < "03:00:00")
                            {
                                
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "03:00:00" && $horaactual < "04:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }

                            if($horaactual >= "04:00:00" && $horaactual < "05:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 
                                
                            }

                            if($horaactual >= "05:00:00" && $horaactual < "06:00:00")
                            {
                                $count_pend = "SELECT Count (PendienteP2.[WorkOrder]) AS OrdenesPendientes FROM PendienteP2 WHERE PendienteP2.Machine = '$maquina';";
                                $run_count_pend = $database->query($count_pend);
                                $ordenespendientes = $run_count_pend ->fetch();
                                //echo "#".$numberofproducts["NumberOfProducts"]; 

                                //no se encontro ninguna 
                                if($ordenespendientes["OrdenesPendientes"] == 0)
                                {
                                    $nextorder = "No order";
                                    $nextpart = "No item";
                                }
                                else
                                {

                                    $sql3 = "SELECT TOP 1 PendienteP2.[WorkOrder], PendienteP2.[Item]  FROM PendienteP2 WHERE  PendienteP2.[Machine] = '$maquina';";
                                    $result3 = $database->query($sql3);
                                    if(!$result3)
                                    {
                                        echo "<br>query 3 fallo:<br>".$sql3;
                                    }
                                    while($row3 = $result3 ->fetch())
                                    {
                                        $nextorder = $row3["WorkOrder"];
                                        $nextpart  = $row3["Item"];
                                    }
                                }
                                echo $nextorder;
                                echo "/";
                                echo $nextpart; 



                            }
                        
                        ?>
                    </td>
                    
                    


                    <td>
                        <?php 
                        //CANTIDAD A PRODUCIR
                        echo $cantidad_producir;
                        ?>
                    </td>
                    
                    
                    <td>
                        <?php 

                            //POR HORA


                            if($horaactual >= "06:00:00" && $horaactual < "07:00:00")
                            {


                            $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine],  Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina],  Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["6:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["6:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;


                            }

                            if($horaactual >= "07:00:00" && $horaactual < "08:00:00")
                            {
                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["7:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["7:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;



                            }

                            if($horaactual >= "08:00:00" && $horaactual < "09:00:00")
                            {
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["8:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["8:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;
                            }

                            if($horaactual >= "09:00:00" && $horaactual < "10:00:00")
                            {
                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["9:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["9:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =   $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;


                            }

                            if($horaactual >= "10:00:00" && $horaactual < "11:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["10:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =   $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["10:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =   $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;


                            }

                            if($horaactual >= "11:00:00" && $horaactual < "12:00:00")
                            {
                                
                            $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["11:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["11:00 AM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =   $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;


                            }

                            if($horaactual >= "12:00:00" && $horaactual < "13:00:00")
                            {
                            
                            $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                            $result_active = $database->query($get_active);
                            while($row_active = $result_active->fetch())
                            {
                                $active = $row_active["Activo"];
                            }

                            

                            if($active == 1)//si es captura automatica
                            {
                                $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                $sensor = 1;
                            }      
                            else//captura manual
                            {
                                $sql4  = "SELECT Planta2.[Maquina], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $sensor = 0;
                            }
                            
                            //echo $sql4;
                                
                            $result4 = $database->query($sql4);
                            if(!$result4)
                            {
                                echo "Fallo query 4: ".$sql4;
                            }

                            if($sensor == 0)
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["12:00 PM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 

                                }
                            }
                            else
                            {
                                while ($row = $result4->fetch()) 
                                {
                                    $porhora  = $row["12:00 PM"];
                                    if($porhora == ""){$porhora = 0;}
                                    $acumulado =  $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                }
                            }
                            
                            
                            echo $porhora;
                            echo "/";
                            echo $pph;
                                
                            }

                            if($horaactual >= "13:00:00" && $horaactual < "14:00:00")
                            {
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["1:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["1:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;
                            }

                            if($horaactual >= "14:00:00" && $horaactual < "15:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["2:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["2:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;


                            }

                            if($horaactual >= "15:00:00" && $horaactual < "16:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["3:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["3:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "16:00:00" && $horaactual < "17:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["4:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["4:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "17:00:00" && $horaactual < "18:00:00")
                            {
                                

                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["5:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["5:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "18:00:00" && $horaactual < "19:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["6:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["6:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;




                            }

                            if($horaactual >= "19:00:00" && $horaactual < "20:00:00")
                            {
                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["7:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["7:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "20:00:00" && $horaactual < "21:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina],  Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["8:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["8:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "21:00:00" && $horaactual < "22:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["9:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["9:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;




                            }

                            if($horaactual >= "22:00:00" && $horaactual < "23:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["10:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["10:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;





                            }

                            if($horaactual >= "23:00:00" && $horaactual < "00:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["11:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["11:00 PM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;





                            }

                            if($horaactual >= "00:00:00" && $horaactual < "01:00:00")
                            {
                                
                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["12:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["12:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;




                            }

                            if($horaactual >= "01:00:00" && $horaactual < "02:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[1:00 AM], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[1:00 AM], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["1:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["1:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "02:00:00" && $horaactual < "03:00:00")
                            {

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[2:00 AM], Planta2Ex.[1:00 AM], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[2:00 AM], Planta2.[1:00 AM], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["2:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["2:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;

                                
                            }

                            if($horaactual >= "03:00:00" && $horaactual < "04:00:00")
                            {
                                

                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[3:00 AM], Planta2Ex.[2:00 AM], Planta2Ex.[1:00 AM], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[3:00 AM], Planta2.[2:00 AM], Planta2.[1:00 AM], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["3:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["3:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;



                            }

                            if($horaactual >= "04:00:00" && $horaactual < "05:00:00")
                            {
                                

                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[4:00 AM], Planta2Ex.[3:00 AM], Planta2Ex.[2:00 AM], Planta2Ex.[1:00 AM], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[4:00 AM], Planta2.[3:00 AM], Planta2.[2:00 AM], Planta2.[1:00 AM], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["4:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["4:00 AM"] + $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["4:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["4:00 AM"] + $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;


                            }

                            if($horaactual >= "05:00:00" && $horaactual < "06:00:00")
                            {

                                
                                $get_active = "SELECT Planta2.[Activo] FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                $result_active = $database->query($get_active);
                                while($row_active = $result_active->fetch())
                                {
                                    $active = $row_active["Activo"];
                                }
    
                                
    
                                if($active == 1)//si es captura automatica
                                {
                                    $sql4  = "SELECT Planta2Ex.[Machine], Planta2Ex.[5:00 AM], Planta2Ex.[4:00 AM], Planta2Ex.[3:00 AM], Planta2Ex.[2:00 AM], Planta2Ex.[1:00 AM], Planta2Ex.[12:00 AM], Planta2Ex.[11:00 PM], Planta2Ex.[10:00 PM], Planta2Ex.[9:00 PM], Planta2Ex.[8:00 PM], Planta2Ex.[7:00 PM], Planta2Ex.[6:00 PM], Planta2Ex.[5:00 PM], Planta2Ex.[4:00 PM], Planta2Ex.[3:00 PM], Planta2Ex.[2:00 PM], Planta2Ex.[1:00 PM], Planta2Ex.[12:00 PM], Planta2Ex.[11:00 AM], Planta2Ex.[10:00 AM], Planta2Ex.[9:00 AM], Planta2Ex.[8:00 AM], Planta2Ex.[7:00 AM], Planta2Ex.[6:00 AM]  FROM Planta2Ex WHERE Planta2Ex.[Machine] = '$maquina';";
                                    $sensor = 1;
                                }      
                                else//captura manual
                                {
                                    $sql4  = "SELECT Planta2.[Maquina], Planta2.[5:00 AM], Planta2.[4:00 AM], Planta2.[3:00 AM], Planta2.[2:00 AM], Planta2.[1:00 AM], Planta2.[12:00 AM], Planta2.[11:00 PM], Planta2.[10:00 PM], Planta2.[9:00 PM], Planta2.[8:00 PM], Planta2.[7:00 PM], Planta2.[6:00 PM], Planta2.[5:00 PM], Planta2.[4:00 PM], Planta2.[3:00 PM], Planta2.[2:00 PM], Planta2.[1:00 PM], Planta2.[12:00 PM], Planta2.[11:00 AM], Planta2.[10:00 AM], Planta2.[9:00 AM], Planta2.[8:00 AM], Planta2.[7:00 AM], Planta2.[6:00 AM]  FROM Planta2 WHERE Planta2.[Maquina] = '$maquina';";
                                    $sensor = 0;
                                }
                                
                                //echo $sql4;
                                    
                                $result4 = $database->query($sql4);
                                if(!$result4)
                                {
                                    echo "Fallo query 4: ".$sql4;
                                }
    
                                if($sensor == 0)
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["5:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado =  $row["5:00 AM"] + $row["4:00 AM"] + $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
    
                                    }
                                }
                                else
                                {
                                    while ($row = $result4->fetch()) 
                                    {
                                        $porhora  = $row["5:00 AM"];
                                        if($porhora == ""){$porhora = 0;}
                                        $acumulado = $row["5:00 AM"] + $row["4:00 AM"] + $row["3:00 AM"] + $row["2:00 AM"] + $row["1:00 AM"] + $row["12:00 AM"] + $row["11:00 PM"] + $row["10:00 PM"] + $row["9:00 PM"] + $row["8:00 PM"] + $row["7:00 PM"] + $row["6:00 PM"] + $row["5:00 PM"] + $row["4:00 PM"] + $row["3:00 PM"] + $row["2:00 PM"] + $row["1:00 PM"] + $row["12:00 PM"] + $row["11:00 AM"] + $row["10:00 AM"] + $row["9:00 AM"] + $row["8:00 AM"] + $row["7:00 AM"] + $row["6:00 AM"]; 
                                    }
                                }
                                
                                
                                echo $porhora;
                                echo "/";
                                echo $pph;

                                
                            }



                            
                            
                        ?>
                    
    
                        
                    </td>
                    
                    
                    <td>
                        <?php
                        //ACUMULADO
                        echo $acumulado_total = $acumulado + $total_nc;
                        echo "/";
                        echo $cantidad_producir;
                         
                        ?>
                    </td>
                    
                    
                    <td>
                        <?php
                        //RATE ACUMULADO


                        $acumulado_total = $acumulado + $total_nc;
                        echo $acumulado_total;
                        echo "/";
                        if($acumulado_requerido > $cantidad_producir)
                        {
                            echo $cantidad_producir;
                            $op = 0;
                        }
                        else
                        {
                            echo $acumulado_requerido;
                            $op =1;
                        }


                        if($op == 0)
                        {
                        
                            echo "<br>";
                            if($acumulado_total == 0 || $cantidad_producir == 0){$rate = 0;}else { $rate = ($acumulado_total/$cantidad_producir)*100; } 
                            if($rate>=100){$color = "green";}elseif($rate >=90 && $rate<=99.9999){$color = "yellow";}  else{$color = "red";};
                            echo round($rate)."%";
                            if($noplan == 1){$color = "grey";}
                        }   
                        else
                        {
                            echo "<br>";
                            if($acumulado_total == 0 || $acumulado_requerido == 0){$rate = 0;}else { $rate = ($acumulado_total/$acumulado_requerido)*100; } 
                            if($rate>=100){$color = "green";}elseif($rate >=90 && $rate<=99.9999){$color = "yellow";}else{$color = "red";};
                            echo round($rate)."%";   
                            if($noplan == 1){$color = "grey";} 
                        } 

                        ?>
                    </td>
                    
                    
                    <td>
                        <?php
                        //SEMAFORO DE ACUMULADO
                        ?>
                        <div class="center-block" style="width:60px; height: 60px; background-color: <?php echo $color ?>; border-radius: 50px;"></div>
                        
                    </td>
                </tr>

            

            

                    
            <?php
            endwhile;//maquina name loop end
            /**************************************************SERVER CODE END  */
            ?>




            </tbody>
        </table>
    </div>
</div>

                   
            <!-- /.box -->
        </div>
    </div>
</section>


