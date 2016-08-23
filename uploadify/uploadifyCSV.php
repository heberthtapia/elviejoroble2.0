<?php

include '../classes/class.connection.php';
	
$operations = new DBConnection();

/* Nombre de la imagen */

$nameImg = $_POST['name'];

$fecha = $operations->ToDay();    
$hora = $operations->TimeC();

/*$fecha = str_replace('-','',$fecha);
$hora = str_replace('-','',$hora);
$hora = str_replace(':','',$hora);*/

/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = $_POST['path']; // Relative to the root
//echo $ad = $_REQUEST['directorio'];
if (!empty($_FILES)) {
	
	//echo $nom = $_POST['someKey'];
	
	//$name = $_FILES['Filedata']['name'];
	$name = md5($_SERVER['REMOTE_ADDR'].rand()).'.csv';
	//$name = $nameImg.'-'.$fecha.$hora.'.jpg';
	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetPath = '../modulo/'.$targetFolder.'/uploads';
	$targetFile = rtrim($targetPath,'/') . '/' . $name;//$_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('csv'); // File extensions
	$fileParts = pathinfo($name);//$_FILES['Filedata']['name']);
	
	//$name = $_FILES['Filedata']['name'];
		 
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		
		/*REGISTRO DE VENTAS*/

		$idS = $_REQUEST['idS'];
	
		$fila = 1;
		
		if (($gestor = fopen("../modulo/csv/uploads/".$name."", "r")) !== FALSE) {
		
			while (($row = fgetcsv($gestor, 1000, ",")) !== FALSE) {
				
				$c = 0;
			
				if( $fila != 1 ){
				
				foreach($row as $dato) {
					
					$data = explode(';',$dato);
					
					if( $data[0] != NULL ){
						
					/* INSERTA o MODIFICA CLIENTE */
		
					$strC = "SELECT id_cliente FROM cliente WHERE id_cliente = ".$data[3]." ";
					$sqlC = $operations->Query($strC);
					$numC = $operations->numQuery($sqlC);
					
					if( $numC == 0 ){
					
					$strCli = "INSERT INTO cliente (id_cliente, id_sucursal, nombre, apellido_pat, apellido_mat, status )";
					$strCli.= "VALUES ('".$data[3]."', '".$idS."', '".$data[6]."', '".$data[4]."', '".$data[5]."', 'Activo') ";
					
					$sqlCli = $operations->Query($strCli);
					
					}else{
						
					$strCli = "UPDATE cliente SET id_sucursal = '".$idS."', nombre = '".$data[6]."', ";
					$strCli.= "apellido_pat = '".$data[4]."', apellido_mat = '".$data[5]."' ";
					$strCli.= "WHERE id_cliente = '".$data[3]."' ";
					
					$sqlCli = $operations->Query($strCli);
						
					}
					
					
					/* REGISTRA VENTA */	
					$strQuery = "INSERT INTO venta (nit, dateReg, id_empleado, subTotal, descuento, total, efectivo, cambio, cajero )";
					$strQuery.= "VALUES (".$data[3].", '".$fecha." ".$hora."', '".$data[2]."', '".$data[10]."', '".$data[11]."', ";
					$strQuery.= "'".$data[12]."', '0', '0', '".$_REQUEST['idE']."'  )";
					
					$sql = $operations->Query($strQuery);
					
					$id_venta = $operations->lastIDTable('venta');
					
					//$data->idVenta = $id_venta;
					
					$codB = explode('-',$data[7]);
						
					$j = count($codB);
					
					$cand = explode('-',$data[8]);
					$precio = explode('-',$data[9]);
					
					
					for($k = 0;$k < $j;$k++){
					  
						$str = "SELECT id_producto FROM producto WHERE codBarras = '".$codB[$k]."' ";
						$strSql = $operations->Query($str);
						$reg = $operations->rstQuery($strSql);
						
						$strQuery = "INSERT INTO sucurventa (id_sucursal, id_venta, id_producto, cantidad, precio ) ";
						$strQuery.= "VALUES (".$idS.", '".$id_venta."', '".$reg[0]."', '".$cand[$k]."', '".$precio[$k]."' )";	  
						$sql = $operations->Query($strQuery);
						
						$sqlCant = "SELECT cantidad FROM invsucursal WHERE codBarras = '".$codB[$k]."' ";
						$sqlCant.= "AND id_sucursal = ".$idS." ";
						$sqlReg = $operations->Query($sqlCant);
						$regCant = $operations->rstQuery($sqlReg);
						
						$cantidad = $regCant[0] - $cand[$k];
						
						$strInv = "UPDATE invsucursal SET cantidad = '".$cantidad."' WHERE codBarras = '".$codB[$k]."' ";
						$strInv.= "AND id_sucursal = ".$idS." ";
						$sqlInv = $operations->Query($strInv);
						
						
						  
					  };
					  
						//$lastCaja = $operations->lastIDTable('caja');
						$sqlLast = "SELECT max(id_caja) FROM caja WHERE id_sucursal = '".$idS."'";
						$strLast = $operations->Query($sqlLast);
						$lastCaja = $operations->rstQuery($strLast);
						
						$strC = "SELECT caja FROM caja WHERE id_caja = ".$lastCaja[0]."";
						$sqlStrC = $operations->Query($strC);
						$regC = $operations->rstQuery($sqlStrC);
						
						$caja = $regC[0] + $data[12];
						
						$strCaja = "UPDATE caja SET caja = '".$caja."' WHERE id_caja = '".$lastCaja[0]."' ";
						$sqlCaja = $operations->Query($strCaja);

					}
					
				}
				
				}
				$fila++;			
			}		
			fclose($gestor);	
		}	
		
		/*FIN DE REGISTRO*/
		
		echo ($name);
	} else {
		echo 'Invalid file type.';
	}
}
?>