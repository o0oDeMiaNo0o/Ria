<?php

function cierre() {

    include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/footer.php");
}

register_shutdown_function('cierre');
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/cabecera.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
?>
<div id="contenido-filtro">

    <?php
    if (!isset($_SESSION['admin'])) {
        session_destroy();
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Por favor,
primero inicie sesion</span></h1></div></div></div>");
    }

    if (!isset($_GET['enviar'])) {
        $_GET['agente'] = "Todos";
        $_GET['inicio'] = "2016-01-01";
        $_GET['fin'] = date('Y-m-d');
    }

    $conexion = mysql_connect($sql_location, $db_user, $db_pass) or
            die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexion con MySql</span></h1></div></div></div>");

    mysql_select_db($db_base, $conexion) or
            die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la seleccion de la base de datos</span></h1></div></div></div>");

// Establecer el n�mero de filas por p�gina y la fila inicial
    $num = 10; // n�mero de filas por p�gina

    $comienzo = $_REQUEST['comienzo'];
    if (!isset($comienzo))
        $comienzo = 0;

// Calcular el n�mero total de filas de la tabla
    if ($_GET['agente'] == "Todos") {
        $instruccion = "select * from webchat_lineas";
    } else {
        $instruccion = "select * from webchat_lineas where emisor='$_GET[agente]' or receptor='$_GET[agente]'";
    }

    $consulta = mysql_query($instruccion, $conexion)
            or die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");

    print ("<div id='listados'><h1>Consultas:<span>A continuacion veras el registro de actividad y podras filtrar por agente y/o fecha.</span></h1>");
    ?>
    <form id="listadoprod" name="listadoprod" action="listado-consultas.php" method="get">
        <div id="listadoprod"><label for="agente">Agente:</label> <select name="agente">
                <option value="Todos">Todos</option>
                <?php
                $instruccion2 = "select nombre from agentes where rango = 1";
                $consulta2 = mysql_query($instruccion2, $conexion);
                $nfilas2 = mysql_num_rows($consulta2);
                for ($j = 0; $j < $nfilas2; $j++) {
                    $resultado2 = mysql_fetch_array($consulta2);
                    echo "<option value='" . $resultado2['nombre'] . "'>" . $resultado2['nombre'] . "</option>";
                }
                ?>
            </select>
            <br><br><br>
            <label for="fechas">Rango de fechas:</label> 
            <input type="date" name="inicio" value="2016-01-01" />
            <input type="date" name="fin" value="<?php echo date('Y-m-d'); ?>"  />

            <input type="submit" name="enviar" value="Filtrar"  />
        </div></form>
    <?php
    $nfilas = mysql_num_rows($consulta);

    if ($nfilas > 0) {


        // Mostrar n�meros inicial y final de las filas a mostrar

        print ("<P>Mostrando registros " . ($comienzo + 1) . " a ");
        if (($comienzo + $num) < $nfilas)
            print ($comienzo + $num);
        else
            print ($nfilas);
        print (" de un total de $nfilas.\n");

        // Mostrar botones anterior y siguiente
        $estapagina = $_SERVER['PHP_SELF'];
        if ($nfilas > $num) {
            if ($comienzo > 0)
                print ("[ <A HREF='$estapagina?comienzo=" . ($comienzo - $num) . "&agente=" . $_GET['agente'] . "&inicio=" . $_GET['inicio'] . "&fin=" . $_GET['fin'] . "'>Anterior</A> | ");
            else
                print ("[ Anterior | ");
            if ($nfilas > ($comienzo + $num))
                print ("<A HREF='$estapagina?comienzo=" . ($comienzo + $num) . "&agente=" . $_GET['agente'] . "&inicio=" . $_GET['inicio'] . "&fin=" . $_GET['fin'] . "'>Siguiente</A> ]\n");
            else
                print ("Siguiente ]\n");
        }
        print ("</P>\n");
    }

// Enviar consulta
    if ($_GET['agente'] == "Todos") {
        $instruccion = "select * from webchat_lineas order by ts desc limit $comienzo, $num";
    } else {
        $instruccion = "select * from webchat_lineas where emisor='$_GET[agente]' or receptor='$_GET[agente]' order by ts desc limit $comienzo, $num";
    }

    $consulta = mysql_query($instruccion, $conexion)
            or die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");

// Mostrar resultados de la consulta
    $nfilas = mysql_num_rows($consulta);
    if ($nfilas > 0) {
        print ("<TABLE WIDTH='650'>\n");
        print ("<TR>\n");
        print ("<TH WIDTH='75'>Emisor</TH>\n");
        print ("<TH WIDTH='75'>Receptor</TH>\n");
        print ("<TH WIDTH='300'>Texto</TH>\n");
        print ("<TH WIDTH='150'>Fecha y hora</TH>\n");
        print ("</TR>\n");

        for ($i = 0; $i < $nfilas; $i++) {
            $resultado = mysql_fetch_array($consulta);
            print ("<TR>\n");
            print ("<TD>" . $resultado['emisor'] . "</TD>\n");
            print ("<TD>" . $resultado['receptor'] . "</TD>\n");
            print ("<TD>" . $resultado['texto'] . "</TD>\n");
            print ("<TD>" . $resultado['ts'] . "</TD>\n");
            print ("</TR>\n");
        }

        print ("</TABLE>\n</div>");
    } else
        print ("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No hay registros disponibles</span></h1></div></div></div>");
    $_GET['agente'] = "Todos";
    $_GET['inicio'] = "2016-01-01";
    $_GET['fin'] = date('Y-m-d');
// Cerrar conexi�n
    mysql_close($conexion);
    ?>

