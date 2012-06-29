<?php
/**
 * Bloque que se debería mostar cuando no existe al menos un resultados para la búsqueda planteada
 * Bloque con texto de búsqueda avanzada
 */
if ($totalResultados) { ?>

	<section class="resultadosbox">

		<p><strong>(<?php echo $totalResultados;?>)</strong> 
		coincidencia/s encontradas con la palabra/s o frase: <strong><?php echo $qText;?></strong><br/>
		Resultado/s obtenidos entre el: <span><?php echo date("d/m/Y", $fecha_inicial);?></span>,
		al: <span><?php echo date("d/m/Y", $fecha_final);?></span><br/>
		Divididos en: <span><?php echo $totalPaginas;?>  páginas</span>
		</p>

	</section>

<?php } ?>