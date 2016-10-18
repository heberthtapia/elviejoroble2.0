/**
 * Created by TAPIA on 13/07/2016.
 */

 function verifica(id_F, p){

    var dato = JSON.stringify( $('#'+id_F).serializeObject() );

    $.ajax({
        url: "inc/"+p,
        type: 'post',
        dataType: 'json',
        async:true,
        data:{res:dato},
        beforeSend: function(data){
            $('#error').css('display','block');
            $('#error').html('<div id="loader"><p>Validando...</p></div>');
        },
        success: function(data){
            if(data !== 0){
                $(location).attr('href','admin.php');
            }else{
                $('#alert').css('display','block').fadeIn(5000,function () {
                    $('#alert').fadeOut(4000);
                    $('.btn').delay(7000).val('Ingresar');
                    $('.btn').delay(7000).removeAttr('disabled');

                    $('#login').click();
                    $('#password').val('');
                    $('#username').val('');

                    $('input, select, textarea').filter(':first').focus();
                });
            }
        },
        error: function(data){
            //alert('Error al guardar el formulario');
        }
    });
}

function outSession(user){
    $(location).attr('href','inc/outSession.php?user='+user);
}

function despliega(p, div, id){
    $.ajax({
        url: p,
        type: 'post',
        cache: false,
        data: 'id='+id,
        beforeSend: function(data){
            $("#"+div).html('<div id="load" align="center"><p>Cargando contenido. Por favor, espere ...</p></div>');
        },
        success: function(data){
            $("#"+div).fadeOut(1000,function(){
                $("#"+div).html(data).fadeIn(2000);
            });
            //$("#"+div).html(data);
        }
    });
}

/**
 * GENERA CONTRASEÑA
 */
 function generaPass(id){
    $.ajax({
        url: 'inc/generaPass.php',
        type: 'post',
        cache: false,
        success: function(data){
            //alert();
            $("#"+id).val(data);
        }
    });
}
/**
 *  GUARDA FORMULARIO
 */
 function saveForm(idForm, p){

    var dato = JSON.stringify( $('#'+idForm).serializeObject() );

    $.ajax({
        url: "modulo/"+p,
        type: 'post',
        dataType: 'json',
        async:true,
        data:{res:dato},
        success: function(data){
            //alert(data.checksEmail);
            //parent.$.colorbox.close();
            //ordena(2);
            if(data.tabla === 'cliente'){
                $('#datos_ajax').html('<div class="alert alert-success" role="alert"><strong>Guardado Correctamente!!!</strong></div><br>').fadeIn(4000,function () {
                    $('#datos_ajax').fadeOut(2000,function () {
                        $('#dataRegister').modal('hide').delay(7000);
                        despliega('modulo/cliente/listTabla.php','listTabla');
                    });
                });
            }
            if(data.tabla === 'empleado'){
                $('#datos_ajax').html('<div class="alert alert-success" role="alert"><strong>Guardado Correctamente!!!</strong></div><br>').fadeIn(4000,function () {
                    $('#datos_ajax').fadeOut(2000,function () {
                        $('#dataRegister').modal('hide').delay(7000);
                        despliega('modulo/empleado/listTabla.php','listTabla');
                    });
                });
            }
            if(data.tabla === 'inventario'){
                //fnClickAddRowU(data,true);
                $('#datos_ajax').html('<div class="alert alert-success" role="alert"><strong>Guardado Correctamente!!!</strong></div><br>').fadeIn(4000,function () {
                    $('#datos_ajax').fadeOut(2000,function () {
                        $('#dataRegister').modal('hide').delay(7000);
                        despliega('modulo/almacen/listTabla.php','listTabla');
                    });
                });
            }
            if(data.tabla === 'pedido'){
                //fnClickAddRowInvG(data,true);
                /* CAMBIIO STASTUS CONTADOR */
                $('#modalCheckPedido').modal('hide');
                if(data.OkCont === 0){
                    $('tr#tb'+data.pedido+' td.Pendiente').removeClass('Pendiente').addClass('Aprobado');
                    $('tr#tb'+data.pedido+' td.Aprobado a').text('Aprobado');
                    $('tr#tb'+data.pedido+' td.Aprobado a').removeAttr('data-status1');
                    $('tr#tb'+data.pedido+' td.Aprobado a').attr('data-status1','Aprobado');
                }else{
                    if(data.OkCont === 1){
                        $('tr#tb'+data.pedido+' td.Aprobado').removeClass('Aprobado').addClass('Pendiente');
                        $('tr#tb'+data.pedido+' td.Pendiente a').text('Pendiente');
                        $('tr#tb'+data.pedido+' td.Pendiente a').removeAttr('data-status1');
                        $('tr#tb'+data.pedido+' td.Pendiente a').attr('data-status1','Pendiente');
                    }else{
                        /* CAMBIIO STASTUS ALMACEN */
                        $('#modalCheckAlmacen').modal('hide');
                        if(data.OkAlm === 0){
                            $('tr#tb'+data.pedido+' td.NoEntregado').removeClass('NoEntregado').addClass('Entregado');
                            $('tr#tb'+data.pedido+' td.Entregado a').text('Entregado');
                            detalleAlm(data.pedido);
                        }else{
                            if(data.OkAlm === 1){
                                $('tr#tb'+data.pedido+' td.Entregado').removeClass('Entregado').addClass('NoEntregado');
                                $('tr#tb'+data.pedido+' td.NoEntregado a').text('No Entregado');
                            }else{
                                //despliega('modulo/pedido/pedido.php','contenido');
                            }
                        }
                    }
                }
            }
        },
        error: function(data){
            alert('Error al guardar datos');
        }
    });
}

function updateForm(idForm, p){

    var dato = JSON.stringify( $('#'+idForm).serializeObject() );

    $.ajax({
        url: "modulo/"+p,
        type: 'post',
        dataType: 'json',
        async:false,
        data:{res:dato},
        success: function(data){
            if(data.tabla === 'empleado'){
                $('#datos_ajax_update').html('<div class="alert alert-success" role="alert"><strong>Modificado Correctamente!!!</strong></div><br>').fadeIn(4000,function () {
                    $('#datos_ajax_update').fadeOut(2000,function () {
                        $('#dataUpdate').modal('hide').delay(7000);
                        despliega('modulo/empleado/listTabla.php','listTabla');
                    });
                });
            }
            if(data.tabla === 'inventario'){
                $('#datos_ajax_update').html('<div class="alert alert-success" role="alert"><strong>Modificado Correctamente!!!</strong></div><br>').fadeIn(4000,function () {
                    $('#datos_ajax_update').fadeOut(2000,function () {
                        $('#dataUpdate').modal('hide').delay(7000);
                        despliega('modulo/almacen/listTabla.php','listTabla');
                    });
                });
            }
        },
        error: function(data){
            alert('Error al modificar datos');
        }
    });
}

function fDelete(idForm, p){
    var dato = JSON.stringify( $('#'+idForm).serializeObject() );
    $.ajax({
        url: "modulo/"+p,
        type: 'post',
        dataType: 'json',
        async:false,
        data:{res:dato},
        success: function(data){
            $('#dataDelete').modal('hide');
            despliega('modulo/'+data.tabla+'/listTabla.php','listTabla');
        },
        error: function(data){
            alert('Error al eliminar');
        }
    });
}

function obtenerCoor(id){
    $.ajax({
        url: "modulo/empleado/obtenerCoor.php",
        type: 'post',
        dataType: 'json',
        async:false,
        data:{res:id},
        success: function(data){
            return data;
        },
        error: function(data){
            alert('Error al eliminar');
        }
    });
}

/**
 *  WEB CAM
 * */

 /* RECARGA IMAGEN */

 function recargaImg(img, mod){
    $('#foto').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/'+mod+'/uploads/photos/'+img+'&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
}

function recargaImgU(img, mod){
    $('#fotoU').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/'+mod+'/uploads/photos/'+img+'&amp;w=120&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
}

function closeWebcam(){
    $('#camera').css('display','none');
    $('#save').removeAttr('disabled', 'disabled');
}

function openWebcam(){
    $('#camera').css('display','block');
    $('#save').attr('disabled', 'disabled');
}

function idImg(mod){
    $.ajax({
        url: 'inc/img.php',
        type: 'post',
        cache: false,
        success: function(data){
            recargaImg(data,mod);
            recargaImgU(data,mod);
        }
    });
}

/**
 *  WEB CAM
 */

/**
 * STATUS EMPLEADO
 */
 function statusEmp(id, status){
    $.ajax({
        url: 'inc/statusEmp.php',
        type: 'post',
        async:true,
        data: 'id='+id+'&status='+status,
        success: function(data){

        }
    });
}

/**
 * MODULO PEDIDO
 * AGREGA PEDIDO
 */

 function adicFila(idForm, p){

    var dato = JSON.stringify( $('#'+idForm).serializeObject() );
    //alert(dato);
    $.ajax({
        url: "inc/"+p,
        type: 'post',
        dataType: 'json',
        async:true,
        data:{res:dato},
        success: function(data){
            sw = 0;

            $('#tabla tbody').find('tr').each(function(index, element){
                cod = $(this).attr('id');

                if( cod === data.producto ){
                    cantidad = $('tr#'+data.producto).find('td').eq(1).find('input').val();
                    //alert('******'+cantidad);
                    cantidad = parseInt(cantidad) + parseInt(data.cant);
                    //alert('-------'+cantidad);
                    if( parseFloat(cantidad) <= parseFloat(data.cantI) ){

                      precio = $('tr#'+data.producto).find('td').eq(2).find('input').val();
                      precio = parseFloat(precio) * parseFloat(cantidad);
                      $('tr#'+data.producto).find('td').eq(1).find('input').val(cantidad);
                      //$('tr#'+data.producto).find('td').eq(3).find('input').val(cant);
                      $('tr#'+data.producto).find('td').eq(5).find('input').val(precio.toFixed(2));
                      //$('tr#'+data.producto).find('td').eq(4).find('input').val(precio);

                  }else{
                    alert('Producto sin Stock.....');
                }
                sw = 1;
            }
        });

            if( sw === 0 && data.producto !== undefined ){
                agregarFila(data);
            }
            $('#producto').val('');
            $('#cant').val('');
            subPrecio = 0;
            $('#tabla tbody').find('tr').each(function(index, element){
                subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());
            });
            $('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));

            des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val();
            if(des === '') des = 0;

            bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val();
            if(bon === '') bon = 0;

            total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);
            $('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));

            /**
             * Quitar la validacion
             */
             $('#ventIzq').find('div').removeClass('has-success');
             $('#ventIzq').find('label').removeClass('has-success');
             $('#ventIzq').find('#producto, #cant').removeClass('valid');
            /**
             * Fin
             */
         },
         error: function(data){
            alert('Error al guardar el formulario');
        }
    });
    $('#efectivo').val('');
    $('#cambio').val('');
    $('#codigo').focus();
}

/* EDITA PEDIDO */

function adicFilaEdit(idForm, p){
    "use strict";
    var dato = JSON.stringify( $('#'+idForm).serializeObject() );
    $.ajax({
        url: "inc/"+p,
        type: 'post',
        dataType: 'json',
        async:true,
        data:{res:dato},
        success: function(data){
            sw = 0;
            $('#tabla tbody').find('tr').each(function(index, element){
                cod = $(this).attr('id');
                if( cod === data.producto ){
                    cant = $('tr#'+data.producto).find('td').eq(1).find('input#cantidad').val();
                    cant = parseInt(cant) + parseInt(data.cant);
                    if( parseFloat(cant) <= (parseFloat(data.cantI) + parseInt(data.cantInv)) ){
                      precio = $('tr#'+data.producto).find('td').eq(2).find('input').val();
                      precio = parseFloat(precio) * parseFloat(cant);
                      $('tr#'+data.producto).find('td').eq(1).find('input#cantidad').val(cant);
                      //$('tr#'+data.producto).find('td').eq(3).find('input').val(cant);
                      $('tr#'+data.producto).find('td').eq(5).find('input').val(precio.toFixed(2));
                      //$('tr#'+data.producto).find('td').eq(4).find('input').val(precio);
                  }else{
                    alert('Producto sin Stock.....');
                }
                sw = 1;
            }
        });

            if( sw === 0 && data.producto !== undefined ){
                agregarFila(data);
            }
            $('#producto').val('');
            $('#cant').val('');
            subPrecio = 0;
            $('#tabla tbody').find('tr').each(function(index, element){
                subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());
            });
            $('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));

            des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val();
            if(des === '') des = 0;

            bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val();
            if(bon === '') bon = 0;

            total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);
            $('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));

        },
        error: function(data){
            alert('Error al guardar el formulario');
        }
    });
    $('#efectivo').val('');
    $('#cambio').val('');
    $('#codigo').focus();
}

function agregarFila(data){

  cant = $('input#cant').val();
  //cant = parseInt(cant) + parseInt(data.cant);
  if( parseFloat(cant) <= parseFloat(data.cantI) ){
  // Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
  //if( data.cantidad > 0 ){
      //$('#tabla thead').removeAttr('hidden');
      $('#tabla tfoot').removeAttr('hidden');
      //$('#submitVenta').removeAttr('hidden');
      //alert(data.cant);
      precio = parseFloat(data.cant) * parseFloat(data.precio);

      var strNueva_Fila = '<tr id="'+data.producto+'">'+
      '<td class="det">'+data.producto+' '+data.detalle+''+
      '<input type="hidden" id="item" name="item" value="'+data.producto+'" ></td>'+

      '<td><input type="text" disabled="disabled" id="cantidad" name="cantidad" value="'+data.cant+'" >'+
      '<input type="hidden" id="cantidad" name="cantidad" value="'+data.cant+'" ></td>'+

      '<td><input type="text" disabled="disabled" id="precio" name="precio" value="'+data.precio+'" >'+
      '<input type="hidden" id="precio" name="precio" value="'+data.precio+'" ></td>'+

      '<td></td>'+
      '<td></td>'+
      '<td><input type="text" disabled="disabled" id="subTotal" name="subTotal" value="'+precio.toFixed(2)+'" ></td>'+
      '<td align="right" class="delete"><a class="del" onclick="eliminarFila(&#39;&#39;, &#39;'+0+'&#39;, &#39;'+data.producto+'&#39;)"><i class="fa fa-ban fa-2x" aria-hidden="true"></i></a></td>'+
      '</tr>';

      $("#tabla tbody").append(strNueva_Fila);

      $('#tabla tbody').find('tr').each(function(index, element){
          if( (index % 2) === 0 ){
              $(this).addClass('even');
          }else{
              $(this).addClass('odd');
          }
      });

      $('#tabla tbody').find('tr').each(function(index, element){
        var p = parseFloat($(this).find('td').eq(6).find('input').val());
        $(this).find('td').eq(6).find('input').val(p.toFixed(2));
    });

  }else{

      alert('Producto sin Stock');

  }
}
/**
 * [eliminarFila Elimina los registros de los pedidos]
 * @param  {[type]} idTr [description]
 * @param  {[type]} sw   [description]
 * @return {[type]}      [description]
 */
function eliminarFila(idTr, cant, idInv){

    if( $('#tabla tbody').find('tr').length == 1 ){

        if(confirm('Si elimina el ultimo registro. "SE ELIMINARA TODO EL PEDIDO...!!!"')){
            deleteRowBD('delPedido.php',idTr, 'pedido', 'pedido');
            despliega('modulo/pedido/pedido.php','contenido');
        }

    }else{

        if(confirm('¿Esta seguro que desea ELIMINAR EL REGISTRO...!!!?')){
            $.ajax({
                url: 'modulo/pedido/generaXML.php',
                async: true,
                type: 'post',
                cache: false,
                data: 'id='+idInv+'&cant='+cant,
                success: function(data){
                    $('#'+idInv).remove();

                    subPrecio = 0;
                    $('#tabla tbody').find('tr').each(function(index, element){
                        subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());
                    });

                    $('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));

                    des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val();
                    if(des === '') des = 0;

                    bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val();
                    if(bon === '') bon = 0;

                    total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);
                    $('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));

                    if( total < 0  )
                        $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').css('color','#F7070B');
                    else
                        $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').css('color','#000000');

                    $('#tabla tbody').find('tr').each(function(index, element){
                        if( index % 2 === 0 ){
                            $(this).removeClass('odd');
                            $(this).addClass('even');
                        }else{
                            $(this).removeClass('even');
                            $(this).addClass('odd');
                        }
                    });
                    $('#efectivo').val('');
                    $('#cambio').val('');
                    $('#codigo').focus();

                }
            });
        }

    }

}

/* RECARGA TOTALES DEL EDIT */

function recargaFila(){
    subPrecio = 0;
    $('#tabla tbody').find('tr').each(function(index, element){
        subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());
    });
    $('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));

    des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val();
    if(des === '') des = 0;

    bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val();
    if(bon === '') bon = 0;

    total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);
    $('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));

}

/* GUARDA PEDIDO */

function savePedido(idForm, p){

    if( !confirm('CONFIRMAR PEDIDO!!!')){
        return;
    }
    var dato = JSON.stringify( $('#'+idForm).serializeObject() );
    $.ajax({
        url: "modulo/pedido/"+p,
        type: 'post',
        dataType: 'json',
        async:true,
        data:{res:dato},
        success: function(data){
            despliega('modulo/pedido/pedido.php','contenido');
            window.open('modulo/pedido/pdfPedido.php?res='+dato, '_blank');
        },
        error: function(data){
            alert('Error al guardar el formulario');
        }
    });
}

/**
 * CANCELAR PEDIDO
 */

function cancelarPedido(){
    if(confirm("Seguro que desea eliminar pedido..!!!"))
    despliega('modulo/pedido/pedido.php','contenido');
}

function cancelarPedidoEdit(){
    if(confirm("Si cancela la modificación no se guardara ningun cambio..!!!"))
    despliega('modulo/pedido/pedido.php','contenido');
}

/**
 * [detalle description genera pdf]
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
 function detalle(id){
    window.open('modulo/pedido/pdfPedDet.php?res='+id, '_blank');
}

 function detalleAlm(id){
    window.open('modulo/pedido/pdfPedAlm.php?res='+id, '_blank');
}
/**
 * [selecCampo Recarga camppo producto]
 * @param  {[type]} name [input producto]
 * @return {[type]}      [description]
 */
 function selecCampo( name ){
    $('#producto').val(name);
}
/**
 * [deleteRowBD Elimina un registritro del modulo de pedido]
 * @param  {[type]} p     [pagina]
 * @param  {[type]} idTr  [id a eliminar]
 * @param  {[type]} tipo  [descripticion de modulo]
 * @param  {[type]} table [tabla de BD]
 * @return {[type]}       [Elimina el item]
 */
 function deleteRowBD(p, idTr, tipo, table){
  var resp=0;
  rr = $.ajax({
    url: 'modulo/'+tipo+'/'+p,
    type: 'post',
    async:false,
    data: 'id='+idTr+'&tipo='+tipo+'&table='+table,
    success: function(data){
        if(data!=1)
            alert('No se puede eliminar el ITEM.');
        else
            resp = data;
    },
    error: function(data){
        alert('Error al eliminar el ITEM.');
    }
});
  return resp;
}