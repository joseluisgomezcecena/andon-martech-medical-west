<?php

require_once ("config/configuracion.php");

/********************************************template de pagina********************************************************/

require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
require_once ("includes/header.php");


/*************************errores****************************/
if ($login->isUserLoggedIn() == true && $page=="error_atender")
{
    include("views/error/atender.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="error_atender_resolver")
{
    include("views/error/atender_resolver.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="atender_resolver_list")
{
    include("views/error/atender_resolver_list.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="atender_list")
{
    include("views/error/atender_list.php");
}

/***************************Maquinas*****************************/

elseif ($login->isUserLoggedIn() == true && $page=="alta_maquina")
{
    include("views/maquinas/alta_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="editar_maquina")
{
    include("views/maquinas/editar_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="detalles_maquina")
{
    include("views/maquinas/detalles_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="eliminar_maquina")
{
    include("views/maquinas/eliminar_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="asignar_maquina")
{
    include("views/maquinas/asignar_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_maquina")
{
    include("views/maquinas/lista_maquina.php");
}

/***************************reportes*****************************/


elseif ($login->isUserLoggedIn() == true && $page=="reporte_fallas")
{
    include("views/reportes/reporte_fallas.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_fallas_resolver")
{
    include("views/reportes/reporte_fallas_resolver.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="horaxhoracomplete1")
{
    include("views/reportes/horaxhoracomplete1.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="horaxhoracomplete1_backup")
{
    include("views/reportes/horaxhoracomplete1_backup.php");
}


elseif ($login->isUserLoggedIn() == true && $page=="horaxhoracomplete2")
{
    include("views/reportes/horaxhoracomplete2.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="horaxhoracomplete3")
{
    include("views/reportes/horaxhoracomplete3.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_equipos")
{
    include("views/reportes/reporte_equipos.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_general")
{
    include("views/reportes/reporte_general3.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_general_plantas")
{
    include("views/reportes/reporte_general_plantas.php");
}


elseif ($login->isUserLoggedIn() == true && $page=="reporte_detalles")
{
    include("views/reportes/reporte_detalles.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_fallas_atendidas")
{
    include("views/reportes/reporte_fallas_atendidas.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="reporte_departamentos")
{
    include("views/reportes/reporte_departamentos.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="horaxhora")
{
    include("views/reportes/horaxhora.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="mttf_mttr")
{
    include("views/reportes/mttf_mttr.php");
}

/****************************lineas******************************/

elseif ($login->isUserLoggedIn() == true && $page=="alta_linea")
{
    include("views/lineas/alta_linea.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_linea")
{
    include("views/lineas/lista_linea.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="editar_linea")
{
    include("views/lineas/editar_linea.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="detalles_linea")
{
    include("views/lineas/detalles_linea.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="eliminar_linea")
{
    include("views/lineas/eliminar_linea.php");
}


/***************************usuarios****************************/

elseif ($login->isUserLoggedIn() == true && $page=="usuario_nuevo")
{
    include("views/usuarios/usuario_nuevo.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_usuarios")
{
    include("views/usuarios/lista_usuarios.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="usuario_editar")
{
    include("views/usuarios/usuario_editar.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="usuario_borrar")
{
    include("views/usuarios/usuario_borrar.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_gerentes")
{
    include("views/usuarios/lista_gerentes.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_trabajadores")
{
    include("views/usuarios/lista_trabajadores.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="lista_soporte")
{
    include("views/usuarios/lista_soporte.php");
}

/***************************Hora por hora************************/

elseif ($login->isUserLoggedIn() == true && $page=="importar_archivo")
{
    include("views/horas/importar_archivo.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="menu_principal")
{
    include("views/horas/menu_principal.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="equipos_table")
{
    include("views/horas/equipos_table.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="meta_maquina")
{
    include("views/horas/meta_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="hora_hora_reportes_maquina")
{
    include("views/horas/hora_hora_reportes_maquina.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="semanas")
{
    include("views/horas/semanas.php");
}

/***************************usuarios****************************/

/***********************estado de reporte***********************/

elseif ($login->isUserLoggedIn() == true && $page=="estado_reporte")
{
    include("views/estado/estado_reporte.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="estado_reporte_datos")
{
    include("views/estado/estado_reporte_datos.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="hrxhr1")
{
    include("views/estado/hrxhr1.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="eficienciap1")
{
    include("views/estado/eficienciap1.php");
}


elseif ($login->isUserLoggedIn() == true && $page=="hrxhr2")
{
    include("views/estado/hrxhr2.php");
}

elseif ($login->isUserLoggedIn() == true && $page=="hrxhr3")
{
    include("views/estado/hrxhr3.php");
}


/***************************perfil****************************/
elseif ($login->isUserLoggedIn() == true && $page=="editar_perfil")
{
    include("views/perfil/editar_perfil.php");
}
/***************************perfil****************************/




elseif ($login->isUserLoggedIn() == true)
{
    include("views/logged_in.php");
}



else
{
    include("views/not_logged_in.php");
}

?>


<?php
require_once ("includes/footer.php");
?>


<script>
    
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    })

    $( document ).ready(function() {
        $('#myModal').modal('show');
    });



    $(document).ready(function(){
        var url = "index.php?page=modalbox";
        jQuery('.modallink').click(function(e) {
            $('.modal-container').load(url,function(result){
                $('#myModal1').modal({show:true});
            });
        });
    });



$(function() {

// We can attach the `fileselect` event to all file inputs on the page
$(document).on('change', ':file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

// We can watch for our custom `fileselect` event like this
$(document).ready( function() {
    $(':file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
});

});

</script>
