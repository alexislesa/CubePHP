<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'encuestas';
$incBody = dirTemplate.'/encuestas/voto-proceso.inc.php';

// Cuando finaliza el proceso
$incBodyEnd = dirTemplate.'/encuestas/voto-fin.inc.php';

// Página de Ud ya votó
$incBodyFail = dirTemplate.'/encuestas/voto-yavoto.inc.php';


// Web titulo
$webTitulo = 'Votar - '.$webTitulo;
$webDescripcion = '';


// Validaciones a realizar
$checkForm = array();
$checkForm[] = 'required, encuesta, La encuesta no está ingresada';
$checkForm[] = 'digits_only, encuesta, El identificador de la encuesta ingresada no es correcta';

$checkForm[] = 'required, item, El item de la encuesta no está ingresada';
$checkForm[] = 'digits_only, item, El item a votar no es correcta';

$dataToSkin = array();

if (empty($_GET['act'])) {

	/* Limpio los datos ingresados vía POST */
	$_GET = cleanArray($_GET);

	$usr = new checkFormulario($_GET, $checkForm);
	if (!$usr->process()) {

		// Devuelvo el mensaje de error detectado
		$msjError = $usr->errorInfo;

	}
	
} else {

	// Agrego validación de Captcha
	$checkForm[] = 'required, captcha, Ingrese el texto que aparece en la imagen de seguridad';
	$checkForm[] = 'length=4, captcha, Debes ingresar los 4 digitos para el código de seguridad';

	/* Limpio los datos ingresados vía POST */
	$_POST = cleanArray($_POST);

	$usr = new checkFormulario($_POST, $checkForm);
	
	/* Información si tiene Captcha propio */
	$usr->captchaName = 'captcha';
	$usr->captchaValue = !empty($_SESSION['imgvalor']) ? $_SESSION['imgvalor'] : '';

	if ($usr->process()) {

		/** 
		 * Consulto si tiene recaptcha y lo proceso
		 */
		if (!empty($_POST['recaptcha_response_field'])) {

			include(dirPath.'/includes/widgets/api/recaptcha.php');

			/* Usado para respuesta de reCaptcha */
			$resp = null;
			$resp = recaptcha_check_answer ($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
			
			if (!$resp->is_valid) {
				$msjError = $resp->error;
			}
		}
	
		if (!$msjError) {
		
			$enc = new Encuestas();
			$enc->db = $db;
			$enc->itemId = $usr->campos['encuesta'];
			if ($enc->votar($usr->campos['item'])) {

				// Muestro la página de éxito.
				$incBody = $incBodyEnd;

			} else {
			
				$msjError = $enc->errorInfo;
				
				// Página de Ud ya votó.
				if ($enc->errorNro == 6) {
					$incBody = $incBodyFail;
				}
			}

		} // End check error_msj;

	} else {
	
		// Devuelvo el mensaje de error detectado
		$msjError = $usr->errorInfo;
	}
	
	// $dataToSkin = $usr->campos;
}

// Cargo información de la encuesta a mostrar
if (isset($usr->campos['encuesta']) && is_numeric($usr->campos['encuesta'])) {

	$data = $usr->campos;

	$enc = New Encuestas();
	$enc->db = $db;
	$enc->itemId = $usr->campos['encuesta'];
	$enc->itemEstado = false;
	if (!$dataToSkin = $enc->process()) {

		$msjError = $enc->errorInfo;

	} else {
		// Compruebo que el item exista para esa encuesta
		if (!isset($dataToSkin[0]['items'][$usr->campos['item']])) {

			$msjError = "El item que desea votar no existe";
			$data['item'] = 0;
		}

	}
}

unset($_SESSION['imgvalor']);

include (dirPath.'/includes/comun/pop-header.inc.php');

include (dirTemplate.'/herramientas/base-pop.inc.php');

include (dirPath.'/includes/comun/pop-bottom.inc.php');
?>