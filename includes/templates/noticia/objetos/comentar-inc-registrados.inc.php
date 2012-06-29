<?php
/**
 * Muestra bloques para comentar de usuarios registrados al sitio
 * Muestra 3 instancias del comentario, el login, el comentar y la pantalla de respuesta.
 */
if ($v['noticia_comentarios']) { ?>

	<div id="bloque-comentario-msj" style="display:none;">
		<p>Su comentario se envío con éxito.</p>
		<a href="#" class="submit" title="Nuevo comentario" href="">Nuevo comentario</a>
	</div>
	
	<script type="text/javascript">
		$("#bloque-comentario-msj a").click(function() {
			$("#bloque-comentario-msj").hide();
			$("#bloque-comentario textarea").val("");
			$("#bloque-comentario").fadeIn("slow");
			return false;
		});
	</script>

	<section class="bloque-comentarios">

		<?php 
		// Bloque de login
		if (empty($_SESSION['web_site']['user'])) { ?>
		
			<div id="bloque-comentario-login" class="block">
			
				<h4>Enviar Comentario</h3>
				
				<div class="error-msj" style="display:none;"></div>
				
				<div class="formulario">
					
					<span class="title2">Ingresá tu nombre de usuario y contraseña:</span>
					
					<form name="fcomentarnota" id="fcomentarnota" action="?" method="post">
					
						<div class="form-com" id="nombre">
							<label>Usuario:</label>
							<input type="text" name="usuario" value="" maxlength="35" autocomplete="off" class="txt" />
						</div>
					
						<div class="form-com" id="clave">
							<label>Clave:</label>
							<input type="password" name="clave" value="" autocomplete="off" class="txt"/>
						</div>

						<div class="com-bt">
					
							<input type="submit" title="Ingresar" value="Ingresar" />
					
						</div>
						
						<div class="clear"></div>
					</form>
					
					<span class="title2"><b>¿Aún no eres usuario?</b>
						Click <a href="/lectores/registro.php" rel="nofollow" title="">aquí</a> para registrase
					</span>
				</div><!--fin formulario-->
				
				<div class="legend">
					<b>IMPORTANTE:</b> Los comentarios publicados son exclusiva 
					responsabilidad de sus autores. Este sitio se reserva 
					el derecho de eliminar aquellos comentarios injuriantes, 
					discriminadores o contrarios a las leyes de la República Argentina
				</div>
			
				<div class="clear"></div>
				
				<script type="text/javascript">
					var notalogin<?php echo $v['noticia_id']?> = [];
					
					notalogin<?php echo $v['noticia_id']?>.push("required,usuario,Ingrese un nombre de usuario"); 
					notalogin<?php echo $v['noticia_id']?>.push("length=6-15,usuario,El nombre de usuario debe tener entre 6 y 15 caracteres"); 
					notalogin<?php echo $v['noticia_id']?>.push("is_alpha,usuario,El nombre de usuario no puede contener espacios\\,guiones\\,símbolos ni acentos"); 
					notalogin<?php echo $v['noticia_id']?>.push("required,clave,Ingrese una clave"); 
					notalogin<?php echo $v['noticia_id']?>.push("length=6-15,clave,La clave debe tener entre 6 y 30 caracteres"); 

					$(document).ready(function() {
						$("#fcomentarnota").RSV({
							onCompleteHandler: myOnCompleteNota<?php echo $v['noticia_id']?>,
							displayType: "alert-one",
							rules: notalogin<?php echo $v['noticia_id']?> 
						});
					});	
				
					function myOnCompleteNota<?php echo $v['noticia_id']?>() {
						$.ajax({
							type: "POST",
							async: false,
							url: "/lectores/login-pop.php?act=true",
							data: $("#fcomentarnota").serialize(),
							success: function(msg){
								if (msg == "OK") {
									$("#fcomentarnota input").val("");
									$("#bloque-comentario-login").slideUp("fast", function() {
										$("#bloque-comentario").slideDown("fast");
									});
								} else {
									var mj = $("#bloque-comentario-login .error-msj");
									$(mj).html(msg);
									$(mj).fadeIn("fast", function() {
										$(mj).delay(2000).fadeOut("slow");
									});
								}
							}
						});
						
						return false;
					}
				</script>

			</div>

		<?php } ?>
		
		
		<?php 
		// Bloque de comentar (usuario logeado)
		$bloqueClass = (empty($_SESSION['web_site']['user'])) ? "style='display:none;'" : '';
		?>

		<section id="bloque-comentario" <?php echo $bloqueClass;?>>

			<h4>Enviar comentario</h4>
			
			<div class="error-msj"></div>
			
			<div class="formulario">
			
				<form name="fcomentarnota2" id="fcomentarnota2" action="?" method="post">
				
					<input type="hidden" name="id" value="<?php echo $v['noticia_id'];?>" />
				
					<textarea name="mensaje" id="bloque-com-txt"></textarea>
				
					<div class="com-caract">
						<input type="text" name="contador" value="600"/> <span>/ 600 Caract.</span>				
					</div><!--fin caracteres-->
					
					<div class="term">
						
						<input type="checkbox" name="terminosyc" value="1" checked/>
						<span>Acepto las <a href="/institucional/normas.php" title="Normas de Participación" target="_blank">Normas de Participación</a></span>
					</div><!--fin term-->
				
					<div class="com-bt">
						<span class="rounded"><span><b>Enviar</b></span></span><input type="submit" title="Enviar" value="" />
					</div>
					<div class="clear"></div>
				</form>

			</div><!--fin formulario-->

			<div class="disc">
				<b>IMPORTANTE:</b> Los comentarios publicados son exclusiva 
				responsabilidad de sus autores. este sitio se reserva el 
				derecho de eliminar aquellos comentarios injuriantes, 
				discriminadores o contrarios a las leyes de la República Argentina
			</div>
			
			<div class="clear"></div>
			
			<script type="text/javascript">
			$("#bloque-comentario textarea").limit(600, "#bloque-comentario .comentario-caract input", 15);
			
			var notacom<?php echo $v['noticia_id']?> = [];
		
			notacom<?php echo $v['noticia_id']?>.push("required,mensaje,Ingrese su comentario"); 
			notacom<?php echo $v['noticia_id']?>.push("length=0-600,mensaje,El comentario no puede superar los 600 caracteres"); 
			notacom<?php echo $v['noticia_id']?>.push("required,terminosyc,Debe aceptar los términos y condiciones"); 
			
			$(document).ready(function() {
				$("#fcomentarnota2").RSV({
					onCompleteHandler: myOnCompleteCom,
					displayType: "alert-one",
					rules: notacom<?php echo $v['noticia_id']?> 
				});
			});	
		
			function myOnCompleteCom() {
				$.ajax({
					type: "POST",
					async: false,
					url: "/extras/notas/comentario-enviar.php",
					data: $("#fcomentarnota2").serialize(),
					success: function(msg){
						if (msg == "OK") {
							$("#bloque-com-txt").val("");
							$("#bloque-comentario").slideUp("fast", function() {
								$("#bloque-comentario-fin").slideDown("fast");
							});
						} else {
							var mj = $("#bloque-comentario .error-msj");
							$(mj).html(msg);
							$(mj).fadeIn("fast", function() {
								$(mj).delay(2000).fadeOut("slow");
							});
						}
					}
				});
				
				return false;
			}
			</script>

		</section><!-- /bloque-comentario-->

		<?php
		// Bloque a mostrar cuando el comentario se ha enviado con éxito
		?>
		<section id="bloque-comentario-fin" style="display:none;" class="article-block cm-block">

			<h4>Enviar comentario</h4>
			
			<div class="formulario">
				
				<span class="exito"><b>Tu comentario ha sido enviado exitosamente !!!</b> 
				La publicación del mismo estará sujeta a revisión por parte del administrador</span>
				
				<div class="nuevoc">
					<span class="rounded"><span>
						<a href="#" title="Nuevo comentario">nuevo comentario</a>
					</span></span>
				</div>
			
			</div><!--fin formulario-->
			
			<div class="disc">
				<b>IMPORTANTE:</b> Los comentarios publicados son exclusiva 
				responsabilidad de sus autores. Este sitio se reserva 
				el derecho de eliminar aquellos comentarios injuriantes, 
				discriminadores o contrarios a las leyes de la República Argentina
			</div>
			
			<div class="clear"></div>
		
		</section><!--fin /bloque-comentario-fin-->
		
		<script type="text/javascript">
		$("#bloque-comentario-fin .nuevoc a").click( function() {
			$("#bloque-comentario-fin").slideUp("fast", function() {
				$("#bloque-comentario").slideDown("fast");
			});
			return false;
		});
		</script>

	</section><!--fin /bloque comentarios-->
	
<?php } ?>