<div class="bloque-twitter">
	
	<div class="mtwit">
		
		<div class="twitter-title">
	
			<h3>en Twitter</h3>
		
		</div>
		
		<div class="clear"></div>

		<div id="mytwitter">

		</div>
	
	</div><!--fin mtwit-->

	<div class="tw-bottom">
		<a class="logotw" href="http://www.twitter.com/" target="_blank" title="Seguinos en Twitter"></a>
	</div>

</div><!--fin bloque-twitter-->

<script type="text/javascript">
$(document).ready(function(){
	
	<?php
	/* Bloque para mostrar una lista de un usuario */
	?>
	// $("#mytwitter").liveTwitter({user: "", list: ""}, {limit: 20, refresh: false, mode: "list", showAuthor:true});
	
	<?php
	/* Bloque para mostrar los tweet de un usuario en particular */
	?>
	$("#mytwitter").liveTwitter("", {limit: 10, refresh: false, mode: "user_timeline", showAuthor:true});
	
	twitter_refresh();
});

/* Animación del bloque de twitter */
function twitter_refresh() {
	$(".tweet").last().hide();
	$("#mytwitter").prepend($(".tweet").last().slideDown());

	setTimeout('twitter_refresh()',5000);
}
</script>