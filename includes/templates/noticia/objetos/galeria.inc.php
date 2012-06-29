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
 * Muestra un bloque de galer�a de fotos solo si el art�culo devuelve m�s de una imagen
 * 
 */

/**
 * ***************************************************************************************
 * Esta galer�a muestra todas las imagenes en un tama�o grande en el interior del art�culo
 * con controles de anterior y siguiente con el javaScript CodaSlider
 *	/-----------------\
 *	|				  |
 *	| <		FOTO	> |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-codaslider.inc.php");


/**
 * ***************************************************************************************
 * Esta galer�a muestra todas las imagenes en un tama�o grande en el interior del art�culo
 * con controles de anterior y siguiente con el javaScript jCarrousel
 *	/-----------------\
 *	|				  |
 *	| <		FOTO	> |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-jcarrousel-1.inc.php");

/* Mismo esquema de jcarrousel pero utilizando el jcarrousel lite */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-jcarrousel-2.inc.php");


/**
 * ***************************************************************************************
 * Esta galer�a muestra todas las imagenes en un tama�o thumb y al cliquear la imagen, 
 * se muestra m�s grande con controles de anterior y siguiente.
 *	/----------------\
 *	|				 |
 *	| 	   FOTO	 	 |
 *	|				 |
 *	|----------------|
 *	| [FT] [FT] [FT] |
 *	\----------------/
 * ***************************************************************************************
 */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-scrollable-2.inc.php");


/**
 * ***************************************************************************************
 * Esta galer�a muestra la primer imagen en el interior del art�culo 
 * y la opci�n de ver el resto en un lightbox ampliado.
 *	/-----------------\
 *	|				  |
 *	| 		FOTO	  |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-more-lightbox.inc.php");


/**
 * ***************************************************************************************
 * Esta galer�a muestra todas las imagenes en un tama�o grande en el interior del art�culo
 * con controles de de posici�n de imagen y botones de anterior y siguiente con el javaScript jCarrousel
 *	/-----------------\
 *	|				  |
 *	| <		FOTO	> |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-jcarrousel-1.inc.php");
?>