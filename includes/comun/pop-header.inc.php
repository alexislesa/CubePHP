<?php 
/* Modifico algunas cosas para el header */
$oDescArr = array("<br\>", "<br>", "\n", "\r", "&quot;", "'", "\"", "´", "`", "  ");

$webTitulo = isset($webTitulo) ? trim(strip_tags($webTitulo)) : '';
$webDescripcion = isset($webDescripcion) ? trim(strip_tags(str_replace($oDescArr, ' ', $webDescripcion))) : '';
$webKeywords = !empty($webKeywords) ? $webKeywords : str_replace(' ', ' ,', $webDescripcion);
$webImagen = !empty($webImagen) ? $webImagen : $urlRoot.'/images/logo.png';

/* Carga las clases del body */
$pagClassBody = implode(" ",$pagActualClass);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title><?php echo isset($web_titulo) ? strip_tags($web_titulo) : "";?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="robots" content="noindex">

<meta name="keywords" content="<?php echo $webKeywords; ?>" />
<meta name="description" content="<?php echo $webDescripcion;?>" />

<meta name="lang" content="es" />
<meta name="author" content="Advertis Web Factory" />
<meta name="organization" content="Advertis Web Factory" />
<meta name="copyright" content="Copyright (c) 2007-<?php echo date('Y');?> Advertis Web Factory" />
<meta name="locality" content="Entre Rios, Argentina" />

<link href="<?php echo $ss_config['web_site_css'];?>/styles/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $ss_config['web_site_css'];?>/styles/popup.css" rel="stylesheet" type="text/css" />

<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/thickbox.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.rsv.js"></script>

<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/funciones.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/selectivizr.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.tools.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.raty.js"></script>
</head>

<body class="<?php echo $pagClassBody;?>">