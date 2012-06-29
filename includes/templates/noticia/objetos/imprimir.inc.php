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
 * Muestra un bloque con el interior para imprimir
 * 
 * Array de datos que es posible devolver:
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
?>

<div class="fecha">
	
	<span class="fecha"><?php echo $notaFecha;?></span>

	<a href="javascript:window.print();" title="Imprimir" class="print">Imprimir</a>
	
</div>

<h2><?php echo $v['noticia_titulo'];?></h2>
	
<div class="nota-bajada">
	
	<?php echo $v['noticia_bajada'];?>
	
</div>

<?php
/**
 * Si tubiera imagen, muestro la primera
 */
if (!empty($v['imagen'])) {
	$m = $v['imagen'][1];
	?>

	<div id="alone" class="nota-foto">

		<div class="foto">
			
			<img src="<?php echo $m['url']['o'];?>" alt="<?php echo $m['adjunto_descripcion'];?>" title="<?php echo $m['adjunto_descripcion'];?>" width="200" height="200"/>
		
        </div>

		<?php if ($m['adjunto_descripcion'] != '') { ?>
			
			<div class="foto-pie">
				<span><?php echo $m['adjunto_descripcion'];?></span>
			</div><!-- pie -->
			
		<?php } ?>
		
	</div>

<?php } ?>

<div class="nota-texto">
	
	<?php echo $v['noticia_texto'];?>

</div>