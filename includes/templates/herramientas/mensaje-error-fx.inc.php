<?php
/**
 * Genera un efecto visual para el mensaje de error
 * Carga el mensaje de error utilizado para el sitio y le agrega un efecto visual extra.
 * El sistema deja visible el bloque de error [x] segundos y luego oculta el bloque automaticamente
 */
include (dirTemplate.'/herramientas/mensaje-error.inc.php');
?>

<script type="text/javascript">
	$(".msj-error").delay(4000).slideUp("fast");
</script>