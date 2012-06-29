<?php
// Conector
include ('../../includes/comun/conector.inc.php');

// Validaciones a realizar
$checkForm = array();
$checkForm[] = "required, nombre, Ingrese su nombre";
$checkForm[] = "length=0-35, nombre, El nombre no puede superar los 35 caracteres";

$checkForm[] = "required, email, Ingrese su email";
$checkForm[] = "valid_email, email, Ingrese un email válido";

$dataToSkin = array();

if (!empty($_GET['act'])) {

	// Limpio los datos ingresados vía POST
	$_POST = cleanArray($_POST);

	$usr = new checkFormulario($_POST, $checkForm);
	
	if ($usr->process()) {
	
		include (dirPath.'/includes/widgets/api/MCAPI.class.php');

		$api = new MCAPI($ss_config['api_mailchimp_key']);

		$mergeVars = array('FNAME' => $usr->campos['nombre']);

		if($api->listSubscribe($ss_config['api_mailchimp_list'], $usr->campos['email'], $mergeVars) === true) {
			// Se envío correctamente a su email
			$msjError = 'ok';
			
		}else{
			// Error, no se pudo guardar en mailchimp
			// 'Error: ' . $api->errorMessage;
			// $error_msj = $api->errorMessage;
			$msjError = 'Error al guardar sus datos. Intente nuevamente.';
		}
	
	} else {
	
		// Devuelvo el mensaje de error detectado
		$msjError = $usr->errorInfo;	
	}
} else {

	$msjError = 'No he ingresaron datos';
}

echo $msjError;
?>