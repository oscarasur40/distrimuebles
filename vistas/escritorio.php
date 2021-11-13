
<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require 'header.php';

    if ($_SESSION['escritorio']==1)
    {
        require_once "../modelos/Consultas.php";
        $consulta = new Consultas();
        $respuestacompras = $consulta->totalcomprashoy();
        $regc = $respuestacompras->fetch_object();
        $totalcomprashoy = $regc->total_compra;

        $respuestaventas = $consulta->totalventashoy();
        $regv = $respuestaventas->fetch_object();
        $totalventashoy = $regv->total_venta;

        //Datos para mostrar los graficos de la barra de las compras
        $compras10 = $consulta->comprasultimos10dias();
        $fechasc='';
        $totalesc='';
        while ($regfechasc=$compras10->fetch_object())
        {
            $fechasc = $fechasc.'"'.$regfechasc->fecha . '",';
            $totalesc=$totalesc.$regfechasc->total . ',';
        }

        //quitamos la ultima coma
        $fechasc=substr($fechasc, 0,-1);
        $totalesc=substr($totalesc, 0,-1);
        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title">Escritorio</h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h4 style="font-size: 17px">
                                                <strong>$/ <?php echo $totalcomprashoy; ?></strong>
                                            </h4>
                                            <p>Ventas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag">

                                            </i>
                                        </div>
                                        <a href="venta.php" class="small-box-footer">Ventas <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h4 style="font-size: 17px">
                                                <strong>$/ <?php echo $totalventashoy ; ?></strong>

                                            </h4>
                                            <p>Ingresos</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag">

                                            </i>
                                        </div>
                                        <a href="ingreso.php" class="small-box-footer">Ingresos <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-body" style="height: 400px;" >
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        Compras de los ultimos 10 dias
                                    </div>
                                    <div class="box-body">


                                        <div id="tester"></div>



                                    </div>
                                </div>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <?php
    }
    else
    {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>


    <!-- <script type="text/javascript" src="scripts/categoria.js"></script> -->
    <script src="../public/js/plotly-latest.min.js"></script>





    <script>
        TESTER = document.getElementById('tester');

        var data = [
            {
                x: [<?php echo $fechasc; ?>],
                y: [<?php echo $totalesc ?>],
                type: 'bar'
            }
        ];

        Plotly.newPlot('tester', data);

    </script>




    <?php
}
ob_end_flush();
?>


