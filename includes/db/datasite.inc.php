<?php
/**
 Información sobre el cliente
 */
$webTitulo = '';
$webDescripcion = '';

$ss_config['site_name'] = '';
$ss_config['site_descripcion'] = ''; 
$ss_config['site_url'] = 'http://www.stream-server.com.ar';
$ss_config['site_email'] = 'soporte@stream-server.com.ar';

/**
 Url donde estarían los js y las hojas de estilos del sitio
 */
$ss_config['web_site_script'] = '';
$ss_config['web_site_css'] = '';
$ss_config['web_site_flash'] = '';

/**
 Manejo de Cache del sitio 
 */
$ss_config['cache_type'] = '';
$ss_config['cache_path'] = '';

/**
 Sobre los datos de conexión a la dBase
 */
$ss_config['db_host'] = 'localhost'; 
$ss_config['db_name'] = '';
$ss_config['db_user'] = '';
$ss_config['db_pass'] = '';

/**
 Información para el envio de emails
 */
$ss_config['email_send_tipo'] = 'smtp';
$ss_config['email_host'] = 'smtp.stream-server.com.ar';
$ss_config['email_user'] = 'mailenvio@stream-server.com.ar';
$ss_config['email_pass'] = 'k2j3h4';
$ss_config['email_auth'] = true;
$ss_config['email_port'] = 25;
$ss_config['email_sender'] = 'info@stream-server.com.ar';
$ss_config['email_to'] = 'info@stream-server.com.ar';

/**
 Datos para registro a newsletter
 */
$ss_config['api_mailchimp_key'] = '';
$ss_config['api_mailchimp_list'] = '';

/**
 Datos del FTP Local
 */
$i = 0;
$ss_config['remoto'][$i]['host'] = 'localhost';
$ss_config['remoto'][$i]['port'] = 21;
$ss_config['remoto'][$i]['user'] = 'hiro';
$ss_config['remoto'][$i]['pass'] = 'advhiro';
$ss_config['remoto'][$i]['path'] = '/base';
$ss_config['remoto'][$i]['url'] = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/';

/**
 Formato de generación de galerías de adjuntos
 */
$adjuntos_files[1] = array(
	'title' 	=> 'Galería de Fotos',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '1',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '210000',			// Peso máximo permitido para el adjunto
	'multi'		=> '',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:640,480',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,640,480,,o_,,exact| t,80,60,,t_,,crop',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
	'save_data'	=> $ss_config['remoto'][0],	// Array con la información de como guardar los adjuntos
	
	'add_title'	=> true,				// Boolean que indica si se agrega un nombre al adjunto
	'add_title_require'	=> true,		// Boolean que indica si el titulo es requerido
	'add_desc'	=> false,				// Boolean que indica si se agrega una descripción al adjunto
	'add_desc_require'	=> '',			// Boolean que indica si la desc es requerida

	'add_com'	=> false,				// Boolean que indica si se agrega un comentario extra al adjunto (solo visible desde el ss)
	'add_com_require'	=> '',			// Boolean que indica si el comentario es requerido
	'add_tags'	=> false,				// Boolean que indica si se agrega etiquetas al adjunto (solo visibles desde el SS)
	'add_tags_recquire'	=> '',			// Boolean que indica si el tag es requerido
	'add_obj'	=> '',					// Si es número indica el ID de la galería del objeto incrustado, sino es cero o false
	'add_obj_require' => '',			// Boolean que indica si el objeto es requerido
	
	'template'	=> ''					// Template a visualizar en el streamserver, sino utiliza el default
);
?>