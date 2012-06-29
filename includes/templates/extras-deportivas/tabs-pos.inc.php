<div id="tablas">

	<div class="tabs">
		
		<div class="inner-tabs">
			
			<h3><span class="ico"></span>Futbol argentino</h3>
			
			<ul class="tab3">
				<li id="first"><a href="#" class="current">Primera División</a></li>
				<li id="last"><a href="#">Nacional B</a></li>
			</ul><!--fin tab3-->
			
			<div class="clear"></div>
			
			<div class="panes">
				
				<div style="display:block">
					
					<?php include ($path_root."/includes/templates/extras/tabs-pos-pri-a.inc.php"); ?>
				
				</div><!--fin priemra-->
	
				<div>
				
					<?php include ($path_root."/includes/templates/extras/tabs-pos-nac-b.inc.php"); ?>
					
				</div><!--fin nacial b-->
	
			</div><!--fin panes-->
	
		</div><!--fin inner tabs-->
		
	</div><!--fin tabs-->
	
</div><!--fin tablas-->

<script type="text/javascript">
$(function() {
	$("ul.tab3").tabs("div.panes > div");
});
</script>