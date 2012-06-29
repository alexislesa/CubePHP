<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/registro.inc.php';
$incBodyEnd = dirTemplate.'/'.$pathRelative.'/registro-fin.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';

// Body class / Menú active
$pagActualClass[] = "lectores";
$pagActualClass[] = "lector-offline";
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds["Registrarse"] = "";

$webTitulo = "Registro de usuario - ".$webTitulo;

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
$checkForm[] = "length=6-15, usuario, El nombre de usuario debe tener entre 6 y 15 caracteres";
$checkForm[] = "is_alpha, usuario, El nombre de usuario no puede contener espacios\, guiones\, símbolos ni acentos";

$checkForm[] = "required, clave, Ingrese una clave";
$checkForm[] = "length=6-15, clave, La clave debe tener entre 6 y 30 caracteres";

$checkForm[] = "same_as, clave2, clave, La clave no coincide";

$checkForm[] = "required, email, Ingrese su email";
$checkForm[] = "valid_email, email, Ingrese un email válido";
$checkForm[] = "length=0-100, email, El email ingresado es demasiado largo. Verifique si es correcto";

$checkForm[] = "required, nombre, Ingrese su nombre";
$checkForm[] = "length=0-35, nombre, El nombre no puede superar los 35 caracteres";

$checkForm[] = "required, apellido, Ingrese su apellido";
$checkForm[] = "length=0-64, nombre, El apellido no puede superar los 64 caracteres";

$checkForm[] = "required, sexo, Debe seleccionar su sexo";

$checkForm[] = "range>1901, fanio, Seleccione su fecha de nacimiento";
$checkForm[] = "valid_date,fmes,fdia,fanio,any_date, Ingrese una fecha de nacimiento válida";

$checkForm[] = "required, pais, Seleccione su país";
$checkForm[] = "if:pais=12, required, provincia, Seleccione su provincia";
//$checkForm[] = "if:provincia!=795, required, departamento, Seleccioná tu departamento";
$checkForm[] = "if:provincia=248, required, localidad, Seleccione su localidad";

$checkForm[] = "required, captcha, Ingrese el texto que aparece en la imagen de seguridad";
$checkForm[] = "length=4, captcha, Debes ingresar los 4 digitos para el código de seguridad";

$checkForm[] = "required, terminosycondiciones, Debe aceptar las Normas de Participación y Política de Privacidad para continuar";



$dataToSkin = array();

if (!empty($_GET['act'])) {

	$_POST = cleanArray($_POST);

	/* Agregado para que me carge un avatar por defecto cuando me registro */
	/*
	$avMasculino = '/images/avatar/15.jpg';
	$avFemenino = '/images/avatar/16.jpg';
	$_POST['avatar'] = !empty($_POST['sexo']) 
						? ($_POST['sexo'] == 'M' ? $avMasculino : $avFemenino) 
						: $avMasculino;
	*/

	$usrCheck = new checkFormulario($_POST, $checkForm);
	$usrCheck->captchaValue = !empty($_SESSION['imgvalor']) ? $_SESSION['imgvalor'] : '';

	if ($usrCheck->process()) {

		$usr = new Usuarios();
		$usr->db = $db;

		$usr->nickNameMin = 6;
		$usr->nickNameMax = 15;
		$usr->passMin = 6;
		$usr->passMax = 30;
		$usr->passwordStrong = 0;
		$usr->passEncript = 'md5';		
		
		if ($usr->alta($usrCheck->campos)) {
		
			// Envio email
			$email_config = array(
				'tipo' => $ss_config['email_send_tipo'],
				'host' => $ss_config['email_host'],
				'port' => $ss_config['email_port'],
				'auth' => $ss_config['email_auth'],
				'user' => $ss_config['email_user'],
				'pass' => $ss_config['email_pass'],
				'sender' => $ss_config['email_sender'],
				'to' => $usr->campos['email']
			);

			$email = New EnvioMails($email_config);
			$email->phpmailer = dirPath.'/includes/clases/class.phpmailer.php';

			$email->asunto = 'Confirmación de registro a '.$_SERVER['SERVER_NAME'];
			$email->destinatario = $email_config['to'];
			$email->datos = $usr->campos;
			
			if ($email->process(dirTemplate.'/emails/registro_confirmacion.htm')) {

				$incBody = $incBodyEnd;
				
				// Cargo información de suscripción a mailchimp
				$usr->campos['campo_1'] = 1;
				if (!empty($usr->campos['campo_1']) && $usr->campos['campo_1'] == 1 && !empty($ss_config['api_mailchimp_key'])) {
				
					include (dirPath.'/includes/widgets/api/MCAPI.class.php');

					$api = new MCAPI($ss_config['api_mailchimp_key']);

					$mergeVars = array('FNAME' => $usr->campos['nombre'], 'LNAME' => $usr->campos['apellido']);

					if($api->listSubscribe($ss_config['api_mailchimp_list'], $usr->campos['email'], $mergeVars) === true) {
						// Se envío correctamente a su email
					}else{
						// Error, no se pudo guardar en mailchimp
						// 'Error: ' . $api->errorMessage;
						// $error_msj = $api->errorMessage;
					}
				}				

			} else {
				$msjError = $email->errorInfo;
			}

		} else {
		
			// Devuelvo el mensaje de error detectado
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