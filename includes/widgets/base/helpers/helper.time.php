<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>31.05.2012 <br/>
 *	- Added: Se agregó la función "toDuracion" para calcular 
 *	el tiempo ingresado en segundos a matriz de días, horas, 
 *	minutos y segundos.</li>
 *
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualizó la documentación de las funciones. </li>
 *
 * <li>21.01.2012 <br/>
 *	- Creación de Helpers Time.</li>
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
 * Formatea la fecha según el tipo establecido en formato de idioma español
 *
 * Tipos predefinidos: <br/>
 * <pre>
 *	%j%		1...31
 *	%d%		01...31
 *	
 *	%D%		Lun...Dom
 *	%l%		Lunes...Domingo
 *	
 *	%F%		Enero...Diciembre
 *	%M%		Ene...Dic
 *	
 *	%m%		01...12
 *	%n%		1...12
 *
 *	%Y%		99...15
 *	%y%		1999..2015
 *	
 *	%g%		0...12
 *	%G%		0...23
 *	
 *	%h%		00...12
 *	%H%		00...23
 *	
 *	%i%		00...59 (minutos)
 *	
 *	%s%		00...59	(segundos)
 * </pre>
 *
 * Ejemplo de uso: "%l% %j% de %F% de %y%" -> "Lunes 20 de Diciembre de 2010"
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	time 
 * @param		string	$tipo	Formato de la fecha a retornar
 * @param		integer	$date	Fecha en formato Timestamp
 * @return		string	Fecha formateada
 */
function formatDate($tipo='',$fecha=false) {

	$fecha = ($fecha) ? $fecha : time();

	$arrDate = array(
		'%j%' => 'j',
		'%d%' => 'd',
		'%m%' => 'm',		
		'%n%' => 'n',
		'%Y%' => 'Y',
		'%y%' => 'y',
		'%g%' => 'g',
		'%G%' => 'G',
		'%h%' => 'h',
		'%H%' => 'H',
		'%i%' => 'i',
		'%s%' => 's'
	);
	
	$dia[0] = 'Domingo';
	$dia[1] = 'Lunes';
	$dia[2] = 'Martes';
	$dia[3] = 'Miercoles';
	$dia[4] = 'Jueves';
	$dia[5] = 'Viernes';
	$dia[6] = 'Sábado';

	$mes[1] = 'Enero';
	$mes[2] = 'Febrero';
	$mes[3] = 'Marzo';
	$mes[4] = 'Abril';
	$mes[5] = 'Mayo';
	$mes[6] = 'Junio';
	$mes[7] = 'Julio';
	$mes[8] = 'Agosto';
	$mes[9] = 'Septiembre';
	$mes[10] = 'Octubre';
	$mes[11] = 'Noviembre';
	$mes[12] = 'Diciembre';	
	
	$txt = array();
	$txt['%l%'] = $dia[date('w', $fecha)];	
	$txt['%D%'] = substr($dia[date('w', $fecha)],0,3);
	$txt['%F%'] = $mes[date('n', $fecha)];
	$txt['%M%'] = substr($mes[date('n', $fecha)],0,3);
	
	foreach ($arrDate as $k => $v) {
		$tipo = str_replace($k, date($v, $fecha), $tipo);
	}
	
	foreach ($txt as $k => $v) {
		$tipo = str_replace($k, $v, $tipo);
	}

	return $tipo;
}

/**
 * Convierte Una fecha ISO a UNIXTIMESTAMP
 * Extraido de: http://www.idealog.us/2005/10/php_timestamp_h.html
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	time 
 * @param		string	$tstamp	Cadena de fecha. Ej: 1984-09-01T14:21:31Z
 * @return		integer	TimeStamp de la fecha ingresada
 */
function stringToTime($tstamp) {
	sscanf($tstamp,'%u-%u-%uT%u:%u:%uZ',$year,$month,$day, $hour,$min,$sec);
	$newtstamp=mktime($hour,$min,$sec,$month,$day,$year);
	return $newtstamp;
}


/**
 * Convierte segundos en formato humano de tiempo
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	time 
 * @param		string	$duracion
 * @return		array	matriz de datos dividido en hora, min, segundos
 */
function toDuracion($duracion=0) {

	$oDuracion = $duracion;
	
	$im = array(
		'dias' => 0, 
		'horas' => 0, 
		'minutos' => 0, 
		'segundos' => 0
	);

	if ($oDuracion > 86400) {
		$im['dias'] = floor($oDuracion/86400);
		$oDuracion = $oDuracion - ($im['dias'] * 86400);
	}
	
	if ($oDuracion > 3600) {
		$im['horas'] = floor($oDuracion/3600);
		$oDuracion = $oDuracion - ($im['horas'] * 3600);
	}
	
	if ($oDuracion > 60) {
		$im['minutos'] = floor($oDuracion/60);
		$oDuracion = $oDuracion - ($im['minutos'] * 60);
	}
	
	$im['segundos'] = $oDuracion;
	
	return $im;
}

/**
 * Muestra el tiempo que ha pasado desde el momento ingresado
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	time 
 * @param		integer	$fecha	Fecha en formato UNIXTIMESTAMP
 * @return		string	Formato de fecha transformado
 */
function fromTime($fecha=false) {

	// Por si es error (cero)
	if (!$fecha) {
		return '';
	}

	// Si es menor a una hora muestra: Hace xx'
	if ((time() - $fecha) < 3600) {
	
		if ((time() - $fecha) < 60) {
			return 'Hace 1 minuto';
		}
	
		$tiempo = ceil( (time() - $fecha) / 60 );
		return 'Hace '.$tiempo.' minutos';
	}

	// Si es menor a un día muestra la hora de publicación
	if ((time() - $fecha ) < 86400) {
		$tiempo = ceil( (time() - $fecha) / 3600);

		return 'Hace '.$tiempo.' horas';
	}
	
	// Si es mayor a un día muestra los días
	$fc = time() - $fecha;
	$fc_dias = ceil($fc/(60*60*24));
	
	return 'Hace '.$fc_dias.' días';
}
?>