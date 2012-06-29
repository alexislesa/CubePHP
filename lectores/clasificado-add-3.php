<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/clasificado-add-3.inc.php';
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
$breds['Mi cuenta'] = '/lectores';
$breds['Nuevo Clasificado'] = '';

$webTitulo = 'Nuevo Clasificado - '.$webTitulo;

// Consulto si realmente debe estar en esta página, sino retorno al inicio
if (empty($_SESSION['clasificado-add']) || $_SESSION['clasificado-add']['paso_actual'] < 3) {
	Header("Location: clasificado-add-2.php");
	exit();
}

// Recupero información de la página anterior
$clasToSkin = $_SESSION['clasificado-add'];

// Recupero información sobre las categorías
$catDB = New ClasificadosCategoria();
$catDB->db = $db;
// $catDB->cache = $Cache;
$catArr = $catDB->process();
$catInf = $catDB->g($clasToSkin['catid']);

$catInf1 = $catDB->g($clasToSkin['categoria']);
$catInf2 = $catDB->g($clasToSkin['subcat1']);
$catInf3 = $catDB->g($clasToSkin['subcat2']);
$catInf4 = $catDB->g($clasToSkin['subcat3']);

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, duracion, Seleccione la duración del aviso";
$checkForm[] = "digits_only, duracion, Error al seleccionar la duración. Intente nuevamente";

$sql = "SELECT * FROM clasificados LIMIT 0,1";
$res = $db->query($sql);
$rs = $db->next($res);

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);
	
	$usrCheck = new checkFormulario($_POST, $checkForm);

	// Seguridad para prevenir XSS en form.
	$usrCheck->tokenName = 'token';
	$usrCheck->tokenValue = !empty($_SESSION['token']) ? $_SESSION['token'] : '';
	unset($_SESSION['token']);

	if ($usrCheck->process()) {
	
		// $_SESSION['clasificado-add'] = $usrCheck->campos;
		$_SESSION['clasificado-add'] = array_merge($_SESSION['clasificado-add'], $usrCheck->campos);
		
		// Realizo la carga en la dBase
		
		
		
		$_SESSION['clasificado-add']['paso_actual'] = 4;
		
		echo "<pre>".print_r($_SESSION, true)."</pre>";
		

		// Paso 3 OK, ingreso al Paso 4
		Header("Location: clasificado-add-4.php");
		exit();

	} else {

		$msjError = $usrCheck->errorInfo;
	}

	$dataToSkin = $usrCheck->campos;
}

$dataToSkin = $usr->campos;

// Cargo la duración del aviso
$catDur = New ClasificadosDuracion();
$catDur->db = $db;
$durArrData = $catDur->process();

// Cargo la duración del aviso
$catExp = New ClasificadosExposicion();
$catExp->db = $db;
$expArrData = $catExp->process();



// Cargo token
$token = md5(microtime(true));
$_SESSION['token'] = $token;

/**
 * Agregado en caso de debug
 * Para visualizar el contenido del proceso final
 */
if (!empty($_GET['debug']) && $_GET['debug'] == true) {
	$incBody = $incBodyEnd;
	$dataToSkin['email'] = 'emaildemo@advertis.com.ar';
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>