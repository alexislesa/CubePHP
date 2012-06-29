<?php
/**
 * Muestra un listado de artículos devueltos por una consulta desde el archivo
 */
?>
<section>

	<h2 id="title">Resultados de la busqueda</h2>

	<?php 
	include (dirTemplate."/{$pathRelative}/objetos/bloque-archivo.inc.php");
	
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

		include (dirTemplate."/{$pathRelative}/objetos/listado.inc.php");
		
		include (dirTemplate."/herramientas/paginador-1.inc.php");
	}
	?>

</section>