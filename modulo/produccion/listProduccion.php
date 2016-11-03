<?PHP
	session_start();

	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();
?>
<style>
.accPro {
    height: 45px;
    margin-top: 8px;
    width: 240px;
	text-align:center;
}
.accionButton {
    float: left;
    height: 32px;
    margin-left: 8px;
    width: 146px;
}
.button{
	/*float:left;*/
	margin-left:5px;
	}
.status1{
	background-color:#f58400;
	color:#ffffff;
	font-weight:bold;
}
.status2{
	background-color:#FCFB00;
	color:#000000;
	font-weight:bold;
  }
.status3{
	background-color:#1D5EA3;
	color:#ffffff;
	font-weight:bold;
}
.status4{
	background-color: #8AD120;
	color:#ffffff;
	font-weight:bold;
}
.status5{
	background-color: #AA0000;
	color:#ffffff;
	font-weight:bold;
}
</style>

<div class="titulo">
  <div class="subTit"><p class="text_titulo">Ordenes de Producci&oacute;n</p></div>
  <div class="new">
  	<a onClick="open_win('modulo/produccion/newProduccion.php', '', '600', '250');"><img src="images/add.png" width="24" height="24"><span>NUEVO...</span></a>
  </div>
  <div class="clearfix"></div>
</div><!--End titulo-->
<div id="lista">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableList" style="width:1000px">
  <thead>
    <tr>
      <th width="20px">Nº</th>
      <th>N° de Orden</th>
      <th>Codigo Producto</th>
      <th width="450px">Detalle</th>
      <th>Cant</th>
      <th>Fecha Inicio Producci&oacute;n</th>
      <th>Fecha Fin Producci&oacute;n</th>
      <th>Status Producci&oacute;n</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?PHP
      $sql	 = "SELECT * ";
      $sql	.= "FROM produccion ";
      $sql	.= "ORDER BY (id_produccion) DESC ";

      $cont = 0;

      $srtQuery = $db->Execute($sql);
	  if($srtQuery === false)
	  	die("failed");

    while( $row = $srtQuery->FetchRow()){

    ?>
      <tr id="tb<?=$row[0]?>">
          <td class="last center"></td>
          <td class="last center">OR-P-<?=$row['id_produccion'];?></td>
          <td class="last center"><?=$row['id_inventario'];?></td>
          <td class="last center"><?=$row['detalle'];?></td>

          <td class="last center"><?=$row['cantidad'];?></td>
          <td class="last center"><?=$row['dateInc'];?></td>
          <td class="last center fin"><?=$row['dateFin'];?></td>
          <?PHP
		  if(strcmp($row['statusProd'], 'Nueva Orden') == 0){
			  $st="status1";
		  }else{
			  if(strcmp($row['statusProd'], 'En Produccion') == 0){
			  	$st="status2";
		  	  }else {
				  if (strcmp($row['statusProd'], 'Terminado') == 0) {
					  $st = "status3";
				  } else
					  if (strcmp($row['statusProd'], 'Terminado y Asignado') == 0) {
						  $st = "status4";
					  } else
						  $st = "status5";
			  }
		  }
		  ?>
          <td class="last center <?=$st;?>">
          	<?=$row['statusProd'];?>
          </td>
          <td>
          	<div class="accPro">

              <div class="accion">
              	<a class="tooltip aprob" href="javascript:void(0);" onClick="sProAprobado('<?=$row[0]?>');" title="Aprobar Orden">
                    <img src="images/icono/checkOff.png" width="32"/>
                </a>
              </div><!--End accion-->

              <div class="accion">
              	<a class="tooltip cancel" href="javascript:void(0);" onClick="sProCancelar('<?=$row[0]?>');" title="Cancelar Orden">
                    <img src="images/icono/delOff.png" width="32" alt="Cancelar" />
                </a>
              </div><!--End accion-->

              <div class="accion">
              	<a class="tooltip terminar" href="javascript:void(0);" onClick="sProTerminado('<?=$row[0]?>');" title="Orden Terminada">
                    <img src="images/icono/asig.png" width="32" alt="Orden Terminada" />
                </a>
              </div><!--End accion-->

              <div class="accion">
              	<a class="tooltip import" href="javascript:void(0);" onClick="open_win('modulo/produccion/importar.php', '', '490', '500', '<?=$row['id_produccion']?>');" title="Asignar Producci&oacute;n">
                    <img src="images/icono/import.png" width="32" alt="Asignar Produccion" />
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a class="tooltip edit" href="javascript:void(0);" onClick="open_win('modulo/produccion/editProduccion.php', '', '600', '270', '<?=$row['id_produccion']?>');" title="Editar Orden">
                    <img src="images/icono/edit1.png" width="32" alt="Editar"/>
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a class="tooltip del" href="javascript:void(0);" onclick="deleteRow('delProduccion.php', '<?=$row['id_produccion']?>', 'produccion','produccion');" title="Eliminar Orden" >
                    <img src="images/icono/recycle.png" width="32" height="32" alt="Eliminar"/>
                </a>
              </div><!--End accion-->
              <div class="cleafix"></div>

           	</div><!--End accPro-->

          </td>
      </tr>
    <?PHP
      }
    ?>
  </tbody>
  <tfoot>
    <tr>
      <th>Nº</th>
      <th>N° de Orden</th>
      <th>Codigo Producto</th>
      <th>Detalle</th>
      <th>Cant</th>
      <th>Fecha Inicio Producci&oacute;n</th>
      <th>Fecha Fin Producci&oacute;n</th>
      <th>Status Producci&oacute;n</th>
      <th>Acciones</th>
    </tr>
  </tfoot>
</table>
</div><!--End Lista-->
<div class="clearfix"></div>

<script type="text/javascript">
//========DataTables========
var oTable;
$(document).ready(function() {
	/* idealForm */
	$('.accProd').idealForms();

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
	  };

  /* Init the table */
  oTable = $('#tableList').dataTable({
	  "bFilter": true,
	  "bJQueryUI": true,
	  "sPaginationType": "full_numbers",
	  "aaSorting": [[ 5, "desc" ] , [ 0, "desc" ]],
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
	  /*"aoColumnDefs": [
		   { "bVisible": false, "aTargets": [ 1 ]
		   }
		  ],*/
	  "oColVis": {
			"activate": "mouseover",
			"buttonText": "&nbsp;",
			"bRestore": true,
			"sAlign": "right"
		  }
	});

	$('.tooltip').tooltipster({
		animation: 'swing',
		delay: 200,
		theme:'tooltipster-shadow'
		});
});
/* Get the rows which are currently selected */
  function fnGetSelected( oTableLocal )
  {
	  return oTableLocal.$('tr.row_selected');
  }

  function sProAprobado(id){
  	var val = $('tr#tb'+id).find('td.status1').text();
	$.ajax({
		url: 'modulo/produccion/aprobar.php',
		type: 'post',
		cache: false,
		data:{res:id},
		success: function(data){
				$('tr#tb'+id).find('td.status1').addClass('status2');
				$('tr#tb'+id).find('td.status1').text('En Produccion');
				$('tr#tb'+id).find('td.status1').removeClass('status1');
		}
	});
  }

  function sProCancelar(id){
  	var val = $('tr#tb'+id).find('td.status1').text();
	$.ajax({
		url: 'modulo/produccion/cancelar.php',
		type: 'post',
		cache: false,
		data:{res:id},
		success: function(data){
			if(data === "1"){
				$('tr#tb'+id).find('td.status1').addClass('status5');
				$('tr#tb'+id).find('td.status1').text('Cancelado');
				$('tr#tb'+id).find('td.status1').removeClass('status1');
			}else{
				alert('En este momento no se puede cancelar la Orden');
			}
		}
	});
  }

  function sProTerminado(id){
  	var val = $('tr#tb'+id).find('td.status2').text();
	$.ajax({
		url: 'modulo/produccion/terminar.php',
		type: 'post',
		cache: false,
		data:{res:id},
		success: function(data){
			var f = new Date();
			var m = f.getMonth()+1;
			if(m<10){
				mm = '0'+m;
			}
			$('tr#tb'+id).find('td.status2').addClass('status3');
			$('tr#tb'+id).find('td.status2').text('Terminado');
			$('tr#tb'+id).find('td.status2').removeClass('status2');
			$('tr#tb'+id).find('td.fin').text(f.getFullYear()+'-'+mm+'-'+f.getDate()+' '+f.getHours()+':'+f.getMinutes()+':'+f.getSeconds());
			window.open('modulo/produccion/pdfOrdenPT.php?res='+id, '_blank');
		}
	});
  }
</script>