<?php
/**
 * Genera la pantalla con el interior de un art�culo institucional
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

// Solo si  hay art�culos en la secci�n
if ($totalResultados) {

	$v = $dataToSkin[0];

	include (dirTemplate."/{$pathRelative}/objetos/interior.inc.php");
}
?>