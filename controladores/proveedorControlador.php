<?php
	if($peticionAjax){
		require_once "../modelos/proveedorModelo.php";
	}else{
		require_once "./modelos/proveedorModelo.php";
	}

	class proveedorControlador extends proveedorModelo{

		/*----------  Controlador agregar proveedor  ----------*/
		public function agregar_proveedor_controlador(){
			$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
			$responsable=mainModel::limpiar_cadena($_POST['responsable-reg']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
			$email=mainModel::limpiar_cadena($_POST['email-reg']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT ProveedorNombre FROM proveedor WHERE ProveedorNombre='$nombre'");

			if($consulta1->rowCount()<=0){

				$consulta2=mainModel::ejecutar_consulta_simple("SELECT id FROM proveedor");
				$numero=($consulta2->rowCount())+1;

				$codigo=mainModel::generar_codigo_aleatorio("PV",7,$numero);

				$dataProv=[
					"Codigo"=>$codigo,
					"Nombre"=>$nombre,
					"Responsable"=>$responsable,
					"Telefono"=>$telefono,
					"Email"=>$email,
					"Direccion"=>$direccion
				];

				$saveProv=proveedorModelo::agregar_proveedor_modelo($dataProv);

				if($saveProv->rowCount()>=1){
					$alerta=[
						"Alerta"=>"limpiar",
						"Titulo"=>"¡Proveedor registrado!",
						"Texto"=>"Los datos del proveedor se registraron con éxito en el sistema",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido registrar el proveedor, por favor intente nuevamente",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El nombre que acaba de ingresar ya se encuentra registrado en el sistema",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador paginador proveedor  ----------*/
		public function paginador_proveedor_controlador($pagina,$registros,$privilegio,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedor WHERE  (ProveedorNombre LIKE '%$busqueda%' OR ProveedorTelefono LIKE '%$busqueda%' OR ProveedorEmail LIKE '%$busqueda%') ORDER BY ProveedorNombre ASC LIMIT $inicio,$registros";
				$paginaurl="providersearch";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM proveedor ORDER BY ProveedorNombre ASC LIMIT $inicio,$registros";
				$paginaurl="providerlist";
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
						<th class="text-center">NOMBRE</th>
						<th class="text-center">TELÉFONO</th>
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
							<td>'.$rows['ProveedorNombre'].'</td>
							<td>'.$rows['ProveedorTelefono'].'</td>
							<td>'.$rows['ProveedorEmail'].'</td>';
							if($privilegio<=2){
								$tabla.='
									<td>
										<a href="'.SERVERURL.'providerinfo/'.mainModel::encryption($rows['ProveedorCodigo']).'/" class="btn btn-success btn-raised btn-xs">
											<i class="zmdi zmdi-refresh"></i>
										</a>
									</td>
								';
							}
							if($privilegio==1){
								$tabla.='<td>
									<form action="'.SERVERURL.'ajax/proveedorAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['ProveedorCodigo']).'">
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


		/*----------  Controlador eliminar proveedor  ----------*/
		public function eliminar_proveedor_controlador(){
			$codigo=mainModel::decryption($_POST['codigo-del']);
			$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

			$codigo=mainModel::limpiar_cadena($codigo);
			$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

			if($adminPrivilegio==1){

				$consulta1=mainModel::ejecutar_consulta_simple("SELECT ProveedorCodigo FROM libro WHERE ProveedorCodigo='$codigo'");

				if($consulta1->rowCount()==0){
					$delProv=proveedorModelo::eliminar_proveedor_modelo($codigo);

					if($delProv->rowCount()>=1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"Proveedor eliminado",
							"Texto"=>"El proveedor fue eliminado del sistema con éxito",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No podemos eliminar este proveedor en estos momentos, por favor intente nuevamente",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No podemos eliminar este proveedor ya que hay libros asociados a este",
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


		/*----------  Controlador datos proveedor  ----------*/
		public function datos_proveedor_controlador($tipo,$codigo){
			$codigo=mainModel::decryption($codigo);
			$tipo=mainModel::limpiar_cadena($tipo);

			return proveedorModelo::datos_proveedor_modelo($tipo,$codigo);
		}


		/*----------  Controlador actualizar proveedor  ----------*/
		public function actualizar_proveedor_controlador(){
			$codigo=mainModel::decryption($_POST['codigo-up']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-up']);
			$responsable=mainModel::limpiar_cadena($_POST['responsable-up']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-up']);
			$email=mainModel::limpiar_cadena($_POST['email-up']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-up']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT * FROM proveedor WHERE ProveedorCodigo='$codigo'");
			$dataProv=$consulta1->fetch();

			if($nombre!=$dataProv['ProveedorNombre']){
				$consulta2=mainModel::ejecutar_consulta_simple("SELECT ProveedorNombre FROM proveedor WHERE ProveedorNombre='$nombre'");
				if($consulta2->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El nombre que acaba de ingresar ya se encuentra asignado a otro proveedor",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}

			$dataProvUp=[
				"Nombre"=>$nombre,
				"Responsable"=>$responsable,
				"Telefono"=>$telefono,
				"Email"=>$email,
				"Direccion"=>$direccion,
				"Codigo"=>$codigo
			];


			if(proveedorModelo::actualizar_proveedor_modelo($dataProvUp)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Proveedor actualizado!",
					"Texto"=>"Los datos del proveedor han sido actualizados con éxito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos del proveedor, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}
		
	}