<?php
/**
 * Muestra el bloque de resultados facetados por sección
 */
if ($totalResultados) { 

	if (!empty($dataToFacetas['noticia_seccion_id'])) { 
		
		?>

		<section class="facetado-seccion">
		
			<b class="title">Filtros por sección:</b>

			<?php if (!empty($_GET['sec'])) { 
				// Limpio del href la opción de la sección actual
				$href1 = str_replace('&sec='.$_GET['sec'], '', $oHrefBase);
				$sec_fac = $secToFacetados[$_GET['sec']]['seccion_nombre'];
				?>

				<a class="filter" href="<?php echo $href1;?>" title="<?php echo $sec_fac;?>"> <?php echo $sec_fac;?> <b>[ x ]</b></a>
				
				<div class="facetado-clear"></div>
				
			<?php } else { ?>

				<div class="facetado-box">
				
					<ul>

						<?php
						/* Listado de las secciones encontradas */
						foreach ($secToFacetados as $kSec => $vSec) {
						
							if (!empty($dataToFacetas['noticia_seccion_id'][$kSec])) { ?>
							
								<li>
									<a href="<?php echo $oHrefBase;?>&sec=<?php echo $kSec;?>" title="<?php echo $vSec['seccion_nombre'];?>">
									<b><?php echo $vSec['seccion_nombre'];?></b> <span>(<?php echo count($dataToFacetas['noticia_seccion_id'][$kSec]);?>)</span>
									</a>
								</li>
							
							<?php } else { ?>
							
								<li>
									<b><?php echo $vSec['seccion_nombre'];?></b> <span>(0)</span>
								</li>
							
							<?php }
						} ?>
						
					</ul>
					
				</div>
				
			<?php } ?>

		</section>

	<?php } ?>

<?php } ?>