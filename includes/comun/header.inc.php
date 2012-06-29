<?php 
/* Modifico algunas cosas para el header */
$oDescArr = array("<br\>", "<br>", "\n", "\r", "&quot;", "'", "\"", "´", "`", "  ");

$webTitulo = isset($webTitulo) ? trim(strip_tags($webTitulo)) : '';
$webTitulo = accentToUTF8($webTitulo);

$webDescripcion = isset($webDescripcion) ? trim(strip_tags(str_replace($oDescArr, ' ', $webDescripcion))) : '';
$webKeywords = !empty($webKeywords) ? $webKeywords : str_replace(' ', ' ,', $webDescripcion);
$webImagen = !empty($webImagen) ? $webImagen : $urlRoot.'/images/logo.png';

$webUrl = xssClean($advThisUrl);

/* Carga las clases del body */
$pagClassBody = implode(' ',$pagActualClass);
?>
<!DOCTYPE html>
<html lang="es-ar" dir="ltr">
<head>
<title><?php echo $webTitulo;?></title>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<meta http-equiv="Content-Language" content="es" />
<meta http-equiv="imagetoolbar" content="no" />

<meta name="keywords" content="<?php echo $webKeywords; ?>" />
<meta name="description" content="<?php echo $webDescripcion;?>" />

<meta name="lang" content="es" />
<meta name="author" content="Advertis Web Factory" />
<meta name="organization" content="Advertis Web Factory" />
<meta name="copyright" content="Copyright (c) 2007-<?php echo date('Y');?> Advertis Web Factory" />
<meta name="locality" content="Entre Rios, Argentina" />

<?php
/* Admnistración de comentarios desde FB
 *
 * Meta utilizado en caso de utilizar plataforma de comentarios de FB en el sitio
 *
<meta property="fb:app_id" content="{YOUR_APPLICATION_ID}"/>
 */
?>
 
<meta property="og:title" content="<?php echo $webTitulo;?>" />
<meta property="og:description" content="<?php echo $webDescripcion;?>" />
<meta property="og:type" content="blog<?php /* {lista en http://ogp.me/#types}*/ ?>" />
<meta property="og:url" content="<?php echo $webUrl;?>" />
<meta property="og:image" content="<?php echo $webImagen;?>" />
<meta property="og:locality" content="Entre Ríos, Argentina" />

<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/layout.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/styles.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/colorbox.css" />

<?php 
/* Hoja de estilos específica si estoy visualizando con iPad, iPod, iPhone */
if ($oMobile) { ?>

	<meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=0.5" />
	<link rel="stylesheet" type="text/css" href="<?php echo $ss_config['web_site_css'];?>/styles/ipad.css" />
	
<?php } else { 
	/* Hoja de estilo para el resto de los navegadores */ ?>

<?php } ?>

<link rel="shortcut icon" href="/favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS" href="/rss/actualidad.xml" />

<!-- ie9 -->
<?php 
/**
 * Para JumList en ie9. 
 * Más info en: http://www.codigobit.info/2010/09/anclar-tu-sitio-la-barra-de-tareas-de.html
 *
 * application-name: 				Nombre del acceso directo. Si lo omites, se mostrará el título del documento que dio origen al atajo.
 * msapplication-starturl:			Dirección de la página de inicio. Si esta vacio, se abrirá la que usó el usuario para crear el vínculo.
 * msapplication-tooltip:			Texto que saldrá al pasar el ratón sobre el ícono.
 * msapplication-window:			Define el ancho y alto inicial del navegador.
 * msapplication-navbutton-color:	Color de los botones Adelante y Atrás, en formato hexadecimal.
 * msapplication-task:				name=nombre-de-la-tarea;action-uri=dirección-de-la-página;icon-uri=dirección-del-ícono
 *
 * Ejemplo de uso:
 * 
 * application-name: 				advertis.com.ar
 * msapplication-starturl:			http://www.advertis.com.ar
 * msapplication-tooltip:			Advertis - Web Factory
 * msapplication-window:			width=1024;height=768
 * msapplication-navbutton-color:	#0EA8DC
 * msapplication-task:				name=Ultimas noticias;action-uri=http://www.advertis.com.ar/index.php;icon-uri=http://www.advertis.com.ar/item1.ico
 */
?>
<meta name="application-name" content="<?php echo $ss_config['site_name'];?>" />
<meta name="msapplication-starturl" content="<?php echo $webUrl;?>" />
<meta name="msapplication-tooltip" content="<?php echo $webTitulo;?>" />
<meta name="msapplication-window" content="width=1024;height=768" />
<meta name="msapplication-navbutton-color" content="" />
<meta name="msapplication-task" content="" />
<!-- /ie9 -->

<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.js"></script>
<? /* <script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/thickbox.js"></script> */ ?>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/funciones.js"></script>

<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.rsv.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.tools.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.raty.js"></script>

<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.coda-slider.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/jquery.easing.js"></script>
<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/si.files.js"></script>

<!--[if IE]>
	<script type="text/javascript" src="<?php echo $ss_config['web_site_script'];?>/js/selectivizr.js"></script>
<![endif]-->

<!--[if IE 6]>
	<style type="text/css">
		.pngfix { behavior: url("/styles/csshover.htc"); }
	</style>
<![endif]-->

<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>
<body class="<?php echo $pagClassBody;?>">