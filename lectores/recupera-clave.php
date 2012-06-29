<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'lectores';
$incBody = dirTemplate.'/'.$pathRelative.'/recupera-clave.inc.php';
$incBodyEnd = dirTemplate.'/'.$pathRelative.'/recupera-clave-fin.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra.inc.php';


// Body class / Menú active
$pagActualClass[] = "lectores";
$pagActualClass[] = "lector-offline";
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Recuperación de clave'] = '';

$webTitulo = 'Recuperación de clave - '.$webTitulo;

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

/* Validaciones a realizar */
$checkForm = array();

$checkForm[] = "required, email, Ingrese su email";
$checkForm[] = "valid_email, email, Ingrese un email válido";
$checkForm[] = "length=0-100, email, El email ingresado es demasiado largo. Verifique si es correcto";

$dataToSkin = array();

if (!empty($_GET['act'])) {

	$oForm = cleanArray($_POST);
	
	$usrCheck = new checkFormulario($oForm, $checkForm);

	if ($usrCheck->process()) {
	

		if ($codigo = $usr->lostPassword($usrCheck->campos['email'])) {

			$campos = array();
			$campos["email"] = $usrCheck->campos["email"];
			$campos["codigo"] = $codigo;
			
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
				'to' => $usrCheck->campos['email']
			);
			
			$email = New envioMails($email_config);
			$email->url_phpmailer = dirPath."/includes/clases/class.phpmailer.php";

			$email->email_asunto = "Recupero de clave desde ".$ss_config["site_name"];
			$email->destinatario = $email_config["to"];
			$email->datos = $campos;
			
			if ($email->process(dirTemplate."/emails/recupera_clave_1.htm")) {
			
				$include_file = $include_file_fin;

			} else {
				$msjError = $email->errorInfo;
			}

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