<?php 
/**
 * Genera un bloque para nueva búsqueda o ordenamiento de resultados del buscador
 * Permite reordenar los bloques como se requiera
 */
$oHref = $_GET;
unset($oHref['p']);
$oHrefBase = http_build_query($oHref);
$oHrefBase = $_SERVER['REQUEST_URI'].'?'.$oHrefBase;
?>

<form name="buscadorpage" id="buscadorpage" action="?" method="get">

	<?php 
	// Carga los items no facetables
	if (isset($qInput) && is_array($qInput) && count($qInput)) {
		foreach ($qInput as $ki => $vi) { ?>

			<input type="hidden" name="<?php echo $vi[0];?>" value="<?php echo $vi[1];?>" />

		<? }
	} ?>

	<?php
	/**
	 * Bloques de filtro de búsqueda
	 */
		// Bloque de texto
		include (dirTemplate."/{$pathRelative}/objetos/bloque-texto.inc.php");

		// Filtro de búsqueda por fecha (desde.. hasta ...)
		include (dirTemplate."/{$pathRelative}/objetos/bloque-fecha-select.inc.php");
		
		// Filtro de secciones por las cuales buscar
		include (dirTemplate."/{$pathRelative}/objetos/bloque-secciones.inc.php");

		// Bloque de orden de los resultados obtenidos
		if ($totalResultados) { 
			include (dirTemplate."/{$pathRelative}/objetos/bloque-orden.inc.php");		
		}

	/**
	 * Bloque de resultados facetados de la búsqueda
	 */
	if ($totalResultados) {
	
		// Resultados facetados por fango de fechas (hoy, ayer, etc)
		include (dirTemplate."/{$pathRelative}/objetos/bloque-facetado-fechas-rango.inc.php");
		
		// Resultados facetados por períodos de fechas (Enero 2010, Febrero 2010, etc)
		include (dirTemplate."/{$pathRelative}/objetos/bloque-facetado-fechas-periodo.inc.php");	
		
		// Resultados facetados por sección
		include (dirTemplate."/{$pathRelative}/objetos/bloque-facetado-seccion.inc.php");
	}

	/**
	 * Bloque de mensajes de la búsqueda
	 */
	if (!$msjAlerta && !$msjError) {
		// Texto indicativo de que se encontraron al menos un resultado
		include (dirTemplate."/{$pathRelative}/objetos/bloque-resultado-simple.inc.php");
		
		// Texto indicativo de que NO existen resultados para la búsqueda
		include (dirTemplate."/{$pathRelative}/objetos/bloque-no-resultados.inc.php");
	}
	?>

</form>

<script type="text/javascript">
$("#buscadorpage").submit(function() {
	<?php /* Comentar o quitar esta comprobación si no existe el bloque de búsqueda */ ?>
	if ($(this).find("input[name='q']").val().length < 4) {
		alert("Debe ingresar un texto de al menos 4 caracteres");
		return false;
	}
	
	<?php 
	/* incluye esta comprobación si esta activo el bloque de filtro por secciones */
	if (!empty($inc_bloque_secciones)) { ?>
		if ($("div.secciones_nombres input:checked").length == 0) {
			alert("Debe seleccionar al menos una sección donde buscar");
			return false;
		}
	<?php } ?>
});

$("#sortby .radio").click(function() {
	$("#buscadorpage").submit();
});

/*
$(document).ready(function(){
	$(".radio").dgStyle();
	$(".checkbox").dgStyle();
}); */
</script>