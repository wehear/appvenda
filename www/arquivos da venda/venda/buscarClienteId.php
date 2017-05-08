<?
		session_start();
		$funcionario = $_SESSION['funcionario'];
		
		include_once ("../../sistema/includes/connection.inc");
		
		// Abertura da conexпїЅo.
		$con = new Connection;
		$con->open();

		$idcliente = $_POST['idcliente'];
		$acao = $_POST['acao'];
		
		if($idcliente && $acao == 'busca_cliente') {
		
			// Seleзгo do Nome do cliente.   
			$sql = "SELECT IdCliente, Nome, IdTipoBloqueio, CPF, Naturalidade, DataNascimento, RG, Telefone1, Pai, Mae, Endereco, TipoCliente FROM cliente";
			$sql = "$sql WHERE IdCliente = '$idcliente' AND Ativo = '1'";			
			$rs = $con->executeQuery ($sql);
			//echo $sql;
			if($rs->next()) {
				$idcliente = $rs->get("IdCliente");
				$nomecliente = $rs->get("Nome");
				$idtipobloqueio = $rs->get("IdTipoBloqueio");
				$cpfcliente = $rs->get("CPF");
				$naturalidadecliente = $rs->get("Naturalidade");
				$datanascimentocliente = $rs->get("DataNascimento");
				$rgcliente = $rs->get("RG");
				$telcliente = $rs->get("Telefone1");
				$paicliente = $rs->get("Pai");
				$maecliente = $rs->get("Mae");
				$enderecocliente = $rs->get("Endereco");
				$tipocliente = $rs->get("TipoCliente");

				$sql = "SELECT Bloqueio, Nome FROM tipo_bloqueio";
				$sql = "$sql WHERE IdTipoBloqueio = '$idtipobloqueio'";			
				$rs2 = $con->executeQuery ($sql);
				if($rs2->next()) {
				   if($rs2->get(0) == '1') {
						$isBloqueio = 'Cliente Bloqueado - '.$rs2->get(1);
						$idcliente = "";
						$nomecliente = "";
						$nomeconsulta = "";
						$codigocliente = "";
					}
				}
				$rs2->close();
				
				$sql = "SELECT conjuge.Cpf FROM cliente, conjuge";
				$sql = "$sql WHERE cliente.IdCliente = conjuge.IdCliente AND IdCliente = '$codigocliente'";			
				$rs2 = $con->executeQuery ($sql);
				if($rs2->next()) {
					$cpf = $rs2->get(0);
					
					// Seleзгo do Nome do cliente.   
					$sql = "SELECT Bloqueio, tipo_bloqueio.Nome FROM cliente, tipo_bloqueio";
					$sql = "$sql WHERE tipo_bloqueio.IdTipoBloqueio = cliente.IdTipoBloqueio' AND CPF = $cpf'";			
					$rs = $con->executeQuery ($sql);
					if($rs3->next()) {
						if($rs3->get(0) == '1') {
							$isBloqueio = 'Conjuge Bloqueado - '.$rs3->get(1);
							$idcliente = "";
							$nomecliente = "";
							$nomeconsulta = "";
							$codigocliente = "";
						}
					}
					$rs3->close();
					
				}
				$rs2->close();
				
				$sql = "SELECT Limite, SaldoVale FROM cliente WHERE IdCliente = '$idcliente'";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next())
					$valorcredito = $rs1->get(0);
					$saldovale = $rs1->get(1);
				$rs1->close();
			}
			$rs->close();	
			
			echo $idcliente.'|'.
				$nomecliente.'|'.
				$idtipobloqueio.'|'.
				$cpfcliente.'|'.
				$naturalidadecliente.'|'.
				$datanascimentocliente .'|'.
				$rgcliente.'|'.
				$telcliente.'|'.
				$paicliente.'|'.
				$maecliente.'|'.
				$enderecocliente.'|'.
				$tipocliente.'|'.
				$valorcredito.'|'.
				$saldovale.'|'.
				$isBloqueio;
		}
  
?>