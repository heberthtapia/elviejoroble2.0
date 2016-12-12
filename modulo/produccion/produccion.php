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

button.aprob {
    width: 45px;
    height: 36px;
    padding: 5px;

    background-image: url(images/iconos/checkOn.png);
    background-repeat: no-repeat;
    background-position: center;
}
button.aprob:hover img {
    visibility: hidden;

    background: transparent;
}
button.cancel:hover {
    width: 45px;
    height: 36px;
    padding: 5px;

    background-image: url(images/iconos/delOn.png);
    background-repeat: no-repeat;
    background-position: center;
}
button.cancel:hover img {
    visibility: hidden;

    background: transparent;
}
.ui-menu .ui-menu-item a {
    text-transform: uppercase;
}
</style>
<?PHP
include 'newProduccion.php';
include 'importar.php';
include 'editProduccion.php';
include 'delProduccion.php';
?>
<div class="row" id="listTabla">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="avisos" align="center"><strong>PRODUCCIÓN</strong></h1>
        <h2 class="avisos">Ordenes de Produción</h2>
        <div class="pull-right"><br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#dataRegister">
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
                    <td align="center" style="text-transform: uppercase"><?=$row['id_inventario'];?></td>
                    <td align="center" style="text-transform: uppercase"><?=$row['detalle'];?></td>
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
                    <td width="15%">
                         <div class="btn-group" style="width: 271px">

                            <button type="button" class="btn btn-primary btn-sm aprob tooltipp" onClick="sProAprobado('<?=$row[0]?>');" title="Aprobar Orden" >
                                <img src="images/iconos/checkOff.png" width="24"/>
                                <span></span>
                            </button>

                            <button type ="button" class="btn btn-primary btn-sm cancel tooltipp" onClick="sProCancelar('<?=$row[0]?>');" title="Cancelar Orden" >
                                <img src="images/iconos/delOff.png" width="24" alt="Cancelar" />
                                <span></span>
                            </button>

                            <?PHP
                                if( $row[statusProd] == 'Cancelado' || $row[statusProd] == 'Nueva Orden'){
                            ?>

                            <button type ="button" class="btn btn-primary btn-sm terminar tooltipp" onClick="sProTerminado('<?=$row[0]?>');" title="Orden Terminada" disabled>
                                <img src="images/iconos/asig.png" width="24" alt="Orden Terminada" />
                                <span></span>
                            </button>

                            <?PHP
                            }else{
                            ?>

                            <button type ="button" class="btn btn-primary btn-sm terminar tooltipp" onClick="sProTerminado('<?=$row[0]?>');" title="Orden Terminada" >
                                <img src="images/iconos/asig.png" width="24" alt="Orden Terminada" />
                                <span></span>
                            </button>

                            <?PHP
                            }
                                if( $row[statusProd] == 'Terminado'){
                            ?>

                            <button type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataImport" title="Asignar Producción"
                                            data-id="<?=$row['id_produccion']?>"
                                            data-idInv="<?=$row['id_inventario']?>"
                                            data-detalle="<?=$row['detalle']?>"
                                            data-cantidad="<?=$row['cantidad']?>"
                                >
                                <img src="images/iconos/import.png" width="24" alt="Asignar" />
                                <span></span>
                            </button>

                            <?PHP
                            }else{
                            ?>

                            <button id="<?=$row['id_produccion']?>" type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataImport" title="Asignar Orden de Producción" disabled
                                            data-id="<?=$row['id_produccion']?>"
                                            data-idInv="<?=$row['id_inventario']?>"
                                            data-detalle="<?=$row['detalle']?>"
                                            data-cantidad="<?=$row['cantidad']?>"
                                >
                                <img src="images/iconos/import.png" width="24" alt="Asignar" />
                                <span></span>
                            </button>

                            <?PHP
                            }
                            if( $row[statusProd] == 'Nueva Orden'){
                            ?>

                            <button type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataUpdate" title="Editar Orden de Producción"
                                        data-id="<?=$row['id_produccion']?>"
                                        data-idInv="<?=$row['id_inventario']?>"
                                        data-detalle="<?=$row['detalle']?>"
                                        data-cantidad="<?=$row['cantidad']?>"
                            >
                                <img src="images/iconos/edit1.png" width="24" alt="Editar" />
                                <span></span>
                            </button>

                            <button type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataDelete" title="Eliminar Orden de Producción"
                                        data-id="<?=$row['id_produccion']?>"
                            >
                                <img src="images/iconos/recycle.png" width="24" alt="Eliminar" />
                                <span></span>
                            </button>

                            <?PHP
                            }else{
                            ?>

                            <button type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataUpdate" title="Editar Orden de Producción" disabled
                                        data-id="<?=$row['id_produccion']?>"
                                        data-idInv="<?=$row['id_inventario']?>"
                                        data-detalle="<?=$row['detalle']?>"
                                        data-cantidad="<?=$row['cantidad']?>"
                            >
                                <img src="images/iconos/edit1.png" width="24" alt="Editar" />
                                <span></span>
                            </button>

                            <button type ="button" class="btn btn-primary btn-sm import tooltipp" data-toggle="modal" data-target="#dataDelete" title="Eliminar Orden de Producción" disabled
                                        data-id="<?=$row['id_produccion']?>"
                            >
                                <img src="images/iconos/recycle.png" width="24" alt="Eliminar" />
                                <span></span>
                            </button>

                            <?PHP
                            }
                            ?>

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

        $('.tooltipp').tooltipster({
        animation: 'swing',
        delay: 200,
        theme:'tooltipster-shadow'
        });

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


  function sProAprobado(id){
    var val = $('tr#tb'+id).find('td.status1').text();
    $.ajax({
        url: 'modulo/produccion/aprobar.php',
        type: 'post',
        cache: false,
        data:{res:id},
        success: function(data){
                $('tr#tb'+id).find('td.status1').addClass('status2');
                $('tr#tb'+id).find('td.status1').text('En Produccion');
                $('tr#tb'+id).find('td.status1').removeClass('status1');
                //$('button').tooltip('hide');
        }
    });
  }

  function sProCancelar(id){
    var val = $('tr#tb'+id).find('td.status1').text();
    $.ajax({
        url: 'modulo/produccion/cancelar.php',
        type: 'post',
        cache: false,
        data:{res:id},
        success: function(data){
            if(data === "1"){
                $('tr#tb'+id).find('td.status1').addClass('status5');
                $('tr#tb'+id).find('td.status1').text('Cancelado');
                $('tr#tb'+id).find('td.status1').removeClass('status1');
            }else{
                alert('En este momento no se puede cancelar la Orden');
            }
            //$('button').tooltip('hide');
        }
    });
  }

  function sProTerminado(id){
    var val = $('tr#tb'+id).find('td.status2').text();
    $.ajax({
        url: 'modulo/produccion/terminar.php',
        type: 'post',
        cache: false,
        data:{res:id},
        success: function(data){
            var f = new Date();
            var m = f.getMonth()+1;
            var mm = 0;
            if(m<10){
                mm = '0'+m;
            }
            $('tr#tb'+id).find('td.status2').addClass('status3');
            $('tr#tb'+id).find('td.status2').text('Terminado');
            $('tr#tb'+id).find('td.status2').removeClass('status2');
            $('tr#tb'+id).find('td.fin').text(f.getFullYear()+'-'+mm+'-'+f.getDate()+' '+f.getHours()+':'+f.getMinutes()+':'+f.getSeconds());
            $('button#'+id).removeAttr('disabled','disabled');
            window.open('modulo/produccion/pdfOrdenPT.php?res='+id, '_blank');
        }
    });
  }
</script>