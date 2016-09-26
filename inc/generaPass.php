<?PHP
	session_start();

	//$id_cel = $_SESSION['CEL'];

	include("../adodb5/adodb.inc.php");

	$db = NewADOConnection('mysqli');
	//$db->debug = true;
	$db->Connect("localhost", "root", "mysql", "bd_elviejoroble");

	//Se define una cadena de caractares. Te recomiendo que uses esta.
	//$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
	$cadena = "abcdefghijklmnopqsrtuwxyzABCDEFGHIJKLMNOPQSRTUWXYZ1234567890";

	//Obtenemos la longitud de la cadena de caracteres
	$longitudCadena=strlen($cadena);

	//Se define la variable que va a contener la contraseña
	$pass = "";
	//Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
	$longitudPass=6;
	$sw=0;
	while($sw!=1)
	{

	//Creamos la contraseña
	for($i=1 ; $i<=$longitudPass ; $i++){
		//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		$pos=rand(0,$longitudCadena-1);

		//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		$pass .= substr($cadena,$pos,1);
	}

	$vara="SELECT pass ";
	$vara.= "FROM usuario ";
	$vara.="WHERE pass = '".$pass."' ";
	$sql = $db->Execute($vara);

	echo $num = $sql->RecordCount();
	if($num == 0)
	$sw=1;
	}
	echo $pass;

?>