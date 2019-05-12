<?PHP
if (isset($_POST['port_type'])) {
	$type = $_POST['port_type'];
	echo '<!-- the type of review is (post): '.$type.' -->';
}
if (isset($_GET['port_type'])) {
	$type = $_GET['port_type'];
	echo '<!-- the type of review is (get): '.$type.' -->';
}
?>

<select name="type" id="cbo_type">
<option value="1" <?PHP if ($type == 1) {echo "selected";}; ?>>4th Quarter - 1st Review</option>
<option value="2" <?PHP if ($type == 2) {echo "selected";}; ?>>8th Quarter - 2nd Review</option>
<option value="3" <?PHP if ($type == 3) {echo "selected";}; ?>>Final - 3rd Review</option>
</select>

<?PHP /*
<div class="ui labeled dropdown">
<input name="type" type="hidden">
<div class="default text">Review Type</div>
    <i class="dropdown icon"></i>
    <div class="menu">
        <div class="item" data-value="1">4th Quarter - 1st Review</div>
        <div class="item" data-value="2">8th Quarter - 2nd Review</div>
        <div class="item" data-value="3">Final - 3rd Review</div>
    </div>
  </div>

<script>
$('.ui.dropdown').dropdown();
</script>
*/
?>