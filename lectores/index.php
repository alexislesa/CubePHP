<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/home.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'lectores';
$pagActualClass[] = 'lector-offline';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Lectores'] = '/lectores';
$breds['Mi cuenta'] = '';

$webTitulo = 'Panel del usuario - '.$webTitulo;

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>