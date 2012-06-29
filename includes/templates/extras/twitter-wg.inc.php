<?php
/**
 * Bloque de twitter del Widgets de twitter.com
 */
$twitterUserName = 'advertisweb';
?>
<script src="http://widgets.twimg.com/j/2/widget.js"></script>

<script>
new TWTR.Widget({
	version: 2,
	type: 'profile',
	rpp: 4,
	interval: 6000,
	width: 260,
	height: 212,
	theme: {
		shell: {
			background: '#257dbb',
			color: '#ffffff'
		},
		tweets: {
			background: '#ffffff',
			color: '#257DBB',
			links: '#152F6A'
		}
	},
	features: {
		scrollbar: true,
		loop: false,
		live: false,
		hashtags: true,
		timestamp: true,
		avatars: true,
		behavior: 'all'
	}
}).render().setUser('<?=$twitterUserName;?>').start();
</script>