<?php
/**
 * Genera una imagen con texto para validación Captcha
 *
 * <b>Que hace?</b> <br/>
 * Imprime en el navegador una imagen PNG / JPG con el código captcha y genera 
 * una variable de session con el mismo valor
 *
 * <b>Cómo se usa:</b> <br>
 * Inicializando la clase Captcha: <br/><br/>
 *
 * Ejemplo de uso: (configuración mínima)
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
 * - PHP 5+ / GD2
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>08.03.2012 <br/>
 *	- Added: Se agregó opciones de fondo transparente y alfa opacity.</li>
 *
 * <li>04.01.2011 <br/>
 *	- Modify: Se modificó la forma de generar una cadena randomizada de 
 *	texto para el capcha, sin generar una semilla de ramdom.</li>
 *
 * <li>23.08.2010 <br/>
 *	- Alta de la clase</li>
 * </ul>
 *
 * @package		Widgets
 * @subpackage	Validators
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	23.08.2010
 * @version		1.0	- last revision 2012.03.08 
 */
class Captcha {

	/**
	 * Angulo de inclinación para el texto generado
	 *
	 * Permite valores positivos o negativos. Cero, no tiene inclinación.
	 *
	 * @access public
	 * @var integer Valor por defecto: 5 (inclinación de 5 grados)
	 */
	public $angle;
	
	/**
	 * Porcentaje de alfa Transparente
	 *
	 * @access public
	 * @var integer Valor del 0 al 100, por defecto 0
	 */
	public $alfaTransparent;
	
	/**
	 * Color del fondo en hexadecimales
	 *
	 * Permite utilizar un color de fondo para la imagen captcha
	 *
	 * @access public
	 * @var string Valor por defecto: #fff
	 */
	public $backgroundColor;
	
	/**
	 * Archivos de fondos para mostrar en el captcha
	 * 
	 * Permite recuperar una imagen de fondo para mostrar debajo del texto. 
	 * Acepta más de un archivo separado por comas.
	 * Si se ingresan más de un archivo se utilizará de a uno de forma aleatoria
	 *
	 * @access public
	 * @var string Valor por defecto: null
	 */
	public $backgroundFile;
	
	/**
	 * Color del texto generado en hexadecimal
	 *
	 * @access public
	 * @var string Valor por defecto: #000
	 */
	public $color;	

	/**
	 * Fuentes con las que se generará la imagen captcha
	 *
	 * Permite más de una fuente separadas por coma.
	 * Se debe ingresar toda la ruta donde se encuentra el archivo.
	 * Las fuentes deben ser True Type Font (ttf)
	 *
	 * @access public
	 * @var string Valor por defecto: false
	 */
	public $font;
	
	/**
	 * Alto de la imagen a generar
	 *
	 * Si el valor es cero tendrá el alto mínimo al que el texto requiera
	 *
	 * @access public
	 * @var integer
	 */
	public $height;

	/**
	 * Longitud de caracteres de la imagen
	 *
	 * @access public
	 * @var integer Valor por defecto: 4
	 */
	public $length;
	
	/**
	 * Nombre de la session generada
	 *
	 * Nombre de SESSION a utilizar para guardar el valor captcha. 
	 *
	 * @access public
	 * @var string Valor por defecto: imgvalor
	 */
	public $sessionName;
	
	/**
	 * Tamaño de la fuente en pixeles
	 *
	 * @access public
	 * @var integer Valor por defecto: 20 (20pixeles)
	 */
	public $size;
	
	/**
	 * Fortaleza de la palabra
	 * Selecciona 3 niveles de fortalezas, entre 1 y 3
	 * <pre>
	 * 1) Solo números
	 * 2) Números y letras en minúsculas
	 * 3) Números y letras en mayúsculas y minúsculas
	 * </pre>
	 *
	 * @access public
	 * @var integer Valor por defecto:3
	 */
	public $strong;
	
	/**
	 * Ancho de la imagen a generar
	 *
	 * Por defecto la imagen tendrá el ancho mínimo al que el texto requiera
	 *
	 * @access public
	 * @var integer
	 */
	public $width;

	/**
	 * Inicializa los valores por defecto del captcha
	 *
	 * @access public
	 */
	public function __construct() {
	
		$this->length = 4;
		$this->font = false;
		$this->size = 20;
		$this->angle = 5;
		$this->strong = 3;
		
		$this->width = 0;
		$this->height = 0;
		
		$this->alfaTransparent = 0;
		$this->backgroundFile = '';
		$this->backgroundColor = '#FFF';
		$this->color = '#000';
		$this->sessionName = 'imgvalor';
	}
	
	/**
	 * Procesa el color y devuelve los valores decimales
	 *
	 * @access	private
	 * @param	string	$color	Color en hexadecimal de 3 o 6 digitos
	 * @return	array	matriz de colores en RGB
	 */
	private function checkColor($color) {

		if (preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $color)) {
			$color = str_replace('#','', $color);
			$l = strlen($color) == 3 ? 1 : (strlen($color) == 6 ? 2 : false);

			if ($l) {
				unset($out);
				$out['r'] = hexdec(substr($color, 0,1*$l));
				$out['g'] = hexdec(substr($color, 1*$l,1*$l));
				$out['b']= hexdec(substr($color, 2*$l,1*$l));
			}else {
				$out = false;
			}

		} else {

			if (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $c)){
				
				$spr = str_replace(array(',',' ','.'), ':', $c);
				$e = explode(':', $spr);
				
				if(count($e) != 3) {
					return false;
				}
				
				$out = '#';

				for ($i = 0; $i<3; $i++) {
					$e[$i] = dechex(($e[$i] <= 0) ? 0 :(($e[$i] >= 255) ? 255 : $e[$i]));
				}

				for($i = 0; $i<3; $i++) {
					$out.= ((strlen($e[$i]) < 2) ? "0" : "" ).$e[$i];
				}

				$out = strtoupper($out);
			}
		}
		
		return $out;
	}

	/**
	 * Genera el texto a cargar en la imagen y en la session de usuario
	 *
	 * @access	private
	 * @return	string	Texto generado
	 */
	private function randomText() {
		$output = '';
		$input = array('1','2','3','4','5','6','7','8','9');
		
		if ($this->strong > 1) {
			$input = array_merge($input, 
				array(
				'a','b','c','d','e','f','g','h','i',
				'j','k','l','m','n','o','p','q','r',
				's','t','u','v','w','x','y','z'
				)
			);
		}
		
		if ($this->strong > 2) {
			$input = array_merge($input, 
				array(
				'A','B','C','D','E','F','G','H','I',
				'J','K','L','M','N','O','P','Q','R',
				'S','T','U','V','W','X','Y','Z'
				)
			);
		}

		shuffle($input);
		$output = implode('', $input);

		return substr($output, 0, $this->length);
	}

	/**
	 * Procesa los parametros ingresados y genera la imagen captcha
	 * Imprime en la pantalla la imagen generada
	 *
	 * @access	public
	 */
	public function process() {
	
		// Consulto si tiene al menos una fuente
		if (!$this->font) {
			return false;
		}
		$oFont = explode(',',$this->font);
		$fuente = $oFont[rand(0,(count($oFont) - 1))];
		if (!file_exists($fuente)) {
			return false;
		}

		// Consulto por el archivo de fondo para cargar y selecciono uno
		if ($this->backgroundFile != '') {
			$oFile = explode(',',$this->backgroundFile);
			$bg_file = $oFile[rand(0,(count($oFile) - 1))];
		}

		$random = $this->randomText();
		$textBox = imagettfbbox($this->size, $this->angle, $fuente, $random);

		// Ingreso el valor en la session
		$_SESSION[$this->sessionName] = $random;

		// Si el tamaño no esta definido, lo fijo acá
		$oWidth = ($this->width) ? $this->width : ($textBox[4] + 10);
		$oHeight = ($this->height) ? $this->height : ($this->size + 4);

		if ($this->alfaTransparent) {
			$im = imagecreatetruecolor($oWidth, $oHeight);
		} else {
			$im = imagecreate($oWidth, $oHeight);
		}

		// Fondo de la imagen
		$bgColorArr = $this->checkColor($this->backgroundColor);
		$fondo = array();
		$fondo['red']	=  $bgColorArr['r'];
		$fondo['green']=  $bgColorArr['g'];
		$fondo['blue']	=  $bgColorArr['b'];
		$fondo['alfa']	=  $this->alfaTransparent;
		
		if ($this->alfaTransparent) {
			//modo sobreescritura de pixeles anteriores activado
			imagealphablending($im,false);
			
			//color con canal alfa
			$fondo = imagecolorallocatealpha($im, $fondo['red'], $fondo['green'], $fondo['blue'], $fondo['alfa']);
			
			//rellenamos (sustituimos) toda la imagen con este color
			imagefilledrectangle( $im , 0 , 0 , $oWidth , $oHeight , $fondo);
			
			//modo sobreescritura de pixeles anteriores desactivado
			imagealphablending($im,true);

		} else {
		
			$bgColor = imagecolorallocate($im, $bgColorArr['r'], $bgColorArr['g'], $bgColorArr['b']);
		}

		// Color de la fuente
		$fontColorArr = $this->checkColor($this->color);
		$color = imagecolorallocate($im, $fontColorArr['r'], $fontColorArr['g'], $fontColorArr['b']);
		
		$px = abs(($oWidth - $textBox[4]) / 2);
		$py = abs(($oHeight - $textBox[5]) / 2);

		imagettftext($im, $this->size, $this->angle, $px, $py, $color, $fuente, $random);		
		
		//cabecera
		header('Content-type: image/png');

		if ($this->alfaTransparent) {
			//salida conservando el canal alfa
			imagesavealpha($im,true);
		}
		
		//devolvemos datos al navegador
		imagepng($im);
		
		//destruimos imagen
		imagedestroy($im);
	}
}
?>