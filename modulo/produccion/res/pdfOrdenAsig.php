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

$strSql = "SELECT id_produccion, id_inventario, detalle, dateInc, dateFin, cantidad FROM produccion WHERE id_produccion = '".$id."' ";
$str = $db->Execute($strSql);

$orp = $db->Execute($strSql);

$orp = $orp->FetchRow();

$strPre = "SELECT i.id_empleado, e.nombre, e.apP, p.id_inventario, p.detalle, i.cantidad FROM produccion AS p, empleado AS e, inventarioPre AS i ";
$strPre.= "WHERE p.id_produccion = i.id_produccion AND i.id_empleado = e.id_empleado AND p.id_produccion = '".$id."' ";
$strP = $db->Execute($strPre);

$sql = 'SELECT * ';
$sql.= 'FROM empleado ';
$sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

$sql = 'SELECT * ';
$sql.= 'FROM empleado ';
$sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

$reg = $db->Execute($sql);

$user = $reg->FetchRow();
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

  <h4 align="center" style="font-size:26px;">ORDEN DE PRODUCCIÓN TERMINADA</h4>
  <table id="datos" style="border-collapse: collapse;">
    <tbody>
      <tr>
        <td style="text-align:left;"><strong>Orden Terminada por:</strong></td>
        <td style="width:240px; text-align:left; text-transform:capitalize"><?=ucfirst($user['nombre']).'&nbsp;'.ucfirst($user['apP']);?></td>
      </tr>

    </tbody>
  </table>
  <br>
  <h5>DETALLE ORDEN DE PRODUCCIÓN</h5>
  <table class="report" style=" border-collapse: collapse" align="center">
    <thead>
      <tr>
        <th>COD.<br>PRODUCCIÓN</th>
        <th>COD.<br>PRODUCTO</th>
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
          <td align="center" style="text-transform: uppercase;">OR-P-<?=$op->ceros($row[0],2);?></td>
          <td align="center" style="text-transform: uppercase;"><?=$row[1];?></td>
          <td align="center" style="text-transform: uppercase;"><?=$row[2];?></td>
          <td align="center"><?=$row[3];?></td>
          <td align="center"><?=$row[4];?></td>
          <td align="center"><?=$row[5];?></td>
        </tr>
      <?PHP
        }
      ?>

    </tbody>

  </table>

  <br>
  <h5>DETALLE DE LA ASIGNACION A LOS PREVENTISTAS</h5>

  <table class="report" style=" border-collapse: collapse" align="center">
    <thead>
        <tr>
            <th>COD.<br>EMPLEADO</th>
            <th>PREVENTISTA</th>
            <th>COD.<br>PRODUCTO</th>
            <th>DETALLE</th>
            <th>CANTIDAD<br>ASIGNADA</th>
        </tr>
    </thead>
    <tbody>
    <?PHP
        $t = 0;
        while( $file = $strP->FetchRow()){
            $t = $t + $file[5];
            $cont = $cont + 1;
    ?>
        <tr <?php if($cont%2 == 0) echo("class='even'"); ?>>
            <td align="center" style="text-transform: uppercase;"><?=$file[0]?></td>
            <td align="center" style="text-transform: uppercase;"><?=$file[1].' '.$file[2]?></td>
            <td align="center" style="text-transform: uppercase;"><?=$file[3]?></td>
            <td align="center" style="text-transform: uppercase;"><?=$file[4]?></td>
            <td align="center"><?=$file[5]?></td>
        </tr>
    <?PHP
        }
        $margen = 360 - ($cont *22);
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" align="right" style="text-transform: uppercase;">Total </th>
            <th align="center"><?=$t?></th>
        </tr>
    </tfoot>

    </table>

  <table id="firma" align="center" style="font-weight: bold; margin-top: <?=$margen?>px;">
    <tbody>
      <tr>
        <td align="center" style="width: 200px; text-align: center; text-transform: capitalize; border-top: 1px solid dashed;"><?=ucfirst($user['nombre']).'&nbsp;'.ucfirst($user['apP']);?></td>
      </tr>
      <tr>
        <td align="center">PRODUCCIÓN</td>
      </tr>
    </tbody>
  </table>

</page>