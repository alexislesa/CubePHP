<?php
/**
 * Clase para el manejo de avisos Clasificados del sitio
 *
 * <b>Que hace?</b> <br/>
 * Recupera o edita informaci�n de los avisos clasificados del sitio
 *
 * <b>C�mo se usa:</b> <br>
 * Declaraci�n m�nima para listadr avisos, sin filtros
 * <pre>
 * $gd = New Clasificados();
 * $gd->db = $db;
 * $dataToSkin = $gd->process();
 * </pre>
 *
 * <b>Par�metros por defecto:</b> <br/>
 * <ul>
 * <li><b>tablePrefix</b> Default:'' </li>
 * <li><b>cantidad</b> Default:0 </li>
 * <li><b>desplazamiento</b> Default:0 </li>
 * <li><b>orden</b> Default:'clasificado_id desc' </li>
 * <li><b>itemCategoria</b> Default:0 </li>
 * <li><b>itemCategoriaRecursiva</b> Default:true </li>
 * <li><b>itemConImagen</b> Default:false </li>
 * <li><b>itemDepartamento</b> Default:0 </li>
 * <li><b>itemDestacado</b> Default:0 </li>
 * <li><b>itemEstado</b> Default:0 </li>
 * <li><b>itemFormasEnvio</b> Default:0 </li>
 * <li><b>itemId</b> Default:0 </li>
 * <li><b>itemLocalidad</b> Default:0 </li>
 * <li><b>itemMedioPago</b> Default:0 </li>
 * <li><b>itemOperacion</b> Default:0 </li>
 * <li><b>itemPais</b> Default:0 </li>
 * <li><b>itemProvincia</b> Default:0 </li>
 * <li><b>itemSoloActivos</b> Default:1 </li>
 * <li><b>itemSQLExtra</b> Default:'' </li>
 * <li><b>itemTexto</b> Default:'' </li>
 * <li><b>itemTextoTipov'and' </li>
 * <li><b>itemTipo</b> Default:0 </li>
 * <li><b>itemTitulo</b> Default:'' </li>
 * <li><b>itemUserId</b> Default:0 </li>
 * </ul>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>16.05.2012 <br/>
 *	- Added: Se agreg� a la clase las funciones para salvar 
 *	informaci�n del aviso. </li>
 *
 * <li>02.05.2012 <br/>
 *	- Added: Se agreg� que el sistema devuelva en la variable precio: 
 *	el s�mbolo, el valor y los decimales separados </li>
 *
 * <li>10.04.2012 <br/>
 *	- Modify: Se optimiz� la funci�n process 
 *	incorporando la funcion checkInit() en el core.</li>
 * 
 * <li>20.03.2012 <br/>
 *	- Fix: Se corrigieron comprobaciones en categor�as y ubicaciones 
 *	para evitar ataques XSS.</li>
 *
 * <li>13.02.2012 <br/>
 *	- Modify. Se agreg� la clase a CubePHP <br/>
 * </li>
 * <li>01.01.2012 <br/>
 *	- <br/>
 * </li> 
 * </ul>
 *
 * @package		Core
 * @subpackage	Clasificados
 * @category	
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	01.01.2012
 * @version		1.0	- last revision 2012.02.20 
 */
class Clasificados extends CubePHP {

	/**
	 * Guarda informaci�n sobre las categorias utilizadas
	 *
	 * @access private
	 * @var array
	 */
	private $categoriaArr;
	
	/**
	 * Retorna informaci�n de las facetas entontradas en la consulta
	 *
	 * @access public
	 * @var array
	 */
	public $facetas;
	
	/** 
	 * Filtro de items por categoria del aviso
	 * Acepta una sola categor�a, o multiples separadas por coma
	 *
	 * @access public
	 * @var string
	 */
	public $itemCategoria;
	
	/** 
	 * Indica si la b�squeda de categor�as se realiza de forma recursiva o no.
	 *
	 * @access public
	 * @var boolean
	 */
	public $itemCategoriaRecursiva;	
	
	/** 
	 * Indica si filtra solo avisos con imagenes
	 * 
	 * @access public
	 * @var boolean
	 */
	public $itemConImagen;
	
	/**
	 * Filtro de avisos por ID del departamento (ubicaci�n)
	 * Acepta una sola variable
	 *
	 * @access public
	 * @var integer
	 */
	public $itemDepartamento;

	/** 
	 * Filtro que indica el tipo de destacado a mosrtar
	 * Permite n�meros de 0 a 5 (corresp. a cada tipo de destacado)
	 * 
	 * @access public
	 * @var integer
	 */
	public $itemDestacado;

	/**
	 * Filtro por estado del art�culo (borrador, eliminaod, etc)
	 * Permite un solo valor, o multiples separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemEstado;
	
	/** 
	 * Filtro de items por estado del aviso (nuevo, usado, etc)
	 * Acepta una sola variable, o multiples separados por coma
	 * 
	 * @access public
	 * @var string
	 */
	public $itemEstadoAviso;	
	
	/**
	 * Indica los filtros de facetas a retornar
	 * Permite multiples facetas separadas por coma (ej: mes, dia, seccion_id)
	 *
	 * @access public
	 * @var string
	 */
	public $itemFacetas;
	
	/** 
	 * Filtro que indica la forma de env�o a filtrar
	 * Permite n�meros de 0 a 5 (corresp. a cada tipo de env�o)
	 * 
	 * @access public
	 * @var integer
	 */
	public $itemFormasEnvio;	
	
	/**
	 * Id del item, permite multiples Ids separados por coma
	 *
	 * @access public
	 * @var integer|string
	 */
	public $itemId;
	
	/**
	 * Filtro de avisos por ID de la localidad
	 * Acepta una sola variable
	 *
	 * @access public
	 * @var integer
	 */
	public $itemLocalidad;
	
	/** 
	 * Filtro que indica el medio de pago a filtrar
	 * Permite n�meros de 0 a 5 (corresp. a cada tipo de pago)
	 * 
	 * @access public
	 * @var integer
	 */
	public $itemMedioPago;
	
	/** 
	 * Filtro de items por operaci�n del aviso (compra, venta, etc)
	 * Acepta una sola variable, o multiples separados por coma
	 * 
	 * @access public
	 * @var string
	 */
	public $itemOperacion;
	
	/**
	 * Filtro de avisos por ID del Pa�s
	 * Acepta una sola variable
	 *
	 * @access public
	 * @var integer
	 */
	public $itemPais;
	
	/**
	 * Filtro de avisos por ID de la provincia
	 * Acepta una sola variable
	 *
	 * @access public
	 * @var integer
	 */
	public $itemProvincia;

	/** 
	 * Flag que indica si muestra solo items activos
	 * 
	 * @access public
	 * @var boolean
	 */
	public $itemSoloActivos;	

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
	 * Indica el tipo de item a devolver
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
	 * Guarda informaci�n sobre las monedas utilizadas
	 *
	 * @access private
	 * @var array
	 */
	private $monedaArr;
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 */
	public function __construct() {
	
		$this->_name = 'Clasificados';
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
		
		$this->log.= '- Preparando consulta por facetas.<br/>';

		
		$oSql = "SELECT clasificado_tipo as tipo, clasificado_categoria as categoria, 
			clasificado_pais as pais, clasificado_provincia as provincia, 
			clasificado_departamento as departamento, clasificado_localidad as localidad, 
			clasificado_operacion as operacion, clasificado_estado as estado,
			clasificado_imagen as imagen
			FROM {$this->_table}clasificados
			WHERE {$sqlWhereFinal}";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(5);
			return false;
		}

		$this->totalResultados = $this->db->num_rows($res);

		$this->log.= '- Consulta SQL OK.<br/>';

		if (!$this->totalResultados) {
			$this->log.= '- No se encontraron resultados <br/>';
			return false;
		}
		
		$oFac = array();

		for ($i=0; $i<$this->totalResultados;$i++) {
			$rs = $this->db->next($res);
				
			foreach($rs as $k => $v) {
				$oFac[$k][$v] = !empty($oFac[$k][$v]) ? ($oFac[$k][$v]+1) : 1;
			}

			$this->facetas = $oFac;
		}
		
		$this->log.= '- Carga de informaci�n facetada finalizada OK. <br/>';

		if ($this->cantidad) {
			$this->totalPaginas = ceil($this->totalResultados / $this->cantidad);
		} else {
			$this->totalPaginas = $this->totalResultados;
		}
		
		$this->log.= '- Total de resultados encontrados:'.$this->totalResultados.' <br/>';
		$this->log.= '- Total de p�ginas devueltas:'.$this->totalPaginas.' <br/>';

		$this->log.= '- Generando consulta final <br/>';
		$oSql = "SELECT {$this->_table}clasificados.*, lectores.*, {$this->_table}categorias.*
			FROM {$this->_table}clasificados, lectores, {$this->_table}categorias
			WHERE clasificado_user_id = lector_id 
				AND clasificado_categoria = categoria_id 
				AND {$sqlWhereFinal}
			{$sqlOrden}
			{$sqlLimit}";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(5);
			return false;
		}
		
		$this->log.= '- Recuperando informaci�n final<br/>';
		$im = array();

		$idList = '';	// Listado de ids para luego agregar informaci�n
		$idArray = array();	// Ubicaci�n del Id para luego agregarle informaci�n

		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res);$i++) {
				$rs = $this->db->next($res);

				foreach ($rs as $j => $s) {
					if (strpos($j,'clasificado_') !== false && is_string($s) && $s != '') {

						// Aplica el formato negrita, cursiva, etc y los saltos de l�neas
						$s = htmlspecialchars_decode($s, ENT_QUOTES);
						$s = str_replace("\n", '<br/>', $s);
						
						// Aplico v�nculos cliqueables al texto
						$s = clickable($s);

						// cierro los tags que puedan haber quedado abiertos
						$s = closeTags($s);
						
						$rs[$j] = $s;
					}
				}
				
				$rs['moneda'] = $this->moneda($rs['clasificado_moneda']);
				$rs['categorias'] = $this->categorias($rs['clasificado_categoria']);
				$rs['ubicacion'] = $this->ubicaciones($rs['clasificado_localidad']);
				
				if ($rs['clasificado_fotos'] != '') {
					$rs['imagen'] = $this->formatImagenes($rs['clasificado_fotos']);
				}
				if ($rs['clasificado_videos'] != '') {
					$rs['videos'] = $this->formatVideos($rs['clasificado_videos']);
				}
				
				if ($rs['clasificado_maps'] != '') {
					$rs['gmap'] = $this->formatMapas($rs['clasificado_maps']);
				}
				
				// Proceso el precio
				$pr = explode('.', $rs['clasificado_precio_final']);
				$rs['precio']['simbolo'] = $rs['moneda']['moneda_simbolo'];
				$rs['precio']['valor'] = $pr[0];
				$rs['precio']['decimal'] = $pr[1];

				$im[$i] = $rs;
				$idList.= ','.$rs['clasificado_id'];
				$idArray[$rs['clasificado_id']] = $i;
			}

			$idList = substr($idList,1);
		}

		// Cargo los comentarios del item
		$im = $this->loadComentarios($im, $idList, $idArray);
		
		// Cargo informaci�n de estad�sticas del item
		$im = $this->stats($im, $idList, $idArray);
		return $im;
	}

	/**
	 * Inicializa todas las opciones b�sicas de la clase
	 *
	 * @access	private
	 */
	private function init() {
	
		$this->_init();

		// Listado de posibles errores
		$this->_errorArray[1] = 'No se puede conectar con la base de datos';
		$this->_errorArray[2] = 'No hay parametros de filtro definido';
		$this->_errorArray[3] = 'No se ha ingresado el ID del art�culo';
		$this->_errorArray[4] = 'El ID del art�culo no existe o es incorrecto';
		$this->_errorArray[5] = 'Error en la consulta. No se puede retornar ning�n resultado';

		$this->facetas = array();
		$this->orden = 'clasificado_id desc';
		$this->_tipo = 6;	// 6:clasificados
		
		$this->monedaArr = false;
		$this->categoriaArr = false;
		$this->ubicacionArr = false;

		$this->itemCategoria = 0;
		$this->itemCategoriaRecursiva = true;
		$this->itemConImagen = false;
		$this->itemDepartamento = 0;
		$this->itemDestacado = 0;
		$this->itemEstado = 0;
		$this->itemFormasEnvio = 0;
		$this->itemId = 0;
		$this->itemLocalidad = 0;
		$this->itemMedioPago = 0;
		$this->itemOperacion = 0;
		$this->itemPais = 0;
		$this->itemProvincia = 0;
		$this->itemSoloActivos = 1;
		$this->itemSQLExtra = '';
		$this->itemTexto = '';
		$this->itemTextoTipo = 'and';
		$this->itemTipo = 0;
		$this->itemTitulo = '';
		$this->itemUserId = 0;
	}
	
	/**
	 * Guarda informaci�n del aviso procesado
	 *
	 * @access	public
	 * @param	string	$datos
	 * @return	integer	Id del aviso procesado, false en caso de error.
	 */
	public function save($datos) {
		// Revisi�n inicial
		if (!$this->checkInit()) {
			return false;
		}
		
		$this->log.= '- Se inicia proceso de alta de aviso <br/>';
		
		$im = array();
		$im['id'] = '';
		$im['titulo'] = '';
		$im['texto'] = '';
		$im['tipo'] = 1;	// Por defecto venta directa
		$im['categoria'] = 0;
		$im['tienda'] = 0;	// Sin tienda
		$im['activo'] = 0;	// Inactivo
		$im['fecha_creacion'] = time();
		$im['fecha_modificacion'] = time();
		$im['fecha_inicio'] = 0;
		$im['fecha_final'] = 0;
		$im['duracion'] = 0;
		$im['republicacion'] = 0;
		$im['pais'] = 0;
		$im['provincia'] = 0;
		$im['departamento'] = 0;
		$im['localidad'] = 0;
		$im['cantidad_total'] = 1;
		$im['cantidad_actual'] = 1;
		$im['user_id'] = '';
		$im['moneda'] = 0;
		$im['precio_inicial'] = 0;
		$im['precio_final'] = 0;
		$im['precio_reserva'] = 0;
		$im['vendido'] = 0;	// El prod. no esta vendido
		$im['operacion'] = 0;	// Sin operacion seleccionado
		$im['estado'] = 0;	// Estado del aviso (nuevo, usado, etc)
		$im['producto_estado'] = 0;	// Pendiente de aprobaci�n, activo, etc
		$im['visitas'] = 0;
		$im['recomendaciones'] = 0;
		$im['comentarios'] = 0;
		$im['votos'] = 0;
		$im['votos_usuarios'] = 0;
		$im['punto_positivo'] = 0;
		$im['punto_negativo'] = 0;
		$im['punto_total'] = 0;
		$im['url_page'] = '';
		$im['imagen'] = '';
		$im['fotos'] = '';
		$im['videos'] = '';
		$im['maps'] = '';
		$im['medios_pagos_1'] = '';
		$im['medios_pagos_2'] = '';
		$im['medios_pagos_3'] = '';
		$im['medios_pagos_4'] = '';
		$im['medios_pagos_5'] = '';
		$im['formas_envio_1'] = '';
		$im['formas_envio_2'] = '';
		$im['formas_envio_3'] = '';
		$im['formas_envio_4'] = '';
		$im['formas_envio_5'] = '';
		$im['destacado_1'] = '';
		$im['destacado_2'] = '';
		$im['destacado_3'] = '';
		$im['destacado_4'] = '';
		$im['destacado_5'] = '';
		$im['contacto_1'] = '';
		$im['contacto_2'] = '';
		$im['contacto_3'] = '';
		$im['contacto_4'] = '';
		$im['contacto_5'] = '';
		$im['contacto_6'] = '';
		$im['contacto_7'] = '';
		$im['contacto_8'] = '';
		$im['contacto_9'] = '';
		$im['contacto_10'] = '';
		$im['extra_1'] = '';
		$im['extra_2'] = '';
		$im['extra_3'] = '';
		$im['extra_4'] = '';
		$im['extra_5'] = '';
		$im['extra_6'] = '';
		$im['extra_7'] = '';
		$im['extra_8'] = '';
		$im['extra_9'] = '';
		$im['extra_10'] = '';
		$im['extra_11'] = '';
		$im['extra_12'] = '';
		$im['extra_13'] = '';
		$im['extra_14'] = '';
		$im['extra_15'] = '';
		$im['extra_16'] = '';
		$im['extra_17'] = '';
		$im['extra_18'] = '';
		$im['extra_19'] = '';
		$im['extra_20'] = '';
		
		// Consulta si ya existen datos anteriores (si estoy editando).
		if ($im['id'] != '') {
		
			$this->log.= '- Ya existe un aviso clasificado con ese ID.';
			$this->log.= ' Se procede a levantar informaci�n del ID'.$im['id'].'.<br/>';

			$oSql = "SELECT * FROM {$this->_table}clasificados 
				WHERE clasificado_id = '{$im['id']}' LIMIT 0,1";

			$this->log.= '- Consulta SQL a ingresar:'.$oSql.' <br/>';
			if ($res != $this->db->query($oSql)) {
				$this->log.= '- Error al realizar la consulta. ';
				$this->log.= 'El sistema dice:'.$this->db->error($res).' <br/>';
				
				$this->error(5);
			} else {
				if ($this->db->num_rows($res)) {
					$rs = $this->db->next($res);

					foreach ($rs as $k => $v) {
						$k = str_replace('clasificado_', '', $k);
						$im[$k] = $v;
					}
				}
			}
		}
		
		
		$datos = cleanArray($datos);
		foreach ($im as $k => $v) {
			$datos[$k] = !empty($datos[$k]) 
					? (is_string($datos[$k]) ? trim($datos[$k]) : $datos[$k])
					: $v;
		}

		$oSql = "REPLACE INTO {$this->_table}clasificados SET
			clasificado_id = '{$im['id']}',
			clasificado_titulo = '{$im['titulo']}',
			clasificado_texto = '{$im['texto']}',
			clasificado_tipo = '{$im['tipo']}',
			clasificado_categoria = '{$im['categoria']}',
			clasificado_tienda = '{$im['tienda']}',
			clasificado_activo = '{$im['activo']}',
			clasificado_fecha_creacion = '{$im['fecha_creacion']}',
			clasificado_fecha_modificacion = '{$im['fecha_modificacion']}',
			clasificado_fecha_inicio = '{$im['fecha_inicio']}',
			clasificado_fecha_final = '{$im['fecha_final']}',
			clasificado_duracion = '{$im['duracion']}',
			clasificado_republicacion = '{$im['republicacion']}',
			clasificado_pais = '{$im['pais']}',
			clasificado_provincia = '{$im['provincia']}',
			clasificado_departamento = '{$im['departamento']}',
			clasificado_localidad = '{$im['localidad']}',
			clasificado_cantidad_total = '{$im['cantidad_total']}',
			clasificado_cantidad_actual = '{$im['cantidad_actual']}',
			clasificado_user_id = '{$im['user_id']}',
			clasificado_moneda = '{$im['moneda']}',
			clasificado_precio_inicial = '{$im['precio_inicial']}',
			clasificado_precio_final = '{$im['precio_final']}',
			clasificado_precio_reserva = '{$im['precio_reserva']}',
			clasificado_vendido = '{$im['vendido']}',
			clasificado_operacion = '{$im['operacion']}',
			clasificado_estado = '{$im['estado']}',
			clasificado_producto_estado = '{$im['producto_estado']}',
			clasificado_visitas = '{$im['visitas']}',
			clasificado_recomendaciones = '{$im['recomendaciones']}',
			clasificado_comentarios = '{$im['comentarios']}',
			clasificado_votos = '{$im['votos']}',
			clasificado_votos_usuarios = '{$im['votos_usuarios']}',
			clasificado_punto_positivo = '{$im['punto_positivo']}',
			clasificado_punto_negativo = '{$im['punto_negativo']}',
			clasificado_punto_total = '{$im['punto_total']}',
			clasificado_url_page = '{$im['url_page']}',
			clasificado_imagen = '{$im['imagen']}',
			clasificado_fotos = '{$im['fotos']}',
			clasificado_videos = '{$im['videos']}',
			clasificado_maps = '{$im['maps']}',
			clasificado_medios_pagos_1 = '{$im['medios_pagos_1']}',
			clasificado_medios_pagos_2 = '{$im['medios_pagos_2']}',
			clasificado_medios_pagos_3 = '{$im['medios_pagos_3']}',
			clasificado_medios_pagos_4 = '{$im['medios_pagos_4']}',
			clasificado_medios_pagos_5 = '{$im['medios_pagos_5']}',
			clasificado_formas_envio_1 = '{$im['formas_envio_1']}',
			clasificado_formas_envio_2 = '{$im['formas_envio_2']}',
			clasificado_formas_envio_3 = '{$im['formas_envio_3']}',
			clasificado_formas_envio_4 = '{$im['formas_envio_4']}',
			clasificado_formas_envio_5 = '{$im['formas_envio_5']}',
			clasificado_destacado_1 = '{$im['destacado_1']}',
			clasificado_destacado_2 = '{$im['destacado_2']}',
			clasificado_destacado_3 = '{$im['destacado_3']}',
			clasificado_destacado_4 = '{$im['destacado_4']}',
			clasificado_destacado_5 = '{$im['destacado_5']}',
			clasificado_contacto_1 = '{$im['contacto_1']}',
			clasificado_contacto_2 = '{$im['contacto_2']}',
			clasificado_contacto_3 = '{$im['contacto_3']}',
			clasificado_contacto_4 = '{$im['contacto_4']}',
			clasificado_contacto_5 = '{$im['contacto_5']}',
			clasificado_contacto_6 = '{$im['contacto_6']}',
			clasificado_contacto_7 = '{$im['contacto_7']}',
			clasificado_contacto_8 = '{$im['contacto_8']}',
			clasificado_contacto_9 = '{$im['contacto_9']}',
			clasificado_contacto_10 = '{$im['contacto_10']}',
			clasificado_extra_1 = '{$im['extra_1']}',
			clasificado_extra_2 = '{$im['extra_2']}',
			clasificado_extra_3 = '{$im['extra_3']}',
			clasificado_extra_4 = '{$im['extra_4']}',
			clasificado_extra_5 = '{$im['extra_5']}',
			clasificado_extra_6 = '{$im['extra_6']}',
			clasificado_extra_7 = '{$im['extra_7']}',
			clasificado_extra_8 = '{$im['extra_8']}',
			clasificado_extra_9 = '{$im['extra_9']}',
			clasificado_extra_10 = '{$im['extra_10']}',
			clasificado_extra_11 = '{$im['extra_11']}',
			clasificado_extra_12 = '{$im['extra_12']}',
			clasificado_extra_13 = '{$im['extra_13']}',
			clasificado_extra_14 = '{$im['extra_14']}',
			clasificado_extra_15 = '{$im['extra_15']}',
			clasificado_extra_16 = '{$im['extra_16']}',
			clasificado_extra_17 = '{$im['extra_17']}',
			clasificado_extra_18 = '{$im['extra_18']}',
			clasificado_extra_19 = '{$im['extra_19']}',
			clasificado_extra_20 = '{$im['extra_20']}'
		";
		
		$this->log.= '- Consulta SQL a ingresar:'.$oSql.' <br/>';
		if ($res != $this->db->query($oSql)) {
			$this->log.= '- Error al realizar la consulta. ';
			$this->log.= 'El sistema dice:'.$this->db->error($res).' <br/>';
			
			$this->error(5);
			return false;
		}

		$this->log.= '- Aviso guardado con �xito. <br/>';
		return true;
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

		if ($this->itemCategoria) {
			if ($oSql = $this->filterNumeric($this->itemCategoria, 'Categor�a', 'clasificado_categoria')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemConImagen) {
		
			$this->log.= '- Filtro asignado: <b>solo avisos con imagen</b><br/>';
			$sql[] = 'clasificado_imagen = 1';
		}
		
		if ($this->itemDepartamento) {
			if ($oSql = $this->filterNumeric($this->itemDepartamento, 'Departamento', 'clasificado_departamento')) {
				$sql[] = $oSql;
			}		
		}
		
		if ($this->itemDestacado 
			&& ($this->itemDestacado > 0 && $this->itemDestacado <= 5)) {
			
			$this->log.= '- Filtro asignado: <b>Solo destacados</b>. Valor ingresado:'.$this->itemDestacado.'<br/>';

			$sql[] = "clasificado_destacado_{$this->itemDestacado} = 1";
		}

		if ($this->itemEstado !== false) {
			if ($oSql = $this->filterNumeric($this->itemEstado, 'estado', 'clasificado_estado')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemFormasEnvio 
			&& ($this->itemFormasEnvio > 0 && $this->itemFormasEnvio <= 5)) {
			
			$this->log.= '- Filtro asignado: <b>Solo con env�os</b>. Valor ingresado:'.$this->itemFormasenvio.'<br/>';
			
			$sql[] = "clasificado_formas_envio_{$this->itemFormasEnvio} = 1";
		}		
		
		if ($this->itemId) {
			if ($oSql = $this->filterNumeric($this->itemId, 'Id', 'clasificado_id')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemLocalidad) {

			if ($oSql = $this->filterNumeric($this->itemLocalidad, 'localidad', 'clasificado_localidad')) {
				$sql[] = $oSql;
			}		
		}

		if ($this->itemMedioPago 
			&& ($this->itemMedioPago > 0 && $this->itemMedioPago <= 5)) {
			
			$this->log.= '- Filtro asignado: <b>Solo con medio de pago</b>.';
			$this->log.= 'Valor ingresado:'.$this->itemMedioPago.'<br/>';
			
			$sql[] = "clasificado_medios_pagos_{$this->itemMedioPago} = 1";
		}		
		
		if ($this->itemOperacion) {

			if ($oSql = $this->filterNumeric($this->itemOperacion, 'Operacion', 'clasificado_operacion')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemPais) {
			if ($oSql = $this->filterNumeric($this->itemPais, 'Pais', 'clasificado_pais')) {
				$sql[] = $oSql;
			}
		}
		
		if ($this->itemProvincia) {
			if ($oSql = $this->filterNumeric($this->itemProvincia, 'Provincia', 'clasificado_provincia')) {
				$sql[] = $oSql;
			}
		}
		
		if ($this->itemSoloActivos) {
		
			$this->log.= '- Filtro asignado: <b>Solo avisos activos</b>.';
			$this->log.= 'Valor ingresado:'.$this->itemSoloActivos.'<br/>';
		
			$sql[] = 'clasificado_activo = 1';
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

				$sql[] = "MATCH (clasificado_titulo, clasificado_texto) 
				AGAINST ('{$texto}' IN BOOLEAN MODE)";
			}
		}

		if ($this->itemTipo) {
			if ($oSql = $this->filterNumeric($this->itemTipo, 'tipo', 'clasificado_tipo')) {
				$sql[] = $oSql;
			}
		}

		if ($this->itemTitulo != '') {
		
			$this->log.= '- Filtro asignado: <b>Filtro por url friendly del titulo</b>.';
			$this->log.= ' Valor ingresado:'.$this->itemTitulo.'<br/>';

			// Limpio texto de Injection
			$texto = cleanInjection($this->itemTitulo);

			$this->log.= '- Titulo Url Friendly a buscar:'.$texto.'<br/>';

			$sql[] = "clasificado_page_url = '{$texto}'";
		}

		if ($this->itemUserId) {
			if ($oSql = $this->filterNumeric($this->itemUserId, 'user Id', 'clasificado_user_id')) {
				$sql[] = $oSql;
			}
		}

		$this->log.= '- Asignaci�n de filtros OK <br/>';

		return $sql;		
	}
	
	/**
	 * Recupera toda la informaci�n del �rbol de categor�as
	 *
	 * @access	public
	 * @param	integer	$id	Identificador de la categor�a
	 * @return	array	Informaci�n del �rbol de categor�as
	 */
	public function categorias($id=0) {
	
		if (!$id || !is_numeric($id)) {
			return false;
		}

		if (isset($this->categoriaArr[$id])) {
		
			return $this->categoriaArr[$id];

		} else {

			$this->log.= '- Retornando informaci�n de la categor�a: '.$id.' <br/>';
		
			$oSql = "SELECT * FROM categorias 
				WHERE categoria_id = '{$id}' LIMIT 0,1";
			
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
			
			$rs = $this->db->next($res);

			$im = array();
			if ($data = $this->categorias($rs['categoria_pertenece'])) {
				$im = $data;
			}
			$im[] = $rs;
			
			$this->categoriaArr[$id] = $im;
		}

		return $im;
	}
	
	/**
	 * Retorna informaci�n del precio / moneda del clasificado
	 *
	 * @access	private
	 * @param	integer	$id	Identificador de la moneda
	 * @return	array	Informaci�n de la moneda utilizada
	 */
	private function moneda($id=0) {

		if (!$id) {
			return false;
		}

		if (!$this->monedaArr) {
		
			$this->log.= '- Retornando informaci�n de las monedas utilizadas en el sitio <br/>';
		
			$oSql = 'SELECT * FROM monedas';

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

			$data = array();
			for ($i=0; $i<$this->db->num_rows($res); $i++) {
				$rs = $this->db->next($res);
				$data[$rs['moneda_id']] = $rs;
			}
			$this->monedaArr = $data;
		}
		
		return !empty($this->monedaArr[$id]) ? $this->monedaArr[$id] : false;
	}
	
	/**
	 * Genera array de imagenes
	 *
	 * @access	private
	 * @param	string	$data	Informaci�n serializada de las imagenes
	 * @return	array	Datos normalizados de imagenes
	 */
	private function formatImagenes($data) {
		$im[1] = unserialize($data);
		$im[1]['adjunto_descripcion'] = '';

		return $im;
	}
	
	/**
	 * Genera array de videos
	 *
	 * @access	private
	 * @param	string	$data	Informaci�n serializada de los videos
	 * @return	array	Datos normalizados de videos
	 */
	private function formatVideos($data) {
		$im[1] = unserialize($data);
		$im[1]['adjunto_descripcion'] = '';

		return $im;
	}
	
	/**
	 * Genera array de maps
	 *
	 * @access	private
	 * @param	string	$data	Informaci�n serializada del mapa
	 * @return	array	Datos normalizados del mapa
	 */
	private function formatMapas($data) {
		$im[1] = unserialize($data);
		return $im;
	}
}
?>