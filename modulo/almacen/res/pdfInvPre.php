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

$strSql = "SELECT p.dateReg, p.id_inventario, i.detalle, p.cantidad ";
$strSql.= "FROM inventarioPre AS p, inventario AS i, empleado AS e ";
$strSql.= "WHERE p.id_inventario =  i.id_inventario ";
$strSql.= "AND p.id_empleado = e.id_empleado ";
$strSql.= "AND p.id_empleado = $id ORDER BY(p.id_inventario)";

$str = $db->Execute($strSql);

$sql = 'SELECT * ';
$sql.= 'FROM empleado ';
$sql.= 'WHERE id_empleado = '.$id.'';

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
      <td style="width: 40%; text-align: center;"><strong></strong></td>
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

  <h4 align="center" style="font-size:26px;">INVENTARIO PREVENTISTA</h4>
  <table id="datos" style="border-collapse: collapse;">
    <tbody>
      <tr>
        <td style="text-align:left;"><strong>Preventista:</strong></td>
        <td style="width:240px; text-align:left; text-transform:capitalize"><?=ucfirst($file['nombre']).'&nbsp;'.ucfirst($file['apP']).'&nbsp;'.ucfirst($file['apM']);?></td>
      </tr>

    </tbody>
  </table>
  <br>
  <h5>DETALLE DEL INVENTARIO</h5>
  <table class="report" style=" border-collapse: collapse" align="center">
    <thead>
      <tr>
        <th align="center">N°</th>
        <th align="center">PRODUCTO</th>
        <th align="center">DETALLE</th>
        <th align="center">CANTIDAD</th>
      </tr>
    </thead>
    <tbody>
      <?PHP
        $cont = 0;

        while( $row = $str->FetchRow()){
          $cont = $cont + 1;
      ?>
        <tr <?php if($cont%2 == 0) echo("class='even'"); ?> >
          <td align="center"><?=$cont?></td>
          <td align="center" style="text-transform: uppercase;"><?=$row[1];?></td>
          <td align="center" style="text-transform: uppercase;"><?=$row[2];?></td>
          <td align="center"><?=$row[3];?></td>
        </tr>
      <?PHP
        }
        $margen = 360 - ($cont *22);
      ?>

    </tbody>

  </table>

</page>
