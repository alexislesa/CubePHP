<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'buscador';
$incBody = dirTemplate.'/'.$pathRelative.'/archivo.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'buscador';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Archivo'] = '';

/**
 * Si se limita la búsqueda a un rango de fecha:
 $oInicial = time() - (60*60*24*30);
 $oFinal = time();
 */

// Información para recuperar artículos
$itemTipo = 0;
$itemSeccion = 0;

// Cargo manejador de datos del buscador
include(dirPath.'/includes/widgets/base/buscador.inc.php');

if ($qText != '') {

	$gd = New Notas();
	$gd->db = $db;
	$gd->itemTipo = $itemTipo;
	$gd->itemSeccion = $itemSeccion;
	$gd->itemTexto = $qText;
	$gd->itemTextoTipo = $qTipo;
	$gd->itemSQLExtra = implode(' AND ', $sqlFiltroArr);
	$gd->itemFacetas = 'noticia_seccion_id, rango'; // opciones de facetado
	$gd->cantidad = $pagCantidad;
	$gd->desplazamiento = $pagDesplazamiento;

		$ogd = New Adjuntos();
		$ogd->db = $db;
		$ogd->datos = $adjuntos_files;
		$gd->loadObj('adjunto',$ogd);

	$dataToSkin = $gd->process();
	$dataToFacetas = $gd->facetas;

	$totalResultados = $gd->totalResultados;
	$totalPaginas = $gd->totalPaginas;

	if ($gd->errorInfo) {

		$msjError = $gd->errorInfo;

	}
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>