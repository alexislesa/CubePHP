<?php
/* Conector */
include ('../../includes/comun/conector.inc.php');

$img = new Captcha();
$img->length = 4;
$img->size = 30;
$img->width = 298;
$img->height = 40;
$img->backgroundColor = '#FFFFFF';
$img->color = '#000000';
$img->font = dirPath.'/includes/widgets/font/georgia.ttf';
$img->process();
?>