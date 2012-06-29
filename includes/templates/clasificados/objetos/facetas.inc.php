<?php 
/** Modifica información de GEt para facetar */
$oHref = $_GET;
unset($oHref['p']);
$oHrefBase = http_build_query($oHref);


/* Muestra las opciones por las que se faceto */
$oFacCategoria = false;
$oFacPais = false;
$oFacProvincia = false;
$oFacDepartamento = false;
$oFacLocalidad = false;
$oSelPais = false;
$oSelProvincia = false;
$oSelDepartamento = false;
$oSelLocalidad = false;
$oSelCategoria = false;

if (!empty($dataFacetas) && is_array($dataFacetas)) {
	if (count($dataFacetas['categoria']) > 1) {
		foreach ($dataFacetas['categoria'] as $oId => $oCant) {
			$oInf = $gd->categorias($oId);

			foreach ($oInf as $oNivel => $vd) {
				
				if (!isset($oFacCategoria[$vd['categoria_id']])) {
					$oFacCategoria[$vd['categoria_id']]['nombre'] = $vd['categoria_nombre'];
					$oFacCategoria[$vd['categoria_id']]['cantidad'] = $oCant;
				} else {
					$oFacCategoria[$vd['categoria_id']]['cantidad']+= $oCant;
				}
			}
		}
	}

	if (count($dataFacetas['pais']) > 1) {
		foreach ($dataFacetas['pais'] as $oId => $oCant) {
			$oInf = $gd->ubicaciones($oId);
			$oFacPais[$oId] = array(
				"nombre" => $oInf['pais']['ubicacion_nombre'], 
				"cantidad" => $oCant
			);
		}
	}

	if (count($dataFacetas['provincia']) > 1) {
		foreach ($dataFacetas['provincia'] as $oId => $oCant) {
			$oInf = $gd->ubicaciones($oId);
			$oFacProvincia[$oId] = array(
				"nombre" => $oInf['provincia']['ubicacion_nombre'], 
				"cantidad" => $oCant
			);
		}
	}

	if (count($dataFacetas['departamento']) > 1) {
		foreach ($dataFacetas['departamento'] as $oId => $oCant) {
			$oInf = $gd->ubicaciones($oId);
			$oFacDepartamento[$oId] = array(
				"nombre" => $oInf['departamento']['ubicacion_nombre'], 
				"cantidad" => $oCant
			);
		}
	}

	if (count($dataFacetas['localidad']) > 1) {
		foreach ($dataFacetas['localidad'] as $oId => $oCant) {
			$oInf = $gd->ubicaciones($oId);
			$oFacLocalidad[$oId] = array(
				"nombre" => $oInf['localidad']['ubicacion_nombre'], 
				"cantidad" => $oCant
			);
		}
	}

	if (!empty($oHref['pais'])) {
		$oHrefT = $oHref;
		$oHrefT['pais'] = '';
		
		$oInf = $gd->ubicaciones($oHref['pais']);
		$oSelPais['nombre'] = $oInf['pais']['ubicacion_nombre'];
		$oSelPais['link'] = http_build_query($oHrefT);
	}

	if (!empty($oHref['prov'])) {
		$oHrefT = $oHref;
		$oHrefT['prov'] = '';
		
		$oInf = $gd->ubicaciones($oHref['prov']);
		$oSelProvincia['nombre'] = $oInf['provincia']['ubicacion_nombre'];
		$oSelProvincia['link'] = http_build_query($oHrefT);
	}

	if (!empty($oHref['dep'])) {
		$oHrefT = $oHref;
		$oHrefT['dep'] = '';
		
		$oInf = $gd->ubicaciones($oHref['dep']);
		$oSelDepartamento['nombre'] = $oInf['departamento']['ubicacion_nombre'];
		$oSelDepartamento['link'] = http_build_query($oHrefT);
	}

	if (!empty($oHref['loc'])) {
		$oHrefT = $oHref;
		$oHrefT['loc'] = '';
		
		$oInf = $gd->ubicaciones($oHref['loc']);
		$oSelLocalidad['nombre'] = $oInf['localidad']['ubicacion_nombre'];
		$oSelLocalidad['link'] = http_build_query($oHrefT);
	}

	if (!empty($oHref['cat'])) {
	$oHrefT = $oHref;
	unset($oHrefT['cat']);
	
	$o = $gd->categorias($oHref['cat']);
	$oInf = $o[count($o)-1];
	$oSelCategoria['nombre'] = $oInf['categoria_nombre'];
	$oSelCategoria['link'] = http_build_query($oHrefT);
}
}
?>
<section class="block" id="facetas">
	
	<h4>Facetados:</h4>
	
	<?php 
	// Pais
	if ($oFacPais) { ?>
		<div style="padding:5px; border:solid 1px #CCC;margin:5px;">
		País: <br/>
		<?php foreach($oFacPais as $oId => $oData) { ?>

			<br/> <a href="?<?php echo $oHrefBase;?>&pais=<?php echo $oId;?>"><?php echo $oData['nombre'];?> (<?php echo $oData['cantidad'];?>)</a>

		<?php } ?>
		</div>
	<?php } elseif (!empty($oSelPais)) { ?>
	
		<br/>País: <?php echo $oSelPais['nombre'];?> <a href="?<?php echo $oSelPais['link'];?>">[X]</a>
	
	<?php } ?>
	
	<?php 
	// Provincia
	if ($oFacProvincia) { ?>
		<div style="padding:5px; border:solid 1px #CCC;margin:5px;">
		Provincia: <br/>
		<?php foreach($oFacProvincia as $oId => $oData) { ?>

			<br/> <a href="?<?php echo $oHrefBase;?>&prov=<?php echo $oId;?>"><?php echo $oData['nombre'];?> (<?php echo $oData['cantidad'];?>)</a>

		<?php } ?>
		</div>
	<?php } elseif ($oSelProvincia) { ?>
	
		<br/>Provincia: <?php echo $oSelProvincia['nombre'];?> <a href="?<?php echo $oSelProvincia['link'];?>">[X]</a>
	
	<?php } ?>
	
	<?php 
	// Departamento
	if ($oFacDepartamento) { ?>
		<div style="padding:5px; border:solid 1px #CCC;margin:5px;">
		Departamento: <br/>
		<?php foreach($oFacDepartamento as $oId => $oData) { ?>

			<br/> <a href="?<?php echo $oHrefBase;?>&dep=<?php echo $oId;?>"><?php echo $oData['nombre'];?> (<?php echo $oData['cantidad'];?>)</a>

		<?php } ?>
		
		</div>
	<?php } elseif($oSelDepartamento) { ?>
	
		<br/>Departamento: <?php echo $oSelDepartamento['nombre'];?> <a href="?<?php echo $oSelDepartamento['link'];?>">[X]</a>
	
	<?php } ?>
	
	<?php 
	// Localidad
	if ($oFacLocalidad) { ?>
		<div style="padding:5px; border:solid 1px #CCC;margin:5px;">
		Localidad: <br/>
		<?php foreach($oFacLocalidad as $oId => $oData) { ?>

			<br/> <a href="?<?php echo $oHrefBase;?>&loc=<?php echo $oId;?>"><?php echo $oData['nombre'];?> (<?php echo $oData['cantidad'];?>)</a>

		<?php } ?>
		</div>
	<?php } elseif($oSelLocalidad) { ?>

		<br/>Localidad: <?php echo $oSelLocalidad['nombre'];?> <a href="?<?php echo $oSelLocalidad['link'];?>">[X]</a>
	
	<?php } ?>
	

	<?php
	// Categorías
	if (count($oFacCategoria) > 1) { ?>
	
		<div style="padding:5px; border:solid 1px #CCC;margin:5px;">
			Categorías: <br/>
			
			<?php foreach ($oFacCategoria as $oNro => $oData) { ?>
			
				<br/> <a href="?<?php echo $oHrefBase;?>&cat=<?php echo $oNro;?>"><?php echo $oData['nombre'];?> (<?php echo $oData['cantidad'];?>)</a>
				
			<?php } ?>
			
		</div>
	
	<?php 
	} 
	
	if ($oSelCategoria) {?>
	
		<br/>Categoría: <?php echo $oSelCategoria['nombre'];?> <a href="?<?php echo $oSelCategoria['link'];?>">[X]</a>
	
	<?php } ?>
</section>