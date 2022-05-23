<?php

date_default_timezone_set("America/Tijuana");

define("DB_HOST", "localhost");
define("DB_NAME", "smartstu_martech_dev");
define("DB_USER", "root");
define("DB_PASS", "");

/*
define("DB_HOST", "localhost");
define("DB_NAME", "smartstu_martech");
define("DB_USER", "smartstu_jlgc");
define("DB_PASS", "Administrador1!");
*/

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if(!$connection)
{
    die("Error de conexion con base de datos.");
}

require_once('functions/functions.php');


?>