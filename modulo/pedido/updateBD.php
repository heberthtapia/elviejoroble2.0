<?php

if(!$xml = simplexml_load_file('libros.xml')){
    echo "No se ha podido cargar el archivo";
} else {
    echo "El archivo se ha cargado correctamente";
}

  crear(); //Creamos el archivo
  leer();  //Luego lo leemos
  modificar();

  //Para crear el archivo
  function crear(){
    $xml = new DomDocument('1.0', 'UTF-8');

    $pedido = $xml->createElement('pedido');
    $pedido = $xml->appendChild($pedido);

    /*$pedidoEliminado = $xml->createElement('pedidoEliminado');
    $pedidoEliminado = $pedido->appendChild($pedidoEliminado);

    // Agregar un atributo al pedidoEliminado
    $pedidoEliminado->setAttribute('seccion', 'eliminado');

    $id = $xml->createElement('id','');
    $id = $pedidoEliminado->appendChild($id);

    $cantidad = $xml->createElement('cantidad','');
    $cantidad = $pedidoEliminado->appendChild($cantidad);*/


    $xml->formatOutput = true;
    $el_xml = $xml->saveXML();
    $xml->save('eliminados.xml');

    //Mostramos el XML puro
    //echo "<p><b>El XML ha sido creado.... Mostrando en texto plano:</b></p>".
     //    htmlentities($el_xml)."<br/><hr>";
  }

  //Para leerlo
  function leer(){
   /* echo "<p><b>Ahora mostrandolo con estilo</b></p>";
    $xml = simplexml_load_file('libros.xml');
    $salida ="";
    foreach($xml->libro as $item){
      $salida .=
        "<b>Autor:</b> " . $item->autor . "<br/>".
        "<b>Título:</b> " . $item->titulo . "<br/>".
        "<b>Ano:</b> " . $item->anio . "<br/>".
        "<b>Editorial:</b> " . $item->editorial . "<br/><hr/>";
    }
    echo $salida;*/
  }



  function modificar(){

    // Nuevo objeto SimpleXMLElement al que se le pasa un archivo xml
   $pedido = new SimpleXMLElement('eliminados.xml', 0, true);

    $pedidoEliminado = $pedido->addChild('pedidoEliminado');

    // Agregar un atributo al pedidoEliminado
    $pedidoEliminado->addAttribute('seccion', 'eliminado');

    $pedidoEliminado->addChild('id','1111111');
    $pedidoEliminado->addChild('cantidad','4545');

    $nuevoXML = $pedido->asXML('eliminados.xml');
    var_dump($nuevoXML); // Devuelve un string con los datos en XML

  }

?>