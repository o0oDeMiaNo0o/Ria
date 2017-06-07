<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8"><title> HelpDesk </title> 
        <link rel='stylesheet' type='text/css' href='/CSS/style.css' />
        
    </header>

<body>
    <div id="pagina">
        <header id="cabecera">
            <a href="/index.php"><img src="/imagenes/logo.png" /></a>
        </header>

        <nav id="menu-superior">

            <?php
            session_start();
            // Obtener el entorno de la sesi�n y comprobar
            // que est� definido.
            if (isset($_SESSION['username'])) {
                ?>
                <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/index.php">Inicio</a></li>
                <!--<li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/listado-productos.php">Agentes</a></li>
                <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/listado-juegos.php">Registro</a></li>-->
                <li style="float:right;padding:5px;"><img style="vertical-align:middle;margin-right:5px;" src="/imagenes/cerrar.png" /><a style="font-size:14px;" href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/logout.php">Cerrar sesión</a></li>
                <li style="float:right;padding:5px;"><img style="vertical-align:middle;margin-right:5px;" src="/imagenes/user.png" /><a style="font-size:14px;" href="#"><?php echo $_SESSION['username'] ?></a></li>

                <?php
            }
            ?>
        </nav>

        <body>
            <div id="cuerpo">
                <?php
                error_reporting(0);
                ?>