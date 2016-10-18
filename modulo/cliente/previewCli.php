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
	
	$strSql = "SELECT * FROM cliente ";
	$strSql.= "WHERE id_cliente = '".$id."' ";
	
	$str = $db->Execute($strSql);
	$file = $str->FetchRow();
?>
<!--Web Cam-->
<style>
	#mapa{
		width:350px;
		height:220px;		
		border:1px #CCCCCC solid;
		margin-top:-190px;
		float:right;	
		}
	textarea {
    	height: 4em;
    	overflow: auto;
    	width: 637px;
		cursor: not-allowed;
		}
	input{
		cursor: not-allowed;
	}
	#camera{
		margin: -490px auto auto 382px;
		position:absolute;
	}	
	#ci{
		cursor: not-allowed;
		}
</style>
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
			
	/*google.maps.event.addListener(mapa, "click", function(event){			
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
	});	*/
	listar();		
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
		url:"classes/listaPuntos.php?bd=cliente",
		dataType:"JSON",
		data:"&id=<?=$file['id_cliente'];?>",
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
 
  <form id="form" class="ideal-form" action="javascript:saveForm('form','cliente/update.php')" >
  	<fieldset>
      <legend>V I S T A&nbsp;&nbsp;&nbsp;P R E V I A&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;C L I E N T E</legend>
      
      	<?PHP
		if($file['foto'] != ''){
		?>
        <div id="foto">
        	<img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/photos/<?=$file['foto'];?>&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">        	
        </div>
        <?PHP
		}else{
		?>
        <div id="foto">
        	<img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">        	
        </div>
        <?PHP
		}
		?>
      
        <div class="idealWrap WrapDS">
        <label class="date">Fecha: </label>
        <input id="fecha" name="fecha" type="text" value="<?=$fecha;?> <?=$hora;?>" readonly />
        <input id="tabla" name="tabla" type="hidden" value="cliente" />       
        </div><!--End idealWrap-->
        <div class="clearfix"></div>    
        
        <div class="idealWrap WrapCR">
        <label>Codigo Cliente: </label>
        <input id="codCl" name="codCl" type="text" placeholder="Cod. Cliente" value="<?=$file['id_cliente'];?>" class="validate[required] text-input" autocomplete="off" readonly/>
        </div><!--End idealWrap-->
        
       	<div class="idealWrap WrapD">
        <label>Nombre Negocio: </label>
        <input id="nameEmp" name="nameEmp" type="text" placeholder="Nombre Negocio" value="<?=$file['nombreEmp'];?>" class="validate[required] text-input" autocomplete="off" readonly  />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap Wrap">
        <label>Nombre: </label>
        <input id="name" name="name" type="text" placeholder="Nombre" value="<?=$file['nombre'];?>" class="validate[required] text-input" autocomplete="off" readonly />
        </div><!--End idealWrap-->
        
        <div class="idealWrap Wrap">
        <label>Paterno: </label>
        <input id="paterno" name="paterno" type="text" placeholder="Ap. Paterno" value="<?=$file['apP'];?>" class="validate[required] text-input" readonly />
        </div><!--End idealWrap-->
        
        <div class="idealWrap Wrap">
        <label>Materno: </label>
        <input id="materno" name="materno" type="text" placeholder="Ap. Materno" value="<?=$file['apM'];?>" class="validate[required] text-input" readonly />
        </div><!--End idealWrap-->        
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapF">
        <label>CI: </label>
        <input id="ci" name="ci" type="text" placeholder="N° C.I." value="<?=$file['ci'];?>" readonly />        
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapCD">
        <label>Lugar de Exp.: </label>
        <select data-placeholder="Departamento" id="dep" name="dep" title="Seleccione" class="chosen-select validate[required]" disabled>
        	<option value="<?=$file['depa'];?>"><?=$op->toSelect($file['depa']);?></option>                
        </select>        
        </div><!--End idealWrap-->
        <div class="clearfix"></div>  
        
        <div class="idealWrap WrapFo">
        <label>Telefono: </label>
        <input id="fono" name="fono" type="text" placeholder="Telefono" value="<?=$file['phone'];?>" class="validate[custom[phone]] text-input" readonly />
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapFo">
        <label>Celular: </label>
        <input id="celular" name="celular" type="text" placeholder="Celular" value="<?=$file['celular'];?>" class="validate[required,custom[celular]] text-input" readonly />
        </div><!--End idealWrap-->        
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapD">
        <label>Direcci&oacute;n: </label>
        <input id="addresC" name="addresC" type="text" placeholder="Direcci&oacute;n" value="<?=$file['direccion'];?>" class="validate[required] text-input" readonly />
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapCR">
        <label>Coordenada X: </label>
        <input id="cx" name="cx" type="text" value="<?=$file['coorX'];?>" class="validate[required] text-input" readonly/>
        </div><!--End idealWrap-->
        
        <div class="idealWrap WrapCR">
        <label>Coordenada Y: </label>
        <input id="cy" name="cy" type="text" value="<?=$file['coorY'];?>" class="validate[required] text-input" readonly/>
        </div><!--End idealWrap-->
        <div class="clearfix"></div>
        
        <div class="idealWrap WrapD">
        <label>Correo Electronico: </label>
        <input id="emailC" name="emailC" type="text" placeholder="Correo Electronico" value="<?=$file['email'];?>" class="validate[required, custom[email]] text-input" readonly />
        </div><!--End idealWrap-->
        
        <div id="mapa"></div><!--End mapa--> 
        <div class="clearfix"></div>
        
        <div class="idealWrap">
        <label>Oserbaciones: </label>
        <textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="validate[custom[onlyLetterSp]]" readonly><?=$file['obser'];?></textarea>
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

	<script type="text/javascript">
    
      $(".chosen-select").chosen({
          disable_search_threshold: 10,
          width: "130px"
      });
    
    </script>
	
  </form>
<div class="clearfix"></div>      
<script  src = "https://maps.googleapis.com/maps/api/js?callback=initMap" async  defer ></script> 