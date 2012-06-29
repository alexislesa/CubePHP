<?php
/**
 * agrega, edita y elimina las noticias generadas por lectores
 * 
 * @package		Widgets
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	20.09.2010
 *
 * @changelog:
 *
 * @revision 	20.09.2010
 */

class lectornoticias {

	/**
	 * Objeto de conexión con la base de datos
	 *
	 * @access public
	 * @var object
	 */
	var $db;
	
	/**
	 * Array de datos para los adjuntos
	 *
	 * @access public
	 * @var array
	 */
	var $adj;
	
	/**
	 * Array de datos de configuración del panel StreamServer
	 *
	 * @access public
	 * @var array
	 */
	var $ss_config;
	
	/**
	 * Array con información sobre los permisos del usuario
	 *
	 * Informa sobre lo que puede hacer y no sobre las noticias
	 *
	 * @access public
	 * @var array
	 */
	var $usr_perm;
	
	/**
	 * Identificador de usuario
	 *
	 * Utilizado en la comprobación de edición y eliminación de noticias
	 * 
	 * @access public
	 * @var integer
	 */
	var $user_id;
	
	/**
	 * Prefijo de tabla de datos a utilizar
	 *
	 * Nombre de la tabla básica para quevolver información de las noticias
	 * Por defecto es: noticias
	 *
	 * @access public
	 * @var string
	 */
	var $nota_prefix;
	 
	/**
	 * Identificador de la noticia a procesar
	 *
	 * Utilizado cuando se desea consultar por una nota, y verificar permisos de edición y eliminado
	 * @access public
	 * @var integer
	 */
	var $nota_id;
	
	/**
	 * Array con información de la última nota guardada o devuelta
	 *
	 * @access public
	 * @var array
	 */
	var $datos;
	
	/**
	 * **********************************************************
	 * Información utilizada en el listado de artículos
	 * **********************************************************
	 */
	 
	/**
	 * Indica el tipo de nota a devolver
	 * Puede ser un solo número, o un array de tipos separados por coma
	 *
	 * @access public
	 * @string
	 */
	var $nota_tipo;

	/** 
	 * Indica el tipo de sección de nota a devolver
	 * Puede ser un solo número, o un array de secciones separados por coma
	 *
	 * @access public
	 * @var string
	 */
	var $nota_seccion;
	
	/**
	 * Indica los tags de las notas a devolver
	 * Acepta un solo parametro o varios separados por coma
	 *
	 * @access public
	 * @var string
	 */
	var $nota_tags;
	
	/**
	 * Filtra el resultado de las notas por las palabras ingresadas
	 *
	 * @access public
	 * @var string
	 */
	var $nota_searchtext;
	
	/**
	 * Indica como filtrar por palabras claves
	 * Acepta: AND/OR/ALL
	 * Valor por defecto: ALL
	 *
	 * @access public
	 * @var string
	 */
	var $nota_searchtipo;
	
	/**
	 * Nombre del objeto por el cual filtrar una nota
	 *
	 * Utilizado cuando quiero devolver todas las notas que contengan un objeto específico.
	 * indico el objeto tal como se guarda en la asociación de la nota (no el tipo de objeto)
	 * Acepta un solo item o varios separados por coma
	 *
	 * @access public
	 * @var string
	 */
	var $nota_searchobj;
	
	/**
	 * Filtro por usuario creador de la noticia
	 * 
	 * Acepta un solo usuario específicado
	 *
	 * @access public
	 * @var integer
	 */
	var $nota_user_id;
	
	/**
	 * Filtra por fecha inicial
	 *
	 * Filtra si la fecha de creación del artículo es mayor a la fecha dada
	 * Formato de la fecha: TIMESTAMP_UNIX
	 *
	 * @access public
	 * @var integer
	 */
	var $nota_fecha_inicial;
	
	/**
	 * Filtra por fecha final
	 *
	 * Filtra si la fecha de creación del artículo es menor a la fecha dada
	 * Formato de la fecha: TIMESTAMP_UNIX
	 *
	 * @access public
	 * @var integer
	 */
	var $nota_fecha_final;
	
	/**
	 * Me indica todos los posibles estados de la nota a devolver
	 *
	 * Acepta un solo item o varios separados por coma
	 * 
	 * @access public
	 * @var string
	 */
	var $nota_estado;
	
	/**
	 * Indica como ordenar los resultados
	 * En caso de ordenar por más de un parametro, debe estar separado por comas
	 *
	 * @access public
	 * @var string
	 */
	var $nota_orden;
	
	/**
	 * Cantidad de resultados a devolver
	 *
	 * @access public
	 * @var integer
	 */
	var $nota_cantidad;
	
	/**
	 * Indica el desplazamiento de los resultados a devolver
	 * Utilizado cuando los resultados se estan páginanado
	 *
	 * @access public
	 * @var integer
	 */
	var $nota_desplazamiento;
	
	/**
	 * Devuelve el número de resultados encontrado
	 *
	 * @access public
	 * @var integer
	 */
	var $total_resultados;
	
	/**
	 * Devuelve el total de páginas que coincidan con el filtro
	 *
	 * @access public
	 * @var integer
	 */
	var $total_paginas;
	
	
	/**
	 * **********************************************************
	 * Información utilizada en el manejo de errores
	 * **********************************************************
	 */
	
	/**
	 * Array de mensajes de error
	 * 
	 * En caso de modificar los mensajes de error que devuelve esta clase, esta variable sobre-escribe el array de estos mensajes
	 *
	 * @access public
	 * @var array
	 */
	var $error_array;
	
	/**
	 * Flag que me indica que se encontró un error.
	 *
	 * @access private
	 * @var boolean
	 */
	var $error_found;
	
	/**
	 * Variable con el mensaje o mensajes de error a mostrar.
	 * 
	 * @access public
	 * @var string
	 */
	var $error_msj;
	
	/**
	 * Flag utilizado cuando la consulta no generó ningún resultado
	 * Guarda true si consulta devuelve cero resultados, false si devuelve resultados
	 *
	 * @access public
	 * @var boolean
	 */
	var $nota_not_found;
	
	/**
	 * Flag para saber si estoy en modo test de errores
	 *
	 * @access public
	 * @var boolean
	 */
	var $debug;
	
	/**
	 * Utilizado en el debug, me devuelve el tiempo en segundos utilizado para realizar el proceso
	 *
	 * @access public
	 * @var float
	 */
	var $debug_time;
	
	/**
	 * Registra toda la actividad de la clase
	 *
	 * Guarda el registro en formato HTML
	 *
	 * @access public
	 * @var string
	 */
	var $log;
	
	/**
	 * Constructor de la clase, inicializa los parametros básicos de la clase
	 */
	function __construct() {

		$this->debug = false;
		$this->error_msj = "";
		$this->error_found = false;
		
		// Inicializo los parametros básicos de listado de artículos
		$this->init();

		// Defino la tabla de errores
		$this->error_array = array();
		$this->error_array[1] = "No existe ninguna base de datos definida";
		$this->error_array[2] = "No se puede cargar la tabla de permisos del usuario para la siguiente acción. Contactese con el administrador";
		$this->error_array[3] = "El ID del artículo no esta definido";
		$this->error_array[4] = "No tiene permisos para agregar artículos";
		$this->error_array[5] = "El usuario no esta definido";
		$this->error_array[6] = "El usuario no tiene permisos para editar el artículo";
		$this->error_array[7] = "El usuario no tiene permisos para eliminar el artículo";
		$this->error_array[8] = "El artículo no se puede eliminar porque se encuentra en las siguientes portadas: [x]";
		$this->error_array[9] = "Error al eliminar el artículo. No se pudo eliminar o se elimino parcialmente";
		$this->error_array[10] = "Error al leer los datos del artículo";
		$this->error_array[11] = "No se recibió ningún parametro. El artículo no se guardo";
		$this->error_array[12] = "Error al guardar los cambios en la base de datos. La información no se ha grabado";
		$this->error_array[13] = "El usuario no tiene permisos para ingresar a esta página";
		$this->error_array[14] = "Error al guardar información en la base de datos. No se guardó completamente";
	}
	
	
	/**
	 * Elimina el artículo seleccionado
	 *
	 * @access public
	 * @param $nota_id integer Opcional, Identificador del artículo, si no esta declarado, lo toma de $this->nota_id
	 * @return boolean True si se eliminó correctamente, False si hubo error
	 */
	function del($nota_id=false) {
		
		$id = ($nota_id) ? $nota_id : $this->nota_id;
		if (!$id) {
			$this->in_error(3);
			return false;
		}
		
		$sql = "DELETE FROM {$this->nota_prefix}_geomap WHERE mapa_noticia_id ='{$id}'";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(9);
			return false;
		}
	
		$sql = "DELETE FROM {$this->nota_prefix}_multimedia WHERE adjunto_noticia_id = '{$id}'";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(9);
			return false;
		}

		$sql = "DELETE FROM {$this->nota_prefix}_comentarios WHERE comentario_noticia_id = '{$id}'";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(9);
			return false;
		}
		
		$sql = "DELETE FROM {$this->nota_prefix}_tags WHERE adjunto_noticia_id = '{$id}'";
		if (!$res = $this->db->query($sql)){
			$this->in_error(9);
			return false;
		}
		
		$sql = "DELETE FROM {$this->nota_prefix} WHERE noticia_id ='{$id}'";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(9);
			return false;
		}
	
		return true;
	}


	/**
	 * Retorna todos los datos del artículo
	 *
	 * @access public
	 * @param $nota_id integer Opcional, identificador de artículo, si no se declara se toma de la variable  $this->nota_id
	 * @return array Matriz de datos del artículo, false si hubo algun error
	 */
	function data($nota_id = false) {
		
		$id = ($nota_id) ? $nota_id : $this->nota_id;
		if (!$id) {
			$this->in_error(3);
			return false;
		}
	
		$sql = "SELECT * FROM {$this->nota_prefix} WHERE noticia_id = '{$id}' LIMIT 0,1";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(10);
			return false;
		}
		$im = $this->db->next($res);

		/**
		 * Extraigo información de los tags del recurso.
		 */
		$sql = "SELECT tag_nombre FROM {$this->nota_prefix}_tags, {$this->nota_prefix}_galeria_tags 
			WHERE tag_id = adjunto_tag_id AND tag_tipo = 1 AND adjunto_noticia_id = '{$id}' 
			ORDER BY tag_nombre";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(10);
			return false;
		}
		
		$im["tags"] = "";
		if ($this->db->num_rows($res)) {
			for ($i=0; $i<$this->db->num_rows($res); $i++) {
				$rs = $this->db->next($res);
				$im["tags"].= ", ".$rs["tag_nombre"];
			}
			$im["tags"] = substr($im["tags"], 2);
		}
		
		/**
		 * Extraigo información de los adjuntos
		 */
		$im["adjuntos"] = $this->adjuntos($id);
	
		/**
		 * Cargo información del mapa
		 */
		$sql = "SELECT mapa_x, mapa_y, mapa_zoom, mapa_tipo FROM {$this->nota_prefix}_geomap WHERE mapa_noticia_id = '{$id}'";
		if (!$res = $this->db->query($sql)) {
			$this->in_error(10);
			return false;
		}
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);
			$im["mapa"] = $rs;
		}
		
		// Guardo un preliminar de la nota a devuelta
		$this->datos = $im;

		return $im;
	}

	/**
	 * Lista los artículos por los parametros definidos
	 *
	 * @access public
	 * @return array Listado de los artículos, false si hay error
	 */
	function listado() {

		if (!$this->user_id) {
			$this->in_error(5);
			return false;
		}
		
		$sql_where = array();
		$sql_orden = "";
		$sql_limit = "";
		$sql_where_final = "";
		
		// Filtro por tipo de nota
		if ($this->nota_tipo) {
			if (is_numeric($this->nota_tipo)) {
				$sql_where[] = "noticia_tipo = '{$this->nota_tipo}'";
			} else {
				$this->nota_tipo = trim($this->nota_tipo);
				$sql_where[] = "noticia_tipo IN ({$this->nota_tipo})";
			}
		}
		
		// Filtro por sección de la nota
		if ($this->nota_seccion) {
			if (is_numeric($this->nota_seccion)) {
				$sql_where[] = "noticia_seccion_id = '{$this->nota_seccion}'";
			} else {
				$this->nota_seccion = trim($this->nota_seccion);
				$sql_where[] = "noticia_seccion_id IN ({$this->nota_seccion})";
			}
		}
		
		// Filtro por objeto asociado
		// Acepta un item o varios separados por coma
		if ($this->nota_searchobj && $this->nota_searchobj != "") {
			$obj_list = explode(",", $this->nota_searchobj);
			$obj_search = array();
			foreach($obj_list as $oId=> $o) {
				$o = trim($o);
				$obj_search[] = "adjunto_tipo = '{$o}'";
			}
			$obj_sql = implode(" OR ", $obj_search);
			
			/*
			$sql_where[] = "noticia_id IN (SELECT adjunto_recurso_id
			FROM {$this->nota_prefix}_multimedia, {$this->nota_prefix}_galeria_multimedia
			WHERE gal_id = adjunto_multimedia_id AND ({$obj_sql}))";
			*/
		}
		
		
		// Filtro por palabras en el texto
		if ($this->nota_searchtext) {
			$textoBusca = trim($this->nota_searchtext);
			$textoBusca = html_entity_decode($textoBusca, ENT_QUOTES, "ISO-8859-1");
			$textoBusca = preg_replace('/&#(\d+);/me',"chr(\\1)", $textoBusca);
			$textoBusca = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $textoBusca);

			// Limpio de Injection
			$textoBusca = strip_tags($textoBusca);
			$textoBusca = addslashes($textoBusca);
			
			// Detecto algún tipo de injection
			$inyecc='/script|http|<|>|%3c|%3e|SELECT|UNION|UPDATE|AND|exe|exec|INSERT|tmp/i';
			if (preg_match($inyecc, $textoBusca)) {
				// Contiene código de injection
				/*
				$this->error_found = true;
				$this->error_msj = $this->error_array[3];
				return false;
				*/
			} 

			if ($textoBusca != "") {
				switch(strtolower($this->nota_searchtipo)) {
					case 'or':
					break;
						
					case 'and':
					default:
						$textoBusca = "+".$textoBusca;
						$textoBusca = str_replace(" ", " +",$textoBusca);
					break;

					case 'all':
						$textoBusca = "\"$textoBusca\"";
					break;
				}

				$sql_where[] = "MATCH (noticia_volanta, noticia_titulo, noticia_bajada, noticia_texto) AGAINST ('{$textoBusca}' IN BOOLEAN MODE)";

			} else {
				// Contiene código de injection
			}
		}
		
		// Filtro por etiquetas
		if ($this->nota_tags) {
			$sql_where[] ="noticia_id IN (
				SELECT adjunto_noticia_id 
				FROM {$this->nota_prefix}_tags, {$this->nota_prefix}_galeria_tags 
				WHERE tag_tipo = 1 AND adjunto_tag_id = tag_id AND tags_nombre = '{$this->nota_tags}'
			)";
		}

		// Filtro por usuario creador del artículo
		if ($this->nota_user_id && is_numeric($this->nota_user_id)) {
			$sql_where[] = "noticia_user_id = '{$this->nota_user_id}'";
		}
		
		// Filtro por fecha inicial
		if ($this->nota_fecha_inicial && is_numeric($this->nota_fecha_inicial)) {
			$sql_where[] = "noticia_fecha_creacion > {$this->nota_fecha_inicial}";
		}
		
		// Filtro por fecha final
		if ($this->nota_fecha_final && is_numeric($this->nota_fecha_final)) {
			$sql_where[] = "noticia_fecha_creacion < {$this->nota_fecha_final}";
		}
		
		// Filtro por estado del artículo
		if ($this->nota_estado) {
			if (is_numeric($this->nota_estado)) {
				$sql_where[] = "noticia_estado = {$this->nota_estado}";
			} else {
				$this->nota_estado = trim($this->nota_estado);
				$sql_where[] = "noticia_estado IN ({$this->nota_estado})";
			}
		}
		
		// Orden del listado
		if ($this->nota_orden) {
			$sql_orden = "ORDER BY {$this->nota_orden}";
		}
		
		// Cantidad y desplazamientos
		if ($this->nota_cantidad || $this->nota_desplazamiento) {
			$sql_limit = "LIMIT {$this->nota_desplazamiento}, {$this->nota_cantidad}";
		}
	
		if (count($sql_where)) {
			$sql_where_final = implode(" AND ", $sql_where);
			$sql_where_final = "WHERE ".$sql_where_final;
		}
		
		
		// Busco los datos para paginar
		$sql = "SELECT count(noticia_id) as total 
				FROM {$this->nota_prefix}
				{$sql_where_final}";
		$result = $this->db->query($sql);
		$rst = $this->db->next($result);
		$this->total_resultados = $rst["total"];
		
		if (!$this->total_resultados) {
			return false;
		}

		if ($this->nota_cantidad) {
			$this->total_paginas = @ceil($this->total_resultados / $this->nota_cantidad);
		} else {
			$this->total_paginas = 1;
		}
		
		// Genero el SQL final
		$sql = "SELECT *
			FROM {$this->nota_prefix}
			{$sql_where_final}
			{$sql_orden}
			{$sql_limit}";
		$result = $this->db->query($sql);

		$im = array();
		for ($a=0; $a<$this->db->num_rows($result); $a++) {
			$rs = $this->db->next($result);
			
			$im[] = $rs;
		}
		
		return $im;
	}
	
	/**
	 * Guarda los cambios en el artículo
	 *
	 * @access public
	 * @param $data Matriz de datos a guardar, generalmente los datos POST del formulario
	 * @return boolean true si se guardó correctamente, false si hubo error
	 */
	function save($data=false) {
		
		// No se recibió ningún parametro
		if (!$data || !is_array($data)) {
			$this->in_error(11);
			return false;
		}
		
		if (!$this->user_id) {
			$this->in_error(5);
			return false;
		}

		/**
		 * Consulto si estoy en modo Vista Preliminar o no
		 */
		$preliminar = (!empty($_GET["preview"])) ? true : false;
		$table_prefix = $preliminar ? "preliminar_" : "";
		
		/** 
		 * Valores por defecto
		 */
		$im = array();
		$im["id"] = "";
		$im["tipo"] = 0;
		$im["seccion_id"] = 0;
		$im["volanta"] = "";
		$im["titulo"] = "";
		$im["bajada"] = "";
		$im["texto"] = "";
		$im["texto_complementario"] = "";
		$im["ubicacion_id"] = "";
		$im["estado"] = 0;
		$im["comentarios"] = 1;
		$im["autor"] = "";
		$im["fuente"] = "";
		$im["fecha_texto"] = "";
		$im["fecha_inicio"] = 0;
		$im["fecha_fin"] = 0;
		$im["campo_1"] = "";
		$im["campo_2"] = "";
		$im["campo_3"] = "";
		$im["campo_4"] = "";
		$im["fecha_creacion"] = time();
		$im["fecha_modificacion"] = time();
		$im["user_id"] = $this->user_id;
		$im["last_user_id"] = $this->user_id;
		$im["page_url"] = "";
		
		foreach ($im as $k=> $v) {
			if (!isset($data[$k])) {
				$data[$k] = $im[$k];
			}
			
			if (is_string($data[$k])) {
				$data[$k] = trim($data[$k]);
				$data[$k] = htmlspecialchars($data[$k], ENT_QUOTES);
			}
		}
		
		// Fecha y hora de modificación de la noticia
		if (isset($data["fecha"]) && $data["fecha"] != "") {
			list($dia,$mes,$anio) = explode("/",$data["fecha"]);
			$hora = date("G");
			$min = date("i");
			
			if ($data["fecha_hora"] != "") {
				list($hora, $min) = explode(":", $data["fecha_hora"]);
			}
			$data["fecha_modificacion"] = mktime($hora,$min,0,$mes,$dia,$anio);
		}
		
		// Si tiene fecha de evento inicio cargado
		if (isset($data["evento_inicio"]) && $data["evento_inicio"] != "") {
			list($dia,$mes,$anio) = explode('/',$data["evento_inicio"]);
			
			$hora = 0;
			$min = 0;
			if (!empty($data["evento_inicio_hora"])) {
				list($hora, $min) = explode(":", $data["evento_inicio_hora"]);
			}
			$data["fecha_inicio"] = mktime($hora,$min,0,$mes,$dia,$anio);
		}
		
		// Si tiene fecha de evento final cargado
		if (isset($data["evento_final"]) && $data["evento_final"] != "") {
			list($dia,$mes,$anio) = explode('/',$data["evento_final"]);
			
			$hora = 23;
			$min = 59;
			if (!empty($data["evento_final_hora"])) {
				list($hora, $min) = explode(":", $data["evento_final_hora"]);
			}
			$data["fecha_fin"] = mktime($hora,$min,0,$mes,$dia,$anio);
		}

		$sql = "REPLACE INTO {$this->nota_prefix} (
			noticia_id,
			noticia_tipo,
			noticia_seccion_id,
			noticia_volanta,
			noticia_titulo,
			noticia_bajada,
			noticia_texto,
			noticia_texto_complementario,
			noticia_ubicacion_id,
			noticia_estado,
			noticia_comentarios,
			noticia_autor,
			noticia_fuente,
			noticia_fecha_texto,
			noticia_fecha_inicio,
			noticia_fecha_fin,
			noticia_campo_1,
			noticia_campo_2,
			noticia_campo_3,
			noticia_campo_4,
			noticia_fecha_creacion,
			noticia_fecha_modificacion,
			noticia_user_id,
			noticia_last_user_id,
			noticia_page_url
			) VALUES (
			'{$data["id"]}',
			'{$data["tipo"]}',
			'{$data["seccion_id"]}',
			'{$data["volanta"]}',
			'{$data["titulo"]}',
			'{$data["bajada"]}',
			'{$data["texto"]}',
			'{$data["texto_complementario"]}',
			'{$data["ubicacion_id"]}',
			'{$data["estado"]}',
			'{$data["comentarios"]}',
			'{$data["autor"]}',
			'{$data["fuente"]}',
			'{$data["fecha_texto"]}',
			'{$data["fecha_inicio"]}',
			'{$data["fecha_fin"]}',
			'{$data["campo_1"]}',
			'{$data["campo_2"]}',
			'{$data["campo_3"]}',
			'{$data["campo_4"]}',
			'{$data["fecha_creacion"]}',
			'{$data["fecha_modificacion"]}',
			'{$data["user_id"]}',
			'{$data["last_user_id"]}',
			'{$data["page_url"]}'
		)";

		if (!$res = $this->db->query($sql)) {
			$this->in_error(12);
			return false;
		}
	
		$new_articulo = false;
		if (!is_numeric($data["id"])) {
			$new_articulo = true;
			$data["id"] = $this->db->last_insert_id();
		}
		
		/**
		 * Si estoy editando un artículo, elimino todos los adjuntos cargados
		 */
		if (!$new_articulo) {
			// Elimino todos los objetos adjuntos que pueda tener esta nota
			$sql = "DELETE FROM {$this->nota_prefix}_multimedia WHERE adjunto_noticia_id = '{$data["id"]}'";
			if (!$res = $this->db->query($sql)) {
				// Error al eliminar información del artículo
			}
		}
		
		/** 
		 * Cargo todos los adjuntos del artículo (imagenes, relacionadas, audios, videos, documentos, etc)
		 */
		if (!empty($data["adjuntos"])) {
		
			foreach ($data["adjuntos"] as $adjunto_tipo => $adjunto) {
			
				$adj_orden = 0;
				$adjunto = str_replace("&amp;", "&", $adjunto);
				$a = explode("&", $adjunto);
				$i = 0;
				while ($i < count($a)) {
				
					$b = split("=", $a[$i]);
					$campo_pie = str_replace("[]", "", $b[0])."_desc_";
					
					// Indico el tipo de información a ingresar (1:noticia, 2:adjunto, etc)
					$campo_tipo = str_replace("[]", "", $b[0])."_type";
					$adj_tipo = !empty($data[$campo_tipo]) ? $data[$campo_tipo] : 0;
					
					$adj_id = !empty($b[1]) ? $b[1] : 0;
					$i++;
					
					$adj_pie = !empty($data[$campo_pie.$adj_id]) ? trim($data[$campo_pie.$adj_id]) : "";
					
					
					$adj_orden++;
					
					if ($adj_id != "") {
					
						$sql = "INSERT INTO {$this->nota_prefix}_multimedia (
							adjunto_tipo,
							adjunto_noticia_id,
							adjunto_multimedia_id,
							adjunto_descripcion,
							adjunto_orden,
							adjunto_tipo_id
						) VALUES (
							'{$adjunto_tipo}',
							'{$data["id"]}',
							'{$adj_id}',
							'{$adj_pie}',
							'{$adj_orden}',
							'{$adj_tipo}'
						)";
						$res = $this->db->query($sql);
					}
				}
			}
		}

		/**
		 * Carga los Tags del recurso, esto se realiza fijandose si el tag ya existe, sino se genera.
		 */
		if (!$new_articulo) {
			$sql = "DELETE FROM {$this->nota_prefix}_tags WHERE adjunto_noticia_id = '{$data["id"]}'";
			if (!$res = $this->db->query($sql)){
				$this->in_error(14);
				return false;
			}
		}
		if (!empty($data["tags"])) {

			foreach (explode(",", $data["tags"]) as $k => $v) {
				$v = trim($v);
				if ($v != "") {
					// Reviso si el tag existe.
					$sql = "SELECT tag_id FROM {$this->nota_prefix}_galeria_tags WHERE tag_tipo = 1 AND tag_nombre = '{$v}' LIMIT 0,1";
					$res = $this->db->query($sql);

					if ($this->db->num_rows($res)) {
						$rs = $this->db->next($res);
						$tag_id = $rs["tag_id"];
					} else {
						$sql = "INSERT INTO {$this->nota_prefix}_galeria_tags (tag_tipo, tag_nombre) VALUES ('1', '$v')";
						if (!$res = $this->db->query($sql)){
							$this->in_error(14);
							return false;
						}
						$tag_id = $this->db->last_insert_id();
					}

					$sql = "INSERT INTO {$this->nota_prefix}_tags (adjunto_tag_id, adjunto_noticia_id) VALUES ('{$tag_id}', '{$data["id"]}')";
					if (!$res = $this->db->query($sql)){
						$this->in_error(14);
						return false;
					}
				}
			}
		}

		/**
		 * Inserto Geo Posicion Google Maps.
		 */
		if (!$new_articulo) {
			$sql = "DELETE FROM {$this->nota_prefix}_geomap WHERE mapa_noticia_id = '{$data["id"]}'";
			if (!$res = $this->db->query($sql)){
				$this->in_error(14);
				return false;
			}
		}

		if (!empty($data["map"])) {
			$map_x = !empty($data["map"]["x"]) ? $data["map"]["x"] : 0;
			$map_y = !empty($data["map"]["y"]) ? $data["map"]["y"] : 0;
			$map_zoom = !empty($data["map"]["zoom"]) ? $data["map"]["zoom"] : 0;
			$map_tipo = !empty($data["map"]["tipo"]) ? $data["map"]["tipo"] : "";
			
			if ($map_x && $map_y && $map_zoom && $map_tipo != "") {
				$sql = "REPLACE INTO {$this->nota_prefix}_geomap (
					mapa_noticia_id,
					mapa_x,
					mapa_y,
					mapa_zoom,
					mapa_tipo,
					mapa_orden
				) VALUES (
					'{$data["id"]}',
					'{$map_x}',
					'{$map_y}',
					'{$map_zoom}',
					'{$map_tipo}',
					'1'
				)";
				
				if (!$res = $this->db->query($sql)){
					$this->in_error(14);
					return false;
				}
			}
		}
		
		// Guardo el final procesado de la nota guardada
		$this->datos = $data;

		return true;
	}
	
	

	/**
	 * Extensión de la clase Save
	 *
	 * Se utiliza si se desea extender la clase agregando funciones luego de grabar los datos normalmente
	 * 
	 */
	function extend_save() {
	}
	

	
	/**
	 * *****************************************************************
	 * Listado de Funciones privadas exclusivas de la clase
	 * *****************************************************************
	 */
	 
	/**
	 * Inicializo los parametros de listado de artículos
	 *
	 * @access private
	 */
	function init() {
		$this->nota_prefix = "lectores_noticias";
		$this->nota_tipo = 0;
		$this->nota_seccion = 0;
		$this->nota_tags = "";
		$this->nota_searchtext = "";
		$this->nota_searchtipo = "AND";
		$this->nota_searchobj = "";
		$this->nota_user_id = false;
		$this->nota_fecha_inicial = false;
		$this->nota_fecha_final = false;
		$this->nota_estado = false;
		$this->nota_orden = "";
		$this->nota_cantidad = 0;
		$this->nota_desplazamiento = 0;
		$this->total_resultados = 0;
		$this->total_paginas = 0;
		
		$this->datos = array();
	}
	
	/**
	 * Procesa los errores de la clase
	 * 
	 * @access private
	 * @param $id integer Identificador del número de error
	 * @param $texto string Parametro Opcional, texto a reemplazar en el mensaje de error. Parametro que se reemplaza: [x]
	 */
	function in_error($id=0, $texto=false) {
		$this->error_msj = $this->error_array[$id];
		
		if ($texto) {
			$this->error_msj = str_replace("[x]", $texto, $this->error_msj);
		}
		
		$this->error_found = true;
	}
	
	/**
	 * Retorna todos los adjuntos de una noticia específica
	 *
	 * @param $id integer ID de la noticia
	 * @return array matriz de datos de los objetos encontrados
	 */
	function adjuntos($id=false) {
		if (!$id) {
			$id = $this->nota_id;
		}
		
		if (!$id) {
			return false;
		}

		$adjunto_arr = array();
		
		/**
		 * Devuelvo información del adjunto tipo 2 (1:noticia, 2:adjunto, 3:encuesta, etc)
		 */
		$sql = "SELECT * FROM {$this->nota_prefix}_galeria_multimedia, {$this->nota_prefix}_multimedia 
			WHERE adjunto_noticia_id = '{$id}' AND adjunto_multimedia_id = gal_id AND adjunto_tipo_id = 2
			ORDER BY adjunto_tipo, adjunto_orden";
		$res = $this->db->query($sql);
		
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);
			
			// Devuelvo la ruta de los archivos
			if ($rs["gal_tipo"] == "link" || $rs["gal_tipo"] == "ytube") {
				$orig = $rs["gal_file"];
				$thumb = "";
			} else {
			
				$pbase = "";
				$adj = $this->adj[$rs["gal_galeria"]];
				if ($adj["path"] != "") {
					$pbase = $adj["path"];
					
					// Reemplazo los parametros dinamicos con datos actuales
					$m = array("d", "j", "N", "w", "z", "W", "m", "n", "Y", "y", "g", "G", "h", "H","i", "s", "U");
					foreach ($m as $fId => $fType) {
						$pbase = str_replace("%{$fType}", date($fType, $rs["gal_fecha"]), $pbase);
					}
				}
				
				/** */
				
				if (!$adj["is_image"]) {
					$orig = $adj["save_data"]["url"].$pbase.$rs["gal_file"];
					$thumb = "";
				} else {
		
					$file_name = $rs["gal_file"];
					$file_name_body = substr($file_name,0,strrpos($file_name,"."));
					$file_name_ext = substr($file_name, strlen($file_name_body)+1);
			
					$img_datos = explode("|", trim($adj["imagen"]));
					$img_datos = array_map("trim", $img_datos);
			
					// name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
					foreach($img_datos as $j=> $m) {
						$mf = explode(",", $m);
						
						$img_prefix = "";
						$img_sufix = "";
						$img_folder = "";
						list($img_name,$img_x,$img_y,$img_folder,$img_prefix,$img_sufix,$img_crop) = array_pad($mf,7,"");
						unset($mf);
						
						$file_path_name = $adj["save_data"]["url"].$pbase;
						$file_path_name.= ($img_folder != "") ? $img_folder."/" : "";
						$file_path_name.= ($img_prefix != "") ? $img_prefix : "";
						$file_path_name.= $file_name_body;
						$file_path_name.= ($img_sufix != "") ? $img_sufix : "";
						$file_path_name.= ".".$file_name_ext;
						
						if ($img_name == "o") {
							$orig = $file_path_name;
						}
						
						if ($img_name == "t") {
							$thumb = $file_path_name;
						}
					}
				}

				/** */

			}
			
			$im = array(
				"id" => $rs["gal_id"],
				"orig" => $orig,
				"thumb" => $thumb,
				"pie" => $rs["adjunto_descripcion"],
				"nombre" => $rs["gal_nombre"],
				"descripcion" => $rs["gal_descripcion"]
			);
			
			$adjunto_arr[$rs["adjunto_tipo"]][$rs["adjunto_orden"]] = $im;
		}
		
		/**
		 * Devuelvo información del adjunto tipo 1 (1:noticia, 2:adjunto, 3:encuesta, etc)
		 */
		$sql = "SELECT * FROM {$this->nota_prefix}_multimedia, {$this->nota_prefix}
			WHERE adjunto_noticia_id = '{$id}' AND adjunto_multimedia_id = noticia_id AND adjunto_tipo_id = 1
			ORDER BY adjunto_tipo, adjunto_orden";
		$res = $this->db->query($sql);
		
		for ($i=0; $i<$this->db->num_rows($res); $i++) {
			$rs = $this->db->next($res);
			
			$im = array(
				"id" => $rs["noticia_id"],
				"orig" => "",
				"thumb" => "",
				"pie" => $rs["adjunto_descripcion"],
				"nombre" => $rs["noticia_titulo"],
				"descripcion" => $rs["noticia_bajada"]
			);
			
			$adjunto_arr[$rs["adjunto_tipo"]][$rs["adjunto_orden"]] = $im;
		}
		
		return $adjunto_arr;
	}
	
}
?>