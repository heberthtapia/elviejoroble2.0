<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
    // get the HTML
    //set_time_limit (60);
    ob_start();
    include(dirname(__FILE__).'/res/pdfInvPre.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once(dirname(__FILE__).'/../../html2pdf/vendor/autoload.php');

    use Spipu\Html2Pdf\Html2Pdf;
    use Spipu\Html2Pdf\Exception\Html2PdfException;
    use Spipu\Html2Pdf\Exception\ExceptionFormatter;

    try
    {
        $html2pdf = new HTML2PDF('P', 'letter', 'es', true, 'UTF-8', 3);
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('Inventario_Preventista_'.$fecha.'.pdf');
    }
    catch(HTML2PDF_exception $e) {
        $html2pdf->clean();

        $formatter = new ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }
?>
