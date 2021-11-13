<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"]))
{
    echo 'debe ingresar al sistema para visualizar el reporte';
}
else
{


    if ($_SESSION['almacen']==1)
    {
        //incluimos la clase pdf
    require  'PDF_MC_Table.php';

    //instacioamos la clase pdf
    $pdf = new PDF_MC_Table();

    //agregamos la primera pagina al documento pdf
        $pdf->AddPage();

        //establecemos el inicio del mapa en 24 pixeles
        $y_axis_initial = 25;

        //seteamos el tipode letra que va a tener el teto que va debajo
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->Cell(100,6,'LISTA DE ARTICULOS',1,0,'C');
        $pdf->Ln(10);

        //creamos las celdas para los titulos de cada columna
        $pdf->SetFillColor(232,232,232);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(58,6,'Nombre',1,0,'C',1);
        $pdf->Cell(50,6,utf8_decode('Categoría'),1,0,'C',1);
        $pdf->Cell(30,6,utf8_decode('Código'),1,0,'C',1);
        $pdf->Cell(12,6,'Stock',1,0,'c',1);
        $pdf->Cell(35,6,utf8_decode('Descripción'),1,0,'C',1);
        $pdf->Ln(10);

        //ingresamos los registro segun la consulta de la base de datos

        require_once '../modelos/Articulo.php';
        $articulo = new Articulo();
        $rspt = $articulo->listar();

        //implementamos las celdas de nuestra tabla
        $pdf->SetWidths(array(58,50,35,12,35));

        while($reg=$rspt->fetch_object()){
            $nombre = $reg->nombre;
            $categoria = $reg->categoria;
            $codigo = $reg->codigo;
            $stock = $reg->stock;
            $descripcion = $reg->descripcion;

            $pdf->SetFont('Arial','B',10);
            $pdf->Row(array(utf8_decode($nombre),utf8_decode($categoria),$codigo,$stock,utf8_decode($descripcion)));

        }
        //mostramos el documento pdf
        $pdf->Output();


    }
    else
    {
        echo 'notiene permiso para ver el reporte';
    }





}
ob_end_flush();
?>
