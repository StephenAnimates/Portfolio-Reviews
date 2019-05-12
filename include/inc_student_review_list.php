<?PHP

require_once ('Connections/studentReviews.php');

$dt = date_create();
//echo date_format($date, 'U = Y-m-d H:i:s') . "\n";
//echo '<!-- dt '.$dt.' -->';

//$dt = new DateTime();
$tz = new DateTimeZone("America/Los_Angeles");
$dt->setTimezone($tz);

$timestamp = $dt->format("Y-m-d H:i:s");

$datetime = explode(" ",$timestamp);
$date = $datetime[0];

//echo '<!-- dt '.$date.' -->';

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_studentReviews, $studentReviews);

$where = "WHERE";

// the $arr_term_name contains the number to term name reference

if (isset($_GET['port1_term'])) {
	// create a variable for the term
	$port1_term = $_GET['port1_term'];
}
if ($port1_term != "") {
	// get the name of the term from the number in the paramaters
	$port_term1_name = $arr_term_name[$port1_term];
}

//echo '<!-- port term name: '.$port_term_name.' term num: '.$port1_term.' -->';

if(isset($_GET['port2_term'])) {
	// create a variable for the term
	$port2_term = $_GET['port2_term'];
	// get the name of the term from the number in the paramaters
	$port_term2_name = $arr_term_name[$port2_term];
	// add this to the "where"
	if ($where != "WHERE") {
		$where .= " AND";
	}
	$where .= " port2_term = '".$port_term2_name."'";
}

if(isset($_GET['port3_term'])) {
	// create a variable for the term
	$port3_term = $_GET['port3_term'];
	// get the name of the term from the number in the paramaters
	$port_term3_name = $arr_term_name[$port3_term];
	// add this to the "where"
	if ($where != "WHERE") {
		$where .= " AND";
	}
	$where .= " port3_term = '".$port_term3_name."'";
}

if(isset($_GET['program'])) {
	$program = $_GET['program'];
}
if ($program != "") {
	if ($where != "WHERE") {
		$where .= " AND";
	}
	$where .= " program = '".$program."'";
}

if ($where == "WHERE") {
	$where = "";
}

echo "<!-- where ".$where." -->";

//$query_RS_students = sprintf("SELECT * FROM students WHERE students.port1_term = %s", GetSQLValueString($_GET['port1_term'], "text"));

//$query_RS_students = sprintf("SELECT * FROM students ".$where);

$query_RS_students = sprintf("SELECT * FROM students");


$query_limit_RS_students = sprintf("%s", $query_RS_students);
$RS_students = mysql_query($query_limit_RS_students, $studentReviews) or die(mysql_error());
$row_RS_students = mysql_fetch_assoc($RS_students);

//echo '<!-- test -->';

if (isset($_GET['totalRows_RS_students'])) {
  $totalRows_RS_students = $_GET['totalRows_RS_students'];
} else {
  $all_RS_students = mysql_query($query_RS_students);
  $totalRows_RS_students = mysql_num_rows($all_RS_students);
}

$queryString_RS_students = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RS_students") == false && 
        stristr($param, "totalRows_RS_students") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RS_students = "&" . htmlentities(implode("&", $newParams));
  }
}
?>

<?php include ("inc_jquery.php");?>

<script type="text/javascript" language="javascript" src="../scripts/jquery.dataTables.js"></script>

<script type="text/javascript" language="javascript" src="../scripts/jquery.ddslick.min.js"></script>

<link href="../css/review.css" rel="stylesheet" type="text/css">

 <style>
.custom-combobox {
	position: relative;
	display: inline-block;
}
.custom-combobox-toggle {
	position: absolute;
	top: 0;
	bottom: 0;
	margin-left: -1px;
	padding: 0;
	/* support: IE7 */
	*height: 1.7em;
	*top: 0.1em;
}
.custom-combobox-input {
	margin: 0;
	padding: 0.3em;
}
ul.ui-autocomplete {
	z-index:99999;
}
</style>


<script src="../scripts/oTable.js"></script>

<script type="text/javascript" charset="utf-8">

$('#rubric-view')
	.click(function() {
	$( "#dialog-form" ).dialog( "open" );
});

$(document).ready(function() {
	
	var oTable = $('#student_table').dataTable( {
		"bJQueryUI": true,
        "oLanguage": {
            "sSearch": "Search All:"
        },
		"sPaginationType": "full_numbers",
		"iDisplayLength": 30,
		"aLengthMenu": [[30, 40, 50, -1], [30, 40, 50, "All"]]
	});
	
	/* Add a select menu for each TH element in the table footer */
	$("tfoot th").each( function ( i ) {
		if ($(this).attr('id') == 'filtered') {
			//alert (this);
			this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
			$('select', this).change( function () {
				oTable.fnFilter($(this).val(), i );
			});
		}
		
		// if the id is the same as the option set from the url parameters, set the value of the drop down
		if ($(this).hasClass('program')) {
			$('select option[value="<?php echo strtoupper($program); ?>"]').prop('selected', true);
			oTable.fnFilter('<?php echo strtoupper($program); ?>', i );
		}
		if ($(this).hasClass('port1_term')) {
			$('select', this).val('<?php echo $port_term1_name; ?>').prop('selected', true);
			oTable.fnFilter('<?php echo $port_term1_name; ?>', i );
		}
		if ($(this).hasClass('port2_term')) {
			$('select', this).val('<?php echo $port_term2_name; ?>').prop('selected', true);
			oTable.fnFilter('<?php echo $port_term2_name; ?>', i );
		}
		if ($(this).hasClass('port3_term')) {
			$('select', this).val('<?php echo $port_term3_name; ?>').prop('selected', true);
			oTable.fnFilter('<?php echo $port_term3_name; ?>', i );
		}
		
	});

	// this will populate the fields of the edit window with the appropraite info
	/*
	$('a#lastname, a#firstname, a#studentID').live('click', function() {
		//autoFillForm($(this));
		//$('#dialog-form').dialog('open');
		return false;
    });
	*/

});
</script>

<div class="container">

<table id="student_table" border="0" align="center">
<thead>
  <tr role="row">
    <th class="ui-state-default" tabindex="0">Student ID</th>
    <th>Last Name</th>
    <th>First Name</th>
    <th width="50">Program</th>
    <th>Campus</th>
    <th>4th Quarter</th>
    <th>Portfolio 1</th>
    <th>Portfolio 2</th>
  </tr>
  </thead>
  <tbody>
  <?PHP do { ?>
    <tr>
      <td><a id="iframe" data-fancybox-width="90%" data-fancybox-height="90%" href="../reviews/inc_review_details.php?studentID=<?PHP echo $row_RS_students['id']; ?>&userid=<?PHP echo $_SESSION['userid']; ?>&campus=<?PHP echo $row_RS_students['campus']; ?>&program=<?PHP echo $row_RS_students['program']; ?>&port1_term=<?PHP echo array_search($row_RS_students['port1_term'], $arr_term_name); ?>&port2_term=<?PHP echo array_search($row_RS_students['port2_term'], $arr_term_name); ?>&port3_term=<?PHP echo array_search($row_RS_students['port3_term'], $arr_term_name); ?>" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['studentID']; ?></a></td>
      <td><a class="iframe" data-fancybox-width="90%" data-fancybox-height="90%" href="../reviews/inc_review_details.php?studentID=<?PHP echo $row_RS_students['id']; ?>&userid=<?PHP echo $_SESSION['userid']; ?>&campus=<?PHP echo $row_RS_students['campus']; ?>&program=<?PHP echo $row_RS_students['program']; ?>&port1_term=<?PHP echo array_search($row_RS_students['port1_term'], $arr_term_name); ?>&port2_term=<?PHP echo array_search($row_RS_students['port2_term'], $arr_term_name); ?>&port3_term=<?PHP echo array_search($row_RS_students['port3_term'], $arr_term_name); ?>" id="lastname" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['lastname']; ?></a></td>
      <td><a class="iframe" data-fancybox-width="90%" data-fancybox-height="90%" href="../reviews/inc_review_details.php?studentID=<?PHP echo $row_RS_students['id']; ?>&userid=<?PHP echo $_SESSION['userid']; ?>&campus=<?PHP echo $row_RS_students['campus']; ?>&program=<?PHP echo $row_RS_students['program']; ?>&port1_term=<?PHP echo array_search($row_RS_students['port1_term'], $arr_term_name); ?>&port2_term=<?PHP echo array_search($row_RS_students['port2_term'], $arr_term_name); ?>&port3_term=<?PHP echo array_search($row_RS_students['port3_term'], $arr_term_name); ?>" id="firstname" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['firstname']; ?></a></td>
      <td id="program"><?PHP echo $row_RS_students['program']; ?></td>
    <td><?PHP echo $row_RS_students['campus']; ?></td>
    <td><?PHP echo $row_RS_students['port1_term']; ?></td>
    <td><?PHP echo $row_RS_students['port2_term']; ?></td>
    <td><?PHP echo $row_RS_students['port3_term']; ?></td>
    </tr>
    
    <?PHP } while ($row_RS_students = mysql_fetch_assoc($RS_students)); ?>
    </tbody>
    <tfoot>
    	<tr>
            <th></th>
            <th></th>
            <th></th>
            <th id="filtered" class="program"></th>
            <th id="filtered" class="campus"></th>
            <th id="filtered" class="port1_term"></th>
            <th id="filtered" class="port2_term"></th>
            <th id="filtered" class="port3_term"></th>
       </tr>
    </tfoot>
</table>
</div>

<?php mysql_free_result($RS_students); ?>