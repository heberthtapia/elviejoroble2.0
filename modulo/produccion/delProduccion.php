<form id="formDelete" action="javascript:fDelete('formDelete','produccion/deletePro.php')" class="form-horizontal" autocomplete="off" >
    <div class="modal fade" id="dataDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Eliminar Orden de Producción</h4>
                </div>
                <div class="modal-body">
                    <div id="datos_ajax_delete"></div>
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="tabla" name="tabla" value="produccion">
                    <div class="alert alert-warning" role="alert"><p><strong>Advertencia!</strong> está seguro que desea eliminar la ORDEN DE PRODUCCIÓN.</p></div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-close" aria-hidden="true"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" id="save" class="btn btn-success">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                        <span>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#dataDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botón que activó el modal
        var id = button.data('id'); // Extraer la información de atributos de datos
        var modal = $(this);
        modal.find('#id').val(id);
    });
</script>