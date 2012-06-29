<?php
/**
 * Bloque que se debería mostar cuando no existe resultados para la búsqueda planteada
 */
if (!$totalResultados) { ?>

	<section class="bsq-error">

		<p>
			<strong>No se encontraron coincidencia/s con la palabra/s o frase:</strong>
			<span><?php echo $qText;?></span> en los artículos 
			desde el: <span><?php echo $ddia;?>/<?php echo $dmes;?>/<?php echo $danio;?></span> 
			hasta el: <span><?php echo $hdia;?>/<?php echo $hmes;?>/<?php echo $hanio;?></span><br />

			Revisá la ortografía de la/s palabra/s de búsqueda que utilizaste. 
			Si la ortografía es correcta y escribiste una 
			sola palabra, comenzá una nueva búsqueda con una o más palabras similares. <br/>
			Prueba modificar el rango de fechas de la búsqueda.
		</p>

	</section>
	
<?php } ?>