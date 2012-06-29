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
 *	- Creación de Helpers Files.</li>
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
 * Convierte el peso en byte a peso en formato humano
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	files
 * @param		integer	$peso	Peso en byte a calcular
 * @param		integer	$dec	Decimales a devolver (por defecto 2 decimales)
 * @return		string	Valor pasador a formato humano (ej: 2.34 MB)
 */
function getFileSize($peso=0, $dec=2) {
 
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = $peso;
	$pow = floor(($peso ? log($peso) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);
	$txt = round($bytes, $dec).' '.$units[$pow];
	return $txt;
}
?>