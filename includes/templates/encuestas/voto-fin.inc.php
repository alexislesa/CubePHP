<?php
/**
 * Muestra la pantalla de que su voto se ingresó con éxito
 * @changelog:
 */
$v = $dataToSkin[0];
?>
<div class="encuesta">

	<div class="inner-encuesta">

		<div>
		
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
				<span>Encuesta</span>
				
				<a href="#" class="close" onclick="closepopup();" title="Cerrar">[ x ]</a>
				
			</div><!--fin title-->

			<div class="clear"></div>
			
			<div class="modalidad">
			
				<b>Su voto ha sido registrado con éxito.</b>
				
				<?php echo  $v['encuesta_titulo']?>
				
			</div><!--finmodalidad-->
	
		</div>

		<div class="clear"></div>

		<a class="vr" href="javascript:ventana('/extras/encuestas/resultados.php?id=<?php echo  $v['encuesta_id'];?>','',648, 439, 'yes'); self.close();" title="Ver resultados">Ver resultados</a>
		
		<div class="disclaimer">
			Cantidad de votos hasta el momento: <b><?php echo  $v['encuesta_total_votos'];?></b>
		</div>

	</div><!--fin inner encuesta-->
	
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