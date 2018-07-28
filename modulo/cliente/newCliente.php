<?PHP
$fecha = $op->ToDay();
$hora = $op->Time();
?>

<script>
	//VARIABLES GENERALES
	//DECLARAS FUERA DEL READY DE JQUERY
	var map;
	var markers = [];
	var marcadores_bd=[];
	var mapa = null; //VARIABLE GENERAL PARA EL MAPA

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
			formulario.find("input[name='addres']").focus();

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

	$('#dateNac').datetimepicker({
		locale: 'es',
		viewMode: 'years',
		format: 'YYYY-MM-DD'
	});

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

	$('#dataRegister').on('show.bs.modal', function() {
		//Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
		initMap();
		function lastId(){
			$.ajax({
				url: 'inc/lastId.php',
				type: 'post',
				cache: false,
				success: function(data){
					idCod = $('input#codCl').val();
					n = pad(data,2);
					f = idCod+n;
					$('input#codCl').val(f);
					$('input#codCli').val(f);
				}
			});
		}
		lastId();
	});

	$('#dataRegister').on('hidden.bs.modal', function (e) {
		// do something...
		$('#formNew').get(0).reset();
		$('#foto').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/files/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
	});

$(document).ready(function() {
	/* JQUERY FILE UPLOAD */
	//UPLOAD FILES
	'use strict';
	$('#formNew').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'modulo/cliente/uploads/',
        disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
        imageMaxWidth: 1200,
        //imageMaxHeight: 800,
        imageCrop: false, // Force cropped images
        //maxFileSize: 999,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        limitMultiFileUploads: 1,
        maxNumberOfFiles: 1
    });

    $('#formNew').bind('fileuploadcompleted', function (e, data) {
	    $.each(data.files, function (index, file) {
	    //console.log('Added file: ' + file.name);
	      	//saveImg('cliente', file.name, file.size);
            loadImg('cliente', 'auxImg');
	    });
	});

});
</script>
<style type="text/css">
	#codCl{
		text-transform: uppercase;
	}
	.table {
		margin-bottom: 0;
	}
</style>

<form id="formNew" action="javascript:saveForm('formNew','cliente/save.php')" class="" autocomplete="off" >
<div class="modal fade bs-example-modal-lg" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" data-backdrop="static"
   data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="gridSystemModalLabel">Nuevo Cliente <span class="fecha">Fecha: <?=$fecha;?> <?=$hora;?></span></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="datos_ajax"></div>
					</div>
				</div>
				<div class="row">
					<input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
					<input id="tabla" name="tabla" type="hidden" value="cliente">
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-4 form-group">
								<label for="codCl" class="sr-only">Cod. Cliente:</label>

								<input id="codCl" name="codCl" type="text" placeholder="Cod. Cliente" class="form-control" value="<?=$_SESSION['inc'];?>" readonly />

								<input id="codCli" name="codCli" type="hidden" value="<?=$_SESSION['inc']?>"/>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8 form-group">
								<label for="nameEmp" class="sr-only">Nombre Negocio:</label>
								<input id="nameEmp" name="nameEmp" type="text" placeholder="Nombre Negocio" data-validation="required" class="form-control" autocomplete="off" />
							</div>
							<div class="col-md-4 form-group">
								<label for="nit" class="sr-only">NIT:</label>
								<input id="nit" name="nit" type="text" placeholder="NIT" data-validation="required number" class="form-control" autocomplete="off" />
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 form-group">
								<label for="name" class="sr-only">Nombre:</label>
								<input id="name" name="name" type="text" placeholder="Nombre" class="form-control" data-validation="required" autocomplete="off" onBlur="cargaCod()"/>
							</div>
							<div class="col-md-4 form-group">
								<label for="paterno" class="sr-only">Paterno:</label>
								<input id="paterno" name="paterno" type="text" placeholder="Paterno" data-validation="required" class="form-control" onBlur="cargaCod()" />
							</div>
							<div class="col-md-4 form-group">
								<label for="materno" class="sr-only">Materno:</label>
								<input id="materno" name="materno" type="text" placeholder="Materno" class="form-control" />
							</div>
						</div>
					</div>

					<div class="col-md-3" align="center">
						<div id="foto" class="form-group">
							<img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/files/thumbnail/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3 form-group">
						<label for="ci" class="sr-only">N° C.I.:</label>
						<input id="ci" name="ci" type="text" placeholder="N° C.I." class="form-control"
							   data-validation="required number server"
							   data-validation-url="modulo/cliente/validateCI.php"/>
					</div>
					<div class="col-md-3 form-group">
						<label for="dep" class="sr-only">Lugar Exp.:</label>
						<select id="dep" name="dep" class="form-control" data-validation="required">
							<option value="" disabled selected hidden>Lugar Exp.</option>
							<option value="lp">La Paz</option>
							<option value="cbb">Cochabamba</option>
							<option value="sz">Santa Cruz</option>
							<option value="bn">Beni</option>
							<option value="tr">Tarija</option>
							<option value="pt">Potosi</option>
							<option value="or">Oruro</option>
							<option value="pd">Pando</option>
						</select>
					</div>
					<div class="col-md-3 form-group">
						<label for="fono" class="sr-only">Telefono:</label>
						<input id="fono" name="fono" type="text" placeholder="Telefono" class="form-control" data-validation="number" data-validation-optional-if-answered="celular"/>
					</div>
					<div class="col-md-3 form-group">
						<label for="celular" class="sr-only">Celular:</label>
						<input id="celular" name="celular" type="text" placeholder="Celular" class="form-control" data-validation="number" data-validation-optional-if-answered="fono"/>
					</div>
				</div>

				<div class="row">
					<div class="col-md-5 form-group">
						<label for="email" class="sr-only">Correo Electronico:</label>
						<input id="email" name="email" type="text" placeholder="Correo Electronico" value="" class="form-control" data-validation="email"/>
					</div>
					<div class="col-md-3 form-group">
						<label for="calle" class="sr-only">Calle/Avenida/Plaza/Otro (*):</label>
						<select data-validation="required" class="form-control" name="calle">
							<option disabled selected hidden>Calle/Avenida/Plaza/Otro</option>
                          	<option>Avenida</option>
                          	<option>Calle</option>
                          	<option>Carretera</option>
                          	<option>Pasaje</option>
                          	<option>Plaza</option>
                        </select>
					</div>
					<div class="col-md-4 form-group">
						<label for="nom_calle" class="sr-only">Nombre Calle:</label>
						<input id="nom_calle" name="nom_calle" type="text" placeholder="Nombre Calle" class="form-control" data-validation="required"/>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
						<label for="Nro" class="sr-only"></label>
						<input id="Nro" name="Nro" type="text" placeholder="N° de domicilio" class="form-control" data-validation="required number"/>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                        <label for="zona" class="sr-only">Zona/Barrio/Otro (*):</label>
                        <select data-validation="required" class="form-control" name="zona">
                        	<option disabled selected hidden>Zona/Barrio/Otro</option>
                          	<option>Zona</option>
                          	<option>Anillo</option>
                          	<option>Barrio</option>
                          	<option>Comunidad</option>
                          	<option>Localidad</option>
                          	<option>Manzano</option>
                          	<option>Plan</option>
                          	<option>Unidad Vecinal</option>
                          	<option>Urbanización</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
                            <label for="nom_zona" class="sr-only">Nombre Zona/Barrio/Otro (*):</label>
                            <input id="nom_zona" type="text" maxlength="70" name="nom_zona" data-validation="required" placeholder="Nombre Zona/Barrio/Otro" class="form-control" autofocus="" />
                    </div>
				</div>

				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 form-group">
						<label for="departamento" class="sr-only">Departamento:</label>
						<select id="departamento" name="departamento" class="form-control" data-validation="required">
							<option value="" disabled selected hidden>Departamento</option>
							<option value="lp">La Paz</option>
							<option value="cbb">Cochabamba</option>
							<option value="sz">Santa Cruz</option>
							<option value="bn">Beni</option>
							<option value="tr">Tarija</option>
							<option value="pt">Potosi</option>
							<option value="or">Oruro</option>
							<option value="pd">Pando</option>
						</select>
					</div>
					<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 form-group">
                            <label for="direccion" class="sr-only">Nombre Zona/Barrio/Otro (*):</label>
                            <input id="direccion" type="text" maxlength="70" name="direccion" data-validation="required" placeholder="Direción" class="form-control" autofocus="" />
                    </div>
				</div>

				<div class="row">
					<div class="col-md-5" align="center">
						<div id="mapa" class="form-group"></div><!--End mapa-->
					</div>
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-9 form-group">
								<input id="buscar" name="buscar" type="text" placeholder="Buscar en Google Maps" value="" class="form-control" autocomplete="off"/>
							</div>
							<div class="col-md-3  form-group">
								<button type="button" id="search" class="btn btn-primary" >
									<i class="fa fa-search" aria-hidden="true"></i>
									<span>Buscar</span>
								</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 form-group">
								<input id="cx" name="cx" type="text" placeholder="Latitud" value="" readonly class="form-control" data-validation="required"/>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 form-group">
								<input id="cy" name="cy" type="text" placeholder="Longitud" value="" readonly class="form-control" data-validation="required"/>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 form-group">
								<button type="button"  class="btn btn-primary btn-sm" onclick="initMap();" >
									<i class="fa fa-refresh" aria-hidden="true"></i>
									<span>Cargar Mapa</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 form-group">
						<label for="obser" class="sr-only"></label>
						<p id="maxText"><span id="max-length-element">200</span> caracteres restantes</p>
						<textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="form-control"></textarea>
					</div>
				</div>
				<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
		        <div class="row fileupload-buttonbar">
		            <div class="col-md-7">
		                <!-- The fileinput-button span is used to style the file input field as button -->
		                <span class="btn btn-success btn-sm fileinput-button">
		                    <i class="fa fa-folder-open-o" aria-hidden="true"></i>
		                    <span>Examinar...</span>
		                    <input type="file" id="files" name="files[]" multiple>
		                </span>
		                <button type="submit" class="btn btn-primary btn-sm start">
		                    <i class="fa fa-upload"></i>
		                    <span>Iniciar Subida</span>
		                </button>
		                <button type="reset" class="btn btn-warning btn-sm cancel">
		                    <i class="fa fa-ban"></i>
		                    <span>Cancelar</span>
		                </button>
		                <button type="button" class="btn btn-danger btn-sm delete">
		                    <i class="fa fa-trash-o"></i>
		                    <span>Borrar</span>
		                </button>
		                <input type="checkbox" class="toggle">
		                <!-- The global file processing state -->
		                <span class="fileupload-process"></span>
		            </div>
		            <!-- The global progress state -->
		            <div class="col-md-5 fileupload-progress fade">
		                <!-- The global progress bar -->
		                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
		                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
		                </div>
		                <!-- The extended global progress state -->
		                <div class="progress-extended">&nbsp;</div>
		            </div>
		        </div>
		        <div class="file-preview">
		        	<div class="file-drop-zone-title">
		        		Arrastre y suelte aquí los archivos …
		        	</div>
		        	<div class="file-drop-zone">
			        	<!-- The table listing the files available for upload/download -->
			        	<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
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
					<span>Agregar Cliente</span>
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<script>
	function cargaCod(){

	  var cod   = $('#codCli').val();
	  var n     = $('#name').val();
	  var p     = $('#paterno').val();
	  var m     = $('#materno').val();
	  var vr    = cod.substr(0,2);
	  var nvr   = cod.substr(3,4);

	  var c = vr+'-'+n.substr(0,1)+''+p.substr(0,1)+'-'+nvr;

	  $('#codCl').val(c);

	}
</script>
