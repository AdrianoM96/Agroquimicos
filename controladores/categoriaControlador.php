<?php
	if($peticionAjax){
		require_once "../modelos/categoriaModelo.php";
	}else{
		require_once "./modelos/categoriaModelo.php";
	}

	class categoriaControlador extends categoriaModelo{

		/*----------  Controlador agregar categoria  ----------*/
		public function agregar_categoria_controlador(){
			$codigo=mainModel::limpiar_cadena($_POST['codigo-reg']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-reg']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT CategoriaCodigo FROM categoria WHERE CategoriaCodigo='$codigo'");

			if($consulta1->rowCount()<=0){

				$consulta2=mainModel::ejecutar_consulta_simple("SELECT CategoriaNombre FROM categoria WHERE CategoriaNombre='$nombre'");

				if($consulta2->rowCount()<=0){

					$dataCat=[
						"Codigo"=>$codigo,
						"Nombre"=>$nombre
					];

					$saveCat=categoriaModelo::agregar_categoria_modelo($dataCat);

					if($saveCat->rowCount()>=1){
						$alerta=[
							"Alerta"=>"limpiar",
							"Titulo"=>"Categoría registrada",
							"Texto"=>"La categoría se registró con éxito en el sistema",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"Lo sentimos, no hemos podido registrar la categoría. Por favor intente nuevamente",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Lo sentimos, el nombre ingresado ya está asociado a una categoría registrada",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Lo sentimos, el código ingresado ya está asociado a una categoría registrada",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador paginador categoria  ----------*/
		public function paginador_categoria_controlador($pagina,$registros,$privilegio,$busqueda){

			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$busqueda=mainModel::limpiar_cadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM categoria WHERE  (CategoriaNombre LIKE '%$busqueda%' OR CategoriaCodigo LIKE '%$busqueda%') ORDER BY CategoriaCodigo ASC LIMIT $inicio,$registros";
				$paginaurl="categorysearch";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM categoria ORDER BY CategoriaCodigo ASC LIMIT $inicio,$registros";
				$paginaurl="categorylist";
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
						<th class="text-center">CÓDIGO</th>
						<th class="text-center">NOMBRE</th>';
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
							<td>'.$rows['CategoriaCodigo'].'</td>
							<td>'.$rows['CategoriaNombre'].'</td>';
							if($privilegio<=2){
								$tabla.='
									<td>
										<a href="'.SERVERURL.'categoryinfo/'.mainModel::encryption($rows['id']).'/" class="btn btn-success btn-raised btn-xs">
											<i class="zmdi zmdi-refresh"></i>
										</a>
									</td>
								';
							}
							if($privilegio==1){
								$tabla.='<td>
									<form action="'.SERVERURL.'ajax/categoriaAjax.php" method="POST" class="FormularioAjax" data-form="delete" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="codigo-del" value="'.mainModel::encryption($rows['CategoriaCodigo']).'">
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
							<td colspan="5">
								<a href="'.SERVERURL.$paginaurl.'/" class="btn btn-sm btn-info btn-raised">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				}else{
					$tabla.='
						<tr>
							<td colspan="5">
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

		/*----------  Controlador eliminar categoria  ----------*/
		public function eliminar_categoria_controlador(){
			$codigo=mainModel::decryption($_POST['codigo-del']);
			$adminPrivilegio=mainModel::decryption($_POST['privilegio-admin']);

			$codigo=mainModel::limpiar_cadena($codigo);
			$adminPrivilegio=mainModel::limpiar_cadena($adminPrivilegio);

			if($adminPrivilegio==1){

				$consulta1=mainModel::ejecutar_consulta_simple("SELECT CategoriaCodigo FROM libro WHERE CategoriaCodigo='$codigo'");

				if($consulta1->rowCount()==0){
					$delCategoria=categoriaModelo::eliminar_categoria_modelo($codigo);

					if($delCategoria->rowCount()>=1){
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"Categoría eliminada",
							"Texto"=>"La categoría fue eliminada del sistema con éxito",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No podemos eliminar esta categoría en estos momentos, por favor intente nuevamente",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No podemos eliminar esta categoría ya que hay libros asociados a esta",
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


		/*----------  Controlador datos categoria  ----------*/
		public function datos_categoria_controlador($tipo,$codigo){
			$codigo=mainModel::decryption($codigo);
			$tipo=mainModel::limpiar_cadena($tipo);

			return categoriaModelo::datos_categoria_modelo($tipo,$codigo);
		}


		/*----------  Controlador actualizar categoria  ----------*/
		public function actualizar_categoria_controlador(){
			$id=mainModel::decryption($_POST['id-up']);
			$id=mainModel::limpiar_cadena($id);

			$codigo=mainModel::limpiar_cadena($_POST['codigo-up']);
			$nombre=mainModel::limpiar_cadena($_POST['nombre-up']);

			$consulta1=mainModel::ejecutar_consulta_simple("SELECT * FROM categoria WHERE id='$id'");
			$dataCat=$consulta1->fetch();

			if($codigo!=$dataCat['CategoriaCodigo']){

				$consulta2=mainModel::ejecutar_consulta_simple("SELECT CategoriaCodigo FROM categoria WHERE CategoriaCodigo='$codigo'");
				if($consulta2->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El código que acaba de ingresar ya se encuentra asignado a otra categoría",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}

				$consulta3=mainModel::ejecutar_consulta_simple("SELECT CategoriaCodigo FROM libro WHERE CategoriaCodigo='".$dataCat['CategoriaCodigo']."'");
				if($consulta3->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No podemos actualizar el código de esta categoría ya que hay libros asociados a esta",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}

			if($nombre!=$dataCat['CategoriaNombre']){
				$consulta4=mainModel::ejecutar_consulta_simple("SELECT CategoriaNombre FROM categoria WHERE CategoriaNombre='$nombre'");
				if($consulta4->rowCount()>=1){
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El nombre que acaba de ingresar ya se encuentra asignado a otra categoría",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}


			$dataUpCat=[
				"Codigo"=>$codigo,
				"Nombre"=>$nombre,
				"ID"=>$id
			];

			if(categoriaModelo::actualizar_categoria_modelo($dataUpCat)){
				$alerta=[
					"Alerta"=>"recargar",
					"Titulo"=>"¡Categoría actualizada!",
					"Texto"=>"Los datos de la categoría han sido actualizados con éxito",
					"Tipo"=>"success"
				];
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido actualizar los datos de la categoría, por favor intente nuevamente",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}
	}