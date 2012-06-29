<?php
/**
 * Carga información sobre el clima provisto por Yahoo
 */

/* Cargo las funciones utilizadas en el cron */
include("funciones.inc.php");

$xml_data_list = array();
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=464562&u=c"] = "../cache/clima-464562.xml";	// Aldea Protestantes
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=464565&u=c"] = "../cache/clima-464565.xml";	// Aldea san gregorio
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=464566&u=c"] = "../cache/clima-464566.xml";	// Aldea San José
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466904&u=c"] = "../cache/clima-466904.xml";	// Basavilbaso
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467164&u=c"] = "../cache/clima-467164.xml";	// Ceibas
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466925&u=c"] = "../cache/clima-466925.xml";	// Chajarí
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467583&u=c"] = "../cache/clima-467583.xml";	// Clara
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466935&u=c"] = "../cache/clima-466935.xml";	// Colón
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332483&u=c"] = "../cache/clima-332483.xml";	// Bovril
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332485&u=c"] = "../cache/clima-332485.xml";	// C del U
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332475&u=c"] = "../cache/clima-332475.xml";	// Concordia
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467597&u=c"] = "../cache/clima-467597.xml";	// Consc. Bernardi
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=465275&u=c"] = "../cache/clima-465275.xml";	// Cuchilla Redonda
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=465283&u=c"] = "../cache/clima-465283.xml";	// Curtiembre
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466950&u=c"] = "../cache/clima-466950.xml";	// Diamante
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332743&u=c"] = "../cache/clima-332743.xml";	// El Redomón
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467631&u=c"] = "../cache/clima-467631.xml";	// Enrique Carbó
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=465391&u=c"] = "../cache/clima-465391.xml";	// Federación
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466959&u=c"] = "../cache/clima-466959.xml";	// Federal
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=465442&u=c"] = "../cache/clima-465442.xml";	// General Campos
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467659&u=c"] = "../cache/clima-467659.xml";	// General Galarza
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466979&u=c"] = "../cache/clima-466979.xml";	// Gualeguay
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332486&u=c"] = "../cache/clima-332486.xml";	// Gualeguaychú
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=330739&u=c"] = "../cache/clima-330739.xml";	// Ibicuy
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467756&u=c"] = "../cache/clima-467756.xml";	// Lucas González
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=465739&u=c"] = "../cache/clima-465739.xml";	// Maciá
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467015&u=c"] = "../cache/clima-467015.xml";	// Nogoyá
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466869&u=c"] = "../cache/clima-466869.xml";	// Paraná
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466116&u=c"] = "../cache/clima-466116.xml";	// Piedras Blancas
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332898&u=c"] = "../cache/clima-332898.xml";	// San Salvador
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466469&u=c"] = "../cache/clima-466469.xml";	// Seguí
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466411&u=c"] = "../cache/clima-466411.xml";	// Tala
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=20071076&u=c"] = "../cache/clima-20071076.xml";	// Uruguay
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332523&u=c"] = "../cache/clima-332523.xml";	// Victoria
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=467957&u=c"] = "../cache/clima-467957.xml";	// Villa Hernandarias
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=469006&u=c"] = "../cache/clima-469006.xml";	// Villa María Grande
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=466771&u=c"] = "../cache/clima-466771.xml";	// Villa Paranacito
$xml_data_list["http://weather.yahooapis.com/forecastrss?w=332524&u=c"] = "../cache/clima-332524.xml";	// Villaguay


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