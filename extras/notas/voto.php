<?
/* Conector */
include ("../../includes/comun/conector.inc.php");

$nota_tipo = 0;
$nota_seccion = 0;

/* Id del artículo a mostrar */
$nota_id = !empty($_GET["id"]) ? (is_numeric($_GET["id"]) ? $_GET["id"] : 0) : 0;
$voto_id = !empty($_GET["v"]) ? (is_numeric($_GET["v"]) ? $_GET["v"] : 0) : 0;

if ($voto_id) {
	$gd = New notaVer($nota_id);
	$gd->db = $db;
	$gd->nota_tipo = $nota_tipo;
	$gd->nota_stats = "voto-".$voto_id;
	$dataToSkin = $gd->process();
	$error_msj = ($gd->error_found) ? $gd->error_msj : false;
	$total_resultados = !$gd->nota_not_found;
} else {
	$error_msj = "No recibí datos para la votación";
}
if (!$error_msj) {
	echo "OK";
}
?>