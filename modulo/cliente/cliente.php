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
<script type="text/javascript" language="javascript" class="init">

    $(document).ready(function() {
        $('#tablaList').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ filas por pagina",
                "zeroRecords": "No se encontro nada - Lo siento",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(Filtrada de _MAX_ registros en total)",
                "search":         "Buscar:",
                "paginate": {
                    "first":      "Primero",
                    "last":       "Ultimo",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                }
            },
            "columnDefs": [
                {
                    "targets": [ 1 ],
                    "visible": false,
                    "searchable": false
                }
            ]
        });

        $('input.statusCli').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            //increaseArea: '100%' // optional
          });

        $('input.statusCli').on('ifChecked', function(event){
            id = $(this).attr('id');
            statusCli(id, 'Activo');
        });
        $('input.statusCli').on('ifUnchecked',function(event){
            id = $(this).attr('id');
            statusCli(id, 'Inactivo');
        });
    });
    $.validate({
        lang: 'es',
        modules : 'security, modules/logic'
    });
    $('#obser').restrictLength( $('#max-length-element') );
</script>
<?PHP
include 'newCliente.php';
include 'editCliente.php';
include 'previewCliente.php';
include 'delCliente.php';
?>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>CLIENTES</strong></h1>
        <h2 class="avisos">Lista de Clientes</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Agregar Cliente</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Codigo Cliente</th>
                <th>Foto</th>
                <th>Nombre Negocio</th>
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Dirección</th>
                <th>#</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql = "SELECT * ";
            $sql.= "FROM cliente ";
            if($cargo!='adm'){
                $sql.= "WHERE id_empleado = '".$_SESSION['idEmp']."' ";
            }
            $sql.= "ORDER BY (dateReg) DESC ";

            $cont = 1;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){

                ?>
                <tr id="tb<?=$row[0]?>">
                    <td align="center"><?=$cont++;?></td>
                    <td><?=$row['dateReg']?></td>
                    <td align="center" style="text-transform: uppercase;"><?=$row['id_cliente']?></td>
                    <td align="center" width="10%">
                        <?PHP
                        if( $row['foto'] != '' )
                        {
                            ?>
                        <a href="modulo/cliente/uploads/files/<?=($row['foto']);?>" title="<?=($row['foto']);?>" download="<?=($row['foto']);?>" data-lightbox="lightbox-admin" data-title="Optional caption.">
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/files/<?=($row['foto']);?>&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                        </a>
                            <?PHP
                        }
                        else{
                            ?>
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/cliente/uploads/files/sin_imagen.jpg&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                            <?PHP
                        }
                        ?>
                    </td>
                    <td><?=$row['nombreEmp'];?></td>
                    <td><?=$row['nombre'];?></td>
                    <td><?=$row['apP'];?></td>
                    <td><?=$row['apM'];?></td>
                    <td><?=$row['direccion'];?></td>
                    <td><?=$row['numero'];?></td>
                    <td width="10%">
                        <div class="btn-group" style="width: 191px">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataPreview"
                                    data-foto         ='<?=$row['foto']?>'
                                    data-name         ='<?=$row['nombre']?>'
                                    data-paterno      ='<?=$row['apP']?>'
                                    data-materno      ='<?=$row['apM']?>'
                                    data-id           ='<?=$row['id_cliente']?>'
                                    data-dep          ='<?=$row['depa']?>'
                                    data-nameEmp      ='<?=$row['nombreEmp']?>'
                                    data-nit          ='<?=$row['nit']?>'
                                    data-fono         ='<?=$row['phone']?>'
                                    data-celular      ='<?=$row['celular']?>'
                                    data-calle        ='<?=$row['calle']?>'
                                    data-nom_calle    ='<?=$row['nom_calle']?>'
                                    data-Nro          ='<?=$row['numero']?>'
                                    data-zona         ='<?=$row['zona']?>'
                                    data-nom_zona     ='<?=$row['nom_zona']?>'
                                    data-departamento ='<?=$row['departamento']?>'
                                    data-direccion    ='<?=$row['direccion_des']?>'
                                    data-emailC       ='<?=$row['email']?>'
                                    data-ci           ='<?=$row['ci']?>'
                                    data-cx           ='<?=$row['coorX']?>'
                                    data-cy           ='<?=$row['coorY']?>'
                                    data-obser        ='<?=$row['obser']?>'
                            >
                                <i class='fa fa-external-link'></i>
                                <span>Vista Previa</span>
                            </button>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataUpdate"
                                    data-foto         ='<?=$row['foto']?>'
                                    data-name         ='<?=$row['nombre']?>'
                                    data-paterno      ='<?=$row['apP']?>'
                                    data-materno      ='<?=$row['apM']?>'
                                    data-id           ='<?=$row['id_cliente']?>'
                                    data-dep          ='<?=$row['depa']?>'
                                    data-nameEmp      ='<?=$row['nombreEmp']?>'
                                    data-nit          ='<?=$row['nit']?>'
                                    data-fono         ='<?=$row['phone']?>'
                                    data-celular      ='<?=$row['celular']?>'
                                    data-calle        ='<?=$row['calle']?>'
                                    data-nom_calle    ='<?=$row['nom_calle']?>'
                                    data-Nro          ='<?=$row['numero']?>'
                                    data-zona         ='<?=$row['zona']?>'
                                    data-nom_zona     ='<?=$row['nom_zona']?>'
                                    data-departamento ='<?=$row['departamento']?>'
                                    data-direccion    ='<?=$row['direccion_des']?>'
                                    data-emailC       ='<?=$row['email']?>'
                                    data-ci           ='<?=$row['ci']?>'
                                    data-cx           ='<?=$row['coorX']?>'
                                    data-cy           ='<?=$row['coorY']?>'
                                    data-obser        ='<?=$row['obser']?>'
                            >
                                <i class='fa fa-pencil-square-o '></i>
                                <span>Modificar</span>
                            </button>
                        </div>
                        <div style="width: 177px; margin-top: 5px">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dataDeleteCli" data-id="<?=$row['id_cliente']?>"  ><i class='glyphicon glyphicon-trash'></i> Eliminar
                            </button>

                            <div class="checkbox" id="status<?=$row['id_cliente']?>">
                                    <?PHP
                                    if( $row['status'] == 'Activo' ){
                                    ?>
                                        <input type="checkbox" class="statusCli" name="checks" checked id="<?=$row['id_cliente']?>"/>
                                        <label>Status</label>
                                    <?PHP
                                    }else{
                                    ?>
                                        <input type="checkbox" class="statusCli" name="checks" id="<?=$row['id_cliente']?>"/>
                                        <label>Status</label>
                                    <?PHP
                                    }
                                    ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?PHP
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Codigo Cliente</th>
                <th>Foto</th>
                <th>Nombre Negocio</th>
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Dirección</th>
                <th>#</th>
                <th>Acciones</th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>


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
