<?php
/**
 * Agrega un bloque de twitter de búsqueda
 */
$twitterSearch = '';
?>

<div class="twitter_module">

	<div class="tw-search">

		<form class="search">
			<input type="text" value="<?php echo $twitterSearch;?>" data-placeholder="<?php echo $twitterSearch;?>" class="text placeholder" />
			<input class="submit" type="submit" value="" />
		</form>
		
	</div>
	
	<div class="content">
		<script src="/js/twitter-widget-min.js"></script>
		<script type="text/javascript" charset="utf-8">
			var twt_search = new TWTR.Widget({
				version: 2,
				type: 'search',
				search: '<?php echo $twitterSearch;?>',
				interval: 4000,
				width: 630,
				height: 1000,
				theme: { 
					shell: { background: '#ffffff', color: '#333333' },
					tweets: { background: '#ffffff', color: '#333333' }
				},
				features: { scrollbar: true, loop: false, live: true, hashtags: true, timestamp: true, avatars: true, toptweets: true, behavior: 'default' }
			}).render().start();
		</script>

	</div><!--fin content-->

</div><!--fin tw-search-->