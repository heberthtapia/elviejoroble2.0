<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);
$fecha = $op->ToDay();
$hora = $op->Time();

$srtSql = "SELECT * FROM empleado WHERE cargo = 'pre' ";
$srtQuery = $db->Execute($srtSql);
?>
<form id="formImport" action="javascript:saveForm('formImport','produccion/savePro.php')" class="form-horizontal" autocomplete="off" >
  <div class="modal fade" id="dataImport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">Asignar Producción</h4>
        </div>
        <div class="modal-body">
          <div id="datos_ajax_import"></div>

          <div class="form-group">
            <label for="fecha" class="control-label col-md-2">Fecha:</label>
            <div class="col-md-4">
              <input id="fecha" name="fecha" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
            </div>
            <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
            <input id="idP" name="idP" type="hidden" value="" />
            <input id="tabla" name="tabla" type="hidden" value="inventarioPre">
          </div>
          <div class="form-group">
            <label for="idInv" class="control-label col-md-2">Codigo:</label>
            <div class="col-md-4">
              <input type="text" class="form-control" id="idInv" name="idInv" placeholder="Codigo:" data-validation="required">
            </div>
          </div>
          <div class="form-group">
            <label for="detalle" class="control-label col-md-2">Producto:</label>
            <div class="col-md-10">
              <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Nombre Producto:" readonly="" >
            </div>
          </div>
          <div class="form-group">
            <label for="cant" class="control-label col-md-2">Cantidad:</label>
            <div class="col-md-4">
              <input type="text" class="form-control" id="cant" name="cant" placeholder="Cantidad:" data-validation="number" data-validation-allowing="range[0;0]" data-validation-error-msg="Se debe de asignar toda la produccion">
              <input id="cantP" name="cantP" type="hidden" value=""/>
            </div>
          </div>

          <h4 style="text-align: center; color: #112863">Asignar Cantidades</h4>

          <?php
          $strEmp = "SELECT COUNT(*) FROM empleado WHERE cargo = 'pre' ";
          $strNum = $db->Execute($strEmp);
          $NumRow = $strNum->FetchRow();
            $c=0;
           while( $row = $srtQuery->FetchRow() ){
            $c++;
          ?>

          <div class="form-group">
            <label for="pre<?=$c?>" class="control-label col-md-2"><?=$row['nombre'].' '.$row['apP'];?>: </label>
            <div class="col-md-4">
              <input id="pre<?=$c;?>" name="<?=$row['id_empleado'];?>" type="text" class="form-control" autocomplete="off" placeholder="Cantidad" onblur="actuCant(<?=$NumRow[0];?>)" value="0" data-validation="required number" />
            </div>
          </div>

          <?php
          }
          ?>

        </div>
        <div class="modal-footer">
          <button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
            <i class="fa fa-close" aria-hidden="true"></i>
            <span>Cancelar</span>
          </button>
          <button type="submit" id="save" class="btn btn-success">
            <i class="fa fa-check" aria-hidden="true"></i>
            <span>Guardar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</form>

<script>
  $('#dataImport').on('hidden.bs.modal', function (e) {
    // do something...
    $('#formImport').get(0).reset();
    //despliega('modulo/almacen/producto.php','contenido');
  });

  $('#dataImport').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var id = button.data('id'); // Extraer la información de atributos de datos
        var idInv = button.data('idinv'); // Extraer la información de atributos de datos
        var detalle = button.data('detalle'); // Extraer la información de atributos de datos
        var cantidad = button.data('cantidad'); // Extraer la información de atributos de datos

        var modal = $(this);
        modal.find('.modal-title').text('Asignar Orden de Producción: OR-P-'+id);
        modal.find('.modal-body #idP').val(id);
        modal.find('.modal-body #idInv').val(idInv);
        modal.find('.modal-body #detalle').val(detalle);
        modal.find('.modal-body #cant').val(cantidad);
        modal.find('.modal-body #cantP').val(cantidad);

    });

  /**
   * Funcion para restar actualizar cantidades
   */
    function actuCant(num){
      pre = 'pre';
      total = 0;
      cantPro = $('input#cantP').val();
      for(i=1; i<=num; i++){
          f = pre+i;
          //alert(f);
          cantPre = $('input#'+f).val();
          //alert(cantPre);
          total = parseInt(total) + parseInt(cantPre);
      }
      resto = parseInt(cantPro) - parseInt(total);
      $('input#cant').val(resto);
    }

</script>