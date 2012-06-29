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
 * Muestra un bloque de documento embebido en el HTML del texto del artículo
 * El artículo carga este bloque para cada una de los documentos embebidos en el HTML
 * 
 *
 * Array de datos que es posible devolver:
 *	- gal_file_ext		La extensión del archivo
 *	- gal_file			Nombre del archivo
 *	- adjunto_descripcion	Pie de foto
 *	- gal_fecha			Fecha de publicación en el sitio
 *	- extra["size"]		Peso en Bytes
 *	- url["o"]			Url del documento
 *   
 *	- $peso_doc			Devuelve el peso del documento en KB/MB/GB etc
 *
 * @changelog:
 */

$m = $dataToSkin;
$peso = getFileSize($m['extra']['size']);
?>

<div class="embed">

	<div class="inner-doc">

		<span class="ico <?php echo $m['gal_file_ext'];?>"></span><!--cambia class segun tipo de documento-->

		<a href="/extras/descargas/descarga.php?id=<?php echo $m['gal_id'];?>" title="<?php echo $m['gal_nombre'];?>">

			<?php echo $m['gal_nombre'];?>

		</a> (<?php echo $peso;?>)
		
		<?php if ($m['texto'] != '') { ?>
			
			<?php echo $m['texto'];?>
		
		<?php } ?>

		<div class="clear"></div>
		
	</div>

</div><!--/.embed-->