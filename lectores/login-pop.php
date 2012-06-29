<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

// Validaciones a realizar
$checkForm = array();
$checkForm[] = 'required, usuario, Ingrese un nombre de usuario';
$checkForm[] = 'length=6-20, usuario, El nombre de usuario debe tener entre 6 y 20 caracteres';
$checkForm[] = 'required, clave, Ingrese una clave';
$checkForm[] = 'length=6-15, clave, La clave debe tener entre 6 y 30 caracteres';

$dataToSkin = array();
if (!empty($_COOKIE['recordar'])) {
	$dataToSkin['usuario'] = $_COOKIE['recordar'];
	$dataToSkin['recordar'] = 1;
}

if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);

	$usrCheck = new checkFormulario($_POST, $checkForm);

	if ($usrCheck->process()) {
	
		$usr = new Usuarios();
		$usr->db = $db;
		$usr->passEncript = 'md5';
		$usr->nickNameMin = 6;
		$usr->nickNameMax = 15;
		$usr->passMin = 6;
		$usr->passMax = 30;
		$usr->passwordStrong = 0;
		
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

		} else {
		
			$msjError = $usr->errorInfo;
		}

	} else {
	
		$msjError = $usrCheck->errorInfo;
	}
}

echo ($msjError) ? utf8_encode($msjError) : 'OK';
?>