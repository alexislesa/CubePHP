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
 * Muestra un listado de artículos devueltos
 *
 * @changelog:
 */
if (!$msjError && !empty($dataToSkin)) { ?>

<pre><?=print_r($dataToSkin, true);?></pre>	
	<div class="listado-article">
	
		<?php
		$parImpar = 0;
		foreach ($dataToSkin as $k => $v) {
			
			$fecha = '%l% %j% de %F% de %y%'; 
			$notaFecha = formatDate($fecha, $v['gal_fecha']);

			// Al primer bloque le agrega la clase 'primera'
			$notaFirstClass = (!$k) ? 'primera' : '';
			
			// Par / impar
			$parImpar = !$parImpar;
			$notaImparClass = ($parImpar) ? 'impar' : '';
			
			// nombre de la sección como clase
			$notaTipoClass = 'tipo-'.$v['gal_tipo'];
			$notaTipoClass = (!empty($v['extra']['link_type'])) 
						? 'tipo-'.$v['extra']['link_type'] 
						: $notaTipoClass;
			
			// Formato de link
			$notaUrl = '/videos/item.php?id='.$v['gal_id'];	// Link tipo: nota.php?id=xx
			
			// Consulta de imagenes
			$notaImg = '';
			$notaImgClass = '';
			if (!empty($v['extra']['adj']['url'])) {
				$notaImg = $v['extra']['adj']['url']['o'];
				$notaImgClass = 'confoto';
				
			} elseif (!empty($v['extra']['img_large'])) {
			
				$notaImg = $v['extra']['img_large'];
				$notaImgClass = 'confoto';
			}
			?>

			<article class="item-post <?php echo $notaTipoClass.' '.$notaFirstClass.' '.$notaImgClass.' '.$notaImparClass;?>">

				<header>
					<h3><a href="<?php echo $notaUrl;?>" title="<?php echo $v['gal_nombre'];?>" ><?php echo $v['gal_nombre'];?></a></h3>
				</header>

				<div class="item-texto">

					<?php
					/* En caso de que tenga imagen */
					if ($notaImg != '') { ?>

						<figure>
							<a class="a-img" href="<?php echo $notaUrl;?>" title="<?php echo $v['gal_nombre'];?>" >
								<img src="<?php echo $notaImg;?>" width="120" height="90"/>
							</a>
						</figure>

					<?php } ?>

					<div class="item-bajada">
						<a href="<?php echo $notaUrl;?>" title="">
							<span class="item-fecha"><?php echo $notaFecha;?> | </span><?php echo $v['gal_descripcion'];?>
						</a>
					</div>

				</div><!--fin item texto-->

				<div class="clear"></div>

			</article><!--/.item-post--> 

		<?php } ?>

	</div><!--fin /.listado-post-->

<?php } ?>