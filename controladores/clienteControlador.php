<?php
	if($peticionAjax){
		require_once "../modelos/clienteModelo.php";
	}else{
		require_once "./modelos/clienteModelo.php";
	}

	class clienteControlador extends clienteModelo{

		/*----------  Controlador agregar cliente  ----------*/
		public function agregar_cliente_controlador(){

			$dni=mainModel::limpiar_cadena($_POST['dni-reg']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
			$apellido=mainModel::limpiar_cadena($_POST['apellido-reg']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
			$ocupacion=mainModel::limpiar_cadena($_POST['ocupacion-reg']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);

			$usuario=mainModel::limpiar_cadena($_POST['usuario-reg']);
			$password1=mainModel::limpiar_cadena($_POST['password1']);
			$password2=mainModel::limpiar_cadena($_POST['password2']);
			$email=mainModel::limpiar_cadena($_POST['email-reg']);
			$genero=mainModel::limpiar_cadena($_POST['optionsGenero']);
			$privilegio=4;

			if($genero=="Masculino"){
				$foto="Male2Avatar.png";
			}else{
				$foto="Female2Avatar.png";
			}

			if($password1!=$password2){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Las contraseñas que acabas de ingresar no coinciden, por favor verifique e intente nuevamente",
					"Tipo"=>"error"
				];
			}else{
				$consulta1=mainModel::ejecutar_consulta_simple("SELECT ClienteDNI FROM cliente WHERE ClienteDNI='$dni'");
				if($consulta1->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El DNI que acaba de ingresar ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
				}else{
					if($email!=""){
						$consulta2=mainModel::ejecutar_consulta_simple("SELECT CuentaEmail FROM cuenta WHERE CuentaEmail='$email'");
						$ec=$consulta2->rowCount();
					}else{
						$ec=0;
					}

					if($ec>=1){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema",
							"Tipo"=>"error"
						];
					}else{
						$consulta3=mainModel::ejecutar_consulta_simple("SELECT CuentaUsuario FROM cuenta WHERE CuentaUsuario='$usuario'");
						if($consulta3->rowCount()>=1){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"El USUARIO que acaba de ingresar ya se encuentra registrado en el sistema",
								"Tipo"=>"error"
							];
						}else{
							$consulta4=mainModel::ejecutar_consulta_simple("SELECT id FROM cuenta");
							$numero=($consulta4->rowCount())+1;

							$codigo=mainModel::generar_codigo_aleatorio("CC",7,$numero);

							$clave=mainModel::encryption($password1);

							$dataAc=[
								"Codigo"=>$codigo,
								"Privilegio"=>$privilegio,
								"Usuario"=>$usuario,
								"Clave"=>$clave,
								"Email"=>$email,
								"Estado"=>"Activo",
								"Tipo"=>"Cliente",
								"Genero"=>$genero,
								"Foto"=>$foto
							];

							$guardarCuenta=mainModel::agregar_cuenta($dataAc);

							if($guardarCuenta->rowCount()>=1){

								$dataCli=[
									"DNI"=>$dni,
									"Nombre"=>$nombre,
									"Apellido"=>$apellido,
									"Telefono"=>$telefono,
									"Ocupacion"=>$ocupacion,
									"Direccion"=>$direccion,
									"Codigo"=>$codigo
								];

								$guardarCliente=clienteModelo::agregar_cliente_modelo($dataCli);

								if($guardarCliente->rowCount()>=1){
									$alerta=[
										"Alerta"=>"limpiar",
										"Titulo"=>"Cliente registrado",
										"Texto"=>"El cliente se registró con éxito en el sistema",
										"Tipo"=>"success"
									];
								}else{
									mainModel::eliminar_cuenta($codigo);
									$alerta=[
										"Alerta"=>"simple",
										"Titulo"=>"Ocurrió un error inesperado",
										"Texto"=>"No hemos podido registrar el cliente, por favor intente nuevamente",
										"Tipo"=>"error"
									];
								}
							}else{
								$alerta=[
									"Alerta"=>"simple",
									"Titulo"=>"Ocurrió un error inesperado",
									"Texto"=>"No hemos podido registrar el cliente, por favor intente nuevamente",
									"Tipo"=>"error"
								];
							}
						}
					}
				}
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador paginador cliente  ----------*/
		public function paginador_cliente_controlador($pagina,$registros,$privilegio,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente WHERE  (ClienteNombre LIKE '%$busqueda%' OR ClienteApellido LIKE '%$busqueda%' OR ClienteDNI LIKE '%$busqueda%' OR ClienteTelefono LIKE '%$busqueda%') ORDER BY ClienteNombre ASC LIMIT $inicio,$registros";
				$paginaurl="clientsearch";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cliente ORDER BY ClienteNombre ASC LIMIT $inicio,$registros";
				$paginaurl="clientlist";
			}

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);

			### Cuerpo de la tabla ###
			$tabla.='
			<div class="table-responsive">
			<table class="table table-hover text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">DNI</th>
						<th class="text-center">NOMBRES</th>
						<th class="text-center">APELLIDOS</th>
						<th class="text-center">TELÉFONO</th>';
						if($privilegio<=2){
							$tabla.='
								<th class="text-center">A. CUENTA</th>
								<th class="text-center">A. DATOS</th>
							';
						}
						if($privilegio==1){
							$tabla.='<th class="text-center">ELIMINAR</th>';
						}
					$tabla.='</tr>
				</thead>
				<tbody>
			';

			if($total>=1 && $pagina<=$Npaginas){
				$contador=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr>
							<td>'.$contador.'</td>
							<td>'.$rows['ClienteDNI'].'</td>
							<td>'.$rows['ClienteNombre'].'</td>
							<td>'.$rows['ClienteApellido'].'</td>
							<td>'.$rows['ClienteTelefono'].'</td>';
							if($privilegio<=2){
								$tabla.='
									<td>
										<a href="'.SERVERURL.'myaccount/user/'.mainModel::encryption($rows['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
											<i class="zmdi zmdi-refresh"></i>
										</a>
									</td>
									<td>
										<a href="'.SERVERURL.'mydata/user/'.mainModel::encryption($rows['CuentaCodigo']).'/" class="btn btn-success btn-raised btn-xs">
											<i class="zmdi zmdi-refresh"></i>
										</a>
									</td>
								';
							}
							if($privilegio==1){
								$tabla.='<td>
									<form action="'.SERVERURL.'ajax/clienteAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['CuentaCodigo']).'">
										<input type="hidden" name="privilegio-admin" value="'.mainModel::encryption($privilegio).'">
										<button type="submit" class="btn btn-danger btn-raised btn-xs">
											<i class="zmdi zmdi-delete"></i>
										</button>
										<div class="RespuestaAjax"></div>
									</form>
								</td>';
							}
					$tabla.='</tr>';
					$contador++;
				}
			}else{
				if($total>=1){
					$tabla.='
						<tr>
							<td colspan="8">
								<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				}else{
					$tabla.='
						<tr>
							<td colspan="8">
								No hay registros en el sistema
							</td>
						</tr>
					';
				}
			}

			$tabla.='</tbody></table></div>';

			### Paginacion ###
			if($total>=1 && $pagina<=$Npaginas){
				$tabla.='<nav class="text-center"><ul class="pagination pagination-sm">';

				if($pagina==1){
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>';
				}else{
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina-1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>';
				}

				for($i=1; $i<=$Npaginas; $i++){
					if($pagina==$i){
						$tabla.='<li class="active"><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					}else{
						$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					}
				}

				if($pagina==$Npaginas){
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>';
				}else{
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina+1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>';
				}

				$tabla.='</ul></nav>';
			}

			return $tabla;
		}


		/*----------  Controlador datos cliente  ----------*/
		public function datos_cliente_controlador($tipo,$codigo){
			$codigo=mainModel::decryption($codigo);
			$tipo=mainModel::limpiar_cadena($tipo);

			return clienteModelo::datos_cliente_modelo($tipo,$codigo);
		}


		/*----------  Controlador eliminar cliente  ----------*/
		public function eliminar_cliente_controlador(){
			$codigo=mainModel::decryption($_POST['codigo-del']);
			$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

			$codigo=mainModel::limpiar_cadena($codigo);
			$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

			if($adminPrivilegio==1){
				$DelClient=clienteModelo::eliminar_cliente_modelo($codigo);
				mainModel::eliminar_bitacora($codigo);

				if($DelClient->rowCount()>=1){
					$DelCuenta=mainModel::eliminar_cuenta($codigo);
					if($DelCuenta->rowCount()==1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"Cliente eliminado",
							"Texto"=>"El cliente fue eliminado del sistema con éxito",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No podemos eliminar esta cuenta en este momento",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No podemos eliminar este cliente en este momento",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Tú no tienes los permisos necesarios para eliminar registros del sistema",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador actualizar cliente ----------*/
		public function actualizar_cliente_controlador(){
			$cuenta=mainModel::decryption($_POST['cuenta-up']);

			$dni=mainModel::limpiar_cadena($_POST['dni-up']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-up']);
			$apellido=mainModel::limpiar_cadena($_POST['apellido-up']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-up']);
			$ocupacion=mainModel::limpiar_cadena($_POST['ocupacion-up']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-up']);


			$query1=mainModel::ejecutar_consulta_simple("SELECT * FROM cliente WHERE CuentaCodigo='$cuenta'");
			$DatosCliente=$query1->fetch();

			if($dni!=$DatosCliente['ClienteDNI']){
				$consulta1=mainModel::ejecutar_consulta_simple("SELECT ClienteDNI FROM cliente WHERE ClienteDNI='$dni'");
				if($consulta1->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El DNI que acaba de ingresar ya se encuentra registrado en el sistema",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}

			$dataClient=[
				"DNI"=>$dni,
				"Nombre"=>$nombre,
				"Apellido"=>$apellido,
				"Telefono"=>$telefono,
				"Ocupacion"=>$ocupacion,
				"Direccion"=>$direccion,
				"Codigo"=>$cuenta
			];

			if(clienteModelo::actualizar_cliente_modelo($dataClient)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Datos actualizados!",
					"Texto"=>"Tus datos han sido actualizados con éxito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar tus datos por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			return mainModel::sweet_alert($alerta);
		}
	}