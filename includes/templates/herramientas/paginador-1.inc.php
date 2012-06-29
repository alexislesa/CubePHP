<?php
/**
 * Genera el paginador del listado
 */
$pag = new Paginador();
$pag->actual = $pagActual;
$pag->cantidad = $pagCantidad;
$pag->metodo = 'get';
$pag->paginador = $pagPaginador;
$pag->resultados = $totalResultados;
$pag->total = $totalPaginas;
$pag->variable ='p';

if ($pg = $pag->process()) {

	if ($pg['totales'] > 1)  { ?>

		<div class="main-paginador">

			<div id="paginador">

				<div class="inner-paginador">

					<span class="a-anterior">

						<?php if ($pg['anteriorView']) { ?>

							<a href="<?php echo $pg['anteriorUrl'];?>" rel="prev" title="Ir a página anterior" id="anterior">Anterior</a> 

						<?php } ?>

					</span>

					<?php for ($i=$pg['paginadorInicio']; $i<$pg['paginadorFin']; $i++) { 
						$j = $i+1;
						$pagActiveClass = ($i == $pg['actualId']) ? 'active' : '';
						?>

						<a href="<?php echo $pg['url']?>&p=<?php echo $i;?>" title="Ir a página <?php echo $j;?>" class="number <?php echo $pagActiveClass;?>" ><?php echo  $j;?></a>

					<?php } ?>

					<span class="a-siguiente">

						<?php if ($pg['siguienteView']) { ?>
						
							<a href="<?php echo $pg['siguienteUrl'];?>" rel="next" title="Ir a página siguiente" id="siguiente">Siguiente</a>
						
						<?php } ?>

					</span>

				</div><!--fin paginador-->

			</div><!--fin pag archivo-->

		</div><!--fin main paginador-->	

	<?php }
} ?>