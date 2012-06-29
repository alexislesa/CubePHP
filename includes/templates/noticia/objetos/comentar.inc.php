<?php
/**
 * Carga las opciones de Comentarios de un artículo
 * Muestra el bloque solo si esta habilitado para comentar
 */

// Comentario desde facebook
// include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-fb.inc.php");

// Comentario con plataforma propia de usuario registrados
// include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-registrados.inc.php");

// Comentario con plataforma propia de usuarios anónimos
include (dirTemplate."/{$pathRelative}/objetos/comentar-inc-sin-registro.inc.php");

// Listado de comentarios de los usuarios registrados o anónimos
include (dirTemplate."/{$pathRelative}/objetos/comentario.inc.php");
?>