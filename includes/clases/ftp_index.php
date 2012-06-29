<?
/**
 * Abstracción de fileSystem
 * 
 * Genera una abstracción de FTP sin importar el sistema operativo o la ubicación del remoto en el cual se monte
 * Genera la conexión al filesistem y devuelve el objeto en la variable $fl
 *
 * @package StreamServer FileSystem
 * @access public
 * @author Alexis Lesa
 * @copyright Advertis Web Factory
 * @licence Comercial
 * @version 1.0
 * @revision 17.05.2010
 */

class fs {

	/**
	 * Objeto de la clase de filesystem
	 *
	 * @access public
	 * @var objetc
	 */
	var $obj;
	
	/**
	 * Array de datos de conexión
	 *
	 * @access public
	 * @var array
	 */
	var $data;
	
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
	 * Constructor de la clase
	 *
	 * Inicializa los parametros por defecto.
	 * Inicializa el conector de FTP
	 *
	 * @access public
	 * @param	$data	array	parametros para conectar con el filesystem de FTP
	 * @return	object	Retorna el objeto conectado, false si hubo error
	 */
	function __construct($data=false) {
	
		$this->data = $data;
	
		$this->error_msj = "";
		$this->error_found = false;

		// Defino la tabla de errores
		$this->error_array = array();
		$this->error_array[1] = "Error de conexión al repositorio";
		$this->error_array[2] = "Error de conexión FTP";
		$this->error_array[3] = "Login de conexión al repositorio de archivos incorrecto";
	}
	
	/**
	 * Conecta el FTP
	 *
	 */
	function conect() {
	
		$path = dirname(__FILE__);
		include ($path."/ftp_class.php");

		$fl = new ftp(FALSE);
		$fl->Verbose = FALSE;
		$fl->LocalEcho = FALSE;

		if(!$fl->SetServer($this->data["host"], $this->data["port"])) {

			$fl->quit();
			
			$this->in_error(1);
			return false;
			
		} else {
			if (!$fl->connect()) {
				
				$this->in_error(2);
				return false;

			} else {
				if (!$fl->login($this->data["user"], $this->data["pass"])) {
					$fl->quit();
					
					$this->in_error(3);
					return false;

				} else {

					if(!$fl->SetType(FTP_AUTOASCII)) {
						// Fallo al setear el tipo
					} 

					if(!$fl->Passive(true)) {
						// fallo al setear modo pasivo
					}
				}
			}
		}
		
		return $fl;
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
}
?>