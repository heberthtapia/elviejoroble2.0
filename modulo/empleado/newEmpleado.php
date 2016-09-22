<?PHP
$sql = "TRUNCATE TABLE aux_img ";
$strQ = $db->Execute($sql);
$fecha = $op->ToDay();
$hora = $op->Time();
?>
<style>
    #mapa{
        width:345px;
        height:220px;
        border:1px #CCCCCC solid;
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
    .fecha{
        float: right;
        margin-right: 15px;
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


    $(function () {
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
    });

</script>

<form id="formNew" action="javascript:saveForm('formNew','empleado/save.php')" class="" autocomplete="off" >
<div class="modal fade bs-example-modal-lg" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Nuevo Empleado <span class="fecha">Fecha: <?=$fecha;?> <?=$hora;?></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="datos_ajax"></div>
                    </div>
                </div>
                <div class="row">
                    <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                    <input id="tabla" name="tabla" type="hidden" value="empleado">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="name" class="sr-only">Nombre:</label>
                                <input id="name" name="name" type="text" placeholder="Nombre" class="form-control" autocomplete="off"/>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="paterno" class="sr-only">Paterno:</label>
                                <input id="paterno" name="paterno" type="text" placeholder="Paterno" class="form-control" />
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="materno" class="sr-only">Materno:</label>
                                <input id="materno" name="materno" type="text" placeholder="Materno" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label for="ci" class="sr-only">N° C.I.:</label>
                                <input id="ci" name="ci" type="text" placeholder="N° C.I." value="" class="form-control validate[required,custom[integer1],ajax[ajaxCiCallPhp]] text-input" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="dep" class="sr-only">Lugar Exp.:</label>
                                <select id="dep" name="dep" class="form-control chosen-select validate[required]">
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
                                <label for="dateNac" class="sr-only">Fecha de Nacimiento:</label>
                                <input id="dateNac" name="dateNac" type="text" placeholder="Fecha Nac." class="form-control validate[required,custom[date]] text-input datepicker" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="fono" class="sr-only">Telefono:</label>
                                <input id="fono" name="fono" type="text" placeholder="Telefono" class="form-control validate[custom[phone]] text-input" />
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="celular" class="sr-only">Celular:</label>
                                <input id="celular" name="celular" type="text" placeholder="Celular" class="form-control validate[required,custom[celular]] text-input" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" align="center">
                        <div id="foto" class="form-group">
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/sin_imagen.jpg&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="emailC" class="sr-only">Correo Electronico:</label>
                        <input id="emailC" name="emailC" type="text" placeholder="Correo Electronico" value="" class="form-control validate[required, custom[email]] text-input" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="cargo" class="sr-only">Cargo:</label>
                        <select id="cargo" name="cargo" class="form-control validate[required]">
                            <option value="" disabled selected hidden>Cargo</option>
                            <option value="adm">Administrador</option>
                            <option value="alm">Almacen</option>
                            <option value="con">Contador</option>
                            <option value="pre">Preventista</option>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="codUser" class="sr-only">Usuario:</label>
                        <input id="codUser" name="codUser" type="text" placeholder="Usuario" class="form-control validate[required,custom[onlyLetterNumber],maxSize[20],ajax[ajaxUserCallPhp]] text-input" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="password" class="sr-only">Contraseña:</label>
                        <input id="password" name="password" type="text" placeholder="Contraseña" value="" class="form-control"/>
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="button" id="genera" value="Generar" onclick="generaPass('password');" class="btn btn-primary"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="addresC" class="sr-only"></label>
                        <input id="addresC" name="addresC" type="text" placeholder="Direcci&oacute;n" class="form-control validate[required] text-input" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="Nro" class="sr-only"></label>
                        <input id="Nro" name="Nro" type="text" placeholder="N° de domicilio" class="form-control validate[required] text-input" />
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
                                <input id="search" name="search" type="button" value="Buscar" class="btn btn-primary"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input id="cx" name="cx" type="text" placeholder="Latitud" value="" readonly class="form-control validate[required] text-input"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <input id="cy" name="cy" type="text" placeholder="Longitud" value="" readonly class="form-control validate[required] text-input"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="checkbox">
                                <label><input id="checksEmail" name="checksEmail" type="checkbox" checked/>Enviar datos por E-mail</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="obser" class="sr-only"></label>
                        <textarea id="obser" name="obser" cols="2" placeholder="Observaciones" class="form-control validate[custom[onlyLetterSp]]"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            Capturar Foto
                        </button>
                        <p>Capturar Foto haga clik: <a onclick="openWebCam()" >Aqu&iacute;</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>Subir Foto haga clik: <a class="openUpload" >Aqu&iacute;</a></p>
                    </div>
                </div>
                <!-- Button trigger modal -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</form>

<div id="camera">
    <span class="tooltip"></span>
    <span class="camTop"></span>

    <div id="screen"></div>
    <div id="buttons">
        <div class="buttonPane">
            <a id="closeButton" onclick="closeWebcam()" class="btn btn-danger">Cerrar</a>
            <a id="shootButton" href="" class="btn btn-primary">Capturar!</a>
        </div>
        <div class="buttonPane" style="display: none">
            <a id="cancelButton" href="" class="btn btn-danger">Cancelar</a>
            <a id="uploadButton" href="" class="btn btn-primary">Subir!</a>
        </div>
    </div>

    <span class="settings"></span>
</div>

<style>
    /*----------------------
	Camera slide up
	-----------------------*/

    #camera {
        position: absolute;

        display: none;

        width: 465px;
        height: 330px;
        margin: 0 auto 0 auto;

        border: 1px solid #f0f0f0;
        -webkit-border-radius: 4px 4px 0 0;
        -moz-border-radius: 4px 4px 0 0;
        border-radius: 4px 4px 0 0;
        background: url('../images/cam_bg.jpg') repeat-y;
        -webkit-box-shadow: 0 0 4px rgba(0, 0, 0, .6);
        -moz-box-shadow: 0 0 4px rgba(0, 0, 0, .6);
        box-shadow: 0 0 4px rgba(0, 0, 0, .6);
        z-index: 10000;
    }

    .camTop {
        position: absolute;
        top: 0;
        left: 0;

        width: 100%;
        height: 66px;

        cursor: pointer;

        background: url('../img/cam.png') no-repeat center center;
    }

    .settings {
        position: absolute;
        top: 448px;
        right: 37px;

        width: 30px;
        height: 28px;

        cursor: pointer;

        background: url('../img/settings.png') no-repeat;
    }

    .settings:hover {
        background-position: left bottom;
    }

    #screen {
        width: 450px;
        height: 320px;
        margin: 5px auto 5px;

        text-align: center;

        color: #666;
        background: #ccc;
    }

    .buttonPane {
        margin-top: 10px;

        text-align: center;
    }

    /*.tooltip{
        background:url('../img/tooltip.png') no-repeat;
        position:absolute;
        width:177px;
        height:146px;
        right: 38px;
        top: -140px;
        }*/

    .blueButton,
    .greenButton {
        font-family: 'Century Gothic';
        font-size: 16px;
        line-height: 32px;

        display: inline-block;

        width: 99px;
        height: 38px;
        margin: 0 4px;

        text-align: center;
        text-decoration: none;

        color: #fff !important;
        border: none;
        background: url('../images/buttons.png') no-repeat;
        text-shadow: 1px 1px 1px #277c9b;
    }

    .greenButton {
        background: url('../images/buttons.png') no-repeat right top;
        text-shadow: 1px 1px 1px #498917;
    }

    .blueButton:hover,
    .greenButton:hover {
        text-decoration: none !important;

        background-position: left bottom;
    }

    .greenButton:hover {
        background-position: right bottom;
    }

    .blueButton:active,
    .greenButton:active {
        position: relative;
        bottom: -1px;
    }



    h3 {
        font-family: 'Century Gothic';
    }

    div#load {
        width: 300px;
        margin: -30px auto 0 auto;
        padding: 10px 0;

        border: 2px solid #f0c36d;
        -webkit-border-radius: 0 0 5px 5px;
        -moz-border-radius: 0 0 5px 5px;
        border-radius: 0 0 5px 5px;
        background-color: #f9edbe;
    }

    div#load p {
        font-family: 'Century Gothic';
        font-size: 14px;

        text-align: center;

        color: #000;
    }

    p.rojo {
        font-size: 9px;

        color: #fc070b;
    }
</style>