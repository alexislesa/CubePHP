<?php
/**
 * Muestra el interior del artículo con todos los componentes accesorios
 */
 
/**
 * *********************************
 * Formatos de Fecha aceptados
 * *********************************
 *
 * Tipos predefinidos:
 *	%j%		1...31
 *	%d%		01...31
 *	
 *	%D%		Lun...Dom
 *	%l%		Lunes...Domingo
 *	
 *	%F%		Enero...Diciembre
 *	%M%		Ene...Dic
 *	
 *	%m%		01...12
 *	%n%		1...12
 *
 *	%Y%		99...15
 *	%y%		1999..2015
 *	
 *	%g%		0...12
 *	%G%		0...23
 *	
 *	%h%		00...12
 *	%H%		00...23
 *	
 *	%i%		00...59 (minutos)
 *	
 *	%s%		00...59	(segundos)
 *
 * Ejemplo de uso: "%l% %j% de %F% de %y%" -> "Lunes 20 de Diciembre de 2010"
 */
$fecha = "%l% %j% de %F% de %y%"; 
$itemFecha = formatDate($fecha, $v['clasificado_fecha_inicio']);

$itemFechaFin = formatDate($fecha, $v['clasificado_fecha_final']);

$itemMoneda = '';
$itemPrecio = 'Consultar';
if ($v['clasificado_precio_final'] > 0) {
	$itemMoneda = $v['moneda']['moneda_simbolo'];
	$itemPrecio = $v['clasificado_precio_final'];
	
	list($itemPrecioNumero, $itemPrecioDecimal) = explode('.', $itemPrecio);
}

$itemLocalidad = $v['ubicacion']['localidad']['ubicacion_nombre'];
$itemDepartamento = $v['ubicacion']['departamento']['ubicacion_nombre'];
$itemProvincia = $v['ubicacion']['provincia']['ubicacion_nombre'];

$itemCantidad = $v['clasificado_cantidad_actual'];

$itemImgClass = true;
$itemImgPrimera = "http://base.dev/images/temp/nota.gif";

$itemVendido = $v['clasificado_vendido'];
?>

<div class="post-interior">
	
	<article>
		
		<header>
		
			<span class="fecha-nota">Publicado: <?php echo $itemFecha;?></span>
		
			<h2 id="nota-title"><?php echo  $v['clasificado_titulo'];?>
				<span class="likeit">
					<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $advThisUrl;?>&amp;layout=button_count&amp;show-faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;locale=es_LA" scrolling="no" 
			frameborder="0" allowTransparency="true" style="vertical-align: middle; border:none; overflow:hidden; width:100px; height:21px"></iframe>
				</span>
				
				<?php
				/**
				 * Google +1 
				 * Configuración del botón: http://www.google.com/webmasters/+1/button/index.html
				
				<span>
					<script type="text/javascript" src="http://apis.google.com/js/plusone.js">{lang: 'es-419'}</script>
					<g:plusone size="medium" count="false"></g:plusone>		
				</span>
				
				<?php
				/**
				 * Twitter BT Seguime
				 * Configuración del botón: http://twitter.com/about/resources/followbutton
				 *
				
				<span>
					<a href="http://twitter.com/advertisweb" class="twitter-follow-button" data-show-count="false" data-lang="es">Seguir a @advertisweb</a>
					<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
				</span> */
				?>
			</h2>
			
		</header>
	
		<div class="herramientas">
	
			<?php include (dirTemplate."/{$pathRelative}/objetos/advthis.inc.php"); ?>

		</div>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/galeria.inc.php"); ?>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/foto.inc.php"); ?>
		
		<section class="texto"><?php echo $v['clasificado_texto'];?></section>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/video.inc.php"); ?>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/gmap.inc.php"); ?>

		<div class="info-cl">
			<span>Usuario: <?php echo $v['lector_usuario'];?></span>
			<span>Categoría Principal: 
				<?php echo $v['categorias'][0]['categoria_nombre'];?> 
				<?php echo !empty($v['categorias'][1]) ? '/'.$v['categorias'][1]['categoria_nombre'] : '';?>
			</span>
			<span>Precio: <?php echo $itemMoneda;?> <?php echo $itemPrecio;?></span> 
			<span>Ubicación: <?php echo $itemLocalidad;?> (<?php echo $itemDepartamento;?>) </span>
			<span>Visitas: <?php echo $v['clasificado_visitas'];?> </span>
			<span>Finaliza: <?php echo $itemFechaFin;?></span>
			<span>Cantidad: <?php echo $itemCantidad;?></span>

		</div>
	
	</article>

</div><!--fin /.post-interior-->