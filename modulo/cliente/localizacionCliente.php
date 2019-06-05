<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$cargo = $_SESSION['cargo'];

/* vaciamos las tablas auxiliares */

$sql = "TRUNCATE TABLE auxImg ";
$strQ = $db->Execute($sql);

$strEmp = "SELECT COUNT(*) FROM cliente WHERE id_empleado = ".$_SESSION['idEmp']." ";
$strNum = $db->Execute($strEmp);
$NumRow = $strNum->FetchRow();
?>
<script type="text/javascript">
    //VARIABLES GENERALES
    //DECLARAS FUERA DEL READY DE JQUERY
    var mapl;
    var markersl       = [];
    var marcadores_bdl = [];
    var mapal          = null; //VARIABLE GENERAL PARA EL MAPAl

    function initMapLoc(){
        /* GOOGLE MAPS */
        //var formulario = $('#frmCliente');
        //COODENADAS INICIALES -16.5207007,-68.1615534
        //VARIABLE PARA EL PUNTO INICIAL
        var punto = new google.maps.LatLng(-16.499299167397574, -68.1646728515625);
        //VARIABLE PARA CONFIGURACION INICIAL
        var config = {
            zoom:10,
            center:punto,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        mapal = new google.maps.Map( $("#mapsLoc")[0], config );
    }

    //FUNCIONES PARA EL GOOGLE MAPS
    function deleteMarkersLoc(lista){
        for(i in lista){
            lista[i].setMap(null);
        }
    }

    function listaCliente(status,zona,fecha){
        //ANTES DE LISTAR MARCADORES
        //SE DEBEN QUITAR LOS ANTERIORES DEL MAPAl
        deleteMarkersLoc(markersl);
        //deleteMarkersLoc(marcadores_bdl);
        var img;
        //var formulario_edicion = $("#formUpdate");
        $.ajax({
            type:"POST",
            url:"inc/listPuntos.php",
            data: {
                status: status,
                zona: zona,
                fecha: fecha,
            },
            dataType:"JSON",
            //data:"&id="+id_empleado,
            success: function(data){
                var c = 0;
                // Add multiple markers to map
                var infoWindow = new google.maps.InfoWindow();
                var contentString = new Array();

                $.each(data, function(i, item){
                    $.each(item, function(j, val){
                        contentString[j] = '<div>'
                         +'<h3>Cliente: '+data.nombre[j]+'</h3>'
                         +'<p>Dirección: <strong>'+data.avenida[j]+'</strong> '+data.nomAve[j]+' </p>'
                         +'<p><strong># </strong>: '+data.num[j]+' </p>'
                         +'<p><strong>'+data.zona[j]+': </strong> '+data.nomZona[j]+' </p>'
                         +'</div>';

                        var infowindow = new google.maps.InfoWindow({
                            content: contentString[j],
                            maxWidth: 300
                        });

                        switch (data.status[j]) {
                            case 'V':
                                img = 'green.png';
                                break;
                            case 'C':
                                img = 'yellow.png';
                                break;
                            default:
                                img = 'red.png';
                        }

                        //alert(item.status[c]+'---'+img);

                        //OBTENER LAS COORDENADAS DEL PUNTO
                        var posi = new google.maps.LatLng(data.cx[c], data.cy[c]);
                        //CARGAR LAS PROPIEDADES AL MARCADOR
                        var marca = new google.maps.Marker({
                            //idMarcador:data.IdPunto,
                            position:posi,
                            icon: 'images/iconos/'+img,
                            //zoom:15,
                            //titulo: data.Titulo,
                            cx:data.cx[c],//esas coordenadas vienen de la BD
                            cy:data.cy[c],//esas coordenadas vienen de la BD
                            draggable: false
                        });
                        // Add info window to marker
                        google.maps.event.addListener(marca, 'click', (function(marca, c) {
                            return function() {
                                infoWindow.setContent(contentString[c]);
                                infoWindow.open(mapal, marca);
                            }
                        })(marca, c));
                        //AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BDl
                        // marcadores_bdl.push(marca);
                        //UBICAR EL MARCADOR EN EL MAPAl
                        markersl.push(marca);
                        marca.setMap(mapal);
                        c++;
                    });
                });
            },
            beforeSend: function(){
            },
            complete: function(){
            }
        });
    }

    jQuery(document).ready(function($) {
        status = $("#status").val();
        zona   = $("#txtZona").val();
        fecha  = $("#cboFecha").val();

        initMapLoc();
        listaCliente(status,zona,fecha);

        $("#status, #txtZona, #cboEmpleado").change(function(){
            status     = $("#status").val();
            zona       = $("#txtZona").val();
            fecha      = $("#cboFecha").val();
            idempleado = $("#cboEmpleado").val();

            listaCliente(status,zona,fecha,idempleado);
        });
    });
</script>
<?php
    include 'newCliente.php';
?>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>CLIENTES</strong></h1>
        <h2 class="avisos">Geolocalizacion de Clientes</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Agregar Cliente</span>
            </button>
        </div>
        <div class="clearfix"></div>

        <div class="col-sm-14">

            <form role="form">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                                <div class="form-group has-success">
                                    <label>Zona:</label>
                                    <input id="txtZona" type="text" maxlength="100" name="txtZona" class="form-control" placeholder="Zona" autofocus="" />
                                </div>
                            </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 left">
                        <div class="form-group has-success">
                            <label>Estado:</label>
                            <select id="status" name="status" class="form-control" required="" >
                                <option value="">Todos</option>
                                <option value="V">Vendidas</option>
                                <option value="N">No Vendidas</option>
                                <option value="C">Cerradas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 left">
                        <div class="form-group has-success">
                            <label for="inputMarca">Fecha :</label>
                            <input id="txtIdSucursal" type="hidden" value="<?php echo $_SESSION["idsucursal"] ?>" maxlength="50" class="form-control" name="txtIdSucursal" required="" placeholder="" autofocus="" />
                            <input id="cboFecha" type="date" maxlength="50" value="<?php echo date("Y-m-d"); ?>"  class="form-control" name="cboFecha" required="" />
                        </div>
                    </div>
                </div>
            </form>
            <br>
            <div id="mapsLoc" class="table-responsive">
                <!-- Listado de articulos por vendedor -->
            </div>
        </div>
    </div>
</div>
<style>
#mapsLoc {
    height: 500px;
    border: 1px #ccc solid;
}
</style>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Procesando...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Iniciar</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancelar</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancelar</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
