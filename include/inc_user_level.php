<?PHP
$user_level = $_SESSION['user_level'];

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

echo $user_type;

?>