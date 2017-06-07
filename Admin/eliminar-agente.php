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
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */
    session_start();
    if (!isset($_SESSION['admin'])) {
        session_destroy();
        die("<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Por favor,
inicie primero la sesi&oacute;n como administrador</span></h1></div></div></div>");
    } else {

        include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");
        $conexion = mysql_connect($sql_location, $db_user, $db_pass) or
                die("Problemas en la conexion");
        mysql_select_db($db_base, $conexion) or
                die("Problemas en base de datos");

        if (isset($_GET['idagente'])) {
            $id = $_GET['idagente'];
        }

        $registros = mysql_query("select * from agentes WHERE id = $id", $conexion) or
                die("Problemas en el select:" . mysql_error());

        if ($reg = mysql_fetch_array($registros)) {

            $sql = "DELETE FROM agentes WHERE id = $id";
            $res = mysql_query($sql, $conexion);

            header("location:/Admin/listado-agentes.php");
        } else {
            echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>No existe un agente con ese id.</span></h1></div></div></div>";
        }
        mysql_close($conexion);
    }
    echo "</div>";
    ?>

