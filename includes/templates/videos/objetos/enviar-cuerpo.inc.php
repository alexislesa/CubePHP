<?
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	24.08.2010
 * *************************************************************** 
 *
 * Muestra un bloque para el envío de artículos por email
 * 
 * Array de datos que es posible devolver:
 *
 *
 * @changelog:
 */

/**
 * Cantidad máxima de caracteres del mensajes
 */
$max_length = 250;
?>
 
<div class="encuesta" id="enviar">
	
	<div class="inner-encuesta">

		<img src="/images/print/logo.png" width="300" height="51" />
		
		<div class="container-form">
			
			<span class="ico border"></span>
			
			<? if ($error_msj) {
		
				include ($path_root."/includes/templates/herramientas/mensaje-error.inc.php");
			
			} ?>
		
			<form name="form1" id="form1" action="?act=true&id=<?=$notaToSkin[0]["gal_id"];?>" method="post">
	
				<input type="hidden" name="id" value="<?=$notaToSkin[0]["gal_id"];?>" />
				
				<input type="hidden" name="titulo" value="<?=$notaToSkin[0]["gal_nombre"];?>" />
				
				<input type="hidden" name="seccion" value="<?=$notaToSkin[0]["gal_descripcion"];?>" />
				
				<input type="hidden" name="url" value="http://<?=$_SERVER["SERVER_NAME"];?>videos/index.php?id=<?=$notaToSkin[0]["gal_id"];?>" />
				
				<input type="hidden" name="bajada" value="<?=$notaToSkin[0]["noticia_descripcion"];?>" />
	
				<? $tabindex=0; ?>
				<div class="form-block left">
					<span><b>*</b> Destinatario</span>
					<input type="text" name="destinatario" tabindex="<?=$tabindex++;?>" class="txt" value="<?=@$dataToSkin["destinatario"]?>"/>
				</div>
							
				<div class="form-block right">	
					<span><b>*</b> Email</span>
					<input type="text" class="txt" tabindex="<?=$tabindex++;?>" name="destinatario-mail" value="<?=@$dataToSkin["destinatario-mail"]?>" />
				</div>
								
				<div class="form-block left">
					<span><b>*</b> Remitente</span>
					<input type="text" name="nombre" tabindex="<?=$tabindex++;?>" class="txt" value="<?=@$dataToSkin["nombre"]?>"/>
				</div>
			
				<div class="form-block right">	
					<span><b>*</b> Email</span>
					<input type="text" name="email" tabindex="<?=$tabindex++;?>" class="txt" value="<?=@$dataToSkin["destinatario"]?>" />
				</div>
				
				<div class="clear"></div>
				
				<span class="ico border"></span>

				<div class="comentario">
					<span>Añadir comentario <b>(opcional)</b></span>
				
					<textarea name="mensaje" tabindex="<?=$tabindex++;?>"><?=$dataToSkin["mensaje"]?></textarea>
					
					<div class="comentario-caract">
						<input type="text" value="<?=$max_length;?>" name="contador"> <span>/ Caracteres restantes. Máximo <?=$max_length;?> caracteres</span>				
					</div>
				</div><!--fin comentario-->
				
				<div class="captcha">
					
					<span><b>*</b> Código de seguridad:</span>
					<div class="verificacion">
				
						<img src="/extras/captcha/enviar.php?<?=time();?>" width="203" height="55"/>
			
					</div><!--fin verificacion-->
			
					<div class="codigo">

						<input type="text" maxlength="4" name="captcha" value="" size="10"/>
			
					</div><!--fin codigo-->		
				
				</div><!--fin captcha-->
				
				<div class="clear"></div>
				
				<div class="disclaimer2">
					<span class="border ico"></span>
					
					<div class="enviar-copia">
						<input type="checkbox" tabindex="<?=$tabindex++;?>" name="copia" value="1"/>
						<span class="txt">Enviarme una copia</span>
					</div>

					<div class="bt-enviar">
						<input type="submit" name="Enviar" value="" tabindex="<?=$tabindex++;?>" title="Enviar" />
					</div>
				
				</div> <!--fin disclaimer-->
				
			</form>
			
		</div>
	
	</div><!--fin inner-->
	
</div><!--fin enviar-->


<script type="text/javascript">
$("#form1 textarea").keypress( function () {
	m = $("#form1 textarea")[0];
	if (m.value.length > <?=$max_length;?>) {
		alert("Ha superado los <?=$max_length;?> caracteres");
		m.value = m.value.substr(0,<?=$max_length;?>);
	}
	
	$("#form1 .comentario-caract input").val(<?=$max_length;?> - m.value.length);
});
</script>
	
<? 
/**
 * Procesa la validación del formulario
 */
processForm("form1", $checkForm);
?>