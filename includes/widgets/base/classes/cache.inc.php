<?php
/**
 * Abstracción de clase para el procesamiento de cache
 *
 * <b>Que hace?</b> <br/>
 * Guarda o recupera información de cache para ser utilizada en el sitio.
 *
 * <b>Cómo funciona:</b> <br/>
 * La clase posee diversos tipo de cache, permitiendo 
 *	seleccionar de a un solo formato a la vez.
 *
 * <b>Cómo se usa:</b> <br>
 * Inicializando la clase cache: <br/><br/>
 *
 * Modo: memcache
 * <code>
 * $cach = new Cache("memcahe");
 * </code>
 *
 * Modo: db
 * <code>
 * $cach = new Cache("db");
 * $cach->db = $db;
 * </code>
 *
 * Modo: file
 * <code>
 * $cach = new Cache("file");
 * $cach->path = "/includes/cache";
 * </code> 
 *
 * Ejemplo de uso (guardando y recuperando información)
 * <code>
 *	$nombre = "frases";
 *	$valores = array("frase1","frase2","frase3");
 *	$expira = 60;	// valor en segundos
 *
 *	$cach = new Cache("memcahe");	// Modo memcache
 *
 *	// Guardando info
 *	$cach->set($nombre, $valores, $expira);
 *
 *	// Recuperando info
 *	$data = $cach->get($nombre);
 *
 *	// Eliminado info de cache
 *	$cach->del($nombre);
 * </code>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL (en modo db) / Folder #0777 (en modo file)
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>09.04.2012 <br/>
 *	- Modify: Se optimizó la clase y se actualizó la documentación.</li>
 *
 * <li>19.03.2012 <br/>
 *	- Modify: Se optimizó la función de cache. </li>
 *
 * <li>13.02.2012 <br/>
 *	- Modify: Se optimizó la clase y se separó del resto de los objetos 
 *	permitiendo utilizarlo en cualquier etapa de desarrollo</li>
 *
 * <li>04.01.2012<br/>
 *	- Fix: Se repararon errores mínimos de inicialización de variables.<br/>
 *	- Added: Se agregó en los tipos db y file que se elimine el cache alojado 
 *	una vez que expiró. </li>
 *
 * <li>22.12.2011<br/>
 *	- Modify: Se refactorizó la clase, y se normalizó 
 *	a estandares de programación </li>
 *
 * <li>29.11.2011</li>
 * </ul>
 *
 * @package		Drivers
 * @subpackage	Cache
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	29.11.2011
 * @version		1.0	- last revision 2012.02.13 
 */
class Cache {

	/**
	 * Flag que indica que la revisión de cache esta OK
	 *
	 * @access private
	 * @var boolean
	 */
	private $_cacheOK;
	
	/**
	 * Array de tipos de métodos de cache aceptados
	 *
	 * @access private
	 * @var array
	 */
	private $_cacheType;
	
	/**
	 * Tiempo de expiración en segundos
	 *
	 * @access private
	 * @var integer
	 */
	private $_expire;	

	/**
	 * Objeto de manejo de memcache
	 *
	 * @access private
	 * @var object
	 */
	private $_mem;
	
	/**
	 * Nombre de la consulta guardada o a guardar en cache
	 *
	 * @access private
	 * @var string
	 */
	private $_name;	
	
	/**
	 * Tipo de cache a utilizar. Permite utilizar Memcache, file, etc
	 * 
	 * @access private
	 * @var string
	 */
	private $_type;

	/**
	 * Objeto de conexión con la base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Registro de log de la clase
	 *
	 * @access public
	 * @var string
	 */
	public $log;	

	/**
	 * Path donde se guarda el cache (solo utilizado cuando el tipo de file)
	 *
	 * @access public
	 * @var string
	 */
	public $path;	
	
	/**
	 * Constructor de la clase
	 *
	 * @access	public
	 * @param	string	$type	Tipo de cache a utilizar
	 */
	public function __construct($type='') {

		$this->log = '<b>Información de actividad de la clase</b><br/>';
		$this->log.= '- Clase:		Cache <br/>';
		$this->log.= '- Fecha:		'.date('d.m.Y G:i:s').' <br/>';
		$this->log.= '- Inicializo variables. <br/>';

		$this->path = '';
		$this->db = false;
		
		$this->_cacheOK = false;
		$this->_mem = false;
		$this->_name = '';
		$this->_type = 'db';
		
		// Tipos de cache disponibles
		$this->_cacheType = array('memcache', 'file', 'db');

		if ($type != '' && in_array($type, $this->_cacheType)) {
			$this->_type = $type;
			
			$this->log.= '- Cache utilizado:'.$this->_type.' <br/>';
		}
	}
	
	/**
	 * Guarda información en el cache 
	 *
	 * @access	public
	 * @param	string	$name	Nombre a asignar al conjunto de datos a guardar
	 * @param	mixed	$value	Valor a aguardar, acepta cualquier formato (string, integer, array)
	 * @param	integer	$expire	Valor en segundos del tiempo a guardar la información
	 * @return	boolean	True si se guardo todo bien, false en caso contrario
	 */
	public function set($name='', $value='', $expire=0) {
	
		if ($name == '') {
			return false;
		}
		
		/* Reviso si la configuración esta OK */
		if (!$this->_cacheOK) {
			$func = '_'.$this->_type.'_check';
			if (!$this->$func()) {
				return false;
			}
		}
		
		$this->_name = $name;
		$this->_expire = $expire;
		
		/* Exepción en caso de que el valor este vacio,
		es como eliminar el contenido */
		if ($value == '') {
			$func = '_'.$this->_type.'_del';
			return $this->$func();
		} 
		
		$func = '_'.$this->_type.'_set';
		return $this->$func($value);
	}
	
	/**
	 * Recupera información guardada en cache
	 *
	 * @access	public
	 * @param	string	$name	Nombre asinado al datoa a recuperar
	 * @return	mixed	Información guardada en el mismo formato 
	 * 					en el que se envío (string, array, integer), 
	 *					false en caso de error
	 */
	public function get($name='') {

		if ($name == '') {
			return false;
		}
		$this->_name = $name;
		
		/* Reviso si la configuración esta OK */
		if (!$this->_cacheOK) {
			$func = '_'.$this->_type.'_check';
			if (!$this->$func()) {
				return false;
			}
		}
		
		$func = '_'.$this->_type.'_get';
		return $this->$func();
	}
	
	/**
	 * Elimina información guardada en cache
	 *
	 * @access	public
	 * @param	string	$name	Nombre asignado al dato a eliminar
	 * @return	boolean	True en caso de eliminado, false en caso de error
	 */
	public function del($name='') {
	
		if ($name == '') {
			return false;
		}
		$this->_name = $name;
		
		/* Reviso si la configuración esta OK */
		if (!$this->_cacheOK) {
			$func = '_'.$this->_type.'_check';
			if (!$this->$func()) {
				return false;
			}
		}
		
		$func = '_'.$this->_type.'_del';
		return $this->$func();
	}
	
	/**
	 * ******************************************************************
	 * Funciones de manejo de cache tipo Memcache
	 * ******************************************************************
	 */

	/**
	 * Revisa si esta inicializada la clase de Memcache sino intenta inicializarla
	 *
	 * @access	private
	 * @return	boolean	True si la revisión e inicialización fue positiva, false en caso contrario
	 */
	private function _memcache_check() {
		
		if (!class_exists('Memcache')) {
			return false;
		}
	
		$this->_mem = new Memcache;
		
		if ($memc = $this->_mem->connect('localhost', 11211)) {
		
			$this->_cacheOK = true;
		
			return true;
		}

		return false;
	}

	/**
	 * Manejo de cache vía memcache
	 * Recupera la información guardada en cache de memcache
	 *
	 * @access	private
	 * @return	array	Matriz de datos recuperada desde cache
	 */
	private function _memcache_get() {

		return $this->_mem->get($this->_name);
	}

	/**
	 * Manejo de cache vía memcache
	 * Guarda información en el cache de memcache
	 * 
	 * @access	private
	 * @param	array	$info	Datos a guardar en cache
	 * @return	boolean	True si todo se guardo correctamente, false en caso contrario
	 */
	private function _memcache_set($info) {

		$expire = $this->_expire + time();
		
		if ($this->_mem->set($this->_name, $info, false, $expire)) {
			return true;
		}

		return false;
	}
	
	/**
	 * Elimina la información guardada en cache de memcache
	 *
	 * @access	private
	 * @return	boolean	True si se eliminó correctamente, false en caso contrario	
	 */
	private function _memcache_del() {

		if (!$this->_mem->delete($this->_name)) {
			return false;
		}

		return true;
	}
	
	/**
	 * ******************************************************************
	 * Funciones de manejo de cache tipo File
	 * ******************************************************************
	 */
	 
	/**
	 * Revisa si estan declarados todos los parámetros básicos
	 *
	 * @access	private
	 * @return	boolean	True si la revisión fue positiva, false en caso contrario
	 */
	private function _file_check() {

		if ($this->path == '') {
			return false;
		}
		
		if (file_exists($this->path) && is_writable($this->path)) {

			$this->_cacheOK = true;
			return true;
		}

		return false;
	}	

	/**
	 * Recupera la información guardada en cache de file
	 *
	 * @access	private
	 * @return	array	Matriz de datos recuperada desde cache
	 */
	private function _file_get() {

		$fileName = md5($this->_name).'.cache.php';
	
		if (file_exists($this->path.'/'.$fileName)) {
	
			include ($this->path.'/'.$fileName);
		
			// Consulta si expiró o no
			if($cache_timeout > time()) {
				return $cache_data;
			}
			
			// Como la consulta expiró, la elimino.
			$this->_file_del();
		}

		return false;
	}

	/**
	 * Manejo de cache vía file
	 * Guarda información en el cache de file
	 * 
	 * @access	private
	 * @param	array	$info	Datos a guardar en cache
	 * @return	boolean	True si todo se guardo correctamente, false en caso contrario
	 */
	private function _file_set($info) {

		$fileName = md5($this->_name).'.cache.php';
		$timeToExpire = $this->_expire + time();

		$data = "<?php\n";
		$data.= "/**\n";
		$data.= " * Cache name:	".$this->_name." \n";
		$data.= " * Generate:	".date('d.m.y G:i:s')." hs \n";
		$data.= " * Time to live:	".$this->_expire." seg. \n";
		$data.= " * Date Expire:	".date('d.m.y G:i:s', $timeToExpire)." hs \n";
		$data.= " */\n\n";

		// Guardo información de expire
		$data.= '$cache_timeout='.$timeToExpire.";\n\n";
	
		// Guardo información de los datos
		$data.= '$cache_data = '. var_export($info, true) .';';
		$data.= "\n\n ?>";
		
		return file_put_contents($this->path.'/'.$fileName, $data);
	}
	
	/**
	 * Elimina la información guardada en cache de file
	 *
	 * @access	private
	 * @return	boolean	True si se eliminó correctamente, false en caso contrario	
	 */
	private function _file_del() {
	
		$fileName = md5($this->_name).'.cache.php';
		
		if (!file_exists($this->path.'/'.$fileName)) {
			return false;
		}
	
		return unlink($this->path.'/'.$fileName);
	}
	
	/**
	 * ******************************************************************
	 * Funciones de manejo de cache tipo Base de datos
	 * ******************************************************************
	 */

	/**
	 * Revisa si estan declarados todos los parámetrso básicos
	 *
	 * @access	private
	 * @return boolean	True si la revisión fue positiva, false en caso contrario
	 */
	private function _db_check() {

		if (!is_object($this->db)) {
			$this->log.= '- No esta definida la base de datos para el cache. <br/>';
			return false;
		}

		$tablaOK = false;

		$sql = 'SHOW TABLES';
		if ($res = $this->db->query($sql)) {
			if ($this->db->num_rows($res)) {
				for ($i=0; $i<$this->db->num_rows($res); $i++) {
					$rs = $this->db->next($res);
					if ('_cache' == current($rs)) {
						$tablaOK = true;
						break;
					}
				}
			}
		}

		if (!$tablaOK) {
		
			$this->log.= '- La tabla de cache no existe. Se procede a crearla.<br/>';

			$sql = "CREATE TABLE _cache (
			  `name` varchar(100) NOT NULL,
			  `time` int(11) NOT NULL,
			  `text` longtext NOT NULL,
			  UNIQUE KEY `name` (`name`),
			  KEY `time` (`time`)
			) ENGINE=MEMORY DEFAULT CHARSET=latin1 COMMENT='Cache de base de datos'";

			if (!$res = $this->db->query($sql)) {
				$this->log.= '- No se pudo crear la tabla de cache: _cache. <br/>';
				return false;
			}
		}

		// Una vez finalizado la revisión de cache, elimino todos los datos expirados
		$sql = 'DELETE FROM _cache WHERE time < UNIX_TIMESTAMP()';
		$res = $this->db->query($sql);
		
		$this->_cacheOK = true;

		$this->log.= '- Comprobación de cache de dbase OK. <br/>';

		return true;
	}
	
	/**
	 * Recupera la información guardada en cache de base de datos
	 *
	 * @access	private
	 * @return	array	Matriz de datos recuperada desde cache
	 */
	private function _db_get() {
	
		$this->log.= '- Se intenta recuperar info de cache nombre:'.$this->_name.'. <br/>';

		$sql = "SELECT * FROM _cache WHERE name = '{$this->_name}' AND time > UNIX_TIMESTAMP() LIMIT 0,1";
		
		if ($res = $this->db->query($sql)) {
		
			if ($this->db->num_rows($res)) {
			
				$this->log.= '- La información existe en el cache actual. <br/>';
				$this->log.= '- Datos recuperados OK.<br/>';
			
				$rs = $this->db->next($res);
				return unserialize($rs['text']);
			}
		}
		
		// Elimino la consulta (suponiendo que exista pero expiró)
		$this->_db_del();

		$this->log.= '- La información no existe en el cache. <br/>';
		return false;
	}

	/**
	 * Guarda información en el cache de base de datos
	 * 
	 * @access	private
	 * @param	array	$info	Datos a guardar en cache
	 * @return	boolean	True si todo se guardo correctamente, false en caso contrario
	 */
	private function _db_set($info) {

		$im = serialize($info);
		$im = addslashes($im);

		$expire = $this->_expire + time();
		$sql = "REPLACE INTO _cache (name, time, text) VALUES (\"{$this->_name}\",\"{$expire}\",\"{$im}\")";
		
		if ($res = $this->db->query($sql)) {
			return true;
		}

		return false;
	}
	
	/**
	 * Elimina la información guardada en cache de base de datos
	 *
	 * @access	private
	 * @return	boolean	True si se eliminó correctamente, false en caso contrario	
	 */
	private function _db_del() {
		
		$sql = "DELETE FROM _cache WHERE name = '{$this->_name}'";
		if ($res = $this->db->query($sql)) {
		
			return true;
		}

		return false;
	}
}
?>