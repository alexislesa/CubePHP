<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'noticia';
$incBody = dirTemplate.'/'.$pathRelative.'/listado.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'noticias';
$pagActualClass[] = 'nota-listado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Noticias'] = '/noticias';

// Información para recuperar artículos
$itemTipo = 1;
$itemSeccion = 0;

$gd = New Notas();
$gd->db = $db;
$gd->itemTipo = $itemTipo;
$gd->itemSeccion = $itemSeccion;
$gd->cantidad = $pagCantidad;
$gd->desplazamiento = $pagDesplazamiento;
$gd->cache = $cache;

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

/* CONTENIDO DEMO */
// include(dirPath.'/includes/cache/test-listado.inc.php');

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>