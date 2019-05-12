<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->

<script language="javascript" type="text/javascript" src="scripts/dist/jquery.jqplot.min.js"></script>

<link rel="stylesheet" type="text/css" href="scripts/dist/jquery.jqplot.css" />
<?php
include ("../include/constants.php");

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

// old one
//SELECT students.program, scores.* FROM students, scores WHERE scores.port_type = %s AND scores.port_term = %s AND scores.student_id_fk = students.id AND students.program = %s GROUP BY score_id

mysql_select_db($database_studentReviews, $studentReviews);
$query_RS_deptScores = sprintf("SELECT students.program, scores.port_type, scores.port_term, AVG(scores.S1), AVG(scores.S2), AVG(scores.S3), AVG(scores.S4), AVG(scores.S5), AVG(scores.S6), COUNT(DISTINCT scores.student_id_fk) FROM students, scores WHERE scores.port_type = %s AND scores.port_term = %s AND scores.student_id_fk = students.id AND students.program = %s GROUP BY scores.port_term", GetSQLValueString($port_type, "int"),GetSQLValueString($port_term, "text"),GetSQLValueString($program, "text"));
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
	
echo "<p><strong>Term:</strong> ".$port_term."</p><p><strong>Review:</strong> ".$port_name.'</p><p><strong>Program:</strong> '.$program.'</p>';?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <!-- th scope="col">ID</th -->
    <th scope="col" width="100">Num of Students</th>
    <th scope="col" width="100">SLO1</th>
    <th scope="col" width="100">SLO2</th>
    <th scope="col" width="100">SLO3</th>
    <th scope="col" width="100">SLO4</th>
    <th scope="col" width="100">SLO5</th>
    <?php if ($sloCount >= 6) { ?>
    <th scope="col" width="100">SLO6</th>
    <?php }; ?>
	</tr>
<?php 
$n = 0;
// loop through each result in the "score" data table
do {
    //echo '<tr>';
	//echo '<td align="center">'.$row_RS_deptScores['score_id'].'</td>';
	for ($i = 1; $i <= $sloCount; $i++) {
		if ($row_RS_deptScores['S'.$i] != "") {
			// create a multidimensinal array where the score is stored for each
			// value for each id. Therefore, row 1 scores will be stored in
			// $arr_slo[1]
			// row 2 scores will be in $arr_slo[2]
			$arr_slo[$i][$n] = $row_RS_deptScores['S'.$i];
			//show this for testing
			//echo '<td>'.$row_RS_deptScores['S'.$i].'</td>';
		}
	}
	//echo '</tr>';
	$n++;
} while ($row_RS_deptScores = mysql_fetch_assoc($RS_deptScores)); ?>
  <?php
  
/* if there total of the array is 0, don't calculate it. this might not be the best way, but the only way I can think of right now.

The problem is, that if all the reviewers give a student 0, it will not be calculated into the final, and they could pass. However, based on the scoring system right now, there's no way a student could get a 0 from a reviewer.
*/

// now loop through all the SLOs to create an array for averages
//echo '<tr>';
//echo '<td></td>';			
for ($i = 1; $i <= $sloCount; $i++) {
//echo '<td>';
		$avg[$i] = array_sum($arr_slo[$i]) / count($arr_slo[$i]);
		//echo array_sum($arr_slo[$i]).' / '.count($arr_slo[$i]). ' = '.$avg[$i];
		//echo "</td>";
			
}
// create total average
$avgTotal = array_sum($avg) / count($avg);
//echo '</tr>';
?>

<tr>

<?php	

/*
loop through and make the SQL rows, this assumes there will be a total of 6 SLOs, but will eliminate one if there's no data (since sometimes this may happen)
*/
echo '<td align="center">'.count($arr_slo).'</td>';
for ($i = 1; $i <= $sloCount; $i++) {
  // if there's no slo6, don't calculate it
  // if this is slo6, don't show it

	if ((($i != "6")) || (array_sum($avg[$i]) != "0")) {
		echo '<td align="center" data-slo="'.$i.'" data-porttype="'.$port_type.'" data-avg="'.$avg[$i].'" width="20">';
		
		echo '<span class="averages">';
		echo round($avg[$i], 1);
	  echo '</span>
	  </td>';
	}
}
echo '</tr>';
?>
     
  </p>

</table>
<?php
/*
<script language="javascript">

$(document).ready(function(){
		var arrAvgs = new Array();
        //var s1 = [2, -6, 7, -5];
		$.each($('span.averages'), function(i,v) {
			alert(v.text());
			arrAvgs[i] = v.text();
		});
		
       var ticks = ['a', 'b', 'c', 'd'];
		//alert (arrAvgs);
 
        plot7 = $.jqplot('chartdiv', [arrAvgs], {
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                rendererOptions: { fillToZero: true },
                    pointLabels: { show: true }
            },
            axes: {
                // yaxis: { autoscale: true },
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });
    });
</script>

<div id="chartdiv" style="height:400px;width:300px; "></div>

*/
?>
<?php
mysql_free_result($RS_deptScores);
?>