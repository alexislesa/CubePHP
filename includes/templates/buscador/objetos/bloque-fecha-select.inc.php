<?php
/**
 * Filtra la búsqueda por un rango de fecha inicial y uno final
 */
?>

<section class="rango">

	<h5>Filtros por fecha</h5>

	<div class="rango-fecha">
		
		<div class="select">
			
			<span class="ico"></span>
			
			<b>Desde:</b>
			
			<select class="d2" name='ddia' >
				<?php optionRange(1,31,$ddia);?>
			</select>
			
			<select class="m2" name='dmes' >
				<?php mesRange(1,12,$dmes);?>
			</select>

			<select class="a2" name='danio' >
				<?php optionRange(2005,date("Y"),$danio);?>
			</select>
			
		</div><!--fin seelct-->
		
		<div class="select">
			
			<span class="ico"></span>
			
			<b>Hasta:</b>
			
			<select name='hdia' class="d2">
				<?php optionRange(1,31,$hdia);?>
			</select>
			
			<select name='hmes' class="m2">
				<?php mesRange(1,12,$hmes);?>
			</select>

			<select name='hanio' class="a2">
				<?php optionRange(2010,date("Y"),$hanio);?>
			</select>
			
		</div><!--fin select-->
	
	</div><!--fin rango fecha-->
	
</section><!--fin rango-->