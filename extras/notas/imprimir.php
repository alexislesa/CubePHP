<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$pathRelative = 'noticia';
$incBody = dirTemplate.'/noticia/imprimir.inc.php';

// Body class / Menú active
$pagActualClass[] = "noticias";
$pagActualClass[] = "noticias-interior";

// Información para recuperar artículos
$itemTipo = 0;
$itemSeccion = 0;
$itemId = (!empty($_GET["id"]) && is_numeric($_GET["id"])) ? $_GET["id"] : 0;
$itemTitulo = !empty($_GET["t"]) ? cleanInjection($_GET["t"]) : false;
$itemForce = (!empty($_GET["mid"]) && $_GET["mid"] == substr(md5($_GET["id"]),0,3)) ? true : false;	// Utilizado en vista preliminar

if ($itemId || $itemTitulo) {
	$gd = New Notas();
	$gd->db = $db;
	$gd->itemTipo = $itemTipo;
	$gd->itemSeccion = $itemSeccion;
	$gd->itemId = $itemId;
	$gd->itemTitulo = $itemTitulo;
	$gd->embed = dirTemplate.'/productos/objetos/';
	$gd->cantidad = 1;
	$gd->statsAdd('print');

		$ogd = New Adjuntos();
		$ogd->db = $db;
		$ogd->datos = $adjuntos_files;
		$gd->loadObj("adjunto",$ogd);

	$dataToSkin = $gd->process();

	if ($gd->errorInfo) {

		$msjError = $gd->errorInfo;

	} else {

		$totalResultados = $gd->totalResultados;
		$totalPaginas = $gd->totalPaginas;

		if ($totalResultados) {

			$webTitulo = $dataToSkin[0]['noticia_titulo'].' - '.$webTitulo;
			$webDescripcion = $dataToSkin[0]['noticia_bajada'];

			if (!empty($dataToSkin[0]['imagen'])) {
				$webImagen = $dataToSkin[0]['imagen'][1]['url']['o'];
			}

		} else {

			$msjAlerta = "El artículo que desea ver no existe";
		}
	}

} else {
	$msjAlerta = "El artículo que desea ver no existe";
	$totalResultados = 0;
}

include (dirPath.'/includes/comun/imprimir-header.inc.php');

include (dirTemplate.'/herramientas/base-pop.inc.php');

include (dirPath.'/includes/comun/pop-bottom.inc.php');
?>