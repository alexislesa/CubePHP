<?php
/**
 * Genera un bloque Tabla de de posiciones. 
 * Permite reutilizarlo para Nacional A o Nacional B
 *
 */
$torneo = "primeraa";
// $torneo = "nacionalb";
?>

<div id="deportivas" class="interior">
	
	<div id="main">
	
		<div class="wrapper">

			<h2 class="nota-title">Torneo Primera A</h2>

			<ul id="sttab" class="tab5">
				<li><a id="tab1" href="#posiciones">Tabla de Posiciones</a></li>
				<li><a id="tab2" href="#fixture">Fixture</a></li>
				<li><a id="tab3" href="#descenso">Descenso</a></li>
				<li class="lastitem"><a id="tab4" href="#goleadores">Goleadores</a></li>
			</ul>
			
			<div id="stpanes" class="panes">

				<div class="main-posiciones">
					<?
					/* Levanto la info de posiciones */
					if ($contenido = file_get_contents($path_root."/includes/cache/df-{$torneo}-posiciones.inc.php")) {
						$data_posiciones = unserialize($contenido); ?>
						
						<table class="main-estadisticas">
						<tr>
							<td class="main-equipos">Equipos</td>
							<td class="main-pts">pts</td>
							<td class="main-jugados">pj</td>
							<td class="main-ganados">pg</td>
							<td class="main-empatados">pe</td>
							<td class="main-perdidos">pp</td>
							<td class="golesfavor">gf</td>
							<td class="golescontra">gc</td>
						</tr>
						
						<?php
						/* Levanto el Array y lo modifico respecto a mi forma de mostrarlo */
						foreach ($data_posiciones["posiciones"]["equipo"] as $eqarr => $eq) {
						
							$eq["nombre"]["value"] = utf8_decode($eq["nombre"]["value"]);
							
							foreach ($eq as $eq1 => $eq2) {
								$eq[$eq1]["value"] = !empty($eq[$eq1]["value"]) ? $eq[$eq1]["value"] : 0;
							} ?>
						
							<tr>
								<td class="txt txt-eq"><img src="/images/equipos/home/<?php echo $eq["attr"]["id"];?>.gif" />
									<span><?php echo $eq["nombre"]["value"];?></span></td>
								<td class="txt txt-pts"><?php echo $eq["puntos"]["value"];?></td>
								<td class="txt"><?php echo $eq["jugados"]["value"];?></td>
								<td class="txt"><?php echo $eq["ganados"]["value"];?></td>
								<td class="txt"><?php echo $eq["empatados"]["value"];?></td>
								<td class="txt"><?php echo $eq["perdidos"]["value"];?></td>
								<td class="txt"><?php echo $eq["golesfavor"]["value"];?></td>
								<td class="txt"><?php echo $eq["golescontra"]["value"];?></td>
								
							</tr>

						<? } ?>

						</table>
						
					<?php } ?>

				</div><!--fin posiciones-->


				<div class="main-fixture">

					<?php
					
					/* Levanto la info del fixture */
					if ($contenido = file_get_contents($path_root."/includes/cache/df-primeraa-fixture.inc.php")) {
						$data_fixture = unserialize($contenido); ?>

						<ul id="fixa" class="fx_tab">

							<?php foreach ($data_fixture["fixture"]["fecha"] as $fx_fechas => $fx_partidos) { 
								if ($fx_partidos["attr"]["nombrenivel"] == "Promocion") {
									$fx_partidos["attr"]["fn"] = "P".$fx_partidos["attr"]["fn"];
								} ?>
					
								<li><a href="#"><?php echo $fx_partidos["attr"]["fn"];?></a></li>
								
							<? } ?>
							
							<div class="clear"></div>
						</ul>

						<div class="fx_pane">
						
							<? foreach ($data_fixture["fixture"]["fecha"] as $fx_fechas => $fx_partidos) { ?>

								<div>
								
									<table class="fixture-t">
										<tr>
										<td class="fixture-l"><span class="fix-local">Local</span></td>
										<td class="fixture-fake"></td>
										
										<td class="fixture-fake nb"></td>
										<td class="fixture-l"><span class="fix-vis">Visitante</span></td>
										
										<td class="fixture-e">Estado</td>
										<? /*<td class="fixture-est">Estadio</td> */?>
										<td  class="fixture-a">Arbitro</td>
										</tr>
										
										<? foreach ($fx_partidos["partido"] as $k => $v) { 
											$v["local"]["value"] = utf8_decode($v["local"]["value"]);
											$v["visitante"]["value"] = utf8_decode($v["visitante"]["value"]);
											$v["arbitro"]["attr"]["nc"] = !empty($v["arbitro"]["attr"]["nc"]) ? utf8_decode($v["arbitro"]["attr"]["nc"]) : "";
											$v["goleslocal"]["value"] = !empty($v["goleslocal"]["value"]) ? $v["goleslocal"]["value"] : 0;
											$v["golesvisitante"]["value"] = !empty($v["golesvisitante"]["value"]) ? $v["golesvisitante"]["value"] : 0;
											
											$v["attr"]["nombreEstadio"] = utf8_decode($v["attr"]["nombreEstadio"]);
											$v["attr"]["nombreEstadio"] = strlen($v["attr"]["nombreEstadio"]) > 14 ? substr($v["attr"]["nombreEstadio"],0,14)."..." : $v["attr"]["nombreEstadio"];
											
											if ($v["estado"]["attr"]["id"] == 0) {
												$fch = $v["attr"]["fecha"];

												// $fx_estado = "09.08.2010 | 21:00";
												$fx_estado = substr($fch,6,2).".".substr($fch,4,2).".".substr($fch,0,4);
												
												if ($v["attr"]["hora"] != "") {
													$fchh = list($fch_h, $fch_m, $fch_s) = explode(":", $v["attr"]["hora"]);
													$fx_estado.= " | ".$fch_h.":".$fch_m;
												}
											} else {
												$fx_estado = utf8_decode($v["estado"]["value"]);
											}
											?>
										
										<tr>
										<td class="txt txt-eq"><img src="/images/equipos/home/<?php echo $v["local"]["attr"]["id"];?>.gif" /><span><?php echo $v["local"]["value"];?></span></td>
										<td class="txt txt-pts"><?php echo $v["goleslocal"]["value"];?></td>
										
										<td class="txt txt-pts"><?php echo $v["golesvisitante"]["value"];?></td>
										<td class="txt txt-eq"><img class="right" src="/images/equipos/home/<?php echo $v["visitante"]["attr"]["id"];?>.gif" /><span><?php echo $v["visitante"]["value"];?></span></td>
										
										<td class="txt txt-eq"><?php echo $fx_estado;?></td>
										<? /*<td class="txt txt-eq"><span class="estadios"><?php echo $v["attr"]["nombreEstadio"];?></span></td>*/?>
										<td class="txt txt-eq"><?php echo $v["arbitro"]["attr"]["nc"];?></td>
										</tr>
										
										<? } ?>
										
									</table>
								
								</div>
						
							<? } ?>
						
						</div>
					
					
					} ?>
					
				</div><!--fin fiture-->

				
				<div>
					<?
					/* Levanto la info de Descenso */
					$contenido = file_get_contents($path_root."/includes/cache/df-primeraa-posiciones.inc.php");
					$data_descenso = unserialize($contenido);
					?>
					<table class="fixture-g">
					<tr>
						<td class="equipo-g">Equipo</td>
						<td class="main-jugados">08-09</td>
						<td class="main-jugados">09-10</td>
						<td class="main-jugados">10-11</td>
						<td class="main-jugados">total</td>
						<td class="main-jugados">pj</td>
						<td class="main-pts">prom.</td>
					</tr>
					
					<? 
					$fx_descenso = array();
					foreach ($data_descenso["posiciones"]["equipo"] as $eqarr => $eq) { 
						$eq["nombre"]["value"] = utf8_decode($eq["nombre"]["value"]);
						
						foreach ($eq as $eq1 => $eq2) {
							$eq[$eq1]["value"] = !empty($eq[$eq1]["value"]) ? $eq[$eq1]["value"] : 0;
						}
						
						$fx_descenso[] = array(
							"id" 		=> $eq["attr"]["id"],
							"nombre" 	=> $eq["nombre"]["value"],
							"anterior2"	=> $eq["puntosanterior2"]["value"],
							"anterior1"	=> $eq["puntosanterior1"]["value"],
							"actual"	=> $eq["puntosactual"]["value"],
							"jugados"	=> $eq["jugados"]["value"],
							"promedio"	=> $eq["promediodescenso"]["value"]
						);
					}
					
					/**
					 * Realizo mi propio sistema de ordenamiento
					 * Función de comparación binaria del array
					 */
					function cmp($a, $b) {
						// Ordena de mayor a menor
						return strcmp($b["promedio"], $a["promedio"]);
					}

					// Ordeno el array según la función de más arriba.
					usort($fx_descenso, "cmp");
					foreach ($fx_descenso as $k => $v) {
						?>

						<tr>
							<td class="txt txt-eq">
								<img src="/images/equipos/home/<?php echo $v["id"];?>.gif" /> 
								<span><?php echo $v["nombre"];?></span>
							</td>
							<td class="txt"><?php echo $v["anterior2"];?></td>
							<td class="txt"><?php echo $v["anterior1"];?></td>
							<td class="txt"><?php echo $v["actual"];?></td>
							<td class="txt"><?php echo $v["actual"];?></td>
							<td class="txt"><?php echo $v["jugados"];?></td>
							<td class="txt txt-pts"><?php echo $v["promedio"];?></td>
						</tr>
					
					<? } ?>
					
					</table>
					
				</div><!--fin descenso-->

				<div class="tabla-goleadores">
					
					<?
					/* Levanto la info de goleadores */
					$contenido = file_get_contents($path_root."/includes/cache/df-primeraa-goleadores.inc.php");
					$data_goleadores = unserialize($contenido);
					?>
				
					<table class="fixture-g">
					<tr>
						<td class="nro-g partidos">nro</td>
						<td class="fixture-l txt-eq">Jugador</td>
						<td class="equipo-g">Equipo</td>
						<td class="main-jugados">Jugada</td>
						<td class="main-jugados">Cabeza</td>
						<td class="main-jugados">T. Libre</td>
						<td class="main-jugados">Penal</td>
						<td class="main-pts">Total</td>
					</tr>
					
					<? 
					$nro = 0;
					if (!empty($data_goleadores["goleadores"]["persona"])) {
						foreach ($data_goleadores["goleadores"]["persona"] as $gol_pers => $gol_per) {
							$nro++;
							
							if ($nro <= 20) { 
								$gol_per["nombreCompleto"]["value"] = utf8_decode($gol_per["nombreCompleto"]["value"]);
								$gol_per["equipo"]["attr"]["nombreasociacion"] = utf8_decode($gol_per["equipo"]["attr"]["nombreasociacion"]);
								?>

								<tr>
									<td class="txt"><?php echo $nro;?></td>
									<td class="txt txt-eq"><?php echo $gol_per["nombreCompleto"]["value"];?></td>
									<td class="txt txt-eq"><img src="/images/equipos/home/<?php echo $gol_per["equipo"]["attr"]["id"];?>.gif" /> <span><?php echo $gol_per["equipo"]["attr"]["nombreasociacion"];?></span></td>
									<td class="txt"><?php echo $gol_per["jugada"]["value"];?></td>
									<td class="txt"><?php echo $gol_per["cabeza"]["value"];?></td>
									<td class="txt"><?php echo $gol_per["tirolibre"]["value"];?></td>
									<td class="txt"><?php echo $gol_per["penal"]["value"];?></td>
									<td class="txt txt-pts"><?php echo $gol_per["goles"]["value"];?></td>
								</tr>
							
							<? 
							}
						}
					} ?>
					
					</table>
					
				</div><!--fin goleadores-->					
				
			</div><!--fin panes-->
	
			<span class="data"><a title="DATAFACTORY" target="_blank" href="http://www.datafactory.com.ar">DATAFACTORY</a><span>provisto por</span></span>

		</div><!--fin inner-->

		<div class="col-3">
		
			<!-- Barra -->

		</div>
		
		<div class="clear"></div>
		
	</div><!--fin contenido-->

</div><!--fin main-->

<script type="text/javascript">
$(function() {
	$("#sttab").tabs("#stpanes > div", { history: true });
	$("ul.fx_tab").tabs("div.fx_pane > div");
});
</script>