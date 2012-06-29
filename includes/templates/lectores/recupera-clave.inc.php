<?php
/**
 * Muestra un formulario para recuperar clave (paso 1)
 */
$tabindex=1;
?>
<article>
	
	<header><h2 id="title">Recuperar clave</h2></header>

	<p class="legend">
		Para iniciar el proceso de recuperación de nombre de usuario o clave 
		por favor ingresa el <strong>e-mail</strong> con el que te registraste.
	</p>
	
	<?php 
	// Mensaje de Error
	if ($msjError) {
		include (dirTemplate.'/herramientas/mensaje-error.inc.php');
	}

	// Mensaje de Alerta
	if ($msjAlerta) {
		include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
	}
	?>
	
	<form name="fcontacto" id="fcontacto" action="?act=true" method="post">
		<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />
		
		<section>
			
			<div class="form-block">
				
				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='email' title="Email" value='<?php echo !empty($dataToSkin['email']) ? $dataToSkin['email'] : '';?>' maxlength='120' />

			</div><!--fin form block-->

			<div class="form-block form-bt">

				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Enviar' value='Enviar'/>			

			</div><!--fin form block-->

		</section>

	</form>

	<footer>
		<p><strong>*</strong> Revisá tu casilla de correo, allí te enviamos 
		tu usuario y clave para que puedas acceder al sitio. </p>
		<p>En caso de no recibirlo en la bandeja de entrada, 
		por favor revisá la carpeta de correo no deseado.</p>
	</footer>
	
</article>

<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});
</script>

<?php processForm('fcontacto', $checkForm); ?>