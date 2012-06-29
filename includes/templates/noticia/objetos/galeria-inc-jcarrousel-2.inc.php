<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	12.09.2011
 * *************************************************************** 
 *
 * Muestra un bloque de galería de fotos solo si el artículo devuelve más de una imagen
 * 
 * Esta galería muestra todas las imagenes en el interior del artículo con controles de anterior y siguiente
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
 * jcarrousel lite: http://www.gmarwaha.com/jquery/jcarousellite/
 * 
 * @changelog:
 */

if (!empty($v["imagen"]) && is_array($v["imagen"]) && count($v["imagen"]) > 1)  { 
	$total_imagenes = count($v["imagen"]);
	?>

	<?php
	/**
	 * Muestra la imagen que se esta visualizando y cuantas hay en total
	 */
	?>
	<span class="contador"><b>1</b> / <?php echo $total_imagenes;?></span>
	
	<div id="mycarousel" class="slider">
	
		<ul>

			<?php 
			/**
			 * Genera un bloque para cada imagen devuelta por el artículo
			 */
			foreach ($v["imagen"] as $m_img => $v_img) { ?>
			
				<li>
				
					<figure class="cont-foto panel-wrapper">				

						<img src="<?php echo $v_img["url"]["o"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" width="432" height="324" />

						<? if ($v_img['adjunto_creditos'] != '') {?>
						
							<div class="foto-credit">
								<span>Crédito:</span> <?php echo $v_img['adjunto_creditos'];?>
							</div><!-- credit -->

						<? } ?>						
						
						<?php 
						/* Pie de foto */
						if ($v_img["adjunto_descripcion"] != "") {?>

							<figcaption>
								<strong>Foto: </strong><?php echo $v_img["adjunto_descripcion"];?>
							</figcaption>
							
							
						<?php } ?>
					
					</figure><!--fin cont foto-->
				
				</li>
				
			<?php } ?>

		</ul>

		<?php
		/**
		 * Controles de la imagen que se esta visualizando y cual quier visualizar
		 */
		?>
		<div class="jcarousel-control">
			<?php for ($b=1; $b<=$total_imagenes; $b++) { ?>
			
				<a href="#"><b><?php echo $b;?></b></a>
				
			<?php } ?>
		</div>
	
	</div><!--fin slider-->
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#mycarousel").jCarouselLite({
				scroll: 1,
				wrap: "both",
				visible: 1,
				afterEnd: function(a) {
				},
				circular: false,
				btnNext: ".next",
				btnPrev: ".prev"
			});
		});
	</script>

<?php } ?>