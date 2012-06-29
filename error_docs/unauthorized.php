<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "No Autorizado";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">No Autorizado</h2>

	
	<p>La página que intenta acceder requiere autorización. Puede tratar de encontrar la página accediendo a:</p>
	
	<ul>
		<li>Página de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro menú o desde nuestro pie de página</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>
