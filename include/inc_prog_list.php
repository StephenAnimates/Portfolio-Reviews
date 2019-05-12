<?PHP
if ($_POST['program'] != "") {
	$program = $_POST['program'];
}
?>

<select name="program" id="cbo_program">
<option value="DFVP" <?PHP if ($program == "DFVP") {echo "selected";}; ?>>Digital Film and Video Production</option>
<option value="PHOA" <?PHP if ($program == "PHOA") {echo "selected";}; ?>>Digital Photography</option>
<option value="GAD" <?PHP if ($program == "GAD") {echo "selected";}; ?>>Game Art and Design</option>
<option value="MAA" <?PHP if ($program == "MAA") {echo "selected";}; ?>>Media Arts and Animation</option>
</select>