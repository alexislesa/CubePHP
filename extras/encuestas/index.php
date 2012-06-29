<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'encuestas';
$incBody = dirTemplate.'/'.$pathRelative.'/listado.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'encuestas';
$pagActualClass[] = 'encuestas-listado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Encuestas'] = '/extras/encuestas';

$gd = New Encuestas();
$gd->db = $db;
$gd->itemEstado = 3;	// Activas y finalizadas
$gd->cantidad = $pagCantidad;
$gd->desplazamiento = $pagDesplazamiento;
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

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>