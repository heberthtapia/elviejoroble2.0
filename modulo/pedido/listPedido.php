<?PHP
	session_start();

	include("../../adodb5/adodb.inc.php");
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

    $idEmp = $_SESSION['idEmp'];
	$cargo = $_SESSION['cargo'];
?>
<div class="titulo">
  <div class="subTit"><p class="text_titulo">pedidos realizados</p></div>
  <div class="new">
  	<a onClick="despliega('modulo/pedido/newPedido.php','contenido');"><img src="images/add.png" width="24" height="24"><span>NUEVO...</span></a>
  </div>
  <div class="clearfix"></div>
</div><!--End titulo-->
<div id="lista">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableList" style="width:1000px">
  <thead>
    <tr>
      <th width="20px">Nº</th>
      <th width="70px">Fecha</th>
      <th width="100px">N&deg; pedido</th>
      <th>SubTotal</th>
      <th>Des.</th>
      <th>Bonf.</th>
      <th>Total</th>
      <th width="90px">a cuenta</th>
      <th>saldo</th>
      <th>Observaciones</th>
      <th width="100">Tipo de Pago</th>
      <th>Status Contador</th>
      <th>Status Almacen</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?PHP
      $sql = "SELECT * ";
      $sql.= "FROM pedido AS p, empleado AS e ";
	  $sql.= "WHERE p.id_empleado = e.id_empleado ";
	  if($cargo!='adm'){
	  $sql.= "AND p.id_empleado = ".$idEmp." ";
	  }
      $sql.= "ORDER BY (p.dateReg) DESC ";

      $cont = 0;

      $srtQuery = $db->Execute($sql);
	  if($srtQuery === false)
	  	die("failed");

    while( $row = $srtQuery->FetchRow()){

    ?>
      <tr id="tb<?=$row[0]?>">
          <td class="last center"><?=$cont;?></td>
          <td class="last center"><?=$row['dateReg']?></td>
          <td class="last center">PD-<?=$op->ceros($row['id_pedido'],7);?></td>
          <td class="last center"><?=$row['subTotal'];?></td>
          <td class="last center"><?=$row['descuento'];?></td>
          <td class="last center"><?=$row['bonificacion'];?></td>
          <td class="last center"><?=$row['total'];?></td>
          <td class="last center"><?=$row['aCuenta'];?></td>
          <td class="last center"><?=$row['saldo'];?></td>
          <td class="last center"><?=$row['obser'];?></td>
          <td class="last center">
		  	<?PHP
			  if( $row['tipo']=='con' )
			  	echo 'Al Contado';
			  else
			  	echo 'Al Credito';
			?>
          </td>
          <td class="last center <?=$row['status1'];?>">
          <a class="status1" href="javascript:void(0)" onClick="open_win('modulo/pedido/checkPedido.php', '', '710', '520', '<?=$row['id_pedido'];?>');"><?=$row['status1'];?></a></td>
          <td class="last center <?=str_replace(' ', '', $row['status2']);?>">
          <a class="status2" href="javascript:void(0)" onClick="open_win('modulo/pedido/checkPedidoA.php', '', '710', '520', '<?=$row['id_pedido'];?>');"><?=$row['status2'];?></a></td>
          <td>
          	<div class="accPed">

              <div class="accion">
                <a href="javascript:void(0)" onclick="javascript:detalle('<?=$row['id_pedido'];?>');">
                    <img src="images/icono/preview.png" width="32" alt="" title="Detalle" />
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a href="javascript:void(0)" onclick="javascript:despliega('modulo/pedido/editPedido.php','contenido','<?=$row['id_pedido']?>');">
                    <img src="images/icono/edit1.png" width="32" alt="" title="Editar" />
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a href="javascript:void(0)" onclick="javascript:deleteRow('delPedido.php','<?=$row['id_pedido']?>', 'pedido', 'pedido');">
                    <img src="images/icono/recycle.png" width="32" height="32" alt="" title="Eliminar" />
                </a>
              </div><!--End accion-->

              <div class="cleafix"></div>
           	</div><!--End accEmp-->

          </td>
      </tr>
    <?PHP
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th>Nº</th>
      <th>Fecha</th>
      <th>N&deg; pedido</th>
      <th>SubTotal</th>
      <th>Des.</th>
      <th>Bonf.</th>
      <th>Total</th>
      <th>a cuenta</th>
      <th>saldo</th>
      <th>Observaciones</th>
      <th>Tipo de Pago</th>
      <th>Status Contador</th>
      <th>Status Almacen</th>
      <th>Acciones</th>
    </tr>
  </tfoot>
</table>

</div>
<div class="clearfix"></div>

<script type="text/javascript" charset="utf-8">
//========DataTables========
var oTable;
$(document).ready(function() {

	deleteRow = function(p, idTr, tipo, table){

		var respuesta = confirm("SEGURO QUE DESEA ELIMINAR EL "+" ' "+tipo.toUpperCase()+" ' ");

		if(respuesta){
			var i = 1;
			$('#tb'+idTr).addClass('row_selected');
			var anSelected = fnGetSelected( oTable );
			if ( anSelected.length !== 0 ) {
				r = deleteRowBD(p, idTr, tipo, table);
				if(r==1)
					oTable.fnDeleteRow( anSelected[0] );
				else
					$('#tb'+idTr).removeClass('row_selected');
			}
		}
	  }

  /* Init the table */
  oTable = $('#tableList').dataTable({
	  "bFilter": true,
	  "bJQueryUI": true,
	  "sPaginationType": "full_numbers",
	  "aaSorting": [[ 1, "desc" ] , [ 0, "desc" ]],
	  "sDom": 'C<"clear">lfrtip',
	  "oLanguage": {
		  "sLengthMenu": 'Mostrar <select>'+
			'<option value="10">10</option>'+
			'<option value="20">20</option>'+
			'<option value="30">30</option>'+
			'<option value="-1">Todos</option>'+
			'</select> registros',
		   "sInfo": "Del _START_ al _END_ de _TOTAL_ registros",
		   "sInfoEmpty": "No hay registros para mostrar.",
		   "sLoadingRecords": "Por favor espere - Cargando...",
		   "sZeroRecords": "No se encontraron registros...",
		   "sInfoFiltered": "(filtrado de _MAX_ registros)",
		   "sSearch": "Buscar: "
		  },
	  "aoColumnDefs": [
		   { "bVisible": false, "aTargets": [ 1 ] }
		  ],
	  "oColVis": {
			"activate": "mouseover",
			"buttonText": "&nbsp;",
			"bRestore": true,
			"sAlign": "right"
		  }
});

});
/* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
	  return oTableLocal.$('tr.row_selected');
  }

</script>