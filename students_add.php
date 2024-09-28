<?PHP
/*
Portfolio Reviews
https://github.com/pixonti

Copyright (C) 2018 Stephen Studyvin

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

require_once('Connections/studentReviews.php'); ?>
<?php
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
$query_RS_students = "SELECT * FROM students";
$RS_students = mysql_query($query_RS_students, $studentReviews) or die(mysql_error());
$row_RS_students = mysql_fetch_assoc($RS_students);
$totalRows_RS_students = mysql_num_rows($RS_students);

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_studentInfo"])) && ($_POST["MM_studentInfo"] == "deleteStudentInfo")) {
	$deleteSQL = sprintf("DELETE FROM students WHERE id=%s",
						GetSQLValueString($_POST['id'], "int"));

	mysql_select_db($database_studentReviews, $studentReviews);
	$Result1 = mysql_query($deleteSQL, $studentReviews) or die(mysql_error());
}

if ((isset($_POST["MM_studentInfo"])) && ($_POST["MM_studentInfo"] == "editStudentInfo")) {
  $updateSQL = sprintf("UPDATE students SET lastname=%s, firstname=%s, program=%s, url=%s, email=%s, studentID=%s, doc_key=%s, stu_pass=%s, grad_term=%s, port_term=%s WHERE id=%s",
                       GetSQLValueString(htmlentities($_POST['lastname'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['firstname'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['program'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['url'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['studentID'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['doc_key'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['stu_pass'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString(htmlentities($_POST['grad_term'], ENT_QUOTES, 'UTF-8'), "text"),
					   GetSQLValueString(htmlentities($_POST['port_term'], ENT_QUOTES, 'UTF-8'), "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_studentReviews, $studentReviews);
  $Result1 = mysql_query($updateSQL, $studentReviews) or die(mysql_error());
}

if ((isset($_POST["MM_studentInfo"])) && ($_POST["MM_studentInfo"] == "addStudentInfo")) {
  $insertSQL = sprintf("INSERT INTO students (lastname, firstname, program, url, email, studentID, doc_key, stu_pass, grad_term, port_term) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['program'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['studentID'], "text"),
                       GetSQLValueString($_POST['doc_key'], "text"),
                       GetSQLValueString($_POST['stu_pass'], "text"),
                       GetSQLValueString($_POST['grad_term'], "text"),
                       GetSQLValueString($_POST['port_term'], "text"));

  mysql_select_db($database_studentReviews, $studentReviews);
  $Result1 = mysql_query($insertSQL, $studentReviews) or die(mysql_error());
}

mysql_select_db($database_studentReviews, $studentReviews);
$query_RS_students = "SELECT * FROM students";
$query_limit_RS_students = sprintf("%s", $query_RS_students);
$RS_students = mysql_query($query_limit_RS_students, $studentReviews) or die(mysql_error());
$row_RS_students = mysql_fetch_assoc($RS_students);

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

// this sould load the dialog after adding student info, and requesting to add another
$addMore = $_POST["MM_addMore"];

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Students</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript">
</script>

<!-- script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="scripts/jquery-ui/1.10.3/jquery-ui.js" type="text/javascript"></script>
<!-- script src="http://code.jquery.com/ui/jquery.ui.menu.js"></script -->
<!-- script src="http://code.jquery.com/ui/jquery.ui.menubar.js"></script -->

<script type="text/javascript" language="javascript" src="scripts/jquery.dataTables.js"></script>

<script type="text/javascript" language="javascript" src="scripts/jquery.ddslick.min.js"></script>

<link rel="stylesheet" href="jquery-ui-1.9.2.custom/css/smoothness/jquery-ui.css" type="text/css" />

<!-- link href="http://code.jquery.com/ui/1.10.3/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" -->
<link href="css/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
<!-- link rel="stylesheet" type="text/css" href="css/styles.css" -->
<link href="css/review.css" rel="stylesheet" type="text/css">

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


<script src="scripts/oTable.js"></script>

<script type="text/javascript" charset="utf-8">

<?PHP echo 'var addMore = \''.$addMore.'\';' ?>

function fnFormatDetails ( nTr )
{
    //var aData = oTable.fnGetData( nTr );
    var sOut = '<tr class="details" ><td colspan="6"><table id="details" cellpadding="0" cellspacing="0" border="0" style="padding-left:0px;">';
    sOut += '<tr><td width="150" align="right">Portfolio 2 Term:</td><td> <?PHP echo $row_RS_students['port_term'];?></td></tr>';
    sOut += '<tr><td>Scores:</td><td>SLO1: </td></tr>';
    sOut += '<tr><td>Business Cards Uploaded?</td><td></td></tr>';
    sOut += '</table></td></tr>';

    return sOut;
}

function clearForm() {
	/*	this function is designed to clear the fields of the dialog box, that is
		used for both adding and editing students, and clears the previous values.
	*/
	$('input#id').val("");
	$('input#studentID').val("");
	$('input#lastname').val("");
	$('input#firstname').val("")
	$('input#url').val("");
	$('input#email').val("");
	$('input#doc_key').val("");
	$('input#stu_pass').val("");
	$('select#program option:eq(0)').prop('selected', 'true');
	$('select#port_term option:eq(0)').prop('selected', 'true');
	$('select#grad_term option:eq(0)').prop('selected', 'true');

	// now set any prefs for the form
	setPrefs();

}

function autoFillForm(target) {

	/*	this function is designed to fill in the form fields of the dialog box
		with the fileds in the table row associated with the cliked name or id
		of the student, this way it can be edited.

		This takes a single parameter, which is the target of the clicked field
		and uses this to parse though the row.

	*/


	//alert (target);
	var theParent = target.parent();

	//alert (theParent);

		// get the value of the clicked link
        //$('input#studentID').val(target.text());
		//$('input#studentID').val(theParent.prev().children('#studentID').text());

		//alert(target.prop('id'));

		if (target.prop('id') == "studentID") {
			// if the "studentID" is clicked
			var studentID = theParent.find('#studentID').text();
			var lastName = theParent.parent().find('#lastname').text();
			var firstname = theParent.parent().find('#firstname').text();
		} else if (target.prop('id') == "lastname"){
			// if the "last Name" is clicked
			var studentID = theParent.prev().find('#studentID').text();
			var lastName = theParent.find('#lastname').text();
			var firstname = theParent.parent().find('#firstname').text();
		} else if (target.prop('id') == "firstname"){
			// if the "first Name" is clicked
			var studentID = theParent.parent().find('#studentID').text();
			var lastName = theParent.prev().find('#lastname').text();
			var firstname = theParent.find('#firstname').text();
		}
		//alert(theParent.parent().parent().siblings('tr').html());
		$('input#studentID').val(studentID);

		// set the hidden field to the db id of the student
		$('input#id').val(target.attr('record'));
		// now parse the other info
        //$('input#lastname').val($(this).text());
		$('input#lastname').val(lastName);
		$('input#firstname').val(firstname);
		// I added these attributes to an element, so I could read them in
		$('input#doc_key').val(theParent.siblings('#doc_key').text());
		$('input#stu_pass').val(theParent.siblings('#stu_pass').text());
		$('input#url').val(theParent.siblings('#url').children().attr('href'));
		$('input#email').val(theParent.siblings('#email').children().attr('title'));

		var theOption = theParent.siblings('#program').text();
		$('select#program option[value='+theOption+']').prop('selected', 'true');

		var theTerm = theParent.siblings('#grad_term').text();
		//alert (theTerm);
		if (theTerm != "") {
			$('select#grad_term option:contains('+theTerm+')').prop('selected', 'true');
		} else {
			$('select#grad_term option:eq(0)').prop('selected', 'true');
		}

		var portTerm = theParent.siblings('#port_term').text();
		if (portTerm != "") {
			$('select#port_term option:contains('+portTerm+')').prop('selected', 'true');
		} else {
			$('select#port_term option:eq(0)').prop('selected', 'true');
		}

		return false;
}

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

	$('div.dataTables_length').append('<button class="addstudent">Add Student</button>');

	/* Add a select menu for each TH element in the table footer */
	$("tfoot th").each( function ( i ) {
		if ($(this).attr("id") == 'filtered') {
			//alert (this);
			this.innerHTML = fnCreateSelect( oTable.fnGetColumnData(i) );
			$('select', this).change( function () {
				oTable.fnFilter( $(this).val(), i );
			});
		}
	});

	$('td img').live( 'click', function () {
        //var nTr = $(this).parent();
		var nTr = $(this).parents('tr')[0];
		//alert (oTable.fnIsOpen(nTr) + " row? " + $(this).parent().parent().text());
        if ( oTable.fnIsOpen(nTr) ) {
            // This row is already open - close it
            this.src = "icons/details_open.png";
            oTable.fnClose( nTr );
        } else {
            // Open this row
            this.src = "icons/details_close.png";
            oTable.fnOpen( nTr, fnFormatDetails(nTr), 'details' );
        }
    } );

});

	// this will populate the fields of the edit window with the appropraite info

	$('a#lastname, a#firstname, a#studentID').live('click', function() {
		autoFillForm($(this));
		return false;
    });


$(function(){
	/*$('div.dataTables_length select')
	.button({
		icons: {
			primary: "ui-icon-circle-plus"
		}
	});*/

	// when clicked the add student button needs to open the dialog window
	$('button.addstudent')
	.button({
		icons: {
			primary: "ui-icon-circle-plus"
		}
	})
	.click(function() {
		clearForm();
		$('#dialog-form').dialog('open');
		$('#dialog-form').dialog({
			buttons: {
				"Add Student Info": function() {

					$('input[name=MM_studentInfo]').val('addStudentInfo');
					$('#StudentInfo').submit();
				},
				"Add Info & Add Another": function() {

					$('input[name=MM_studentInfo]').val('addStudentInfo');
					// if the "add more" button is pressed, add true to the add more hidden input
					$('input[name=MM_addMore]').val('true');
					$('#StudentInfo').submit();
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			},
			close: function() {
				// not sure I need anything
			}
		});
	});

	$('.clickEdit').live('click', function() {
		$( "#dialog-form" ).dialog( "open" );
		$('#dialog-form').dialog({
			buttons: {
				"Update Student Info": function() {
					$('input[name=MM_studentInfo]').val('editStudentInfo');
					$('#StudentInfo').submit();
				},
				"Delete Student Info": function (){
					$('input[name=MM_studentInfo]').val('deleteStudentInfo');

					var confirmWin = confirm("Are you sure you want to delete this student information?");
					if (confirmWin == true) {
						$('#StudentInfo').submit();
					} else {
						$(this).dialog( "close" );
					}
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			},
			close: function() {
				// not sure I need anything
			}
		});
		return true;
	});

	// open the dialog when clicking the name or student ID in the table
	$('#dialog-form').dialog({
		autoOpen: false,
		height: 500,
		width: 600,
		modal: true,
	});

	// this should trigger the form to open if the "add more" button was pressed
	if (addMore == 'true') {
		openDialog();
		//$('#dialog-form').dialog({autoOpen: true});
	}

});

function openDialog() {
	/* this function is supposed to open the dialog, depending on where it's activated
	*/

	$('#dialog-form').dialog({autoOpen: true});
	clearForm();
	setPrefs();
	$('#dialog-form').dialog({
			buttons: {
				"Add Student Info": function() {

					$('input[name=MM_studentInfo]').val('addStudentInfo');
					$('#StudentInfo').submit();
				},
				"Add Info & Add Another": function() {

					$('input[name=MM_studentInfo]').val('addStudentInfo');
					$('input[name=MM_addMore]').val('true');
					$('#StudentInfo').submit();
				},
				Cancel: function() {
					$(this).dialog( "close" );
				}
			},
			close: function() {
				// not sure I need anything
			}
	});
}

function setPrefs() {
	var currentTerm = "Summer 2013";

	$('select#port_term option:contains(' + currentTerm + ')').attr('selected', 'selected');
}

</script>
</head>

<body id="student_list">

<div class="container">
<div id="dialog-form" title="Edit Student Info">

      <fieldset id="details">
      <form method="post" name="StudentInfo" id="StudentInfo" action="<?PHP echo $editFormAction; ?>">
      	<!-- table border="0" style="width:100%;" id="details" -->
        <!-- tbody -->
        <!-- tr -->
        <!-- td -->
        <oL>

        <li>
        <span>
        <label for="studentID">Student ID</label>
        <input type="text" name="studentID" id="studentID" size="20" class="text ui-widget-content ui-corner-all" value=""/>
        </span>
        </li>
        <li>
        <span>
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" size="20" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>
        <li>
        <span>
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" size="20" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>
        <li>
       <span>
       <div class="ui-widget">
        <label for="program">Program</label>
        <select name="program" id="cbo_program">
        	 <option value="">Select one...</option>
        	<option value="DFVP">DFVP</option>
			<option value="GAD">GAD</option>
           <option value="MAA">MAA</option>
        </select>
        </div>


        </span>
        </li>

        <!-- /td -->
        <!-- /tr -->
        <!-- tr -->
        <!-- td -->

        <li>
        <span>
        <label for="url">Website</label>
        <input type="text" name="url" id="url" size="50" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>
        <li>
        <span>
        <label for="email">email</label>
        <input type="text" name="email" id="email" size="50" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>

        <li>

        <label for="doc_key">Document Key (this is for the google doc)</label>

        </li>
        <li>
        <span>
        <input type="text" name="doc_key" id="doc_key" size="60" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>

        <li>
        <span>
        <label for="stu_pass">password</label>
        <input type="text" name="stu_pass" id="stu_pass" size="20" class="text ui-widget-content ui-corner-all" value="" />
        </span>
        </li>

        <li>
        <span>
        <div class="ui-widget">
        <label for="grad_term">Graduation Term</label>
        <select name="grad_term" id="cbo_grad_term">
        <!-- select name="grad_term" id="grad_term combobox" class="text ui-widget-content ui-corner-all" -->

			<?php
			$sel_option = $row_RS_students['grad_term'];
			//include("include/inc_term_list.php");
			?>

        </select>
        </div>
        </span>
        </li>
        <li>
        <span>
        <label for="port_term" >First Portfolio 2 Term</label>
        <select name="port_term" id="cbo_port_term">
        <!-- select name="port_term" id="combobox" class="text ui-widget-content ui-corner-all" -->

        	<?php
			$sel_option = $row_RS_students['port_term'];
            //
			//include("include/inc_term_list.php");
			?>
        </select>
        </span>
        </li>

        <!-- /td -->
        <!-- /tr -->
        <!-- /tbody -->
        <!-- /table -->

        <input type="hidden" name="id" id="id" value="">
        <input type="hidden" name="MM_studentInfo" value="">
        <input type="hidden" name="MM_addMore" value="false">
        </oL>
  </form>
</fieldset>
</div>

<table id="student_table" border="0" align="center">
<thead>
  <tr role="row">
  <th width="4%"></th>
    <th class="ui-state-default" tabindex="0">Student ID</th>
    <th>Last Name</th>
    <th>First Name</th>
    <th>site</th>
    <th>email</th>
    <th width="50">Program</th>
    <th>First Port 2 Term</th>
    <th>Grad Term</th>
    <th style="display:none;"></th>
    <th style="display:none;"></th>
  </tr>
  </thead>
  <tbody>
  <?PHP do { ?>
    <tr>
    <td class="center"><img src="images/icons/details_open.png"></td>
      <td><a class="clickEdit" id="studentID" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['studentID']; ?></a></td>
      <td><a class="clickEdit" id="lastname" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['lastname']; ?></a></td>
      <td><a class="clickEdit" id="firstname" record="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['firstname']; ?></a></td>
      <td id="url">

      <?PHP if ($row_RS_students['url']) { ?>

      <a href="<?PHP echo $row_RS_students['url']; ?>" target="_blank" alt="<?PHP echo $row_RS_students['url']; ?>" title="<?PHP echo $row_RS_students['url']; ?>"><img src="images/icons/website_icon.png" width="14" height="14" /></a>

      <?PHP } ?>

      </td>
      <td id="email">

	  <?PHP if ($row_RS_students['email']) { ?>

      <a href="mailto:<?PHP echo $row_RS_students['email']; ?>" alt="<?PHP echo $row_RS_students['email']; ?>" title="<?PHP echo $row_RS_students['email']; ?>"><img src="images/icons/mail_icon.png" width="14" height="14" /></a>

      <?PHP } ?>

      </td>
      <td id="program"><?PHP echo $row_RS_students['program']; ?></td>
      <td id="port_term" title="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['port_term']; ?></td>
      <td id="grad_term" title="<?PHP echo $row_RS_students['id']; ?>"><?PHP echo $row_RS_students['grad_term']; ?></td>
      <td id="doc_key" style="display:none;"><?PHP echo $row_RS_students['doc_key']; ?></td>
      <td id="stu_pass" style="display:none;"><?PHP echo $row_RS_students['stu_pass']; ?></td>
    </tr>

    <?PHP } while ($row_RS_students = mysql_fetch_assoc($RS_students)); ?>
    </tbody>
    <tfoot>
    	<tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th id="filtered"></th>
            <th id="filtered"></th>
            <th id="filtered"></th>
       </tr>
    </tfoot>
</table>
</div>

<?php
mysql_free_result($RS_students);
?>
<?PHP include ("include/inc_footer.php"); ?>
