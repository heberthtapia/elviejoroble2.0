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
            if(data != 0){
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
/*
GUARDA FORMULARIO
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
            //alert(data.tabla);
            if(data.tabla === 'empleado'){
                //fnClickAddRow(data,true);
                despliega('modulo/empleado/listEmpleado.php','contenido');
            }
            if(data.tabla === 'inventario'){
                //fnClickAddRowU(data,true);
                //despliega('modulo/producto/listProducto.php','contenido');
            }
            if(data.tabla === 'pedido'){
                //fnClickAddRowInvG(data,true);
                /* CAMBIIO STASTUS CONTADOR */

                if(data.OkCont === 0){
                    $('tr#tb'+data.pedido+' td.Pendiente').removeClass('Pendiente').addClass('Aprobado');
                    $('tr#tb'+data.pedido+' td.Aprobado a').text('APROBADO');
                }else{
                    if(data.OkCont === 1){
                        $('tr#tb'+data.pedido+' td.Aprobado').removeClass('Aprobado').addClass('Pendiente');
                        $('tr#tb'+data.pedido+' td.Pendiente a').text('PENDIENTE');
                    }else{
                        /* CAMBIIO STASTUS ALMACEN */
                        if(data.OkAlm === 0){
                            $('tr#tb'+data.pedido+' td.NoEntregado').removeClass('NoEntregado').addClass('Entregado');
                            $('tr#tb'+data.pedido+' td.Entregado a').text('ENTREGADO');
                        }else{
                            if(data.OkAlm === 1){
                                $('tr#tb'+data.pedido+' td.Entregado').removeClass('Entregado').addClass('NoEntregado');
                                $('tr#tb'+data.pedido+' td.NoEntregado a').text('NO ENTREGADO');
                            }else{
                                despliega('modulo/pedido/listPedido.php','contenido');
                            }
                        }
                    }
                }
            }
            if(data.tabla === 'cliente'){
                despliega('modulo/cliente/listCliente.php','contenido');
            }
        },
        error: function(data){
            alert('Error al guardar el formulario');
        }
    });
}