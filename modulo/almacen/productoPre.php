<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();
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
    });

    $.validate({
        lang: 'es',
        modules : 'security'
    });

</script>
<?PHP
include 'newProducto.php';
include 'editProducto.php';
include 'delProducto.php';
?>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>ALMACEN</strong></h1>
        <h2 class="avisos">Lista de Productos</h2>

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
                            <div class="col-md-9">
                                <div class="row">
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
                                </div>
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
                            <span>Agregar Empleado</span>
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        </form>

        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Agregar Producto</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Codigo</th>
                <th>Detalle</th>
                <th>Volumen</th>
                <th>Cantidad</th>
                <th>Precio C/F</th>
                <th>Precio S/F</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql	 = "SELECT * ";
            $sql	.= "FROM inventario ";
            $sql	.= "ORDER BY (dateReg) DESC ";

            $cont = 1;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){

                ?>
                <tr id="tb<?=$row[0]?>">
                    <td align="center"><?=$cont++;?></td>
                    <td align="center"><?=$row['dateReg']?></td>
                    <td align="center" style="text-transform: uppercase"><?=$row['id_inventario'];?></td>
                    <td align="center" style="text-transform: uppercase"><?=$row['detalle'];?></td>
                    <td align="center"><?=$row['volumen'];?></td>
                    <td align="center"><?=$row['cantidad'];?></td>
                    <td align="center"><?=$row['precioCF'];?></td>
                    <td align="center"><?=$row['precioSF'];?></td>
                    <td width="15%">
                        <div class="btn-group" style="width: 169px">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataUpdate" data-detalle="<?=$row['detalle']?>" data-idInv="<?=$row['id_inventario']?>" data-cant="<?=$row['cantidad']?>" data-vol="<?=$row['volumen']?>" data-precioCF="<?=$row['precioCF']?>" data-precioSF="<?=$row['precioSF']?>">
                                <i class='glyphicon glyphicon-edit'></i> Modificar
                                </button>

                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dataDelete" data-id="<?=$row['id_inventario']?>"  >
                                    <i class='glyphicon glyphicon-trash'></i> Eliminar
                                </button>
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
                <th>Codigo</th>
                <th>Detalle</th>
                <th>Volumen</th>
                <th>Cantidad</th>
                <th>Precio C/F</th>
                <th>Precio S/F</th>
                <th>Acciones</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
<style>
    .btn_ {
        margin-bottom: 5px;
    }
</style>