<?php
/**
 * Muestra la pantalla que la comprobación del registro se realizó correctamente
 */
?>
<article>
	
	<header><h2 id="title">Recuperar clave</h2></header>
			
			<p class="legend">
				Se ha enviado a: <span><?php echo $dataToSkin['email'];?></span> 
				su nueva contraseña.
			</p>
			
	<footer>
		<p><strong>*</strong> Tu proximo ingreso deberás realizarlo 
		con la clave que te enviamos.</p>
		<p><strong>*</strong> <span>En caso de no recibirlo en la bandeja de entrada, 
		por favor revisá la carpeta de correo no deseado.</span></p>
	</footer>
	
</article>