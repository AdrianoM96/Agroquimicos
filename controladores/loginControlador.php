<?php
	if($peticionAjax){
		require_once "../modelos/loginModelo.php";
	}else{
		require_once "./modelos/loginModelo.php";
	}

	class loginControlador extends loginModelo{

		/*----------  Controlador iniciar sesion  ----------*/
		public function iniciar_sesion_controlador(){
			$usuario=mainModel::limpiar_cadena($_POST['usuario']);
			$clave=mainModel::limpiar_cadena($_POST['clave']);

			$clave=mainModel::encryption($clave);

			$datosLogin=[
				"Usuario"=>$usuario,
				"Clave"=>$clave
			];

			$datosCuenta=loginModelo::iniciar_sesion_modelo($datosLogin);

			if($datosCuenta->rowCount()==1){

				$row=$datosCuenta->fetch();

				$fechaActual=date("Y-m-d");
				$yearActual=date("Y");
				$horaActual=date("h:i:s a");

				$consulta1=mainModel::ejecutar_consulta_simple("SELECT id FROM bitacora");
				$numero=($consulta1->rowCount())+1;

				$codigoB=mainModel::generar_codigo_aleatorio("CB",7,$numero);

				$datosBitacora=[
					"Codigo"=>$codigoB,
					"Fecha"=>$fechaActual,
					"HoraInicio"=>$horaActual,
					"HoraFinal"=>"Sin registro",
					"Tipo"=>$row['CuentaTipo'],
					"Year"=>$yearActual,
					"Cuenta"=>$row['CuentaCodigo']
				];

				$insertarBitacora=mainModel::guardar_bitacora($datosBitacora);

				if($insertarBitacora->rowCount()>=1){

					if($row['CuentaTipo']=="Administrador"){
						$query1=mainModel::ejecutar_consulta_simple("SELECT * FROM admin WHERE CuentaCodigo='".$row['CuentaCodigo']."'");
					}else{
						$query1=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE CuentaCodigo='".$row['CuentaCodigo']."'");
					}

					if($query1->rowCount()==1){
						$UserData=$query1->fetch();
						session_start(['name'=>'SBP']);

						if($row['CuentaTipo']=="Administrador"){
							$_SESSION['nombre_sbp']=$UserData['AdminNombre'];
							$_SESSION['apellido_sbp']=$UserData['AdminApellido'];
						}else{
							$_SESSION['nombre_sbp']=$UserData['ClienteNombre'];
							$_SESSION['apellido_sbp']=$UserData['ClienteApellido'];
						}

						$_SESSION['usuario_sbp']=$row['CuentaUsuario'];
						$_SESSION['tipo_sbp']=$row['CuentaTipo'];
						$_SESSION['privilegio_sbp']=$row['CuentaPrivilegio'];
						$_SESSION['foto_sbp']=$row['CuentaFoto'];
						$_SESSION['token_sbp']=md5(uniqid(mt_rand(), true));
						$_SESSION['codigo_cuenta_sbp']=$row['CuentaCodigo'];
						$_SESSION['codigo_bitacora_sbp']=$codigoB;

						if($row['CuentaTipo']=="Administrador"){
							$url=SERVERURL."home/";
						}else{
							$url=SERVERURL."catalog/";
						}

						return $urlLocation='<script type="text/javascript"> window.location="'.$url.'"; </script>';
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No hemos podido iniciar la sesión por problemas técnicos, por favor intente nuevamente",
							"Tipo"=>"error"
						];
						return mainModel::sweet_alert($alerta);
					}

				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido iniciar la sesión por problemas técnicos, por favor intente nuevamente",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre de usuario y contraseña no son correctos o su cuenta puede estar deshabilitada",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
			}
		}


		/*----------  Controlador cerrar sesion  ----------*/
		public function cerrar_sesion_controlador(){
			session_start(['name'=>'SBP']);
			$token=mainModel::decryption($_GET['Token']);
			$hora=date("h:i:s a");
			$datos=[
				"Usuario"=>$_SESSION['usuario_sbp'],
				"Token_S"=>$_SESSION['token_sbp'],
				"Token"=>$token,
				"Codigo"=>$_SESSION['codigo_bitacora_sbp'],
				"Hora"=>$hora
			];
			return loginModelo::cerrar_sesion_modelo($datos);
		}


		/*----------  Controlador cierre de sesion  ----------*/
		public function cierre_sesion_controlador(){
			session_unset();
			session_destroy();
			return header("Location: ".SERVERURL."login/");
		}


		/*----------  Controlador forzar cierre de sesion  ----------*/
		public function forzar_cierre_sesion_controlador(){
			session_unset();
			session_destroy();
			$redirect="<script language='javascript'> window.location='".SERVERURL."login/'; </script>";
			return $redirect;
		}


		/*----------  Controlador redireccionar  ----------*/
		public function redireccionar_usuario($tipo){
			if($tipo=="Administrador"){
				$redirect="<script language='javascript'> window.location='".SERVERURL."home/'; </script>";
			}else{
				$redirect="<script language='javascript'> window.location='".SERVERURL."catalog/'; </script>";
			}
			return $redirect;
		}

	}