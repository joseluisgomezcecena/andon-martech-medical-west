


<script>
    
   /*********************************MODALES */   
    
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

/********************************AJAX MULTISELECT */

$(document).ready(function(){

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

 $('#third_level').multiselect({
  nonSelectedText: 'Seleccione Maquina',
  buttonWidth:'100%'
 });

});
</script>


<script>

/**********************TIEMPO REAL - AJAX LONG POLLING */

(function worker() {
  $.ajax({
    url: 'andon.php',
    success: function(data) {

      $("#misalertas2").load(location.href+" #misalertas2>*","");

    },
    complete: function() {
      // Schedule the next request when the current one's complete
      setTimeout(worker, 60000);
      //alert("funciona");
    }
  });
})();


function on() {
    document.getElementById("overlay").style.display = "block";
}

function off() {
    document.getElementById("overlay").style.display = "none";
}
</script>

</body>
</html>