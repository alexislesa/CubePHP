<?php
function viewVideo($m) {
	if (!empty($m)) {
		$f = $m["gal_fecha"];
		$fecha_nota = date("d/m/Y", $f);

		if ($m["gal_tipo"] == "ytube" && empty($m["extra"]["adj"])) {
			$img_foto = $m["extra"]["img_alternativa"];
		} else {

			$img_foto = $m["adj"]["url"]["o"];

		}
		?>

		<div class="item-video">
		
			<a class="caja" href="/videos/index.php?id=<?php echo $m["gal_id"]?>" rel="nofollow" title="<?php echo $m["gal_nombre"];?>">
				
				<img src="<?php echo $img_foto;?>" alt="<?php echo $m["gal_nombre"];?>" width="186" height="104"/>
				
				<span>
					<b><span class="ico arrow"></span><?php echo $fecha_nota;?></b><span>&nbsp;|&nbsp;</span><?php echo $m["gal_nombre"];?>
				</span>

			</a>
			
			<a href="/videos/index.php?id=<?php echo $m["gal_id"]?>" class="ico play"></a>
		   
		</div><!--fin item video-->

	<?php }

} // End function
?>

<pre><?=print_r($dataToSkin, true);?></pre>
<div class="bloque-video">

	<?php foreach ($dataToSkin as $k => $v) {
		viewVideo($v);
	} ?>
	
</div><!--fin bloque video-->