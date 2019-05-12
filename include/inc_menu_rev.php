<div class="ui menu">
<div class="header item">Media Arts Portfolio Review</div>
    <?PHP
		// set up a link for home
        if ($_SESSION['user_level'] >= 1) {
			if (basename($_SERVER['PHP_SELF']) == 'main.php') {
				$isActive = ' active';
			} else {
				$isActive = '';
			}
            echo '<a class="item'.$isActive.'" href="/main.php"><i class="dashboard icon"></i>Main</a>';
        };
        // set up a link for "admin"
        if ($_SESSION['user_level'] >= 5) {
			if (basename($_SERVER['PHP_SELF']) == 'admin_center.php') {
				$isActive = ' active';
			} else {
				$isActive = '';
			}
            echo '<a class="item'.$isActive.'" href="/admin/admin_center.php"><i class="dashboard icon"></i>Admin</a>';
        }; 
        
        // set up a link for "reviewer" level to review - this will no longer be needed in the new system
        if ($_SESSION['user_level'] >= 2) {
			if (basename($_SERVER['PHP_SELF']) == 'student_reviews_new.php') {
				$isActive = ' active';
			} else {
				$isActive = '';
			}
            //echo '<a class="item" href="reviews/student_reviews_new.php"><i class="edit icon"></i>Proceed to Review</a>';
        }; 
        
        // set up a link for "advisor" level to view the reports
        if ($_SESSION['user_level'] >= 3) {
			if (basename($_SERVER['PHP_SELF']) == 'admin_reports.php') {
				$isActive = ' active';
			} else {
				$isActive = '';
			}
            echo '<a class="item'.$isActive.'" href="/admin/admin_reports.php"><i class="browser icon"></i>Program Averages</a>';
        }; ?>
        
    <div class="right menu">
        <a class="item" href="/include/processes.php?log_out=true"><i class="power icon"></i>Log out</a>
    </div>
</div>
