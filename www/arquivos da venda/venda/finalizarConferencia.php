<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idvenda = $_POST['idvenda'];
		$idconferencia = $_POST['idconferencia'];

		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 

		$sql = "UPDATE venda SET
				Conferida = '1'
				WHERE IdVenda = $idvenda";
		//echo $sql;
		if($con->executeUpdate($sql) == 1)
			$isVendaOK= TRUE;	
		
		if($isVendaOK){
			$sql = "UPDATE nota_conferencia_venda SET
					Fechada = '1'
					WHERE IdNotaConferenciaVenda = $idconferencia";
			//echo $sql;
			if($con->executeUpdate($sql) == 1){
				$isConferenciaOK = TRUE;	
			}else{
				$sql = "UPDATE venda SET
				Conferida = '0'
				WHERE IdVenda = $idvenda";
				//echo $sql;
				$con->executeUpdate($sql);
			}
		}
		if($isVendaOK && $isConferenciaOK)
			echo '1';
?>