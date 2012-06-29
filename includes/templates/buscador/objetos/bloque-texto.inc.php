<?php
/**
 * Muestra un bloque de búsqueda de texto con las siguientes opciones de formato de texto:
 * - Frase exacta
 * - Todas las palabras
 * - Algunas de las palabras
 */
?>

<div class="main-search">

	<div class="new-search">

		<h5>Nueva búsqueda:</h5>

		<input class="text" type="text" name="q" maxlength="35" title="Ingrese palabra o frase a buscar..." value="<?php echo $qText;?>" />

		<div class="bt2">
			<input type="submit" name="enviar" value="Buscar" title="Buscar"/>
		</div>	

	</div><!--end new search-->

</div><!--fin main search-->

<div class="sort-by">

	<h5>Buscar por:</h5>

	<span class="radio">
		<input type="radio" name="qtipo" value="all" id="qtipoall" <?php echo ($qTipo == 'all') ? 'checked' : '';?>/>	
	</span>
	<label for="qtipoall">Frase exacta</label>

	<span class="radio">
		<input type="radio" name="qtipo" value="and" id="qtipoand" <?php echo ($qTipo == 'and') ? 'checked' : '';?>/>
	</span>			
	<label for="qtipoand">Todas las palabras</label>
	
	<span class="radio">
		<input type="radio" name="qtipo" value="or" id="qtipoor" <?php echo ($qTipo == 'or') ? 'checked' : '';?>/>
	</span>		
	<label for="qtipoor">Algunas palabras</label>
	
	<div class="clear"></div>
	
</div><!--fin sort by-->