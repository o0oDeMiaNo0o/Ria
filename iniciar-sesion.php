<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/cabecera.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php");
include_once("config.php");

function cierre() {

    include_once("includes/footer.php");
}

register_shutdown_function('cierre');
?>
<div id="contenido">
    <?PHP
// Obteniendo las variables externas 
// del inicio de sesion
    $username = $_POST['username'];
    $password = $_POST['password'];
// Definici�n del entorno de sesion
    session_start();
    $_SESSION['username'] = $username;

// recuperando contrase�a bd
    include_once("config.php");
    $conexion = mysql_connect($sql_location, $db_user, $db_pass) or
            die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexion con MySQL</span></h1></div></div></div>");
    mysql_select_db($db_base, $conexion) or
            die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la seleccion de la base de datos</span></h1></div></div></div>");
    $registros = mysql_query("select email, password, rango, activo from agentes where email='$_POST[username]'", $conexion) or
            die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en el select" . mysql_error() . "</span></h1></div></div></div>");
    if ($reg = mysql_fetch_array($registros)) {
        $rango = $reg['rango'];
        $passd = $reg['password'];
        $activo = $reg['activo'];
    }
// Autenticando la contrase�a
    if (empty($_POST['password'])) {
        unset($_SESSION['username']);

        echo("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No se ha introducido la contrase&ntilde;a. 
Vuelva a intentarlo</span></h1></div></div></div>");
        include_once("includes/footer.php");
        die();
    } else if (!($_POST['password'] == $passd)) {
        unset($_SESSION['username']);
        echo("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>La contrase&ntilde;a es inv&aacute;lida, vuelve a intentarlo</span></h1></div></div></div>");
        die();
    }
    
//Si esta bloqueado
    if ($activo == 1) {
        unset($_SESSION['username']);
        echo("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Su usuario se encuentra bloqueado.</span></h1></div></div></div>");
        die();
    }


// Enviando a la p�gina de inicio del usuario

    if ($rango == 2) {
        $_SESSION['admin'] = "admin";
    }
    $url = "Location:index.php";
    header($url);
    ?>