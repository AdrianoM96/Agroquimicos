<?php
	if($peticionAjax){
		require_once "../modelos/empresaModelo.php";
	}else{
		require_once "./modelos/empresaModelo.php";
	}

	class empresaControlador extends empresaModelo{

		/*----------  Controlador agregar empresa  ----------*/
		public function agregar_empresa_controlador(){

			$codigo=mainModel::limpiar_cadena($_POST['dni-reg']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
			$email=mainModel::limpiar_cadena($_POST['email-reg']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);
			$director=mainModel::limpiar_cadena($_POST['director-reg']);
			$moneda=mainModel::limpiar_cadena($_POST['moneda-reg']);
			$year=mainModel::limpiar_cadena($_POST['year-reg']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT EmpresaCodigo FROM empresa WHERE EmpresaCodigo='$codigo'");

			if($consulta1->rowCount()<=0){

				$consulta2=mainModel::ejecutar_consulta_simple("SELECT EmpresaNombre FROM empresa WHERE EmpresaNombre='$nombre'");

				if($consulta2->rowCount()<=0){

					$datosEmpresa=[
						"Codigo"=>$codigo,
						"Nombre"=>$nombre,
						"Telefono"=>$telefono,
						"Email"=>$email,
						"Direccion"=>$direccion,
						"Director"=>$director,
						"Moneda"=>$moneda,
						"Year"=>$year
					];

					$guardarEmpresa=empresaModelo::agregar_empresa_modelo($datosEmpresa);

					if($guardarEmpresa->rowCount()>=1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"¡Empresa registrada!",
							"Texto"=>"Los datos de la empresa se registraron con éxito en el sistema",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No hemos podido registrar la empresa, por favor intente nuevamente",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El nombre que acaba de ingresar ya se encuentra asignado",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El código que acaba de ingresar ya se encuentra asignado",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador paginador empresa  ----------*/
		public function paginador_empresa_controlador($pagina,$registros,$privilegio){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM empresa ORDER BY EmpresaNombre ASC LIMIT $inicio,$registros";
			$paginaurl="companylist";

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
						<th class="text-center">CÓDIGO DE REGISTRO</th>
						<th class="text-center">NOMBRE</th>
						<th class="text-center">EMAIL</th>';
						if($privilegio<=2){
							$tabla.='
								<th class="text-center">ACTUALIZAR</th>
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
							<td>'.$rows['EmpresaCodigo'].'</td>
							<td>'.$rows['EmpresaNombre'].'</td>
							<td>'.$rows['EmpresaEmail'].'</td>';
							if($privilegio<=2){
								$tabla.='
									<td>
										<a href="'.SERVERURL.'companyinfo/'.mainModel::encryption($rows['id']).'/" class="btn btn-success btn-raised btn-xs">
											<i class="zmdi zmdi-refresh"></i>
										</a>
									</td>
								';
							}
							if($privilegio==1){
								$tabla.='<td>
									<form action="'.SERVERURL.'ajax/empresaAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['EmpresaCodigo']).'">
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
							<td colspan="6">
								<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				}else{
					$tabla.='
						<tr>
							<td colspan="6">
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


		/*----------  Controlador datos empresa  ----------*/
		public function datos_empresa_controlador($tipo,$codigo){
			$codigo=mainModel::decryption($codigo);
			$tipo=mainModel::limpiar_cadena($tipo);

			return empresaModelo::datos_empresa_modelo($tipo,$codigo);
		}


		/*----------  Controlador eliminar empresa  ----------*/
		public function eliminar_empresa_controlador(){

			$codigo=mainModel::decryption($_POST['codigo-del']);
			$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

			$codigo=mainModel::limpiar_cadena($codigo);
			$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

			if($adminPrivilegio==1){

				$consulta1=mainModel::ejecutar_consulta_simple("SELECT EmpresaCodigo FROM libro WHERE EmpresaCodigo='$codigo'");

				if($consulta1->rowCount()<=0){

					$DelEmpresa=empresaModelo::eliminar_empresa_modelo($codigo);

					if($DelEmpresa->rowCount()==1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"Empresa eliminada",
							"Texto"=>"La empresa fue eliminada del sistema con éxito",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No podemos eliminar esta empresa en este momento",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No podemos eliminar esta empresa ya que hay libros asociados a esta",
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


		/*----------  Controlador actualizar empresa ----------*/
		public function actualizar_empresa_controlador(){

			$codigo=mainModel::decryption($_POST['codigo']);

			$dni=mainModel::limpiar_cadena($_POST['dni-up']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-up']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-up']);
			$email=mainModel::limpiar_cadena($_POST['email-up']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-up']);
			$director=mainModel::limpiar_cadena($_POST['director-up']);
			$moneda=mainModel::limpiar_cadena($_POST['moneda-up']);
			$year=mainModel::limpiar_cadena($_POST['year-up']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT * FROM empresa WHERE id='$codigo'");
			$DatosEmpresa=$consulta1->fetch();

			if($DatosEmpresa['EmpresaCodigo']!=$dni){
				$consulta2=mainModel::ejecutar_consulta_simple("SELECT EmpresaCodigo FROM empresa WHERE EmpresaCodigo='$dni'");
				if($consulta2->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El código que acaba de ingresar ya se encuentra asignado",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}


			if($DatosEmpresa['EmpresaNombre']!=$nombre){
				$consulta3=mainModel::ejecutar_consulta_simple("SELECT EmpresaNombre FROM empresa WHERE EmpresaNombre='$nombre'");
				if($consulta3->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El nombre que acaba de ingresar ya se encuentra asignado",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}


			$datos_empresa=[
				"Codigo"=>$dni,
				"Nombre"=>$nombre,
				"Telefono"=>$telefono,
				"Email"=>$email,
				"Direccion"=>$direccion,
				"Director"=>$director,
				"Moneda"=>$moneda,
				"Year"=>$year,
				"ID"=>$codigo
			];


			if(empresaModelo::actualizar_empresa_modelo($datos_empresa)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Datos actualizados!",
					"Texto"=>"Los datos de la empresa han sido actualizados con éxito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la empresa, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}

			return mainModel::sweet_alert($alerta);


		}

	}