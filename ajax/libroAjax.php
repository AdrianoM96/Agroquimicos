<?php
	$peticionAjax=true;
	require_once "../core/configGeneral.php";
	if(isset($_POST['codigo-reg']) || isset($_POST['id-up']) || isset($_POST['adjunto-del-id']) || isset($_POST['adjunto-up-id']) || isset($_POST['id-del'])){

		require_once "../controladores/libroControlador.php";
		$insLibro = new libroControlador();

		if(isset($_POST['codigo-reg']) && isset($_POST['titulo-reg'])){
			echo $insLibro->agregar_libro_controlador();
		}

		if(isset($_POST['id-up']) && isset($_POST['codigo-up']) && isset($_POST['titulo-up'])){
			echo $insLibro->actualizar_libro_controlador();
		}

		if(isset($_POST['adjunto-del-id']) && isset($_POST['adjunto-del-tipo'])){
			echo $insLibro->eliminar_adjunto_libro_controlador();
		}

		if(isset($_POST['adjunto-up-id']) && isset($_POST['adjunto-up-tipo'])){
			echo $insLibro->actualizar_adjunto_libro_controlador();
		}

		if(isset($_POST['id-del']) && isset($_POST['privilegio-del'])){
			echo $insLibro->eliminar_libro_controlador();
		}
		
	}else{
		session_start(['name'=>'SBP']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}