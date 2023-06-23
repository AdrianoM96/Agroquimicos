<?php
	session_start(['name'=>'SBP']);
	require_once "../core/configGeneral.php";
	if(isset($_POST)){

		/*----------  Modulo Administrador  ----------*/
		if(isset($_POST['busqueda_inicial_admin'])){
			$_SESSION['busqueda_admin']=$_POST['busqueda_inicial_admin'];
		}

		if(isset($_POST['eliminar_busqueda_admin'])){
			unset($_SESSION['busqueda_admin']);
			$url="adminsearch";
		}


		/*----------  Modulo Cliente  ----------*/
		if(isset($_POST['busqueda_inicial_cliente'])){
			$_SESSION['busqueda_cliente']=$_POST['busqueda_inicial_cliente'];
		}

		if(isset($_POST['eliminar_busqueda_cliente'])){
			unset($_SESSION['busqueda_cliente']);
			$url="clientsearch";
		}


		/*----------  Modulo Categoria  ----------*/
		if(isset($_POST['busqueda_inicial_categoria'])){
			$_SESSION['busqueda_categoria']=$_POST['busqueda_inicial_categoria'];
		}

		if(isset($_POST['eliminar_busqueda_categoria'])){
			unset($_SESSION['busqueda_categoria']);
			$url="categorysearch";
		}


		/*----------  Modulo Proveedor  ----------*/
		if(isset($_POST['busqueda_inicial_proveedor'])){
			$_SESSION['busqueda_proveedor']=$_POST['busqueda_inicial_proveedor'];
		}

		if(isset($_POST['eliminar_busqueda_proveedor'])){
			unset($_SESSION['busqueda_proveedor']);
			$url="providersearch";
		}


		/*----------  Modulo Libro  ----------*/
		if(isset($_POST['busqueda_inicial_libro'])){
			$_SESSION['busqueda_libro']=$_POST['busqueda_inicial_libro'];
		}

		if(isset($_POST['eliminar_busqueda_libro'])){
			unset($_SESSION['busqueda_libro']);
			$url="search";
		}


		if(isset($url)){
			echo '<script> window.location="'.SERVERURL.$url.'/"; </script>';
		}else{
			echo "<script> location.reload(); </script>";
		}
	}else{
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
	}