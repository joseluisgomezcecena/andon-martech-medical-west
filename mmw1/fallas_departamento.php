<?php require_once('config/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MMW - Andon Production Screens by department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <style>
    ::-webkit-scrollbar { 
    display: none; 
    }

    </style>
</head>
<body style='background-color:black;'>

<audio id="xyz" src="assets/sounds/alarma.mp3" preload="auto"></audio>


    <div id="error" >
        <div >
            <table class="table table-striped">  
                <tbody >
                    <?php
                    if(isset($_GET['id']))
                    {
                        $responsable = $_GET['id'];

                        if($responsable == 'maquinas')
                        {
                            $error = "WHERE tipo_error = 'setup'";
                        }
                        elseif($responsable == 'mantenimiento')
                        {
                            $error = "WHERE (tipo_error = 'caida' OR tipo_error = 'agua' OR tipo_error = 'energia')";
                        }
                        elseif($responsable == 'materiales')
                        {
                            $error = "WHERE (tipo_error = 'falta_material' OR tipo_error = 'reemplazo')";

                        }
                        elseif($responsable == 'ingenieria calidad')
                        {
                            $error = "WHERE tipo_error = 'qap' OR tipo_error ='producto'";
                        }
                        elseif($responsable == 'ingenieria')
                        {
                            $error = "WHERE tipo_error = 'sop'";
                        }


                        
                        /***************************sonidos */
                        $play = 0;
                        $sound_query = "SELECT * FROM martech_fallas  $error  AND atendido_flag = 'no' AND alarma = 'no' ORDER by id DESC";
                        $run_sound = mysqli_query($connection, $sound_query);
                        if($alarmas = mysqli_num_rows($run_sound)>0)
                        {
                           
                           
                            $link = "assets/sounds/alarma.mp3"; 

                            $audio = "<embed src='".$link."' style='opacity:.0; margin-top:-200px'>"; 

                            echo $audio;

                            $set_flag = "UPDATE martech_fallas SET alarma='si' $error";
                            $run_set_flag_query = mysqli_query($connection, $set_flag);
                           
                         
                        }

                        /***************************sonidos */

                        
                        $query = "SELECT * FROM martech_fallas  $error  AND atendido_flag = 'no' ORDER by id DESC";


                    } 
                    else
                    {
                        $query = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' ORDER by id DESC ";    
                    }
                    

                    
                    $result = mysqli_query($connection, $query);

                    if($error_count = mysqli_num_rows($result)==0)
                    {
                        $ok = <<<DELIMITER
                        <div class='text-center col-lg-12' style="width:100%; height:100vh; background-color:green; background-image: linear-gradient(to bottom, #55ff00, #3bb300); color:white; text-shadow: 2px 2px 2px #000000;">
                             <h1 style='vertical-align:middle; margin-top:20%; font-size:104px;' class='text-center;'>OK</h1>
                        </div>                      
DELIMITER;
echo $ok;
                    }


                    while($row = mysqli_fetch_array($result)):
                    //calculo de tiempo

                    $start_date = new DateTime($row['inicio']);

                    $hoy = date("Y-m-d h:i:sa");

                    $since_start = $start_date->diff(new DateTime($hoy));

                    $since_start->days.' dias total<br>';
                    $since_start->y.' años<br>';
                    $since_start->m.' meses<br>';
                    $d = $since_start->d.' dias ';
                    $h = $since_start->h.' horas ';
                    $m = $since_start->i.' minutos ';
                    $s = $since_start->s.' segundos ';

                    //calculo de tiempo

                    //datos especificos por departamento
                    $datos = $row['info_departamento'];
                    
                    //materiales
                    $filtro = substr($datos, strpos($datos, "_") + 1);
                    
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
                        $error = "Falta de Claridad en QAP";
                    }
                    elseif($row['tipo_error'] == 'sop')
                    {
                        $error = "Falta de Claridad en SOP";
                    } 
                    elseif($row['tipo_error'] == 'producto')
                    {
                        $error = "Error en Calidad de Producto";
                    } 

                    ?>
                    
                            <tr class="bg-danger">
                                <td style="background-color:red; color: white; vertical-align: middle; background-image: linear-gradient(to bottom, #ff1a1a, #660000);text-shadow: 2px 2px 2px #000000;">
                                    <h1>
                                        <?php echo $row['maquina_nombre']; ?> 
                                    </h1>
                                    <h4><?php echo $row['planta_nombre'] ?> <?php echo $row['departamento_nombre'] ?></h4>
                                    <h3><?php echo $d.$h.$m.$s; ?></h3>
                                </td>
                                <td style="background-color:red; color: white; vertical-align: middle; background-image: linear-gradient(to bottom, #ff1a1a, #660000);text-shadow: 2px 2px 2px #000000; ">
                                    <h2 style="float:right; text-align:right;">
                                        <?php
                                        echo $error."<br>";
                                        echo $row['descripcion_operador']."<br>";
                                        echo $filtro;
                                        ?>
                                    </h2>
                                </td>
                            <tr>
                            
                    <?php 
                    endwhile;
                    ?>

                    <?php 
                    
                    ?>

                <tbody>    
            </table>
        </div>
    </div>

    

    
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    
    <script>

    //if(x > 10)
    //{
        //document.getElementById('xyz').play();
        //alert("Thank you!");
    //}
    </script>

    <script>
        (function worker() {
            $.ajax({
                url: 'index.php', 
                success: function(data) {
                $("#error").load(location.href+" #error>*","");
                
                },
                complete: function() {
                
                setTimeout(worker, 10000);
                //alert('funciona!');
                }
            });
        })();

    </script>



</body>
</html>