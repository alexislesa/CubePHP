<?
include ("../../includes/comun/conector.inc.php");

foreach ($_POST as $k => $v) {
	$_POST[$k] = utf8_decode($v);
}

$fr = new checkFormulario();
$f = $fr->cleanDatos($_POST);

$fecha_nota =date("d")." de ".$mes_txt[date("n")]." de ".date("Y");
$fecha_hoy = time();

/**
 * Genero el Gravatar.
 *
include ($path_root."/includes/clases/gravatar.php");

$path_gravatar = "http://".$_SERVER["SERVER_NAME"];
$path_gravatar.= ($_SERVER["SERVER_PORT"] != 80) ? ":".$_SERVER["SERVER_PORT"] : "";

$default = $path_gravatar."/images/extras/avatar.gif";
$gravatar = new Gravatar($f["email"], $default);
$gravatar->size = 38;
$gravatar->rating = "G";
$img_avatar = $gravatar->getSrc();
*/

// Guardo los datos del usuario y cargo el comentario 
$sql = "INSERT INTO lectores (
	lector_id,
	lector_nombre,
	lector_apellido,
	lector_email,
	lector_activo,
	lector_confirmado,
	lector_avatar,
	lector_fregistro
	) VALUES (
	'',
	'{$f["nombre"]}',
	'',
	'{$f["email"]}',
	'1',
	'1',
	'{$img_avatar}',
	'{$fecha_hoy}'
	)";
$res = $db->query($sql);

$id = $db->last_insert_id();
$mensaje = $f["mensaje"];

// Por defecto el comentario esta activo
$sql = "INSERT INTO noticias_comentarios (
	comentario_noticia_id,
	comentario_lector_id,
	comentario_fecha_hora,
	comentario_texto,
	comentario_estado
	) VALUES (
	'{$f["id"]}',
	'{$id}',
	'{$fecha_hoy}',
	'{$mensaje}',
	'1'
	)";
$res = $db->query($sql);

$comentario = array();
$comentario["lector_nombre"] = $f["nombre"];
$comentario["comentario_texto"] = str_replace("\n","<br/>", $f["mensaje"]);
$comentario["comentario_fecha_hora"] = time();

// comentario individual
include($path_root."/includes/templates/noticias/objetos/comentario-individual.inc.php");
?>