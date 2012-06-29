<?php
/**
 * Muestra un listado de artículos 
 */

// Mensaje de Error
if ($msjError) {
	include (dirTemplate.'/herramientas/mensaje-error.inc.php');
}

// Mensaje de Alerta
if ($msjAlerta) {
	include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
}

// Solo si  hay artículos en la sección
if ($totalResultados) {

	include (dirTemplate.'/'.$pathRelative.'/objetos/listado.inc.php');
	
	include (dirTemplate.'/herramientas/paginador-1.inc.php');
}
?>