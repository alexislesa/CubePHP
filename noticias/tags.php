<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'noticia';
$incBody = dirTemplate.'/'.$pathRelative.'/tags.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Men� active
$pagActualClass[] = 'etiquetas';
$pagActualClass[] = 'etiquetas-listado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Noticias'] = '/noticias';
$breds['Etiquetas'] = '';

// Informaci�n para recuperar art�culos
$itemTipo = 1;
$itemSeccion = 0;
$itemTag = !empty($_GET['tag']) ? cleanInjection($_GET['tag']) : false;

// Para evitar que me muestre algo si no hay tags cargadas
if (!$itemTag) { 

	$msjError = 'No se ingres� ninguna etiqueta.';
	
} else {

	$gd = New Notas();
	$gd->db = $db;
	$gd->itemTipo = $itemTipo;
	$gd->itemSeccion = $itemSeccion;
	$gd->itemTags = $itemTag;
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

	$dataToSkin = $gd->process();

	$totalResultados = $gd->totalResultados;
	$totalPaginas = $gd->totalPaginas;

	if ($gd->errorInfo) {

		$msjError = $gd->errorInfo;

	} else {

		if ($totalResultados) {

		} else {

			$msjAlerta = 'No se encontraron art�culos para mostrar con la etiqueta <b>'.$itemTag.'</b>';
		}
	}
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>