<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$ret = '';

/* Consulta si el nombre de usuario existe */
$nombre = (!empty($_GET['n']) && is_string($_GET['n'])) ? $_GET['n'] : '';
$nombre = cleanInjection($nombre);

if (is_string($nombre) && strlen($nombre) > 3) {

	$sql = "SELECT count(lector_id) as total from lectores WHERE lector_usuario = '".$nombre."'";
	if ($res = $db->query($sql)) {
		if ($db->num_rows($res)) {
			$rs = $db->next($res);
			$ret = ($rs['total']) ? '1' : '';
		}
	}
}

echo $ret;
?>