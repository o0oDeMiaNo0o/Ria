<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/cabecera.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

function cierre() {

    include_once("includes/footer.php");
}

register_shutdown_function('cierre');
?>
<div id="contenido">

    <?php
    if (!isset($_SESSION['admin'])) {
        session_destroy();
        die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Por favor,
inicie primero la sesi&oacute;n como administrador</span></h1></div></div></div>");
    }


    $conexion = mysql_connect($sql_location, $db_user, $db_pass) or
            die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexi&oacute;n con MySql</span></h1></div></div></div>");

    mysql_select_db($db_base, $conexion) or
            die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la selecci&oacute;n de la base de datos</span></h1></div></div></div>");

// Establecer el n�mero de filas por p�gina y la fila inicial
    $num = 10; // n�mero de filas por p�gina

    $comienzo = $_REQUEST['comienzo'];
    if (!isset($comienzo))
        $comienzo = 0;

// Calcular el n�mero total de filas de la tabla

    $instruccion = "select * from agentes where rango = 1";

    $consulta = mysql_query($instruccion, $conexion)
            or die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");
    $nfilas = mysql_num_rows($consulta);

    if ($nfilas > 0) {
        // Mostrar n�meros inicial y final de las filas a mostrar
        print ("<div id='listados'><h1>Listado de agentes:<span>A continuaci&oacute;n vera todos los agentes ordenados por nombre.</span></h1><P>Mostrando agentes " . ($comienzo + 1) . " a ");
        if (($comienzo + $num) < $nfilas)
            print ($comienzo + $num);
        else
            print ($nfilas);
        print (" de un total de $nfilas.\n");

        // Mostrar botones anterior y siguiente
        $estapagina = $_SERVER['PHP_SELF'];
        if ($nfilas > $num) {
            if ($comienzo > 0)
                print ("[ <A HREF='$estapagina?comienzo=" . ($comienzo - $num) . "'>Anterior</A> | ");
            else
                print ("[ Anterior | ");
            if ($nfilas > ($comienzo + $num))
                print ("<A HREF='$estapagina?comienzo=" . ($comienzo + $num) . "'>Siguiente</A> ]\n");
            else
                print ("Siguiente ]\n");
        }
        print ("</P>\n");
    }

// Enviar consulta

    $instruccion = "select * from agentes where rango = 1 order by nombre asc limit $comienzo, $num";


    $consulta = mysql_query($instruccion, $conexion)
            or die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");

// Mostrar resultados de la consulta
    $nfilas = mysql_num_rows($consulta);
    if ($nfilas > 0) {
        print ("<TABLE WIDTH='650'>\n");
        print ("<TR>\n");
        print ("<TH WIDTH='100'>Nombre</TH>\n");
        print ("<TH WIDTH='100'>Email</TH>\n");
        print ("<TH WIDTH='100'>Dias</TH>\n");
        print ("<TH WIDTH='100'>Hora inicio</TH>\n");
        print ("<TH WIDTH='100'>Hora fin</TH>\n");
        print ("<TH WIDTH='50'>Activo</TH>\n");
        print ("<TH WIDTH='50'>Eliminar</TH>\n");
        print ("</TR>\n");

        for ($i = 0; $i < $nfilas; $i++) {
            $resultado = mysql_fetch_array($consulta);
            print ("<TR>\n");
            print ("<TD><a href='modificar-agente.php?idagente=" . $resultado['id'] . "'>" . $resultado['nombre'] . "</a></TD>\n");
            print ("<TD>" . $resultado['email'] . "</TD>\n");
            print ("<TD>" . $resultado['dias'] . "</TD>\n");
            print ("<TD>" . $resultado['hora_inicio'] . "</TD>\n");
            print ("<TD>" . $resultado['hora_fin'] . "</TD>\n");
            if ($resultado['activo'] == 0) {
                print ("<TD><a href='modificar-estado-agente.php?idagente=" . $resultado['id'] . "'><IMG BORDER='0' SRC='/imagenes/ic-activo.png' ALT='Foto' /></A></TD>\n");
            } else {
                print ("<TD><a href='modificar-estado-agente.php?idagente=" . $resultado['id'] . "'><IMG BORDER='0' SRC='/imagenes/ic-inactivo.png' ALT='Foto' /></A></TD>\n");
            }
            print ("<TD><a href='eliminar-agente.php?idagente=" . $resultado['id'] . "'><IMG BORDER='0' SRC='/imagenes/ic-papelera.png' ALT='Foto' /></A></TD>\n");
            print ("</TR>\n");
        }

        print ("</TABLE>\n</div>");
    } else
        print ("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No hay agentes disponibles</span></h1></div></div></div>");

    // Cerrar conexi�n
    mysql_close($conexion);
    include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
    ?>

