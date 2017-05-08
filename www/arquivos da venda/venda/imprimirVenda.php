<?
    session_start();
    $funcionario = $_SESSION[funcionario];

    include_once ("../../sistema/includes/connection.inc");

    require_once ("../../sistema/includes/funcoes.php");

		if($funcionario[nivel] >= 1) {

			//$idvenda = $_POST['idvenda'];
			$idvenda = $_POST['idvenda'];

			echo $idvenda;

			// abertura de conexão com o banco.
			$con = new Connection;
			$con->open();

          	//$data = date("d-m-Y");
			//$hora = date("H:i");

            // Seleção dos dados do estabelecimento.
			$sql = "SELECT Nome, Endereco, Cidade, Telefone1, Email, WebSite FROM estabelecimento WHERE IdLoja = '$funcionario[idloja]'";
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
				$nomeestabelecimento = $rs->get(0);
			}
			$rs->close();

			$sql = "SELECT cliente.IdCliente, cliente.Nome, NumeroVenda, funcionario.Nome, venda.IdTipoVenda
					FROM venda, cliente, funcionario
					WHERE cliente.IdCliente = venda.IdCliente AND funcionario.IdFuncionario = venda.IdFuncionario AND IdVenda = $idvenda";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next ()) {
				$idcliente = $rs->get(0);
				$nomecliente = $rs->get(1);
				$numerovenda = $rs->get(2);
				$nomefuncionario = $rs->get(3);
				$idtipovenda = $rs->get(4);
     		}
			$rs->close();

			 // Seleção do nome funcionario.
            $sql = "SELECT finaliza_venda.IdTabelaVenda, Prazo, finaliza_venda.Fator, Valor, Desconto, PorcentagemDesc, Data, Hora
					FROM tabela_venda, finaliza_venda
					WHERE tabela_venda.IdTabelaVenda = finaliza_venda.IdTabelaVenda
					AND finaliza_venda.IdVenda = '$idvenda' AND IdLoja = '$funcionario[idloja]'";
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
				$idtabelavenda = $rs->get(0);
				$prazo = $rs->get(1);
				$fator = $rs->get(2);
				$somavalortotal = $rs->get(3);
				$valordesconto = $rs->get(4);
				$porcentagemdesconto = $rs->get(5);
				$data = substr($rs->get(6), 8, 2) ."/". substr($rs->get(6), 5, 2) ."/". substr($rs->get(6), 0, 4);
				$hora = substr($rs->get(7), 0, 5);
			}
			$rs->close();

		 	$sql = "SELECT IdUnidadeVenda, Sigla FROM unidade_venda";
			$sql = "$sql WHERE Ativo = 1";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while($rs->next ()) {
				$quantidadeunidade++;
				${"idunidadevenda$quantidadeunidade"} = $rs->get(0);
				${"siglaunidadevenda$quantidadeunidade"} = $rs->get(1);
				${"somaitenstotal$quantidadeunidade"} = "";
				${"somaitenstotaldev$quantidadeunidade"} = "";
     		}
			$rs->close();

            // Seleção das moedas vendas
			$sql = "SELECT indexadores.IdIndexador, Sigla FROM indexadores, indexadores_loja WHERE indexadores_loja.IdIndexador = indexadores.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]' AND indexadores.Ativo = 1";
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			while($rs->next ()) {
				$quantidadeindexadores++;
				${"idindexadorv$quantidadeindexadores"} = $rs->get(0);
				${"siglaindexadorv$quantidadeindexadores"} = $rs->get(1);
				${"totalindexadorv$quantidadeindexadores"} = "";
				${"totalindexadorvreais$quantidadeindexadores"} = "";
			}
			$rs->close();

			$sql = "SELECT IdVenda, venda_produto.IdVendaProduto, venda_produto.IdProduto, venda_produto.IdGrade, CodigoBarra, Descricao,
					Quantidade, PrecoCusto, PrecoVenda, PrecoReal, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.sigla, indexadores.sigla,
					produto.IdIndexador, PrecoVendaOrig, venda_produto.IdGrade, venda_produto.Fator
					FROM produto, grade, venda_produto, unidade_venda, indexadores";
			$sql = "$sql WHERE IdVenda = $idvenda AND grade.IdProduto = produto.IdProduto AND venda_produto.IdGrade = grade.IdGrade
					AND produto.IdProduto = venda_produto.IdProduto AND produto.IdUnidadeVenda = unidade_venda.IdUnidadeVenda AND produto.IdIndexador = indexadores.IdIndexador
					ORDER BY CodigoBarra";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while ($rs->next ()) {
				$quantidadelinha++;
				${"idvendaproduto$quantidadelinha"} = $rs->get(1);
				${"idproduto$quantidadelinha"} = $rs->get(2);
				${"idgrade$quantidadelinha"} = $rs->get(3);
				${"codigobarra$quantidadelinha"} = $rs->get(4);
				${"descricao$quantidadelinha"} = $rs->get(5);
				${"quantidade$quantidadelinha"} = $rs->get(6);
				${"precocusto$quantidadelinha"} = $rs->get(7);
				${"precovenda$quantidadelinha"} = $rs->get(8);
				${"precoreal$quantidadelinha"} = $rs->get(9);
				${"idmoeda$quantidadelinha"} = $rs->get(10);
				${"idunidade$quantidadelinha"} = $rs->get(11);
				${"siglaunidade$quantidadelinha"} = $rs->get(12);
				${"siglamoeda$quantidadelinha"} = $rs->get(13);
				${"idindexador$quantidadelinha"} = $rs->get(14);
				${"precovendaorig$quantidadelinha"} = $rs->get(15);
				${"fator_produto$quantidadelinha"} = $rs->get(17);

				//Seleção do tipo de Index
				$sql = "SELECT  Margem FROM indexadores, indexadores_loja WHERE indexadores.IdIndexador = '${"idindexador$quantidadelinha"}' AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					$margemindex = $rs1->get(0);
				}
				$rs1->close();

				if(${"idunidade$quantidadelinha"} == 2){
					${"precoetiqueta$quantidadelinha"} = ${"precoreal$quantidadelinha"} * $margemindex;
				}
				else{
					${"precoetiqueta$quantidadelinha"} = ${"precoreal$quantidadelinha"};
				}

				if(${"quantidade$quantidadelinha"} < 0)
					$somavalordev += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"};
				if(${"quantidade$quantidadelinha"} > 0)
					$somavalor += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"};

				$somaporcentagem = ($somavalordev * 100) / $somavalor;

				for($j = 1; $j <= $quantidadeunidade; $j++){
					if(${"quantidade$quantidadelinha"} > 0){
						if(${"idunidadevenda$j"} == ${"idunidade$quantidadelinha"})
							${"somaitenstotal$j"} += ${"quantidade$quantidadelinha"};
					}
					else{
						if(${"idunidadevenda$j"} == ${"idunidade$quantidadelinha"}){
							${"somaitenstotaldev$j"} += ${"quantidade$quantidadelinha"} * (-1);
							$devolucao = true;
						}

					}
				}

				for($m = 1; $m <= $quantidadeindexadores; $m++){
					if(${"idindexadorv$m"} == ${"idindexador$quantidadelinha"}){
						${"totalindexadorv$m"} += ${"precoreal$quantidadelinha"} * ${"quantidade$quantidadelinha"} * ${"fator_produto$quantidadelinha"};
						${"totalindexadorvreais$m"} += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"} * ${"fator_produto$quantidadelinha"};
					}
				}

			}
			$rs->close();

			$sql = "SELECT IdFormaPagamento, ValorMoeda, DataPrevista
					FROM parcela, parcela_venda, venda
					WHERE parcela.IdParcela = parcela_venda.IdParcela
					AND venda.IdVenda = parcela_venda.IdVenda
					AND venda.IdVenda= $idvenda";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while ($rs->next ()) {
				$quantidadeparcela++;
				${"idformapagamentoparcela$quantidadeparcela"} = $rs->get(0);
				${"valorparcela$quantidadeparcela"} = $rs->get(1);
				${"datavencimento$quantidadeparcela"} = $rs->get(2);

				if(${"valorparcela$quantidadeparcela"} > 0)
					$mostrarparcelas = true;

			}
			$rs->close();


			$romaneio = fopen("../impressao/venda_conferida.txt", "w");

            fwrite($romaneio, "\n\n\n");

			fwrite($romaneio, "             $nomeestabelecimento \n");
                     fwrite($romaneio, "            ORCAMENTO\n\n");

			$cliente = espacoVazioDireita (substr($nomecliente, 0, 25), 30);
			fwrite($romaneio, "$idcliente $nomecliente\n");
			fwrite($romaneio, "F: $numerovenda $nomefuncionario\n");
			fwrite($romaneio, "Orcamento: $idvenda\n");
			fwrite($romaneio, "Prazo: $idtabelavenda - $prazo Fator: $fator\n");
			fwrite($romaneio, "Data: $data    Hrs: $hora\n");
			fwrite($romaneio, "================================================\n");
			fwrite($romaneio, "	       Relacao de Produtos\n");
			fwrite($romaneio, "Item                  Produto      Total\n");

			for ($i = 1; $i <= $quantidadelinha; $i++) {

				$codigobarra = espacoVazioEsquerda (substr(${"codigobarra$i"}, 0, 13),14);
				$descricao = espacoVazioDireita (substr(${"descricao$i"}, 0, 19), 20);
				$precoreal = espacoVazioEsquerda(sprintf("%1.2f", ${"precoreal$i"}), 8);
				$siglamoeda= espacoVazioEsquerda(${"siglamoeda$i"}, 0);

				$quantidade= espacoVazioEsquerda(${"quantidade$i"}, 0);
				//$precovenda = espacoVazioEsquerda(sprintf("%1.2f", ${"precovenda$i"} * $fator), 8);

				if($idtipovenda == 8)
					$fator_imp = espacoVazioEsquerda(sprintf("%1.2f", ${"fator_produto$i"}), 8);
				else
					$fator_imp = espacoVazioEsquerda(sprintf("%1.2f", $fator), 8);
				//$totallinha = espacoVazioEsquerda(sprintf("%1.2f", ${"quantidade$i"} * ${"precovenda$i"} * $fator), 10);
				$totallinha = espacoVazioEsquerda(sprintf("%1.2f", ${"quantidade$i"} * ${"precoreal$i"} * $fator_imp), 10);

				fwrite($romaneio, " $i $codigobarra $descricao\n");
				fwrite($romaneio, " ($quantidade X $precoreal $siglamoeda) x $fator_imp = $totallinha\n");

			}

			fwrite($romaneio, "================================================\n");

			if($valordesconto <= 0) {
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
				//$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto * (-1)), 9);
				$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $pacrescimo), 7);
				//$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $vacrescimo), 10);
			}
			else {
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $pdesconto), 5);
				//$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $vdesconto), 7);
				$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto), 7);
				//$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto), 10);
			}

			$somavalortotalimp = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotal * $fator), 16);

			fwrite($romaneio, "\n\n");
			//fwrite($romaneio, "Total Venda................: R$ $somavalortotalimp\n");
			fwrite($romaneio, "Desconto...................: $pdesconto%  $vdesconto\n");
			fwrite($romaneio, "Acrescimo..................: $pacrescimo% $vacrescimo\n");
			$totalfinal = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotalimp  + $valordesconto), 16);
			//fwrite($romaneio, "Total Liquido..............: R$ $totalfinal\n\n");

			fwrite($romaneio, "Total Itens Saida..........:");
			for($j = 1; $j <= $quantidadeunidade; $j++) {
                if(${"somaitenstotal$j"} != 0){
					$somaitenstotalimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotal$j"}),9);
					$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},4);
					fwrite($romaneio, "$somaitenstotalimp $siglaunidadevendaimp");
                }
            }

			fwrite($romaneio, "\n");

			fwrite($romaneio, "Total Itens Devolvidos.....:");
			for($j = 1; $j <= $quantidadeunidade; $j++) {
                if(${"somaitenstotaldev$j"} != 0){
					$somaitenstotaldevimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotaldev$j"}),6);
					$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},4);
					fwrite($romaneio, "$somaitenstotaldevimp $siglaunidadevendaimp");
                }
            }

			fwrite($romaneio, "\n");

			fwrite($romaneio, "Total Indexadores..........:\n");
			for($m = 1; $m <= $quantidadeindexadores; $m++) {
				if(${"totalindexadorv$m"} != 0){
                   $somatotalindexadorimp = espacoVazioEsquerda (sprintf("%1.2f",${"totalindexadorv$m"} - ((${"totalindexadorv$m"} * $porcentagemdesconto)/100)),12);
                  // $totalindexadorvreaisimp = espacoVazioEsquerda (sprintf("%1.2f",${"totalindexadorvreais$m"}),12);
                   $siglaindexadorvendaimp = espacoVazioDireita (${"siglaindexadorv$m"},4);
                   fwrite($romaneio, "$somatotalindexadorimp $siglaindexadorvendaimp $totalindexadorvreaisimp \n");
				}
            }

			$somaporcentagem = sprintf("%1.2f",$somaporcentagem);

			fwrite($romaneio, "\n");
			fwrite($romaneio, "% Devol......................: $somaporcentagem\n\n");

			if($mostrarparcelas){
				for ($i = 1; $i <= $quantidadeparcela; $i++) {

					// Seleção do nome da forma de pagamento.
					$sql = "SELECT Nome FROM forma_pagamento where IdFormapagamento = '${"idformapagamentoparcela$i"}'";
					$rs = $con->executeQuery ($sql);
					if($rs->next()) {
						$nomeformapagamentoparcela = $rs->get(0);
					}
					$rs->close();

					$parcela = espacoVazioEsquerda(sprintf ("%1.2f", ${"valorparcela$i"}), 0);
					$vencimento = substr(${"datavencimento$i"}, 8, 2) . "/" . substr(${"datavencimento$i"}, 5, 2) . "/" . substr(${"datavencimento$i"}, 0, 4);
					$nomeformapagamento = espacoVazioDireita (substr($nomeformapagamentoparcela, 0, 3), 4);
					fwrite($romaneio, "$i V. $vencimento F.Pag. $nomeformapagamento $parcela\n\n");
				}
			}

			fwrite($romaneio, "\n\n");

			fwrite($romaneio, "AGRADECEMOS SUA PREFERENCIA-VOLTE SEMPRE\n");
			fwrite($romaneio, "PRAZO P/TROCA DE MERCADORIA E DE 30 DIAS\n");
			fwrite($romaneio, "         PECAS COM ETIQUETAS\n");
			fwrite($romaneio, "******** CUPOM SEM VALOR FISCAL ********\n");

			fwrite($romaneio, "\n\n\n");

			fclose($romaneio);


				$ip = $_SERVER[REMOTE_ADDR];

                //echo $ip;

                if($ip == "192.168.1.214"){
					passthru("lpr -P balcao01cp ../impressao/venda_conferida.txt");
				}
				else if($ip == "192.168.1.40"){
					passthru("lpr -P balcao01cp ../impressao/venda_conferida.txt");
				}
               // else if($ip == "192.168.1.35"){
				//	passthru("lpr -P balcao02cp ../impressao/venda_conferida.txt");
				//}
				else if($ip == "192.168.1.55"){
					passthru("lpr -P balcao02cp ../impressao/venda_conferida.txt");
				}
				else{
				   passthru("lpr -P caixa01cp ../impressao/venda_conferida.txt");
				}




              //teste para impressora nova que dá diferença na impressao
           if($ip == "192.168.1.35"){


                    	//Atualização do arquivo romaneio que será impresso.

            $romaneio = fopen("../impressao/venda_conferida.txt", "w");

            fwrite($romaneio, "\n\n\n");

			fwrite($romaneio, "             $nomeestabelecimento \n");
                     fwrite($romaneio, "            ORCAMENTO\n\n");

			$cliente = espacoVazioDireita (substr($nomecliente, 0, 25), 30);
			fwrite($romaneio, "$idcliente $nomecliente\n");
			fwrite($romaneio, "F: $numerovenda $nomefuncionario\n");
			fwrite($romaneio, "Orcamento: $idvenda\n");
			fwrite($romaneio, "Prazo: $idtabelavenda - $prazo Fator: $fator\n");
			fwrite($romaneio, "Data: $data    Hrs: $hora\n");
			fwrite($romaneio, "================================================\n");
			fwrite($romaneio, "	       Relacao de Produtos\n");
			fwrite($romaneio, "Item                  Produto      Total\n");

			for ($i = 1; $i <= $quantidadelinha; $i++) {

				$codigobarra = espacoVazioEsquerda (substr(${"codigobarra$i"}, 0, 13),14);
				$descricao = espacoVazioDireita (substr(${"descricao$i"}, 0, 19), 20);
				$precoreal = espacoVazioEsquerda(sprintf("%1.2f", ${"precoreal$i"}), 7);
				$siglamoeda= espacoVazioEsquerda(${"siglamoeda$i"}, 0);

				$quantidade= espacoVazioEsquerda(${"quantidade$i"}, 0);
				//$precovenda = espacoVazioEsquerda(sprintf("%1.2f", ${"precovenda$i"} * $fator), 8);

				if($idtipovenda == 8)
					$fator_imp = espacoVazioEsquerda(sprintf("%1.2f", ${"fator_produto$i"}), 5);
				else
					$fator_imp = espacoVazioEsquerda(sprintf("%1.2f", $fator), 8);
				//$totallinha = espacoVazioEsquerda(sprintf("%1.2f", ${"quantidade$i"} * ${"precovenda$i"} * $fator), 10);
				$totallinha = espacoVazioEsquerda(sprintf("%1.2f", ${"quantidade$i"} * ${"precoreal$i"} * $fator_imp), 8);

				fwrite($romaneio, " $i $codigobarra $descricao\n");
				fwrite($romaneio, " ($quantidade X $precoreal $siglamoeda) x $fator_imp = $totallinha\n");

			}

			fwrite($romaneio, "================================================\n");

			if($valordesconto <= 0) {
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
				//$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto * (-1)), 9);
				$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $pacrescimo), 7);
				//$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $vacrescimo), 10);
			}
			else {
				$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $pdesconto), 5);
				//$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $vdesconto), 7);
				$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto), 7);
				//$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto), 10);
			}

			$somavalortotalimp = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotal * $fator), 12);

			fwrite($romaneio, "\n\n");
			//fwrite($romaneio, "Total Venda................: R$ $somavalortotalimp\n");
			fwrite($romaneio, "Desconto...................: $pdesconto%  $vdesconto\n");
			fwrite($romaneio, "Acrescimo..................: $pacrescimo% $vacrescimo\n");
			$totalfinal = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotalimp  + $valordesconto), 9);
			//fwrite($romaneio, "Total Liquido..............: R$ $totalfinal\n\n");

			fwrite($romaneio, "Total Itens Saida..........:");
			for($j = 1; $j <= $quantidadeunidade; $j++) {
                if(${"somaitenstotal$j"} != 0){
					$somaitenstotalimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotal$j"}),6);
					$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},3);
					fwrite($romaneio, "$somaitenstotalimp $siglaunidadevendaimp");
                }
            }

			fwrite($romaneio, "\n");

			fwrite($romaneio, "Total Itens Devolvidos.....:");
			for($j = 1; $j <= $quantidadeunidade; $j++) {
                if(${"somaitenstotaldev$j"} != 0){
					$somaitenstotaldevimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotaldev$j"}),6);
					$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},4);
					fwrite($romaneio, "$somaitenstotaldevimp $siglaunidadevendaimp");
                }
            }

			fwrite($romaneio, "\n");

			fwrite($romaneio, "Total Indexadores..........:\n");
			for($m = 1; $m <= $quantidadeindexadores; $m++) {
				if(${"totalindexadorv$m"} != 0){
                   $somatotalindexadorimp = espacoVazioEsquerda (sprintf("%1.2f",${"totalindexadorv$m"} - ((${"totalindexadorv$m"} * $porcentagemdesconto)/100)),12);
                  // $totalindexadorvreaisimp = espacoVazioEsquerda (sprintf("%1.2f",${"totalindexadorvreais$m"}),12);
                   $siglaindexadorvendaimp = espacoVazioDireita (${"siglaindexadorv$m"},4);
                   fwrite($romaneio, "$somatotalindexadorimp $siglaindexadorvendaimp $totalindexadorvreaisimp \n");
				}
            }

			$somaporcentagem = sprintf("%1.2f",$somaporcentagem);

			fwrite($romaneio, "\n");
			fwrite($romaneio, "% Devol......................: $somaporcentagem\n\n");

			if($mostrarparcelas){
				for ($i = 1; $i <= $quantidadeparcela; $i++) {

					// Seleção do nome da forma de pagamento.
					$sql = "SELECT Nome FROM forma_pagamento where IdFormapagamento = '${"idformapagamentoparcela$i"}'";
					$rs = $con->executeQuery ($sql);
					if($rs->next()) {
						$nomeformapagamentoparcela = $rs->get(0);
					}
					$rs->close();

					$parcela = espacoVazioEsquerda(sprintf ("%1.2f", ${"valorparcela$i"}), 0);
					$vencimento = substr(${"datavencimento$i"}, 8, 2) . "/" . substr(${"datavencimento$i"}, 5, 2) . "/" . substr(${"datavencimento$i"}, 0, 4);
					$nomeformapagamento = espacoVazioDireita (substr($nomeformapagamentoparcela, 0, 3), 4);
					fwrite($romaneio, "$i V. $vencimento F.Pag. $nomeformapagamento $parcela\n\n");
				}
			}

			fwrite($romaneio, "\n\n");

			fwrite($romaneio, "AGRADECEMOS SUA PREFERENCIA-VOLTE SEMPRE\n");
			fwrite($romaneio, "PRAZO P/TROCA DE MERCADORIA E DE 30 DIAS\n");
			fwrite($romaneio, "         PECAS COM ETIQUETAS\n");
			fwrite($romaneio, "******** CUPOM SEM VALOR FISCAL ********\n");

			fwrite($romaneio, "\n\n\n");

			fclose($romaneio);


                $ip = $_SERVER[REMOTE_ADDR];

                //echo $ip;

                if($ip == "192.168.1.214"){
					passthru("lpr -P balcao02cp ../impressao/venda_conferida.txt");
				}

            }



			if($devolucao){
				$romaneio = fopen("../impressao/venda_conferida_devolucao.txt", "w");

				fwrite($romaneio, "            $nomeestabelecimento\n");
				fwrite($romaneio, "          ORCAMENTO - DEVOLUCAO\n\n");

				$cliente = espacoVazioDireita (substr($nomecliente, 0, 25), 30);
				fwrite($romaneio, "$idcliente $nomecliente\n");
				fwrite($romaneio, "F: $numerovenda $nomefuncionario\n");
				fwrite($romaneio, "Orcamento: $idvenda   Prazo: $idtabelavenda - $prazo\n");
				fwrite($romaneio, "Data: $data    Hrs: $hora\n");
				fwrite($romaneio, "================================================\n");
				fwrite($romaneio, "       Relacao de Produtos Devolvidos\n");
				fwrite($romaneio, "Item                  Produto      Total\n");

				for ($i = 1; $i <= $quantidadelinha; $i++) {
					if(${"quantidade$i"} < 0){

						$codigobarra = espacoVazioEsquerda (substr(${"codigobarra$i"}, 0, 13),14);
						$descricao = espacoVazioDireita (substr(${"descricao$i"}, 0, 19), 20);
						$precoreal = espacoVazioEsquerda(sprintf("%1.2f", ${"precoreal$i"}), 10);
						$siglamoeda= espacoVazioEsquerda(${"siglamoeda$i"}, 0);

						if($idtipovenda == 8)
					    $fator_imp = espacoVazioEsquerda(sprintf("%1.2f", ${"fator_produto$i"}), 8);
				        else
					    $fator_imp = espacoVazioEsquerda(sprintf("%1.2f", $fator), 8);

						$quantidade= espacoVazioEsquerda(${"quantidade$i"}, 0);
						$precovenda = espacoVazioEsquerda(sprintf("%1.2f", ${"precovenda$i"} * $fator_imp), 10);
						$totallinha = espacoVazioEsquerda(sprintf("%1.2f", ${"quantidade$i"} * ${"precovenda$i"} * $fator_imp), 10);

				        fwrite($romaneio, " $i $codigobarra $descricao\n");
				        fwrite($romaneio, " ($quantidade X $precoreal $siglamoeda) x $fator_imp = $totallinha\n");

					}
				}

				fwrite($romaneio, "================================================\n");

			/* 	if($valordesconto <= 0) {
					$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
					$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto * (-1)), 7);
					$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto * (-1)), 9);
					$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $pacrescimo), 7);
					$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $vacrescimo), 10);
				}
				else {
					$pdesconto = espacoVazioEsquerda(sprintf ("%1.1f", $pdesconto), 5);
					$vdesconto = espacoVazioEsquerda(sprintf ("%1.2f", $vdesconto), 7);
					$pacrescimo = espacoVazioEsquerda(sprintf ("%1.1f", $porcentagemdesconto), 7);
					$vacrescimo = espacoVazioEsquerda(sprintf ("%1.2f", $valordesconto), 10);
				}

				$somavalortotalimp = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotal * $fator), 16); */

				/* fwrite($romaneio, "\n\n");
				fwrite($romaneio, "Total Venda................: R$ $somavalortotalimp\n");
				fwrite($romaneio, "Desconto...................: $pdesconto%  $vdesconto\n");
				fwrite($romaneio, "Acrescimo..................: $pacrescimo% $vacrescimo\n");
				$totalfinal = espacoVazioEsquerda(sprintf("%1.2f", $somavalortotalimp  + $valordesconto), 16);
				fwrite($romaneio, "Total Liquido..............: R$ $totalfinal\n\n");

				fwrite($romaneio, "Total Itens Saida..........:");
				for($j = 1; $j <= $quantidadeunidade; $j++) {
					if(${"somaitenstotal$j"} != 0){
						$somaitenstotalimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotal$j"}),9);
						$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},4);
						fwrite($romaneio, "$somaitenstotalimp $siglaunidadevendaimp");
					}
				}

				fwrite($romaneio, "\n"); */

				fwrite($romaneio, "Total Itens Devolvidos.....:");
				for($j = 1; $j <= $quantidadeunidade; $j++) {
					if(${"somaitenstotaldev$j"} != 0){
						$somaitenstotaldevimp = espacoVazioEsquerda (sprintf("%1.2f",${"somaitenstotaldev$j"}),6);
						$siglaunidadevendaimp = espacoVazioDireita (${"siglaunidadevenda$j"},4);
						fwrite($romaneio, "$somaitenstotaldevimp $siglaunidadevendaimp");
					}
				}

				fwrite($romaneio, "\n");

			/* 	fwrite($romaneio, "Total Indexadores..........:\n");
				for($m = 1; $m <= $quantidadeindexadores; $m++) {
					if(${"totalindexadorv$m"} != 0){
					   $somatotalindexadorimp = espacoVazioEsquerda (sprintf("%1.2f",${"totalindexadorv$m"}),12);
					   $siglaindexadorvendaimp = espacoVazioDireita (${"siglaindexadorv$m"},4);
					   fwrite($romaneio, "$somatotalindexadorimp $siglaindexadorvendaimp\n");
					}
				} */

				$somaporcentagem = sprintf("%1.2f",$somaporcentagem);

				fwrite($romaneio, "\n");
				fwrite($romaneio, "% Devol......................: $somaporcentagem\n\n");

			/* 	for ($i = 1; $i <= $quantidadeparcela; $i++) {

					// Seleção do nome da forma de pagamento.
					$sql = "SELECT Nome FROM forma_pagamento where IdFormapagamento = '${"idformapagamentoparcela$i"}'";
					$rs = $con->executeQuery ($sql);
					if($rs->next()) {
						$nomeformapagamentoparcela = $rs->get(0);
					}
					$rs->close();

					$parcela = espacoVazioEsquerda(sprintf ("%1.2f", ${"valorparcela$i"}), 0);
					$vencimento = substr(${"datavencimento$i"}, 8, 2) . "/" . substr(${"datavencimento$i"}, 5, 2) . "/" . substr(${"datavencimento$i"}, 0, 4);
					$nomeformapagamento = espacoVazioDireita (substr($nomeformapagamentoparcela, 0, 3), 4);
					fwrite($romaneio, "$i V. $vencimento F.Pag. $nomeformapagamento R$ $parcela\n\n");
				} */

			/* 	fwrite($romaneio, "\n\n");

				fwrite($romaneio, "AGRADECEMOS SUA PREFERENCIA-VOLTE SEMPRE\n");
				fwrite($romaneio, "PRAZO P/TROCA DE MERCADORIA E DE 30 DIAS\n");
				fwrite($romaneio, "         PECAS COM ETIQUETAS\n");
				fwrite($romaneio, "******** CUPOM SEM VALOR FISCAL ********\n");

				fwrite($romaneio, "\n\n\n");*/

				fclose($romaneio);


				$ip = $_SERVER[REMOTE_ADDR];

                //echo $ip;

                if($ip == "192.168.1.214"){
					passthru("lpr -P balcao01cp ../impressao/venda_conferida_devolucao.txt");
				}
				else if($ip == "192.168.1.40"){
					passthru("lpr -P balcao01cp ../impressao/venda_conferida_devolucao.txt");
				}
                else if($ip == "192.168.1.35"){
					passthru("lpr -P balcao02cp ../impressao/venda_conferida_devolucao.txt");
				}
				else if($ip == "192.168.1.55"){
					passthru("lpr -P balcao02cp ../impressao/venda_conferida_devolucao.txt");
				}
				else{
				    passthru("lpr -P caixa01cp ../impressao/venda_conferida_devolucao.txt");
				}

			}

			/* $nomeimpressao = 'finaliza_venda.txt';

				require_once ("../../sistema/includes/venda_conferida.php"); */


			//  echo '<script language="javascript">window.open("../impressao/finaliza_venda.txt", "Popup",  "width=600, height=700");</script>';


			$con->close();
        }
        else echo '<br><br><br><center><font class="titulo">Acesso Restrito!<p><br></font><font class="mensagem">O Usuário não tem permissão para executar esta funcionalidade!<font><p><a href="../../home.php"><input type="submit" name="submeterRetorno" value="Retornar"></a></center>';

?>


