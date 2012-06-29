<section id="newsletter" class="block">
    
    <h4 class="shad-txt">Newsletter</h4>

    <div class="inner">
        
        <div class="form-news">

            <h5>Suscribite y recibí nuestra ofertas y novedades...</h5>
            
			<div class="begin">

				<form id="form-newsletter" action="?">
					<div>
						<label>Nombre:</label>
						<input type="text" class="item-txt" name="nombre" maxlength="35" autocomplete="off" />
					</div>
					<div>
						<label>E-mail:</label>
						<input type="text" class="item-txt" name="email" maxlength="120" autocomplete="off" />
					</div>
					
					<input type="submit" value="Suscribirse" class="btn-o submit" />
				</form>
			</div>
            
            <div class="ready" >
                <p>Ahora estas suscripto al Newsletter</p>
                <span class="btn-b">Gracias</span>
            </div>
            
        </div><!--/.form-news-->

    </div><!--/.inner-->

</section>

<script type="text/javascript">
	var checknews = [];
	checknews.push("required,nombre,Ingrese su nombre"); 
	checknews.push("length=0-35,nombre,El nombre no puede superar los 35 caracteres"); 
	checknews.push("required,email,Ingrese su email"); 
	checknews.push("valid_email,email,Ingrese un email válido"); 

	$(document).ready(function() {
		$("#form-newsletter").RSV({
			onCompleteHandler: myOnCompleteNewsletter,
			displayType: "alert-one",
			rules: checknews		
		});
	});	
	

	function myOnCompleteNewsletter() {

		$.ajax({
			type: "POST",
			async: false,
			url: "/extras/newsletter/index.php?act=true",
			data: $("#form-newsletter").serialize(),
			success: function(msg){
				if (msg != "ok") {
					alert(msg);
					return false;
				}

				$(".form-news .begin").hide();
				$(".form-news .ready").fadeIn("fast");
			}
		});	
	
		return false;
	}
</script>