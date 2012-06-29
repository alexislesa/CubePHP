<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'institucional';
$incBody = dirTemplate.'/'.$pathRelative.'/rss.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra-interior.inc.php';

// Body class / Menú active
$pagActualClass[] = 'rss';
$pagActualClass[] = 'institucional';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Institucional'] = '/institucional';
$breds['Canales RSS'] = '';

$webTitulo = 'Canales RSS - '.$webTitulo;

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>