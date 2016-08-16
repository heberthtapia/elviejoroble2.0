<?PHP	
	session_start();
	
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';
	
	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();
	
	$op = new cnFunction();
?>
<div class="titulo">
  <div class="subTit"><p class="text_titulo">productos</p></div>
  <div class="new">
  	<a onClick="open_win('modulo/producto/newProducto.php', '', '710', '310');"><img src="images/add.png" width="24" height="24"><span>NUEVO...</span></a>
  </div>
  <div class="clearfix"></div>
</div><!--End titulo-->
<div id="lista">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableList" style="width:1000px">
  <thead>
    <tr>
      <th width="20px">Nº</th>
      <th width="70px">Fecha</th>
      <th>Codigo</th>
      <th width="400px">Detalle</th>
      <th>Volumen</th>
      <th>Cantidad</th>
      <th>Precio C/F</th>
      <th>Precio S/F</th>      
      <th width="70px">Acciones</th>     
    </tr>
  </thead>
  <tbody>
    <?PHP
      $sql	 = "SELECT * ";
      $sql	.= "FROM inventario ";
      $sql	.= "ORDER BY (dateReg) DESC ";
      
      $cont = 0;
      
      $srtQuery = $db->Execute($sql);
	  if($srtQuery === false)
	  	die("failed");
	  
    while( $row = $srtQuery->FetchRow()){
	
    ?>
      <tr id="tb<?=$row[0]?>">
          <td class="last center"><?=$cont;?></td>
          <td class="last center"><?=$row['dateReg']?></td>
          <td class="last center"><?=$row['id_inventario'];?></td>
          <td class="last center"><?=$row['detalle'];?></td>
          <td class="last center"><?=$row['volumen'];?></td>
          <td class="last center"><?=$row['cantidad'];?></td>
          <td class="last center"><?=$row['precioCF'];?></td>
          <td class="last center"><?=$row['precioSF'];?></td>          
          <td>
          	<div class="accPro">
                          
              <div class="accion">
                <a href="javascript:void(0);" onClick="open_win('modulo/producto/editProducto.php', '', '710', '310', '<?=$row['id_inventario']?>');">
                    <img src="images/icono/edit1.png" width="32" alt="" title="Editar" />
                </a>
              </div><!--End accion-->
              
              <div class="accion">
                <a href="javascript:void(0);" onclick="deleteRow('delProducto.php', '<?=$row['id_inventario']?>', 'producto','inventario');">
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
      <th>Codigo</th>
      <th>Detalle</th>
      <th>Volumen</th>
      <th>Cantidad</th>
      <th>Precio C/F</th>
      <th>Precio S/F</th>    
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