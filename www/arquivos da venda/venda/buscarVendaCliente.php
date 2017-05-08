<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$tipovenda = $_POST['tipovenda'];
		$vlcheque = $_POST['vlcheque'];
		$id = $_POST['id'];
	
		// Abertura da conexão.
		$con = new Connection;
		$con->open();  
		
		$formapagamento = explode("|", $_POST['formapagamento']);
		$sizeof_arr = sizeof($formapagamento) - 1;
		$quantidadeformapagamentoparcela = $_POST['quantidadeformapagamentoparcela'];
		$aux = 0;
		
		for($i=1;$i<=$sizeof_arr;$i++){
			$aux++;
			${"idformapagamentoparcela$aux"} = $formapagamento[$i - 1];
			${"nomeformapagamentoparcela$aux"} = $formapagamento[$i];
			${"valorrecebidoparcela$aux"} = str_replace(",", ".", $formapagamento[$i + 1]);
			$i+=2;
		}  
		
		$cheque = explode("|", $_POST['cheque']);
		$sizeof_arr = sizeof($cheque) - 1;
		$quantidadelinhacheque = $_POST['quantidadelinhacheque'];
		$aux = 0;
		 
		for($i=1;$i<=$sizeof_arr;$i++){
			$aux++;
			${"titular$aux"} = $cheque[$i - 1];
			${"dataprevista$aux"} = $cheque[$i];
			${"banco$aux"} = $cheque[$i + 1];
			${"agencia$aux"} = $cheque[$i + 2];
			${"conta$aux"} = $cheque[$i + 3];
			${"cheque$aux"} = $cheque[$i + 4];
			${"valorcheque$aux"} = str_replace(",", ".", $cheque[$i + 5]);
			$somavalorcheque += ${"valorcheque$aux"};
			$i+=6;
		} 
		 
		for($i=1;$i<=$sizeof_arr;$i++){
			$aux++;
			${"titular$aux"} = $cheque[$i - 1];
			${"dataprevista$aux"} = $cheque[$i];
			${"banco$aux"} = $cheque[$i + 1];
			${"agencia$aux"} = $cheque[$i + 2];
			${"conta$aux"} = $cheque[$i + 3];
			${"cheque$aux"} = $cheque[$i + 4];
			${"valorcheque$aux"} = str_replace(",", ".", $cheque[$i + 5]);
			$somavalorcheque += ${"valorcheque$aux"};
			$i+=6;
		}  
		
		$linha = explode("|", $_POST['linha']);
		$sizeof_arr = sizeof($linha) - 1;
		$quantidadelinha = $_POST['quantidadelinha'];
		$aux = 0;
		
		for($i=1;$i<=$sizeof_arr;$i++){
			$aux++;
			${"idvenda$aux"} = $linha[$i - 1];
			${"idresgateconsorcio$aux"} = $linha[$i];
			${"idsinal$aux"} = $linha[$i + 1];
			${"nomevendedor$aux"} = $linha[$i + 2];
			${"numerovenda$aux"} = $linha[$i + 3];
			${"idcliente$aux"} = $linha[$i + 4];
			${"datavenda$aux"} = $linha[$i + 5];
			${"horavenda$aux"} = $linha[$i + 6];
			${"cotacao$aux"} = $linha[$i + 7];
			${"valor$aux"} = str_replace(",", ".", $linha[$i + 8]);
			${"valorfinal$aux"} = str_replace(",", ".", $linha[$i + 9]);
			${"desconto$aux"} = str_replace(",", ".", $linha[$i + 10]);
			${"sinal$aux"} = str_replace(",", ".", $linha[$i + 11]);
			${"porcentagemdesconto$aux"} = str_replace(",", ".", $linha[$i + 12]);
			${"nomecliente$aux"} = $linha[$i + 13];
			${"idtabelavenda$aux"} = $linha[$i + 14];
			${"nomeformapagament$aux"} = $linha[$i + 15];
			${"valormoeda$aux"} = $linha[$i + 16];
			$i+=17;
		} 
		
	//	echo $tipovenda;
	
		// Recuperação das formas de pagamento.
		$sql = "SELECT IdFormaPagamento, Nome, Cheque FROM forma_pagamento WHERE Ativo = 1";
		$rs = $con->executeQuery($sql);
		while($rs->next()) {
			$quantidadeformapagamento++;
			${"idformapagamento$quantidadeformapagamento"} = $rs->get(0);
			${"nomeformapagamento$quantidadeformapagamento"} = $rs->get(1);
			${"cheque$quantidadeformapagamento"} = $rs->get(2);
			${"valorrecebido$quantidadeformapagamento"} = "";

		}
		$rs->close();
			
		if($tipovenda == "VENDA") {
				
		/* 	// Verificação da venda. Se existe e já está baixada.
			$sql = "SELECT IdVenda, Baixa FROM venda WHERE IdVenda = '$id' AND Ativo = 1";
			//echo $sql;
			$rs = $con->executeQuery($sql);
			if($rs->next()) {
				if($rs->get(1)== '1')
					$isVendaBaixada = TRUE;
				else
					$verificaVenda = TRUE;;
			}
			$rs->close(); */

			//if($verificaVenda) {
				// Seleção dos dados da venda.
				$sql = "SELECT funcionario.Nome, venda.IdCliente, finaliza_venda.Data, finaliza_venda.Hora, finaliza_venda.Fator, Desconto, PorcentagemDesc, finaliza_venda.IdTabelaVenda, venda.IdVenda, Cotacao, Valor, ValorServico, Sinal, cliente.Nome FROM venda, finaliza_venda, cliente, funcionario, tabela_venda";
				$sql = "$sql WHERE funcionario.IdFuncionario = venda.IdFuncionario AND cliente.IdCliente = venda.IdCliente AND finaliza_venda.IdTabelaVenda = tabela_venda.IdTabelaVenda AND venda.IdCliente = '$id' AND finaliza_venda.IdVenda = venda.IdVenda AND venda.Baixa = '0'";
				//echo $sql;				
				$rs = $con->executeQuery($sql);
				while($rs->next()) {
					$quantidadelinha++;
					${"nomevendedor$quantidadelinha"} = $rs->get(0);						
					${"idcliente$quantidadelinha"} = $rs->get(1);		
					${"datavenda$quantidadelinha"} = substr($rs->get(2), 8, 2) . "/" . substr($rs->get(2), 5, 2) . "/" . substr($rs->get(2), 0, 4);	
					${"horavenda$quantidadelinha"} = substr($rs->get(3), 0, 5);
					${"fator$quantidadelinha"} = $rs->get(4);
					${"desconto$quantidadelinha"} = $rs->get(5);
					${"porcentagemdesconto$quantidadelinha"} = $rs->get(6);
					${"idvenda$quantidadelinha"} = $rs->get(8);
					${"cotacao$quantidadelinha"} = $rs->get(9);
					$cotacao += ${"cotacao$quantidadelinha"};
					$valort = round(($rs->get(10) + $rs->get(11)) * $rs->get(4), 2);
					${"valor$quantidadelinha"} = ($valort + $rs->get(5));
					${"sinal$quantidadelinha"} = $rs->get(12);
					${"nomecliente$quantidadelinha"} = $rs->get(13);
					
					// Seleção dos dados da venda.
					$sql = "SELECT SUM(ValorMoeda)
							FROM parcela_venda, parcela
							WHERE parcela_venda.IdVenda = '$id'";
					//echo $sql;				
					$rs1 = $con->executeQuery($sql);
					if($rs1->next()) {
						${"valormoeda$quantidadelinha"} = $rs1->get(0);
					}
					$rs1->close();
					
					if(${"cotacao$quantidadelinha"} > 0)
						${"valorfinal$quantidadelinha"} = ((${"valor$quantidadelinha"} * ${"cotacao$quantidadelinha"}) + ${"desconto$quantidadelinha"});
						
					${"valorfinal$quantidadelinha"} = ${"valor$quantidadelinha"} - ${"sinal$quantidadelinha"};
						
					// Seleção da forma de pagamento.
					$sql = "SELECT parcela.IdFormaPagamento, Nome, Valor FROM forma_pagamento, parcela, parcela_venda WHERE parcela.IdParcela = parcela_venda.IdParcela AND forma_pagamento.IdFormaPagamento = parcela.IdFormaPagamento AND parcela.Sinal = 0 AND IdVenda = '${"idvenda$quantidadelinha"}'";
					$rs1 = $con->executeQuery($sql);
					//echo $sql;
					while ($rs1->next()){
						$quantidadeformapagamentoparcela++;
						${"idformapagamentoparcela$quantidadeformapagamentoparcela"} = $rs1->get(0);
						${"nomeformapagamentoparcela$quantidadeformapagamentoparcela"} = $rs1->get(1);
						${"valorrecebidoparcela$quantidadeformapagamentoparcela"} = $rs1->get(2);
					}
					$rs1->close();
				}
				$rs->close();
					
					
				
			//}
		}
		else if($tipovenda == "SINAL") {
		
			// Verificação da Consignação. Se existe e já está baixada.
			$sql = "SELECT IdSinal, Baixa FROM sinal WHERE IdSinal = '$id' AND Ativo = 1";				
			$rs = $con->executeQuery($sql);
			if($rs->next()) {
				if($rs->get(1)=='1')
					$isSinalBaixada = TRUE;
				else
					$verificaSinal = TRUE;
			}
			$rs->close();

			if($verificaSinal) {
				// Seleção dos dados da Consignação.
										
				$sql = "SELECT funcionario.Nome, venda.IdCliente, sinal.Data, sinal.Hora, Sinal, venda.IdVenda, sinal.IdSinal, cliente.Nome FROM venda, venda_sinal, sinal, funcionario, cliente";
				$sql = "$sql WHERE funcionario.IdFuncionario = venda.IdFuncionario AND cliente.IdCliente = venda.IdCliente AND venda.IdCliente = '$id' AND venda.IdVenda = venda_sinal.IdVenda AND sinal.IdSinal = venda_sinal.IdSinal";
				//echo $sql;
				$rs = $con->executeQuery($sql);
				if($rs->next()) {
					$quantidadelinha++;
					${"nomevendedor$quantidadelinha"} = $rs->get(0);
					${"idcliente$quantidadelinha"} = $rs->get(1);
					${"datavenda$quantidadelinha"} = substr($rs->get(2), 8, 2) . "/" . substr($rs->get(2), 5, 2) . "/" . substr($rs->get(2), 0, 4);
					${"horavenda$quantidadelinha"} = substr($rs->get(3), 0, 5);
					${"valor$quantidadelinha"} = $rs->get(4);
					${"idvenda$quantidadelinha"} = $rs->get(5);
					${"idsinal$quantidadelinha"} = $rs->get(6);
					${"nomecliente$quantidadelinha"} = $rs->get(7);
				}
				$rs->close();
					
				// Seleção da forma de pagamento.
				$sql = "SELECT parcela.IdFormaPagamento, Nome, Valor FROM forma_pagamento, parcela, parcela_venda WHERE parcela.IdParcela = parcela_venda.IdParcela AND forma_pagamento.IdFormaPagamento = parcela.IdFormaPagamento AND IdVenda = '${"idvenda$quantidadelinha"}'";
				$rs1 = $con->executeQuery($sql);
				//echo $sql;
				while ($rs1->next()){
					$quantidadeformapagamentoparcela++;
					${"idformapagamentoparcela$quantidadeformapagamentoparcela"} = $rs1->get(0);
					${"nomeformapagamentoparcela$quantidadeformapagamentoparcela"} = $rs1->get(1);
					${"valorrecebidoparcela$quantidadeformapagamentoparcela"} = $rs1->get(2);
				}
				$rs1->close();
			}
		}
		else if($tipovenda == "RESGATE") {
			// Verificação da Consignação. Se existe e já está baixada.
			$sql = "SELECT IdResgateConsorcio, Baixa FROM resgate_consorcio WHERE IdResgateConsorcio = '$id' AND Ativo = 1";
			$rs = $con->executeQuery($sql);
			if($rs->next()) {
				if($rs->get(1)=='1')
					$isResgateBaixada = TRUE;
				else
					$verificaResgate = TRUE;
			}
			$rs->close();

			if($verificaResgate) {
				// Seleção dos dados da Consignação.

				$sql = "SELECT funcionario.Nome, venda.IdCliente, resgate_consorcio.Data, ValorResgate, venda.IdVenda, resgate_consorcio.IdResgateConsorcio, cliente.Nome FROM venda, resgate_consorcio, funcionario, cliente";
				$sql = "$sql WHERE funcionario.IdFuncionario = venda.IdFuncionario AND cliente.IdCliente = venda.IdCliente AND venda.IdCliente = '$id' AND venda.IdVenda = resgate_consorcio.IdVenda";
				// echo $sql;
				$rs = $con->executeQuery($sql);
				if($rs->next()) {
					$quantidadelinha++;
					${"nomevendedor$quantidadelinha"} = $rs->get(0);
					${"idcliente$quantidadelinha"} = $rs->get(1);
					${"datavenda$quantidadelinha"} = substr($rs->get(2), 8, 2) . "/" . substr($rs->get(2), 5, 2) . "/" . substr($rs->get(2), 0, 4);
					${"valor$quantidadelinha"} = $rs->get(3);
					${"idvenda$quantidadelinha"} = $rs->get(4);
					${"idresgateconsorcio$quantidadelinha"} = $rs->get(5);
					${"nomecliente$quantidadelinha"} = $rs->get(6);
				
				}
				$rs->close();
					
				
			}
		}
		
		//verifica contas corrente para o cliente
		$sql = "SELECT IdCtaCorrenteCliente, SaldoAtual, Sigla, Valor
				FROM ctacorrente_cliente, moeda, cotacao
				WHERE moeda.IdMoeda = ctacorrente_cliente.IdMoeda
				AND ctacorrente_cliente.IdMoeda = cotacao.IdMoeda
				AND IdCliente = $id";
		//echo $sql;
		$rs = $con->executeQuery($sql);
		while($rs->next()) {
			$quantidadeconta = $quantidadeconta + 1;
			${"idcontacorrente$quantidadeconta"} = $rs->get("IdCtaCorrenteCliente");
			${"saldoatual$quantidadeconta"} = $rs->get("SaldoAtual");
			${"sigla$quantidadeconta"} = $rs->get("Sigla");
			${"cotacao$quantidadeconta"} = $rs->get("Valor");
		}
		
		for($k = 1; $k <= $quantidadeformapagamentoparcela; $k++) {
			for($j = 1; $j <= $quantidadeformapagamento; $j++) {
				if(${"idformapagamentoparcela$k"} ==  ${"idformapagamento$j"}){
					${"valorrecebido$j"} += ${"valorrecebidoparcela$k"};
				}
			}
		}
		
		if($quantidadeconta){
		
			$html_conta = '
				<table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>		
							<table width="800" align="center" cellpadding="2" cellspacing="2">
								<tr>
									<td colspan="12" align="left">
										<b>Conta Corrente</b>
									</td>
								</tr>
								<tr>
									<td>
										<b>Conta</b>
									</td>
									<td>
										<b>Saldo</b>
									</td>
									<td>
										<b>Cotação</b>
									</td>
									<td>
										<b>Valor a Pagar</b>
									</td>
									<td>
										<b>Valor Convertido</b>
									</td>
									<td>
										<b>Forma Pagamento</b>
									</td>
								</tr>';
				
					for($i = 1;$i<=$quantidadeconta;$i++){
						if(${"saldoatual$i"} > 0){
							
							$html_conta .= '
								<tr>
									<td>
										'.${"sigla$i"}.'
										<input type="hidden" name="idcontacorrente'.$i.'" id="idcontacorrente'.$i.'" value="'.${"idcontacorrente$i"}.'" />
										<input type="hidden" name="saldoatual'.$i.'" id="saldoatual'.$i.'" value="'.${"saldoatual$i"}.'" />
									</td>
									<td>
										'.${"saldoatual$i"}.'
									</td>
									<td>
										<input type="text" name="cotacao'.$i.'" id="cotacao'.$i.'" value="'.${"cotacao$i"}.'" size="8" maxlength="8" />
									</td>
									<td>
										<input type="text" name="debito'.$i.'" id="debito'.$i.'" value="'.${"debito$i"}.'" size="8" maxlength="8" onchange="submeterCotacao('.$i.')"/>
									</td>
									<td>
										<input type="text" name="valor_ct'.$i.'" id="valor_ct'.$i.'" value="'.${"valor_ct$i"}.'" size="8" maxlength="8" readonly="yes"/>
									</td>
									<td>
										<select name = "idformapagamento_ct'.$i.'"  id = "idformapagamento_ct'.$i.'" onchange="submeterFormaPagamentoCta();">
											<option value = "">Selecione</option>';
													
											$sql = "SELECT IdFormaPagamento, Nome, Cheque FROM forma_pagamento WHERE Ativo = 1";
											$rs = $con->executeQuery ($sql);
											while ($rs->next()) {
													
												$html_conta .= '<option value = "'.$rs->get(0).'">'.$rs->get(1).'</option>';
													
											}
											$rs->close();
									$html_conta .= '		
											</select>
									</td>
								</tr>
								';
						}
					}
				
				$html_conta .= '
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
				<br>
			';
		}
		if($quantidadelinha){
			$header = '
				<table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>		
							<table width="800" align="center" cellpadding="2" cellspacing="2">
								<tr bgcolor="#77777">
									<td width="800" height="25" align="center" colspan="10">';

									if($tipovenda == "VENDA") {
										$header .= '<font class="titulo_tabela">
														Relação das Vendas
													</font>';
									}
           
								$header .= '	
									</td>
								</tr>
								<tr height="20">
									<td width="80" align="center"><b>Venda</b></td>
									<td width="100" align="center"><b>Vendedor</b></td>
									<td width="220" align="center"><b>Cliente</b></td>
									<td width="100" align="center"><b>Valor</b></td>
									<td width="100" align="center"><b>Desconto (R$)</b></td>';
											
									if($tipovenda=="SINAL"){
												
										$header .= '<td width="100" align="center"><b>Sinal</b></td>';

									}
									if($cotacao > 0){

										$header .= '<td width="100" align="center"><b>Cotação</b></td>';

									}
									if($tipovenda=="RESGATE"){

										$header .= '<td width="100" align="center"><b>Valor Resgate</b></td>';

									}

								$header .= '
									<td width="100" align="center"><b>Valor Final</b></td>
									<td width="70" align="center"><b>Mudar Prazo</b></td>
									<td width="70" align="center"><b>Mudar <br>Forma Pgto</b></td>
									<td width="70" align="center"><b>Mostrar <br>Produtos</b></td>
								</tr>';
							for($i = 1; $i <= $quantidadelinha; $i++) {
							
								$somavalortotal += ${"valorfinal$i"};
								$body .= '
									<tr>
										<td width="20" align="center">
											'.${"idvenda$i"}.'
											<input type = "hidden" name = "idvenda'.$i.'"  id = "idvenda'.$i.'" value = "'.${"idvenda$i"}.'"/>
										</td>
										<td width="100" align="center">
											'.${"nomevendedor$i"}.'		
											<input type = "hidden" name = "idsinal'.$i.'" id = "idsinal'.$i.'" value = "'.${"idsinal$i"}.'"/>
											<input type = "hidden" name = "idresgateconsorcio'.$i.'" id = "idresgateconsorcio'.$i.'" value = "'.${"idresgateconsorcio$i"}.'"/>
											<input type = "hidden" name = "nomevendedor'.$i.'" id = "nomevendedor'.$i.'" value = "'.${"nomevendedor$i"}.'"/>													
										</td>
										<td width="220" align="center">
											'.${"nomecliente$i"}.'
											<input type = "hidden" name = "nomecliente'.$i.'" id = "nomecliente'.$i.'" value = "'.${"nomecliente$i"}.'" />
										</td>
										<td width="100" align="center">
											'.sprintf("%1.2f", ${"valor$i"}).'
											<input type = "hidden" name = "valor'.$i.'" id = "valor'.$i.'" value="'.sprintf("%1.2f", ${"valor$i"}).'"/>
										</td>
										<td width="100" align="center">
											'.sprintf("%1.2f", ${"desconto$i"}).'
											<input type = "hidden" name = "desconto'.$i.'" id = "desconto'.$i.'" value="'.sprintf("%1.2f", ${"desconto$i"}).'"/>
											<input type = "hidden" name = "fator'.$i.'" id = "fator'.$i.'" value="'.sprintf("%1.2f", ${"fator$i"}).'"/>
											<input type = "hidden" name = "porcentagemdesconto'.$i.'" id = "porcentagemdesconto'.$i.'" value="'.sprintf("%1.2f", ${"porcentagemdesconto$i"}).'"/>																
										</td>';												
											
									if($tipovenda=="SINAL"){

										$body .= '
											<td width="100" align="center">
												'.sprintf("%1.2f", ${"sinal$i"}).'
												<input type = "hidden" name = "sinal'.$i.'" id = "sinal'.$i.'" value="'.sprintf("%1.2f", ${"sinal$i"}).'"/>												
											</td>';

									}
									if($cotacao > 0){

										$body .= '

											<td width="100" align="center">
												'.sprintf("%1.2f", ${"cotacao$i"}).'
												<input type = "hidden" name = "cotacao'.$i.'" id = "cotacao'.$i.'" value="'.sprintf("%1.2f", ${"cotacao$i"}).'"/>	
											</td>';

									}
									$body .= '
										<td width="100" align="center">
											'.sprintf("%1.2f", ${"valorfinal$i"}).'
												<input type = "hidden" name = "valorfinal'.$i.'" id = "valorfinal'.$i.'" value="'.sprintf("%1.2f", ${"valorfinal$i"}).'"/>												
										</td>
										<td width="70" align="center">';
										if(${"idvenda$i"}){
											
											$body .= '
												<a href="#" onclick="submeterPrazo(\''.${"idvenda$i"}.'\');" title="Mudar prazo de pagamento?">
													<img src="../../sistema/images/selecionado.JPG" width="15" height="15" border="0">
												</a>';
										}
									$body .= '
										</td>
										<td width="70" align="center">';
										if(${"idvenda$i"}){
											
											$body .= '
												<a href="#" onclick="submeterForma(\''.${"idvenda$i"}.'\');" title="Mudar forma de pagamento?">
													<img src="../../sistema/images/selecionado.JPG" width="15" height="15" border="0">
												</a>';
										}
									$body .= '
										</td>
										<td width="70" align="center">';
										if(${"idvenda$i"}){
											
											$body .= '
												<a href="#" onclick="submeterMostrarProdutos(\''.${"idvenda$i"}.'\');" title="Mostrar produtos da venda?">
													<img src="../../sistema/images/selecionado.JPG" width="15" height="15" border="0">
												</a>';
										}
									$body .= '
										</td>
									</tr>';
							}
					$footer = '
								</table>
								<table width="800" align="center" cellpadding="2" cellspacing="2">
									<tr height="25" bgcolor="#777777">
										<td width = "800" align = "center">
											<font class = "titulo_tabela">
												<b>Valor Total</b>&nbsp;&nbsp;
													'.sprintf("%1.2f", $somavalortotal).'
												</font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
						
					<br>';
		}
		
		if($somavalortotal != 0) {

			$html_pagamento = '
				
				<table width="250" border="1" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table width="250" cellpadding="2" cellspacing="2">
								<tr height="25" bgcolor="#777777">
									<td width="250" align="center" colspan="2"><font class="titulo_tabela">Forma de Pagamento</font></td>
								</tr>';

							for($j = 1; $j <= $quantidadeformapagamento; $j++) {
								if(${"valorrecebido$j"} != 0){
							
									$html_pagamento .= '
											<tr>
												<td width="200" align="right">
													'.${"nomeformapagamento$j"}.'
												</td>
												<td width="100" align="left">
													<input type = "text" name = "valorrecebido'.$j.'" id = "valorrecebido'.$j.'" value="'.sprintf("%1.2f", ${"valorrecebido$j"}).'" size="13" maxlength="13" readonly="yes">
												</td>
											</tr>';

									}

									if(${"cheque$j"} == 1 && ${"valorrecebido$j"} != 0)
										$vlcheque += ${"valorrecebido$j"};

							}

							$html_pagamento .= '
                           				</table>
									</td>
								</tr>
						</table>
						
						<br>';

		}
		else {
			if($quantidadelinha){

				$html_pagamento .= '<input type="button" name="submeteRecebimento"  id="submeteRecebimento" value="Receber" onclick="submeterRecebimento();"><br>';

			}
		}
		
		if($quantidadelinhacheque) {
	
			$html_chq = '
				<table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<table width="800" align="center" cellpadding="2" cellspacing="2">
								<tr bgcolor="#77777">
									<td width="800" height="25" align="center" colspan="9">
										<font class="titulo_tabela">
											Relação de Cheques
										</font>
									</td>
								</tr>
								<tr height="20">
									<td width="20" align="center">.</td>
									<td width="70" align="center"><b>Titular</b></td>												
									<td width="70" align="center"><b>Banco</b></td>												
									<td width="70" align="center"><b>Agencia</b></td>
									<td width="100" align="center"><b>Conta</b></td>
									<td width="100" align="center"><b>Cheque</b></td>
									<td width="100" align="center"><b>Valor</b></td>
									<td width="100" align="center"><b>Compensação</b></td>
									<td width="20" align="center"><b>.</b></td>
								</tr>';
								
							for($i = 1; $i <= $quantidadelinhacheque; $i++) {
								$somavalorcheque += ${"valorcheque$i"};

								if($i % 2)
									$html_chq .= '<tr bgcolor="#EEEEEE">';
								else
									$html_chq .= '<tr>';
									
									$html_chq .= '
										<td width="20" align="center">'.$i.'</td>
										<td width="70" align="center">
											'.${"titular$i"}.'
											<input type = "hidden" name = "titular'.$i.'" id = "titular'.$i.'" value = "'.${"titular$i"}.'"/>																											
										</td>	
										<td width="70" align="left">
											'.${"banco$i"}.'
											<input type = "hidden" name = "banco'.$i.'" id = "banco'.$i.'" value = "'.${"banco$i"}.'"/>		
										</td>
										<td width="70" align="center">
											'.${"agencia$i"}.'
											<input type = "hidden" name = "agencia'.$i.'" id = "agencia'.$i.'" value = "'.${"agencia$i"}.'"/>	
										</td>
										<td width="100" align="center">
											'.${"conta$i"}.'
											<input type = "hidden" name = "conta'.$i.'" id = "conta'.$i.'" value = "'.${"conta$i"}.'"/>
										</td>
										<td width="100" align="center">
											'.${"cheque$i"}.'
											<input type = "hidden" name = "cheque'.$i.'" id = "cheque'.$i.'" value = "'.${"cheque$i"}.'"/>
										</td>
										<td width="100" align="center">
											'.${"valorcheque$i"}.'
											<input type = "hidden" name = "valorcheque'.$i.'" id = "valorcheque'.$i.'" value = "'.${"valorcheque$i"}.'"/>
										</td>
										<td width="100" align="center">
											'.substr(${"dataprevista$i"}, 8, 2) . " - " . substr(${"dataprevista$i"}, 5, 2) . " - " . substr(${"dataprevista$i"}, 0, 4).'
											<input type = "hidden" name = "dataprevista'.$i.'" id = "dataprevista'.$i.'" value = "'.${"dataprevista$i"}.'"/>
										</td>
										<td width="20" align="center">
											<a href="#" onclick="submeterExclusaoCheque(\''.$i.'\');" title="Excluir este produto da lista."><img src="../../sistema/images/lixo.gif" width="15" border="0"></a>
										</td>													
									</tr>';

							}

						$html_chq .= '</table>
									</td>
								</tr>
							</table>';
		}
		
		for($k = 0; $k <= $quantidadeformapagamentoparcela; $k++) {

			$inputs .= 
				'<input type="hidden" name="idformapagamentoparcela'.$k.'" id="idformapagamentoparcela'.$k.'" value="'.${"idformapagamentoparcela$k"}.'">
				<input type="hidden" name="nomeformapagamentoparcela'.$k.'" id="nomeformapagamentoparcela'.$k.'" value="'.${"nomeformapagamentoparcela$k"}.'">
				<input type="hidden" name="valorrecebidoparcela'.$k.'" id="valorrecebidoparcela'.$k.'" value="'.${"valorrecebidoparcela$k"}.'">';

		}
				
		if(!$vlcheque || ($vlcheque == $somavalorcheque)){

			//if($somavalortotal != 0) {

				$botoes .= '<input type="button" name="submeteRecebimento" id="submeteRecebimento" value="Receber" onclick="submeterRecebimento();"><br>';
	
			//}
		}
		
		$valorcheque = $vlcheque - $somavalorcheque; 
		
		echo utf8_encode($header.$body.$footer.$inputs).'|'.$quantidadelinha.'|'.$quantidadeformapagamentoparcela.'|'.$quantidadelinhacheque.'|'.$html_chq.'|'.$somavalorcheque.'|'.$vlcheque.'|'.sprintf("%1.2f", $valorcheque).'|'.$quantidadeconta.'|'.utf8_encode( $html_conta).'|'.utf8_encode($html_pagamento).'|'.utf8_encode($botoes);
