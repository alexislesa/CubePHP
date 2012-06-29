<?php
/**
 * Muestra un formulario para el cambio de contraseña de usuarios
 */
$tabindex=1; 
?>

<?php include (dirTemplate.'/lectores/objetos/profile.inc.php'); ?>

<article>
	
	<header><h2 id="title">Cambiar clave</h2></header>

	<figure>
		<img src="<?php echo $usr->campos['lector_avatar'];?>" width="40" height="40" alt="" title="" />
	</figure>

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

		<section class="formulario">

			<h5>Datos del usuario</h5>
			
			<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />

			<div class="form-block">
				<label><strong>*</strong> Clave actual:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='password' class="txt" autocomplete='off' name='claveold' value='' maxlength='30' />
			</div><!--fin form block-->
		
			<div class="form-block highblock">
				<label><strong>*</strong> Nueva Clave:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='password' class="txt" autocomplete='off' name='clave' value='' maxlength='30' />
				<p class="disc">La clave debe tener entre 6 y 30 letras y/o números</p>
			</div><!--fin form block-->
		
			<div class="form-block wd">
				<label><strong>*</strong> Repitir la Clave:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='password' class="txt" autocomplete='off' name='clave2' value='' maxlength='30' />
				<p class="disc">Confirmá tu clave</p>
			</div><!--fin form block-->						
		
			<div class="form-block form-bt">
				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Guardar Cambios' value='Guardar Cambios'/>
			</div><!--fin form block-->
		
		</section>
		
	</form>
		
</article>
	
<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});
</script>

<?php processForm('fcontacto', $checkForm); ?>