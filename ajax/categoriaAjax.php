<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['codigo-reg']) || isset($_POST['codigo-del']) || isset($_POST['id-up'])){

		require_once "../controladores/categoriaControlador.php";
		$insCateg = new categoriaControlador();

		if(isset($_POST['codigo-reg']) || isset($_POST['nombre-reg'])){
			echo $insCateg->agregar_categoria_controlador();
		}

		if(isset($_POST['codigo-del']) && isset($_POST['privilegio-admin'])){
			echo $insCateg->eliminar_categoria_controlador();
		}

		if(isset($_POST['id-up']) && isset($_POST['codigo-up']) && isset($_POST['nombre-up'])){
			echo $insCateg->actualizar_categoria_controlador();
		}
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}