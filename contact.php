<?php
//Stop if no Ajax request
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
	header('Location: ./');
	exit;
}
if (function_exists ('ini_set')){
	//prevent display errors
  ini_set("display_errors", 0);
  	//but log them
  ini_set('log_errors', 1 ); 
  	//in the document root
  ini_set('error_log', getcwd().'/php_error.log' );
}
//just for development
//error_reporting (E_ALL);

//include the config file
include('config.php');

$the_email = addslashes($_POST["email"]);
$the_name = addslashes(utf8_decode($_POST["name"]));
$the_msg = addslashes(utf8_decode($_POST["msg"]));



$return['success'] = false;
$valid = checkEmail($the_email);

//Send Email?
if($valid){
	$return['success'] = sendIt($the_email, $the_name, $the_msg);	
}
//output success as JSON
echo json_encode($return);






//Functions

//send Mail
function sendIt($email, $name, $msg){
	$subject = 'Mail from '.CONF_DOMAIN;
	$header = 'From: '.$name.' <'.$email.'>' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	return mail(CONF_EMAILADDRESS, $subject, $msg, $header);
}


//verify the email
function checkEmail($email){
	return ($email && preg_match('#^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$#',$email));	
}

?>