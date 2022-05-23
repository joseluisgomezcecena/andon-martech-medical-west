<?php

//fetch_second_level_category.php
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

if(isset($_POST["selected"]))
{
 $id = join("','", $_POST["selected"]);
 $query = "
 SELECT * FROM martech_departamentos 
 WHERE activo = 1 AND planta_id IN ('".$id."')
 ";
 //$statement = $connect->prepare($query);
 //$statement->execute();
 //$result = $statement->fetchAll();
 $result = mysqli_query($connection, $query);
 
 $output = '';
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
 }
 echo $output;
    
}
?>