<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrador - El Viejo Roble</title>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

    <link rel="stylesheet" href="css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-vertical.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/calendar.css">
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="css/myStyle.css">

    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>

    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/slider-vertical.js"></script>

    <script type="text/javascript" src="js/es-ES.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/bootstrap-datetimepicker.js"></script>
    <script src="js/bootstrap-datetimepicker.es.js"></script>
    <script type="text/javascript" src="js/underscore-min.js"></script>
    <script type="text/javascript" src="js/calendar.js"></script>

    <script type="text/javascript" src="js/myJavaScript.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){ // Script del Navegador
            $("ul.subnavegador").not('.selected').hide();
            $("a.desplegable").click(function(e){
                var desplegable = $(this).parent().find("ul.subnavegador");
                $('.desplegable').parent().find("ul.subnavegador").not(desplegable).slideUp('slow');
                desplegable.slideToggle('slow');
                e.preventDefault();
            })
        });
    </script>

    <style>

        ul {
            text-align: left;
        }
        ul li {
            font-size: 18px;
            padding: 5px 0;
        }
        ul li a {
            background: rgba(0, 0, 0, 0) url("http://emenia.es/demos-blog/menu-desplegable/images/flecha_enlace.png") no-repeat scroll 10px center;
            padding-left: 25px;
            width: 120px;
        }
        ul li a.desplegable {
            background: rgba(0, 0, 0, 0) url("http://emenia.es/demos-blog/menu-desplegable/images/flecha_desplegable.png") no-repeat scroll 10px center;
        }
        ul li li {
            font-size: 14px;
            padding-left: 15px;
        }
        ul li a.desplegable li a {
            background: rgba(0, 0, 0, 0) url("http://emenia.es/demos-blog/menu-desplegable/images/flecha_subenlace.png") no-repeat scroll 10px center;
        }

    </style>

</head>
<body>


<ul class="navegador">
    <li><a href="#" class="desplegable" title="Venta">Venta</a>
        <ul class="subnavegador">
            <li><a href="#" title="Viviendas">Viviendas</a></li>
            <li><a href="#" title="Aparcamientos">Aparcamientos</a></li>
        </ul>
    </li>
    <li><a class="desplegable" href="#" title="Alquiler">Alquiler</a>
        <ul class="subnavegador">
            <li><a href="#" title="Viviendas">Viviendas</a></li>
        </ul>
    </li>
    <li><a href="#" title="Oficinas">Oficinas</a></li>
    <li><a href="#" title="Ofertas">Ofertas</a></li>
    <li><a href="#" title="Oficina de Ventas">Oficina de Ventas</a></li>
</ul>

</body>
</html>