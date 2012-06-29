<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'encuestas';
$incBody = dirTemplate.'/encuestas/resultados-pop.inc.php';

// Web titulo
$webTitulo = 'Resultados de la encuesta - '.$webTitulo;
$webDescripcion = '';

$itemId = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;

$gd = New Encuestas();
$gd->db = $db;
$gd->itemEstado = 3;	// Activas y finalizadas
$gd->itemId = $itemId;
$gd->cantidad = 1;
$dataToSkin = $gd->process();

$totalResultados = $gd->totalResultados;
$totalPaginas = $gd->totalPaginas;

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	if ($totalResultados) {

	} else {

		$msjAlerta = 'No se encontraron encuestas activas para mostrar';
	}
}

include (dirPath.'/includes/comun/pop-header.inc.php');

include (dirTemplate.'/herramientas/base-pop.inc.php');

include (dirPath.'/includes/comun/pop-bottom.inc.php');
?>