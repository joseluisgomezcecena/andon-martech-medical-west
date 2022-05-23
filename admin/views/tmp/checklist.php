<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php");
$puesto = $_SESSION['user_puesto'];
if($puesto == 'supervisor' || $puesto == 'lider')
{
    header("Location: index.php?page=estado_reporte");
}
?>


<section class="content-header">
    <h1>
        Inicio 
        <small>Panel de control </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Panel de control</li>
    </ol>
</section>

<?php if($puesto == "lider" || $puesto == "supervisor") {echo"";}else{require_once ("includes/panels.php");} ?>


<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Atención Inmediata</h3>
                </div>
                <!-- /.box-header -->
                <div id="mitabla" class="box-body">
                    <form method="post">

		
				1.	Visualmente verifique que el equipo se encuentre limpio y  libre de residuos de material de procesos.
2.	Antes de iniciar verifique que el equipo no se encuentre con algún candado o inhabilitado por parte de mantenimiento.   
3.	Antes de iniciar la operación verifique que el equipo no tenga algún tipo de fuga o derrame de líquido.
4.	Antes de iniciar operación verifique que todos los suministros (aire, agua, temperatura y presión hidráulica) se encuentre dentro de los límites de operación. 
5.	Antes de iniciar la operación verifique que el equipo no tenga alguna alarma que impida el inicio o arranque.
6.	Visualmente verifique que el equipo no tenga sueltas  mangueras o conexiones.
7.	Visualmente verifique que el equipo tenga sus guardas de protección.
8.	Verifique el buen funcionamiento de los sistemas de seguridad, actívelos y restablezca el equipo.  
9.	Visualmente verifique que las partes móviles no se encuentre atoradas con material de proceso.
10.	Al encender el equipo o en sus proceso verifique si el equipo genera algún ruido fuera de lo normal.			

		    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>









