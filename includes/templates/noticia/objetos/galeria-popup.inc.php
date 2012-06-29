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
 * Muestra la galería de fotos en un popup/lightbox
 * 
 * Esta galería muestra todas las imagenes con controles de anterior y siguiente.
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
 *	- $total_imagenes	Devuelve la cantidad de imagenes del artículo
 *
 * @changelog:
 */

if ($total_imagenes) { 

	$total_imagenes = count($v["imagen"]);

	$nota_foto_id = ($nota_foto_id > (count($v["imagen"])+1)) ? (count($v["imagen"])+1) : $nota_foto_id;
	$im = !empty($v["imagen"][$nota_foto_id]) ? $v["imagen"][$nota_foto_id] : 1;

	// Anterior
	$pag_anterior = ($nota_foto_id > 1) ? 1 : 0;
	$pag_anterior_url = ($nota_foto_id -1);

	// Siguiente
	$pag_siguiente = ($nota_foto_id < count($v["imagen"])) ? 1 : 0;
	$pag_siguiente_url = $nota_foto_id +1;
	?>

	<div class="main-galeria">
		
		<a href="#" onclick="closepopup();" class="cerrar">[X]</a>
		
		<div class="inner-galeria">
			
			<h4>Fotogaleria:</h4>
			
			<div><?php echo $v["noticia_titulo"];?></div>
			
			<figure>
				
				<img src="<?php echo $im["url"]["o"];?>" />

				<? if ($im['adjunto_creditos'] != '') {?>
				
					<div class="foto-credit">
						<span>Crédito:</span> <?php echo $im['adjunto_creditos'];?>
					</div><!-- credit -->

				<? } ?>				
				
				<figcaption>
					<?php echo $im["adjunto_descripcion"];?>
				</figcaption>
				
			</figure>
			
			<div id="paginador">
			
				<span>Foto <b><?php echo $nota_foto_id;?></b> de <?php echo $total_imagenes;?></span>
			
				<?php if ($pag_anterior) { ?>
				
					<a title="Anterior" id="anterior"href="?id=<?php echo $nota_id?>&fid=<?php echo $pag_anterior_url;?>">Anterior</a>
					
				<?php } ?>
			
			
				<?php for ($i=1; $i<=count($v["imagen"]); $i++) { 
					$clase = ($i==$nota_foto_id) ? "active" : "";
					?>
				
					<a href="?id=<?php echo $nota_id?>&fid=<?php echo $i;?>" class="<?php echo $clase;?>"><?php echo $i;?></a>
				
				<?php } ?>
			
			
				<?php if ($pag_siguiente) { ?>
					
					<a id="siguiente" title="Siguiente" href="?id=<?php echo $nota_id?>&fid=<?php echo $pag_siguiente_url;?>">Siguiente</a>
				<?php } ?>
			
			</div><!--fin paginator-->
			
		</div><!--fin inner galeria-->
		
	</div><!--fin main galeria-->


<?php } ?>

<script type="text/javascript">
/* Revisa si estoy en popup o ligthbox y cierro la ventana */ 
function closepopup() {
	if (top.location.href == self.location.href) {
		self.close();
	} else {
		window.top.tb_remove();
	}
};
</script>