<?php
/**
 * Muestra un formulario para registrarse
 */
$tabIndex=1;

// Objetizo los datos del formulario
$dt = New ToObj($dataToSkin);
?>
<article>
	
	<header><h2 id="title">Registro de usuarios paso 1/2</h2></header>

	<p class="legend">
		Registrate gratis y obtené tu cuenta para acceder a los espacios de participación.<br />
		Si ya te encuentras registrado hace 
		<a href="/lectores/login.php" title="click aquí">click aquí</a> para ingresar.
	</p><!--fin block-->

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
		<input type="hidden" name="pais_txt" id="pais_txt" value="<?php echo $dt->g('pais_txt');?>" />
		<input type="hidden" name="provincia_txt" id="provincia_txt" value="<?php echo $dt->g('provincia_txt');?>" />
		<input type="hidden" name="departamento_txt" id="departamento_txt" value="<?php echo $dt->g('departamento_txt');?>" />
		<input type="hidden" name="localidad_txt" id="localidad_txt" value="<?php echo $dt->g('localidad_txt');?>" />

		<section class="formulario">

			<h5>Datos del usuario</h5>

			<div class="form-block highblock">
	
				<label><strong>*</strong> Nombre de usuario:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='usuario' autocomplete='off' value='<?php echo $dt->g('usuario');?>' maxlength='15' title="Nombre de usuario" />

				<div class="clear"></div>

				<p class="disc">
					Este será tu seudónimo en los espacios de participación 
					y no podrá ser modificado.<br />
					Debe tener entre 6 y 15 letras y/o números 
					sin espacios en blanco, signos y acentos.
				</p>

				<span class="nombre-error"><span class="ico"></span>Nombre de usuario no disponible</span>
				<span class="nombre-exito"><span class="ico"></span>Nombre de usuario disponible</span>
				<a href="#" class="disponibilidad">[ Verificar disponibilidad ]</a>

				<div class="clear"></div>

			</div><!--fin form block-->

			<div class="form-block wd">

				<label><strong>*</strong> Clave:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='password' autocomplete="off" class="txt" name='clave' value='' maxlength='30'/>

				<div class="clear"></div>

				<p class="disc">La clave debe tener entre 6 y 30 letras y/o números.</p>

				<div class="clear"></div>

			</div><!--fin form block-->

			<div class="form-block highblock">

				<label><strong>*</strong> Repitir Clave:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='password' class="txt" name='clave2' value='' maxlength='30'/>

				<div class="clear"></div>

				<p class="disc">Confirmá tu clave.</p>

				<div class="clear"></div>

			</div><!--fin form block-->
	
			<div class="form-block wd">
	
				<label><strong>*</strong> Email:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='email' value='<?php echo $dt->g('email');?>' maxlength='120' title="Email" />

				<div class="clear"></div>

				<p class="disc">Ingresá un e-mail válido.</p>

				<div class="clear"></div>

			</div><!--fin form block-->

			<h5>Datos Personales:</h5>

			<div class="form-block">

				<label><strong>*</strong> Nombre/s:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='nombre' value='<?php echo $dt->g('nombre');?>' maxlength='35' title="Nombre"/>

			</div><!--fin form block-->
			
			<div class="form-block">

				<label><strong>*</strong> Apellido/s:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='apellido' value="<?php echo $dt->g('apellido');?>" maxlength='64'title="Apellido"  />

			</div><!--fin form block-->
	
			<div class="form-block">

				<label><strong>*</strong> Sexo</label>

				<span class="radio"><input type='radio' class="radio" tabindex='<?php echo $tabIndex++;?>' id='sexo_m' name='sexo' value="M" <?php echo ($dt->g('sexo') == 'M') ? 'checked' : ''; ?> /></span>
				<label for='sexo_m' class='texto-radio'>Masculino</label>

				<span class="radio"><input type='radio' class="radio" tabindex='<?php echo $tabIndex++;?>' id='sexo_f' name='sexo' value="F" <?php echo ($dt->g('sexo') == 'F') ? 'checked' : ''; ?> /></span>
				<label for='sexo_f' class='texto-radio'>Femenino</label>

			</div><!--fin formblock-->

			<div class="form-block">

				<label>DNI:</label>
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='dni' value="<?php echo $dt->g('dni');?>" maxlength='12' title="DNI" />

			</div><!--fin form block-->					
	
			<div class="form-block">
			
				<label><strong>*</strong> Fecha de nacimiento</label>

				<select class="d2" tabindex="<?php echo $tabIndex++;?>" name='fdia'>
				<?php for ($a=1;$a<=31;$a++) { 
					$sel = ($dt->g('fdia') == $a) ? 'selected' : '';
				?>

					<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $a?></option>

				<?php } ?>
				</select>

				<select class="m2" tabindex="<?php echo $tabIndex++;?>" name="fmes">
					<?php for ($a=1;$a<=12;$a++) { 
					$sel = ($dt->g('fmes') == $a) ? 'selected' : ''; ?>
					
						<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $mes_txt[$a];?></option>
					<?php } ?>
				</select>
					
				<select class="a2" tabindex="<?php echo $tabIndex++;?>" name="fanio">
					<?php for ( $a=1901; $a<=(date('Y')-17); $a++ ) { 
					$sel = ($dt->g('fanio') == $a) ? 'selected' : ''; ?>

						<option value='<?php echo $a;?>' <?php echo $sel;?>><?php echo $a?></option>
					<?php } ?>
				</select> 

			</div>	<!--fin formblock-->		

			<!--ubicacion-->
			<div class="form-block">

				<label><strong>*</strong> País:</label>
				<select tabindex='<?php echo $tabIndex++;?>' class="select" id='pais_sel' name='pais'>
					<option value=''>Seleccione un país</option>
				</select>

			</div><!--fin form block-->

			<div id="provincia_block">

				<div class="form-block">

					<label><strong>*</strong> Provincia:</label>

					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='provincia_sel' name='provincia'>
						<option value=''>Seleccione una provincia</option>
					</select>

					<div class="loading" id="provincia_load"></div>

				</div><!--fin item-contact-->
				
			</div><!--fin usrprovinciablock-->

			<div id="departamento_block">

				<div class="form-block">

					<label><strong>*</strong> Dpto / Partido:</label>
					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='departamento_sel' name='departamento'>
						<option value=''>Seleccione un departamento</option>
					</select>

					<div class="loading" id="departamento_load"></div>
				
				</div><!--fin block form-->

			</div><!--fin divprovincianro-->

			<div id="localidad_block">
					
				<div class="form-block">

					<label><strong>*</strong> Localidad:</label>
					<select class="select" tabindex='<?php echo $tabIndex++;?>' id='localidad_sel' name='localidad' >
						<option value=''>Seleccione una localidad</option>
					</select>

					<div class="loading" id="localidad_load"></div>

				</div><!--fin form blok-->

			</div><!--fin divLocalidad-->

			<div id="localidad2_block" style="<?php echo (empty($dataToSkin['localidad']) && !empty($dataToSkin['localidad2'])) ? '' : 'display:none;';?>">

				<div class="form-block">

					<label><strong>*</strong> Localidad:</label>
					<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" autocomplete='off' name='localidad2' id='localidad2_sel' value='<?php echo $dt->g('localidad2');?>' maxlength='60' />

					<div class="loading" id="localidad2_load"></div>

				</div><!--fin form blok-->

			</div><!--fin divLocalidad-->		

			<div class="form-block">

				<label>Teléfono:</label>						
				<input tabindex='<?php echo $tabIndex++;?>' type='text' class="txt" name='campo_1' value="<?php echo $dt->g('campo_1');?>" maxlength='15' title="Teléfono" />

			</div><!--fin form block-->

			<h5>Verificación</h5>

			<div class="form-block captcha">
				<img src="/extras/captcha/registro.php?k=<?php echo microtime(true);?>"  alt="" width="298" height="40"/>
			</div>
	
			<div class="form-block">
				<label><strong>*</strong> Ingresá el código:</label>						
				<input tabindex='<?php echo $tabIndex++;?>' class="txt" type='text' autocomplete='off' name='captcha' maxlength='5' title=""/>
			</div><!--fin form block-->

			<div class="form-block">

				<div class="item-normas">

					<span class="checkbox">
						<input class="check" tabindex='<?php echo $tabIndex++;?>' type="checkbox" name="terminosycondiciones" checked value="1" />
					</span>

					<label>He leído y acepto las <a rel="nofollow" target="_blank" title="Normas de Participación" href="/institucional/normas.php">Normas de Participación</a> y la <a href="/institucional/politicas.php" title="Política de Privacidad" target="_blank">Política de Privacidad</a> </label>

				</div><!--fin radio check-->

			</div><!--fin form block-->

			<div class="form-block form-bt">

				<input tabindex='<?php echo $tabIndex++;?>' type='submit' name='registrarme' title='Enviar' value='Enviar'/>				

			</div><!--fin form block-->

		</section><!--fin /.formulario-->

	</form>

	<footer>
		Los campos marcados con un <strong>(*)</strong> son obligatorios
	</footer>

</article>

<script type="text/javascript">
$("#fcontacto input[type='text']").each(function() {
	if ($(this).attr("title") != "") {
		$(this).fieldtag();
	}
});
</script>

<?php 
// JS de listado de paises
include (dirTemplate.'/herramientas/lista_paises.inc.php');

// Procesa las validaciones del Form
processForm('fcontacto', $checkForm); 
?>

<script type="text/javascript">
$("a.disponibilidad").click(function() {

	var m = $(this).parent();

	if ($(m).find(":input").val() == "") {
		alert("Debe ingresar un nombre de usuario");
		return false;
	}
	
	var n = $(m).find(":input").val();
	if (n.length < 6 || n.lenght > 15) {
		alert("El nombre de usuario debe tener entre 6 y 15 caracteres.");
		return false;
	}
	
	$(m).find(":input").addClass("check-disponibilidad");
	$(m).find(".nombre-error").hide();
	$(m).find(".nombre-exito").hide();
	
	var url = "check-name.php?n=" + $(m).find(":input").val();
	$.ajax({
		type: "GET",
		async: false,
		url: url,
		success: function(msg){
			if (msg == "1") {
				$(m).find(".nombre-error").fadeIn();
			} else {
				$(m).find(".nombre-exito").fadeIn();
			}
			
			$(m).find(":input").removeClass("check-disponibilidad");
		}
	});
	return false;
});
</script>

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