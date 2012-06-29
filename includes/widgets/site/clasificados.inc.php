<?php
/**
 * Recupera información de categorias de avisos clasificados
 */
class ClasificadosCategoria {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre las categorías
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Flag que indica si retorna toda la información o no
	 *
	 * @access public
	 * @var boolean
	 */
	public $extend;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'categorias';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Recupera información de la categoría
	 *
	 * @access	public
	 * @param	integer	$id
	 * @return	array	Información de la categoría consultada, false si hay error
	 */
	public function g($id=false) {
		if (!$id || !is_numeric($id)) {
			return false;
		}
		
		if (!isset($this->data[$id])) {
			return false;
		}

		return $this->data[$id];
	}
	
	/**
	 * Procesa la información y retorna arra de datos
	 *
	 * @access	public
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process() {
	
		if (!is_object($this->db)) {
			return false;
		}
		
		$oSql = 'SELECT * FROM '.$this->table.' 
			WHERE categoria_visible = 1 
			ORDER BY categoria_pertenece, categoria_orden, categoria_nombre';
		if (!$res = $this->db->query($oSql)) {
			return false;
		}
	
		if (!$this->db->num_rows($res)) {
			return false;
		}
		
		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);

			if ($this->extend) {
				$inf = $rs;
			} else {
				$inf = array(
					'categoria_id' => $rs['categoria_id'],
					'categoria_nombre' => $rs['categoria_nombre']
				);
			}
			
			$this->data[$rs['categoria_id']] = $rs;
			$im[$rs['categoria_pertenece']][] = $inf;
		}
		
		return $im;
	}
}

/**
 * Recupera información de las operaciones de los avisos
 */
class ClasificadosOperaciones {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre las operaciones
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Flag que indica si retorna toda la información o no
	 *
	 * @access public
	 * @var boolean
	 */
	public $extend;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'operaciones';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Recupera información de la o las operaciones consultadas
	 *
	 * @access	public
	 * @param	mixed	$id	Identificado/res de las operaciones a consultar 
	 *					(acepta un solo identificado, 
	 *					identificadores como cadena separada por coma o array).
	 * @return	array	Información de la operación consultada, false si hay error
	 */
	public function g($id=false) {
		if (!$id) {
			return false;
		}

		if (is_array($id)) {
			$ids = $id;
		} else {
			$ids = explode(',', $id);
		}

		$im = array();
		foreach ($ids as $k => $v) {
			if (isset($this->data[$v])) {
				$im[] = $this->data[$v];
			}
		}

		return (count($im) > 1) ? $im : $im[0];
	}
	
	/**
	 * Procesa la información y retorna arra de datos
	 *
	 * @access	public
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process() {
	
		if (!is_object($this->db)) {
			return false;
		}
		
		$oSql = 'SELECT * FROM '.$this->table;
		if (!$res = $this->db->query($oSql)) {
			return false;
		}

		if (!$this->db->num_rows($res)) {
			return false;
		}
		
		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);

			$inf = array(
				'id' => $rs['operacion_id'],
				'nombre' => $rs['operacion_nombre']
			);

			$this->data[$rs['operacion_id']] = $inf;
		}
		
		$im = $this->data;

		return $im;
	}

}

/**
 * Listas de opciones del clasificado
 */
class ClasificadosListas {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre las operaciones
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'listas';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Procesa la información y retorna array de datos de la lista consultada
	 *
	 * @access	public
	 * @param	integet	$id	Identificador de la lista
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process($id=false) {
	
		if (!is_object($this->db)) {
			return false;
		}

		if ($id === false || !is_numeric($id)) {
			return false;
		}
		
		if (empty($this->data[$id])) {
		
			$oSql = 'SELECT * FROM '.$this->table.' 
				WHERE lista_pertenece = '.$id.' 
				ORDER BY lista_orden, lista_nombre';

			if (!$res = $this->db->query($oSql)) {
				return false;
			}

			if (!$this->db->num_rows($res)) {
				return false;
			}
			
			$im = array();
			for ($i=0; $i<$this->db->num_rows($res); $i++) {
				$rs = $this->db->next($res);

				$inf = array(
					'id' => $rs['lista_id'],
					'nombre' => $rs['lista_nombre']
				);

				$this->data[$id][] = $inf;
			}
		}
		
		$im = $this->data[$id];

		return $im;
	}	
	
}

/**
 * Listas de monedas del clasificado
 */
class ClasificadosMonedas {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre las operaciones
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'monedas';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Procesa la información y retorna array de datos de la lista consultada
	 *
	 * @access	public
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process() {
	
		if (!is_object($this->db)) {
			return false;
		}

		// Si ya lo cargó no levanto info de la dbase
		if (count($this->data)) {
			return $this->data;
		}
		
		$oSql = 'SELECT * FROM '.$this->table.' 
			ORDER BY moneda_valor, moneda_simbolo';

		if (!$res = $this->db->query($oSql)) {
			return false;
		}

		if (!$this->db->num_rows($res)) {
			return false;
		}
		
		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);

			$inf = array(
				'id' => $rs['moneda_id'],
				'nombre' => $rs['moneda_simbolo']
			);

			$this->data[] = $inf;
		}

		$im = $this->data;

		return $im;
	}	
	
}

/**
 * Listas duraciones del clasificado
 */
class ClasificadosDuracion {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre las duraciones
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'duracion';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Procesa la información y retorna array de datos de la lista consultada
	 *
	 * @access	public
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process() {
	
		if (!is_object($this->db)) {
			return false;
		}

		// Si ya lo cargó no levanto info de la dbase
		if (count($this->data)) {
			return $this->data;
		}
		
		$oSql = 'SELECT * FROM '.$this->table.' 
			ORDER BY duracion_dias';

		if (!$res = $this->db->query($oSql)) {
			return false;
		}

		if (!$this->db->num_rows($res)) {
			return false;
		}
		
		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);

			$inf = array(
				'id' => $rs['duracion_dias'],
				'nombre' => $rs['duracion_nombre']
			);

			$this->data[] = $inf;
		}

		$im = $this->data;

		return $im;
	}
}

/**
 * Listas métodos de exposición del clasificado
 */
class ClasificadosExposicion {

	/**
	 * Objeto de Cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Guarda toda la información sobre los métodos de exposición
	 *
	 * @access private
	 * @var array
	 */
	private $data;

	/**
	 * Conecto de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Tabla de datos a consultar
	 *
	 * @access public
	 * @var string
	 */
	public $table;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
		$this->table = 'destacados';
		$this->cache = false;
		$this->extend = true;
		$this->data = array();
	}

	/**
	 * Procesa la información y retorna array de datos de la lista consultada
	 *
	 * @access	public
	 * @return	array	Array de datos, o false en caso de error
	 */
	public function process() {
	
		if (!is_object($this->db)) {
			return false;
		}

		// Si ya lo cargó no levanto info de la dbase
		if (count($this->data)) {
			return $this->data;
		}
		
		$oSql = 'SELECT * FROM '.$this->table.' 
			ORDER BY destacado_orden';

		if (!$res = $this->db->query($oSql)) {
			return false;
		}

		if (!$this->db->num_rows($res)) {
			return false;
		}
		
		$im = array();
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);
			
			$rst = array();
			foreach ($rs as $k => $v) {
				$k = str_replace('destacado_','',$k);
				$rst[$k] = $v;
			}
			$inf = $rst;

			$this->data[] = $inf;
		}

		$im = $this->data;

		return $im;
	}

}
?>