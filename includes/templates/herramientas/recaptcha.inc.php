<?php
/**
 * Genera un bloque de comprobación vía recaptcha
 * Requiere tener la clave generada en la variable: "publicKey"
 */

/**
 * Estilos Utilizados:
 *
	#recaptcha_widget{border:1px solid #CCC;width:302px;margin:40px auto;padding:10px;position:relative;font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;}
	#recaptcha_image{border:1px solid #CCC;margin:0 auto 10px;}
	#recaptcha_image img{}
	.recaptcha_only_if_image, .recaptcha_only_if_audio{font-size:11px;float:left;width:200px;display:inline-block;}
	#recaptcha_response_field{float:right;width:98px;border:1px solid #CCC;}
	.rebottom{margin-top:10px;}
	#recaptcha_widget a{color:#333;font-weight:bold;text-decoration:none;display:block;margin-bottom:10px;}
 *
 */
?>
<div id="recaptcha_widget" style="display:none">

	<div id="recaptcha_image"></div>

	<div class="recaptcha_only_if_incorrect_sol" style="color:red">Incorrecto. Intente nuevamente</div>

	<span class="recaptcha_only_if_image">Introduzca las palabras de la imagen:</span>
	
	<span class="recaptcha_only_if_audio">Introduzca los números que escucha:</span>

	<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />
	
	<div class="clear"></div>
	
	<div class="rebottom"><a href="javascript:Recaptcha.reload()">Mostrar otro CAPTCHA</a></div>
	
	<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Cargar un audio CAPTCHA</a></div>
	
	<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">cargar una imagen CAPTCHA</a></div>
	
	<div class="clear"></div>
	
	<div><a href="javascript:Recaptcha.showhelp()">Ayuda</a></div>

	<noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $publicKey;?>" height="300" width="500" frameborder="0"></iframe>
		<br>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
	</noscript>

	<div class="clear"></div>

</div>

<script type="text/javascript">
var RecaptchaOptions = {
	lang : "es",
	theme : "custom",
	custom_theme_widget: "recaptcha_widget"
};
</script>

<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $publicKey;?>"></script>