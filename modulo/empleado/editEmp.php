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
	
	$id = $_REQUEST['id'];
	
	$strSql = "SELECT * FROM empleado AS e, usuario AS u ";
	$strSql.= "WHERE e.id_empleado = u.id_empleado ";
	$strSql.= "AND e.id_empleado = '".$id."' ";
	
	$str = $db->Execute($strSql);
	$file = $str->FetchRow();
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
	#ci{
		cursor: not-allowed;
		}
</style>
<!--Web Cam-->
<script type="text/javascript" src="webcam/webcam.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/chosen.jquery.js"></script>
<script>
  //VARIABLES GENERALES
  //DECLARAS FUERA DEL READY DE JQUERY
  var map;
  var markers = [];
  var marcadores_bd=[];
  var mapa = null; //VARIABLE GENERAL PARA EL MAPA
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
  
  function initMap(){	  
/* GOOGLE MAPS */
	var formulario = $('#form');
	//COODENADAS INICIALES -16.5207007,-68.1615534
	//VARIABLE PARA EL PUNTO INICIAL
	var punto = new google.maps.LatLng(<?=$file['coorX'];?>, <?=$file['coorY'];?>);
	//VARIABLE PARA CONFIGURACION INICIAL
	var config = {
		zoom:15,
		center:punto,
		mapTypeId: google.maps.MapTypeId.ROADMAP
		}

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
		deleteMarkers(marcadores_bd);
		marcador.setMap(mapa);
	});	
	listar();		
  }
  
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
		  
		  $('#form').find("input[name='cx']").val(lista[0]);
		  $('#form').find("input[name='cy']").val(lista[1]);
		  //$('#form').find("input[name='buscar']").val('');
		 	  
		  var marcador = new google.maps.Marker({
			  position: direccion,
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
  //FUERA DE READY DE JQUERY
  //FUNCION PARA RECUPERAR PUNTOS DE LA BD
  function listar(){
	//ANTES DE LISTAR MARCADORES
	//SE DEBEN QUITAR LOS ANTERIORES DEL MAPA	
	deleteMarkers(markers);
	var formulario_edicion = $("#form");
	$.ajax({
		type:"POST",
		url:"classes/listaPuntos.php?bd=empleado",
		dataType:"JSON",
		data:"&id=<?=$file['id_empleado'];?>",
		success: function(data){		
			if(data.coordenada.estado=="ok"){				
				//alert('Hay puntos en la BD');				
				$.each(data, function(i, item){
					//OBTENER LAS COORDENADAS DEL PUNTO					
					var posi = new google.maps.LatLng(item.cx, item.cy);
					//CARGAR LAS PROPIEDADES AL MARCADOR
					var marca = new google.maps.Marker({
						//idMarcador:item.IdPunto,
						position:posi,										
						//zoom:15,
						//titulo: item.Titulo,
						cx:item.cx,//esas coordenadas vienen de la BD
						cy:item.cy,//esas coordenadas vienen de la BD
						draggable: false
					});
					//AGREGAR EVENTO CLICK AL MARCADOR
					//MARCADORES QUE VIENEN DE LA BASE DE DATOS
					google.maps.event.addListener(marca, 'click', function(){
						alert("Hiciste click en "+marca.position + " - " + marca.titulo);
						//ENTRAR EN EL SEGUNDO COLAPSIBLE
						//Y OCULTAR EL PRIMERO
						//$("#collapseTwo").collapse("show");
						//$("#collapseOne").collapse("hide");
						//VER DOCUMENTACION DE BOOTSTRAP
						
						//AHORA PASAR LA INFORMACION DEL MARCADOR
						//AL FORMULARIO
						/*formulario_edicion.find("input[name='id']").val(marca.idMarcador);
						formulario_edicion.find("input[name='titulo']").val(marca.titulo).focus();
						formulario_edicion.find("input[name='cx']").val(marca.cx);
						formulario_edicion.find("input[name='cy']").val(marca.cy);*/
						
					});
					//AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BD
					marcadores_bd.push(marca);
					//UBICAR EL MARCADOR EN EL MAPA
					marca.setMap(mapa);
				});
			}else{
				alert('No hay puntos en la BD');
			}
		},
		beforeSend: function(){
		},
		complete: function(){
		}
	});
  }		
  
</script>
 
  <form id="form" class="ideal-form" action="javascript:saveForm('form','empleado/update.php')" >
  	<fieldset>
      <legend>E D I T A&nbsp;&nbsp;&nbsp;E M P L E A D O</legend>
      
      	<?PHP
		if($file['foto'] != ''){
		?>
        <div id="foto">
        	<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/<?=$file['foto'];?>&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">        	
        </div>
        <?PHP
		}else{
		?>
        <div id="foto">
        	<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">        	
        </div>
        <?PHP
		}
		?>
      
        <div class="idealWrap WrapDS">
        <label class="date">Fecha: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
        <input id="tabla" name="tabla" type="hidden" value="empleado" />       
        </div><!--End idealWrap-->       
        
        <div class="idealWrap WrapC">
        <label class="fono">Cargo: </label>
        <select data-placeholder="Seleccione" id="cargo" name="cargo" title="Seleccione" class="chosen-select validate[required]">
        	<option value="<?=$file['cargo'];?>"><?=$op->toSelect($file['cargo']);?></option>
            <option value="adm">Administrador</option>  
            <option value="alm">Almacen</option>          
            <option value="con">Contador</option>          
            <option value="pre">Preventista</option>                    
        </select>        
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap Wrap">
        <input id="codUser" name="codUser" type="text" placeholder="Usuario" value="<?=$file['user'];?>" class="validate[required,custom[onlyLetterNumber],maxSize[20],ajax[ajaxUserCallPhp]] text-input" />        
        </div><!--End idealWrap-->
  
  		<div class="idealWrap Wrap">
        <input id="password" name="password" type="text" placeholder="Contraseña" value="<?=$file['pass'];?>" />
        <input type="button" id="genera" value="Generar" onclick="generaPass('password');"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap Wrap">
        <input id="name" name="name" type="text" placeholder="Nombre" value="<?=$file['nombre'];?>" class="validate[required] text-input" autocomplete="off"  />
        </div><!--End idealWrap-->
        
        <div class="idealWrap Wrap">
        <input id="paterno" name="paterno" type="text" placeholder="Ap. Paterno" value="<?=$file['apP'];?>" class="validate[required] text-input" />
        </div><!--End idealWrap-->
        
        <div class="idealWrap Wrap">
        <input id="materno" name="materno" type="text" placeholder="Ap. Materno" value="<?=$file['apM'];?>" class="validate[required] text-input" />
        </div><!--End idealWrap-->        
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapF">
        <input id="ci" name="ci" type="text" placeholder="N° C.I." value="<?=$file['id_empleado'];?>" disabled />
        <input id="ci" name="ci" type="hidden" value="<?=$file['id_empleado'];?>" />
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapCD">
        <select data-placeholder="Departamento" id="dep" name="dep" title="Seleccione" class="chosen-select validate[required]" disabled>
        	<option value="<?=$file['depa'];?>"><?=$op->toSelect($file['depa']);?></option>
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
        <input id="dateNac" name="dateNac" type="text" placeholder="Fecha Nac." value="<?=$file['dateNac'];?>" class="validate[required,custom[date]] text-input datepicker" />
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapFo">
        <input id="fono" name="fono" type="text" placeholder="Telefono" value="<?=$file['phone'];?>" class="validate[custom[phone]] text-input" />
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapFo">
        <input id="celular" name="celular" type="text" placeholder="Celular" value="<?=$file['celular'];?>" class="validate[required,custom[celular]] text-input" />
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
        <input id="addresC" name="addresC" type="text" placeholder="Detalle Direcci&oacute;n" value="<?=$file['direccion'];?>" class="validate[required] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapCR">
        <input id="cx" name="cx" type="text" placeholder="Coordenada X" value="<?=$file['coorX'];?>" readonly class="validate[required] text-input"/>
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapCR">
        <input id="cy" name="cy" type="text" placeholder="Coordenada Y" value="<?=$file['coorY'];?>" readonly class="validate[required] text-input"/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>  
        
        <div class="idealWrap WrapD">
        <input id="emailC" name="emailC" type="text" placeholder="Correo Electronico" value="<?=$file['email'];?>" class="validate[required, custom[email]] text-input" />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>        
        
        <div class="idealWrap WrapLabel">
        <label><input id="checksEmail" name="checksEmail" type="checkbox" checked />Enviar datos por E-mail</label>        
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div id="mapa"></div><!--End mapa--> 
        <div class="clearfix"></div>
        
        <div class="idealWrap">
        <textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="validate[custom[onlyLetterSp]]"><?=$file['obser'];?></textarea>
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
        
	</fieldset>
   		<div class="idealWrap" align="center">			
			<input type="reset" id="reset" value="Limpiar..."/>
			<input type="submit" id="save" value="Guardar..."/>
		</div>

<script type="text/javascript">

  $(".chosen-select").chosen({
	  disable_search_threshold: 10,
	  width: "130px"
  });

</script>
	
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
<script  src = "https://maps.googleapis.com/maps/api/js?callback=initMap" async  defer ></script> 