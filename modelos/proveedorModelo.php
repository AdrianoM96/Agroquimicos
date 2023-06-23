<?php
	if($peticionAjax){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}

	class proveedorModelo extends mainModel{

		/*----------  Modelo agregar proveedor  ----------*/
		protected function agregar_proveedor_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO proveedor(ProveedorCodigo,ProveedorNombre,ProveedorResponsable,ProveedorTelefono,ProveedorEmail,ProveedorDireccion) VALUES(:Codigo,:Nombre,:Responsable,:Telefono,:Email,:Direccion)");
			$sql->bindParam(":Codigo",$datos['Codigo']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Responsable",$datos['Responsable']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Email",$datos['Email']);
			$sql->bindParam(":Direccion",$datos['Direccion']);
			$sql->execute();
			return $sql;
		}


		/*----------  Modelo eliminar proveedor  ----------*/
		protected function eliminar_proveedor_modelo($codigo){
			$query=mainModel::conectar()->prepare("DELETE FROM proveedor WHERE ProveedorCodigo=:Codigo");
			$query->bindParam(":Codigo",$codigo);
			$query->execute();
			return $query;
		}


		/*----------  Modelo datos proveedor  ----------*/
		protected function datos_proveedor_modelo($tipo,$codigo){
			if($tipo=="Unico"){
				$query=mainModel::conectar()->prepare("SELECT * FROM proveedor WHERE ProveedorCodigo=:Codigo");
				$query->bindParam(":Codigo",$codigo);
			}elseif($tipo=="Conteo"){
				$query=mainModel::conectar()->prepare("SELECT id FROM proveedor");
			}elseif($tipo=="Select"){
				$query=mainModel::conectar()->prepare("SELECT ProveedorCodigo,ProveedorNombre FROM proveedor ORDER BY ProveedorNombre ASC");
			}
			$query->execute();
			return $query;
		}


		/*----------  Modelo actualizar proveedor  ----------*/
		protected function actualizar_proveedor_modelo($datos){
			$query=mainModel::conectar()->prepare("UPDATE proveedor SET ProveedorNombre=:Nombre,ProveedorResponsable=:Responsable,ProveedorTelefono=:Telefono,ProveedorEmail=:Email,ProveedorDireccion=:Direccion WHERE ProveedorCodigo=:Codigo");
			$query->bindParam(":Nombre",$datos['Nombre']);
			$query->bindParam(":Responsable",$datos['Responsable']);
			$query->bindParam(":Telefono",$datos['Telefono']);
			$query->bindParam(":Email",$datos['Email']);
			$query->bindParam(":Direccion",$datos['Direccion']);
			$query->bindParam(":Codigo",$datos['Codigo']);
			$query->execute();
			return $query;
		}

	}