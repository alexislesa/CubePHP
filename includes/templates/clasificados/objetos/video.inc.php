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
 * Muestra un bloque de galer�a de videos solo si el art�culo devuelve m�s de un video
 * 
 */

/**
 * Bloque simple de video
 *
 */
include (dirTemplate."/{$pathRelative}/objetos/video-inc-simple.inc.php"); 
 
/**
 * ***************************************************************************************
 * Esta galer�a muestra todas las imagenes en un tama�o grande en el interior del art�culo
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