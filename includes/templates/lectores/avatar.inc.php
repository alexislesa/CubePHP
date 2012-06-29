<?php
/**
 * Muestra un formulario para modificar el avatar de usuario
 */
$tabindex=1;
?>

<?php include (dirTemplate.'/lectores/objetos/profile.inc.php'); ?>

<article>
	
	<header><h2 id="title">Cambiar avatar</h2></header>

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
	?>			
	
	<form name="fcontacto" id="fcontacto" action="?act=true" method="post" enctype='multipart/form-data'>

		<input type="hidden" name="fecha" value="<?php echo date('d.m.Y');?>" />

		<section class="formulario">

			<h5>seleccionar avatar</h5>
		
			<div class="main-avatar">

				<ul>
					
					<?php for ($i=1; $i<14;$i++) { ?>

						<li class="avatarimg">

							<label for="avatar-<?php echo $i;?>" class="label">
								<img src="/images/lectores/avatar/<?php echo $i;?>.jpg" />
							</label>

							<span class="radio-no"><input type="radio" name="avatar" id="avatar-<?php echo $i;?>" value="/images/lectores/avatar/<?php echo $i?>.jpg" /></span>

						</li><!--fin avatar-->

					<?php } ?>

					<li class="avatarimg">

						<label for="gravatar" class="label">
							<img src="<?php echo getGravatar($usr->campos['lector_email'], 40, "/images/lectores/avatar/gravatar.jpg")?>" />
						</label>
	
						<span class="radio-no"><input type="radio" name="avatar" id="avatar-<?php echo $i;?>" value="<?php echo getGravatar($usr->campos['lector_email'], 40, "http://".$_SERVER["SERVER_NAME"]."/images/lectores/avatar/gravatar.jpg")?>" /></span>
						<span class="txt">Usar Gravatar</span>
					
					</li>

				</ul>

			</div><!--fin main avatar-->

			<div class="clear"></div>

			<h5>mi propio avatar</h5>

			<div class="form-block highblock">

				<label>Subir imágen:</label>
	
				<label class="cabinet">
					<span></span>
					<input type="file" name="avatarf" value="" class="file" />
				</label>

				<p class="disc">
					Esta imagen aparecerá en su perfil y en sus comentarios. <br />
					El tamaño de la imagen aconsejado: 40x40px. <br />
					El peso máximo de la imagen debe ser de: 25Kb.
				</p>

			</div><!--fin form bloque-->

			<div class="form-block form-bt">
				<input tabindex='<?php echo $tabindex++;?>' type='submit' name='enviar' title='Guardar Cambios' value='Guardar Cambios'/>
			</div><!--fin form block-->

		</section>
		
	</form>
		
	<footer>
		<p>Este sitio soporta <strong>Gravatar</strong></p>
		<p>
			<strong>¿Qué es un Gravatar?</strong><br />
			Un gravatar es una imagen o foto personalizada con la cual 
			identificas tus intervenciones en los espacios de participacion.<br/>
			Puedes elegir tu Gravatar y usarlo en todas las páginas 
			que soportan Gravatar (en todo el mundo).
			<a href="http://www.gravatar.com" target="_blank">¡Hace click aquí 
			para elegir tu Gravatar! </a>
		</p>
	</footer>

</article>

<?php processForm('fcontacto', $checkForm); ?>

<script type="text/javascript">
SI.Files.stylizeAll();

$("input[name='avatarf']").change(function() {
	$(".cabinet span").html($(this).val());
});

</script>