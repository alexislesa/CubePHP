<?php
/**
 * Muestra un reproductor por cada audio devuelto por el artículo
 */
if (!empty($v['audios']) && is_array($v['audios'])) {
	$totalAudios = count($v['audios']);
	?>

	<section class="article-block" id="audios">

		<?php
		// Esta información se muestra una sola vez si existe al menos un audio
		?>

		<h4>Listado de Audios de la nota</h4>
	
		<?php 
		// Repite el bloque completo por cada audio devuelto por el artículo
		foreach ($v['audios'] as $j => $m) {
			$pesoAudio = getFileSize($m['extra']['size']);
			?>

			<div class="nota-audio">

				<div class="audios">

					<div id='audioplayer<?php echo $j;?>'> 
					
						<span>
						Requiere <a href='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&Lang=Spanish' 
						target='_blank'>Adobe Flash Player 9</a> o Superior
						</span>
					
					</div>

				</div><!--fin /.audios-->
			
				<?php 
				// Nombre o titulo del audio
				if ($m['gal_nombre'] != '') { ?>

					<div class="video-pie">
					
						<strong>Audio: </strong> <?php echo $m['gal_nombre'];?>

					</div>
			
				<?php } ?>
				
				<?php 
				// Descripción del audio (generalmente no utilizado)
				if ($m['gal_descripcion'] != '') { ?>

					<div class="video-desc">
						<span><?php echo $m['gal_descripcion'];?></span>
					</div>

				<?php } ?>

				<?php
				// Información extra del archivo
				?>
				<div class="video-desc">
				
					<strong>Peso: </strong> <?php echo $pesoAudio;?> 
					<a href="/extras/descargas/descarga.php?id=<?php echo $m['gal_id'];?>" title="Descargar audio">Descargar audio</a>

				</div>				
				
				<?php
				// audio propio
				if ($m['gal_tipo'] == 'audio') { ?>			

					<script type="text/javascript">
					<!-- // --><![CDATA[
					var flashvarsa<?php echo $j;?> = {
						file:"<?php echo $m['url']['o'];?>", 
						autostart:"false",
						bufferlength:3,
						skin: "/flash/modieus.zip"
					}

					var paramsa<?php echo $j;?> = {
						allowfullscreen:"false", 
						allowscriptaccess:"always",
						wmode:"opaque"
					}

					var attributesa<?php echo $j;?> = {
						id:"idaudioplayer<?php echo $j;?>",  
						name:"idaudioplayer<?php echo $j;?>"
					}

					swfobject.embedSWF("/flash/player.swf", "audioplayer<?php echo $j;?>", "640", "31", "9.0.115", false, flashvarsa<?php echo $j;?>, paramsa<?php echo $j;?>, attributesa<?php echo $j;?>);
					// ]]>
					</script>
				
				<?php } ?>
				
				<?php 
				// Audio externo
				if ($m['gal_tipo'] == 'saudio') {
					
					// Audio desde SoundCloud
					if ($m['extra']['link_type'] == 'soundcloud') { ?>
					
						<script type="text/javascript">
							var flashvars<?php echo $j;?> = {
								enable_api: true, 
								object_id: "myPlayer",
								url: "<?php echo $m['extra']['link_id'];?>"
							};
							var params<?php echo $j;?> = {
								allowscriptaccess: "always"
							};
							var attributes<?php echo $j;?> = {
								id: "myPlayer",
								name: "myPlayer"
							};
							swfobject.embedSWF("http://player.soundcloud.com/player.swf", "audioplayer<?php echo $j;?>", "640", "80", "9.0.0",false, flashvars<?php echo $j;?>, params<?php echo $j;?>, attributes<?php echo $j;?>);
						</script>				
					
					<?php }
					
					// Desde GoEar
					if ($m['extra']['link_type'] == 'goear') { ?>
					
						<script type="text/javascript">
							var flashvars<?php echo $j;?> = {
								file: "<?php echo $m['extra']['link_id'];?>"
							};
							var params<?php echo $j;?> = {
								allowscriptaccess: "always"
							};
							var attributes<?php echo $j;?> = {
								id: "myPlayergogear",
								name: "myPlayergogear"
							};
							swfobject.embedSWF("http://www.goear.com/files/external.swf", "audioplayer<?php echo $j;?>", "640", "160", "9.0.0",false, flashvars<?php echo $j;?>, params<?php echo $j;?>, attributes<?php echo $j;?>);
						</script>				

					<?php }	// en if goear

				} // end if audio sindicado ?>

			</div><!--fin /.nota-audio-->


		<?php } ?>

		<?php
		// Este bloque información se muestra una sola vez si existe al menos un audio
		?>
		<div class="clear"></div>
		
	</section>
	
<?php } ?>