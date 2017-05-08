<?
		session_start();
		$funcionario = $_SESSION["funcionario"];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$idvenda = (int) trim($_POST['idvenda']);
		$idfuncionario = (int) trim($_POST['idfuncionario']);
		$idcliente = (int) trim($_POST['idcliente']);
	
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		$sql = "SELECT IdVenda 
				FROM venda
				WHERE IdFuncionario = $idfuncionario
				AND IdCliente = $idcliente
				AND Fechada = 0 AND Ativo = 1";
		//echo $sql;
		$rs1 = $con->executeQuery ($sql);
		if($rs1->next()) {
			
			$sql = "SELECT venda.IdFuncionario, funcionario.NumeroVenda
					FROM venda, funcionario
					WHERE funcionario.IdFuncionario = venda.IdFuncionario
					AND venda.IdVenda = $idvenda";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
				echo utf8_encode("Não foi possível alterar o funcionário. Já existe venda aberta para esse funcionario e cliente")."|".$rs->get("IdFuncionario")."|".$rs->get("NumeroVenda");
			}
			$rs->close();
		}else{	
			$sql = "UPDATE venda SET
					IdFuncionario = $idfuncionario
					WHERE IdVenda = $idvenda";
			$con->executeQuery ($sql);	
		}
		$rs1->close();
			


?>
