<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'noticia';
$incBody = dirTemplate.'/'.$pathRelative.'/interior.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra-interior.inc.php';

// Body class / Menú active
$pagActualClass[] = 'noticias';
$pagActualClass[] = 'nota-interior';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Noticias'] = '/noticias';

// Información para recuperar artículos
$itemTipo = 1;
$itemSeccion = 0;
$itemId = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : false;
$itemTitulo = !empty($_GET['t']) ? cleanInjection($_GET['t']) : false;
$itemForce = (!empty($_GET['mid']) && $_GET['mid'] == substr(md5($_GET['id']),0,3)) ? true : false;	// Utilizado en vista preliminar

if ($itemId || $itemTitulo) {

	$gd = New Notas();
	$gd->db = $db;
	$gd->itemTipo = $itemTipo;
	$gd->itemSeccion = $itemSeccion;
	$gd->itemId = $itemId;
	$gd->itemTitulo = $itemTitulo;
	$gd->embed = dirTemplate.'/'.$pathRelative.'/objetos/';
	$gd->cantidad = 1;
	$gd->statsAdd('view');
	$gd->cache = $cache;

		$ogd = New Adjuntos();
		$ogd->db = $db;
		$ogd->datos = $adjuntos_files;
			$ogd2 = New Adjuntos();
			$ogd2->db = $db;
			$ogd2->datos = $adjuntos_files;
			$ogd->loadObj('adjunto',$ogd2);
		$gd->loadObj('adjunto',$ogd);

		$ogd = New Notas();
		$ogd->db = $db;
		$gd->loadObj('nota',$ogd);

		$ogd = New Comentarios();
		$ogd->db = $db;
		$gd->loadObj('comentario',$ogd);

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

} else {

	$msjAlerta = 'El artículo que desea ver no existe';

}

/** CONTENIDO DEMO - LUEGO SACAR */
include (dirPath.'/includes/cache/test-interior.inc.php');

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>