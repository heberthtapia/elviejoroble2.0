<?PHP
  include '../../adodb5/adodb.inc.php';
  include '../../inc/function.php';

  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();

  $op = new cnFunction();

  $fecha = $op->ToDay();
  $hora = $op->Time();

  $table	= $_POST['table'];
  $id		= $_POST['id'];
  $tipo		= $_POST['tipo'];

  $strQuery = "SELECT * FROM pedidoEmp WHERE id_pedido = '".$id."' ";
  $strQuery = $db->Execute($strQuery);

    while ( $row = $strQuery->FetchRow() ) {

      $strCant = "SELECT cantidad FROM inventario WHERE id_inventario = '".$row['id_inventario']."'";
      $strCant = $db->Execute($strCant);
      $cant = $strCant->FetchRow();
      $cantidad = $cant[0] + $row['cantidad'];

      $strInv = "UPDATE inventario SET cantidad = '".$cantidad."' WHERE id_inventario = '".$row['id_inventario']."' ";

      $sqlInv = $db->Execute($strInv);
    }

    if($sqlInv){

  	  $q = "DELETE FROM pedidoEmp WHERE id_pedido = '".$id."' ";
  	  $reg = $db->Execute($q);

    }

    if($reg){

      $q = "DELETE FROM pedido WHERE id_pedido = '".$id."' ";
      $reg = $db->Execute($q);

	    echo 1;
    }else
  	  echo 0;
?>