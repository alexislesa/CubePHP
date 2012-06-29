<div title="Compartir" class="advthis" onmouseout="this.className='advthis'" onmouseover="this.className='advthis showme'">

	<span class="cmp"><span class="compartir"></span>Compartir</span>

	<div><!--contenedora de links sociales-->
		<ul>			
			<li class="pl"><a href="https://plus.google.com/share?url=<?php echo $advThisUrl;?>&t=<?php echo $webTitulo;?>" target="_blank" title="Google Plus">Google Plus</a></li>
			<li class="bz"><a href="http://www.google.com/buzz/post" target="_blank" title="Buzz">Buzz</a></li>
			<li class="pi"><span class="ico"></span><a href="http://pinterest.com/pin/create/button/?url=<?php echo $advThisUrl;?>&media=<?php echo $webImagen;?>&description=<?php echo $webTitulo;?>" target="_blank">Pin It</a></li>
			<li class="fb"><a href="http://www.facebook.com/share.php?u=<?php echo $advThisUrl;?>" target="_blank" title="Facebook">Facebook</a></li>
			<li class="tw"><a href="http://twitthis.com/twit?url=<?php echo $advThisUrl;?>" target="_blank" title="Twitter">Twitter</a></li>
			<li class="gm"><a href="https://mail.google.com/mail/?view=cm&fs=1&to&su=&body=<?php echo $advThisUrl;?>&ui=2&tf=1&shva=1" title="Gmail" target="_blank">GMail</a></li>			
			<li class="yh"><a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo $advThisUrl;?>" title="Yahoo!" target="_blank">Yahoo!</a></li>
			<li class="del"><a href="http://del.icio.us/post?url=<?php echo $advThisUrl;?>&title=<?php echo $webTitulo;?>" target="_blank" title="Delicious">Delicious</a></li>
			<li class="lk"><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $advThisUrl;?>&title=<?php echo $webTitulo;?>&ro=false&summary=&source=" target="_blank" title="Linkedin">Linkedin</a></li>
			<li class="lv"><a href="http://www.live.com/?add=<?php echo $advThisUrl;?>" title="Live" target="_blank">Live</a></li>
			<li class="su"><a href="http://www.stumbleupon.com/submit?url=<?php echo $advThisUrl;?>&title=<?php echo $webTitulo;?>" target="_blank" title="Stumble Upon">Stumble Upon</a></li>
			<li class="tc"><a href="http://technorati.com/faves?sub=favthis&add=<?php echo $advThisUrl;?>" target="_blank" title="Technorati">Technorati</a></li>	
			<li class="dg"><a href="http://digg.com/submit?partner=addthis&url=<?php echo $advThisUrl;?>&title=<?php echo $webTitulo;?>&bodytext=" target="_blank" title="Digg">Digg</a></li>
			<li class="rd"><a href="http://reddit.com/submit?url=<?php echo $advThisUrl;?>&title=<?php echo $webTitulo;?>" target="_blank" title="Reedit">Reedit</a></li>
			<li class="mx"><a href="http://www.mixx.com/submit?page_url=<?php echo $advThisUrl;?>" target="_blank" title="Mixx">Mixx</a></li>
			<li class="nv"><a href="http://www.newsvine.com/_tools/seed&save?u=<?php echo $advThisUrl;?>&h=<?php echo $webTitulo;?>&s=" target="_blank" title="Newsvine">Newsvine</a></li>
			<li class="ms"><a href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo $advThisUrl;?>" target="_blank" title="My Space">My Space</a></li>
			<li class="ok"><a href="http://promote.orkut.com/preview?nt=orkut.com&tt=<?php echo $webTitulo;?>&du=<?php echo $advThisUrl;?>&cn=" target="_blank" title="Orkut">Orkut</a></li>
			<li class="nt"><a href="http://www.netvibes.com/subscribe.php?module=UWA&moduleUrl=" target="_blank" title="Netvibes">Netvibes</a></li>
			<li class="fq"><a href="http://tec.fresqui.com/post?url=<?php echo $advThisUrl;?>" title="Fresqui" target="_blank">Fresqui</a></li>
			<li class="sn"><a href="http://www.sonico.com/share.php?url=<?=$adv_this_url?>" onclick="return sonico_share()" title="Sonico">Sonico</a></li>
			<li class="tb"><a href="http://www.tumblr.com/share?v=3&u=<?=$adv_this_url?>&t=<?php echo $webTitulo;?>&s=" target="_blank" title="Tumblr">Tumblr</a></li>
			<li class="mn"><a href="http://meneame.net/submit.php?url=<?php echo $advThisUrl;?>" title="Meneame" target="_blank">Meneame</a></li>
			<li class="fv"><a href="#" onclick="agregarfav();" title="Favoritos">Favoritos</a></li>
		</ul>
	</div>
	
</div>

<script language='JavaScript' type='text/javascript'>
function sonico_share() {
	loc=location.href;
	title=document.title;
	window.open('http://www.sonico.com/share.php?title='+encodeURIComponent(title)+'&url='+encodeURIComponent(loc),'share','toolbar=0,status=0,width=626,height=436');
	return false;
}

function agregarfav() {
	var title = document.title;
	var url = "<?=$adv_this_url_clean;?>";
	if (window.sidebar) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(title, url,"");
		return false;
	} else {
		if ( window.external ) { // IE Favorite
			window.external.AddFavorite( url, title); 
			return false;
		} else {
			if (window.opera && window.print) { // Opera Hotlist
				alert("Presione 'Ctrl-D' en Firefox o 'Ctrl-T' en Opera para agregar la página a favoritos");
				return true;
			}
		}
	}
	return false;
}
</script>