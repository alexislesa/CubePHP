<div class="posiciones">

	<?php
	/* Levanto la info de posiciones */
	$contenido = file_get_contents($path_root."/includes/cache/df-primeraa-posiciones.inc.php");
	$data_posiciones = unserialize($contenido);
	?>

	<h5>Tabla de posiciones</h5>
	
	<table>
		<tr>
			<td class="equipos">Equipos</td>
			<td class="partidos">Pts</td>
			<td class="jugados">J</td>
			<td class="ganados">G</td>
			<td class="empatados">E</td>
			<td class="puntos nbr">P</td>
		</tr>
		
		<?php
		/* Levanto el Array y lo modifico respecto a mi forma de mostrarlo */
		if (!empty($data_posiciones["posiciones"]["equipo"])) {
			foreach ($data_posiciones["posiciones"]["equipo"] as $eqarr => $eq) { 
				$eq["nombre"]["value"] = utf8_decode($eq["nombre"]["value"]);
				
				foreach ($eq as $eq1 => $eq2) {
					$eq[$eq1]["value"] = !empty($eq[$eq1]["value"]) ? $eq[$eq1]["value"] : 0;
				}
				?>
			
				<tr>
					<td class="txt txt-eq"><img src="/images/equipos/home/<?php echo $eq["attr"]["id"];?>.gif" /><span><?php echo $eq["nombre"]["value"];?></span></td>
					<td class="txt txt-pts"><?php echo $eq["puntos"]["value"];?></td>
					<td class="txt"><?php echo $eq["jugados"]["value"];?></td>
					<td class="txt"><?php echo $eq["ganados"]["value"];?></td>
					<td class="txt"><?php echo $eq["empatados"]["value"];?></td>
					<td class="txt nbr"><?php echo $eq["perdidos"]["value"];?></td>
				</tr>

			<?php } 
		} ?>

	</table>

	<span class="data"><!--<a href="http://www.datafactory.com.ar" rel="nofollow" target="_blank" title="DATAFACTORY">DATAFACTORY</a><span>provisto por</span>--></span>

	<ul>
		<li><a href="/secciones/deportes/nacionala.php#fixture.htm" title="Fixture">fixture</a></li>
		<li><a href="/secciones/deportes/nacionala.php#descenso.htm" title="Descenso">descenso</a></li>
		<li class="last"><a href="/secciones/deportes/nacionala.php#goleadores.htm" title="Goleadores">goleadores</a></li>
	</ul>

</div><!--fin posiciones-->