<div id="page">
	
	<?php include (dirTemplate.'/herramientas/breadcrumb.inc.php'); ?>

	<div id="container">
		
		<div id="main" class="column">

			<div id="main-squeeze">

				<div id="content" role="main">

					<div id="content-content">
						
						<?php 
						if (!empty($incBody) && file_exists($incBody)) {
							include($incBody);
						} ?>

					</div> <!-- /content-content -->
			
				</div> <!-- /content -->

			</div>
		</div> <!-- /main-squeeze /main -->

		<div class="clear"></div>

	</div><!--fin /container-->

</div><!--fin /page-->