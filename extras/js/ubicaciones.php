<?php
include ("../../includes/comun/conector.inc.php");

/* 
 * Devuelve un array con la ubicación solicitada.
 * 
 * $id;		// ubicación padre. (pertenece)
 * $campo;	// Campo del formulario a ingresarle los datos. (opcional).
 * $selIndex	// Si campo no es vacio, este campo indica el campo seleccionado.
 * $typ;		// html= incrusta los tahs de scritp al principio y final.
 * 
 */
$id = (!empty($_GET["id"])) ? (is_numeric($_GET["id"]) ? $_GET["id"] : 0) : 0;

$sql = "SELECT ubicacion_id as id, ubicacion_nombre as nombre 
	FROM ubicaciones 
	WHERE ubicacion_pertenece = '{$id}' 
	ORDER BY ubicacion_nombre";
$result = $db->query($sql);

$datos = array();
for ($i=0;$i<$db->num_rows($result);$i++) {
	$rs = $db->next($result);
	$rs["nombre"] = utf8_encode($rs["nombre"]);
	$datos[] = $rs;
}
echo json_encode($datos);
?>