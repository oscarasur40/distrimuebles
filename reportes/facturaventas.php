<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'debe ingresar al sistema para visualizar el reporte';
} else {

    if ($_SESSION['almacen'] == 1) {
        //incluimos el archivo PDF_INVOICE
        require 'PDF_Invoice.php';

        //Establecemos Los datos de la empresa

        $logo = "logo.jpg";
        $ext_logo = "jpg";
        $empresa = "Soluciones Innovadoras Perú S.A.C.";
        $documento = "20477157772";
        $direccion = "Chongoyape, José Gálvez 1368";
        $telefono = "931742904";
        $email = "jcarlos.ad7@gmail.com";


//Obtenemos Los datos de la cabecera de la venta actual
        require_once "../modelos/Venta.php";
        $venta = new Venta();
        $rsptav = $venta->ventacabecera($_GET["id"]);
//Recorremos todos los valores obtenidos

        $regv = $rsptav->fetch_object();



//Establecemos la configuración de la factura $pdf = new PDF Invoice( 'P', 'mm', 'A4 );
        $pdf = new PDF_Invoice('P', 'mm', 'A4');
        $pdf->AddPage();
//Enviamos Los datos de la empresa al método addSociete de la clase Factura
        $pdf->addSociete($empresa,
            $documento . "\n" .
            "Dirección: " . $direccion . "\n" .
            "Teléfono: " . $telefono . "\n" .
            "Email: " . $email, $logo, $ext_logo);

        $pdf->fact_dev("$regv->tipo_comprobante ", "$regv->serie_comprobante-$regv->num_comprobante");
        $pdf->temporaire("");
        $pdf->addDate($regv->fecha);
//Enviamos Los datos del cliente al método addClientAdresse de la clase Factura

        $pdf->addClientAdresse(utf8_decode($regv->cliente), "Domicilio: " .
            utf8_decode($regv->direccion), $regv->tipo_documento . ": " . $regv->
            num_documento, "Email: " . $regv->email, "Telefono: " . $regv->telefono);

//establecemos las columnas que va a tener la seccion donde mostramos los detalles de la venta
        $cols=array("CODIGO"=>23,
            "DESCRICION"=>78,
            "CANTIDAD"=>22,
            "P.U."=>"R",
            "DSCTO"=>20,
            "SUBTOTAL"=>22);
        $pdf->addCols($cols);
        $cols = array("CODIGO"=>"L,",
            "DESCRIPCION"=>"L",
            "CANTIDAD"=>"C",
            "P.U."=>"R",
            "DSCTO"=>"R",
            "SUBTOTAL"=>"C");
        $pdf->addLineFormat($cols);
        $pdf->addLineFormat($cols);

        //actualiza el valor de la cordenada y donde se empezaremos a mostrar los datos

        $y=89;

        //obtenemos todos los detalles de la venta actual
        $rsptad = $venta->ventadetalle($_GET["id"]);
        while ($regd = $rsptad->fetch_object()){
            $line = array("CODIGO"=>"$regd->codigo",
                "DESCRIPCION"=>utf8_decode("$regd->articulo"),
                "CANTIDAD"=>"$regd->cantidad",
                "P.U."=>"$regd->precio_venta",
                "DCTO"=>"$regd->descuento",
                "SUBTOTAL"=>"$regd->subtotal");
            $size = $pdf->addLine($y,$line);
            $y   +=$size + 2;
        }

        $pdf->addCadreTVAs("TOTAL VENTA ---".$regv->total_ventas);

        //mostrar el impuesto
        $pdf->addTVAs($regv->impuesto,$regv->toral_venta,"$/ ");
        $pdf->addCadreEurosFrancs("IGV"."$regv->impuesto %");
        $pdf->Output('Reporte de venta','I');


    } else {
        echo 'notiene permiso para ver el reporte';
    }

}
ob_end_flush();
?>