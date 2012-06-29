<?php
/**
 * Muestra un bloque de documentos adjuntos
 */
if (!empty($v['documentos']) && is_array($v['documentos'])) { 
	$totalDocumentos = count($v['documentos']);
	?>

	<section class="article-block" id="docs">
	
		<?php
		// Información que se muestra una sola vez si existe al menos un documento
		?>
		
		<h4>Documentos</h4>
		
		<ul>
		<?php 
		// Bloque completo por cada documento devuelto por el artículo
		foreach ($v['documentos'] as $j => $m) {	
			$pesoDoc = getFileSize($m['extra']['size']);
			?>

			<li>
				
				<?php if ($m['gal_descripcion'] != '') { ?>
					
					<div class="doc-desc"><?php echo $m['gal_descripcion'];?></div>
				
				<?php } ?>
				
				<span class="ico <?php echo $m['gal_file_ext'];?>"></span><!--cambia class segun tipo de documento-->
				
				<a href="/extras/descargas/descarga.php?id=<?php echo $m['gal_id'];?>" title="<?php echo $m['gal_nombre'];?>"><?php echo $m['gal_nombre'];?></a> 
				<span class="peso">(<?php echo $pesoDoc;?>)</span>
				
			</li>
		
		<?php } ?>
		
		</ul>
		
		<?php
		// Información que se muestra una sola vez si existe al menos un documento
		?>
		
	</section><!-- fin /docs-->

<?php } ?>