<?php

//fetch_third_level_category.php

include('../../../admin/config/db.php');

if(isset($_POST["selected"]))
{
 $id = join("','", $_POST["selected"]);
 $query = "
 SELECT * FROM martech_maquinas 
 WHERE departamento_id IN ('".$id."')
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