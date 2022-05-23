<?php

//fetch_second_level_category.php

include('../../../admin/config/db.php');

if(isset($_POST["selected"]))
{



 $id = join("','", $_POST["selected"]);
 

 $query = "
 SELECT * FROM martech_departamentos 
 WHERE planta_id IN ('".$id."') AND activo = 1";
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