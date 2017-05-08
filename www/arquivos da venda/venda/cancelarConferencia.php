<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");

		$idconferencia = $_POST['idconferencia'];

		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		$sql = "SELECT IdVenda 
				FROM nota_conferencia_venda
				WHERE IdNotaConferenciaVenda = $idconferencia";
		//echo $sql;
		$rs = $con->executeQuery($sql);
		if($rs->next()) {
			$idvenda = $rs->get("IdVenda");
		}
		$rs->close();

		$sql = "UPDATE venda_produto SET 
				QtdConferida = 'NULL'
				WHERE IdVenda = $idvenda";
		//echo $sql;
		$con->executeQuery($sql); 
		
		$sql = "DELETE FROM nota_conferencia_venda
				WHERE IdNotaConferenciaVenda = $idconferencia
				AND Fechada = '0'";
		//echo $sql;
		if($con->executeUpdate($sql) == 1){
			$isConferenciaOK = TRUE;

			$sql = "DELETE FROM notaconferenciavenda_produto
					WHERE IdNotaConferenciaVenda = $idconferencia";
			//echo $sql;
			if($con->executeUpdate($sql) == 1){
				$isConferenciaOK = TRUE;	
			}
		}
	
		if($isConferenciaOK)
			echo '1';
?>