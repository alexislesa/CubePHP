<?php
/* Se sobre escriben los parámetros globales del sitio */

/* Modo debug, no eliminar esta línea */
$ss_config['debug_mode'] = false;

$webTitulo = 'Web Site Demo';
$webDescripcion = 'Sitio demo [Descripción]';
 
$ss_config['site_name'] = '[Web Site Demo]';
$ss_config['site_descripcion'] = '[Descripción del sitio demo]';
$ss_config['site_url'] = 'http://base.dev';
$ss_config['site_email'] = 'soporte@base.dev';

/* Datos para registro a newsletter */
$ss_config['api_mailchimp_key'] = '993bfa71b21dc0aa7f2648d0dab86b0e-us4';
$ss_config['api_mailchimp_list'] = '983321c9e5';


/* Información sobre el API de Google. http://base.dev */
$ss_config['api_google_maps'] = 'ABQIAAAAs2lzptoWVNIdi_W8XjLu8xTQrTh8ATPxrCxUek14NNPm4sUaRBTaqYR6tnaecdcJv1XienySqh73hA';

/* Sobre los datos de conexión a la dBase */
$ss_config['db_user'] = 'web_base';
$ss_config['db_pass'] = '1325';
$ss_config['db_name'] = 'web_base';
$ss_config['db_host'] = 'localhost';

/* Manejo de Cache del sitio */
$ss_config['cache_type'] = 'file';
$ss_config['cache_path'] = dirPath.'/includes/cache/';

/** Configuración de email Tester */
$ss_config['email_to_tester'][] = 'joselogil@gmail.com';
$ss_config['email_to_tester'][] = 'joselopelo17@hotmail.com';
$ss_config['email_to_tester'][] = 'alexislesa@yahoo.com';

/* Datos del FTP para adjuntos */
$i = 0;
$ss_config['remoto'][$i]['host'] = 'localhost';
$ss_config['remoto'][$i]['port'] = 21;
$ss_config['remoto'][$i]['user'] = 'hiro';
$ss_config['remoto'][$i]['pass'] = 'advhiro';
$ss_config['remoto'][$i]['path'] = '/base/galeria';
$ss_config['remoto'][$i]['url'] = 'http://base.advertis.dyndns.org/galeria';



$adjuntos_files[1] = array(
	'title' 	=> 'Galería de Fotos',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '1',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '410000',			// Peso máximo permitido para el adjunto
	'multi'		=> '5',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:600,350',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,600,350,,o_,,exact| l,540,315,,l_,,exact| m,372,217,,m_,,exact| n,350,175,,n_,,exact| r,276,161,,r_,,exact| p,240,140,,p_,,exact| d,180,105,,d_,,exact| j,156,91,,j_,,exact| s,120,70,,s_,,exact| t,80,60,,t_,,crop',
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
 
$adjuntos_files[2] = array(
	'title' 	=> 'Galería de Audios',
	'type'		=> 'audio',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '2',
	
	'ext'		=> 'mp3',
	'peso'		=> '6200000',
	'multi'		=> '5',

	'is_image'	=> false,
	'convert'	=> '',
	'tam_require'	=> '',
	'imagen'		=> '',
	
	'metodo'	=> 'ftp',
	'path'		=> '/audios/%Y/%m/%d/',
	'save_data'	=> $ss_config['remoto'][0],
	
	'add_title'	=> true,
	'add_title_require'	=> true,
	'add_desc'	=> true,		
	'add_desc_require'	=> '',	

	'add_com'	=> false,		
	'add_com_require'	=> '',	
	'add_tags'	=> false,		
	'add_tags_recquire'	=> '',	
	'add_obj'	=> '',			
	'add_obj_require' => '',	
	
	'template'	=> ''
);


$adjuntos_files[3] = array(
	'title' 	=> 'Galería de Videos',
	'type'		=> 'ytube',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '3',	
	
	'ext'		=> '',	
	'peso'		=> '',	
	'multi'		=> '',	

	'is_image'	=> false, 	
	'convert'	=> '',		
	'tam_require'	=> '', 
	'imagen'		=> '',		

	'metodo'	=> 'ftp',
	'path'		=> '',	
	'save_data'	=> $ss_config['remoto'][0],	
	
	'add_title'	=> true,
	'add_title_require'	=> true,	
	'add_desc'	=> true,
	'add_desc_require'	=> false,

	'add_com'	=> false,			
	'add_com_require'	=> '',		
	'add_tags'	=> false,			
	'add_tags_require'	=> '',		
	'add_obj'	=> '',				
	'add_obj_require' => false,
	
	'template'	=> ''	
);

$adjuntos_files[4] = array(
	'title' 	=> 'Galería de Links',	
	'type'		=> 'link',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '4',	
	
	'ext'		=> '',	
	'peso'		=> '',	
	'multi'		=> '4',	

	'is_image'	=> false, 
	'convert'	=> '',		
	'tam_require'	=> '', 
	'imagen'		=> '',	
	
	'metodo'	=> '',
	'path'		=> '',	
	'save_data'	=> $ss_config['remoto'][0],	
	
	'add_title'	=> true,			
	'add_title_require'	=> true,	
	'add_desc'	=> true,			
	'add_desc_require'	=> '',		

	'add_com'	=> false,			
	'add_com_require'	=> '',		
	'add_tags'	=> false,			
	'add_tags_require'	=> '',		
	'add_obj'	=> '',				
	'add_obj_require' => '',		
	
	'template'	=> 'template.edit-links.inc.php'	
);

$adjuntos_files[5] = array(
	'title' 	=> 'Galería de Documentos',	
	'type'		=> 'file',	

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '5',
	
	'ext'		=> 'doc, docx, rtf, xls, xlsx, ppt, pptx, pdf, odt, rtf',
	'peso'		=> '8000000',
	'multi'		=> '5',

	'is_image'	=> false,
	'convert'	=> '',	
	'tam_require'	=> '', 
	'imagen'		=> '',	
	
	'metodo'	=> 'ftp',	
	'path'		=> '/docs/%Y/%m/%d/',
	'save_data'	=> $ss_config['remoto'][0],
	
	'add_title'	=> true,			
	'add_title_require'	=> true,	
	'add_desc'	=> true,			
	'add_desc_require'	=> '',		

	'add_com'	=> false,			
	'add_com_require'	=> '',		
	'add_tags'	=> false,			
	'add_tags_require'	=> '',		
	'add_obj'	=> '',				
	'add_obj_require' => '',		
	
	'template'	=> ''
);

$adjuntos_files[6] = array(
	'title' 	=> 'Galería de Videos',
	'type'		=> 'video',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '6',	
	
	'ext'		=> 'flv',
	'peso'		=> '32000000',
	'multi'		=> '5',

	'is_image'	=> false,
	'convert'	=> '',
	'tam_require'	=> '',
	'imagen'		=> '',
	
	'metodo'	=> 'ftp',
	'path'		=> '/videos/%Y/%m/%d/',
	'save_data'	=> $ss_config['remoto'][0],
	
	'add_title'	=> true,
	'add_title_require'	=> true,
	'add_desc'	=> true,
	'add_desc_require'	=> true,

	'add_com'	=> false,
	'add_com_require'	=> '',
	'add_tags'	=> true,
	'add_tags_require'	=> '',
	'add_obj'	=> '1',
	'add_obj_require' => true,
	
	'template'	=> ''
);

$adjuntos_files[7] = array(
	'title' 	=> 'Galería de Presentaciones (Slideshare / Prezi)',
	'type'		=> 'presentation',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '7',	
	
	'ext'		=> '',
	'peso'		=> '',
	'multi'		=> '',

	'is_image'	=> false,
	'convert'	=> '',
	'tam_require'	=> '',
	'imagen'		=> '',
	
	'metodo'	=> 'ftp',
	'path'		=> '/videos/%Y/%m/%d/',
	'save_data'	=> $ss_config['remoto'][0],
	
	'add_title'	=> true,
	'add_title_require'	=> true,
	'add_desc'	=> true,
	'add_desc_require'	=> false,

	'add_com'	=> false,
	'add_com_require'	=> '',
	'add_tags'	=> false,
	'add_tags_require'	=> '',
	'add_obj'	=> '',
	'add_obj_require' => '',
	
	'template'	=> ''
);

$adjuntos_files[8] = array(
	'title' 	=> 'Galería de audios sindicados',
	'type'		=> 'saudio',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '8',	
	
	'ext'		=> '',	
	'peso'		=> '',	
	'multi'		=> '',	

	'is_image'	=> false, 	
	'convert'	=> '',		
	'tam_require'	=> '', 
	'imagen'		=> '',		

	'metodo'	=> 'ftp',
	'path'		=> '',	
	'save_data'	=> $ss_config['remoto'][0],	
	
	'add_title'	=> true,
	'add_title_require'	=> true,	
	'add_desc'	=> true,			
	'add_desc_require'	=> true,		

	'add_com'	=> false,			
	'add_com_require'	=> '',		
	'add_tags'	=> false,			
	'add_tags_require'	=> '',		
	'add_obj'	=> '',
	'add_obj_require' => false,		
	
	'template'	=> ''	
);

$adjuntos_files[9] = array(
	'title' 	=> 'Galería de streamin en vivo',
	'type'		=> 'vivo',

	'dbase' 	=> 'noticias_galeria_multimedia',
	'gallery'	=> '9',	
	
	'ext'		=> '',	
	'peso'		=> '',	
	'multi'		=> '',	

	'is_image'	=> false, 	
	'convert'	=> '',		
	'tam_require'	=> '', 
	'imagen'		=> '',		

	'metodo'	=> 'ftp',
	'path'		=> '',	
	'save_data'	=> $ss_config['remoto'][0],	
	
	'add_title'	=> true,
	'add_title_require'	=> true,
	'add_desc'	=> true,			
	'add_desc_require'	=> false,		

	'add_com'	=> false,			
	'add_com_require'	=> '',		
	'add_tags'	=> false,			
	'add_tags_require'	=> '',		
	'add_obj'	=> '',				
	'add_obj_require' => false,	
	'template'	=> ''	
);

$adjuntos_files[10] = array(
	'title' 	=> 'Galería de Fotos Opiniones y entrevistas',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '10',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '410000',			// Peso máximo permitido para el adjunto
	'multi'		=> '5',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:420,450',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,420,450,,o_,,exact| n,308,330,,n_,,exact| r,280,300,,r_,,exact| p,210,225,,p_,,exact| j,140,150,,j_,,exact| s,84,90,,s_,,exact| t,80,60,,t_,,exact',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos-enc/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
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


$adjuntos_files[11] = array(
	'title' 	=> 'Galería de Fotos Full para home',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '11',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '510000',			// Peso máximo permitido para el adjunto
	'multi'		=> '',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:960,270',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,960,270,,o_,,exact| t,80,60,,t_,,exact',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos-full/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
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


$adjuntos_files[12] = array(
	'title' 	=> 'Galería de Infogafía',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '12',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '410000',			// Peso máximo permitido para el adjunto
	'multi'		=> '5',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:720,420',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,720,420,,o_,,exact| l,650,350,,l_,,exact| m,600,350,,m_,,exact| r,276,161,,r_,,exact| s,120,70,,s_,,exact| t,80,60,,t_,,crop',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos-infog/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
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



$adjuntos_files[13] = array(
	'title' 	=> 'Galería de Fotos para descargar (1)',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '13',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '410000',			// Peso máximo permitido para el adjunto
	'multi'		=> '',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:600,350',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,600,350,,o_,,exact| l,500,315,,l_,,exact| m,372,217,,m_,,exact| n,350,175,,n_,,exact| r,276,161,,r_,,exact| p,240,140,,p_,,exact| d,180,105,,d_,,exact| j,156,91,,j_,,exact| s,120,70,,s_,,exact| t,80,60,,t_,,exact',
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
	'add_obj'	=> '14',					// Si es número indica el ID de la galería del objeto incrustado, sino es cero o false
	'add_obj_require' => true,			// Boolean que indica si el objeto es requerido
	'template'	=> ''					// Template a visualizar en el streamserver, sino utiliza el default
); 


$adjuntos_files[14] = array(
	'title' 	=> 'Galería de Fotos para descargar (2)',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '14',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '2100000',			// Peso máximo permitido para el adjunto
	'multi'		=> '',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'mayor:800,600',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,0,0,,o_,,exact| t,80,60,,t_,,crop',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos-big/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
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



$adjuntos_files[15] = array(
	'title' 	=> 'Galería de Fotos viejas',	// Nombre de la galería
	'type'		=> 'image',				// Tipo de objeto de galería (image, audio, video, file, link, youtube, etc)

	'dbase' 	=> 'noticias_galeria_multimedia',	// Nombre de la base de datos
	'gallery'	=> '15',					// ID de la galería a donde se guarda
	
	'ext'		=> 'jpg, gif, png',		// Extensiones permitidas separadas con espacios
	'peso'		=> '410000',			// Peso máximo permitido para el adjunto
	'multi'		=> '',					// Cantidad de upload simultaneos permitidos (1 -> nro)

	'is_image'	=> true, 				// Boolean que indica si el adjunto va a ser tratado como una imagen
	'convert'	=> '',					// Extensión a la cual converir la imagen (vacio por defecto)
	'tam_require'	=> 'exact:480,280',	
		//prop:1.333|mayor:360,360|menor:1500,1500|exact:480,360',	
		// Acepta parametros separados por | ej: exact:480,360|mayor:480,360|prop:1.25|menor:800,600
		
	'imagen'		=> 'o,480,280,,o_,,exact| l,350,375,,l_,,exact| m,276,161,,m_,,exact| r,168,98,,r_,,exact| s,216,126,,s_,,exact| t,132,77,,t_,,exact',
		// Procesamiento de imagenes, parametros separados por |:
		// Ej: name(o:original, t:thumb), ancho, alto, folder, prefix, sufix, resize mode (extact, best, crop)
	
	'metodo'	=> 'ftp',				// Como se va a guardar el adjunto: file, ftp, ssh, dbase
	'path'		=> '/fotos-old/%Y/%m/%d/',	// Path donde se van a guardar los objetos, acepta variables de fecha ej: /galeria/fotos/%Y/%m/
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