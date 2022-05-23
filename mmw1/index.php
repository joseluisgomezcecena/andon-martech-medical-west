<?php  require_once('config/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MMW - Andon Production Screens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" />
   
    <style>
    ::-webkit-scrollbar 
    { 
    display: none; 
    }

    </style>

</head>
<body style='background-color:black;'>

    <audio id="xyz" src="assets/sounds/alarma.mp3" preload="auto"></audio>

    <div id="error" class=''>
        
                    
                    <?php

                    if(isset($_GET['id']))
                    {
                        $id = $_GET['id'];
                        
                        $query = "SELECT * FROM martech_fallas WHERE offline = 'si' AND departamento_id = $id ORDER BY id DESC;";

                         /***************************sonidos */
                            $play = 0;
                            $sound_query = "SELECT * FROM martech_fallas  WHERE atendido_flag = 'no' AND alarma = 'no' AND departamento_id = $id ORDER by id DESC";
                            $run_sound = mysqli_query($connection, $sound_query);
                            if($alarmas = mysqli_num_rows($run_sound)>0)
                            {
                            
                                while($row_sound = mysqli_fetch_array($run_sound))
                                {
                                    $id_falla  =  $row_sound['id'];

                                    $link = "assets/sounds/alarma.mp3"; 

                                    $audio = "<embed src='".$link."' style='opacity:.0; margin-top:-200px'>"; 

                                    echo $audio;

                                    $set_flag = "UPDATE martech_fallas SET alarma='si' WHERE id = $id_falla";
                                    $run_set_flag_query = mysqli_query($connection, $set_flag);

                                }
                            
                            }

                        /***************************sonidos */




                    } 
                    else
                    {
                        $query = "SELECT * FROM martech_fallas WHERE atendido_flag = 'no' ORDER BY id DESC ";    
                    }
                    
                   
                    
                    $result = mysqli_query($connection, $query);

                    if($error_count = mysqli_num_rows($result)==0)
                    {
                        $ok = <<<DELIMITER
                        <div class='text-center col-lg-12' style="width:100%; height:100vh; background-color:green; background-image: linear-gradient(to bottom, #55ff00, #3bb300); color:white; text-shadow: 2px 2px 2px #000000;">
                             <h1 style='vertical-align:middle; padding-top:20%; font-size:104px;' class='text-center;'>OK</h1>
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

                    if($row['atendido_flag'] == 'si')
                    {
                        $bg = 'bg-warning;';
                        $bc = 'background-color: yellow;';
                        $bi = 'background-image: linear-gradient(to bottom, #f5ef42, #f2ba00);';
                        $atiende = 'Atiende: '.$row['atendio'];
                    }
                    else
                    {
                        $bg = 'bg-danger;';
                        $bc = 'background-color: red;';
                        $bi = 'background-image: linear-gradient(to bottom, #ff1a1a, #660000);';
                        $atiende = '';
                    }

                    ?>
                    
                            
                                    <div class="row">
                                        
                                            <div style="<?php echo $bc ?> color: white; vertical-align: middle; <?php echo $bi ?> text-shadow: 2px 2px 2px #000000;" class="col-lg-12">

                                                <div style="margin:10px;" class="row">
                                                    <div class="col-lg-6">
                                                        <h1>
                                                            <?php echo $row['maquina_nombre']; ?> 
                                                        </h1>
                                                        <h3><?php echo $d.$h.$m.$s; ?></h3>
                                                    </div>
                                                    <div class="col-lg-6" >
                                                        <h2 style="float:right; text-align:right;">
                                                            <?php
                                                            echo $error."<br>";
                                                            echo $row['descripcion_operador']."<br>";
                                                            echo $filtro."<br>";
                                                            echo $atiende;
                                                            ?>
                                                        </h2>
                                                    </div>
                                                </div>


                                            </div>
                                        
                                    </div>
                                
                          
                            
                            
                    <?php 
                    endwhile;
                    ?>
              
        </div>
    </div>

    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
    
    <script>
    (function worker() {
        $.ajax({
            url: 'index.php', 
            success: function(data) {
            $("#error").load(location.href+" #error>*","");
            
            },
            complete: function() {
            
            setTimeout(worker, 5000);
            }
        });
})();


    
    </script>

</body>
</html>