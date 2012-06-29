<?php
/**
 * Muestra un formulario para contactos
 */
$tabIndex=1;

// Cantidad máxima de caracteres en el textarea
$textMaxLength = 600;

// Objetizo los datos del formulario
$dt = New ToObj($dataToSkin);

/**
 * MODO DEBUG:
 * Activa o desactiva el modo debug del formulario de contactos
 * Una vez finaliza el período de test, pasar a modo debug false
 */
$debugMode = true;

if ($debugMode && !$msjError) {
	$msjError = "El siguiente formulario esta en modo debug, y este es un bloque de error";
}
?>
<article class="form">
	
	<header><h2 id="title">Contactenos</h2></header>

	<?php 
	// Muestra mensaje de error
	if ($msjError) {
		include(dirTemplate.'/herramientas/mensaje-error.inc.php');
	}
	?>

	<section class="formulario">

		<p class="info">Comuniquese completando el siguiente formulario o bien escribiendo un email a </p>

		<form name="fcontacto" id="fcontacto" action="?act=true" method="post">

			<input type="hidden" name="token" value="<?php echo $token;?>" />
			<input type="hidden" name="pais_txt" id="pais_txt" value="<?php echo $dt->g('pais_txt');?>" />
			<input type="hidden" name="provincia_txt" id="provincia_txt" value="<?php echo $dt->g('provincia_txt');?>" />
			<input type="hidden" name="departamento_txt" id="departamento_txt" value="<?php echo $dt->g('departamento_txt');?>" />
			<input type="hidden" name="localidad_txt" id="localidad_txt" value="<?php echo $dt->g('localidad_txt');?>" />

			<h5>Datos personales</h5>

			<div class="form-block">

				<label><strong>*</strong> Nombre/s:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='nombre' title='Nombre...' value='<?php echo $dt->g('nombre');?>' maxlength='35'/>

			</div><!--fin form block-->
			
			<div class="form-block">
	
				<label><strong>*</strong> Apellido/s:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='apellido' title='Apellido...' value="<?php echo $dt->g('apellido');?>" maxlength='64' />

			</div><!--fin form block-->

			<div class="form-block">
		
				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='email' title='Email...' value='<?php echo $dt->g('email');?>' maxlength='120' />

			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Teléfono:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt txt2" autocomplete='off' name='tel_pref' value='<?php echo $dt->g('tel_pref');?>' title='' maxlength='4' />
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt txt3" autocomplete='off' name='tel_nro' title='Teléfono...' value='<?php echo $dt->g('tel_nro');?>' maxlength='8' />
                
			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Otro Teléfono:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt txt2" autocomplete='off' name='tel_otro_pref' value='<?php echo $dt->g('tel_otro_pref');?>' title='' maxlength='4' />
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt txt3" autocomplete='off' name='tel_otro_nro' title='Teléfono...' value='<?php echo $dt->g('tel_otro_nro');?>' maxlength='8' />
                
			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Empresa:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='empresa' title='Empresa...' value='<?php echo $dt->g('empresa');?>' maxlength='120' />

			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Cargo:</label>						
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='cargo' title='Cargo...' value='<?php echo $dt->g('cargo');?>' maxlength='80' />

			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Rubro / Actividad:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='rubro' title='Rubro...' value='<?php echo $dt->g('rubro');?>' maxlength='80' />

			</div><!--fin form block-->

			<div class="form-block">
		
				<label>Sitio Web:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='sitio' title='Sitio Web...' value='<?php echo $dt->g('sitio');?>' maxlength='120' />

			</div><!--fin form block-->					

			<!---ubicacion--->
			<div class="form-block">

				<label><strong>*</strong> País:</label>
				<select tabindex='<?php echo $tabIndex++;?>' class="select" id='pais_sel' name='pais'>
					<option value=''>Seleccione un país</option>
				</select>

			</div><!--fin form block-->
					
			<div class="form-block" id="provincia_block">
	
				<div class="form-block">
	
					<label><strong>*</strong> Provincia:</label>
					<select tabindex='<?php echo $tabIndex++;?>' id='provincia_sel' class="select" name='provincia'>
						<option value=''>Seleccione una provincia</option>
					</select>
	
					<div class="loading" id="provincia_load"></div>
	
				</div><!--fin item-contact-->

			</div><!--fin usrprovinciablock-->
	
			<div class="form-block" id="departamento_block">
	
				<div class="form-block">
	
					<label><strong>*</strong> Dpto / Partido:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" id='departamento_sel' name='departamento'>
						<option value=''>Seleccione un departamento</option>
					</select>

					<div class="loading" id="departamento_load"></div>
					
				</div><!--fin block form-->
	
			</div><!--fin divprovincianro-->
	
			<div id="localidad_block">
	
				<div class="form-block formblock2">
	
					<label><strong>*</strong> Localidad:</label>
					<select tabindex='<?php echo $tabIndex++;?>' class="select" id='localidad_sel' name='localidad' >
						<option value=''>Seleccione una localidad</option>
					</select>
	
					<div class="loading" id="localidad_load"></div>
	
				</div><!--fin form blok-->
	
			</div><!--fin divLocalidad-->

			<div class="form-block" id="localidad2_block" style="display:none;">

				<div class="form-block">
					<label><strong>*</strong>Localidad:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='localidad2' id='localidad2_sel' value='<?php echo $dt->g('localidad2');?>' maxlength='60' />
				
					<div class="loading" id="localidad2_load"></div>

				</div><!--fin form blok-->
	
			</div><!--fin divLocalidad-->		

			<div class="form-block">
		
				<label>Adjuntar archivo:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='file' class="txt" name='adjunto' title='' value='' />

			</div><!--fin form block-->
			
			<h5>Motivo de la Consulta</h5>

			<div class="form-block">
		
				<label><strong>*</strong> Asunto:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='asunto' title='Asunto...' value='<?php echo $dt->g('asunto');?>' maxlength='120' />

			</div><!--fin form block-->

			<div class="form-block formprofile">
	
				<label><strong>*</strong> Mensaje:</label>

				<textarea  name="mensaje" tabindex='<?php echo $tabIndex++;?>'><?php echo $dt->g('mensaje');?></textarea>

				<div class="countdown">
					<input type="text" value="<?php echo $textMaxLength;?>" readonly />
					<span>Máximo <?php echo $textMaxLength;?> caracteres.</span>
				</div>

			</div><!--fin form block-->

			<h5>Verificacion</h5>

			<div class="form-block captcha">

				<img src="/extras/captcha/contacto.php?=<?php echo microtime(true);?>"  alt="Imagen captcha" width="298" height="40"/>

			</div>
	
			<div class="form-block">
	
				<label><strong>*</strong> Ingresá el código:</label>
				<input tabindex='<?php echo $tabIndex++;?>' class="txt" type='text' autocomplete='off' name='captcha' maxlength='5'/>
	
			</div><!--fin form block-->

			<div class="form-block but">

				<input tabindex='<?php echo $tabIndex++;?>' type='reset' name='limpiar' value='Borrar' title='Borrar' class="borrar"/>
				<input tabindex='<?php echo $tabIndex++;?>' type='submit' name='enviar' title='Enviar' value='Enviar' class="enviar"/>				
	
			</div><!--fin form block-->
	
		</form>

	</section><!--fin formulario-->
	
	<footer>
		<p>Los campos marcados con un <strong>(*)</strong> son obligatorios</p>
	</footer>

</article>


<?php
/**
 * Genera las marcas de Agua en los formularios
 * y carga el contador máximo de caracteres para el mensaje
 */
?>
<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});

$("#fcontacto textarea").each(function() {
	$(this).keypress( function () {
		m = $(this)[0];
		if (m.value.length > <?php echo $textMaxLength;?>) {
			alert("Ha superado los <?php echo $textMaxLength;?> caracteres");
			m.value = m.value.substr(0,<?php echo $textMaxLength;?>);
		}
	
		$(this).next().find("input").val(<?php echo $textMaxLength;?> - m.value.length);
	});
});
</script>

<?php include (dirTemplate.'/herramientas/lista_paises.inc.php'); ?>

<?php if (empty($_GET['nojs'])) {
	processForm('fcontacto', $checkForm);
} ?>

<?php
// como estoy en modo debug, muestro los iconos de loading
if ($debugMode) { ?>
	<script type="text/javascript">
		$("#provincia_load").html("<?php echo $ubicacionIconLoading;?>");
		$("#departamento_load").html("<?php echo $ubicacionIconLoading;?>");
		$("#localidad_load").html("<?php echo $ubicacionIconLoading;?>");
		$("#localidad2_load").html("<?php echo $ubicacionIconLoading;?>");
	</script>
<?php } ?>