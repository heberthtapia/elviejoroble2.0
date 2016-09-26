<?php

/**
**
**  BY iCODEART
**
**********************************************************************
**                      REDES SOCIALES                            ****
**********************************************************************
**                                                                ****
** FACEBOOK: https://www.facebook.com/icodeart                    ****
** TWIITER: https://twitter.com/icodeart                          ****
** YOUTUBE: https://www.youtube.com/c/icodeartdeveloper           ****
** GITHUB: https://github.com/icodeart                            ****
** TELEGRAM: https://telegram.me/icodeart                         ****
** EMAIL: info@icodeart.com                                       ****
**                                                                ****
**********************************************************************
**********************************************************************
**/

    //incluimos nuestro archivo config
    //include 'config.php';

    include '../adodb5/adodb.inc.php';
    include '../inc/function.php';

    $op = new cnFunction();

    $db = NewADOConnection('mysqli');
    //$db->debug = true;
    $db->Connect();

    // Incluimos nuestro archivo de funciones
    //include 'funciones.php';

    // Obtenemos el id del evento
    $id  = $op->evaluar($_GET['id']);

    // y lo buscamos en la base de dato
    //$bd  = $conexion->query("SELECT * FROM eventos WHERE id=$id");
    $bd = $db->Execute("SELECT * FROM eventos WHERE id=$id");

    // Obtenemos los datos
    //$row = $bd->fetch_assoc();
    $row = $bd->FetchRow();

    // titulo
    $titulo=$row['title'];

    // cuerpo
    $evento=$row['body'];

    // Fecha inicio
    $inicio=$row['inicio_normal'];

    // Fecha Termino
    $final=$row['final_normal'];

// Eliminar evento
if (isset($_POST['eliminar_evento']))
{
    $id  = $op->evaluar($_GET['id']);
    $sql = "DELETE FROM eventos WHERE id = $id";
    //if ($conexion->query($sql))
    if($db->Execute($sql))
    {
        echo "Evento eliminado";
    }
    else
    {
        echo "El evento no se pudo eliminar";
    }
}
 ?>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap.css">

	 <h3><?=$titulo?></h3>
	 <hr>
     <b>Fecha inicio:</b> <?=$inicio?>
     <b>Fecha termino:</b> <?=$final?>
     <br>
     <br>
 	<p><?=$evento?></p>
    <br>

<form action="" method="post">
    <button type="submit" class="btn btn-danger" name="eliminar_evento">Eliminar</button>
</form>
