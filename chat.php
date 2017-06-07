<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/cabecera.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/includes/sidebar.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/config.php");

function cierre() {

    include_once("includes/footer.php");
}

$conexion = mysql_connect($sql_location, $db_user, $db_pass) or
            die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la conexi&oacute;n con MySql</span></h1></div></div></div>");

mysql_select_db($db_base, $conexion) or
            die("<div id='listados'><div id='error' class='msjerror'><h1>Error:</h1><span class='msjerror'>Problemas en la selecci&oacute;n de la base de datos</span></h1></div></div></div>");

register_shutdown_function('cierre');

$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");

$imgPublic = "/Admin/imagenes/agentes/";

$diaActual = $dias[date("w")];

$horaActual = date("H:i");
//var_dump($horaActual, $diaActual);
$userLoggin = null;

if($_SESSION['username']!=null){
    $username = $_SESSION['username'];
    
    $queryAgenteString = "select a.* from agentes a where a.email='".$username."'";
    $queryAgente = mysql_query($queryAgenteString, $conexion);
    
    
    while($res = mysql_fetch_array($queryAgente)) {
        $userLoggin = (object)$res;
        break;
    }
} else {
    if(isset($_SESSION['googleuser'])){
        $username = $_SESSION['googleuser'];
        $userLoggin = (object)['nombre'=> $username];
        $userAnonimoImg = $_SESSION['googlepicture'];
    }else{
        if($_SESSION['anonymous']==null){
            $_SESSION['anonymous'] = substr('anonymous '.uniqid(), 0, 16);
        }
        $username = $_SESSION['anonymous'];
        $userLoggin = (object)['nombre'=> $username];
            if(isset($_SESSION['googlepicture'])){
                $userAnonimoImg = $_SESSION['googlepicture'];
            }else{
                $userAnonimoImg = $imgPublic."default.jpg";
            }
    }
}

if($_POST['op'] == null ){
    $agentes = array();
    
    $instruccion = "select * from agentes a, dias d where a.nombre = d.nombre ".
    "and rango = 1 and d.dia = '".$diaActual."' ".
    "and a.hora_inicio <= CAST('".$horaActual."' AS time) ".
    "and a.hora_fin >= CAST('".$horaActual."' AS time) AND a.activo = 0";

    $qry = mysql_query($instruccion, $conexion);
    
    
    while($res = mysql_fetch_array($qry)) {
        //$agentes[$res['id']] = $res;
        
        $nombre = $res["nombre"];
        $res['img'] = $imgPublic.$nombre.".jpg";
        //var_dump($res["disponible"]);
        //echo "<br>";
        //if($res["disponible"] == 1){
        //if($res["disponible"] == 1){
          //  $res["disponible"] = 0;
            //mysql_query('UPDATE agentes SET disponible = 0 WHERE id = '. $res["id"]."'", $conexion);
        //}
        array_push($agentes,(object)$res);
    }
} else {
    $agenteSelected = null;//(object)array(nombre => 'Vincent Porter', disponible=> true);
    if($_POST['op']){
        switch ($_POST['op']) {
        case 'selectAgent':
            if($_POST['agenteSelectedId']){
                $agentSelectedId = $_POST['agenteSelectedId'];
                $qry = mysql_query("SELECT * FROM agentes WHERE id=$agentSelectedId",$conexion);
                $agenteSelected = mysql_fetch_object($qry);
                $img = "/Admin/imagenes/agentes/";
                $nombre = $agenteSelected->nombre;
                $agenteSelected->img = $img.$nombre.".jpg";
                mysql_query("UPDATE agentes SET disponible = 2 WHERE id = $agenteSelected->id", $conexion);
            }
        break;
        case 'sendMessage':
            if($_POST['agenteSelectedId']){
                $agentSelectedId = $_POST['agenteSelectedId'];
                $qry = mysql_query("SELECT * FROM agentes WHERE id=$agentSelectedId",$conexion);
                $agenteSelected = mysql_fetch_object($qry);
                $img = "/Admin/imagenes/agentes/";
                $nombre = $agenteSelected->nombre;
                $agenteSelected->img = $img.$nombre.".jpg";
            }
            
            $agentSelectedNombre = $_POST['agenteSelectedNombre'];
            $messageText = null;
            if($_POST['messageText']){
                $messageText = $_POST['messageText'];
                $query = "INSERT INTO webchat_lineas (id, receptor, emisor, texto, img, ts) VALUES (NULL,'".($agentSelectedNombre? $agentSelectedNombre : $agenteSelected->nombre)."', '".$userLoggin->nombre."', '".$messageText."', '".$_SESSION['googlepicture']."', Now());";
                mysql_query($query,$conexion);
            }
            if($messageText == "Fin de la conversacion"){
                $agentes = array();
                mysql_query("UPDATE agentes SET disponible = 0 WHERE id = $agentSelectedId");
                $instruccion = "select * from agentes a, dias d where a.nombre = d.nombre and rango = 1 and d.dia = '" . $diaActual . "' and a.hora_inicio <= '" . $horaActual . "' and a.hora_fin >= '" . $horaActual . " AND a.activo = 0";
                //$instruccion = "select * from agentes a where a.rango = 1 AND a.activo = 0";
                $qry = mysql_query($instruccion, $conexion);
                $agenteSelected = null;
                while($res = mysql_fetch_array($qry)) {
                    //$agentes[$res['id']] = $res;
                    $img = "/Admin/imagenes/agentes/";
                    $nombre = $res["nombre"];
                    $res['img'] = $img.$nombre.".jpg";
                    array_push($agentes,(object)$res);
                }
            }
        break;
        case 'refreshChat':
            $agentSelectedNombre = $_POST['agenteSelectedNombre'];
            $messageText = null;
            $agenteSelected = null;
            if($_POST['messageText']){
                $messageText = $_POST['messageText'];
                $query = "INSERT INTO webchat_lineas (id, receptor, emisor, texto, img, ts) VALUES (NULL,'".($agentSelectedNombre? $agentSelectedNombre : $agenteSelected->nombre)."', '".$userLoggin->nombre."', '".$messageText.".', '".$_SESSION['googlepicture']."', Now());";
                mysql_query($query,$conexion);
            }
            if($_SESSION['username'] == null){
                $agentes = array();
                mysql_query("UPDATE agentes SET disponible = 0 WHERE nombre = '".$agentSelectedNombre."'");
                $instruccion = "select * from agentes a, dias d where a.nombre = d.nombre and rango = 1 and d.dia = '" . $diaActual . "' and a.hora_inicio <= '" . $horaActual . "' and a.hora_fin >= '" . $horaActual . " AND a.activo = 0";
                $qry = mysql_query($instruccion, $conexion);
                while($res = mysql_fetch_array($qry)) {
                    //$agentes[$res['id']] = $res;
                    $img = "/Admin/imagenes/agentes/";
                    $nombre = $res["nombre"];
                    $res['img'] = $img.$nombre.".jpg";
                    array_push($agentes,(object)$res);
                }
            }else{
                mysql_query("UPDATE agentes SET disponible = 0 WHERE email = '".$_SESSION['username']."'");
            }
            break;
            
       case 'sendMail':;
            $data = split(',',$_POST['data']);
            $para      = $data[1];
            $titulo    = "";
            $mensaje   = $data[2];
            $cabeceras = 'From: '.$data[0]."\r\n" .
                'Reply-To: '.$data[0]."\r\n" .
                'X-Mailer: PHP/' . phpversion();
                //var_dump($para, $titulo, $mensaje, $cabeceras);
            mail($para, $titulo, $mensaje, $cabeceras);
        }
    }
}

if($agenteSelected!=null){
    
    $query2 = mysql_query("select * from agentes where id = $agenteSelected->id");
    unset($agentes);
    $agentes = array();
    $agente = mysql_fetch_object($query2);
    $agente->img = $imgPublic.$agente->nombre.".jpg";
    unset($agentes);
    $agentes = array();
    array_push($agentes,$agente);
}

$chatMessages = array();
    
$lastMessages = null;

if($agenteSelected!=null){
    $lastUser1 = $userLoggin->nombre;
    $lastUser2 = $agenteSelected->nombre;
    
    $queryMessagesString = "select wcl.* from webchat_lineas wcl where (wcl.receptor = '".$lastUser1."' and wcl.emisor = '".$lastUser2."') or (wcl.emisor = '".$lastUser1."' and wcl.receptor = '".$lastUser2."') ";
    $queryMessages = mysql_query($queryMessagesString." order by wcl.ts", $conexion);
    
    while($res = mysql_fetch_array($queryMessages)) {
        array_push($chatMessages,(object)$res);
    }
} else if($_SESSION['username'] != null){
    $query3 = mysql_query("select * from agentes where email = '".$_SESSION['username']."'",$conexion);
    $agenteConectado = mysql_fetch_object($query3);
    if($agenteConectado->disponible == 2){
        $queryMessagesString = "select wcl.* from webchat_lineas wcl, agentes a where wcl.receptor = a.nombre and a.email='".$username."'";
        $queryMessages = mysql_query($queryMessagesString." order by wcl.ts desc", $conexion);
        $lastMessages = null;
        while($res = mysql_fetch_array($queryMessages)) {
            $lastMessages = (object)$res;
            break;
        }
    }
    $lastUser1 = null;
    $lastUser2 = null;
    
    if($lastMessages!=null){
        $agenteSelected = (object)["nombre"=> $lastMessages->emisor, "img"=>''];
        $lastUser1 = $lastMessages->receptor;
        $lastUser2 = $lastMessages->emisor;
    }
    if($lastUser1!=null && $lastUser2!=null && $lastMessages != null ){
        $queryMessagesString = "select wcl.* from webchat_lineas wcl where (wcl.receptor = '".$lastUser1."' and wcl.emisor = '".$lastUser2."') or (wcl.emisor = '".$lastUser1."' and wcl.receptor = '".$lastUser2."') ";
        $queryMessages = mysql_query($queryMessagesString." order by wcl.ts", $conexion);
        while($res = mysql_fetch_array($queryMessages)) {
            array_push($chatMessages,(object)$res);
        }
        foreach ($chatMessages as $message) {
            if(strlen($message->img)>0){
            $userAnonimoImg = $message->img;
            }
        } 
    }
} ?>
<div id="contenido">
    <input type="hidden" name="agenteSelectedId" value="<?php echo $agenteSelected->id; ?>" id="agenteSelectedId"/>
    <input type="hidden" name="agenteSelectedNombre" value="<?php echo $agenteSelected->nombre; ?>" id="agenteSelectedNombre"/>
    <div class="container-chat clearfix">
        <?php if($_SESSION['username'] == null) { ?>
            <div class="people-list" id="people-list">
                <ul class="list">
                    <?php foreach ($agentes as $agente) { ?>
                        <?php
                        if ($agente->disponible == 2)
                            $status = 'ocupado';
                        else
                            $status = $agente->disponible ? 'desconectado' : 'conectado';
                        $onClick = !$agente->disponible ? 'onclick="selectAgent(' . $agente->id . ')"' : "";
                        $onClickClass = !$agente->disponible ? 'mousePointer' : "";
                        ?>
                        <li class="clearfix <?php echo $onClickClass; ?>" <?php echo $onClick; ?>>
                            <img src="<?php echo $agente->img; ?>" class="ProfileImage" alt="avatar"/>
                            <div class="about">
                                <div class="name"><?php echo $agente->nombre; ?></div>
                                <div class="status">
                                    <i class="fa fa-circle <?php echo $status; ?>"></i> <?php echo $status; ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php }
            if ($agenteSelected != null) { 
                $finConversacion = $lastMessages->texto == "Fin de la conversacion.";
            ?>
            <div class="chat">
                <div class="chat-header clearfix">
                    <?php if($_SESSION['username'] == null ){ ?>
                    <img src="<?php echo $agenteSelected->img; ?>" class="ProfileImage" alt="avatar"/>
                    <?php } else {  ?>
                        <img src="<?php echo $userAnonimoImg; ?>" class="ProfileImage" alt="avatar"/>
                    <?php } ?>
                    <div class="chat-about">
                        <div class="chat-with"><?php echo $agenteSelected->nombre; ?></div>
                        <div class="chat-num-messages"></div>
                    </div>
                    <i class="fa fa-star"></i>
                    <div align="right">
                        <button id="finish" value="<?php echo $agenteSelected->id; ?>" class="botonFinish">Salir
                        </button>
                    </div>
                </div>
                <div id="chat-history" class="chat-history <?php echo $finConversacion? "chat-history-block" : ""; ?>">
                    <ul>
                        <?php foreach ($chatMessages as $message) {
                            $isMe = $userLoggin->nombre == $message->emisor;
                            messageTemplate($isMe,$message);
                        } 
                        ?>
                    </ul>
                </div>
                <div class="chat-message clearfix">
                    <textarea id="mensaje" 
                              name="message-to-send" 
                              placeholder="Escribe tu mensaje" 
                              rows="3" <?php echo $finConversacion? 'readonly="readonly"' : ''; ?> >
                    
                    </textarea>
                    <i class="fa fa-file-o"></i>
                    <i class="fa fa-file-image-o"></i>
                    <button id="sendMessage">Enviar</button>

                </div>
            </div>
        <?php }
        if($agenteSelected == null){ ?>
            <div class="chat">
                <!-- Trigger/Open The Modal -->
                <?php if($_SESSION['username'] == null){ ?>
                    <button id="myBtn">Enviar Mail</button>
                <?php } ?>
                <!-- The Modal -->
                <div id="myModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close">×</span>
                            <h2>Contáctanos</h2>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="form-type" value="contact"/>
                            <div class="col-sm-6">
                                <label data-add-placeholder="">
                                    <input type="text"
                                           id="Nombre"
                                           name="Nombre"
                                           placeholder="Nombre*"
                                           data-constraints="@LettersOnly @NotEmpty"/>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label data-add-placeholder="">
                                    <input type="text"
                                           id="Email"
                                           name="Email"
                                           placeholder="E-mail*"
                                           data-constraints="@Email @NotEmpty"/>
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <label class="textarea" data-add-placeholder="">
                                    <textarea name="message" id="Mensaje" placeholder="Mensaje*"
                                              data-constraints="@NotEmpty"></textarea>
                                </label>

                                <div class="mfControls text-sm-right">
                                    <button class="btn btn-md btn-primary" id="send" type="submit">Enviar mensaje
                                    </button>
                                </div>
                                <div class="mfInfo"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <h3>GTech</h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php function messageTemplate($isMe, $message){
        if($isMe) {?>
            <li class="clearfix">
                <div class="message-data align-right">
                    <span class="message-data-time"><?php echo $message->ts;?></span>
                    <span class="message-data-name"><?php echo $message->emisor;?></span>
                </div>
                <div class="message other-message float-right">
                    <?php echo $message->texto;?>
                </div>
            </li>
        <?php } else {?>
            <li>
                <div class="message-data">
                    <span class="message-data-name"><?php echo $message->emisor;?></span>
                    <span class="message-data-time"><?php echo $message->ts;?></span>
                </div>
                <div class="message my-message">
                    <?php echo $message->texto;?>
                </div>
            </li>
        <?php }
    }?>
   
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="https://codepb.github.io/jquery-template/jquery.loadTemplate-0.4.3.js"></script>
 
    <script type="text/html" id="meMessageTemplate">
        <li class="clearfix">
            <div class="message-data align-right">
                <span class="message-data-time" data-content="ts"></span>
                <span class="message-data-name" data-content="emisor"></span>
            </div>
            <div class="message other-message float-right" data-content="texto">
            </div>
        </li>
    </script>
    
    <script type="text/html" id="messageTemplate">
        <li>
            <div class="message-data">
        	  <span class="message-data-name" data-content="emisor"></span>
        	  <span class="message-data-time" data-content="ts"></span>
        	</div>
        	<div class="message my-message" data-content="texto">
        	</div>
    	</li>
    </script>
    <script type="text/javascript">
            function selectAgent(agenteId){
                redirectWithPostData("/chat.php", {op:'selectAgent', agenteSelectedId : agenteId});
            }
    
            function sendMessage(){
                var agenteId = $("#agenteSelectedId").val();
                var agenteNombre = $("#agenteSelectedNombre").val();
                var message = $("#mensaje").val();
                if(message == "Fin de la conversacion"){
                    redirectWithPostData("/chat.php", {op:'refreshChat', messageText: message, agenteSelectedNombre:agenteNombre});
                }
                else
                    redirectWithPostData("/chat.php", {op:'sendMessage', messageText: message, agenteSelectedId : agenteId, agenteSelectedNombre:agenteNombre});
            }
    
    
            function redirectWithPostData(strLocation, objData, strTarget)
            {
                var objForm = document.createElement('FORM');
                objForm.method = 'post';
                objForm.action = strLocation;
            
                if (strTarget)
                    objForm.target = strTarget;
            
                var strKey;
                for (strKey in objData)
                {
                    var objInput = document.createElement('INPUT');
                    objInput.type = 'hidden';
                    objInput.name = strKey;
                    objInput.value = objData[strKey];
                    objForm.appendChild(objInput);
                }
            
                document.body.appendChild(objForm);
                objForm.submit();
            
                if (strTarget)
                    document.body.removeChild(objForm);
            }
            
            $( document ).ready(function() {
                $("#sendMessage").click(function() {
                  sendMessage();
                });
                //$("#pruebaYobs").click(function() {
                
                var myVar = null;

                myVar = setInterval(myTimer, 1000);
                function myTimer() {
                    clearInterval(myVar);
                    var cantMensajes = $("#chat-history ul").children().length; 
                    $.ajax({
                        url: "chat-api.php?user1=<?php echo $lastUser1;?>&user2=<?php echo $lastUser2;?>&cantMensajes="+cantMensajes
                        //url:"chat-api.php?user1=anonymous 577d9e&user2=agente1&cantMensajes="+cantMensajes
                    }).then(function(data) {
                        $.each(data, function( index, value ) {
                            var newTemp = $("<li>");
                            newTemp.loadTemplate($("#messageTemplate"),value);
                          $("#chat-history ul").append(newTemp.children().first());
                          if(value[3] == "Fin de la conversacion" || value[3] == "Fin de la conversacion."){
                              var agenteNombre = $("#agenteSelectedNombre").val();
                              redirectWithPostData("/chat.php", {op:'refreshChat', agenteSelectedNombre:agenteNombre});
                          }
                        });
                        myVar = setInterval(myTimer, 1000);
                    });
                }
            });

            $(document).ready(function(){
                $("#finish").click(function(){
                    var exit = confirm("Estas seguro que deseas abandonar el chat? ");
            		if(exit==true){
                		//window.location = 'index.php?logout=true';	
                        $('#mensaje').attr("readonly", true);
                        $("#chat-history").toggleClass("chat chat-history-block");
                        $('#mensaje').val("Fin de la conversacion");
                        $("#finish").hide();
                        sendMessage();
                        //redirectWithPostData("/chat.php", {op:'ChangeState', agenteSelectedId : $("#finish").val()});
            		}
                });
            });
    
        // Get the modal
        var modal = document.getElementById('myModal');
        
        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");
        
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        
        var sendbtn = document.getElementById("send");
        
        // When the user clicks on the button, open the modal 
        if(btn!=null){
            btn.onclick = function() {
                modal.style.display = "block";
            };
        }
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };
        
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            } 
        };
        
        sendbtn.onclick = function(){
            var em = $('#Email').val();
            var rec = "admin@gtech.com.uy";
            var mens = $('#Mensaje').val();
            var nom = $('#Nombre').val();
            var data = [em,rec,mens,nom];
            redirectWithPostData("/chat.php", {op:'sendMail', data : data });
        };
        
       // window.onbeforeunload = confirmExit;
        //function confirmExit()
        //{
          //   return "You have attempted to leave this page.  If you have made any changes to the fields without clicking the Save button, your changes will be lost.  Are you sure you want to exit this page?";
        //}
</script>
