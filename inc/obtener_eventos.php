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

// Incluimos nuestro archivo config
//include 'config.php';

include '../adodb5/adodb.inc.php';
include '../inc/function.php';

$op = new cnFunction();

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

// Sentencia sql para traer los eventos desde la base de datos
$sql="SELECT * FROM eventos"; 

// Verificamos si existe un dato
//if ($conexion->query($sql)->num_rows)
$result = $db->Execute($sql);

$max = $result->RecordCount();

//$num = mysql_num_rows($r);

if($max > 0)
{ 

    // creamos un array
    $datos = array(); 

    //guardamos en un array multidimensional todos los datos de la consulta
    $i=0; 

    // Ejecutamos nuestra sentencia sql
    //$e = $conexion->query($sql);
    $e = $db->Execute($sql);

    //while($row=$e->fetch_array()) // realizamos un ciclo while para traer los eventos encontrados en la base de dato
    while ( $row = $e->FetchRow())
    {
        // Alimentamos el array con los datos de los eventos
        $datos[$i] = $row; 
        $i++;
    }

    // Transformamos los datos encontrado en la BD al formato JSON
        echo json_encode(
                array(
                    "success" => 1,
                    "result" => $datos
                )
            );

    }
    else
    {
        // Si no existen eventos mostramos este mensaje.
        echo "No hay datos"; 
    }

?>
