<?php
/* Conector */
include ('includes/comun/conector.inc.php');

/* Cargo la portada */
$noticia_arr = loadHome('0');

/* Web titulo */
$webTitulo = $noticia_arr[1][1]['noticia_titulo'].' - '.$webTitulo;
$webDescripcion = $noticia_arr[1][1]['noticia_bajada'];

$pathRelative = 'home';
$incBody = dirTemplate.'/'.$pathRelative.'/home-cuerpo.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

$pagActualClass[] = 'inicio';
$pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
// $pagActualClass[] = 'sidebar-right';

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>