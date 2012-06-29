<?php
/**
 * Clase para el manejo de items tipo encuestas
 *
 * Recupera informaci�n de las encuestas del sitio
 *
 * <b>Que hace?</b> <br/>
 * Recupera en forma de array, datos de las encuestas del sitio
 * Permite recuperar estos en forma de array o dentro de un item asociado.
 *
 * <b>C�mo se usa:</b> <br>
 * Recuperando encuestas
 * <code>
 * $gd = New Encuestas();
 * $gd->db = $db;	// Requiere conexi�n a base de datos
 * $gd->itemId = 1;
 * $data = $gd->process();
 * </code>
 *
 * Recuperando informaci�n de un item como adjunto asociado
 * <code>
 * $gd = New Notas();
 * $gd->db = $db;	// Requiere conexi�n a base de datos
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
 * <b>Par�metros por defecto:</b> <br/>
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
 *	- Modify: Se optimiz� la funci�n process 
 *	incorporando la funcion checkInit() en el core.</li>
 * 
 * <li>09.08.2011 <br/>
 *	- Fix: Se repar� problema al intentar mostrar listado de votos 
 *	de una encuesta que todav�a no tiene votos. 
 *	(error de divisi�n por cero). </li>
 *
 * <li>15.12.2010 <br/>
 *	- Modify: reparaciones menores, de inicializaci�n de variables.</li>
 *
 * <li>19.11.2010 <br/>
 *	- Added: Se agreg� la variable "item_maximo" y "item_maximo_valor" 
 *	al visualizar una encuesta para indicar cual item es el m�s votado. <li/>
 *
 * <li>03.05.2010 <br/>
 *	- Modify: Se agreg� los de registro de actividad de la encuesta.
 *	- Modify: Se modific� la comprobaci�n cuando el ID de la encuesta 
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
	 * Guarda informaci�n de la �ltima encuesta cargada
	 *
	 * @access private
	 * @var array
	 */
	private $_data;

	/**
	 * Filtro por estado del art�culo (borrador, eliminaod, etc)
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
	 * Indica el tipo de secci�n de art�culo a devolver
	 * Puede ser un solo n�mero, o un string de secciones separados por coma
	 *
	 * @access public
	 * @var string
	 */
	public $itemSeccion;
	
	/**
	 * B�squeda personalizada con comandos SQL
	 *
	 * @access public
	 * @var string
	 */
	public $itemSQLExtra;

	/**
	 * Filtro por b�squeda de texto 
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
	 * Indica el tipo de art�culo a devolver
	 * Puede ser un solo n�mero o n�meros separados por coma
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
	 * Filtro por usuario creador del art�culo
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
		
			$this->log.= '- Filtro asignado: <b>Par�metros externos de SQL</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemSQLExtra.'<br/>';

			$sql[] = $this->itemSQLExtra;
		}

		if ($this->itemTexto != '') {
		
			$this->log.= '- Filtro asignado: <b>b�squeda por texto</b>.';
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

				$this->log.= '- Texto a env�ar para la b�squeda:'.$texto.'<br/>';

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

		$this->log.= '- Asignaci�n de filtros OK <br/>';

		return $sql;
	}
	
	/**
	 * Inicializa todas las opciones b�sicas de la clase
	 *
	 * @access	private
	 */
	private function init() {

		$this->_init();

		// Listado de posibles errores
		$this->_errorArray[1] = "No se puede conectar con la base de datos";
		$this->_errorArray[2] = "La encuesta no esta definida";
		$this->_errorArray[3] = "El item a votar no esta definido";
		$this->_errorArray[4] = "Error al cargar parametros de votaci�n";	// No se cargo el IP
		$this->_errorArray[5] = "La encuesta solicitada no existe";
		$this->_errorArray[6] = "Ud ya voto";
		$this->_errorArray[7] = "La encuesta solicitada se encuentra finalizada y no permite votaci�n";
		$this->_errorArray[8] = "El item que desea votar no existe";
		$this->_errorArray[9] = "Error interno. Su opci�n de voto no se puede guardar";
		$this->_errorArray[10] = "El c�digo ingresado es incorrecto";	// Error de Captcha

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
	
		$this->log.= '- inicio proceso para recuperar informaci�n de encuestas <br/>';
		
		// Revisi�n inicial
		if (!$this->checkInit()) {
			return false;
		}

		$sqlWhere = array();
		$sqlOrden = '';
		$sqlLimit = '';
		
		// Cargo los filtros
		$sqlWhere = $this->checkFilters();

		if (!count($sqlWhere)) {
			$this->log.= '- <b>Error:</b> No existe ning�n condicional (WHERE) definido<br/>';
		
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
		$this->log.= '- Total de p�ginas devueltas:'.$this->totalPaginas.' <br/>';

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
		
		$this->log.= '- Recuperando informaci�n final.<br/>';
		$im = array();
		
		$idList = '';	// Listado de ids para luego agregar informaci�n
		$idArray = array();	// Ubicaci�n del Id para luego agregarle informaci�n

		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$rs = $this->db->next($res);

				foreach ($rs as $j => $s) {
					if (strpos($j,'encuesta_') !== false && is_string($s) && $s != '') {

						// Aplica el formato negrita, cursiva, etc y los saltos de l�neas
						$s = htmlspecialchars_decode($s, ENT_QUOTES);
						$s = str_replace("\n", '<br/>', $s);
						
						// Aplico v�nculos cliqueables al texto
						$s = clickable($s);

						// cierro los tags que puedan haber quedado abiertos
						$s = closeTags($s);

						$rs[$j] = $s;
					}
				} // end for
				
				
				// C�lculo si esta pendiente de inicio
				if ($rs['encuesta_fecha_inicio'] > time()) {
				
					$rs['encuesta_estado'] = 0;
				
				// Encuesta activa
				} elseif ($rs['encuesta_fecha_fin'] > time()) {
				
					$rs['encuesta_estado'] = 1;

				// Encuesta finalizada
				} else {

					$rs['encuesta_estado'] = 2;
				}

				// Recupero informaci�n de los votos
				$this->log.= '- Recuperando informaci�n de los votos <br/>';
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
	 * @param	integer	$banned	D�as que inhabilito al usuario para votar
	 * @return	integer	True si se agreg� correctamente, false en caso contrario.
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

		// Guarda informaci�n del voto
		$this->log.= '- Guardando informaci�n del voto.<br/>';
		
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
		$this->log.= '- Informaci�n de la votaci�n ingresada con �xito. <br/>';
		$this->log.= '- Baneo al ip:'.$ip.' por '.$banned.' d�as <br/>';
		
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
	
		// Proceso de votaci�n finalizado con �xito.
		$this->log.= '- Proceso de votaci�n finalizado con �xito. <br/>';
		
		return true;
	}
}
?>