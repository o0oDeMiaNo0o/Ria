<div id="contenedor-login">

    <?php
    session_start();
// Obtener el entorno de la sesi�n y comprobar
// que est� definido.
    if (!isset($_SESSION['username'])) {
        ?>
        <div id="login-form">
            <h3>Iniciar sesi&oacute;n</h3>
            <form action="<?php $_SERVER["DOCUMENT_ROOT"] ?>/iniciar-sesion.php" method="post">
                <input type="text" name="username" required value="email" onBlur="if (this.value == '')
                                this.value = 'email'" onFocus="if (this.value == 'email')
                                            this.value = ''"> <!-- JS because of IE support; better: placeholder="Usuario" -->
                <input type="password" required name="password" value="Password" onBlur="if (this.value == '')
                                this.value = 'Password'" onFocus="if (this.value == 'Password')
                                            this.value = ''"> <!-- JS because of IE support; better: placeholder="Password" -->
                <input type="submit" value="Login">
                <div class="clearfix">
                    <p><span class="info">?</span><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/recuperar-pass.php">Olvide la contrase&ntilde;a</a></p>
    <!--                    <p><span class="info">!</span><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/registrarse.php">Registrarme</a></p>-->
                </div>
            </form>
        </div><?php
    }
    ?>

    <?php
// Obtener el entorno de la sesi�n y comprobar
// que est� definido.
    if (isset($_SESSION['admin'])) {
        ?>
        <div id="panel">
            <h3>Panel administraci&oacute;n</h3>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/Admin/ingresar-agente.php">A&ntilde;adir agente</a></li>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/Admin/listado-agentes.php">Modificar agente</a></li>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/Admin//listado-consultas.php">Registro de consultas</a></li>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/logout.php">Cerrar sesi&oacute;n</a></li>
        </div> <!-- end panel -->
        <?php
    } elseif ((isset($_SESSION['username']))) {
        ?>
        <div id="panel">
            <h3>Panel de Agente</h3>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/chat.php">Chat</a></li>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/modificar-datos.php">Cambiar datos personales</a></li>
            <li><a href="<?php $_SERVER["DOCUMENT_ROOT"] ?>/logout.php">Cerrar sesi&oacute;n</a></li>
        </div> <!--  end panel -->
    <?php } ?>

</div>