<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['cuit-reg'])){

		require_once "../controladores/productorControlador.php";
		$insProd = new productorControlador();

		if(isset($_POST['cuit-reg']) && isset($_POST['nombre-reg']) && isset($_POST['apellido-reg']) && isset($_POST['usuario-reg'])){
			echo $insProd->agregar_productor_controlador();
		}


		if(isset($_POST['codigo-del']) && isset($_POST['privilegio-admin'])){
			echo $insAdmin->eliminar_administrador_controlador();
		}


		if(isset($_POST['cuenta-up']) && isset($_POST['dni-up'])){
			echo $insAdmin->actualizar_administrador_controlador();
		}
		
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}