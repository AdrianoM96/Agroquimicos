<?php
	if($_SESSION['tipo_sbp']!="Administrador" || ($_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2)){
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
	</ul>
</div>
<?php
	$datos=explode("/", $_GET['views']);

	require_once "./controladores/categoriaControlador.php";
	$insCat = new categoriaControlador();

	$filesCat=$insCat->datos_categoria_controlador("Unico",$datos[1]);

	if($filesCat->rowCount()==1):
		$campos=$filesCat->fetch();
?>
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> &nbsp; NUEVA CATEORÍA</h3>
		</div>
		<div class="panel-body">
			<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/categoriaAjax.php" data-form="update" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" value="<?php echo $datos[1]; ?>" name="id-up">
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Información de la categoría</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Código *</label>
								  	<input pattern="[0-9]{1,7}" class="form-control" type="text" name="codigo-up" value="<?php echo $campos['CategoriaCodigo']; ?>" required="" maxlength="7">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Nombre *</label>
								  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="nombre-up" value="<?php echo $campos['CategoriaNombre']; ?>" required="" maxlength="30">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
			    <p class="text-center" style="margin-top: 20px;">
			    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> Actualizar</button>
			    </p>
			    <div class="RespuestaAjax"></div>
		    </form>
		</div>
	</div>
</div>
<?php else: ?>
<div class="alert alert-dismissible alert-warning text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i>
	<h4>¡Lo sentimos!</h4>
	<p>No podemos mostrar la información de la categoría debido a un error</p>
</div>
<?php endif; ?>