<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "No aceptable";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">No aceptable</h2>

	<p>La p�gina solicitada posee caracter�sticas de contenidos que no son aceptables. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>