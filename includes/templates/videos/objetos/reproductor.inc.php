<?php
/**
* Si tuviera, muestra el video de la noticia.
* Recorre el array y muestra todos los videos asociados.
* La variable que maneja los videos debe ser $v
*/
$fhc = time();
if (!empty($videoToSkin)) { 
	$m = $videoToSkin[0];
	if ($m["gal_tipo"] == "ytube" && empty($m["extra"]["adj"])) {
		$img_foto = $m["extra"]["img_alternativa"];
	} else {
		$img_foto = ($m["gal_galeria"] != 9) ? $m["extra"]["adj"]["url"]["o"] : $m["extra"]["adj"]["url"]["l"];
	}
	?>

	<div id="video-cont" class="block">
	
		<div class="video-interior">
		
			<?php 
			if (($ipod || $iphone || $ipad) && $m["gal_tipo"] == "ytube") { ?>

				<object width="640" height="395" id="videoYT">
				<param name="movie" value="<?php echo $m["url"]["o"];?>"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name='wmode' value='transparent'>
				<embed src="<?php echo $m["url"]["o"];?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="395" wmode='transparent'></embed>
				</object>

			<?php } else { ?>
			
				<div id="video">
				
					<div id="videoplayer<?php echo $fhc;?>"> 

						<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='640' height='396' id='idytubeplayer<?php echo $j;?>' name='idytubeplayer<?php echo $j;?>'>
							<param name='movie' value='/flash/player.swf'>
							<param name='allowfullscreen' value='true'>
							<param name='allowscriptaccess' value='always'>
							<param name='wmode' value='transparent'>
							<param name='flashvars' value='file=<?php echo urlencode($m["url"]["o"]);?>&amp;autostart=false&amp;bufferlength=3&amp;image=<?php echo urlencode($img_foto);?>&amp;skin=/flash/skin-video-int2.swf'>
							<embed
							  id='idytubeplayer<?php echo $j;?>'
							  name='idytubeplayer<?php echo $j;?>'
							  src='/flash/player.swf'
							  width='640'
							  height='396'
							  bgcolor='#000000'
							  allowscriptaccess='always'
							  allowfullscreen='true'
							  wmode='transparent'
							  flashvars='file=<?php echo $m["url"]["o"];?>&amp;autostart=false&amp;bufferlength=3&amp;image=<?php echo $img_foto;?>&amp;skin=/flash/skin-video-int2.swf'
							/>
						</object>
		
					</div>
				</div>	
				
			<?php } ?>
		
		</div>
		
	</div>
	
	<div class="bloque-thumbs">
    
		<?php
		/**
		 * Genera un listado de videos con fotos que se ven al costado del video principal
		 */
        $oMax = (count($videoToSkin) > 5) ? 5 : count($videoToSkin);
        for ($vd=0; $vd<$oMax; $vd++) { 
            $m = $videoToSkin[$vd];
			$img_foto = !empty($m["extra"]["adj"]["url"]) ? $m["extra"]["adj"]["url"]["t"] : $m["extra"]["img_alternativa"];
			$clase = (!$vd) ? "active" : "";
            ?>
    
            <div class="thumbs <?php echo $clase;?>">
            	
                <div class="img">
                	
					<a href="/videos/index.php?id=<?php echo $m["gal_id"]?>" title="<?php echo $m["gal_nombre"];?>"></a>

					<img src="<?php echo $img_foto;?>" width="80" height="45"/>                  
				
                </div><!--fin img-->
                
                <div class="desc">
                    
                    <div class="middle">
                    
                    	<div class="center"><a href="/videos/index.php?id=<?php echo $m["gal_id"]?>" title="<?php echo $m["gal_nombre"];?>"><?php echo $m["gal_nombre"];?></a></div>
                        
                    </div>
                    
                </div><!--fin desc-->
                
            </div><!--fin thumbs-->
			
            <div class="clear"></div>
  
        <?php } ?>
        
    </div><!--fin bloque thumbs-->
	
	<div class="list-links">
	
		<ul class="linksv">
			
			<li><a href="#" title="Modo Cine" class="cinema lightSwitcher"></a></li>

			<li><a href="" rel="nofollow" onclick="return false;" title="URL" class="url" onmouseover="this.className='url showme'" onmouseout="this.className='url'">
				<span class="pop-body">
					<span class="pop-box">
						<em class="arrow-pop"></em>
		
						<span class="pop-inner">
							<b>url permanente</b>				
							
							<input type="text" value="http://<?php echo $_SERVER["SERVER_NAME"];?>/videos/index.php?id=<?php echo $videoToSkin[0]["gal_id"];?>"  onclick="this.focus();this.select();" id="urlperm" />
						</span><!--fin pop inner-->	
					
					</span><!--fin pop box-->
				</span>			
			</a></li>
			
			<li><a href="#" rel="nofollow" title="Embeber" onclick="return false;" class="embeber" onmouseover="this.className='embeber showme'" onmouseout="this.className='embeber'">
				<span class="pop-body">
					<span class="pop-box">
						<em class="arrow-pop"></em>
		
						<span class="pop-inner">
							<b>embeber</b>				
							
							<textarea id="emb_id" onclick="this.focus();this.select();" ><object width="480" height="385"><param name="movie" value="<?php echo $m["url"]["o"];?>"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="<?php echo $m["url"]["o"];?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object></textarea>
						</span><!--fin pop inner-->	
					
					</span><!--fin pop box-->
				</span>
			</a></li>
			
			<li><a title="Recomendar" href="javascript:ventana('/extras/videos/enviar.php?id=<?php echo $m["gal_id"]?>','', 650,496,'no');" class="env"></a></li>
			<li><a class="fbk" href="http://www.facebook.com/share.php?u=<?php echo $adv_this_url;?>" target="_blank" title="Facebook"></a></li>
			<li><a class="twt" href="http://twitthis.com/twit?url=<?php echo $adv_this_url;?>" target="_blank" title="Twitter"></a></li>
			<li><a class="deli" href="http://del.icio.us/post?url=<?php echo $adv_this_url;?>&title=<?php echo $web_titulo;?>" target="_blank" title="Delicious"></a></li>
			<li><a class="link" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $adv_this_url;?>&title=<?php echo $web_titulo;?>&ro=false&summary=&source=" target="_blank" title="Linkedin"></a></li>
			<li><a class="stu" href="http://www.stumbleupon.com/submit?url=<?php echo $adv_this_url;?>&title=<?php echo $web_titulo;?>" target="_blank" title="Stumble Upon"></a></li>

			<li class="youtube"><a href="http://www.youtube.com/" target="_blank" title="Canal Youtube"></a></li>
		</ul>
		
	</div>
    
    <div class="clear"></div>
	
<?php } ?>