<?php
/**
 * Levanta la encuesta activa
 */
$gd = New Encuestas();
$gd->db = $db;
$gd->itemEstado = 1; // solo activas
$gd->cantidad = 1;
if ($encToSkin = $gd->process()) {
	include (dirTemplate.'/encuestas/home.inc.php');
}
?>