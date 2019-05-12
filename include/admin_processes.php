<?PHP
error_reporting (E_ERROR | 0);
include ("constants.php");
include ("mail.php");
include ("localization.php");

class Admin_Process {

	function check_status($page) {

		ini_set("session.gc_maxlifetime", Session_Lifetime); 
		session_start();

		if($_SESSION['user_level'] < 4){
			header("Location: http://".$_SERVER['HTTP_HOST'].Script_Path."index.php?page=".$page); 
		}
	}


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
	
	function update_page($location, $alert_text) {
/*
this function will take the page to forward to, and the alert message will be called on that page indicating that the action was successful.

So by all means at my disposal, this should have worked. The function doesn't seem to have any errors, and when I use the same commands where the function is called, it works. But for some reason this has an error as a function.

This is the sort of stuff that drives me mad. It works there, but doesn't work when passing arguements to a function? Why?

I'm leaving this here to hopefully come back to it one day when I find out why.
*/
		// set the session alert to the message
		$_SESSION['alert_text'] = $alert_text;
		// now forward to the new page
		header('Location: http://'.$_SERVER['HTTP_HOST'].$location);
	}
	
	function Register($post, $process) {

		if(isset($process)) {

		$pass1			= $post['pass1'];
		$pass2			= $post['pass2'];
		$username		= $post['username'];
		$email_address	= $post['email_address'];
		$first_name     = $post['first_name'];
		$last_name		= $post['last_name'];
		$info			= $post['info'];
		$level			= $post['level'];
		$status			= $post['status'];
		
		if((!$pass1) || (!$pass2) || (!$username) || (!$email_address) || (!$first_name) || (!$last_name) || (!$info)) {
			return array ('error', 'Please enter all required information.');
		}
		if ($pass1 !== $pass2) {
			return array ('error', 'Passwords do not match');
		}
		$query = $this->query("SELECT username FROM ".DBTBLE." WHERE username = '$username'");
		if($query['num_rows'] > 0){
			return array ('error', 'The id number entered is already in use. Check the id number and try again. If you know this is correct, please contact '.Email_From);
		}
		$query = $this->query("SELECT email_address FROM ".DBTBLE." WHERE email_address = '$email_address'");
		if($query['num_rows'] > 0){
			return array ('error', 'The email address entered is already in use. Check the id number and try again. If you know this is correct, please contact '.Email_From);
		}

		$this->query("INSERT INTO ".DBTBLE." (first_name, last_name, email_address, username, password, info) VALUES ('$first_name', '$last_name', '$email_address', '$username', '".md5($pass1)."', '".htmlspecialchars($info)."')");
			
		return array ('success', 'User was created.');
	}
	}
	
	function active_users_table() {
		$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'live'");
		$result = $sql['sql'];
		$num_rows = $sql['num_rows'];
		$this->create_table($result, $num_rows, "active_table");
	}

	function suspended_users_table() {
		$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'suspended'");
		$result = $sql['sql'];
		$num_rows = $sql['num_rows'];
		$this->create_table($result, $num_rows, "suspended_table");
	}
	
	function pending_users_table() {
		$sql = $this->query("SELECT * FROM ".DBTBLE." WHERE status = 'pending'");
		$result = $sql['sql'];
		$num_rows = $sql['num_rows'];
		$this->create_table($result, $num_rows, "pending_table");
	}

	function create_table($result, $num_rows, $tableName) {
		
		include ("inc_admin_table_header.php");
	   
		for($i=0; $i<$num_rows; $i++){
			$userid=mysql_result($result,$i,"userid");
			
			$last_name = mysql_result($result,$i,"last_name");
			$first_name = mysql_result($result,$i,"first_name");
			
			$status=substr(mysql_result($result,$i,"status"),0,32);
			$name=ucwords(substr($first_name." ".$last_name,0,30));
			$email_address=substr(mysql_result($result,$i,"email_address"),0,32);
			$info=ucwords(substr(mysql_result($result,$i,"info"),0,32));
			$username=ucwords(substr(mysql_result($result,$i,"username"),0,16));
			$user_level=mysql_result($result,$i,"user_level");
			// this needs to show the title of the level, instead of the number
			
			$user_type = get_port_label($user_level);
/*
switch ($user_level) {
	case 1:
    	$user_type = 'Student';
        break;
	case 2:
    	$user_type = 'Reviewer';
        break;  
	case 3:
    	$user_type = 'Director';
        break; 
	case 4:
    	$user_type = 'Other';
        break;
	case 5:
    	$user_type = 'Administrator';
        break;
}
*/
	
	$last_loggedin=mysql_result($result,$i,"last_loggedin");
	
	echo "
			<tr height=\"35\">
			<td>$name</td>
			<td>$email_address</td>
			<td>$username</td>
			<td>$info</td>
			<td align=\"center\">$last_loggedin</td>
			<td>$user_type</td>
			<td align=\"center\"><a href=\"admin_edituser.php?userid=$userid&username=$username&last_name=$last_name&first_name=$first_name&email_address=$email_address&status=$status&user_level=$user_level&campus=$info\"><span id=\"field-icon\" class=\"fa fa-pencil fa-lg\" alt=\"Edit Users Details\"></span></a>&nbsp;<a href=\"admin_editpass.php?userid=$userid\"><span id=\"field-icon\" class=\"fa fa-unlock-alt fa-lg\" alt=\"Change Users Password\"></span></a>&nbsp;<a href=\"#\" class=\"opener\" id=\"$userid\"><span id=\"field-icon\" class=\"fa fa-trash-o fa-lg\" alt=\"Delete User\"></span></a></td>
	</tr>";
	}
		echo "</tbody>
		<tfoot>
    	<tr>
            <th></th>
            <th></th>
            <th></th>
            <th class=\"filtered\"></th>
            <th></th>
            <th class=\"filtered\"></th>
            <th></th>
       </tr>
    </tfoot>
	</table>";
    
	}

	function list_users() {
	
	   $q = "SELECT * FROM ".DBTBLE."";
	   $result = mysql_query($q);
	   $num_rows = mysql_numrows($result);
	
		echo "<select name=\"username\">";	
	
		for($i=0; $i<$num_rows; $i++){
			$name=mysql_result($result,$i,"username");
			echo "<option value=\"$name\">$name</option>";
		}
		echo "</select>";
	}
	
	function update_user($POST, $change) {
	/*
	This will update the users access level and send them an email
	
	2/26/2014 - Added to the form to change both the access level and make the user active or inactive at the same time. This is common, and helpful.
	
	*/
	
	if(isset($change)) {
	
		$username = $POST['username'];
		$user_level = $POST['level'];
		$status = $POST['status'];
		
		$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username = '$username'");
				
		$first_name = $query['result']['first_name'];
		$last_name = $query['result']['last_name'];
		$email = $query['result']['email_address'];
		
		$fullname = $first_name.' '.$last_name;
		
		$this->query("UPDATE ".DBTBLE." SET user_level = '$level', status = '$status' WHERE username = '$username'");

		Status_Changed($fullname, $email, get_level_name($user_level), $status);
		return $fullname.'\'s access level was changed to '.$level_name.'. And the status was changed to '.$status;
		}	
	}

	function suspend_user($POST, $suspend) {
		
		if(isset($suspend)) {
		
			$username = $POST['username'];
			$status = $POST['status'];
			// I want to get all the info for the user
			//$email = $this->query("SELECT email_address FROM ".DBTBLE." WHERE username = '$username'");
			$query = $this->query("SELECT * FROM ".DBTBLE." WHERE username = '$username'");
				
			$first_name = $query['result']['first_name'];
			$last_name = $query['result']['last_name'];
			$email = $query['result']['email_address'];
			
			$fullname = $first_name.' '.$last_name;
			
			$this->query("UPDATE ".DBTBLE." SET status = '$status' WHERE username = '$username'");
		
			Status_Changed($fullname, $email, '', $status);
			
			return $fullname.'\'s ('.$email.') status was changed to '.$status.'.';
			
		}
	}

	function delete_user($POST, $delete) {
		
		if(isset($delete)) {
		
			$check = $POST['check'];
			$id = $POST['id'];
			
		if ($check == "yes") {	
	 
		$this->query("DELETE FROM ".DBTBLE." WHERE userid = $id");
	
			return array ("error", "User was deleted.<br /><a href=\"admin_center.php\">Admin Center</a>");
	
		} else if ($check == "no") {
		
			return  "User was not deleted.<br /><a href=\"admin_center.php\">Admin Center</a>";
		
		}
		
		} else {
			return "Are you sure you want to delete the user?";
		}
	}
	

	function edit_user($POST, $edit) {
		
		if(isset($edit)) {
		
			$first_name = $POST['first_name'];
			$last_name = $POST['last_name'];
			$info = $POST['info'];
			$email_address = $POST['email_address'];
			$username = $POST['username'];
			$userid = $POST['userid'];
			$user_level = $POST['user_level'];
			$status = $POST['status'];
			$fullname = $first_name.' '.$last_name;
			
			// update the database with the new fields
			$this->query("UPDATE ".DBTBLE." SET first_name='$first_name', last_name='$last_name', email_address='$email_address', info='$info', username='$username', user_level='$user_level', status='$status' WHERE userid='".$userid."'");
			
			// then forward to the admin page, and post the success
			$page = Script_Path."admin/admin_center.php";
			$alert_text = "User Details Updated";
			
			// the function that never worked...
			//update_page($page_location, $alert_msg);
			
			// send email
			Status_Changed($fullname, $email_address, get_level_name($user_level), $status);
			
			// set the session alert to the message
			$_SESSION['alert_text'] = $alert_text;
			// now forward to the new page
			header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
		}
	}
	
	function edit_request($edit) {
		
		if(isset($edit)) {
		$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['userid'].'');
			return $details['result'];
		} else {
		$details = $this->query('SELECT * FROM '.DBTBLE.' WHERE userid = '.$_GET['userid'].'');
			return $details['result'];
		}
		
	}

	function edit_pass($POST, $edit) {
	
			if(isset($edit)) {
		
				$pass1 = $POST['pass1'];
				$pass2 = $POST['pass2'];
				$userid = $POST['userid'];
			
				if ($pass1 !== $pass2) {
					//return "Passwords do not match.";
					$alert_text = "Passwords do not match.";
				}
				
				$this->query("UPDATE ".DBTBLE." SET password = '".md5($pass1)."' WHERE userid = '$userid'");
			
				//return "User password was updated.<br /><a href=\"admin_center.php\">Admin Center</a>";
				$alert_text = "User password was updated.<br /><a href=\"admin_center.php\">Admin Center</a>";
				
				// set the session alert to the message
				$_SESSION['alert_text'] = $alert_text;
				// now forward to the new page
				header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
			
			}
	}
}

	
function get_level_name($level) {
	/*
	this function will assign the name of the user level, based on a number, I use this so often, it should be a function.
	
	echo '<script>alert(\'status change '.$level.'\');</script>';
	*/

	$level_name = "";
	switch ($level) {
		case 1:
			$level_name = "Student";
			break;
		case 2:
			$level_name = "Reviewer";
			break;
		case 3:
			$level_name = "Director";
			break;
		case 4:
			$level_name = "Other";
			break;
		case 5:
			$level_name = "Administrator";
			break;
		}
		return $level_name;
}
?>