<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$idvenda = $_POST['idvenda'];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 

		if($idvenda)  {
		 	
		 	$sql = "SELECT IdCliente, IdFuncionario FROM venda";
			$sql = "$sql WHERE IdVenda = '$idvenda'";
			//echo $sql;						
			$rs = $con->executeQuery ($sql);
			if($rs->next ()) {			 	
				$idcliente = $rs->get(0);								
				$idfuncionario = $rs->get(1);
				$idtabelavenda = 1;	
			}
			$rs->close();
			
		 	$sql = "SELECT Fator FROM tabela_venda";
			$sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda'";
			//echo $sql;						
			$rs = $con->executeQuery ($sql);
			if($rs->next ()) {			 	
				$fator = $rs->get(0);	
			}
			$rs->close();

			echo $idvenda.'|'.
				$idcliente.'|'.
				$idfuncionario.'|0|1'.
				$fator;
	    }	 

	
?>