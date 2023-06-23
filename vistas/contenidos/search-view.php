<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-search zmdi-hc-fw"></i> BUSCAR LIBRO</h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>
<?php
	if(!isset($_SESSION['busqueda_libro']) && empty($_SESSION['busqueda_libro'])):
?>
<div class="container-fluid">
	<form action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" class="FormularioAjax well" data-form="default" method="POST" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="form-group label-floating">
					<span class="control-label">¿Qué libro estas buscando?</span>
					<input class="form-control" type="text" name="busqueda_inicial_libro" required="">
				</div>
			</div>
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-primary btn-raised btn-sm"><i class="zmdi zmdi-search"></i> &nbsp; Buscar</button>
				</p>
			</div>
		</div>
		<div class="RespuestaAjax"></div>
	</form>
</div>
<?php else: ?>
<div class="container-fluid">
	<form action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" class="FormularioAjax" data-form="search" method="POST" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<input class="form-control" type="hidden" name="eliminar_busqueda_libro" value="destruir">
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-danger btn-raised btn-sm"><i class="zmdi zmdi-delete"></i> &nbsp; Eliminar búsqueda</button>
				</p>
			</div>
		</div>
		<div class="RespuestaAjax"></div>
	</form>
</div>
<?php
		require_once "./controladores/libroControlador.php";
  		$insLibro= new libroControlador();

  		$pagina=explode("/", $_GET['views']);

  		echo $insLibro->catalogo_libro_controlador($pagina[1],15,0,$_SESSION['busqueda_libro'],$_SESSION['privilegio_sbp']);
	endif; 
?>