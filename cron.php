<?php

    /*
     * LINUX
     *
     * sudo vim crontab
     * 00   7      *    *    *     root    cd/  && /usr/bin/php -f /var/www/cron_a_ejecutar.php
     * Por ejemplo esto ejecutará todos los días a las 7:00 a.m. el archivo cron.php
     *
     * WINDOWS (tarea programada)
     *
     * archivo bat
     * start iexplore.exe -e localhost/cron.php
     * Configurar la frecuencia 
     *
     */

include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");

$diaActual = $dias[date("w")];

$horaActual = date("H:i");

$conexion = mysql_connect($sql_location, $db_user, $db_pass) or
        die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexi&oacute;n con MySql</span></h1></div></div></div>");

mysql_select_db($db_base, $conexion) or
        die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la selecci&oacute;n de la base de datos</span></h1></div></div></div>");


$instruccion = "select a.nombre as agente from agentes a, dias d where a.nombre = d.nombre and rango = 1 and disponible = 1 and TIMESTAMPDIFF(MINUTE,ts_nodisponible,NOW()) >= 15 and d.dia = '" . $diaActual . "' and a.hora_inicio <= '" . $horaActual . "' and a.hora_fin >= '" . $horaActual . "'";


$consulta = mysql_query($instruccion, $conexion)
        or die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Fallo en la consulta</span></h1></div></div></div>");


$nfilas = mysql_num_rows($consulta);
if ($nfilas > 0) {

    for ($i = 0; $i < $nfilas; $i++) {
        $resultado = mysql_fetch_array($consulta);
        print ($resultado['agente'] . "<BR>");

        $formsent = mail('admin@gtech.com.uy', 'Aviso de Agente No Disponible', 'El agente $resultado[agente] se encuentra no disponible.', 'From: $email \r\n Bounce-to: admin@gtech.com.uy');
        if (!$formsent) {
            echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Lo siento hay un problema con el envio de notificaciones</span></h1></div></div></div>";
        }
    }
}
?>