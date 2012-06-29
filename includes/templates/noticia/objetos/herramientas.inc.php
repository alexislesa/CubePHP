<?php
/**
 * Herramientas del artículo
 *
 * Tiene los accesos de enviar/imprimir y manejo de fuentes
 *
 */
 
/* Tamaño máximo del texto */
$font_size_max = 15;

/* Tamaño mínimo del texto */
$font_size_min = 12;

/* Tamaño actual del texto */
$font_size_actual = 12;
?>

<div class="font-size">

	<div class="txt">

		<span id="mas" class="ico">Aumentar tamaño de texto</span>
		<span id="menos" class="ico">Disminuír tamaño de texto</span>
		<span class="left">Manejo de texto</span>

	</div>
	<div class="clear"></div>
	
</div>


<script type="text/javascript">
<?php 
/**
 * Manejo de tamaño de fuente
 */
if (!empty($_COOKIE["TEXT_SIZE"])) { 
	$font_size_actual = $_COOKIE["TEXT_SIZE"];
}

if (!empty($_COOKIE["TEXT_SIZE"])) { ?>
$(document).ready(function() {
	$(".noticia-texto").css("font-size", <?php echo $font_size_actual;?>);
});
<?php } ?>

font_size_actual = <?php echo $font_size_actual;?>;

$("#mas").click(function() {
	font_size_actual = font_size_actual+1
	if (font_size_actual > <?php echo $font_size_max;?>) {
		font_size_actual = <?php echo $font_size_max;?>;
	}
	$(".noticia-texto").css("font-size", font_size_actual);
	$.cookie("TEXT_SIZE",font_size_actual, { path: "/", expires: 10000 });
	return false;
});

$("#menos").click(function() {
	font_size_actual = font_size_actual-1;
	if (font_size_actual < <?php echo $font_size_min;?>) {
		font_size_actual = <?php echo $font_size_min;?>;
	}
	$(".noticia-texto").css("font-size", font_size_actual);
	$.cookie("TEXT_SIZE",font_size_actual, { path: "/", expires: 10000 });
	return false;
});
</script>