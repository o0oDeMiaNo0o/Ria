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
  <script type='text/javascript'>
function validar() {	
    if (document.registro.username.value.length==0){ 
      	alert("Tiene que escribir su email") 
      	document.registro.username.focus() 
      	return 0; 
   	} 
    document.registro.submit(); 
}
</script>
  <div id="contenido">

<form name="registro" action="recuperar-pass.php" method="post">
<div id="registro"><label for="username=">Email:</label>
<input type="text" name="username"></div>
<input type="submit" value="Recuperar" name="recuperar" onclick="validar()" />
</form>
  
<?php 

if (isset($_POST['recuperar']))
{

	$conexion=mysql_connect( $sql_location, $db_user, $db_pass) or
	die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Problemas en la conexion con MySql</span></h1></div></div></div>");

	mysql_select_db($db_base,$conexion) or
	die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Problemas en la seleccion de la base de datos</span></h1></div></div></div>");

	$instruccion = "select * from usuarios where email='$_REQUEST[username]'";
	$consulta = mysql_query ($instruccion, $conexion)
	or die ("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");
	$nfilas = mysql_num_rows ($consulta);

	if ($nfilas==0) {
		echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>El usuername escogido no existe</span></h1></div></div></div>";
	}
	else
	{
		$instruccion="select email,password from usuarios where username='$_POST[username]'";
		$consulta = mysql_query ($instruccion, $conexion);
		$resultado = mysql_fetch_array ($consulta);
		$pass=$resultado['password'];
		$email=$resultado['email'];

		mysql_close($conexion);
		$username=$_POST['username'];



		if (!$username )
		{
			echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No has completado todos los campos</span></h1></div></div></div>";
			exit;
		}

		$formsent = mail($email, 'Olvidaste tu password', 'Usuario: $username \r\n E-Mail: $email \r\n Password: $pass','From: $email \r\n Bounce-to: $email');
		if ($formsent) {
			echo '<div id="listados"><h1>Contrase&ntilde;a recuperada<span>A continuacion se detallan sus datos de acceso:</span></h1><p>Hola: '.$username.' hemos enviado a tu direcci&oacute;n de email: '.$email.' un correo electr&oacute;nico con tus datos de acceso.</p></div>';
		}
		else {
			echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Lo siento hay un problema con tu formulario</span></h1></div></div></div>";
		}


	}


}


?>
