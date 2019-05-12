<?php
$query_RS_studentScores = sprintf("SELECT * FROM `scores` AS t WHERE score_id = (SELECT MAX(score_id) FROM `scores` WHERE student_id_fk = %s GROUP BY reviewer_id HAVING reviewer_id = t.reviewer_id)", $studentID);
$RS_studentScores = mysql_query($query_RS_studentScores, $studentReviews) or die(mysql_error());
$row_RS_studentScores = mysql_fetch_assoc($RS_studentScores);
$totalRows_RS_studentScores = mysql_num_rows($RS_studentScores);

// SELECT scores.S1, scores.S2, scores.S3, scores.S4, scores.S5, scores.S6, scores.port_type FROM `scores` WHERE scores.student_id_fk = %s

            /*
            Okay, for now, what I did was create a table for each of the 3 diffrent review types. The query for this runs on the entire page, and then I merely filtered it for the type of review. This will work temporarily, but not long term.
            
            What would be better is to do this as an include, and run the query on each individual section, setting a variable for the type of review (target the "port_type" of the table. Then the appriate info can be generated for each of the individual reviews. This will make the sql statements easier, and less prone to error, and will simplify the creation of each section. It's more of an objecct oriented approach.
            
            create a table to show the actual scores - testing purposes
            
            
            */
			//echo '<table>';
			
			// set up variables, arrays for data
			$arr_slo = array();
			$avg = array();
			
			// set a default slo count
			$sloCount = 5;
			
			// if it's maa, then make it 6
			if ($progCode == "maaa") {
				$sloCount = 6;
			}
			
			// of course if it's maa, and it's port 1, turn it back to 5
			if ($port_type == "1") {
				$sloCount = 5;
			}
			
			// this will create the number in the multidimensional array
			// for the do loop
			$n = 0;
			do {
				//echo '<tr>';
				for ($i = 1; $i <= $sloCount; $i++) {
					if (($row_RS_studentScores['S'.$i] != "") && ($row_RS_studentScores['port_type'] == $port_type)) {
                    $arr_slo[$i][$n] = $row_RS_studentScores['S'.$i];
              //show this for testing
			  //echo '<p>port type: '.$row_RS_studentScores['port_type'].'</p>';
              //echo '<td>slo: '.$i.' value: '.$row_RS_studentScores['S'.$i].'</td>';
                }
            }
    
                // show for testing
               //echo '</tr>';
                $n++;
            } while ($row_RS_studentScores = mysql_fetch_assoc($RS_studentScores));
            
            // show for testing
            //echo '</table>';
            
            // now loop through all the SLOs to create an array for averages
			for ($i = 1; $i <= $sloCount; $i++) {
				//if ($arr_slo[$i] != "") {
				// if the sum of the slo is 0, this means it was no entered, an should not be calculated.
                    //echo '<p>';
                    $avg[$i] = array_sum($arr_slo[$i]) / count($arr_slo[$i]);
                    //echo 'slo '.$i.' '.array_sum($arr_slo[$i]).' / '.count($arr_slo[$i]). ' = '.$avg[$i];
                    //echo "</p>";
            }
                // create total average
                $avgTotal = array_sum($avg) / count($avg);
            ?>
    
      <table>
      <?php
      // loop through and make the SQL rows, this assumes there will be a total of 6 SLOs, but will eliminate one if there's no data (since sometimes this may happen)
      for ($i = 1; $i <= $sloCount; $i++) {
		  
		  /* if there's no slo6 and if this is the 1st review, and slo6, don't show it
		  */
		  
            //if (($arr_slo[$i] != "") || (($i != 6) && ($port_type != 1))) {
                echo '<tr>
          <th scope="row" class="heading" data-progCode="./SLO/'.$progCode.'_header_slo'.$i.'.php">';
                include ('./SLO/'.$progCode.'_header_slo'.$i.'.php');
				
                echo '</th>
          <td data-stuid="'.$studentID.'">';
                echo round($avg[$i], 1);
              echo '</td>
          </tr>';
        }
            ?>
    
          <tr>
            <th scope="row" class="heading right">Total Average</th>
            <td><?php echo round($avgTotal, 1); ?></td>
          </tr>
        </table>
        <?php mysql_free_result($RS_studentScores); ?>