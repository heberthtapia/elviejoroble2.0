<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 11/07/2016
 * Time: 21:24
 */
  ini_set("session.use_trans_sid","0");
  ini_set("session.use_only_cookies","1");

  session_start();
  date_default_timezone_set("America/La_Paz" );
  session_set_cookie_params(0,"/",$_SERVER["HTTP_HOST"],0);

  include 'adodb5/adodb.inc.php';
  include 'inc/function.php';

  $op = new cnFunction();
  $db = NewADOConnection('mysqli');
  //$db->debug = true;
  $db->Connect();
/**
 * Inicio de session
 */
  if(!isset($_SESSION['idUser'])){
	  header('location:index.php');
  }else{
	  $fechaGuardada = $_SESSION["ultimoAcceso"];
	  $ahora = date("Y-n-j H:i:s");
	  $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));

	  if($tiempo_transcurrido >= 2160){
		  $user = $_SESSION["idUser"];
		  $strQuery = 'UPDATE usuario SET status = "Inactivo", dateReg = "0000-00-00 00:00:00" WHERE id_usuario = "'.$user.'"';
		  $str = $db->Execute($strQuery);
		  session_destroy();
		  header('location:index.php');
	  }else{
		  $_SESSION["ultimoAcceso"] = $ahora;
	  }
  }

  $sql = 'SELECT * ';
  $sql.= 'FROM empleado ';
  $sql.= 'WHERE id_empleado = '.$_SESSION['idEmp'].'';

  $reg = $db->Execute($sql);

  $row = $reg->FetchRow();

  $nombre = ltrim($row['nombre']);
  $nombre = rtrim($nombre);

  $nom = explode(' ',$nombre);

  $nombre1 = strtoupper($nom[0]);
  $nombre2 = strtoupper($nom[1]);


  $apP = strtoupper($row['apP']);

  $_SESSION['inc'] = $nombre1[0].''.$apP[0].'-';

  $cargo = $op->toSelect($row['cargo']);
/**
 * Fin de inicio de session
 *
 * Variables para el control de eventos en calendario.
 */

// Verificamos si se ha enviado el campo con name from
if (isset($_POST['from']))
{

	// Si se ha enviado verificamos que no vengan vacios
	if ($_POST['from']!="" AND $_POST['to']!="")
	{

		// Recibimos el fecha de inicio y la fecha final desde el form

		$inicio = $op->_formatear($_POST['from']);
		// y la formateamos con la funcion _formatear

		$final  = $op->_formatear($_POST['to']);

		// Recibimos el fecha de inicio y la fecha final desde el form

		$inicio_normal = $_POST['from'];

		// y la formateamos con la funcion _formatear
		$final_normal  = $_POST['to'];

		// Recibimos los demas datos desde el form
		$titulo = $op->evaluar($_POST['title']);

		// y con la funcion evaluar
		$body   = $op->evaluar($_POST['event']);

		// reemplazamos los caracteres no permitidos
		$clase  = $op->evaluar($_POST['class']);

		// insertamos el evento
		$query="INSERT INTO eventos VALUES(null,'$titulo','$body','','$clase','$inicio','$final','$inicio_normal','$final_normal')";

		// Ejecutamos nuestra sentencia sql
		//$conexion->query($query);
		$conexion = $db->Execute($query);

		// Obtenemos el ultimo id insetado
		//$im=$conexion->query("SELECT MAX(id) AS id FROM eventos");

		$im = $db->Execute("SELECT MAX(id) AS id FROM eventos");
		//$row = $im->fetch_row();
		$row = $im->FetchRow();
		$id = trim($row[0]);

		// para generar el link del evento
		$link = "$base_url"."inc/descripcion_evento.php?id=$id";

		// y actualizamos su link
		$query="UPDATE eventos SET url = '$link' WHERE id = $id";

		// Ejecutamos nuestra sentencia sql
		//$conexion->query($query);
		$conexion = $db->Execute($query);

		// redireccionamos a nuestro calendario
		header("Location:admin.php");
	}
}

?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Administrador - El Viejo Roble</title>

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/style-vertical.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/calendar.css">
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="css/theme-default.css">
	<link rel="stylesheet" href="uploadify/uploadify.css">
	<link rel="stylesheet" href="css/square/blue.css">
	<!--Autocomplete-->
	<link rel="stylesheet" href="css/themes/base/jquery.ui.autocomplete.css">

	<link rel="stylesheet" href="css/themes/base/jquery-ui-1.10.4.custom.css">
	<!-- Styles del CHAT -->
	<link rel="stylesheet" type="text/css" href="css/jquery.mCustomScrollbar.css">
	<link rel="stylesheet" type="text/css" href="css/myStyleChat.css">
	<!-- Mis Styles -->
	<link rel="stylesheet" href="css/myStyle.css">

	<script type="text/javascript" src="js/jquery-1.10.2.js"></script>

	<script type="text/javascript" src="js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/slider-vertical.js"></script>

	<script type="text/javascript" src="js/es-ES.js"></script>
	<script src="js/moment.js"></script>
	<script src="js/bootstrap-datetimepicker.js"></script>
	<script src="js/es.js"></script>
	<script type="text/javascript" src="js/underscore-min.js"></script>
	<script type="text/javascript" src="js/calendar.js"></script>

	<script type="text/javascript" src="js/jquery.form-validator.js"></script>
	<script type="text/javascript" src="js/jquery.json-2.3.js"></script>
	<!--uploadIfy-->
	<script type="text/javascript" src="uploadify/jquery.uploadify-3.1.js"></script>
	<!--icheck-->
	<script type="text/javascript" src="js/icheck.js"></script>
	<!--Autocomplete-->
	<script src="js/ui/jquery.ui.core.js"></script>
	<script src="js/ui/jquery.ui.widget.js"></script>
	<script src="js/ui/jquery.ui.position.js"></script>
	<script src="js/ui/jquery.ui.menu.js"></script>
	<script src="js/ui/jquery.ui.autocomplete.js"></script>
	<!-- JS para CHAT -->
	<script src="https://js.pusher.com/4.0/pusher.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script type="text/javascript" src="js/push.min.js"></script>
	<script type="text/javascript" src="js/miChat.js"></script>

	<link rel="stylesheet" type="text/css" href="css/tooltipster.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster-shadow.css">
	<script src="js/jquery.tooltipster.js"></script>

	<script type="text/javascript" src="js/myJavaScript.js"></script>

</head>
<body>
<div class="container-fluid display-table">
	<div class="row display-table-row">
		<div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
			<div class="logo">
				<a hef="admin.php">
					<img src="images/logo-elviejoroble.png" alt="El Viejo Roble">
				</a>
			</div>
			<div class="navi">
				<ul>
					<li class="active">
						<a href="#">
							<i class="fa fa-home" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Inicio</span>
						</a>
					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-indent" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Pedidos</span>
						</a>
						<ul class="subnavegador">
							<li><a href="#" onClick="despliega('modulo/pedido/newPedido.php','contenido')">
									<i class="fa fa-plus-square-o" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Nuevo Pedido</span>
								</a>
							</li>
							<li><a href="#" onclick="despliega('modulo/pedido/pedido.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Lista de Pedidos</span>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-archive" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Almacen</span>
						</a>
						<ul class="subnavegador">
							<li>
								<a href="#" onclick="despliega('modulo/almacen/producto.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Lista de Productos en General</span>
								</a>
							</li>
							<li>
								<a href="#" onclick="despliega('modulo/almacen/productoPre.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Lista de Productos por Preventista</span>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-industry" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Producción</span>
						</a>
						<ul class="subnavegador">
							<li>
								<a href="#" onclick="despliega('modulo/produccion/produccion.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Ordenes de Producción</span>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Reportes</span>
						</a>

					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-user" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Empleados</span>
						</a>
						<ul class="subnavegador">
							<!--<li><a href="#">
									<i class="fa fa-plus-square-o" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Nueva Empleado</span>
								</a>
							</li>-->
							<li><a href="#" onclick="despliega('modulo/empleado/empleado.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Lista de Empleados</span>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="#" class="desplegable">
							<i class="fa fa-users" aria-hidden="true"></i>
							<span class="hidden-xs hidden-sm">Clientes</span>
						</a>
						<ul class="subnavegador">
							<!--<li><a href="#">
									<i class="fa fa-plus-square-o" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Nuevo Cliente</span>
								</a>
							</li>-->
							<li><a href="#" onclick="despliega('modulo/cliente/cliente.php','contenido')">
									<i class="fa fa-list" aria-hidden="true"></i>
									<span class="hidden-xs hidden-sm">Lista de Clientes</span>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-10 col-sm-11 display-table-cell v-align">
			<!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
			<div class="row">
				<header>
					<div class="col-md-7">
						<nav class="navbar-default pull-left">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#side-menu" aria-expanded="false">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
						</nav>
						<div class="">
							<h1 id="titleEmp">Sistema de Administracion</h1>
						</div>
						<!--<div class="search hidden-xs hidden-sm">
							<input type="text" placeholder="Search" id="search">
						</div>-->
					</div>
					<div class="col-md-5">
						<div class="header-rightside">
							<ul class="list-inline header-top pull-right">
								<!--<li class="hidden-xs"><a href="#" class="add-project" data-toggle="modal" data-target="#add_project">Add Project</a></li>-->
								<li><a href="#"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
								<li>
									<a href="#" class="icon-info">
										<i class="fa fa-bell" aria-hidden="true"></i>
										<span class="label label-primary">4</span>
									</a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<img src="images/iconos/hombre.png" alt="user">
										<b class="caret"></b>
									</a>
									<ul class="dropdown-menu">
										<li>
											<div class="navbar-content">
												<span><?=$nombre1[0].$nombre2[0];?>&nbsp;<?=ucwords($row['apP']);?></span>
												<p class="text-muted small">
													ht.heberth@gmail.com
												</p>
												<div class="divider">
												</div>
												<a href="#" onclick="outSession('<?=$_SESSION['idUser'];?>');" class="btn-sm active">Cerrar Sesion</a>
											</div>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</header>
			</div>
			<div class="user-dashboard" id="contenido">

				<div class="row">
					<div class="col-xs-12 col-sm-8 col-md-8">
						<h2 class="avisos">Aviso Importante</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus adipisci, aspernatur atque consequuntur corporis, dicta libero magnam minima perferendis quam quia quibusdam saepe voluptates. Ad cumque dicta eos eum sapiente.</p>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab amet, aperiam consequuntur culpa delectus dolorum minima nam rem repellendus ullam. Ad esse explicabo facilis nobis tempore. Commodi consequuntur hic iste.</p>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<div class="contenido">

							<div class="nivel slider-vertical">
								<h4 class="avisos">Todos los Avisos</h4>
								<div class="contenedor-slider">

									<div class="bloque-slider">
										<div class="modulo-slider">
											<h4><a href="#">Titular 1</a></h4>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium alias dicta distinctio error facere impedit, inventore iusto non, odio perferendis perspiciatis placeat quas qui quis reiciendis rerum tenetur vel voluptas.</p>
										</div>
										<!-- fin modulo-noticias-slide -->
										<div class="modulo-slider">
											<h4><a href="#">Titular 2</a></h4>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa molestias nisi perferendis, perspiciatis quia sint totam. Delectus, deleniti dolore dolores error facilis mollitia perspiciatis! Adipisci nobis nostrum quos recusandae voluptatem.</p>
										</div>
										<!-- fin modulo-noticias-slide -->
										<div class="modulo-slider">
											<h4><a href="#">Titular 3</a></h4>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. At commodi dolor fugit id incidunt iste maiores minima nulla odit porro qui quibusdam, quidem quod saepe sequi veniam vero voluptates voluptatum?</p>
										</div>
										<!-- fin modulo-noticias-slide -->
										<div class="modulo-slider">
											<h4><a href="#">Titular 4</a></h4>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est eum illo quasi quibusdam sapiente similique totam vel voluptatibus. Aut consequatur esse illo maiores nemo pariatur provident? Modi nobis obcaecati sed.</p>
										</div>
										<!-- fin modulo-noticias-slide -->
										<div class="modulo-slider">
											<h4><a href="#">Titular 5</a></h4>
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A autem commodi consequatur distinctio eos harum illo magni minima modi molestiae provident, quaerat quas quia rem tempore. Beatae culpa possimus quisquam.</p>
										</div>
										<!-- fin modulo-noticias-slide -->
									</div>
									<!-- fin bloque-slider -->
									<p class="mover-slider-vertical">
										<a class="subir-slider" >
											<i class="fa fa-sort-asc fa-2x" aria-hidden="true"></i>
										</a>
										<a class="bajar-slider">
											<i class="fa fa-sort-desc fa-2x" aria-hidden="true"></i>
										</a>
									</p>
								</div>
								<!-- fin contenedor-noticias-slide
								<p class="vinculo-especial2">
									<a href="#">Ver más noticias</a>
								</p>-->

							</div>
							<!-- fin nivel slide-vertical -->


						</div>
						<!-- fin	contenido -->
					</div>
				</div><!-- fin fila -->
				<div class="row">
					<div class="page-header"><h2></h2></div>
					<div class="pull-left form-inline"><br>
						<div class="btn-group">
							<button class="btn btn-primary" data-calendar-nav="prev"><< Anterior</button>
							<button class="btn" data-calendar-nav="today">Hoy</button>
							<button class="btn btn-primary" data-calendar-nav="next">Siguiente >></button>
						</div>
						<div class="btn-group">
							<button class="btn btn-warning" data-calendar-view="year">Año</button>
							<button class="btn btn-warning active" data-calendar-view="month">Mes</button>
							<button class="btn btn-warning" data-calendar-view="week">Semana</button>
							<button class="btn btn-warning" data-calendar-view="day">Dia</button>
						</div>

					</div>
					<div class="pull-right form-inline"><br>
						<button class="btn btn-info" data-toggle='modal' data-target='#add_evento'>Añadir Evento</button>
					</div>
				</div><hr>

				<div class="row">
					<div id="calendar"></div> <!-- Aqui se mostrara nuestro calendario -->
					<br><br>
				</div>

			</div>
		</div>
	</div>

</div>

<!--ventana modal para el calendario-->
<div class="modal fade" id="events-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" style="height: 400px">
				<p>One fine body&hellip;</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick="setTimeout(cerrarModal(), 5000);" data-dismiss="modal">Cerrar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
<div id="add_project" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header login-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="modal-title">Add Project</h4>
			</div>
			<div class="modal-body">
				<input type="text" placeholder="Project Title" name="name">
				<input type="text" placeholder="Post of Post" name="mail">
				<input type="text" placeholder="Author" name="passsword">
				<textarea placeholder="Desicrption"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="cancel" data-dismiss="modal">Close</button>
				<button type="button" class="add-project" data-dismiss="modal">Save</button>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	(function($){
		//creamos la fecha actual
		var date = new Date();
		var yyyy = date.getFullYear().toString();
		var mm = (date.getMonth()+1).toString().length == 1 ? "0"+(date.getMonth()+1).toString() : (date.getMonth()+1).toString();
		var dd  = (date.getDate()).toString().length == 1 ? "0"+(date.getDate()).toString() : (date.getDate()).toString();
		//establecemos los valores del calendario
		var options = {

			// definimos que los eventos se mostraran en ventana modal
			modal: '#events-modal',

			// dentro de un iframe
			modal_type:'iframe',

			//obtenemos los eventos de la base de datos
			events_source: 'inc/obtener_eventos.php',

			// mostramos el calendario en el mes
			view: 'month',

			// y dia actual
			day: yyyy+"-"+mm+"-"+dd,



			// definimos el idioma por defecto
			language: 'es-ES',

			//Template de nuestro calendario
			tmpl_path: '<?=$base_url?>tmpls/',
			tmpl_cache: false,


			// Hora de inicio
			time_start: '08:00',

			// y Hora final de cada dia
			time_end: '22:00',

			// intervalo de tiempo entre las hora, en este caso son 30 minutos
			time_split: '30',

			// Definimos un ancho del 100% a nuestro calendario
			width: '100%',

			onAfterEventsLoad: function(events)
			{
				if(!events)
				{
					return;
				}
				var list = $('#eventlist');
				list.html('');

				$.each(events, function(key, val)
				{
					$(document.createElement('li'))
						.html('<a href="' + val.url + '">' + val.title + '</a>')
						.appendTo(list);
				});
			},
			onAfterViewLoad: function(view)
			{
				$('.page-header h2').text(this.getTitle());
				$('.btn-group button').removeClass('active');
				$('button[data-calendar-view="' + view + '"]').addClass('active');
			},
			classes: {
				months: {
					general: 'label'
				}
			}
		};


		// id del div donde se mostrara el calendario
		var calendar = $('#calendar').calendar(options);

		$('.btn-group button[data-calendar-nav]').each(function()
		{
			var $this = $(this);
			$this.click(function()
			{
				calendar.navigate($this.data('calendar-nav'));
			});
		});

		$('.btn-group button[data-calendar-view]').each(function()
		{
			var $this = $(this);
			$this.click(function()
			{
				calendar.view($this.data('calendar-view'));
			});
		});

		$('#first_day').change(function()
		{
			var value = $(this).val();
			value = value.length ? parseInt(value) : null;
			calendar.setOptions({first_day: value});
			calendar.view();
		});
	}(jQuery));
</script>

<div class="modal fade" id="add_evento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Agregar nuevo evento</h4>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<label for="from">Inicio</label>
					<div class='input-group date' id='from'>
						<input type='text' id="from" name="from" class="form-control" />
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
					</div>

					<br>

					<label for="to">Final</label>
					<div class='input-group date' id='to'>
						<input type='text' name="to" id="to" class="form-control"  />
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
					</div>

					<br>

					<label for="tipo">Tipo de evento</label>
					<select class="form-control" name="class" id="tipo">
						<option value="event-info">Informacion</option>
						<option value="event-success">Exito</option>
						<option value="event-important">Importantante</option>
						<option value="event-warning">Advertencia</option>
						<option value="event-special">Especial</option>
					</select>

					<br>


					<label for="title">Título</label>
					<input type="text" required autocomplete="off" name="title" class="form-control" id="title" placeholder="Introduce un título">

					<br>


					<label for="body">Evento</label>
					<textarea id="body" name="event" required class="form-control" rows="3"></textarea>

					<script type="text/javascript">
						$(function () {
							$('#from').datetimepicker({
								locale: 'es'
							});
							$('#to').datetimepicker({
								locale: 'es',
								useCurrent: false //Important! See issue #1075
							});

							$("#from").on("dp.change", function (e) {
								$('#to').data("DateTimePicker").minDate(e.date);
							});
							$("#to").on("dp.change", function (e) {
								$('#from').data("DateTimePicker").maxDate(e.date);
							});

						});
					</script>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Agregar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" language="javascript" class="init">

	$(document).ready(function() {
		$('[data-toggle="offcanvas"]').click(function(){
			$("#navigation").toggleClass("hidden-xs");
		});
		/*********************/
		/* SLIDER DE NOTICIAS*/
		/*********************/
		moverSlider();
		$(".bajar-slider").click(function(){
			bajarSlider();
		});

		$(".subir-slider").click(function(){
			subirSlider();
		});

		$(".slider-vertical").mouseover(function(){
			verificar = 0;
		});

		$(".slider-vertical").mouseout(function(){
			verificar = 1;
		});

		/**
		 * Menu Desplegable
		 */

		$("ul.subnavegador").not('.selected').hide();
		$("a.desplegable").click(function(e){
			var desplegable = $(this).parent().find("ul.subnavegador");
			$('.desplegable').parent().find("ul.subnavegador").not(desplegable).slideUp('slow');
			desplegable.slideToggle('slow');
			e.preventDefault();
		})
	} );
	function cerrarModal() {
		location.href = "admin.php";
	}

</script>
<!-- AQUI ESTA EL CODIGO DEL CHAT -->
<?PHP
	$sql = 'SELECT * FROM usuario AS u, empleado AS e ';
	$sql.= 'WHERE u.id_empleado = e.id_empleado ';
	$sql.= 'AND u.status = "Activo"';
	$sql.= 'AND u.id_empleado != '.$_SESSION['idEmp'].'';
	$srtQuery = $db->Execute($sql);
?>
<audio id="audio4"><source src="modulo/chat/tono/Peanut.ogg" type="audio/ogg"></audio>

<aside id="sidebar_primary" class="tabbed_sidebar ng-scope chat_sidebar">
	<div class="popup-head">
		<div class="popup-head-left pull-left">
			<h1>Conectados</h1>
		</div>
		<div class="popup-head-right-online pull-right">
			<button class="chat-header-button" type="button" onclick="minimizar('connect')"><i class="fa fa-minus" aria-hidden="true"></i></button>
		</div>
	</div>
<div id="connect" class="chat_box_wrapper chat_box_small chat_box_active connect mCustomScrollbar">
	<div class="chat_box touchscroll chat_box_colors_a">
		<div class="chat_message_wrapper">
			<div class="chat_user_avatar">
				<ul>
				<?php
					while( $row = $srtQuery->FetchRow() ){
				?>
					<li>
						<a onclick="chatClick(<?=$row['id_empleado']?>, <?=$_SESSION['idEmp']?>);" >
							<?PHP
								  if( $row['foto'] != '' ){
							?>
								<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../modulo/empleado/uploads/<?=($row['foto']);?>&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
							<?PHP
								}else{
							?>
								<img class="thumb md-user-image" src="thumb/phpThumb.php?src=../images/sin_imagen.jpg&amp;w=32&amp;h=32&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">
							<?PHP
								}
							?>
							<p><?=$row['nombre'].' '.$row['apP'].' '.$row['apM']?></p>
						</a>
					</li>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
	</div>
</aside>
<div id="sidebar"></div>
<!-- AQUI TERMINA -->
<br>
<br>
</body>
</html>

