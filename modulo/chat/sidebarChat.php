<?php
session_start();

include '../../adodb5/adodb.inc.php';
include '../../inc/function.php';

$op = new cnFunction();

$db = NewADOConnection('mysqli');
//$db->debug = true;
$db->Connect();

$userFrom = $_POST['userFrom'];
$userTo = $_POST['userTo'];
$num  = $_POST['num'];

$sqlFoto = 'SELECT foto FROM empleado ';
$sqlFoto.= 'WHERE id_empleado = '.$userFrom.'';

$srtQuery = $db->Execute($sqlFoto);
$rowFoto = $srtQuery->FetchRow();

$sql = 'SELECT * FROM usuario AS u, empleado AS e ';
$sql.= 'WHERE u.id_empleado = e.id_empleado ';
$sql.= 'AND u.id_empleado = '.$userTo.'';
	//$sql.= 'AND u.status = "Activo"';

$srtQuery = $db->Execute($sql);

$row = $srtQuery->FetchRow();

$num ++;

$srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$userFrom.' AND sendTo = '.$userTo.'';
$srtQ = $db->Execute($srtSql);

if($srtQ){
	$srtSql = 'SELECT chatID FROM chat WHERE sendFrom = '.$userTo.' AND sendTo = '.$userFrom.'';
	$srtQ = $db->Execute($srtSql);
}

$chatID = $srtQ->FetchRow();

if(!$chatID[0]){
	$chatID[0] = 0;
}

$sqlQuery = 'SELECT * FROM chat WHERE chatID = '.$chatID[0].' ORDER BY (dateSend) ASC';
$query = $db->Execute($sqlQuery);


$html = '
<aside id="'.$userFrom.''.$userTo.'" class="tabbed_sidebar ng-scope chat_sidebar" style="right: '.((280*$num)+30).'px; width: 260px;">

	<div class="popup-head">
		<div class="popup-head-left pull-left">
			<a title="'.$row['nombre'].' '.$row['apP'].' '.$row['apM'].'" onclick="minimizar(&#39;chat'.$userFrom.''.$userTo.'&#39;)">';
			if( $row['foto'] != '' ){
$html.='        <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'.$row['foto'].'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';

			}else{
$html.='       	<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">';
			}
$html.='<h1>'.$row['nombre'].' '.$row['apP'].' '.$row['apM'].'</h1><small><br> <i class="fa fa-briefcase" aria-hidden="true"></i> '.$op->toSelect($row['cargo']).'</small>
			</a>
		</div>
		<div class="popup-head-right pull-right">
			<button class="chat-header-button" type="button" onclick="minimizar(&#39;chat'.$userFrom.''.$userTo.'&#39;)"><i class="glyphicon glyphicon-minus"></i></button>
			<button data-widget="remove" id="removeClass" class="chat-header-button pull-right" type="button" onclick="cerrar(&#39;'.$userFrom.''.$userTo.'&#39;)"><i class="glyphicon glyphicon-remove"></i></button>
		</div>
	</div>
<div class="chat'.$userFrom.''.$userTo.'">
<div id="chat'.$userFrom.''.$userTo.'" class="chat_box_wrapper chat_box_small chat_box_active" style="opacity: 1; display: block; transform: translateX(0px);">
	<div id="chat_box_'.$userFrom.''.$userTo.'" class="chat_box touchscroll chat_box_colors_a mCustomScrollbar">';

		$m1 = 0;
		$m2 = 0;
		$sw = 0;
		while($reg = $query->FetchRow()){
		while ($sw == 0) {
		if ($reg[2] == $userFrom) {
			$m1++;
$html.='<div class="chat_message_wrapper">';

			if($m1 == 1){

$html.='<div class="chat_user_avatar">';

			if( $row['foto'] != '' ){
$html.='        <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'.$row['foto'].'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';

			}else{
$html.='       	<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">';
			}
$html.=' </div>';
$html.='<ul class="chat_message">';
$html.='    <li>
				<p>'.$reg[4].'</p>
			</li>';
			}

		while($reg = $query->FetchRow()){
			//print_r($reg);
			if ($reg[2] == $userFrom) {

$html.='    <li>
				<p>'.$reg[4].'</p>
			</li>';
			}else{
				$m1 = 0;
				break;
			}
		}

$html.='</ul>

		</div>';
		}
		if ($reg[2] == $userTo) {
			$m2++;
$html.='<div class="chat_message_wrapper chat_message_right">';

			if($m2 == 1){

$html.='<div class="chat_user_avatar">';

			if( $rowFoto['foto'] != '' ){
$html.='        <img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/'.$rowFoto['foto'].'&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="Foto de Perfil" title="Foto de Perfil">';

			}else{
$html.='       	<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">';
			}
$html.='  </div>';
$html.='<ul class="chat_message">';
$html.='    <li>
				<p>'.$reg[4].'</p>
			</li>';
			}

		while($reg = $query->FetchRow()){
			if ($reg[2] == $userTo) {
$html.='    <li>
				<p>'.$reg[4].'</p>
			</li>';
			}else{
				$m2 = 0;
				break;
			}
		}
$html.='</ul>';


$html.='</div>';
		}
		if(!$reg) $sw = 1;
	 }
	}

$html.='</div>';
$html.='</div>
	<div class="chat_submit_box">
		<div class="uk-input-group">
			<form id="formChat'.$row['id_empleado'].'" method="POST">
				<div class="gurdeep-chat-box">
					<input type="text" placeholder="Escriba mensaje" id="submit_message'.$row['id_empleado'].'" name="submit_message'.$row['id_empleado'].'" class="md-input chatMessage" autocomplete="off" onclick="limpNMessaje('.$userFrom.''.$userTo.');">
				</div>
				<span class="uk-input-group-addon">
					<a href="#" id = "send'.$row['id_empleado'].'" onclick = "sendSubmit('.$row['id_empleado'].', '.$_SESSION['idEmp'].')" ><i class="glyphicon glyphicon-send"></i></a>
				</span>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$( "#formChat'.$row['id_empleado'].'" ).submit(function( event ) {
		  sendSubmit('.$row["id_empleado"].', '.$_SESSION['idEmp'].');
		event.preventDefault();
	});
</script>
</aside>';

echo $html;

?>
