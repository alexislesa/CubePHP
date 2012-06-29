<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualizó la documentación de las funciones. </li>
 *
 * <li>21.01.2012 <br/>
 *	- Creación de Helpers Validations.</li>
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
 * Valida si un texto contiene comandos de injection
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	validations
 * @param		string		$text	Texto a validar
 * @return		boolean		True si contiene comandos XSS, false en caso contrario
 */
function isInjection($text='') {
	if(is_string($text) && $text != '') {
		$text = html_entity_decode($text);
		$text = ' '.$text;

		if(strpos($text, '<script') 
			|| strpos($text, '<iframe') 
			|| strpos($text, '<img')) {
			
			return true;
		}	
	}
	
	return false;
}

/**
 * Valida que el valor ingresado sea un número
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	validations
 * @param		string		$campo		Valor del campo a revisar
 * @param		string		$default	Valor por defecto si no es numérico
 * @return		integer		Retorna el valor del campo si es número, 
 *							o el valor por defecto ingresado
 */
function isNumber($campo, $default=false) {
	$ret = !empty($campo) ? (is_numeric($campo) ? $campo : $default) : $default;
	return $ret;
}

/**
 * Valida que el valor ingresado sea distinto de vacio
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	validations
 * @param		string		$campo		Valor del campo a revisar
 * @param		string		$default	Valor por defecto si no es vacio
 * @return		integer		Retorna el valor del campo si es valido, 
 *							o el valor por defecto ingresado
 */
function isNotEmpty($campo, $default=false) {
	$ret = !empty($campo) ? ((trim($campo)!='') ? $campo : $default) : $default;
	return $ret;
}

/**
 * Valida si un texto contiene SPAM
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	validations
 * @param		string		$string	Texto a validar
 * @param		integer		$max	Cantidad máxima de indicadores a consultar para indicar que es SPAM
 * @return		boolean		True si contiene SPAM, false en caso contrario
 */
function isSPAM($string='', $max=2) {
	$words = array(	
		'http', 'www', '.com', '.mx', '.org', '.net', '.co.uk', '.jp',
		'.ch', '.info', '.me', '.mobi', '.us', '.biz', '.ca', '.ws', '.ag', 
		'.com.co', '.net.co', '.com.ag', '.net.ag', '.it', '.fr', '.tv', '.am',
		'.asia', '.at', '.be', '.cc', '.de', '.es', '.com.es', '.eu', 
		'.fm', '.in', '.tk', '.com.mx', '.nl', '.nu', '.tw', '.vg', 'sex',
		'porn', 'fuck', 'buy', 'free', 'dating', 'viagra', 'money', 'dollars', 
		'payment', 'website', 'games', 'toys', 'poker', 'cheap'
	);
    $count = 0;
    
    $string = strtolower($string);
	foreach($words as $word) {
		$count += substr_count($string, $word);
	}

	return ($count > $max) ? TRUE : FALSE;
}

?>