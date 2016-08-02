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
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <link rel="stylesheet" href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-vertical.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/myStyle.css">

    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>

    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/slider-vertical.js"></script>

    <script type="text/javascript" src="js/myJavaScript.js"></script>

    <script type="text/javascript" language="javascript" class="init">

        $(document).ready(function() {
            $('[data-toggle="offcanvas"]').click(function(){
                $("#navigation").toggleClass("hidden-xs");
            });

            $('#example').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ filas por pagina",
                    "zeroRecords": "No se encontro nada - Lo siento",
                    "info": "Mostrando _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Filtrada de _MAX_ registros en total)",
                    "search":         "Buscar:",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Ultimo",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    }
                }
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
        } );

    </script>
</head>
<body>
<div class="container-fluid display-table">
    <div class="row display-table-row">
        <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
            <div class="logo">
                <a hef="home.html">
                    <img src="images/logo-elviejoroble.png" alt="El Viejo Roble">
                </a>
            </div>
            <div class="navi">
                <ul>
                    <li class="active"><a href="#"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Inicio</span></a></li>
                    <li><a href="#"><i class="fa fa-archive" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Pedidos</span></a></li>
                    <li><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Almacen</span></a></li>
                    <li><a href="#"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Producción</span></a></li>
                    <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Reportes</span></a></li>
                    <li><a href="#"><i class="fa fa-music" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Empleados</span></a></li>
                    <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Clentes</span></a></li>
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
                                <li class="hidden-xs"><a href="#" class="add-project" data-toggle="modal" data-target="#add_project">Add Project</a></li>
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
            <div class="user-dashboard">

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
                </div>

            </div>
        </div>
    </div>

</div>



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

</body>
</html>

