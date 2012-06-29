<?php
/**
 * Muestra el listado de los canales RSS del sitio
 */
?>
<article>
	
	<header>
		<h2 id="title">Canal Rss</h2>
	</header>
	
	<p class="legend">						
		RSS (en ingl�s "Really Simple Syndication") es una forma de leer p�ginas 
		web sin entrar en esas p�ginas. <br/>
		<strong>Esta p�gina</strong> ofrece a los usuarios el canal RSS 
		f�cilmente adaptable a sus necesidades e intereses, 
		de modo que puedan agregarlos en otras aplicaciones o sitios web.
	</p>

	<ul class="rss-links">

		<li>
			<span class="ico"></span>
			<span class="txt">Noticias:</span>
			<a href="<?php echo $urlRoot;?>/rss/noticias.xml"><?php echo $urlRoot;?>/rss/noticias.xml</a>
		</li>

		<li>
			<span class="ico"></span>
			<span class="txt">Noticias:</span>
			<a href="<?php echo $urlRoot;?>/rss/noticias.xml"><?php echo $urlRoot;?>/rss/noticias.xml</a>
		</li>

	</ul><!--fin /.rss links-->

	<section class="rss-txt">

		<h4 class="title2">MODO DE USO</h4>

		<p>Se accede a este servicio a trav�s de programas conocidos como 
			"Lectores de noticias", que organizan, actualizan y muestran 
			el contenido de los canales.</p>
		<br/>
		<p>Para agregar canales, se debe ingresar la url del canal deseado 
			en los programas lectores. Se pueden crear grupos de canales, 
			agrupando todas las secciones del diario bajo un mismo grupo.</p>
		<br/>
	
		<h4 class="title2">TIPOS DE LECTORES (RSS)</h4>

		<p><strong>Lectores RSS que se instalan directamente en la PC:</strong> 
		Son programas que se instalan en cada computadora. Cuando se tiene abierto
		este software, �ste accede cada cierto tiempo a las p�ginas web suscritas 
		para traer las actualizaciones directamente a la PC. 
		Algunos de los programas m�s populares son: 
		<a href="http://www.feedreader.com/" target="_blank" title="Feedreader">Feedreader</a>, 
		<a href="http://www.newsmonster.org/" title="Newsmonster" target="_blank">Newsmonster</a> 
		y <a href="http://www.rssreader.com/" title="RSSReader" target="_blank">RSSReader</a>.</p>
		<br />

		<p><strong>Lectores RSS online:</strong> Los lectores Rss online 
		cumplen la misma funci�n que los programas que se instalan en la computadora,
		aunque en �ste se hace todo a trav�s de una p�gina web. Para ello, 
		uno se tiene que registrar en la p�gina web que ofrece ese servicio y 
		tambi�n dar de alta un perfil. A partir de ese momento, se puede acceder 
		cuando se desee al lector web, introduciendo el nombre de usuario y 
		contrase�a elegidos. Algunos de los lectores online m�s populares y 
		conocidos son: <a href="http://www.netvibes.com/es" title="Netvibes" target="_blank">Netvibes</a>
		� <a href="http://www.google.com/reader" title="Google Reader" target="_blank">Google Reader</a>.</p>
		<br />

		<p><strong>Lectores RSS en tu navegador web o programa de correo electr�nico.</strong>
		Tambi�n se puede recibir las actualizaciones de las p�ginas web a trav�s 
		del navegador web o del programa de correo electr�nico. Algunos de los 
		navegadores y clientes de correo m�s conocidos que permiten hacer esto 
		son: <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx" title="Internet Explorer" target="_blank">Internet Explorer</a>, 
		<a href="http://www.mozilla-europe.org/es/firefox/" title="Mozilla Firefox" target="_blank">Mozilla Firefox</a>, 
		<a href="http://www.microsoft.com/downloads/en/details.aspx?FamilyID=56883de5-2024-4631-806e-757693072a1c" title="Outlook Express" target="_blank">Outlook Express</a> 
		o <a href="http://www.mozillamessaging.com/es-ES/thunderbird/" title="Mozilla Thunderbird" target="_blank">Mozilla Thunderbird</a>. 
		</p>

	</section><!--fin /.rss txt-->

	<ul class="more">
		<li><a href="http://www.youtube.com/watch?v=ZaiOQfYrvp8" title="Mas acerca de rss ... [+]" target="_blank" rel="nofollow">Mas acerca de rss<span> ... [+]</span></a></li>
	</ul>

</article><!--fin-->	