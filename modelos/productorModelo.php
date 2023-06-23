<?php
	if($peticionAjax){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}

	class productorModelo extends mainModel{

		protected function agregar_productor_modelo($datos){

			$sql=mainModel::conectar()->prepare("INSERT INTO productor(ProductorCodigo,Nombre,Apellido,Cuit,Telefono,Direccion,CuentaCodigo) VALUES(:ProductorCodigo,:Nombre,:Apellido,:Cuit,:Telefono,:Direccion,:CuentaCodigo)");
			$sql->bindParam(":ProductorCodigo",$datos['ProductorCodigo']);
			$sql->bindParam(":Nombre",$datos['Nombre']);
			$sql->bindParam(":Apellido",$datos['Apellido']);
			$sql->bindParam(":Cuit",$datos['Cuit']);
			$sql->bindParam(":Telefono",$datos['Telefono']);
			$sql->bindParam(":Direccion",$datos['Direccion']);
			$sql->bindParam(":CuentaCodigo",$datos['CuentaCodigo']);

			$sql->execute();
			return $sql;
		}
	}

 ?>