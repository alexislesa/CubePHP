<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo agil creado por Advertis Web Technology 
 * para implementación de aplicaciones en portales de noticias.
 *
 * Clase abstracta conteniendo funciones más utilizadas en todos los ámbitos
 * 
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL
 *
 * <b>Changelog</b> <br/>
 *  
 * <ul>
 * <li>04.06.2012 <br/>
 *	- Modify: Se modificó la forma en la que se realiza el log de la actividad 
 *	de la clase. Ahora se realiza a través de una nueva función logs.</li>
 *
 * <li>20.04.2012 <br/>
 *	- Added: Se agregó que por defecto el cro maneje los mensajes de error. <br/>
 *	- Modify: Se optimizaron algunas funciones de la clase core. </li>
 *
 * <li>10.04.2012 <br/>
 *	- Added: Se agregó función checkInit() para chequeo inicial.<br/>
 * 	- Modify: Se optimizó la clase y se agregó documentación. </li>
 *
 * <li>20.03.2012 <br/>
 *	- Fix: Se modificó la consulta previa que se realiza para identificar 
 *	la ubicación a fin de evitar XSS injection.</li>
 *
 * <li>19.03.2012 <br/>
 *	- Modify: Se optimizó la cargar inicial de configuraciónes 
 *	básicas [function init()]</li>
 *
 * <li>29.02.2012 <br/>
 *	- Fix: Se reparó error mínimo al recuperar información 
 *	de las etiquetas del item <br/>
 *	- Fix: Se reparó error al intentar recuperar información de un item 
 *	y este no devolvía ningún dato</li>
 *</ul>
 *  
 * @abstract
 * @package		Core
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	21.01.2012
 * @version		1.0	- last revision 2012.01.21
 */
abstract class CubePHP {

	/**
	 * Flag que indica que esta activo el cache
	 *
	 * @access protected
	 * @var boolean
	 */
	protected $_cacheOK;

	/**
	 * Listado de mensajes de error de la clase
	 *
	 * @access protected
	 * @var array
	 */
	protected $_errorArray;
	
	/**
	 * Nombre de la clase
	 *
	 * @access protected
	 * @var string
	 */
	protected $_name;
	
	/**
	 * Array de objetos adjuntos
	 *
	 * @access protected
	 * @var array
	 */
	protected $_objeto;
	
	/**
	 * Array de datos a estatificar
	 *
	 * @access protected
	 * @var array
	 */
	protected $_statsArr;
	
	/**
	 * Prefijo completo de tabla a utilizar
	 *
	 * @access protected
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Define el tipo de item
	 * Información utilizada para saber como proceder según el tipo
	 *
	 * <pre>
	 * Tipos:
	 * - 1: artículo
	 * - 2: adjunto
	 * - 3: encuesta
	 * - 4: publicidad
	 * - 5: comentarios
	 * </pre>
	 *
	 * @access private
	 * @var integer
	 */
	protected $_tipo;

	/**
	 * Versión de la clase
	 *
	 * @access private
	 * @var string
	 */
	protected $_version;

	/**
	 * Objeto del manejo de cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
	/**
	 * Tiempo de expiración en segundos 
	 *
	 * @access public
	 * @var integer
	 */
	public $cacheExpire;
	
	/**
	 * Cantidad de resultados a retornar
	 *
	 * @access public
	 * @var integer
	 */
	public $cantidad;
	
	/**
	 * Objeto de manejo de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;

	/**
	 * Cantidad de resultados a omitir (utilizado en la páginación de datos)
	 *
	 * @access public
	 * @var integer
	 */
	public $desplazamiento;
	
	/**
	 * Mensaje de último error generado
	 *
	 * @access public
	 * @var string
	 */
	public $errorInfo;

	/**
	 * Número de mensaje de error generado
	 * En caso de que todo este correcto, retorna 0 (cero)
	 * 
	 * @access public
	 * @var integer
	 */
	public $errorNro;

	/**
	 * Registro de log de la clase
	 *
	 * @access public
	 * @var string
	 */
	public $log;
	
	/**
	 * Orden de los resultados
	 * 
	 * @access public
	 * @var string
	 */
	public $orden;	
	
	/**
	 * Indica si recupera los objetos reversos del item
	 *
	 * @access public
	 * @var boolean
	 */
	public $reverse;
	
	/**
	 * Tabla nexo entre items y objetos
	 *
	 * @access public
	 * @var string
	 */
	public $tableMultimedia;
	
	/**
	 * Prefijo de tabla
	 *
	 * @access public
	 * @var string
	 */
	public $tablePrefix;
	
	/**
	 * Total de páginas de resultados
	 * Dato que resulta de la cantidad a paginar
	 *
	 * @access public
	 * @var integer
	 */
	public $totalPaginas;

	/** 
	 * Total de resultados obtenidos
	 * 
	 * @access public
	 * @var integer
	 */
	public $totalResultados;	
	
	/**
	 * Guarda información de las ubicaciones del item
	 *
	 * @access private
	 * @var array
	 */
	private $ubicacionArr;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	private
	 */
	private function __construct() {

	}
	
	/**
	 * Destructor de la clase
	 *
	 * @access	private
	 */
	private function __destruct() {

		// Destruyo las variables que no se utilicen
		unset($this->log);
	}
	
	/**
	 * Carga las configuraciones básicas
	 *
	 * @access	protected
	 */
	protected function _init() {

		$this->logs('<b>Información de actividad de la clase</b>');
		$this->logs('- Clase:		'.$this->_name);
		$this->logs('- Versión:	'.$this->_version);
		$this->logs('- Fecha:		'.date('d.m.Y G:i:s'));

		// Cache
		$this->_cacheOK = false;

		// Mensajes de Error
		$this->_errorArray = array();
		$this->_errorArray[1] = 'No se puede conectar con la base de datos';
		$this->_errorArray[2] = 'No hay parametros de filtro definido';
		$this->_errorArray[3] = 'No se ha ingresado el ID del item';
		$this->_errorArray[4] = 'El ID del item no existe o es incorrecto';
		$this->_errorArray[5] = 'Error en la consulta. No se puede retornar ningún resultado';
		
		$this->errorNro = 0;
		$this->errorInfo = '';

		// Información de tabla y consultas
		$this->tablePrefix = '';
		$this->_table = '';
		$this->cantidad = 0;
		$this->desplazamiento = 0;
		$this->orden = '';

		// Resultados
		$this->totalPaginas = 0;
		$this->totalResultados = 0;

		// Estadísticas / objetos y ubicaciones
		$this->_statsArr = array();
		$this->reverse = false;
		$this->tableMultimedia = '';	// Tabla de conexión art / objeto
		$this->ubicacionArr = false;
	}

	/**
	 * Realiza las revisiones iniciales de la clase
	 *
	 * @access	protected
	 * @return	boolean		True si la revisión se realizó exitosamente. 
	 */
	protected function checkInit() {

		// Reviso si tiene declarado el obj de base de datos
		$this->logs('- Consultando conexión a base de datos');
		if (!is_object($this->db)) {
			$this->error(1);
			return false;
		}
		$this->logs('- Conexión a base de datos: OK ');

		// Reviso si tiene Cache
		$this->logs('- Consultando si posee Cache ');
		if (is_object($this->cache)) {
			$this->_cacheOK = true;

			$this->logs('- Cache:  Activo ');
		} else {
			$this->logs('- Cache: Inactivo ');
		}
		
		if ($this->tablePrefix != '') {
			$this->logs('- Prefijo de tabla a utilizar: '.$this->tablePrefix);
			$this->_table = $this->tablePrefix.'_';
		}

		return true;
	}	

	/**
	 * Procesa los errores de la clase
	 * 
	 * @access	protected
	 * @param	mixed	$id		Identificador del número de error (integer) 
	 *							o string para que me carge el texto del error
	 * @param	string	$texto	Opcional, texto a reemplazar 
	 *							en el mensaje de error. Valor a reemplazar: [x]
	 */
	protected function error($id=0, $texto=false) {

		$this->errorNro = 0;

		if (is_string($id)) {

			$this->errorInfo = $id;

		} else {
		
			$this->errorInfo = !empty($this->_errorArray[$id]) 
							? $this->_errorArray[$id] 
							: 'Error inesperado';
			
			$this->errorNro = !empty($this->_errorArray[$id]) ? $id : 0;
		}
		
		if ($texto) {
			$this->errorInfo = str_replace('[x]', $texto, $this->errorInfo);
		}

		$this->logs('- Error: '.$this->errorInfo);
	}

	/**
	 * Revisa que el dato ingresado este correcto y retorna el SQL correspondiente
	 *
	 * @access	protected
	 * @param	mixed	$data	Valor a revisar para el filtro
	 * @param	string	$name	Nombre del filtro
	 * @param	string	$field	Nombre del campo SQL a generar
	 * @return	string	Comando SQL o false en caso de error
	 */
	protected function filterNumeric($data, $name, $field) {
	
		$this->logs('- Filtro asignado: <b>'.$name.'</b>.');
		$this->logs('Valor ingresado:'.$data);
		$cat = false;

		if (is_numeric($data)) {

			$cat = true;
			$sql = $field.' = \''.$data.'\'';

		} else {

			/* patrón de filtro test: 1212,212,2121 , 254, 54 */
			/* @todo luego corregir esto que no funciona
			$patron = "^([0-9]+),([0-9]+)(,([0-9]+))*s";
			$texto = trim(str_replace(" ", "",$data));
			if (preg_match($patron, $texto, $match)){ 

				$sql = $field." IN({$texto})";
				$cat = true;
			}
			*/
			$texto = trim(str_replace(' ', '',$data));
			
			$a = explode(',', $texto);
			$texto = '';
			foreach($a as $k => $v) {
				$texto.= is_numeric($v) ? ','.$v : '';
			}
			$texto = substr($texto,1);
			if ($texto != '') {
				$sql = $field.' IN('.$texto.')';
				$cat = true;
			}

		}

		if ($cat == false) {
			$this->logs('- Error en Filtro <b>'.$name.'</b>: El dato ingresado es incorrecto.');
			return false;
		}
		
		$this->logs('- Filtro ingresado OK.');
		return $sql;
	}

	/**
	 * Recupera los adjuntos del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadAdjuntos($im, $ids='', $idarr=false) {

		if ($ids != '' && isset($this->_objeto['adjunto'])) {
			$obj = $this->_objeto['adjunto'];
			return $this->loadItems($im, $ids, $idarr, $this->_tipo, $obj, 2);
		}

		return $im;
	}
	
	/**
	 * Recupera los adjuntos reversos del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadReverseAdjuntos($im, $ids='', $idarr=false) {

		if ($ids != '' && isset($this->_objeto['adjunto'])) {
			$obj = $this->_objeto['adjunto'];
			return $this->loadReverseItems($im, $ids, $idarr, $this->_tipo, $obj, 2);
		}

		return $im;
	}	

	/**
	 * Carga array de errores para utilizar en la clase
	 *
	 * @access	public
	 * @param	integer	$key	Clave (número) del error 
	 * @param	string	$value	Mensaje de error a mostrar para la clave
	 * @return	boolean	True si se ingresó con éxito, false en caso contrario
	 */
	public function loadError($key='', $value='') {

		if ($key != '' && $value != '') {
			$this->_errorArray[$key] = $value;
			return true;
		}

		return false;
	}

	/**
	 * Recupera los comentarios del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con comentarios
	 */
	protected function loadComentarios($im, $ids='', $idarr=false) {

		if ($ids == '' || !isset($this->_objeto['comentario'])) {
			return $im;
		}

		$obj = $this->_objeto['comentario'];
		$obj->itemTipo = $this->_tipo;
		$obj->itemAsocId = $ids;
		$dataObj = $obj->process();

		$this->logs('------------------------------- ');
		$this->logs('--- Información del log de adjunto --- ');
		$this->logs($obj->log);
		$this->logs('--- Fin log de adjunto --- ');
		$this->logs('------------------------------- ');

		// Mezclo la información de los adjuntos y los artículos
		// Consulto si posee información antes de continuar
		if (!empty($dataObj) && is_array($dataObj) && count($dataObj)) {

			$this->logs('- Uniendo los datos de los comentarios a los artículos.');

			foreach($dataObj as $n => $objArr) {
				
				$posicion = $idarr[$objArr['comentario_noticia_id']];
				$im[$posicion]['comentarios'][] = $objArr;
			}
		}

		$this->logs('- Comentarios unidos OK.');

		return $im;
	}
	
	/**
	 * Recupera los items del item actual (imagenes, videos, relacionadas, etc)
	 *
	 * @access	protected
	 * @param	array	$im			Datos de la matriz de items
	 * @param	string	$ids		Listado de Ids de los items
	 * @param	array	$idarr		Matriz de enlaces entre id y posición
	 * @param	integer	$tipo		Tipo de item actual
	 * @param	object	$obj		Objeto del item
	 * @param	integer	$objTipo	Tipo de objeto a recuperar (adjunto, nota, etc)
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadItems($im, $ids='', $idarr=false, $tipo=1, $obj, $objTipo=2) {
	
		if ($this->tableMultimedia == '') {
			$this->tableMultimedia = $this->_table.'noticias_multimedia';
		}

		$this->logs('- Recuperando adjuntos asociados al item.');

		$oSql = "SELECT * FROM {$this->tableMultimedia} 
			WHERE adjunto_noticia_tipo = {$tipo} 
				AND adjunto_tipo_id = {$objTipo} 
				AND adjunto_noticia_id IN({$ids})
			ORDER BY adjunto_noticia_id, adjunto_tipo, adjunto_orden";

		$this->logs('- Consulta SQL:'.$oSql);
		if (!$res = $this->db->query($oSql)) {
			$this->logs('- Error al ejecutar la consulta.');
			$this->logs('- El sistema dice:'.$this->db->error());

			$this->error(5);
			return false;

		} else {
		
			$objIdList = '';
			$objData = array();

			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$ors = $this->db->next($res);

				$objIdList.= ','.$ors['adjunto_multimedia_id'];
				$objData[$ors['adjunto_noticia_id']][$ors['adjunto_tipo']][$ors['adjunto_orden']] = $ors;
			}
			$objIdList = substr($objIdList,1);
		}
	
		$obj->itemId = $objIdList;
		$dataObj = $obj->process();

		$this->logs('------------------------------- ');
		$this->logs('--- Información del log de adjunto --- ');
		$this->logs($obj->log);
		$this->logs('--- Fin log de adjunto --- ');
		$this->logs('------------------------------- ');

		// Desdoblo los ids
		if ($objTipo == 1) {
			$primaryKey = 'noticia_id';
		} elseif ($objTipo == 2) {
			$primaryKey = 'gal_id';
		} else {
			$primaryKey = '';
		}

		$dataObjId = array();

		// Para evitar intentar iterar sobre un array vacio
		if (!empty($dataObj) && is_array($dataObj) && count($dataObj) > 0) {

			$this->logs('- Uniendo los datos de los objetos a los artículos.');

			foreach ($dataObj as $k => $v) {
				$dataObjId[$v[$primaryKey]] = $v;
			}
		}

		// Mezclo la información de los adjuntos y los artículos
		// Consulto si el array contiene información antes de continuar
		if (count($objData)) {

			foreach($objData as $artId => $objArr) {
				
				$artInfo = array();

				foreach ($objArr as $adjTipo => $objArrInfo) {

					foreach($objArrInfo as $adjOrden => $objArrData) {
					
						if (isset($dataObjId[$objArrData['adjunto_multimedia_id']])) {
							$objArrData = array_merge($objArrData, $dataObjId[$objArrData['adjunto_multimedia_id']]);
							$artInfo[$adjTipo][$adjOrden] = $objArrData;
						}
					}
				}
				
				$posicion = $idarr[$artId];
				$im[$posicion] = array_merge($im[$posicion], $artInfo);
			}

		}

		return $im;
	}
	
	/**
	 * Recupera los items reversos del item actual (imagenes, videos, relacionadas, etc)
	 *
	 * @access	protected
	 * @param	array	$im			Datos de la matriz de items
	 * @param	string	$ids		Listado de Ids de los items
	 * @param	array	$idarr		Matriz de enlaces entre id y posición
	 * @param	integer	$tipo		Tipo de item actual
	 * @param	object	$obj		Objeto del item
	 * @param	integer	$objTipo	Tipo de objeto a recuperar (adjunto, nota, etc)
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadReverseItems($im, $ids='', $idarr=false, $tipo=1, $obj, $objTipo=2) {
	
		if ($this->tableMultimedia == '') {
			$this->tableMultimedia = $this->_table.'noticias_multimedia';
		}
		
		$this->logs('- Recuperando adjuntos reversos asociados al item.');

		$oSql = "SELECT * FROM {$this->tableMultimedia} 
			WHERE adjunto_noticia_tipo = {$objTipo} 
				AND adjunto_tipo_id = {$tipo} 
				AND adjunto_multimedia_id IN({$ids})
			ORDER BY adjunto_multimedia_id, adjunto_tipo, adjunto_orden";

		$this->logs('- Consulta SQL:'.$oSql);
		if (!$res = $this->db->query($oSql)) {
			$this->logs('- Error al ejecutar la consulta.');
			$this->logs('- El sistema dice:'.$this->db->error());

			$this->error(5);
			return false;

		} else {
		
			$objIdList = '';
			$objData = array();

			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$ors = $this->db->next($res);

				$objIdList.= ','.$ors['adjunto_noticia_id'];
				$objData[$ors['adjunto_multimedia_id']]['r_'.$ors['adjunto_tipo']][$ors['adjunto_orden']] = $ors;
			}
			$objIdList = substr($objIdList,1);
		}
	
		$obj->itemId = $objIdList;
		$dataObj = $obj->process();

		$this->logs('------------------------------- ');
		$this->logs('--- Información del log de adjunto --- ');
		$this->logs($obj->log);
		$this->logs('--- Fin log de adjunto --- ');
		$this->logs('------------------------------- ');

		$this->logs('- Uniendo los datos de los objetos a los artículos.');
		
		// Desdoblo los ids
		if ($objTipo == 1) {
			$primaryKey = 'noticia_id';
		} elseif ($objTipo == 2) {
			$primaryKey = 'gal_id';
		} else {
			$primaryKey = '';
		}

		$dataObjId = array();

		if (!empty($dataObj)) {
			foreach ($dataObj as $k => $v) {
				$dataObjId[$v[$primaryKey]] = $v;
			}
		}

		// Mezclo la información de los adjuntos y los artículos
		foreach($objData as $artId => $objArr) {
			
			$artInfo = array();

			foreach ($objArr as $adjTipo => $objArrInfo) {

				foreach($objArrInfo as $adjOrden => $objArrData) {
				
					if (isset($dataObjId[$objArrData['adjunto_noticia_id']])) {
						$objArrData = array_merge($objArrData, $dataObjId[$objArrData['adjunto_noticia_id']]);
						$artInfo[$adjTipo][$adjOrden] = $objArrData;
					}
				}
			}
			
			$posicion = $idarr[$artId];
			$im[$posicion] = array_merge($im[$posicion], $artInfo);
		}

		return $im;
	}	
	
	/**
	 * Recupera los items del item (art. relacionados)
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadNotas($im, $ids='', $idarr=false) {

		if ($ids != '' && isset($this->_objeto['nota'])) {
			$obj = $this->_objeto['nota'];
			return $this->loadItems($im, $ids, $idarr, $this->_tipo, $obj, 1);
		}

		return $im;
	}
	
	/**
	 * Recupera las encuestas del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadEncuestas($im, $ids='', $idarr=false) {

		if ($ids != '' && isset($this->_objeto['encuesta'])) {
			$obj = $this->_objeto['encuesta'];
			return $this->loadItems($im, $ids, $idarr, $this->_tipo, $obj, 3);
		}

		return $im;
	}

	/**
	 * Recupera los items reversos del item (art. i-relacionados)
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadReverseNotas($im, $ids='', $idarr=false) {

		if ($ids != '' && isset($this->_objeto['nota'])) {
			$obj = $this->_objeto['nota'];
			return $this->loadReverseItems($im, $ids, $idarr, $this->_tipo, $obj, 1);
		}

		return $im;
	}	
	
	/**
	 * Carga objetos para utilizar en la clase
	 *
	 * @access	public
	 * @param	string	$name	Nombre del objeto
	 * @param	object	$obj	Objeto a incorporar
	 * @return	boolean	True si se ingresó con éxito, false en caso contrario
	 */
	public function loadObj($name='', $obj=false) {

		// Objetos permitidos: comentario, map, adjunto, nota, estadistica
		if ($name != '' && is_object($obj)) {
			$this->_objeto[$name] = $obj;
			return true;
		}

		return false;
	}	

	/**
	 * Guarda información en tiempo de ejecución sobre la actividad de la clase
	 *
	 * @access	private
	 * @param	string	$log	Información de la ejecución actual
	 */
	private function logs($log='') {
		if ($log != '') {
			$this->log.= $log.'<br/>';
		}
	}
	
	/**
	 * Recupera información de las estadisticas del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function stats($im, $ids='', $idarr=false) {
		if ($ids == '') {
			return $im;
		}
		
		$this->logs('- Recuperando información estadística asociado al item.');

		$oSql = "SELECT * FROM {$this->_table}noticias_stats 
			WHERE stats_tipo = {$this->_tipo} AND stats_noticia_id IN({$ids})";

		$this->logs('- Consulta SQL:'.$oSql);
		if (!$res = $this->db->query($oSql)) {
			$this->logs('- Error al ejecutar la consulta.');
			$this->logs('- El sistema dice:'.$this->db->error());

			$this->error(5);
			return false;

		} else {
		
			$this->logs('- Total de items con estadísticas:'.$this->db->num_rows($res));

			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$ors = $this->db->next($res);
				
				$posicion = $idarr[$ors['stats_noticia_id']];
				$im[$posicion]['estadisticas'] = $ors;
			}
		}
		
		// Guarda información de las estadísticas de los items selecionados
		if (isset($this->_statsArr) && is_array($this->_statsArr)) {

			$tipoArr = array(
				'print' => 'stats_print',
				'email' => 'stats_mail',
				'view' => 'stats_view'
			);

			$idsToStat = explode(',', $ids);
			foreach ($idsToStat as $k => $sStat) {

				if (!is_numeric($sStat)) {
					break;
				}

				reset($this->_statsArr);
				foreach ($this->_statsArr as $n => $sArr) {
				
					$sTipo = $sArr['tipo'];
					$sId = $sArr['id'];
					$sValor = $sArr['valor'];
				
					$this->logs('- Cargando estadisticas tipo: '.$sTipo.' 
						para el item ID:'.$sStat);

					// Tipo: print, view, email
					if (!empty($tipoArr[$sTipo])) {
					
						$oSql = "INSERT DELAYED INTO {$this->_table}noticias_stats 
							(stats_noticia_id, stats_tipo, {$tipoArr[$sTipo]}) 
							VALUES ( '{$sStat}', '{$this->_tipo}', '1')
						ON DUPLICATE KEY UPDATE {$tipoArr[$sTipo]} = ({$tipoArr[$sTipo]} + 1)";

						$this->logs('- Consulta SQL:'.$oSql);
						if (!$rest = $this->db->query($oSql)) {
							$this->logs('- Error al ejecutar la consulta.');
							$this->logs('- El sistema dice:'.$this->db->error());

							$this->error(5);
							return false;
						} else {
							$this->logs('- Estaditica ingresada con éxito. ');
						}

					// Tipo: Valoración de comentario 
					} elseif ($sTipo == 'comentario') {
					
						$oSql = "UPDATE LOW_PRIORITY {$this->_table}noticias_comentarios 
						SET comentario_votos_usuarios = (comentario_votos_usuarios + 1),
							comentario_votos_total = (comentario_votos_total + {$sValor})
						WHERE comentario_id = '{$sId}' 
							AND comentario_noticia_id ='{$sStat}'";

						$this->logs('- Consulta SQL:'.$oSql);
						if (!$rest = $this->db->query($oSql)) {
							$this->logs('- Error al ejecutar la consulta.');
							$this->logs('- El sistema dice:'.$this->db->error());

							$this->error(5);
							return false;
						} else {
							$this->logs('- Estaditica ingresada con éxito. ');
						}
					
					// Tipo: Valoración del item
					} elseif ($sTipo == 'voto') {
					
						$oSql = "INSERT DELAYED INTO {$this->_table}noticias_stats 
							(stats_noticia_id, stats_tipo, stats_vote_users, stats_vote_total) 
							VALUES ( '{$sStat}', '{$this->_tipo}', '1','{$valor}')
							ON DUPLICATE KEY UPDATE stats_vote_users = (stats_vote_users + 1),
								stats_vote_total = (stats_vote_total + {$votacion})";
						$this->logs('- Consulta SQL:'.$oSql);
						if (!$rest = $this->db->query($oSql)) {
							$this->logs('- Error al ejecutar la consulta.');
							$this->logs('- El sistema dice:'.$this->db->error());

							$this->error(5);
							return false;
						} else {
							$this->logs('- Estaditica ingresada con éxito. ');
						}
					} // End if tipos

				} // End foreach				
			
			}
			
		}		

		return $im;
	}
	
	/**
	 * Recupera todas las etiquetas del item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con etiquetas
	 */
	protected function tags($im, $ids='', $idarr=false) {
		if ($ids == '') {
			return $im;
		}
		
		$this->logs('- Recuperando las etiquetas asociadas al item.');
		$oSql = "SELECT tag_nombre, adjunto_noticia_id 
			FROM {$this->_table}noticias_galeria_tags, {$this->_table}noticias_tags 
			WHERE tag_id = adjunto_tag_id 
				AND adjunto_noticia_id IN ({$ids}) 
				AND tag_tipo = '{$this->_tipo}'
		ORDER BY adjunto_orden";

		$this->logs('- Consulta SQL:'.$oSql);
		if (!$rest = $this->db->query($oSql)) {
			$this->logs('- Error al ejecutar la consulta.');
			$this->logs('- El sistema dice:'.$this->db->error());

			$this->error(5);
			return false;

		} else {

			for ($i=0; $i<$this->db->num_rows($rest);$i++) {
				$ors = $this->db->next($rest);
				
				$posicion = $idarr[$ors['adjunto_noticia_id']];
				$im[$posicion]['noticia_tags'][] = $ors['tag_nombre'];
			}
		}

		return $im;
	}
	
	/**
	 * Guarda información sobre las estadísticas del item
	 *
	 * @access	public
	 * @param	string	$tipo	Tipo de información a guardar (print, view, etc)
	 * @param	integer	$id		Id del item a estatificar (id del comentario, etc)
	 * @param	integer	$valor	Valor a estatificar (nivel de votacion, etc)
	 * @return 	boolean	True, si se ingresó con éxito. False en caso contrario
	 */
	public function statsAdd($tipo='', $id='', $valor=0) {
		
		$this->logs('- Agrega información para estadísticas al item ');
		
		if ($tipo == 'print' 
			|| $tipo == 'view' 
			|| $tipo == 'email' 
			|| $tipo == 'voto' 
			|| $tipo == 'comentario') {

			$this->_statsArr[] = array(
				'tipo' => $tipo,
				'id' => $id,
				'valor' => $valor
			);

			return true;
		}
		
		return false;
	}
	
	/**
	 * Genera datos de las ubicaciones del item
	 *
	 * @access	private
	 * @param	Integer	$ubicacion	ID de la ubicación hijo
	 * @return	array	Información sobre la ubicación declarada
	 */
	public function ubicaciones($ubicacion=false) {

		$this->logs(' - Recuperando información de la ubicación del item.');

		if (!$ubicacion) {
		
			$this->logs(' - No hay ubicación definida.');
			return false;
		}
		
		if (!is_numeric($ubicacion)) {
		
			$this->logs(' - La ubicación no es un número.');
			return false;
		}		
		
		if (isset($this->ubicacionArr[$ubicacion])) {
		
			$texto = $this->ubicacionArr[$ubicacion];

		} else {
		
			$texto = array();

			$oSql = "SELECT * FROM ubicaciones 
				WHERE ubicacion_id = '{$ubicacion}' LIMIT 0,1";

			$this->logs('- Consulta SQL:'.$oSql);
			if (!$rest = $this->db->query($oSql)) {
				$this->logs('- Error al ejecutar la consulta.');
				$this->logs('- El sistema dice:'.$this->db->error());

				$this->error(5);
				return false;
			}
			
			if (!$this->db->num_rows($rest)) {
				$this->logs('- La ubicación seleccionada no existe ');
				return false;
			}

			$this->logs('- Ubicación recuperada OK ');
			
			$rs = $this->db->next($rest);
			$texto[$rs['ubicacion_tipo']] = $rs;

			$this->logs('- Intentando recuperar ubicación superior ');
			if ($txt = $this->ubicaciones($rs['ubicacion_pertenece'])) {
				$texto = array_merge($texto, $txt);
			}

			$this->logs('- Retornando datos de ubicación OK.');
			
			$this->ubicacionArr[$ubicacion] = $texto;
		}

		return $texto;
	}	
}
?>