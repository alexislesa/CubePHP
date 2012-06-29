<?php

/* Cargo las funciones utilizadas en el cron */
include("funciones.inc.php");

$xml_data_list = array(

	/* Tenis */
	"http://www.df06.com.ar/xml/deportes.tenis.cincinnati.fixture.xml" => "../cache/deportes.tenis.cincinnati.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.copadavis.fixture.xml" => "../cache/deportes.tenis.copadavis.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.gsaustralia.fixture.xml" => "../cache/deportes.tenis.gsaustralia.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.indianwells.fixture.xml" => "../cache/deportes.tenis.indianwells.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.madrid.fixture.xml" => "../cache/deportes.tenis.madrid.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.masters.fixture.xml" => "../cache/deportes.tenis.masters.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.miami.fixture.xml" => "../cache/deportes.tenis.miami.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.montecarlo.fixture.xml" => "../cache/deportes.tenis.montecarlo.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.montreal.fixture.xml" => "../cache/deportes.tenis.montreal.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.paris.fixture.xml" => "../cache/deportes.tenis.paris.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.rolandgarros.fixture.xml" => "../cache/deportes.tenis.rolandgarros.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.roma.fixture.xml" => "../cache/deportes.tenis.roma.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.toronto.fixture.xml" => "../cache/deportes.tenis.toronto.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.usopen.fixture.xml" => "../cache/deportes.tenis.usopen.fixture.xml",
	"http://www.df06.com.ar/xml/deportes.tenis.wimbledon.fixture.xml" => "../cache/deportes.tenis.wimbledon.fixture.xml"
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