<?php require '../app/init.php'?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte de errores</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="img/favicon.png" rel="icon" type="image/png" />
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/multiselect.css">

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/multiselect.js"></script>

    <style>

          body{
            background-color: #fafafa;
          }

          .espacios
          {
              padding:20px;
          }
      
          .arriba
          {
              margin-top:50px;
          }


          .overlay
          {
              background: #ffffff;
              color: #666666;
              position: fixed;
              height: 100%;
              width: 100%;
              z-index: 5000;
              top: 0;
              left: 0;
              float: left;
              text-align: center;
              padding-top: 25%;
          }
          
          .btn-flat
          {
              border-radius:0;
              text-shadow: 2px 2px 2px #333333;
              box-shadow: 0 1px 4px 0 rgba(0,0,0,0.2);
          }

        
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
    </style>
  
</head>
<body>

