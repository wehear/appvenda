<?
	session_start();
	$funcionario = $_SESSION[funcionario];

	include_once ("../../sistema/includes/connection.inc");

	$idvenda = trim($_POST['idvenda']);
	$idtabelavenda = trim($_POST['idtabelavenda']);
	$idcliente = trim($_POST['idcliente']);
	$fator = trim($_POST['fator']);

	// Abertura da conexão.
	$con = new Connection;
	$con->open(); 

	$sql = "SELECT * FROM venda WHERE IdCliente = $idcliente AND IdVenda = $idvenda";
	$rs = $con->executeQuery ($sql);
	//echo $sql;
	if(!$rs->next()) {
		$isAlterarCliente = true;
	}
	$rs->close();
	
	if($isAlterarCliente){
		
		$sql = "SELECT Especial, IdTabelaVenda FROM venda WHERE IdCliente = $idcliente AND IdVenda = $idvenda";
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {
			$especial = $rs->get("Especial");
			$idtabelavenda = $rs->get("IdTabelaVenda");
		}
		$rs->close();
		
		$sql = "SELECT  Fator FROM tabela_venda";
		$sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda' AND IdLoja = '$funcionario[idloja]'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {       
			$fator_prazo = $rs->get(0);
		}
		$rs->close();
	
		$sql = "UPDATE venda SET
				IdCliente = $idcliente
				WHERE IdVenda = $idvenda";
		$con->executeQuery ($sql);
		
		$sql = "SELECT venda_produto.IdGrade, venda_produto.IdVendaProduto
				FROM venda_produto
				WHERE IdVenda = $idvenda";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while ($rs->next ()) {
		
			$sql = "SELECT produto.IdProduto, grade.IdGrade, produto.Descricao, estoque.Quantidade, estoque.PrecoCusto, estoque.PrecoVenda, grade.CodigoBarra, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla, indexadores.Sigla, estoque.IdLoja, indexadores.IdIndexador, produto.IdSecao, estoque.Desconto, produto.CodigoFornecedor, estoque_grade.Peso, produto.Imagem, estoque.ValorPedra, estoque.QuantidadePedra
				FROM produto, grade, unidade_venda, estoque, indexadores, estoque_grade, cotacao 
				WHERE produto.IdIndexador = indexadores.IdIndexador AND produto.IdProduto = grade.IdProduto AND estoque.IdLoja = '$funcionario[idloja]' AND estoque_grade.IdGrade = grade.IdGrade AND grade.Ativo = 1	AND estoque_grade.IdLoja = '$funcionario[idloja]' AND produto.IdProduto = estoque.IdProduto AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda AND cotacao.IdCotacao = indexadores.IdCotacao
				AND grade.IdGrade = ".$rs->get("IdGrade");
			//echo $sql;
			$rs1 = $con->executeQuery ($sql);
			if ($rs1->next ()) {
	
				$sql = "SELECT Fator, IdCotacao, Margem FROM indexadores_cliente, indexadores 
						WHERE indexadores.IdIndexador = ".$rs1->get("IdIndexador")."
						AND indexadores.IdIndexador = indexadores_cliente.IdIndexador
						AND IdCliente = $idcliente 
						AND indexadores_cliente.IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				$rs4= $con->executeQuery ($sql);
				if($rs4->next()){
					$margemindex = $rs4->get("Margem");
					$valorindex = $rs4->get("Fator");
					$idcotacao = $rs4->get("IdCotacao");
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
				$rs4->close();

                // Seleção da ultima cotação da moeda para o produto.
    			$sql = "SELECT IdCotacao, Valor FROM cotacao WHERE IdCotacao = '$idcotacao'";
				//echo $sql;
    			$rs4 = $con->executeQuery ($sql);
    			if($rs4->next())
                    $cotacao = $rs4->get('Valor');
                $rs4->close();
          
				if($rs1->get('IdUnidadeVenda') == 2){
					$precoreal = $rs1->get('PrecoVenda') * $rs1->get('Peso');
					$precovenda =  round($precoreal * $margemindex, 2) * round($cotacao * $valorindex, 2);
					$precoetiqueta = round($precoreal * $margemindex, 2);
                }
                else{
					$precoreal = $rs1->get('PrecoVenda');
					$precovenda =  $precoreal * round($cotacao * $valorindex, 2);
					$precoetiqueta =  $precoreal;
				}
				
				$precovendaorig =  $precovenda;
		
				if($especial > 0){
					if($rs1->get("Peso") > 0 && $idunidade == 2){
						$precovenda = '1.00';
						$precovendaorig = $rs1->get("PrecoVenda");
					}else{
						$precovenda = $rs1->get("PrecoVenda");
						$precovendaorig = $rs1->get("PrecoVenda");
					}
					$peso = $rs1->get("Peso");
				}
                
				if($precovendaorig == '0.00')
					$precovendaorig = $precovenda / $fator_prazo;
				
			}
			$rs1->close();
		
			$precovenda = $precovenda * $fator_prazo;
			
			$sql = "UPDATE venda_produto SET
					PrecoVendaOrig = '$precovendaorig',
					PrecoVenda = '$precovenda',
					PrecoReal = '$precoreal'
					WHERE IdVendaProduto = ".$rs->get('IdVendaProduto');
			//echo $sql;
			$con->executeQuery ($sql);		
		
		}
		$rs->close();
	}

?>
