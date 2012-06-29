<?php
/**
 * Clase para el manejo de items tipo multimedia
 *
 * Recupera información de los items tipo multimedia o como adjuntos de otro item
 *
 * <b>Que hace?</b> <br/>
 * Recupera en forma de array, datos de los adjuntos multimedia 
 * (video, audio, documentos, imagenes, etc). 
 * Permite recuperar estos en forma de array o dentro de un item asociado.
 *
 * <b>Cómo se usa:</b> <br>
 * Recuperando información de un item
 * <code>
 * $gd = New Adjuntos();
 * $gd->db = $db;	// Requiere conexión a base de datos
 * $gd->itemId = 1;
 * $data = $gd->process();
 * </code>
 *
 * Recuperando información de un item como adjunto asociado
 * <code>
 * $gd = New Notas();
 * $gd->db = $db;	// Requiere conexión a base de datos
 * $gd->itemId = 1;
 * 
 *	// Se agrega a la clase notas para recuperar los adjuntos asociiados
 *	$gdadj = New Adjuntos();
 *	$gdadj->db = $db;
 * 	$gd->addobj("adjuntos", $gdadj);	// Forma de asociarlo
 *
 * $data = $gd->process();
 * </code>
 * 
 * <b>Parámetros por defecto:</b> <br/>
 * <ul>
 * <li><b>tablePrefix</b> default: ''</li>
 * <li><b>cantidad</b> default:0</li>
 * <li><b>desplazamiento</b> default:0</li>
 * <li><b>orden</b> default: 'gal_id desc'</li>
 * <li><b>itemGaleria</b> default:0</li>
 * <li><b>itemId</b> default:0</li>
 * <li><b>itemSQLExtra</b> default:''</li>
 * <li><b>itemTags</b> default: ''</li>
 * <li><b>itemTexto</b> default: ''</li> 
 * <li><b>itemTextoTipo</b> default: 'and'</li> 
 * <li><b>itemTipo</b> default: 0</li> 
 * <li><b>itemTitulo</b> default: ''</li> 
 * <li><b>itemUserId</b> default: 0</li> 
 * </ul>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>31.05.2012 <br/>
 *	- Added: Se agregó que se pueda filtrar por ID de galería
 * </li>
 *
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.</li>
 *
 * <li>22.03.2012 <br/>
 * - Fix: Se reparó error al intentar mostrar información de los adjuntos 
 *	ingresados ocn el sistema viejo.</li>
 *
 * <li>21.03.2012 <br/>
 *	- Fix: Se reparó error en caso de que se seleccione múltiples tipos 
 *	(antes el sistema permitia un solo tipo por vez).</li>
 *
 * <li>19.03.2012 <br/>
 *	- Modify: Se optimizó la carga de configuración inicial por defecto.</li>
 *
 * <li>29.02.2012 <br/>
 *	- Fix: Se reparó error mínimo de recupero de variables 
 *	al detectar la extensión del archivo </li>
 *
 * <li>08.02.2012 <br/>
 *	- Modify: Se modificó completamente la clase de manejo de objetos 
 *	y se hizo parte de la clase  CubePHP </li>
 *
 * <li>27.02.2011<br/>
 *	- Fix: se agregó variable "url_yt" para retornar url válida 
 *	para reproductores HTML5, iPad y object cuando el videos es desde Youtube </li>
 *
 * <li>03.01.2011<br/>
 *	- Fix: Se solucionó error al levantar un archivo desde youtube 
 *	con una url incorrecta (intentaba levantar una variable 
 *	$noticia_video_Tmp["v"] y $m que no existían)<br/>
 *	- Fix: Se modificó como se consulta por la foto asociada al adjunto. 
 *	(Se generaba un error cuando no tenía foto adjunta)</li>
 *
 * <li>13.12.2010<br/>
 *	- Modify: Optimizaciónes menores (inicialización de variables, etc)</li>
 *
 * <li>03.12.2010<br/>
 *	- Fix: Se modificó que si la Url del video de YouTube es inválida, 
 *	devuelva un campo vacio para la imagen</li>
 *
 * <li>26.11.2010<br/>
 *	- Fix: Se reparó que al buscar buscaba en el campo gal_titulo que no existe. 
 *	se corrigio a gal_nombre </li>
 *
 * <li>25.11.2010<br/>
 *	- Added: Se agregó que se puedan devolver los tags del objeto</li>
 *
 * <li>22.11.2010<br/>
 *	- Fix: Se igualó la ruta del archivo final para videos de ytube 
 *	(antes era accesible desde gal_file ahora desde url->o 
 *	como el resto de los archivos </li>
 *
 * <li>08.11.2010<br/>
 *	- Fix: Se reparó error cuando se intentaba seleccionar más de un tipo, 
 *	el sistema no devolvía ninguno<br/>
 *	- Fix: Se reparó la forma en la que se devuelven los adjuntos, 
 *	la ruta y los archivos asociados </li>
 *
 * <li>08.04.2010<br/>
 *	- modify: Se modificó la forma de filtrar los resultados a LIKE</li>
 * </ul>
 *
 * @package		Core
 * @subpackage	Items
 * @category	Adjuntos
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	13.02.2012
 * @version		1.0	- last revision 2012.02.13
 */
class Adjuntos extends CubePHP {

	/**
	 * Array de datos de información de objetos (path base, etc)
	 *
	 * @access public
	 * @var array
	 */
	public $datos;

	/**
	 * Id de la galería, permite multiples Ids separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemGaleria;

	/**
	 * Id del item, permite multiples Ids separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemId;
	
	/**
	 * Búsqueda personalizada con comandos SQL
	 *
	 * @access public
	 * @var string
	 */
	public $itemSQLExtra;
	
	/**
	 * Filtro de búsqueda por etiquetas del artículo
	 *
	 * @access public
	 * @var string
	 */
	public $itemTags;
	
	/**
	 * Filtro por búsqueda de texto 
	 *
	 * @access public
	 * @var string
	 */
	public $itemTexto;
	
	/**
	 * Indica la forma de buscar el texto (and, or, all)
	 *
	 * @access public
	 * @var string
	 */
	public $itemTextoTipo;

	/**
	 * Indica el tipo de artículo a devolver
	 * Puede ser un solo número o números separados por coma
	 *
	 * @access public
	 * @integer|string
	 */
	public $itemTipo;

	/**
	 * Filtro por Titulo 
	 *
	 * @access public
	 * @var string
	 */
	public $itemTitulo;
	
	/**
	 * Filtro por usuario creador del artículo
	 * Permite un usuario, o varios separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemUserId;	
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
	
		$this->_name = 'Adjuntos';
		$this->_version = '1.01';

		$this->init();
	}
	
	/**
	 * Destructor de la clase
	 *
	 * @access	public
	 */
	public function __destruct() {

	}

	/**
	 * Revisa todos los filtros, retorna array de comandos a procesar
	 *
	 * @access	protected
	 * @return	array	Matriz de datos a procesar
	 */
	protected function checkFilters() {
	
		$this->log.= '- <b>Inicializando filtros para listado</b> <br/>';
	
		$sql = array();
		
		if ($this->itemId) {
			if ($oSql = $this->filterNumeric($this->itemId, 'Id', 'gal_id')) {
				$sql[] = $oSql;
			}
		}
		
		if ($this->itemGaleria) {
			if ($oSql = $this->filterNumeric($this->itemGaleria, 'Galería', 'gal_galeria')) {
				$sql[] = $oSql;
			}
		}
		
		if ($this->itemSQLExtra != '') {
		
			$this->log.= '- Filtro asignado: <b>Parámetros externos de SQL</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemSQLExtra.'<br/>';

			$sql[] = $this->itemSQLExtra;
		}
		
		if ($this->itemTags != '') {
			
			$this->log.= '- Filtro asignado: <b>búsqueda por etiqueta</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemTags.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTags);
			
			$this->log.= '- Valor limpio y filtrado a buscar:'.$texto.'<br/>';
			
			if ($texto != '') {
			

			}
		}

		if ($this->itemTexto != '') {
		
			$this->log.= '- Filtro asignado: <b>búsqueda por texto</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemTexto.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTexto);
			
			$this->log.= '- Texto limpio y filtrado a buscar:'.$texto.'<br/>';
			
			if ($texto != '') {
			
				$textoTipo = strtolower(trim($this->itemTextoTipo));

				switch($textoTipo) {
					case 'and':
					default:
						$texto = '+'.$texto;
						$texto = str_replace(' ', ' +',$texto);
					break;
					
					case 'or':
					break;

					case 'all':
						$texto = "\"{$texto}\"";
					break;
				}

				$this->log.= '- Texto a envíar para la búsqueda:'.$texto.'<br/>';

				$sql[] = "MATCH (gal_nombre, gal_descripcion) 
				AGAINST ('{$texto}' IN BOOLEAN MODE)";
			}
		}
		
		if ($this->itemTipo) {
		
			$this->log.= '- Filtro asignado: <b>Filtro por tipo de contenido</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemTitulo.'<br/>';		

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTipo);
			$texto = trim(str_replace(' ', '',$texto));

			$this->log.= '- Tipo a consultar (luego de filtrado):'.$texto.'<br/>';

			$iTitulo = explode(',',$texto);
			$texto = '';
			foreach($iTitulo as $k => $v) {
				$v = trim($v);
				$texto.= ($v != '') ? ",'{$v}'" : '';
			}
			$texto = substr($texto,1);
			
			if ($texto != '') {
				$sql[] = 'gal_tipo IN('.$texto.')';
				
				$this->log.= '- Filtro por tipo. Ingresado OK. <br/>';
			}
		}

		if ($this->itemTitulo != '') {
		
			$this->log.= '- Filtro asignado: <b>Filtro por url friendly del titulo</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemTitulo.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTitulo);

			$this->log.= '- Titulo Url Friendly a buscar:'.$texto.'<br/>';

			$sql[] = "noticia_page_url = '{$texto}'";
		}
		
		if ($this->itemUserId) {
			if ($oSql = $this->filterNumeric($this->itemUserId, 'user Id', 'gal_user_id')) {
				$sql[] = $oSql;
			}
		}

		$this->log.= '- Asignación de filtros OK <br/>';

		return $sql;
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
		$this->_errorArray[3] = 'No se ha ingresado el ID del item';
		$this->_errorArray[4] = 'El ID del artículo no existe o es incorrecto';
		$this->_errorArray[5] = 'Error en la consulta. No se puede retornar ningún resultado';

		$this->datos = false;
		$this->facetas = array();
		$this->orden = 'gal_id desc';
		$this->_tipo = 2;	// 2:adjuntos

		$this->itemGaleria = 0;
		$this->itemId = 0;
		$this->itemSQLExtra = '';
		$this->itemTags = '';
		$this->itemTexto = '';
		$this->itemTextoTipo = 'and';
		$this->itemTipo = 0;
		$this->itemTitulo = '';
		$this->itemUserId = 0;
	}
	
	/**
	 * Procesa todos los filtros ingresados y retorna array con resultados
	 *
	 * @access 	public
	 * @return	array
	 */
	public function process() {

		// Revisión inicial
		if (!$this->checkInit()) {
			return false;
		}

		$sqlWhere = array();
		$sqlOrden = '';
		$sqlLimit = '';
		
		// Cargo los filtros
		$sqlWhere = $this->checkFilters();

		if (!count($sqlWhere)) {
			$this->log.= '- <b>Error:</b> No existe ningún condicional (WHERE) definido<br/>';
		
			$this->error(2);
			return false;
		}
		$sqlWhereFinal = implode(' AND ', $sqlWhere);

		if ($this->orden != '') {
			$sqlOrden = 'ORDER BY '.$this->orden;
		}

		if ($this->cantidad || $this->desplazamiento) {
			$sqlLimit = 'LIMIT '.$this->desplazamiento;
			$sqlLimit.= ($this->cantidad) ? ','.$this->cantidad : '';
		}
		
		$oSql = "SELECT count(gal_id) as total 
			FROM {$this->_table}noticias_galeria_multimedia
			WHERE {$sqlWhereFinal}";

		$this->log.= '- Realizando consulta previa, para saber la cantidad de resultados. <br/>';
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		
		$this->log.= '- Generando consulta definitiva <br/>';
		$oSqlFinal = "SELECT {$this->_table}noticias_galeria_multimedia.*
			FROM {$this->_table}noticias_galeria_multimedia
			WHERE {$sqlWhereFinal}
			{$sqlOrden}
			{$sqlLimit}";

		$this->log.= '- Consulta SQL definitiva:'.$oSqlFinal.'<br/>';

		/* Consultando si posee cache */
		if ($this->_cacheOK) {

			$this->log.= '- Intentando recuper datos de cache. <br/>';

			$cacheName = md5($oSqlFinal);

			if ($cacheData = $this->cache->get($cacheName)) {
			
				$this->log.= '- Datos de cache recuperados OK. <br/>';

				$this->totalPaginas = $cacheData['totalPaginas'];
				$this->totalResultados = $cacheData['totalResultados'];
				// $this->facetas = $cacheData['facetas'];
				$im = $cacheData['im'];
				
				return $im;
			}
		}		
		
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(5);
			return false;
		}

		$rs = $this->db->next($res);
		$this->totalResultados = $rs['total'];

		$this->log.= '- Consulta SQL OK.<br/>';

		if ($this->cantidad) {
			$this->totalPaginas = ceil($this->totalResultados / $this->cantidad);
		} else {
			$this->totalPaginas = $this->totalResultados;
		}
		
		$this->log.= '- Total de resultados encontrados:'.$this->totalResultados.' <br/>';
		$this->log.= '- Total de páginas devueltas:'.$this->totalPaginas.' <br/>';

		if (!$res = $this->db->query($oSqlFinal)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;
		}
		
		$this->log.= '- Recuperando información final<br/>';
		$im = array();

		$idList = '';	// Listado de ids para luego agregar información
		$idArray = array();	// Ubicación del Id para luego agregarle información
		
		// Información extra solo para Adjuntos
		$idListExtra = '';	// Listado de ids para luego agregar información
		$idArrayExtra = array();	// Ubicación del Id para luego agregarle información		

		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$rs = $this->db->next($res);

				$rs['extra'] = json_decode($rs['gal_extra'], true);
				
				foreach ($rs as $j => $s) {
					if (is_string($s) && $s != '') {

						// Aplica el formato negrita, cursiva, etc y los saltos de líneas
						$s = htmlspecialchars_decode($s, ENT_QUOTES);
						$s = str_replace("\n", "<br/>", $s);
						
						// Aplico vínculos cliqueables al texto
						if ($j == 'gal_descripcion') {
							$s = clickable($s);
						}

						// cierro los tags que puedan haber quedado abiertos
						$s = closeTags($s);

						$rs[$j] = $s;
					}
				}
				
				// Devuelvo la extensión del adjunto.
				$rs['gal_file_ext'] = '';
				if (strpos($rs['gal_file'], '.') && strpos($rs['gal_file'], 'http:') === false) {
					$oFileExtArr = explode('.', $rs['gal_file']);
					$oFileExt = (count($oFileExtArr)>1) ? $oFileExtArr[count($oFileExtArr)-1] : '';
					$rs['gal_file_ext'] = $oFileExt;
				}
				
				// Proceso solo si tengo asignado el array de datos
				if (is_array($this->datos)) {
		
					$fileUrl = pathAdjunto($this->datos[$rs['gal_galeria']], $rs['gal_file'], $rs['gal_fecha']);
					if ($fileUrl) {
						$rs = array_merge($rs, array('url' => $fileUrl));
					}
				}
				
				// Si es un video de YouTube proceso info extra
				if ($rs['gal_tipo'] == 'ytube') {
				
					$type_all = array(
						'link' => '/http:\/\/[w\.]*youtube\.com\/watch\?v=([^&#]*)|http:\/\/[w\.]*youtu\.be\/([^&#]*)/i',
						'embed' => '/http:\/\/[w\.]*youtube\.com\/v\/([^?&#"\']*)/is',
						'iframe' => '/http:\/\/[w\.]*youtube\.com\/embed\/([^?&#"\']*)/is'
					);
					
					$code = '';
					foreach($type_all as $type => $regexp) {

						preg_match($regexp, $rs['gal_file'], $match);
						if (!empty($match)) {
							
							for($ii = 1; $ii < sizeof($match); $ii++) {
								if($match[$ii] != '') {
									$code = $match[$ii];
									break;
								}
							}
							if($code != '') {
								break;
							}
						}
					}

					if ($code != '') {
						// Imagen grande alternativa
						$rs['extra']['img_small'] = 'http://i.ytimg.com/vi/'.$code.'/default.jpg';
						$rs['extra']['img_large'] = 'http://i4.ytimg.com/vi/'.$code.'/hqdefault.jpg';
						
						// Igualo la ruta del archivo final para videos de ytube
						$rs['url']['o'] = $rs['gal_file'];
						
						// Compatibilizo si no recibe esta data desde la dbase
						$rs['extra']['iframe'] = 'http://www.youtube.com/embed/'.$code;
						$rs['extra']['embed'] = 'http://www.youtube.com/v/'.$code;
						$rs['extra']['url'] = 'http://www.youtube.com/watch?v='.$code;
					}
				}
				
				$im[$i] = $rs;
				$idList.= ','.$rs['gal_id'];
				$idArray[$rs['gal_id']][] = $i;
				
				if (!empty($rs['extra']['adjunto'])) {
					$idListExtra.= ','.$rs['extra']['adjunto'];
					$idArrayExtra[$rs['extra']['adjunto']][$rs['gal_id']] = $i;
				}				
				
			}
			
			$idList = substr($idList,1);
			$idListExtra = substr($idListExtra,1);
		}
		
		// Cargo los adjuntos extras del item
		$im = $this->loadItemsExtras($im, $idListExtra, $idArrayExtra);

		// Cargo los adjuntos del item
		$im = $this->loadAdjuntos($im, $idList, $idArray);
		
		// Cargo los art relacionados del item
		$im = $this->loadNotas($im, $idList, $idArray);
	
		// Cargo los comentarios del item
		$im = $this->loadComentarios($im, $idList, $idArray);
	
		// Cargo el mapa asociado al item
		// $im = $this->mapa($im, $idList, $idArray);
	
		// Cargo información de etiquetas del item
		$im = $this->tags($im, $idList, $idArray);
	
		// Cargo información de estadísticas del item
		$im = $this->stats($im, $idList, $idArray);

		// Cargo información reversa del item
		if ($this->reverse) {
			// Cargo los adjuntos a los que se asoció el item
			$im = $this->loadReverseAdjuntos($im, $idList, $idArray);
			
			// Cargo los art relacionados asociados al item
			$im = $this->loadReverseNotas($im, $idList, $idArray);	
		}

		/* Consultando si posee cache */
		if ($this->_cacheOK) {
		
			$this->log.= '- Guardando consulta en Cache. <br/>';

			$cacheName = md5($oSqlFinal);

			$cacheData = array();
			$cacheData['totalPaginas'] = $this->totalPaginas;
			$cacheData['totalResultados'] = $this->totalResultados;
			// $cacheData['facetas'] = $this->facetas;
			$cacheData['im'] = $im;

			// consulta si posee cache, sino lo guarda por 24 hs.
			$cacheExpire = !empty($this->cacheExpire) ? $this->cacheExpire : 86400;

			if ($this->cache->set($cacheName, $cacheData, $cacheExpire)) {

				$this->log.= '- Información guardada en cache por:'.$cacheExpire.' segundos. <br/>';

			} else {

				$this->log.= '- Error al guardar información en cache.<br/>';

			}
		}

		return $im;
	}


	/**
	 * Recupera los items del item actual (imagenes, videos, relacionadas, etc)
	 *
	 * @access	protected
	 * @param	array	$im			Datos de la matriz de items
	 * @param	string	$ids		Listado de Ids de los items
	 * @param	array	$objData	Array de conexiones de items y objetos
	 * @return	array	Matriz de datos de items con adjuntos
	 */
	protected function loadItemsExtras($im, $ids='', $objData) {

		if (empty($this->_objeto['adjunto'])) {
			
			$this->log.= '- El objeto adjunto extra no esta definido.<br/>';

			return $im;
		}
		$obj = $this->_objeto['adjunto'];
	
		$this->log.= '- Recuperando adjuntos extras al item.<br/>';
		
		$this->log.= '- Items arecuperar: '.$ids.'.<br/>';

		$primaryKey = 'gal_id';

		$obj->itemId = $ids;
		$dataObj = $obj->process();

		$this->log.= '------------------------------- <br/>';
		$this->log.= '--- Información del log de adjunto --- <br/>';
		$this->log.= $obj->log;
		$this->log.= '--- Fin log de adjunto --- <br/>';
		$this->log.= '------------------------------- <br/>';
		$this->log.= '- Uniendo los datos de los objetos a los artículos.<br/>';
		
		$dataObjId = array();

		// Para evitar intentar iterar sobre un array vacio
		if (!empty($dataObj) && is_array($dataObj) && count($dataObj) > 0) {
			foreach ($dataObj as $k => $v) {
				$dataObjId[$v[$primaryKey]] = $v;
			}
		}

		// Mezclo la información de los adjuntos y los artículos
		foreach($objData as $objId => $objArr) {
			
			$artInfo = array();

			foreach ($objArr as $itemId => $itemPos) {

				$adjInfo = $dataObjId[$objId];
				$im[$itemPos]['extra']['adj'] = array_merge($im[$itemPos], $adjInfo);
			}
		}

		return $im;
	}	
	
}
?>