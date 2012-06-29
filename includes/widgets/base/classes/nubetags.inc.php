<?php
/**
 * Genera array correspondiente a la nube de tags a mostrar
 * 
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.</li> 
 */
class NubeTags extends CubePHP {

	/**
	 * Cantidad de resultados a retornar
	 * 0: retorna cero resultados, -1 retorna todos
	 *
	 * @access public
	 * @var integer
	 */
	public $cantidad;
	
	/**
	 * Cantidad de resultados a omitir (utilizado en la páginación de datos)
	 *
	 * @access public
	 * @var integer
	 */
	public $desplazamiento;	

	/**
	 * Indica el tipo de artículo a devolver
	 * Puede ser un solo número o números separados por coma
	 *
	 * @access public
	 * @integer|string
	 */
	public $itemTipo;

	/** 
	 * Indica el tipo de sección de artículo a devolver
	 * Puede ser un solo número, o un string de secciones separados por coma
	 *
	 * @access public
	 * @var string
	 */
	public $itemSeccion;

	/**
	 * Cantidad de días para recolectar los Tags
	 *
	 * Indica cuantos días para atras busco los tags en las notas
	 * Por defecto son 30 días de la fecha de inicio
	 *
	 * @access public
	 * @var integer
	 */
	public $itemFechaRango;
	
	/** 
	 * Desde que fecha se empieza a buscar los tags
	 *
	 * Indica desde que fecha es la nota donde el sistema empieza a buscar etiquetas 
	 * La fecha debe ingresarse en formato TIMESTAMP
	 * Por defecto es la fecha actual
	 *
	 * @access public
	 * @var integer
	 */
	public $itemFechaInicio;
	
	/**
	 * Orden de los resultados
	 * 
	 * @access public
	 * @var string
	 */
	public $orden;
	
	/**
	 * Peso máximo del tags.
	 *
	 * Cada etiqueta tiene un peso respecto de las apariciones devueltas.
	 * Esta variable me indica que número debe asignarse a la etiqueta con mayores apariciones (maximo peso)
	 * El resto de las apariciones se realiza por proporcionalidad
	 *
	 * Por defecto, el peso máximo esta definido en 10
	 *
	 * @access public
	 * @var integer
	 */
	public $itemMaxPeso;
	
	/**
	 * Constructor de la clase
	 * Defino los parametros por defecto de la clase
	 *
	 * @access public
	 * @param integer $tags cantidad de tags a devolver
	 * @param string $tipo lista de tipos de notas a devolver tags
	 * @param string $seccion lista de secciones de notas a devolver tags
	 */
	public function __construct($tags=0, $tipo=false, $seccion=false) {
	
		$this->_name = "Nube de Etiquetas";
		$this->_version = "1.01";

		$this->init();
		
		$this->itemTipo = $tipo;
		$this->itemSeccion = $seccion;

		if ($tags == -1) {
			$this->cantidad = 0;
		} elseif ($tags > 0) {
			$this->cantidad = $tags;
		}
	}
	
	/**
	 * Destructor de la clase
	 *
	 * @access	public
	 */
	public function __destruct() {

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
		$this->_errorArray[1] = 'No se puede conectar con la base de datos';
		$this->_errorArray[2] = 'No hay parametros de filtro definido';		
		
		$this->errorNro=0;
		$this->errorInfo='';
		
		$this->tablePrefix = '';
		$this->_table = '';
		$this->cantidad = 20;
		$this->desplazamiento = 0;
		$this->orden = 'tag_nombre';
		$this->totalResultados = 0;

		$this->itemTipo = 0;
		$this->itemSeccion = 0;
		$this->itemFechaRango = 30;	// últimos 30 días.
		$this->itemFechaInicio = time(); // Fecha actual
		$this->itemMaxPeso = 10; // Peso máximo de las etiquetas
	}
	
	/**
	 * Proceso los datos ingresados y genero el array con los parametros
	 *
	 * Si se llama este método con el parametro $urlto, entrega el control a este archivo 
	 * y pasa los parametros vía variable $dataToSkin, sino retorna el array con los datos
	 *
	 * @access public
	 * @return array en caso de que no se haya definido el parametro urlto devuelve el array con los datos
	 */
	public function process() {
		
		// Revisión inicial
		if (!$this->checkInit()) {
			return false;
		}

		$sqlWhere = array();
		$sqlOrden = "";
		$sqlLimit = "";
		
		// Cargo los filtros
		$sqlWhere = $this->checkFilters();

		if (!count($sqlWhere)) {
			$this->log.= "- <b>Error:</b> No existe ningún condicional (WHERE) definido<br/>";
		
			$this->error(2);
			return false;
		}
		$sqlWhereFinal = implode(' AND ', $sqlWhere);

		if ($this->orden != '') {
			$sqlOrden = "ORDER BY ".$this->orden;
		}

		if ($this->cantidad || $this->desplazamiento) {
			$sqlLimit = "LIMIT {$this->desplazamiento}";
			$sqlLimit.= ($this->cantidad) ? ','.$this->cantidad : '';
		}
		

		$oSql = "SELECT count(tag_id) as total, tag_nombre as nombre 
			FROM {$this->_table}noticias_tags, {$this->_table}noticias_galeria_tags, {$this->_table}noticias
			WHERE {$sqlWhereFinal}
			GROUP BY tag_id
			{$sqlOrden}
			{$sqlLimit}";

		$this->log.= "- Consulta SQL:{$oSql}<br/>";				
		if (!$res = $this->db->query($oSql)) {
			$this->log.= "- Error al ejecutar la consulta.<br/>";
			$this->log.= "- El sistema dice:".$this->db->error()."</br>";
		
			$this->error(5);
			return false;
		}

		// Recolecto todas las etiquetas
		$this->totalResultados = $this->db->num_rows($res);
		
		$etiquetas = array();
		$maxValue = 0;
		$minValue = 0;
		$diferencia = 0;
		$aTag = array();
		
		if ($this->totalResultados) {
		
			for ($i=0; $i<$this->totalResultados; $i++) {

				$rs = $this->db->next($res);

				if (!$i) {
					$minValue = $rs['total'];
				}
				$etiquetas[$rs['nombre']] = $rs['total'];
				$maxValue = ($rs['total'] > $maxValue) ? $rs['total'] : $maxValue;
				$minValue = ($rs['total'] < $minValue) ? $rs['total'] : $minValue;
			}
			
			$diferencia = $maxValue - $minValue;

			foreach ($etiquetas as $nombre => $cantidad) {
			
				$peso = ($cantidad - $minValue) ? (ceil((($cantidad - $minValue) / $diferencia) * $this->itemMaxPeso)) : 0;
				$peso = (!$peso) ? 1 : $peso;

				$aTag[] = array(
					'tag' => $nombre,
					'cantidad' => $cantidad,
					'peso' => $peso
				);
			}
		}

		return $aTag;

	}

	/**
	 * Revisa todos los filtros, retorna array de comandos a procesar
	 *
	 * @access	protected
	 * @return	array	Matriz de datos a procesar
	 */
	protected function checkFilters() {

		$this->log.= "- <b>Inicializando filtros para listado</b> <br/>";

		$sql = array();

		// Cargo los filtros básicos
		$sql[] = 'adjunto_tag_id = tag_id';
		$sql[] = 'adjunto_noticia_id = noticia_id';
		$sql[] = 'tag_tipo = 1';	// Solo nube de tags de artículos		
		$sql[] = 'noticia_estado = 1';

		if ($this->itemTipo) {
			if ($oSql = $this->filterNumeric($this->itemTipo, "tipo", "noticia_tipo")) {
				$sql[] = $oSql;
			}
		}
		
		if ($this->itemSeccion) {
			if ($oSql = $this->filterNumeric($this->itemSeccion, "seccion", "noticia_seccion_id")) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemFechaInicio 
			&& is_numeric($this->itemFechaInicio) 
			&& $this->itemFechaRango 
			&& is_numeric($this->itemFechaRango)) {
		
			$inicio = $this->itemFechaInicio - (60*60*24*$this->itemFechaRango);
			$sql[] = 'noticia_fecha_modificacion > '.$inicio;
		}
		
		$this->log.= "- Asignación de filtros OK <br/>";

		return $sql;		
	}
}
?>