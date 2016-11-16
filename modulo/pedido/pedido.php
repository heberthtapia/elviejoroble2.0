<?PHP
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$op = new cnFunction();

$idEmp = $_SESSION['idEmp'];
$cargo = $_SESSION['cargo'];
?>

<?PHP
include 'modalCheckPedido.php';
include 'modalCheckAlmacen.php';
?>

<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>PEDIDOS</strong></h1>
        <h2 class="avisos">Lista de Pedidos</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" onClick="despliega('modulo/pedido/newPedido.php','contenido');">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Nuevo Pedido</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>N&deg; pedido</th>
                <th>SubTotal</th>
                <th>Des.</th>
                <th>Bonf.</th>
                <th>Total</th>
                <th>a cuenta</th>
                <th>saldo</th>
                <th>Observaciones</th>
                <th>Tipo de Pago</th>
                <th>Status Contador</th>
                <th>Status Almacen</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql = "SELECT * ";
            $sql.= "FROM pedido AS p, empleado AS e ";
            $sql.= "WHERE p.id_empleado = e.id_empleado ";
            if($cargo!='adm'){
                $sql.= "AND p.id_empleado = ".$idEmp." ";
            }
            $sql.= "ORDER BY (p.dateReg) DESC ";

            $cont = 0;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){
                $cont++;
                ?>
                <tr id="tb<?=$row[0]?>">
                    <td class="last center"><?=$cont;?></td>
                    <td class="last center"><?=$row['dateReg']?></td>
                    <td class="last center">PD-<?=$op->ceros($row['id_pedido'],5);?></td>
                    <td class="last center"><?=$row['subTotal'];?></td>
                    <td class="last center"><?=$row['descuento'];?></td>
                    <td class="last center"><?=$row['bonificacion'];?></td>
                    <td class="last center"><?=$row['total'];?></td>
                    <td class="last center"><?=$row['aCuenta'];?></td>
                    <td class="last center"><?=$row['saldo'];?></td>
                    <td class="last center"><?=$row['obs'];?></td>
                    <td class="last center">
                    <?PHP
                      if( $row['tipo']=='con' )
                        echo 'Al Contado';
                      else
                        echo 'Al Credito';
                    ?>
                    </td>
                    <td class="last center <?=$row['status1'];?>">

                        <a class="status1" data-toggle="modal" data-target="#modalCheckPedido" data-id="<?=$row['id_pedido']?>" data-status1 = "<?=$row['status1'];?>" data-status2 = "<?=$row['status2'];?>" ><?=$row['status1'];?></a></td>

                    <td class="last center <?=str_replace(' ', '', $row['status2']);?>">

                        <a class="status2" data-toggle="modal" data-target="#modalCheckAlmacen" data-id="<?=$row['id_pedido']?>" data-status1 = "<?=$row['status1'];?>" data-status2 = "<?=$row['status2'];?>"><?=$row['status2'];?></a></td>

                    <td width="15%">
                        <div class="btn-group" style="width: 194px">
                            <button type="button" class="btn btn-primary btn-sm" onclick="detalle('<?=$row['id_pedido'];?>')" >
                                <i class='fa fa-external-link'></i>
                                <span>Generar PDF</span>
                            </button>

                            <button type ="button" class="btn btn-primary btn-sm" onclick="despliega('modulo/pedido/editPedido.php','contenido','<?=$row['id_pedido']?>');" >
                                <i class='fa fa-pencil-square-o '></i>
                                <span>Modificar</span>
                            </button>
                        </div>
                        <div style="width: 188px; margin-top: 5px">
                            <button type="button" class="btn btn-danger btn-sm" onclick="javascript:deleteRow('delPedido.php','<?=$row['id_pedido']?>', 'pedido', 'pedido');">
                            <i class='glyphicon glyphicon-trash'></i>
                            <span>Eliminar</span>
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
                <th>N&deg; pedido</th>
                <th>SubTotal</th>
                <th>Des.</th>
                <th>Bonf.</th>
                <th>Total</th>
                <th>a cuenta</th>
                <th>saldo</th>
                <th>Observaciones</th>
                <th>Tipo de Pago</th>
                <th>Status Contador</th>
                <th>Status Almacen</th>
                <th>Acciones</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>

<script type="text/javascript" language="javascript" class="init">

    //========DataTables========

    $(document).ready(function() {
    deleteRow = function(p, idTr, tipo, table){
        var respuesta = confirm("SEGURO QUE DESEA ELIMINAR EL "+" ' "+tipo.toUpperCase()+" ' ");
        if(respuesta){
            $('#tb'+idTr).remove();
            deleteRowBD(p, idTr, tipo, table);
        }
    };
    /* Init the table */
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

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            //increaseArea: '100%' // optional
          });

        $('input').on('ifChecked', function(event){
            id = $(this).attr('id');
            statusEmp(id, 'Activo');
        });
        $('input').on('ifUnchecked',function(event){
            id = $(this).attr('id');
            statusEmp(id, 'Inactivo');
        });
    });
    $.validate({
        lang: 'es',
        modules : 'security, modules/logic'
    });
    $('#obser').restrictLength( $('#max-length-element') );
</script>