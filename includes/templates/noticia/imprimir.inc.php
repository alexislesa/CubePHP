<?php
/**
 * Genera la pantalla con el interior de un artículo para imprimir
 *
 * @changelog:
 */

 ?>
<div id="pop-imprimir">

	<div class="interior">

		<div class="nota">

			<?php 
			// Mensaje de Error
			if ($msjError) {
				include (dirTemplate.'/herramientas/mensaje-error.inc.php');
			}

			// Mensaje de Alerta
			if ($msjAlerta) {
				include (dirTemplate.'/herramientas/mensaje-alerta.inc.php');
			}

			// Solo si  hay artículos para mostrar
			if ($totalResultados) {

				$v = $dataToSkin[0];

				include (dirTemplate.'/'.$pathRelative.'/objetos/imprimir.inc.php');
			}
			?>

		</div><!--fin nota-->	

	</div><!--fin bloque noticia-->

</div><!--fin pop imprimir-->