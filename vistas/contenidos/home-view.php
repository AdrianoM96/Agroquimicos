<?php
    if($_SESSION['tipo_sbp']!="Administrador"){
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }
?>  
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles">Sistema <small>Registros</small></h1>
	</div>
</div>
<div class="full-box text-center" style="padding: 30px 10px;">
    <?php 
        require "./controladores/administradorControlador.php";
        $IAdmin = new administradorControlador();
        $CAdmin=$IAdmin->datos_administrador_controlador('Conteo',0);
    ?>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			administradores
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-account"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $CAdmin->rowCount(); ?></p>
			<small>Registros</small>
		</div>
	</article>
    <?php 
        require "./controladores/clienteControlador.php";
        $ICliente = new clienteControlador();
        $CCliente=$ICliente->datos_cliente_controlador('Conteo',0);
    ?>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Clientes
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-male-female"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $CCliente->rowCount(); ?></p>
			<small>Registros</small>
		</div>
	</article>
	<?php 
        require "./controladores/categoriaControlador.php";
        $ICat = new categoriaControlador();
        $CCat=$ICat->datos_categoria_controlador('Conteo',0);
    ?>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Categorías
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-labels"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $CCat->rowCount(); ?></p>
			<small>Registros</small>
		</div>
	</article>
	<?php 
        require "./controladores/proveedorControlador.php";
        $IProv = new proveedorControlador();
        $CProv=$IProv->datos_proveedor_controlador('Conteo',0);
    ?>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Proveedores
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-truck"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?php echo $CProv->rowCount(); ?></p>
			<small>Registros</small>
		</div>
	</article>
</div>
<div class="container-fluid">
	<div class="page-header">
	  <h1 class="text-titles">Sistema <small>Bitacora</small></h1>
	</div>
    <p class="lead text-center">Últimos 15 usuarios que iniciaron sesión en el sistema</p>
	<section id="cd-timeline" class="cd-container">
        <?php
            require "./controladores/bitacoraControlador.php";
            $IBitacora = new bitacoraControlador();

            echo $IBitacora->listado_bitacora_controlador(15);
        ?>    
    </section>
</div>