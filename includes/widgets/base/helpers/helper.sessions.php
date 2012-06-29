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
 *	- Creación de Helpers Time.</li>
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
 * Guarda o retorna valores de una session
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	sessions
 * @param		string		$session	Nombre de la session a consultar o asignar
 * @param		string		$value		Valor a asignar a la session
 * @return		mixed		True en caso de asignación exitosa, o valor de la session en caso de consulta
 */
function SESSION($session='', $value=false) {
	if ($session == '') {
		return false;
	}
	
	if(!$value) {
		if(isset($_SESSION[$session])) {
			return filter($_SESSION[$session]);
		} else {
			return false;
		}
	} else {
		$_SESSION[$session] = $value;
	}
	
	return true;
}
?>