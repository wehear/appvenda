<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		 
		$idtabelavenda = $_POST['idtabelavenda'];
		
		// Abertura da conexуo.
		$con = new Connection;
		$con->open(); 

	 	// Seleчуo dos dados da forma de pagamento.
		$sql = "SELECT  QuantidadeParcela, Fator, Crediario FROM tabela_venda";
		$sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda' AND IdLoja = '$funcionario[idloja]'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {				
			$quantidadeparcelapermitida = $rs->get(0);
			$fator = $rs->get(1);
			$primeiraparcela= $rs->get(2);
			$crediario= $rs->get(2);
		}
		$rs->close();

		if($quantidadeparcelapermitida == 1) {
			$quantidadeparcela = 1;
		}
	
		echo $crediario.'|'.
			$fator.'|'.
			$primeiraparcela.'|'.
			$quantidadeparcelapermitida; 
	
?>