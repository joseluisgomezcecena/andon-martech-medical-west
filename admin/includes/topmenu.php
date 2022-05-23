<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">

    <header class="main-header"><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>MM</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Martech</b>Medical</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                   
                    <li class="dropdown tasks-menu">
                        <a id="misalertas" href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <!--sacando alertas ->
                            <?php 
                                
                                if($_SESSION['admin_role'] == 'no')
                                {
                                   
                                    if($puesto == 'maquinas')
                                    { 
                                        $error = "AND tipo_error = 'setup'";
                                    }
                                    elseif($puesto == 'mantenimiento')
                                    {
                                        $error = "AND (tipo_error = 'caida' OR tipo_error = 'agua' OR tipo_error = 'energia')";
                                    }
                                    elseif($puesto == 'materiales')
                                    {
                                        $error = "AND (tipo_error = 'falta_material' OR tipo_error = 'reemplazo')";
                                    }
                                    elseif($puesto == 'ingenieria')
                                    { 
                                        $error = "AND tipo_error = 'sop'";
                                    }
                                    elseif($puesto == 'ingenieria calidad')
                                    { 
                                        $error = "AND tipo_error = 'qap'";
                                    }
                                   
                                    $alert_query = "SELECT id, inicio, maquina_nombre FROM martech_fallas WHERE atendido_flag = 'no' $error";    
                                
                                }
                                else
                                {
                                    $alert_query = "SELECT id, inicio, maquina_nombre FROM martech_fallas WHERE atendido_flag = 'no'";
                                }
                                
                                $result_alert_query = mysqli_query($connection, $alert_query);
                                
                                $number_alerts = mysqli_num_rows($result_alert_query);
                                
                                if($number_alerts == 0)
                                {
                                    $label = "label-success";
                                }
                                else
                                {
                                    $label = "label-danger";
                                }
                            ?>
                            <!-sacando alertas -->
                        
                            <span class="label <?php echo $label ?>"><?php echo $number_alerts; ?></span>
                        </a>
                        <ul id="misalertas-detalles" class="dropdown-menu">

                            

                            <li class="header"><?php # echo $numero_encontrado ?> Alertas</li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">

                                    <?php 
                                    while($row_alert = mysqli_fetch_array($result_alert_query)):
                                
                                    ?>
                                    <li><!-- Task item -->
                                     <a style="font-size:10px; color:#3c8dbc" href="index.php?page=error_atender&id=<?php echo $row_alert['id'] ?>">
                                            <h3>
                                                <small class="pull-right"><?php echo $row_alert['inicio']; ?></small>
                                            </h3>
                                            <?php echo $row_alert['maquina_nombre']; ?>
                                        </a>
                                    </li>
                                    <?php endwhile; ?>

                                    
                                    <!-- end task item -->
                               </ul>
                            </li>
                            <li class="footer">
                                <a href="index.php?page=atender_list">Ver Todo</a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <?php
                    
                    if($rol == "si")
                    {
                        $role = "Administrador";
                    }
                    else
                    {
                        $role = "Trabajador";
                    }
                    ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="img/logo.png" class="user-image" alt="Usuario" width="160" height="160">
                            <span class="hidden-xs"> &nbsp;&nbsp; <?php echo $usuario ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                               <img src="img/logo.png" class="img-circle" alt="Usuario">

                                <p>
                                    <?php echo $usuario ?> - <?php echo $role ?>
                                    
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">

                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="index.php">Inicio</a>
                                    </div>
                                    <div class="col-xs-4 text-center">

                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="index.php?page=editar_perfil" class="btn btn-default btn-flat">Mi perfil</a>
                                </div>
                                <div class="pull-right">
                                    <a href="index.php?logout" class="btn btn-default btn-flat">Cerrar sesión</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->

                </ul>
            </div>
        </nav>
    </header>