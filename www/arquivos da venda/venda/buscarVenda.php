<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idvenda = $_POST['idvenda'];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 

	 	// Seleção dos dados da forma de pagamento.
		$sql = "SELECT  venda.IdCliente, cliente.Nome AS Cliente, venda.IdFuncionario, funcionario.Nome AS Funcionario, finaliza_venda.IdTabelaVenda,				tabela_venda.Prazo, tabela_venda.Fator, funcionario.NumeroVenda, venda.IdTipoVenda FROM tabela_venda, funcionario, cliente, venda, finaliza_venda";
		$sql = "$sql WHERE venda.IdVenda = '$idvenda' AND  finaliza_venda.IdTabelaVenda = tabela_venda.IdTabelaVenda AND venda.IdLoja = '$funcionario[idloja]' AND venda.IdFuncionario = funcionario.IdFuncionario AND venda.IdCliente = cliente.IdCliente AND venda.IdVenda = finaliza_venda.IdVenda	AND venda.Fechada = 1 AND venda.Conferida = 0";
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {				
			$idcliente = $rs->get("IdCliente");
			$nomecliente = $rs->get("Cliente");
			$idfuncionario = $rs->get("IdFuncionario");
			$nomefuncionario = $rs->get("Funcionario");
			$idtabelavenda = $rs->get("IdTabelaVenda");
			$prazo = $rs->get("Prazo");
			$fator = $rs->get("Fator");
			$numerovenda = $rs->get("NumeroVenda");
			if($rs->get("IdTipoVenda") == "8")
				$especial = 1;
			$sql = "SELECT IdNotaConferenciaVenda	FROM nota_conferencia_venda	WHERE IdVenda = $idvenda";
			//echo $sql;
			$rs1 = $con->executeQuery ($sql);
			if($rs1->next()) {
				$idnotaconferenciavenda = $rs1->get("IdNotaConferenciaVenda"); 
			}
			$rs1->close();

		}else{
			unset($idcliente);
			$msgErro = 'Venda não encontrada!';
		}
		$rs->close();


		echo $idcliente.'|'.
			utf8_encode($nomecliente).'|'.
			$idfuncionario.'|'.
			$numerovenda.'|'.
			utf8_encode($nomefuncionario).'|'.
			$idtabelavenda.'|'.
			utf8_encode($prazo).'|'.
			$fator.'|'.
			$idnotaconferenciavenda.'|'.
			utf8_encode($msgErro).'|'.
			$especial;
	
?>
