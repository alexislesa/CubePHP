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
 * Esta galería muestra todas las imagenes en un tamaño thumb y al cliquear la imagen, 
 * esta se muestra más grande con controles de anterior y siguiente.
 *
 *	[    FOTOS    ]
 *	<< [t][t][t] >>
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
$img_bloques = 4;	// Cantidad de imagenes por bloque

if (!empty($v["imagen"]) && is_array($v["imagen"]) && count($v["imagen"]) > 1)  { 
	$total_imagenes = count($v["imagen"]);
	
	$bloques = ceil($total_imagenes/$img_bloques);
	$vi = 0;
	?>

	
	<figure>
	
		<img src="<?php echo $v["imagen"][1]["url"]["o"];?>" />

		<figcaption><?php echo $v["imagen"][1]["adjunto_descripcion"];?></figcaption>
	
	</figure>
	
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
		</ul>
		
		<div class="pasador">
		
			<div class="items-pasador">
			
				<?php 
				/**
				 * Genera un bloque para cada imagen devuelta por el artículo
				 */
				for ($i=0; $i<$bloques; $i++) { ?>
				
					<div>
				
						<?php for ($q=0; $q<$img_bloques; $q++) { 
							$vi++;
							
							if (empty($v["imagen"][$vi])) {
								$v_img = $v["imagen"][$vi];
								?>
								
								<figure>			
						
									<a href="<?php echo $v_img["url"]["o"];?>" title="<?php echo $v_img["adjunto_descripcion"];?>">
										<img src="<?php echo $v_img["url"]["t"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" />
									</a>
									
								</figure>
								
							<?php
							}
						} ?>
					
					</div>
					
				<?php } ?>
			
			</div>
		
		</div>

	</div>
	
	<script type="text/javascript">
	$(function() {
		$(".pasador").scrollable({
			onSeek: function(event, i) {
			}
		});
	});
	
	$("item-foto a").click(function() {
		var m = $(this).attr("href");
		var t = $(this).attr("title");
		
		$(".foto img").attr("src", m);
		$(".foto-desc").html(t);
		return false;
	});
	</script>

<?php } ?>