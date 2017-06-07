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
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    echo "<div id='listados'>
<div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Coming soon.</span></h1></div></div></div>";

    echo "</div>";
    ?>

