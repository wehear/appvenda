<?
        session_start();
		$funcionario = $_SESSION[funcionario];
		
        include_once ("../../sistema/includes/connection.inc");
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open();
		
		$idestabelecimento = $_POST['idestabelecimento'];
		$idloja = $_POST['idloja'];
		$idfuncionario = $_POST['idfuncionario'];
		$numerovenda = $_POST['numerovenda'];
		$usuario = $_POST['usuario'];
		$senha = trim($_POST['senha']);
		$acao = $_POST['acao'];
		
		if($idfuncionario && $acao == 'busca_funcionario'){
		
			$sql = "SELECT Nome, Sexo, DataNascimento, RG, OrgaoEmissor, CPF, 
					Endereco, Bairro, Cidade, Estado, CEP, Telefone, Celular, Email, NumeroVenda
					FROM funcionario WHERE IdFuncionario = $idfuncionario";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if ($rs->next()) {
		
				$nome = $rs->get("Nome");
				$sexo = $rs->get("Sexo");
				$dnasc = substr($rs->get("DataNascimento"),8,2);
				$mnasc = substr($rs->get("DataNascimento"),5,2);
				$anasc = substr($rs->get("DataNascimento"),0,4);
				$rg = $rs->get("RG");
				$orgaoemissor = $rs->get("OrgaoEmissor");
				$cpf = $rs->get("CPF");
				$endereco = $rs->get("Endereco");
				$bairro = $rs->get("Bairro");
				$cidade = $rs->get("Cidade");
				$cep = $rs->get("CEP");
				$telefone = $rs->get("Telefone");
				$celular = $rs->get("Celular");
				$email = $rs->get("Email");
				$numerovenda = $rs->get("NumeroVenda");
				
			}
			$rs->close();
			
			echo utf8_encode($nome).'|'.
				$sexo.'|'.
				$dnasc.'|'.
				$mnasc.'|'.
				$anasc.'|'.
				$rg.'|'.
				$orgaoemissor.'|'.
				$cpf.'|'.
				utf8_encode($endereco).'|'.
				utf8_encode($bairro).'|'.
				utf8_encode($cidade).'|'.
				$cep.'|'.
				$telefone.'|'.
				$celular.'|'.
				utf8_encode($email).'|'.
				$numerovenda;
		
		}
		
		if($numerovenda && $acao == 'busca_numerovenda'){

			$sql = "SELECT Nome, Sexo, DataNascimento, RG, OrgaoEmissor, CPF, 
					Endereco, Bairro, Cidade, Estado, CEP, Telefone, Celular, Email,
					IdFuncionario
					FROM funcionario WHERE NumeroVenda = '$numerovenda' AND Ativo = 1";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if ($rs->next()) {
		
				$nome = $rs->get("Nome");
				$sexo = $rs->get("Sexo");
				$dnasc = substr($rs->get("DataNascimento"),8,2);
				$mnasc = substr($rs->get("DataNascimento"),5,2);
				$anasc = substr($rs->get("DataNascimento"),0,4);
				$rg = $rs->get("RG");
				$orgaoemissor = $rs->get("OrgaoEmissor");
				$cpf = $rs->get("CPF");
				$endereco = $rs->get("Endereco");
				$bairro = $rs->get("Bairro");
				$cidade = $rs->get("Cidade");
				$cep = $rs->get("CEP");
				$telefone = $rs->get("Telefone");
				$celular = $rs->get("Celular");
				$email = $rs->get("Email");
				$idfuncionario = $rs->get("IdFuncionario");
				
			}
			$rs->close();
			
			echo utf8_encode($nome).'|'.
				$sexo.'|'.
				$dnasc.'|'.
				$mnasc.'|'.
				$anasc.'|'.
				$rg.'|'.
				$orgaoemissor.'|'.
				$cpf.'|'.
				utf8_encode($endereco).'|'.
				utf8_encode($bairro).'|'.
				utf8_encode($cidade).'|'.
				$cep.'|'.
				$telefone.'|'.
				$celular.'|'.
				utf8_encode($email).'|'.
				$idfuncionario; 
		
		}
		if($acao == 'busca_usuario'){
			// Verificacao do Funcionario e Loja.
			$sql = "SELECT funcionario.IdFuncionario, funcionario.Nome
				FROM funcionario, loja, estabelecimento
				WHERE funcionario.IdFuncionario = '$funcionario[idfuncionario]' AND loja.IdLoja = '$funcionario[idloja]'
				AND funcionario.IdEstabelecimento = estabelecimento.IdEstabelecimento
				AND loja.Idloja = estabelecimento.IdLoja AND loja.Ativo = 1 AND funcionario.Ativo = 1";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
				$idfuncionario = $rs->get(0);
				$nomefuncionario = $rs->get(1);

				echo $idfuncionario.'|'.$nomefuncionario;
			}
			$rs->close();

		}
		
		if($idestabelecimento && $acao == 'cmb_estabelecimento'){
			$sql = "SELECT IdFuncionario, Nome FROM funcionario WHERE IdEstabelecimento = $idestabelecimento";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while ($rs->next()) {
		
				$html .= '<option value = "'.$rs->get(0).'">'.trim($rs->get(1)).'</option>';
				
			}
			$rs->close();
			
			if($html){
			
			
				$header = '
							<b>Funcionário</b>&nbsp;&nbsp;&nbsp;&nbsp;
								<select name = "idfuncionario_aux" id = "idfuncionario_aux" onchange="submeterFuncionario();">
									<option value = "">Selecione o Funcionário</option>
				';
			
				$footer = '</select>';
				
				echo utf8_encode($header.$html.$footer);
			}
		}
		
		if($idloja && $acao == 'cmb_loja'){
		
			$sql = "SELECT IdFuncionario, funcionario.Nome 
					FROM funcionario, estabelecimento 
					WHERE estabelecimento.IdEstabelecimento = funcionario.IdEstabelecimento
					AND IdLoja = $idloja";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while ($rs->next()) {
		
				$html .= '<option value = "'.$rs->get(0).'">'.trim($rs->get(1)).'</option>';
				
			}
			$rs->close();
			
			if($html){
			
			
				$header = '
								<select name = "idfuncionario_aux" id = "idfuncionario_aux" onchange="submeterFuncionario();">
									<option value = "">Selecione o Funcionário</option>
				';
			
				$footer = '</select>';
				
				echo utf8_encode($header.$html.$footer);
			}
		}
		
		if($idloja && $acao == 'cmb_loja2'){
		
			$sql = "SELECT IdFuncionario, funcionario.Nome 
					FROM funcionario, estabelecimento 
					WHERE estabelecimento.IdEstabelecimento = funcionario.IdEstabelecimento
					AND IdLoja = $idloja";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while ($rs->next()) {
		
				$html .= '<option value = "'.$rs->get(0).'">'.trim($rs->get(1)).'</option>';
				
			}
			$rs->close();
			
			if($html){
			
			
				$header = '
								<select name = "idfuncionario_aux2" id = "idfuncionario_aux2" onchange="submeterFuncionario2();">
									<option value = "">Selecione o Funcionário</option>
				';
			
				$footer = '</select>';
				
				echo utf8_encode($header.$html.$footer);
			}

		}

	
?>
