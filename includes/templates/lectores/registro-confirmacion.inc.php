<?php
/**
 * Muestra un formulario para validación de registro de usuarios
 */
$tabindex=1;
?>

<article>
	
	<header><h2 id="title">Registro de usuarios paso 2/2</h2></header>

	<p class="legend">
		Ingresá el email con el que te registraste en el paso 1/2 
		y el <b>código de confirmación</b> adjunto. 
	</p><!--fin /.legend-->

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

	<section class="formulario">

		<form name="fcontacto" id="fcontacto" action="?act=true" method="post">

			<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />

			<div class="form-block">
				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='email' value='<?php echo !empty($dataToSkin['email']) ? $dataToSkin['email'] : '';?>' maxlength='120' />
			</div><!--fin form block-->
	
			<div class="form-block">
				<label><strong>*</strong> Código de confirmación:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='codigo' autocomplete='off' value='<?php echo !empty($dataToSkin['codigo']) ? $dataToSkin['codigo'] : '';?>' maxlength='30'/>
			</div><!--fin form block-->
	
			<div class="form-block form-bt">
				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Continuar' value='Continuar'/>
			</div><!--fin form block-->
	
		</form>

	</section><!--fin formulario-->
	
	<footer>
		<p>Los campos marcados con un <strong>(*)</strong> son obligatorios
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