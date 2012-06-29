<?php 
/* Modifico algunas cosas para el header */
$webTitulo = isset($webTitulo) ? trim(strip_tags($webTitulo)) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es-ar">

<head>
<title><?php echo $webTitulo;?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="robots" content="noindex">

<meta name="lang" content="es" />
<meta name="author" content="Advertis Web Factory" />
<meta name="organization" content="Advertis Web Factory" />
<meta name="copyright" content="Copyright (c) 2007-<?php echo date('Y');?> Advertis Web Factory" />
<meta name="locality" content="Entre Rios, Argentina" />

<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/error.css" />
	
<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>