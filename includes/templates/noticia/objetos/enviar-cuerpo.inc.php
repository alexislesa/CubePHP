<?php
/**
 * Muestra un bloque para el envío de artículos por email
 */

// Objetizo los datos del formulario
$dt = New ToObj($dataToSkin);
 
$tabIndex=1;
 
// Cantidad máxima de caracteres del mensajes
$maxLength = 250;

/**
 * MODO DEBUG:
 * Activa o desactiva el modo debug del formulario 
 * Una vez finaliza el período de test, pasar a modo debug false
 */
$debugMode = true;

if ($debugMode && !($msjError)) {
	$msjError = 'El siguiente formulario esta en modo debug, y este es un bloque de error';
}
?>

<div>

	<?php 
	/* Mensaje si hay error en el envío */
	if ($msjError) {

		include (dirTemplate.'/herramientas/mensaje-error.inc.php');

	} 

	/* Mensaje si hay error de alerta */
	if ($msjAlerta) {

		include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');

	} ?>
	
	<div class="title">
		<span>Recomendar</span>
		
		<a href="#" class="close" onclick="self.close();" title="Cerrar">[ x ]</a>
	</div>		
	
	<div class="clear"></div>
	
	<?php if ($totalResultados) { ?>
	
		<div class="container-form">
		
			<form name="form1" id="form1" action="?act=true&id=<?php echo $notaId;?>" method="post">

				<?php // Carga los campos específicos de cada formulario
				if (!empty($inputHiddenArr) && count($inputHiddenArr)) {
					foreach ($inputHiddenArr as $inputk => $inputv) { ?>
						<input type="hidden" name="<?php echo $inputk;?>" value="<?php echo $inputv;?>" />
					<?php }
				}?>

				<div class="form-block">
					<span><b>*</b> Remitente</span>
					<input type="text" name="nombre" tabindex="<?php echo $tabIndex++;?>" maxlength="35" class="txt" value="<?php echo $dt->g('nombre');?>"/>
				</div>
			
				<div class="form-block">	
					<span><b>*</b> Email</span>
					<input type="text" name="email" tabindex="<?php echo $tabIndex++;?>" maxlength="120" class="txt" value="<?php echo $dt->g('email');?>" />
				</div>
				
				<span class="ico line-contact"></span>
				
				<div class="form-block">
					<span><b>*</b> Destinatario</span>
					<input type="text" name="destinatario" tabindex="<?php echo $tabIndex++;?>" maxlength="35" class="txt" value="<?php echo $dt->g('destinatario');?>"/>
				</div>

				<div class="form-block">	
					<span><b>*</b> Email</span>
					<input type="text" class="txt" tabindex="<?php echo $tabIndex++;?>" maxlength="120" name="destinatario-mail" value="<?php echo $dt->g('destinatario-mail');?>" />
				</div>
				
				<div class="comentario">
					<span>Añadir comentario <b>(opcional)</b></span>
				
					<textarea name="mensaje" tabindex="<?php echo $tabIndex++;?>"><?php echo $dt->g('mensaje');?></textarea>
					
					<div class="comentario-caract">
						<input type="text" value="<?php echo $maxLength;?>" name="contador"> <span>/ Caracteres restantes. Máximo <?php echo $maxLength;?> caracteres</span>				
					</div>
				</div>
				
				<div class="enviar-copia">
					<input type="checkbox" tabindex="<?php echo $tabIndex++;?>" name="copia" value="1"/>
					<span class="txt">Enviarme una copia</span>
				</div>

				<span class="title2">Verificacion</span>
				
				<div class="form-block captcha">
				
					<img src="/extras/captcha/contacto.php?=<?php echo microtime(true);?>"  alt="" width="298" height="40"/>
					
				</div>

				<div class="form-block">

					<span class="label"><b>*</b> Ingresá el código:</span>
					<input tabindex='<?php echo $tabIndex++;?>' class="txt" type='text' autocomplete='off' name='captcha' maxlength='5'/>

				</div><!--fin form block-->			
				
				
				<div class="disclaimer2"><b>(*) </b>Campos obligatorios</div> 

				<div class="bt-enviar">
					<input type="submit" name="Enviar" value="" tabindex="<?php echo $tabIndex++;?>" title="Enviar" />
				</div>
			
			</form>
			
		</div>

	<?php } ?>

</div>

<script type="text/javascript">
$("#form1 textarea").keypress( function () {
	m = $("#form1 textarea")[0];
	if (m.value.length > <?php echo $maxLength;?>) {
		alert("Ha superado los <?php echo $maxLength;?> caracteres");
		m.value = m.value.substr(0,<?php echo $maxLength;?>);
	}
	
	$("#form1 .comentario-caract input").val(<?php echo $maxLength;?> - m.value.length);
});
</script>
	
<?php 
/* Procesa la validación del formulario */
processForm("form1", $checkForm);
?>