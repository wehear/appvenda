<?
		session_start();
		$funcionario = $_SESSION['funcionario'];
		
		include_once ("../../sistema/includes/connection.inc");
		
		// Abertura da conexï¿½o.
		$con = new Connection;
		$con->open();

		$nomeconsulta = $_POST['nomeconsulta'];
		$representante = $_POST['representante'];
		$acao = $_POST['acao'];

		if($nomeconsulta && $acao == 'busca_combo') {

			$isCliente = false;
			
			$header = 
					'<select name="codigocliente" id="codigocliente" onblur = "submeterCmbCliente();" >
						<option value="">Selecione o Cliente</option>';
			
						$sql = "SELECT IdCliente, Nome 
								FROM cliente
								WHERE Nome LIKE '%$nomeconsulta%' AND Ativo = 1";
						if($representante)
							$sql = "$sql AND IdTipoCliente = '3'";
							
						$sql = "$sql ORDER BY Nome";	
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						while($rs->next()) {													
							
							$isCliente = 1;		
							$opcoes .= "<option value='".$rs->get(0)."'>".$rs->get(1)."</option>";
																						
						}
						$rs->close();
						
						$footer .= "
								</select>&nbsp;&nbsp;
								<input type=\"button\" name=\"novabusca\" id=\"novabusca\" value=\"Nova Busca\" onclick = \"submeterNovaBusca();\">";
			
			if($opcoes){
				$combo = $header.$opcoes.$footer;
			}else{
				$msg = "Cliente não encontrado!";
			}
			
			echo utf8_encode($combo).'|'.utf8_encode($msg).''; 
		} 
  
		if($nomeconsulta && $acao == 'busca_combo_cod') {

	
			$header = 
					'<select name="codigocliente" id="codigocliente_cmb" onchange = "submeterCmbCliente();" >
						<option value="">Selecione o Cliente</option>';
			
						$sql = "SELECT IdCliente, Nome 
								FROM cliente
								WHERE Nome LIKE '%$nomeconsulta%' AND Ativo = 1";
						if($representante)
							$sql = "$sql AND IdTipoCliente = '3'";
							
						$sql = "$sql ORDER BY Nome";	
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						while($rs->next()) {													
								
							$opcoes .= "<option value='".$rs->get(0)."'>".$rs->get(1)."</option>";
																						
						}
						$rs->close();
						
						$footer .= "
								</select>&nbsp;&nbsp;
								<input type=\"button\" name=\"novabusca\" id=\"novabusca\" value=\"Nova Busca\" onclick = \"submeterNovaBusca();\">";
			
			if($opcoes){
				$combo = $header.$opcoes.$footer;
			}else{
				$msg = "Cliente não encontrado!";
			}
			
			echo utf8_encode($combo).'|'.utf8_encode($msg); 
		} 
  
?>