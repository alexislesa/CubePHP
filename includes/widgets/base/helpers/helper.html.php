<?php
/**
 * CubePHP
 *
 * Framework de Desarrollo 
 *
 * <b>Changelog</b> <br/>
 *
 * <ul>
 * <li>08.04.2012 <br/>
 *	- Modify: Se optimizaron las funciones del helpers. 
 *	Se actualizó la documentación de las funciones. </li>
 *
 * <li>21.01.2012 <br/>
 *	- Creación de Helpers html.</li>
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
 * Busca en el texto algo que se parezca a un vínculo y devuelve el HTML del vínculo
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	html
 * @param		string	$texto	Texto a revisar
 * @return		string	Texto modificado con vínculo
 */
function clickable($texto='') {

	if ($texto == '') {
		return $texto;
	}
	
	$ret = ' '.$texto;

	// busca "xxxx://yyyy" URL al comienzo del línea o despues de un espacio.
	// xxxx solo caracteres alfabeticos.
	// yyyy cualquier cosa menos espacio, salto de línea, coma, tilde o simbolo
	$ret = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" target="_blank" >\2://\3</a>', $ret);

	// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
	// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
	// zzzz is optional.. will contain everything up to the first space, newline, 
	// comma, double quote or <.
	$ret = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" target="_blank" >\2.\3</a>', $ret);

	// matches an email@domain type address at the start of a line, or after a space.
	// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
	$ret = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\" >\\2@\\3</a>", $ret);

	$ret = substr($ret, 1);
	
	return($ret);
}

/**
 * Cierra los tags HTML que pueden haber quedado abiertos en la publicación
 * Extraido desde: http://snipplr.com/view/3618/close-tags-in-a-htmlsnippet/
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	html
 * @param		string	$html	Texto a cerrar las etiquetas
 * @return		string	Texto con las etiqueta completas
 */
function closeTags($html='') {

	if ($html == '') {
		return $html;
	}

	$html = (strrpos($html, '<') > strrpos($html, '>')) ? rtrim( substr( $html, 0, strrpos($html, '<') ) ) : rtrim($html);

	/* Carga todas las tags abiertas */
	preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
	$openedtags = $result[1];

	/* Carga todas las tags cerradas */
	preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
	$closedtags = $result[1];

	$len_opened = count ( $openedtags );

	/* Comprueba que la cantidad de tags abiertas son igual que las cerradas */
	if( count ( $closedtags ) == $len_opened ) {
		return $html;
	}

	$openedtags = array_reverse ( $openedtags );

	/* Cierra todas las tags que quedaron abiertas */
	for( $i = 0; $i < $len_opened; $i++ ) {

		if ( !in_array ( $openedtags[$i], $closedtags ) ) {
		
			/* Si el comando es BR no lo cierro, para evitar errores futuros */
			if (strtolower($openedtags[$i]) != 'br') {
				$html .= '</' . $openedtags[$i] . '>';
			}
		} else {
			unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
		}
	}

	return $html;
}	

/**
 * Procesa las url de los archivos adjunto y devuelve array de url definitivo
 *
 * @package		CubePHP
 * @subpackage	helpers
 * @category	html 
 * @param		array	$datos	Matriz de información de los adjuntos
 * @param 		string 	$file	Nombre del archivo
 * @param 		integer	$fecha 	Fecha de creación del archivo
 * @param 		string 	$tam 	Tamaño a devolver, false para retornar todos
 * @return 		array 	Url final de cada uno de los tamaños
 */
function pathAdjunto($datos='', $file='', $fecha=false, $tam=false) {

	$ret = array();

	if ($datos == '' || $file == '' || !$fecha) {
		return false;
	}

	$pBase = '';
	if ($datos['path'] != '') {
		$pBase = $datos['path'];
		
		// Reemplazo los parametros dinámicos con datos actuales
		$m = array('d', 'j', 'N', 'w', 'z', 'W', 'm', 'n', 
				'Y', 'y', 'g', 'G', 'h', 'H', 'i', 's', 'U');
		foreach ($m as $fId => $fType) {
			$pBase = str_replace('%'.$fType, date($fType, $fecha), $pBase);
		}
	}

	if (!$datos['is_image']) {
		
		if (!$tam || $tam == 'o') {
			$ret['o'] = !empty($datos['save_data']['url']) ? $datos['save_data']['url'].$pBase.$file : $file;
		}

	} else {

		$fileName = $file;
		$fileNameBody = substr($fileName,0,strrpos($fileName,'.'));
		$fileNameExt = substr($fileName, strlen($fileNameBody)+1);

		$imgDatos = explode('|', trim($datos['imagen']));
		$imgDatos = array_map('trim', $imgDatos);

		// name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
		foreach($imgDatos as $j=> $m) {
			$mf = explode(',', $m);
			
			$imgPrefix = '';
			$imgSufix = '';
			$imgFolder = '';
			list($imgName,$imgX,$imgY,$imgFolder,$imgPrefix,$imgSufix,$imgCrop) = array_pad($mf,7,'');
			unset($mf);
			
			$filePathName = $datos['save_data']['url'].$pBase;
			$filePathName.= ($imgFolder != '') ? $imgFolder.'/' : '';
			$filePathName.= ($imgPrefix != '') ? $imgPrefix : '';
			$filePathName.= $fileNameBody;
			$filePathName.= ($imgSufix != '') ? $imgSufix : '';
			$filePathName.= '.'.$fileNameExt;
			
			if (!$tam || $tam == $imgName) {
				$ret[$imgName] = $filePathName;
			}
		}
	}

	return $ret;
}
?>