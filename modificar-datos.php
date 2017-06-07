<?php
include_once("includes/cabecera.php");
include_once("includes/sidebar.php");
include_once("config.php");
function cierre()
{

	include_once("includes/footer.php");
}
register_shutdown_function('cierre');
?>
<div id="contenido">
<?php 
if (!isset($_SESSION['username']))
{
	session_destroy();
	die ("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Por favor,
primero inicie sesi&oacute;n</span></h1></div></div></div>");
}
$conexion=mysql_connect( $sql_location, $db_user, $db_pass) or
die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexi&oacute;n con MySql
</span></h1></div></div></div>");

mysql_select_db($db_base,$conexion) or
die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la selecci&oacute;n de la base de datos
</span></h1></div></div></div>");

$instruccion = "select * from agentes where email='$_SESSION[username]'";
$consulta = mysql_query ($instruccion, $conexion)
or die ("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta<
</span></h1></div></div></div>");

?>

<?php 
if ($reg=mysql_fetch_array($consulta))
{
?>
<form name="registro" enctype="multipart/form-data" action="modificar-usuario.php" method="post">
<h1 id="registro">Modificar datos personales:<span>Modifique los datos que desee, asegurse de mantener todos los campos completos</span></h1>
<div id="registro"><label for="nombre"  >Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $reg['nombre'] ?>" required></div>
<div id="registro"><label for="password"  ">Contrase&ntilde;a:</label>
<input type="text" name="password" value="<?php echo $reg['password'] ?>" readonly></div>
<div id="registro"><label for="email" >Email:</label>
<input type="email" name="email"  value="<?php echo $reg['email'] ?>" readonly></div>
<br>
<?php
switch ($reg['disponible'] ) {
      case 0:
       echo "<div id='registro'><label for='disponible'>Disponible:</label> SI <input type='radio' name='disponible' value='0' checked> NO <input type='radio' name='disponible' value='1'></div>";
       break;
      case 1:
       echo "<div id='registro'><label for='disponible'>Disponible:</label> SI <input type='radio' name='disponible' value='0'> NO <input type='radio' name='disponible' value='1' checked></div>";
       break;
      default:
       echo "<div id='registro'><label for='disponible'>Disponible:</label> SI <input type='radio' name='disponible' value='0' checked disabled> NO <input type='radio' name='disponible' value='1' disabled></div>";
       break;
}
?>
<br>
<div id="registro"><label for="userfile">Avatar:</label> <input type="file" name="userfile" id="userfile" /> </div>
<br>
<input type="submit" value="Actualizar datos" />
</form>
<?php 
}
include_once("includes/footer.php");
?>