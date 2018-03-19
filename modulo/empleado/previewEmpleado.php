<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 25/09/2016
 * Time: 0:56
 */
$fecha = $op->ToDay();
$hora = $op->Time();

$id = $_REQUEST['id'];

$strSql = "SELECT * FROM empleado AS e, usuario AS u ";
$strSql.= "WHERE e.id_empleado = u.id_empleado ";
$strSql.= "AND e.id_empleado = '".$id."' ";

$str = $db->Execute($strSql);
$file = $str->FetchRow();
?>

<script>

var coorX;
var coorY;
var id_empleado;

$(document).ready(function(e) {

    $('#dataPreview').on('show.bs.modal', function (event) {
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

        var modal = $(this);
        //modal.find('.modal-title').text('Modificar Empleado: '+nombre+' '+apP);
        modal.find('.modal-body #nameP').val(nombre);
        modal.find('.modal-body #paternoP').val(apP);
        modal.find('.modal-body #maternoP').val(apM);
        modal.find('.modal-body #ciP').val(id_empleado);
        modal.find('.modal-body #depP').val(depa);
        modal.find('.modal-body #dateNacP').val(dateNac);
        modal.find('.modal-body #fonoP').val(phone);
        modal.find('.modal-body #celularP').val(celular);
        modal.find('.modal-body #emailP').val(email);
        modal.find('.modal-body #cargoP').val(cargo);
        modal.find('.modal-body #codUserP').val(user);
        modal.find('.modal-body #passwordP').val(pass);
        modal.find('.modal-body #addresP').val(direccion);
        modal.find('.modal-body #NroP').val(numero);
        modal.find('.modal-body #cxP').val(coorX);
        modal.find('.modal-body #cyP').val(coorY);
        modal.find('.modal-body #obserP').val(obser);

        if(foto !== ''){
            modal.find('.modal-body #fotoP').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/'+foto+'&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
        }else {
            modal.find('.modal-body #fotoP').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
        }
        //$('.alert').hide();//Oculto alert
        initMapU();
    });

    $('#dataPreview').on('hidden.bs.modal', function (e) {
        // do something...
        $('#formPreview').get(0).reset();
        $('#fotoP').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/sin_imagen.jpg&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
    });

    $('#dateNacP').datetimepicker({
        locale: 'es',
        viewMode: 'years',
        format: 'YYYY-MM-DD'
    });

});

    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var map;
    var markers = [];
    var marcadores_bd=[];
    var mapa = null; //VARIABLE GENERAL PARA EL MAPA

    function initMapU(){
        /* GOOGLE MAPS */
        var formulario = $('#formPreview');
        //COODENADAS INICIALES -16.5207007,-68.1615534
        //VARIABLE PARA EL PUNTO INICIAL
        var punto = new google.maps.LatLng(coorX, coorY);
        //VARIABLE PARA CONFIGURACION INICIAL
        var config = {
            zoom:15,
            center:punto,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        mapa = new google.maps.Map( $("#mapaP")[0], config );


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

            $('#formPreview').find("input[name='cxP']").val(lista[0]);
            $('#formPreview').find("input[name='cyP']").val(lista[1]);
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
    var formulario_edicion = $("#formPreview");
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
</script>

<form id="formPreview" action="javascript:updateForm('formPreview','empleado/update.php')" class="" autocomplete="off" >
    <div class="modal fade bs-example-modal-lg" id="dataPreview" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" data-backdrop="static"
   data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Vista Previa Empleado <span class="fecha">Fecha: <?=$fecha;?> <?=$hora;?></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="datos_ajax_update"></div>
                        </div>
                    </div>
                    <div class="row">
                        <input id="date" name="date" type="hidden" value="<?=$fecha;?> <?=$hora;?>" />
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="nameP" class="sr-only">Nombre:</label>
                                    <input id="nameP" name="nameP" type="text" placeholder="Nombre" class="form-control" disabled="" />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="paternoP" class="sr-only">Paterno:</label>
                                    <input id="paternoP" name="paternoP" type="text" placeholder="Paterno" class="form-control" disabled="" />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="maternoP" class="sr-only">Materno:</label>
                                    <input id="maternoP" name="maternoP" type="text" placeholder="Materno" class="form-control" disabled="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="ciP" class="sr-only">N° C.I.:</label>
                                    <input id="ciP" name="ciP" type="text" placeholder="N° C.I." class="form-control" disabled="" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="depP" class="sr-only">Lugar Exp.:</label>
                                    <select id="depP" name="depP" class="form-control" disabled="">
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
                                    <label for="dateNacP" class="sr-only">Fecha de Nacimiento:</label>
                                    <input id="dateNacP" name="dateNacP" type="text" placeholder="Fecha Nac." class="form-control" disabled=""/>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="fonoP" class="sr-only">Telefono:</label>
                                    <input id="fonoP" name="fonoP" type="text" placeholder="Telefono" class="form-control" disabled=""/>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="celularP" class="sr-only">Celular:</label>
                                    <input id="celularP" name="celularP" type="text" placeholder="Celular" class="form-control" disabled=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" align="center">
                            <div id="fotoP" class="form-group"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="emailP" class="sr-only">Correo Electronico:</label>
                            <input id="emailP" name="emailP" type="text" placeholder="Correo Electronico" value="" class="form-control" disabled=""/>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="cargoP" class="sr-only">Cargo:</label>
                            <select id="cargoP" name="cargoP" class="form-control" disabled="">
                                <option value="" disabled selected hidden>Cargo</option>
                                <option value="adm">Administrador</option>
                                <option value="alm">Almacen</option>
                                <option value="con">Contador</option>
                                <option value="pre">Preventista</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="codUserP" class="sr-only">Usuario:</label>
                            <input id="codUserP" name="codUserP" type="text" placeholder="Usuario" class="form-control" disabled="" />
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="passwordP" class="sr-only">Contraseña:</label>
                            <input id="passwordP" name="passwordP" type="text" placeholder="Contraseña" class="form-control" disabled=""/>
                        </div>
                        <div class="col-md-2 form-group">
                        	<button type="button" id="genera" class="btn btn-primary" onclick="generaPass('passwordU');" disabled="">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                                <span>Generar</span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label for="addresP" class="sr-only"></label>
                            <input id="addresP" name="addresP" type="text" placeholder="Direcci&oacute;n" class="form-control" disabled=""/>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="NroP" class="sr-only"></label>
                            <input id="NroP" name="NroP" type="text" placeholder="N° de domicilio" class="form-control" disabled=""/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5" align="center">
                            <div id="mapaP" class="form-group"></div><!--End mapa-->
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <input id="buscarU" name="buscarU" type="text" placeholder="Buscar en Google Maps" value="" class="form-control" autocomplete="off"  disabled=""/>
                                </div>
                                <div class="col-md-3  form-group">
                                    <button type="button" id="searchU" class="btn btn-primary" disabled="">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span>Buscar</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input id="cxP" name="cxP" type="text" placeholder="Latitud" class="form-control" disabled=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input id="cyP" name="cyP" type="text" placeholder="Longitud" class="form-control" disabled=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <div class="checkbox">
                                        <input id="checksEmailU" name="checksEmailU" type="checkbox" checked disabled="" />
                                        <label>Enviar datos por E-mail</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <button type="button"  class="btn btn-primary btn-sm" onclick="initMapU();" >
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
                            <span id="max-length-element">200</span><span id="maxText"> caracteres restantes</span>
                            <textarea id="obserP" name="obserP" cols="2" placeholder="Observaciones" class="form-control" disabled=""></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="closeU" class="btn btn-success" data-dismiss="modal">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        <span>Cerrar</span>
                    </button>
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
