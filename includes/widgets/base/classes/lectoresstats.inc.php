<?php
/**
 * Recupera información de la actividad del lector
 *
 * @revision 25.04.2012
 */
class UsuariosStats {

	/**
	 * Listado de mensajes de error de la clase
	 *
	 * @access private
	 * @var array
	 */
	protected $_errorArray;
	
	/**
	 * Nombre de la clase
	 *
	 * @access private
	 * @var string
	 */
	protected $_name;
	
	/**
	 * Prefijo completo de tabla a utilizar
	 *
	 * @access protected
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Versión de la clase
	 *
	 * @access private
	 * @var string
	 */
	protected $_version;	

	/**
	 * Información del último usuario agregado, editado, listado, etc
	 *
	 * @access public
	 * @var array
	 */
	public $campos;
	
	/**
	 * Objeto de manejo de base de datos
	 *
	 * @access public
	 * @var object
	 */
	public $db;
	
	/**
	 * Mensaje de error en caso de que existe alguno
	 * False en caso contrario
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
	 * Identificador del usuario a devolver
	 *
	 * @access public
	 * @var integer
	 */
	public $itemId;

	/**
	 * Registro de log de la clase
	 *
	 * @access public
	 * @var string
	 */
	public $log;
	
	/**
	 * Array de consultas a realizar
	 *
	 * @access private
	 * @var array
	 */
	private $stats;
	
	/**
	 * Prefijo de tabla
	 *
	 * @access public
	 * @var string
	 */
	public $tablePrefix;


	/**
	 * Constructor de la clase
	 *
	 * @access public
	 */
	public function __construct() {
	
		$this->_name = 'UsuariosStats';
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
	 * Carga información para el alta de info de estadísticas
	 *
	 * @access	public
	 * @param	string	$nombre	Nombre con el que se guarda la consulta
	 * @param	string	$table	Nombre de la tabla a consultar
	 * @param	string	$campo	Nombre del campo de id del usuario
	 * @param	string	$extra	Consulta SQl extra 
	 */
	public function addStats($nombre, $table, $campo, $extra='') {

		if ($nombre != '' && $table != '' && $campo != '') {
			$this->stats[$nombre] = array(
				'tabla'=>$table, 
				'campo'=>$campo, 
				'extra'=>$extra
			);
		}

	}
	
	/**
	 * Carga información de la tabla a utilizar
	 *
	 * @access private
	 */
	private function checkInit() {

		// Reviso si tiene declarado el obj de base de datos
		if (!is_object($this->db)) {
			$this->error(1);
			return false;
		}
		
		// Reviso si tiene Cache
		if (is_object($this->cache)) {
			$this->_cacheOK = true;

			$this->log.= '- Cache:  Activo <br/>';
		}
		
		if ($this->tablePrefix != '') {
			$this->log.= '- Prefijo de tabla a utilizar: '.$this->tablePrefix.'<br/>';
			$this->_table = $this->tablePrefix.'_';
		}
		
		return true;
	}

	/**
	 * Procesa los errores de la clase
	 * 
	 * @access	private
	 * @param	mixed	$id 	Identificador del número de error (integer) 
	 *							o string para que me carge el texto del error
	 * @param	string	$texto	Opcional, texto a reemplazar 
	 *							en el mensaje de error. Valor a reemplazar: [x]
	 */
	private function error($id=0, $texto=false) {

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

		$this->log.= '- Error: '.$this->errorInfo.'<br/>';
	}

	/**
	 * Devuelve información del Usuario solicitado
	 *
	 * @access 	public
	 * @param	integer	$itemId	ID del usuario a devolver la información
	 * @return	array	Matriz de datos del usuario seleccionado
	 */
	public function getInfo($itemId=false) {

		if (!$itemId) {
			$itemId = $this->campos['lector_id'];
		}
	
		$this->log.= '- Inicio proceso de devolver información del usuario logeado <br/>';
		$this->log.= '- Id del usuario: '.$itemId.'<br/>';
	
		$oSql = "SELECT * 
			FROM {$this->_table}lectores 
			WHERE lector_id = '{$itemId}' LIMIT 0,1";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(2);
			return false;
		}
		
		if (!$this->db->num_rows($res)) {
			$this->error(2);
			$this->log.= '- Error, no se encontró información del usuario:'.$itemId.'<br/>';

			return false;			
		}
		
		$im = $this->db->next($res);
		
		// Si tiene nivel de participación cargo el nivel
		$im['puntaje_maximo'] = 0;
		$im['puntaje_ranking'] = 0;
		
		if ($im['lector_puntaje']) {
		
			// Nivel máximo
			$sql = "SELECT MAX(lector_puntaje) as puntajes FROM {$this->_table}lectores";
			$res = $this->db->query($sql);
			$rs = $this->db->next($res);
			$im['puntaje_maximo'] = $rs['puntajes'];

			// Rankin del usuario
			$oSql = "SELECT COUNT(lector_id) as total FROM {$this->_table}lectores WHERE lector_puntaje > {$im['lector_puntaje']}";
			$res = $this->db->query($oSql);
			$rs = $this->db->next($res);
			$im['puntaje_ranking'] = ($rs['total'] + 1);
		}
		
		// Recupero información de las consultas extras realizadas
		if (count($this->stats)) {
		
			$this->log.= '- Rcuperando información específica de estadísticas del usuario <br/>';
		
			foreach ($this->stats as $k => $v) {
				
				$oSql = "SELECT count({$v['campo']}) as total FROM {$v['tabla']} ";
				$oSql.= ($v['extra'] != '') ? " WHERE {$v['extra']}" : "";
				
				$this->log.= '- Estadística:'.$k.'<br/>';
				$this->log.= '- Consulta SQL:'.$oSql.'<br/>';

				if ($res = $this->db->query($oSql)) {
					$rs = $this->db->next($res);

					$this->log.= '- Total de resultados encontrados:'.$rs['total'].'</br>';
					$im[$k] = $rs['total'];
				}
			}
		}

		$this->campos = $im;
		return $im;
	}
	
	/**
	 * Inicializa todas las opciones básicas de la clase
	 *
	 * @access	private
	 */
	private function init() {
	
		$this->log = '<b>Información de actividad de la clase</b><br/>';
		$this->log.= '- Clase:		'.$this->_name.'<br/>';
		$this->log.= '- Versión:	'.$this->_version.'<br/>';
		$this->log.= '- Fecha:		'.date("d.m.Y G:i:s").' <br/>';

		// Listado de posibles errores
		$this->_errorArray = array();
		$this->_errorArray[1] = "No se puede conectar con la base de datos";
		$this->_errorArray[2] = "Error al devolver información de la base de datos. Intente nuevamente";
		$this->_errorArray[3] = "No se recibió ningún parametro. Los datos no se guardaron";
		$this->_errorArray[4] = "La contraseña no coincide.";
		$this->_errorArray[5] = "La contraseña ingresada es muy predecible. Ingrese otra contraseña.";
		$this->_errorArray[6] = "Error al guardar información en la base de datos. Intente nuevamente.";
		$this->_errorArray[7] = "El email ingresado ya existe, ingrese otra casilla de email";
		$this->_errorArray[8] = "El nombre de usuario ingresado ya existe, ingrese otro nombre de usuario";

		$this->_errorArray[9] = "El email o el código ingresado es incorrecto. Verifique que la información ingresada sea correcta e intente nuevamente";
		$this->_errorArray[10] = "El código ingresado esta vacio. Ingrese todos los datos solicitados";
		$this->_errorArray[11] = "El email ingresado esta vacio. Ingrese todos los datos solicitados";

		$this->_errorArray[12] = "Su registro ya se encuentra confirmado";

		$this->_errorArray[13] = "El usuario o contraseña estan vacios";
		$this->_errorArray[14] = "Nombre de usuario o contraseña incorrecta";
		$this->_errorArray[15] = "No has confirmado tu registro. Revisa tu casilla de email para saber como continuar";
		$this->_errorArray[16] = "El usuario se encuentra temporalmente deshabilitado. Intente ingresar más tarde";

		$this->_errorArray[17] = "El email ingresado no existe. Verifique que los datos ingresados sean correctos e intente nuevamente";
		$this->_errorArray[18] = "El email o el código ingresado es incorrecto. Verifique que la información ingresada sea correcta e intente nuevamente";

		$this->_errorArray[19] = "La contraseña ingresada es demasiado predecible. No puede contener datos personal. Ingrese otra contraseña.";
		$this->_errorArray[20] = "La contraseña ingresada es demasiado predecible. Ingrese otra contraseña.";
		$this->_errorArray[21] = "La contraseña ingresada es similar a alguno de sus datos personales. Ingrese otra contraseña.";

		$this->errorNro=0;
		$this->errorInfo='';

		$this->tablePrefix = '';
		$this->_table = '';

		$this->itemId = 0;

		$this->nickNameMin = 6;
		$this->nickNameMax = 15;

		$this->passMin = 6;
		$this->passMax = 32;

		$this->passwordStrong = 0; // Fortaleza de la contraseña
		$this->passEncript = 'md5'; // Formato por defecto para encriptar la contraseña
		$this->passHash = ''; // Semilla de encriptación

		$this->defaultActive = 0; // Por defecto el usuario no esta activo
		$this->defaultConfirm = 0; // Por defecto el usuario no esta confirmado
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

} ?>