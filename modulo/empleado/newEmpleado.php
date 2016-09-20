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
        /*margin-top:-177px;*/
        float:right;
    }
    textarea {
        height: 4em;
    }
    #camera{
        margin: -490px auto auto 100px;
        position:absolute;
    }
    div#foto {
        width: 98px;
        /*margin-right: 5px;
        margin-left: 554px;
        position: absolute;*/
    }
    div#foto img {
        border: 1px solid #cccccc;
        padding: 3px;
    }
</style>

<script>
    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var map;
    var markers = [];
    var marcadores_bd=[];
    var mapa = null; //VARIABLE GENERAL PARA EL MAPA

    $('#dataRegister').on('show.bs.modal', function() {
        //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
        initMap();
    });

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

<form id="formNew" action="javascript:saveForm('formNew','empleado/save.php')" class="" autocomplete="off" >
<div class="modal fade bs-example-modal-lg" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Nuevo Empleado</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="datos_ajax"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="fecha" class="sr-only">Fecha:</label>
                        <input id="fecha" name="fecha" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
                        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                        <input id="tabla" name="tabla" type="hidden" value="empleado">
                    </div>
                    <div class="col-md-3 col-md-offset-6" align="center">
                        <div id="foto" class="form-group">
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="name" class="sr-only">Nombre:</label>
                        <input id="name" name="name" type="text" placeholder="Nombre" class="form-control" autocomplete="off"/>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="paterno" class="sr-only">Paterno:</label>
                        <input id="paterno" name="paterno" type="text" placeholder="Paterno" class="form-control" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="materno" class="sr-only">Materno:</label>
                        <input id="materno" name="materno" type="text" placeholder="Materno" value="" class="form-control" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="ci" class="sr-only">N° C.I.:</label>
                        <input id="ci" name="ci" type="text" placeholder="N° C.I." value="" class="form-control validate[required,custom[integer1],ajax[ajaxCiCallPhp]] text-input" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="dep" class="sr-only">Lugar:</label>
                        <select data-placeholder="Departamento" id="dep" name="dep" title="Seleccione" class="form-control chosen-select validate[required]">
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
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="codUser" class="sr-only">Usuario:</label>
                        <input id="codUser" name="codUser" type="text" placeholder="Usuario" class="form-control validate[required,custom[onlyLetterNumber],maxSize[20],ajax[ajaxUserCallPhp]] text-input" />
                    </div>
                    <div class="col-md-2">
                        <label for="password" class="sr-only">Contraseña:</label>
                        <input id="password" name="password" type="text" placeholder="Contraseña" value="" class="form-control"/>
                    </div>
                    <div class="col-md-3">
                        <input type="button" id="genera" value="Generar" onclick="generaPass('password');" class="btn btn-primary"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">.col-md-6 .col-md-offset-3</div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        Level 1: .col-sm-9
                        <div class="row">
                            <div class="col-xs-8 col-sm-6">
                                Level 2: .col-xs-8 .col-sm-6
                            </div>
                            <div class="col-xs-4 col-sm-6">
                                Level 2: .col-xs-4 .col-sm-6
                                <label for="cargo" class="control-label">Cargo: </label>
                                <select id="cargo" name="cargo" class="form-control validate[required]">
                                    <option value=""></option>
                                    <option value="adm">Administrador</option>
                                    <option value="alm">Almacen</option>
                                    <option value="con">Contador</option>
                                    <option value="pre">Preventista</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<form id="formNew___" action="javascript:saveForm('formNew','empleado/save.php')" class="form-inline" autocomplete="off" >
    <div class="modal fade bs-example-modal-lg" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Nuevo Producto</h4>
                </div>
                <div class="modal-body">
                    <div id="datos_ajax"></div>

                    <div id="foto" class="form-group">
                        <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">Nombre:</label>
                        <input id="name" name="name" type="text" placeholder="Nombre" value="" class="form-control" autocomplete="off"  />
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">Paterno:</label>
                        <input id="paterno" name="paterno" type="text" placeholder="Ap. Paterno" value="" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">Materno:</label>
                        <input id="materno" name="materno" type="text" placeholder="Ap. Materno" value="" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label">N° C.I.:</label>
                        <input id="ci" name="ci" type="text" placeholder="N° C.I." value="" class="form-control validate[required,custom[integer1],ajax[ajaxCiCallPhp]] text-input" />
                    </div>

                    <div class="form-group">
                        <label for="fecha" class="control-label">Fecha:</label>
                            <input id="fecha" name="fecha" type="text" class="form-control" value="<?=$fecha;?> <?=$hora;?>" disabled="disabled" />
                        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                        <input id="tabla" name="tabla" type="hidden" value="empleado">
                    </div>
                    <div class="form-group">
                        <label for="cargo" class="control-label">Cargo: </label>
                        <select id="cargo" name="cargo" class="form-control validate[required]">
                            <option value=""></option>
                            <option value="adm">Administrador</option>
                            <option value="alm">Almacen</option>
                            <option value="con">Contador</option>
                            <option value="pre">Preventista</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usuario" class="control-label">Usuario:</label>
                        <input id="codUser" name="codUser" type="text" placeholder="Usuario" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Contraseña:</label>
                        <input id="password" name="password" type="text" placeholder="Contraseña" class="form-control" />
                        <input type="button" id="genera" class="btn btn-primary" value="Generar" onclick="generaPass('password');"/>
                    </div>
                    <div class="form-group">
                        <div id="mapa"></div><!--End mapa-->
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
