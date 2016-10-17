
<form id="formCheckAlm" action="javascript:saveForm('formCheckAlm','pedido/OkAlm.php')" class="form-horizontal" autocomplete="off" >
	<div class="modal fade" id="modalCheckAlmacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel">Entregar Pedido</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" id="pedido" name="pedido" value=""/>
					<embed id="embedPdf" type="application/pdf" width="100%" height="400px">
				</div>
				<div class="modal-footer">
					<button type="button" id="close" class="btn btn-danger" data-dismiss="modal">
						<i class="fa fa-close" aria-hidden="true"></i>
						<span>Cancelar</span>
					</button>
					<button type="submit" id="saveAlm" class="btn btn-success">
						<i class="fa fa-check" aria-hidden="true"></i>
						<span>Entregar Pedido</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$('#modalCheckAlmacen').on('hidden.bs.modal', function (e) {
		// do something...
		//$('#formNew').get(0).reset();
		//despliega('modulo/almacen/producto.php','contenido');
		if(sw === 1){
			$.ajax({
		        url: "modulo/pedido/verificaStatusAlmacenUn.php",
		        type: 'post',
		        dataType: 'json',
		        async:true,
		        data:{res:idSw},
		        success: function(data){

		        },
		        error: function(data){
		            //alert('Error al guardar el formulario');
		        }
	    	});
		}
	});

	$('#modalCheckAlmacen').on('show.bs.modal', function (event) {

		var button = $(event.relatedTarget); // Botón que activó el modal
		var id = button.data('id'); // Extraer la información de atributos de datos
		var status1 = $('#tb'+id).find('a.status1').text();
		var status2 = $('#tb'+id).find('a.status2').text();
		alt = '';
		sw = 0;

		if( status1 == 'Aprobado' && status2 == 'Entregado' ){
			msj = 'Entregar Pedido';
			alt = 'No puede cancelar el pedido por que ya fue ENTREGADO.';
 		}else if( status1 == 'Aprobado' && status2 != 'Entregado' ){
 			msj = 'Entregar Pedido';
 			$('#saveAlm').removeAttr('disabled','disabled');
 		}else{
 			alt = 'El pedido no fue APROBADO todavía';
 			msj = 'Entregar Pedido';
 			$('#saveAlm').attr('disabled','disabled');
 		}
 		if(alt === ''){
	 		$('#saveAlm').find('span').text(msj);
	 		$('.modal-title').text(msj);
 		}else{
 			$('#saveAlm').find('span').text(msj);
 			$('#saveAlm').attr('disabled','disabled');
	 		$('.modal-title').text(alt);
 		}

		var modal = $(this);
		modal.find('.modal-body').find('embed').attr({
			src: 'modulo/pedido/pdfPedAlm.php?res='+id
		});
		modal.find('.modal-body #pedido').val(id);

		$.ajax({
	        url: "modulo/pedido/verificaStatusAlmacen.php",
	        type: 'post',
	        dataType: 'json',
	        async:true,
	        data:{res:id},
	        success: function(data){
	        	if(data.sw === 1){
	            	sw = 1;
	            	idSw = data.id;
	            	//$('#saveAlm').removeAttr('disabled','disabled');
	        	}else{
	        		$('.modal-title').text('En este momento no puede realizar cambios');
	        		$('#saveAlm').attr('disabled','disabled');
	     	  		sw = 0;
	        	}
	        },
	        error: function(data){
	            //alert('Error al guardar el formulario');
	        }
	    });

	});
</script>