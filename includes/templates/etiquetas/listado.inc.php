<?php
/**
 * Muestra un listado de artículos devueltos por una consulta de etiquetas
 * @changelog:
 */
?>
<section>
	
	<h2 id="title">Nube de Etiquetas</h2>

	<?php
	// Mensaje de Error
	if ($msjError) {
		include (dirTemplate.'/herramientas/mensaje-error.inc.php');
	}

	// Mensaje de Alerta
	if ($msjAlerta) {
		include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
	}

	// Solo si  hay artículos para mostrar
	if ($totalResultados) {

		include (dirTemplate.'/'.$pathRelative.'/objetos/nubes.inc.php');
		
		include (dirTemplate.'/herramientas/paginador-1.inc.php');
	}
	?>

</section> 