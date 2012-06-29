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
 * Muestra un listado de artículos ingresados por el lector
 * 
 * El listado muestra mensaje de error si existiera, como por ejemplo: Que la sección no contiene ningun tipo de resultados 
 * o que se intento generar un comando de hacking (injection, XSS, etc).
 *
 * Permite copiarlo a cualquier sección que se requiera solo modificando el parametro: $path_relativo;
 *
 * @changelog:
 */
$path_relativo = "lectores";
?>

<?php include ($path_root."/includes/templates/lectores/objetos/profile.inc.php"); ?>
			
<section>
	
	<h2 id="title">Mis Noticias</h2>

	<figure><img src="<?php echo $usr->campos["lector_avatar"];?>" width="40" height="40" alt="" title="" /></figure>
			
	<?php 
	/* Mensaje de Error */
		if ($error_msj) {
			include ($path_root."/includes/templates/herramientas/mensaje-error.inc.php");
		}
	/* No hay artículos del lector */
		if (!$total_resultados) { 
			$error_msj = "No se encontraron notas para mostrar";
			include ($path_root."/includes/templates/herramientas/mensaje-error.inc.php");
	}?>
	
	<?php include ($path_root."/includes/templates/{$path_relativo}/objetos/listado.inc.php"); ?>
	<?php include ($path_root."/includes/templates/herramientas/paginador-1.inc.php"); ?>
		
</section>