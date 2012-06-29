<?php
/**
 * Muestra un bloque de como ordenar los resultados de la búsqueda
 */
$q_orden = isset($q_orden) ? $q_orden : 'Asc';
?>

<section id="sortby" class="sort-by">
	
	<h5>Resultados ordenados por fecha:</h5>

	<div class="desc">
		<span class="radio">
			<input type="radio" name="ord" value="0" <?php echo ($q_orden != "Asc") ? "checked" : "";?> />
		</span>
		<label>Descendente</label>
	</div><!--fin desc-->
	
	<div class="asc">
		<span class="radio">
			<input type="radio" name="ord" value="1" <?php echo ($q_orden == "Asc") ? "checked" : "";?> />
		</span>
		<label>Ascendente</label>
	</div><!--fin asc-->

	<div class="clear"></div>

</section><!--fin sort by-->