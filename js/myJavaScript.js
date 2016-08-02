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