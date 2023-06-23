<?php 
	if($peticionAjax){
		require_once "../core/mainModel.php";
	}else{
		require_once "./core/mainModel.php";
	}

	class inspeccionModelo extends mainModel{


		protected function solicitar_inspeccion_modelo($datos){
			$sql=mainModel::conectar()->prepare("INSERT INTO inspeccion (InspeccionCodigo,Calle,Numero,Localidad,Latitud,Longuitud,ProductorCodigo) VALUES(:InspeccionCodigo,:Calle,:Numero,:Localidad,:Latitud,:Longuitud,:ProductorCodigo)");		
			$sql->bindParam(":InspeccionCodigo",$datos['InspeccionCodigo']);
			$sql->bindParam(":Calle",$datos['Calle']);
			$sql->bindParam(":Numero",$datos['Numero']);
			$sql->bindParam(":Localidad",$datos['Localidad']);
			$sql->bindParam(":Latitud",$datos['Latitud']);
			$sql->bindParam(":Longuitud",$datos['Longuitud']);
			$sql->bindParam(":ProductorCodigo",$datos['ProductorCodigo']);
			$sql->execute();
			return $sql;
			}

			protected function buscar_cuit_modelo($datos){

				$sql=mainModel::conectar()->prepare("SELECT * FROM productor WHERE Cuit=:Cuit");

				$sql->bindParam(':Cuit',$datos['Cuit']);
				$sql->execute();
				return $sql;
			}

			protected function eliminar_solicitud_modelo($codigo){
			$sql=self::conectar()->prepare("DELETE FROM inspeccion WHERE InspeccionCodigo=:InspeccionCodigo");
			$sql->bindParam(":InspeccionCodigo",$codigo);
			$sql->execute();
			return $sql;
		}


}

 ?>