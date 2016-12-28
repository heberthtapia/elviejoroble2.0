<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();
?>
<div class="titulo">
  <div class="subTit"><p class="text_titulo">lista general de empleados</p></div>
  <div class="new">
  	<a onClick="open_win('modulo/empleado/newsEmp.php', '', '710', '625');"><img src="images/add.png" width="24" height="24"><span>NUEVO...</span></a>
  </div>
  <div class="clearfix"></div>
</div><!--End titulo-->
<div id="lista">

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tableList" style="width:1000px">
  <thead>
    <tr>
      <th width="20px">Nº</th>
      <th width="70px">Fecha</th>
      <th width="100px">Foto</th>
      <th>Nombre</th>
      <th>Ap. Paterno</th>
      <th>Ap. Materno</th>
      <th>Cargo</th>
      <th width="160px">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?PHP
      $sql = "SELECT * ";
      $sql.= "FROM empleado ";
      $sql.= "ORDER BY (dateReg) DESC ";

      $cont = 0;

      $srtQuery = $db->Execute($sql);
	  if($srtQuery === false)
	  	die("failed");

    while( $row = $srtQuery->FetchRow()){

    ?>
      <tr id="tb<?=$row[0]?>">
          <td class="last center"><?=$cont;?></td>
          <td class="last center"><?=$row['dateReg']?></td>
          <td class="last center">
			  <?PHP
			  	if( $row['foto'] != '' )
                {
              ?>
                  <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/<?=($row['foto']);?>&amp;w=100&amp;h=50&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">

              <?PHP
                 }
                 else{
              ?>
              	  <img class="thumb" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=100&amp;h=50&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
              <?PHP
                  }
              ?>
          </td>
          <td class="last center"><?=$row['nombre'];?></td>
          <td class="last center"><?=$row['apP'];?></td>
          <td class="last center"><?=$row['apM'];?></td>
          <td class="last center"><?=$op->toSelect($row['cargo']);?></td>
          <td>
          	<div class="accEmp">

              <div class="accion">
                <a href="javascript:void(0);" onclick="open_win('modulo/empleado/previewEmp.php', '', '710', '580', '<?=$row['id_empleado']?>');">
                    <img src="images/icono/preview.png" width="32" alt="" title="Vista Previa" />
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a href="javascript:void(0);" onClick="open_win('modulo/empleado/editEmp.php', '', '710', '625', '<?=$row['id_empleado']?>');">
                    <img src="images/icono/edit1.png" width="32" alt="" title="Editar" />
                </a>
              </div><!--End accion-->

              <div class="accion">
                <a href="javascript:void(0);" onclick="javascript:deleteRow('delEmp.php','<?=$row['id_empleado']?>','empleado');">
                    <img src="images/icono/recycle.png" width="32" height="32" alt="" title="Eliminar" />
                </a>
              </div><!--End accion-->

              <div class="accion check">
              	<form name="myform<?=$row[0]?>" class="status">
					          <?PHP
                        if( $row['status'] == 'Activo' ){
                    ?>
                    <label><input name="checks" type="checkbox" checked="checked" onclick="status(<?=$row['id_empleado']?>,'empleado');" /></label>
                    <?PHP
                    }else{
                    ?>
                    <label><input name="checks" type="checkbox" onclick="status(<?=$row['id_empleado']?>,'empleado');" /></label>
                    <?PHP
                        }
                    ?>
                </form>
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
      <th>Foto</th>
      <th>Nombre</th>
      <th>Ap. Paterno</th>
      <th>Ap. Materno</th>
      <th>Cargo</th>
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

	deleteRow = function(p, idTr, table){

	var respuesta = confirm("SEGURO QUE DESEA ELIMINAR EL "+" ' "+table.toUpperCase()+" ' ");

	if(respuesta){
		var i = 1;
		$('#tb'+idTr).addClass('row_selected');
		var anSelected = fnGetSelected( oTable );

		if ( anSelected.length !== 0 ) {
			oTable.fnDeleteRow( anSelected[0] );
			deleteRowBD(p, idTr, table);
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