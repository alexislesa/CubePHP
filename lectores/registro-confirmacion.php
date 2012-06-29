<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/registro-confirmacion.inc.php';
$incBodyEnd = dirTemplate.'/'.$pathRelative.'/registro-confirmacion-fin.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = "lectores";
$pagActualClass[] = "lector-offline";
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Confirmación de registro'] = '';

$webTitulo = 'Confirmación de registro - '.$webTitulo;

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

$checkForm[] = "required, email, Ingrese su email";
$checkForm[] = "valid_email, email, Ingrese un email válido";
$checkForm[] = "length=0-100, email, El email ingresado es demasiado largo. Verifique si es correcto";

$checkForm[] = "required, codigo, Ingrese el código de usuario que se ha enviado a su casilla de email";
$checkForm[] = "length=0-30, codigo, El código de usuario debe tener un máximo de 30 caracteres";


$dataToSkin = array();

if (!empty($_GET['act'])) {

	$oForm = cleanArray($_POST);
	
	/**
	 * Si viene desde email, levanto datos vía GET 
	 */
	if ($_GET['act']=='confirm') {
		$oForm = cleanArray($_GET);
	}

	$usrCheck = new checkFormulario($oForm, $checkForm);

	if ($usrCheck->process()) {
	
		if ($usr->altaConfirm($usrCheck->campos)) {
		
			/* Opcional: Logeo al usuario */
			/*
			if ($usr->login($usr->campos["lector_usuario"], $usr->campos["lector_clave"])) {
				$_SESSION["web_site"]["user"] = $usr->campos["lector_usuario"];
				$_SESSION["web_site"]["pw"] = $usr->campos["lector_clave"];
				
				Header("Location: /lectores");
				exit();
			}
			*/

			$incBody = $incBodyEnd;

		} else {
		
			$msjError = $usr->errorInfo;

		}

	} else {
	
		$msjError = $usrCheck->errorInfo;
	}
	
	$dataToSkin = $usrCheck->campos;
}


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