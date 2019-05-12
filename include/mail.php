<?PHP

//ini_set('display_errors', 1); // set to 0 for production version
//error_reporting(E_ALL);

error_reporting (E_ERROR | 0);
//require_once 'constants.php';
include ("constants.php");
			
# Common Headers
$eol = "\r\n";
$random_hash = md5(date('r', time()));
$headers = 'From: '.Email_From.' <'.Email_Address.'>'.$eol;
//$headers .= 'From: '.Email_Address.$eol;
$headers .= 'Reply-To: '.Email_From.' <'.Email_Address.'>'.$eol;
//$headers .= 'Bcc: '.Email_From.' <'.Email_Address.'>'.$eol;
$headers .= 'Bcc: '.Email_From.' <'.Email_Address.'>'.$eol;
$headers .= 'Return-Path: '.Email_From.' <'.Email_Address.'>'.$eol;
$headers .= 'MIME-Version: 1.0'.$eol;
$headers .= 'Content-Type: multipart/alternative; boundary="PHP-alt-'.$random_hash.'"' .$eol;
// these two to set reply address
$headers .= 'X-Mailer: PHP v'.phpversion().$eol;
// These two to help avoid spam-filters


// Email Sent when a user requests to reset their password
function Mail_Reset_Password($username, $fullname, $code, $email) {
	
	/*
	this function requires:
	$fullname is first and last name
	$code is the key for the password reset
	$email is the users email address
	$username is the users id number, and is how the unique identity is returned
	*/
		
		global $headers;
		$subject = Site_Name.' Password Reset Request';
		$message = $fullname.',
		
	Your password for '.Site_Name.' has been requested.
		
	To reset your password please open a web browser with the following link: '.Script_URL.Script_Path.'forgotpassword.php?username='.$username.'&code='.$code.'.
		
	If you cannot click the link, copy it link and paste it in your browser\'s address box.
		
	If you did not request your password please delete this email.
		
	Thanks
	'.Email_From;
	
	return mail($email, $subject, $message, $headers);

}

// Email sent if the user resets the password with the password recovery tool
function Mail_Reset_Password_Confirmation($fullname, $email, $username) {
	
	global $headers;
	$subject = Site_Name.' Password Reset';
	
	$message = $fullname.',
	
Your password for the '.Site_Name.' has been reset. If you did not request that your password was reset please contact '.Admin_Name.' at '.Email_Address.'.
	
Thanks 
'.Email_From;
	
		return mail($email, $subject, $message, $headers);

}

// Email sent when a user signs up to the system
function User_Created($username, $fullname, $email) {

	global $headers;
	$subject = 'Welcome to '.Site_Name;
	$message = $fullname.',

Thanks for signing up to the '.Site_Name.'.';
	
	// if admin approvial is true.
	if(Admin_Approvial == true) {
		$message .= 'You will receive an email from the administrator once your account information has been verified, You can then login with your id number ('.$username.') and password at '.Script_URL.Script_Path.'.';
	
	// If admin approvial is false
	} else {
		$message .= 'You can login with your id number and password at '.Script_URL.Script_Path.'.';
	}
	$message .= '
Thanks 
'.Email_From;

		return mail($email, $subject, $message, $headers);
}

// Email sent to user if the admin changes their status
function Status_Changed($fullname, $email, $level_name, $status) {

	global $headers;
	$subject = Site_Name.' Status Change';
	$message = $fullname;
	$message = $message.',
	
Your account has been activated and your status was set to '.$level_name.'. Should you have any questions please contact '.Admin_Name.' at '.Email_Address.'.
		
Thanks 
';
	$message = $message.Email_From;

	return mail($email, $subject, $message, $headers);

}

function send_admin_confirm($subject, $username, $fullname, $status, $level_name, $email) {
	/*
	This sends an email to the administrator of the site anytime an email is sent to the user. This way there is an additional notification of the message.
	
	I initially wrote this to send a second email, but decided to try the BCC header, to see if this would be a better way. But since I wrote all this already, I figured I would just leave it in place.
	
	*/
	
	switch($subject) {
		case Site_Name.' Password Reset Request':
			$message = 'The user '.$fullname.' has requested a password reset.';
			break;
		case Site_Name.' Status Change':
			$message = 'The user '.$fullname.' status was changed to '.$status.'.';
			break;
		case Site_Name.' Password Reset':
			$message = 'The user '.$fullname.' has successfully reset their password.';
			break;
		case 'Welcome to '.Site_Name:
			$message = 'The user '.$fullname.' has successfully created a profile for the review site. Please log in and confirm the users information.';
			break;
	}
	
	return mail(Email_Address, $subject, $message, $headers, '-f'.Email_Address);
}

// Email Sent to students with results
function Mail_Student_Scores($username, $message, $fullname, $code, $email) {
	
	/*
	this function requires:
	$fullname is first and last name
	$code is the key for the password reset
	$email is the users email address
	$username is the users id number, and is how the unique identity is returned
	*/
		
	global $headers;
	$subject = Site_Name.' Portfolio Review Results';
	
	return mail($email, $subject, $message, $headers);

}


?>