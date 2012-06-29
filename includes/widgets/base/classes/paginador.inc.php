<?php
/**
 * Clase para generar las variables necesarias para páginar resultados.
 * 
 */
class Paginador {

	/** 
	 * Página actual de resultados
	 * 
	 * @access public
	 * @public integer
	 */
	public $actual;
	
	/** 
	 * Cantidad de resultados por página
	 * 
	 * @access public
	 * @public integer
	 */
	public $cantidad;
	
	/**
	 * Array de resultados a devolver
	 *
	 * <pre>
	 * Estructura de la matriz devuelta:
	 *	actualId		ID de la página actual (por defecto 0)
	 *	actualNro		Número de la página actual (generalmente sería ID+1)
	 *
	 *	totales			Total de páginas del resultado
	 *	resultados		Totalidad de resultados devueltos
	 *	cantidad		Cantidad de resultados por página
	 *	url				Url base de paginación (puede ser: resultados.php, pagina.php, etc)
	 *	variable		Variable de paginación (por defecto siempre es "p")
	 *
	 *	anteriorId		Id de la página anterior (0 si es la primera)
	 *	anteriorUrl		URL completa de la página anterior		
	 *	anteriorView	TRUE si la página anterior es diferente a la actual, FALSE si son las mismas
	 *
	 *	siguienteId		Id de la página siguiente (cantidad-1 si es la última)
	 *	siguienteUrl	URL completa de la página siguiente
	 *	siguienteView	TRUE si la página siguiente es diferente a la actual, FALSE si son las mismas
	 *
	 *	paginadorInico	Si pag_paginador es mayor a cero, indica el inicio de bloque de resultados.
	 *	paginadorFin	Si pag_paginador es mayor a cero, indica el fin de bloque de resultados
	 * </pre>
	 *
	 * @access public
	 * @public array
	 */
	public $datos;
	
	/** 
	 * Método utilizado para paginar resultados (get/post)
	 * 
	 * @access public
	 * @public string
	 */
	public $metodo;
	
	/** 
	 * Indica la cantidad de resultados por página en cada paginador
	 *
	 * Esta variable me indica cuantos elementos muestro en la paginación
	 * Ej: anterior .. 1-2-3-4-5 .. siguiente
	 *
	 * @access public
	 * @public integer
	 */
	public $paginador;
	
	/** 
	 * Total resultados de la consulta
	 * 
	 * @access public
	 * @public integer
	 */	
	public $resultados;	
	
	/** 
	 * Total de páginas devueltas
	 * 
	 * @access public
	 * @public integer
	 */	
	public $total;

	/** 
	 * Variable utilizada para paginar los resultados
	 * Generalmente se utiliza 'p'
	 *
	 * @access public
	 * @public string
	 */
	public $variable;

	/**
	 * Constructor de la clase
	 * Inicializo los parametros básicos
	 *
	 * @access	public
	 */
	public function __construct() {
	
		$this->total = 0;
		$this->resultados = 0;
		$this->cantidad = 0;
		$this->actual = 0;
		$this->metodo = 'get';
		$this->variable = 'p';
		$this->paginador = 10;
		$this->url = '';
	}

	/**
	 * Procesa los datos ingresados y genero matriz con los parametros
	 *
	 * @access	public
	 * @return	array	Matriz de datos, false en caso de error
	 */
	public function process($urlto = false) {

		if (!$this->total) {
			return false;
		}

		$info = ($this->metodo == 'get') ? $_GET : $_POST;
		foreach ($info as $k => $v) {
		
			$k = strtolower($k);

			if ( is_array($v) ) {
			
				foreach ($v as $i => $o) {
					$this->url.= ($k != $this->variable) ? '&'.$k.'['.$i.']='.$o : "";
				}
				
			} else {
			
				$this->url.= ($k != $this->variable) ? '&'.$k.'='.$v : '';
			}
		}
		$this->url = substr($this->url,1);
		$this->url = '?'.$this->url;
		
		// Arreglo para mostrar desplazamiento de las páginas
		// mostrando algunas anteriores, la actual y algunas siguientes.
		// Ej: ... [3] 4 [5] [6] [7] ...

		$desplaza = round(($this->paginador - 1) / 2);

		$oInicial = (($this->actual - $desplaza) > 0) ? ($this->actual - $desplaza) : 0;

		$oFinal = (($oInicial + $this->paginador) > $this->total) 
				? $this->total 
				: ($oInicial + $this->paginador);

		if ($oFinal == $this->total) {
			if (($oFinal - $this->paginador) > 0) {
				$oInicial = $oFinal - $this->paginador;
			} else {
				$oInicial = 0;
			}
		}

		$this->datos['actualId'] = $this->actual;
		$this->datos['actualNro'] = $this->actual +1;
		
		$this->datos['totales'] = $this->total;
		$this->datos['resultados'] = $this->resultados;
		$this->datos['cantidad'] = $this->cantidad;
		$this->datos['url'] = $this->url;
		$this->datos['variable'] = $this->variable;

		// Calculo de pag. anterior y pag siguiente.
		$this->datos['anteriorId'] = ($this->actual) ? ($this->actual-1) : 0;
		$this->datos['anteriorUrl'] = $this->url.'&'.$this->variable.'='.$this->datos['anteriorId'];
		$this->datos['anteriorView'] = ($this->actual) ? 1 : 0;
		
		$this->datos['siguienteId'] = ($this->actual < ($this->total-1)) ? ($this->actual + 1) : $this->actual;
		$this->datos['siguienteUrl'] = $this->url.'&'.$this->variable.'='.$this->datos['siguienteId'];
		$this->datos['siguienteView'] = ($this->datos['siguienteId'] != $this->datos['actualId']) ? 1 : 0;

		$this->datos['paginadorInicio'] = $oInicial;
		$this->datos['paginadorFin'] = $oFinal;

		return $this->datos;
	}
}
?>