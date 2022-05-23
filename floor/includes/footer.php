


<script>
    
    /***********************************MODALES DE CAPTURA DE ERROR */

    //modal inicial bloquea pantalla
    $('#avisoModal').modal({
        backdrop: 'static',
        keyboard: false
    })
    
    //modal con la forma para el tipo de error y datos
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    })
    
    
    $( document ).ready(function() {
        $('#functionModal').modal('show');
    });
    
</script>



<script>

/*************************************AJAX MULTISELECT */

$(document).ready(function(){

 //primer nivel
 $('#first_level').multiselect({
  nonSelectedText:'Seleccione Planta',
  buttonWidth:'100%',
  onChange:function(option, checked){
   $('#second_level').html('');
   $('#second_level').multiselect('rebuild');
   $('#third_level').html('');
   $('#third_level').multiselect('rebuild');
   var selected = this.$select.val();
   if(selected.length > 0)
   {
    $.ajax({
     url:"functions/segundo.php",
     method:"POST",
     data:{selected:selected},
     success:function(data)
     {
      $('#second_level').html(data);
      $('#second_level').multiselect('rebuild');
     }
    })
   }
  }
 });

 //segundo nivel
 $('#second_level').multiselect({
  nonSelectedText: 'Seleccione Departamento',
  buttonWidth:'100%',
  onChange:function(option, checked)
  {
   $('#third_level').html('');
   $('#third_level').multiselect('rebuild');
   var selected = this.$select.val();
   if(selected.length > 0)
   {
    $.ajax({
     url:"functions/tercero.php",
     method:"POST",
     data:{selected:selected},
     success:function(data)
     {
      $('#third_level').html(data);
      $('#third_level').multiselect('rebuild');
     }
    });
   }
  }
 });

 //tercer nivel
 $('#third_level').multiselect({
  nonSelectedText: 'Seleccione Maquina',
  buttonWidth:'100%'
 });

});
</script>



<script>

/*************************************CAPTURA MANUAL DE LINEA */

$('#linea_manual').hide();
$('#second_level').change(function(){
        
        

        if($('#second_level option:selected').text()=="Otro")
        {
            //alert("Otro");
            
            $('#linea_manual').show(200);
            //$('#maquina').hide(200);
            
        }
        else
        {
            $('#linea_manual').hide(200);
            //$('#maquina').show(200);
        }
});
</script>



<script>

/***********************************BLOQUEO DE PANTALLA E ICONO DE LOADING */

function myFunction1() {
    document.getElementById("demo").innerHTML = "<img class='img-responsive center-block' src='img/ajax_loading.gif' width='80'><p class='text-center'><b>Procesando, espera un momento...</b></p>";
    document.getElementById("disp").style.display = "none";
}
</script>

</body>
</html>