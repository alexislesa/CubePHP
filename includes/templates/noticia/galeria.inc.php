<?php
/**
 *
 * Genera la pantalla con informaci�n para mostrar la galer�a de imagenes de un art�culo
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

	include (dirTemplate.'/'.$pathRelative.'/objetos/galeria-popup.inc.php');
}
?>