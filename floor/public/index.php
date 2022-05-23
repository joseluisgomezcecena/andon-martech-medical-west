<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// init para mensajes sms
require_once ("../includes/header.php");

require_once ("functions/functions.php");


/******Modales de tipos de error****/

if(isset($_POST['error_producto']))
{
    include("../includes/producto.php");
}

if(isset($_POST['setup']))
{
    include("../includes/setup.php");
}


if(isset($_POST['falta_material']))
{
    include("../includes/falta_material.php");
}

if(isset($_POST['reemplazo']))
{
    include("../includes/reemplazo.php");
}


if(isset($_POST['caida']))
{
    include("../includes/caida.php");
}

if(isset($_POST['agua']))
{
    include("../includes/agua.php");
}


if(isset($_POST['energia']))
{
    include("../includes/energia.php");
}


if(isset($_POST['sop']))
{
    include("../includes/sop.php");
}


if(isset($_POST['qap']))
{
    include("../includes/qap.php");
}



if(isset($_POST['ensamble']))
{
    include("../includes/ensamble.php");
}


/******Modales de tipos de error****/

/*************Funciones************/

registrarError();

?>



<div class="container">
    <form method="post">
        <div style="box-shadow: 0 1px 4px 0 rgba(0,0,0,0.2);" class="panel panel-default arriba">
          <!-- Default panel contents -->
            <div class="panel-heading">
                <img class="img-responsive" src="img/transparente.png">
                
                <a href="../../admin" style="float:right; margin-top:-50px;" class=" btn btn-primary">Ir a Atención de errores</a>
           </div>
          
            <div class="panel-body">
                <div class="col-lg-12">
                <h2></h2>     
            </div>        

              
          
            <!--*******************************************EQUIPO DE SAUL-SETUP********************-->
                        
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-flat" name="setup" data-toggle="modal" >Set Up</button>
            </div>


            <!--*******************************************MATERIAL********************-->
            
            
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-danger btn-lg btn-block btn-flat" name="falta_material"  >Requerir Orden Almacen</button>
            </div>
            

            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-danger btn-lg btn-block btn-flat" name="reemplazo" data-toggle="modal" >Reemplazo de Material</button>
            </div>
            
                
            <!--*******************************************MANTENIMIENTO********************-->
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-warning btn-lg btn-block btn-flat" name="caida" >Maquina Caida</button>
            </div>


            
            
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-warning btn-lg btn-block btn-flat" name="agua" data-toggle="modal" >Falta Suministro de Agua</button>
            </div>




            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-warning btn-lg btn-block btn-flat" name="energia" data-toggle="modal" >Falta Suministro de Energia</button>
            </div>



            <!--*******************************************INGENIERIA********************-->
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-info btn-lg btn-block btn-flat" name="sop" data-toggle="modal" >Falta de claridad en SOP</button>
            </div>


            <!--*******************************************INGENIERIA DE CALIDAD********************-->
            
            
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-success btn-lg btn-block btn-flat" name="qap" data-toggle="modal" >Falta de claridad en QAP (Calidad)</button>
            </div>


            
            <!--*******************************************FALLA EN CALIDAD DE MATERIAL********************-->
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <!-- boton -->
                <button type="submit" class="btn btn-success btn-lg btn-block btn-flat" name="error_producto" data-toggle="modal" >Falla en calidad de Producto</button>
            </div>

            
            <div class="col-lg-6 col-md-6 col-sm-12 espacios">
                <button style="background-color: black;" type="submit" class="btn btn-primary btn-lg btn-block btn-flat" name="ensamble" data-toggle="modal" >Maquina Ensamble</button>
            </div>
            


        </div>
    </form>
</div>



<?php include("../includes/footer.php") ?>

