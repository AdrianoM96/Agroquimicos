<?php
	if($peticionAjax){
		require_once "../modelos/productorModelo.php";
	}else{
		require_once "./modelos/productorModelo.php";
	}

	class productorControlador extends productorModelo{

		public function agregar_productor_controlador(){

			$cuit=mainModel::limpiar_cadena($_POST['cuit-reg']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);
			$apellido=mainModel::limpiar_cadena($_POST['apellido-reg']);
			$telefono=mainModel::limpiar_cadena($_POST['telefono-reg']);
			$direccion=mainModel::limpiar_cadena($_POST['direccion-reg']);

			$usuario=mainModel::limpiar_cadena($_POST['usuario-reg']);
			$password1=mainModel::limpiar_cadena($_POST['password1-reg']);
			$password2=mainModel::limpiar_cadena($_POST['password2-reg']);
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
				$consulta1=mainModel::ejecutar_consulta_simple("SELECT Cuit FROM productor WHERE productor='$cuit'");
				if($consulta1->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El CUIT que acaba de ingresar ya se encuentra registrado en el sistema",
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

							$codigo=mainModel::generar_codigo_aleatorio("CP",7,$numero);

							$clave=mainModel::encryption($password1);

							$dataAc=[
								"Codigo"=>$codigo,
								"Privilegio"=>$privilegio,
								"Usuario"=>$usuario,
								"Clave"=>$clave,
								"Email"=>$email,
								"Estado"=>"Activo",
								"Tipo"=>"Productor",
								"Genero"=>$genero,
								"Foto"=>$foto
							];

							$guardarCuenta=mainModel::agregar_cuenta($dataAc);

							if($guardarCuenta->rowCount()>=1){

							$consulta5=mainModel::ejecutar_consulta_simple("SELECT id FROM productor");
							$numeroProd=($consulta5->rowCount())+1;

							$codigoProd=mainModel::generar_codigo_aleatorio("PA",7,$numeroProd);


								$dataProd=[
									"ProductorCodigo"=>$codigoProd,
									"Nombre"=>$nombre,
									"Apellido"=>$apellido,
									"Cuit"=>$cuit,								
									"Telefono"=>$telefono,
									"Direccion"=>$direccion,
									"CuentaCodigo"=>$codigo
								];

								$guardarProductor=productorModelo::agregar_productor_modelo($dataProd);

								if($guardarProductor->rowCount()>=1){
									$alerta=[
										"Alerta"=>"limpiar",
										"Titulo"=>"Productor registrado",
										"Texto"=>"El Productor se registró con éxito en el sistema",
										"Tipo"=>"success"
									];
								}else{
									mainModel::eliminar_cuenta($codigo);
									$alerta=[
										"Alerta"=>"simple",
										"Titulo"=>"Ocurrió un error inesperado",
										"Texto"=>"Noooo hemos podido registrar el productor, por favor intente nuevamente",
										"Tipo"=>"error"
									];
								}
							}else{
								$alerta=[
									"Alerta"=>"simple",
									"Titulo"=>"Ocurrió un error inesperado",
									"Texto"=>"NoOOO hemos podido registrar el productor, por favor intente nuevamente",
									"Tipo"=>"error"
								];
							}
						}
					}
				}
			}
			return mainModel::sweet_alert($alerta);
		}

		/*----------  Controlador paginador administrador  ----------*/
		/*----------  Controlador paginador administrador  ----------*/
		public function paginador_administrador_controlador($pagina,$registros,$privilegio,$codigo,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$codigo=mainModel::limpiar_cadena($codigo);
			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";


			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM productor WHERE (Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Cuit LIKE '%$busqueda%' OR Telefono LIKE '%$busqueda%') ORDER BY Nombre ASC LIMIT $inicio,$registros";
				$paginaurl="productorsearch";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM cuenta ORDER BY  Nombre  ASC  LIMIT $inicio,$registros";
				$paginaurl="productorlist";
			}

			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);//numero de paginas en total
            
            ### Cuerpo de la tabla ###
			$tabla.='
			<div class="table-responsive">
			<table class="table table-hover text-center">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">CUIT</th>
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

			if($total>=1 && $pagina<=$Npaginas){//compurebo si hay registros o no  y si lapaginan solicitada existe
				$contador=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr>
							<td>'.$contador.'</td>
							<td>'.$rows['Cuit'].'</td>
							<td>'.$rows['Nombre'].'</td>
							<td>'.$rows['Apellido'].'</td>
							<td>'.$rows['Telefono'].'</td>';
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
			if($total>=1 && $pagina<=$Npaginas){//si estoy en un apagina que existe se muestra el paginador
				$tabla.='<nav class="text-center"><ul class="pagination pagination-sm">';

				if($pagina==1){//habilita desabilita la flecha a la izquierda
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-left"></i></a></li>';
				}else{
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina-1).'/"><i class="zmdi zmdi-arrow-left"></i></a></li>';
				}

				for($i=1; $i<=$Npaginas; $i++){//muestra la cantidad de paginas a seleccionar
					if($pagina==$i){
						$tabla.='<li class="active"><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					}else{
						$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.$i.'/">'.$i.'</a></li>';
					}
				}

				if($pagina==$Npaginas){//habilita desabilita la flecha a la derecha
					$tabla.='<li class="disabled"><a><i class="zmdi zmdi-arrow-right"></i></a></li>';
				}else{
					$tabla.='<li><a href="'.SERVERURL.$paginaurl.'/'.($pagina+1).'/"><i class="zmdi zmdi-arrow-right"></i></a></li>';
				}

				$tabla.='</ul></nav>';
			}

			return $tabla;
		}




	}	
 ?>