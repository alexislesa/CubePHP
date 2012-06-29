<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'noticia';
$incBody = dirTemplate.'/noticia/objetos/enviar-cuerpo.inc.php';
$incBodyEnd = dirTemplate.'/noticia/objetos/enviar-cuerpo-fin.inc.php';

// Body class / Menú active
$pagActualClass[] = "noticias";
$pagActualClass[] = "noticias-interior";

// Información para recuperar artículos
$itemTipo = 0;
$itemSeccion = 0;
$itemId = (!empty($_GET["id"]) && is_numeric($_GET["id"])) ? $_GET["id"] : 0;
$itemTitulo = !empty($_GET["t"]) ? cleanInjection($_GET["t"]) : false;
$itemForce = (!empty($_GET["mid"]) && $_GET["mid"] == substr(md5($_GET["id"]),0,3)) ? true : false;	// Utilizado en vista preliminar

$gd = New Notas();
$gd->db = $db;
$gd->itemTipo = $itemTipo;
$gd->itemSeccion = $itemSeccion;
$gd->itemId = $itemId;
$gd->itemTitulo = $itemTitulo;
$gd->cantidad = 1;
$gd->statsAdd('email');

	$ogd = New Adjuntos();
	$ogd->db = $db;
	$ogd->datos = $adjuntos_files;
	$gd->loadObj("adjunto",$ogd);

$notaToSkin = $gd->process();

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	$totalResultados = $gd->totalResultados;
	$totalPaginas = $gd->totalPaginas;

	if ($totalResultados) {

		$webTitulo = $notaToSkin[0]['noticia_titulo'].' - '.$webTitulo;
		$webDescripcion = $notaToSkin[0]['noticia_bajada'];

		if (!empty($notaToSkin[0]['imagen'])) {
			$webImagen = $notaToSkin[0]['imagen'][1]['url']['o'];
		}
		
		// Array de información para enviar al form
		$fecha = "%l% %d% de %F% de %Y% &nbsp;|&nbsp; %G%:%i% Hs.";
		$notaFecha = formatDate($fecha, $notaToSkin[0]['noticia_fecha_modificacion']);
		
		$notaId = !empty($notaToSkin[0]['noticia_id']) ? $notaToSkin[0]['noticia_id'] : '';
		$notaTitulo = !empty($notaToSkin[0]['noticia_titulo']) ? $notaToSkin[0]['noticia_titulo'] : '';
		$notaBajada = !empty($notaToSkin[0]['noticia_bajada']) ? $notaToSkin[0]['noticia_bajada'] : '';

		$notaUrl = $urlRoot;
		$notaUrl.= !empty($notaToSkin[0]['seccion_rss_page']) ? $notaToSkin[0]['seccion_rss_page'] : '';
		$notaUrl.= '?id=';
		$notaUrl.= !empty($notaToSkin[0]['noticia_id']) ? $notaToSkin[0]['noticia_id'] : '';
		
		$inputHiddenArr = array();
		$inputHiddenArr['id'] = $notaId;
		$inputHiddenArr['titulo'] = $notaTitulo;
		$inputHiddenArr['url'] = $notaUrl;
		$inputHiddenArr['bajada'] = $notaBajada;
		$inputHiddenArr['fecha_nota'] = $notaFecha;
		
		
		// Fecha actual
		$fecha = "%l% %d% de %F% de %Y% &nbsp;|&nbsp; %G%:%i% Hs.";
		$fechaHoy = formatDate($fecha);		
		$inputHiddenArr['fecha'] = $fechaHoy;
		
	} else {

		$msjAlerta = "El artículo que desea ver no existe";
	}
}

$dataToSkin = array();

/* Cargo las validaciones a realizar */
$checkForm = array();
$checkForm[] = "required, id, La nota no esta definida";
$checkForm[] = "required, titulo, El titulo de la nota no esta definido";
$checkForm[] = "required, url, La nota no esta definida";

$checkForm[] = "required, nombre, Ingrese su nombre";
$checkForm[] = "length=0-35, nombre, El nombre no puede superar los 35 caracteres";

$checkForm[] = "required, email, Ingrese una dirección de email en el remitente";
$checkForm[] = "valid_email, email, Ingrese un email válido para el remitente";

$checkForm[] = "required, destinatario, Ingrese el nombre del destinatario";
$checkForm[] = "length=0-35, destinatario, El destinatario no puede superar los 35 caracteres";

$checkForm[] = "required, destinatario-mail, Ingrese el email del destinatario";
$checkForm[] = "valid_email, destinatario-mail, Ingrese un email válido para el destinatario";

$checkForm[] = "length=0-251, mensaje, El comentario no puede superar los 250 caracteres";

if (!empty($_GET['act'])) {

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
		if (!empty($_POST["recaptcha_response_field"])) {

			include(dirPath.'/includes/widgets/api/recaptcha.php');

			/* Usado para respuesta de reCaptcha */
			$resp = null;
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) {
				$msjError = $resp->error;
			}
		}
	
		if (!$msjError) {

			/** 
			 * Envio email 
			 */
			 $email_config = array(
				"tipo" => $ss_config["email_send_tipo"],
				"host" => $ss_config["email_host"],
				"port" => $ss_config["email_port"],
				"auth" => $ss_config["email_auth"],
				"user" => $ss_config["email_user"],
				"pass" => $ss_config["email_pass"],
				"sender" => $ss_config["email_sender"],
				"to" => $ss_config["email_to"]
			 );
			
			$email = New EnvioMails($email_config);
			$email->phpmailer = dirPath.'/includes/clases/class.phpmailer.php';

			$email->asunto = $us->campos['nombre'].' te envía un artículo desde '.$_SERVER['SERVER_NAME'];
			$email->datos = $usr->campos;
			
			$email->destinatario = $usr->campos['destinatario-mail'];
			
			if ($email->process(dirTemplate.'/emails/noticias_enviar.htm')) {
			
				$incBody = $incBodyEnd;

				// Envio mail al usuario remitente
				$email->asunto = 'Copia de artículo enviado desde '.$_SERVER['SERVER_NAME'];
				$email->destinatario = $usr->campos['email'];
				$email->process(dirTemplate.'/emails/noticias_enviar_copia.htm');
				
				/**
				 * Si la opción es cerrarla ventana al finalizar el envío
				 * Habilitar esta parte ->
				?>
					<html>
						<body>
						<script type="text/javascript">
							self.close();
						</script>
						</body>
					</html>
				<?
				*/				
				
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

include (dirPath.'/includes/comun/imprimir-header.inc.php');

include (dirTemplate.'/herramientas/base-pop.inc.php');

include (dirPath.'/includes/comun/pop-bottom.inc.php');
?>