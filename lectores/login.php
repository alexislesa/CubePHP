<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/login.inc.php';
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

$webtitulo = 'Ingreso de usuarios - '.$webTitulo;

// Si el usuario no esta logeado, lo llevo al login
$usr = new Usuarios();
$usr->db = $db;
$usr->passEncript = 'md5';

if (isset($_SESSION['web_site']['user'])
	&& isset($_SESSION['web_site']['pw']) 
	&& $usr->login($_SESSION['web_site']['user'], $_SESSION['web_site']['pw'])) {

	Header('Location: /lectores/');
	exit();
}

// Validaciones a realizar
$checkForm = array();
$checkForm[] = "required, usuario, Ingrese un nombre de usuario";
$checkForm[] = "length=6-20, usuario, El nombre de usuario debe tener entre 6 y 20 caracteres";
$checkForm[] = "required, clave, Ingrese una clave";
$checkForm[] = "length=6-15, clave, La clave debe tener entre 6 y 30 caracteres";

$dataToSkin = array();
if (!empty($_COOKIE['recordar'])) {
	$dataToSkin['usuario'] = $_COOKIE['recordar'];
	$dataToSkin['recordar'] = 1;
}

if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);

	$usrCheck = new checkFormulario($_POST, $checkForm);

	if ($usrCheck->process()) {
	
		// $usr = new Usuarios();
		// $usr->db = $db;
		$usr->nickNameMin = 6;
		$usr->nickNameMax = 15;
		$usr->passMin = 6;
		$usr->passMax = 30;
		$usr->passwordStrong = 0;
		// $usr->passEncript = 'md5';
		
		if ($usr->login($usrCheck->campos['usuario'], $usrCheck->campos['clave'], true)) {
		
			$_SESSION['web_site']['user'] = $usrCheck->campos['usuario'];
			$_SESSION['web_site']['pw'] = $usrCheck->campos['clave'];
			$_SESSION['web_site']['id'] = $usr->campos['lector_id'];

			if (!empty($usrCheck->campos['recordar'])) {

				// recuerda usuario por 30 días
				setcookie('recordar', $usrCheck->campos['usuario'] , time()+(60*60*24*30));

			} else {

				setcookie('recordar', '');
			}

			if (!isset($usrCheck->campos['url']) || $usrCheck->campos['url'] == '') {
				$usrCheck->campos['url'] = '/lectores/';
			}

			Header('Location: '.$usrCheck->campos['url']);
			exit();

		} else {
		
			$msjError = $usr->errorInfo;
		}

	} else {
	
		$msjError = $usrCheck->errorInfo;
	}

	$dataToSkin = $usrCheck->campos;
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>