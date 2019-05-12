<div class="one column row">
	<div class="column">
        <div class="ui fluid search icon selection dropdown" id="campus-dropdown">
            <input name="campus" type="hidden" value="<?PHP echo $_SESSION['info']; ?>">
            <i class="dropdown icon"></i>
            <div class="default text">Select Campus</div>
            <div class="menu">
                <div class="item" data-value="California-Hollywood">California-Hollywood</div>
                <div class="item" data-value="California-Inland Empire">California-Inland Empire</div>
                <div class="item" data-value="California-Los Angeles">California-Los Angeles</div>
                <div class="item" data-value="California-Orange County">California-Orange County</div>
                <div class="item" data-value="California-Sacramento">California-Sacramento</div>
                <div class="item" data-value="California-San Diego">California-San Diego</div>
                <div class="item" data-value="California-San Francisco">California-San Francisco</div>
                <div class="item" data-value="California-Silicon Valley">California-Silicon Valley</div>
            </div>
        </div>

		<script type="text/javascript">
        $('.dropdown#campus-dropdown').dropdown('hide others', true);
        </script>
        
    </div>
</div>
