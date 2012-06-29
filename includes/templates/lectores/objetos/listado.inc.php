<?
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
 * Muestra un listado de artículos del usuario
 * 
 * @changelog:
 */

/* Cargo los estados de las notas */
$estados_arr = array();
$estados_arr[0] = "Pendiente de Aprobación";
$estados_arr[1] = "Aprobada";
$estados_arr[2] = "Rechazada";

if (!$error_msj && !empty($dataToSkin)) { ?>
	
	<div class="listado-noticias">
	
		<table class="misnoticias">
		<thead>
		<tr class="encabezado">
		<td class="noticia-data">Mis noticias</td>
		<td class="noticia-estado">Estado</td>
		<td class="noticia-editar">Editar</td>
		<td class="noticia-borrar">Borrar</td>
		</tr>
		</thead>
		
		<tbody>
		<tr><td class="noticia-margin" colspan="4"></td></tr>
		
		<?
		$par_impar = 0;
		foreach ($dataToSkin as $k => $v) {
			
	

			$fecha_nota = format_fecha($v["noticia_fecha_modificacion"], $tipo_fecha, $dia_txt, $mes_txt);

			/* Modifico la clase en caso de que el artículo se muestre con foto */
			$class_foto = !empty($v["imagen"]) ? "confoto" : "";

			/* Modifico la clase en caso de desee diferencia las pares de las impares */
			$par_impar = !$par_impar;
			$class_impar = ($par_impar) ? "impar" : "";
			
			/**
			 * *******************************************
			 * Nota pendiente de aprobación
			 * *******************************************
			 */
			if ($v["noticia_estado"] == 0) { ?>

				<tr class="<?=$class_foto;?> <?=$class_impar;?> item-estado-<?=$v["noticia_estado"];?>">
				
				<td class="noticia-info">
					<span class="table-seccion"><?=$v["seccion_nombre"];?></span><span class="table-fecha"><?=$fecha_nota;?> - 11:56 Hs.</span>
					<div class="clear"></div>
					<a class="table-title" target="_blank" href="/lectores/noticia-edit.php?id=<?=$v["noticia_id"];?>" title="Editar esta noticia"><?=$v['noticia_titulo'];?></a>
	
				</td>
				
				<td class="table-estado">pendiente <span>de aprobación</span></td>
				
				<td class="editar-ico"><a href="/lectores/noticia-edit.php?id=<?=$v["noticia_id"];?>" title="Editar">Editar</a></td>
				
				<td class="del-ico"><a href="/lectores/noticia-list.php?id=<?=$v["noticia_id"];?>&act=del" title="Borrar">Eliminar</a></td>
				
				</tr>
			
			<? } 
			
			/**
			 * *******************************************
			 * Nota aprobada
			 * *******************************************
			 */
			if ($v["noticia_estado"] == 1) { ?>

				<tr class="<?=$class_foto;?> <?=$class_impar;?> item-estado-<?=$v["noticia_estado"];?>">
				
				<td class="noticia-info">
					<span class="table-seccion"><?=$v["seccion_nombre"];?></span><span class="table-fecha"><?=$fecha_nota;?> - 11:56 Hs.</span>
					<div class="clear"></div>
					<a class="table-title" target="_blank" href="/comunidad/nota.php?id=<?=$v["noticia_id"];?>" title="Ver esta noticia"><?=$v['noticia_titulo'];?></a>
	
				</td>
				
				<td class="table-estado">publicado</td>
				
				<td class="editar-ico"><span></span></td>
				
				<td class="del-ico"><span></span></td>
				
				</tr>
			
			<? }
			
			/**
			 * *******************************************
			 * Nota rechazada
			 * *******************************************
			 */
			if ($v["noticia_estado"] == 2) { ?>

				<tr class="<?=$class_foto;?> <?=$class_impar;?> item-estado-<?=$v["noticia_estado"];?>">
				
				<td class="noticia-info">
					<span class="table-seccion"><?=$v["seccion_nombre"];?></span><span class="table-fecha"><?=$fecha_nota;?> - 11:56 Hs.</span>
					<div class="clear"></div>
					<span class="table-title"><?=$v['noticia_titulo'];?></span>

				</td>
				
				<td class="table-estado">rechazado</td>
				
				<td class="editar-ico"><span></span></td>
				
				<td class="del-ico"><span></span></td>
				
				</tr>
			
			<? } ?>			
			
		

		<? } ?>
		
		</tbody>
		</table>

	</div>
	
<? } ?>