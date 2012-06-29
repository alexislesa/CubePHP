<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>15.05.2012 <br/>
 *	- Added: Se agregó que la función climaYahoo devuelva información 
 *	sobra la ubicación del clima a consultar (localidad, etc) </li>
 *
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualizó la documentación de las funciones. </li>
 *
 * <li>21.01.2012 <br/>
 *	- Creación de Helpers Site.</li>
 * </ul>
 *
 * @package		CubePHP
 * @subpackage	helpers 
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory (c) 2010-2012
 * @licence 	Comercial
 * @version 	0.90
 */

/**
 * Carga la portada seleccionada y devuelve la información en forma de array
 * Si esta en modo preliminar carga la portada preliminar
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	site
 * @param		string	$portada	Identificador de la portada
 * @return		array	Matriz de los datos devueltos de la portada seleccionada
 */
function loadHome($portada=false) {

	if ($portada === false) {
		return false;
	}
	
	/* Agregado para que levante la versión preliminar */
	$fPrefix = !empty($_GET['ref']) 
			? ( ($_GET['ref'] == 'preview' || $_GET['ref'] == 'preliminar') ? '-p' : '' ) 
			: '';
	
	$file_portada = dirPath.'/includes/cache/portada-'.$portada.$fPrefix.'.inc.look.php';

	/* Consulto si no estoy en modo de portada temporal 
	(que se esta grabando actualmente) y si es asi cargo esa */
	if (!file_exists($file_portada)) {
		$file_portada = dirPath.'/includes/cache/portada-'.$portada.$fPrefix.'.inc.php';
	}

	if (!file_exists($file_portada)) {
		return false;
	}

	$contenido = file_get_contents($file_portada);

	if ($contenido == '') {
		return false;
	}
	
	// return unserialize($contenido);
	if (function_exists('__unserialize')) {
		$m = __unserialize($contenido);
	} else {
		$m = unserialize($contenido);
	}
	return $m;	
}

/**
 * Retorna información de Clima de Yahoo
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	site
 * @param		string	$file	Ruta completa del archivo
 * @return		array	Matriz de los datos devueltos del clima
 */
function climaYahoo($file='') {

	if ($file == '') {
		return false;
	}

	// fileName: dirBase."/includes/cache/clima-{$id}.xml";
	if (file_exists($file)) {
		$c = file_get_contents($file);
		$clima_id = unserialize($c);
		
		/* Temperatura en C° */
		$f = $clima_id['rss']['channel'];
		$clima_temperatura = $f['item']['yweather:condition']['attr']['temp'];

		/* Velocidad del viento en Km/h */
		$clima_viento = $f['yweather:wind']['attr']['speed'];

		/* Humedad en % */
		$clima_humedad = $f['yweather:atmosphere']['attr']['humidity'];

		/* Visibilidad en Km */ 
		$clima_visibilidad = $f['yweather:atmosphere']['attr']['visibility'];

		/* Presión atmosférica en mb */
		$clima_presion = $f['yweather:atmosphere']['attr']['pressure'];

		/* Código del clima */
		$clima_codigo = $f['item']['yweather:condition']['attr']['code'];

		/* Latitud y Long geográfica */ 
		$clima_latitud = $f['item']['geo:lat']['value'];
		$clima_longitud = $f['item']['geo:lat']['value'];
		$clima_localidad = $f['yweather:location']['attr']['city'];
		
		/* Pronostico para hoy */
		$clima_hoy_min = $f['item']['yweather:forecast']['0']['attr']['low'];
		$clima_hoy_max = $f['item']['yweather:forecast']['0']['attr']['high'];
		$clima_hoy_img = $f['item']['yweather:forecast']['0']['attr']['code'];

		/* Pronostico para mañana */
		$clima_man_min = $f['item']['yweather:forecast']['1']['attr']['low'];
		$clima_man_max = $f['item']['yweather:forecast']['1']['attr']['high'];
		$clima_man_img = $f['item']['yweather:forecast']['1']['attr']['code'];		
		
		$im = array();
		
		$im['temp'] = $clima_temperatura;
		$im['viento'] = $clima_viento;
		$im['humedad'] = $clima_humedad;
		$im['visibilidad'] = $clima_visibilidad;
		$im['presion'] = $clima_presion;
		$im['img'] = $clima_codigo;

		$im['location'] = $clima_localidad;
		$im['lat'] = $clima_latitud;
		$im['long'] = $clima_longitud;
		
		$im['hoy_min'] = $clima_hoy_min;
		$im['hoy_max'] = $clima_hoy_max;
		$im['hoy_img'] = $clima_hoy_img;
		
		$im['man_min'] = $clima_man_min;
		$im['man_max'] = $clima_man_max;
		$im['man_img'] = $clima_man_img;
		
		return $im;
	}
}

/**
 * Función que devuelve el tiempo estimado de lectura de la nota
 * 
 * Retorna array de datos con horas, min y segundos estimados de lectura
 * Idea extraida desde: http://oloblogger.blogspot.com/2011/06/calcular-y-mostrar-el-tiempo-de-lectura.html
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	site
 * @param		string	$texto	Texto a consultar el tiempo
 * @param		integer	$ppmin	Palabras por minuto de lectura (por defecto 300)
 * @return		array	Array con las claves: hora, min y seg
 */
function timeToRead($texto, $ppmin=0) {

	if (!$ppmin) {
		// persona promedio utiliza 300 pal. p/min
		$ppmin = 300;
	}

	$horas = 0;
	$min = 0;
	$seg = 0;
	
	$texto = strip_tags($texto);
	$texto = trim($texto);
	$texto = str_replace(array('\n','\r', '  '), ' ', $texto);
	$texto = str_replace('  ', ' ', $texto);
	$texto_arr = explode(' ', $texto);

	$p = count($texto_arr);
	$horas = ($p >= 3600) ? floor($p / 3600) : 0;

	$p = $p - ($horas*3600);
	$min = ($p >= 60) ? floor($p/60) : 0;
	
	$p = $p - ($min*60);
	$seg = $p;

	$ar = array();
	$ar['hora'] = $horas;
	$ar['min'] = $min;
	$ar['seg'] = $seg;
	return $ar;
}
?>