<!DOCTYPE html>
<html>
<head>
    <?php
    
    if(isset($_SESSION['user_puesto']))
    {
        $puesto = $_SESSION['user_puesto'];
    }
    else
    {
        $puesto = "";
    }
    
    if(isset($_SESSION['admin_role']))
    {
        $rol = $_SESSION['admin_role'];
    }
    else
    {
        $rol = "";
    }
    
    if(isset($_SESSION['user_name']))
    {
        $usuario =  $_SESSION['user_name'];
    }
    else
    {
        $usuario = "";
    }



    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Martech Andon - Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="assets/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="assets/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">

    <link rel="stylesheet" href="assets/mycss/dropzone.css">


    <?php
    if(isset($datatablesop))
    {
        if($datatablesop == 1)
        {
            echo "<link rel=\"stylesheet\" href=\"assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css\">";
        }
        else{
            echo "<link rel=\"stylesheet\" href=\"assets/datatables/buttons.dataTables.min.css\">
        <link rel=\"stylesheet\" href=\"assets/datatables/jquery.dataTables.min.css\">";
        }
    }
    else
    {
        echo "<link rel=\"stylesheet\" href=\"assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css\">";

    }
    

    
    ?>

    <!-- DataTables
    <link rel="stylesheet" href="assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    -->


    <!--
    <link rel="stylesheet" href="assets/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="assets/datatables/jquery.dataTables.min.css">
    -->

    <link href="assets/datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="assets/datepicker/dist/css/timepicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <!--
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    -->
    <!--
    <script src="assets/myscripts/dropzonephotos.js"></script>
    -->

    <!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>-->
    <!--
    <script src="assets/tinymce/customtext.js"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    -->
    <style>
    

    .visibletableta
    {
            display:none;
    }

    /* Portrait */
    @media only screen 
    and (min-device-width: 168px) 
    and (max-device-width: 1024px)
    and (-webkit-min-device-pixel-ratio: 1) 
    {
        .visibletableta
        {
            display:block;
        }

    }


    
    .test + .tooltip > .tooltip-inner {
        background-color: black; 
        color: #FFFFFF; 
        border: 1px solid black; 
        padding: 5px;
        font-size: 12px;
    }
    
    .test + .tooltip.top > .tooltip-arrow {
        border-top: 5px solid black;
    }

    .main-header{
        box-shadow: black 2px 2px 5px;
        
    } 

    .content-wrapper
    {
        background-color: white;
    }

    .box
    {
        box-shadow: #cccccc 5px 5px 5px;
        border: solid #cccccc 1px;
    }

    .btn
    {
        border-radius:0;
    }

    </style>
</head>

