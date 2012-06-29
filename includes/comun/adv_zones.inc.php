<?php
// Zonas de espacios publicitarios
$advZone1 = '';
$advZone2 = '';
$advZone3 = '';
$advZone4 = '';
$advZone5 = '';

$advZone20 = '';

// Manejador de publicidad
$adv = new Admonitor();
$adv->db = $db;
$adv->path = dirPath.'/admonitor/';
$adv->bannerExterno = true;	// En caso de utilizar banners de formato externo
?>