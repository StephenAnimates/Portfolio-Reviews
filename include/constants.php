 <?PHP

# Databse Infomation

############# remember to switch to the remote definitions when uploading this

// Database Server (localhost)
define("DBHOST","localhost");
//define("DBHOST","localhost"); // remote
// Database Username
define("DBUSER","root");
//define("DBUSER","portrev"); // remote
// Database Password
define("DBPASS","gxTG3J$@D"); //m94vf7c2 
//define("DBPASS",""); // remote                       
// Database Name
//define("DBNAME","review");  
define("DBNAME","my_portrev"); // remote, and now the local name
// Database Table
define("DBTBLE","cw_users");                    

# Location Infomation

// Path of script with trailing slashes
define("Script_Path","/");
// URL of script (no trailing slash)
define("Script_URL","http://portrev.altervista.local");
//define("Script_URL","http://portrev.altervista.org"); // remote

# System Infomation

// System Name
define("Site_Name","Portfolio Review");
// Name on system emails
define("Email_From","Portfolio Review Site");
// Webmaster email address
define("Email_Address","me@stephenstudyvin.com");
//define("Email_Address","portrev@altervista.org");
// administrators name sent in emails
define("Admin_Name","Stephen");
// Do not reply email address
define("Non_Reply","donotreply@stephenstudyvin.com");
// timezone
if (!function_exists('getTimeStamp')) {
	function getTimeStamp() {
		$dt = date_create();
		$timeZone = new DateTimeZone("America/Los_Angeles");
		$dt->setTimezone($timeZone);
		$timestamp = $dt->format("Y-m-d H:i:s");
		
		return $timestamp;
	}
	
}

# Session and Cookie Infomation

// Session Lifetime in Seconds
//define("Session_Lifetime", 0*0); 
define("Session_Lifetime", 2000*60); 
// Cookie names
define("CKIEUS","USERNAME");
define("CKIEPS","PASSWORDMD5");

# System Settings
// Require admin approvial for new users
define("Admin_Approvial", true); // true or false
// define the path prefix for the SLOs (just to make it consistent)
define("SLO_Path_Prefix", "../SLO/");

// this sets the current portfolio term. At some point in time, this should
// be determined automatiically, or through a preference in the admin side
define ("portTerm", "Spring 2014");

$portTerm = "Spring 2014";
// this next variable will remove the space, and replace this with an underscore, used when creating directories for the materials
$currentTermID = str_replace(' ', '_', $portTerm);

// setting up an automatically generating term list

// this will establish the starting year and quarter for the term list
$year_start = "2012";
$quarter_start = 2; // winter = 1, spring = 2, summer = 3, fall = 4

// can this be used for all pages that need it?

if (!function_exists('GetSQLValueString')) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
	  if (PHP_VERSION < 6) {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	  }
	
	  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
	
	  switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'".$theValue."'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'".$theValue."'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
	  }
	  echo '<!-- the value is '.$theValue.' -->';
	  return $theValue;
	}
}

if (!function_exists('get_port_label')) {
	function get_port_label($level_num) {
	
		switch ($level_num) {
			case 1:
				$port_label = 'Basic';
				break;
			case 2:
				$port_label = 'Reviewer';
				break;  
			case 3:
				$port_label = 'Advisor';
				break; 
			case 4:
				$port_label = 'Director';
				break;
			case 5:
				$port_label = 'Administrator';
				break;
		}
		return $port_label;
	}
}

// TODO - create an array of user level labels
//define ("arr_level_label", ("Basic","Reviewer","Advisor","Director","Administrator"));
//$arr_level_label ()

/*
Port term key
1 = Winter 2012
2 = Spring 2012
3 = Summer 2012
4 = Fall 2012
5 = Winter 2013
6 = Spring 2013
7 = Summer 2013
8 = Fall 2013
9 = Winter 2014
10 = Spring 2014
11 = Summer 2014
12 = Fall 2014
13 = Winter 2015
14 = Spring 2015
15 = Summer 2015
16 = Fall 2015
*/

// determine the port types

if (!function_exists('get_port_name')) {
	function get_port_name($port_type) {
		switch ($port_type) {
			case 1:
				$port_name = 'First Review';
				break;
			case 2:
				$port_name = 'Second Review';
				break;
			case 3:
				$port_name = 'Final Review';
				break;
		}
		return $port_name;
	}
}

date_default_timezone_set('America/Los_Angeles');

$cur_year = date('Y');
$start_year = "2012";
$cur_month = date('n');
//echo '<!-- the current month: '.$cur_month.' -->';
$cur_quarter = "";
switch ($cur_month) {
	case ($cur_month >= 1 && $cur_month <= 3):
		$cur_quarter = "Winter";
		break;
	case ($cur_month >= 4 && $cur_month <= 6):
		$cur_quarter = "Spring";
		break;
	case ($cur_month >= 7 && $cur_month <= 9):
		$cur_quarter = "Summer";
		break;
	case ($cur_month >= 10 && $cur_month <= 12):
		$cur_quarter = "Fall";
		break;
}
// $n is the times through the loop to set the array key
$n = 1;
$arr_term_name = array();
//echo '<!-- the current year: '.$cur_year.' -->';
// load an array with the years
do {
	for ($i = 1; $i <= 4; $i++) {
		switch ($i) {
			case 1:
				$quarter = 'Winter';
				break;
			case 2:
				$quarter = 'Spring';
				break;
			case 3:
				$quarter = 'Summer';
				break;
			case 4:
				$quarter = 'Fall';
				break;
		}
		// create the array for the term and year
		if (($start_year == $cur_year) && ($quarter == $cur_quarter)) {
			// show this last one, then stop
			//array_push($arr_term, $n, $quarter.' '.$start_year);
			$arr_term_name[$n] = $quarter.' '.$start_year;
			//echo '<!-- term name: '.$arr_term_name[$n].' number: '.$n.' -->';
			break;
		} else {
			// if it's not quite the current year and quarter, then keep going
			$arr_term_name[$n] = $quarter.' '.$start_year;
			//array_push($arr_term, $n, $quarter.' '.$start_year);
			//echo '<!-- term name: '.$arr_term_name[$n].' number: '.$n.' -->';
		}
		$n++;
	}
	$start_year++;
} while ($start_year <= $cur_year);

//echo '<!-- term array count '.$n.' -->';
?>