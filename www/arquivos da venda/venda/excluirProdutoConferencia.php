<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$id = $_POST['id'];

		// Abertura da conexão.
		$con = new Connection;
		$con->open();  

	 	$sql = "SELECT nota_conferencia_venda.IdNotaConferenciaVenda, IdVenda 
				FROM nota_conferencia_venda, notaconferenciavenda_produto
				WHERE nota_conferencia_venda.IdNotaConferenciaVenda = notaconferenciavenda_produto.IdNotaConferenciaVenda
				AND IdNotaConferenciaVendaProduto = $id";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {
			$idconferencia = $rs->get("IdNotaConferenciaVenda");
			$idvenda = $rs->get("IdVenda");
		}
		$rs->close();
		
		$sql = "SELECT IdProduto, IdGrade, Quantidade, PrecoVenda 
				FROM notaconferenciavenda_produto
				WHERE IdNotaConferenciaVendaProduto = $id";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {
			$idproduto = $rs->get("IdProduto");
			$idgrade = $rs->get("IdGrade");
			$quantidade = $rs->get("Quantidade");
			$precovenda = $rs->get("PrecoVenda");
		}
		$rs->close();
		
		if($quantidade < 0){
			while($quantidade != 0){ 
			
				$sql = "
						SELECT QtdConferida, IdVendaProduto
						FROM venda_produto
						WHERE IdVenda = $idvenda AND IdProduto = $idproduto 
						AND IdGrade = $idgrade AND PrecoVenda = '$precovenda'
						AND QtdConferida IS NOT NULL AND Quantidade < 0";
				$rs = $con->executeQuery ($sql);
				//echo $sql;
				if($rs->next()){
				
					$idvendaproduto = $rs->get("IdVendaProduto");
						
					if($quantidade > $rs->get("QtdConferida")){
					
						$update = $rs->get("QtdConferida") - $quantidade;
						$quantidade = 0;
							
						$sql = "UPDATE venda_produto SET
								QtdConferida = '$update'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
							
					}elseif($quantidade == $rs->get("QtdConferida")){
						$quantidade = 0;
							
						$sql = "UPDATE venda_produto SET
								QtdConferida = 'NULL'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
							
					}elseif($quantidade < $rs->get("QtdConferida")){
						$quantidade = $quantidade - $rs->get("QtdConferida");
							
						$sql = "UPDATE venda_produto SET
								QtdConferida = 'NULL'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
					}
				}
				$rs->close();
			}
		}elseif($quantidade > 0){
			while($quantidade != 0){ 
				$sql = "
						SELECT QtdConferida, IdVendaProduto
						FROM venda_produto
						WHERE IdVenda = $idvenda AND IdProduto = $idproduto 
						AND IdGrade = $idgrade AND PrecoVenda = '$precovenda'
						AND QtdConferida IS NOT NULL AND Quantidade > 0";
				$rs = $con->executeQuery ($sql);
				//echo $sql;
				if($rs->next()){
				
					$idvendaproduto = $rs->get("IdVendaProduto");
							
					if($quantidade > $rs->get("QtdConferida")){
						$quantidade = $quantidade - $rs->get("QtdConferida");
								
						$sql = "UPDATE venda_produto SET
								QtdConferida = 'NULL'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
								
					}elseif($quantidade == $rs->get("QtdConferida")){
						$quantidade = 0;
							
						$sql = "UPDATE venda_produto SET
								QtdConferida = 'NULL'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
						
					}elseif($quantidade < $rs->get("QtdConferida")){
						
						$update = $rs->get("QtdConferida") - $quantidade;
						
						$quantidade = 0;
							
						$sql = "UPDATE venda_produto SET
								QtdConferida = '$update'
								WHERE IdVendaProduto = $idvendaproduto";
						$con->executeQuery ($sql);
					}
				
				}
				$rs->close();
			}
		} 
		
		
		$sql = "DELETE FROM notaconferenciavenda_produto";
		$sql = "$sql WHERE IdNotaConferenciaVendaProduto = $id";
		//echo $sql;
		if($con->executeUpdate($sql) == 1)
			$isExcluiVendaProduto = TRUE;	
			
		echo $isExcluiVendaProduto;
	
?>