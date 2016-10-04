<?PHP
	session_start();
	
	include '../../classes/class.connection.php';
	
	$operations = new DBConnection();
	
	$fecha = $operations->ToDay();    
	$hora = $operations->Time();
	$mes = $operations->ToMes();
	$anio = $operations->ToAno();
	
	$str = "SELECT SUM(total) ";
	$str.= "FROM venta ";
	$str.= "WHERE dateReg > ".$anio."-".$mes."-1 ";
	$str.= "AND dateReg < ".$anio."-".$mes."-31";
	
	$sql = $operations->Query($str);
	
	$total = $operations->rsQuery($sql);
	
	$str = "SELECT * ";
	$str.= "FROM venta ";
	$str.= "WHERE dateReg > '".$anio."-11-1' AND dateReg < '".$anio."-".$mes."-31'  ";
	
	
	$sql = $operations->Query($str);
	
	
	
?>
<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
    <page_header>
        <table id="header" style="width: 100%;">
            <tr>
                <td style="width: 50%">YESUWEAR</td>          
                <td style=" text-align:right; width: 50%"><?php echo date('Y/m/d'); ?></td>
            </tr>
        </table>
        <hr />
    </page_header>
    <page_footer>
        <hr />
        <table id="footer" style="width: 100%;;">
            <tr>
                <td style="width: 80%">Av. Arce Nª 2147 Tel&eacute;fonos: (591 - 2) 2442144 - 2442074 Casilla de Correo: 3116<br />Pagina Web: <a href="http://pnp.gob.bo/">www.pnp.gob.bo</a></td>
                <td style=" text-align:right; width: 20%">pagina [[page_cu]]/[[page_nb]]</td>
            </tr>
        </table>
    <link rel="stylesheet" type="text/css" href="../css/pdf.css"/>
    </page_footer>
<span style="font-size: 20px; font-weight: bold">Démonstration des retour à la ligne automatique, ainsi que des sauts de page automatique</span><br>
    <br>
    <br>
    <table style="width: 80%;border: solid 1px #5544DD; border-collapse: collapse" align="center">
      <thead>
          <tr>
              <th style="width: 10%; text-align: left; border: solid 1px #337722; background: #CCFFCC">N°</th>
              <th style="width: 30%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Fecha de Registro</th>
              <th style="width: 30%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Codigo</th>
              <th style="width: 30%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Venta</th>
          </tr>
      </thead>
      <tbody>
<?php

    $cont = 0;  
    
    while( $row = $operations->rstQuery($sql) ){
        $cont = $cont + 1;         
  ?>
    <tr id="tb<?=$row[0]?>">
        <td class="last"><?=$cont;?></td>
        <td class="last"><?=$row['dateReg'];?></td>
        <td class="last"><?=$row['nit'];?></td>
        <td class="last"><?=$row['total'];?></td>         
    </tr>                  
 

<?php
  }
?>
      </tbody>
      <tfoot>
          <tr>
              <th style="width: 10%; text-align: left; border: solid 1px #337722; background: #CCFFCC">N°</th>
              <th style="width: 15%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Fecha de Registro</th>
              <th style="width: 55%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Codigo</th>
              <th style="width: 20%; text-align: left; border: solid 1px #337722; background: #CCFFCC">Venta</th>
          </tr>
      </tfoot>
    </table>
    
</page>
