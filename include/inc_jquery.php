<?php
$siteScripts = 'http://'.$_SERVER['HTTP_HOST'].'/scripts/';
?>

<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

<!-- jquery UI -->
<!-- script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script -->

<!-- script type="text/javascript" src="../scripts/toolbar_buttons.js"></script -->

<script type="text/javascript" src="<?php echo $siteScripts; ?>jquery.steps.js"></script>
<script type="text/javascript" src="<?php echo $siteScripts; ?>jquery.steps.min.js"></script>

<!-- jquery PDF viewer -->
<script type="text/javascript" src="<?php echo $siteScripts; ?>jquery.media.js?v0.92"></script>
<!-- THe metadata.js script was intended for the jquery PDF viewer, but it was breaking other things - it's really out of date --> 
<!-- script type="text/javascript" src="../scripts/jquery.metadata.js"></script -->

<!-- script type="text/javascript" src="../scripts/hover_zoom_extended.js"></script -->

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo $siteScripts; ?>fancybox2/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo $siteScripts; ?>fancybox2/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $siteScripts; ?>fancybox2/source/jquery.fancybox.pack.js?v=2.1.4"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?php echo $siteScripts; ?>fancybox2/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $siteScripts; ?>fancybox2/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?php echo $siteScripts; ?>fancybox2/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href=".<?php echo $siteScripts; ?>fancybox2/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $siteScripts; ?>fancybox2/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<link rel="stylesheet" href=	"//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

<link href='http://fonts.googleapis.com/css?family=Denk+One' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="<?php echo $siteScripts; ?>jquery.easing.1.3.js"></script>

<script type="text/javascript" src="<?php echo $siteScripts; ?>itoggle.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="../css/itoggle_styles.css">	

<!-- script src="../scripts/dropzone.js"></script -->

<!-- link rel="stylesheet" type="text/css" href="../css/dropzone.css" -->

<script src="<?php echo $siteScripts; ?>fancySettings.js"></script>

<!-- link href="http://code.jquery.com/ui/1.10.3/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" -->
<link href="../css/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css">
<!-- link rel="stylesheet" type="text/css" href="css/styles.css" -->

<!-- bootstrap -->
<!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" -->

<!-- Optional theme -->
<!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" -->

<!-- Latest compiled and minified JavaScript -->
<!-- script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script -->

<link rel="stylesheet" href="<?php echo $siteScripts; ?>semantic_ui/semantic.css">
<script src="<?php echo $siteScripts; ?>semantic_ui/semantic.js"></script>
