<?php
//Distintos permisos de acceso a datos del usuario separados por coma (perfil y email)
        $scopes = 'https://www.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&state=%2Fprofile' ;

        $url = 'https://accounts.google.com/o/oauth2/auth?client_id=' . '805646824826-esfpeeul2e6dos33b11ac9c50h1j6ipi.apps.googleusercontent.com' . '&approval_prompt=force&response_type=code&scope=' . $scopes . '&redirect_uri=' . 'https://php-demianpizzo.c9users.io/GoogleAuthentication.php';

header('Location:'. $url);
var_dump($url);
?>

 <a href=”<?php echo  $url  ?>” >Google login</a>