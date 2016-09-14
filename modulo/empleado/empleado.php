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
    } );

</script>
<?PHP

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
            $sql	 = "SELECT * ";
            $sql	.= "FROM empleado ";
            $sql	.= "ORDER BY (dateReg) DESC ";

            $cont = 1;

            $srtQuery = $db->Execute($sql);
            if($srtQuery === false)
                die("failed");

            while( $row = $srtQuery->FetchRow()){

                ?>
                <tr id="tb<?=$row[0]?>">
                    <td class="last center"><?=$cont++;?></td>
                    <td class="last center"><?=$row['dateReg']?></td>
                    <td class="last center" align="center">
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
                    <td width="140px">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-info btn_" data-toggle="modal" data-target="#dataUpdate" data-detalle="<?=$row['detalle']?>" data-idInv="<?=$row['id_inventario']?>" data-cant="<?=$row['cantidad']?>" data-vol="<?=$row['volumen']?>" data-precioCF="<?=$row['precioCF']?>" data-precioSF="<?=$row['precioSF']?>">
                                    <i class='fa fa-external-link'></i> Vista Previa
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-info btn_" data-toggle="modal" data-target="#dataUpdate" data-detalle="<?=$row['detalle']?>" data-idInv="<?=$row['id_inventario']?>" data-cant="<?=$row['cantidad']?>" data-vol="<?=$row['volumen']?>" data-precioCF="<?=$row['precioCF']?>" data-precioSF="<?=$row['precioSF']?>">
                                        <i class='fa fa-pencil-square-o '></i> Modificar
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-danger btn_" data-toggle="modal" data-target="#dataDelete" data-id="<?=$row['id_inventario']?>"  ><i class='glyphicon glyphicon-trash'></i> Eliminar
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Status
                                        </label>
                                    </div>
                                </div>
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
<style>
    .btn_ {
        margin-bottom: 5px;
        font-size: 12px;
    }
</style>