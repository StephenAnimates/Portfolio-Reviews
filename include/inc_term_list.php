<select name="port_term" id="cbo_port_term">
<option value="">Not Determined</option>
<?PHP

echo '<!-- the type of review is: '.$port_term.' -->';

// check the current year and quarter
// unless something odd happens, these should always be the months the quarters break on.
$cur_year = date('Y');
echo '<!-- the current year: '.$cur_year.' -->';
$cur_month = date('n');
echo '<!-- the current month: '.$cur_month.' -->';
$cur_quarter = "";
switch ($cur_month) {
	case ($cur_month >= 1 && $cur_month <= 3):
		$cur_quarter = "Winter";
		break;
	case ($cur_month >= 4 && $cur_month <= 6):
		$cur_quarter = "Spring";
		break;
	case ($cur_month >= 7 && $cur_month <= 9):
		$cur_quarter = "Summer";
		break;
	case ($cur_month >= 10 && $cur_month <= 12):
		$cur_quarter = "Fall";
		break;
}
echo '<!-- the current quarter: '.$cur_quarter.' -->';
$cur_term = $cur_quarter.' '.$cur_year;
echo '<!-- the current term: '.$cur_term.' -->';
$s=1;
do {
	for ($i = 1; $i <= 4; $i++) {
		switch ($i) {
			case 1:
				$this_quarter = 'Fall';
				break;
			case 2:
				$this_quarter = 'Summer';
				break;
			case 3:
				$this_quarter = 'Spring';
				break;
			case 4:
				$this_quarter = 'Winter';
				break;
		}
		
		// set the variable for the current quarter and year
		$this_term = $this_quarter.' '.$cur_year;
		// convert the port term to the proper value
		// perhaps one day I will make this all match
		$port_term = str_replace("_"," ",$port_term);
		
		echo '<!-- the port term is now: '.$port_term.' and this term is: '.$this_term.'-->';
		
		echo '<option data-term-val="'.$s.'" value="'.$this_term.'" ';
		if (($port_term != "") && ($port_term == $this_term)) {
			echo 'selected';
		} elseif ($cur_term == $this_term) {
			echo 'selected';
		};
		echo '>'.$this_term.'</option>';
		$s++;
	}
	$cur_year--;
} while ($cur_year >= $year_start);

?>

</select>