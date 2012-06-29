<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

// Consulta si esta logeado
include (dirTemplate.'/lectores/check-login.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/cambio-clave.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// una vez modificada
$incBodyEnd = dirTemplate.'/lectores/cambio-clave-fin.inc.php';

// Body class / Menú active
$pagActualClass[] = 'lectores';
$pagActualClass[] = 'lectorcambioclave';
$pagActualClass[] = 'lector-online';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds["Lectores"] = "/lectores";
$breds["Modificar mi clave"] = "";

$webTitulo = 'Modificación de contraseña - '.$webTitulo;

/* Validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, claveold, Ingrese su clave actual";
$checkForm[] = "length=6-15, claveold, La clave actual debe tener entre 6 y 30 caracteres";
$checkForm[] = "required, clave, Ingrese una nueva clave";
$checkForm[] = "length=6-15, clave, Su nueva clave debe tener entre 6 y 30 caracteres";
$checkForm[] = "same_as, clave2, clave, La nueva clave no coincide";

$dataToSkin = array();
if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);

	$usrCheck = new checkFormulario($_POST, $checkForm);

	if ($usrCheck->process()) {
	
		if ($usr->login($_SESSION['web_site']['user'], $usrcheck->campos['claveold'])) {
			
			if ($usr->newPass($usrcheck->campos['clave'])) {
			
				if (isset($incBodyEnd) && file_exists($incBodyEnd)) {
				
					$incBody = $incBodyEnd;
					
				} else {
				
					Header('Location: salir.php');
					exit();
				}

			} else {
			
				$msjError = $usr->errorInfo;
			}

		} else {
			$msjError = "La contraseña actual no coincide. Verifique que los datos sean correctos e intente nuevamente";
		}

	} else {
	
		$msjError = $usrcheck->errorInfo;
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