<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idgrade = $_POST['idgrade'];
		$idtabelavenda = $_POST['idtabelavenda'];
        $codigobarra = $_POST['codigobarra'];
		$referenciaconsulta = $_POST['referenciaconsulta'];
		$idcliente = $_POST['idcliente'];
		$idsecao = $_POST['idsecao'];
		$idmoeda = $_POST['idmoeda'];
		$fator = $_POST['fator'];
		$especial = $_POST['especial'];
        $idprodutocombo = $_POST["idprodutocombo"];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		if($especial){
			$fator = 1;
		}else{
			// Seleção dos dados da forma de pagamento.
			$sql = "SELECT  Fator FROM tabela_venda";
			$sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda' AND IdLoja = '$funcionario[idloja]'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {       
				$fator = $rs->get(0);
			}
			$rs->close();
		}

		require('../../estoque/produto/validaCodigoBarra.php');
				
		// Seleção dos dados do produto.
		$sql = "SELECT produto.IdProduto, grade.IdGrade, produto.Descricao, estoque.Quantidade, estoque.PrecoCusto, estoque.PrecoVenda, 
				grade.CodigoBarra, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla, indexadores.Sigla, estoque.IdLoja, 
				indexadores.IdIndexador, produto.IdSecao, estoque.Desconto, produto.CodigoFornecedor, estoque_grade.Peso, 
				produto.Imagem, estoque.ValorPedra, estoque.QuantidadePedra, estoque.Fator, grade.IdLocalizacaoPeca
				FROM produto, grade, unidade_venda, estoque, indexadores, estoque_grade, cotacao 
				WHERE produto.IdIndexador = indexadores.IdIndexador AND produto.IdProduto = grade.IdProduto AND estoque.IdLoja = '$funcionario[idloja]' AND estoque_grade.IdGrade = grade.IdGrade AND grade.Ativo = 1	AND estoque_grade.IdLoja = '$funcionario[idloja]' AND produto.IdProduto = estoque.IdProduto AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda AND cotacao.IdCotacao = indexadores.IdCotacao";
		if($idprodutocombo)
			$sql = "$sql AND produto.IdProduto = '$idprodutocombo'";
		if($codigobarra)
			$sql = "$sql AND CodigoBarra LIKE '$codigobarra%'";
		if($idgrade)
			$sql = "$sql AND grade.IdGrade = '$idgrade'";
		if($idmoeda)
			$sql = "$sql AND produto.IdMoeda = '$idmoeda'";
 			
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
                $idproduto = $rs->get(0);
                $idgrade = $rs->get(1);
                $idmoeda = $rs->get(7);
                $idunidade = $rs->get(8);
                $siglaunidade = $rs->get(9);
                $siglamoeda = $rs->get(10);
                $idindexador = $rs->get(12);
				$idsecao = $rs ->get(13);
				$descontoProduto = $rs->get(14);
				$referenciaconsulta = $rs->get(15);
				
				if($rs ->get(17))
					$imagem = '<img src="../../'.$rs ->get(17).'" width="200" height="150" border="0">';
				else
					$imagem = '<img src="../../sistema/images/imagem_indisponivel.png" width="200" height="150" border="0">';
				$valorpedra = $rs->get(18);
				$quantidadepedra = $rs->get(19);
				$estoqueatual = $rs->get(3);
				
				//Seleção do tipo de Index
				$sql = "SELECT Nome, Mostruario FROM localizacao_peca WHERE IdLocalizacaoPeca = ".$rs->get("IdLocalizacaoPeca");
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
    			if($rs1->next()){
                    $localizacao = $rs1->get(0);
                    $mostruario = $rs1->get(1);
				}
                $rs1->close();
				
				//Seleção do tipo de Index
				$sql = "SELECT Valor FROM cotacao WHERE IdMoeda = '$idmoeda' AND IdSecao = '$idsecao'";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
    			if($rs1->next())
                    $cotacaocusto = $rs1->get(0);
                $rs1->close();
				
				//Seleção do tipo de Index
				$sql = "SELECT Fator, IdCotacao, Margem FROM indexadores_cliente, indexadores 
						WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")."
						AND indexadores.IdIndexador = indexadores_cliente.IdIndexador
						AND IdCliente = $idcliente 
						AND indexadores_cliente.IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					$margemindex = $rs1->get("Margem");
					$valorindex = $rs1->get("Fator");
					$idcotacao = $rs1->get("IdCotacao");
				}else{
					//Seleção do tipo de Index
					$sql = "SELECT Fator, IdCotacao, Margem FROM indexadores, indexadores_tipocliente, cliente 
							WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")." AND cliente.IdTipoCliente = indexadores_tipocliente.IdTipoCliente
							AND cliente.IdCliente = $idcliente 
							AND indexadores.IdIndexador = indexadores_tipocliente.IdIndexador AND indexadores_tipocliente.IdLoja = '$funcionario[idloja]'";
					//echo $sql;
					$rs2 = $con->executeQuery ($sql);
					if($rs2->next()){
						$margemindex = $rs2->get("Margem");
						$valorindex = $rs2->get("Fator");
						$idcotacao = $rs2->get("IdCotacao");
					}else{
						//Seleção do tipo de Index
						$sql = "SELECT Fator, IdCotacao, Margem FROM indexadores, indexadores_loja 
								WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")." 
								AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
						//echo $sql;
						$rs3 = $con->executeQuery ($sql);
						if($rs3->next()){
							$margemindex = $rs3->get("Margem");
							$valorindex = $rs3->get("Fator");
							$idcotacao = $rs3->get("IdCotacao");
						}
						$rs3->close();
					}
					$rs2->close();
				}
				$rs1->close();

                // Seleção da ultima cotação da moeda para o produto.
    			$sql = "SELECT IdCotacao, Valor FROM cotacao WHERE IdCotacao = '$idcotacao'";
    			$rs1 = $con->executeQuery ($sql);
    			if($rs1->next())
                    $cotacao = $rs1->get(1);
                $rs1->close();
                
				$descricao = $rs->get(2);
				$descricaoconsulta = $rs->get(2);
				$precocusto = $rs->get(4) * $cotacaocusto;
				$precocustoreais = (($rs->get("PrecoCusto")*$rs->get("Fator")) + ($rs->get("ValorPedra")*$rs->get("QuantidadePedra"))) * $cotacaocusto;
				
				if($idunidade == 2){
					$precoreal = $rs->get(5) * $rs->get(16);
					$precovenda =  round($precoreal * $margemindex, 2) * round($cotacao * $valorindex, 2);
					$precoetiqueta = round($precoreal * $margemindex, 2);
                }
                else{
					$precoreal = $rs->get(5);
					$precovenda =  $precoreal * round($cotacao * $valorindex, 2);
					$precoetiqueta =  $precoreal;
				}
				
				
				$cotacao_aux = round($cotacao * $valorindex, 2);
				
				$precovendaorig =  $precovenda;
				$codigobarra = $rs->get(6);
				//$quantidade = "1";
				
                $sql = "SELECT IdNotaPromocao, DataInicial, DataFinal FROM nota_promocao, funcionario";
			    $sql = "$sql WHERE DataInicial <= '$data' AND DataFinal >= '$data' AND IdLoja = '$funcionario[idloja]' AND nota_promocao.Ativo = '1'";
			    //echo $sql;
			    $rs1 = $con->executeQuery ($sql);
			    if($rs1->next())
			       $idnotapromocao = $rs1->get(0);
			    else
                   $idnotapromocao = "";
        	    $rs1->close();
        	    
        	    if($idnotapromocao){
        	       $sql = "SELECT Desconto FROM produto_nota_promocao";
			       $sql = "$sql WHERE IdNotaPromocao = $idnotapromocao AND IdProduto = $idproduto";
                   //echo $sql;
			       $rs1 = $con->executeQuery ($sql);
			       if($rs1->next())
			          $desconto = $rs1->get(0);
			       else
                      $desconto = "";
        	       $rs1->close();
        	    }
        	
                //calcular valor com desconto.
                if($desconto > 0){
                   $preco = ($desconto * $precovenda) / 100;
                   $precovenda = $precovenda - $preco;
                }
				
				if($especial > 0){
					if($rs->get("Peso") > 0 && $idunidade == 2){
						$precovenda = '1.00';
						$precovendaorig = $rs->get("PrecoVenda");
					}else{
						$precovenda = $rs->get("PrecoVenda");
						$precovendaorig = $rs->get("PrecoVenda");
					}
					$peso = $rs->get("Peso");
				}
                
     		}
			$rs->close();
			
			echo $idproduto.'|'.
                $idgrade.'|'.
                $idmoeda.'|'.
                $idunidade.'|'.
                $siglaunidade.'|'.
                $siglamoeda.'|'.
                $idindexador.'|'.
				$idsecao.'|'.
				$descontoProduto.'|'.
				$imagem.'|'.
				utf8_encode($referenciaconsulta).'|'.
				utf8_encode($descricao).'|'.
				utf8_encode($descricaoconsulta).'|'.
				sprintf("%1.2f", $precocusto).'|'.
				sprintf("%1.2f", $precocustoreais).'|'.
				sprintf("%1.2f", $precoreal).'|'.
				sprintf("%1.2f", $precovenda * $fator).'|'.
				sprintf("%1.2f", $precoetiqueta).'|'.
				sprintf("%1.2f", $precovendaorig).'|'.
				$codigobarra.'|'.
				$desconto.'|'.
				sprintf("%1.2f", $precovenda * $fator).'|'.
				//$peso;
				sprintf("%1.2f", $peso).'|'.
				sprintf("%1.2f", $valorpedra).'|'.
				sprintf("%1.2f", $quantidadepedra).'|'.
				sprintf("%1.2f", $estoqueatual).'|'.
				sprintf("%1.2f", $cotacao_aux).'|'.
                $localizacao.'|'.
				$mostruario;
		
	
?>
