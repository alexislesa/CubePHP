<?php 
/**
 * Bloque que muestra la pantalla de login en el encabezado del sitio
 */
if (empty($_SESSION['web_site']['user'])) { ?>

	<style>
	.combo-box, .login-box .close {cursor:pointer;}
	.login-box { display:none; }
	</style>


	<div class="user">

		<ul>
			<li class="combo-box"><span>Iniciar Sesión</span><span class="ico"></span></li>

			<li class="default"><a href="/lectores/registro.php" title="Registrarse">Registrarse</a></li>

		</ul>
		
		<div class="login-box">

			<span class="close" title="Cerrar">x</span>

			<div class="login-inner">

				<form name="form-login" id="logintop" action="#">
					<h4><span>Iniciar sesión</span></h4>	

					<div class="log-item">									
						<label>Usuario:</label>
						<input type="text" class="txt" title="Usuario" name="usuario" autocomplete="off" maxlength="32" value="" />
					</div>

					<div class="log-item">									
						<label>Clave:</label>
						<input type="password" class="txt" title="Clave" name="clave" maxlength="32" value="" />
					</div>

					<div class="log-bottom">									
						<span>¿<a href="/lectores/recupera-clave.php" title="Recuperar Clave">Recuperar clave</a>?</span>
						<input type="submit" class="btn" value="Ingresar" />
					</div>
				</form>

			</div><!--/.inner-->

		</div><!--/.login-box-->
		
		<script>
			$(".combo-box").click(function () {
				$(".login-box").fadeIn();
			});
			$(".login-box .close").click(function () {
				$(".login-box").fadeOut();
			});

			var topr39fd4 = [];
			topr39fd4.push("required,usuario,Ingrese su usuario");
		 
			$(document).ready(function() {
				$("#logintop").RSV({
					onCompleteHandler: myOnCompleteTop,
					displayType: "alert-one",
					rules: topr39fd4 });
			});	
		
			function myOnCompleteTop() {
				var sData = $("#logintop").serialize();

				$.ajax({
					type: "POST",
					async: false,
					url: "/lectores/login-pop.php?act=true",
					data: $("#logintop").serialize(),
					success: function(msg){
						if (msg == "OK") {
							setTimeout('document.location.reload()',50);
						} else {
							alert(msg);
						}
					}
				});
				
				return false;
			}
		</script>		
	
	</div><!--fin user-->

<?php 
// Pantalla en caso de que el usuario se encuentre logrado
} else { ?>

	<div class="user">
		
		<ul>
			<li class="combo-box"><a href="/lectores" title="" ><?php echo $_SESSION['web_site']['user'];?></a></li>
			<li class="default"><a href="/lectores/salir.php" title="Salir">Salir</a></li>
		</ul>

	</div><!--fin user-->			

<?php } ?>