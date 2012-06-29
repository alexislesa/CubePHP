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
 * Muestra el interior del artículo con todos los componentes accesorios
 *
 * @changelog:
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
$notaFecha = formatDate($fecha, $v['noticia_fecha_modificacion']);

/* Tiempo estimado de lectura */
$lectura = timeToRead($v['noticia_texto']);
$tiempoLectura = $lectura['hora'] ? ($lectura['hora']."hs ") : "";
$tiempoLectura.= $lectura['min'] ? ($lectura['min']."min. ") : "";
$tiempoLectura.= $lectura['seg']."seg "; 
?>

<div class="post-interior">
	
	<article>
		
		<header>
		
			<?php if ($v['noticia_volanta'] != "") { ?>
				<span class="volanta"><?php echo $v['noticia_volanta'];?></span>
			<?php } ?>
		
			<span class="fecha-nota">Fecha: <?php echo $notaFecha;?></span>
		
			<h2 id="nota-title"><?php echo  $v['noticia_titulo'];?>
			
				<?php
				/**
				 * Facebook Like 
				 *
				<span class="likeit">
					<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $advThisUrl;?>&amp;layout=button_count&amp;show-faces=false&amp;width=100&amp;action=like&amp;colorscheme=light&amp;locale=es_LA" scrolling="no" 
			frameborder="0" allowTransparency="true" style="vertical-align: middle; border:none; overflow:hidden; width:100px; height:21px"></iframe>
				</span>
				*/ ?>

				<?php 
				/**
				 * Pinterest
				 * configuración del botón: http://pinterest.com/about/goodies/
				 
				<a href="http://pinterest.com/pin/create/button/?url=www.lanacion.com&media=http%3A%2F%2Fimagen.com&description=Titulo%20del%20pinteressssss" class="pin-it-button" count-layout="horizontal">Pin It</a>
				<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
				*/ ?>

				<?php
				/**
				 * Google +1 
				 * Configuración del botón: http://www.google.com/webmasters/+1/button/index.html

				<span>
					<script type="text/javascript" src="http://apis.google.com/js/plusone.js">{lang: 'es-419'}</script>
					<g:plusone size="medium" count="false"></g:plusone>		
				</span>
				*/ ?>

				<?php
				/**
				 * Twitter BT Seguime
				 * Configuración del botón: http://twitter.com/about/resources/followbutton

				<span>
					<a href="http://twitter.com/advertisweb" class="twitter-follow-button" data-show-count="false" data-lang="es">Seguir a @advertisweb</a>
					<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
				</span>
				*/ ?>
			</h2>
			
		</header>
	
		<section class="bajada"><?php echo $v['noticia_bajada'];?></section>
	
		<div class="herramientas">
	
			<?php include (dirTemplate."/{$pathRelative}/objetos/advthis.inc.php"); ?>
			
			<?php include (dirTemplate."/{$pathRelative}/objetos/herramientas.inc.php"); ?>
	
			<div>Tiempo estimado de lectura: <?php echo $tiempoLectura;?></div>
			
		</div>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/galeria.inc.php"); ?>
		
		<?php include (dirTemplate."/{$pathRelative}/objetos/foto.inc.php"); ?>	
		
		<section class="texto"><?php echo $v['noticia_texto'];?></section>
	
		<?php if ($v['noticia_texto_complementario'] != "") { ?>
		
			<section class="comp">
				<h4>Texto complementario:</h4>
				<?php echo  $v['noticia_texto_complementario'];?>
			</section>
	
		<?php } ?>
		
		
		<?php if ($v['noticia_fuente'] != '' && ($v['noticia_autor'] != '')) { ?>
		
			<footer>
				
				
				<?php if ($v['noticia_fuente'] != '') { ?>
				
					<span class="fuente"><b>Fuente:</b> <?php echo $v['noticia_fuente'];?></span>
				
				<?php } ?>
				
				<?php if ($v['noticia_autor'] != '') { ?>
				
					<span class="fuente"><b>Autor:</b> <?php echo $v['noticia_autor'];?></span>
				
				<?php } ?>
			</footer>
		<?php } ?>
		
	</article>

	<?php include (dirTemplate."/{$pathRelative}/objetos/votar.inc.php"); ?>

	<?php include (dirTemplate."/{$pathRelative}/objetos/stream.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/video.inc.php"); ?>

	<?php include (dirTemplate."/{$pathRelative}/objetos/audio.inc.php"); ?>

	<?php include (dirTemplate."/{$pathRelative}/objetos/presentacion.inc.php"); ?>	
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/gmap.inc.php"); ?>

	<?php // include (dirTemplate."/{$pathRelative}/objetos/storyfi.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/relacionadas.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/documentos.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/links.inc.php"); ?>

	<?php // include (dirTemplate."/{$pathRelative}/objetos/encuesta.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/etiquetas.inc.php"); ?>
	
	<?php include (dirTemplate."/{$pathRelative}/objetos/comentar.inc.php"); ?>
	
</div><!--fin /.post-interior-->