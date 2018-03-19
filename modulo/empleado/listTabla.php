<?php
/**
 * Created by PhpStorm.
 * User: SONY
 * Date: 23/9/2016
 * Time: 16:25
 */
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

        $('input.statusEmp , #checksEmail, #checksEmailU').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            //increaseArea: '100%' // optional
          });

        $('input.statusEmp').on('ifChecked', function(event){
            id = $(this).attr('id');
            statusEmp(id, 'Activo');
        });
        $('input.statusEmp').on('ifUnchecked',function(event){
            id = $(this).attr('id');
            statusEmp(id, 'Inactivo');
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
    $('#obserU').restrictLength( $('#max-length-elementU') );
</script>
</script>
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
                    <td align="center"><?=$cont++;?></td>
                    <td><?=$row['dateReg']?></td>
                    <td align="center" width="10%">
                        <?PHP
                        if( $row['foto'] != '' )
                        {
                            ?>
                            <a href="modulo/empleado/uploads/files/<?=($row['foto']);?>" title="<?=($row['foto']);?>" download="<?=($row['foto']);?>" data-lightbox="lightbox-admin" data-title="Optional caption.">
                                <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/thumbnail/<?=($row['foto']);?>&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                            </a>
                            <?PHP
                        }
                        else{
                            ?>
                            <img class="thumb" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/files/sin_imagen.jpg&amp;w=120&amp;h=80&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
                            <?PHP
                        }
                        ?>
                    </td>
                    <td><?=$row['nombre'];?></td>
                    <td><?=$row['apP'];?></td>
                    <td><?=$row['apM'];?></td>
                    <td><?=$op->toSelect($row['cargo']);?></td>
                    <td width="15%">
                        <div class="btn-group" style="width: 188px">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#dataPreview"
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
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dataDelete" data-id="<?=$row['id_empleado']?>"  ><i class='glyphicon glyphicon-trash'></i> Eliminar
                            </button>

                            <div class="checkbox" id="status<?=$row['id_empleado']?>">
                                    <?PHP
                                    if( $row['statusEmp'] == 'Activo' ){
                                    ?>
                                        <input type="checkbox" class="statusEmp" name="checks" checked id="<?=$row['id_empleado']?>"/>
                                        <label>Status</label>
                                    <?PHP
                                    }else{
                                    ?>
                                        <input type="checkbox" class="statusEmp" name="checks" id="<?=$row['id_empleado']?>"/>
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
