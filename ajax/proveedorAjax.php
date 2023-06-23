<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['nombre-reg']) || isset($_POST['codigo-del']) || isset($_POST['codigo-up'])){

		require_once "../controladores/proveedorControlador.php";
		$insProv = new proveedorControlador();

		if(isset($_POST['nombre-reg']) && isset($_POST['responsable-reg'])){
			echo $insProv->agregar_proveedor_controlador();
		}

		if(isset($_POST['codigo-del']) && isset($_POST['privilegio-admin'])){
			echo $insProv->eliminar_proveedor_controlador();
		}

		if(isset($_POST['codigo-up']) && isset($_POST['nombre-up']) && isset($_POST['responsable-up'])){
			echo $insProv->actualizar_proveedor_controlador();
		}	
		
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}