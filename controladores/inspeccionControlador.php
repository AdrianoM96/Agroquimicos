<?php 

	if($peticionAjax){
		require_once "../modelos/inspeccionModelo.php";
	}else{
		require_once "./modelos/inspeccionModelo.php";
	}

	class inspeccionControlador extends inspeccionModelo{

		/*----------  Controlador agregar administrador  ----------*/
		public function solicitar_inspeccion_controlador(){

			$cuit=mainModel::limpiar_cadena($_POST['cuit-reg']);

			$localidad=mainModel::limpiar_cadena($_POST['localidad-reg']);
			$calle=mainModel::limpiar_cadena($_POST['calle-reg']);
			$numeroCalle=mainModel::limpiar_cadena($_POST['numero-reg']);
			$latitud=mainModel::limpiar_cadena($_POST['latitud-reg']);
			$longuitud=mainModel::limpiar_cadena($_POST['longuitud-reg']);

					
					$consulta1=mainModel::ejecutar_consulta_simple("SELECT Cuit FROM productor WHERE Cuit='$cuit'");

					if($consulta1->rowCount()<1){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El CUIT que acaba de ingresar no se encuentra registrado en el sistema",
							"Tipo"=>"error"
						];
					}else{
							$datosCuenta=[
							"Cuit"=>$cuit
							];

							$datosCuenta=inspeccionModelo::buscar_cuit_modelo($datosCuenta);

							if($datosCuenta->rowCount()>=1){
									$row=$datosCuenta->fetch();

									$consulta2=mainModel::ejecutar_consulta_simple("SELECT id FROM productor");
									$numero=($consulta2->rowCount())+1;//llevar un registro del numero de registro
									$codigo=mainModel::generar_codigo_aleatorio("SI",7,$numero);

									$dataSi=[
										"InspeccionCodigo"=>$codigo,
										"Calle"=>$calle,
										"Numero"=>$numeroCalle,
										"Localidad"=>$localidad,
										"Latitud"=>$latitud,
										"Longuitud"=>$longuitud,
										"ProductorCodigo"=>$row['ProductorCodigo']
										];

									$completarSolicitudInspeccion=inspeccionModelo::solicitar_inspeccion_modelo($dataSi);

									if($completarSolicitudInspeccion->rowCount()>=1){
											$alerta=[
											"Alerta"=>"limpiar",
											"Titulo"=>"Administrador registrado",
											"Texto"=>"La solicitud se registró con éxito en el sistema",
											"Tipo"=>"success"
										];
									}else{
											inspeccionModelo::eliminar_solicitud_modelo($codigo);
											$alerta=[
												"Alerta"=>"simple",
												"Titulo"=>"Ocurrió un error inesperado",
												"Texto"=>"No hemos podido registrar la solicitud, por favor intente nuevamente",
												"Tipo"=>"error"
										];
										}
								}else{
									mainModel::eliminar_cuenta($codigo);
										$alerta=[
											"Alerta"=>"simple",
											"Titulo"=>"Ocurrió un error inesperado",
											"Texto"=>"No hemos podido registrar la solicitud, por favor intente nuevamente",
											"Tipo"=>"error"
										];
								}
						}
			return mainModel::sweet_alert($alerta);
	}
}

 ?>
