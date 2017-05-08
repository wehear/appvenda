<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$quantidade = $_POST['quantidade'];
		$precovenda = $_POST['precovenda'];
		$idvenda = $_POST['idvenda'];
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		$sql = "SELECT Quantidade, PrecoVenda 
				FROM venda_produto
				WHERE IdVenda = $idvenda";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while ($rs->next ()) {

			$somavalortotal += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
					
			if($rs->get("Quantidade") < 0){
				$somavalordev += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
			}
						
			if($rs->get("Quantidade") > 0){
				$somavalor += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
			}
	
		}	
		$rs->close();
		
		$somavalordev += (sprintf("%1.2f", $precovenda * $quantidade)) * -1;
		
		if($somavalor == '0' || $somavalor == "")
			$somavalor_aux = 100;
		else
			$somavalor_aux = $somavalor;
				
		$somaporcentagem = (($somavalordev * 100) / $somavalor_aux) * -1;
		
		$sql = "SELECT MaximoDevolucao 
				FROM alteracao_parametrosvenda";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while ($rs->next ()) {

			$maximodevolucao = $rs->get("MaximoDevolucao");
					
		}	
		$rs->close();

		if($somaporcentagem > $maximodevolucao)
			echo utf8_encode('Venda já atingiu a porcentagem máxima de devolução!');
	
?>