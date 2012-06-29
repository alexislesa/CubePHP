<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'institucional';
$incBody = dirTemplate.'/'.$pathRelative.'/interior.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra-interior.inc.php';

// Body class / Menú active
$pagActualClass[] = 'institucional';
$pagActualClass[] = 'quieressomos';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Institucional'] = '/institucional';
$breds['Quienes somos'] = '';

// Información para recuperar artículos
$itemTipo = 1;
$itemSeccion = 0;
$itemId = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 1;
$itemTitulo = !empty($_GET['t']) ? cleanInjection($_GET['t']) : false;
$itemForce = (!empty($_GET['mid']) && $_GET['mid'] == substr(md5($_GET['id']),0,3)) ? true : false;	// Utilizado en vista preliminar

$gd = New Notas();
$gd->db = $db;
$gd->itemTipo = $itemTipo;
$gd->itemSeccion = $itemSeccion;
$gd->itemId = $itemId;
$gd->itemTitulo = $itemTitulo;
$gd->cantidad = 1;

	$ogd = New Adjuntos();
	$ogd->db = $db;
	$ogd->datos = $adjuntos_files;
	$gd->loadObj('adjunto',$ogd);

$dataToSkin = $gd->process();

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	$totalResultados = $gd->totalResultados;
	$totalPaginas = $gd->totalPaginas;

	if ($totalResultados) {

		$webTitulo = $dataToSkin[0]['noticia_titulo'].' - '.$webTitulo;
		$webDescripcion = $dataToSkin[0]['noticia_bajada'];

		if (!empty($dataToSkin[0]['imagen'])) {
			$webImagen = $dataToSkin[0]['imagen'][1]['url']['o'];
		}

	} else {

		$msjAlerta = 'El artículo que desea ver no existe';
	}
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>