<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idconferencia = $_POST['idconferencia'];
		$idgrade = $_POST['idgrade'];
		$idproduto = $_POST['idproduto'];
		$idvenda = $_POST['idvenda'];
		$quantidade = $_POST['quantidade'];
		$precovendaorig = $_POST['precovendaorig'];
		$descontoProduto = $_POST['descontoProduto'];
		$precovenda = str_replace(',','.',$_POST['precovenda']);
		$precocusto = $_POST['precocusto'];
		$precocustoreais = $_POST['precocustoreais'];
		$precoreal = $_POST['precoreal'];
		$defeito = $_POST['defeito'];
		$descricaodefeito = $_POST['descricaodefeito'];
		$devolucao = $_POST['devolucao'];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		if($idproduto > 0 && $idgrade > 0){
			if($quantidade == 0 OR $quantidade == 0.00 OR $quantidade == "" OR !$quantidade){
				$msgErro = 'Insira uma quantidade válida!';
				$isErro = true;
			}
			
			if($devolucao == '1'){
				$quantidade = $quantidade * -1;
			}
		
			$sql = "
					SELECT Quantidade, QtdConferida
					FROM venda_produto
					WHERE IdVenda = $idvenda AND IdProduto = $idproduto 
					AND IdGrade = $idgrade AND PrecoVenda = '$precovenda'
					AND PrecoVenda > '0'
					AND (QtdConferida != Quantidade OR QtdConferida IS NULL)";
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			while($rs->next()){
		
				$diferenca = $rs->get("Quantidade") - $rs->get("QtdConferida");
				
				if($diferenca < 0)
					$dif_neg += $diferenca;
				else
					$dif_pos += $diferenca;
					
		
			}
			$rs->close();
		
		 	/*echo 'diferenca'.$diferenca;
			echo 'dif_neg'.$dif_neg;
			echo 'dif_pos'.$dif_pos;
			//echo 'dif'.$dif_neg.'qtd'.$quantidade; */
	 
			if($quantidade < 0 && $dif_neg){
			
				if($quantidade < $dif_neg){
					$isErro = true;
					$msgErro = 'Quantidade de ítens maior que a quantidade da nota!';
					$isConferido = False;
				}else if($quantidade > $dif_neg){
					//$msgErro = 'Quantidade de ítens menor que a quantidade da nota! Verifique que ainda faltam ítens!';
					$isConferido = true;
				}else if($quantidade == $dif_neg){
					$isConferido = true;
				}
			}elseif($quantidade > 0 && $dif_pos){
			
				if($quantidade > $dif_pos){
					$isErro = true;
					$msgErro = 'Quantidade de ítens maior que a quantidade da nota!';
					$isConferido = False;
				}else if($quantidade < $dif_pos){
					//$msgErro = 'Quantidade de ítens menor que a quantidade da nota! Verifique que ainda faltam ítens!';
					$isConferido = true;
				}else if($quantidade == $dif_pos){
					$isConferido = true;
				}
			}else{
				$isErro = true;
				$msgErro = 'Quantidade de ítens não confere com a quantidade da nota! Verifique também o preço do produto!';
				$isConferido = False;
			}
			
			if(!$isErro){
			
				$quantidade_aux = $quantidade;
				
				if($quantidade < 0){
					while($quantidade != 0){ 
						$sql = "
								SELECT Quantidade, QtdConferida, IdVendaProduto
								FROM venda_produto
								WHERE IdVenda = $idvenda AND IdProduto = $idproduto 
								AND IdGrade = $idgrade AND PrecoVenda = '$precovenda'
								AND (QtdConferida != Quantidade OR QtdConferida IS NULL)
								AND Quantidade < 0";
						$rs = $con->executeQuery ($sql);
						//echo $sql;
						if($rs->next()){
					
							$diferenca = $rs->get("Quantidade") - $rs->get("QtdConferida");
							$idvendaproduto = $rs->get("IdVendaProduto");
							
							if($quantidade < $diferenca){
								$qtd_update = $diferenca;
								$quantidade = $quantidade - $diferenca;
								
								$sql = "UPDATE venda_produto SET
										QtdConferida = ".$rs->get("QtdConferida")." + '$qtd_update'
										WHERE IdVendaProduto = $idvendaproduto";
								$con->executeQuery ($sql); 
								
							}elseif($quantidade > $diferenca || $quantidade == $diferenca){
								$qtd_update = $quantidade;
								$quantidade = 0;
								
								$sql = "UPDATE venda_produto SET
										QtdConferida = ".$rs->get("QtdConferida")." + '$qtd_update'
										WHERE IdVendaProduto = $idvendaproduto";
								$con->executeQuery ($sql);
							}
					
						}
						$rs->close();
						
					}
					
				}elseif($quantidade > 0){
						while($quantidade != 0){ 
							$sql = "
									SELECT Quantidade, QtdConferida, IdVendaProduto
									FROM venda_produto
									WHERE IdVenda = $idvenda AND IdProduto = $idproduto 
									AND IdGrade = $idgrade AND PrecoVenda = '$precovenda'
									AND (QtdConferida != Quantidade OR QtdConferida IS NULL)
									AND Quantidade > 0";
							$rs = $con->executeQuery ($sql);
							//echo $sql;
							if($rs->next()){
					
								$diferenca = $rs->get("Quantidade") - $rs->get("QtdConferida");
								$idvendaproduto = $rs->get("IdVendaProduto");
								
								if($quantidade > $diferenca){
									$qtd_update = $diferenca;
									$quantidade = $quantidade - $diferenca;
									
									$sql = "UPDATE venda_produto SET
											QtdConferida = ".$rs->get("QtdConferida")." + '$qtd_update'
											WHERE IdVendaProduto = $idvendaproduto";
									$con->executeQuery ($sql);
									
								}elseif($quantidade < $diferenca || $quantidade == $diferenca){
									$qtd_update = $quantidade;
									$quantidade = 0;
								
									$sql = "UPDATE venda_produto SET
											QtdConferida = ".$rs->get("QtdConferida")." + '$qtd_update'
											WHERE IdVendaProduto = $idvendaproduto";
									$con->executeQuery ($sql);
								}
					
							}
							$rs->close();
						}
				}
		

				// Inserindo a lista de produtos no banco, tabela 'venda_produto'.
				$sql = "INSERT INTO notaconferenciavenda_produto (IdProduto, IdGrade, IdNotaConferenciaVenda, Quantidade, PrecoCusto,
						PrecoVenda, PrecoReal, PrecoVendaOrig, Data, Hora)";
				$sql = "$sql VALUES ('$idproduto', '$idgrade', '$idconferencia', '$quantidade_aux', '$precocusto', 
					'$precovenda', '$precoreal', '$precovendaorig', '$data', '$hora')";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isVendaProduto = TRUE;	

			} 
		}
		echo utf8_encode($msgErro);
?>
