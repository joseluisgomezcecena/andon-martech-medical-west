<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-lime">
                <div class="inner">
                    <?php

                    if($_SESSION['admin_role'] == 'no')
                    {
                        $link = "#";
                    }
                    else
                    {
                        $link = "index.php?page=lista_usuarios";
                    }

                    $query = mysqli_query($connection,"SELECT COUNT(*) FROM  users");
                    $row = mysqli_fetch_array($query);
                    ?>
                    <h3><?php echo $row[0] ?></h3>

                    <p>Usuarios</p>
                    
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="<?php echo $link ?>" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <?php

                    if($_SESSION['admin_role'] == 'no')
                    {
                        $link2 = "#";
                    }
                    else
                    {
                        $link2 = "index.php?page=lista_maquina";
                    }


                    $query = mysqli_query($connection,"SELECT COUNT(*) FROM  martech_maquinas");
                    $row = mysqli_fetch_array($query);
                    ?>
                    <h3><?php echo $row[0] ?></h3>

                    <p>Equipos</p>
                    
                </div>
                <div class="icon">
                    <i class="fa fa-cogs"></i>
                </div>
                <a href="<?php echo $link2 ?>" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div id="panel_resolver" class="small-box bg-yellow">
                <div class="inner">
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

                        $query = mysqli_query($connection,"SELECT COUNT(*) FROM  martech_fallas WHERE atendido_flag = 'no' $error");
                    }
                    else
                    {
                        $query = mysqli_query($connection,"SELECT COUNT(*) FROM  martech_fallas WHERE atendido_flag = 'no'");
                    }
   

                    
                    
                    $row = mysqli_fetch_array($query);
                    
                    ?>
                    <h3><?php echo $row[0] ?></h3>

                    <p>Por Atender</p>
                   

                </div>
                <div class="icon">
                    <i class="ion ion-clipboard"></i>
                </div>
                <a href="index.php?page=atender_list" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div id="panel_error" class="inner">
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

                        $query = mysqli_query($connection,"SELECT COUNT(*) FROM  martech_fallas WHERE offline = 'si' $error ");
                    }
                    else
                    {
                        $query = mysqli_query($connection,"SELECT COUNT(*) FROM  martech_fallas WHERE offline = 'si'");
                    }
                    
                    $row = mysqli_fetch_array($query);
                    
                    ?>
                    <h3><?php echo $row[0] ?></h3>

                    <p>Por Resolver</p>
                

                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="index.php?page=atender_resolver_list" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
</section>