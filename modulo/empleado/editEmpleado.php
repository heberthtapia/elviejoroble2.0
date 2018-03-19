<script>
    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var map;
    var markers = [];
    var marcadores_bd=[];
    var mapa = null; //VARIABLE GENERAL PARA EL MAPA

    function initMapEmp(){
        /* GOOGLE MAPS */
        var formulario = $('#formUpdate');
        //COODENADAS INICIALES -16.5207007,-68.1615534
        //VARIABLE PARA EL PUNTO INICIAL

        var punto = new google.maps.LatLng(coorX, coorY);
        //VARIABLE PARA CONFIGURACION INICIAL
        var config = {
            zoom:15,
            center:punto,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        mapa = new google.maps.Map( $("#mapaU")[0], config );

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
        formulario.find("input[name='cxU']").val(lista[0]);
        formulario.find("input[name='cyU']").val(lista[1]);
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
    listarU();
  }

    //FUNCIONES PARA EL GOOGLE MAPS

    function deleteMarkersU(lista){
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

            $('#formUpdate').find("input[name='cxU']").val(lista[0]);
            $('#formUpdate').find("input[name='cyU']").val(lista[1]);
            //$('#form').find("input[name='buscar']").val('');

            var marcador = new google.maps.Marker({
                position: direccion,
                zoom:15,
                map: mapa, //EN QUE MAPA SE UBICARA EL MARCADOR
                animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
                draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
            });
            markers.push(marcador);
            deleteMarkersU(markers);
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
  function listarU(){
    //ANTES DE LISTAR MARCADORES
    //SE DEBEN QUITAR LOS ANTERIORES DEL MAPA
    deleteMarkers(markers);
    var formulario_edicion = $("#formUpdate");
    $.ajax({
        type:"POST",
        url:"inc/listaPuntos.php?bd=empleado",
        dataType:"JSON",
        data:"&id="+id_empleado,
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

 // BUSCADOR
$('#searchU').on('click', function() {
    // Obtenemos la dirección y la asignamos a una variable
    var address = $('#buscarU').val();
    // Creamos el Objeto Geocoder
    var geocoder = new google.maps.Geocoder();
    // Hacemos la petición indicando la dirección e invocamos la función
    // geocodeResult enviando todo el resultado obtenido
    geocoder.geocode({ 'address': address}, geocodeResult);
});

var coorX;
var coorY;
var id_empleado;

$(document).ready(function(e) {

    $('#dataUpdate').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Botón que activó el modal
        var foto = button.data('foto'); // Extraer la información de atributos de datos
        var nombre = button.data('name'); // Extraer la información de atributos de datos
        var apP = button.data('paterno'); // Extraer la información de atributos de datos
        var apM = button.data('materno'); // Extraer la información de atributos de datos
            id_empleado = button.data('ci'); // Extraer la información de atributos de datos
        var depa = button.data('dep'); // Extraer la información de atributos de datos
        var dateNac = button.data('datenac');
        var phone = button.data('fono');
        var celular = button.data('celular');
        var email = button.data('emailc');
        var cargo = button.data('cargo');
        var user = button.data('coduser');
        var pass = button.data('password');
        var direccion = button.data('addresc');
        var numero = button.data('nro');
            coorX = button.data('cx');
            coorY = button.data('cy');
        var obser = button.data('obser');
        var sucur = button.data('sucur');
        //alert(cargo);

        var modal = $(this);
        //modal.find('.modal-title').text('Modificar Empleado: '+nombre+' '+apP);
        modal.find('.modal-body #nameU').val(nombre);
        modal.find('.modal-body #paternoU').val(apP);
        modal.find('.modal-body #maternoU').val(apM);
        modal.find('.modal-body #ciU').val(id_empleado);
        modal.find('.modal-body #depU').val(depa);
        modal.find('.modal-body #dateNacU').val(dateNac);
        modal.find('.modal-body #fonoU').val(phone);
        modal.find('.modal-body #celularU').val(celular);
        modal.find('.modal-body #emailU').val(email);
        modal.find('.modal-body #cargoU').val(cargo);
        modal.find('.modal-body #codUserU').val(user);
        modal.find('.modal-body #passwordU').val(pass);
        modal.find('.modal-body #addresU').val(direccion);
        modal.find('.modal-body #NroU').val(numero);
        modal.find('.modal-body #cxU').val(coorX);
        modal.find('.modal-body #cyU').val(coorY);
        modal.find('.modal-body #obserU').val(obser);
        modal.find('.modal-body #sucursalU').val(sucur);

        if(foto !== ''){
            modal.find('.modal-body #fotoU').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/'+foto+'&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
        }else {
            modal.find('.modal-body #fotoU').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
        }
        //$('.alert').hide();//Oculto alert
        //
        //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
        initMapEmp();
        'use strict';
        // Initialize the jQuery File Upload widget:
        $('#formUpdate').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: 'modulo/empleado/uploads/?idEmp='+id_empleado,
            disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent),
            imageMaxWidth: 1200,
            //imageMaxHeight: 800,
            imageCrop: false, // Force cropped images
            //maxFileSize: 999,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            limitMultiFileUploads: 1,
            maxNumberOfFiles: 1
        });

        $('#formUpdate').bind('fileuploadcompleted', function (e, data) {
            $.each(data.files, function (index, file) {
                //console.log('Added file: ' + file.name);
                //saveImg('empleado', file.name, file.size);
                loadImg('empleado', 'auxImgEmp');
            });
        })

        // Load existing files:
        $('#formUpdate').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#formUpdate').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#formUpdate')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });
    });

    $('#dataUpdate').on('hidden.bs.modal', function (e) {
        // do something...
        $('#formUpdate').get(0).reset();
        $('#fotoU').html('<img class="thumb" src="thumb/phpThumb.php?src=modulo/empleado/uploads/files/thumbnail/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
        html = '';
        $('#loadImages tbody').html(html);
    });

    $('#dateNacU').datetimepicker({
        locale: 'es',
        viewMode: 'years',
        format: 'YYYY-MM-DD'
    });

});

</script>

<form id="formUpdate" action="javascript:updateForm('formUpdate','empleado/update.php')" class="" autocomplete="off" >
    <div class="modal fade bs-example-modal-lg" id="dataUpdate" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" data-backdrop="static"
   data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Modificar Empleado <span class="fecha">Fecha: <?=$fecha;?> <?=$hora;?></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="datos_ajax_update"></div>
                        </div>
                    </div>
                    <div class="row">
                        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                        <input id="tabla" name="tabla" type="hidden" value="empleado">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="nameU" class="sr-only">Nombre:</label>
                                    <input id="nameU" name="nameU" type="text" placeholder="Nombre" class="form-control" data-validation="required" autocomplete="off"/>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="paternoU" class="sr-only">Paterno:</label>
                                    <input id="paternoU" name="paternoU" type="text" placeholder="Paterno" data-validation="required" class="form-control" />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="maternoU" class="sr-only">Materno:</label>
                                    <input id="maternoU" name="maternoU" type="text" placeholder="Materno" data-validation="required" class="form-control" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="ciU" class="sr-only">N° C.I.:</label>
                                    <input id="ciU" name="ciU" type="text" placeholder="N° C.I." class="form-control" disabled="" />
                                    <input id="ciU" name="ciU" type="hidden" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="depU" class="sr-only">Lugar Exp.:</label>
                                    <select id="depU" name="depU" class="form-control" data-validation="required">
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
                                    <label for="dateNacU" class="sr-only">Fecha de Nacimiento:</label>
                                    <input id="dateNacU" name="dateNacU" type="text" placeholder="Fecha Nac." class="form-control" data-validation="date" data-validation-format="yyyy-mm-dd"/>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="fonoU" class="sr-only">Telefono:</label>
                                    <input id="fonoU" name="fonoU" type="text" placeholder="Telefono" class="form-control" data-validation="number" data-validation-optional-if-answered="celularU"/>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="celularU" class="sr-only">Celular:</label>
                                    <input id="celularU" name="celularU" type="text" placeholder="Celular" class="form-control" data-validation="number" data-validation-optional-if-answered="fonoU"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" align="center">
                            <div id="fotoU" class="form-group"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="emailU" class="sr-only">Correo Electronico:</label>
                            <input id="emailU" name="emailU" type="text" placeholder="Correo Electronico" value="" class="form-control" data-validation="email"/>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="cargoU" class="sr-only">Cargo:</label>
                            <select id="cargoU" name="cargoU" class="form-control" data-validation="required">
                                <option value="" disabled selected hidden>Cargo</option>
                                <option value="adm">Administrador</option>
                                <option value="alm">Almacen</option>
                                <option value="con">Contador</option>
                                <option value="pre">Preventista</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="codUserU" class="sr-only">Usuario:</label>
                            <input id="codUserU" name="codUserU" type="text" placeholder="Usuario" class="form-control" disabled="" />
                            <input id="codUserU" name="codUserU" type="hidden" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="passwordU" class="sr-only">Contraseña:</label>
                            <input id="passwordU" name="passwordU" type="text" placeholder="Contraseña" class="form-control" data-validation="required"/>
                        </div>
                        <div class="col-md-2 form-group">
                            <button type="button" id="genera" class="btn btn-primary" onclick="generaPass('passwordU');">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                                <span>Generar</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="addresU" class="sr-only"></label>
                            <input id="addresU" name="addresU" type="text" placeholder="Direcci&oacute;n" class="form-control" data-validation="required"/>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="NroU" class="sr-only"></label>
                            <input id="NroU" name="NroU" type="text" placeholder="N° de domicilio" class="form-control" data-validation="required number"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5" align="center">
                            <div id="mapaU" class="form-group"></div><!--End mapa-->
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <input id="buscarU" name="buscarU" type="text" placeholder="Buscar en Google Maps" value="" class="form-control" autocomplete="off"/>
                                </div>
                                <div class="col-md-3  form-group">
                                    <button type="button" id="searchU" class="btn btn-primary">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span>Buscar</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input id="cxU" name="cxU" type="text" placeholder="Latitud" value="" readonly class="form-control" data-validation="required"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input id="cyU" name="cyU" type="text" placeholder="Longitud" value="" readonly class="form-control" data-validation="required"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="checkbox">
                                        <input id="checksEmailU" name="checksEmailU" type="checkbox" checked/>
                                        <label>Enviar datos por E-mail</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <button type="button"  class="btn btn-primary btn-sm" onclick="initMapEmp();" >
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
                            <span id="max-length-elementU">200</span><span id="maxTextU"> caracteres restantes</span>
                            <textarea id="obserU" name="obserU" cols="2" placeholder="Observaciones" class="form-control"></textarea>
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
                            <table id="loadImages" role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeU" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" id="saveU" class="btn btn-success">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <span>Modificar Empleado</span>
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
