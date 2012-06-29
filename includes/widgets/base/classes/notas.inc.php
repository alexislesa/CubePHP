<?php
/**
 * Clase base para el procesamiento de artículos
 * 
 * <li>16.06.2012 <br/>
 *	- Modify: Se modificó la función embedHTML para eliminar 
 *	los espacios en blanco del texto adjunto.</li>
 *
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.</li>
 
 * @revision: 19.03.2012
 *  - Added: Se agregó la opción de mmanejar las tablas nexos de objetos multimedia
 *	- Modify: Se optimizó la inicialización de las variables básicas
 */
class Notas extends CubePHP {

	/**
	 * Path donde se encuentra la ruta de los archivos para embeber en el texto
	 *
	 * @access public
	 * @var string
	 */
	public $embed;
	
	/**
	 * Retorna todos los objetos embebidos en el texto
	 *
	 * @access public
	 * @var array
	 */
	public $embedList;
	
	/**
	 * Retorna información de las facetas entontradas en la consulta
	 *
	 * @access public
	 * @var array
	 */
	public $facetas;
	
	/**
	 * Filtro por Autor
	 *
	 * @access public
	 * @var string
	 */
	public $itemAutor;

	/**
	 * Filtro por estado del artículo (borrador, eliminaod, etc)
	 * Permite un solo valor, o multiples separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemEstado;
	
	/**
	 * Indica los filtros de facetas a retornar
	 * Permite multiples facetas separadas por coma (ej: mes, dia, seccion_id)
	 *
	 * @access public
	 * @var string
	 */
	public $itemFacetas;
	
	/**
	 * Filtro por Fuente del artículo
	 *
	 * @access public
	 * @var string
	 */
	public $itemFuente;

	/**
	 * Id del item, permite multiples Ids separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemId;
	
	/**
	 * Filtro de búsqueda por objeto asociado al artículo
	 *
	 * @access public
	 * @var string
	 */
	public $itemObject;
	
	/**
	 * Resalta la palabra o frase indicada
	 *
	 * @access public
	 * @var string
	 */
	public $itemResaltado;
	
	/**
	 * Tipo de tag de resaltado a utilizar (b, i, cite, ... etc)
	 * 
	 * @access public
	 * @var string
	 */
	public $itemResaltadoTag;
	
	/**
	 * Clase a asignar a la palabra o frase resaltada
	 *
	 * @access public
	 * @var string
	 */
	public $itemResaltadoClass;
	
	/** 
	 * Indica el tipo de sección de artículo a devolver
	 * Puede ser un solo número, o un string de secciones separados por coma
	 *
	 * @access public
	 * @var string
	 */
	public $itemSeccion;
	
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
	 * Filtro por ubicación (pais, provincia, etc) del artículo
	 * 
	 * @access public
	 * @var integer
	 */
	public $itemUbicacion;
	
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
	
		$this->_name = 'Notas';
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
		
		$this->log.= '- Generando consulta final <br/>';
		$oSqlFinal = "SELECT {$this->_table}noticias.*, {$this->_table}noticias_secciones.*
			FROM {$this->_table}noticias, {$this->_table}noticias_secciones
			WHERE noticia_seccion_id = seccion_id AND {$sqlWhereFinal}
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
				$this->facetas = $cacheData['facetas'];
				$im = $cacheData['im'];
				
				return $im;
			}
		}
		
		if ($this->itemFacetas == '') {
			
			$oSql = "SELECT count(noticia_id) as total 
				FROM {$this->_table}noticias, {$this->_table}noticias_secciones 
				WHERE noticia_seccion_id = seccion_id AND {$sqlWhereFinal}";

			$this->log.= '- Realizando consulta previa, para saber la cantidad de resultados. <br/>';
			$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
			if (!$res = $this->db->query($oSql)) {
				$this->log.= '- Error al ejecutar la consulta.<br/>';
				$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
			
				$this->error(5);
				return false;
			}

			$rs = $this->db->next($res);
			$this->totalResultados = $rs['total'];

			$this->log.= '- Consulta SQL OK.<br/>';

		} else {
		
			$this->log.= '- Preparando consulta por facetas.<br/>';
			$this->log.= '- Información de facetas a procesar:'.$this->itemFacetas.'.<br/>';
			
			$oFacetas = str_replace(' ','',strtolower($this->itemFacetas));
			$oFacetasArr = explode(',',$oFacetas);
			$oFacetasFinal = array();
			
			foreach($oFacetasArr as $k => $v) {
			
				if ($v=='anio' || $v=='mes' || $v=='rango') {
					$oFacetasFinal['fecha'][$v] = '';
				} elseif (strpos($v, 'noticia_') !== false) {
					$oFacetasFinal['campos'][] = $v;
				} else {
					$oFacetasfinal['extras'][] = $v;
				}
			}

			// Si faceta por fecha o rango, necesito cargar el campo fecha
			if (!empty($oFacetasFinal['fecha'])) {
				$oFacetasFinal['campos'][] = 'noticia_fecha_modificacion';
			}

			if (!empty($oFacetasFinal['campos'])) {
				$oFacetasCampos = implode(',', $oFacetasFinal['campos']);
				$this->log.= '- Realizando búsqueda facetada por los siguientes campos:'.$oFacetasCampos.'.<br/>';
				
			} else {
				$oFacetasCampos = 'noticia_id';
				$this->log.= '- No se encontraron campos para facetar. Se realizará la búsqueda por Id.<br/>';
			}

			$oSql = "SELECT {$oFacetasCampos}
				FROM {$this->_table}noticias, {$this->_table}noticias_secciones 
				WHERE noticia_seccion_id = seccion_id AND {$sqlWhereFinal}";

			$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
			if (!$res = $this->db->query($oSql)) {
				$this->log.= '- Error al ejecutar la consulta.<br/>';
				$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
			
				$this->error(5);
				return false;
			}

			$this->totalResultados = $this->db->num_rows($res);

			$this->log.= '- Consulta SQL OK.<br/>';

			if ($this->totalResultados) {
			
				$oFac = array();

				for ($i=0; $i<$this->totalResultados;$i++) {
					$rs = $this->db->next($res);
					
					foreach($oFacetasFinal['campos'] as $k => $v) {

						if ($v != 'noticia_fecha_modificacion') {
							$oFac[$v][$rs[$v]] = !empty($oFac[$v][$rs[$v]]) 
												? $oFac[$v][$rs[$v]]+1
												: 1;
						}
					}

					if (!empty($oFacetasFinal['fecha'])) {
					
						$oFechaField = $rs['noticia_fecha_modificacion'];
						
						if (isset($oFacetasFinal['fecha']['mes'])) {
							$f = date('Y-m',$oFechaField);
							$oFac['fecha']['mes'][$f] = !empty($oFac['fecha']['mes'][$f]) 
														? $oFac['fecha']['mes'][$f]+1
														: 1;
						}
						
						if (isset($oFacetasFinal['fecha']['anio'])) {
							$f = date('Y',$oFechaField);
							$oFac['fecha']['anio'][$f] = !empty($oFac['fecha']['anio'][$f]) 
														? $oFac['fecha']['anio'][$f]+1
														: 1;
						}

						if (isset($oFacetasFinal['fecha']['rango'])) {

							$oAnioAnterior = false;
							$oAnioActual = false;
							$oTrimestreActual = false;
							$oUltimos30Dias = false;
							$oMesActual = false;
							$oMesPasado = false;
							$oSemanaActual =false;
							$oAyer = false;
							$oHoy = false;
							
							// consulta por año
							if ($oFechaField < mktime(0,0,0,1,1,date('Y'))) {
								$oAnioAnterior = true;
							} else {
								$oAnioActual = true;
								
								if ($oFechaField > mktime(0,0,0,date('m')-3, 1, date('Y'))) {
									$oTrimestreActual = true;

									if ($oFechaField > mktime(0,0,0,date('m'), 1, date('Y'))) {
										$oMesActual = true;
									} else {
										if ($oFechaField > mktime(0,0,0,date('m')-1, 1, date('Y'))) {
											$oMesPasado = true;
										}
									}
									
									if ($oFechaField > mktime(0,0,0,date('m'), date('d')-30, date('Y'))) {
										$oUltimos30Dias = true;
										
										if ($oFechaField > mktime(0,0,0,date('m'), date('d')-7, date('Y'))) {
											$oSemanaActual = true;

											$testFecha = mktime(0,0,0,date('m', $oFechaField), date('d',$oFechaField), date('Y', $oFechaField));
											
											if ($testFecha == mktime(0,0,0,date('m'), date('d')-1, date('Y'))) {
												$oAyer = true;
											}
											
											if ($testFecha == mktime(0,0,0,date('m'), date('d'), date('Y'))) {
												$oHoy = true;
											}											
										}
									}
								}
							}
							
							if ($oAnioAnterior) {
								$oFac['fecha']['rango']['anios_anteriores'] = !empty($oFac['fecha']['rango']['anios_anteriores'])
																			? $oFac['fecha']['rango']['anios_anteriores']+1
																			: 1;
							}
							
							if ($oAnioActual) {
								$oFac['fecha']['rango']['este_anio'] = !empty($oFac['fecha']['rango']['este_anio'])
																		? $oFac['fecha']['rango']['este_anio']+1
																		: 1;
							}

							if ($oTrimestreActual) {
								$oFac['fecha']['rango']['trimestre'] = !empty($oFac['fecha']['rango']['trimestre'])
																	? $oFac['fecha']['rango']['trimestre']+1
																	: 1;
							}
							
							if ($oUltimos30Dias) {
								$oFac['fecha']['rango']['ultimos_30dias'] = !empty($oFac['fecha']['rango']['ultimos_30dias'])
																		? $oFac['fecha']['rango']['ultimos_30dias']+1
																		: 1;
							}
							
							if ($oMesActual) {
								$oFac['fecha']['rango']['este_mes'] = !empty($oFac['fecha']['rango']['este_mes'])
																	? $oFac['fecha']['rango']['este_mes']+1
																	: 1;
							}

							if ($oMesPasado) {
								$oFac['fecha']['rango']['mes_pasado'] = !empty($oFac['fecha']['rango']['mes_pasado'])
																	? $oFac['fecha']['rango']['mes_pasado']+1
																	: 1;
							}							
							
							if ($oSemanaActual) {
								$oFac['fecha']['rango']['semana'] = !empty($oFac['fecha']['rango']['semana'])
																	? $oFac['fecha']['rango']['semana']+1
																	: 1;
							}
							
							if ($oAyer) {
								$oFac['fecha']['rango']['ayer'] = !empty($oFac['fecha']['rango']['ayer'])
																? $oFac['fecha']['rango']['ayer']+1
																: 1;
							}
							
							if ($oHoy) {
								$oFac['fecha']['rango']['hoy'] = !empty($oFac['fecha']['rango']['hoy'])
																? $oFac['fecha']['rango']['hoy']+1
																: 1;
							}
						}
					}
				}

				$this->facetas = $oFac;
			}
			
			$this->log.= '- Carga de información facetada finalizada OK. <br/>';
		}

		if ($this->cantidad) {
			$this->totalPaginas = ceil($this->totalResultados / $this->cantidad);
		} else {
			$this->totalPaginas = $this->totalResultados;
		}
		
		$this->log.= '- Total de resultados encontrados:'.$this->totalResultados.' <br/>';
		$this->log.= '- Total de páginas devueltas:'.$this->totalPaginas.' <br/>';

		$this->log.= '- Generando consulta SQL:'.$oSqlFinal.'<br/>';
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

		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$rs = $this->db->next($res);

				foreach ($rs as $j => $s) {
					if (strpos($j,'noticia_') !== false && is_string($s) && $s != '') {

						// Aplica el formato negrita, cursiva, etc y los saltos de líneas
						$s = htmlspecialchars_decode($s, ENT_QUOTES);
						$s = str_replace("\n", '<br/>', $s);
						
						// Aplico vínculos cliqueables al texto
						$s = clickable($s);

						// cierro los tags que puedan haber quedado abiertos
						$s = closeTags($s);
						
						$rs[$j] = $s;
						
						if ($j == 'noticia_bajada' 
							|| $j == 'noticia_texto' 
							|| $j == 'noticia_texto_complementario') {

							// Consulta por HTML embebido en los textos
							$rs[$j] = preg_replace(
								'/\\[\\{(adj|art|map):(\\d+)\\}([^\\]\\[]+) ?]/Usie',	
								"\\\$this->embedHTML('\\1', '\\2', '\\3')", 
								$rs[$j]
							);
			
							$rs[$j] = preg_replace(
								'/\\[\\{(adj|art|map):(\\d+)\\}?]/Usie',	
								"\\\$this->embedHTML('\\1', '\\2', '')", 
								$rs[$j]
							);

							// Consulta por resaltado
							if ($this->itemResaltado != '') {
							
								$this->log.= '- Aplicando resaltado a la palabra:';
								$this->log.= $this->itemResaltado.' <br/>';

								$oText = '<'.$this->itemResaltadoTag;
								$oText.= 'class="'.$this->itemResaltadoClass.'" >';
								$oText.= $this->itemResaltado;
								$oText.= '</'.$this->itemResaltadoTag.'>';

								$rs[$j] = str_replace($this->itemResaltado, $oText, $rs[$j]);
							}
						}
					}
				} // end for
				
				// Devuelve array con la ubicación definida
				if ($rs['noticia_ubicacion_id']) {
					$rs['ubicacion'] = $this->ubicaciones($rs['noticia_ubicacion_id']);
				}

				$im[$i] = $rs;
				$idList.= ','.$rs['noticia_id'];
				$idArray[$rs['noticia_id']] = $i;
			}
			$idList = substr($idList,1);
		}

		// Cargo los adjuntos del item
		$im = $this->loadAdjuntos($im, $idList, $idArray);
		
		// Cargo los art relacionados del item
		$im = $this->loadNotas($im, $idList, $idArray);
		
		// Cargo los comentarios del item
		$im = $this->loadComentarios($im, $idList, $idArray);
		
		// Cargo el mapa asociado al item
		$im = $this->mapa($im, $idList, $idArray);
		
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
			$cacheData['facetas'] = $this->facetas;
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
	 * Inicializa todas las opciones básicas de la clase
	 *
	 * @access	private
	 */
	private function init() {

		$this->_init();

		// Listado de posibles errores
		$this->_errorArray[1] = "No se puede conectar con la base de datos";
		$this->_errorArray[2] = "No hay parametros de filtro definido";
		$this->_errorArray[3] = "No se ha ingresado el ID del artículo";
		$this->_errorArray[4] = "El ID del artículo no existe o es incorrecto";
		$this->_errorArray[5] = "Error en la consulta. No se puede retornar ningún resultado";

		$this->facetas = array();
		$this->orden = 'noticia_id desc';
		$this->_tipo = 1;	// 1:articulos
	
		$this->embed = '';
		$this->embedList = array();

		$this->itemAutor = '';
		$this->itemEstado = 1;
		$this->itemFacetas = '';
		$this->itemFuente = '';
		$this->itemId = 0;
		$this->itemObject = '';
		
		$this->itemResaltado = '';
		$this->itemResaltadoTag = 'cite';
		$this->itemResaltadoClass = 'resaltado';

		$this->itemSeccion = 0;
		$this->itemSQLExtra = '';
		$this->itemTags = '';
		$this->itemTexto = '';
		$this->itemTextoTipo = 'and';
		$this->itemTipo = 0;
		$this->itemTitulo = '';
		$this->itemUbicacion = 0;
		$this->itemUserId = 0;
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

		if ($this->itemAutor != '') {
		
			$this->log.= '- Filtro asignado: <b>Filtro por autor</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemAutor.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemAutor);

			$this->log.= '- Autor buscar:'.$texto.'<br/>';

			$sql[] = "noticia_autor = '{$texto}'";
		}
		
		if ($this->itemEstado !== false) {
			if ($oSql = $this->filterNumeric($this->itemEstado, 'estado', 'noticia_estado')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemFuente != '') {
		
			$this->log.= '- Filtro asignado: <b>Filtro por fuente</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemFuente.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemFuente);

			$this->log.= '- Fuente a buscar:'.$texto.'<br/>';

			$sql[] = "noticia_fuente = '{$texto}'";
		}

		if ($this->itemId) {
			if ($oSql = $this->filterNumeric($this->itemId, 'Id', 'noticia_id')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemObject != '') {
			
			$this->log.= '- Filtro asignado: <b>búsqueda por objetos asociados</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemObject.'<br/>';
			
			$oObjs = explode(',', $this->itemObject);
			$oObjSearch = array();
			foreach($oObjs as $oId=> $o) {
				$o = trim($o);
				$oObjSearch[] = "adjunto_tipo = '{$o}'";
			}
			$oSql = implode(' OR ', $oObjSearch);
			
			if ($oSql != '') {

				$oObj='';
			
				$oSql = "SELECT DISTINCT(adjunto_noticia_id)
					FROM {$this->_table}noticias_multimedia
					WHERE adjunto_noticia_tipo = '{$this->_tipo}' AND {$oSql}";
				if ($res = $this->db->query($oSql)){
					if ($this->db->num_rows($res)) {
						for ($i=0; $i<$this->db->num_rows($res);$i++) {
							$rs = $this->db->next($res);
							$oObj.= ','.$rs["adjunto_noticia_id"];
						}
						
						$oObj = substr($oObj,1);
					}
				}
				
				if ($oObj == '') {
					$this->log.= '- No existen artículos con los objetos solicitados.<br/>';
					$this->log.= '- Se asigna la búsqueda de noticia_id = 0 <br/>';
					
					$sql[] = "noticia_id = '0'";
				} else {
					$sql[] = "noticia_id IN({$oObj})";
				}
			}
		}

		if ($this->itemSeccion) {
			if ($oSql = $this->filterNumeric($this->itemSeccion, 'seccion', 'noticia_seccion_id')) {
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
				$oTags = '';
			
				$oSql = "SELECT adjunto_noticia_id 
					FROM {$this->_table}noticias_tags, {$this->_table}noticias_galeria_tags 
					WHERE adjunto_tag_id = tag_id 
						AND tag_tipo = {$this->_tipo} 
						AND tag_nombre = '{$texto}'";

				if ($res = $this->db->query($oSql)){
					if ($this->db->num_rows($res)) {
						for ($i=0; $i<$this->db->num_rows($res);$i++) {
							$rs = $this->db->next($res);
							$oTags.= ','.$rs['adjunto_noticia_id'];
						}
						
						$oTags = substr($oTags,1);
					}
				}
				
				if ($oTags == '') {
					$this->log.= '- No existen artículos con la etiqueta.<br/>';
					$this->log.= '- Se asigna la búsqueda de noticia_id = 0 <br/>';
					
					$sql[] = "noticia_id = '0'";
				} else {
					$sql[] = "noticia_id IN({$oTags})";
				}
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

				$sql[] = "MATCH (noticia_volanta, noticia_titulo, noticia_bajada,
					noticia_texto, noticia_texto_complementario) 
				AGAINST ('{$texto}' IN BOOLEAN MODE)";
			}
		}

		if ($this->itemTipo) {
			if ($oSql = $this->filterNumeric($this->itemTipo, 'tipo', 'noticia_tipo')) {
				$sql[] = $oSql;
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

		if ($this->itemUbicacion) {
			if ($oSql = $this->filterNumeric($this->itemUbicacion, 'ubicación', 'noticia_ubicacion_id')) {
				$sql[] = $oSql;
			}
		}		

		if ($this->itemUserId) {
			if ($oSql = $this->filterNumeric($this->itemUserId, 'user Id', 'noticia_user_id')) {
				$sql[] = $oSql;
			}
		}

		$this->log.= '- Asignación de filtros OK <br/>';

		return $sql;		
	}
	
	/** 
	 * Recupera HTML embebido en el cuerpo del item
	 *
	 * @access	private
	 * @param	string	$tipo	Tipo de objeto a seleccionar (adj, map, etc)
	 * @param	integer	$id		ID del objeto
	 * @param	string	$texto	(opcional) texto adjunto como pie de foto, etc
	 * @return	string	Código HTML del objeto embebido
	 */
	private function embedHTML($tipo='', $id='', $texto='') {

		$this->log.= '- Recuperando información de objetos embebidos en el HTML.<br/>';
		$this->log.= '- Información recibida. Tipo'.$tipo.' - Id: '.$id;
		
		$ret = '';

		if ($this->embed != '') {
		
			$this->log.= '- Path donde estan ubicados los objetos:'.$this->embed.'<br/>';
		
			// Cargo los adjuntos embebidos en el texto
			if ($tipo == 'adj' && $id != '' && isset($this->_objeto['adjunto'])) {

				$obj = $this->_objeto['adjunto'];
				$obj->itemId = $id;
				$dataObj = $obj->process();
				
				$this->log.= '------------------------------- <br/>';
				$this->log.= '--- Información del log de adjunto embebido --- <br/>';
				$this->log.= $obj->log;
				$this->log.= '--- Fin log de adjunto --- <br/>';
				$this->log.= '------------------------------- <br/>';
				
				$tipo = isset($dataObj[0]['gal_tipo']) ? $dataObj[0]['gal_tipo'] : false;
			}

			// Cargo los textos embebidos en el texto
			if ($tipo == 'art' && $id != '') {

				$obj = $this->_objeto['nota'];
				$obj->itemId = $id;
				$dataObj = $obj->process();
				
				$this->log.= '------------------------------- <br/>';
				$this->log.= '--- Información del log de nota embebida  --- <br/>';
				$this->log.= $obj->log;
				$this->log.= '--- Fin log de adjunto --- <br/>';
				$this->log.= '------------------------------- <br/>';
				
				$tipo = isset($im[0]['noticia_id']) ? 'art' : false;
			}
			
			if($tipo) {
			
				$this->log.= '- Objeto tipo'.$tipo.' recuperado OK<br/>';

				$this->embedList[$tipo][] = $dataObj[0];

				$fileName = $this->embed.'/interior-emb-'.$tipo.'.inc.php';
				
				$this->log.= '- Archivo a embeber:'.$fileName.' <br/>';
				
				if (file_exists($fileName)) {
				
					$this->log.= '- Archivo cargado OK.<br/>';
				
					$dataToSkin = $dataObj[0];
					$dataToSkin['texto'] = trim($texto);
					ob_start();
					include $fileName;
					$ret = ob_get_contents();
					ob_end_clean();
				} else {
				
					$this->log.= '- El archivo a embeber no existe. <br/>';
				}
			}

		} else {
			$this->log.= '- La ruta de los archivos a embeber no esta definida.<br/>';
			$this->log.= '- No se retornó ningún objeto.<br/>';
		}

		return $ret;
	}

	/**
	 * Recupera información del mapa asociado al item
	 *
	 * @access	protected
	 * @param	array	$im		Datos de la matriz de items
	 * @param	string	$ids	Listado de Ids de los items
	 * @param	array	$idarr	Matriz de enlaces entre id y posición
	 * @return	array	Matriz de datos de items con mapa
	 */
	protected function mapa($im, $ids='', $idarr=false) {
		if ($ids == '') {
			return $im;
		}
		
		$this->log.= "- Recuperando el mapa asociados al item.<br/>";
		$oSql = "SELECT * 
			FROM {$this->_table}noticias_geomap
			WHERE mapa_noticia_id IN ({$ids}) 
				AND mapa_tipo_id = '{$this->_tipo}'
		ORDER BY mapa_orden";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;

		} else {

			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$ors = $this->db->next($res);
				
				$posicion = $idarr[$ors['mapa_noticia_id']];
				$im[$posicion]['gmap'][$ors['mapa_orden']] = $ors;
			}
		}

		return $im;
	}
}
?>