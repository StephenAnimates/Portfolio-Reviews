<?PHP

// query the database for the averages based on the program
mysql_select_db($database_studentReviews, $studentReviews);
$query_RS_deptScores = sprintf("SELECT students.program, students.campus, scores.port_type, scores.port_term_num, AVG(scores.S1) AS S1, AVG(scores.S2) AS S2, AVG(scores.S3) AS S3, AVG(scores.S4) AS S4, AVG(scores.S5) AS S5, AVG(scores.S6) AS S6, COUNT(DISTINCT scores.student_id_fk) AS totalStudents FROM students, scores WHERE scores.student_id_fk = students.id GROUP BY students.campus, students.program, scores.port_term, scores.port_type");
$RS_deptScores = mysql_query($query_RS_deptScores, $studentReviews) or die(mysql_error());
$row_RS_deptScores = mysql_fetch_assoc($RS_deptScores);
$totalRows_RS_deptScores = mysql_num_rows($RS_deptScores);
mysql_select_db($database_studentReviews, $studentReviews);

include_once ("inc_script_datatable.php");

// set up variables, arrays for data
$arr_slo = array();
$avg = array();
// set a default slo count
$sloCount = 6;
?>

<script language="javascript" type="text/javascript" src="../scripts/dist/jquery.jqplot.min.js"></script>
<link rel="stylesheet" type="text/css" href="../scripts/dist/jquery.jqplot.css" />

<script language="javascript">

$(function() {
	
        "activate": function(event, ui) {
            $( $.fn.dataTable.tables( true ) ).DataTable().columns.adjust();
        }
	
	
	// hide all the validation messages when reloading the page
	$('.validate').hide();
	
	// when leaving the input fields, show the validation message
	$( '.field' ).blur( function () {
		// create a variable to hold the type of validation
		var is_valid = $(this).data('data-validation');
		// make sure the validation is not blank
		if (is_valid != "") {
			$(this).next('span').addClass(is_valid);
		}
		$(this).next('span').show();
	});

});

$(document).ready(function() {
		 alert('test');
	
	$('#program-average-list').dataTable({
		"order": [[ 1, "asc" ]],
		"jQueryUI": true,
		renderer: "bootstrap",
		"language": {
			"search": "Search All:"
		},
		"pagingType": "full_numbers",
		"pageLength": 20,
		"lengthMenu": [[10, 20, 30, 40, -1], [10, 20, 30, 40, "All"]]
    });
	
	var table = $('#program-average-list').DataTable();
	
	 $('#program-average-list tfoot th').each( function ( i ) {
		 if ($(this).hasClass('filtered')) {
			 var select = $('<select><option value="">All</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column(i)
                    .search( $(this).val(), true, false )
                    .draw();
            } );
 
        table.column(i).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
		};
    } );
	
} );
</script>

<table id="program-average-list" class="ui striped ten column compact table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <th scope="col">Term</th>
    <th scope="col">Review Type</th>
    <th scope="col">Program</th>
    <th scope="col" width="200px">Campus</th>
    <th scope="col" width="80px">Num of Students</th>
    <th scope="col" width="80px">SLO1</th>
    <th scope="col" width="80px">SLO2</th>
    <th scope="col" width="80px">SLO3</th>
    <th scope="col" width="80px">SLO4</th>
    <th scope="col" width="80px">SLO5</th>
    <th scope="col" width="80px">SLO6</th>
  </tr>
  </thead>
  <tbody>
    <?php do {
	echo '<tr class="row_edit">';
    echo '<td data-termNum="'.$row_RS_deptScores['port_term_num'].'">'.$arr_term_name[$row_RS_deptScores['port_term_num']].'</td>';
    echo '<td align="center">'.get_port_name($row_RS_deptScores['port_type']).'</td>';
    echo '<td>'.$row_RS_deptScores['program'].'</td>';
    echo '<td>'.$row_RS_deptScores['campus'].'</td>';
	echo '<td align="center">'.$row_RS_deptScores['totalStudents'].'</td>';
	echo '<td align="center">'.round($row_RS_deptScores['S1'], 1).'</td>';
	echo '<td align="center">'.round($row_RS_deptScores['S2'], 1).'</td>';
	echo '<td align="center">'.round($row_RS_deptScores['S3'], 1).'</td>';
	echo '<td align="center">';
	if ($row_RS_deptScores['program'] == "MAA" && $row_RS_deptScores['port_type'] == 1) {
		echo 'N/A';
	} else {
		echo round($row_RS_deptScores['S4'], 1);
	};
	echo '</td>';
	echo '<td align="center">'.round($row_RS_deptScores['S5'], 1).'</td>';
	echo '<td align="center">';
	if (!($row_RS_deptScores['program'] == "MAA" && $row_RS_deptScores['port_type'] == 1 || $row_RS_deptScores['program'] == "MAA" && $row_RS_deptScores['port_type'] == 2 || $row_RS_deptScores['program'] == "GAD")) {
		echo round($row_RS_deptScores['S6'], 1);
	} else {
		echo 'N/A';
	};

	echo '</td></tr>';
} while ($row_RS_deptScores = mysql_fetch_assoc($RS_deptScores)); ?>

  </tbody>
  <tfoot>
    	<tr>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th></th>
            <th></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
            <th class="filtered"></th>
       </tr>
    </tfoot>
</table>