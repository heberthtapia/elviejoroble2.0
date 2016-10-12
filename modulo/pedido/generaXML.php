<?PHP
	include '../../adodb5/adodb.inc.php';
	include '../../inc/function.php';

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect();

	$op = new cnFunction();

	$fecha = $op->ToDay();
	$hora = $op->Time();

	$id = $_REQUEST['id'];

	$cant = $_REQUEST['cant'];


	$pedido = new SimpleXMLElement('eliminados.xml', 0, true);

    $pedidoEliminado = $pedido->addChild('pedidoEliminado');

    // Agregar un atributo al pedidoEliminado
    $pedidoEliminado->addAttribute('seccion', 'eliminado');

    $pedidoEliminado->addChild('id',$id);
    $pedidoEliminado->addChild('cantidad',$cant);

    $nuevoXML = $pedido->asXML('eliminados.xml');
    var_dump($nuevoXML); // Devuelve un string con los datos en XML

?>