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
 * Muestra un reproductor de audio embebido en el HTML del texto 
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
 *	- $peso_audio		Devuelve el peso del audio en KB/MB/GB etc
 *
 * @changelog:
 */

$m = $dataToSkin; 
$j = md5(microtime(true));
?>

<div class="embed">
	
	<div class="audios">
	
		<div id="audioplayeremb<?php echo $j;?>"> 
		
			Requiere Adobe Flash Player 9 o Superior
		
		</div>
	
	</div>

	<?php 
	/**
	 * Muestra el nombre o titulo del audio
	 */
	if ($m['gal_nombre'] != '') { ?>
	
		<div class="video-pie">
			Audio: <span><?php echo $m['gal_nombre'];?></span>
		</div>
	
	<?php } ?>
	
	<?php 
	/**
	 * Muestra una descripción del audio (generalmente no utilizado)
	 */
	if ($m['gal_descripcion'] != '') { ?>
	
		<div class="video-desc">
			<span><?php echo $m['gal_descripcion'];?></span>
		</div>
	
	<?php } ?>

</div><!--/.embed-->

<script type="text/javascript">
<!-- // --><![CDATA[

var flashvarsa<?php echo $j;?> = {
	file:"<?php echo $m['url']['o'];?>", 
	autostart:"false",
	bufferlength:3,
	skin: "/flash/modieus.zip"
}

var paramsa<?php echo $j;?> = {
	allowfullscreen:"false", 
	allowscriptaccess:"always",
	wmode:"opaque"
}

var attributesa<?php echo $j;?> = {
	id:"idaudioplayer<?php echo $j;?>",  
	name:"idaudioplayer<?php echo $j;?>"
}

swfobject.embedSWF("/flash/player.swf", "audioplayeremb<?php echo $j;?>", "640", "31", "9.0.115", false, flashvarsa<?php echo $j;?>, paramsa<?php echo $j;?>, attributesa<?php echo $j;?>);

// ]]>
</script>