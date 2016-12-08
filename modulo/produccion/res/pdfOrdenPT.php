<?PHP

session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');

$db->Connect();

$op = new cnFunction();

$fecha  = $op->ToDay();
$hora = $op->Time();
$mes  = $op->ToMes();
$anio = $op->ToAno();

$id = $_REQUEST['res'];

$strSql = "SELECT id_inventario, detalle, dateInc, dateFin, cantidad, id_produccion FROM produccion WHERE id_produccion = '".$id."' ";
$str = $db->Execute($strSql);

$sql = 'SELECT * ';
$sql.= 'FROM empleado ';
$sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

$reg = $db->Execute($sql);

$file = $reg->FetchRow();

$strSqlOR = "SELECT id_produccion FROM produccion WHERE id_produccion = '".$id."' ";
$strOR = $db->Execute($strSqlOR);

$orp = $strOR->FetchRow();

$inc = strtoupper($file['nombre']);
$incp = strtoupper($file['apP']);
?>

<page format="140x216" orientation="P" backtop="5mm" backbottom="5mm" backleft="5mm" backright="5mm">
  <page_header>
  <table class="page_header">
    <tr>
      <td style="width: 30%">Industrias “El Viejo Roble” s.r.l.</td>
      <td style="width: 40%; text-align: center;"><strong>OR-P-<?=$op->ceros($orp[0],2);?></strong></td>
      <td style="width: 30%; text-align:right;"><strong><?php echo date('d/m/Y'); ?></strong></td>
    </tr>
  </table>
  </page_header>

  <page_footer>
  <table class="page_footer" style="width: 100%;">
    <tr>
      <td style="width: 50%">Industrias “El Viejo Roble” s.r.l.</td>
      <td style=" text-align:right; width: 50%">Pag. [[page_cu]]/[[page_nb]]</td>
    </tr>
  </table>
  <link rel="stylesheet" type="text/css" href="../../css/pdf.css"/>
  </page_footer>

  <h4 align="center" style="font-size:26px;">ORDEN DE PRODUCCIÓN OR-P-<?=$op->ceros($orp[0],2);?> TERMINADA</h4>
  <table id="datos" style="border-collapse: collapse;">
    <tbody>
      <tr>
        <td style="text-align:left;"><strong>Orden Terminada por:</strong></td>
        <td style="width:240px; text-align:left; text-transform:capitalize"><?=ucfirst($file['nombre']).'&nbsp;'.ucfirst($file['apP']);?></td>
      </tr>

    </tbody>
  </table>
  <br>
  <h5>DETALLE DE LA ORDEN</h5>
  <table class="report" style=" border-collapse: collapse" align="center">
    <thead>
      <tr>
        <th>PRODUCTO</th>
        <th>DETALLE</th>
        <th>FECHA INICIO</th>
        <th>FECHA FINAL</th>
        <th>CANTIDAD</th>
      </tr>
    </thead>
    <tbody>
      <?PHP
        $cont = 0;

        while( $row = $str->FetchRow()){
          $cont = $cont + 1;
      ?>
        <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
          <td align="center" style="text-transform: uppercase;"><?=$row[0];?></td>
          <td align="center" style="text-transform: uppercase;"><?=$row[1];?></td>
          <td align="center"><?=$row[2];?></td>
          <td align="center"><?=$row[3];?></td>
          <td align="center"><?=$row[4];?></td>
        </tr>
      <?PHP
        }
        $margen = 360 - ($cont *22);
      ?>

    </tbody>

  </table>

  <table id="firma" align="center" style="font-weight: bold; margin-top: <?=$margen?>px;">
    <tbody>
      <tr>
        <td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=ucfirst($file['nombre']).'&nbsp;'.ucfirst($file['apP']);?></td>
      </tr>
      <tr>
        <td align="center">PRODUCCIÓN</td>
      </tr>
    </tbody>
  </table>

</page>
