</div>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="assets/bower_components/raphael/raphael.min.js"></script>
<script src="assets/bower_components/morris.js/morris.min.js"></script>
<!-- <script src="assets/bower_components/chart.js/Chart.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>-->
<!-- Sparkline -->
<script src="assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/bower_components/moment/min/moment.min.js"></script>
<script src="assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="assets/dist/js/pages/dashboard.js"></script>
<script src="assets/myscripts/myscript.js"></script>


<?php
if($datatablesop == 1)
{
    echo "<script src=\"assets/bower_components/datatables.net/js/jquery.dataTables.min.js\"></script>
<script src=\"assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js\"></script>";
}
else
{
    echo "<script src=\"assets/datatables/jquery.dataTables.min.js\"></script>
<script src=\"assets/datatables/dataTables.buttons.min.js\"></script>
<script src=\"assets/datatables/jszip.min.js\"></script>
<script src=\"assets/datatables/pdfmake.min.js\"></script>
<script src=\"assets/datatables/vfs_fonts.js\"></script>
<script src=\"assets/datatables/buttons.html5.min.js\"></script>";
}
?>

<script src="assets/plugins/input-mask/jquery.inputmask.js"></script>
<script src="assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="assets/plugins/input-mask/jquery.inputmask.phone.extensions.js"></script>



<!-- AdminLTE for demo purposes
<script src="assets/dist/js/demo.js"></script>-->
<!-- DataTables -->
<!--
<script src="assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
-->

<!--
<script src="assets/datatables/jquery.dataTables.min.js"></script>
<script src="assets/datatables/dataTables.buttons.min.js"></script>
<script src="assets/datatables/jszip.min.js"></script>
<script src="assets/datatables/pdfmake.min.js"></script>
<script src="assets/datatables/vfs_fonts.js"></script>
<script src="assets/datatables/buttons.html5.min.js"></script>
-->

<script src="assets/myscripts/dropzonephotos.js"></script>

<script src="assets/datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/datepicker/js/jquery.timepicker.min.js"></script>

<!-- SweetAlert.js 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
<!-- app.js -->
<script src="assets/js/app.js"></script>

<script>

    //input mask para fechas
    
    $('[data-mask]').inputmask()

    $.fn.datepicker.defaults.format = "yyyy-mm-dd";
    $('.datepicker').datepicker({
        startDate: '-3d'
    });

</script>

<script>
    //formato de tiempo
    $('.timeformatExample1').timepicker({ 'timeFormat': 'H:i:s' });
</script>

<script>
    //datatables para presentar datos en toda la applicación


    $(document).ready(function() {
        //tres
        
        $('#example3').DataTable( {

            "scrollX": true,
            "bSort": false,
            "pageLength": 20,
            //"bFilter": false,


//idioma

                language: {
                    processing:     "Procesando...",
                    search:         "Buscar&nbsp;:",
                    lengthMenu:    "Mostrar _MENU_ elementos",
                    info:           "Mostrando _START_ a _END_ de _TOTAL_ elementos",
                    infoEmpty:      "Mostrando 0 a 0 de 0 elementos",
                    infoFiltered:   "(Filtrando; de _MAX_ elementos en total)",
                    infoPostFix:    "",
                    loadingRecords: "Cargando datos...",
                    zeroRecords:    "No se encontro ningun registro",
                    emptyTable:     "No hay datos que mostrar",
                    paginate: {
                        first:      "Primero",
                        previous:   "Regresar",
                        next:       "Avanzar",
                        last:       "Ultimo"
                    },
                    aria: {
                        //sortAscending:  ": activer pour trier la colonne par ordre croissant",
                        //sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    }
                },

            //idioma



            dom: 'Bfrtip',
            buttons: [
                /*
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
                */
                {
                extend: 'excelHtml5',
                title: 'Reporte Martech Medical West'
                },
                {
                extend: 'csvHtml5',
                title: 'Reporte Martech Medical West '
                },
                {
                extend: 'pdfHtml5',
                title: 'Reporte Martech Medical West '
                },
                {
                extend: 'copyHtml5',
                title: 'Reporte Martech Medical West '
                }


            ]


            


        } );

        //tres
    } );




    $(function () {
        $('#example1').DataTable({
            "scrollX": true,
            "bSort": false,

            language: {
                    processing:     "Procesando...",
                    search:         "Buscar&nbsp;:",
                    lengthMenu:    "Mostrar _MENU_ elementos",
                    info:           "Mostrando _START_ a _END_ de _TOTAL_ elementos",
                    infoEmpty:      "Mostrando 0 a 0 de 0 elementos",
                    infoFiltered:   "(Filtrando; de _MAX_ elementos en total)",
                    infoPostFix:    "",
                    loadingRecords: "Cargando datos...",
                    zeroRecords:    "No se encontro ningun registro",
                    emptyTable:     "No hay datos que mostrar",
                    paginate: {
                        first:      "Primero",
                        previous:   "Regresar",
                        next:       "Avanzar",
                        last:       "Ultimo"
                    },
                    aria: {
                        //sortAscending:  ": activer pour trier la colonne par ordre croissant",
                        //sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    }
                }

            
        })
        
     
        
        $('#example2').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : true,

        })


    });




    //funcion para hacer ajax request y desplegar datos en tiempo real

    (function worker() {
    $.ajax({
        url: 'index.php', 
        success: function(data) {

        $("#mitabla").load(location.href+" #mitabla>*","");//logged_in 

         $("#mitabla2").load(location.href+" #mitabla2>*",""); //logged_in tabla de atendidos pero no resueltos
        
        $("#misalertas").load(location.href+" #misalertas>*","");

        $("#misalertas-detalles").load(location.href+" #misalertas-detalles>*","");

        $("#atender").load(location.href+" #atender>*","");

        $("#panel_error").load(location.href+" #panel_error>*","");//includes panels
        
        $("#panel_resolver").load(location.href+" #panel_resolver>*","");//includes panels

         $("#excel").load(location.href+" #excel>*","");

        },
        complete: function() {
        // Siguiente peticion de ajax cuando la actual este terminada
        setTimeout(worker, 60000);
        
        }
    });
    })();

</script>

<script>

    //atencion de fallas, atender_form.php

    /*
    $('#descripcion_atencion').hide(200);

    $('#error_operador').change(function(){
        if($('#error_operador').val()=="si"){
            $('#descripcion_atencion').show(200);
        }
        else
        {
            $('#descripcion_atencion').hide(200);
        }
    })
    */
</script>

<script>
 
    //niveles de usuario, para el registro usuario_nuevo.php
    
    if($('#nivel').val()=='lider' || $('#nivel').val()=='supervisor' || $('#nivel').val()=='gerente produccion' || $('#nivel').val()=='gerente operaciones' || $('#nivel').val()=='gerente planta')
    {
        $('#super3').hide();
        $('#super5').hide();
        $('#super').hide();    
    }
    else
    {
        $('#super3').show();
        $('#super5').show();
        $('#super').show();

    }
    
    
    $('#nivel').change(function(){
        
        if($('#nivel').val()=='mantenimiento')
        {
            $('#super').show(200);
            $('#super2').required = true;

            $('#super3').show(200);
            $('#super4').required = true;

            $('#super5').show(200);
            $('#super6').required = true;


        }
        else if($('#nivel').val()=='maquinas')
        {
            $('#super').show(200);
            $('#super2').required = true;

            $('#super3').show(200);
            $('#super4').required = true;

            $('#super5').show(200);
            $('#super6').required = true;


        }
        else if($('#nivel').val()=='ingenieria')
        {
            $('#super').show(200);
            $('#super2').required = true;
        
            $('#super3').show(200);
            $('#super4').required = true;

            $('#super5').show(200);
            $('#super6').required = true;

        
        }
        else if($('#nivel').val()=='ingenieria calidad')
        {
            $('#super').show(200);
            $('#super2').required = true;

            $('#super3').show(200);
            $('#super4').required = true;

            $('#super5').show(200);
            $('#super6').required = true;


        }
        else if($('#nivel').val()=='materiales')
        {
            $('#super').show(200);
            $('#super2').required = true;

            $('#super3').show(200);
            $('#super4').required = true;

            $('#super5').show(200);
            $('#super6').required = true;


        }
        else
        {
            $('#super').hide(200);
            $('#super2').required = false;

            $('#super3').hide(200);
            $('#super4').required = false;

            $('#super5').hide(200);
            $('#super6').required = false;


        }

        
    })
    
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

</body>
</html>
