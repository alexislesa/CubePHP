<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'videos';
$incBody = dirTemplate.'/'.$pathRelative.'/listado.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'videos';
$pagActualClass[] = 'videos-listado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Videos'] = '/videos';

// Información para recuperar artículos
$itemTipo = 'video, ytube';

$gd = New Adjuntos();
$gd->db = $db;
$gd->itemTipo = $itemTipo;
$gd->cantidad = $pagCantidad;
$gd->desplazamiento = $pagDesplazamiento;
// $gd->cache = $cache;

	$ogd = New Adjuntos();
	$ogd->db = $db;
	$ogd->datos = $adjuntos_files;
	$gd->loadObj('adjunto',$ogd);

	$ogd = New Notas();
	$ogd->db = $db;
	$gd->loadObj('nota',$ogd);

	$ogd = New Comentarios();
	$ogd->db = $db;
	$gd->loadObj('comentario',$ogd);

$dataToSkin = $gd->process();

// echo $gd->log;

$totalResultados = $gd->totalResultados;
$totalPaginas = $gd->totalPaginas;

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	if ($totalResultados) {

	} else {

		$msjAlerta = 'No se encontraron artículos para mostrar';
	}
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>