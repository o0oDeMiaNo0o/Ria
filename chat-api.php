<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

$conexion = mysql_connect($sql_location, $db_user, $db_pass);
mysql_select_db($db_base, $conexion);

// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
        header('Content-type: application/json');
        $user1 = $_GET['user1'];
        $user2 = $_GET['user2'];
        $cantMensajes = $_GET['cantMensajes'];
        $queryMessagesString = "select wcl.* from webchat_lineas wcl where (wcl.receptor = '".$user1."' and wcl.emisor = '".$user2."') or (wcl.emisor = '".$user1."' and wcl.receptor = '".$user2."') order by wcl.ts limit ".$cantMensajes.",10 ";
        $queryMessages = mysql_query($queryMessagesString, $conexion);
        if($queryMessages === FALSE) { 
            die(mysql_error()); // TODO: better error handling
        }
        $chatMessages = array();
        while($res = mysql_fetch_array($queryMessages)) {
            array_push($chatMessages,$res);
        }
        //var_dump($chatMessages);
        echo json_encode($chatMessages);
      break;
  case 'PUT':
      break;
  case 'POST':
      break;
  case 'DELETE':
      break;
}
