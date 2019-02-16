<?php require_once('Connections/studentReviews.php');

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_studentReviews, $studentReviews);
$query_studentNamesRS = sprintf("SELECT students.lastname, students.firstname, students.program, students.id, students.port_term, students.grad_term FROM students ORDER BY students.lastname");
$studentNamesRS = mysql_query($query_studentNamesRS, $studentReviews) or die(mysql_error());
$row_studentNamesRS = mysql_fetch_assoc($studentNamesRS);
$totalRows_studentNamesRS = mysql_num_rows($studentNamesRS);
?>
<?php do { ?>
<li class="student-list" data-port-term="<?php echo $row_studentNamesRS['port_term']; ?>" data-grad-term="<?php echo $row_studentNamesRS['grad_term']; ?>" data-program="<?php echo $row_studentNamesRS['program']; ?>">
  <a value="<?php echo $row_studentNamesRS['id']; ?>" title="<?php echo $row_studentNamesRS['lastname']; ?>_<?php echo $row_studentNamesRS['firstname']; ?>"><?php echo $row_studentNamesRS['lastname']; ?>, <?php echo $row_studentNamesRS['firstname']; ?>
</a>
</li>
<?php } while ($row_studentNamesRS = mysql_fetch_assoc($studentNamesRS)); ?>

<script>

$(document).ready(function(){
	
	//alert('ready');
	
	getValues();

	//when a link in the filters div is clicked...  
    $('#cbo_port_term').change(function(e){ 
	
		getValues();
  
    });
	
	$('#cbo_program').change(function(e){
		
		getValues();
		
    });
});
	
function getValues(){
		
		$('.student-list').hide();
		
		var termVal = $('select#cbo_port_term').val();
		var programVal = $('select#cbo_program').val();
		
		//alert(termVal + " " + programVal);
		
		if ($('#cbo_port_term > option:selected').prevAll().length == 1) {
			$('.student-list[data-port-term="'+termVal+'"]').show();
			$('.student-list[data-grad-term=""]').show();
			$('.student-list:not([data-program="'+programVal+'"])').hide();
		} else {
			$('.student-list[data-port-term="'+termVal+'"]').show();
			$('.student-list:not([data-program="'+programVal+'"])').hide();
		}
};
</script>
<?php
mysql_free_result($studentNamesRS);
?>
