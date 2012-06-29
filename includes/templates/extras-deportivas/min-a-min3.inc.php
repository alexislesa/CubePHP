<?php
/**
 * Información minuto a minuto
 */
?>

<div id="tres" class="minamin">
	
	<div class="inner-minamin">
	
		<div class="container-partidos">
		
			<?php if ($partido_arr = data_min($path_root."/includes/cache/".$info_mam_1)) { ?>
		
				<div class="partido">
					
					<div class="eq-local">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["loc_nombre"];?>
						</div><!--fin nombre-->			
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["loc_img"];?>.png" width="48" height="47" />
						</div><!--fine escudo-->
						
						<div class="goles"><?php echo $partido_arr["loc_goles"];?></div><!--fin goles-->
			
					</div><!--fin eq local-->
					
					<div class="tiempo-minamin"> </div><!--fin tiempo-->
					
					<div class="eq-visitante">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["vis_nombre"];?>
						</div><!--fin nombre-->
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["vis_img"];?>.png" width="48" height="47" />
						</div><!--fin escudo-->
						
						<div class="goles"><?php echo $partido_arr["vis_goles"];?></div><!--fin goles-->
					
					</div><!--fin eq visitante-->
					
					<div class="clear"></div>
					
					<div class="partido-estado"><?php echo $partido_arr["estado"];?></div><!--fin estado-->
					
					<div class="incidencias">
						<marquee behavior="scroll" scrollamount="3" direction="left" width="219">
							<?php echo $partido_arr["incidencia"];?>
						</marquee>
					</div><!--fin incidencias-->
					
				</div><!--fin partido-->
			
			<?php } ?>
			
			
			<?php if ($partido_arr = data_min($path_root."/includes/cache/".$info_mam_2)) { ?>
		
				<div class="partido">
					
					<div class="eq-local">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["loc_nombre"];?>
						</div><!--fin nombre-->			
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["loc_img"];?>.png" width="48" height="47" />
						</div><!--fine escudo-->
						
						<div class="goles"><?php echo $partido_arr["loc_goles"];?></div><!--fin goles-->
			
					</div><!--fin eq local-->
					
					<div class="tiempo-minamin"> </div><!--fin tiempo-->
					
					<div class="eq-visitante">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["vis_nombre"];?>
						</div><!--fin nombre-->
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["vis_img"];?>.png" width="48" height="47" />
						</div><!--fin escudo-->
						
						<div class="goles"><?php echo $partido_arr["vis_goles"];?></div><!--fin goles-->
					
					</div><!--fin eq visitante-->
					
					<div class="clear"></div>
					
					<div class="partido-estado"><?php echo $partido_arr["estado"];?></div><!--fin estado-->
					
					<div class="incidencias">
						<marquee behavior="scroll" scrollamount="3" direction="left" width="219">
							<?php echo $partido_arr["incidencia"];?>
						</marquee>
					</div><!--fin incidencias-->
					
				</div><!--fin partido-->
			
			<?php } ?>			
		
		
			<?php if ($partido_arr = data_min($path_root."/includes/cache/".$info_mam_3)) { ?>
		
				<div class="partido">
					
					<div class="eq-local">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["loc_nombre"];?>
						</div><!--fin nombre-->			
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["loc_img"];?>.png" width="48" height="47" />
						</div><!--fine escudo-->
						
						<div class="goles"><?php echo $partido_arr["loc_goles"];?></div><!--fin goles-->
			
					</div><!--fin eq local-->
					
					<div class="tiempo-minamin"> </div><!--fin tiempo-->
					
					<div class="eq-visitante">
						
						<div class="eq-nombre">
							<?php echo $partido_arr["vis_nombre"];?>
						</div><!--fin nombre-->
						
						<div class="escudo">
							<img src="/images/equipos/mam/<?php echo $partido_arr["vis_img"];?>.png" width="48" height="47" />
						</div><!--fin escudo-->
						
						<div class="goles"><?php echo $partido_arr["vis_goles"];?></div><!--fin goles-->
					
					</div><!--fin eq visitante-->
					
					<div class="clear"></div>
					
					<div class="partido-estado"><?php echo $partido_arr["estado"];?></div><!--fin estado-->
					
					<div class="incidencias">
						<marquee behavior="scroll" scrollamount="3" direction="left" width="219">
							<?php echo $partido_arr["incidencia"];?>
						</marquee>
					</div><!--fin incidencias-->
					
				</div><!--fin partido-->
			
			<?php } ?>		
		
		</div><!--fin container partidos-->
		
	</div><!--fin inner-->
	
</div><!--fin minamin-->

<div class="clear"></div>