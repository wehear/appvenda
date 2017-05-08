<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idgrade = $_POST['idgrade'];
		$codigobarra = $_POST['codigobarra'];
		$idcliente = $_POST['idcliente'];
		$fator = $_POST['fator'];
		$idvenda = $_POST['idvenda'];
		$idconferencia = $_POST['idconferencia'];
		
		
		// Acrescenta zeros no incio do codigo.
		function validaCodigoBarra($codigo, $tamanho) {
            for($i = 1; $i <= $tamanho - strlen($codigo); $i++)
                $retorno .= 0;
            $retorno .= $codigo;
			return($retorno);
		}
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 

		if($codigobarra && strlen($codigobarra) < 7)
			$codigobarra = validaCodigoBarra($codigobarra, 7);
				
/* 		echo 'idgrade'.$idgrade.'<br>';
		echo 'codigobarra'.$codigobarra.'<br>';
		echo 'idcliente'.$idcliente.'<br>';
		echo 'fator'.$fator.'<br>'; */
		// Seleção dos dados do produto.
		$sql = "SELECT produto.IdProduto, grade.IdGrade, produto.Descricao, estoque.Quantidade, estoque.PrecoCusto, estoque.PrecoVenda, grade.CodigoBarra, 
				produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla, indexadores.Sigla, estoque.IdLoja, indexadores.IdIndexador, produto.IdSecao, 
				estoque.Desconto, produto.CodigoFornecedor, estoque_grade.Peso, produto.Imagem
				FROM produto, grade, unidade_venda, estoque, indexadores, estoque_grade
				WHERE produto.IdIndexador = indexadores.IdIndexador AND produto.IdProduto = grade.IdProduto 
				AND estoque.IdLoja = '$funcionario[idloja]' AND estoque_grade.IdGrade = grade.IdGrade 
				AND estoque_grade.IdLoja = '$funcionario[idloja]' AND produto.IdProduto = estoque.IdProduto 
				AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda";
		if($idprodutocombo)
			$sql = "$sql AND produto.IdProduto = '$idprodutocombo'";
		if($codigobarra)
			$sql = "$sql AND CodigoBarra Like '$codigobarra%'";
		if($idgrade)
			$sql = "$sql AND grade.IdGrade = '$idgrade'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
                $idproduto = $rs->get(0);
                $idgrade = $rs->get(1);
                $idmoeda = $rs->get(7);
                $idunidade = $rs->get(8);
                $siglaunidade = $rs->get(9);
                $siglamoeda = $rs->get(10);
                $idindexador = $rs ->get(12);
				$idsecao = $rs ->get(13);
				$descontoProduto = $rs ->get(14);
				$referenciaconsulta = $rs ->get(15);
				$imagem = '<img src="../../'.$rs ->get(17).'" width="200" height="150" border="0">';
				
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
					$sql = "SELECT Fator, IdCotacao, Margem FROM indexadores, indexadores_loja 
							WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")." 
							AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
					//echo $sql;
					$rs2 = $con->executeQuery ($sql);
					if($rs2->next()){
						$margemindex = $rs2->get("Margem");
						$valorindex = $rs2->get("Fator");
						$idcotacao = $rs2->get("IdCotacao");
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
				$precocustoreais = $rs->get(4) * $cotacaocusto;
				
				if($idunidade == 2){
					$precoreal = $rs->get(5) * $rs->get(16);
					$precovenda =  ($precoreal * $margemindex) * ($cotacao * $valorindex);
					$precoetiqueta = ($precoreal * $margemindex);
                }
                else{
					$precoreal = $rs->get(5);
					$precovenda =  $precoreal * ($cotacao * $valorindex);
					$precoetiqueta =  $precoreal;
				}
				
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
				sprintf("%1.2f", $precovenda).'|'.
				sprintf("%1.2f", $precoetiqueta).'|'.
				sprintf("%1.2f", $precovendaorig).'|'.
				$codigobarra.'|'.
				$desconto.'|'.
				sprintf("%1.2f", $precovenda * $fator);
		
	
?>
