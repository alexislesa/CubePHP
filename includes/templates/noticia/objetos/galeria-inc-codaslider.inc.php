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

	<div class="main-fotos coda-slider-wrapper">
	
		<?php
		/**
		 * Controles de anterior y siguiente
		 * además muestra la imagen que se esta visualizando y cuantas hay en total
		 */
		?>
		
		<?/*<span class="contador"><b>1</b> / <?php echo $total_imagenes;?></span>*/?>
		
		<div id="coda-nav-1" class="coda-nav">

			<ul>
				<?php for ($b=1; $b<=$total_imagenes; $b++) { ?>
				<li class="tab<?php echo $b;?>"><a href="#<?php echo $b;?>"><b><?php echo $b;?></b> / <?php echo $total_imagenes;?></a></li>
				<?php } ?>
			</ul>
			
		</div>
		
		<div class="nota-foto coda-slider preload" id="coda-slider-1">

				<?php 
				/**
				 * Genera un bloque para cada imagen devuelta por el artículo
				 */
				foreach ($v["imagen"] as $m_img => $v_img) { ?>
				
					<div class="panel">
					
						<figure class="cont-foto panel-wrapper">
						
							<img src="<?php echo $v_img["url"]["o"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" width="640" />

							<? if ($v_img['adjunto_creditos'] != '') {?>
							
								<div class="foto-credit">
									<span>Crédito:</span> <?php echo $v_img['adjunto_creditos'];?>
								</div><!-- credit -->

							<? } ?>							

							<?php 
							/* Pie de foto */
							if ($v_img["adjunto_descripcion"] != "") {?>
								
								<figcaption><strong>Foto: </strong><?php echo $v_img["adjunto_descripcion"];?></figcaption>
								
							<?php } ?>
							<!--fin pie-->
						
						</figure><!--fin cont foto-->
					
					</div><!--/.panel-->
					
				<?php } ?>
		
		</div>

	</div>
	
	<script type="text/javascript">
	$(document).ready(function() {
	
		$("#coda-slider-1").codaSlider({
			dynamicTabs: false,
			crossLinking: false
		});

		/*$(".main-fotos").hover( function () {
			$(".coda-nav-left, .coda-nav-right").animate({opacity: 0.6}, 500);
		}, function () {
			$(".coda-nav-left, .coda-nav-right").animate({opacity:0}, 500);
		});
		
		$(".coda-nav-left, .coda-nav-right").hover(function(){ 
			$(this).animate({opacity: 0.8}, 0);
		}, function() {
			$(this).animate({opacity: 0.6}, 0);
		});*/
	});
	</script>

<?php } ?>