<?php
/**
 * Created by PhpStorm.
 * User: TAPIA
 * Date: 11/07/2016
 * Time: 21:24
 */

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

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

    <script type="text/javascript" src="js/jquery.json-2.3.js"></script>

    <script type="text/javascript" src="js/valida.2.1.6.js"></script>

    <script type="text/javascript" src="js/myJavaScript.js"></script>

    <script type="text/javascript" language="javascript" class="init">

        $(document).ready(function() {

            $('#myModal').modal({
                show: true,
                backdrop: 'static',
                keyboard: true
            });

            $("#myModal").on('shown.bs.modal', function(){
                $(this).find('#username').focus();
            });

            /*$("#login").validationEngine({
                promptPosition: "bottomLeft"
            });*/
            $('#login').valida();

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
<style>
    .modal-title{
        font-family: "Trebuchet MS";
        font-weight: 500;
    }
    .modal-login{
        width: 390px;
    }
    .modal-content{
        background-color: #EEEEEE;
        border: 5px solid #CCCCCC;
    }
    .fade.in {
        opacity: 1;
        background-image: url(images/backgraund.jpg);
        background-repeat: repeat;
    }
    p{
        color: #666;
        font-family: "Trebuchet MS";
        font-size: 11px;
    }

    /* rewrite some rules from bootstrap */
    .has-feedback .form-control-feedback {
        top: 0;
    }

    /*
     * Special CSS classes
     * for jQuery - Valida
     *
     * You can try to change the styles via Firebug (Firefox) or related plugin.
     */

    .at-error {
        /* placed on a error labels */
        color: #A94442;
        margin: 6px 0;
    }

    .at-warning {
        /* placed on a warning (invalid) labels */
        color: #8A6D3B;
        margin: 6px 0;
    }

    .at-invalid {
        /* placed on a invalid fields (which do not match with their filters or masks) */
    }

    .at-required {
        /* placed on a required fields (which are not filled) */
    }

    .at-description {
        /* placed on description paragrapher, right after TEXTAREA fields. */
    }

    .at-description > span {
        /* into description paragrapher, right after TEXTAREA fields there are 2 span TAGs. */
    }

    .at-required-highlight {
        /* highlight required form fields */
        color: red;
    }

    .alert {
        border: 1px solid transparent;
        border-radius: 4px;
        margin-bottom: 0px;
        padding: 10px;
    }

    #alert{
        display: none;
    }
</style>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-login">
        <div class="modal-content">
            <form id="login" name="login" action="javascript:verifica('login','password.php');">
            <div class="modal-header">
                <h3 class="modal-title">Inicie sesión</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <p>Por favor ingrese su usuario y contraseña para continuar</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8 col-sm-8">

                            <div class="form-group">
                                <input type="text" id="username" name="username" placeholder="Usuario" required="true" class="form-control" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" name="password" placeholder="Contrase&ntilde;a" required="true" class="form-control"/>
                            </div>

                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <img src="images/lock.png" width="90">
                    </div>
                    <div class="clearfix"></div>
                    <div id="alert" class="alert alert-danger col-xs-offset-1 col-xs-10 col-sm-10 col-md-10">
                        <strong>¡Error!</strong> Usuario o contrase&ntilde;a no validas.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                <input type="submit" name="submit" class="btn btn-primary" value="Ingresar" />
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
  (function (w,i,d,g,e,t,s) {w[d] = w[d]||[];t= i.createElement(g);
    t.async=1;t.src=e;s=i.getElementsByTagName(g)[0];s.parentNode.insertBefore(t, s);
  })(window, document, '_gscq','script','//widgets.getsitecontrol.com/72399/script.js');
</script>
</body>
</html>

