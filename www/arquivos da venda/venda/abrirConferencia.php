<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");

		$idvenda = $_POST['idvenda'];
		$idusuario = $_POST['idusuario'];

		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 

		if($idvenda)  {
		 	
		 	$data = date("Y-m-d");
		 	$hora = date("H:i:s");


				$sql = "SELECT IdNotaConferenciaVenda, Fechada, IdUsuario FROM nota_conferencia_venda";
				$sql = "$sql WHERE IdVenda = '$idvenda'
						AND Ativo = '1'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if($rs->next ()) {
					if($rs->get(1) == '0') {
						$idconferencia = $rs->get(0);
						$idusuario = $rs->get(1);
                        $isVenda = TRUE;
					}
					$isVenda = FALSE;
				}
				else
					$isVenda = TRUE;
					
				$rs->close();
				
				$sql = "SELECT Nome FROM funcionario";
				$sql = "$sql WHERE IdFuncionario = '$idusuario'
						AND Ativo = '1'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if($rs->next ()) {
					$nomeusuario = $rs->get(0);
				}
				$rs->close();

				 if($isVenda){

					// Inserindo a lista de produtos no banco, tabela 'venda_produto'.
					$sql = "INSERT INTO nota_conferencia_venda (IdUsuario, IdUsuarioLogin, IdVenda, Data, Hora, Fechada,  Ativo)";
					$sql = "$sql VALUES ('$idusuario','$funcionario[idfuncionario]', '$idvenda', '$data', '$hora', '0', '1')";
					//echo $sql;
					if($con->executeUpdate($sql) == 1)
						$isVenda = TRUE;
						
					//Buscar o idvenda.
					$sql = "SELECT MAX(IdNotaConferenciaVenda) FROM nota_conferencia_venda WHERE IdVenda = '$idvenda'
							AND IdVenda = '$idvenda'";
					$rs = $con->executeQuery ($sql);
					if($rs->next())
						$idconferencia = $rs->get(0);
					$rs->close();
									
				}

			
			echo $idconferencia.'|'.utf8_encode($msg).'|'.$idusuario.'|'.utf8_encode($nomeusuario);
			
	    }else{
			echo '0|'.utf8_encode("Erro ao abrir a conferência. Verificar se a venda está aberto ou já foi conferida. Caso o erro persista, favor entrar em contato com o administrador.");
		}	 

	
?>
