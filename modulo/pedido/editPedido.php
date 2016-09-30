<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$id = $_REQUEST['id'];

	$strSql = "SELECT * FROM pedido AS p, pedidoEmp AS pe ";
	$strSql.= "WHERE p.id_pedido = pe.id_pedido ";
	$strSql.= "AND p.id_pedido = '".$id."' ";

	$str = $db->Execute($strSql);
	$file = $str->FetchRow();

	$strQuery = "SELECT * FROM cliente WHERE id_cliente = '".$file['id_cliente']."' ";
	$sql = $db->Execute($strQuery);
	$rcl = $sql->FetchRow();

?>
<style>
a.button{
	width:11.5em;
	}
.obs{
	padding:0 20px;
	width:198px;
	}
textarea#obs {     	/* Para el resumen */
	width: 185px;
	height: 12em;
	overflow: auto;
}
.ui-autocomplete-loading { background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat; }

</style>
<div class="titulo">
  <div class="subTit"><p class="text_titulo">PEDIDO</p></div>

  <form id="formPreVenta" class="ideal-form" action="javascript:savePedido('formPreVenta','update.php')" >

  <div id="preventa">
  	<div id="preizq">
        <div class="idealWrap WrapPre">
        <label>FECHA: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap">
        <label>Cliente: </label>
        <input id="cliente" name="cliente" type="text" class="validate[required] text-input" value="<?=$rcl['nombreEmp'].' - '.$rcl['nombre'].' '.$rcl['apP'].' '.$rcl['apM']?>"/>

        <input id="idCliente" name="idCliente" type="hidden" value="<?=$rcl['id_cliente']?>" />
        </div><!--End idealWrap-->
    </div><!--End preizq-->
    <div id="preder">
        <div class="idealWrap WrapPre">
        <label>N&deg; pedido: </label>
        <input id="pedido" name="pedido" type="text" disabled value="PD-<?=$op->ceros($file['id_pedido'],7);?>"/>
        <input id="pedido" name="pedido" type="hidden" value="<?=$op->ceros($file['id_pedido'],7);?>"/>
        </div><!--End idealWrap-->
    </div><!--End preder-->
    <div class="clearfix"></div>

    <div id="ventIzq">
      <div id="ventIq">
        <p>NUEVO PRODUCTO</p>
        <div class="idealWrap WrapPre">
        <label>Producto: </label>
        <input id="producto" name="producto" type="text"/>
        </div><!--End idealWrap-->

        <div class="idealWrap WrapPre">
        <label>Cantidad: </label>
        <input id="cant" name="cant" type="text" autocomplete="off"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap" align="center">
            <input type="button" id="confPedido" value="Añadir" onclick="adicFilaEdit('formPreVenta','producto.php');"/>
        </div>
      </div>

        <div class="idealWrap" align="center">
            <input type="submit" id="submit" value="Confirmar Pedido" class="formPedido"/>
        </div>

        <div class="idealWrap" align="center">
            <input type="button" id="cancelar" value="Cancelar" class="formPedido" onclick=""/>
        </div>

        <div class="idealWrap" align="center">
            <input type="button" id="imprimir" value="Imprimir" class="formPedido" onclick=""/>
        </div>

     </div><!--End ventIzq-->

     <div id="ventCent">

      <table id="tabla" align="center" width="450">
          <thead>
            <tr>
              <th width="270">PRODUCTO</th>
              <th>CANT.</th>
              <th width="60">P. UNIT (Bs)</th>
              <th>DESC.</th>
              <th>BONIF.</th>
              <th width="80">SUBTOTAL (Bs)</th>
              <th id="oculto"></th>
            </tr>
          </thead>
          <tbody>
          	<?PHP
				$strSql = "SELECT p.*,pe.*, i.id_inventario, i.detalle FROM pedido AS p, pedidoEmp AS pe, inventario AS i ";
				$strSql.= "WHERE p.id_pedido = pe.id_pedido ";
				$strSql.= "AND p.id_pedido = '".$id."' ";
				$strSql.= "AND i.id_inventario = pe.id_inventario";

				$str = $db->Execute($strSql);

				 while($row = $str->FetchRow()){

			?>
            <tr id="<?=$row['id_inventario']?>">
              <td class="det"><?=$row['id_inventario']?> <?=$row['detalle']?>
              <input type="hidden" id="item" name="item" value="<?=$row['id_inventario']?>" ></td>

              <td>
              <input type="text" disabled="disabled" id="cantidad" name="cantidad" value="<?=$row['cantidad']?>" >
              <input type="hidden" id="cantidad" name="cantidad" value="<?=$row['cantidad']?>" >
              <input type="hidden" id="cantInv" name="cantInv" value="<?=$row['cantidad']?>" >
              </td>

              <td><input type="text" disabled="disabled" id="precio" name="precio" value="<?=$row['precio']?>" >
              <input type="hidden" id="precio" name="precio" value="<?=$row['precio']?>" ></td>

              <td></td>
              <td></td>
              <td><input type="text" disabled="disabled" id="subTotal" name="subTotal" value="<?=$row['cantidad']*$row['precio']?>" ></td>
              <td align="right"><a onclick="eliminarFila('<?=$row['id_inventario']?>')"><img class="delet" src="images/delete.png" width="16" height="16" /></a></td>
             </tr>
            <?PHP
				 }
			?>
          </tbody>
          <tfoot>
              <tr>
                  <th colspan="5">SUB-TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="subTotal" name="subTotal" value="0" >Bs
                      <input type="hidden" id="subTotal" name="subTotal" value="0" >
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">DESCUENTO:</th>
                  <th>
                      <input type="text" id="descuento" name="descuento" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">BONIFICACI&Oacute;N:</th>
                  <th>
                      <input type="text" id="bonificacion" name="bonificacion" autocomplete="off" onKeyUp="calculaDes();" > Bs
                  </th>
                  <th></th>
              </tr>
              <tr>
                  <th colspan="5">TOTAL:</th>
                  <th>
                      <input type="text" disabled="disabled" id="total" name="total" value="0" />Bs
                      <input type="hidden" id="total" name="total" value="0" />
                  </th>
              </tr>
          </tfoot>

      </table >

     </div><!--End ventCent-->

     <div id="ventDer">
      <div id="ventDe">
        <p>FORMA DE PAGO</p>
        <div class="idealWrap WrapV">
        <?PHP
			if($row['tipo'] == 'con'){
		?>
        <label class="rp"><input type="radio" checked value="con" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CONTADO</label>
        <label class="rp"><input type="radio" value="cre" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CREDITO</label>
        <?PHP
			}else{
		?>
        <label class="rp"><input type="radio" value="con" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CONTADO</label>
        <label class="rp"><input type="radio" checked value="cre" name="tipo" id="tipo" class="validate[required]"><span>&nbsp;</span>AL CREDITO</label>
        <?PHP
			}
		?>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
       </div>
        <div class="idealWrap obs">
        <label>Observaciones: </label>
        <textarea id="obs" name="obs"></textarea>
        </div><!--End idealWrap-->

     </div><!--End ventDer-->

  <div class="clearfix"></div>
  </div><!--End preventa-->

  </form>
  <div class="clearfix"></div>
</div><!--End titulo-->


<script type="text/javascript" charset="utf-8">
//========DataTables========

recargaFila();

var oTable;
$(document).ready(function() {



	function log( message ) {
		$( "input#idCliente" ).val( message );
		//$( "#log" ).scrollTop( 0 );
	}
	$( "#cliente" ).autocomplete({
		source: "classes/search.php",
		minLength: 2,
		select: function( event, ui ) {
			log( ui.item.id

				/*"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value*/
				);
		}
	});
 	/* idealForm */
	$('#formPreVenta').idealForms();
	/* Validación */
	jQuery("#formPreVenta").validationEngine({
		prettySelect	: true,
		useSuffix		: "_chosen"
	   // scroll		: false,
	});

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
});
</script>