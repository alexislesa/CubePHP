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
 * Esta galería muestra todas las imagenes en el interior del artículo con controles de anterior y siguiente.
 *
 *	<< [FOTO] >>
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
if (!empty($v["imagen"]) && is_array($v["imagen"]) && count($v["imagen"]) > 1)  { 
	$total_imagenes = count($v["imagen"]);
	?>

	<div class="main-fotos">
	
		<?php
		/**
		 * Controles de anterior y siguiente
		 * además muestra la imagen que se esta visualizando y cuantas hay en total
		 */
		?>
		<ul class="control-p2">
		
			<li id="prev" class="prev">Anterior</li>
			<li id="next" class="next">Siguiente</li>
			
			<span class="contador"><b>1</b> / <?php echo $total_imagenes;?></span>
			
		</ul>
		
		<div class="nota-foto">
		
			<div class="items-foto">
			
				<?php 
				/**
				 * Genera un bloque para cada imagen devuelta por el artículo
				 */
				foreach ($v["imagen"] as $m_img => $v_img) { ?>
				
					<figure="cont-foto">
			
						<img src="<?php echo $v_img["url"]["o"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" width="250" height="250" />

						<? if ($v_img['adjunto_creditos'] != '') {?>
						
							<div class="foto-credit">
								<span>Crédito:</span> <?php echo $v_img['adjunto_creditos'];?>
							</div><!-- credit -->

						<? } ?>						

						<?php 
						/**
						 * Pie de foto
						 */
						if ($v_img["adjunto_descripcion"] != "") {?>
							
							<figcaption>
							
								<?php echo $v_img["adjunto_descripcion"];?>
							
							</figcaption><!--fin pie-->
							
						<?php } ?>
					
					</figure><!--fin cont foto-->
				
				<?php } ?>
			
			</div>
		
		</div>

	</div>
	
	<script type="text/javascript">
	$(function() {
		$(".nota-foto").scrollable({
			onSeek: function(event, i) {
				$(".control-p2 span.contador b").html(i+1);
			}
		});
	});
	</script>
	
	<?php
	/**
	 * Agregado para que se la altura pueda ser relativa 
	<script type="text/javascript">
	$(function() {
		$(".nota-foto").scrollable({ 
			circular: true, 
			mousewheel: false, 
			onBeforeSeek: function(evt, index){
				$(".nota-foto").stop().animate({ height: $(".cont-foto").eq(index+1).height()+'px'}, 250);
			}
		}).navigator();
	});
	</script>	
	*/ ?>

<?php } ?>