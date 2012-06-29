<?php
/**
 * clase para objetizar un array
 */

class ToObj {

	/**
	 * Array con los valores a consultar
	 *
	 * @access private
	 * @var array
	 */
	private $arr;
	
	public function __construct($data=false) {

		if (!$data) {
			$data = array();
		}
		
		$this->arr = $data;
	}
	
	/**
	 * Recupera información del array o el valor por defecto
	 *
	 * @access	public
	 * @param	string	$clave	Clave a consultar
	 * @param	string	$valor	Valor por defecto si la clave no existe
	 * @return	mixed	Valor de la variable o el valor por defecto ($val)
	 */
	public function g($clave='', $valor='') {
		
		return isset($this->arr[$clave]) ? $this->arr[$clave] : $valor;

	}
}
?>