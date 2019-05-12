<?php

require_once('Connections/studentReviews.php');
include_once ('include/constants.php');

mysql_select_db($database_studentReviews, $studentReviews);
$query_RS_deptScores = sprintf("SELECT students.program, students.campus, avgscores.port_term, avgscores.port_type, ROUND(AVG(NULLIF(avgscores.S1,0)),1) AS S1, ROUND(AVG(NULLIF(avgscores.S2,0)),1) AS S2, ROUND(AVG(NULLIF(avgscores.S3,0)),1) AS S3, ROUND(AVG(NULLIF(avgscores.S4,0)),1) AS S4, ROUND(AVG(NULLIF(avgscores.S5,0)),1) AS S5, ROUND(AVG(NULLIF(avgscores.S6,0)),1) AS S6 FROM `students`, (SELECT students.program, students.campus, scores.*, COUNT(scores.student_id_fk) AS totalstudents, max(scores.timestamp) FROM `students`, `scores` WHERE scores.student_id_fk = students.id GROUP BY scores.reviewer_id, scores.student_id_fk ORDER BY student_id_fk) AS avgscores WHERE avgscores.student_id_fk = students.id GROUP BY students.campus, students.program, avgscores.port_term, avgscores.port_type ORDER BY students.campus, students.program, avgscores.port_term, avgscores.port_type;");
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

<?php do {
    echo '<tr>';
	
	// load the elements for each row in the table
	echo '<td align="center">'.$row_RS_deptScores['totalstudents'].'</td>';
	echo '<td align="center">'.$row_RS_deptScores['port_term'].'</td>';
	echo '<td>'.$row_RS_deptScores['port_type'].'</td>';
	echo '<td>'.$row_RS_deptScores['program'].'</td>';
	echo '<td>'.$row_RS_deptScores['campus'].'</td>';
	
	for ($i = 1; $i <= $sloCount; $i++) {
		echo '<td>'.$row_RS_deptScores['S'.$i].'</td>';
	}
	// end the row
	echo '</tr>';

} while ($row_RS_deptScores = mysql_fetch_assoc($RS_deptScores));

mysql_free_result($RS_deptScores);
?>
