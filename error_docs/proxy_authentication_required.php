<?php 
/* Conector */
include ('../includes/comun/conector.inc.php');

$webTitulo = "Requiere autenticaci�n de proxy";
include('header.inc.php');?>

<div class="notfound">

	<h2 class="nota-title">Requiere autenticaci�n de Proxy</h2>

	
	<p>La p�gina a la que intenta ingresar requiere la autenticaci�n previa del servidor de proxy. Puede tratar de encontrar la p�gina accediendo a:</p>
	
	<ul>
		<li>P�gina de inicio de <?php echo $urlRoot;?>: <a href="/"><?php echo $urlRoot;?></a></li>
	
		<li>Secciones desde nuestro men� o desde nuestro pie de p�gina</li>
	
	</ul>

</div><!--fin not found-->

<?php include('bottom.inc.php');?>