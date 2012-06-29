<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'institucional';
$incBody = dirTemplate.'/'.$pathRelative.'/contacto.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra-interior.inc.php';

// Cuando finaliza el proceso
$incBodyEnd = dirTemplate.'/'.$pathRelative.'/contacto-fin.inc.php';

// Body class / Menú active
$pagActualClass[] = 'contacto';
$pagActualClass[] = 'institucional';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Institucional'] = '/institucional';
$breds['Contacto'] = '';

// Web titulo
$webTitulo = 'Contacto - '.$webTitulo;
$webDescripcion = '';

// Validaciones a realizar
$checkForm = array();
$checkForm[] = 'required, nombre, Ingrese su nombre';
$checkForm[] = 'length=0-35, nombre, El nombre no puede superar los 35 caracteres';

$checkForm[] = 'required, apellido, Ingrese su apellido';
$checkForm[] = 'length=0-64, nombre, El apellido no puede superar los 64 caracteres';

$checkForm[] = 'required, email, Ingrese su email';
$checkForm[] = 'valid_email, email, Ingrese un email válido';

$checkForm[] = 'if:tel_pref!=, digits_only, tel_pref, Ingrese solo números para el prefijo del teléfono móvil sin el cero inicial';
$checkForm[] = 'if:tel_pref!=, length=2-4, tel_pref, Ingrese el prefijo completo de su teléfono móvil sin el cero inicial';

$checkForm[] = 'if:tel_pref!=, required, tel_nro, Ingrese su número de teléfono móvil';
$checkForm[] = 'if:tel_nro!=, required, tel_pref, Ingrese el prefijo de su número de teléfono móvil';

$checkForm[] = 'if:tel_nro!=, digits_only, tel_nro, Ingrese solo números sin espacios ni guiones para el número de su teléfono móvil';
$checkForm[] = 'if:tel_nro!=, length=6-8, tel_nro, Ingrese el número completo de su teléfono móvil sin el 15 inicial';

/* Otro teléfono
Uno de los teléfonos es requerido */
$checkForm[] = 'if:tel_otro_pref=, required, tel_pref, Ingrese al menos un teléfono';

$checkForm[] = 'if:tel_otro_pref!=, digits_only, tel_otro_pref, Ingrese solo números para el prefijo de su teléfono sin el cero inicial';
$checkForm[] = 'if:tel_otro_pref!=, length=2-4, tel_otro_pref, Ingrese el prefijo completo de su teléfono sin el cero inicial';

$checkForm[] = 'if:tel_otro_pref!=, required, tel_otro_nro, Ingrese su número de teléfono';
$checkForm[] = 'if:tel_otro_nro!=, required, tel_otro_pref, Ingrese el prefijo de su número de teléfono';

$checkForm[] = 'if:tel_otro_nro!=, digits_only, tel_otro_nro, Ingrese solo números sin espacios ni guiones para el número de su teléfono';
$checkForm[] = 'if:tel_otro_nro!=, length=6-8, tel_otro_nro, Ingrese el número completo de su teléfono';

$checkForm[] = 'required, pais, Seleccione su país';
$checkForm[] = 'if:pais=12, required, provincia, Seleccione su provincia';
//$checkForm[] = 'if:provincia!=795, required, departamento, Seleccioná tu departamento';
$checkForm[] = 'if:provincia=248, required, localidad, Seleccione su localidad';

$checkForm[] = 'required, mensaje, Ingrese el texto de su consulta';
$checkForm[] = 'length=0-601, mensaje, El texto de la consulta no puede superar los 600 caracteres';

$checkForm[] = 'required, captcha, Ingrese el texto que aparece en la imagen de seguridad';
$checkForm[] = 'length=4, captcha, Debes ingresar los 4 digitos para el código de seguridad';

$dataToSkin = array();

/* Llaves para reCaptcha generadas desde https://www.google.com/recaptcha/admin/create */
$publickey = '6LcHn70SAAAAANrA3RNzRg74tU1q9jbNa54-bckA';
$privatekey = '6LcHn70SAAAAAAuMkLjsKeWYwwXL3K8TXbO9WrzQ';

if (!empty($_GET['act'])) {

	// Limpio los datos ingresados vía POST
	$_POST = cleanArray($_POST);

	$usr = new checkFormulario($_POST, $checkForm);
	
	// Información si tiene Captcha propio
	$usr->captchaName = 'captcha';
	$usr->captchaValue = !empty($_SESSION['imgvalor']) ? $_SESSION['imgvalor'] : '';

	// Seguridad para prevenir XSS en form.
	$usr->tokenName = 'token';
	$usr->tokenValue = !empty($_SESSION['token']) ? $_SESSION['token'] : '';
	unset($_SESSION['token']);

	if ($usr->process()) {

		/**
		 * Agregado para que el adjunto se suba al sitio y lo puedan descargar 
		 * Respeta el nombre, pero elimina los espacios en blanco y agrega datos de fecha y hora del archivo
		 */
		if (!empty($_FILES)) {
		
			$ft = 0;
			foreach($_FILES as $k => $v) {
				$ft++;
				
				$fcha = date('Y_m_d_G_i_s_').$ft;
				$nombre = str_replace(' ', '_', $v['name']);
				$nombre = strtolower($nombre);

				if (move_uploaded_file($v['tmp_name'], '../includes/tmp/'.$fcha.$nombre)) {
					$usr->campos['adjunto_'.$ft] = $url_root.'includes/tmp/'.$fcha.$nombre;
				}
			}
		}
	
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

			/** 
			 * Envio email 
			 */
			 $email_config = array(
				'tipo' => $ss_config['email_send_tipo'],
				'host' => $ss_config['email_host'],
				'port' => $ss_config['email_port'],
				'auth' => $ss_config['email_auth'],
				'user' => $ss_config['email_user'],
				'pass' => $ss_config['email_pass'],
				'sender' => $ss_config['email_sender'],
				'to' => $ss_config['email_to']
			 );
			
			$email = New EnvioMails($email_config);
			$email->phpmailer = dirPath.'/includes/clases/class.phpmailer.php';

			$email->asunto = 'Consulta desde '.$_SERVER['SERVER_NAME'];
			$email->datos = $usr->campos;
			
			if (!empty($ss_config['email_to_tester'])) {
				foreach($ss_config['email_to_tester'] as $key => $destinatario) {
					$email->destinatario[] = $destinatario;
				}
			} else {
				$email->destinatario = $ss_config['email']['to'];
			}			
			
			if ($email->process(dirTemplate.'/emails/institucional_contacto.htm')) {
			
				$incBody = $incBodyEnd;
				
				// Agrego la clase fin
				$pagActualClass[] = 'contacto-fin';

				// Envio mail al usuario remitente
				$email->asunto = 'Copia de consulta enviada desde '.$_SERVER['SERVER_NAME'];
				$email->destinatario = $usr->campos['email'];
				$email->process(dirTemplate.'/emails/institucional_contacto_respuesta.htm');

			} else {
				$msjError = $email->errorInfo;
			}
			
		} // End check error_msj;
		
	} else {
	
		// Devuelvo el mensaje de error detectado
		$msjError = $usr->errorInfo;
	}
	
	$dataToSkin = $usr->campos;
}

// Genero Token para verificar la procedencia del formulario
$token = md5(microtime(true));
$_SESSION['token'] = $token;

/**
 * Agregado en caso de debug
 * Para visualizar el contenido del proceso final
 */
if (!empty($_GET['debug']) && $_GET['debug'] == true) {
	$incBody = $incBodyEnd;
	$dataToSkin['email'] = 'emaildemo@advertis.com.ar';
	
	$pagActualClass[] = 'contacto-fin';
}

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>