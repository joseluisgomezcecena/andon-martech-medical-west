<?php

define("DB_HOST", "localhost");
define("DB_NAME", "smartstu_martech_dev");
//define("DB_NAME", "andon_martech_backup");
define("DB_USER", "root");
define("DB_PASS", "");


define("DB_HOST3", "localhost");
define("DB_NAME3", "hourxhour");
define("DB_USER3", "root");
define("DB_PASS3", "");



$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(!$connection)

{
    
die("Error de conexion con base de datos Andon.");

}

/*
$connection3 = mysqli_connect(DB_HOST3, DB_USER3, DB_PASS3, DB_NAME3);

if(!$connection3)

{
    
die("Error de conexion con base de datos Hr x Hr.");

}
*/



require_once ("functions.php");

require_once ("functions_users_account.php");



