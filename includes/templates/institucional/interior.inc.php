<?php
/**
 * Genera la pantalla con el interior de un artículo institucional
 *
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

	$v = $dataToSkin[0];

	include (dirTemplate."/{$pathRelative}/objetos/interior.inc.php");
}
?>