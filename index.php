<?PHP

/*
Portfolio Reviews
https://github.com/StephenAnimates

Copyright (C) 2014-2024 Stephen Studyvin

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
$Login_Process->check_login($_GET['page']);

if (isset($_POST['login'])) {
	$Login = $Login_Process->log_in($_POST['user'], $_POST['pass'], $_POST['remember'], $_POST['page'], $_POST['login']);
}

if (isset($_POST['request'])) {
	$Check = $Login_Process->Forgot_Password($_GET, $_POST);
	$Request = $Login_Process->Request_Password($_POST, $_POST['request']);
}

if (isset($_POST['process'])) {
	$New = $Login_Process->Register($_POST, $_POST['process']);
}

include ("include/inc_header.php");
?>

<title>Portfolio Review Website - Login</title>
<link href="include/style.css" rel="stylesheet" type="text/css">

<?PHP include ("include/inc_jquery.php");?>

<!-- script type="text/javascript" src="scripts/js_dropdown.js"></script -->

<body>
<div class="ui three column centered grid" id="login">
<div class="column">
<div class="ui segment">

<h2 class="ui center header">Portfolio Review Website</h2>
<div class="ui clearing divider"></div>

<div class="ui top attached tabular menu">
  <a class="active item" data-tab="log"><i class="power icon"></i>Login</a>
  <a class="item" data-tab="reg"><i class="edit icon"></i>Register</a>
	<a class="item" data-tab="pas"><i class="lock icon"></i>Forgot Password</a>
</div>

<div class="ui bottom attached active tab segment" data-tab="log">
<form name="form-login" id="form-login" class="ui form fluid" method="post" >
<div class="ui four column grid">

<?php
	include ("include/inc_field_idnum.php"); 
	$pass_field_type = "pass";
	$pass_field_label = ph_pass;
	include ("include/inc_field_password.php");
?>

<div class="one column row">
	<div class="column">
        <div class="ui toggle checkbox">
          <input name="check" id="check" type="checkbox">
          <label>Remember login</label>
        </div>
    </div>
</div>

<div class="one column row">
	<div class="column">
        <input name="page" type="hidden" value="<?PHP echo $_GET['page']; ?>" />
        <input name="login" type="hidden" />
        <!-- button class="ui primary button" id="btn_login">Log In</button -->
        <div class="ui blue button" id="btn_login">Log In</div>
    </div>
</div>
</div>
</form>

<?PHP if ($_SESSION['alert_text'] != "") { ?>
<div class="alert-box ui bottom positive message"><span>Success: </span><?PHP echo $_SESSION['alert_text']; ?></div>
<?PHP };

if ($Login[0] != "") {
	echo '<div class="alert-box ui bottom negative message"><span>'.$Login[0].': </span>'.$Login[1].'</div>';
} ?>
</div>

<!-- registration form -->
<div class="ui bottom attached tab segment" data-tab="reg" id="reg">
  <form name="form-register" id="form-register" class="ui form fluid" method="post">
  <!-- form name="form-register" id="form-register" class="ui form fluid segment" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post" -->
  <div class="ui four column grid">
  
  <?php
  include ("include/inc_field_idnum.php");
  include ("include/inc_field_firstname.php");
  include ("include/inc_field_lastname.php");
  include ("include/inc_field_emailaddr.php");
  include ("include/inc_campus_list.php");
  $pass_field_type = "pass1";
  $pass_field_label = ph_pass;
  include ("include/inc_field_password.php");
  $pass_field_type = "pass2";
  $pass_field_label = ph_repass;
  include ("include/inc_field_password.php");
  ?>

<div class="one column row">
	<div class="column">
        <input name="process" type="hidden" />
        <!-- button class="ui primary button" id="btn_login">Log In</button -->
        <div class="ui blue button" id="btn_process">Register</div>
    </div>
</div>
</div>
</form>

<?PHP if ($_SESSION['alert_text'] != "") { ?>
<div class="alert-box success"><span>Success: </span><?PHP echo $_SESSION['alert_text']; ?></div>
<?PHP }

if ($Login[0] != "") {
	echo '<div class="alert-box ui bottom negative message"><span>'.$Login[0].': </span>'.$Login[1].'</div>';
} ?>

</div>

<div class="ui bottom attached tab segment" data-tab="pas" id="pas">
<form name="form-request" id="form-request" class="ui form fluid" method="post">
<!-- form name="form-request" id="form-request" class="ui form fluid segment" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post" -->
  <div class="ui four column grid">
  
<?php
	include ("include/inc_field_emailaddr.php");
	include ("include/inc_field_idnum.php");
?>

<div class="one column row">
	<div class="column">
        <input name="request" type="hidden" />
        <!-- button class="ui primary button" id="btn_login">Log In</button -->
        <div class="ui blue button" id="btn_request">Request Reset Email</div>
    </div>
</div>
</div>
</form>

<?PHP if ($_SESSION['alert_text'] != "") { ?>
<div class="alert-box ui bottom positive message"><span>Success: </span><?PHP echo $_SESSION['alert_text']; ?></div>
<?PHP } 

if ($Login[0] != "") {
	echo '<div class="alert-box ui bottom negative message"><span>'.$Login[0].': </span>'.$Login[1].'</div>';
} ?>

</div>
</div><!-- end of segment -->
</div><!-- end of column -->
</div><!-- end of main grid -->

<script type="text/javascript">
$('form#form-login')
  .form({
    fields: {
      username: {
        identifier: 'username',
        rules: [
          {
            type   : 'empty',
            prompt : '<?php echo val_idnum; ?>'
          },
			{
			type   : 'integer',
			prompt : '<?php echo val_validnum; ?>'
			}
        ]
      },
      password: {
        identifier: 'password',
        rules: [
          {
            type   : 'empty',
            prompt : '<?php echo val_pass; ?>'
          }
        ]
      }
    }
  })
;

$('form#form-register').form({
	on: 'blur',
    fields: {
      user: {
        identifier: 'user',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_idnum; ?>'
        },
		{
			type   : 'integer',
			prompt : '<?php echo val_validnum; ?>'
		}]
      },
      first_name: {
        identifier: 'first_name',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_fname; ?>'
        }]
      },
      last_name: {
        identifier: 'last_name',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_lname; ?>'
        }]
      },
      email_address: {
        identifier: 'email_address',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_email; ?>'
        },
		{
			type   : 'email',
			prompt : '<?php echo val_validemail; ?>'
		}]
      },
      campus: {
        identifier: 'campus',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_campus; ?>'
        }]
      },
      pass1: {
        identifier: 'pass1',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_regpass; ?>'
        }]
      },
      pass2: {
        identifier: 'pass2',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_repass; ?>'
        },
		{
            type   : 'match',
            prompt : '<?php echo val_passmismatch; ?>'
        }]
      }
    }
});

$('form#form-request').form({
	on: 'blur',
    fields: {
      email_address: {
        identifier: 'email_address',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_email; ?>'
        },
		{
			type   : 'email',
			prompt : '<?php echo val_validemail; ?>'
		}]
      },
      user: {
        identifier: 'user',
        rules: [{
            type   : 'empty',
            prompt : '<?php echo val_idnum; ?>'
        },
		{
			type   : 'integer',
			prompt : '<?php echo val_validnum; ?>'
		}]
      }
    }
});

$('#btn_login').bind('click', function () {
	//alert ('clicked '+$('input[name="user"]').val());
	$('form[name="form-login"]').submit();
});
$('#btn_process').bind('click', function () {
	//alert ('clicked '+$('input[name="user"]').val());
	$('form[name="form-register"]').submit();
});
$('#btn_request').bind('click', function () {
	//alert ('clicked '+$('input[name="user"]').val());
	$('form[name="form-request"]').submit();
});

$('.menu .item').tab();

$(document).ready(function(){
  
	// get the hash of the site
	var theHash = location.hash;

	var setTab = '#'+($('.tabs').first().attr('id'));
	// if a hash is present, then show the proper tab and content
	if(theHash) {
		setTab = theHash;
		//setTab = (theHash.replace('#',''));
		//alert (theHash);
	}

	// hide all the active tabs
	$('a.linkNav').removeClass("active");
	// hide all the content
	$('.tabs').hide();
	
	//alert ($('a.linkNav[href*='+setTab+']').text());
	
	// show the appropraite tab
	$('a.linkNav[href*='+setTab+']').addClass("active");
	
	// show the appropraite content
	$(setTab).fadeToggle("slow");

});

</script>
<?PHP include ("include/inc_footer.php"); ?>