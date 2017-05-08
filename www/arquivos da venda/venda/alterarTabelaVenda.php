<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idvenda = $_POST['idvenda'];
		$idtabelavenda = $_POST['idtabelavenda'];
	
		$con = new Connection;
		$con->open(); 

		$sql = "SELECT Fator
				FROM tabela_venda 
				WHERE IdTabelaVenda = '$idtabelavenda'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()){
			$fator = $rs->get("Fator");
		}
		$rs->close();
		
		$sql = "SELECT IdVendaProduto, PrecoVendaOrig, PrecoVenda
				FROM venda_produto 
				WHERE IdVenda = '$idvenda'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while($rs->next()){
			
			$precovenda = $rs->get("PrecoVendaOrig") * $fator;
			
			$sql = "UPDATE venda_produto SET
					PrecoVenda = '$precovenda'
					WHERE IdVendaProduto = ".$rs->get("IdVendaProduto");
			$con->executeQuery ($sql);
		}
		$rs->close();
		






?>