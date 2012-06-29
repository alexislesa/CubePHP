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
 * Muestra un bloque de fotos solo si el artículo devuelve una sola imagen
 * 
 * Array de datos que es posible devolver:
 *	- gal_file_ext		La extensión del archivo
 *	- gal_file			Nombre del archivo
 *	- adjunto_descripcion	Pie de foto
 *	- gal_fecha			Fecha de publicación en el sitio
 *	- extra["size"]		Peso en Bytes
 *	- extra["width"]	Ancho de la imagen Original
 *	- extra["height"]	Alto de la imagen Original
 *	- url["o"]			Url de la imagen original
 *	- url["t"]			Url de la imagen thumbnails más chica
 *
 * @changelog:
 */
if (!empty($v["imagen"]) && count($v["imagen"]) == 1) { 
	$m = $v["imagen"][1];
	?>
	
	<figure class="nota-foto">
		
		<img src="<?php echo $m["url"]["o"];?>" alt="<?php echo $m["adjunto_descripcion"];?>" title="<?php echo $m["adjunto_descripcion"];?>" width="640" />

		<?php if ($m["adjunto_descripcion"] != '') {?>
			
			<figcaption>
				<strong>Foto: </strong><?php echo $m["adjunto_descripcion"];?>
			</figcaption>
			
		<?php } ?>
		
	</figure>

<?php } ?>