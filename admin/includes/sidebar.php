<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
               <img src="img/logo.png" class="img-circle" alt="Martech">
                <br/><br/><br/>
            </div>
            <div class="pull-left info">
                <p><?php echo $usuario = $_SESSION['user_name'] ?></p>

                <a href="#">
                    <i class="fa fa-user"></i> 
                    <?php 
                    echo $role;
                    ?>
                </a>
                
                <a href="#"><i class="fa fa-circle text-success"></i> Conectado</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Navegación</li>

            <li class="">
                <a href="index.php">
                    <i class="fa fa-dashboard"></i> <span>Inicio</span>
                    
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>

            <?php 
            if($puesto == "lider" || $puesto == "supervisor")
            {
                echo "";
            }
            else
            {
                $fallas = <<<DELIMITER

                <li class=" treeview">
                <a href="#">
                    <i class="fa fa-exclamation-triangle"></i> <span>Fallas</span>
                    
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=atender_list"><i class="fa fa-circle-o"></i> Por atender</a></li>
                    <li><a href="index.php?page=atender_resolver_list"><i class="fa fa-circle-o"></i> Por resolver</a></li>
                </ul>
            </li>

DELIMITER;
echo $fallas;
            }
            ?>

            


            <?php 
            if($rol == "si" )
            {
                $lineas = <<<DELIMITER

            <li class=" treeview">
                <a href="#">
                    <i class="fa fa-edit"></i> <span>Lineas</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=alta_linea"><i class="fa fa-circle-o"></i> Registrar lineas</a></li>
                    <li><a href="index.php?page=lista_linea"><i class="fa fa-circle-o"></i> Lista de lineas</a></li>
                </ul>
            </li>
DELIMITER;
echo $lineas;
            }
            else
            {
                $lineas = "";
            }
            ?>




            <?php 
            if($rol == "si" )
            {
                $equipos = <<<DELIMITER

            <li class=" treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Equipos</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=alta_maquina"><i class="fa fa-circle-o"></i> Registrar equipo</a></li>
                    <li><a href="index.php?page=lista_maquina"><i class="fa fa-circle-o"></i> Lista de equipos</a></li>

                </ul>
            </li>
DELIMITER;
echo $equipos;
            }
            else
            {
                $equipos = "";
            }
            ?>






           


            <?php 
            if($puesto == "lider" || $puesto == "supervisor")
            {
                echo "";
            }
            else
            {
            ?>

            <li class=" treeview">
                <a href="#">
                    <i class="fa fa-clipboard"></i> <span>Reportes</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=reporte_general"><i class="fa fa-circle-o"></i> Tiempos muertos (Plantas)</a></li>
                    <li><a href="index.php?page=reporte_general_plantas"><i class="fa fa-circle-o"></i> Tiempos muertos (Celdas)</a></li>
                    <!--
                    <li><a href="index.php?page=reporte_departamentos"><i class="fa fa-circle-o"></i> Tiempos muertos (visual)</a></li>
   
                 <li><a target="_blank" href="http://mxmtsvrandon1/andon/hourxhour/"><i class="fa fa-circle-o"></i> Pantallas HrxHr</a></li>

<li><a href="index.php?page=hrxhr2"><i class="fa fa-circle-o"></i> Hora por Hora P2</a></li>
                    <li><a href="index.php?page=hrxhr3"><i class="fa fa-circle-o"></i> Hora por Hora P3</a></li>

                    <li><a href="index.php?page=horaxhoracomplete1"><i class="fa fa-circle-o"></i>Produccion Hr x Hr </a></li>
                    <li><a href="index.php?page=mttf_mttr"><i class="fa fa-circle-o"></i>MTTF & MTTR</a></li>
-->
             </ul>
            </li>

            <?php
            }
            ?>






              <?php 
            if($puesto == "lider" || $puesto == "supervisor")
            {
            ?>

            <li class=" treeview">
                <a href="#">
                    <i class="fa fa-clipboard"></i> <span>Estado de reporte</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=estado_reporte"><i class="fa fa-circle-o"></i> Estado</a></li>
                </ul>
            </li>


            <?php   
            }
            else
            {
            
                echo "";
            
            }
            ?>






           

            <!-- DESARROLLO
            <li class=" treeview">
                <a href="#">
                    <i class="fa fa-clock-o"></i> <span>Hora por hora</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li><a href="index.php?page=semanas"><i class="fa fa-circle-o"></i> Semanas de trabajo</a></li>
                    <li><a href="index.php?page=importar_archivo"><i class="fa fa-circle-o"></i> Importar</a></li>
                    <li><a href="index.php?page=menu_principal"><i class="fa fa-circle-o"></i> Metas</a></li>
                    <li><a href="index.php?page=hora_hora_reportes_maquina"><i class="fa fa-circle-o"></i> Reportes</a></li>
                </ul>
            </li>
            -->

           

            <?php
            if($rol == "si")
            {
                $usuario_menu = <<<DELIMITER
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user-plus"></i>
                    <span>Usuarios</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="index.php?page=usuario_nuevo"><i class="fa fa-circle-o"></i> Registrar usuario</a></li>
                    <li><a href="index.php?page=lista_usuarios"><i class="fa fa-circle-o"></i> Edición de usuarios</a></li>
                    <li><a href="index.php?page=lista_gerentes"><i class="fa fa-circle-o"></i> Administrar gerentes</a></li>
                    <li><a href="index.php?page=lista_trabajadores"><i class="fa fa-circle-o"></i> Administrar trabajadores</a></li>
                    <li><a href="index.php?page=lista_soporte"><i class="fa fa-circle-o"></i> Administrar soporte </a></li>
                </ul>
            </li>
DELIMITER;
                echo $usuario_menu;
            }
            ?>


            <li class="  visibletableta">
                <a href="../floor/">
                    <i class="fa fa-tablet"></i> <span>Volver a Produccion</span>
                    
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->



<div  class="content-wrapper">

