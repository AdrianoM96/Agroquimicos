<?php
	if($peticionAjax){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}

	class libroModelo extends mainModel{

		/*----------  Modelo agregar libro  ----------*/
		protected function agregar_libro_modelo($datos){
			$query=mainModel::conectar()->prepare("INSERT INTO libro(LibroCodigo,LibroTitulo,LibroAutor,LibroPais,LibroYear,LibroEditorial,LibroEdicion,LibroPrecio,LibroStock,LibroUbicacion,LibroResumen,LibroImagen,LibroPDF,LibroDescarga,CategoriaCodigo,ProveedorCodigo,EmpresaCodigo) VALUES(:Codigo,:Titulo,:Autor,:Pais,:Year,:Editorial,:Edicion,:Precio,:Stock,:Ubicacion,:Resumen,:Imagen,:PDF,:Descarga,:Categoria,:Proveedor,:Empresa)");
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->bindParam(":Titulo",$datos['Titulo']);
			$query->bindParam(":Autor",$datos['Autor']);
			$query->bindParam(":Pais",$datos['Pais']);
			$query->bindParam(":Year",$datos['Year']);
			$query->bindParam(":Editorial",$datos['Editorial']);
			$query->bindParam(":Edicion",$datos['Edicion']);
			$query->bindParam(":Precio",$datos['Precio']);
			$query->bindParam(":Stock",$datos['Stock']);
			$query->bindParam(":Ubicacion",$datos['Ubicacion']);
			$query->bindParam(":Resumen",$datos['Resumen']);
			$query->bindParam(":Imagen",$datos['Imagen']);
			$query->bindParam(":PDF",$datos['PDF']);
			$query->bindParam(":Descarga",$datos['Descarga']);
			$query->bindParam(":Categoria",$datos['Categoria']);
			$query->bindParam(":Proveedor",$datos['Proveedor']);
			$query->bindParam(":Empresa",$datos['Empresa']);
			$query->execute();
			return $query;
		}


		/*----------  Modelo datos libro  ----------*/
		protected function datos_libro_modelo($tipo,$codigo){
			if($tipo=="Unico"){
				$query=mainModel::conectar()->prepare("SELECT * FROM libro WHERE id=:Codigo");
				$query->bindParam(":Codigo",$codigo);
			}elseif($tipo=="Conteo"){
				$query=mainModel::conectar()->prepare("SELECT id FROM libro");
			}
			$query->execute();
			return $query;
		}


		/*----------  Modelo actualizar libro  ----------*/
		protected function actualizar_libro_modelo($datos){
			$query=mainModel::conectar()->prepare("UPDATE libro SET LibroCodigo=:Codigo,LibroTitulo=:Titulo,LibroAutor=:Autor,LibroPais=:Pais,LibroYear=:Year,LibroEditorial=:Editorial,LibroEdicion=:Edicion,LibroPrecio=:Precio,LibroStock=:Stock,LibroUbicacion=:Ubicacion,LibroResumen=:Resumen,LibroDescarga=:Descarga,CategoriaCodigo=:Categoria,ProveedorCodigo=:Proveedor,EmpresaCodigo=:Empresa WHERE id=:ID");
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->bindParam(":Titulo",$datos['Titulo']);
			$query->bindParam(":Autor",$datos['Autor']);
			$query->bindParam(":Pais",$datos['Pais']);
			$query->bindParam(":Year",$datos['Year']);
			$query->bindParam(":Editorial",$datos['Editorial']);
			$query->bindParam(":Edicion",$datos['Edicion']);
			$query->bindParam(":Precio",$datos['Precio']);
			$query->bindParam(":Stock",$datos['Stock']);
			$query->bindParam(":Ubicacion",$datos['Ubicacion']);
			$query->bindParam(":Resumen",$datos['Resumen']);
			$query->bindParam(":Descarga",$datos['Descarga']);
			$query->bindParam(":Categoria",$datos['Categoria']);
			$query->bindParam(":Proveedor",$datos['Proveedor']);
			$query->bindParam(":Empresa",$datos['Empresa']);
			$query->bindParam(":ID",$datos['ID']);
			$query->execute();
			return $query;
		}


		/*----------  Modelo eliminar libro  ----------*/
		protected function eliminar_libro_modelo($codigo){
			$query=mainModel::conectar()->prepare("DELETE FROM libro WHERE id=:Codigo");
			$query->bindParam(":Codigo",$codigo);
			$query->execute();
			return $query;
		}

	}