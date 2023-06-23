<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['dni-reg']) || isset($_POST['codigo-del']) || isset($_POST['dni-up'])){

		require_once "../controladores/empresaControlador.php";
		$insEm = new empresaControlador();

		if(isset($_POST['dni-reg']) && isset($_POST['nombre-reg']) && isset($_POST['director-reg'])){
			echo $insEm->agregar_empresa_controlador();
		}

		if(isset($_POST['codigo-del']) && isset($_POST['privilegio-admin'])){
			echo $insEm->eliminar_empresa_controlador();
		}

		if(isset($_POST['dni-up']) && isset($_POST['nombre-up']) && isset($_POST['year-up'])){
			echo $insEm->actualizar_empresa_controlador();
		}
		
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}