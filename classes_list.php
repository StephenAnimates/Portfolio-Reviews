<?PHP
require_once('Connections/classList.php');
$query_RS_classes = "SELECT `classSchedule`.`classes`.*, `classSchedule`.`progs`.* FROM `classSchedule`.`classes`, `classSchedule`.`progs` WHERE `progs`.`class_id` = `classes`.`id_classes`";
$RS_classes = mysql_query($query_RS_classes, $studentReviews) or die(mysql_error());
$row_RS_classes = mysql_fetch_assoc($RS_classes);
$totalRows_RS_classes = mysql_num_rows($RS_classes);
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Course List</title>
<?php include ("include/inc_jquery.php");?>

<script type="text/javascript" language="javascript" src="scripts/jquery.dataTables.js"></script>

<script type="text/javascript" language="javascript" src="scripts/jquery.ddslick.min.js"></script>
<script src="scripts/oTable.js"></script>

<link href="../css/review.css" rel="stylesheet" type="text/css">

<script type="text/javascript" charset="utf-8">

$(document).ready(function() {
	
	$('#class_table').dataTable( {
		"bJQueryUI": true,
		stateSave: true,
        "oLanguage": {
            "sSearch": "Search All:"
        },
		"sPaginationType": "full_numbers",
		"iDisplayLength": 30,
		"aLengthMenu": [[30, 40, 50, -1], [30, 40, 50, "All"]]
	});
	
	var table = $('#class_table').DataTable();
	
	 $('#class_table tfoot th').each( function ( i ) {
	//alert($(this).attr('class'));
		 if ($(this).attr('id') == 'filtered') {
			 var select = $('<select><option value="">All</option></select>')
            .appendTo($(this).empty())
            .on('change', function () {
                table.column(i)
                    .search($(this).val(), true, false)
                    .draw();
            });
 
        table.column(i).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
		};
    });

});
</script>

</head>

<body>
<div class="container">
<table cellspacing="0" cellpadding="0" id="class_table" class="hover display" >
<thead>
  <tr role="row">
      <th width="80">Program</th>
      <th width="80">Class Number</th>
      <th width="450">Class Title</th>
      <th width="80">Version</th>
      <th width="80">Spring 2015</th>
      <th width="80">Summer 2015</th>
      <th width="80">Fall 2015</th>
      <th width="80">&nbsp;</th>
  </tr>
  </thead>
   <?PHP do { ?>
  <tr>
    <td><?PHP echo $row_RS_classes['prog_abbr']; ?></td>
    <td><?PHP echo $row_RS_classes['class_num']; ?></td>
    <td><?PHP echo $row_RS_classes['class_title']; ?></td>
    <td><?PHP echo $row_RS_classes['version']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <?PHP } while ($row_RS_classes = mysql_fetch_assoc($RS_classes)); ?>
  <tfoot>
    	<tr>
            <th id="filtered" class="program"></th>
            <th></th>
            <th></th>
            <th id="filtered" class="type"></th>
            <th id="filtered" class="spring"></th>
            <th id="filtered" class="summer"></th>
            <th id="filtered" class="winter"></th>
            <th></th>
       </tr>
    </tfoot>
</table>
</div>
<?PHP include ("include/inc_footer.php"); ?>
