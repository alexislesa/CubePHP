<?php
/**
 * bloque que muestra las recomendaciones de la página en Facebook
 */
$fbUrlPage = 'www.advertis.com.ar';
$fbUserPage = 'advertis';
?>
<div id="fbshare" class="sideblock">
	
	<div class="facebook-title">
	
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/es_LA/all.js#xfbml=1"></script>
		<fb:like-box href="http://www.facebook.com/<?php echo $fbUserPage;?>" width="300" height="60" show_faces="false" border_color="" stream="false" header="true"></fb:like-box>

	</div>
	
	<div class="frame">
		<iframe src="http://www.facebook.com/plugins/recommendations.php?site=<?php echo $fbUrlPage;?>&amp;width=300&amp;height=345&amp;header=false&amp;colorscheme=light&amp;font=tahoma&amp;border_color=%23D8D8D8" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:345px; margin-top:0" allowTransparency="true"></iframe>
	</div>
	
</div><!--fin fb connect-->	