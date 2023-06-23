<?php
	if($peticionAjax){
		require_once "../modelos/libroModelo.php";
	}else{
		require_once "./modelos/libroModelo.php";
	}

	class libroControlador extends libroModelo{

		/*----------  Controlador agregar libro  ----------*/
		public function agregar_libro_controlador(){
			$codigo=mainModel::limpiar_cadena($_POST['codigo-reg']);
			$titulo=mainModel::limpiar_cadena($_POST['titulo-reg']);
			$autor=mainModel::limpiar_cadena($_POST['autor-reg']);
			$pais=mainModel::limpiar_cadena($_POST['pais-reg']);
			$year=mainModel::limpiar_cadena($_POST['year-reg']);
			$editorial=mainModel::limpiar_cadena($_POST['editorial-reg']);
			$edicion=mainModel::limpiar_cadena($_POST['edicion-reg']);


			$empresa=mainModel::decryption($_POST['empresa-reg']);
			$categoria=mainModel::decryption($_POST['categoria-reg']);
			$proveedor=mainModel::decryption($_POST['proveedor-reg']);


			$precio=mainModel::limpiar_cadena($_POST['precio-reg']);
			$precio=number_format($precio, 2, '.', '');

			$ejemplares=mainModel::limpiar_cadena($_POST['ejemplares-reg']);
			$ubicacion=mainModel::limpiar_cadena($_POST['ubicacion-reg']);


			$resumen=mainModel::limpiar_cadena($_POST['resumen-reg']);
			$descarga=mainModel::limpiar_cadena($_POST['optionsPDF']);


			/* Verificando el codigo del libro */
			$consulta1=mainModel::ejecutar_consulta_simple("SELECT LibroCodigo FROM libro WHERE LibroCodigo='$codigo'");
			if($consulta1->rowCount()>=1){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El código de libro que acaba de ingresar ya se encuentra registrado en el sistema.",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}


			/* Verificando el titulo y autor del libro */
			$consulta2=mainModel::ejecutar_consulta_simple("SELECT id FROM libro WHERE LibroTitulo='$titulo' AND LibroAutor='$autor'");
			if($consulta2->rowCount()>=1){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El título y autor que acaba de ingresar corresponden a otro libro que ya se encuentra registrado en el sistema.",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}


			if($empresa==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Selecciona una empresa para poder registrar los datos del libro.",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}


			if($categoria==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Selecciona una categoría para poder registrar los datos del libro.",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}


			if($proveedor==""){
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Selecciona un proveedor para poder registrar los datos del libro.",
					"Tipo"=>"error"
				];
				return mainModel::sweet_alert($alerta);
				exit();
			}

			/* Valores en KB para peso maximo de archivos */
			$imgMaxSize=1024;
			$pdfMaxSize=10240;


			/* Directorios de archivos */
			$imgDir='../adjuntos/img/';
			$pdfDir='../adjuntos/pdf/';


			$consulta3=mainModel::ejecutar_consulta_simple("SELECT id FROM libro");
			$numero=($consulta3->rowCount())+1;

			$fileCodigo=mainModel::generar_codigo_aleatorio("FL",10,$numero);


			/* Cargando imagen si se ha seleccionado */
			if($_FILES['imagen']['name']!="" && $_FILES['imagen']['size']>0){
				if($_FILES['imagen']['type']=="image/jpeg" || $_FILES['imagen']['type']=="image/png"){
					if(($_FILES['imagen']['size']/1024)<=$imgMaxSize){

						switch ($_FILES['imagen']['type']) {
			              case 'image/jpeg':
			                $imgExt=".jpg";
			              break;
			              case 'image/png':
			                $imgExt=".png";
			              break;
			            }

						chmod($imgDir, 0777);
						$imgFinalName=$fileCodigo.$imgExt;

						if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $imgDir.$imgFinalName)){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"No podemos subir la imagen al sistema en este momento, por favor intente nuevamente.",
								"Tipo"=>"error"
							];
							return mainModel::sweet_alert($alerta);
							exit();
						}
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"La imagen que ha seleccionado supera el límite de peso permitido.",
							"Tipo"=>"error"
						];
						return mainModel::sweet_alert($alerta);
						exit();
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"La imagen que ha seleccionado es de un formato que no está permitido.",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}else{
				$imgFinalName="default.png";
			}


			/* Cargando pdf si se ha seleccionado */
			if($_FILES['pdf']['name']!="" && $_FILES['pdf']['size']>0){
				if($_FILES['pdf']['type']=="application/pdf"){
					if(($_FILES['pdf']['size']/1024)<=$pdfMaxSize){

						chmod($pdfDir, 0777);
						$pdfFinalName=$fileCodigo.".pdf";

						if(!move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfDir.$pdfFinalName)){
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"No podemos subir el archivo PDF al sistema en este momento, por favor intente nuevamente.",
								"Tipo"=>"error"
							];
							return mainModel::sweet_alert($alerta);
							exit();
						}
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El archivo PDF que ha seleccionado supera el límite de peso permitido.",
							"Tipo"=>"error"
						];
						return mainModel::sweet_alert($alerta);
						exit();
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"El archivo PDF que ha seleccionado es de un formato que no está permitido.",
						"Tipo"=>"error"
					];
					return mainModel::sweet_alert($alerta);
					exit();
				}
			}else{
				$descarga="No";
				$pdfFinalName="";
			}


			$datosLibro=[
				"Codigo"=>$codigo,
				"Titulo"=>$titulo,
				"Autor"=>$autor,
				"Pais"=>$pais,
				"Year"=>$year,
				"Editorial"=>$editorial,
				"Edicion"=>$edicion,
				"Precio"=>$precio,
				"Stock"=>$ejemplares,
				"Ubicacion"=>$ubicacion,
				"Resumen"=>$resumen,
				"Imagen"=>$imgFinalName,
				"PDF"=>$pdfFinalName,
				"Descarga"=>$descarga,
				"Categoria"=>$categoria,
				"Proveedor"=>$proveedor,
				"Empresa"=>$empresa
			];

			$guardarLibro=libroModelo::agregar_libro_modelo($datosLibro);

			if($guardarLibro->rowCount()==1){
				$alerta=[
					"Alerta"=>"limpiar",
					"Titulo"=>"¡Libro registrado!",
					"Texto"=>"Los datos del libro fueron registrados en el sistema con éxito.",
					"Tipo"=>"success"
				];
			}else{

				if(is_file($imgDir.$imgFinalName)){
					chmod($imgDir.$imgFinalName, 0777);
					unlink($imgDir.$imgFinalName);
				}

				if(is_file($pdfDir.$pdfFinalName)){
					chmod($pdfDir.$pdfFinalName, 0777);
					unlink($pdfDir.$pdfFinalName);
				}

				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos podido almacenar los datos del libro en el sistema, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador catalogo libro  ----------*/
		public function catalogo_libro_controlador($pagina,$registros,$categoria,$busqueda,$privilegio){
			$pagina=mainModel::limpiar_cadena($pagina);
			$registros=mainModel::limpiar_cadena($registros);
			$categoriaEn=mainModel::limpiar_cadena($categoria);
			$categoria=mainModel::decryption($categoriaEn);
			$busqueda=mainModel::limpiar_cadena($busqueda);
			$privilegio=mainModel::limpiar_cadena($privilegio);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;


			if(isset($busqueda) && $busqueda!=""){
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM libro WHERE  (LibroTitulo LIKE '%$busqueda%' OR LibroAutor LIKE '%$busqueda%' OR LibroPais LIKE '%$busqueda%' OR LibroYear LIKE '%$busqueda%' OR LibroEditorial LIKE '%$busqueda%') ORDER BY LibroTitulo ASC LIMIT $inicio,$registros";
				$paginaurl="search";
			}else{
				$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM libro WHERE CategoriaCodigo='$categoria' ORDER BY LibroTitulo ASC LIMIT $inicio,$registros";
				$paginaurl="catalog".'/'.$categoriaEn;
			}


			$conexion = mainModel::conectar();

			$datos = $conexion->query($consulta);

			$datos = $datos->fetchAll();

			$total = $conexion->query("SELECT FOUND_ROWS()");
			$total = (int) $total->fetchColumn();

			$Npaginas =ceil($total/$registros);


			/* Inicio lista*/

			$tabla.='<div class="row"><div class="col-xs-12">';

			if($total>=1 && $pagina<=$Npaginas){

				if(isset($busqueda) && $busqueda!=""){
					$tabla.='<h2 class="text-titles text-center">Resultados de la búsqueda <strong>"'.$busqueda.'"</strong></h2>';
				}else{
					$selCat=mainModel::ejecutar_consulta_simple("SELECT CategoriaNombre FROM categoria WHERE CategoriaCodigo='$categoria'");
					$catNombre=$selCat->fetch();
					$tabla.='<h2 class="text-titles text-center">Libros en la categoría <strong>"'.$catNombre['CategoriaNombre'].'"</strong></h2>';
				}

				$tabla.='<div class="list-group">';
				$contador=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
					<div class="list-group-item">
						<div class="row-picture">';
						if(is_file("./adjuntos/img/".$rows['LibroImagen'])){
							$tabla.='<img class="circle" src="'.SERVERURL.'adjuntos/img/'.$rows['LibroImagen'].'" alt="'.$rows['LibroTitulo'].'">';
						}else{
							$tabla.='<img class="circle" src="'.SERVERURL.'adjuntos/img/default.png" alt="'.$rows['LibroTitulo'].'">';	
						}
						$tabla.='</div>
						<div class="row-content">
							<h4 class="list-group-item-heading">'.$contador.' - '.$rows['LibroTitulo'].'</h4>
							<p class="list-group-item-text">
								<strong>Autor: </strong>'.$rows['LibroAutor'].' &nbsp; <strong>País: </strong>'.$rows['LibroPais'].' &nbsp; <strong>Año: </strong>'.$rows['LibroYear'].'<br>
								<a href="'.SERVERURL.'bookinfo/'.mainModel::encryption($rows['id']).'/" class="btn btn-primary" title="Más información"><i class="zmdi zmdi-info"></i></a>';

								if(is_file("./adjuntos/pdf/".$rows['LibroPDF']) && $rows['LibroDescarga']=="Si"){
									$tabla.='
									<a href="'.SERVERURL.'adjuntos/pdf/'.$rows['LibroPDF'].'" target="_blank" class="btn btn-primary" title="Ver PDF"><i class="zmdi zmdi-file"></i></a>
									<a href="'.SERVERURL.'adjuntos/pdf/'.$rows['LibroPDF'].'" download="'.$rows['LibroTitulo'].'" class="btn btn-primary" title="Descargar PDF"><i class="zmdi zmdi-cloud-download"></i></a>
									';
								}

								if($privilegio==1 || $privilegio==2){
									$tabla.='<a href="'.SERVERURL.'bookconfig/'.mainModel::encryption($rows['id']).'/" class="btn btn-primary" title="Gestionar libro"><i class="zmdi zmdi-wrench"></i></a>';
								}
						$tabla.='</p>
						</div>
					</div>
					<div class="list-group-separator"></div>
					';
				}
				$tabla.='</div>';
			}else{
				if($total>=1){
						$tabla.='
							<p class="text-center" style="font-size: 200px; "><i class="zmdi zmdi-rotate-left"></i></p>
							<p class="text-center"><a href="'.SERVERURL.$paginaurl.'/" class="btn btn-raised btn-primary"><i class="zmdi zmdi-rotate-left"></i> &nbsp; CLIC AQUÍ PARA RECARGAR LISTADO</a></p>
						';
				}else{
					$tabla.='
						<p class="text-center" style="font-size: 300px; "><i class="zmdi zmdi-mood-bad"></i></p>
						<h2 class="text-titles text-center">No hemos encontrado resultados</h2>
					';
				}
			}

			$tabla.='</div></div>';
			/* Fin lista*/


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


		/*----------  Controlador datos libro  ----------*/
		public function datos_libro_controlador($tipo,$codigo){
			$codigo=mainModel::decryption($codigo);
			
			$codigo=mainModel::limpiar_cadena($codigo);
			$tipo=mainModel::limpiar_cadena($tipo);

			return libroModelo::datos_libro_modelo($tipo,$codigo);
		}


		/*----------  Controlador actualizar libro  ----------*/
		public function actualizar_libro_controlador(){
			$id=mainModel::decryption($_POST['id-up']);
			$codigo=mainModel::limpiar_cadena($_POST['codigo-up']);
			$titulo=mainModel::limpiar_cadena($_POST['titulo-up']);
			$autor=mainModel::limpiar_cadena($_POST['autor-up']);
			$pais=mainModel::limpiar_cadena($_POST['pais-up']);
			$year=mainModel::limpiar_cadena($_POST['year-up']);
			$editorial=mainModel::limpiar_cadena($_POST['editorial-up']);
			$edicion=mainModel::limpiar_cadena($_POST['edicion-up']);


			$empresa=mainModel::decryption($_POST['empresa-up']);
			$categoria=mainModel::decryption($_POST['categoria-up']);
			$proveedor=mainModel::decryption($_POST['proveedor-up']);


			$precio=mainModel::limpiar_cadena($_POST['precio-up']);
			$precio=number_format($precio, 2, '.', '');

			$ejemplares=mainModel::limpiar_cadena($_POST['ejemplares-up']);
			$ubicacion=mainModel::limpiar_cadena($_POST['ubicacion-up']);


			$resumen=mainModel::limpiar_cadena($_POST['resumen-up']);
			$descarga=mainModel::limpiar_cadena($_POST['optionsPDF']);


			$consulta=mainModel::ejecutar_consulta_simple("SELECT * FROM libro WHERE id='$id'");

			if($consulta->rowCount()==1){

				$datosLibro=$consulta->fetch();

				/* Verificando el codigo del libro */
				if($datosLibro['LibroCodigo']!=$codigo){
					$consulta1=mainModel::ejecutar_consulta_simple("SELECT LibroCodigo FROM libro WHERE LibroCodigo='$codigo'");
					if($consulta1->rowCount()>=1){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El código de libro que acaba de ingresar ya se encuentra registrado en el sistema.",
							"Tipo"=>"error"
						];
						return mainModel::sweet_alert($alerta);
						exit();
					}
				}


				/* Verificando el titulo y autor del libro */
				if($datosLibro['LibroTitulo']!=$titulo || $datosLibro['LibroAutor']!=$autor){
					$consulta2=mainModel::ejecutar_consulta_simple("SELECT id FROM libro WHERE LibroTitulo='$titulo' AND LibroAutor='$autor'");
					if($consulta2->rowCount()>=1){
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El título y autor que acaba de ingresar corresponden a otro libro que ya se encuentra registrado en el sistema.",
							"Tipo"=>"error"
						];
						return mainModel::sweet_alert($alerta);
						exit();
					}
				}


				$datosLibroUp=[
					"Codigo"=>$codigo,
					"Titulo"=>$titulo,
					"Autor"=>$autor,
					"Pais"=>$pais,
					"Year"=>$year,
					"Editorial"=>$editorial,
					"Edicion"=>$edicion,
					"Precio"=>$precio,
					"Stock"=>$ejemplares,
					"Ubicacion"=>$ubicacion,
					"Resumen"=>$resumen,
					"Descarga"=>$descarga,
					"Categoria"=>$categoria,
					"Proveedor"=>$proveedor,
					"Empresa"=>$empresa,
					"ID"=>$id
				];

				if(libroModelo::actualizar_libro_modelo($datosLibroUp)){
					$alerta=[
						"Alerta"=>"recargar",
						"Titulo"=>"¡Libro actualizado!",
						"Texto"=>"Los datos del libro han sido actualizados con éxito.",
						"Tipo"=>"success"
					];
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos podido actualizar los datos del libro, por favor intente nuevamente.",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado el libro seleccionado, por favor intente nuevamente.",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador actualizar adjunto libro  ----------*/
		public function actualizar_adjunto_libro_controlador(){
			$id=mainModel::decryption($_POST['adjunto-up-id']);
			$tipo=mainModel::limpiar_cadena($_POST['adjunto-up-tipo']);

			if(($tipo=="img" || $tipo=="pdf") && $_FILES['archivo']['type']!="" && $_FILES['archivo']['size']>0){

				$consulta=mainModel::ejecutar_consulta_simple("SELECT * FROM libro WHERE id='$id'");
				if($consulta->rowCount()==1){

					$datosLibro=$consulta->fetch();
					$fileCodigo=mainModel::generar_codigo_aleatorio("FLU",9,$id);


					if($tipo=="img"){
						$campo="LibroImagen";
						$dir="../adjuntos/img/";
						$fileMaxSize=1024;

						if($_FILES['archivo']['type']=="image/jpeg" || $_FILES['archivo']['type']=="image/png"){

							switch ($_FILES['archivo']['type']) {
				              case 'image/jpeg':
				                $imgExt=".jpg";
				              break;
				              case 'image/png':
				                $imgExt=".png";
				              break;
				            }

							$fileNameUp=$fileCodigo.$imgExt;
						}else{
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"El archivo que ha seleccionado es de un formato no valido, solo se admiten archivos con extensión .png, .jpg.",
								"Tipo"=>"error"
							];
							return mainModel::sweet_alert($alerta);
							exit();
						}
					}


					if($tipo=="pdf"){
						$campo="LibroPDF";
						$dir="../adjuntos/pdf/";
						$fileMaxSize=10240;

						if($_FILES['archivo']['type']=="application/pdf"){
							$fileNameUp=$fileCodigo.".pdf";
						}else{
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"El archivo que ha seleccionado es de un formato no valido, solo se admiten archivos con extensión .png, .jpg.",
								"Tipo"=>"error"
							];
							return mainModel::sweet_alert($alerta);
							exit();
						}
					}

					if(($_FILES['archivo']['size']/1024)<=$fileMaxSize){

						chmod($dir, 0777);

						if(move_uploaded_file($_FILES['archivo']['tmp_name'], $dir.$fileNameUp)){

							$upArchivo=mainModel::ejecutar_consulta_simple("UPDATE libro SET $campo='$fileNameUp' WHERE id='$id'");

							if($upArchivo->rowCount()==1){
								$alerta=[
									"Alerta"=>"recargar",
									"Titulo"=>"¡Archivo actualizado!",
									"Texto"=>"El archivo adjunto ha sido actualizado con éxito.",
									"Tipo"=>"success"
								];
							}else{
								if(is_file($dir.$fileNameUp)){
									chmod($dir.$fileNameUp, 0777);
									unlink($dir.$fileNameUp);
								}
								$alerta=[
									"Alerta"=>"simple",
									"Titulo"=>"Ocurrió un error inesperado",
									"Texto"=>"Lo sentimos no hemos podido actualizar el archivo, por favor intente nuevamente.",
									"Tipo"=>"error"
								];
							}
						}else{
							$alerta=[
								"Alerta"=>"simple",
								"Titulo"=>"Ocurrió un error inesperado",
								"Texto"=>"Lo sentimos no hemos podido guardar el archivo en el sistema, por favor intente nuevamente.",
								"Tipo"=>"error"
							];
						}

					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"El archivo que ha seleccionado supera el límite de peso permitido.",
							"Tipo"=>"error"
						];
					}

				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos encontrado el libro seleccionado.",
						"Tipo"=>"error"
					];
				}

			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado el archivo que quieres actualizar.",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador eliminar adjunto libro  ----------*/
		public function eliminar_adjunto_libro_controlador(){
			$id=mainModel::decryption($_POST['adjunto-del-id']);
			$tipo=mainModel::limpiar_cadena($_POST['adjunto-del-tipo']);

			if($tipo=="img" || $tipo=="pdf"){

				$consulta=mainModel::ejecutar_consulta_simple("SELECT * FROM libro WHERE id='$id'");
				if($consulta->rowCount()==1){

					$datosLibro=$consulta->fetch();

					if($tipo=="img"){
						$campo="LibroImagen";
						$dir="../adjuntos/img/";
						$archivo=$datosLibro['LibroImagen'];
					}

					if($tipo=="pdf"){
						$campo="LibroPDF";
						$dir="../adjuntos/pdf/";
						$archivo=$datosLibro['LibroPDF'];
					}

					$upArchivo=mainModel::ejecutar_consulta_simple("UPDATE libro SET $campo='' WHERE id='$id'");

					if($upArchivo->rowCount()==1){
						if(is_file($dir.$archivo)){
							chmod($dir.$archivo, 0777);
							unlink($dir.$archivo);
						}
						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"¡Archivo eliminado!",
							"Texto"=>"El archivo adjunto ha sido eliminado con éxito.",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No hemos podido eliminar el archivo adjunto.",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos encontrado el libro seleccionado.",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No hemos encontrado el archivo que quieres eliminar.",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}


		/*----------  Controlador eliminar libro  ----------*/
		public function eliminar_libro_controlador(){
			$id=mainModel::decryption($_POST['id-del']);
			$privilegio=mainModel::decryption($_POST['privilegio-del']);

			if($privilegio==1){

				$consulta=mainModel::ejecutar_consulta_simple("SELECT * FROM libro WHERE id='$id'");

				if($consulta->rowCount()==1){

					$datosLibro=$consulta->fetch();

					$delLibro=libroModelo::eliminar_libro_modelo($id);

					if($delLibro->rowCount()==1){

						if(is_file("../adjuntos/img/".$datosLibro['LibroImagen'])){
							chmod("../adjuntos/img/".$datosLibro['LibroImagen'], 0777);
							unlink("../adjuntos/img/".$datosLibro['LibroImagen']);
						}

						if(is_file("../adjuntos/pdf/".$datosLibro['LibroPDF'])){
							chmod("../adjuntos/pdf/".$datosLibro['LibroPDF'], 0777);
							unlink("../adjuntos/pdf/".$datosLibro['LibroPDF']);
						}

						$alerta=[
							"Alerta"=>"recargar",
							"Titulo"=>"¡Libro eliminado!",
							"Texto"=>"Todos los datos y archivos adjuntos asociados al libro han sido eliminados del sistema completamente.",
							"Tipo"=>"success"
						];
					}else{
						$alerta=[
							"Alerta"=>"simple",
							"Titulo"=>"Ocurrió un error inesperado",
							"Texto"=>"No hemos podido eliminar el libro, por favor intente nuevamente.",
							"Tipo"=>"error"
						];
					}
				}else{
					$alerta=[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"No hemos encontrado el libro en la base de datos para eliminarlo.",
						"Tipo"=>"error"
					];
				}
			}else{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Tú no tienes los permisos necesarios para realizar esta operación.",
					"Tipo"=>"error"
				];
			}
			return mainModel::sweet_alert($alerta);
		}
	}