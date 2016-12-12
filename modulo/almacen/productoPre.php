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
                <form id="listaEmp" class="form-horizontal" action="javascript:saveForm('formNew','empleado/save.php')">
                  <div class="form-group">
                    <label for="preventista" class="col-sm-2 col-xs-2 control-label">Preventista</label>
                    <div class="col-sm-4 col-xs-4">
                        <select id="pre" name="pre" class="form-control" data-validation="required">
                        <?PHP
                            $sql = "SELECT * ";
                            $sql.= "FROM empleado ";
                            $sql.= "WHERE cargo = 'pre' ";
                            $sql.= "ORDER BY (apP) ASC ";

                            $srtQuery = $db->Execute($sql);
                            if($srtQuery === false)
                                die("failed");
                            $sw = 0;

                            while( $row = $srtQuery->FetchRow()){
                                if($sw == 0){
                                    $sw = 1;
                                    $idemp = $row[0];
                                }
                        ?>
                            <option value="<?=$row[0]?>"><?=$row[2].' '.$row[3].' '.$row[4]?></option>
                        <?PHP
                        }
                        ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-xs-offset-2 col-sm-10">
                      <button type="button" class="btn btn-default" onclick="listaInv('listaEmp');">Ver Inventario</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <?PHP
            $sqle = "SELECT * ";
            $sqle.= "FROM empleado ";
            $sqle.= "WHERE id_empleado = $idemp ";

            $srtQuery = $db->Execute($sqle);

            $rowe = $srtQuery->FetchRow();
        ?>
        <div id="lista" >
        <h2 class="avisos">Preventista: <?=ucfirst($rowe['nombre']).'&nbsp;'.ucfirst($rowe['apP']).'&nbsp;'.ucfirst($rowe['apM']);?></h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" onclick="window.open('modulo/almacen/pdfInvPre.php?res=<?=$rowe[0];?>', '_blank');">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                <span>Ver PDF</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql	 = "SELECT p.dateReg, p.id_inventario, i.detalle, p.cantidad ";
            $sql	.= "FROM inventarioPre AS p, inventario AS i, empleado AS e ";
            $sql    .= "WHERE p.id_inventario =  i.id_inventario ";
            $sql    .= "AND p.id_empleado = e.id_empleado ";
            $sql    .= "AND p.id_empleado = $idemp ORDER BY(p.id_inventario) ASC";

            $cont = 1;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){

                ?>
                <tr id="tb<?=$row[0]?>">
                    <td align="center"><?=$cont++;?></td>
                    <td align="center"><?=$row[0]?></td>
                    <td align="center" style="text-transform: uppercase"><?=$row[1];?></td>
                    <td align="center" style="text-transform: uppercase"><?=$row[2];?></td>
                    <td align="center"><?=$row[3];?></td>
                    <td width="15%">

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
                <th>Producto</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
            </tfoot>
        </table>

        </div>

    </div>
</div>
<style>
    .btn_ {
        margin-bottom: 5px;
    }
</style>