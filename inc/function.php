<?php
session_start();

class cnFunction{

	public function toSelect($op){
        switch( $op ){
			case "adm": $select = 'Administrador';
            break;
      		case "alm": $select = 'Almacen';
            break;
         	case "con": $select = 'Contador';
            break;
         	case "pre": $select = 'Preventista';
            break;
			case "lp": $select = 'La Paz';
            break;
			case "cbb": $select = 'Cochabamba';
            break;
			case "sz": $select = 'Santa Cruz';
            break;
			case "bn": $select = 'Beni';
            break;
			case "tr": $select = 'Tarija';
            break;
			case "pt": $select = 'Potosi';
            break;
			case "or": $select = 'Oruro';
            break;
			case "pd": $select = 'Pando';
            break;
			case "chu": $select = 'Chuquisaca';
            break;
        }
   		return $select;
    }

	public function ToMes(){
		date_default_timezone_set("America/La_Paz" ) ;
		return date( 'm' );
		}
	public function ToAno(){
		date_default_timezone_set("America/La_Paz" ) ;
		return date( 'Y' );
		}
	public function ToDay(){
		date_default_timezone_set("America/La_Paz" ) ;
		return date( 'Y-m-d' );
		}
	public function Time(){
		$hora = time();
		$hora = getdate(time());
		$h = $hora["hours"];
		$m = $hora["minutes"];

		if($h < 10)
			$h = "0".$h;
		if($m < 10)
			$m = "0".$m;

		$hora = ($h . ":" . $m . ":" . $hora["seconds"] );
		return ($hora);
		}
	public function TimeC(){
		$hora = time();
		$hora = getdate(time());
		$h = $hora["hours"];
		$m = $hora["minutes"];
		$s = $hora["seconds"];

		if($h < 10)
			$h = "0".$h;
		if($m < 10)
			$m = "0".$m;

		$hora = ($h . ":" . $m . ":" . $s );
		return ($hora);
		}
	public function ToDateTime(){
		date_default_timezone_set("America/La_Paz" ) ;
		return date( 'Y-m-d H:i', time());
		}
	public function ToFormatToDay(){
		$today = date( 'w' );
		switch( $today ){
			case 1: $dia = 'Lunes';
            break;
         	case 2: $dia = 'Martes';
            break;
         	case 3: $dia = 'Miercoles';
            break;
        	case 4: $dia = 'Jueves';
            break;
        	case 5: $dia = 'Viernes';
            break;
        	case 6: $dia = 'Sabado';
            break;
        	case 7: $dia = 'Domingo';
            break;
       	}
		return $dia;
	}

	public function ToMonth($month){
		//$month = date( 'm' );
        switch( $month ){
			case 1: $mes = 'Enero';
            break;
      		case 2: $mes = 'Febrero';
            break;
         	case 3: $mes = 'Marzo';
            break;
         	case 4: $mes = 'Abril';
            break;
         	case 5: $mes = 'Mayo';
           	break;
         	case 6: $mes = 'Junio';
            break;
         	case 7: $mes = 'Julio';
           	break;
         	case 8: $mes = 'Agosto';
            break;
         	case 9: $mes = 'Septiembre';
           	break;
        	case 10: $mes = 'Octubre';
           	break;
         	case 11: $mes = 'Noviembre';
           	break;
         	case 12: $mes = 'Diciembre';
            break;
        }
   		return $mes;
    }

	public function ToYear(){
		return date( 'Y' );
	}

	public function ActiveMonth(){
		$month = date( 'm' );
        switch( $month ){
			case 1: $mes = 'Diciembre';
            break;
      		case 2: $mes = 'Enero';
            break;
         	case 3: $mes = 'Febrero';
            break;
         	case 4: $mes = 'Marzo';
            break;
         	case 5: $mes = 'Abril';
           	break;
         	case 6: $mes = 'Mayo';
            break;
         	case 7: $mes = 'Junio';
           	break;
         	case 8: $mes = 'Julio';
            break;
         	case 9: $mes = 'Agosto';
           	break;
        	case 10: $mes = 'Septiembre';
           	break;
         	case 11: $mes = 'Octubre';
           	break;
         	case 12: $mes = 'Noviembre';
            break;
        }
   		return $mes;
    }

	public function validPage(){
		session_start();
		if( !session_is_registered( $_SESSION['user'] )  ){
			header( "HTTP/1.0 404 Not Found" );
			die();
		}
	}


	public function IP(){
		if ($_SERVER){
  			if ($_SERVER["HTTP_X_FORWARDED_FOR"])
    			$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  			elseif ($_SERVER["HTTP_CLIENT_IP"])
    				$realip = $_SERVER["HTTP_CLIENT_IP"];
  				else
    				$realip = $_SERVER["REMOTE_ADDR"];
		}
		else{
  			if (getenv ("HTTP_X_FORWARDED_FOR"))
    			$realip = getenv ("HTTP_X_FORWARDED_FOR");
  			elseif (getenv ("HTTP_CLIENT_IP"))
    				$realip = getenv ("HTTP_CLIENT_IP");
  				else
    				$realip = getenv ("REMOTE_ADDR");
		}
		return $realip;
	}

	public function video($video, $width){

		$height = round($width*0.8235);

		$fv	= htmlentities("<iframe width='$width' height='$height' src='video' frameborder='0' allowfullscreen></iframe>");

		$dv		= explode('/',$video);

		$vc		= 'http://www.youtube.com/embed/'.$dv[count($dv)-1];

		$vtr	= strtok ($fv,' ');

		$cad='';

		while($vtr!=FALSE){

			$w=explode('=',$vtr);
			if($w[0]=='src'){
						 $cad.=' '.'src="'.$vc.'"';
						}
			else{
				$cad.=' '.$vtr;}
			  $vtr=strtok (' ');
			 }

		return html_entity_decode($cad);

		}

	public function ceros($numero, $ceros){
		return sprintf("%0".$ceros."s", $numero );
	}

    // Evaluar los datos que ingresa el usuario y eliminamos caracteres no deseados.
  public function evaluar($valor)
  {
    $nopermitido = array("'",'\\','<','>',"\"");
    $valor = str_replace($nopermitido, "", $valor);
    return $valor;
  }

  // Formatear una fecha a microtime para añadir al evento, tipo 1401517498985.
  public function _formatear($fecha)
  {
    return strtotime(substr($fecha, 6, 4)."-".substr($fecha, 3, 2)."-".substr($fecha, 0, 2)." " .substr($fecha, 10, 6)) * 1000;
  }

}

?>