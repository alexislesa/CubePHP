<?php
/**
 * Muestra la pantalla de proceso de votación en una encuesta
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
	
		<div class="title">
		
			<span class="ico"></span>
			<span>Encuesta:</span> <?php echo $encuestaTitulo;?>
			
			<a href="#" class="close" onclick="closepopup();" title="Cerrar">[ x ]</a>

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
			
		</div>
	
		<div class="clear"></div>

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