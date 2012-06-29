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
 * Muestra un bloque de galer�a de fotos solo si el art�culo devuelve m�s de una imagen
 * 
 * Esta galer�a muestra la primer imagen en el interior del art�culo y la opci�n de ver el resto en un lightbox ampliado.
 *
 * Array de datos que es posible devolver:
 *	- gal_file_ext		La extensi�n del archivo
 *	- gal_file			Nombre del archivo
 *	- adjunto_descripcion	Pie de foto
 *	- gal_fecha			Fecha de publicaci�n en el sitio
 *	- extra["size"]		Peso en Bytes
 *	- extra["width"]	Ancho de la imagen Original
 *	- extra["height"]	Alto de la imagen Original
 *	- url["o"]			Url de la imagen original
 *	- url["t"]			Url de la imagen thumbnails m�s chica
 *   
 *	- $total_imagenes	Devuelve la cantidad de imagenes del art�culo
 *
 * @changelog:
 */
if (!empty($v["imagen"]) && is_array($v["imagen"]) && count($v["imagen"]) > 1)  { 
	$total_imagenes = count($v["imagen"]);
	$v_img = $v["imagen"][1];	// Muestra la primer imagen
	?>

	<div class="main-fotos">

		<h4>Galer�a de Fotos</h4>
		
		<div class="nota-foto">
		
			<div class="items-foto">
			
				<div class="cont-foto">
				
					<figure>			

						<?php
						/**
						 * Utilizar esta opci�n si abre un lightbox con la galer�a
						 */
						?>
						<a href="/extras/notas/galeria.php?id=<?php echo $v["noticia_id"]?>&fid=1&KeepThis=true&TB_iframe=true&height=528&width=496" class="thickbox">
							<img src="<?php echo $v_img["url"]["o"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" width="250" height="250" />
						</a>
						
						<?php
						/**
						 * Utilizar esta opci�n si abre un PopUp con la galer�a
						 */
						?>
						<a href="javascript:ventana('/extras/notas/galeria.php?id=<?php echo $v["noticia_id"]?>&fid=1',gal', 496,528,'yes');" >
							<img src="<?php echo $v_img["url"]["o"];?>" alt="<?php echo $v_img["adjunto_descripcion"];?>" width="250" height="250" />
						</a>						

						<span title="Ampliar" class="multimedia">Ampliar 1/<?php echo $total_imagenes;?></span>

						<? if ($v_img['adjunto_creditos'] != '') {?>
						
							<div class="foto-credit">
								<span>Cr�dito:</span> <?php echo $v_img['adjunto_creditos'];?>
							</div><!-- credit -->

						<? } ?>
						
						<?php 
						/* Pie de foto */
						if ($v_img["adjunto_descripcion"] != "") {?>
							
							<figcaption>
							
								<?php echo $v_img["adjunto_descripcion"];?>
							
							</figcaption><!--fin pie-->
							
						<?php } ?>
						
					</figure>
				
				</div><!--fin cont foto-->
				
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

<?php } ?>