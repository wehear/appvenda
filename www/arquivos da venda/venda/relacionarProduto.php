<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idgrade = $_POST['idgrade'];
		$idproduto = $_POST['idproduto'];
		$idvenda = $_POST['idvenda'];
		$idtabelavenda = $_POST['idtabelavenda'];
		$senhaestoque = $_POST['senhaestoque'];
		$quantidade = str_replace(',','.',$_POST['quantidade']);
		$precovendaorig = str_replace(',','.',$_POST['precovendaorig']);
		$descontoProduto = str_replace(',','.',$_POST['descontoProduto']);
		$precocusto = str_replace(',','.',$_POST['precocusto']);
		$precocustoreais = str_replace(',','.',$_POST['precocustoreais']);
		$precoreal = str_replace(',','.',$_POST['precoreal']);
		$defeito = $_POST['defeito'];
		$descricaodefeito = $_POST['descricaodefeito'];
		$devolucao = $_POST['devolucao'];
		$fator = str_replace(',','.',$_POST['fator']);
		$fatorpedra = str_replace(',','.',$_POST['fatorpedra']);
		$qtdpedra_rel = $_POST['qtdpedra_rel'];
		$quantidadepedra = $_POST['quantidadepedra'];
		$precoetiqueta = $_POST['precoetiqueta'];
		$cotacao_aux = $_POST['cotacao_aux'];
		$etiqueta = $_POST['etiqueta'];
		$valorpedra = str_replace(',','.',$_POST['valorpedra']);
		if($fator == ""){
			$fator = 1;
		}
		$especial = $_POST['especial'];
		$peso = str_replace(',','.',$_POST['peso']);
		

		if($especial){
			$precovenda = round($precoreal * $fator, 2);
		}else{	
			$precovenda = $_POST['precovenda'];
			//$precoreal = $_POST['precovenda'];
		}
		
		if($etiqueta){

		   $precoreal = $precoetiqueta;
           $precovenda = $precoetiqueta * $cotacao_aux;
           $precovendaorig = $precoetiqueta * $cotacao_aux;
           
		}
			
		if($especial ==1)
			$fator_produto = $fator;
		else
			$fator_produto = '1.000';
			
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		// Seleção dos dados da forma de pagamento.
		$sql = "SELECT  Fator FROM tabela_venda";
		$sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda' AND IdLoja = '$funcionario[idloja]'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {       
			$fator_prazo = $rs->get(0);
		}
		$rs->close();
		
		if($idproduto > 0 && $idgrade > 0){
			if($devolucao == '1')
				$quantidade = $quantidade * -1; 
				
			$sql = "SELECT LiberaVenda FROM libera_estoque_loja WHERE IdLoja = $funcionario[idloja]";
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			if($rs->next()) {
				$liberaestoque = $rs->get(0);
			}
			$rs->close();

			$sql = "SELECT Desconto FROM parametros_venda";
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			if($rs->next()) {
				$descontopermitido = $rs->get(0);
			}
			$rs->close();

			$sql = "SELECT Peso FROM estoque_grade
					WHERE IdGrade = $idgrade";
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			if($rs->next()) {
				$totalpeso = $rs->get(0) * $quantidade;
			}
			$rs->close();
			
			if($quantidade == 0 OR $quantidade == 0.00 OR $quantidade == "" OR !$quantidade){
				$msgErro = 'Insira uma quantidade válida!';
			}
			
			if($liberaestoque != 1 && $quantidade > 0){
				$sql = "SELECT IdGrade FROM estoque_grade WHERE IdGrade = $idgrade AND IdLoja = $funcionario[idloja] AND Quantidade >= '$quantidade'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if(!$rs->next()) {
					if($senhaestoque){
						$sql = "SELECT *
								FROM libera_estoque_venda
								WHERE Senha = '".md5($senhaestoque)."'";
						//echo $sql;
						$rs1 = $con->executeQuery ($sql);
						if(!$rs1->next()) {
							unset($isEstoqueOK);
							$msgErro = 'Senha nao confere!<br>Favor verificar!';
							unset($quantidade);
							unset($relacionarproduto);
						}
						$rs1->close();
					}else{
						unset($isEstoqueOK);
						$msgErro = 'Quantidade em estoque nao suficiente para a venda!<br>Favor verificar!';
						unset($quantidade);
						unset($relacionarproduto);
					}
				}
				$rs->close();	
			}
			if(!$msgErro){
				$data = date("Y-m-d");
				$dataemissao = date("d/m/Y");
				$hora = date("H:i:s");
				if($especial != 1){
					if(($precovendaorig != 0.00) && ($descontopermitido == 0)){
						$desconto_aux = (100 - $descontoProduto) /100;
						$descontopermitido = round($precovendaorig * $desconto_aux,2);
					
						$descontopermitido = $descontopermitido_cal - 1;
						
						if($precovenda < $descontopermitido){
							$precovenda = $precovendaorig;
							$msgErro = "Desconto Não Permitido!";
						}else{
							$isDesconto = true;
						}
						
					}elseif($descontopermitido == 1){
				
						if($precovenda < $precocusto){
							$precovenda = $precovendaorig;

							$msgErro = "Desconto Não Permitido 2!";
						}else{
							$isDesconto = true;
						}
					}else{
						$isDesconto = true;
					}
				}else{
					$isDesconto = true;
				}
				
				if($isDesconto ){
				
					if($precovendaorig == '0.00')
						$precovendaorig = $precovenda / $fator_prazo;
				
					$sql = "SELECT PrecoCustoImp FROM estoque WHERE IdProduto = $idproduto AND IdLoja = $funcionario[idloja]";
					$rs = $con->executeQuery ($sql);
					//echo $sql;
					if($rs->next()) {
						$precocusto = $rs->get(0);
					}
					$rs->close();
				
					if($qtdpedra_rel > 0){
					
						$qtdpedra_rel = $qtdpedra_rel;
					
					}else{
					
						$qtdpedra_rel = $quantidadepedra;
					}
					// Inserindo a lista de produtos no banco, tabela 'venda_produto'.
					$sql = "INSERT INTO venda_produto (IdProduto, IdGrade, IdVenda, Quantidade, PrecoCusto, PrecoCustoReais, PrecoVenda, PrecoReal, PrecoVendaOrig, Fator, ValorPedra, FatorPedra, QuantidadePedra, Data, Hora)";
					$sql = "$sql VALUES ('$idproduto', '$idgrade', '$idvenda', '$quantidade', '$precocusto', '$precocustoreais', '$precovenda', '$precoreal', '$precovendaorig', '$fator_produto', '$valorpedra', '$fatorpedra', '$qtdpedra_rel', '$data', '$hora')";
					//echo $sql;
					if($con->executeUpdate($sql) == 1)
						$isVendaProduto = TRUE;
					// Busca do idvendaproduto.
					$sql = "SELECT MAX(IdVendaProduto) FROM venda_produto WHERE IdVenda = '$idvenda'";
					$rs = $con->executeQuery ($sql);
					if($rs->next())
						$idvendaproduto = $rs->get(0);
					$rs->close();
					
					if(!$defeito) {
						$sql = "UPDATE estoque SET";
						$sql = "$sql Quantidade = Quantidade - '$quantidade',";
						$sql = "$sql Peso = Peso - '$totalpeso'";
						$sql = "$sql WHERE IdProduto = '$idproduto' AND IdLoja = '$funcionario[idloja]'";
						//echo $sql;
						if($con->executeUpdate($sql) == 1)
							$isProduto = TRUE;
							
						// Updata para atualizar a quantidade do produto.
						$sql = "UPDATE estoque_grade SET";
						$sql = "$sql Quantidade = Quantidade - '$quantidade'";
						$sql = "$sql WHERE IdGrade = '$idgrade' AND IdLoja = '$funcionario[idloja]'";
						//echo $sql;
						if($con->executeUpdate($sql) == 1)
							$isProduto = TRUE;

						  // Insere os dados na tabela 'produto_troca'.
						if($quantidade < 0) {
							// Cálculo do idprodutotroca.
							$sql = "SELECT MAX(IdProdutoTroca) FROM produto_troca";
							$rs = $con->executeQuery ($sql);
							if($rs->next())
								$idprodutotroca = $rs->get(0) + 1;
							$rs->close();

							$qtde = $quantidade * (-1);
							// Inserindo o produto na tabela 'produto_troca'.
							$sql = "INSERT INTO produto_troca (IdProdutoTroca, IdVendaProduto, IdProduto, IdGrade, IdVenda, IdLoja, Quantidade, PrecoCusto, PrecoVenda, ValorPedra, FatorPedra, QuantidadePedra, DataEntrada, HoraEntrada)";
							$sql = "$sql VALUES ('$idprodutotroca', '$idvendaproduto', '$idproduto', '$idgrade', '$idvenda', '$funcionario[idloja]', '$qtde', '$precocusto', '$precovenda', '$valorpedra', '$fatorpedra', '$qtdpedra_rel', '$data', '$hora')";
							//echo $sql;
							if($con->executeUpdate($sql) == 1)
								$isProdutoTroca = TRUE;
						}
					}	else {
						// Cálculo do idprodutodefeito.
						$sql = "SELECT MAX(IdProdutoDefeito) FROM produto_defeito";
						$rs = $con->executeQuery ($sql);
						if($rs->next())
							$idprodutodefeito = $rs->get(0) + 1;
						$rs->close();
						$qtde = $quantidade * (-1);

						// Inserindo o produto na tabela 'produto_defeito'.
						$sql = "INSERT INTO produto_defeito (IdProdutoDefeito, IdVendaProduto, IdProduto, IdGrade, IdVenda, IdSituacaoDefeito, IdLoja, DataEntrada, HoraEntrada, Quantidade, PrecoCusto, PrecoVenda, ValorPedra, FatorPedra, QuantidadePedra, Observacao)";
						$sql = "$sql VALUES ('$idprodutodefeito', '$idvendaproduto', '$idproduto', '$idgrade', '$idvenda', 1, '$funcionario[idloja]', '$data', '$hora', '$qtde', '$precocusto', '$precovenda', '$valorpedra', '$fatorpedra', '$qtdpedra_rel', '$descricaodefeito')";
						//echo $sql;
							if($con->executeUpdate($sql) == 1)
								$isProduto = TRUE;
					}
				}
			}
			echo utf8_encode($msgErro);
		}
?>
