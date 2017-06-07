<?PHP
// Iniciar la sesi�n
session_start();
// Obtener el entorno de la sesi�n y 
// comprobar que est� definido
include_once("includes/cabecera.php");
include_once("includes/sidebar.php");
include_once("config.php");
?>
<div id="contenido">
<?php 
function cierre()
{

	include_once("includes/footer.php");
}
register_shutdown_function('cierre');
if (isset($_SESSION['username']))
{
// Caso de que la sesi�n est� definida
unset($_SESSION['username']);
session_destroy();
$url = "Location:index.php";
header($url);
	
if (isset($_SESSION['admin']))
{
	// Caso de que la sesi�n est� definida
	unset($_SESSION['admin']);
	session_destroy();
}

}
else 
{
die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>S&oacute;lo los usuarios registrados pueden cerrar la sesi&oacute;n. 
Inicie primero la sesi&oacute;n</span></h1></div></div></div>");
}
?>