<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<?php  
if(isset($_GET['atencion']))
{
    include('includes/martech_atender/atender_form.php');
}

if(isset($_GET['no_orden']))
{
    include('includes/martech_atender/no_hay_orden_form.php');
}


?>


<section class="content-header">
    <h1>
        Errores
        <small>Atencion de Errores</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?page=atender_list"><i class="fa fa-warning"></i> Fallas por atender</a></li>
        <li class="active"> Errores</li>
    </ol>
</section>




<section  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Detalles del error</h3>
                </div>

                



                <div class="box-body">
                    <div id="atender" >
                        
                        <?php                    
                        $id = $_GET['id'];

                

                        $query = "SELECT * FROM martech_fallas WHERE id = $id";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_array($result);
                        
                        $id_maquina = $row['maquina_id'];
                        $tipo = $row['tipo_error'];

                        ?>


                        <?php 
                        if($row['offline'] == 'si' && $row['atendido_flag'] == 'no')
                        {
                            $label_color = "label-danger";
                            $label_text  = "No atendido";
                            $display = "";
                        }
                        elseif($row['offline'] == 'si' && $row['atendido_flag'] == 'si')
                        {
                            $label_color = "label-warning";
                            $label_text  = "En proceso";
                            $display = "";
                        }
                        else
                        {
                            $label_color = "label-success";
                            $label_text  = "Solucionado";
                            $display = "display:none;";
                        }
                        ?>    
                        <h4>Estado de error <span class="label <?php echo $label_color ?>"><?php echo $label_text ?></span></h4>


                    
                     

                    
                
                <table class="table table-striped table-bordered table-hover">
                
                    <thead>
                        <tr>
                            <th>Caracteristica</th>
                            <th>Datos</th>
                        </tr>
                    <thead>
                    <tbody>
                        
                        <tr>
                            <td>Id Error</td>
                            <td><?php echo $row['id']; ?></td>
                        </tr>

                        <?php 
                        if($tipo == "caida" || $tipo == "energia" || $tipo == "agua" )
                        {
                        ?>

                        <tr>
                            <td>Folio orden de mantenimiento</td>
                            <td><?php echo strtoupper($row['folio_mantenimiento']); ?></td>
                        </tr>

                        <?php 
                        }
                        ?>




                        <tr>
                            <td>Nombre del equipo</td>
                            <td><?php echo $row['maquina_nombre']; ?></td>
                        </tr>

                        <tr>
                            <td>Centro de trabajo</td>
                            <td><?php echo $row['maquina_centro_trabajo']; ?></td>
                        </tr>

                        <tr>
                            <td>Numero de ID / Serie</td>
                            <td><?php echo $row['maquina_serie']; ?></td>
                        </tr>

                        <tr>
                            <td>Numero de control</td>
                            <td><?php echo $row['maquina_numero_control']; ?></td>
                        </tr>

                        <tr>
                            <td>Planta</td>
                            <td><?php echo $row['planta_nombre']; ?></td>
                        </tr>

                        <tr>
                            <td>Ubicacion</td>
                            <td><?php echo $row['departamento_nombre']; ?></td>
                        </tr>


                        <tr>
                            <td><b>Tipo de error</b></td>
                            <td><?php echo $row['tipo_error']; ?></td>
                        </tr>


                        <tr>
                            <td>Inicio de error</td>
                            <td><?php echo $row['inicio']; ?></td>
                        </tr>

                        <tr>
                            <td>Mensaje del operador</td>
                            <td>
                                <?php
                                  echo $row['descripcion_operador'];
                                ?>
                             </td>
                        </tr>

                        
                        <tr>
                            <td>Atendio</td>
                            <td>
                                <?php 
                                if($row['atendio']== NULL)
                                {
                                    echo "No atendido";
                                }
                                else
                                {
                                    echo $row['atendio'];    
                                }         
                                ?>
                            </td>
                        </tr>


                        <tr>
                            <td>Resolvio</td>
                            <td>
                                <?php 
                                if($row['resolvio']== NULL)
                                {
                                    echo "No resuelto";
                                }
                                else
                                {
                                    echo $row['resolvio'];    
                                }         
                                ?>
                            </td>
                        </tr>
                        
                    </tbody>



                </table>
                </div>
                <div style="<?php echo $display ?>" id="time-elapsed"></div>
                <br><br/>

                 <div class='box-footer'>
                    <a class='btn btn-primary' href='index.php?page=error_atender&id=<?php echo $id ?>&atencion=true'>Atender</a>       
                
                    <?php

                        if($tipo == "falta_material" || $row['tipo_error'] == "reemplazo"):   
                    
                    ?>
                        <!--
                        <a class='btn btn-danger' href='index.php?page=error_atender&id=<?php echo $id ?>&no_orden=true'>No Se Tiene La Orden</a>       
                        -->

                    <?php 
                        endif;
                    ?>
                
                </div>       


                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>



<script>
var startDateTime = new Date("<?php echo $row['inicio'] ?>"); // YYYY (M-1) D H m s (start time and date from DB)
var startStamp = startDateTime.getTime();

var newDate = new Date();
var newStamp = newDate.getTime();

var timer;

function updateClock() {
    newDate = new Date();
    newStamp = newDate.getTime();
    var diff = Math.round((newStamp-startStamp)/1000);
    
    var d = Math.floor(diff/(24*60*60));
    diff = diff-(d*24*60*60);
    var h = Math.floor(diff/(60*60));
    diff = diff-(h*60*60);
    var m = Math.floor(diff/(60));
    diff = diff-(m*60);
    var s = diff;
    
    document.getElementById("time-elapsed").innerHTML = d+" dia(s), "+h+" hora(s), "+m+" minuto(s), "+s+" segundo(s) activo";
}

setInterval(updateClock, 1000);                            
</script>
