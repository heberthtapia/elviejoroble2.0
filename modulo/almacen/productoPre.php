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
        <h1 class="avisos" align="center"><strong>INVENTARIO POR PREVENTISTA</strong></h1>
        <h2 class="avisos">Lista de Productos</h2>
        <br>
        <div class="row">
            <div class="col-sm-offset-3">
                <form class="form-horizontal" action="javascript:saveForm('formNew','empleado/save.php')">
                  <div class="form-group">
                    <label for="preventista" class="col-sm-2 col-xs-2 control-label">Preventista</label>
                    <div class="col-sm-4 col-xs-4">
                        <select id="´pre" name="´pre" class="form-control" data-validation="required">
                        <?PHP
                            $sql = "SELECT * ";
                            $sql.= "FROM empleado ";
                            $sql.= "WHERE cargo = 'pre' ";
                            $sql.= "ORDER BY (apP) ASC ";

                            $srtQuery = $db->Execute($sql);
                            if($srtQuery === false)
                                die("failed");

                            while( $row = $srtQuery->FetchRow()){
                        ?>
                            <option value="<?=$row[0]?>"><?=$row[3].' '.$row[4].' '.$row[2]?></option>
                        <?PHP
                        }
                        ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-xs-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-default">Ver Inventario</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
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