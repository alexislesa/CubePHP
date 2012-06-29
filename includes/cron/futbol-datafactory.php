<?php
/**
 * Descarga la información sobre fixture, tabla de posiciones y goleadores para fútbol de Primera A y Nacional B provisto por datafactory.
 */

/* Cargo las funciones utilizadas en el cron */
include("funciones.inc.php");
 
$xml_data_list = array(

	/* Primera A */
	"http://www.df06.com.ar/xml/deportes.futbol.primeraa.fixture.xml" => "../cache/df-primeraa-fixture.inc.php",
	"http://www.df06.com.ar/xml/deportes.futbol.primeraa.posiciones.xml" => "../cache/df-primeraa-posiciones.inc.php",
	"http://www.df06.com.ar/xml/deportes.futbol.primeraa.goleadores.xml" => "../cache/df-primeraa-goleadores.inc.php",
	
	/* Nacional B */
	"http://www.df06.com.ar/xml/deportes.futbol.nacionalb.posiciones.xml" => "../cache/df-nacionalb-posiciones.inc.php",
	"http://www.df06.com.ar/xml/deportes.futbol.nacionalb.fixture.xml" => "../cache/df-nacionalb-fixture.inc.php",
	"http://www.df06.com.ar/xml/deportes.futbol.nacionalb.goleadores.xml" => "../cache/df-nacionalb-goleadores.inc.php"
);

foreach ($xml_data_list as $file_src => $file_dst) {

	$xml_data = file_get_contents($file_src);

	$arrayData = xml2array($xml_data,1, "attribute");

	//Guardo la información serializada en un archivo local.
	$contenido = serialize($arrayData);

	if (!$archivo = fopen($file_dst, "w")) {
		echo "No se abrir el archivo ($file_dst)";
	}

	if (fwrite($archivo, $contenido) === FALSE) {
		echo "No se puede escribir al archivo ($file_dst)";
	}
	fclose($archivo);
}

echo "Actualizado:".date("d.m.Y G:i:s")."hs";
?>