<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<style>
    .sk-chase {
    width: 40px;
    height: 40px;
    position: relative;
    animation: sk-chase 2.5s infinite linear both;
    }

    .sk-chase-dot {
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0; 
    animation: sk-chase-dot 2.0s infinite ease-in-out both; 
    }

    .sk-chase-dot:before {
    content: '';
    display: block;
    width: 25%;
    height: 25%;
    background-color: #000000;
    border-radius: 100%;
    animation: sk-chase-dot-before 2.0s infinite ease-in-out both; 
    }

    .sk-chase-dot:nth-child(1) { animation-delay: -1.1s; }
    .sk-chase-dot:nth-child(2) { animation-delay: -1.0s; }
    .sk-chase-dot:nth-child(3) { animation-delay: -0.9s; }
    .sk-chase-dot:nth-child(4) { animation-delay: -0.8s; }
    .sk-chase-dot:nth-child(5) { animation-delay: -0.7s; }
    .sk-chase-dot:nth-child(6) { animation-delay: -0.6s; }
    .sk-chase-dot:nth-child(1):before { animation-delay: -1.1s; }
    .sk-chase-dot:nth-child(2):before { animation-delay: -1.0s; }
    .sk-chase-dot:nth-child(3):before { animation-delay: -0.9s; }
    .sk-chase-dot:nth-child(4):before { animation-delay: -0.8s; }
    .sk-chase-dot:nth-child(5):before { animation-delay: -0.7s; }
    .sk-chase-dot:nth-child(6):before { animation-delay: -0.6s; }

    @keyframes sk-chase {
    100% { transform: rotate(360deg); } 
    }

    @keyframes sk-chase-dot {
    80%, 100% { transform: rotate(360deg); } 
    }

    @keyframes sk-chase-dot-before {
    50% {
        transform: scale(0.4); 
    } 100%, 0% {
        transform: scale(1.0); 
    } 
    }
</style>

<section class="content-header">
    <h1>
        Reportes
        <small>MTTF & MTTR</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">MTTF & MTTR </li>
    </ol>
</section>

<section class="content">
<div class="row">
    <!-- left column -->
    <div class="col-lg-12">
        <!-- general form elements  form form-inline form-multiline-->
        <div class="box box-danger">
            <form class="my-3">
                <div class="box-header with-border">
                    <h3 class="box-title">Filtrado por fechas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- <div class="form-group col-lg-2">
                        <label for="planta">Seleccione planta:</label>
                        <select class="form-control" id="planta">
                            <option value="0" selected disabled></option>
                            <option value="1">MM1</option>
                            <option value="2">MM2</option>
                            <option value="3">MM3</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="maquina">Maquina:</label>
                        <input type="text" class="form-control m-2" placeholder="Maquina" id="maquina" list="machines_list" autocomplete="off">
                        <datalist id="machines_list"></datalist>
                    </div> -->
                    <div class="form-check-inline col-lg-2" style="text-align: center;">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" value="1" id="full">  Todo el historial
                        </label>
                        <div class="form-group">
                            <label for="top">Top: </label>
                            <input type="number" name="top" id="top" class="form-control m-2" value="3">
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="start">Fecha de Inicio</label>
                        <input type="date" name="start" id="start" class="form-control m-2">
                    </div>
                    
                    <div class="form-group col-lg-4">
                        <label for="end">Fecha Final</label>
                        <input type="date" name="end" id="end" class="form-control m-2">
                    </div>

                    <div class="form-group">
                        <button id="buscar_btn" type="button" class="btn btn-flat btn-primary"><i class="fa fa-search"></i>&nbsp;Buscar</button>
                    </div>

                    <div class="form-group">
                        <a href="index.php?page=mttf_mttr" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i>&nbsp;Re-establecer</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 id="box-title-report" class="box-title"></h3>
            </div>
            <div class="sk-chase" id="spinner" style="margin: auto; display: none;">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
            </div>
            <div class="box-body">
                <div id="output" class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="small-box bg-red">
                            <div id="mttfText" class="inner">
                                <h4>MTTF Top:</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xs-12">
                        <div class="small-box bg-green">
                            <div id="mttrText" class="inner">
                                <h4>MTTR Top:</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div id="histogram-mttf"></div>
                    </div>
                    <div class="col-lg-6">
                        <div id="histogram-mttr"></div>
                    </div>
                </div>
                <br><br><br>
                <table style="font-size: 12px;" class="table table-hover mt-5" id="data_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Maquina</th>
                            <th>Planta</th>
                            <th>Mean Time Between Failure (Min)</th>
                            <th>Mean Time to Repair (Min)</th>
                            <th>Num of failures</th>
                        </tr>
                    </thead>
                    <tbody> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>