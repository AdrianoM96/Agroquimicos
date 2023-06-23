<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-book zmdi-hc-fw"></i> INFORMACIÓN LIBRO</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<!-- Panel info libro -->
<div class="container-fluid">
	<?php
		$pagina=explode("/", $_GET['views']);

		require_once "./controladores/libroControlador.php";
  		$insLibro= new libroControlador();

  		$SeLibro=$insLibro->datos_libro_controlador("Unico",$pagina[1]);

  		if($SeLibro->rowCount()==1){
  			$datosLibro=$SeLibro->fetch();
	?>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-info"></i> &nbsp; DATOS DEL LIBRO</h3>
		</div>
		<div class="panel-body">
			<fieldset>
				<legend><i class="zmdi zmdi-library"></i> &nbsp; <?php echo $datosLibro['LibroTitulo']; ?></legend>
				<div class="container-fluid">
					<div class="row">
	    				<div class="col-xs-12 col-sm-6">
					    	<img src="<?php echo SERVERURL; ?>adjuntos/img/<?php echo $datosLibro['LibroImagen']; ?>" alt="<?php echo $datosLibro['LibroTitulo']; ?>" class="img-responsive">
	    				</div>
	    				<div class="col-xs-12 col-sm-6">
					    	<div class="container-fluid">
					    		<div class="row">
				    				<div class="col-xs-12">
								    	<div class="form-group label-floating">
										  	<span>Autor</span>
										  	<input class="form-control" value="<?php echo $datosLibro['LibroAutor']; ?>" readonly="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span>País</span>
										  	<input class="form-control" value="<?php echo $datosLibro['LibroPais']; ?>" readonly="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span>Año</span>
										  	<input class="form-control" value="<?php echo $datosLibro['LibroYear']; ?>" readonly="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span>Editorial</span>
										  	<input class="form-control" value="<?php echo $datosLibro['LibroEditorial']; ?>" readonly="">
										</div>
				    				</div>
				    				<div class="col-xs-12 col-sm-6">
								    	<div class="form-group label-floating">
										  	<span>Edición</span>
										  	<input class="form-control" value="<?php echo $datosLibro['LibroEdicion']; ?>" readonly="">
										</div>
				    				</div>
					    		</div>
					    	</div>
	    				</div>
					</div>
				</div>
			</fieldset>
			<br>
			<fieldset>
				<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Resumen del libro</legend>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group label-floating">
							  	<span>Resumen</span>
							  	<textarea readonly="" class="form-control" rows="3"><?php echo $datosLibro['LibroResumen']; ?></textarea>
							</div>
	    				</div>
					</div>
				</div>
			</fieldset>
			<?php if(is_file("./adjuntos/pdf/".$datosLibro['LibroPDF']) && $datosLibro['LibroDescarga']=="Si"){ ?>
			<br>
			<fieldset>
				<legend><i class="zmdi zmdi-download"></i> &nbsp; Descargar archivo PDF</legend>
				<p class="text-center">
					<a href="<?php echo SERVERURL."adjuntos/pdf/".$datosLibro['LibroPDF']; ?>" download="<?php echo $datosLibro['LibroTitulo']; ?>" class="btn btn-raised btn-primary">
					<i class="zmdi zmdi-cloud-download"></i> &nbsp; DESCARGAR PDF
					</a>
				</p>
			</fieldset>
			<?php } ?>
		</div>
	</div>
	<?php }else{ ?>
	<p class="text-center" style="font-size: 300px; "><i class="zmdi zmdi-mood-bad"></i></p>
	<h2 class="text-titles text-center">No hemos encontrado datos del libro</h2>
	<?php } ?>
</div>