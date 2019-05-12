<div class="ui pointing menu large top fixed">
	<div class="ui container">
        <a href="index.php" class="active item">Main Page</a>
        <a href="portfolio_guide.php?program=maaa" class="item <?php if ($_GET['program'] == "maaa") { echo 'active'; } ?>">Animation</a>
        <a href="portfolio_guide.php?program=dfvp" class="item <?php if ($_GET['program'] == "dfvp") { echo 'active'; } ?>">Digital Film</a>
        <a href="portfolio_guide.php?program=gada" class="item <?php if ($_GET['program'] == "gada") { echo 'active'; } ?>">Game Design</a>
        <a href="portfolio_guide.php?program=phoa" class="item <?php if ($_GET['program'] == "phoa") { echo 'active'; } ?>">Photography</a>
        <div class="right item">
          <a class="ui button">Reviewers</a>
        </div>
    </div>
</div>