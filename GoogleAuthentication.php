<?php
    session_start();

 if (isset($_GET['code'])) {
            $fields=
                array(
                    'code'=>  urlencode($_GET['code']),
                    'client_id'=>  urlencode('805646824826-esfpeeul2e6dos33b11ac9c50h1j6ipi.apps.googleusercontent.com'),
                    'client_secret'=>  urlencode('0eOroIz4PrTLBsM8B8mCEaCa'),
                    //Debe coincidir con el Redirect_uri indicado en la Api console
                    'redirect_uri'=>  urlencode('https://php-demianpizzo.c9users.io/GoogleAuthentication.php'),
                    'grant_type'=>  urlencode('authorization_code')
                     );

            //url-ify the data for the POST
            $fields_string='';
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string=rtrim($fields_string,'&');

           
           
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt( $ch,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
            curl_setopt( $ch,CURLOPT_POST,5);
            curl_setopt( $ch,CURLOPT_POSTFIELDS,$fields_string);
            // Set so curl_exec returns the result instead of outputting it.
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
            //to trust any ssl certificates
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
            //execute post
            $result = curl_exec( $ch);
            //close connection
            curl_close( $ch);
                 
            //extracting access_token from response string
            $response= json_decode($result);
            $accesstoken= $response->access_token;
          
          
            $token_url  =  'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $accesstoken;      
            $responseUserInfo = file_get_contents($token_url);                     
            $user =  json_decode($responseUserInfo);
     
            
             echo '<pre>';
             print_r($user);
             echo '</pre>';
             $_SESSION['googleuser'] = $user->name;
             $_SESSION['googlepicture'] = $user->picture;
             
             header('Location: chat.php');
        }
?>