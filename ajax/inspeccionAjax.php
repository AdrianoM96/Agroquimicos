<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['cuit-reg'])){

		require_once "../controladores/inspeccionControlador.php";
		$solicitarInspeccion = new inspeccionControlador();

		if(isset($_POST['cuit-reg']) && isset($_POST['latitud-reg']) && isset($_POST['longuitud-reg'])){
			echo $solicitarInspeccion->solicitar_inspeccion_controlador();
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