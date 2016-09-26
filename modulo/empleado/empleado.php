<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();
?>
<script type="text/javascript" src="webcam/webcam.js"></script>
<script type="text/javascript" src="js/script.js"></script>
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
    });
    $.validate({
        lang: 'es',
        modules : 'security, modules/logic'
    });
    $('#obser').restrictLength( $('#max-length-element') );
</script>
<?PHP
include 'newEmpleado.php';
include 'editEmpleado.php';
?>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>EMPLEADOS</strong></h1>
        <h2 class="avisos">Lista de Empleados</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Agregar Empleado</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Cargo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql = "SELECT * ";
            $sql.= "FROM empleado AS e, usuario AS u ";
            $sql.= "WHERE e.id_empleado = u.id_empleado ";
            $sql.= "ORDER BY (e.dateReg) DESC ";

            $cont = 1;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){

                ?>
                <tr id="tb<?=$row[0]?>">
                    <td class="last center"><?=$cont++;?></td>
                    <td class="last center"><?=$row['dateReg']?></td>
                    <td class="last center" align="center" width="10%">
                        <?PHP
                        if( $row['foto'] != '' )
                        {
                            ?>
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/photos/<?=($row['foto']);?>&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">

                            <?PHP
                        }
                        else{
                            ?>
                            <img class="thumb" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                            <?PHP
                        }
                        ?>
                    </td>
                    <td class="last center"><?=$row['nombre'];?></td>
                    <td class="last center"><?=$row['apP'];?></td>
                    <td class="last center"><?=$row['apM'];?></td>
                    <td class="last center"><?=$op->toSelect($row['cargo']);?></td>
                    <td width="15%">
                        <div class="btn-group" style="width: 188px">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataUpdate" data-detalle="<?=$row['detalle']?>" data-idInv="<?=$row['id_inventario']?>" data-cant="<?=$row['cantidad']?>" data-vol="<?=$row['volumen']?>" data-precioCF="<?=$row['precioCF']?>" data-precioSF="<?=$row['precioSF']?>">
                                    <i class='fa fa-external-link'></i> Vista Previa
                                    </button>

                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataUpdate"
                                            data-foto="<?=$row['foto']?>"
                                            data-name="<?=$row['nombre']?>"
                                            data-paterno="<?=$row['apP']?>"
                                            data-materno="<?=$row['apM']?>"
                                            data-ci="<?=$row['id_empleado']?>"
                                            data-dep="<?=$row['depa']?>"
                                            data-dateNac="<?=$row['dateNac']?>"
                                            data-fono="<?=$row['phone']?>"
                                            data-celular="<?=$row['celular']?>"
                                            data-emailC="<?=$row['email']?>"
                                            data-cargo="<?=$row['cargo']?>"
                                            data-codUser="<?=$row['user']?>"
                                            data-password="<?=$row['pass']?>"
                                            data-addresC="<?=$row['direccion']?>"
                                            data-Nro="<?=$row['numero']?>"
                                            data-cx="<?=$row['coorX']?>"
                                            data-cy="<?=$row['coorY']?>"
                                            data-obser="<?=$row['obser']?>"
                                    >
                                        <i class='fa fa-pencil-square-o '></i>
                                        <span>Modificar</span>
                                    </button>
                        </div>
                        <div style="width: 188px; margin-top: 5px">
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dataDelete" data-id="<?=$row['id_inventario']?>"  ><i class='glyphicon glyphicon-trash'></i> Eliminar
                                    </button>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Status
                                        </label>
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
                <th>Foto</th>
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>Cargo</th>
                <th>Acciones</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIG-WEdvtbElIhE06jzL5Kk1QkFWCvymQ" async  defer></script>