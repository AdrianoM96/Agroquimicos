<?php
	if($_SESSION['tipo_sbp']!="Administrador"){
		echo $lc->forzar_cierre_sesion_controlador();
		exit();
	}
?>	
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-labels zmdi-hc-fw"></i> Administración <small>CATEORÍAS</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>category/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVA CATEORÍA
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>categorylist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE CATEORÍAS
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>categorysearch/" class="btn btn-primary">
	  			<i class="zmdi zmdi-search"></i> &nbsp; BUSCAR CATEORÍA
	  		</a>
	  	</li>
	</ul>
</div>

<?php
	if(!isset($_SESSION['busqueda_categoria']) && empty($_SESSION['busqueda_categoria'])):
?>
<div class="container-fluid">
	<form action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" class="FormularioAjax well" data-form="default" method="POST" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<div class="col-xs-12 col-md-8 col-md-offset-2">
				<div class="form-group label-floating">
					<span class="control-label">¿Qué categoria estas buscando?</span>
					<input class="form-control" type="text" name="busqueda_inicial_categoria" required="">
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
	<form action="<?php echo SERVERURL; ?>ajax/buscadorAjax.php" class="FormularioAjax well" data-form="search" method="POST" enctype="multipart/form-data" autocomplete="off">
		<p class="lead text-center">Su última búsqueda  fue <strong>“<?php echo $_SESSION['busqueda_categoria']; ?>”</strong></p>
		<div class="row">
			<input class="form-control" type="hidden" name="eliminar_busqueda_categoria" value="destruir">
			<div class="col-xs-12">
				<p class="text-center">
					<button type="submit" class="btn btn-danger btn-raised btn-sm"><i class="zmdi zmdi-delete"></i> &nbsp; Eliminar búsqueda</button>
				</p>
			</div>
		</div>
		<div class="RespuestaAjax"></div>
	</form>
</div>

<!-- Panel listado de categorias -->
<div class="container-fluid">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-search"></i> &nbsp; BUSCAR CATEORÍA</h3>
		</div>
		<div class="panel-body">
			<?php
				require_once "./controladores/categoriaControlador.php";
				$insCat = new categoriaControlador();

				$pagina=explode("/", $_GET['views']);
				echo $insCat->paginador_categoria_controlador($pagina[1],15,$_SESSION['privilegio_sbp'],$_SESSION['busqueda_categoria']);
			?>
		</div>
	</div>
</div>
<?php endif; ?>