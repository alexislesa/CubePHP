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
 * Muestra un reproductor de vivo devuelto por el artículo
 * 
 * @changelog:
 */
if (!empty($v["vivo"])) {
	$m = $v["vivo"][1] ; ?>

	<section id="stream" class="article-block">			
	
		<div class="stream">

			<div id='videovivo'>
						
				<object width="640" height="361" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
					<param name="flashvars" value=""/>
					<param name="allowfullscreen" value="true"/>
					<param name="allowscriptaccess" value="always"/>
					<param name="src" value="<?php echo $m["extra"]["link_embed"];?>"/>
					<embed flashvars="" width="640" height="361" allowfullscreen="true" allowscriptaccess="always" src="<?php echo $m["extra"]["link_embed"];?>" type="application/x-shockwave-flash"></embed>
				</object>

			</div>

		
		</div><!--/.stream-->
		
		<?php /* Muestra el nombre o titulo del video*/
			if ($m["gal_nombre"] != "") { ?>
		
			<div class="stream-pie">
				<strong>En vivo:</strong> <?php echo $m["gal_nombre"];?>
			</div>

		<?php } ?>

	</section><!--fin /stream-->
<?php } ?>