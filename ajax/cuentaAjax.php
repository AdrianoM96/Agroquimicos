<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['codigoCuenta-up'])){

		require_once "../controladores/cuentaControlador.php";
		$cuenta = new cuentaControlador();

		if(isset($_POST['codigoCuenta-up']) && isset($_POST['tipoCuenta-up']) && isset($_POST['usuario-up'])){
			echo $cuenta->actualizar_cuenta_controlador();
		}
		
			
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}