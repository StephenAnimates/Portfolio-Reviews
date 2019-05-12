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
include_once 'include/processes.php';
$Login_Process = new Login_Process;
$Login_Process->check_status($_SERVER['SCRIPT_NAME']);


if (isset($_POST['change_pass'])) {
	$Edit = $Login_Process->edit_password($_POST, $_POST['change_pass']);
}

if (isset($_POST['edit_info'])) {
	$Edit = $Login_Process->edit_details($_POST, $_POST['edit_info']);
}

include ("include/inc_header.php");
?>

<title>Art Institute Portfolio Review Website</title>
<link href="include/style.css" rel="stylesheet" type="text/css">

<?PHP include ("include/inc_jquery.php");?>
<script type="text/javascript">
$(document).ready(function() {
	$('.message .close').on('click', function() {
		$(this).closest('.message').transition('fade');
	});
});
</script>
<body>
<div id="main" class="ui grid">
<div class="sixteen wide column">
    <?php include ("include/inc_menu_rev.php"); ?>
</div>

<div class="four wide column">
<?PHP if ($_SESSION['alert_text'] != "") { ?>
    <div class="ui positive message">
    	<i class="close icon"></i>
    	<div class="header">Good News</div>
    	<p><?PHP echo $_SESSION['alert_text']; ?></p>
    </div>
<?PHP }
if ($Edit[0] != "") { ?> 
    <div class="ui <?PHP echo $Edit[0];?> message">
    	<i class="close icon"></i>
    	<div class="header">Too Bad</div>
    	<p><?PHP echo $Edit[1]; ?></p>
    </div>
<?PHP }
include ("include/inc_user_profile.php");

?>
</div>

<div class="ui segment twelve wide column">
<?php
// need to get program from logged in user (as a start point for the list)
$program = "MAA";
$port1_term = 8;
include 'include/inc_student_review_list.php';
?>
</div>
</div>

<div class="announcement">
</div>

<script type="text/javascript" src="scripts/toolbar_buttons.js"></script>
<script type="text/javascript">

$('a.linkNav').click(function(e){
	tabButton = $(this).attr('href');
	$('a.linkNav').removeClass("active");
	$(this).addClass("active");
	
	// hide the tabs
	$('.tabs').hide();
	
	// remove the hash from the href attr
	//alert(tabButton);
	theTab = tabButton.replace('#','');
	
	//alert(theTab);
	// now show the right tab
	$(tabButton).fadeToggle("slow");
});
</script>
