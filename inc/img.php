<?PHP
  include("../adodb5/adodb.inc.php");

  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();

  $tabla = $_POST['tabla'];

  $strQuery = "SELECT max(id) FROM ".$tabla."";

  $strQ = $db->Execute($strQuery);

  $id = $strQ->FetchRow();

  $sql = "SELECT name FROM ".$tabla." WHERE id = '".$id[0]."' ";

  $strQ = $db->Execute($sql);

  $imagen = $strQ->FetchRow();

  echo $imagen[0];

?>
