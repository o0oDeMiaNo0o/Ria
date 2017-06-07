<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/cabecera.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

function cierre() {

    include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
}

register_shutdown_function('cierre');
?>
<div id="contenido">
    <?php
// Obtener el entorno de la sesi�n y comprobar
// que est� definido.
    if (!isset($_SESSION['admin'])) {
        session_destroy();
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Por favor,
inicie primero la sesi&oacute;n como administrador</span></h1></div></div></div>");
    }
    ?>
    <?php
    if (!isset($_POST['enviar'])) {
        ?>
        <div id="registro">
            <form name="juegoform" enctype="multipart/form-data" action="ingresar-agente.php" method="post" >

                <h1 id="registro">Ingresar agente:<span>Complete el siguiente formulario para a&ntilde;adir un nuevo agente</span></h1>

                <div id="registro"><label for="nombre">Nombre:</label> <input type="text" name="nombre" id="nombre" required/></div>
                <div id="registro"><label for="email">E-mail:</label> <input type="email" name="email" id="email" required/></div>
                <div id="registro"><label for="password">Password:</label> <input type="password" name="password" id="password" required/></div>
                <div id="registro"><label for="estado">Activo:</label> <select name="activo">
                        <option value="1" selected>SI</option>
                        <option value="0">NO</option>
                    </select></div>
                <br>  

                <div id="registro">
                    <label for="jornada">Jornada laboral:</label> 
                    <fieldset>
                        <table id="jornada" align="center"> 
                            <tr> 
                                <td width="7%">L <input type="checkbox" name="dias[]" value="Lunes"  id="lunes"  /></td> 
                                <td width="7%">M <input type="checkbox" name="dias[]" value="Martes"  id="martes" /></td> 
                                <td width="7%">M <input type="checkbox" name="dias[]" value="Miercoles" id="miercoles"  /></td> 
                                <td width="7%">J <input type="checkbox" name="dias[]" value="Jueves"  id="jueves"  /></td> 
                                <td width="7%">V <input type="checkbox" name="dias[]" value="Viernes" id="viernes"   /></td> 
                                <td width="7%">S <input type="checkbox" name="dias[]" value="Sabado" id="sabado"   /></td> 
                                <td width="7%">D <input type="checkbox" name="dias[]" value="Domingo" id="domingo"   /></td> 
                                <td width="15%">Hr. Inicio: <input name="horainicio" type="time" value="08:00" required=""></td> 
                                <td width="15%">Hr. Fin: <input name="horafin" type="time" value="17:00" required=""></td> 
                            </tr>           
                        </table>
                </div>
                <br>
                <div id="registro"><label for="userfile">Avatar:</label> <input type="file" name="userfile" id="userfile" /> </div>
                <br>
                <input type="submit" name="enviar" value="A&ntilde;adir agente" />
            </form>

            <?php
        }
        if (isset($_POST ['enviar'])) {
            $resultado = "";

            include_once("config.php");

            $conexion = mysql_connect($sql_location, $db_user, $db_pass) or
                    die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexi&oacute;n con MySql</span></h1></div></div></div>");

            mysql_select_db($db_base, $conexion) or
                    die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la selecci&oacute;n de la base de datos</span></h1></div></div></div>");

            if (isset($_POST['dias'])) {
                $dias = $_POST['dias'];
                foreach ($dias as $em => $val) {
                    $resultado = $resultado . " " . $val;

                    mysql_query("insert into dias values
			('$_REQUEST[nombre]','$val')", $conexion)
                            or die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en el select" . mysql_error() . "</span></h1></div></div></div>");
                }
            } else {
                echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No se ha escogido ningún dia de la semana.</span></h1></div></div></div>";
                exit();
            }

            if (!($_FILES['userfile']['type'] == "image/pjpeg" || $_FILES['userfile']['type'] == "image/jpg" || $_FILES['userfile']['type'] == "image/jpeg")) {
                echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No ha escogido una foto, o no ha elegido una en formato jpg.</span></h1></div></div></div>";
            } else {
                $uploaddir = 'imagenes/agentes/';
                $nomfoto = $_POST['nombre'] . ".jpg";
                $uploadfile = $uploaddir . $nomfoto;
                if (move_uploaded_file($_FILES ['userfile'] ['tmp_name'], $uploadfile)) {
                    ?>
                    <script>
                        var tama = getSize();
                        document.write('El numero tiene ' + tama);
                    </script>
                    <?php
                    mysql_query("insert into agentes(nombre, email, password, activo, dias, hora_inicio, hora_fin)  values
			('$_REQUEST[nombre]','$_REQUEST[email]','$_REQUEST[password]','$_REQUEST[activo]', '$resultado', '$_REQUEST[horainicio]', '$_REQUEST[horafin]')", $conexion)
                            or die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en el select" . mysql_error() . "</span></h1></div></div></div>");

                    mysql_close($conexion);

                    echo "<div id='listados'>
<div id='correcto' class='correcto'><h1>Agente a&ntilde;adido con &eacute;xito:</h1><span class='correcto'>El agente fue ingresado correctamente</span><br><span class='correcto'><a href='/Admin/ingresar-agente.php'>Continuar agregando agentes</a></span></h1></div></div></div>";
                } else {
                    echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>
  		Por alguna razon la foto no ha podido ser subida al servidor
</span></h1></div></div></div>";
                }
            }
        }
        echo "</div>";
        ?>