<?php
/**
 * Registra, logea y edita usuarios según los parametros definidos
 *
 * @revision: 25.04.2012
 *	- Added: Se agregó que sea opcional especificar el id del 
 *	usuario al recuperar iformación desde la funcion getInfo.
 *
 * @revision: 20.04.2012
 *	- Added: Se agregó más campos de opciones a la tabla de usuarios
 *	- Added: Se agregó que el sistema guarde información del último IP con el que se accedió
 *	- Modify: Se optimizaron las funciones de la clase
 *
 * @revision 21.01.2012
 *	- Modify: Se actualizó la versión de la clase de lectores
 *
 * @revision 14.12.2010
 *	- Modify: Se optmizó el código. se solucionó problema en la inicialización de variables
 *
 * @revision 06.11.2010
 * - Added: Se agregó que al confirmar su alta como usuario, el sistema recupere toda la información del usuario
 * - Modify: Se modificó que el sistema acepta al logearse, la clave encriptada o no. (si es de 32 car es enctriptada)
 *
 * @revision 05.11.2010
 *	- Added: Se agregó la opción de que el usuario pueda agregar  o editar un texto como información de perfil
 *	- Added: Se agregó el manejo de niveles de usuario, esto mide la participación del usuario. 
 *			Si su nivel es mayor a cero, devuelve información extra sobre el porcentaje de participación respecto del resto de usuarios
 *	- Added: Se agregó función para devolver la actividad del lector
 *
 * @revision 20.09.2010
 *	- Modify: Se modificó la función que maneja la fortaleza de la contraseña para que detecte si la contraseña contiene parte del nomreb, apellido, etc
 *
 * @revision 06.09.2010
 */
class Usuarios {

	/**
	 * Flag que indica que esta activo el cache
	 *
	 * @access private
	 * @var boolean
	 */
	protected $_cacheOK;

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
	 * Objeto del manejo de cache
	 *
	 * @access public
	 * @var object
	 */
	public $cache;
	
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
	 * Flag que indica si es usuario esta activo una vez finalizado el registro
	 *
	 * @access public
	 * @var boolean
	 */
	public $defaultActive;
	
	/**
	 * Flag que indica si el usuario require confirmación de email
	 *
	 * @access public
	 * @var boolean
	 */
	public $defaultConfirm;

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
	 * Longitud mínima para el nombre de usuario
	 *
	 * @access public
	 * @var integer
	 */
	public $nickNameMin;
	
	/**
	 * Longitud máxima para el nombre de usuario
	 *
	 * @access public
	 * @var integer
	 */
	public $nickNameMax;
	
	/**
	 * Formato de encriptación de contraseña
	 *
	 * Formatos aceptados:
	 *	- md5	(valor por defecto)
	 *	- sha
	 *	- base64
	 *	- crypt
	 *	- none / (vacio)
	 *
	 * @access public
	 * @var string
	 */
	public $passEncript;
	
	/**
	 * Semilla de encriptación (opcional)
	 *
	 * @access public
	 * @var string
	 */
	public $passHash;
	
	/**
	 * Longitud mínima para la contraseña de usuario
	 *
	 * @access public
	 * @var integer
	 */
	public $passMin;

	/**
	 * Longitud máxima para la contraseña de usuario
	 *
	 * @access public
	 * @var integer
	 */
	public $passMax;
	
	/**
	 * Flag que me indica el nivel de revisión de fortaleza de la contraseña
	 * 
	 * Tipos de fortalezas:
	 *	0: No realiza revisión de fortaleza
	 *	1: Revisa que la contraseña no tenga secuencia de caracteres al estilo: 123456, 555, etc
	 *	2: Revisa que la contraseña no tenga partes del nombre de usuario
	 *
	 *
	 * @access public
	 * @see Información extraida de: http://www.passwordmeter.com/	 
	 * @var integer
	 */
	public $passwordStrong;
	
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
	
		$this->_name = 'Usuarios';
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
	 * Guarda información del nuevo usuario
	 *
	 * @access	public
	 * @param	$data	array	Información del usuario a guardar
	 * @return	boolean	True si se guardó correctamente, false en caso contrario
	 */
	public function alta($data=false) {
	
		$this->log.= '- Proceso de alta de usuario.<br/>';

		if (!$this->checkInit()) {
			return false;
		}

		if (!$data) {
			$this->log.= '- No se recibieron parametros.<br/>';
			$this->error(3);
			return false;
		}
		
		$im = array();
		$im['usuario'] = '';
		$im['clave'] = '';
		$im['clave2'] = '';
		$im['nombre'] = '';
		$im['apellido'] = '';
		$im['email'] = '';
		
		$im['sexo'] = '';
		$im['doc_tipo'] = '';
		$im['doc_numero'] = '';
		$im['fecha_nacimiento'] = '';
		
		$im['pais'] = 0;
		$im['provincia'] = 0;
		$im['departamento'] = 0;
		$im['localidad'] = 0;
		$im['localidad_txt'] = '';
		
		$im['domicilio_calle'] = '';
		$im['domicilio_numero'] = '';
		
		$im['activo'] = $this->defaultActive;
		$im['confirmado'] = $this->defaultConfirm;
		
		// Cargo un valor aleatorio para validar el usuario
		$im['temp'] = !($this->defaultConfirm) ? substr(md5(microtime(true)),-10) : '';
		
		$im['codigo'] = $im['temp']; // Utilizado para validar el email
		
		$im['fecha_registro'] = time();
		$im['fecha_ultimo_acceso'] = time();
		
		$im['avatar'] = '';
		$im['web'] = '';
		$im['config'] = '';
		
		$im['campo_1'] = '';
		$im['campo_2'] = '';
		$im['campo_3'] = '';
		$im['campo_4'] = '';
		$im['campo_5'] = '';
		$im['campo_6'] = '';
		$im['campo_7'] = '';
		$im['campo_8'] = '';
		$im['campo_9'] = '';
		$im['campo_10'] = '';

		$im['tipo'] = 1;	// 1:persona 2:empresa

		// Inicializo algunos valores por defecto
		$data = cleanArray($data);
		foreach ($im as $k => $v) {
			$data[$k] = !empty($data[$k]) 
					? (is_string($data[$k]) ? trim($data[$k]) : $data[$k])
					: $v;
		}
		$data['usuario'] = strtolower($data['usuario']);

		if ($this->nicknameExists($data['usuario'])) {
			$this->error(8);
			return false;
		}
		
		if ($this->emailExists($data['email'])) {
			$this->error(7);
			return false;
		}		

		if ($data['clave'] != $data['clave2']) {
			$this->error(4);
			return false;
		}
		
		if (!$this->passwordIsStrong($data['clave'],$this->passwordStrong, $data)) {
			$this->log.= '- La contraseña ingresada es muy débil.<br/>';
			$this->error(5);
			return false;
		}	

		// Armo la fecha de nacimiento
		if (isset($data['fdia'])
			&& isset($data['fmes'])
			&& isset($data['fanio'])) {

			$fdia = ($data['fdia'] < 10) ? '0'.$data['fdia'] : $data['fdia'];
			$fmes = ($data['fmes'] < 10) ? '0'.$data['fmes'] : $data['fmes'];

			$data['fecha_nacimiento'] = $fdia.$fmes.$data['fanio'];
		}

		$clave = $this->encript($data['clave']);
		$ip = $_SERVER['REMOTE_ADDR'];

		$oSql = "REPLACE INTO {$this->_table}lectores (
			lector_id,
			lector_usuario,
			lector_clave,
			lector_nombre,
			lector_apellido,
			lector_email,
			lector_sexo,
			lector_doctipo,
			lector_docnro,
			lector_fnacimiento,
			lector_pais,
			lector_provincia,
			lector_departamento,
			lector_localidad,
			lector_localidad_txt,
			lector_domicilio_calle,
			lector_domicilio_nro,
			lector_activo,
			lector_confirmado,
			lector_temp,
			lector_fregistro,
			lector_ultimo_acceso,
			lector_last_ip,
			lector_avatar,
			lector_web,
			lector_config,
			lector_tipo,
			lector_campo_1,
			lector_campo_2,
			lector_campo_3,
			lector_campo_4,
			lector_campo_5,
			lector_campo_6,
			lector_campo_7,
			lector_campo_8,
			lector_campo_9,
			lector_campo_10
			) VALUES (
			'',
			'{$data["usuario"]}',
			'{$clave}',
			'{$data["nombre"]}',
			'{$data["apellido"]}',
			'{$data["email"]}',
			'{$data["sexo"]}',
			'{$data["doc_tipo"]}',
			'{$data["doc_numero"]}',
			'{$data["fecha_nacimiento"]}',
			'{$data["pais"]}',
			'{$data["provincia"]}',
			'{$data["departamento"]}',
			'{$data["localidad"]}',
			'{$data["localidad_txt"]}',
			'{$data["domicilio_calle"]}',
			'{$data["domicilio_numero"]}',
			'{$data["activo"]}',
			'{$data["confirmado"]}',
			'{$data["temp"]}',
			'{$data["fecha_registro"]}',
			'{$data["fecha_ultimo_acceso"]}',
			'{$ip}',
			'{$data["avatar"]}',
			'{$data["web"]}',
			'{$data["config"]}',
			'{$data["tipo"]}',
			'{$data["campo_1"]}',
			'{$data["campo_2"]}',
			'{$data["campo_3"]}',
			'{$data["campo_4"]}',
			'{$data["campo_5"]}',
			'{$data["campo_6"]}',
			'{$data["campo_7"]}',
			'{$data["campo_8"]}',
			'{$data["campo_9"]}',
			'{$data["campo_10"]}'
		)";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(6);
			return false;
		}
		
		// Cargo el ID del usuario cargado
		$this->itemId = $this->db->last_insert_id();
		
		$data['id'] = $this->itemId;
		$this->campos = $data;

		return true;
	}
	
	/**
	 * Confirma el alta de usuario generado
	 *
	 * @access 	public
	 * @param	array	$data	Información para confirmar el registro de usuario
	 * @return	boolean	True, si el usuario se confirmó correctamente, false en caso contrario
	 */
	public function altaConfirm($data=false) {

		$this->log.= "- Proceso de confirmación de alta de usuario.<br/>";
		
		if (!$this->checkInit()) {
			return false;
		}

		if (!$data) {
			$this->log.= "- No se recibieron parametros.<br/>";
			$this->error(3);
			return false;
		}
		
		// Limpio de injections
		$data = cleanArray($data);

		if (empty($data['codigo']) || $data['codigo'] == '') {
			$this->log.= " - Error: El código ingresado para validar el usuario esta vacio <br/>";
			$this->error(10);
			return false;
		}

		if (empty($data['email']) || $data['email'] == '') {
			$this->log.= " - Error: El email ingresado para validar el usuario esta vacio <br/>";
			$this->error(11);
			return false;
		}
		
		// Consulto si el email existe
		if (!$this->emailExists($data['email'])) {
			$this->log.= " - Error: El email ingresado para validar el usuario no existe <br/>";
			$this->error(9);
			return false;
		}
		
		$oSql = "SELECT lector_id, lector_activo, lector_confirmado 
			FROM {$this->_table}lectores 
			WHERE lector_email = '{$data['email']}' 
				AND lector_temp = '{$data['codigo']}' 
			LIMIT 0,1";

		$this->log.= "- Consulta SQL:{$oSql}<br/>";
		if (!$res = $this->db->query($oSql)) {
			$this->log.= "- Error al ejecutar la consulta.<br/>";
			$this->log.= "- El sistema dice:".$this->db->error()."</br>";

			$this->error(2);
			return false;
		}

		if (!$this->db->num_rows($res)) {
			$this->log.= " - Error: El código para validar el usuario no existe para ese email<br/>";
			$this->error(9);
			return false;
		}
		
		// Consulto si el usuario ya esta activo
		$rs = $this->db->next($res);
		if ($rs['lector_confirmado']) {
			$this->log.= " - Error: El usuario ya se encuentra confirmado<br/>";
			$this->error(12);
			return false;
		}

		$oSql = "UPDATE {$this->_table}lectores 
			SET lector_activo = 1, lector_confirmado = 1, lector_temp = '' 
			WHERE lector_email = '{$data["email"]}' AND lector_temp = '{$data["codigo"]}'";
		$this->log.= "- Consulta SQL:{$oSql}<br/>";				
		if (!$res = $this->db->query($oSql)) {
			$this->log.= "- Error al ejecutar la consulta.<br/>";
			$this->log.= "- El sistema dice:".$this->db->error()."</br>";
		
			$this->error(2);
			return false;
		}
		
		// Recupero la información del lector
		$this->getInfo($rs['lector_id']);

		return true;
	}

	/**
	 * Modifica el avatar del usuario
	 *
	 * @access 	public
	 * @param	string	$avatar	Url del nuevo avatar
	 * @param	integer	$id		ID del usuario a cargar el nuevo avatar (opcional, sino levanta información de $campos)
	 * @return	boolean	True, si el avatar se cargó con éxito, false en caso contrario
	 */
	public function avatar($avatar=false, $id=false){

		$this->log.= '- Proceso de edición de avatar de usuario.<br/>';
		
		if (!$this->checkInit()) {
			return false;
		}
		
		$itemId = ($id) ? $id : $this->campos['lector_id'];

		$avatar = cleanInjection($avatar);
		
		$oSql = "UPDATE {$this->_table}lectores 
			SET lector_avatar = '{$avatar}' 
			WHERE lector_id = '{$itemId}'";
		
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(6);
			return false;
		}

		return true;
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
	 * Edita el perfil del usuario
	 *
	 * @access 	public
	 * @param	array	$data	Información a modificar del usuario
	 * @param	integer	$id		Id del usuario a editar (Opcional, sino levanta la información desde $campos)
	 * @return	boolean	True, si se guardo todo Ok. False en caso contrario
	 */
	public function edit($data=false, $id=false) {

		$this->log.= '- Proceso de edición de usuario.<br/>';
		
		$this->checkInit();

		if (!$data) {
			$this->log.= '- No se recibieron parámetros.<br/>';
			$this->error(3);
			return false;			
		}
		
		$itemId = ($id) ? $id : $this->campos['lector_id'];
		
		$im = array();
		$im['nombre'] = '';
		$im['apellido'] = '';
		$im['email'] = '';
		
		$im['sexo'] = '';
		$im['doc_tipo'] = '';
		$im['doc_numero'] = '';
		$im['fecha_nacimiento'] = '';
		
		$im['pais'] = 0;
		$im['provincia'] = 0;
		$im['departamento'] = 0;
		$im['localidad'] = 0;
		$im['localidad_txt'] = '';
		
		$im['domicilio_calle'] = '';
		$im['domicilio_numero'] = '';
		
		$im['perfil'] = '';
		
		$im['campo_1'] = '';
		$im['campo_2'] = '';
		$im['campo_3'] = '';
		$im['campo_4'] = '';
		$im['campo_5'] = '';
		$im['campo_6'] = '';
		$im['campo_7'] = '';
		$im['campo_8'] = '';
		$im['campo_9'] = '';
		$im['campo_10'] = '';
		
		// Inicializo algunos valores por defecto
		// Además quito los espacios en blanco para los los campos string
		$data = cleanArray($data);
		foreach ($im as $k => $v) {
			$data[$k] = !empty($data[$k]) 
						? (is_string($data[$k]) ? trim($data[$k]) : $data[$k])
						: $v;
		}

		// Armo la fecha de nacimiento
		if (isset($data['fdia'])
			&& isset($data['fmes'])
			&& isset($data['fanio'])) {

			$fdia = ($data['fdia'] < 10) ? '0'.$data['fdia'] : $data['fdia'];
			$fmes = ($data['fmes'] < 10) ? '0'.$data['fmes'] : $data['fmes'];

			$data['fecha_nacimiento'] = $fdia.$fmes.$data['fanio'];
		}

		if ($this->emailExists($data['email'], $itemId)) {
			$this->log.= ' - Error: El email ingresado para el usuario ya existe <br/>';
			$this->error(7);
			return false;
		}

		$oSql = "UPDATE {$this->_table}lectores
			SET
			lector_nombre = '{$data["nombre"]}',
			lector_apellido = '{$data["apellido"]}',
			lector_email = '{$data["email"]}',
			lector_sexo = '{$data["sexo"]}',
			lector_doctipo = '{$data["doc_tipo"]}',
			lector_docnro = '{$data["doc_numero"]}',
			lector_fnacimiento = '{$data["fecha_nacimiento"]}',
			lector_pais = '{$data["pais"]}',
			lector_provincia = '{$data["provincia"]}',
			lector_departamento = '{$data["departamento"]}',
			lector_localidad = '{$data["localidad"]}',
			lector_localidad_txt = '{$data["localidad_txt"]}',
			lector_domicilio_calle = '{$data["domicilio_calle"]}',
			lector_domicilio_nro = '{$data["domicilio_numero"]}',
			lector_perfil = '{$data["perfil"]}',
			lector_campo_1 = '{$data["campo_1"]}',
			lector_campo_2 = '{$data["campo_2"]}',
			lector_campo_3 = '{$data["campo_3"]}',
			lector_campo_4 = '{$data["campo_4"]}',
			lector_campo_5 = '{$data["campo_1"]}',
			lector_campo_6 = '{$data["campo_2"]}',
			lector_campo_7 = '{$data["campo_3"]}',
			lector_campo_8 = '{$data["campo_4"]}'
			lector_campo_9 = '{$data["campo_1"]}',
			lector_campo_10 = '{$data["campo_2"]}'
		WHERE lector_id = '{$itemId}'";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(6);
			return false;
		}

		return true;
	}

	/**
	 * Verifica que el email ingresado no existe en la base de datos
	 *
	 * @access 	protected
	 * @param	string	$email	Email a comprobar
	 * @param	integer	$user	ID del usuario a comprobar (opcional, indica el id del usuario a excluir en la comprobación)
	 * @return	boolean	True, si el email ya existe, false en caso contrario
	 */
	protected function emailExists($email='', $user=false) {
	
		$this->log.= '- Verificando que el email:'.$email.' no exista <br/>';
		if ($email == '') {
			return false;
		}

		$email = cleanInjection($email);
		$user = cleanInjection($user);

		$usrSql = ($user !== false && $user != '') ? "AND lector_id != '{$user}'" : '';
		$oSql = "SELECT lector_email 
			FROM {$this->_table}lectores 
			WHERE lector_email = '{$email}' {$usrSql}";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(2);
			return false;
		}
		
		$ret = ($this->db->num_rows($res)) ? true : false;
		return $ret;
	}	

	/**
	 * Encripta la contraseña según el formato establecido (sha, md5, etc)
	 *
	 * @access	private
	 * @param	string	$clave	Contraseña a encriptar
	 * @return	string	Contraseña encriptada, según los parametros previamente cargados
	 */
	private function encript($clave='') {
	
		$clave = ($this->passHash != '') ? ($this->passHash.$clave) : $clave;

		switch($this->passEncript) {
			case 'md5':
				$ret = md5($clave);
			break;

			case 'sha1':
				$ret = sha1($clave);
			break;

			case 'base64':
				$ret = base64_encode($clave);
			break;
			
			case 'crypt':
				$ret = crypt($clave);
			break;			

			default:
				$ret = $clave;
			break;
		}

		return $ret;
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
		
		/* Si el avatar esta vacio, consulto por el gravatar */
		if ($im['lector_avatar'] == '') {
			// $im['lector_avatar'] = get_gravatar($im['lector_email"], 40, "mm");
		}

		/* Si tiene nivel de participación cargo el nivel */
		$im['puntaje_maximo'] = 0;
		$im['puntaje_ranking'] = 0;
		
		if ($im['lector_puntaje']) {
		
			/* Nivel máximo */
			$sql = "SELECT MAX(lector_puntaje) as puntajes FROM {$this->_table}lectores";
			$res = $this->db->query($sql);
			$rs = $this->db->next($res);
			$im['puntaje_maximo'] = $rs['puntajes'];

			/* Rankin del usuario */
			$oSql = "SELECT COUNT(lector_id) as total FROM {$this->_table}lectores WHERE lector_puntaje > {$im['lector_puntaje']}";
			$res = $this->db->query($oSql);
			$rs = $this->db->next($res);
			$im['puntaje_ranking'] = ($rs['total'] + 1);
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

	/**
	 * Revisa los datos del user y lo logea
	 *
	 * @access public
	 * @param	string	$user	Nickname del usuario
	 * @param	string	$pass	Password del usuario a logear
	 * @param	boolean	$reg	Flag que indica si registra actividad del usuario o no
	 * @return	boolean	True si el login del usuario es correcto, false en caso contrario
	 */
	public function login($user='', $pass='', $reg=false) {

		$this->log.= '- Proceso de login de usuario.<br/>';

		if (!$this->checkInit()) {
			return false;
		}
		
		$user = strtolower(cleanInjection($user));
		$pass = cleanInjection($pass);

		if ($user=='' || $pass=='') {
			$this->log.= ' - Nombre de usuario o contraseña vacio<br/>';
			$this->error(13);
			return false;			
		}

		// Si la clave tiene 32 car. consulto si es la versión enctriptada o no
		if (strlen($pass) != 32) {
			$pass = $this->encript($pass);
		}
		$oSql = "SELECT lector_id, lector_activo, lector_confirmado 
			FROM {$this->_table}lectores 
			WHERE lector_usuario = '{$user}' AND lector_clave = '{$pass}' 
			LIMIT 0,1";
		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(2);
			return false;
		}

		if (!$this->db->num_rows($res)) {
			$this->log.= ' - Nombre de usuario o contraseña incorrecta <br/>';
			$this->error(14);
			return false;
		}

		$this->log.= $this->db->error();
		
		$rs = $this->db->next($res);
		if (!$rs['lector_confirmado']) {
			$this->log.= ' - El usuario no esta confirmado <br/>';
			$this->error(15);
			return false;
		}

		if (!$rs['lector_activo']) {
			$this->log.= ' - El usuario no se encuentra activo<br/>';
			$this->error(16);
			return false;
		}		

		// si registra el acceso
		if ($reg) {
		
			$this->log.= '- Guardando información de último acceso del usuario:'.$rs['lector_id'].'<br/>';
			
			$ip = $_SERVER['REMOTE_ADDR'];

			$oSql = "UPDATE {$this->_table}lectores 
				SET lector_last_ip = '{$ip}', lector_ultimo_acceso = UNIX_TIMESTAMP() 
				WHERE lector_id = '".$rs['lector_id']."'";
			$this->log.= "- Consulta SQL:{$oSql}<br/>";
			if (!$res = $this->db->query($oSql)) {
				$this->log.= '- Error al ejecutar la consulta.<br/>';
				$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

				$this->error(2);
				return false;
			}

			$this->log.= '- Acceso del usuario guradado OK.';
		}

		$this->getInfo($rs['lector_id']);

		return true;
	}
	
	/**
	 * Procesa el email y devuelve un identificador para genera una nueva contraseña
	 *
	 * @access 	public
	 * @param	string	$email	Dirección de email del usuario
	 * @return	string	Código para recuperar la contrseña, false si hubo error o el email no existe
	 */
	public function lostPassword($email=false) {

		$this->log.= '- Generando código temporal para recuperar clave.<br/>';
	
		if (!$this->checkInit()) {
			return false;
		}

		$this->log.= '- Email ingresado:'.$email.'<br/>';

		if (!$email) {
			$this->error(3);
			return false;
		}

		$email = cleanInjection($email);

		if (!$this->emailExists($email)) {
			$this->error(17);
			return false;
		}

		// Genero un código temporal para el usuario
		$oCode = substr(md5(microtime(true)),0,8);
		$this->log.= '- Código temporal generado:'.$oCode.'<br/>';
		
		$oSql = "UPDATE {$this->_table}lectores 
			SET lector_temp = '{$oCode}' 
			WHERE lector_email = '{$email}'";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(2);
			return false;
		}

		return $oCode;
	}

	/**
	 * Modifica la contraseña del usuario
	 *
	 * @access	public
	 * @param	string	$pass	Nueva contraseña para el usuario
	 * @param	integer	$id		ID del usuario (opcional, sino levanta de $campos)
	 * @return	boolean	True, si se modificó con éxito, false en caso contrario
	 */
	public function newPass($pass=false, $id=false) {

		$this->log.= '- Proceso de edición de contraseña de acceso de usuario.<br/>';
		
		if (!$this->checkInit()) {
			return false;
		}

		$itemId = ($id) ? $id : $this->campos['lector_id'];

		$pass = cleanInjection($pass);

		if ($pass === false || $pass == '') {
			$this->log.= '- La contraseña esta vacia.<br/>';
			$this->error(6);
			return false;
		}

		// Reviso si la fortaleza de la contraseña es correcta
		if (!$this->passwordIsStrong($pass,$this->passwordStrong, $this->campos)) {
			$this->log.= '- La contraseña ingresada es muy débil.<br/>';
			return false;
		}
		
		$newPassEncrypt = $this->encript($pass);
		$oSql = "UPDATE {$this->_table}lectores 
			SET lector_clave = '{$newPassEncrypt}' 
			WHERE lector_id = '{$itemId}'";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(2);
			return false;
		}
		
		return true;
	}

	/**
	 * Verifica que el nickname ingresado no existe en la base de datos
	 *
	 * @access 	protected
	 * @param	string	@nickname	Nickname a comprobar
	 * @param	integer	@user		ID del usuario a comprobar (opcional, indica el id del usuario a excluir en la comprobación)
	 * @return	boolean	True, si el nickname ya existe, false en caso contrario
	 */
	protected function nicknameExists($nickname='', $user=false) {

		$this->log.= '- Consultando que el nickname:'.$nickname.' no exista <br/>';
	
		$nickname = strtolower(cleanInjection($nickname));
		$user = cleanInjection($user);
		
		$usrSql = ($user != false && $user != '') ? "AND lector_id != '{$user}'" : '';

		$oSql = "SELECT lector_usuario 
			FROM {$this->_table}lectores 
			WHERE lector_usuario = '{$nickname}' ".$usrSql;

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';
		
			$this->error(2);
			return false;
		}

		$ret = ($this->db->num_rows($res)) ? true : false;
		return $ret;
	}	

	/**
	 * Revisa la fortaleza de la contraseña ingresada
	 *
	 * Revisa si la contraseña posee la fortaleza requerida y devuelve true si es correcta
	 *
	 * @access protected
	 * @param	string	$clave	Contraseña a revisar
	 * @param	integer	$nivel	Nivel de fortaleza a revisar (0:no revisa, 1:permite claves sencillas como 55555, 10: solo permite claves complejas)
	 * @param	array	$data	Opcional, si tiene información, comprueba que la contraseña no contenga partes del nombre, apellido, etc+
	 * @return	boolean	True, si la contrseña es fuerte, false en caso contrario
	 */
	protected function passwordIsStrong($clave='', $nivel=0, $data=false) {

		// Revisar luego contra :
		// http://www.phpclasses.org/browse/file/13425.html
		// http://www.passwordmeter.com/
		// http://www.phpclasses.org/browse/file/2219.html
		// http://www.phpclasses.org/package/6290-PHP-Check-whether-a-password-is-strong.html
		
		/**
		Por ahora deshabilitado
		*/
		return true;

		if ($data) {
			/**
			 * Reviso si la contraseña contiene parte del nombre o apellido o datos similares
			 */
			$checkData = array();
			
			if (!empty($data['usuario'])) {
				$checkData[] = $data['usuario'];
			}
			
			if (!empty($data['lector_usuario'])) {
				$checkData[] = $data['lector_usuario'];
			}
			
			if (!empty($data['nombre'])) {
				$checkData[] = $data['nombre'];
			}
			
			if (!empty($data['lector_nombre'])) {
				$checkData[] = $data['lector_nombre'];
			}
			
			if (!empty($data['apellido'])) {
				$checkData[] = $data['apellido'];
			}

			if (!empty($data['lector_apellido'])) {
				$checkData[] = $data['lector_apellido'];
			}

			// Comprueba que una palabra no contenga a la otra
			foreach ($checkData as $k=>$v) {
				$strBigger = (strlen($v)>=strlen($clave)) ? $v : $clave;
				$strSmaller = ($v == $strBigger) ? $clave : $v;

				if (stristr($strBigger, $strSmaller) || stristr($strBigger, strrev($strSmaller))) {
					$this->error(19);
					return false;
				}
			}
			
			// Comprueba que la clave no sea foneticamente similar a otra
			$pass = soundex($clave);
			foreach ($checkData as $k=>$v) {
				if ($v) {
					$v = soundex($v);

					$lev = levenshtein($v, $pass);
					if ($lev <= 2) {
						$this->error(21);
						return false;
					}
				}
			}
			
			// Realiza revisión de porcentaje de similaridad
			foreach ($checkData as $k=>$v) {
				if ($v) {			 
					$p = '';
					similar_text($clave, $v, $p);
					if ($p > 50) {
						$this->error(21);
						return false;
					}
				}
			}
		}
		
		// Revisión de Heterogeneidad (revisa que no tenga demasiados caracteres iguales)
		$ucase = 0;
		$lcase = 0;
		$digit = 0;
		$special = 0;
		$j = strlen($clave);

		for ($i=0; $i<$j; $i++) {

			$char = substr($clave, $i, 1);
			if (preg_match('/^[[:upper:]]$/', $char)) {
				$ucase++;
			} elseif (preg_match('/^[[:lower:]]$/', $char)) {
				$lcase++;
			} elseif (preg_match('/^[[:digit:]]$/', $char)) {
				$digit++;
			} else {
				$special++;
			}
		}

		$maximum = ceil($j / 2);
		if (($ucase >= $maximum) || ($lcase >= $maximum) || ($digit >= $maximum) || ($special >= $maximum)) {
			$this->error(20);
			return false;
		}

		return true;
	}

	/**
	 * Revisa los datos del user y le genera una nueva clave de acceso
	 *
	 * @access public
	 * @param	string	$email	Email del usuario
	 * @param	string	$codigo	Código enviado por email al usuario
	 * @return	string	Devuelve la nueva contraseña del usuario si los datos son correctos, false en caso contrario
	 */
	public function recoveryPassword($email='', $codigo='') {

		if (!$this->checkInit()) {
			return false;
		}

		if ($email == '' || $codigo == '') {
			$this->error(18);
			return false;			
		}

		$email = cleanInjection($email);
		$codigo = cleanInjection($codigo);

		$oSql = "SELECT lector_id, lector_activo, lector_confirmado 
			FROM {$this->_table}lectores 
			WHERE lector_email = '{$email}' AND lector_temp = '{$codigo}' 
			LIMIT 0,1";

		$this->log.= '- Consulta SQL:'.oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(2);
			return false;
		}

		if (!$this->db->num_rows($res)) {
			$this->log.= ' - Email o código incorrecto<br/>';
			$this->error(18);
			return false;
		}

		$rs = $this->db->next($res);
		if (!$rs['lector_confirmado']) {
			$this->log.= ' - El usuario no esta confirmado <br/>';
			$this->error(15);
			return false;
		}

		if (!$rs['lector_activo']) {
			$this->log.= ' - El usuario no se encuentra activo<br/>';
			$this->error(16);
			return false;
		}

		// Genero una nueva clave de acceso
		$newPass = rand(100000, 999999);
		$newPassEnc = $this->encript($newPass);

		$oSql = "UPDATE {$this->_table}lectores 
			SET lector_clave = '{$newPassEnc}', lector_temp = '' 
			WHERE lector_id = '{$rs["lector_id"]}'";

		$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
		if (!$res = $this->db->query($oSql)) {
			$this->log.= '- Error al ejecutar la consulta.<br/>';
			$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

			$this->error(2);
			return false;
		}

		return $newPass;
	}

	/**
	 * Genera datos de las ubicaciones del item
	 *
	 * @access	private
	 * @param	Integer	$ubicacion	ID de la ubicación hijo
	 * @return	array	Información sobre la ubicación declarada
	 */
	private function ubicaciones($ubicacion=false) {

		$this->log.= ' - Recuperando información de la ubicación del item.<br/>';

		if (!$ubicacion) {
		
			$this->log.= ' - No hay ubicación definida.<br/>';
			return false;
		}
		
		
		if (isset($this->ubicacionArr[$ubicacion])) {
		
			$texto = $this->ubicacionArr[$ubicacion];

		} else {
		
			$texto = array();

			$oSql = "SELECT * FROM ubicaciones 
				WHERE ubicacion_id = '{$ubicacion}' LIMIT 0,1";

			$this->log.= '- Consulta SQL:'.$oSql.'<br/>';
			if (!$res = $this->db->query($oSql)) {
				$this->log.= '- Error al ejecutar la consulta.<br/>';
				$this->log.= '- El sistema dice:'.$this->db->error().'</br>';

				$this->error(5);
				return false;
			}
			
			if (!$this->db->num_rows($res)) {
				$this->log.= '- La ubicación seleccionada no existe <br/>';
				return false;
			}

			$this->log.= '- Ubicación recuperada OK <br/>';
			
			$rs = $this->db->next($res);
			$texto[$rs['ubicacion_tipo']] = $rs;

			$this->log.= '- Intentando recuperar ubicación superior <br/>';
			if ($txt = $this->ubicaciones($rs['ubicacion_pertenece'])) {
				$texto = array_merge($texto, $txt);
			}

			$this->log.= '- Retornando datos de ubicación OK.<br/>';
			
			$this->ubicacionArr[$ubicacion] = $texto;
			
		}

		return $texto;
	}
	
} ?>