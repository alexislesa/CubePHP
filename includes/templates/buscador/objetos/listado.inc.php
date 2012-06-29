<?php
/**
 * Muestra un listado de artículos devueltos
 */
if (!$msjError && !empty($dataToSkin)) { ?>
	
	<div class="listado-article">
	
		<?php
		$parImpar = 0;
		foreach ($dataToSkin as $k => $v) {
			
			$fecha = '%l% %j% de %F% de %y%'; 
			$notaFecha = formatDate($fecha, $v["noticia_fecha_modificacion"]);

			// Al primer bloque le agrega la clase "primera"
			$notaFirstClass = (!$k) ? 'primera' : '';
			
			// Clase si el art tiene imagen
			$notaImgClass = !empty($v['imagen']) ? 'confoto' : '';

			// Par / impar
			$parImpar = !$parImpar;
			$notaImparClass = ($parImpar) ? 'impar' : '';
			
			// nombre de la sección como clase
			$notaSeccionClass = 'seccion-'.$v['noticia_seccion_id'];
			
			// Formato de link
			$v['seccion_rss_page'] = '/noticias/nota.php';	// solo para demo, luego quitar
			$notaUrl = $v['seccion_rss_page'].'?id='.$v['noticia_id'];	// Link tipo: nota.php?id=xx
			// $notaUrl = $v["seccion_rss_index"].$v["noticia_page_url"].".htm"; // Link tipo: nota-demo-de-hoy.htm
			?>

			<article class="item-post <?php echo $notaSeccionClass.' '.$notaFirstClass.' '.$notaImgClass.' '.$notaImparClass;?>">

				<header>
					<h3><a href="<?php echo $notaUrl;?>" title="<?php echo $v['noticia_titulo'];?>" ><?php echo $v['noticia_titulo'];?></a></h3>
				</header>

				<div class="item-texto">

					<?php
					/* En caso de que tenga imagen */
					if (!empty($v['imagen'][1]['url'])) { ?>

						<figure>
							<a class="a-img" href="<?php echo $notaUrl;?>" title="<?php echo $v['noticia_titulo'];?>" >
								<img src="<?php echo $v['imagen'][1]['url']['o'];?>" width="120" height="90"/>
							</a>
						</figure>

					<?php } ?>

					<div class="item-bajada">
						<a href="<?php echo $notaUrl;?>" title="">
							<span class="item-fecha"><?php echo $notaFecha;?> | </span><?php echo $v['noticia_bajada'];?>
						</a>
					</div>

				</div><!--fin item texto-->

				<div class="clear"></div>

			</article><!--/.item-post--> 

		<?php } ?>

	</div><!--fin /.listado-post-->

<?php } ?>