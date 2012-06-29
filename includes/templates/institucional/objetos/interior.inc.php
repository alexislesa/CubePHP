<?php
/**
 * Muestra el interior del artículo institucional
 */
?>

<div class="post-interior">

	<article>

		<header><h2 id="title"><?php echo  $v['noticia_titulo'];?></h2></header>

		<?php include (dirTemplate."/{$pathRelative}/objetos/foto.inc.php"); ?>	

		<section class="static-txt">
			<p><?php echo $v['noticia_texto'];?></p>
		</section>

	</article>

</div><!--fin /.post-interior-->