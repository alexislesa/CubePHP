<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>25.06.2012 <br/>
 *	- Fix: Se modfic� la funci�n "cleanArray" para que funcione correctamente 
 *	en versiones de PHP +5.4 (se agreg� explicitamente que utilice 
 *	tabla de caracteres ISO-8859-1) </li>
 *
 * <li>24.06.2012 <br/>
 *	- Fix: Se modific� la funci�n cleanInjection para verificar 
 *	que lo que se desea procesar sea solo texto. <br/>
 *	Solo procesa cadenas de string, los n�meros los deja igual 
 *	y elimina otro tipo de variable.</li>
 *
 * <li>14.05.2012 <br/>
 *	- Fix: Se repar� error en funci�n de UrlFriendly. 
 *	Este ten�a error al procesar texto en may�scula.</li>
 *
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualiz� la documentaci�n de las funciones.</li>
 *
 * <li>27.03.2012 <br/>
 *	- Added: Se agreg� funci�n extra para preparar datos para Json.</li>
 *
 * <li>12.03.2012 <br/>
 *	- Modify: Se modific� la funci�n cutString 
 *	para que me deje intacta el tag.</li>
 *
 * <li>28.02.2012 <br/>
 *	- Fix: Se modific� la funci�n arrayUTF8. </li>
 * 
 * <li>21.01.2012 <br/>
 *	- Creaci�n de Helpers Time.</li>
 * </ul>
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @licence 	Comercial
 * @version 	0.90
 */

/**
 * Transforma datos de un array en datos UTF8 para Json
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		array	$datos	Matriz de datos a cambiar a utf8
 * @return		array	Datos transformados a utf8
 */
function JsonUTF8($datos) {

	if (is_array($datos)) {
	
		foreach ($datos as $k => $v) {
		
			$datos[$k] = JsonUTF8($v);
		}

	} else {
	
		if (is_string($datos)) {

			$datos = trim($datos);
			$datos = utf8_encode($datos);
		}
	}

	return $datos;
}
 
/**
 * Convierte caracteres de acentos en caracteres UTF8 (� => &aacute;)
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		string	$texto	Texto
 * @return		string	Texto con acentos en utf8
 */
function accentToUTF8($texto) {

	$reemplazos = array (
		'&' => '&amp;',
		'�' => '&aacute;',
		'�' => '&Acirc;',
		'�' => '&acirc;',
		'�' => '&AElig;',
		'�' => '&aelig;',
		'�' => '&Agrave;',
		'�' => '&agrave;',
		'�' => '&Aring;',
		'�' => '&aring;',
		'�' => '&Atilde;',
		'�' => '&atilde;',
		'�' => '&Auml;',
		'�' => '&auml;',
		'�' => '&Aacute;',

		'�' => '&Ccedil;',
		'�' => '&ccedil;',

		'�' => '&Eacute;',
		'�' => '&eacute;',
		'�' => '&Ecirc;',
		'�' => '&ecirc;',
		'�' => '&Egrave;',
		'�' => '&Euml;',

		'�' => '&egrave;',
		'�' => '&euml;',

		'�' => '&Igrave;',
		'�' => '&Iacute;',
		'�' => '&Icirc;',
		'�' => '&Iuml;',
		'�' => '&igrave;',
		'�' => '&iacute;',
		'�' => '&icirc;',
		'�' => '&iuml;',

		'�' => '&Ograve;',
		'�' => '&Oacute;',
		'�' => '&Ocirc;',
		'�' => '&Otilde;',
		'�' => '&Ouml;',
		'�' => '&Oslash;',
		'�' => '&ograve;',
		'�' => '&oacute;',
		'�' => '&ocirc;',
		'�' => '&otilde;',
		'�' => '&ouml;',

		'�' => '&ucirc;',
		'�' => '&Ugrave;',
		'�' => '&ugrave;',
		'�' => '&Uuml;',
		'�' => '&uuml;',
		'�' => '&uacute;',
		'�' => '&Uacute;',
		'�' => '&Ucirc;',
		'�' => '&Uuml;',

		'�' => '&Yacute;',
		'�' => '&yacute;',
		'�' => '&yuml;',
		'�' => '&Yuml;',

		'�' => '&THORN;',
		'�' => '&szlig;',
		'�' => '&ETH;',
		'�' => '&Ntilde;',
		'�' => '&eth;',
		'�' => '&ntilde;',
		'�' => '&oslash;',
		'�' => '&thorn;'
	);

	return strtr($texto, $reemplazos);
} 

/**
 * Remueve acentos y caracteres que no esten entre azAZ (�,�, �, etc)
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		string	$texto	Texto a reemplazar acentos
 * @return		string	Texto con acentos reemplazados
 */
function cleanAccents($texto) {

	$reemplazos = array(
		'�' => 'TH', '�' => 'th', '�' => 'DH', 
		'�' => 'dh', '�' => 'ss', '�' => 'OE', 
		'�' => 'oe', '�' => 'AE', '�' => 'ae', 
		'�' => 'u', '�' => 'ni', '�' => 'NI'
	);

	$cambiar_esto = 	'����������������������������������������������������������';
	$por_esto = 	'SZszYAAAAAACEEEEIIIIOOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy';

	$texto = str_replace('�','�', $texto);
	return strtr(strtr($texto, $cambiar_esto, $por_esto), $reemplazos);
}

/**
 * Transforma datos de un array en datos UTF8
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		array	$datos	Matriz de datos a cambiar a UTF8
 * @return		array	Datos transformados a UTF8
 */
function arrayUTF8($datos) {

	if (is_array($datos)) {
	
		foreach ($datos as $k => $v) {
		
			$datos[$k] = arrayUTF8($v);
		}

	} else {
	
		if (is_string($datos)) {

			$datos = utf8_encode($datos);
			$datos = htmlentities($datos);
			$datos = trim($datos);
			$datos = replaceQuot($datos);
		}
	}

	return $datos;
}
 
/**
 * Limpia array de datos (generalmente desde un GET o POST)
 * 
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		array	$data	Array de datos para limpiar
 * @return		array	Informaci�n limpia de xss
 */
function cleanArray($data) {

	if (is_array($data)) {
		foreach ($data as $k => $v) {
			if (is_string($v)) {
				$v = trim($v);
				$data[$k] = htmlspecialchars($v, ENT_QUOTES,'ISO-8859-1');
			} else {
				if (is_array($v)) {
					$data[$k] = cleanArray($v);
				}
			}
		}
	}

	return $data;
}

/**
 * Limpia el texto de Injection XSS y SQL Injection
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string 
 * @param		string	$texto	Texto a limpiar
 * @return		string	Texto limpio de injection
 */
function cleanInjection($texto='') {

	if (is_numeric($texto)) {
		return $texto;
	}
	
	if (!is_string($texto)) {
		return '';
	}
	
	$texto = strip_tags($texto);
	$texto = html_entity_decode($texto, ENT_QUOTES, 'ISO-8859-1');
	$texto = preg_replace('/&#(\d+);/me',"chr(\\1)", $texto);
	$texto = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $texto);
	$texto = addslashes($texto);
	$texto = trim($texto);
	
	return $texto;
}

/**
 * Comprime una cadena de texto, elimina los espacios y saltos de l�nea
 * 
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$string	Texto a comprimir
 * @return		string	Texto comprimido sin espacios ni saltos de l�nea.
 */ 
function compress($string) {

    $string = str_replace(array('\r\n', '\r', '\n', '\t', '  ', '    ', '    '), '', $string);

	return $string;	
}

/**
 * Corta el texto ingresado en palabras enteras
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$texto		Texto a realizar el corte
 * @param		integer	$cantidad	Cantidad de caracteres del corte
 * @param		string	$fin		Texto con el que finaliza el texto cortado
 * @return		string	Texto resultante cortado
 */
function cutString($texto='', $cantidad=0, $fin='') {

	$texto = trim(strip_tags($texto,'<br/>'));

	if (strlen($texto) > $cantidad) {
		$caracter = '__|__';
		$texto = wordwrap ($texto, $cantidad, $caracter);
		$texto = substr($texto, 0 , strpos($texto, $caracter)).$fin;
	}

	return $texto;
}

/**
 * Devuelve la URL del Gravatar para el email especificado
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @source 		http://gravatar.com/site/implement/images/php/
 * @param		string	$email	Direcci�n de email para el avatar
 * @param		string	$s		Tama�o de la imagen (por defecto 80px [ 1 - 512 ])
 * @param		string	$d		Imagen por defecto [404 | mm | identicon | monsterid | wavatar]
 * @param 		string	$r		Tipo de imagen a devolver [ g | pg | r | x ]
 * @param		boolean	$img	Si es true devuelve el HTML completo de la imagen, si es false devuelve solo la URL
 * @param		array	$atts	Opcional (utilizado si $img es true), array de datos extras a la imagen generada
 * @return		string	Url completa de la imagen o el HTML del tag IMG
 */
function getGravatar($email, $s=80, $d='mm', $r='g', $img=false, $atts=array()) {
	$url = 'http://www.gravatar.com/avatar/';
	$url.= md5(strtolower(trim($email)));
	$url.= '?s='.$s.'&d='.$d.'&r='.$r;
	if ($img) {
		$url = '<img src="'.$url.'"';
		foreach ($atts as $key => $val) {
			$url.= ' '.$key.'="'.$val.'"';
		}
		$url.= ' />';
	}
	return $url;
}

/**
 * Remueve los espacios en blanco del texto
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$text	Texto a remover espacios
 * @param		boolean	$trim	Indica si realiza la funci�n trim al texto
 * @retunr		string	Texto con los espacios en blanco removidos
 */
function removeSpaces($text, $trim=false) {
	$text = preg_replace("/\s+/", ' ', $text);

	if($trim) {
		return trim($text);
	}

	return $text;
}

/**
 * Reemplaza las comillas por caracteres UTF8
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$texto	Texto a reemplazazr las comillas
 * @return		string	Texto con las comillas reemplazadas
 */
function replaceQuot($texto='') {

	$reemplazos = array(
		chr(34) => "&quot;",
		"�' => '&quot;",
		"�' => '&quot;",
		"�' => '&#8220;",
		"�' => '&#8221;",
		"�' => '&#8216;", 
		"�' => '&#8217;"
	);

	$texto = strtr($texto, $reemplazos);
	return $texto;
}

/**
 * Recorta el texto para que entre en un bloque de columnas y filas
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$texto	Texto a comprobar si excede
 * @param		integer	$rows	Cantidad de renglones del bloque
 * @param		integer	$cols	Cantidad de caracteres por renglon
 * @param		string	$fin	Texto a incluir al final del texto cortado
 * @return		string	Texto recortado paraque entre en el bloque
 */
function textToBlock ($texto='', $rows=1, $cols=1, $fin='') {

	$txtArr = preg_split('(<br/>|<br />)', $texto);

	if (count($txtArr) > $rows) {
		$txtArr = array_slice($txtArr,0,$rows);
	}

 	$c = 0;
	$r = 0;
	foreach ($txtArr as $k => $v) {

		if ($c < $rows) {
		
			$d = floor(strlen(strip_tags($v)) / $cols);
			if (($c+$d) < $rows) {
				$c+=$d;
				$r++;
			} else {
				$d = ($rows - $c-1) * $cols;
				$c++;
				$txtArr[$k] = substr($v,0,$d);
				$r++;
			}
		}
	}

	$txtArr = array_slice($txtArr,0,$r);
	$txtFinal = implode('<br/>', $txtArr);

	if (strlen($texto) != strlen($texto_fin)) {
		$txtFinal.= $fin;
	}

	return $txtFinal;
}

/**
 * Genera url friendly del texto
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	string
 * @param		string	$texto	texto a transformar a url Friendly
 * @return		string	texto en formato url Friendly
 */
function urlFriendly($texto='') {

	// Cambio algunos caracteres que quiero dejar
	$texto = cleanAccents($texto);

	$texto = strtolower($texto);
	$texto = preg_replace("/[^a-z0-9\s-]/", '', $texto);
	$texto = trim(preg_replace("/[\s-]+/", ' ', $texto));
	$texto = preg_replace('/\s/', '-', $texto);

	/* Quito los restos de las comillas */
	$texto = str_replace('quot', '', $texto);

	return $texto;
}
?>