<?php 
/**
 * Clase para validaci�n de datos de formularios
 *
 * <b>Que hace?</b> <br/>
 * Compruba todos los campos seg�n las reglas de validaci�n ingresadas
 *
 * <b>C�mo se usa:</b> <br>
 * Inicializando la clase checkFormulario: <br/><br/>
 *
 * Ejemplo de uso: (configuraci�n m�nima)
 * <code>
 * $img = new Captcha();
 * $img->size = 30;	// Alto de fuente
 * $img->width = 298;	// Ancho de la imagen
 * $img->height = 40;	// Alto de la imagen
 * $img->font = '/font/georgia.ttf';	// Ruta de la fuente
 * $img->process();
 * </code>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>26.03.2012 <br/>
 *	- Added: Se agreg� el uso de token para validaci�n de env�o XSS 
 *	(prevenir env�os desde otro formulario).</li>
 *
 * <li>20.03.2012 <br/>
 *	- Fix: Se corrigieron inicializaciones de variables 
 *	para evitar XSS injections </li>
 *
 * <li>19.03.2012 <br/>
 *	-Modify: Se optimiz� la inicializaci�n y carga de elementos de la clase.<br/>
 *	-Modify: Se modific� la propiedad errors_all por viewAllErrors.<br/>
 *	- Added: Se agreg� la propiedad errorNro 
 *	para indicar el n�mero del ultimo error generado</li>
 *
 * <li>15.12.2010 <br/>
 *	- Fix: Se solucion� problema al limpiar un valor que o es array,
 *	o cuando se llama a la funci�n sin ning�n valor.</li>
 *
 * <li>06.09.2010 <br/>
 *	- Fix: Se solucion� el problema en la validaci�n de rango. 
 *	Este noo funcionaba correctamente. </li>
 *
 * <li>08.04.2010 <br/>
 *	- Modify: Se modific� la limpieza de datos 
 *	agregando comandos de limpieza de de injection. </li>
 *
 *	<li>29.03.2010 <br/>
 *	- Added: Se agreg� que valide captcha al final de la validaci�n normal.<br/>
 *	Permite el chequeo solo ingresando el valor del captcha 
 *	(si el nombre del campo es captcha) </li>
 * </ul>
 *
 * @package		Widgets
 * @subpackage	Validators
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	23.08.2010
 * @version		1.0	- last revision 2012.03.19
 */
class CheckFormulario {

	/**
	 * Indica que se encontr� un error.
	 *
	 * @access private
	 * @var boolean
	 */
	private $_errorFound;

	/**
	 * Array de datos para revisar
	 *
	 * @access public
	 * @var array
	 */
	public $campos;
	
	/**
	 * Texto de mensaje de error cuando el c�digo Captcha en incorrecto
	 *
	 * @access public
	 * @var string
	 */
	public $captchaError;
	
	/**
	 * nombre del campo captcha a revisar, 
	 * si esta vacio no revisa la validacion captcha
	 *
	 * @access public
	 * @var string
	 */
	public $captchaName;
	
	/**
	 * Valor del captcha contra el que debe validar
	 *
	 * @access public
	 * @var string
	 */
	public $captchaValue;
	
	/**
	 * Mensaje de error cuando hay una validaci�n y el campo no existe
	 * 
	 * @access public
	 * @var string
	 */
	public $errorDefault;

	/**
	 * Mensajes de error encontrado
	 * 
	 * @access public
	 * @var string
	 */
	public $errorInfo;
	
	/**
	 * N�mero de error encontrado. (solo en modo viewAllErrors: false)
	 * 
	 * @access public
	 * @var integer
	 */
	public $errorNro;
	
	/**
	 * Texto de mensaje de error cuando el token es incorrecto
	 *
	 * @access public
	 * @var string
	 */
	public $tokenError;
	
	/**
	 * Nombre del campo token a revisar, 
	 * si esta vacio no revisa la validacion token
	 *
	 * @access public
	 * @var string
	 */
	public $tokenName;
	
	/**
	 * Valor del token contra el que debe validar
	 *
	 * @access public
	 * @var string
	 */
	public $tokenValue;
	
	/**
	 * Array de validaciones a comprobar
	 * 
	 * @access private
	 * @var array
	 */
	private $validaciones;
	
	/**
	 * Indica si muestra todos los errores juntos.
	 * Si es true, revisa todos los campos en busca de errores.
	 * si es false, al encontrar un error abandona el chequeo.
	 *
	 * @access public
	 * @var boolean
	 */
	public $viewAllErrors;
	
	/**
	 * Inicializo la clase con valores por defecto
	 * 
	 * @access	public
	 * @param	array	$campos			Datos a revisar
	 * @param	array	$validaciones	Validaciones a comprobar
	 */
	public function __construct($campos=false, $validaciones=false) {
	
		$this->_errorFound = false;
		$this->errorInfo = false;
		$this->errorNro = 0;
		$this->errorDefault = 'El campo: {x} que se desea comprobar no existe';
		
		$this->viewAllErrors = false;	// por defecto muestro de a un (1) error.

		// Mensaje de error y validaciones en captcha
		$this->captchaError = 'El c�digo de seguridad ingresado es incorrecto';
		$this->captchaName = 'captcha';
		$this->captchaValue = '';
		
		// Mensaje de error y validaciones en token (seguridad XSS)
		$this->tokenError = 'Error al recibir informaci�n del formulario';
		$this->tokenName = 'token';
		$this->tokenValue = '';
		
		if ($campos) {
			$this->campos = $campos;
		}

		if ($validaciones) {
			$this->validaciones = $validaciones;
		}
	}

	/**
	 * Realiza el proceso de validaci�n de los campos
	 * 
	 * @access	public
	 * @return	boolean	True si la comprobaci�n salio todo bien
	 */
	public function process() {
	
		// limpio los datos (injection, xss, etc)
		$this->campos = cleanArray($this->campos);

		$this->checkValidaciones();
		
		if ($this->_errorFound) {
		
			return false;
			
		} else {
		
			// Consulto si tiene Captcha definido y compruebo su valor
			if ($this->captchaName != '' 
				&& $this->captchaValue != ''
				&& isset($this->campos[$this->captchaName])
				&& $this->campos[$this->captchaName] != $this->captchaValue) {
					
				$this->processError($this->captchaError);
				return false;
			}
		}

		return true;
	}

	/**
	 * Carga el error en la variable correspondiente y me indica como proceder
	 * En caso de que me muestre todos los errores continua, caso contrario detiene el proceso de validaci�n.
	 * 
	 * @access	private
	 * @param	string	$mensaje	Mensaje de error a mostrar
	 * @return	boolean				Indica si contin�a revisando o no
	 */
	private function processError($mensaje='') {

		$this->_errorFound = true;
		$ret = false;
		
		if ($this->viewAllErrors) {
			$mensaje = $this->errorInfo.'<br/> '.$mensaje;
			$ret = true;
		}

		$this->errorInfo = $mensaje;
		return $ret;
	}
	
	/** 
	 * Realiza la comprobaci�n de todos los campos seg�n las reglas de validaci�n predefinidas
	 * 
	 * @access	private
	 * @return	boolean	True si la validaci�n fue un �xito, false si se encontro alg�n error
	 */
	private function checkValidaciones() {

		$campos = $this->campos;

		// Recorro el array de validaciones.
		for ($i=0; $i<count($this->validaciones); $i++) {

			$errorInLine = false;
			
			$this->validaciones[$i] = cleanInjection($this->validaciones[$i]);

			$rows = explode(',', $this->validaciones[$i]);
			
			// Limpio los campos
			foreach ($rows as $k=>$v) {
				$rows[$k] = trim($v);
			}

			$camposIfContinue = true;	// Utilizado para la validacion en IF

			if (substr($rows[0],0,3) == 'if:') {
				$rowsx = substr($rows[0],3);

				// Comparaci�n del tipo a!=b
				if (strstr($rows[0],'!=')) {
					list($oCampo, $oValue) = explode('!=', $rowsx);
					
					// Compruebo que exista y sea string
					if (isset($campos[$oCampo])) {

						if ($campos[$oCampo] != $oValue) {
						
							array_shift($rows);

						} else {
						
							$camposIfContinue = false;

						}
					
					} else {
					
						$camposIfContinue = false;
					}

				} else {
				
					// Comparaci�n del tipo a=b
					if (strstr($rows[0], '=')) {
					
						list($oCampo, $oValue) = explode('=', $rowsx);
						
						if (isset($campos[$oCampo])) {

							if ($campos[$oCampo] == $oValue) {
								array_shift($rows);
							} else {
								$camposIfContinue = false;
							}
						
						} else {
							$camposIfContinue = false;
						}

					}
				}
			}
	
			if ($camposIfContinue) {
			
				$checkRequerimiento = $rows[0];
				$checkCampo1 = $rows[1];
				
				switch (count($rows)) {
					case 6: // Validaci�n de fechas.
					$checkMsj = $rows[5];
					break;

					case 5: // Validaci�n con expresiones regulares
					$checkMsj = $rows[4];
					break;

					case 4: // Validaci�n de igual a, alfanumericso, algun tipo de expresion regular.
					$checkMsj = $rows[3];
					break;
					
					default: // Resto de las validaciones
					$checkMsj = $rows[2];
					break;
				}
				
				if ($checkRequerimiento != 'function' && empty($checkCampo1)) {
					// Error el campo no existe.
					$errorInLine = true;
					$checkMsj = str_replace('{x}' , $checkCampo1, $this->errorDefault);
				}

				if (!$errorInLine) {
					$errorInLine = !($this->checkValidCampo($rows));
				}

				if ($errorInLine) {
					if (!$this->processError($checkMsj)) {
						return false;
					}
				}
			}
		} // end for validaciones

	} // en process
	
	/**
	 * Realiza comprobaci�n de reglas de validaci�n
	 * 
	 * @access	private
	 * @param	array	$rows	Regla de validaci�n a comprobar
	 * @return	boolean			Retorna true si la validaci�n se realiz� exitosamente
	 */
	private function checkValidCampo($rows) {

		$campos = $this->campos;

		$errorInLine = false;
		$checkRequerimiento = $rows[0];
		$checkCampo1 = $rows[1];
		
		// Comprobaci�n XSS para saber si el campo no fue manipulado
		if (!(isset($checkCampo1) && is_string($checkCampo1))) {
			return false;
		}

		switch (count($rows)) {
			case 6: // Validaci�n de fechas.
			$checkCampo2 = $rows[2];
			$checkCampo3 = $rows[3];
			$check_fecha = $rows[4];
			$checkMsj = $rows[5];
			break;

			case 5: // Validaci�n con expresiones regulares
			$checkCampo2 = $rows[2];
			$checkCampo3 = $rows[3];
			$checkMsj = $rows[4];
			break;

			case 4: // Validaci�n de igual a, alfanumericso, algun tipo de expresion regular.
			$checkCampo2 = $rows[2];
			$checkMsj = $rows[3];
			break;
			
			default: // Resto de las validaciones
			$checkMsj = $rows[2];
			break;
		}

		// Si la validaci�n es por longitud de caracteres.
		if (substr($checkRequerimiento,0,6) == 'length') {
			$checkRequerimiento_all = $checkRequerimiento;
			$checkRequerimiento = 'length';
		}
		
		if (substr($checkRequerimiento,0,5) == 'range') {
			$checkRequerimiento_all = $checkRequerimiento;
			$checkRequerimiento = 'range';
		}

		// Realizo las validaciones.
		switch($checkRequerimiento) {
			case 'required':	// Campo requerido.
				if (empty($campos[$checkCampo1]) || $campos[$checkCampo1] == '') {
					$errorInLine = true;
				}
			break;
			
			case 'digits_only':	// Solo n�meros
				if (!empty($campos[$checkCampo1]) && !is_numeric($campos[$checkCampo1])) {
					$errorInLine = true;
				}
			break;
			
			case 'letters_only':	// Solo letras
				if (!ereg("^[a-zA-Z]+$",$campos[$checkCampo1])){
					$errorInLine = true;
				}
			break;
		
			case 'is_alpha':	// Alfanumericos
				if (!ereg("^[a-zA-Z0-9]+$",$campos[$checkCampo1])){
					$errorInLine = true;
				}
			break;
			
			case 'custom_alpha':	// Alfanumericos personalizados
				$conversion = array(
					'L' => '[A-Z]',
					'V' => '[AEIOU]',
					'l' => '[a-z]',
					'v' => '[aeiou]',
					'D' => '[a-zA-Z]',
					'F' => '[aeiouAEIOU]',
					'C' => '[BCDFGHJKLMNPQRSTVWXYZ]',
					'x' => '[0-9]',
					'c' => '[bcdfghjklmnpqrstvwxyz]',
					'X' => '[1-9]',
					'E' => '[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]'
				);
				
				$exp_regulares = '';
				for ($l=0; $l<strlen($checkCampo2); $l++) {
					if (!empty($conversion[$checkCampo2{$l}])) {
						$exp_regulares.= $conversion[$checkCampo2{$l}];
					} else {
						$exp_regulares.= $checkCampo2{$l};
					}
				}
				
				echo $exp_regulares;
				if (!ereg($exp_regulares,$campos[$checkCampo1])){
					$errorInLine = true;
				}
			break;
			
			case 'reg_exp':	// Expresiones Regulares
				if (!ereg($checkCampo2,$campos[$checkCampo1])){
					$errorInLine = true;
				}
			break;
			
			case 'length':	// Longitud del campo
				$long_campo = strlen($campos[$checkCampo1]);

				// Si la longitud es igual a xx o un rango: xx-yy
				if (substr($checkRequerimiento_all,0,7) == 'length=') {
			
					$revision = substr($checkRequerimiento_all,7);
					$rango = explode('-', $revision);
				
					if (count($rango) == 2) {
						// comprobaci�n en rango. (entre x - y)
						if ($long_campo < $rango[0] || $long_campo > $rango[1]) {
							$errorInLine = true;
						}
					} else {
						// Comprobaci�n del tipo a=x
						if ($long_campo != $rango[0]) {
							$errorInLine = true;
						}
					}
				} else {
			
					// Si la longitud es mayor o igual a xx
					if (substr($checkRequerimiento_all,0,8) == 'length>=') {
						$rango = substr($checkRequerimiento_all,8);
						if ($long_campo >= $rango) {
							$errorInLine = true;
						}
					} else {
					
						// Si la longitud es mayor a xx
						if (substr($checkRequerimiento_all,0,7) == 'length>') {
							$rango = substr($checkRequerimiento_all,7);
							if ($long_campo > $rango) {
								$errorInLine = true;
							}
						} else {
						
							// Si la longitud es menor o igual a xx
							if (substr($checkRequerimiento_all,0,8) == 'length<=') {
								$rango = substr($checkRequerimiento_all,8);
								if ($long_campo <= $rango) {
									$errorInLine = true;
								}
							} else {

								// Si la longitud es menor a xx
								if (substr($checkRequerimiento_all,0,7) == 'length<') {
									$rango = substr($checkRequerimiento_all,7);
									if ($long_campo < $rango) {
										$errorInLine = true;
									}
								}
							
							}
						}
					}
				}
			break;
			
			case 'valid_email':	// Valida si es un email
				if (!ereg("^([a-zA-Z0-9\._]+)\@([a-zA-Z0-9\.-]+)\.([a-zA-Z]{2,4})$",$campos[$checkCampo1])){
					$errorInLine = true;
				}
			break;
			
			case 'valid_date':	// Valida fecha

				if (!checkdate ($campos[$checkCampo1], $campos[$checkCampo2], $campos[$checkCampo3])) {
					$errorInLine = true;
				}

				switch($check_fecha) {
					case 'later_date':	// posterior a la fecha actual
						if (!$errorInLine) {
							if (time() > mktime(0,0,0,$campos[$checkCampo1], $campos[$checkCampo2], $campos[$checkCampo3])) {
								$errorInLine = true;
							}
						}
					break;
					
					case 'any_date': // cualquier fecha
					break;
				}
			
			break;
			
			case 'same_as':	// Valida si el valor de un campo es igual a otro
				if ($campos[$checkCampo1] != $campos[$checkCampo2]) {
					$errorInLine = true;
				}
			break;
			
			case 'range':	// Valida si el valor esta entre un rango de n�meros dado
				$long_campo = $campos[$checkCampo1];
				
				// Si el valor es igual a xx o un rango: xx-yy
				if (substr($checkRequerimiento_all,0,6) == 'range=') {
				
					$revision = substr($checkRequerimiento_all,6);
					$rango = explode("-", $revision);
					
					if (count($rango) == 2) {
						// comprobaci�n en rango. (entre x - y)
						if ($long_campo < $rango[0] || $long_campo > $rango[1]) {
							$errorInLine = true;
						}
					} else {
						// Comprobaci�n del tipo a.value=x
						if ($long_campo != $rango[0]) {
							$errorInLine = true;
						}
					}
					
				} else {
				
					// Si el valor es mayor o igual a xx
					if (substr($checkRequerimiento_all,0,7) == 'range>=') {
						$rango = substr($checkRequerimiento_all,7);
						if ($rango >= $long_campo) {
							$errorInLine = true;
						}
					} else {
					
						// Si el valor es mayor a xx
						if (substr($checkRequerimiento_all,0,6) == 'range>') {
							$rango = substr($checkRequerimiento_all,6);
							if ($rango > $long_campo) {
								$errorInLine = true;
							}
						} else {
						
							// Si el valor es menor o igual a xx
							if (substr($checkRequerimiento_all,0,7) == 'range<=') {
								$rango = substr($checkRequerimiento_all,7);
								if ($rango <= $long_campo) {
									$errorInLine = true;
								}
							} else {

								// Si el valor es menor a xx
								if (substr($checkRequerimiento_all,0,6) == 'range<') {
									$rango = substr($checkRequerimiento_all,6);
									if ($rango < $long_campo) {
										$errorInLine = true;
									}
								}
							
							}

						}

					}
				}
			break;
			
			case 'function':	// valida con una funci�n
			break;
			
			default:
				// No se encuentra ninguna regla de validacion.
				return false;
			break;
		}
		
		return !$errorInLine;
	}
}
?>