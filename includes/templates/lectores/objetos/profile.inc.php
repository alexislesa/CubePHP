<nav class="profile">

	<ul>
		<li><a href="/lectores/">Mi Cuenta</a></li>		
		<li><a href="/lectores/perfil.php">Editar Perfil</a></li>						
		<li><a href="/lectores/clave.php">Modificar Contraseña</a></li>		
		<li><a href="/lectores/avatar.php">Cambiar Avatar</a></li>						

		<li><a href="/lectores/noticia-add.php">Publicar Noticia</a></li>
		<li><a href="/lectores/noticia-list.php">Mis Noticias</a></li>

		<li><a href="/lectores/clasificado-add.php">Publicar Nuevo Aviso</a></li>
		<li><a href="/lectores/clasificado-list.php">Mis Avisos</a></li>

		<li><a href="/lectores/salir.php">Salir</a></li>
	</ul>				

	<?php if ($usr->campos['lector_perfil'] != '') { ?>
	
		<?php echo $usr->campos['lector_perfil'];?>
		
	<?php } else { ?>

		<a href="/lectores/perfil.php">Editar mi texto de presentación</a>
	
	<?php } ?>

</nav><!--fin profile-->