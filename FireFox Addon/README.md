# Firefox-Addon

Este complemento extraerá la información del usuario de su cuenta de Google y muestra un saludo con su nombre en una notificación del navegador.

Se agrega una extensión al navegador Firefox y cuando el usuario le hace clic, el complemento:

* Usa `identity.launchWebAuthFlow()` para obtener un token de acceso de Google. Se le pide al usuario que inicie sesión en Google, si aún no están conectados (autenticación), entonces le pregunta al usuario si concede el permiso de WebExtension para obtener su información de usuario (si el usuario aun no ha concedido este permiso).

* Valida el token de acceso

* Pasa el token de acceso a una API de Google que devuelve la información del usuario

* Muestra una notificación que contiene el nombre del usuario.
