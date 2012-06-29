<?php
/**
 * bloque que muestra un Fan-box de la página
 */
$fbUrlPage = 'http://www.facebook.com/advertisweb';
?>
<div id="likebox" class="sideblock">

	
	<div class="frame">
		<iframe src="http://www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($fbUrlPage);?>&amp;width=300&amp;connections=15&amp;stream=false&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;height=327&amp;font=tahoma&amp;border_color=%23C4C4C4" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:328px;" allowTransparency="true"></iframe>
	</div>
	
</div><!--fin fb connect-->