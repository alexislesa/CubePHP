<?php
/**
 * Muestra un formulario para login de usuarios
 */
$tabIndex=1;

$fUsuario = !empty($dataToSkin['usuario']) ? $dataToSkin['usuario'] : '';
$fRecordar = !empty($dataToSkin['recordar']) 
			? (($dataToSkin['recordar'] == 1) ? 'checked' : '') 
			: '';
$fUrl = !empty($_GET['url']) ? $_GET['url'] : (!empty($dataToSkin['url']) ? $dataToSkin['url'] : '');
?>

<article>
	
	<header><h2 id="title">Ingreso de usuarios</h2></header>

	<p class="legend">
		Si ya eres usuario registrado, ingresa tu nombre de usuario y clave. 
		¿Aún no estás registrado? <a href="/lectores/registro.php" title="click aquí">click aquí</a><br />
		Si olvidaste tu usuario o clave, 
		<a href="/lectores/recupera-clave.php" title="recuperalos aquí">recuperalos aquí</a>
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

	<form name="fcontacto" id="fcontacto" action="?act=true&url=<?php echo $fUrl;?>" method="post">
		<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />
		<input type="hidden" name="url" value="<?php echo $fUrl;?>" />
		<section class="formulario">
			
			<h5>Datos personales</h5>

			<div class="form-block wd">

				<label><strong>*</strong> Usuario:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='usuario' title='Ingrese su usuario' value='<?php echo $fUsuario;?>' maxlength='20' />

				<div class="radiocheck">
					<span class="radio">
						<input tabindex='<?php echo $tabIndex++;?>' type='checkbox' name='recordar' title='Recordar datos de ingreso' value='1' <?php echo $fRecordar;?> />
					</span>
					<label>Recordar mi usuario</label>
				</div>

			</div><!--fin form block-->		

			<div class="form-block">
	
				<label><strong>*</strong> Clave:</label>						
				<input tabindex='<?php echo $tabIndex++;?>' type='password' class="txt" name='clave' title='Ingrese su clave de acceso' autocomplete='off' value='' maxlength='32'/>

			</div><!--fin form block-->
	
			<div class="form-block form-bt">
				<input tabindex='<?php echo $tabIndex++;?>' type='submit' name='enviar' title='Ingresar' value='Ingresar'/>				
			</div><!--fin form block-->
			
			<p><strong>*</strong> Su navegador debe aceptar <span>cookies</span></p>	
		</section>
		
	</form>
	
	<footer>
		<p><strong>¿Qué son las Cookies? </strong><br />
		Las cookies son archivos en forma de texto muy breve 
		que envía el servidor a los usuarios.<br />
		Se ubican en la PC del usuario para identificarlo. 
		Sirve para que el usuario se registre solo una vez cada vez que abre 
		el browser o navegador, y luego el sistema lo reconozca automáticamente.
		</p>
	</footer>

</article>

<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});
</script>

<?php processForm("fcontacto", $checkForm); ?>