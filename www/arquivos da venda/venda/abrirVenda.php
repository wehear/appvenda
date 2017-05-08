<?
		session_start();
		$funcionario = $_SESSION["funcionario"];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$idcliente = $_POST['idcliente'];
		$idfuncionario = $_POST['idfuncionario'];
		$crediario = $_POST['crediario'];
		$idtabelavenda = $_POST['idtabelavenda'];
		$especial = $_POST['especial'];
		$idsecao = $_POST['idsecao'];
		$idmoeda = $_POST['idmoeda'];
	
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		
		if($especial == "1"){
			$idtipovenda = 8;
			$especial_bc = 1;
			$idmoeda = 1;
		}elseif($especial == "2"){
			$idtipovenda = 10;
			$idmoeda = 2;
		}elseif($especial == "orcamento") {
			$idtipovenda = 9;
		} else {
			$idtipovenda = 1;
		}

		if($idcliente && $idfuncionario)  {
		 	
		 	$data = date("Y-m-d");
		 	$hora = date("H:i:s"); 

			if($crediario == '1'){
			
				$sql = "SELECT IdCliente FROM cliente	WHERE IdCliente = $idcliente AND BarraCrediario = '0'";
				//echo $sql;						
				$rs = $con->executeQuery ($sql);
				if($rs->next ()) {
					$isVendaOK = true;
				}else{
					$msg = 'Cliente não permitido para o prazo escolhido!';
				}
				$rs->close();
				
			}else{
				$isVendaOK = true;
			}

			if($isVendaOK){
			
				unset($sql1);
				$sql1 = "SELECT IdVenda, Fechada  FROM venda WHERE IdFuncionario = $idfuncionario AND IdCliente = $idcliente AND Ativo = 1 AND Fechada = 0 AND IdLoja = $funcionario[idloja] AND IdTipoVenda = $idtipovenda";
				//echo $sql;
				$rs_teste = $con->executeQuery ($sql1);
				if($rs_teste->next()) {
					$idvenda = $rs_teste->get(0);
				}
				$rs_teste->close();

				if(!$idvenda){

					// Inserindo a lista de produtos no banco, tabela 'venda_produto'.
					$sql = "INSERT INTO venda (IdFuncionario, IdCliente, IdTipoVenda, IdLoja, Data, Hora, Especial, Cancelada, Baixa, Fechada, Renegociada, Reaberta, Ativo)";
					$sql = "$sql VALUES ('$idfuncionario', '$idcliente', '$idtipovenda', '$funcionario[idloja]','$data', '$hora','$especial_bc', '0', '0', '0', '0', '0', '1')";
					//echo $sql;
					if($con->executeUpdate($sql) == 1)
						$isVenda = TRUE;
						
					//Buscar o idvenda.
					$sql = "SELECT MAX(IdVenda) FROM venda WHERE IdCliente = '$idcliente' AND IdLoja = '$funcionario[idloja]' AND IdFuncionario = '$idfuncionario' AND IdTipoVenda = $idtipovenda";
					$rs = $con->executeQuery ($sql);
					if($rs->next())
						$idvenda = $rs->get(0);
					$rs->close();
									
				}
			}
			
			echo $idvenda.'|'.utf8_encode($msg).'|'.$sql1;
			
	    }else{
			echo '0|'.utf8_encode("Erro ao abrir a venda. Verificar vendedor, cliente e prazo de pagamento. Caso o erro persista, favor entrar em contato com o administrador.");
		}	 

	
?>
