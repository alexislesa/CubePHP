<?php
/**
 * Muestra un bloque de nube de temas
 */
$tagCantidad = -1; // Cantidad de tags a mostrar, -1: todas
$tagTipo = 0; // Tipo de la nota
$tagSeccion = 0; // Sección de la nota
$tagRangoFecha = 0; // 0: sin rango de fechas, 1,2,3..x: cant de días anteriores
$tagPesoMax = 10; // Genera el peso del 1 al 10

$eTags = New NubeTags($tagCantidad, $tagTipo, $tagSeccion);
$eTags->itemFechaRango = $tagRangoFecha;
$eTags->db = $db;
$eTags->itemMaxPeso = $tagPesoMax;
$tagToSkin = $eTags->process();

/**
 * ******************************************************
 * Modo Test, luego eliminar, 
 * para probar que todas las posibles etiquetas 
 * y tamaños funcionen correctamente
*/

/*
$tagToSkin = array();

$sql = "SELECT ubicacion_nombre FROM ubicaciones WHERE ubicacion_pertenece = '547' ORDER BY ubicacion_nombre";
$tempRes = $db->query($sql);
for ($i=0; $i<$db->num_rows($tempRes); $i++) {
	$rs = $db->next($tempRes);
	$cant = rand(1,30);
	$peso = rand(1,10);
	$tagToSkin[] = array("tag"=>$rs["ubicacion_nombre"], "cantidad"=>$cant, "peso"=>$peso);
}
*/
/**
 * ******************************************************
 */

if ($tagToSkin) { ?>

	<section id="temas" class="block">

		<h4>temas</h4>

		<div class="inner nubetags2">

			<?php foreach ($tagToSkin as $kTag => $vTag) { ?>

				<a href="/extras/notas/tags.php?tag=<?php echo $vTag['tag'];?>" class="tags<?php echo $vTag['peso'];?>" title="Etiqueta: <?php echo $vTag['tag'];?> (<?php echo $vTag['cantidad'];?> apariciones)"><?php echo $vTag['tag'];?></a>

			<?php } ?>

		</div>

	</section><!--fin /temas-->

<?php } ?>