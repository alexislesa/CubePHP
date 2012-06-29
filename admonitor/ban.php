<?php
// Incluyo el Conector
include ('../includes/comun/conector.inc.php');

if (empty($_GET['z']) || $_GET['z'] == '') {
	exit();
}

$advZone = trim($_GET['z']);
$advRep = !empty($_GET['rep']) ? $_GET['rep'] : false;

// Modifico configuración para mostrar banners
$adv->bannerExterno = false;
$adv->sitioExterno = true;

if ($banner=$adv->process($advZone, $advRep)) {
	echo $banner;
} 
?>