<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../classes/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	/* vaciamos las tablas auxiliares */

	$sql = "TRUNCATE TABLE aux_img ";

	$strQ = $db->Execute($sql);
?>
<!--Web Cam-->
<script type="text/javascript" src="webcam/webcam.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/chosen.jquery.js"></script>
<script>

  $(document).ready(function(e) {
  /* idealForm */
	  $('#form').idealForms();
  /* Calendario */
	  $('#dateNac').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
		yearRange: 'c-40:c-0'
	  });
  /* Validación */
	  jQuery("#form").validationEngine({
		  prettySelect	: true,
		  useSuffix		: "_chosen"
		 // scroll		: false,
	  });
  /* uploadIfy */
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

<form id="form" action="javascript:saveForm('form','producto/save.php')" >
	<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
				</div>
				<div class="modal-body">
					<div id="datos_ajax_register"></div>
					<div class="form-group">

						<label class="control-label">Fecha:</label>
						<input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
						<input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />

						<label for="codigo0" class="control-label">Código:</label>
						<input type="text" class="form-control" id="codigo0" name="codigo" required maxlength="2">
					</div>
					<div class="form-group">
						<label for="nombre0" class="control-label">Nombre:</label>
						<input type="text" class="form-control" id="nombre0" name="nombre" required maxlength="45">
					</div>
					<div class="form-group">
						<label for="moneda0" class="control-label">Moneda:</label>
						<input type="text" class="form-control" id="moneda0" name="moneda" required maxlength="3">
					</div>
					<div class="form-group">
						<label for="capital0" class="control-label">Capital:</label>
						<input type="text" class="form-control" id="capital0" name="capital" required maxlength="30">
					</div>
					<div class="form-group">
						<label for="continente0" class="control-label">Continente:</label>
						<input type="text" class="form-control" id="continente0" name="continente" required maxlength="15">
					</div>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar datos</button>
				</div>
			</div>
		</div>
	</div>
</form>


  <form id="form" class="ideal-form" action="javascript:saveForm('form','producto/save.php')" >
  	<fieldset>
      <legend>N U E V O&nbsp;&nbsp;&nbsp;P R O D U C T O</legend>
        <div class="idealWrap WrapDS">
        <label class="date">Fecha: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        <input id="tabla" name="tabla" type="hidden" value="inventario" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        <br>

        <div class="idealWrap WrapDET">
        <input id="detalle" name="detalle" type="text" placeholder="Nombre producto" value="" class="validate[required] text-input" autocomplete="off"  />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCOD">
        <input id="idInv" name="idInv" type="text" placeholder="Codigo" class="validate[required,maxSize[20],custom[onlyLetterSpacio], ajax[ajaxIdInvCallPhp]] text-input" value="" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapCOD">
        <input id="cant" name="cant" type="text" placeholder="Cantidad" value="" class="validate[required, custom[number]] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCOD">
        <input id="vol" name="vol" type="text" placeholder="Volumen" value="" class="validate[required, custom[number]] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCOD">
        <input id="precioCF" name="precioCF" type="text" placeholder="Precio C/F" value="" class="validate[required, custom[number]] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCOD">
        <input id="precioSF" name="precioSF" type="text" placeholder="Precio S/F" value="" class="validate[required, custom[number]] text-input" />
        </div><!--End idealWrap-->

	</fieldset>
   		<div class="idealWrap" align="center">
			<input type="reset" id="reset" value="Limpiar..."/>
			<input type="submit" id="save" value="Guardar..."/>
		</div>

  </form>
<div class="clearfix"></div>
<style>
input#cant[type="text"] {
    margin: 0px 0 0 20px;
    width: 6em;
}
</style>