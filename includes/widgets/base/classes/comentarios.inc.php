<?php
/**
 * Clase base para el procesamiento de comentarios del item
 *
 * Recupera información de los comentarios a los items
 *
 * <b>Que hace?</b> <br/>
 * Recupera en forma de array, información de los comentarios
 * Permite recuperar estos en forma de array o dentro de un item asociado.
 *
 * <b>Cómo se usa:</b> <br>
 * Recuperando información de comentarios
 * <code>
 * $gd = New Comentarios();
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
 *	$gdadj = New Comentarios();
 *	$gdadj->db = $db;
 * 	$gd->addobj("comentarios", $gdadj);	// Forma de asociarlo
 *
 * $data = $gd->process();
 * </code>
 * 
 * <b>Parámetros por defecto:</b> <br/>
 * <ul>
 * <li><b>badWordsReplace</b> : '*'</li>
 * <li><b>itemEstado</b> : '1,2,3,5'</li>
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
 * <li>09.04.2012 <br/>
 * - Modify: Se optimizó la clase y se le actualizó la documentación.</li>
 *
 * <li>21.03.2012 <br/>
 *	- Modify: Se optimizó el funcionamiento de la clase. </li>
 *
 * <li>13.02.2012<br/>
 *	- Modify: Se actualizó la clase comentarios extrayendola de las clases noticias</li>
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
 * @version		1.0	- last revision 2012.04.09
 */
class Comentarios extends CubePHP {

	/**
	 * Listado de palabras prohibidas
	 *
	 * @access public
	 * @var array
	 */
	public $badWords;

	/**
	 * Caracteres por los que se reemplazan las palabras prohibidas
	 *
	 * @access public
	 * @var string
	 */
	public $badWordsReplace;

	/**
	 * Filtro por id del item asociado
	 * Permite un solo valor, o multiples separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */	
	public $itemAsocId;
	
	/**
	 * Filtro por estado del item (borrador, eliminaod, etc)
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
	 * Filtro por usuario creador del artículo
	 * Permite un usuario, o varios separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemUserId;
	
	/**
	 * Texto a mostrar en caso de que el comentario se encuentre denunciado
	 *
	 * @access public
	 * @var string
	 */
	public $textoDenunciado;
	
	/**
	 * Texto a mostrar en caso de que el comentario se encuentre eliminado
	 *
	 * @access public
	 * @var string
	 */
	public $textoEliminado;

	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
	
		$this->_name = 'Comentarios';
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
		
		$oSql = "SELECT count(comentario_id) as total 
			FROM {$this->_table}noticias_comentarios, {$this->_table}lectores
			WHERE comentario_lector_id = lector_id AND {$sqlWhereFinal}";

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
		$oSql = "SELECT {$this->_table}noticias_comentarios.*, {$this->_table}lectores.*
			FROM {$this->_table}noticias_comentarios, {$this->_table}lectores
			WHERE comentario_lector_id = lector_id AND {$sqlWhereFinal}
			{$sqlOrden}
			{$sqlLimit}";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;
		}
		
		$this->log.= '- Recuperando información final<br/>';
		$im = array();

		$idList = '';	// Listado de ids para luego agregar información
		$idArray = array();	// Ubicación del Id para luego agregarle información

		$totalComentarios = $this->db->num_rows($res);
		
		if ($totalComentarios) {

			for ($i=0; $i<$totalComentarios;$i++) {
				$rs = $this->db->next($res);

				// Formatea el comentario
				$rs = $this->formatComentario($rs);

				$rs['comentario_numero'] = $totalComentarios - $i;

				// Retorna las respuestas del comentario, 
				// solo si el comentario esta aprobado
				if ($rs['comentario_estado'] == 1 
					|| $rs['comentario_estado'] == 5) {

					$rs = $this->respuestas($rs);
				}
				
				$im[$i] = $rs;
				$idList.= ','.$rs['comentario_id'];
				$idArray[$rs['comentario_id']] = $i;
			}
			$idList = substr($idList,1);
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
		$this->_errorArray[1] = 'No se puede conectar con la base de datos';
		$this->_errorArray[2] = 'No hay parametros de filtro definido';
		$this->_errorArray[3] = 'No se ha ingresado el ID del artículo';
		$this->_errorArray[4] = 'El ID del artículo no existe o es incorrecto';
		$this->_errorArray[5] = 'Error en la consulta. No se puede retornar ningún resultado';

		$this->orden = 'comentario_fecha_hora Desc, comentario_id Desc';
		$this->_tipo = 5;	// 5:comentarios

		$this->textoEliminado = 'El comentario del usuario {usuario} ha sido reportado como abuso y se encuentra a la espera de revisión.';
		$this->textoDenunciado = 'El comentario del usuario {usuario} no será publicado ya que este no encuadra dentro de normas acordadas de publicación preestablecidas.';
		
		$this->badWords = array();
		$this->badWordsReplace = '*';
		
		$this->itemAsocId = 0;
		$this->itemEstado = '1,2,3,5';	// aprobados, denunciados, eliminados, reaprobados
		$this->itemId = 0;
		$this->itemSQLExtra = '';
		$this->itemTexto = '';
		$this->itemTextoTipo = 'and';
		$this->itemTipo = 1;	// Indica el tipo de item (art, adjunto, etc)
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
		
		if ($this->itemEstado !== false) {
			if ($oSql = $this->filterNumeric($this->itemEstado, 'estado', 'comentario_estado')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemId) {
			if ($oSql = $this->filterNumeric($this->itemId, 'Id', 'comentario_id')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemAsocId) {
			if ($oSql = $this->filterNumeric($this->itemAsocId, 'Id', 'comentario_noticia_id')) {
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

				$sql[] = "comentario_texto LIKE '%{$texto}%'";
			}
		}
		
		if ($this->itemUserId) {
			if ($oSql = $this->filterNumeric($this->itemUserId, 'user Id', 'comentario_lector_id')) {
				$sql[] = $oSql;
			}
		}

		// En esta etapa muestro siempre los comentarios de primer nivel
		$sql[] = "comentario_respuesta_comentario_id = '0'";
		
		$this->log.= '- Asignación de filtros OK <br/>';

		return $sql;		
	}
	
	/**
	 * Recupera información de los comentarios anidados (Respuestas)
	 *
	 * @access	private
	 * @param	$rs		Datos del comentario a revisar
	 * @return	array	Datos con el comentario anidado
	 */
	private function respuestas($rs=false) {
		
		$this->log.= '- Consulto si el comentario posee respuesta <br/>';

		$sqlExtra = '';
		if ($this->itemEstado !== false) {
			if ($oSql = $this->filterNumeric($this->itemEstado, 'estado', 'comentario_estado')) {
				$sqlExtra = ' AND '.$oSql;
			}
		}
		
		$oSql = "SELECT {$this->_table}noticias_comentarios.*, {$this->_table}lectores.*
			FROM {$this->_table}noticias_comentarios, {$this->_table}lectores
			WHERE comentario_lector_id = lector_id 
				AND comentario_respuesta_comentario_id = '{$rs["comentario_id"]}'
				AND comentario_noticia_id = '{$rs["comentario_noticia_id"]}'
				AND comentario_tipo = '{$rs["comentario_tipo"]}'
				{$sqlExtra}
			ORDER BY comentario_id";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return $rs;	// retorno los comentarios hasta el momento
		}
		
		$this->log.= '- Recuperando información final<br/>';
		$im = array();

		$totalComentarios = $this->db->num_rows($res);
		
		if ($totalComentarios) {
			for ($i=0; $i<$totalComentarios;$i++) {
				$rst = $this->db->next($res);

				// Formatea el comentario
				$rst = $this->formatComentario($rst);

				$rst['comentario_numero'] = $totalComentarios - $i;

				// Retorna las respuestas del comentario, 
				// solo si el comentario esta aprobado
				if ($rst['comentario_estado'] == 1 
					|| $rst['comentario_estado'] == 5) {

					$rst = $this->respuestas($rst);
				}

				$im[$i] = $rst;
			}

			$rs['comentario_respuestas'] = $im;
		}

		return $rs;
	}
	
	/**
	 * Formatea todos los campos del comentario
	 *
	 * @access	private
	 * @param	$rs		Datos del comentario a procesar
	 * @return	array	Datos del comentario formateado
	 */
	private function formatComentario($rs) {
	
		foreach ($rs as $j => $s) {
			if (is_string($s) && $s != '') {

				// Aplica el formato negrita, cursiva, etc y los saltos de líneas
				$s = htmlspecialchars_decode($s, ENT_QUOTES);
				$s = str_replace("\n", "<br/>", $s);
				
				// Aplico vínculos cliqueables al texto
				if ($j != 'lector_email' && $j != 'lector_web') {
					$s = clickable($s);
				}

				// cierro los tags que puedan haber quedado abiertos
				$s = closeTags($s);

				$rs[$j] = $s;
			}
		}

		/* Modificación según el estado del comentario */
		switch ($rs['comentario_estado']) {
			case 1:	// aprobado
			case 5:	// reaprobado de una denuncia
			default: // Pendiente de aprobación (puede ser com simulado)
				$rs['comentario_texto'] = $this->censura($rs['comentario_texto'], $this->badWords, $this->badWordsReplace);
			break;

			case 2:	// denunciado
				$rs['comentario_texto'] = str_replace('{usuario}', $rs['lector_usuario'], $this->textoDenunciado);
			break;

			case 3:	// eliminado 
				$rs['comentario_texto'] = str_replace('{usuario}', $rs['lector_usuario'], $this->textoEliminado);
			break;

			case 4:	// Pendiente de re-aprobación (desde una denuncia)
			break;
		}		
		
		return $rs;
	}
	
	/**
	 * Realiza la censura del texto
	 *
	 * @access	private
	 * @param	$texto		string	Texto a revisar 
	 * @param	$palabras	array	Listado de palabras prohibidas
	 * @param	$reemplazo	string	Caracter o palabra por la que se reemplaza
	 * @return	string		Texto con las palabras prohibidas reemplazadas
	 */
	private function censura($texto='', $palabras, $reemplazo='*') {
		if ($texto == '') {
			return $texto;
		}
		
		// consulta que tenga listado de badwords, sino cargo de la dbase
		if (!is_array($palabras) || count($palabras) < 1) {
			$oSql = 'SELECT * FROM badwords';
			$res = $this->db->query($oSql);
			
			if ($this->db->num_rows($res)) {
			
				$palabras = array();

				for ($i=0; $i<$this->db->num_rows($res); $i++) {
					$rs = $this->db->next($res);
					
					$palabras[] = $rs['bad_nombre'];
				}

				$this->badWords = $palabras;
			}
		}
		
		$texto = ' '.$texto.' ';
		foreach ($palabras as $badword) {
			$texto = str_replace(' '.$badword.' ', ' '.str_repeat($reemplazo, strlen($badword)).' ', $texto);
		}

		return trim($texto);
	}
}
?>