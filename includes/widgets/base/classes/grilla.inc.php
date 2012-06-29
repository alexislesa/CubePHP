<?php
/**
 * Clase para el manejo grilla de información
 *
 * <b>Que hace?</b>
 * Recupera infocmación de una grilla de datos
 *
 * <b>Cómo se usa:</b> <br>
 * Ejemplo como devolver grilla por id
 * <code>
 * $grillaId = 20;
 * $gd = New grilla();
 * $data = $gd->process($grillaId);
 * </code>
 *
 * Ejemplo como devolver grilla por clave y valor
 * <code>
 * $grillaKey = 'campo_1';
 * $grillaVal = 'caratura';	// valor que debe buscar en el campo
 * $gd = New grilla();
 * $data = $gd->process('', $grillaKey, $grillaVal);
 * </code> 
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.<br/>
 *	- Modify: Se optimizó la clase y se actualizó la documentación. </li>
 *
 * <li>13.02.2012 <br/>
 *	- Se optimizó la clase y se hizo extensiva de la clase abstracta CubePHP <br/>
 * </li>
* <li>17.11.2010 <br/>
 *	- <br/>
 * </li> 
 * </ul>
 *
 * @package		Core
 * @category	Grilla
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	17.11.2010
 * @version		1.0	- last revision 2010.11.17
 */
class Grilla extends CubePHP {


	public function __construct() {
	
		$this->_name = 'Grilla';
		$this->_version = '1.01';

		$this->init();
	}
	
	/**
	 * Inicializa todas las opciones básicas de la clase
	 *
	 * @access	private
	 */
	private function init() {

		$this->_init();	

		// Listado de posibles errores
		$this->_errorArray[1] = 'No se puede conectar con la base de datos';
		$this->_errorArray[2] = 'No hay parametros de filtro definido';
		$this->_errorArray[3] = 'No se ha ingresado el ID del artículo';
		$this->_errorArray[4] = 'El ID del artículo no existe o es incorrecto';
		$this->_errorArray[5] = 'Error en la consulta. No se puede retornar ningún resultado';
	}
	
	/**
	 * Muestra la grilla según los parametros definidos
	 *
	 * @access	public
	 * @param	integer	$id		Identificador del campo id a mostrar
	 * @param	string	$campo	Tipo de campo a consultar coincidencias
	 * @param	string	$valor	Valor a filtrar en campo
	 * @return	array	Datos de la grilla devueltos, o false si no se encontraron resultados o hay error
	 */
	public function process($id=false, $campo='', $valor='') {
	
		// Revisión inicial
		if (!$this->checkInit()) {
			return false;
		}

		$sqlWhere = array();
		$sqlOrden = '';
		$sqlLimit = '';

		if ($id && is_numeric($id)) {
			$sqlWhere[] = "campo_id = '{$id}'";
		}
		
		if ($campo != '' && $valor != '') {
			$sqlWhere[] = "{$campo} = '{$valor}'";
		}
		
		if (!count($sqlWhere)) {
			$this->log.= '- <b>Error:</b> No existe ningún condicional (WHERE) definido<br/>';
		
			$this->error(2);
			return false;
		}
		$sqlWhereFinal = implode(' AND ', $sqlWhere);

		$oSql = 'SELECT * FROM grilla '.$sqlWhereFinal.' ORDER BY campo_id';
		
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;
		}

		if (!$this->db->num_rows($res)) {
			return false;
		}

		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);
			$im[$rs['campo_id']] = $rs;
		}

		return $im;		
	}
}
?>