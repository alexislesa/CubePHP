<?php
/**
 * Bloque que se deber�a mostar cuando no existe al menos un resultados para la b�squeda planteada
 * Bloque de resultados de texto simple
 */
if ($totalResultados) { ?>

	<section class="resultadosbox">

		<strong>(<?php echo $totalResultados;?>)</strong> 
		coincidencia/s con la palabra/s o frase: <strong><?php echo $qText;?></strong><br/>
		Resultado/s desde el: <span><?php echo $ddia;?>/<?php echo $dmes;?>/<?php echo $danio;?></span> 
		hasta el: <span><?php echo $hdia;?>/<?php echo $hmes;?>/<?php echo $hanio;?></span><br />
		Dividido/s en: <span><?php echo $totalPaginas;?></span> p�gina/s

	</section>

<?php } ?>