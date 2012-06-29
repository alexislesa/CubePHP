<?php
/**
 * Agrega un bloque de reacción en twitter
 * Extraido desde: http://blog.niteviva.com/development/realtime-twitter-reactions-using-jquery
 */
?>

<div class="twitter_reaction">

	<div id="twitter-reactions"></div>

	<script type="text/javascript" src="/js/jquery.twitter-reactions.js"></script>

	<script type="text/javascript">
	$(document).ready(function() {
		$("#twitter-reactions").getPageRealtimeReactions({
			query: "<?php echo $advThisUrl;?>",	// OR ....
			numTweets: 10,
			loaderText: "Cargando ...",
			slideIn: true,
			showHeading: true,
			headingText: "Resonancia en Twitter"
		});
	});
	</script>

</div><!--fin tw-reaction-->