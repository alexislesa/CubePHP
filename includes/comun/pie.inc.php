<footer id="footer">

	<a class="subir" href="#top" title="Subir" rel="nofollow"><span class="ico"></span>Subir</a>

	<script type="text/javascript">
	$("a.subir").click(function() {
		$("html, body").animate({scrollTop:0}, "slow");
		return false;
	});
	</script>

	<div class="inner">

		<address class="vcard" id="hcard-website">

			<div class="adr">
				<span class="street-address">calle y nro</span>
				<span class="locality">ciudad</span> 
				<span class="region">provincia</span> 
				<span class="postal-code">cod postal</span> 
				<span class="country-name">pais</span> 
				<span class="tel">telefono</span>
				<span class="url">url</span>				
				<span class="email">email</span>
			</div><!--fin /.adr-->

		</adreess><!--fin /.vcard-->

	</div><!--fin /.inner-->
	
	<section id="clousure">
		
		<a href="/" title="Ir al Inicio" class="url fn"><?php echo $ss_config['site_name'];?></a>
		<span>© Copyright <?php echo date('Y');?>  -  Todos los derechos reservados</span>
		
		<a class="adv" href="http://www.advertis.com.ar" title="Creado por Advertis Web Factory" target="_blank">advertis</a>
		
	</section><!--fin /section-->

</footer><!--fin /footer-->


<script type="text/javascript">
/** Modifica los Radio y checkbok del sitio */
/*
$(document).ready(function(){
	$(".radio").dgStyle();
	$(".checkbox").dgStyle();
});
*/
</script>

<script type="text/javascript">
	$('.sidebar .block').last().css({'margin-bottom': '0', 'padding-bottom': '0', 'border-bottom': '0'});
	$('.col-1 .homeblock').last().css({'margin-bottom': '0', 'padding-bottom': '0', 'border-bottom': '0'});
	$('.col-2 .homeblock').last().css({'margin-bottom': '0', 'padding-bottom': '0', 'border-bottom': '0'});
</script>