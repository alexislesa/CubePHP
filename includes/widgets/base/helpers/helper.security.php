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
 *	- Creación de Helpers Security.</li>
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
 * Filtra datos de XSS ingresados
 * Extraido desde: http://stackoverflow.com/questions/1336776/xss-filtering-function-in-php
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	security
 * @param		string		$data	Valor a revisar
 * @return		string		Retorna el valor del campo filtrado de xss
 */
function xssClean($data) {
	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do {
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	} while ($old_data !== $data);

	// we are done...
	return $data;
}

/**
 * Limpia datos de XSS ingresados
 * Extraido desde: http://snipplr.com/view/8418/
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	security
 * @param		string		$data	Valor a revisar
 * @return		string		Retorna el valor del campo filtrado de xss
 */
function xssExtract($val) {
	$val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);
	$search = 'abcdefghijklmnopqrstuvwxyz';
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$search .= '1234567890!@#$%^&*()';
	$search .= '~`";:?+/={}[]-_|\'\\';
	
	for ($i = 0; $i < strlen($search); $i++) {
		$val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
		$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
	}
	
	$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml',
		'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 
		'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		
	$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 
		'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 
		'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 
		'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 
		'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 
		'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 
		'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 
		'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 
		'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 
		'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 
		'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 
		'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 
		'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 
		'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 
		'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 
		'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 
		'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
	
	$ra = array_merge($ra1, $ra2);
	$found = true;
	
	while ($found == true) {
		$val_before = $val;
		for ($i = 0; $i < sizeof($ra); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($ra[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
					$pattern .= '|(&#0{0,8}([9][10][13]);?)?';
					$pattern .= ')?';
				}
				
				$pattern .= $ra[$i][$j];
			}
	
			$pattern.= '/i';
			$replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2);
			$val = preg_replace($pattern, $replacement, $val);
			
			if ($val_before == $val) {
				$found = false;
			}
		}
	}

	return $val;
}
?>