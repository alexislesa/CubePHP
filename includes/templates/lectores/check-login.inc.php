<?php
/**
 * Consulta si el usuario se encuentra logeado, 
 * sino lo env�a a la p�gina corespondiente.
 */
$usr = new Usuarios();
$usr->db = $db;
$usr->passEncript = 'md5';
if (empty($_SESSION['web_site']) 
	|| !$usr->login($_SESSION['web_site']['user'], $_SESSION['web_site']['pw'])) {
	
	Header('Location: /lectores/login.php');
	exit();
}
?>