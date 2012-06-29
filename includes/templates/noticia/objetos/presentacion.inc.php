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
 * Muestra un bloque con las presentaciones relacionadas del artículo
 * 
 * Array de datos que es posible devolver:
 *	- gal_file			Url del enlace
 *	- gal_nombre		Titulo/Nombre del enlace
 *	- gal_descripcion	Descripción del enlace
 *
 *	- $total_link		Devuelve la cantidad de presentaciones del artículo
 *
 * @changelog: 
 */
if (!empty($v["presentacion"]) && is_array($v["presentacion"])) {
	$totalLinks = count($v["presentacion"]);
	?>

	<section class="article-block" id="slide">
		
		<h4>Presentación</h4>
		
		<div class="coda-slider-wrapper">

			<?php
			/**
			 * Controles de anterior y siguiente
			 * además muestra la imagen que se esta visualizando y cuantas hay en total
			 */
			 
			if ($totalLinks > 1) { ?>
			
				<div id="coda-nav-3" class="coda-nav">
		
					<ul>
						<?php for ($b=1; $b<=$totalLinks; $b++) { ?>
						<li class="tab<?php echo $b;?>"><a href="#<?php echo $b;?>"><b><?php echo $b;?></b> / <?php echo $totalLinks;?></a></li>
						<?php } ?>
					</ul>
					
				</div><!--fin coda-nav-->
				
			<?php } ?>
	
			<div class="nota-slide coda-slider preload" id="coda-slider-3">
			
				<?php foreach ($v["presentacion"] as $j => $m) { ?>

					
					<div class="panel">
					
						<?php 
						/* Bloque si es slideshare */
						if ($m["extra"]["link_type"] == "slideshare") {	?>
							
							<div>
								<iframe src="http://www.slideshare.net/slideshow/embed_code/<?php echo $m["extra"]["link_id"];?>" width="640" height="480" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe> 				
									
							</div>				
						
						<?php } ?>
					
						<?php 	/* Bloque si es prezi */
						if ($m["extra"]["link_type"] == "prezi") { ?>
						
							<div>
							
								<div id="presentid<?php echo $j;?>"></div>
								
								<script type="text/javascript">
								<!-- // --><![CDATA[
								var flashvarsv<?php echo $j;?> = {
									prezi_id:"<?php echo $m["extra"]["link_id"];?>", 
									lock_to_path: 0,
									color: "ffffff",
									autoplay: "no",
									autohide_ctrls: 0
								}

								var paramsv<?php echo $j;?> = {
									allowfullscreen:"true", 
									allowscriptaccess:"always",
									wmode:"opaque"
								}

								var attributesv<?php echo $j;?> = {
									id:"idytubeplayer<?php echo $j;?>",  
									name:"idytubeplayer<?php echo $j;?>"
								}

								swfobject.embedSWF("http://prezi.com/bin/preziloader.swf", "presentid<?php echo $j;?>", "640", "480", "9.0.115", false, flashvarsv<?php echo $j;?>, paramsv<?php echo $j;?>, attributesv<?php echo $j;?>);
								// ]]>
								</script>

							</div>
							
						<?php } ?>	
			
						<?php 
						/**
						 * Muestra el nombre o titulo de la presentación
						 */
						if ($m["gal_nombre"] != "") { ?>
			
							<div class="slide-pie">
								Presentación: <span><?php echo $m["gal_nombre"];?></span>
							</div>
							
						<?php } ?>
						
						<?php 
						/**
						 * Muestra una descripción de la presentación (generalmente no utilizado)
						 */
						if ($m["gal_descripcion"] != "") { ?>

							<div class="slide-desc">
								<span><?php echo $m["gal_descripcion"];?></span>
							</div>
					
						<?php } ?>						
						
					</div><!--fin /.panel-->

			
				<?php } ?>
			
		</div><!--/.nota-slide-->
			
		</div><!--fin /.coda-slider-wrapper-->

	</section><!--fin /main-slide-->

	
	<?php if ($totalLinks > 1) { ?>
	
		<script type="text/javascript">
		$(document).ready(function() {
		
			$("#coda-slider-3").codaSlider({
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

<?php } ?>