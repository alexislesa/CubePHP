<?php
$tipoFechaHoy = '%j% de %M% de %Y%'; 
$fechaHoy = formatDate($tipoFechaHoy);

$tipoHoraHoy = '%G%:%i% Hs'; 
$horaHoy = formatDate($tipoHoraHoy);
?>
<div class="search">

	<form name='buscador2' action="/extras/buscador/index.php" id="buscador-top" method="get">
		<input type='text' name='q' value="" id="buscador-bottom" title="Buscar..." maxlength='30' class="txt"/> 
		<input type='submit' name='enviar' value='Buscar' title="Buscar" class="bt"/>
	</form>

	<script type="text/javascript">
		$("#buscador-top > input").each(function() {
			if ($(this).attr("title") != "") {
				$(this).fieldtag();
			}
		});

		$("#buscador-top").submit(function() {
			if ($(this).find("input:first").val().length < 4) {
				alert("Debe ingresar un texto de al menos 4 caracteres");
				return false;
			}
		});
	</script>
	
	<div class="fecha">

		<span class="date"><?php echo $fechaHoy;?></span>
		<span>|</span>
		<span class="time">Hora <?php echo $horaHoy;?></span>

	</div>

</div>	<!--fin search-->