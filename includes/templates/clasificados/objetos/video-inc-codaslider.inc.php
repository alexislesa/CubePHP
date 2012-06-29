<?php
/**
 * Muestra un reproductor de por cada video devuelto por el artículo en forma de pasador
 * 
 */
if (!empty($v['videos']) && is_array($v['videos']) && count($v['videos']) > 1) { 
	$totalVideos = count($v['videos']);
	?>

	<section class="main-videos coda-slider-wrapper">
	
		<?php
		/**
		 * Controles de anterior y siguiente
		 * además muestra la imagen que se esta visualizando y cuantas hay en total
		 */
		?>
		
		<?/*<span class="contador"><b>1</b> / <?php echo $total_imagenes;?></span>*/?>
		
		<div id="coda-nav-2" class="coda-nav">

			<ul>
				<?php for ($b=1; $b<=$totalVideos; $b++) { ?>
				<li class="tab<?php echo $b;?>"><a href="#<?php echo $b;?>"><b><?php echo $b;?></b> / <?php echo $totalVideos;?></a></li>
				<?php } ?>
			</ul>
			
		</div>
		
		<div class="nota-video coda-slider preload" id="coda-slider-2">

			<?php foreach ($v['videos'] as $j => $m) { ?>
			
				<div class="panel">
				
					<div id="video<?php echo $j;?>" class="video-in">
					
						<?php
						// Reproductor si el video proviene desde youTube
						if ($m['gal_tipo'] == 'ytube') { ?>
						
							<?php 
							/* Script si estas visualizando con dispositivos */
							if ($oMobile) { ?>
							
								<iframe width="640" height="315" src="<?php echo $m["extra"]["embed"];?>" frameborder="0" allowfullscreen></iframe>
							
							<?php } else { ?>

								<div id='videoplayer<?php echo $j;?>'>
							
									<b>Requiere Adobe Flash Player 9 o Superior <br><br></b>
									<a href='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&Lang=Spanish' target='_blank'>Instalar Gratis</a>

								</div>

								<script type="text/javascript">
								<!-- // --><![CDATA[
								var flashvarsv<?php echo $j;?> = {
									file:"<?php echo $m["url"]["o"];?>", 
									image: "<?php echo $m["extra"]["img_large"];?>",
									autostart:"false",
									bufferlength: 3,
									skin: "/flash/skin-video.swf"
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
								swfobject.embedSWF("/flash/player.swf", "videoplayer<?php echo $j;?>", "640", "315", "9.0.115", false, flashvarsv<?php echo $j;?>, paramsv<?php echo $j;?>, attributesv<?php echo $j;?>);
								// ]]>
								</script>

							<?php } ?>

						<?php } // End if YouTube ?>

					</div><!--/.video-in-->
					
					<?php 
					/* Muestra el nombre o titulo del video */
					if ($m['gal_nombre'] != '') { ?>
			
						<div class="video-pie">
							Video: <span><?php echo $m['gal_nombre'];?></span>
						</div>
							
					<?php } ?>
					
					<?php 
					/* Muestra una descripción del video (generalmente no utilizado) */
					if ($m['gal_descripcion'] != '') { ?>

						<div class="video-desc">
							<span><?php echo $m['gal_descripcion'];?></span>
						</div>
					
					<?php } ?>

				</div><!--/.panel-->

			<?php } ?>
		
		</div>

	</section><!--/.main-videos-->
	
	<script type="text/javascript">
	$(document).ready(function() {
	
		$("#coda-slider-2").codaSlider({
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