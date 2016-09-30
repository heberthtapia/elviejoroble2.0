<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Scroll</title>
    <style>
    #detectascroll{
        background-color: #cfc;
    }
    body{
        font-size: 1.2em;
    }
    p{
        max-width: 800px;
    }
    .mayor{
        width: 3500px;
        padding: 20px;
        background-color: #ccf;
    }
    .conscroll{
        height: 90px;
        width: 320px;
        padding: 15px;
        overflow: scroll;
        background-color: #cdd;
    }
    #animarscroll{
        background-color: #fcc;
    }
    #hastaaqui{
        background-color: #900;
        padding: 10px;
        color: white;
        font-size: 0.6em;
        width: 200px;
    }
    #irarriba{
        padding: 5px;
        position: fixed;
        bottom: 40px;
        right: 50px;
        background-color: #009;
        font-weight: bold;
        width: 50px;
        font-size: 0.8em;
        text-align: center;
        display: none;
    }
    #irarriba a{
        color: #fff;
    }
    </style>
</head>
<body>
   <div class="mayor">Este DIV se sale!!</div>
    <p>Lorem...</p>
    <p>Lorem...</p>
    <button id="detectascroll">Detecta el scroll <span></span></button> <button id="animarscroll">Animar hasta la caja roja</button>
    <p>Lorem...</p>
    <div class="conscroll">
        <p>Este párrafo y la siguiente lista están en una división que tiene scroll.</p>
        <button id="scrollelemento">Saber el scroll en el elemento <span></span></button>
        <ul>
            <li><a href="">enlace 1</a></li>
            <li><a href="">enlace 2</a></li>
            <li><a href="">enlace 3</a></li>
            <li><a href="">enlace 4</a></li>
            <li><a href="">enlace 5</a></li>
        </ul>
    </div>
    <p>Lorem...</p>
    <p>Lorem...</p>
    <p>Lorem...</p>
    <div id="hastaaqui">Animar scroll hasta aquí</div>
    <p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<p>Lorem...</p>
<div id="irarriba"><a href="#">Ir arriba</a></div>

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
    $(function(){
        $("#detectascroll").on("click", function () {
            //scroll vertical
            var sv = $(document).scrollTop();
            //scroll horizontal
            var sh = $(document).scrollLeft();
            console.log("El scroll es: Vertical->", sv, " Horizontal->" , sh);
            $(this).find("span").text("(" + sh + "," + sv+ ")");
        });
        $("#scrollelemento").on("click", function () {
            var boton = $(this);
            var elemento = boton.parent();
            //scroll vertical
            var sv = elemento.scrollTop();
            //scroll horizontal
            var sh = elemento.scrollLeft();
            console.log("El scroll del elemento es: Vertical->", sv, " Horizontal->" , sh);
            boton.find("span").text("(" + sh + "," + sv+ ")");
        });
        $("#animarscroll").on("click", function(){
            var posicion = $("#hastaaqui").offset().top;
            $("html, body").animate({
                scrollTop: posicion
            }, 2000);
        });
        $(document).on("scroll", function(){
            var desplazamientoActual = $(document).scrollTop();
            var controlArriba = $("#irarriba");
            console.log("Estoy en " , desplazamientoActual);
            if(desplazamientoActual > 100 && controlArriba.css("display") == "none"){
                controlArriba.fadeIn(500);
            }
            if(desplazamientoActual < 100 && controlArriba.css("display") == "block"){
                controlArriba.fadeOut(500);
            }
        });
        $("#irarriba a").on("click", function(e){
            e.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            }, 1000);
        });
    });
    </script>
</body>
</html>