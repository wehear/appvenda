<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$acao = $_POST['acao'];
		$idcliente = $_POST['idcliente'];
	
		// Abertura da conexão.
		$con = new Connection;
		$con->open();    

		$html = '
			<tr>
				<td width="800" align="center">';
				
		
		if($acao == 'busca_venda'){	
			$html .= '
				<select name = "idvendaselecao" id = "idvendaselecao" onChange = "submeterBuscaVenda();">
					<option value = "">Selecione a Venda</option>';
									
						$sql = "SELECT venda.IdVenda, IdFinalizaVenda, Valor, ValorServico, Fator, Desconto, funcionario.Nome, cliente.Nome, venda.Data FROM venda, finaliza_venda, funcionario, cliente";
						$sql = "$sql WHERE venda.IdFuncionario = funcionario.IdFuncionario AND venda.IdVenda = finaliza_venda.IdVenda AND venda.Baixa = 0 AND venda.Fechada = 1 AND venda.IdLoja = '$funcionario[idloja]' AND venda.IdCliente = cliente.IdCliente AND venda.Cancelada = 0 AND venda.Ativo = 1";
				 		if($idcliente)
							$sql = "$sql AND venda.IdCliente = $idcliente"; 
						$sql = "$sql ORDER BY venda.IdVenda";
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						while ($rs->next()) {
												
							$html .= '<option value = "'.$rs->get(0).'" >'.sprintf("%05d",  $rs->get(0)) . " => Valor: " .sprintf("%12.2f", round(($rs->get(2) + $rs->get(3)) * $rs->get(4), 2) + $rs->get(5)) . " => Vend.: " . $rs->get(6) . " => Cliente.: " . $rs->get(7) . " => Data.: " . substr($rs->get(8), 8, 2) ."/". substr($rs->get(8), 5, 2) ."/". substr($rs->get(8), 0, 4) . '</option>';
													
						}
						$rs->close();
														
					$html .= '</select>';
					
		}else if($acao == 'busca_sinal'){	
			$html .= '
				<select name = "idsinalselecao" onChange="submeterBuscaSinal();">
					<option value = "">Selecione o Sinal</option>';
													
						$sql = "SELECT sinal.IdSinal, venda.IdVenda, Sinal, funcionario.Nome FROM venda, sinal, funcionario, venda_sinal";
						$sql = "$sql WHERE venda.IdFuncionario = funcionario.IdFuncionario AND venda.Cancelada = 0 AND venda.Ativo = 1 AND venda.IdVenda = venda_sinal.IdVenda AND sinal.IdSinal = venda_sinal.IdSinal AND sinal.Baixa = 0 AND venda.IdLoja = '$funcionario[idloja]' ORDER BY venda.IdVenda";
						$rs = $con->executeQuery ($sql);
						while ($rs->next()) {
						
							$html .= '<option value = "'.$rs->get(0).'">'.sprintf("%05d",  $rs->get(1)) . " => Sinal: " . sprintf("%12.2f", $rs->get(2)) . " => Vend.: " . $rs->get(3).'</option>';
													
						}
						$rs->close();
						
				$html .= '</select>';
		}else if($acao == 'busca_resgate'){
		
			$html .= '
				<select name = "idresgateconsorcioselecao" onChange="submeterBuscaResgate();">
					<option value = "">Selecione o Resgate do Consórcio</option>';
													
						$sql = "SELECT resgate_consorcio.IdResgateConsorcio, venda.IdVenda, ValorResgate, funcionario.Nome FROM venda, resgate_consorcio, funcionario";
						$sql = "$sql WHERE venda.IdFuncionario = funcionario.IdFuncionario AND venda.Cancelada = 0 AND venda.Ativo = 1 AND venda.IdVenda = resgate_consorcio.IdVenda AND resgate_consorcio.Baixa = 0 AND venda.IdLoja = '$funcionario[idloja]' ORDER BY venda.IdVenda";
						$rs = $con->executeQuery ($sql);
						while ($rs->next()) {
												
							$html .= '<option value = "'.$rs->get(0).'">'.sprintf("%05d",  $rs->get(1)) . " => Resgate: " . sprintf("%12.2f", $rs->get(2)) . " => Vend.: " . $rs->get(3) . '</option>';
													
						}
						$rs->close();
														
				$html .= '</select>';
		}			
		$html .= '</td></tr>';
				
		echo utf8_encode($html);
