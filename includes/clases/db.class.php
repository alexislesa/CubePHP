<?php
/**
 * Clase para el manejo de abstracción de Base de datos MySQL
 *
 * Manejador de conexión de base de datos MySQL
 *
 * <b>Cómo se usa:</b> <br>
 * Ejecutando una consulta SQL:
 * <code>
 * $db = New db('host','user','pass', 'name');
 * $res = $db->query($sql);
 * </code>
 *
 * Recuperando información de una fila de resultados:
 * <code>
 * $db = New db('host','user','pass', 'name');
 * $res = $db->query($sql);
 * $rows = $db->next($res);
 *
 * // Recupera información en caso de error.
 * echo $db->error();
 * </code>
 *
 * <b>Requerimientos:</b> <br/>
 * - PHP 5+ / MySQL
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>08.02.2012 <br/>
 *	- Modify: Se optimizó la clase de manejo de dbase, se pasaron 
 *		todas las propiedades a privadas.<br/>
 * </li>
 * <li>18.01.2012<br/>
 *	- Modify: Se agregó que finalize las conexiones una 
 *	vez destruida la instancia de clase<br/>
 * </li>
 * <li>17.01.2012 <br/>
 *	- Modify: Se modificó la forma en la que se recupera y almacena 
 *	la fila de la consulta recuperada<br/>
 * </li>
 * <li>22.08.2010 <br/>
 *	- Modify: limpieza de código. más limpio y optimizado <br/>
 * </li>
 * </ul>
 *
 * @package		Drivers
 * @category	dBases
 * @access		public 
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @license		Comercial
 * @generated	04.08.2010
 * @version		1.0	- last revision 2012.02.08 
 */
class dbConnect {

	/**
	 * Nombre del Host de conexión
	 *
	 * @access private
	 * @var string
	 */
	private $host;
	
	/**
	 * Usuario de conexión
	 *
	 * @access private
	 * @var string
	 */	
	private $user;
	
	/**
	 * Contraseña de conexión
	 *
	 * @access private
	 * @var string
	 */
	private $pass;
	
	/**
	 * Nombre de la base de datos
	 *
	 * @access private
	 * @var string
	 */
	private $name;
	
	/**
	 * Flag que indica si se va a generar una conexion persistente o no
	 *
	 * @access private
	 * @var boolean
	 */
	private $persistency;

	/**
	 * Identificador de conexión
	 *
	 * @access private
	 * @var resource
	 */
	private $connectId;
	
	/**
	 * Ultimo recurso de resultado generado por el SQL
	 *
	 * @access private
	 * @var resorce
	 */
	private $queryResult;

	/**
	 * Indica si estoy dentro de una transacción o no
	 *
	 * @access private
	 * @var boolean
	 */
	private $inTransaction = false;
	
	/**
	 * Utilizado al levantar un texto con comandos SQL
	 * 
	 * @access private
	 * @var array
	 */
	private $ret;

	/**
	 * constructor de la clase
	 *
	 * @access	public
	 * @param	string	$host	Host de la conexion de la base de datos
	 * @param	string	$user	Usuario de conexión
	 * @param	string	$pass	Contraseña de conexión
	 * @param	string	$name	Nombre de la base de datos
	 * @param	boolean	$persistency	True si la conexión será persistente
	 */
	public function __construct ($host='', $user='', $pass='', $name='',$persistency=false) {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->name = $name;
		$this->persistency = $persistency;
		
		$this->ret = array();
	}

	/**
	 * Destructor de la clase, cierra las conexiones abiertas
	 *
	 * @access	public
	 */
	public function __destruct() {
	
		$this->close();
	}

	/**
	 * Conecta con el servidor de Mysql y retorna el recurso de conexión
	 *
	 * @access	public
	 * @return	resource	Recurso de conexión establecido, false en caso de error
	 */
	function conect () {

		$this->connectId = ($this->persistency) 
					? mysql_pconnect($this->host, $this->user, $this->pass) 
					: mysql_connect($this->host, $this->user, $this->pass);

		if ($this->connectId) {
		
			if($this->name != '') {
			
				$dbselect = mysql_select_db($this->name);

				if(!$dbselect) {
					mysql_close($this->connectId);
					$this->connectId = $dbselect;

					die("No se puede conectar a la Base de Datos.");
				}
				
			} else {
			
				die("No ha seleccionado ninguna Base de Datos.");
			
			}

			return $this->connectId;
			
		} else {
		
			die("No se puede conectar al Servidor de Base de Datos.");
			return false;
		}
	}
	
	/**
	 * Cierra la conexión actual de la base de datos
	 *
	 * @access	public
	 * @return	boolean	True si se cerro correctamente, false en caso contrario
	 */
	public function close() {
		
		if( $this->connectId ) {
		
			if($this->inTransaction) {
				mysql_query('COMMIT', $this->connectId);
			}

			return mysql_close($this->connectId);
		} else {
			return false;
		}
	}

	/**
	 * Envía una consulta a la base de datos
	 *
	 * @access	public
	 * @param	string	$sql			Consulta a procesar
	 * @param	string	$transaction	Utilizado si estoy en modo transaccional
	 * @return	resorce	Recurso de conexion generado, false en caso de error
	 */
	public function query($sql='', $transaction=false) {

		unset($this->queryResult);

		if($sql != '' ) {
			if ($transaction == 'BEGIN_TRANSACTION' && !$this->inTransaction) {
				$result = mysql_query('BEGIN', $this->connectId);
				if(!$result) {
					return false;
				}
				$this->inTransaction = TRUE;
			}

			$this->queryResult = mysql_query($sql, $this->connectId);
			
		} else {
			if ($transaction == 'END_TRANSACTION' && $this->inTransaction) {
				$result = mysql_query('COMMIT', $this->connectId);
			}
		}

		if ($this->queryResult) {
			if ($transaction == 'END_TRANSACTION' && $this->inTransaction) {
				$this->inTransaction = FALSE;

				if (!mysql_query('COMMIT', $this->connectId)) {
					mysql_query('ROLLBACK', $this->connectId);
					return false;
				}
			}
			
			return $this->queryResult;
			
		} else {
			if ($this->inTransaction) {
				mysql_query('ROLLBACK', $this->connectId);
				$this->inTransaction = FALSE;
			}

			return false;
		}
	}

	/**
	 * Retorna el número de filas devueltas en la consulta
	 *
	 * @access	public
	 * @param	resourse	$query_id	Identificador del recurso a devolver
	 * @return	integer	Número de filas devueltas por la consulta
	 */
	public function num_rows($query_id=0) {
		if(!$query_id) {
			$query_id = $this->queryResult;
		}

		return ($query_id) ? @mysql_num_rows($query_id) : false;
	}

	/** 
	 * Retorna el número de filas afectadas en la última consulta tipo UPDATE/DELETE
	 *
	 * @access 	public
	 * @return	integer	Cantidad de filas afectadas, o false si hubo error
	 */
	public function affected_rows() {
		return ($this->connectId) ? mysql_affected_rows($this->connectId) : false;
	}

	/**
	 * Retorna el último ID generado en la última consulta del tipo INSERT/REPLACE
	 *
	 * @access 	public
	 * @return	integer	Número del último Id generado
	 */
	public function last_insert_id(){
		return ($this->connectId) ? @mysql_insert_id($this->connectId) : false;
	}
	
	/**
	 * Retorna una fila del resultado en forma de array y pone el apuntador en la próxima fila
	 *
	 * @access	public
	 * @param	resourse	$query_id	Recurso a ser evaluado, 
	 *									por defecto la última consulta realizada
	 * @return	array		Array de datos de la fila devuelta
	 */
	public function next($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->queryResult;
		}

		if($query_id){
			return mysql_fetch_assoc($query_id);
		} else {
			return false;
		}
	}

	/**
	 * Retorna array multidimensional con todas las filas devueltas por la consulta
	 *
	 * @access	public
	 * @param	resourse	$query_id	Recurso a ser evaluado
	 * @return	array		Array de datos de la fila devuelta
	 */
	public function nextall($query_id = 0) {
		if(!$query_id) {
			$query_id = $this->queryResult;
		}

		if($query_id) {
			$result = array();
			
			while($rowset = mysql_fetch_assoc($query_id)) {
				$result[] = $rowset;
			}
			return $result;
		}
		
		return false;
	}

	/**
	 * Devuelve el último error generado
	 *
	 * @access 	public
	 * @return	string	Texto del último error generado
	 */
	public function error() {
		return mysql_error($this->connectId);
	}
	
	/**
	 * Genera array con un comando SQL en cada línea de un bloque de SQL
	 *
	 * @access	public
	 * @param	string	$sql	Paquete de texto con comandos SQL
	 * @return	array	Matriz de comandos SQL, false en caso de error
	 */	
	public function splitSql($sql) {
		if ($this->splitSqlFile($sql)) {
			return $this->ret;
		}
		
		return false;
	}
	
	/**
	 * Genera array con un comando SQL en cada línea de un bloque de SQL
	 *
	 * @access	private
	 * @param	string	$sql	Paquete de texto con comandos SQL
	 * @return	boolean	True si todo se ejecuto correctamente, false en caso contrario
	 */
	private function splitSqlFile($sql) {
		$sql = trim($sql);
		$sql_len = strlen($sql);
		$char = '';
		$string_start = '';
		$in_string    = FALSE;
   
		for ($i = 0; $i < $sql_len; ++$i) {
			$char = $sql[$i];
    
			// We are in a string, check for not escaped end of strings except for
			// backquotes that can't be escaped

			if ($in_string) {
				for (;;) {
					$i = strpos($sql, $string_start, $i);
					// No end of string found -> add the current substring to the
					// returned array
					if (!$i) {
						$this->ret[] = $sql;
						return TRUE;
					}

					// Backquotes or no backslashes before quotes: it's indeed the
					// end of the string -> exit the loop
					else if ($string_start == '`' || $sql[$i-1] != '\\') {
						$string_start = '';
						$in_string = FALSE;
						break;
					}

					// one or more Backslashes before the presumed end of string...
					else {
						// ... first checks for escaped backslashes
						$j = 2;
						$escaped_backslash = FALSE;
						while ($i-$j > 0 && $sql[$i-$j] == '\\') {
							$escaped_backslash = !$escaped_backslash;
							$j++;
						}

						// ... if escaped backslashes: it's really the end of the
						// string -> exit the loop
						if ($escaped_backslash) {
							$string_start = '';
							$in_string = FALSE;
							break;
						}

						// ... else loop
						else {
							$i++;
						}
					} // end if...elseif...else
				} // end for
			} // end if (in string)
    
    			// We are not in a string, first check for delimiter...
			else if ($char == ';') {
				// if delimiter found, add the parsed part to the returned array
				$this->ret[] = substr($sql, 0, $i);
				$sql = ltrim(substr($sql, min($i + 1, $sql_len)));
				$sql_len = strlen($sql);
				if ($sql_len) {
					$i = -1;
				} else {

					// The submited statement(s) end(s) here
					return TRUE;
				}
			} // end else if (is delimiter)
    
		    	// ... then check for start of a string,...
			else if (($char == '"') || ($char == '\'') || ($char == '`')) {
				$in_string    = TRUE;
				$string_start = $char;
			} // end else if (is start of string)
    
    			// ... for start of a comment (and remove this comment if found)...
			else if ($char == '#' || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
				// starting position of the comment depends on the comment type
				$start_of_comment = (($sql[$i] == '#') ? $i : $i-2);

				// if no "\n" exits in the remaining string, checks for "\r"
				// (Mac eol style)

				$end_of_comment   = (strpos(' ' . $sql, "\012", $i+2)) ? strpos(' ' . $sql, "\012", $i+2) : strpos(' ' . $sql, "\015", $i+2);

				if (!$end_of_comment) {
					// no eol found after '#', add the parsed part to the returned
					// array if required and exit
					if ($start_of_comment > 0) {
						$this->ret[] = trim(substr($sql, 0, $start_of_comment));
					}
					return TRUE;
				} else {
					$sql = substr($sql, 0, $start_of_comment).ltrim(substr($sql, $end_of_comment));
					$sql_len = strlen($sql);
					$i--;

				} // end if...else
			} // end else if (is comment)


		} // end for
    
		// add any rest to the returned array
		if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
			$this->ret[] = $sql;
		}
    
		return TRUE;
	}
}
?>