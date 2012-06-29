<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	24.08.2010
 * *************************************************************** 
 *
 * Muestra un bloque de galería de videos solo si el artículo devuelve más de un video
 * 
 */

/**
 * Bloque simple de video
 *
 */
include (dirTemplate."/{$pathRelative}/objetos/video-inc-simple.inc.php"); 
 
/**
 * ***************************************************************************************
 * Esta galería muestra todas las imagenes en un tamaño grande en el interior del artículo
 * con controles de anterior y siguiente con el javaScript CodaSlider
 *	/-----------------\
 *	|				  |
 *	| <		VIDEO	> |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
include (dirTemplate."/{$pathRelative}/objetos/video-inc-codaslider.inc.php");
?>