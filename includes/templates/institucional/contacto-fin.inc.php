<?php
/**
 * Muestra la pantalla que el env�o de email se realiz� correctamente
 */
?>
<article>

	<header><h2 id="title">Contactenos</h2></header>

	<p class="contact-block">
		El Mensaje ha sido enviado con exito. se envio una copia a la siguiente casilla de correo: <?php echo $dataToSkin['email'];?>
		<br/><br/>
		<a href="/">Volver</a>
	</p><!--fin block-->

</article>