<?php
/**
 * Inicializo el gestor de Errores del sitio.
 * Para que no cargue todas las cosas nuevamente
 * verifico primero que no exista un config cargado previamente.
 */
if (!function_exists('miGestorErrores')) {

	/**
	 * Manejador interno de errores
	 *
	 * @access 	private
	 * @param	$mod_name		string	Nombre del módulo que genero el error, si esta vacio el error es un objeto global
	 * @param	$mod_version	string	Versión del módulo que generó el error
	 * @param	$err_level		integer	Número del nivel que generó el error
	 * @param	$err_nro		integer	Número del error generado
	 * @param	$err_msj		string	Mensaje de error del módulo
	 * @param	$err_gen		string	Datos para debug del error producido
	 */
	function error_handler($mod_name='--global--', $mod_version='', $err_level='', $err_nro=0, $err_msj='', $err_gen='') {

		$fileName = dirPath.'/includes/tmp/err_'.date('Y_m_d').'.php';

		$err = '';
		if (!file_exists($fileName)) {
			$err.= "<"."?php die();?"."> \n";
		}

		$err.= "\nMódulo:	".$mod_name." ver:".$mod_version;
		$err.= "\nError level:".$err_level;
		$err.= "\nFecha:		".date("Y m d - G:i:s");
		$err.= "\nAgente:		".$_SERVER['HTTP_USER_AGENT'];
		$err.= "\nURL:		".$_SERVER["REQUEST_URI"];
		$err.= "\nError Nro:	".$err_nro;
		$err.= "\nMensaje:	".str_replace(array("\n","\r"), " ", $err_msj);
		$err.= "\n ------------------------------------------------------ \n";

		$fp=fopen($fileName,'a+');
		fputs($fp,$err);
		fclose($fp);
	}

	error_reporting(E_ALL);

	/* Función de gestion de errores */
	function miGestorErrores($num_err, $cadena_err, $archivo_err, $linea_err, $vars_err) {

		if ($num_err < 2048) {
			$err_msj = 'Error en: '.$archivo_err.' linea: '.$linea_err.' cadena: '.$cadena_err;
			error_handler('', '', 'E_ERROR', $num_err, $err_msj, $vars_err);
		}
	}

	/* Establecer el gestor de errores definido */
	$gestor_errores_anterior = set_error_handler("miGestorErrores");
}


/** 
Solo Para test, luego eliminar
*/
function testXSS() {

	$fileName = dirPath.'/includes/tmp/xss_'.date('Y_m_d').'.log';

	$err = '';
	$err.= "\nFecha:		".date("Y m d - G:i:s");
	$err.= "\nAgente:		".$_SERVER['HTTP_USER_AGENT'];
	$err.= "\nURL:		".$_SERVER["REQUEST_URI"];
	
	if (!empty($_GET)) {
		$err.= "\nGET:	".print_r($_GET, true);
	}
	if (!empty($_POST)) {
		$err.= "\nPOST:	".print_r($_POST, true);
	}
	$err.= "\n ------------------------------------------------------ \n";
	$fp=fopen($fileName,'a+');
	fputs($fp,$err);
	fclose($fp);
	
	/** agrego que guarde info por cada página */
	$data = $_SERVER["REQUEST_URI"];
	$data = '<a href="'.$data.'" target="_blank">'.$data.'</a>';
	$data.= "\n";
	
	if (!empty($_POST)) {
		$data.= print_r($_POST, true);
		$data.= "\n";
	}

	$pag = str_replace("/", "-", $_SERVER["SCRIPT_NAME"]);
	$pag = trim(strtolower($pag));
	$pag = trim(strip_tags($pag));

	$pag = dirPath.'/includes/tmp/pag'.$pag.'.log';
	$fp=fopen($pag,'a+');
	fputs($fp,$data);
	fclose($fp);	
}

/*
if (!empty($_GET) || !empty($_POST)) {
	testXSS();
}
*/


/**
 * Versión Array, para que se pueda poner cualquier mes y dia en cualquier idioma.
 */
$dia_txt[0] = 'Domingo';
$dia_txt[1] = 'Lunes';
$dia_txt[2] = 'Martes';
$dia_txt[3] = 'Miercoles';
$dia_txt[4] = 'Jueves';
$dia_txt[5] = 'Viernes';
$dia_txt[6] = 'Sábado';

$mes_txt[1] = 'Enero';
$mes_txt[2] = 'Febrero';
$mes_txt[3] = 'Marzo';
$mes_txt[4] = 'Abril';
$mes_txt[5] = 'Mayo';
$mes_txt[6] = 'Junio';
$mes_txt[7] = 'Julio';
$mes_txt[8] = 'Agosto';
$mes_txt[9] = 'Septiembre';
$mes_txt[10] = 'Octubre';
$mes_txt[11] = 'Noviembre';
$mes_txt[12] = 'Diciembre';
?>