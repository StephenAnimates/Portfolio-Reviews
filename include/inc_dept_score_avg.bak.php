<?php require_once('Connections/studentReviews.php');?>
<?php
include ("include/constants.php");

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
$query_RS_deptScores = sprintf("SELECT students.program, students.campus, avgscores.port_term, avgscores.port_type, ROUND(AVG(NULLIF(avgscores.S1,0)),1), ROUND(AVG(NULLIF(avgscores.S2,0)),1), ROUND(AVG(NULLIF(avgscores.S3,0)),1), ROUND(AVG(NULLIF(avgscores.S4,0)),1), ROUND(AVG(NULLIF(avgscores.S5,0)),1), ROUND(AVG(NULLIF(avgscores.S6,0)),1) FROM `students`, (SELECT students.program, students.campus, scores.*, max(scores.timestamp) FROM `students`, `scores` WHERE scores.student_id_fk = students.id GROUP BY scores.reviewer_id, scores.student_id_fk ORDER BY student_id_fk) AS avgscores WHERE avgscores.student_id_fk = students.id GROUP BY students.campus, students.program, avgscores.port_term, avgscores.port_type ORDER BY students.campus, students.program, avgscores.port_term, avgscores.port_type;");
$RS_deptScores = mysql_query($query_RS_deptScores, $studentReviews) or die(mysql_error());
$row_RS_deptScores = mysql_fetch_assoc($RS_deptScores);
$totalRows_RS_deptScores = mysql_num_rows($RS_deptScores);
mysql_select_db($database_studentReviews, $studentReviews);

 /*
Okay, for now, what I did was create a table for each of the 3 diffrent review types. The query for this runs on the entire page, and then I merely filtered it for the type of review. This will work temporarily, but not long term.
            
What would be better is to do this as an include, and run the query on each individual section, setting a variable for the type of review (target the "port_type" of the table. Then the appriate info can be generated for each of the individual reviews. This will make the sql statements easier, and less prone to error, and will simplify the creation of each section. It's more of an objecct oriented approach.

// the original statement

SELECT students.program, students.campus, scores.* FROM `students`,`scores` WHERE student_id_fk = students.id GROUP BY score_id ORDER BY campus, program, port_term, port_type;

// for testing

SELECT students.program, students.campus, scores.* FROM `students`,`scores` WHERE student_id_fk = students.id AND port_type = 3 AND port_term = 'Winter_2014' GROUP BY score_id ORDER BY campus, program, port_term, port_type;
            
create a table to show the actual scores - testing purposes

SELECT students.program, students.campus, scores.port_term, scores.port_type, ROUND(AVG(NULLIF(S1,0)),1), ROUND(AVG(NULLIF(S2,0)),1), ROUND(AVG(NULLIF(S3,0)),1), ROUND(AVG(NULLIF(S4,0)),1), ROUND(AVG(NULLIF(S5,0)),1), ROUND(AVG(NULLIF(S6,0)),1) FROM `students`,`scores` WHERE scores.student_id_fk = students.id GROUP BY students.campus, students.program, scores.port_term, scores.port_type ORDER BY students.campus, students.program, scores.port_term, scores.port_type


SELECT students.program, students.campus, scores.port_term, scores.port_type, max(scores.timestamp), scores.reviewer_id, ROUND(AVG(NULLIF(S1,0)),1), ROUND(AVG(NULLIF(S2,0)),1), ROUND(AVG(NULLIF(S3,0)),1), ROUND(AVG(NULLIF(S4,0)),1), ROUND(AVG(NULLIF(S5,0)),1), ROUND(AVG(NULLIF(S6,0)),1) FROM `students`,`scores` WHERE scores.student_id_fk = students.id GROUP BY students.campus, students.program, scores.port_term, scores.port_type, scores.reviewer_id ORDER BY students.campus, students.program, scores.port_term, scores.port_type

// this should give me unique results of the reviewers, with the latest timestamp (the last score they gave)

SELECT students.program, students.campus, scores.*, max(scores.timestamp) FROM `students`, `scores` WHERE scores.student_id_fk = students.id GROUP BY scores.reviewer_id, scores.student_id_fk, students.campus, students.program, scores.port_term, scores.port_type ORDER BY student_id_fk


// so if I need unique results, taken from this same table...

// this is the final sql statement


SELECT students.program, students.campus, avgscores.port_term, avgscores.port_type, ROUND(AVG(NULLIF(avgscores.S1,0)),1), ROUND(AVG(NULLIF(avgscores.S2,0)),1), ROUND(AVG(NULLIF(avgscores.S3,0)),1), ROUND(AVG(NULLIF(avgscores.S4,0)),1), ROUND(AVG(NULLIF(avgscores.S5,0)),1), ROUND(AVG(NULLIF(avgscores.S6,0)),1) FROM `students`, (SELECT students.program, students.campus, scores.*, max(scores.timestamp) FROM `students`, `scores` WHERE scores.student_id_fk = students.id GROUP BY scores.reviewer_id, scores.student_id_fk ORDER BY student_id_fk) AS avgscores WHERE avgscores.student_id_fk = students.id GROUP BY students.campus, students.program, avgscores.port_term, avgscores.port_type ORDER BY students.campus, students.program, avgscores.port_term, avgscores.port_type
            
            
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
$sloCount = 5;

// if it's maa, then make it 6
if ($program == "MAA") {
	$sloCount = 6;
}

switch ($port_type) {
	case 1:
		$port_name = "First (4th Quarter)";
		break;
	case 2:
		$port_name = 'Second (8th Quarter)';
		break;
	case 3:
		$port_name = 'Final';
		break;
}
?>
<?php

foreach($row_RS_deptScores['campus'] as $campus) {
	$arr_campus[$campus] = $campus;
	echo $campus;
		/*
	foreach($row_RS_deptScores['program'] as $program) {
		$arr_campus[$campus][$program] = $program;
		foreach($row_RS_deptScores['port_term'] as $port_term) {
			$arr_campus[$campus][$program][$port_term] = $port_term;
			
			foreach($row_RS_deptScores['port_type'] as $port_type) {
				$arr_campus[$campus][$program][$port_term][$port_type] = $port_type;
	echo $arr_campus[$campus][$program][$port_term][$port_type];
			}
			
		}
	}
		*/
}


//echo '<tr>';
$n = 0;
// loop through each result in the "score" data table
do {
    echo '<tr>';
	//echo '<td align="center">'.$row_RS_deptScores['score_id'].'</td>';
	
	/*
	need to do a little re-writing here. I need to look at every result, and add this to some array where the port_term, term_type, program and campus are calculated individually
	*/
	
	// load the elements for each row in the table
	
	
	echo '<td align="center"></td>';
	echo '<td>'.'</td>';
	echo '<td>'.$arr_portType[$n].'</td>';
	echo '<td>'.$arr_program[$n].'</td>';
	echo '<td>'.$arr_campus[$n].'</td>';
	
	
	for ($i = 1; $i <= $sloCount; $i++) {

		if ($row_RS_deptScores['S'.$i] != "") {
			// create a multidimensinal array where the score is stored for each
			// value for each id. Therefore, row 1 scores will be stored in
			// $arr_slo[1]
			// row 2 scores will be in $arr_slo[2]
			$arr_slo[$i][$n] = $row_RS_deptScores['S'.$i];
			//show this for testing
			echo '<td>'.$row_RS_deptScores['S'.$i].'</td>';
		}
	}
	// end the row for testing
	echo '</tr>';
	echo '<tr>';
	// now for this same loop, calculate the averages
	for ($e = 1; $e <= $sloCount; $e++) {
	echo '<td>';
		$avg[$e] = array_sum($arr_slo[$e]) / count($arr_slo[$e]);
		//echo array_sum($arr_slo[$i]).' / '.count($arr_slo[$i]). ' = '.$avg[$i];
		//echo "</td>";
			
}
	$n++;
} while ($row_RS_deptScores = mysql_fetch_assoc($RS_deptScores)); ?>
<?php
  
/* if there total of the array is 0, don't calculate it. this might not be the best way, but the only way I can think of right now.

The problem is, that if all the reviewers give a student 0, it will not be calculated into the final, and they could pass. However, based on the scoring system right now, there's no way a student could get a 0 from a reviewer.
*/

// now loop through all the SLOs to create an array for averages
//echo '<tr>';
//echo '<td></td>';			


// create total average
//$avgTotal = array_sum($avg) / count($avg);
//echo '</tr>';

/*
loop through and make the SQL rows, this assumes there will be a total of 6 SLOs, but will eliminate one if there's no data (since sometimes this may happen)
*/

/*
echo '<td align="center">'.count($arr_slo).'</td>';
echo '<td>'.$RS_deptScores['port_term'].'</td>';
echo '<td>'.$arr_portTerm[$n].'</td>';
echo '<td>'.$RS_deptScores['program'].'</td>';
echo '<td>Campus</td>';
*/

/*
echo '<td align="center">'.round($avg[1], 1).'</td>';
echo '<td align="center">'.round($avg[2], 1).'</td>';
echo '<td align="center">'.round($avg[3], 1).'</td>';
echo '<td align="center">'.round($avg[4], 1).'</td>';
echo '<td align="center">'.round($avg[5], 1).'</td>';

if ($sloCount >= 6) {
	echo '<td>'.round($avg[6], 1).'</td>';
};

for ($i = 1; $i <= $sloCount; $i++) {
	echo '<td align="center">'.round($avg[$i], 1).'</td>';
}
*/

  // if there's no slo6, don't calculate it
  // if this is slo6, don't show it
  
/*
	if ($i <= $sloCount) {
		//echo '<td align="center" data-slo="'.$i.'" data-porttype="'.$port_type.'" data-avg="'.$avg[$i].'" width="20">';
		//echo '<span class="averages">';
		//echo round($avg[$i], 1);
	  //echo '</span>
	  //echo '</td>';
	}
	*/


echo '</tr>';
?>
<?php
mysql_free_result($RS_deptScores);
?>
