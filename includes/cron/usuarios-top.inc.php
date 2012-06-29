<?php
include("../comun/conector.inc.php");

$user_twitter = "alexislesa";	// username de TW
$user_fb = "133136910055640";	// Id del user de FB

/**
 * Revisa la cantidad de usuarios que tiene en TW, FB y registrado y cambia el valor
 * Genera un archivo .php serializado
 */

/* Seguidores en Twitter */
$seguidores = file_get_contents("http://api.twitter.com/1/users/show/{$user_twitter}.json");
$datos = json_decode($seguidores, true);
$tw = $datos["followers_count"];


/* Seguidores en Facebook */
$seguidores = file_get_contents("https://graph.facebook.com/{$user_fb}");
$datos = json_decode($seguidores, true);
$fb = $datos["likes"];

/* Suscriptos */
$sql = "SELECT count(lector_id) as total FROM lectores";
$res = $db->query($sql);
$rs = $db->next($res);
$lec = $rs["total"];

$info = array(
	"twitter" => $tw,
	"facebook" => $fb,
	"lectores" => $lec
);

$dt = json_encode($info);

$nombre_archivo = "../cache/suscriptos.inc.php";
$gestor = fopen($nombre_archivo, "w");
fwrite($gestor,$dt);
fclose($gestor);

/* Para Log */
echo "Actualizado:".date("d.m.Y G:i:s");
echo "<br/>json:".$dt;
echo "<br/>info:".print_r($info, true);
?>