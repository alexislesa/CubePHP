<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/clasificado-add-1.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = 'lectores';
$pagActualClass[] = 'lector-online';
$pagActualClass[] = 'clasificado';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds["Mi cuenta"] = "/lectores";
$breds["Nuevo Clasificado"] = "";

$webTitulo = 'Nuevo Clasificado - '.$webTitulo;

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, categoria, Selecione una categoría";

if (empty($_SESSION['clasificado-add'])) {
	$_SESSION['clasificado-add'] = array();
}

if (empty($_SESSION['clasificado-add']['paso_actual'])) {
	$_SESSION['clasificado-add']['paso_actual'] = 1;
}

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);
	
	$usrCheck = new checkFormulario($_POST, $checkForm);

	// Seguridad para prevenir XSS en form.
	$usrCheck->tokenName = 'token';
	$usrCheck->tokenValue = !empty($_SESSION['token']) ? $_SESSION['token'] : '';
	unset($_SESSION['token']);

	if ($usrCheck->process()) {
	
		$_SESSION['clasificado-add'] = array_merge($_SESSION['clasificado-add'], $usrCheck->campos);
	
		// Indico que voy al paso 2
		$_SESSION['clasificado-add']['paso_actual'] = 2;
	
		// Paso 1 OK, ingreso al Paso 2
		Header("Location: clasificado-add-2.php");
		exit();

	} else {

		$msjError = $usrCheck->errorInfo;

	}

	$dataToSkin = $usrCheck->campos;
}

$dataToSkin = $usr->campos;

// Cargo token
$token = md5(microtime(true));
$_SESSION['token'] = $token;


// Recupero información sobre las categorías
$catDB = New ClasificadosCategoria();
$catDB->db = $db;
// $catDB->cache = $Cache;
$catDB->extend = false;
$catArr = $catDB->process();


include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>