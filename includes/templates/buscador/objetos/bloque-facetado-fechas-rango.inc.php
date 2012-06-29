<?php
/**
 * Muestra el bloque de resultados facetados por rango de fechas (hoy, ayer, mes anterior, etc)
 */
if ($totalResultados) { ?>
	
	<section class="facetado-rango-fecha">

		<?php 
		/** 
		 * Facetados por rango de fecha
		 * Permite los siguientes rangos:
		 *	- Hoy, Ayer, Esta semana, Este mes, Mes anterior, Este Trimestre, Este año, años anteriores
		 */
		if (!empty($dataToFacetas['rango'])) { ?>

			<?php 
			/**
			 * Muestro listado de las opciones ya seleccionadas
			 */
			if (!empty($_GET['rango'])) {
				$href1 = str_replace('&rango='.$_GET['rango'], '', $oHrefBase);
				?>
				
				<div class="filtro-fecha-active">
			
					<b class="title">Filtros por fecha</b>

					<?php 
					/* Rango de fecha: HOY */
					if ($_GET['rango'] == 'hoy') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Hoy <b>[ x ]</b></a>
						
					<?php } ?>

					<?php
					/* Rango de fecha: AYER */
					if ($_GET['rango'] == 'ayer') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Ayer <b>[ x ]</b></a>
						
					<?php } ?>
					
					<?php
					/* Rango de fecha: ESTA SEMANA */
					if ($_GET['rango'] == 'semana') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Esta semana <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango de fecha: ULTIMOS 30 DÍAS */
					if ($_GET['rango'] == 'ultimos_30dias') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Últimos 30 días <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango de fecha: ESTE MES */
					if ($_GET['rango'] == 'este_mes') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Este mes <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango de fecha: MES PASADO */
					if ($_GET['rango'] == 'mes_pasado') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Mes pasado <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango de fecha: ESTE TRIMESTRE */
					if ($_GET['rango'] == 'trimestre') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Este trimestre <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango de fecha: ESTE AÑO */
					if ($_GET['rango'] == 'este_anio') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Este año <b>[ x ]</b></a>
					
					<?php } ?>
					
					<?php
					/* Rango: AÑOS ANTERIORES */
					if ($_GET['rango'] == 'anios_anteriores') { ?>
						
						<a class="filter" href="<?php echo $href1;?>">Años anteriores <b>[ x ]</b></a>
					
					<?php }	?>
				
				</div><!--fin filtro fecha active-->
			
			<?php } else { ?>

				<div class="filtro-fecha-active">
			
					<b class="title">Filtros por fecha</b>  

					<div class="filtros-fecha">

						<?php 
						/* Filtro por fecha: HOY */
						if (!empty($dataToFacetas['rango']['hoy'])) { 
							$totalFac = count($dataToFacetas['rango']['hoy']); ?>
							
							<a class="first" href="<?php echo $oHrefBase;?>&rango=hoy">
								<span><span>Hoy</span> <b>(<?php echo $totalFac;?>)</b></span>
							</a>
							
						<?php } else { ?>
						
							<a class="first disabled"><span>Hoy (0)</span></a>
						
						<?php } ?>


						<?php 
						/* Filtro por fecha: AYER */
						if (!empty($dataToFacetas['rango']['ayer'])) { 
							$totalFac = count($dataToFacetas['rango']['ayer']); ?>
						
							<a href="<?php echo $oHrefBase;?>&rango=ayer">
								<span><span>Ayer</span> <b>(<?php echo $totalFac;?>)</b></span>
							</a>
						
						<?php } else { ?>
					
							<a class="disabled"><span>Ayer (0)</span></a>
					
						<?php } ?>
						

						<?php 
						/* Filtro por fecha: ESTA SEMANA */
						if (!empty($dataToFacetas['rango']['semana'])) { 
							$totalFac = count($dataToFacetas['rango']['semana']); ?>
							
							<a href="<?php echo $oHrefBase;?>&rango=semana">
								<span><span>Esta semana</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>
							
						<?php } else { ?>
						
							<a class="disabled"><span>Esta semana (0)</span></a>
						
						<?php } ?>
						
					
						<?php 
						/* Filtro por fecha: ÚLTIMOS 30 DÍAS */
						if (!empty($dataToFacetas['rango']['ultimos_30dias'])) { 
							$totalFac = count($dataToFacetas['rango']['ultimos_30dias']); ?>
							
							<a href="<?php echo $oHrefBase;?>&rango=ultimos_30dias">
								<span><span>Últimos 30 días</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>
							
						<?php } else { ?>
						
							<a class="disabled"><span>Últimos 30 días (0)</span></a>
						
						<?php } ?>
						
					
						<?php 
						/* Filtro por fecha: ESTE MES */
						if (!empty($dataToFacetas['rango']['este_mes'])) { 
							$totalFac = count($dataToFacetas['rango']['este_mes']); ?>
							
							<a href="<?php echo $oHrefBase;?>&rango=este_mes">
								<span><span>Este mes</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>
							
						<?php } else { ?>
						
							<a class="disabled"><span>Este mes (0)</span></a>
						
						<?php } ?>

						
						<?php 
						/* Filtro por fecha: MES PASADO */
						if (!empty($dataToFacetas['rango']['mes_pasado'])) {
							$totalFac = count($dataToFacetas['rango']['mes_pasado']); ?>
							
							<a href="<?php echo $oHrefBase;?>&rango=mes_pasado">
								<span><span>Mes pasado</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>
							
						<?php } else { ?>
						
							<a class="disabled"><span>Mes pasado (0)</span></a>
						
						<?php } ?>
						
					
						<?php
						/* Filtro por fecha: ESTE TRIMESTRE */
						if (!empty($dataToFacetas['rango']['trimestre'])) { 
							$totalFac = count($dataToFacetas['rango']['trimestre']); ?>
							
							<a href="<?php echo $oHrefBase;?>&rango=trimestre">
								<span><span>Este trimestre</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>
							
						<?php } else { ?>
						
							<a class="disabled"><span>Este trimestre (0)</span></a>
						
						<?php } ?>


						<?php 
						/* Filtro por fecha: ESTE AÑO */
						if (!empty($dataToFacetas['rango']['este_anio'])) { 
							$totalFac = count($dataToFacetas['rango']['este_anio']); ?>

							<a href="<?php echo $oHrefBase;?>&rango=este_anio">
								<span><span>Este año</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>

						<?php } else { ?>

							<a class="disabled"><span>Este año (0)</span></a>

						<?php } ?>


						<?php 
						/* Filtro por fecha: AÑOS ANTERIORES */
						if (!empty($dataToFacetas['rango']['anios_anteriores'])) { 
							$totalFac = count($dataToFacetas['rango']['anios_anteriores']); ?>

							<a href="<?php echo $oHrefBase;?>&rango=anios_anteriores">
								<span><span>Años anteriores</span> <b>(<?php echo $totalFac;?>)</b> </span>
							</a>

						<?php } else { ?>

							<a class="disabled"><span>Años anteriores (0)</span></a>

						<?php } ?>
				
					</div><!--fin filtros fecha-->

				</div>

			<?php } ?>

		<?php } ?>

	</section><!--fin facetado-->

<?php } ?>