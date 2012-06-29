<?php
// Cargo información del clima desde Yahoo
$dataClimaDefault = 466869;	// Id para Paraná
$dataClimaId = !empty($_COOKIE['dataclima']) ? $_COOKIE['dataclima'] : $dataClimaDefault;

$climaArr = climaYahoo(dirPath.'/includes/cache/clima-'.$dataClimaId.'.xml');
?>
<div class="date-time">
	<div class="tiempo">
		<img src="/images/clima/<?php echo $climaArr['img'];?>.gif" width="20" height="20" alt="<?php echo $climaArr['img'];?>" />
		
		<span class="clima-temp"><?php echo $climaArr['temp'];?>°C</span>
		<span class="localidad"><?php echo $climaArr['location'];?></span>

		<a href="#" title="Más Información">Más información</a>
	</div><!--fin tiempo-->
</div>

<style>
.clima-content {display:none;}
#clima-pop {background:#CCC; padding:15px;} 
</style>

<div class="clima-content">
	
	<div id="clima-pop">

		<div class="popclima">

			<a href="#" rel="cerrar" class="clima-close">[X]</a> 

			<div>
				<h5>Personalizar</h5>
				
				<span class="txt">Elegí otra ciudad para mostrar</span>
				
				<span class="ico"></span>
				
				<select name="clima-sel" class="clima-sel">
					<option value="466869">Seleccioná una localidad</option>
					<option value="464562">Aldea Protestantes</option>
					<option value="464565">Aldea san gregorio</option>
					<option value="464566">Aldea San José</option>
					<option value="466904">Basavilbaso</option>
					<option value="467164">Ceibas</option>
					<option value="466925">Chajarí</option>
					<option value="467583">Clara</option>
					<option value="466935">Colón</option>
					<option value="332483">Bovril</option>
					<option value="332485">Concepción del Uruguay</option>
					<option value="332475">Concordia</option>
					<option value="467597">Conscripto Bernardi</option>
					<option value="465275">Cuchilla Redonda</option>
					<option value="465283">Curtiembre</option>
					<option value="466950">Diamante</option>
					<option value="332743">El Redomón</option>
					<option value="467631">Enrique Carbó</option>
					<option value="465391">Federación</option>
					<option value="466959">Federal</option>
					<option value="465442">General Campos</option>
					<option value="467659">General Galarza</option>
					<option value="466979">Gualeguay</option>
					<option value="332486">Gualeguaychú</option>
					<option value="330739">Ibicuy</option>
					<option value="467756">Lucas González</option>
					<option value="465739">Maciá</option>
					<option value="467015">Nogoyá</option>
					<option value="466869">Paraná</option>
					<option value="466116">Piedras Blancas</option>
					<option value="332898">San Salvador</option>
					<option value="466469">Seguí</option>
					<option value="466411">Tala</option>
					<option value="20071076">Uruguay</option>
					<option value="332523">Victoria</option>
					<option value="467957">Villa Hernandarias</option>
					<option value="469006">Villa María Grande</option>
					<option value="466771">Villa Paranacito</option>
					<option value="332524">Villaguay</option>
				</select>

				<a href="#" class="climachange">Cambiar</a>
			</div>
		</div>
	</div>
</div> 

<script type="text/javascript">
function cambiarClima(clima_id) {
	var climaUrl = '/extras/js/clima.php';
	$.getJSON(climaUrl, function(json) {
	
		$.each(json, function(key, val) {

			if (key == clima_id) {
				$(".date-time .localidad").html(val.localidad);
				$(".date-time .clima-temp").html(val.temp + " °C");
				$(".date-time img").attr("src", "/images/clima/" + val.codigo + ".gif");
			}

		});
	});
}

$(".date-time a").click(function() {
	$.colorbox({inline:true, href:"#clima-pop", width:"428px", height:"228px"});
	return false;
});

$("#clima-pop a").click(function() {
	if ($(this).attr("rel") != "cerrar") {
		var v = $(".clima-sel").val();
		$.cookie("dataclima", v, { expires:15, path:'/' });
		cambiarClima(v);
	}
	$.colorbox.close();
	return false;
});

$(document).ready(function(){
	if ($.cookie("dataclima")) {
		climaId = $.cookie("dataclima");
		cambiarClima(climaId);
	}
});
</script>