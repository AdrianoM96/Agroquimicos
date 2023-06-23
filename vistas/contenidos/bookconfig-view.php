<?php
	if($_SESSION['tipo_sbp']!="Administrador" || ($_SESSION['privilegio_sbp']<1 || $_SESSION['privilegio_sbp']>2)){
		echo $lc->forzar_cierre_sesion_controlador();
		exit();
	}
?>	
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-wrench zmdi-hc-fw"></i> GESTIÓN DE LIBRO</small></h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>

<?php 
$pagina=explode("/", $_GET['views']);

require_once "./controladores/libroControlador.php";
$insLibro= new libroControlador();

$SeLibro=$insLibro->datos_libro_controlador("Unico",$pagina[1]);
if($SeLibro->rowCount()==1){
	$datosLibro=$SeLibro->fetch(); 
?>
<div class="container-fluid">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-refresh"></i> &nbsp; ACTUALIZAR LIBRO</h3>
		</div>
		<div class="panel-body">
			<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="update" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" value="<?php echo $pagina[1]; ?>" name="id-up" >
				<fieldset>
					<legend><i class="zmdi zmdi-library"></i> &nbsp; Información básica</legend>
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Código de libro *</label>
								  	<input pattern="[a-zA-Z0-9-]{1,30}" class="form-control" type="text" name="codigo-up" value="<?php echo $datosLibro['LibroCodigo']; ?>" required="" maxlength="30">
								</div>
		    				</div>
							<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Título *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="titulo-up" value="<?php echo $datosLibro['LibroTitulo']; ?>" required="" maxlength="30">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Autor *</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="autor-up" value="<?php echo $datosLibro['LibroAutor']; ?>" required="" maxlength="30">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">País</label>
								  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="pais-up" value="<?php echo $datosLibro['LibroPais']; ?>" maxlength="30">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Año</label>
								  	<input pattern="[0-9]{1,4}" class="form-control" type="text" name="year-up" value="<?php echo $datosLibro['LibroYear']; ?>" maxlength="4">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Editorial</label>
								  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="editorial-up" value="<?php echo $datosLibro['LibroEditorial']; ?>" maxlength="30">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Edición</label>
								  	<input pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="edicion-up" value="<?php echo $datosLibro['LibroEdicion']; ?>" maxlength="30">
								</div>
		    				</div>
						</div>
					</div>
				</fieldset>
				<br>
				<fieldset>
					<legend><i class="zmdi zmdi-labels"></i> &nbsp; Empresa, Categoría y Proveedor</legend>
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Empresa</label>
								  	<select name="empresa-up" class="form-control">
								  		<?php 
								  			require_once "./controladores/empresaControlador.php";
								  			$insEm= new empresaControlador();

								  			$dataE=$insEm->datos_empresa_controlador("Select",0);
								  			

								  			while($rowE=$dataE->fetch()){
								  				if($datosLibro['EmpresaCodigo']==$rowE['EmpresaCodigo']){
								  					echo '<option value="'.$lc->encryption($rowE['EmpresaCodigo']).'" selected="" >'.$rowE['EmpresaNombre'].' (Actual)</option>';
								  				}else{
								  					echo '<option value="'.$lc->encryption($rowE['EmpresaCodigo']).'">'.$rowE['EmpresaNombre'].'</option>';
								  				}
								  			}
								  		?>
							        </select>
								</div>
		    				</div>
							<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Categoría</label>
								  	<select name="categoria-up" class="form-control">
							          	<?php 
								  			require_once "./controladores/categoriaControlador.php";
								  			$insCat= new categoriaControlador();

								  			$dataCat=$insCat->datos_categoria_controlador("Select",0);
								  			

								  			while($rowCat=$dataCat->fetch()){
								  				if($datosLibro['CategoriaCodigo']==$rowCat['CategoriaCodigo']){
								  					echo '<option value="'.$lc->encryption($rowCat['CategoriaCodigo']).'" selected="" >'.$rowCat['CategoriaNombre'].' (Actual)</option>';
								  				}else{
								  					echo '<option value="'.$lc->encryption($rowCat['CategoriaCodigo']).'">'.$rowCat['CategoriaNombre'].'</option>';
								  				}
								  			}
								  		?>
							        </select>
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
								<div class="form-group label-floating">
								  	<label class="control-label">Proveedor</label>
								  	<select name="proveedor-up" class="form-control">
							          	<?php 
								  			require_once "./controladores/proveedorControlador.php";
								  			$insProv= new proveedorControlador();

								  			$dataProv=$insProv->datos_proveedor_controlador("Select",0);
								  			

								  			while($rowProv=$dataProv->fetch()){
								  				if($datosLibro['ProveedorCodigo']==$rowProv['ProveedorCodigo']){
								  					echo '<option value="'.$lc->encryption($rowProv['ProveedorCodigo']).'" selected="" >'.$rowProv['ProveedorNombre'].' (Actual)</option>';
								  				}else{
								  					echo '<option value="'.$lc->encryption($rowProv['ProveedorCodigo']).'">'.$rowProv['ProveedorNombre'].'</option>';
								  				}
								  			}
								  		?>
							        </select>
								</div>
		    				</div>
						</div>
					</div>
				</fieldset>
				<br>
				<fieldset>
					<legend><i class="zmdi zmdi-money-box"></i> &nbsp; Precio, Ejemplares y Ubicación</legend>
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Precio</label>
								  	<input pattern="[0-9.]{1,7}" class="form-control" type="text" name="precio-up" value="<?php echo $datosLibro['LibroPrecio']; ?>" maxlength="7">
								</div>
		    				</div>
		    				<div class="col-xs-12 col-sm-6">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Ejemplares</label>
								  	<input pattern="[0-9]{1,3}" class="form-control" type="text" name="ejemplares-up" value="<?php echo $datosLibro['LibroStock']; ?>" maxlength="3">
								</div>
		    				</div>
		    				<div class="col-xs-12">
						    	<div class="form-group label-floating">
								  	<label class="control-label">Ubicación</label>
								  	<input pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" type="text" name="ubicacion-up" value="<?php echo $datosLibro['LibroUbicacion']; ?>" maxlength="30">
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
								  	<label class="control-label">Resumen</label>
								  	<textarea name="resumen-up" class="form-control" rows="3"><?php echo $datosLibro['LibroResumen']; ?></textarea>
								</div>
		    				</div>
						</div>
					</div>
				</fieldset>
				<br>
				<fieldset>
    				<div class="col-xs-12 col-sm-6">
						<div class="form-group">
							<label class="control-label">¿El archivo PDF será descargable para los clientes?</label>
							<div class="radio radio-primary">
								<label>
									<input type="radio" name="optionsPDF" id="optionsRadios1" value="Si" <?php if($datosLibro['LibroDescarga']=="Si"){ echo 'checked=""'; } ?> >
									<i class="zmdi zmdi-cloud-download"></i> &nbsp; Si, PDF descargable
								</label>
							</div>
							<div class="radio radio-primary">
								<label>
									<input type="radio" name="optionsPDF" id="optionsRadios2" value="No" <?php if($datosLibro['LibroDescarga']=="No"){ echo 'checked=""'; } ?> >
									<i class="zmdi zmdi-cloud-off"></i> &nbsp; No, PDF no descargable
								</label>
							</div>
						</div>
    				</div>
				</fieldset>
				<p class="text-center" style="margin-top: 20px;">
			    	<button type="submit" class="btn btn-success btn-raised btn-sm"><i class="zmdi zmdi-refresh"></i> &nbsp; Actualizar</button>
			    </p>
			    <div class="RespuestaAjax"></div>
			</form>
		</div>
	</div>
</div>


<div class="container-fluid">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-attachment-alt"></i> &nbsp; AGREGAR O ACTUALIZAR ADJUNTOS</h3>
		</div>
		<div class="panel-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
					<?php if($datosLibro['LibroImagen']=="" && !is_file("./adjuntos/img/".$datosLibro['LibroImagen'])){ ?>

						<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="update" enctype="multipart/form-data" autocomplete="off">
							<input type="hidden" name="adjunto-up-id" value="<?php echo $pagina[1]; ?>">
							<input type="hidden" name="adjunto-up-tipo" value="img">
							<div class="form-group">
	    						<span class="control-label">Imágen</span>
								<input type="file" name="archivo" accept=".jpg, .png, .jpeg">
								<div class="input-group">
									<input type="text" readonly="" class="form-control" placeholder="Elija la imágen...">
									<span class="input-group-btn input-group-sm">
										<button type="button" class="btn btn-fab btn-fab-mini">
											<i class="zmdi zmdi-image-o"></i>
										</button>
									</span>
								</div>
								<span><small>Tamaño máximo de los archivos adjuntos 1MB. Tipos de archivos permitidos imágenes: PNG, JPEG y JPG</small></span>
							</div>
							<p class="text-center" style="margin-top: 20px;">
						    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-upload"></i> &nbsp; Cargar imagen</button>
						    </p>
						    <div class="RespuestaAjax"></div>
						</form>
					<?php }else{ ?>
						<div class="alert alert-dismissible text-center" style="color: #333;">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<i class="zmdi zmdi-image zmdi-hc-5x"></i>
							<h4>¡Lo sentimos!</h4>
							<p>Ya existe una imagen asociada a este libro, ya no se pueden agregar más. Para actualizarla primero elimine la actual.</p>
						</div>
					<?php } ?>
					</div>
					<div class="col-xs-12 col-sm-6">
					<?php if($datosLibro['LibroPDF']=="" && !is_file("./adjuntos/pdf/".$datosLibro['LibroPDF'])){ ?>
						<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="update" enctype="multipart/form-data" autocomplete="off">
							<input type="hidden" name="adjunto-up-id" value="<?php echo $pagina[1]; ?>">
							<input type="hidden" name="adjunto-up-tipo" value="pdf">
							<div class="form-group">
	    						<span class="control-label">PDF</span>
								<input type="file" name="archivo" accept=".pdf">
								<div class="input-group">
									<input type="text" readonly="" class="form-control" placeholder="Elija el PDF...">
									<span class="input-group-btn input-group-sm">
										<button type="button" class="btn btn-fab btn-fab-mini">
											<i class="zmdi zmdi-file"></i>
										</button>
									</span>
								</div>
								<span><small>Tamaño máximo de los archivos adjuntos 10MB. Tipos de archivos permitidos: documentos PDF</small></span>
							</div>
							<p class="text-center" style="margin-top: 20px;">
						    	<button type="submit" class="btn btn-info btn-raised btn-sm"><i class="zmdi zmdi-upload"></i> &nbsp; Cargar PDF</button>
						    </p>
						    <div class="RespuestaAjax"></div>
						</form>
					<?php }else{ ?>
						<div class="alert alert-dismissible text-center" style="color: #333;">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<i class="zmdi zmdi-file-text zmdi-hc-5x"></i>
							<h4>¡Lo sentimos!</h4>
							<p>Ya existe un archivo PDF asociado a este libro, ya no se pueden agregar más. Para actualizarlo primero elimine el actual.</p>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php if($_SESSION['privilegio_sbp']==1){ ?>
<div class="container-fluid">
	<div class="panel panel-warning">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-attachment-alt"></i> &nbsp; ELIMINAR ADJUNTOS</h3>
		</div>
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th class="text-center">Nombre</th>
							<th class="text-center">Tipo</th>
							<th class="text-center">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<?php if($datosLibro['LibroImagen']!="" && is_file("./adjuntos/img/".$datosLibro['LibroImagen'])){ ?>
						<tr>
							<td class="text-center"><?php echo $datosLibro['LibroImagen']; ?></td>
							<td class="text-center">Imagen</td>
							<td>
								<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="adjunto-del-tipo" value="img">
									<input type="hidden" name="adjunto-del-id" value="<?php echo $pagina[1]; ?>">
									<p class="text-center">
										<button class="btn btn-raised btn-danger btn-xs"><i class="zmdi zmdi-delete"></i></button>
									</p>
									<div class="RespuestaAjax"></div>
								</form>
							</td>
						</tr>
						<?php 
							}

							if($datosLibro['LibroPDF']!="" && is_file("./adjuntos/pdf/".$datosLibro['LibroPDF'])){ 
						?>
						<tr>
							<td class="text-center"><?php echo $datosLibro['LibroPDF']; ?></td>
							<td class="text-center">Documento PDF</td>
							<td>
								<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="delete" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="adjunto-del-tipo" value="pdf">
									<input type="hidden" name="adjunto-del-id" value="<?php echo $pagina[1]; ?>">
									<p class="text-center">
										<button class="btn btn-raised btn-danger btn-xs"><i class="zmdi zmdi-delete"></i></button>
									</p>
									<div class="RespuestaAjax"></div>
								</form>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="panel panel-danger">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="zmdi zmdi-delete"></i> &nbsp; ELIMINAR LIBRO</h3>
		</div>
		<div class="panel-body">
			<p class="lead">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi voluptatem quas impedit, sint eos corrupti cupiditate.
			</p>
			<form class="FormularioAjax" method="POST" action="<?php echo SERVERURL; ?>ajax/libroAjax.php" data-form="delete" enctype="multipart/form-data" autocomplete="off">
				<input type="hidden" name="id-del" value="<?php echo $pagina[1]; ?>">
				<input type="hidden" name="privilegio-del" value="<?php echo $lc->encryption($_SESSION['privilegio_sbp']); ?>">
				<p class="text-center">
					<button class="btn btn-raised btn-danger">
						<i class="zmdi zmdi-delete"></i> &nbsp; ELIMINAR LIBRO DEL SISTEMA
					</button>	
				</p>
				<div class="RespuestaAjax"></div>
			</form>
		</div>
	</div>
</div>
<?php 
	}
}else{
?>
<div class="alert alert-dismissible alert-warning text-center">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<i class="zmdi zmdi-alert-triangle zmdi-hc-5x"></i>
	<h4>¡Lo sentimos!</h4>
	<p>No podemos mostrar la información del libro debido a un error o el libro ha sido eliminado del sistema</p>
</div>
<?php } ?>

