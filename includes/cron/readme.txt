/**
 * Listado de comandos para tareas programadas
 * Informaci�n sugerida, provista para ejecutarse en plataformas con Plesk
 */

/* ********** Comandos para datafactory - f�tbol ********** */

Informaci�n:	Guarda los archivos XML de fixture en una carpeta de cache local
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/futbol-datafactory.php > /dev/null
Frecuencia:		0 [min]	2 [hor]	* [dia del mes]	* [mes]	* [dia de la semana]

Informaci�n:	Lee todos los xml de los partidos y guarda en base de datos los pr�ximos a jugarse
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/futbol-fixture-to-mam.php > /dev/null
Frecuencia:		0 [min]	4 [hor]	* [dia del mes]	* [mes]	* [dia de la semana]

Informaci�n:	Lee de la base de datos todos los partidos que se estan jugando actualmente y levanta los XML de esos partidos
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/futbol-mam_db.php > /dev/null
Frecuencia:		* [min]	* [hor]	* [dia del mes]	* [mes]	* [dia de la semana]


/* ********** Comandos para datafactory - tenis ********** */

Informaci�n:	Guarda los archivos XML de fixture en una carpeta de cache local
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/tenis-datafactory.php > /dev/null
Frecuencia:		0 [min]	2 [hor]	* [dia del mes]	* [mes]	* [dia de la semana]

Informaci�n:	Lee todos los xml de los partidos y guarda en base de datos los pr�ximos a jugarse
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/tenis-fixture-to-mam.php > /dev/null
Frecuencia:		0 [min]	4 [hor]	* [dia del mes]	* [mes]	* [dia de la semana]

Informaci�n:	Lee de la base de datos todos los partidos que se estan jugando actualmente y levanta los XML de esos partidos
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/tenis-mam_db.php > /dev/null
Frecuencia:		* [min]	* [hor]	* [dia del mes]	* [mes]	* [dia de la semana]


/* ********** Comandos para informaci�n de los seguidores ********** */

Informaci�n:	Recupera informaci�n de los seguidores en FB y TW
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/usuarios-top.inc.php > /dev/null
Frecuencia:		0 [min]	0 [hor]	* [dia del mes]	* [mes]	* [dia de la semana]


/* ********** Comandos para recuperar informaci�n del clima v�a Yahoo Wheater ********** */

Informaci�n:	Recupera informaci�n del clima de las diferentes localidades de Entre R�os v�a Yahoo wheater
Comando: 		/usr/local/bin/lynx -dump http://[www.sitio.com.ar]/includes/cron/clima-yahoo.php > /dev/null
Frecuencia:		*/4 [min]	* [hor]	* [dia del mes]	* [mes]	* [dia de la semana]