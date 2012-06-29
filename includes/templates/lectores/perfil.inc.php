<?php
/**
 * Muestra un formulario para modificar el perfil del usuario
 *
 */
$tabindex=1; 
?>

<?php include (dirTemplate.'/lectores/objetos/profile.inc.php'); ?>

<article>
	
	<header><h2 id="title">Editar perfil</h2></header>

	<figure><img src="<?php echo $usr->campos['lector_avatar'];?>" width="40" height="40" alt="" title="" /></figure>

	<?php 
	// Mensaje de Error
	if ($msjError) {
		include (dirTemplate.'/herramientas/mensaje-error.inc.php');
	}

	// Mensaje de Alerta
	if ($msjAlerta) {
		include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
	}

	// Mensaje de Éxito
	if ($msjExito) {
		include (dirTemplate.'/herramientas/mensaje-exito.inc.php');
	}	
	?>

	<form name="fcontacto" id="fcontacto" action="?act=true" method="post">
		<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />
		<input type="hidden" name="pais_txt" id="pais_txt" value="<?php echo !empty($dataToSkin['pais_txt']) ? $dataToSkin['pais_txt'] : '';?>" />
		<input type="hidden" name="provincia_txt" id="provincia_txt" value="<?php echo !empty($dataToSkin['provincia_txt']) ? $dataToSkin['provincia_txt'] : '';?>" />
		<input type="hidden" name="departamento_txt" id="departamento_txt" value="<?php echo !empty($dataToSkin['departamento_txt']) ? $dataToSkin['departamento_txt'] : '';?>" />
		<input type="hidden" name="localidad_txt" id="localidad_txt" value="<?php echo !empty($dataToSkin['localidad_txt']) ? $dataToSkin['localidad_txt'] : '';?>" />
	
		<section class="formulario">
			
			<h5>datos del usuario</h5>
			
			<div class="form-block">

				<label><strong>*</strong> Nombre de usuario:</label>
				<span class="faketxt"><?php echo $dataToSkin['usuario'];?></span>

			</div><!--fin form block-->

			<div class="formprofile form-block">

				<label>Texto de presentación:</label>
				<textarea tabindex='<?php echo $tabindex++;?>' name='perfil'><?php echo $dataToSkin['perfil'];?></textarea>
				
				<div class="countdown">
					<input type="text" id="perfil-texto" value="<?php echo 250-(strlen($dataToSkin['perfil']));?>"/>
					<span>Caracteres restantes. Máximo 250 caracteres.</span>
				</div>

			</div><!--fin form block-->

			<div class="form-block">

				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='email' value='<?php echo $dataToSkin['email'];?>' maxlength='120' />
			
			</div><!--fin form block-->
	
			<h5>Datos Personales:</h5>
			
			<div class="form-block">

				<label><strong>*</strong> Nombre/s:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='nombre' value='<?php echo $dataToSkin['nombre'];?>' maxlength='35'/>
			
			</div><!--fin form block-->
	
			<div class="form-block">

				<label><strong>*</strong> Apellido/s:</label>
				<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" name='apellido' value="<?php echo $dataToSkin['apellido'];?>" maxlength='64' />
			
			</div><!--fin form block-->
	
			<div class="form-block">

				<label><strong>*</strong> Sexo:</laebl>
			
				<div class="item-sexo">
					<span class="<?php echo ($dataToSkin['sexo'] == 'M') ? 'radio-enabled' : 'radio-disabled'; ?>"></span>
					<label class='texto-radio'>Masculino</label>
				
					<span class="<?php echo ($dataToSkin['sexo'] == 'F') ? 'radio-enabled' : 'radio-disabled'; ?>"></span>
					<label class='texto-radio'>Femenino</label>
			
				</div><!--fin item-->
			
			</div><!--fin formblock-->
		
			<div class="form-block">

				<label><strong>*</strong> Fecha de nacimiento:</label>
				
				<div class="item-nac">
					<select class="d2" tabindex="<?php echo $tabindex++;?>" name="fdiaq" disabled>
						<?php for ($a=1;$a<=31;$a++) { 
							$sel = ($dataToSkin['fdia'] == $a) ? 'selected' : ''; ?>

							<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $a?></option>

						<?php } ?>
					</select>
					
					<select class="m2" tabindex="<?php echo $tabindex++;?>" name="fmesq" disabled>
						<?php for ($a=1;$a<=12;$a++) { 
							$sel = ($dataToSkin['fmes'] == $a) ? 'selected' : ''; ?>
							
								<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $mes_txt[$a];?></option>
						<?php } ?>
					</select>
						
					<select class="a2" tabindex="<?php echo $tabindex++;?>" name="fanioq" disabled>
						<?php for ( $a=1901; $a<=(date('Y')-17); $a++ ) { 
							$sel = ($dataToSkin['fanio'] == $a) ? 'selected' : ''; ?>
						
							<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $a?></option>
							
						<?php } ?>
					</select>	
				</div><!--fin item-nac-->

			</div><!--fin forom block-->
		
			<!---ubicacion--->
			<div class="form-block">
				<label><strong>*</strong> País:</label>
				<select class="select" tabindex='<?php echo $tabindex++;?>' id='pais_sel' name='pais'>
					<option value=''>Seleccione un país</option>
				</select>
			</div><!--fin form block-->
			
			<div id="provincia_block" style="display:none;">

				<div class="form-block">
					<label><strong>*</strong> Provincia:</label>
					<select class="select" tabindex='<?php echo $tabindex++;?>' id='provincia_sel' name='provincia'>
						<option value=''>Seleccione una provincia</option>
					</select>

					<div class="loading" id="provincia_load"></div>

				</div><!--fin item-contact-->
				
			</div><!--fin usrprovinciablock-->

			<div id="departamento_block" style="display:none;">

				<div class="form-block">
					<label><strong>*</strong> Dpto / Partido:</label>
					<select class="select" tabindex='<?php echo $tabindex++;?>' id='departamento_sel' name='departamento'>
						<option value=''>Seleccione un departamento</option>
					</select>

					<div class="loading" id="departamento_load"></div>
				
				</div><!--fin block form-->

			</div><!--fin divprovincianro-->

			<div id="localidad_block" style="display:none;">
			
				<div class="form-block formblock2">
					<label><strong>*</strong> Localidad:</label>
					<select class="select" tabindex='<?php echo $tabindex++;?>' id='localidad_sel' name='localidad' >
						<option value=''>Seleccione una localidad</option>
					</select>

					<div class="loading" id="localidad_load"></div>

				</div><!--fin form blok-->

			</div><!--fin divLocalidad-->

			<div id="localidad2_block" style="<?php echo (empty($dataToSkin['localidad']) && $dataToSkin['localidad2'] != '') ? '' : 'display:none;';?>">

				<div class="form-block">
					<label><b>*</b> Localidad:</label>
					<input tabindex='<?php echo $tabindex++;?>' type='text' class="txt" autocomplete='off' name='localidad2' id='localidad2_sel' value='<?php echo $dataToSkin['localidad2'];?>' maxlength='60' />
				
					<div class="loading" id="localidad2_load"></div>

				</div><!--fin form blok-->

			</div><!--fin divLocalidad-->		

			<h5>subscripciones</h5>
			
			<div class="form-block highblock">
			
				<div class="itm-normas">
					<span class="radio"><input type='checkbox' tabindex='<?php echo $tabindex++;?>' name='campo_1' value="1" <?php echo ($dataToSkin['campo_1'] == '1') ? 'checked' : ''; ?> /></span>
					<label>Deseo recibir titulares por e-mail.</label>
				</div>

				<div class="item-normas">
					<span class="radio"><input type='checkbox' tabindex='<?php echo $tabindex++;?>' name='campo_2' value="1" <?php echo ($dataToSkin['campo_2'] == '1') ? 'checked' : ''; ?> /></span>
					<label>Deseo recibir alertas informativas por email.</label>
				</div>

				<div class="item-normas">
					<span class="radio"><input type='checkbox' tabindex='<?php echo $tabindex++;?>' name='campo_3' value="1" <?php echo ($dataToSkin['campo_3'] == '1') ? 'checked' : ''; ?> /></span>
					<label>Deseo recibir información por e-mail sobre concursos y promociones.</label>
				</div>

			</div><!--fin form block-->

			<div class="form-block form-bt">

				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Guardar Cambios' value='Guardar Cambios'/>

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

$("#fcontacto textarea[name='perfil']").limit(250, "#perfil-texto", 15);
</script>

<?php include (dirTemplate.'/herramientas/lista_paises.inc.php'); ?>

<?php processForm('fcontacto', $checkForm); ?>