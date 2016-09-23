<?PHP
  include("../adodb5/adodb.inc.php");
  
  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();	
  
  $strQuery = "SELECT max(id_img) FROM aux_img";
  
  $strQ = $db->Execute($strQuery);
  
  $id = $strQ->FetchRow();
	
  $sql = "SELECT imagen FROM aux_img WHERE id_img = '".$id[0]."' ";
  
  $strQ = $db->Execute($sql);
  
  $imagen = $strQ->FetchRow();
  
  echo $imagen[0];
   
?>