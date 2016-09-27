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
            //alert(data.tabla);
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
            despliega('modulo/almacen/listTabla.php','listTabla');
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