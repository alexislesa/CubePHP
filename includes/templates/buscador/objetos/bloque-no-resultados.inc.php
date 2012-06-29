<?php
/**
 * Bloque que se deber�a mostar cuando no existe resultados para la b�squeda planteada
 */
if (!$totalResultados) { ?>

	<section class="bsq-error">

		<p>
			<strong>No se encontraron coincidencia/s con la palabra/s o frase:</strong>
			<span><?php echo $qText;?></span> en los art�culos 
			desde el: <span><?php echo $ddia;?>/<?php echo $dmes;?>/<?php echo $danio;?></span> 
			hasta el: <span><?php echo $hdia;?>/<?php echo $hmes;?>/<?php echo $hanio;?></span><br />

			Revis� la ortograf�a de la/s palabra/s de b�squeda que utilizaste. 
			Si la ortograf�a es correcta y escribiste una 
			sola palabra, comenz� una nueva b�squeda con una o m�s palabras similares. <br/>
			Prueba modificar el rango de fechas de la b�squeda.
		</p>

	</section>
	
<?php } ?>