<?php
/**
 * Muestra un formulario para recuperar clave (paso 1)
 */
$tabindex =1; 
?>

<article>
	
	<header><h2 id="title">Recuperar clave</h2></header>

	<p class="legend">
		Ingresa el <strong>e-mail</strong> con el que te registraste y el 
		<strong>código de seguridad</strong> que te enviamos adjunto.
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
		
		<section class="formulario">
			
			<div class="form-block">

				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='email' title="Email" value='<?php echo !empty($dataToSkin['email']) ? $dataToSkin['email'] : '';?>' maxlength='100' />

			</div><!--fin form block-->

			<div class="form-block">

				<label><strong>*</strong> Código de seguridad:</label>				
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='codigo' title="Código" value='<?php echo !empty($dataToSkin['codigo']) ? $dataToSkin['codigo'] : '';?>' maxlength='10' />

			</div><!--fin form block-->				

			<div class="form-block form-bt">

				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Enviar' value=''/>			

			</div><!--fin form block-->

		</section>

	</form>

	<footer>
		<p>Los campos marcados con un <strong>(*)</strong> son obligatorios</p>
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