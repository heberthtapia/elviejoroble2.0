<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);
$fecha = $op->ToDay();
$hora = $op->Time();
?>
<style>
    #detalle{
        text-transform: capitalize;
    }
    #idInv{
        text-transform: uppercase;
    }
    #alertRegister{
        margin-bottom: 10px;
        display: none;
    }
</style>
<form id="form" action="javascript:saveForm('form','almacen/save.php')" class="form-horizontal" >
	<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
				</div>
				<div class="modal-body">

                    <div id="alertRegister">

                    </div>

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
                            <div class="input-group-addon">Bs.</div>
							<input type="text" class="form-control" id="precioCF" name="precioCF" placeholder="Precio C/F:" data-validation="required number" data-validation-allowing="float" >
						</div>
					</div>
					<div class="form-group">
						<label for="precioSF" class="control-label col-md-2">Precio S/F:</label>
                        <div class="col-md-4 input-group">
                            <div class="input-group-addon">Bs.</div>
							<input type="text" class="form-control" id="precioSF" name="precioSF" placeholder="Precio S/F:" data-validation="required number" data-validation-allowing="float" >
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" >Guardar datos</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$.validate({
		lang: 'es',
		modules : 'security',
        decimalSeparator : ','
	});

	$('#dataRegister').on('hidden.bs.modal', function (e) {
		// do something...
		$('#form').get(0).reset();
        despliega('modulo/almacen/producto.php','contenido');
	});

	$(document).ready(function(e) {
		/* idealForm */
		// $('#form').idealForms();

		/* Calendario */
		$('#dateNac').datetimepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			yearRange: 'c-40:c-0'
		});

		$('#file_upload').uploadify({
			'queueID'  		: 'some_file_queue',
			'swf'      		: 'uploadify/uploadify.swf',
			'uploader'		: 'uploadify/uploadify.php',
			'method'   		: 'post',
			'multi'   		: false,
			'auto'   			: false,
			'queueSizeLimit' 	: 1,
			'fileSizeLimit' 	: '100KB',
			'fileTypeDesc' 	: 'Imagen',
			'fileTypeExts' 	: '*.jpg',
			'removeCompleted' : false,
			'buttonText'		: 'Examinar...',
			height       		: 25,
			width        		: 100,
			'formData'      	: {
				'path' : 'empleado'
			},
			// ** Eventos **
			'onSelectOnce':function(event,data){
				$('#file_upload').uploadifySettings('scriptData',{'directorio':'a','CodeUser': '21'});
			},
			'onUploadComplete': function(file) {

				idImg();

				$('#cboxTitle').html('La foto ' + file.name + ' se subio correctamente, <br> ahora puede guardar el formulario.');
				setTimeout(function(){
					$( ".uploadShow" ).toggle(2000,function(){
						$('a#save, a#reset').fadeIn(1000).removeClass('uploadHiden');
						/*$('.labelUpload').find('p').html('');
						 $('.labelUpload').find('a').html('');*/
						$('.labelUpload').find('p').html('Subir Foto haga clik:');
						$('.labelUpload').find('a').html('Aqu&iacute;');

					});
				},4000);

			}
		});
		/* Abrir y cerrar uploadIfy */
		$('.openUpload').click(
			function(){
				var $this = $(this);
				var op = $this.text();

				if( op == 'Aquí' ){
					$('.labelUpload').find('p').html('Imagen:');
					$('.labelUpload').find('a').html(' ( Cerrar )');
					$('a#save, a#reset').fadeOut(1000,function(){
						$('a#save, a#reset').addClass('uploadHiden');
						$('#cboxTitle').html('La imagen (JPG) debe terner un peso menor a 100 Kb.');
					});
				}else{
					$('.labelUpload').find('p').html('Subir foto haga clik:');
					$('.labelUpload').find('a').html('Aqu&iacute;');
					$('a#save, a#reset').fadeIn(1000).removeClass('uploadHiden');
					$('#cboxTitle').html('');
				}
				$( ".uploadShow" ).toggle(1000);
			}
		)


	});

</script>