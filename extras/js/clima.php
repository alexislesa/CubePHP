<?php
include ('../../includes/comun/conector.inc.php');

$climaPath = substr(__FILE__, 0, strlen(__FILE__) - strlen('/extras/json/clima.php'));

// Cargo información del clima de todas las localidades
$xmlData = array();
$xmlData["Aldea Protestante"] = "464562";
$xmlData["Aldea San Gregorio"] = "464565";
$xmlData["Aldea San José"] = "464566";
$xmlData["Basavilbaso"] = "466904";
$xmlData["Ceibas"] = "467164";
$xmlData["Chajarí"] = "466925";
$xmlData["Clara"] = "467583";
$xmlData["Colón"] = "466935";
$xmlData["Bovril"] = "332483";
$xmlData["Concepción del Uruguay"] = "332485";
$xmlData["Concordia"] = "332475";
$xmlData["Conscripto Bernardi"] = "467597";
$xmlData["Cuchilla Redonda"] = "465275";
$xmlData["Curtiembre"] = "465283";	
$xmlData["Diamante"] = "466950";
$xmlData["El Redomón"] = "332743";	
$xmlData["Enrique Carbó"] = "467631";
$xmlData["Federación"] = "465391";
$xmlData["Federal"] = "466959";
$xmlData["General Campos"] = "465442";
$xmlData["General Galarza"] = "467659";
$xmlData["Gualeguay"] = "466979";
$xmlData["Gualeguaychú"] = "332486";
$xmlData["Ibicuy"] = "330739";
$xmlData["Lucas González"] = "467756";
$xmlData["Maciá"] = "465739";
$xmlData["Nogoyá"] = "467015";
$xmlData["Paraná"] = "466869";
$xmlData["Piedras Blancas"] = "466116";
$xmlData["San Salvador"] = "332898";
$xmlData["Seguí"] = "466469";
$xmlData["Tala"] = "466411";
$xmlData["Uruguay"] = "20071076";
$xmlData["Victoria"] = "332523";
$xmlData["Villa Hernandarias"] = "467957";
$xmlData["Villa María Grande"] = "469006";
$xmlData["Villa Paranacito"] = "466771";
$xmlData["Villaguay"] = "332524";

$data = array();

foreach ($xmlData as $localidad => $dataClimaId) {

	$climaArr = climaYahoo(dirPath.'/includes/cache/clima-'.$dataClimaId.'.xml');

	$data[$dataClimaId] = array(
		'localidad' => utf8_encode($localidad),
		'codigo' => $dataClimaId,
		'temp' => $climaArr['temp']
	);
}

echo json_encode($data);
?>