<?php
/**
 * Muestra un bloque para comentar de forma anónima
 * Muestra el bloque solo si esta habilitado para comentar
 */
if ($v['noticia_comentarios']) { ?>

	<div id="bloque-comentario-msj" style="display:none;">

		<p>Su comentario se envío con éxito.</p>
		<a href="#" class="submit" title="Nuevo comentario">Nuevo comentario</a>

	</div>

	<script type="text/javascript">
		$("#bloque-comentario-msj a").click(function() {
			$("#bloque-comentario-msj").hide();
			$("#bloque-comentario textarea").val("");
			$("#bloque-comentario").fadeIn("slow");
			return false;
		});
	</script>
	
	<section class="article-block cm-block" id="bloque-comentario">
	
		<h4>Comenta esta nota</h4>
		
		<div class="formulario">
		
			<form name="fcomentario" id="fcomentario" action="?" method="post">
				<input type="hidden" name="id" value="<?php echo $v['noticia_id'];?>" />
				
				<div class="form-com">
					<label>Nombre y Apellido:</label>
					<input type="text" name="nombre" title="Ingrese su nombre y apellido" value="" maxlength="35" autoload="off" class="txt"/>
				</div>
			
				<div class="form-com">
					<label>Email:</label>
					<input type="text" name="email" value="" title="Ingrese su email" maxlength="120" autoload="off" class="txt"/>
				</div>
			
				<div class="form-com formcom">
					<label>Comentario:</label><textarea name="mensaje"></textarea>
				
					<div class="comdown"><input type="text" name="contador" value="600"/><span>Caracteres disponibles</span></div>
				
				</div><!--fin caracteres-->
			
				<div class="com-bt">
				
					<input type="submit" title="Enviar comentario" value="Enviar Comentario" />
				
				</div>
				<div class="clear"></div>
			</form>
			
		</div><!--fin formulario-->

		<script type="text/javascript">

		$("#fcomentario textarea").keypress( function () {
			m = $("#fcomentario textarea")[0];
			if (m.value.length > 600) {
				alert("Ha superado los 600 caracteres");
				m.value = m.value.substr(0,600);
			}
			
			$("#fcomentario .comdown input").val(600 - m.value.length);
		});
		
		var r39fd4 = [];
	
		r39fd4.push("required,nombre,Ingrese su nombre y apellido"); 
		r39fd4.push("length=0-35,nombre,El nombre no puede superar los 35 caracteres de longitud"); 
		r39fd4.push("required,email,Ingrese su email"); 
		r39fd4.push("valid_email,email,Ingrese un email válido"); 
		r39fd4.push("required,mensaje,Ingrese su comentario"); 
		r39fd4.push("length=0-600,mensaje,El comentario no puede superar los 600 caracteres"); 
	 
		$(document).ready(function() {
			$("#fcomentario").RSV({
				onCompleteHandler: myOnComplete,
				displayType: "alert-one",
				rules: r39fd4 });
		});	
	
		function myOnComplete() {
			var sData = $("#fcomentario").serialize();

			$.ajax({
				type: "POST",
				async: false,
				url: "/extras/notas/comentario.php",
				data: $("#fcomentario").serialize(),
				success: function(msg){
					$("#bloque-comentario").hide();
					$("#bloque-comentario-msj").fadeIn("slow");
				}
			});
			
			return false;
		}
		</script>

	</section><!--fin /bloque-comentario-->
	
<?php } ?>