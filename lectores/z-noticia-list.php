<?
/* Conector */
include ("../includes/comun/conector.inc.php");

/* Web titulo */
$web_titulo = "Mis Noticias - ".$web_titulo;
$web_descripcion = "";

$pagina_actual = "lector-online";
$pagina_actual_2 = "noticialist";

$breds["Mi cuenta"] = "/lectores";
$breds["Mis Noticias"] = "";

/** 
 * Login de usuario
 */
$usr = new usuarios();
$usr->db = $db;
$usr->pws_format = "md5";
if (empty($_SESSION["web_site"]) || !$usr->login($_SESSION["web_site"]["user"], $_SESSION["web_site"]["pw"])) {
	Header("Location: /lectores/login.php");
	exit();
}

/**
 * Información para eliminar una nota
 */
if (!empty($_GET["act"]) && $_GET["act"] == "del") {

	/**
	 * Verifico que la nota sea del usuario que la envío y que este pendiente de aprobar
	 * Sino, envio al listado de notas
	 */
	$nota_id = !empty($_GET["id"]) ? (is_numeric($_GET["id"]) ? $_GET["id"] : 0) : 0;
	$tipo_id = 7;
	$seccion_id = 0;
	
	$gd = New notaVer($nota_id);
	$gd->db = $db;
	$gd->nota_tipo = $nota_tipo;
	$gd->nota_seccion = $nota_seccion;
	$gd->nota_prefix = "lectores_noticias";
	$gd->nota_force_view = true;
	$dtToSkin = $gd->process();

	$dtToSkin = $gd->process();
	$error_msj = ($gd->error_found) ? $gd->error_msj : false;

	if (!$gd->error_found 
		&& !$gd->nota_not_found
		&& ($dtToSkin[0]["noticia_user_id"] == $usr->campos["lector_id"] && $dtToSkin[0]["noticia_estado"] == 0)) {

		$sql = "UPDATE lectores_noticias SET noticia_estado = 3 WHERE noticia_id = '{$nota_id}'";
		$db->query($sql);
	}
}

/**
 * Información básica para el listado de mis noticias 
 */
$tipo_id = 7;
$seccion_id = 0;

$pag_cantidad = 20;
$pag_paginador = 10;

$total_resultados =0;
$total_paginas=0;

$pag_actual = !empty($_GET["p"]) ? (is_numeric($_GET["p"]) ? $_GET["p"] : 0) : 0;
$pag_desplazamiento = $pag_cantidad * $pag_actual;

$href = "";
foreach ($_GET as $k => $v) {
	$href.= (($k != "p")) ? "&{$k}={$v}" : "";
}
$href = "?".substr($href,1);

$gd = New notaListado($tipo_id, $seccion_id);
$gd->db = $db;
$gd->nota_cantidad = $pag_cantidad;
$gd->nota_desplazamiento = $pag_desplazamiento;
$gd->nota_return_objetos = true;
$gd->nota_objetos_datos = $adjuntos_files;
$gd->nota_orden = "noticia_id Desc";

$gd->nota_force_view = true;	// Activas e inactivas
$gd->nota_prefix = "lectores_noticias";
$gd->nota_sqlextra = "noticia_user_id = '{$usr->campos["lector_id"]}' AND noticia_estado IN (0,1,2)"; // pendientes, aprobadas y rechazadas
$dataToSkin = $gd->process();

if ($gd->error_found) {

	$error_msj = $gd->error_msj;
	
} else {

	$total_resultados = $gd->total_resultados;
	$total_paginas = $gd->total_paginas;

	if ($total_resultados) {
		$web_titulo = $dataToSkin[0]["noticia_titulo"]." - ".$web_titulo;
		$web_descripcion = $dataToSkin[0]["noticia_bajada"];
	}
	
	$pg = New paginador();
	$pg->pag_url = $href;
	$pg->pag_actual = $pag_actual;
	$pg->pag_totales = $total_paginas;
	$pg->pag_resultados = $total_resultados;
	$pg->pag_cantidad = $pag_cantidad;
	$pg->pag_paginador = $pag_paginador;
	$pagToSkin = $pg->process();
}
?>

<? include (dirPath."/includes/comun/header.inc.php");?>

<? include (dirPath."/includes/comun/top.inc.php");?>

<?php include (dirPath."/includes/comun/menu.inc.php");?>

<? 
$pathRootCommon = dirPath."/includes/comun/";
$pathRootTemplate = dirTemplate."/";
$path_relativo = "lectores";
$fileIncludeCuerpo = $pathRootTemplate."lectores/noticia-list.inc.php";
$fileIncludeBarra = $pathRootTemplate.$path_relativo."/barra.inc.php";
include ($pathRootTemplate."herramientas/base.inc.php");

?>

<? include (dirPath."/includes/comun/pie.inc.php");?>

<? include (dirPath."/includes/comun/bottom.inc.php");?>