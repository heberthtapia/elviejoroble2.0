<?PHP
  include '../../adodb5/adodb.inc.php';
  include '../../classes/function.php';
  
  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();	
  
  $op = new cnFunction();
  
  $fecha = $op->ToDay();    
  $hora = $op->Time();
  
  $table	= $_POST['table'];	
  $id		= $_POST['id'];
  $tipo		= $_POST['tipo'];
  
	  $q = "DELETE FROM pedidoEmp WHERE id_".$table." = '".$id."' ";
	  $reg = $db->Execute($q);
  
  if($reg){		  
	  $q = "DELETE FROM ".$table." WHERE id_".$table." = '".$id."' ";
	  $reg = $db->Execute($q);
  }
  if($reg)
	  echo 1;
  else
	  echo 0;		
?>