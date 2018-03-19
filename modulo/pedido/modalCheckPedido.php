
<form id="formCheck" action="javascript:saveForm('formCheck','pedido/OkCont.php')" class="form-horizontal" autocomplete="off" >
	<div class="modal fade" id="modalCheckPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Aprobar Pedido</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="pedido" name="pedido" value=""/>
					<div class="form-group">
						<label for="factura" class=" col-sm-3 control-label">N&deg; Factura: </label>
						<div class="col-sm-4">
							<input id="factura" name="factura" type="text" class="form-control"/>
						</div>
					</div>
					<embed id="embedPdf" type="application/pdf" width="100%" height="400px">
				</div>
				<div class="modal-footer">
					<button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-close" aria-hidden="true"></i>
						<span>Cancelar</span>
					</button>
					<button type="submit" id="save" class="btn btn-success">
						<i class="fa fa-check" aria-hidden="true"></i>
						<span>Aprobar Pedido</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$('#modalCheckPedido').on('hidden.bs.modal', function (e) {
		// do something...
		$('#formCheck').get(0).reset();
		//despliega('modulo/almacen/producto.php','contenido');
		//if(sw === 1){
		var id = $('#formCheck').find('#pedido').val(); // Extraer la información de atributos de datos
			$.ajax({
		        url: "modulo/pedido/verificaStatusContadorUn.php",
		        type: 'post',
		        dataType: 'json',
		        async:true,
		        data:{res:id},
		        success: function(data){

		        },
		        error: function(data){
		            //alert('Error al guardar el formulario');
		        }
	    	});
		//}
	});

	$('#modalCheckPedido').on('show.bs.modal', function (event) {

		var button = $(event.relatedTarget); // Botón que activó el modal
		var id = button.data('id'); // Extraer la información de atributos de datos
		var status1 = ($('#tb'+id).find('a.status1').text()).trim();
		var status2 = ($('#tb'+id).find('a.status2').text()).trim();
		alt = '';
		sw = 0;

		if( status1 == 'Aprobado' && status2 == 'Entregado' ){
			msj = 'Cancelar Pedido';
			alt = 'No puede cancelar el pedido por que ya fue ENTREGADO.';

 		}else if( status1 == 'Aprobado' && status2 != 'Entregado' ){
 			msj = 'Cancelar Pedido';

 		}else{
 			msj = 'Aprobar Pedido';

 		}
 		if(alt === ''){
	 		$('#save').find('span').text(msj);
	 		$('.modal-title').text(msj);
 		}else{
 			$('#save').find('span').text(msj);
 			$('#save').attr('disabled','disabled');
	 		$('.modal-title').text(alt);
 		}

		var modal = $(this);
		modal.find('.modal-body').find('embed').attr({
			src: 'modulo/pedido/pdfPedDet.php?res='+id
		});
		modal.find('.modal-body #pedido').val(id);

		$.ajax({
	        url: "modulo/pedido/verificaStatusContador.php",
	        type: 'post',
	        dataType: 'json',
	        async:true,
	        data:{res:id},
	        success: function(data){
	        	if(data.sw === 1){
	            	sw = 1;
	            	idSw = data.id;
	            	$('#save').removeAttr('disabled','disabled');
	        	}else{
	        		$('.modal-title').text('En este momento no puede realizar cambios');
	        		$('#save').attr('disabled','disabled');
	     	  		sw = 0;
	        	}
	        	if(data.fac === 'on'){
	        		$('#formCheck').find('#factura').attr('data-validation', 'required number');
	        		$('#formCheck').find('#factura').removeAttr('disabled');
	        	}else{
	        		$('#formCheck').find('#factura').removeAttr('data-validation', 'required number');
	        		$('#formCheck').find('#factura').attr('disabled','disabled');
	        	}
	        },
	        error: function(data){
	            //alert('Error al guardar el formulario');
	        }
	    });

	});
</script>
