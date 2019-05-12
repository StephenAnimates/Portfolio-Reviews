<?PHP
if (!$campus) {
	$campus = $_SESSION['info'];
	
	if ($_POST['campus'] != "") {
		$campus = $_POST['campus'];
	}
}
?>

<select name="cbo_campus" id="cbo_campus" class="ui search fluid dropdown">
    <option <?PHP if ($campus == "California-Hollywood") {echo "selected";}; ?>>California-Hollywood</option>
    <option <?PHP if ($campus == "California-Inland Empire") {echo "selected";}; ?>>California-Inland Empire</option>
    <option <?PHP if ($campus == "California-Los Angeles") {echo "selected";}; ?>>California-Los Angeles</option>
    <option <?PHP if ($campus == "California-Orange County") {echo "selected";}; ?>>California-Orange County</option>
    <option <?PHP if ($campus == "California-Sacramento") {echo "selected";}; ?>>California-Sacramento</option>
    <option <?PHP if ($campus == "California-San Diego") {echo "selected";}; ?>>California-San Diego</option>
    <option <?PHP if ($campus == "California-San Francisco") {echo "selected";}; ?>>California-San Francisco</option>
    <option <?PHP if ($campus == "California-Silicon Valley") {echo "selected";}; ?>>California-Silicon Valley</option>
</select>