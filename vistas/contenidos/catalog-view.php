<!-- Content page -->
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles"><i class="zmdi zmdi-book-image zmdi-hc-fw"></i> CATALOGO</h1>
	</div>
	<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>
<div class="container-fluid text-center">
	<div class="btn-group">
      <a href="javascript:void(0)" class="btn btn-default btn-raised"><i class="zmdi zmdi-labels"></i> &nbsp; SELECCIONE UNA CATEORÍA</a>
      <a href="javascript:void(0)" data-target="dropdown-menu" class="btn btn-default btn-raised dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
      <ul class="dropdown-menu">
      	<?php

      		$pagina=explode("/", $_GET['views']);

      		require_once "./controladores/categoriaControlador.php";
  			$insCat= new categoriaControlador();

  			$dataCat=$insCat->datos_categoria_controlador("Select",0);
  			

  			while($rowCat=$dataCat->fetch()){
  				echo '<li><a href="'.SERVERURL.'catalog/'.$lc->encryption($rowCat['CategoriaCodigo']).'/">'.$rowCat['CategoriaNombre'].'</a></li>';
  			}
      	?>
      </ul>
    </div>
</div>
<div class="container-fluid" style="min-height: 50vh;">
	<?php 
		if($pagina[1]!=""){ 
			require_once "./controladores/libroControlador.php";
  			$insLibro= new libroControlador();

  			echo $insLibro->catalogo_libro_controlador($pagina[2],15,$pagina[1],"",$_SESSION['privilegio_sbp']);
		}else{ 
	?>
	<h2 class="text-titles text-center">Selecciona una categoría para empezar</h2>
	<p class="text-center" style="font-size: 300px; "><i class="zmdi zmdi-labels"></i></p>
	<?php } ?>
</div>