<?php
	if($_SESSION['tipo_sbp']!="Administrador" || ($_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2)){
		echo $lc->forzar_cierre_sesion_controlador();
		exit();
	}
?>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-balance zmdi-hc-fw"></i> Administración <small>EMPRESA</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<div class="container-fluid">
	<ul class="breadcrumb breadcrumb-tabs">
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>company/" class="btn btn-info">
	  			<i class="zmdi zmdi-plus"></i> &nbsp; NUEVA EMPRESA
	  		</a>
	  	</li>
	  	<li>
	  		<a href="<?php echo SERVERURL; ?>companylist/" class="btn btn-success">
	  			<i class="zmdi zmdi-format-list-bulleted"></i> &nbsp; LISTA DE EMPRESAS
	  		</a>
	  	</li>
	</ul>
</div>

<?php
	require_once "./controladores/empresaControlador.php";
	$insEm = new empresaControlador();

	$datos=explode("/", $_GET['views']);

	$filesEm=$insEm->datos_empresa_controlador("Unico",$datos[1]);

	if($filesEm->rowCount()==1):
		$campos=$filesEm->fetch();
?>
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> &nbsp; ACTUALIZAR DATOS DE EMPRESA</h3>
		</div>
		<div class="panel-body">
			<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/empresaAjax.php" data-form="update" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" name="codigo" value="<?php echo $datos[1]; ?>">
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-assignment"></i> &nbsp; Datos básicos</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">DNI/CÓDIGO/NÚMERO DE REGISTRO *</label>
								  	<input pattern="[0-9-]{1,30}" class="form-control" value="<?php echo $campos['EmpresaCodigo']; ?>" type="text" name="dni-up" required="" maxlength="30">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Nombre de la empresa *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" value="<?php echo $campos['EmpresaNombre']; ?>" type="text" name="nombre-up" required="" maxlength="40">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Teléfono</label>
								  	<input pattern="[0-9+]{1,15}" class="form-control" value="<?php echo $campos['EmpresaTelefono']; ?>" type="text" name="telefono-up" maxlength="15">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">E-mail</label>
								  	<input class="form-control" value="<?php echo $campos['EmpresaEmail']; ?>" type="email" name="email-up" maxlength="50">
								</div>
		    				</div>
		    				<div class="col-xs-12">
								<div class="form-group label-floating">
								  	<label class="control-label">Dirección</label>
								  	<input class="form-control" value="<?php echo $campos['EmpresaDireccion']; ?>" type="text" name="direccion-up" maxlength="170">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
		    	<fieldset>
		    		<legend><i class="zmdi zmdi-assignment-o"></i> &nbsp; Otros datos</legend>
		    		<div class="container-fluid">
		    			<div class="row">
		    				<div class="col-xs-12">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Nombre del gerente o director *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,50}" class="form-control" value="<?php echo $campos['EmpresaDirector']; ?>" type="text" name="director-up" required="" maxlength="50">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Símbolo de moneda *</label>
								  	<input class="form-control" value="<?php echo $campos['EmpresaMoneda']; ?>" type="text" name="moneda-up" required="" maxlength="1">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
					    		<div class="form-group label-floating">
								  	<label class="control-label">Año *</label>
								  	<input pattern="[0-9]{4,4}" class="form-control" value="<?php echo $campos['EmpresaYear']; ?>" type="text" name="year-up" required="" maxlength="4">
								</div>
		    				</div>
		    			</div>
		    		</div>
		    	</fieldset>
		    	<br>
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
	<p>No podemos mostrar la información de la empresa debido a un error</p>
</div>
<?php endif; ?>