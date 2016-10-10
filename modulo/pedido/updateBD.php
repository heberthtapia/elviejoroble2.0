<?php
  crear(); //Creamos el archivo
  leer();  //Luego lo leemos
  modificar();

  //Para crear el archivo
  function crear(){
    $xml = new DomDocument('1.0', 'UTF-8');

    $pedido = $xml->createElement('pedido');
    $pedido = $xml->appendChild($pedido);

    $pedidoEliminado = $xml->createElement('pedidoEliminado');
    $pedidoEliminado = $pedido->appendChild($pedidoEliminado);

    // Agregar un atributo al pedidoEliminado
    $pedidoEliminado->setAttribute('seccion', 'eliminado');

    $autor = $xml->createElement('autor','Paulo Coelho');
    $autor = $pedidoEliminado->appendChild($autor);

    $titulo = $xml->createElement('titulo','El Alquimista');
    $titulo = $pedidoEliminado->appendChild($titulo);

    $anio = $xml->createElement('anio','1988');
    $anio = $pedidoEliminado->appendChild($anio);

    $editorial = $xml->createElement('editorial','Maxico D.F. - Editorial Grijalbo');
    $editorial = $pedidoEliminado->appendChild($editorial);

    $xml->formatOutput = true;
    $el_xml = $xml->saveXML();
    $xml->save('libros.xml');

    //Mostramos el XML puro
    echo "<p><b>El XML ha sido creado.... Mostrando en texto plano:</b></p>".
         htmlentities($el_xml)."<br/><hr>";
  }

  //Para leerlo
  function leer(){
    echo "<p><b>Ahora mostrandolo con estilo</b></p>";
    $xml = simplexml_load_file('libros.xml');
    $salida ="";
    foreach($xml->libro as $item){
      $salida .=
        "<b>Autor:</b> " . $item->autor . "<br/>".
        "<b>Título:</b> " . $item->titulo . "<br/>".
        "<b>Ano:</b> " . $item->anio . "<br/>".
        "<b>Editorial:</b> " . $item->editorial . "<br/><hr/>";
    }
    echo $salida;
  }

  function modificar(){

    $xml = simplexml_load_file('libros.xml');


    $character = $peliculas->pelicula[0]->personajes->addChild('personaje');
$character->addChild('nombre', 'Sr. Parser');
$character->addChild('actor', 'John Doe');

$rating = $peliculas->pelicula[0]->addChild('puntuacion', 'Todos los públicos');
$rating->addAttribute('tipo', 'clasificación');
  }
?>