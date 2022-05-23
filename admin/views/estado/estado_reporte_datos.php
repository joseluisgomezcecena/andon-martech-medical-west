<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>


<section class="content-header">
    <h1>
        Estado de reportes <?php //echo $_SESSION['user_puesto'] ?>
        <small>Estado de la atención </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Estado de la atención</li>
    </ol>
</section>


<?php
$puesto = $_SESSION['user_puesto'];
if($puesto == 'supervisor')
{

    $query_usuario = "SELECT user_id FROM users WHERE user_name = '{$_SESSION['user_name']}'";
    $result_usuario = mysqli_query($connection, $query_usuario);
    $row_usuario = mysqli_fetch_array($result_usuario);
    $id_usuario =  $row_usuario['user_id'];

}
else
{
    echo"No tienes permiso de ver estos datos";

    require_once ("includes/footer.php");

    exit();
}

?>



<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                </div>
                <!-- /.box-header -->
                <div id="mitabla" class="box-body">
                    <table style="width: 100%;"  class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="font-size:12px;">Id</th>
                            <th style="font-size:12px;">Equipo</th>
                            <th style="font-size:12px;">#Serie</th>
                            <th style="font-size:12px;">Planta</th>
                            <th style="font-size:12px;">Ubicación</th>
                            <th style="font-size:12px;">Error</th>
                            <th style="font-size:12px;">Atendido</th>
                            <th style="font-size:12px;">Solucionado</th>
                            <th style="font-size:12px;">Atiende</th>
                            <th style="font-size:12px;">Tiempo de inicio</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        if(isset($_GET['id_departamento']))
                        {
                            $departamento = $_GET['id_departamento'];

                            $query = mysqli_query($connection,"SELECT * FROM martech_fallas WHERE (atendido_flag = 'no' OR offline = 'si') AND departamento_id = $departamento ORDER BY id DESC ");
                        }
                        else
                        {
                            $query = mysqli_query($connection,"SELECT * FROM martech_fallas WHERE (atendido_flag = 'no' OR offline = 'si') AND departamento_id = 0 ORDER BY id DESC ");

                        }




                        while($row = mysqli_fetch_array($query))
                        {

                            if($row['atendido_flag'] == 'no')
                            {
                                $atn = "<i style='color:red' class='fa fa-times'></i>";
                            }
                            else
                            {
                                $atn = "<i style='color:green' class='fa fa-check'></i>";
                            }


                            if($row['offline'] == 'si')
                            {
                                $off = "<i style='color:red' class='fa fa-times'></i>";
                            }
                            else
                            {
                                $off = "<i style='color:green' class='fa fa-check'></i>";
                            }




                            $start_date = new DateTime($row['inicio']);

                            $hoy = date("Y-m-d h:i:sa");

                            $since_start = $start_date->diff(new DateTime($hoy));

                            $since_start->days.' dias totales<br>';
                            $since_start->y.' años<br>';
                            $since_start->m.' meses<br>';
                            $d = $since_start->d.' dias<br>';
                            $h = $since_start->h.' horas<br>';
                            $m = $since_start->i.' minutos<br>';
                            $s = $since_start->s.' segundos<br>';

                            $tabla = <<<DELIMITER
                        <tr>
                            <td style="font-size:12px;">{$row['id']}</td>
                            <td style="font-size:12px;">{$row['maquina_nombre']}<br/><br></td>
                            <td style="font-size:12px;">{$row['maquina_serie']}</td>
                            <td style="font-size:12px;">{$row['planta_nombre']}</td>
                            <td style="font-size:12px;">{$row['departamento_nombre']}</td>
                            <td style="font-size:12px;">{$row['tipo_error']}</td>
                            <td style="font-size:12px;">
                                {$atn}
                            </td>
                            <td style="font-size:12px;">
                                {$off}
                            </td>
                            <td style="font-size:12px;">{$row['atendio']}</td>
                            <td style="font-size:12px;">{$row['inicio']} <br/> {$d} {$h} {$m} {$s} </td>

                            
                        </tr>
                            
DELIMITER;
                            echo $tabla;
                        }

                        ?>


                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>



