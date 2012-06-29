<?php
/* Conector */
include ("../../includes/comun/conector.inc.php");

/* Página activa del menú */
$pagina_actual = "noticias";
$pagina_actual_2 = "";

/* Breadcrums del sitio */
$breds["Noticias"] = "/noticias";

/* Información para recuperar el artículo */
$nota_tipo = 0;
$nota_seccion = 0;
$nota_id = !empty($_GET["id"]) ? (is_numeric($_GET["id"]) ? $_GET["id"] : 0) : 0;
$nota_foto_id = !empty($_GET["fid"]) ? (is_numeric($_GET["fid"]) ? $_GET["fid"] : 1) : 1;

/* Información de recuperación de los artículos */
$gd = New notaVer($nota_id);
$gd->db = $db;
$gd->nota_tipo = $nota_tipo;
$gd->nota_seccion = $nota_seccion;
$gd->nota_objetos = "imagen";
$gd->nota_objetos_datos = $adjuntos_files;
$gd->path_obj_embebed = $path_root."/includes/templates/noticia/objetos";

/* Datos de Cache */
$gd->cache = true;
$gd->cache_name = "nota-id-".$nota_id;
$gd->cache_expire = 900;

$dataToSkin = $gd->process();
$error_msj = ($gd->error_found) ? $gd->error_msj : false;
$total_resultados = !$gd->nota_not_found;

if (!$gd->error_found) {

	if ($total_resultados) {
	
		$web_titulo = $dataToSkin[0]["noticia_titulo"]." - ".$web_titulo;
		$web_descripcion = $dataToSkin[0]["noticia_bajada"];
		
		/* Cargo la estadística de la sección */
		$st_uid = !empty($_SESSION["web_site"]["id"]) ? $_SESSION["web_site"]["id"] : 0;
		$st_ext = "";
		$st_tipo = 1;
		$st = new stats();
		$st->db = $db;
		$st->process($st_tipo,$st_ext,$st_uid);		
	}
}

include ($path_root.'/includes/comun/pop-header.inc.php');

include ($path_root.'/includes/templates/noticia/galeria.inc.php');

include ($path_root.'/includes/comun/pop-bottom.inc.php'); 
?>