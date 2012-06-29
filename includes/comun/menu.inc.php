<?php
/** 
 * Inicializo la clase con el item por defecto
 * Uso: $item_menu = new item_menu([$pagina_actual],[$home]);
 * Si se utiliza con el 3er parametro $home=true, indica que si no hay ninguno activo define a inicio como activo
 * Es decir se activa el home
 */
$oMenu = isset($pagActualClass[0]) ? $pagActualClass[0] : '';
$itemMenu = new Menu($oMenu);

$oMenu = isset($pagActualClass[1]) ? $pagActualClass[1] : '';
$itemMenu2 = new Menu($oMenu);
?>

<nav role="navigation" id="nav">

	<ul>
		<li class="<?php echo $itemMenu->inicio;?>"><a href="/" title="Inicio">Inicio</a></li>
		<li class="<?php echo $itemMenu->noticias;?>"><a href="/noticias" title="Noticias">Noticias</a></li>
		<li class="<?php echo $itemMenu->lectores;?>"><a href="/lectores" title="Lectores">Lectores</a></li>
		<li class="<?php echo $itemMenu->institucional;?>"><a href="/institucional" title="Quienes Somos">Quienes Somos</a></li>
		<li class="<?php echo $itemMenu->rss;?>"><a href="/institucional/rss.php" title="Rss">Rss</a></li>
		<li class="<?php echo $itemMenu->contacto;?>"><a href="/institucional/contacto.php" title="Contactos">Contactos</a></li> 
	</ul>

</nav><!--fin menu-->