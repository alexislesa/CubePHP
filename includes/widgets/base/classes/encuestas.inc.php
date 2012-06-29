<?php
/**
 * Clase para el manejo de items tipo encuestas
 *
 * Recupera información de las encuestas del sitio
 *
 * <b>Que hace?</b> <br/>
 * Recupera en forma de array, datos de las encuestas del sitio
 * Permite recuperar estos en forma de array o dentro de un item asociado.
 *
 * <b>Cómo se usa:</b> <br>
 * Recuperando encuestas
 * <code>
 * $gd = New Encuestas();
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
 *	$gdadj = New Encuestas();
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
 * <li><b>orden</b> default: 'encuesta_id desc'</li>
 * <li><b>itemId</b> default:0</li>
 * <li><b>itemSQLExtra</b> default:''</li>
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
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimizó la función process 
 *	incorporando la funcion checkInit() en el core.</li>
 * 
 * <li>09.08.2011 <br/>
 *	- Fix: Se reparó problema al intentar mostrar listado de votos 
 *	de una encuesta que todavía no tiene votos. 
 *	(error de división por cero). </li>
 *
 * <li>15.12.2010 <br/>
 *	- Modify: reparaciones menores, de inicialización de variables.</li>
 *
 * <li>19.11.2010 <br/>
 *	- Added: Se agregó la variable "item_maximo" y "item_maximo_valor" 
 *	al visualizar una encuesta para indicar cual item es el más votado. <li/>
 *
 * <li>03.05.2010 <br/>
 *	- Modify: Se agregó los de registro de actividad de la encuesta.
 *	- Modify: Se modificó la comprobación cuando el ID de la encuesta 
 *	y del Item no existe. <li/>
 * </ul>
 *
 * @package		Core
 * @subpackage	Items
 * @category	Encuestas
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	19.03.2012
 * @version		1.0	- last revision 2012.02.19
 */
class Encuestas extends CubePHP {

	/**
	 * Guarda información de la última encuesta cargada
	 *
	 * @access private
	 * @var array
	 */
	private $_data;

	/**
	 * Filtro por estado del artículo (borrador, eliminaod, etc)
	 * Permite un solo valor, o multiples separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemEstado;
	
	/**
	 * Id del item, permite multiples Ids separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemId;

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

		$this->_name = 'Encuestas';
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

		if ($this->itemEstado !== false) {

			// Encuesta inactiva, pendiente de inicio
			if ($this->itemEstado == 0) {
				$sql[] = 'encuesta_fecha_inicio > UNIX_TIMESTAMP()';
			
			// Encuesta activa
			} elseif ($this->itemEstado == 1) {
				$sql[] = 'encuesta_fecha_inicio < UNIX_TIMESTAMP()';
				$sql[] = 'encuesta_fecha_fin > UNIX_TIMESTAMP()';
			
			// Encuesta finalizada
			} elseif ($this->itemEstado == 2) {
				$sql[] = 'encuesta_fecha_fin < UNIX_TIMESTAMP()';
			
			// Encuestas activas y finalizadas
			} elseif ($this->itemEstado == 3) {
				$sql[] = 'encuesta_fecha_inicio < UNIX_TIMESTAMP()';
			}
		}

		if ($this->itemId) {
			if ($oSql = $this->filterNumeric($this->itemId, 'Id', 'encuesta_id')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemSeccion) {
			if ($oSql = $this->filterNumeric($this->itemSeccion, 'seccion', 'encuesta_seccion')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemSQLExtra != '') {
		
			$this->log.= '- Filtro asignado: <b>Parámetros externos de SQL</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemSQLExtra.'<br/>';

			$sql[] = $this->itemSQLExtra;
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

				$sql[] = "(encuesta_titulo LIKE '%{$texto}%' OR encuesta_texto LIKE '%{$texto}%')";
			}
		}

		// Por el momento sin uso
		if ($this->itemTipo) {
			/*
			if ($oSql = $this->filterNumeric($this->itemTipo, "tipo", "noticia_tipo")) {
				$sql[] = $oSql;
			} */
		}

		// Por el momento sin uso
		if ($this->itemTitulo != '') {
			/*
			$this->log.= "- Filtro asignado: <b>Filtro por url friendly del titulo</b>.";
			$this->log.= " Valor ingresado:".$this->itemTitulo."<br/>";

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTitulo);

			$this->log.= "- Titulo Url Friendly a buscar:".$texto."<br/>";

			$sql[] = "noticia_page_url = '{$texto}'";
			*/
		}

		if ($this->itemUserId) {
			if ($oSql = $this->filterNumeric($this->itemUserId, 'user Id', 'encuesta_user_id')) {
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
		$this->_errorArray[1] = "No se puede conectar con la base de datos";
		$this->_errorArray[2] = "La encuesta no esta definida";
		$this->_errorArray[3] = "El item a votar no esta definido";
		$this->_errorArray[4] = "Error al cargar parametros de votación";	// No se cargo el IP
		$this->_errorArray[5] = "La encuesta solicitada no existe";
		$this->_errorArray[6] = "Ud ya voto";
		$this->_errorArray[7] = "La encuesta solicitada se encuentra finalizada y no permite votación";
		$this->_errorArray[8] = "El item que desea votar no existe";
		$this->_errorArray[9] = "Error interno. Su opción de voto no se puede guardar";
		$this->_errorArray[10] = "El código ingresado es incorrecto";	// Error de Captcha

		$this->_data = array();
		
		$this->_table = '';
		$this->orden = 'encuesta_id desc';
		$this->_tipo = 3;	// 3: encuestas

		$this->itemEstado = 1;
		$this->itemId = 0;
		
		$this->itemSeccion = 0;
		$this->itemSQLExtra = '';
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
	
		$this->log.= '- inicio proceso para recuperar información de encuestas <br/>';
		
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
		
		$oSql = "SELECT count(encuesta_id) as total 
			FROM {$this->_table}encuesta_lista
			WHERE {$sqlWhereFinal}";

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
		
		if ($this->cantidad) {
			$this->totalPaginas = ceil($this->totalResultados / $this->cantidad);
		} else {
			$this->totalPaginas = $this->totalResultados;
		}
		
		$this->log.= '- Total de resultados encontrados:'.$this->totalResultados.' <br/>';
		$this->log.= '- Total de páginas devueltas:'.$this->totalPaginas.' <br/>';

		$this->log.= '- Generando consulta final <br/>';
		$oSql = "SELECT {$this->_table}encuesta_lista.*
			FROM {$this->_table}encuesta_lista
			WHERE {$sqlWhereFinal}
			{$sqlOrden}
			{$sqlLimit}";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;
		}
		
		$this->log.= '- Recuperando información final.<br/>';
		$im = array();
		
		$idList = '';	// Listado de ids para luego agregar información
		$idArray = array();	// Ubicación del Id para luego agregarle información

		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$rs = $this->db->next($res);

				foreach ($rs as $j => $s) {
					if (strpos($j,'encuesta_') !== false && is_string($s) && $s != '') {

						// Aplica el formato negrita, cursiva, etc y los saltos de líneas
						$s = htmlspecialchars_decode($s, ENT_QUOTES);
						$s = str_replace("\n", '<br/>', $s);
						
						// Aplico vínculos cliqueables al texto
						$s = clickable($s);

						// cierro los tags que puedan haber quedado abiertos
						$s = closeTags($s);

						$rs[$j] = $s;
					}
				} // end for
				
				
				// Cálculo si esta pendiente de inicio
				if ($rs['encuesta_fecha_inicio'] > time()) {
				
					$rs['encuesta_estado'] = 0;
				
				// Encuesta activa
				} elseif ($rs['encuesta_fecha_fin'] > time()) {
				
					$rs['encuesta_estado'] = 1;

				// Encuesta finalizada
				} else {

					$rs['encuesta_estado'] = 2;
				}

				// Recupero información de los votos
				$this->log.= '- Recuperando información de los votos <br/>';
				$oSql = "SELECT voto_id as id, voto_opcion_nombre as valor, voto_opcion_cantidad as cantidad 
					FROM {$this->_table}encuesta_votos 
					WHERE voto_encuesta_id = '{$rs["encuesta_id"]}' 
					ORDER BY voto_id";

				$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
				if (!$resv = $this->db->query($oSql)) {
					$this->log.= '- Error al ejecutar la consulta.<br/>';
					$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

					$this->error(5);
					return false;
				}

				$itemMax = 0;
				$itemMaxId = 0;
				$itemMin = 0;
				$itemMinId = 0;
				$itemTotales = 0;
				$n = array();

				if ($this->db->num_rows($resv)) {
					for ($ii=0; $ii<$this->db->num_rows($resv);$ii++) {
						$rsv = $this->db->next($resv);
						
						if ($itemMax < $rsv['cantidad']) {
							$itemMax = $rsv['cantidad'];
							$itemMaxId = $rsv['id'];
						}

						if ($itemMin > $rsv['cantidad']) {
							$itemMin = $rsv['cantidad'];
							$itemMinId = $rsv['id'];
						}
						
						$rsv['factor'] = ($rsv['cantidad'] > 0) 
									? (round(($rsv['cantidad'] / $rs['encuesta_total_votos']), 2)) 
									: 0;
						$rsv['porcentaje'] = $rsv['factor'] * 100;
						$n[$rsv['id']] = $rsv;
					}
				}
				
				$rs['items'] = $n;
				$rs['items_totales'] = count($n);
				$rs['items_maximo'] = $itemMax;
				$rs['items_maximo_id'] = $itemMaxId;
				$rs['items_minimo'] = $itemMin;
				$rs['items_minimo_id'] = $itemMinId;

				$im[$i] = $rs;
				$idList.= ','.$rs['encuesta_id'];
				$idArray[$rs['encuesta_id']] = $i;
			}
			$idList = substr($idList,1);
		}

		// Cargo los adjuntos del item
		$im = $this->loadAdjuntos($im, $idList, $idArray);
		
		// Cargo los art relacionados del item
		$im = $this->loadNotas($im, $idList, $idArray);
		
		// Cargo los comentarios del item
		$im = $this->loadComentarios($im, $idList, $idArray);

		$this->_data = $im;

		return $im;		
	}

	/**
	 * Agrega un voto al item seleccionado
	 *
	 * @access	public
	 * @param	integer	$item	Identificador del item
	 * @param	integer	$banned	Días que inhabilito al usuario para votar
	 * @return	integer	True si se agregó correctamente, false en caso contrario.
	 */
	public function votar($item=0, $banned=30) {

		$this->log.= '- Inicio proceso para agregar voto a una encuesta <br/>';
		
		if (!$item || !is_numeric($item)) {
			// el item no esta definido
			$this->error(3);
			return false;
		}

		// Proceso la encuesta, para verificar los datos.
		if (!$this->process()) {
			// La encuesta no existe.
			$this->error(5);
			return false;
		}
		
		if (empty($this->_data[0]['items'][$item])) {
			// El item de la encuesta no existe.
			$this->error(8);
			return false;			
		}
		
		// verifico que el usuario no este baneado
		$this->log.= '- Consultando que el usuario no este baneado <br/>';

		$ip = $_SERVER['REMOTE_ADDR'];
		$tiempo = time() - (60 * 60 * 24 * $banned);
		
		$oSql = "SELECT acceso_ip FROM {$this->_table}encuesta_accesos 
			WHERE acceso_encuesta_id = '{$this->itemId}' 
				AND acceso_ip = '{$ip}'
				AND acceso_fecha > '{$tiempo}' 
			LIMIT 0,1";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(5);
			return false;
		}
		
		if ($this->db->num_rows($res)) {

			$this->log.= '- El IP:'.$ip.' se encuentra penalizado para votar.<br/>';

			$this->error(6);
			return false;
		}

		// Guarda información del voto
		$this->log.= '- Guardando información del voto.<br/>';
		
		$oSql = "UPDATE {$this->_table}encuesta_lista 
			SET encuesta_total_votos = (encuesta_total_votos + 1) 
			WHERE encuesta_id = '{$this->itemId}'";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';				
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(9);
			return false;
		}

		$oSql = "UPDATE {$this->_table}encuesta_votos 
			SET voto_opcion_cantidad = (voto_opcion_cantidad + 1) 
			WHERE voto_id = '{$item}'";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';				
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(9);
			return false;
		}
		
		// Baneo al usuario
		$this->log.= '- Información de la votación ingresada con éxito. <br/>';
		$this->log.= '- Baneo al ip:'.$ip.' por '.$banned.' días <br/>';
		
		$oSql = "INSERT INTO {$this->_table}encuesta_accesos 
			(acceso_encuesta_id, acceso_ip,acceso_fecha) 
			VALUES ('{$this->itemId}', '{$ip}', UNIX_TIMESTAMP())";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(9);
			return false;
		}
	
		// Proceso de votación finalizado con éxito.
		$this->log.= '- Proceso de votación finalizado con éxito. <br/>';
		
		return true;
	}
}
?>