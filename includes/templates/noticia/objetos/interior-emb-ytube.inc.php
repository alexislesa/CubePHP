<?php
/**
 * ***************************************************************
 * @package		GUI-WebSite
 * @access		public
 * @author		Alexis Lesa
 * @copyright	Advertis Web Factory
 * @licence 	Comercial
 * @version 	1.0
 * @revision 	24.08.2010
 * *************************************************************** 
 *
 * Muestra un reproductor de video embebido en el HTML del texto 
 * 
 * Array de datos que es posible devolver:
 *	- gal_file_ext		La extensión del archivo
 *	- gal_file			Nombre del archivo
 *	- gal_nombre		Titulo del audio
 *	- gal_descripcion	Descripción del audio
 *	- gal_fecha			Fecha de publicación en el sitio
 *	- extra["size"]		Peso del audio en Bytes
 *	- extra["duracion"]	Duración del video en segundos (no siempre se tiene ese dato)
 *
 *	- $peso_video		Devuelve el peso del video en KB/MB/GB etc
 *
 * @changelog:
 */
$m = $dataToSkin;
$j = 'j'.md5(microtime(true));

// carga la foto
if ($m['gal_tipo'] == 'ytube') {
	$nt_foto = $m["extra"]["img_large"];
}
?>

<div class="embed">
	
	<div id="video">
	
		<div id='videoplayer<?php echo $j;?>'>
		
			<b>Requiere Adobe Flash Player 9 o Superior <br><br></b>
			<a href='http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&Lang=Spanish' target='_blank'>Instalar Gratis</a>
			
		</div>
	
	</div>

	<?php 
	/**
	 * Muestra el nombre o titulo del video
	 */
	if ($m['gal_nombre'] != '') { ?>

		<div class="video-pie">
			Video: <span><?php echo $m['gal_nombre'];?></span>
		</div>

	<?php } ?>

	<?php
	/**
	 * Muestra una descripción del video (generalmente no utilizado)
	 */
	if ($m['gal_descripcion'] != '') { ?>

		<div class="video-desc">
			<span><?php echo $m['gal_descripcion'];?></span>
		</div>

	<?php } ?>						

</div><!--/.embd-->

<script type="text/javascript">
<!-- // --><![CDATA[

var flashvarsv<?php echo $j?> = {
	file:"<?php echo $m['url']['o'];?>", 
	image: "<?php echo $nt_foto;?>",
	autostart:"false",
	bufferlength: 3,
	wmode: "opaque",
	skin: "/flash/modieus.zip"
}

var paramsv<?php echo $j;?> = {
	allowfullscreen:"true", 
	allowscriptaccess:"always",
	wmode:"opaque"
}

var attributesv<?php echo $j;?> = {
	id:"idytubeplayer<?php echo $j;?>",  
	name:"idytubeplayer<?php echo $j;?>"
}

swfobject.embedSWF("/flash/player.swf", "videoplayer<?php echo $j;?>", "640", "315", "9.0.115", false, flashvarsv<?php echo $j;?>, paramsv<?php echo $j;?>, attributesv<?php echo $j;?>);

// ]]>
</script>