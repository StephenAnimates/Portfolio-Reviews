<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->

<script language="javascript" type="text/javascript" src="scripts/dist/jquery.jqplot.min.js"></script>

<link rel="stylesheet" type="text/css" href="scripts/dist/jquery.jqplot.css" />
<?php
include_once ("constants.php");

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


// this may be able to give me the total averages, and the number of students in the review.

//SELECT students.program, scores.port_type, scores.port_term, AVG(scores.S1), AVG(scores.S2), AVG(scores.S3), AVG(scores.S4), AVG(scores.S5), AVG(scores.S6), COUNT(DISTINCT scores.student_id_fk) FROM students, scores WHERE scores.port_type = 3 AND scores.port_term = "Spring_2014" AND scores.student_id_fk = students.id AND students.program = "MAA" GROUP BY scores.port_term

//SELECT students.program, students.campus, scores.port_type, scores.port_term, AVG(scores.S1), AVG(scores.S2), AVG(scores.S3), AVG(scores.S4), AVG(scores.S5), AVG(scores.S6), COUNT(DISTINCT scores.student_id_fk) FROM students, scores WHERE scores.student_id_fk = students.id GROUP BY students.campus, students.program, scores.port_term, scores.port_type
,
// old one
//SELECT students.program, scores.* FROM students, scores WHERE scores.port_type = %s AND scores.port_term = %s AND scores.student_id_fk = students.id AND students.program = %s GROUP BY score_id

mysql_select_db($database_studentReviews, $studentReviews);
$query_RS_deptScores = sprintf("SELECT students.program, scores.port_type, scores.port_term, AVG(scores.S1) AS S1, AVG(scores.S2) AS S2, AVG(scores.S3) AS S3, AVG(scores.S4) AS S4, AVG(scores.S5) AS S5, AVG(scores.S6) AS S6, COUNT(DISTINCT scores.student_id_fk) AS totalStudents FROM students, scores WHERE scores.port_type = %s AND scores.port_term = %s AND scores.student_id_fk = students.id AND students.program = %s GROUP BY scores.port_term", GetSQLValueString($port_type, "int"),GetSQLValueString($port_term, "text"),GetSQLValueString($program, "text"));
$RS_deptScores = mysql_query($query_RS_deptScores, $studentReviews) or die(mysql_error());
$row_RS_deptScores = mysql_fetch_assoc($RS_deptScores);
$totalRows_RS_deptScores = mysql_num_rows($RS_deptScores);
mysql_select_db($database_studentReviews, $studentReviews);

// SELECT scores.S1, scores.S2, scores.S3, scores.S4, scores.S5, scores.S6, scores.port_type FROM `scores` WHERE scores.student_id_fk = %s ORDER BY score_id

 /*
Okay, for now, what I did was create a table for each of the 3 diffrent review types. The query for this runs on the entire page, and then I merely filtered it for the type of review. This will work temporarily, but not long term.
            
What would be better is to do this as an include, and run the query on each individual section, setting a variable for the type of review (target the "port_type" of the table. Then the appriate info can be generated for each of the individual reviews. This will make the sql statements easier, and less prone to error, and will simplify the creation of each section. It's more of an objecct oriented approach.
            
create a table to show the actual scores - testing purposes
            
            
*/

// this include requires posted values
// port_type
// port_term
// campus
// progCode

// set up variables, arrays for data
$arr_slo = array();
$avg = array();
// set a default slo count
$sloCount = 6;

// if it's gad, then make it 5
if ($program == "gad") {
	$sloCount = 5;
}

switch ($port_type) {
	case 1:
		$port_name = "First (4th Quarter)";
		break;
	case 2:
		if ($program == "phoa") {
			$port_name = 'Final';
		};
		$port_name = 'Second (8th Quarter)';
		break;
	case 3:
		$port_name = 'Final';
		break;
}
	
echo "<p><strong>Term:</strong> ".$port_term."</p><p><strong>Review:</strong> ".$port_name.'</p><p><strong>Program:</strong> '.$program.'</p>';?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <!-- th scope="col">ID</th -->
    <th scope="col" width="100">Num of Students</th>
    <th scope="col" width="100">SLO1</th>
    <th scope="col" width="100">SLO2</th>
    <th scope="col" width="100">SLO3</th>
    <?php if (!($program == "maa" && $port_type == 1)) { ?>
    <th scope="col" width="100">SLO4</th>
    <?php }; ?>
    <th scope="col" width="100">SLO5</th>
    <?php if (!($program == "maa" && $port_type == 1 || $program == "maa" && $port_type == 2)) { 
	if ($sloCount >= 6) { ?>
    <th scope="col" width="100">SLO6</th>
    <?php };
	}; ?>
	</tr>

<?php
echo '<tr>';	

/*
loop through and make the SQL rows, this assumes there will be a total of 6 SLOs, but will eliminate one if there's no data (since sometimes this may happen)
*/
echo '<td align="center">'.$row_RS_deptScores['totalStudents'].'</td>';
echo '<td align="center">'.round($row_RS_deptScores['S1'], 1).'</td>';
echo '<td align="center">'.round($row_RS_deptScores['S2'], 1).'</td>';
echo '<td align="center">'.round($row_RS_deptScores['S3'], 1).'</td>';
if (!($program == "maa" && $port_type == 1)) {
	echo '<td align="center">'.round($row_RS_deptScores['S4'], 1).'</td>';
};
echo '<td align="center">'.round($row_RS_deptScores['S5'], 1).'</td>';
if (!($program == "maa" && $port_type == 1 || $program == "maa" && $port_type == 2)) {
	if ($sloCount >= 6) {
		echo '<td align="center">'.round($row_RS_deptScores['S6'], 1).'</td>';
	};
};
echo '</tr>';
?>
     
  </p>

</table>
<?php
mysql_free_result($RS_deptScores);
?>