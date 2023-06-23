<?php
	if($peticionAjax){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}

	class categoriaModelo extends mainModel{

		/*----------  Modelo agregar categoria  ----------*/
		protected function agregar_categoria_modelo($datos){
			$query=mainModel::conectar()->prepare("INSERT INTO categoria(CategoriaCodigo,CategoriaNombre) VALUES(:Codigo,:Nombre)");
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->bindParam(":Nombre",$datos['Nombre']);
			$query->execute();
			return $query;
		}


		/*----------  Modelo eliminar categoria  ----------*/
		protected function eliminar_categoria_modelo($codigo){
			$query=mainModel::conectar()->prepare("DELETE FROM categoria WHERE CategoriaCodigo=:Codigo");
			$query->bindParam(":Codigo",$codigo);
			$query->execute();
			return $query;
		}


		/*----------  Modelo datos categoria  ----------*/
		protected function datos_categoria_modelo($tipo,$codigo){
			if($tipo=="Unico"){
				$query=mainModel::conectar()->prepare("SELECT * FROM categoria WHERE id=:Codigo");
				$query->bindParam(":Codigo",$codigo);
			}elseif($tipo=="Conteo"){
				$query=mainModel::conectar()->prepare("SELECT id FROM categoria");
			}elseif($tipo=="Select"){
				$query=mainModel::conectar()->prepare("SELECT CategoriaCodigo,CategoriaNombre FROM categoria ORDER BY CategoriaNombre ASC");
			}
			$query->execute();
			return $query;
		}


		/*----------  Modelo actualizar categoria  ----------*/
		protected function actualizar_categoria_modelo($datos){
			$query=mainModel::conectar()->prepare("UPDATE categoria SET CategoriaCodigo=:Codigo,CategoriaNombre=:Nombre WHERE id=:ID");
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->bindParam(":Nombre",$datos['Nombre']);
			$query->bindParam(":ID",$datos['ID']);
			$query->execute();
			return $query;
		}
	}