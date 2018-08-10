<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that

//Set the address(es) this will send to
$sendto1 = 'scross@pgahq.com';
$sendto2 = 'communications@radiussportsgroup.com';

$post = $_POST;
session_start();

$formData = $post;
$_SESSION['formData'] = $formData;

if($post['if_other'] == ''){
	$post['if_other'] = 'N/A';
}

function emptyElementExists($arr) {
	// 1 is true, there's empty values
	// 0 is false, array is fully filled in
	return array_search("", $arr) !== false;
}

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (emptyElementExists($post) == 0) { 
	
	$body = '<b>This is a message from the contact form at pgaimpact.org</b><br /><br >'
			. '<b>Name : </b>' . $post['firstname'] . ' ' . $post['lastname']
			. '<br /><b>Title : </b>' . $post['title']
			. '<br /><b>Company : </b>' . $post['company']
			. '<br /><b>Email : </b>' . $post['email']
			. '<br /><b>Phone : </b>' . $post['phone']
			. '<br /><b>Address : </b>' . $post['address']
			. '<br /><b>City : </b>' . $post['city']
			. '<br /><b>State : </b>' . $post['state']
			. '<br /><b>Zip : </b>' . $post['zip']
			. '<br /><b>Website : </b>' . $post['website']
			. '<br /><b>Ownership : </b>' . implode(', ',$post['ownership'])
			. '<br /><b>Other Specified : </b>' . $post['if_other']
			. '<br /><b>Certified : </b>' . $post['certified']
			. '<br /><b>Products / Services : </b>' . $post['services']
			;
	
	date_default_timezone_set('Etc/UTC');
	
	require 'PHPMailerAutoload.php';
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	
	//Set the hostname of the mail server
	$mail->Host = 'smtp.mailgun.org';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "postmaster@mg.dreamcowebsites.com";
	
	//Password to use for SMTP authentication
	$mail->Password = "a9ad60eba6cca7ae955ea59aaa822edb";
	
	//Set who the message is to be sent from
	$mail->setFrom('postmaster@mg.dreamcowebsites.com');
	
	//Set an alternative reply-to address
	$mail->addReplyTo($post['email']);
	
	//Set who the message is to be sent to
	$mail->addAddress($sendto1);
	$mail->addAddress($sendto2);
	
	//Set the subject line
	$mail->Subject = 'Supplier Database Inclusion form submission from pgaimpact.org';
		
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	
	$mail->Body = $body;
	
	//send the message, check for errors
	if (!$mail->send()) {
	    header('Location: join-supplier-database.php?c=0');
	    exit();
	} else {
		unset($_SESSION['formData']);
	    header('Location: join-supplier-database.php?c=1');
	    exit();
	}
} else {	
    header('Location: join-supplier-database.php?errors=true');
    exit();
}