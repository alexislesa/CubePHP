<?php
/**
 * Muestra la pantalla de proceso de votaci�n en una encuesta
 */
$encuestaTitulo = !empty($dataToSkin[0]['encuesta_titulo']) 
				? $dataToSkin[0]['encuesta_titulo'] 
				: '';
				
$encuestaOpcion = !empty($dataToSkin[0]['items'][$data['item']]['valor']) 
				? $dataToSkin[0]['items'][$data['item']]['valor']
				: '';
?>
<div class="encuesta">

	<div class="inner-encuesta">
	
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

		<div class="title">
		
			<span class="ico"></span>
			<span>Encuesta:</span> <?php echo $encuestaTitulo;?>
			
			<a href="#" class="close" onclick="closepopup();" title="Cerrar">[ x ]</a>
		
		</div>
	
		<div class="clear"></div>

		<div>
			Opci�n seleccionada: <?php echo $encuestaOpcion;?>
		</div>
		
		<div class="modalidad">
			
			<b>Modalidad de voto:</b> Para participar ingrese el c�digo y oprima el bot�n "votar".  
			
		</div><!--fin modalidad-->
		
		<form method="post" action='?act=true' name="mandax"> 
			<input type="hidden" name="item" value="<?php echo  $data['item'];?>" />
			<input type="hidden" name="encuesta" value="<?php echo  $data['encuesta'];?>" />

			<div class="verificacion">
			
				<span><b>*</b> Verificaci�n</span>

				<div class="img"><img src="/extras/captcha/votar.php?<?php echo time();?>" /></div>

			</div><!--fin verificacion-->

			<div class="codigo">

				<span>Ingresar c�digo:</span>

				<input type="text" maxlength="4" name="captcha" value="" size="10"/>

			</div><!--fin codigo-->		

			<input type="submit" name="votar" class="pop-votar" value="Votar" title="Votar"/>
		</form>
		
		<div class="disclaimer">
			(Se aceptar�n may�sculas y/o min�sculas) 
		</div>
		
	</div><!--fin inner-->
	
</div><!--fin encuesta-->

<script type="text/javascript">
/* Revisa si estoy en popup o ligthbox y cierro la ventana */ 
function closepopup() {
	if (top.location.href == self.location.href) {
		self.close();
	} else {
		window.top.tb_remove();
	}
};
</script>