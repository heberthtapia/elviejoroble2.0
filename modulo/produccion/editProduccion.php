<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);

$fecha = $op->ToDay();
$hora = $op->Time();

$id = $_REQUEST['id'];

$sql = "SELECT * FROM produccion WHERE id_produccion = '$id'";

$str = $db->Execute($sql);

$row = $str->FetchRow();



?>
<form id="formUpdate" action="javascript:updateForm('formUpdate','produccion/update.php')" class="form-horizontal" autocomplete="off" >
	<div class="modal fade" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Editar Orden de Producción</h4>
				</div>
				<div class="modal-body">
					<div id="datos_ajax_update"></div>

					<div class="form-group">
						<label for="fecha" class="control-label col-md-2">Fecha:</label>
						<div class="col-md-4">
							<input id="fechaN" name="fechaN" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
						</div>
						<input id="dateU" name="dateU" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
						<input id="idU" name="idU" type="hidden" value="" />
						<input id="tabla" name="tabla" type="hidden" value="produccion">
					</div>
					<div class="form-group">
						<label for="idInv" class="control-label col-md-2">Codigo:</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="idInvU" name="idInvU" placeholder="Codigo:" data-validation="required">
						</div>
					</div>
					<div class="form-group">
						<label for="detalle" class="control-label col-md-2">Producto:</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="detalleU" name="detalleU" placeholder="Nombre Producto:" readonly="" >
						</div>
					</div>
					<div class="form-group">
						<label for="cant" class="control-label col-md-2">Cantidad:</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="cantU" name="cantU" placeholder="Cantidad:" data-validation="required number" >
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-close" aria-hidden="true"></i>
						<span>Cancelar</span>
					</button>
					<button type="submit" id="save" class="btn btn-success">
						<i class="fa fa-check" aria-hidden="true"></i>
						<span>Guardar Cambios</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$('#dataUpdate').on('hidden.bs.modal', function (e) {
		// do something...
		$('#formUpdate').get(0).reset();
		//despliega('modulo/almacen/producto.php','contenido');
	});

	$('#dataUpdate').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var id = button.data('id'); // Extraer la información de atributos de datos
        var idInv = button.data('idinv'); // Extraer la información de atributos de datos
        var detalle = button.data('detalle'); // Extraer la información de atributos de datos
        var cantidad = button.data('cantidad'); // Extraer la información de atributos de datos

        var modal = $(this);
        modal.find('.modal-title').text('Editar Orden de Producción: OR-P-'+id);
        modal.find('.modal-body #idU').val(id);
        modal.find('.modal-body #idInvU').val(idInv);
        modal.find('.modal-body #detalleU').val(detalle);
        modal.find('.modal-body #cantU').val(cantidad);

    });

	$(document).ready(function(){
		function log( message ) {
			//alert(message);
			$( "input#detalleU" ).val( message );
			//$( "input#idInv" ).val( message );
			//$( "#log" ).scrollTop( 0 );
		}
		$( "#idInvU" ).autocomplete({
			source: "inc/produccion.php",
			minLength: 2,
			select: function( event, ui ) {
				log(ui.item.id
					/*ui.item ?
					"Selected: " + ui.item.value + " aka " + ui.item.id :
					"Nothing selected, input was " + this.value*/
					);
			}
		});
	});
</script>