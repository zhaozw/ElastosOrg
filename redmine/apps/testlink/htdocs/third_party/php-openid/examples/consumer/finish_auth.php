<?php

require_once "common.php";
session_start();

define(DB_HOST , 'localhost');
define(DB_USER , 'root');
define(DB_PASS , 'kortide');
define(DB_DATABASENAME , 'bitnami_testlink');
define(DB_TABLENAME , 'users');


define(ROLE_ID , '7');

function escape($thing) {
    return htmlentities($thing);
}

function findUser($name)
{
	$conn = mysql_connect(DB_HOST , DB_USER , DB_PASS);
    mysql_select_db(DB_DATABASENAME , $conn);

    $sql = sprintf("select * from %s where login = '%s'",DB_TABLENAME , $name);
    $result = mysql_query($sql , $conn);

    if ($result)
    {
	$count = mysql_fetch_row($result);
	if (!$count){
		$r = 0;
	}else{
		$r = 1;
	}
    }else{
	$r = 0;
    }

    mysql_free_result($result);  
    mysql_close($conn);


    if ($r == 1){
	return true;
    }else{
	return false;
    }
}

function auth_generate_cookie_string()
{
    $t_val = mt_rand( 0, mt_getrandmax() ) + mt_rand( 0, mt_getrandmax() );
    $t_val = md5( $t_val ) . md5( time() );
    return $t_val;
}

function register($nickname,$password,$email) {
	
	$conn = mysql_connect(DB_HOST , DB_USER , DB_PASS);
    mysql_select_db(DB_DATABASENAME , $conn);

    $sql = sprintf("insert into %s(login , password , email , cookie_string , role_id)values('%s','%s','%s','%s','%s')",DB_TABLENAME , $nickname , md5($password),$email,auth_generate_cookie_string(),ROLE_ID);
    $result = mysql_query($sql , $conn);
	if ($result == 1)
	{
		$r = 1;
	}else{
		$r = 0;
	}
	
	mysql_free_result($result);  
	mysql_close($conn);
	login($nickname,$password);
	return $r;
}

function login($nickname,$password) {
  $str='';
  $str.='<form id = "autologinform" style="display:none;" method="post" name="login_form" action="/testlink/login.php">';
  $str.='<input type="text" name="tl_login" id="login" value="'.$nickname.'" required />';
  $str.='<input type="password" name="tl_password" value="'.$password.'" required />';
  $str.='<input type="submit" name="login_submit" value="Login">';
  $str.='</form>';
  $str.='<script>document.getElementById("autologinform").submit();</script>';
  echo $str;
}

function run() {
    $consumer = getConsumer();

    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        // This means the authentication succeeded; extract the
        // identity URL and Simple Registration data (if it was
        // returned).
        $openid = $response->getDisplayIdentifier();
        $esc_identity = escape($openid);

        $success = sprintf('You have successfully verified ' .
                           '<a href="%s">%s</a> as your identity.',
                           $esc_identity, $esc_identity);

        if ($response->endpoint->canonicalID) {
            $escaped_canonicalID = escape($response->endpoint->canonicalID);
            $success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
        }

        $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

        $sreg = $sreg_resp->contents();
        if(!findUser($sreg['nickname'])) {
		/*$success .= "<form action='finish_auth.php'>";
		if (@$sreg['email']) {
		    $success .= "</br>Email:<input value='".escape($sreg['email']).
		        "' readonly=\"readonly\"/></br>";
		}

		if (@$sreg['nickname']) {
		    $success .= "Nickname:<input name='nickname' value='".escape($sreg['nickname']).
		        "' readonly=\"readonly\"/></br>";
		}

		if (@$sreg['fullname']) {
		    $success .= "Fullname:<input value='".escape($sreg['fullname']).
		        "'/>";
		} else {
		    $success .= "Fullname:<input value=''/>";
		}
		$success .= "</br>Password:<input value='' type='password'/>";
		$success .= "</br>Password Confirm:<input value='' type='password'/>";
		$success .= "</br><input value='Register' type='submit'/>";
                $success .= "</br><input name='action' value='register' type='hidden'/>";
		$success .= "</form>";
		$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);
                */
                register(@$sreg['nickname'],"kortide",@$sreg['email']);
        } 
        else {
		/*$success .= "<form action='finish_auth.php'>";
		$success .= "</br><input value='Login' type='submit'/>";
		$success .= "</br><input name='action' value='login' type='hidden'/>";
		$success .= "</form>";*/
                login(@$sreg['nickname'],"kortide");
        }

	if ($pape_resp) {
            if ($pape_resp->auth_policies) {
                $success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

                foreach ($pape_resp->auth_policies as $uri) {
                    $escaped_uri = escape($uri);
                    $success .= "<li><tt>$escaped_uri</tt></li>";
                }

                $success .= "</ul>";
            } else {
                //$success .= "<p>No PAPE policies affected the authentication.</p>";
            }

            if ($pape_resp->auth_age) {
                $age = escape($pape_resp->auth_age);
                $success .= "<p>The authentication age returned by the " .
                    "server is: <tt>".$age."</tt></p>";
            }

            if ($pape_resp->nist_auth_level) {
                $auth_level = escape($pape_resp->nist_auth_level);
                $success .= "<p>The NIST auth level returned by the " .
                    "server is: <tt>".$auth_level."</tt></p>";
            }

	} else {
            //$success .= "<p>No PAPE response was sent by the provider.</p>";
	}
    }

    include 'index.php';
}
if($_GET['action']=="register"){
	register();
}
else if($_GET['action']=="login"){
	login();
}
else {
	run();
}

?>
