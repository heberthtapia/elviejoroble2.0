<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);
$fecha = $op->ToDay();
$hora = $op->Time();
?>
<style>
	#mapa{
		width:350px;
		height:220px;
		border:1px #CCCCCC solid;
		margin-top:-177px;
		float:right;
		}
	textarea {
	height: 4em;
	}
	#camera{
		margin: -490px auto auto 100px;
		position:absolute;
	}
</style>
<!--Web Cam-->
<script type="text/javascript" src="webcam/webcam.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script  src = "https://maps.googleapis.com/maps/api/js" async  defer ></script>

<script>
	$('#dataRegister').on('show.bs.modal', function() {
		//Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
		initMap();
	});

	$('#dataRegister').on('hidden.bs.modal', function (e) {
		// do something...
		$('#formNew').get(0).reset();
		//despliega('modulo/almacen/producto.php','contenido');
	});

	$(document).ready(function(e) {

		/* Calendario */

		$('#dateNac').datetimepicker({
			locale: 'es'
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

		// BUSCADOR
		$('#search').on('click', function() {
			// Obtenemos la dirección y la asignamos a una variable
			var address = $('#buscar').val();
			// Creamos el Objeto Geocoder
			var geocoder = new google.maps.Geocoder();
			// Hacemos la petición indicando la dirección e invocamos la función
			// geocodeResult enviando todo el resultado obtenido
			geocoder.geocode({ 'address': address}, geocodeResult);
		});
	});

	function openWebCam(){
		openWebcam();//document.write( webcam.get_html(320, 240) );
		webcam.set_api_url( 'modulo/empleado/uploadEmp.php' );
		webcam.set_hook( 'onComplete', 'my_callback_function');
	}
	function my_callback_function(response) {
		//alert("Success! PHP returned: " + response);
		msg = $.parseJSON(response);
		//alert(msg.filename);
		//modificado
		recargaImg(msg.filename, 'empleado');
		//
	}

	function initMap(){
		/* GOOGLE MAPS */
		var formulario = $('#formNew');
		//COODENADAS INICIALES -16.5207007,-68.1615534
		//VARIABLE PARA EL PUNTO INICIAL
		var punto = new google.maps.LatLng(-16.499299167397574, -68.1646728515625);
		//VARIABLE PARA CONFIGURACION INICIAL
		var config = {
			zoom:10,
			center:punto,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		mapa = new google.maps.Map( $("#mapa")[0], config );

		google.maps.event.addListener(mapa, "click", function(event){
			//OBTENER COORDENADAS POR SEPARADO
			var coordenadas = event.latLng.toString();
			coordenadas = coordenadas.replace("(", "");
			coordenadas = coordenadas.replace(")", "");

			var lista = coordenadas.split(",");
			//alert(lista[0]+"---"+lista[1])
			var direccion = new google.maps.LatLng(lista[0], lista[1]);
			//variable marcador
			var marcador = new google.maps.Marker({
				//titulo: prompt("Titulo del marcador"),
				position: direccion,
				map: mapa, //ENQUE MAPA SE UBICARA EL MARCADOR
				animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
				draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
				//title:"Hello World!"
			});

			//PASAR LAS COORDENADAS AL FORMULARIO
			formulario.find("input[name='cx']").val(lista[0]);
			formulario.find("input[name='cy']").val(lista[1]);
			//UBICAR EL FOCO EN EL CAMPO TITULO
			formulario.find("input[name='addresC']").focus();

			//UBICAR EL MARCADOR EN EL MAPA
			//setMapOnAll(null);
			markers.push(marcador);

			//AGREGAR EVENTO CLICK AL MARCADOR
			google.maps.event.addListener(marcador, "click", function(){
				//alert(marcador.titulo);
			});
			deleteMarkers(markers);
			marcador.setMap(mapa);
		});

	}

	//FUNCIONES PARA EL GOOGLE MAPS

	function deleteMarkers(lista){
		for(i in lista){
			lista[i].setMap(null);
		}
	}

	function geocodeResult(results, status) {
		// Verificamos el estatus
		if (status == 'OK') {
			// Si hay resultados encontrados, centramos y repintamos el mapa
			// esto para eliminar cualquier pin antes puesto
			var mapOptions = {
				center: results[0].geometry.location,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			//mapa = new google.maps.Map($("#mapa").get(0), mapOptions);
			// fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
			mapa.fitBounds(results[0].geometry.viewport);
			// Dibujamos un marcador con la ubicación del primer resultado obtenido
			//var markerOptions = { position: results[0].geometry.location }
			var direccion = results[0].geometry.location;
			var coordenadas = direccion.toString();

			coordenadas = coordenadas.replace("(", "");
			coordenadas = coordenadas.replace(")", "");
			var lista = coordenadas.split(",");

			//var direccion = new google.maps.LatLng(lista[0], lista[1]);
			//PASAR LAS COORDENADAS AL FORMULARIO

			$('#formNew').find("input[name='cx']").val(lista[0]);
			$('#formNew').find("input[name='cy']").val(lista[1]);
			//$('#form').find("input[name='buscar']").val('');

			var marcador = new google.maps.Marker({
				position: direccion,
				zoom:15,
				map: mapa, //ENQUE MAPA SE UBICARA EL MARCADOR
				animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
				draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
			});
			markers.push(marcador);
			deleteMarkers(markers);
			marcador.setMap(mapa);
			//marker.setMap(mapa);

		} else {
			// En caso de no haber resultados o que haya ocurrido un error
			// lanzamos un mensaje con el error
			alert("El buscador no tuvo éxito debido a: " + status);
		}
	}

</script>
<form id="formNew" action="javascript:saveForm('formNew','empleado/save.php')" class="form-inline" autocomplete="off" >
	<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
				</div>
				<div class="modal-body">
					<div id="datos_ajax"></div>


      	<div id="foto">
        	<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
        </div>

					<div class="form-group">
						<label for="fecha" class="control-label">Fecha:</label>
						<div class="col-md-4">
							<input id="fecha" name="fecha" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
						</div>
						<input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
						<input id="tabla" name="tabla" type="hidden" value="empleado">
					</div>
					<div class="form-group">
						<label for="cargo" class="control-label">Cargo: </label>
						<select data-placeholder="Seleccione" id="cargo" name="cargo" title="Seleccione" class="chosen-select validate[required]">
							<option value=""></option>
							<option value="adm">Administrador</option>
							<option value="alm">Almacen</option>
							<option value="con">Contador</option>
							<option value="pre">Preventista</option>
						</select>
					</div>


        <div class="idealWrap WrapC">
        <label class="fono">Cargo: </label>
        <select data-placeholder="Seleccione" id="cargo" name="cargo" title="Seleccione" class="chosen-select validate[required]">
        	<option value=""></option>
            <option value="adm">Administrador</option>
            <option value="alm">Almacen</option>
            <option value="con">Contador</option>
            <option value="pre">Preventista</option>
        </select>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap Wrap">
        <input id="codUser" name="codUser" type="text" placeholder="Usuario" value="" class="validate[required,custom[onlyLetterNumber],maxSize[20],ajax[ajaxUserCallPhp]] text-input" />
        </div><!--End idealWrap-->

  		<div class="idealWrap Wrap">
        <input id="password" name="password" type="text" placeholder="Contraseña" value="" />
        <input type="button" id="genera" value="Generar" onclick="generaPass('password');"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap Wrap">
        <input id="name" name="name" type="text" placeholder="Nombre" value="" class="validate[required] text-input" autocomplete="off"  />
        </div><!--End idealWrap-->

        <div class="idealWrap Wrap">
        <input id="paterno" name="paterno" type="text" placeholder="Ap. Paterno" value="" class="validate[required] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap Wrap">
        <input id="materno" name="materno" type="text" placeholder="Ap. Materno" value="" class="validate[required] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapF">
        <input id="ci" name="ci" type="text" placeholder="N° C.I." value="" class="validate[required,custom[integer1],ajax[ajaxCiCallPhp]] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCD">
        <select data-placeholder="Departamento" id="dep" name="dep" title="Seleccione" class="chosen-select validate[required]">
        	<option value=""></option>
            <option value="lp">La Paz</option>
            <option value="cbb">Cochabamba</option>
            <option value="sz">Santa Cruz</option>
            <option value="bn">Beni</option>
            <option value="tr">Tarija</option>
            <option value="pt">Potosi</option>
            <option value="or">Oruro</option>
            <option value="pd">Pando</option>
        </select>
        </div><!--End idealWrap-->

        <div class="idealWrap WrapN">
        <input id="dateNac" name="dateNac" type="text" placeholder="Fecha Nac." value="" class="validate[required,custom[date]] text-input datepicker" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapFo">
        <input id="fono" name="fono" type="text" placeholder="Telefono" value="" class="validate[custom[phone]] text-input" />
        </div><!--End idealWrap-->

        <div class="idealWrap WrapFo">
        <input id="celular" name="celular" type="text" placeholder="Celular" value="" class="validate[required,custom[celular]] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapBS">
        <input id="buscar" name="buscar" type="text" placeholder="Buscar" value="" autocomplete="off"  />
        </div><!--End idealWrap-->
        <div class="idealWrap WrapB">
        <input id="search" name="search" type="button" value="Buscar" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapCR">
        <input id="addresC" name="addresC" type="text" placeholder="Detalle Direcci&oacute;n" value="" class="validate[required] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapCR">
        <input id="cx" name="cx" type="text" placeholder="Coordenada X" value="" readonly class="validate[required] text-input"/>
        </div><!--End idealWrap-->

        <div class="idealWrap WrapCR">
        <input id="cy" name="cy" type="text" placeholder="Coordenada Y" value="" readonly class="validate[required] text-input"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapCR">
        <input id="emailC" name="emailC" type="text" placeholder="Correo Electronico" value="" class="validate[required, custom[email]] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap WrapLabel">
        <label><input id="checksEmail" name="checksEmail" type="checkbox" checked />Enviar datos por E-mail</label>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div id="mapa"></div><!--End mapa-->
        <div class="clearfix"></div>

        <div class="idealWrap">
        <textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="validate[custom[onlyLetterSp]]"></textarea>
        </div><!--End idealWrap-->

        <div class="idealWrap uploadTitle">
          <label class="labelWebcam">
          	<p>Capturar Foto haga clik:</p> <a onclick="openWebCam()" >Aqu&iacute;</a>
          </label>
          <div class="clearfix"></div>
          <label class="labelUpload">
          	<p>Subir Foto haga clik:</p> <a class="openUpload" >Aqu&iacute;</a>
          </label>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>

        <div class="idealWrap uploadShow" style="display:none;">
            <div id="some_file_queue"></div>
            <div id="buttonFile">
            <input type="file" name="file_upload" id="file_upload" />
            <input type="button" id="upload" value="Subir el Archivo" onclick="$('#file_upload').uploadify('upload')"/>
            </div>
            <div class="clearfix"></div>
        </div><!--End idealWrap-->

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Guardar datos</button>
					</div>
				</div>
			</div>
		</div>
</form>

<div class="clearfix"></div>

<div id="camera">
  <span class="tooltip"></span>
  <span class="camTop"></span>

  <div id="screen"></div>
  <div id="buttons">
    <div class="buttonPane">
        <a id="closeButton" onclick="closeWebcam()" class="blueButton">Cerrar</a>
        <a id="shootButton" href="" class="greenButton">Capturar!</a>
    </div>
    <div class="buttonPane hidden">
        <a id="cancelButton" href="" class="blueButton">Cancelar</a>
        <a id="uploadButton" href="" class="greenButton">Subir!</a>
    </div>
  </div>

  <span class="settings"></span>
</div>
