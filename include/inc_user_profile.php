<div class="card">
    <div class="ui top attached tabular menu">
      <a class="item active" href="#pro" data-tab="pro">User<br />Profile</a>
      <a class="item" href="#inf" data-tab="inf">Edit<br />Info</a>
      <a class="item" href="#pas" data-tab="pas">Change<br />Password</a>
    </div>

    <div class="ui bottom attached active tab segment" data-tab="pro">
      <div class="description">
      	<div class="header"><strong><?PHP echo $_SESSION['first_name']; ?> <?PHP echo $_SESSION['last_name']; ?></strong>
      </div>

      </div>
        <table class="ui table compact celled definition">
            <tbody>
                <tr>
                  <td>Id Number:</td>
                  <td><?PHP echo $_SESSION['username']; ?></td>
                </tr>
                <tr>
                  <td>Email Address:</td>
                  <td><?PHP echo $_SESSION['email_address']; ?></td>
                </tr>
                <tr>
                  <td>Campus:</td>
                  <td><?PHP echo $_SESSION['info']; ?></td>
                </tr>
                <tr>
                  <td>User Type:</td>
                  <td><?PHP include $_SERVER['DOCUMENT_ROOT'].'/include/inc_user_level.php'; ?></td>
                </tr>
        	</tbody>
        </table>
    </div>
    
    <div class="ui bottom attached tab segment" data-tab="inf">
		<!-- form name="edit-account" id="edit-account" class="ui form" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post" style="height:1000px" -->
		<form name="edit-account" id="edit-account" class="ui form" method="post">
      	<div class="ui four column grid">

<?php
$username = $_SESSION['username'];
$firstname = $_SESSION['first_name'];
$lastname = $_SESSION['last_name'];
$emailaddr = $_SESSION['email_address'];

include ("include/inc_field_idnum.php");
include ("include/inc_field_firstname.php");
include ("include/inc_field_lastname.php");
include ("include/inc_field_emailaddr.php");
include ("include/inc_campus_list.php");
?>
    
                <div class="one column row">
                    <div class="column">
                        <input name="edit_info" type="hidden" />
                        <input name="userid" type="hidden" value="<?php echo $_SESSION['userid']; ?>" />
                        <!-- button class="ui primary button" id="btn_login">Log In</button -->
                        <div class="ui blue button" id="btn_save">Save Changes</div>
                    </div>
                </div>
            </div> 
<div class="ui error message"></div> 
        </form>
    </div>
    
    <div class="ui bottom attached tab segment" data-tab="pas">
        <!-- form name="password-change" id="password-change" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" method="post" -->
        <form name="password-change" id="password-change" class="ui form" method="post">
      	<div class="ui four column grid">
        
<?PHP        
	$pass_field_type = "pass";
	$pass_field_label = ph_curpass;
	include ("include/inc_field_password.php");
	$pass_field_type = "pass1";
	$pass_field_label = ph_newpass;
	include ("include/inc_field_password.php");
	$pass_field_type = "pass2";
	$pass_field_label = ph_repass;
	include ("include/inc_field_password.php");
?>

            <div class="one column row">
                <div class="column">
                    <input name="change_pass" type="hidden" />
                    <input name="username" type="hidden" value="<?PHP echo $_SESSION['username']; ?>"/>
                    <!-- button class="ui primary button" id="btn_login">Log In</button -->
                    <div class="ui blue button" id="btn_password">Save New Password</div>
                </div>
            </div>
            </div>
<div class="ui error message"></div>
        </form>
    </div>
  </div>
  
<script type="text/javascript">
$(document).ready(function() {
	$('form#edit-account').form({
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
		  }
		}
	});
	
	$('form#password-change').form({
		on: 'blur',
		fields: {
		  pass: {
			identifier: 'pass',
			rules: [{
				type   : 'empty',
				prompt : '<?php echo val_curpass; ?>'
			}]
		  },
		  pass1: {
			identifier: 'pass1',
			rules: [{
				type   : 'empty',
				prompt : '<?php echo val_newpass; ?>'
			}]
		  },
		  pass2: {
			identifier: 'pass2',
			rules: [{
				type   : 'empty',
				prompt : '<?php echo val_repass; ?>'
			},
			{
				type   : 'match[pass1]',
				prompt : '<?php echo val_passmismatch; ?>'
			}]
		  }
		}
	});
	
	$('#btn_save').bind('click', function () {
			$('form[name="edit-account"]').submit();
	});
	
	$('#btn_password').bind('click', function () {
		$('form[name="password-change"]').submit();
	});
	
	$('.menu .item').tab();
});
</script>