<?php 
function abrirPantalla()
{

    if(isset($_POST['apantalla']))
    {

        $linea_id = $_POST['second_level'];
        
        header("Location: index.php?id=$linea_id");
    }

}

function abrirPantallaDepartamento()
{

    if(isset($_POST['apantalladepartamento']))
    {

        $falla = $_POST['falla'];
        
        header("Location: fallas_departamento.php?id=$falla");
    }

}

?>