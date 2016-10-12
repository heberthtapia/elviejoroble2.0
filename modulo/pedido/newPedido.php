<?PHP
include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
	//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$fecha = $op->ToDay();
$hora = $op->Time();

$strQuery = "SELECT max(id_pedido) FROM pedido ";
$lastId = $db->Execute($strQuery)->FetchRow();
if(!$lastId[0])
  $lastId[0] = 1;
else
  $lastId[0]++;
?>
<style type="text/css">
  #error-container-radio{
    margin: 5px 0 0 15px;
  }
</style>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12">
    <h1 class="avisos" align="center"><strong>NUEVO PEDIDO</strong></h1>
  </div>
</div>

<form id="formPreVenta" class="form-horizontal" action="javascript:savePedido('formPreVenta','savePedido.php')" >
  <div class="row">
    <div id="preventa" class="col-xs-12 col-sm-12 col-md-12">
     <div id="preizq" class="col-md-6">
      <div class="form-group">
        <label for="fecha" class="col-sm-2 control-label">FECHA: </label>
        <div class="col-sm-6">
          <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" class="form-control"/>
          <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        </div>
      </div>

      <div class="form-group">
        <label for="cliente" class="col-sm-2 control-label">Cliente: </label>
        <div class="col-sm-10">
          <input id="cliente" name="cliente" type="text" class="form-control" data-validation="required"/>
          <input id="idCliente" name="idCliente" type="hidden" value="" />
        </div>
      </div>
    </div><!--End preizq-->
    <div id="preder" class="col-md-3 col-md-offset-3">

      <div class="form-group">
        <label class="col-sm-4 control-label">N&deg; pedido: </label>
        <div class="col-sm-8">
          <input id="pedido" name="pedido" type="text" disabled value="PD-<?=$op->ceros($lastId[0],5);?>" class="form-control"/>
          <input id="pedido" name="pedido" type="hidden" value="<?=$op->ceros($lastId[0],5);?>"/>
        </div>
      </div>

    </div><!--End preder-->
  </div><!--End preventa-->
</div>

<br>

<div class="row" style="border-bottom: 2px #01406b groove;">
  <div id="ventIzq" class="col-md-2">

    <h4 align="center">NUEVO<br>PRODUCTO</h4>

    <div class="col-md-12">
      <label class="control-label">Producto: </label>
      <input id="producto" name="producto" type="text" class="form-control" data-validation="required" data-validation-optional="true"/>
    </div>

    <div class="col-md-12">
      <label class="control-label">Cantidad: </label>
      <input id="cant" name="cant" type="text" autocomplete="off" class="form-control" data-validation="number" data-validation-optional="true" />
    </div>
    <div class="clearfix"></div>
    <br>
    <div class="form-group" align="center">
      <button id="confPedido" type="button" class="btn btn-primary" onclick="adicFila('formPreVenta','producto.php');">
        <i class="fa fa-plus" aria-hidden="true"></i>
        <span>Anadir</span>
      </button>
    </div>

    <div class="form-group" align="center">
      <button id="submit" type="submit" class="btn btn-success" >
        <i class="fa fa-check" aria-hidden="true"></i>
        <span>Confirmar pedido</span>
      </button>
    </div>

    <div class="form-group" align="center">
      <button id="submit" type="reset" class="btn btn-danger" onclick="cancelarPedido();">
        <i class="fa fa-close" aria-hidden="true"></i>
        <span>Cancelar</span>
      </button>
    </div>

        <!--<div class="form-group" align="center">
            <button id="submit" type="button" class="btn btn-primary" onclick="">
              <i class="fa fa-print" aria-hidden="true"></i>
              <span>Imprimir</span>
            </button>
          </div>-->
  </div><!--End ventIzq-->

  <div id ="ventCent" class="col-md-6">
    <table id="tabla" align="center">
      <thead>
        <tr>
          <th width="270">PRODUCTO</th>
          <th>CANT.</th>
          <th width="70">P. UNIT (Bs)</th>
          <th width="70">DESC.</th>
          <th width="70">BONIF.</th>
          <th width="90">SUBTOTAL (Bs)</th>
          <th id="oculto"></th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot hidden="">
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

  <div id="ventDer" class="col-md-2">
    <div id="ventDe" class="col-md-12">
      <h4 align="center">FORMA DE PAGO</h4>
      <label class="control-label"><input type="radio" value="con" name="tipo" id="tipo" data-validation="required" data-validation-error-msg-container="#error-container-radio" ><span>&nbsp;</span> Al contado</label>
      <label class="control-label"><input type="radio" value="cre" name="tipo" id="tipo" ><span>&nbsp;</span> Al credito</label>
      <br>
    </div>
    <div class="clearfix"></div>
    <div id="error-container-radio"></div>

    <div class="col-md-12">
      <label class="control-label">Observaciones: </label><br>
      <p id="maxText"><span id="max-length-element">200</span> caracteres restantes</p>
      <textarea id="obs" name="obs" class="form-control"></textarea>
    </div>

  </div><!--End ventDer-->

  <div id="ventDer1" class="col-md-2">
    <div id="listaProd">
      <h4 align="center">LISTA DE PRODUCTOS</h4>
      <?PHP
      $sqlProd = "SELECT id_inventario, detalle FROM inventario ORDER BY id_inventario DESC";
      $sql = $db->Execute($sqlProd);
      ?>
      <table id="listaP">
        <thead>
         <tr>
           <th>CODIGO</th>
           <th>DETALLE</th>
         </tr>
       </thead>
       <tbody>
         <?PHP
         while( $row = $sql->FetchRow()){
           ?>
           <tr>
             <td><a onclick="selecCampo('<?=$row[0]?>');"><?=$row[0]?></a></td>
             <td><?=$row[1]?></td>
           </tr>
           <?PHP
         }
         ?>
       </tbody>
     </table>
   </div>
 </div>
</div>

</form>
<div class="clearfix"></div>
<script type="text/javascript" charset="utf-8">
  var oTable;
  $(document).ready(function() {

    $('#cliente').click(function(){
      $(this).removeClass('valid');
      $(this).removeClass('error');
    });

    $( "#cliente" ).autocomplete({
      source: "inc/search.php",
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item.id
        /*"Selected: " + ui.item.value + " aka " + ui.item.id :
        "Nothing selected, input was " + this.value*/
        );
      }
    });

    $.validate({
      lang: 'es',
      modules : 'security, modules/logic'
    });

    $('#obs').restrictLength( $('#max-length-element') );

    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
        //increaseArea: '100%' // optional
      });

    function log( message ) {
      $( "input#idCliente" ).val( message );
      //$( "#log" ).scrollTop( 0 );
    }

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
    };

  });
</script>