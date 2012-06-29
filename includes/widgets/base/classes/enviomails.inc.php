<?php
/**
 * Clase para envío de emails
 *
 * <b>Que hace?</b> <br/>
 * Procesa datos que pueden ser de un formulario y 
 * los envía a una o varias casillas de emails.
 *
 * <b>Cómo se usa:</b> <br>
 * Inicializando la clase EnvioMails: <br/><br/>
 *
 * Ejemplo de uso: (configuración mínima)
 * <code>
 * $usr = new EnvioMails();
 * </code>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / Socket / cURL / clase PHPMailer
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>19.03.2012 <br/>
 *	- Modify: Se optimizó la función de envío de emails. Se pasaron todas 
 *	las propiedades a formato CamelCase. </li>
 *
 * <li>14.12.2010 <br/>
 *	- Modify: Se optimizó el código 
 *	y se verificaron las inicializaciones de variables. </li>
 *
 * <li>11.08.2010 <br/>
 *	- Added: Se agregó la opción que el mail a procesar acepte comandos de PHP.</li>
 *
 * <li>03.04.2010 <br/>
 *	- Fixed: Se corrigio problema cuando se desea realizar 
 *	más de un envío y la clase ya se encuentra declarada. </li>
 * </ul>
 *
 * @package		Widgets
 * @subpackage	Emails
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	03.04.2010
 * @version		1.0	- last revision 2012.03.19
 */
class EnvioMails {

	/**
	 * Array de mensajes de error
	 * 
	 * @access public
	 * @var array
	 */
	private $_errorArray;

	/** 
	 * Flag que indica que la clase phpmailer ya ha sido inicializada
	 *
	 * Utilizado cuando deseo enviar otro mail desde el mismo script
	 *
	 * @access private
	 * @var boolean
	 */
	private $_mailDeclared;
	
	/** 
	 * Asunto del email a enviar
	 *
 	 * @access public
	 * @var string
	 */
	public $asunto;
	
	/**
	 * Array de datos con los parametros para el envío de emails
	 * 
	 * Estructura del array:
	 * 	tipo: local/sendmail/qmail/smtp
	 *	host: localhost/smtp.example.com.ar
	 *	user: test@example.com.ar
	 *	pass: clave1234
	 *	auth: true/false (indica si se autentica en envío o no)
	 *	port: 25
	 *	sender: test@example.com.ar (email remitente, opcional)
	 *	sender_name: nombre del remitente (opcional)
	 *	to: test@example.com.ar (dirección a donde se envian los mails, utilizado en formulario de contactos)
	 *
	 * @access public
	 * @var array
	 */
	public $config;
	
	/**
	 * Array de datos a reemplazar en el template email
	 * 
	 * La estructura del array debe ser: clave=>valor para cada valor a reemplazar
	 *
 	 * @access public
	 * @var array
	 */
	public $datos;
	
	/**
	 * Flag para saber si estoy en modo test de errores
	 *
	 * @access public
	 * @var boolean
	 */
	public $debug;
	
	/**
	 * Lista de destinatarios del envío
	 * 
	 * Parametro opcional. Si esta vacio toma la clave "to" del array $config
	 * Acepta las siguientes formas:
	 * <pre>
	 *	email@email.com
	 *	email@email.com, Alexis Lesa
	 *	array("email@email.com, Alexis Lesa")
	 * </pre>
	 * 
	 * @access public
	 * @var string|array
	 */
	public $destinatario;
	
	/**
	 * Dirección donde esta el template del email a enviar
	 * 
	 * Acepta dirección local o remota (url) del template.
	 * La dirección local puede ser relativa o absoluta.
	 * La url debe ser de la forma: http://www.example.com/email.html
	 *
 	 * @access public
	 * @var string
	 */
	public $emailTemplate;
	
	/**
	 * Variable con el mensaje o mensajes de error a mostrar.
	 * 
	 * @access public
	 * @var string
	 */
	public $errorInfo;
	
	/**
	 * Flag que me indica que se encontró un error.
	 *
	 * @access private
	 * @var boolean
	 */
	public $errorNro;
	
	/**
	 * Dirección local donde se encuentra la clase phpmailer
	 *
 	 * @access public
	 * @var string
	 */
	public $phpmailer;

	/**
	 * Constructor de la clase
	 * 
	 * @access	public
	 * @param	array	$config	Parametros de configuración de envío de emails
	 * @param	string	$mailer	Path donde se encuentra la clase PHPmailer
	 */
	public function __construct($config=array(), $mailer=false) {
	
		// inicializo los parametros básicos
		$this->debug = false;
		$this->errorInfo = false;
		$this->errorNro = false;
		$this->asunto = '';
		
		$this->phpmailer = $mailer;
		$this->config = $config;
		
		$this->_mailDeclared = false;
		
		// Array de errores.
		$this->_errorArray = array();
		$this->_errorArray[1] = 'El cuerpo del mensaje esta vacio. El email no se ha enviado';
		$this->_errorArray[2] = 'El mensaje no tiene asunto. El email no se ha enviado';
		$this->_errorArray[3] = 'Faltan parametros del mensaje. El email no se ha enviado';
		$this->_errorArray[4] = 'No se puede inicializar la clase de envios. El email no se ha enviado';
		$this->_errorArray[5] = 'El destinatario no esta definido. El email no se ha enviado';
		$this->_errorArray[6] = 'Error al intentar enviar el email. El mismo no se ha enviado';
		$this->_errorArray[7] = 'Faltan parametros de configuración. El email no se ha enviado';
	}

	/**
	 * Procesa los errores de la clase
	 * 
	 * @access	protected
	 * @param	mixed	$id		Identificador del número de error (integer) 
	 *							o string para que me cargue el texto del error
	 * @param	string	$texto	Opcional, texto a reemplazar 
	 *							en el mensaje de error. Valor a reemplazar: [x]
	 */
	protected function error($id=0, $texto=false) {

		$this->errorNro = 0;

		if (is_string($id)) {

			$this->errorInfo = $id;

		} else {
		
			$this->errorInfo = !empty($this->_errorArray[$id]) 
							? $this->_errorArray[$id] 
							: 'Error inesperado';
			
			$this->errorNro = !empty($this->_errorArray[$id]) ? $id : 0;
		}
		
		if ($texto) {
			$this->errorInfo = str_replace('[x]', $texto, $this->errorInfo);
		}

		$this->log.= '- Error: '.$this->errorInfo.'<br/>';
	}
	
	/**
	 * Procesa el template y envía el email
	 *
	 * @access	public
	 * @param	string	$template	Dirección donde se encuentra el template
	 * @return	boolean	True si todo se procesó correctamente
	 */
	public function process($template=false) {

		$this->emailTemplate = $template;
		
		// Consulto si tengo los parametros de configuración
		if (empty($this->config)) {
			$this->error(7);
			return false;
		}
		
		// Consulto si el template esta declarado y existe
		if (!$template) {
			$this->error(1);
			return false;
		}
		
		// Consulto el si el template existe
		if (!file_exists($template)) {
			$this->error(1);
			return false;
		}

		// Consulto si el email tiene asunto
		if ($this->asunto == '') {
			$this->error(2);
			return false;
		}

		// Consulto si tengo los parametros del mensaje
		if (empty($this->datos)) {
			$this->error(3);
			return false;		
		}
		
		// Consulto si tengo la url del phpmailer
		// Por defecto es la misma que donde esta esta clase.
		// Si ya esta declarado, no verifico esta parte
		if (!$this->_mailDeclared) {
		
			if (!$this->phpmailer) {
				$this->phpmailer = dirname(__FILE__);
			}

			$test_file = pathinfo($this->phpmailer);
			if (empty($test_file['extension'])) {
				$this->phpmailer.= (substr($this->phpmailer,-1) != '/') ? '/' : '';
				$this->phpmailer.= 'class.phpmailer.php';
			}

			$this->log.= '- Url del archivo:'.$this->phpmailer.' <br/>';
			
			if (!file_exists($this->phpmailer)) {
				$this->error(4);
				
				return false;
			}
		}
		
		// consulto si tengo definido el destinatario
		if (empty($this->destinatario) && empty($this->config['to'])) {
			$this->error(5);
			return false;
		}
		
		// Inicializo clase de manejo de emails.
		// Salto este paso si ya esta inicializado.
		if (!$this->_mailDeclared) {
			include($this->phpmailer);
			$this->_mailDeclared = true;
		} // end check redeclarado

		// Incluyo el email aceptando programación.
		// paso todas las variables en $im;
		$im = $this->datos;
		ob_start();
			include $template;
			$cuerpo = ob_get_contents();
		ob_end_clean();

		// Asigno los datos al contenido del email
		foreach($this->datos as $k => $v) {
			$cuerpo = str_replace('{'.$k.'}',$v,$cuerpo);
		}
		$cuerpo = str_replace('{fecha_txt}',date('d.m.Y'),$cuerpo);
		
		$webSite = 'http://'.$_SERVER['SERVER_NAME'];
		$webSite.= ($_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : ''; 
		$cuerpo = str_replace('{web_site}', $webSite, $cuerpo);

		$dest = array();
		if (empty($this->destinatario)) {
			$dest[] = $this->config['to'];
		} else {
			if (!is_array($this->destinatario)) {
				$dest[] = $this->destinatario;
			} else {
				$dest = $this->destinatario;
			}
		}
		
		$mail = new phpmailer();

		switch($this->config['tipo']) {
			case 'smtp':
				$mail->IsSMTP();
			break;

			case 'qmail':
				$mail->IsQmail();
			break;
			
			case 'sendmail':
				$mail->IsSendmail();
			break;

			default:
				$mail->IsMail();
			break;
		}

		$mail->SMTPDebug = $this->debug;
		
		if (!empty($this->config['host'])) {
			$mail->Host = $this->config['host'];
		}
		
		if (!empty($this->config['port'])) {
			$mail->Port = $this->config['port'];
		}
		
		if ($this->config['auth']) {
			$mail->SMTPAuth = $this->config['auth'];
		}
		
		if (!empty($this->config['user'])) {
			$mail->Username = $this->config['user'];
		}
		
		if (!empty($this->config['pass'])) {
			$mail->Password = $this->config['pass'];
		}
		
		$mail->From = $this->config['sender'];
		$mail->FromName = !empty($this->config['sender_name']) 
						? $this->config['sender_name'] 
						: $this->config['sender'];
		$mail->WordWrap = 70;
		$mail->IsHTML(true);
		$mail->Subject = $this->asunto;
		$mail->Body = $cuerpo;

		foreach ($dest as $k => $destino) {
			list($email, $nombre) = array_pad(explode(',', $destino), 2, '');
			$email = ($email != '') ? $email : false;
			$nombre = ($nombre != '') ? $nombre : '';
			
			if ($email) {
				$mail->AddAddress($email, $nombre);
			}
		}

		// Esto es para enviar de a un email por vez. 
		// En vez de enviarlos todos juntos.
		$mail->SingleTo = true;

		if (!$mail->Send()) {
			if ($this->debug) {
				echo $mail->ErrorInfo;
			}
			
			$this->errorNro = true;
			$this->errorInfo = $mail->ErrorInfo;
			return false;
		}
		
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		$mail->IsHTML(false);
		$mail->Subject = '';
		$mail->Body = '';
	
		return true;
	}
	
} // end class
?>