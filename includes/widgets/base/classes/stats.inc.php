<?php
/**
 * Carga las estadisticas para la categoría seleccionada
 * 
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.</li> 
 */
class Stats extends CubePHP {

	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {

		$this->_name = "Stats";
		$this->_version = "1.01";

		$this->init();
	}
	
	/**
	 * Inicializa todas las opciones básicas de la clase
	 *
	 * @access	private
	 */
	private function init() {

		$this->log = "<b>Información de actividad de la clase</b><br/>";
		$this->log.= "- Clase:		".$this->_name."<br/>";
		$this->log.= "- Versión:	".$this->_version."<br/>";
		$this->log.= "- Fecha:		".date("d.m.Y G:i:s")." <br/>";

		// Listado de posibles errores
		$this->_errorArray = array();
		$this->_errorArray[1] = "No se puede conectar con la base de datos";
		$this->_errorArray[2] = "No hay parametros de filtro definido";
		$this->_errorArray[3] = "No se ha ingresado el ID del artículo";
		$this->_errorArray[4] = "El ID del artículo no existe o es incorrecto";
		$this->_errorArray[5] = "Error en la consulta. No se puede retornar ningún resultado";

		$this->errorNro=0;
		$this->errorInfo='';
		
		$this->tablePrefix = '';
		$this->_table = '';
	}	

	/**
	 * Proceso los datos ingresados y cargo la estadistica del tipo seleccionado
	 *
	 * @access	public
	 * @param	$tipo	integer	Identificador del tipo de estadísticas a cargar
	 * @param	$extra	string	Si es un número, indica que es un art., si es texto indica que es un parametro del buscador
	 * @param	$user	integer	Indica que el usuario esta logeado, false en caso contrario
	 * @return	boolean	True si la estadistica se cargó correctamente, false en caso contrario
	 */
	public function process($tipo=false, $extra='', $user=false) {
		
		$this->log.= "- Inicio proceso de paraguardar info estadística. <br/>";

		// Revisión inicial
		if (!$this->checkInit()) {
			return false;
		}
		
		if (!$tipo) {
			$this->log.= "- Error, el tipo no esta definido. <br/>";
			$this->error(2);
			return false;
		}

		if ($extra != '' && is_string($extra)) {

			$this->log.= "- Se procede a guardar información de búsqueda. <br/>";
			$this->log.= "- Palabra ingresada:{$extra} <br/>";
			
			$extra = cleanInjection($extra);

			if ($extra == '') {
				$this->error(2);
				return false;			
			}
			$extra = strtolower($extra);
			
			$oSql = "SELECT * FROM {$this->_table}stats_search 
				WHERE stats_search_palabra = '{$extra}'";

			$this->log.= "- Consultando si la palabra ya esta ingresada <br/>";
			$this->log.= "- Consulta SQL:{$oSql}<br/>";
			if (!$res = $this->db->query($oSql)) {
				$this->log.= "- Error al ejecutar la consulta.<br/>";
				$this->log.= "- El sistema dice:".$this->db->error()."</br>";
			
				$this->error(5);
				return false;
			}
			
			if ($this->db->num_rows($res)) {
			
				$rs = $this->db->next($res);
				$extra = $rs["stats_search_id"];
				
				$this->log.= "- La palabra ya existía base de datos.<br/>";
				$this->log.= "- Id de la palabra:{$extra} <br/>";

			} else {
			
				$this->log.= "- La palabra no se encontraba en la dbase. <br/>";
				$this->log.= "- Se procede a guardarla. <br/>";
				
				$oSql = "INSERT DELAYED INTO {$this->_table}stats_search (stats_search_palabra) VALUES ('{$extra}')";
				
				$this->log.= "- Consulta SQL:{$oSql}<br/>";
				if (!$res = $this->db->query($oSql)) {
					$this->log.= "- Error al ejecutar la consulta.<br/>";
					$this->log.= "- El sistema dice:".$this->db->error()."</br>";
				
					$this->error(5);
					return false;
				}				
				$extra = $this->db->last_insert_id();
			}
		}

		$user = ($user && is_numeric($user)) ? $user : 0;
		$fecha = mktime(0,0,0,date('m'), date('d'), date('Y')); //dia sin importar la hora

		$oSql = "INSERT DELAYED INTO stats_site 
			(stats_site_fecha, stats_site_tipo, stats_site_id, stats_site_user_id, stats_total)
			VALUES ('{$fecha}', '{$tipo}', '{$extra}', '{$user}', 1)
		ON DUPLICATE KEY UPDATE stats_total = (stats_total + 1)";

		$this->log.= "- Consulta SQL:{$oSql}<br/>";
		if (!$res = $this->db->query($oSql)) {
			$this->log.= "- Error al ejecutar la consulta.<br/>";
			$this->log.= "- El sistema dice:".$this->db->error()."</br>";
		
			$this->error(1);
			return false;
		}
		
		$this->log.= "- Información estadística ingresada con éxito <br/>";
		return true;
	}
}
?>