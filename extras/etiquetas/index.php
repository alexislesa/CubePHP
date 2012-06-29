<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'etiquetas';
$incBody = dirTemplate.'/'.$pathRelative.'/listado.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'etiquetas';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Nube de temas'] = '';

// Información para recuperar artículos
$itemTipo = 1;
$itemSeccion = 0;

/* Genero la nube de tags */
$etags = New nubeTags(-1, $itemTipo, $itemSeccion);	// todas las posibles etiquetas del tipo 26 (notas)
$etags->db = $db;
$etags->itemMaxPeso = 10;	// peso del 1 al 10
$dataToSkin = $etags->process();

$msjError = ($etags->errorNro) ? $etags->errorInfo : false;
$totalResultados = $etags->totalResultados;

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>