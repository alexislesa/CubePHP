<?php
/* Conector */
include ("../includes/comun/conector.inc.php");

$url = !empty($_POST["url"]) ? trim($_POST["url"]) : "";
$info = !empty($_POST["info"]) ? trim($_POST["info"]) : "";
$nav = !empty($_POST["nav"]) ? trim($_POST["nav"]) : "";
$ahora = time();

if ($info != "") {

	$fileName = substr(__FILE__, 0, strlen(__FILE__) - strlen('debug/log.php'));
	$fileName.= 'includes/tmp/log-data.inc.php';

	$data = array();
	
	if (file_exists($fileName)) {
		include($fileName);
	}

	$data[$url][$ahora] = array("navegador" => $nav, "error" => $info);
	
	$contenido = "<?php\n";
	$contenido.= "/**\n";
	$contenido.= " * Error debug \n";
	$contenido.= " */\n\n";
	$contenido.= '$data = '. var_export($data, true) .';';
	$contenido.= "\n\n ?>";
	
	file_put_contents($fileName, $contenido);
}
?>