<?PHP
ob_start(); // needed to add this, as it was breaking the header redirects
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

error_reporting (E_ERROR | 0);
//include 'constants.php';
include ("localization.php");
include ("constants.php");
//include 'mail.php';
include ("mail.php");

if(isset($_GET['log_out'])) {
	$Login_Process = new Login_Process;
	$Login_Process->log_out($_SESSION['username'], $_SESSION['password']);
}
class Login_Process {

	var $cookie_user = CKIEUS;
	var $cookie_pass = CKIEPS;

	function connect_db() {
		$conn_str = mysql_connect(DBHOST, DBUSER, DBPASS);
		mysql_select_db(DBNAME, $conn_str) or die ('Could not select Database.');
	}

	function query($sql) {

		$this->connect_db();
		$sql = mysql_query($sql);
		$num_rows = mysql_num_rows($sql);
		$result = mysql_fetch_assoc($sql);
			
	return array("num_rows"=>$num_rows,"result"=>$result,"sql"=>$sql);
	
	}
	function welcome_note() {
			
		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();
			
		if(isset($_COOKIE[$this->cookie_user]) && isset($_COOKIE[$this->cookie_pass])) {		
			$this->log_in($_COOKIE[$this->cookie_user], $_COOKIE[$this->cookie_pass], 'true', 'false', 'cookie'); 
		}
		if(isset($_SESSION['username'])) { 
			return "<a href=\"".Script_URL.Script_Path."main.php\">Welcome ".$_SESSION['first_name']."</a>";
		} else {
			return "<a href=\"".Script_URL.Script_Path."index.php\">Welcome Guest, Please Login</a>";
		}	
	}
	
	function check_login($page) {

		$_SESSION['alert_text'] = "";

		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();
		
		/*echo '<script> alert('.$page.' '.$_SESSION['username'].');</script>'; */

		if(isset($_COOKIE[$this->cookie_user]) && isset($_COOKIE[$this->cookie_pass])){
			$this->log_in($_COOKIE[$this->cookie_user], $_COOKIE[$this->cookie_pass], 'true', $page, 'cookie'); 
		} else if(isset($_SESSION['username'])) { 	
			if(!$page) {
				// this is the only place in the script that will take the user to a page after logging in, if they attempted to enter the page, or if they were logged out
				$page = 'main.php';
				//$page = "main.php";
			}
			header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path.$page); 
			//header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
		} else {
		    return true;
		}
	}

	function check_status($page) {

		$_SESSION['alert_text'] = "";

		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();
		
		/* echo '<script> alert('.$page.' '.$_SESSION['username'].')</script>'; */

		if(!isset($_SESSION['username'])){
			header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
		}
	}

	function log_in($username, $password, $remember, $page, $submit) {

		$_SESSION['alert_text'] = "";
		
		if(isset($submit)) {

		if($submit !== "cookie") {
			$password = md5($password);
		}

		$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND password='$password'");

		if($query['num_rows'] == 1) {
			// just send alert if the account is suspended
			if ($query['result']['status'] == "suspended") {
				return array("error", "Account Suspended, Contact sstudyvin@aii.edu");
			}
			// send message in case the account is pending
			if ($query['result']['status'] == "pending") {
				return array("error", "Account Pending. The administrator has not yet approved your account.");
			}
			// set the session variables
			$this->set_session($username, $password);
			
			if(isset($remember)) {
				$this->set_cookie($username, $password, '+');
			}
			//return array("success", "Login Successful");
			//$alert_text = "Login Successful.";
			//$_SESSION['alert_text'] = $alert_text;
			$_SESSION['alert_text'] = "Login Successful.";
			//header("Location: http://".$_SERVER['HTTP_HOST'].$page);
		} else {
			return array('error', 'Username or Password not recognized.');
		}	
				
		$this->query("UPDATE ".DBTBLE." SET last_loggedin = '".getTimeStamp()."' WHERE username = '$username'");
		
		if (empty($page)) {
			$page = 'main.php';
			/* echo '<script> alert("the page is: '.$page.' the user is: '.$_SESSION['username'].' Location: http://'.$_SERVER['HTTP_HOST'].$page.'");</script>'; */
			//header("Location: http://$_SERVER['HTTP_HOST']$page");
			
		
		}
			
		if ($page == 'false') {
			return true;
		} else {
			//header("Location: http://".$_SERVER['HTTP_HOST'].$page);
			//$location = $_SERVER['HTTP_HOST'].$page;
			//ob_start();
			//header("Location: http://$location");
			/* echo '<script> alert("the page is: '.$page.' the user is: '.$_SESSION['username'].' Location: http://'.$location.'");</script>'; */
			//header("Location: http://");
			header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
			exit();
		}
		
		}
	}
	
	function set_session($username, $password) {
	
			$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND password='$password'");
	
			ini_set("session.gc_maxlifetime", Session_Lifetime); 
			session_start();

			$_SESSION['first_name']		= $query['result']['first_name'];
			$_SESSION['last_name']		= $query['result']['last_name'];
			$_SESSION['email_address']	= $query['result']['email_address'];
			$_SESSION['username']		= $query['result']['username'];
			$_SESSION['info']			= $query['result']['info'];
			$_SESSION['user_level']		= $query['result']['user_level'];
			$_SESSION['password']		= $query['result']['password'];
			$_SESSION['userid']			= $query['result']['userid'];
			$_SESSION['alert_text'] = "";

	}	
	
	function set_cookie($username, $password, $set) {

			if($set == "+")
				{ $cookie_expire = time()+60*60*24*30; }
			else 
				{ $cookie_expire = time()-60*60*24*30; }		
	
			setcookie($this->cookie_user, $username, $cookie_expire, '/');
			setcookie($this->cookie_pass, $password, $cookie_expire, '/');
	
	} 

	function log_out($username, $password, $header) {

	session_start();
	session_unset();
	session_destroy();
    	$this->set_cookie($username, $password, '-');

		if(!isset($header)) {
			//header('Location: ../index.php');
			header("Location: http://".$_SERVER['HTTP_HOST']);
		} else {
			return true;
		}
	
	}

	function edit_details($post, $process) {

		if(isset($process)) {
			
		$first_name		= $post['first_name'];
		$last_name			= $post['last_name'];
		$email_address		= $post['email_address'];
		$info				= $post['info'];
		$username			= $post['username'];
		$password			= $_SESSION['password'];
		$userid				= $post['userid'];
		$_SESSION['alert_text'] = "";

		
		if((!$first_name) || (!$last_name) || (!$email_address) || (!$info)) {
			return array ("error", "Please enter all details.");
		}

		$this->query("UPDATE ".DBTBLE." SET username = '$username', first_name = '$first_name', last_name = '$last_name', email_address = '$email_address', info = '$info' WHERE userid = '$userid'");		

				$this->set_session($username, $password);		
				if(isset($_COOKIE[$this->cookie_pass])) 
				{ $this->set_cookie($username, $pass, '+'); }
				
				// now forward the page to the main page
				//$page = Script_Path."main.php";
				$page = 'main.php';
				//$alert_text = "Details sucessfully changed.";
				//$_SESSION['alert_text'] = $alert_text;
				$_SESSION['alert_text'] = "Details sucessfully changed.";
				//return $alert_text;
				//header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
				header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
		}
	}

	function edit_password($post, $process) {

		if(isset($process)) {

		$pass1		= $post['pass1'];
		$pass2		= $post['pass2'];
		$password	= $post['pass'];
		$username	= $post['username'];
		$_SESSION['alert_text'] = "";
		
		if ((!$password) || (!$pass1) || (!$pass2)) {
			return array('error', 'Missing required details.');
			exit();
		} 
		if (md5($password) !== $_SESSION['password']) {
			return array('error', 'Current password is incorrect.');
			exit();
		}
		if ($pass1 !== $pass2) {
			return array('error', 'New passwords do not match.');
			exit();
		}

		$new = md5($pass1);
		$this->query("UPDATE ".DBTBLE." SET password = '$new' WHERE username = '$username'");

				$this->set_session($username, $new);		
				if(isset($_COOKIE[$this->cookie_pass])) 
				{ $this->set_cookie($username, $pass, '+'); }
				
				// now forward the page to the main page
				//$page = Script_Path."main.php";
				$page = 'main.php';
				//$alert_text = "Password update successful.";
				//$_SESSION['alert_text'] = $alert_text;

				$_SESSION['alert_text'] = "Password update successful.";
				
				//header('Location: http://'.$_SERVER['HTTP_HOST'].$page.'?alert='.$alert_text);
				header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page.'?alert='.$alert_text);

		}
	}

	function Register($post, $process) {

		if(isset($process)) {

		$pass1			= $post['pass1'];
		$pass2			= $post['pass2'];
		$username		= $post['username'];
		$email			= $post['email_address'];
		$first_name	= $post['first_name'];
		$last_name		= $post['last_name'];
		$info			= $post['info'];
		
		$fullname		= $first_name.' '.$last_name;
		
		if((!$pass1) || (!$pass2) || (!$username) || (!$email) || (!$first_name) || (!$last_name) || (!$info)) {
			return array("error", "Some Fields Are Missing");
		}
		if ($pass1 !== $pass2) {
			return array("error", "Passwords do not match");
		}
		$query = $this->query("SELECT username FROM ".DBTBLE." WHERE username = '$username'");
		if($query['num_rows'] > 0){
			return array("error", "Id number is already used, please check your id number and try again.");
		}
		$query = $this->query("SELECT email_address FROM ".DBTBLE." WHERE email_address = '$email'");
		if($query['num_rows'] > 0){
			return array("error", "Email address registered to another account.");
		}
		
		if(Admin_Approvial == true) {
			$status = "pending";
		} else {
			$status = "live";
		}
		
		$this->query("INSERT INTO ".DBTBLE." (first_name, last_name, email_address, username, password, info, status) VALUES ('$first_name', '$last_name', '$email', '$username', '".md5($pass1)."', '".htmlspecialchars($info)."', '$status')");
		
		User_Created($username, $fullname, $email);
		
		// set the page to forward to after success
		//$page = Script_Path."main.php";
		$page = 'main.php';
		// start the session to add the alert text
		session_start();

		if(Admin_Approvial == true) {
			// set alert text for main page
			$_SESSION['alert_text'] = "Sign up was sucessful, your account must be reviewed by the administrator before you can login.";
		} else {
			// set alert text for main page
			$_SESSION['alert_text'] = "Sign up was sucessful, you may now log in.";
		}
		//header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
		header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page);
	}
	
	} 

	function Forgot_Password($get, $post) {
	
	if(!$username) { $username = $get['username']; }
	if (isset($post['username'])) { $username = $post['username'];}
	
	if(!$code) { $code = $get['code']; } 
	if (isset($post['code'])) { $code = $post['code']; }

		if (isset($code)) {
			$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND forgot='$code'");
			
		$first_name = $query['result']['first_name'];
		$last_name = $query['result']['last_name'];
		$fullname = $first_name.' '.$last_name;

		if($query['num_rows'] == 1) {
			return array ('reset', '<!-- !-->');
		} else {
		if(isset($code) && isset($username)) {
			return array ('error', 'Link Invalid, Please Request a new password link.');
		} else {
			return false;
		}
	}
	}
}

	function Request_Password($post, $process) {
		
/*
This function will create the needed info to send a message to the user about the password reset
		
		//if (!$username) { $username = $get['username']; }
		if (isset($post['username'])) { $username = $post['username']; }
		
		//if (!$email) { $email = $get['email']; }
		if (isset($post['email'])) { $email = $post['email']; }

		$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND email_address = '$email'");
		
		$first_name = $query['first_name'];
		$last_name = $query['last_name'];
		$fullname = $first_name.' '.$last_name;
*/
			
		if(isset($process)) {
			
			//list ($fullname, $username, $email) = getFullName($post);
			// get the id number for the user (username)
			if (isset($post['username'])) {
				$username = $post['username'];
			}
			// get the email address for the user
			if (isset($post['email'])) {
				$email = $post['email'];
			}
			
			// this will eventually be a client side validation
			// no need to submit this to get the results
			if((!$username) && (!$email)) {
				return array('error', 'Please enter all information.');
			} elseif (!$username) {
				return array('error', 'Please enter an id number.');
			} elseif (!$email) {
				return array('error', 'Please enter an email address.');
			}
			
			$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND email_address = '$email'");

			if($query['num_rows'] == 0){
				return array('error', 'Account details were not found with this information.');
			}
			
			$first_name = $query['result']['first_name'];
			$last_name = $query['result']['last_name'];
			$fullname = $first_name.' '.$last_name;

		    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
		    srand((double)microtime()*1000000);
		    $i = 0;
		    $pass = '' ;

   			while ($i <= 7) {
   			    $num = rand() % 33;
        		$tmp = substr($chars, $num, 1);
        		$pass = $pass . $tmp;
        		$i++;
    		}
			$code = md5($pass);
			$this->query("UPDATE ".DBTBLE." SET forgot = '$code' WHERE username='$username' AND email_address='$email'");

			Mail_Reset_Password($username, $fullname, $code, $email);
			
			// set the page to forward to after success
			//$page = Script_Path."index.php";
			
			// start the session to add the alert text
			session_start();
			
			$_SESSION['alert_text'] = $fullname.", an email was sent to your address, this will allow you to reset your password.";
			//header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
			//header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
			header("Location: http://".$_SERVER['HTTP_HOST']);
			
		}
	}

	function Reset_Password($post, $process) {

		if(isset($process)) {

			$pass1 = $post['pass1'];
			$pass2 = $post['pass2'];
		
			if ($pass1 !== $pass2) {
				return array ('error', 'New passwords do not match');
			}
			
			$username = $post['username'];
			$code = $post['code'];
			
			$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND forgot='$code'");

			$email = $query['result']['email_address'];
			$first_name = $query['result']['first_name'];
			$last_name = $query['result']['last_name'];
			$fullname = $first_name.' '.$last_name;
		
			$password = md5($pass1);

			$query = $this->query("UPDATE ".DBTBLE." SET password = '$password', forgot = 'NULL' WHERE username = '$username'");
		
			Mail_Reset_Password_Confirmation($fullname, $email, $username);
		
			// set the page to forward to after success
			//$page = Script_Path."index.php";
			// start the session to add the alert text
			session_start();
			
			$_SESSION['alert_text'] = "Password Reset Successful, You may now login.";
			//header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
			header("Location: http://".$_SERVER['HTTP_HOST']);
		}
	}
}

function getFullName($post) {
	# this was used so much, that it should just run as a function
	
		// get the id number for the user (username)
		if (isset($post['username'])) {
			$username = $post['username'];
		}
		// get the email address for the user
		if (isset($post['email'])) {
			$email = $post['email'];
		}
		
		// look up user based on email and id number (more then enough info for this)
			
		$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username='$username' AND email_address = '$email'");
		
		echo $query['result'];
		
		$first_name = $query['result']['first_name'];
		$last_name = $query['result']['last_name'];
		$fullname = $first_name.' '.$last_name;
		
		return array ($fullname, $username, $email);
	}

?>