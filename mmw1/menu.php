<?php require_once('config/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Selección de pantallas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/mycss/multiselect.css" />

    <style>
    

    body, html {
    height: 100%;
    background-repeat: no-repeat;
    background-image: linear-gradient(rgb(33, 118, 255), rgb(32, 229, 247));
}

.card-container.card {
    max-width: 350px;
    padding: 40px 40px;
}

.btn {
    font-weight: 700;
    height: 36px;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
    cursor: default;
}

/*
 * Card component
 */
.card {
    background-color: #FFFFFF;
    /* just in case there no content*/
    padding: 20px 25px 30px;
    margin: 0 auto 25px;
    margin-top: 50px;
    /* shadows and rounded borders */
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
}

.profile-img-card {
    width: 96px;
    height: 96px;
    margin: 0 auto 10px;
    display: block;
    /*
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    */
}

/*
 * Form styles
 */
.profile-name-card {
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    margin: 10px 0 0;
    min-height: 1em;
}

.reauth-email {
    display: block;
    color: #404040;
    line-height: 2;
    margin-bottom: 10px;
    font-size: 14px;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin #inputEmail,
.form-signin #inputPassword {
    direction: ltr;
    height: 44px;
    font-size: 16px;
}

.form-signin input[type=email],
.form-signin input[type=password],
.form-signin input[type=text],
.form-signin button {
    width: 100%;
    display: block;
    margin-bottom: 10px;
    z-index: 1;
    position: relative;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}

.form-signin .form-control:focus {
    border-color: rgb(104, 145, 162);
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
}

.btn.btn-signin {
    /*background-color: #4d90fe; */
    background-color: rgb(33, 118, 255);
    /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
    padding: 0px;
    font-weight: 700;
    font-size: 14px;
    height: 36px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: none;
    -o-transition: all 0.218s;
    -moz-transition: all 0.218s;
    -webkit-transition: all 0.218s;
    transition: all 0.218s;
}

.btn.btn-signin:hover,
.btn.btn-signin:active,
.btn.btn-signin:focus {
    background-color: rgb(32, 229, 247);
}

.forgot-password {
    color: rgb(104, 145, 162);
}

.forgot-password:hover,
.forgot-password:active,
.forgot-password:focus{
    color: rgb(32, 229, 247);
}
    
    
    </style>
</head>
<body style=''>

    <?php 
    abrirPantalla();
    ?>
    
    <div class="container">
        <div class="card card-container">
            
            
            <img id="profile-img" class="profile-img-card" src="assets/img/logo.png" />
            

            <p id="profile-name" class="profile-name-card"></p>
            
            <form class="form-signin" method="post">
               


            <div class="form-group">
                         <label>Planta</label><br />
                         <select id="first_level" name="first_level" multiple class="form-control">
                         <?php 
                            $query_planta = "SELECT * FROM martech_plantas";
                            $result_planta = mysqli_query($connection, $query_planta);
                            while($row = mysqli_fetch_array($result_planta)):
                            ?>
                            
                                <option value="<?php  echo $row['id'] ?>"><?php echo $row['nombre'] ?></option>

                            <?php endwhile; ?>
                         </select>
                    </div>
                    
                    <div class="form-group">
                         <label>Departamento</label><br />
                         <select id="second_level" name="second_level"  class="form-control">
                
                         </select>
                    </div>
                    
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="apantalla">Iniciar Pantalla</button>





            </form><!-- /form -->
            
            <!--
            <a href="#" class="forgot-password">
                Instrucciones <i class="glyphicon glyphicon-blackboard"></i>
            </a>
            -->

        </div><!-- /card-container -->
    </div><!-- /container -->
   
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>

    <script src="assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>


    <script>
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
  nonSelectedText: 'Seleccione Linea',
  buttonWidth:'100%'
 });

/*
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

*/

/*
 $('#third_level').multiselect({
  nonSelectedText: 'Seleccione Maquina',
  buttonWidth:'100%'
 });
*/
});
</script>
    

</body>
</html>