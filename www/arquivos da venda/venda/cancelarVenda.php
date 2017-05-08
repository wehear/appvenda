<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idvenda = $_POST['idvenda'];
		$motivocancelamento = $_POST['motivocancelamento'];
		$acao = $_POST['acao'];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		if($acao == 'abandonar'){
			$sql = "SELECT Nome FROM funcionario WHERE IdFuncionario = '$funcionario[idfuncionario]'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next())
				$nomefuncionario = $rs->get(0);
			$rs->close();
		
			$motivocancelamento = 'Venda abandonada em '.date("d/m/Y H:i").' - Funcionário: '.$nomefuncionario;
		}
			
		$sql = "SELECT Baixa FROM venda WHERE IdVenda = '$idvenda'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next())
			$baixa = $rs->get(0);
		$rs->close();
		
		$sql = "SELECT venda_produto.IdVendaProduto, venda_produto.IdProduto, venda_produto.IdGrade, Quantidade FROM venda_produto WHERE IdVenda = $idvenda";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while ($rs->next ()) {
		
			$quantidadelinha++;
			${"idvendaproduto$quantidadelinha"} = $rs->get("IdVendaProduto");
			${"idproduto$quantidadelinha"} = $rs->get("IdProduto");
			${"idgrade$quantidadelinha"} = $rs->get("IdGrade");
			${"quantidade$quantidadelinha"} = $rs->get("Quantidade");
					
		}
		$rs->close();

		$dataatual = date("Y-m-d");
		$horaatual = date("H:i:s");

		// Seleção do novo IdVendaCancelada;
		$sql = "SELECT MAX(IdVendaCancelada) FROM venda_cancelada";
		$rs = $con->executeQuery ($sql);
		if($rs->next()){
			$idvendacancelada = $rs->get(0) + 1;
		

		// Inserindo os dados da venda cancelada no banco.
		$sql = "INSERT INTO venda_cancelada (IdVendaCancelada, IdVenda, IdFuncionario, IdLoja, Data, Hora, Motivo)";
		$sql = "$sql VALUES ('$idvendacancelada', '$idvenda', '$funcionario[idfuncionario]', '$funcionario[idloja]', '$dataatual', '$horaatual', '$motivocancelamento')";
		//echo $sql;
		if($con->executeUpdate($sql))
			$isCancelamentoVenda = TRUE;
		}
		$rs->close();
		
		// Update para tornar a venda cancelada.
		$sql = "UPDATE venda SET";
		$sql = "$sql Cancelada = 1,";
		$sql = "$sql Ativo = 0";
		$sql = "$sql WHERE IdVenda = '$idvenda'";
		if ($con->executeUpdate($sql) == 1)
			$isVendaCancelada = TRUE;

		$sql = "UPDATE parcela_venda, parcela SET";
		$sql = "$sql parcela.Ativo = 0,";
		$sql = "$sql parcela_venda.Ativo = 0";
		$sql = "$sql WHERE parcela.IdParcela = parcela_venda.IdParcela AND parcela_venda.IdVenda = '$idvenda'";
		//echo $sql;
		if ($con->executeUpdate($sql) == 1)
			$isParcelaCancelada = TRUE;

		if($baixa) {

			// Seleção do  IdRecebimento;
			$sql = "SELECT IdRecebimento FROM recebimento_venda WHERE IdVenda = '$idvenda'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next())
				$idrecebimento = $rs->get(0);
			$rs->close();

			// Update para tornar a venda cancelada.
			$sql = "UPDATE recebimento SET";
			$sql = "$sql Ativo = 0";
			$sql = "$sql WHERE IdRecebimento = '$idrecebimento'";
			if ($con->executeUpdate($sql) == 1)
				$isVendaCancelada = TRUE;

			// Update para tornar a venda cancelada.
			$sql = "UPDATE recebimento_formapagamento SET";
			$sql = "$sql Ativo = 0";
			$sql = "$sql WHERE IdRecebimento = '$idrecebimento'";
			if ($con->executeUpdate($sql) == 1)
				$isVendaCancelada = TRUE;
				
			// verificar depois como fará esse cancelamento

		/* 	// Cálculo do idconta.
			$sql = "SELECT IdCtaCorrenteCliente, Lancamento FROM ctacliente_lancamento WHERE IdVenda = '$idvenda'";
			$rs = $con->executeQuery ($sql);
			if($rs->next()){
				$idconta = $rs->get(0);
				$totallancamento = $rs->get(1);
			}
			$rs->close();

			$sql = "UPDATE ctacorrente_cliente SET";
			$sql = "$sql SaldoAtual = SaldoAtual - $totallancamento";
			$sql = "$sql WHERE IdCtaCorrenteCliente = '$idconta'";
			//echo $sql;
			if ($con->executeUpdate($sql) == 1)
				$isUpdateContaCorrente = TRUE;

			$sql = "UPDATE ctacliente_lancamento SET";
			$sql = "$sql Ativo = 0";
			$sql = "$sql WHERE IdCtaCorrenteCliente = '$idconta' AND IdVenda = '$idvenda'";
			if ($con->executeUpdate($sql) == 1)
				$isUpdateContaCorrente = TRUE; */
		}

		// Atualização da tabela produto. Retorno dos produtos do romaneio para BD (tabela produto).
		for ($i = 1; $i <= $quantidadelinha; $i++) {
			//echo ${"quantidade$i"};
			if(${"quantidade$i"} >= 0) {

				$sql = "UPDATE estoque_grade SET";
				$sql = "$sql Quantidade = Quantidade + '${"quantidade$i"}'";
				$sql = "$sql WHERE IdGrade = '${"idgrade$i"}' AND IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				if ($con->executeUpdate($sql) == 1)
					$isRetornoProduto = TRUE;

				$sql = "UPDATE estoque SET";
				$sql = "$sql Quantidade = Quantidade + '${"quantidade$i"}'";
				$sql = "$sql WHERE IdProduto = '${"idproduto$i"}' AND IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				if ($con->executeUpdate($sql) == 1)
					$isRetornoProduto = TRUE;
			}
			else{
				// Seleção do  IdRecebimento;
				$sql = "SELECT IdVendaProduto FROM produto_troca WHERE IdVendaProduto = '${"idvendaproduto$i"}'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if($rs->next()){
					$idvendaproduto = $rs->get(0);
					
					$sql = "UPDATE estoque_grade SET";
					$sql = "$sql Quantidade = Quantidade + '${"quantidade$i"}'";
					$sql = "$sql WHERE IdGrade = '${"idgrade$i"}' AND IdLoja = '$funcionario[idloja]'";
					//echo $sql;
					if ($con->executeUpdate($sql) == 1)
						$isRetornoProduto = TRUE;

					$sql = "UPDATE estoque SET";
					$sql = "$sql Quantidade = Quantidade + '${"quantidade$i"}'";
					$sql = "$sql WHERE IdProduto = '${"idproduto$i"}' AND IdLoja = '$funcionario[idloja]'";
					//echo $sql;
					if ($con->executeUpdate($sql) == 1)
						$isRetornoProduto = TRUE;
				}
				$rs->close();
			}
		}
		
		echo '1';
?>