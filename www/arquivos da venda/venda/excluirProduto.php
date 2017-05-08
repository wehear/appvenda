<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idvendaproduto = $_POST['idvendaproduto'];

		// Abertura da conexуo.
		$con = new Connection;
		$con->open(); 

		$sql = "SELECT IdGrade, IdProduto, Quantidade
				FROM venda_produto
				WHERE IdVendaProduto = $idvendaproduto";
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {
			$idgrade = $rs->get("IdGrade");
			$idproduto = $rs->get("IdProduto");
			$quantidade = $rs->get("Quantidade");
		}
		$rs->close();
		
		if($quantidade >= 0){
		
			$sql = "DELETE FROM venda_produto";
			$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
			//echo $sql;
			if($con->executeUpdate($sql) == 1)
				$isExcluiVendaProduto = TRUE;			
					
			// Atualizaчуo da tabela produto.
			$sql = "UPDATE estoque SET";
   			$sql = "$sql Quantidade = Quantidade + $quantidade";
			$sql = "$sql WHERE IdProduto = $idproduto AND IdLoja = '$funcionario[idloja]'";
			//echo $sql;
			if($con->executeUpdate($sql) == 1)
				$isProduto = TRUE;

            // Atualizaчуo da tabela produto.
			$sql = "UPDATE estoque_grade SET";
			$sql = "$sql Quantidade = Quantidade + $quantidade";
			$sql = "$sql WHERE IdGrade = $idgrade AND IdLoja = '$funcionario[idloja]'";
			//echo $sql;
			if($con->executeUpdate($sql) == 1)
				$isProduto = TRUE;
                            							
		}
		else{
			$sql = "SELECT IdProdutoTroca FROM produto_troca";
			$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()){			 				    
				$verificaIdProdutoTroca = TRUE;
			}
			$rs->close();
						
			if($verificaIdProdutoTroca){		
				$sql = "DELETE FROM produto_troca";
				$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
				$isExcluiVendaProduto = TRUE;
						
				$sql = "DELETE FROM venda_produto";
				$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isExcluiVendaProduto = TRUE;
											
				// Atualizaчуo da tabela produto.
				$sql = "UPDATE estoque SET";
				$sql = "$sql Quantidade = Quantidade + $quantidade";
				$sql = "$sql WHERE IdProduto = $idproduto AND IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isProduto = TRUE;

				// Atualizaчуo da tabela produto.
				$sql = "UPDATE estoque_grade SET";
				$sql = "$sql Quantidade = Quantidade + $quantidade";
				$sql = "$sql WHERE IdGrade = $idgrade AND IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isProduto = TRUE;
                            							
			}
						
			$sql = "SELECT IdProdutoDefeito FROM produto_defeito";
			$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()){
				$verificaIdProdutoDefeito = TRUE;
			}
			$rs->close();
						
			if($verificaIdProdutoDefeito){						
				$sql = "DELETE FROM produto_defeito";
				$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isExcluiVendaProduto = TRUE;
							
				$sql = "DELETE FROM venda_produto";
				$sql = "$sql WHERE IdVendaProduto = $idvendaproduto";
				//echo $sql;
				if($con->executeUpdate($sql) == 1)
					$isExcluiVendaProduto = TRUE;						
							
			} 				 	
					
		}
		
		echo $isExcluiVendaProduto;
?>