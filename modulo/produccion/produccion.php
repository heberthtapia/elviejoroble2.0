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
//include 'modalCheckPedido.php';
//include 'modalCheckAlmacen.php';
?>
<style type="text/css">
    .status1{
    background-color:#f58400;
    color:#ffffff;
    font-weight:bold;
}
.status2{
    background-color:#FCFB00;
    color:#000000;
    font-weight:bold;
  }
.status3{
    background-color:#1D5EA3;
    color:#ffffff;
    font-weight:bold;
}
.status4{
    background-color: #8AD120;
    color:#ffffff;
    font-weight:bold;
}
.status5{
    background-color: #AA0000;
    color:#ffffff;
    font-weight:bold;
}
</style>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>PRODUCCIÓN</strong></h1>
        <h2 class="avisos">Ordenes de Produción</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" onClick="despliega('modulo/pedido/newPedido.php','contenido');">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <span>Nueva Orden</span>
            </button>
        </div>
        <div class="clearfix"></div>
        <br>
        <table id="tablaList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nº</th>
                <th>Fecha</th>
                <th>N&deg; de Orden</th>
                <th>Codigo de Producto</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Fecha Inicio Producción</th>
                <th>Fecha Fin Producción</th>
                <th>Status Producción</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?PHP
            $sql = "SELECT * ";
            $sql.= "FROM produccion ";
            $sql.= "ORDER BY (id_produccion) DESC ";

            $cont = 0;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){
                $cont++;
                ?>
                <tr id="tb<?=$row[0]?>">
                    <td align="center"><?=$cont;?></td>
                    <td align="center"><?=$row['dateReg']?></td>
                    <td align="center">OR-P-<?=$row['id_produccion'];?></td>
                    <td align="center"><?=$row['id_inventario'];?></td>
                    <td align="center"><?=$row['detalle'];?></td>
                    <td align="center"><?=$row['cantidad'];?></td>
                    <td align="center"><?=$row['dateInc'];?></td>
                    <td align="center"><?=$row['dateFin'];?></td>
                      <?PHP
                      if(strcmp($row['statusProd'], 'Nueva Orden') == 0){
                          $st="status1";
                      }else{
                          if(strcmp($row['statusProd'], 'En Produccion') == 0){
                            $st="status2";
                          }else {
                              if (strcmp($row['statusProd'], 'Terminado') == 0) {
                                  $st = "status3";
                              } else
                                  if (strcmp($row['statusProd'], 'Terminado y Asignado') == 0) {
                                      $st = "status4";
                                  } else
                                      $st = "status5";
                          }
                      }
                      ?>
                    <td width="10%" class="<?=$st;?>" align="center">
                        <?=$row['statusProd'];?>
                    </td>
                    <td>
                        <div class="accPro">

                      <div class="accion">
                        <a class="tooltip aprob" href="javascript:void(0);" onClick="sProAprobado('<?=$row[0]?>');" title="Aprobar Orden">
                            <img src="images/icono/checkOff.png" width="32"/>
                        </a>
                      </div><!--End accion-->

                      <div class="accion">
                        <a class="tooltip cancel" href="javascript:void(0);" onClick="sProCancelar('<?=$row[0]?>');" title="Cancelar Orden">
                            <img src="images/icono/delOff.png" width="32" alt="Cancelar" />
                        </a>
                      </div><!--End accion-->

                      <div class="accion">
                        <a class="tooltip terminar" href="javascript:void(0);" onClick="sProTerminado('<?=$row[0]?>');" title="Orden Terminada">
                            <img src="images/icono/asig.png" width="32" alt="Orden Terminada" />
                        </a>
                      </div><!--End accion-->

                      <div class="accion">
                        <a class="tooltip import" href="javascript:void(0);" onClick="open_win('modulo/produccion/importar.php', '', '490', '500', '<?=$row['id_produccion']?>');" title="Asignar Producci&oacute;n">
                            <img src="images/icono/import.png" width="32" alt="Asignar Produccion" />
                        </a>
                      </div><!--End accion-->

                      <div class="accion">
                        <a class="tooltip edit" href="javascript:void(0);" onClick="open_win('modulo/produccion/editProduccion.php', '', '600', '270', '<?=$row['id_produccion']?>');" title="Editar Orden">
                            <img src="images/icono/edit1.png" width="32" alt="Editar"/>
                        </a>
                      </div><!--End accion-->

                      <div class="accion">
                        <a class="tooltip del" href="javascript:void(0);" onclick="deleteRow('delProduccion.php', '<?=$row['id_produccion']?>', 'produccion','produccion');" title="Eliminar Orden" >
                            <img src="images/icono/recycle.png" width="32" height="32" alt="Eliminar"/>
                        </a>
                      </div><!--End accion-->
                      <div class="cleafix"></div>

                    </div><!--End accPro-->

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
                <th>N&deg; de Orden</th>
                <th>Codigo de Producto</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Fecha Inicio Producción</th>
                <th>Fecha Fin Producción</th>
                <th>Status Producción</th>
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