<?php
/**
 * Muestra una pantalla con el resultado de todas las votaciones efectuadas hata el momento en la encuesta
 */
foreach ($dataToSkin as $k => $v) { ?>

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
				<span>Encuesta</span>
				
				<a class="close" href="#" onclick="self.close();" title="Cerrar">[ x ]</a>
				
			</div><!--fin title-->
			
			<div class="clear"></div>
		
			<div class="modalidad">
				
				<?php echo  $v['encuesta_titulo']?>
				
			</div><!--fin modalidad-->

			<div class="box-resultados">
				
				<table>
				<tr class="row1">
					<td class="col1">Resultados</td>
					<td class="col2">0%</td>
					<td class="col3">50%</td>
					<td class="col4">100%</td>
					<td class="col5">Porcentajes</td>
					<td class="col6">Votos</td>
				</tr>
				
				<tr class="fake"><td colspan="6"></td></tr>
				
				<?php foreach ($v['items'] as $j => $m) { ?>
				
					<tr class="row2">	
						<td><?php echo  $m['valor'];?></td>
						<td colspan="3"><div style='height:20px; width:<?php echo  $m['factor']*170;?>px; background:#F96914;'></div></td>
						<td class="center"><span><?php echo  $m['porcentaje'];?> %</span></td>
						<td class="last"><span><?php echo  $m['cantidad'];?></span></td>
					</tr>
				
				<?php } ?>
				
				<tr class="fake2"><td colspan="6"></td></tr>
				
				<tr class="cant">
					<td colspan="6">Cantidad de votos hasta el momento: <b><?php echo  $v['encuesta_total_votos'];?></b></td>
				</tr>
				</table>			

			</div><!--fin box resultados-->

		</div><!--fin inner-->

	</div><!--fin encuesta-->

<?php } ?>