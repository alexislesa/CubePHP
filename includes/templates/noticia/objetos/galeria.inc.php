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
 * Muestra un bloque de galería de fotos solo si el artículo devuelve más de una imagen
 * 
 */

/**
 * ***************************************************************************************
 * Esta galería muestra todas las imagenes en un tamaño grande en el interior del artículo
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
 * Esta galería muestra todas las imagenes en un tamaño grande en el interior del artículo
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
 * Esta galería muestra todas las imagenes en un tamaño thumb y al cliquear la imagen, 
 * se muestra más grande con controles de anterior y siguiente.
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
 * Esta galería muestra la primer imagen en el interior del artículo 
 * y la opción de ver el resto en un lightbox ampliado.
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
 * Esta galería muestra todas las imagenes en un tamaño grande en el interior del artículo
 * con controles de de posición de imagen y botones de anterior y siguiente con el javaScript jCarrousel
 *	/-----------------\
 *	|				  |
 *	| <		FOTO	> |
 *	|				  |
 *	\-----------------/
 * ***************************************************************************************
 */
// include (dirTemplate."/{$pathRelative}/objetos/galeria-inc-jcarrousel-1.inc.php");
?>