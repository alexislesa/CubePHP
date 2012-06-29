<?php
/* Fuerza al navegador a descargarse el objeto */
include ('../../includes/comun/conector.inc.php');

$id = !empty($_GET['id']) ? (is_numeric($_GET['id']) ? $_GET['id'] : 0) : 0;
if (!$id) {
	die('<b>ERROR!</b> No es posible descargar este archivo');
}

$adj = new Adjuntos();
$adj->db = $db;
$adj->itemId = $id;
$adj->datos = $adjuntos_files;

if (!$dataToFile = $adj->process()) {

	die ('<b>ERROR</b> No es posible devolver el archivo solicitado');

} else {

	if (!$fl = file_get_contents($dataToFile[0]['url']['o'])) {

		die('El archivo solicitado no existe.');

	} else {

		$extArr = explode('.', $dataToFile[0]['url']['o']);
		$ext = $extArr[count($extArr)-1];
		$nombre = urlFriendly($dataToFile[0]["gal_file"]).'.'.$ext;
	
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"{$nombre}\"\n");
		header("Content-Length: ".strlen($fl));
		/*
		$fp=fopen($dataToFile[0]["url"]["o"], "r");
		fpassthru($fp);
		*/
		echo $fl;
	}
}
?>