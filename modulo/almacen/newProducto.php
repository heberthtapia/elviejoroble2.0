<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);
$fecha = $op->ToDay();
$hora = $op->Time();
?>
<form id="formNew" action="javascript:saveForm('formNew','almacen/save.php')" class="form-horizontal" autocomplete="off" >
	<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
				</div>
				<div class="modal-body">
					<div id="datos_ajax"></div>

					<div class="form-group">
						<label for="fecha" class="control-label col-md-2">Fecha:</label>
						<div class="col-md-4">
							<input id="fecha" name="fecha" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
						</div>
						<input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
						<input id="tabla" name="tabla" type="hidden" value="inventario">
					</div>
					<div class="form-group">
						<label for="detalle" class="control-label col-md-2">Producto:</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="detalle" name="detalle" placeholder="Nombre Producto:" data-validation="required">
						</div>
					</div>
					<div class="form-group">
						<label for="idInv" class="control-label col-md-2">Codigo:</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="idInv" name="idInv" placeholder="Codigo:"
								   data-validation="required server"
								   data-validation-url="modulo/almacen/validateCode.php">
						</div>
					</div>
					<div class="form-group">
						<label for="cant" class="control-label col-md-2">Cantidad:</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="cant" name="cant" placeholder="Cantidad:" data-validation="required number" >
						</div>
					</div>
					<div class="form-group">
						<label for="vol" class="control-label col-md-2">Volumen:</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="vol" name="vol" placeholder="Volumen:" data-validation="required number" >
						</div>
					</div>
					<div class="form-group">
						<label for="precioCF" class="control-label col-md-2">Precio C/F:</label>
						<div class="col-md-4 input-group">
							<div class="input-group-addon">Bs</div>
							<input type="text" class="form-control" id="precioCF" name="precioCF" placeholder="Precio C/F:"
							data-validation="required number"
							data-validation-error-msg-container="#error-container"
							data-validation-allowing="float" >
						</div>
						<div id="error-container"></div>
					</div>
					<div class="form-group">
						<label for="precioSF" class="control-label col-md-2">Precio S/F:</label>
						<div class="col-md-4 input-group">
							<div class="input-group-addon">Bs</div>
							<input type="text" class="form-control" id="precioSF" name="precioSF" placeholder="Precio S/F:"
							data-validation="required number"
							data-validation-error-msg-container="#error-container1"
							data-validation-allowing="float" >
						</div>
						<div id="error-container1"></div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-close" aria-hidden="true"></i>
						<span>Cancelar</span>
					</button>
					<button type="submit" id="save" class="btn btn-success">
						<i class="fa fa-check" aria-hidden="true"></i>
						<span>Agregar Producto</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$('#dataRegister').on('hidden.bs.modal', function (e) {
		// do something...
		$('#formNew').get(0).reset();
		//despliega('modulo/almacen/producto.php','contenido');
	});
</script>