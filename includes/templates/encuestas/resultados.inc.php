<?php
/**
 * Muestra el resultado de la encuesta en una página
 */
?>

<div id="container">

	<div id="content">
		
		<div class="main">

			<h2 class="nota-title">Resultado de la encuesta</h2>

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
			
		</div><!--fin main-->

		<div class="sidebar barra-interior">
			
			<?php include (dirTemplate."/{$pathRelative}/barra.inc.php"); ?>
		
		</div><!--fin sidebar-->

		<div class="clear"></div>

	</div><!--fin content-->	

</div><!--fin conatiner-->