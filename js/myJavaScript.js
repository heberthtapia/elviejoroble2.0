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

                $('#error').html('<p>Redireccionando...</p>');

                $(location).attr('href','admin.php');

            }else{
                $('#error').html('<p>Usuario o contrase&ntilde;a no validas</p>');
                clearForm('login');
            }
        },
        error: function(data){
            //alert('Error al guardar el formulario');
        }
    });
}
