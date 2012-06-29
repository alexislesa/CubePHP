<?php
/* Conector */
include ('../includes/comun/conector.inc.php');

$pathRelative = 'clasificados';
$incBody = dirTemplate.'/'.$pathRelative.'/interior.inc.php';
$incBar = dirTemplate.'/'.$pathRelative.'/barra-interior.inc.php';

// Body class / Menú active
$pagActualClass[] = 'clasificados';
$pagActualClass[] = 'clasificados-interior';
// $pagActualClass[] = 'no-sidebars';
// $pagActualClass[] = 'two-sidebars';
// $pagActualClass[] = 'sidebar-left';
$pagActualClass[] = 'sidebar-right';

// Breadcrums
$breds['Clasificados'] = '/clasificados';

$itemId = (!empty($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
$itemTitulo = !empty($_GET['t']) ? cleanInjection($_GET['t']) : false;
$itemForce = (!empty($_GET['mid']) && $_GET['mid'] == substr(md5($_GET['id']),0,3)) ? true : false;	// Utilizado en vista preliminar

$gd = New Clasificados();
$gd->db = $db;
$gd->itemId = $itemId;
$gd->itemTitulo = $itemTitulo;
$gd->cantidad = 1;
$gd->statsAdd('view');
$dataToSkin = $gd->process();

if ($gd->errorInfo) {

	$msjError = $gd->errorInfo;

} else {

	$totalResultados = $gd->totalResultados;
	$totalPaginas = $gd->totalPaginas;

	if ($totalResultados) {

		$webTitulo = $dataToSkin[0]['clasificado_titulo'].' - '.$webTitulo;
		$webDescripcion = $dataToSkin[0]['clasificado_texto'];

		/*if (!empty($dataToSkin[0]['imagen'])) {
			$webImagen = $dataToSkin[0]['imagen'][1]['url']['o'];
		}*/

	} else {

		$msjAlerta = "El artículo que desea ver no existe";
	}
}


/** DEMO ---- LUEGO ELIMIAR */
/*
$dataToSkin[0]['imagen'][1] = array (
	'adjunto_id' => '2562',
	'adjunto_tipo' => 'imagen',
	'adjunto_noticia_tipo' => '1',
	'adjunto_noticia_id' => '14273',
	'adjunto_multimedia_id' => '123',
	'adjunto_descripcion' => 'texto largo para probar como sería una imagen con el máximo texto de pie de imagen permitido',
	'adjunto_orden' => '1',
	'adjunto_tipo_id' => '2',
	'gal_id' => '123',
	'gal_file' => '1315835436.jpg',
	'gal_nombre' => 'URRIBARRI6.jpg',
	'gal_descripcion' => '',
	'gal_comentario' => '',
	'gal_user_id' => '1',
	'gal_fecha' => '1314894390',
	'gal_tipo' => 'image',
	'gal_galeria' => '1',
	'gal_extra' => '{"size":219133,"width":600,"height":350,"video_rate":"","duracion":"","audio_rate":"","adjunto":""}',
	'gal_campo_1' => '',
	'gal_campo_2' => '',
	'gal_campo_3' => '',
	'gal_campo_4' => '',
	'gal_campo_5' => '',
	'gal_campo_6' => '',
	'gal_campo_7' => '',
	'gal_campo_8' => '',
	'gal_campo_9' => '',
	'gal_campo_10' => '',
	'extra' => 
	array (
	  'size' => 219133,
	  'width' => 600,
	  'height' => 350,
	  'video_rate' => '',
	  'duracion' => '',
	  'audio_rate' => '',
	  'adjunto' => '',
	),
	'gal_file_ext' => 'jpg',
	'url' => 
	array (
	  'o' => 'http://www.clasionce.com/galeria/usuarios/1327/1327969585_30473_1_l.jpg',
	  'l' => 'http://www.clasionce.com/galeria/usuarios/1327/1327969585_30473_1_l.jpg',
	  'm' => 'http://www.clasionce.com/galeria/usuarios/1327/1327969585_30473_1_l.jpg',
	  't' => 'http://www.clasionce.com/galeria/usuarios/1327/1327969585_30473_1_t.jpg',
	),
);
	
$dataToSkin[0]['videos'][1] = array (	
	'adjunto_id' => '2574',
	'adjunto_tipo' => 'videos',
	'adjunto_noticia_tipo' => '1',
	'adjunto_noticia_id' => '14273',
	'adjunto_multimedia_id' => '3767',
	'adjunto_descripcion' => '',
	'adjunto_orden' => '1',
	'adjunto_tipo_id' => '2',
	'gal_id' => '3767',
	'gal_file' => 'http://www.youtube.com/watch?v=N6XUTBInNQU',
	'gal_nombre' => 'Atilio Benedetti en Oro Verde',
	'gal_descripcion' => 'El candidato a gobernador de Entre Ríos por el Frente Progresista Cívico y Social, Atilio Benedetti, participó del acto de lanzamiento de la candidatura a intendente de Carlos Schmith en Oro Verde.',
	'gal_comentario' => '',
	'gal_user_id' => '2',
	'gal_fecha' => '1316524010',
	'gal_tipo' => 'ytube',
	'gal_galeria' => '3',
	'gal_extra' => '{"size":"","width":"","height":"","video_rate":"","duracion":"","audio_rate":"","link_id":"N6XUTBInNQU","link_type":"youtube","img_thumbnail":"http://i.ytimg.com/vi/N6XUTBInNQU/default.jpg","img_large":"http://i.ytimg.com/vi/N6XUTBInNQU/hqdefault.jpg","img_width":398,"img_heigth":224,"duration":"380","link_embed":"http://www.youtube.com/v/N6XUTBInNQU"}',
	'gal_campo_1' => '',
	'gal_campo_2' => '',
	'gal_campo_3' => '',
	'gal_campo_4' => '',
	'gal_campo_5' => '',
	'gal_campo_6' => '',
	'gal_campo_7' => '',
	'gal_campo_8' => '',
	'gal_campo_9' => '',
	'gal_campo_10' => '',
	'extra' => 
	array (
	  'size' => '',
	  'width' => '',
	  'height' => '',
	  'video_rate' => '',
	  'duracion' => '',
	  'audio_rate' => '',
	  'link_id' => 'N6XUTBInNQU',
	  'link_type' => 'youtube',
	  'img_thumbnail' => 'http://i.ytimg.com/vi/N6XUTBInNQU/default.jpg',
	  'img_large' => 'http://i4.ytimg.com/vi/N6XUTBInNQU/hqdefault.jpg',
	  'img_width' => 398,
	  'img_heigth' => 224,
	  'duration' => '380',
	  'link_embed' => 'http://www.youtube.com/v/N6XUTBInNQU',
	  'img_small' => 'http://i.ytimg.com/vi/N6XUTBInNQU/default.jpg',
	  'iframe' => 'http://www.youtube.com/embed/N6XUTBInNQU',
	  'embed' => 'http://www.youtube.com/v/N6XUTBInNQU',
	  'url' => 'http://www.youtube.com/watch?v=N6XUTBInNQU',
	),
	'gal_file_ext' => 'com/watch?v=N6XUTBInNQU',
	'url' => 
	array (
	  'o' => 'http://www.youtube.com/watch?v=N6XUTBInNQU',
	)
);

$dataToSkin[0]['gmap'][1] = array (
	'mapa_noticia_id' => '14273',
	'mapa_tipo_id' => '1',
	'mapa_x' => '-60.5292',
	'mapa_y' => '-31.722',
	'mapa_zoom' => '17',
	'mapa_tipo' => 'normal',
	'mapa_orden' => '1',
	'mapa_extra' => '{"texto":"Esquina donde estaba la oficina hasta octubre de 2011"}',
);
*/

include (dirPath.'/includes/comun/header.inc.php');

include (dirPath.'/includes/comun/top.inc.php');

include (dirPath.'/includes/comun/menu.inc.php');

include (dirTemplate.'/herramientas/base.inc.php');

include (dirPath.'/includes/comun/pie.inc.php');

include (dirPath.'/includes/comun/bottom.inc.php');
?>