<?php
/**
 * Muestra el bloque de resultados facetados por períodos de fechas (mes-año)
 */
if ($totalResultados) { ?>
	
	<section class="facetado-rango-fecha">

		<?php 
		/**
		 * Realiza el facetado por fechas al estilo: Enero, Febrero, etc
		 */
		if (!empty($_GET['fecha-mes'])) { 
			/* Limpio del href la opción de la sección actual */
			$href1 = str_replace('&fecha-mes='.$_GET['fecha-mes'], '', $oHrefBase);
			$mesk1 = explode('-',$_GET['fecha-mes']); ?>
		
			<a href="<?php echo $href1;?>" title="<?php echo $mes_txt[$mesk1[1]];?> <?php echo $mesk1[0];?>"> <?php echo $mes_txt[$mesk1[1]];?> <?php echo $mesk1[0];?> [X]</a>

		<?php } else { ?>

			<?php 
			/* Listado de los meses encontrados */
			if (!empty($dataToFacetas['mes'])) {
				foreach ($dataToFacetas['mes'] as $k1=>$v1) { 
					$mesk1 = explode('-',$k1); ?>
			
					<a href="<?php echo $oHrefBase;?>&fecha-mes=<?php echo $k1;?>" title="<?php echo $mes_txt[$mesk1[1]];?> <?php echo $mesk1[0];?>"><?php echo $mes_txt[$mesk1[1]];?> <?php echo $mesk1[0];?> (<?php echo count($v1);?>)</a>
				
				<?php }
			} ?>

		<?php } ?>

	</section><!--fin facetado-->

<?php } ?>