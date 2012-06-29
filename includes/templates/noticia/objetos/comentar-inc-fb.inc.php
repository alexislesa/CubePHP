<?php
/**
 * Muestra un bloque de comentarios de usuarios de facebook
 *
 * Muestra el bloque solo si el comentar esta habilitado en el sitio
 * Este bloque requiere modificación en el Header del sitio.
 *
 * Alta de comentarios: https://developers.facebook.com/docs/reference/plugins/comments/
 *
 * Incorporar esta línea: 
 * <html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="es" lang="es-ar">
 *
 * Requiere un meta campo en el home para administrar los comentarios
 * <meta property="fb:app_id" content="{YOUR_APPLICATION_ID}"/>
 *
 */
if ($v['noticia_comentarios']) { ?>

	<section id="faceco" class="article-block com-block">

		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/es_LA/all.js#xfbml=1"></script>
		<fb:comments xid="sitio_web_nombre_id_<?php echo $v['noticia_id'];?>" href="<?php echo $advThisUrl;?>" num_posts="10" width="650"></fb:comments>
		
	</section>
	
<?php } ?>