<?php

include_once("includes/cabecera.php");
include_once("includes/sidebar.php");
include_once("config.php");

function cierre() {

    include_once("includes/footer.php");
}

register_shutdown_function('cierre');
echo "<div id='contenido'>";

if (!isset($_SESSION['username'])) {
    session_destroy();
    die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Por favor,
primero inicie sesion</span></h1></div></div></div>");
}

$conexion = mysql_connect($sql_location, $db_user, $db_pass) or
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Problemas en la conexion con MySql</span></h1></div></div></div>");

mysql_select_db($db_base, $conexion) or
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Problemas en la seleccion de la base de datos</span></h1></div></div></div>");

$registros = mysql_query("update agentes
                          set nombre='$_REQUEST[nombre]', disponible='$_REQUEST[disponible]'
                        where email='$_REQUEST[email]'", $conexion) or
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Problemas en el select:" . mysql_error() . "</span></h1></div></div></div>");

echo "<div id='listados'>
<div id='correcto' class='correcto'><h1>Correcto:</h1><span class='correcto'>Su usuario fue modificado con &eacute;xito.</span></h1></div></div></div>";
echo "<br><a href='/index.php'>Volver al inicio</a>";

mysql_close($conexion);

if (($_FILES['userfile']['type'] == "image/pjpeg" || $_FILES['userfile']['type'] == "image/jpg" || $_FILES['userfile']['type'] == "image/jpeg")) {
    $uploaddir = 'Admin/imagenes/agentes/';
    $nomfoto = $_POST['nombre'] . ".jpg";
    $uploadfile = $uploaddir . $nomfoto;
    move_uploaded_file($_FILES ['userfile'] ['tmp_name'], $uploadfile);
}
?>