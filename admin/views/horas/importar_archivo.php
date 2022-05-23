<?php require_once ("includes/topmenu.php")?>
<?php require_once ("includes/sidebar.php")?>

<?php 
require_once 'Excel/reader.php';


// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('CP1251');

$data->read('Z:/Book1.xls');

?>

<section class="content-header">
<div class="bg-success">
    <h2 class="text-success">Versión en desarrollo</h2>
</div>
    <h1>
        Archivos
        <small>Importar archivo</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Archivos</a></li>
        <li class="active">Importar archivo</li>
        
       



    </ol>
</section>


<section id="excel"  class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Archivo</h3>
                </div>

                <div class="box-body">

                    <table style="width: 100%;" id="example1" class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th style="font-size:10px;">Col 1 </th>
                                <th style="font-size:10px;">Col 2 </th>
                            </tr>
                            </thead>

                            <tbody>
                                <?php 
                                for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) 
                                {
                                    for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
                                    {
                                        if($j == 1)
                                        {
                                            $tr = "<tr>";
                                        }
                                        else
                                        {
                                            $tr ="";
                                        }
                                        if($j == 3)
                                        {
                                            $trc = "</tr>";
                                        }
                                        else
                                        {
                                            $trc ="";
                                        }
                                        echo "$tr<td>".$data->sheets[0]['cells'][$i][$j]."</td>$trc";
                                    }
                            
                                }
                                ?>            
                            </tbody>


                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>




