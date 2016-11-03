<?php
require('../../fpdf181/fpdf.php');

include '../../adodb5/adodb.inc.php';
include '../../classes/function.php';

//$row = $str->FetchRow();

class PDF extends FPDF
{
    // Cargar los datos
    function LoadData($file)
    {
        // Leer las l�neas del fichero
        $lines = file($file);
        $data = array();
        foreach($lines as $line)
            $data[] = explode(';',trim($line));
        return $data;
    }
// Cabecera de página
    function Header()
    {
        $op = new cnFunction();

        $fecha = $op->ToDay();
        $hora = $op->Time();
        // Logo
        //$this->Image('../../images/add.png',10,8,33);
        // Arial bold 15
        $this->SetFont('helvetica','B',8);
        // Movernos a la derecha
        $this->Cell(80,5,'Industrias "El Viejo Roble" S.r.l',0,0);
        // Título
        $this->Cell(30,5,'',0,0,'C');

        $this->Cell(80,5,$fecha,0,0,'R');

        // Salto de línea
        $this->Ln(20);
    }

// Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
    }

    // Tabla simple
    function BasicTable($header, $data)
    {
        $db = NewADOConnection('mysqli');
        //$db->debug = true;
        $db->Connect();

        $id = $_REQUEST['res'];

        $strSql = "SELECT id_inventario, detalle, cantidad FROM produccion WHERE id_produccion = '".$id."' ";

        $str = $db->Execute($strSql);

        // Cabecera
        foreach($header as $col)
            $this->Cell(40,7,$col,1);
        $this->Ln();
        $i = 0;
        // Datos
        /*foreach($data as $row)
        {
            foreach($row as $col)
                $this->Cell(40,6,$col,1);
            $this->Ln();
        }*/

        while( $row = $str->FetchRow()){
            /*$this->Cell(40,6,$row[$i],1);
            $i++;
            echo $i;*/
            for ($i=0;$i<=2;$i++){
                $this->Cell(40, 6, $row[$i], 1);
           }
           $this->Ln();

        }
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);

$pdf->SetFont('helvetica','B',12);

$pdf->Cell(80);
$txt = $pdf->Write(8,'Producción');
$pdf->Cell(80,5,'Orden de '.$txt.'',0,0);
$pdf->Ln();
// Títulos de las columnas
$header = array('Producto', 'Detalle', 'Cantidad');
// Carga de datos
$data = $pdf->LoadData('../../fpdf181/tutorial/paises.txt');

$pdf->BasicTable($header,$data);

$pdf->Output();
?>