<?php
/**
 * Muestra un listado de artículos devueltos
 */
if (!$msjError && !empty($dataToSkin)) { ?>
	
	<div class="listado-cl">
	
		<?php
		$parImpar = 0;
		foreach ($dataToSkin as $k => $v) {
			
			$fecha = '%l% %j% de %F% de %y%'; 
			$notaFecha = formatDate($fecha, $v['clasificado_fecha_inicio']);

			// Al primer bloque le agrega la clase "primera"
			$notaFirstClass = (!$k) ? 'primera' : '';
			
			// Clase si el art tiene imagen
			$notaImgClass = $v['clasificado_imagen'] ? 'confoto' : '';
			$notaImg = unserialize($v['clasificado_fotos']);

			// Par / impar
			$parImpar = !$parImpar;
			$notaImparClass = ($parImpar) ? 'impar' : '';
			
			// nombre de la sección como clase
			$notaSeccionClass = "seccion-".$v['clasificado_categoria'];
			
			// Formato de link
			$notaUrl = "/clasificados/item.php?id=".$v['clasificado_id'];	// Link tipo: nota.php?id=xx
			// $notaUrl = $v["seccion_rss_index"].$v["noticia_page_url"].".htm"; // Link tipo: nota-demo-de-hoy.htm
			
			
			$notaImgClass = true;
			$notaImgPrimera = "http://base.dev/images/temp/nota.gif";
			
			$notaMoneda = '';
			$notaPrecio = 'Consultar';
			if ($v['clasificado_precio_final'] > 0) {
				$notaMoneda = $v['moneda']['moneda_simbolo'];
				$notaPrecio = $v['clasificado_precio_final'];
			}
			
			$ubicacionLocalidad = $v['ubicacion']['localidad']['ubicacion_nombre'];
			$ubicacionDepartamento = $v['ubicacion']['departamento']['ubicacion_nombre'];
			$ubicacionProvincia = $v['ubicacion']['provincia']['ubicacion_nombre'];
			?>

			<article class="item-cl <?php echo $notaSeccionClass.' '.$notaFirstClass.' '.$notaImgClass.' '.$notaImparClass;?>">

				<header>
					<h3><a href="<?php echo $notaUrl;?>" title="<?php echo $v['clasificado_titulo'];?>" ><?php echo $v['clasificado_titulo'];?></a></h3>
				</header>

				<div class="item-texto">

					<?php
					/* En caso de que tenga imagen */
					if ($notaImgClass) { ?>

						<figure>
							<a class="a-img" href="<?php echo $notaUrl;?>" title="<?php echo $v['clasificado_titulo'];?>" >
								<img src="<?php echo $notaImgPrimera;?>" width="181" />
							</a>
						</figure>

					<?php } ?>

					<div class="item-bajada">
						<?php // echo cutString($v['clasificado_texto'],200,'...');?>
						<span><?php echo $notaFecha;?></span>
						<span>User: <?php echo $v['lector_usuario'];?></span>
						<span>Cat: <?php echo $v['categorias'][0]['categoria_nombre'];?></span>
						<span>Precio: <?php echo $notaMoneda;?> <?php echo $notaPrecio;?></span>
						<span><?php echo $ubicacionLocalidad;?> (<?php echo $ubicacionDepartamento;?>)</span>
						<span>Visitas: <?php echo $v['clasificado_visitas'];?></span>
					</div>

				</div><!--fin item texto-->

				<div class="clear"></div>

			</article><!--/.item-post--> 

		<?php } ?>

	</div><!--fin /.listado-post-->
	
	<script type="text/javascript">
		$('<div class="clear"></div>').insertAfter('.item-cl:nth-child(3n+3)');
	</script>
<?php } ?>