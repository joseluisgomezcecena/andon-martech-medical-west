<?php
ob_start();

date_default_timezone_set("America/Tijuana");

header('X-XSS-Protection:0');

if(isset($_GET['page']))
{
    $page = $_GET['page'];
}
else
{
    $page ="";
}


if($page == "reporte_fallas" || $page == "reporte_fallas_resolver" || $page == "reporte_general" || $page == "reporte_general_plantas" || $page == "reporte_detalles" || $page == "reporte_fallas_atendidas" || $page == "hrxhr" || $page == "reporte_departamentos" || $page == "horaxhoracomplete1" || $page == "horaxhoracomplete2" || $page == "horaxhoracomplete3")
{
    $datatablesop= 2;
}
else
{
    $datatablesop= 1;
}

//version minima de php
if (version_compare(PHP_VERSION, '5.3.7', '<'))
{
    exit(" :( Esta app no puede correr en una version de php menor a 5.3.7 ! contacte a Mejora continua | Software");
}
else if (version_compare(PHP_VERSION, '5.5.0', '<'))
{
    require_once("libraries/password_compatibility_library.php");
}

?>
