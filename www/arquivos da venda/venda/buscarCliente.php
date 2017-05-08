<?
		session_start();
		$funcionario = $_SESSION['funcionario'];
		
		include_once ("../../sistema/includes/connection.inc");
		
		// Abertura da conexï¿½o.
		$con = new Connection;
		$con->open();

		$idcliente = $_POST['idcliente'];
		$acao = $_POST['acao'];
		
		if($idcliente && $acao == 'busca_cliente') {

			// Inserção dos dados no banco.
			$sql = "SELECT IdCliente, IdTipoBloqueio, IdTipoCliente, IdFuncionario, IdLoja, Nome, Fantasia, Naturalidade, DataNascimento, Sexo, OrgaoEmissor, CPF, 
					RG, CNPJ, IE, Endereco, Bairro, Cidade, Estado, Cep, Telefone1, Telefone2, TelConhecido1, TelConhecido2, Conhecido1, Conhecido2, IdProfissao, 
					Empresa, Tempo, Renda, TelefoneEmp, EnderecoEmp, BairroEmp, CidadeEmp, CepEmp, EstadoEmp, EnderecoCob, BairroCob, CidadeCob, EstadoCob, CepCob, 
					Email, TipoCliente, Observacao, Imagem, DataCadastro, DataAlteracao, Ativo, CartaoPreferencial, TelConhecidoc1, TelConhecidoc2, Conhecidoc1, 
					Conhecidoc2, Parentesco1, Parentesco2, DataEmissao, Autorizado, EstadoCivil, Limite, Complemento, NumeroAnel, NumeroAlianca, Categoria,
					SaldoVale, Pai, Mae, Numero
					FROM cliente
					WHERE IdCliente = '$idcliente'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()){						
				$idcategoriacliente = $rs->get("IdTipoCliente");
				$tipocliente = $rs->get("TipoCliente");
				$cpf = $rs->get("CPF");
				$rg = $rs->get("RG");
				$orgaoemissor = $rs->get("OrgaoEmissor");
				$datademissao = substr($rs->get("DataEmissao"),8,2);
				$datamemissao = substr($rs->get("DataEmissao"),5,2);
				$dataaemissao = substr($rs->get("DataEmissao"),0,4);
				$cnpj = $rs->get("CNPJ");
				$ie = $rs->get("IE");
				$fantasia = utf8_encode($rs->get("Fantasia"));
				$nome = utf8_encode($rs->get("Nome"));
				$datadnasc = substr($rs->get("DataNascimento"),8,2);
				$datamnasc = substr($rs->get("DataNascimento"),5,2);
				$dataanasc = substr($rs->get("DataNascimento"),0,4);
				$natural = utf8_encode($rs->get("Naturalidade"));
				$sexo = $rs->get("Sexo");
				$pai = utf8_encode($rs->get("Pai"));
				$mae = utf8_encode($rs->get("Mae"));
				$idprofissao = $rs->get("IdProfissao");
				$estadocivil = $rs->get("EstadoCivil");
				$autorizado = $rs->get("Autorizado");
				$endereco = utf8_encode($rs->get("Endereco"));
				$numero = $rs->get("Numero");
				$bairro = utf8_encode($rs->get("Bairro"));
				$complemento = utf8_encode($rs->get("Complemento"));
				$cidade = utf8_encode($rs->get("Cidade"));
				$cep = $rs->get("Cep");
				$estado = $rs->get("Estado");
				$telefone1 = $rs->get("Telefone1");
				$telefone2 = $rs->get("Telefone2");
				$email = utf8_encode($rs->get("Email"));
				$cartaopreferencial = $rs->get("CartaoPreferencial");
				$limite = $rs->get("Limite");
				$observacao = utf8_encode($rs->get("Observacao"));
				$imagem = $rs->get("Imagem");
				$conhecido1 = utf8_encode($rs->get("Conhecido1"));
				$parentesco1 = utf8_encode($rs->get("Parentesco1"));
				$telconhecido1 = $rs->get("TelConhecido1");
				$conhecido2 = utf8_encode($rs->get("Conhecido2"));
				$parentesco2 = utf8_encode($rs->get("Parentesco2"));
				$telconhecido2 = $rs->get("TelConhecido1");
				$enderecocob = utf8_encode($rs->get("EnderecoCob"));
				$bairrocob = utf8_encode($rs->get("BairroCob"));
				$cidadecob = utf8_encode($rs->get("CidadeCob"));
				$estadocob = $rs->get("EstadoCob");
				$cepcob = $rs->get("CepCob");
				$empresa = utf8_encode($rs->get("Empresa"));
				$telempresa = $rs->get("TelefoneEmp");
				$tempo = $rs->get("Tempo");
				$renda = $rs->get("Renda");
				$cepemp = $rs->get("CepEmp");
				$enderecoemp = utf8_encode($rs->get("EnderecoEmp"));
				$bairroemp = utf8_encode($rs->get("BairroEmp"));
				$estadoemp = $rs->get("EstadoEmp");
				$cidadeemp = utf8_encode($rs->get("CidadeEmp"));
				$conhecidoc1 = utf8_encode($rs->get("Conhecidoc1"));
				$telconhecidoc1 = $rs->get("TelConhecidoc1");
				$conhecidoc2 = utf8_encode($rs->get("Conhecidoc2"));
				$telconhecidoc2 = $rs->get("TelConhecidoc2");
				$numeroalianca = $rs->get("NumeroAnel");
				$numeroanel = $rs->get("NumeroAlianca");
				$idtipobloqueio = $rs->get("IdTipoBloqueio");
				$datacadastro = implode("/", array_reverse(explode("-",$rs->get("DataCadastro"))));
				$saldovale = $rs->get("SaldoVale");
				$idloja = $rs->get("IdLoja");
			}   
			$rs->close();
			
			// Inserção dos dados no banco.
			$sql = "SELECT Nome, DataNascimento, DataCasamento, Cpf, Telefone, Renda
					FROM conjuge
					WHERE IdCliente = '$idcliente'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()){	
			
				$nomeconjuge = utf8_encode($rs->get("Nome"));
				$datadcasamento = substr($rs->get("DataCasamento"),8,2);
				$datamcasamento = substr($rs->get("DataCasamento"),5,2);
				$dataacasamento = substr($rs->get("DataCasamento"),0,4);
				$cpfconj = $rs->get("Cpf");
				$datadnascconj = substr($rs->get("DataNascimento"),8,2);
				$datamnascconj = substr($rs->get("DataNascimento"),5,2);
				$dataanascconj = substr($rs->get("DataNascimento"),0,4);
				$telefoneconj = $rs->get("Telefone");
				$rendaconj = $rs->get("Renda");
			}
			$rs->close();
			
			$sql = "SELECT NumeroBanco, NomeBanco, ContaBanco, AgenciaBanco, CtaDesde, EnderecoBanco, FoneBanco, GerenteBanco		
					FROM referencia_banco_cliente 
					WHERE IdCliente = '$idcliente'";		
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()){	
				$nomebanco = utf8_encode($rs->get("NomeBanco"));
				$numerobanco = $rs->get("NumeroBanco");
				$agenciabanco = $rs->get("AgenciaBanco");
				$contabanco = $rs->get("ContaBanco");
				$enderecobanco = utf8_encode($rs->get("EnderecoBanco"));
				$telefonebanco = $rs->get("FoneBanco");
				$gerente = utf8_encode($rs->get("GerenteBanco"));
				$contadesde = $rs->get("CtaDesde");
			}
			$rs->close();
			
	
			$sql = "SELECT IdDependente, Nome, DataNascimento
					FROM dependente
					WHERE IdCliente = $idcliente AND Ativo = '1'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while($rs->next()){	
				$i++;
				
				if($i % 2)				
					$html_dep .= "<tr bgcolor=\"#EEEEEE\">";
				else
					$html_dep .= "<tr>";
				
						$html_dep .= "
								<td width=\"20\" align=\"center\">$i</td>
								<td width=\"200\" align=\"left\">
									".$rs->get("Nome")."
								</td>
								<td width=\"200\" align=\"center\">
									".substr($rs->get("DataNascimento"),8,2).'/'.substr($rs->get("DataNascimento"),5,2).'/'.substr($rs->get("DataNascimento"),0,4)."
								</td>
								<td width=\"20\" align=\"center\">
									<a href=\"#\" onclick=\"submeterExclusaoDependente('".$rs->get("IdDependente")."');\" title=\"Excluir este dependente da lista.\">
									<img src=\"../../sistema/images/lixo.gif\" width=\"15\" border=\"0\">
									</a>
								</td>
							</tr>";
			}
			$rs->close();
			
			if($html_dep){
				$header_dep = "
						<table width=\"440\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<td>
									<table width=\"440\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\">
										<tr bgcolor=\"#77777\">
											<td width=\"440\" height=\"25\" align=\"center\" colspan=\"8\">
												<font class=\"titulo_tabela\">
													Relação de Dependente
												</font>
											</td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\">.</td>
											<td width=\"200\" align=\"center\"><b>Dependente</b></td>
											<td width=\"200\" align=\"center\"><b>Data Nascimento</b></td>
											<td width=\"20\" align=\"center\"><b>.</b></td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"4\"><hr /></td>
										</tr>
							";
			
				$footer_dep = "
								
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"4\"><hr /></td>
										</tr>
							</table>
						</td>
					</tr>
				</table>";
				
				$dependente = utf8_encode($header_dep.$html_dep.$footer_dep);
			}
			
			// Inserindo a lista de produtos no ban	co, tabela 'venda_produto'.
			$sql = "SELECT IdClienteAcaoCliente, Nome
					FROM cliente_acaocliente, acao_cliente
					WHERE acao_cliente.IdAcaoCliente = cliente_acaocliente.IdAcaoCliente AND IdCliente = $idcliente";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while($rs->next()){	
				$l++;

				if($l % 2)				
					$html_acao .= "<tr bgcolor=\"#EEEEEE\">";
				else
					$html_acao .= "<tr>";
					
						$html_acao .= "
								<td width=\"20\" align=\"center\">$l</td>
								<td width=\"350\" align=\"left\">
									".$rs->get("Nome")."
								</td>
								<td width=\"20\" align=\"center\">
									<a href=\"#\" onclick=\"submeterExclusaoAcaoInclui('".$rs->get("IdClienteAcaoCliente")."');\" title=\"Excluir esta ação da lista.\">
									<img src=\"../../sistema/images/lixo.gif\" width=\"15\" border=\"0\">
									</a>
								</td>
							</tr>";
			}
			$rs->close();
			
			if($html_acao){
				$header_acao = "
						<table width=\"390\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<td>
									<table width=\"390\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\">
										<tr bgcolor=\"#77777\">
											<td width=\"390\" height=\"25\" align=\"center\" colspan=\"8\">
												<font class=\"titulo_tabela\">
													Relação de Ações do Cliente
												</font>
											</td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\">.</td>
											<td width=\"350\" align=\"center\"><b>Ação Cliente</b></td>
											<td width=\"20\" align=\"center\"><b>.</b></td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"3\"><hr /></td>
										</tr>
							";
			
				$footer_acao = "
								
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"3\"><hr /></td>
										</tr>
							</table>
						</td>
					</tr>
				</table>";
				
				$acaocliente = utf8_encode($header_acao.$html_acao.$footer_acao);
			}
			
			// Inserindo a lista de produtos no ban	co, tabela 'venda_produto'.
			$sql = "SELECT IdClientePerfil, Nome
					FROM cliente_perfil, perfil
					WHERE perfil.IdPerfil = cliente_perfil.IdPerfil AND IdCliente = $idcliente";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			while($rs->next()){	
				$j++;

				if($j % 2)				
					$html_perfil .= "<tr bgcolor=\"#EEEEEE\">";
				else
					$html_perfil .= "<tr>";
					
						$html_perfil .= "
								<td width=\"20\" align=\"center\">$j</td>
								<td width=\"350\" align=\"left\">
									".$rs->get("Nome")."
								</td>
								<td width=\"20\" align=\"center\">
									<a href=\"#\" onclick=\"submeterExclusaoPerfil('".$rs->get("IdClientePerfil")."');\" title=\"Excluir este perfil da lista.\">
									<img src=\"../../sistema/images/lixo.gif\" width=\"15\" border=\"0\">
									</a>
								</td>
							</tr>";
			}
			$rs->close();
			
			if($html_perfil){
				$header_perfil = "
						<table width=\"390\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<td>
									<table width=\"390\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\">
										<tr bgcolor=\"#77777\">
											<td width=\"390\" height=\"25\" align=\"center\" colspan=\"8\">
												<font class=\"titulo_tabela\">
													Relação de Perfil do Cliente
												</font>
											</td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\">.</td>
											<td width=\"350\" align=\"center\"><b>Perfil Cliente</b></td>
											<td width=\"20\" align=\"center\"><b>.</b></td>
										</tr>
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"3\"><hr /></td>
										</tr>
							";
			
				$footer_perfil = "
								
										<tr height=\"20\">
											<td width=\"20\" align=\"center\" colspan=\"3\"><hr /></td>
										</tr>
							</table>
						</td>
					</tr>
				</table>";
				
				$perfilcliente = utf8_encode($header_perfil.$html_perfil.$footer_perfil);
			}
			
			// Ultima Compra
			$sql = "SELECT MAX(IdVenda) FROM venda WHERE IdCliente = '$idcliente'";
            //echo $sql;
            $rs = $con->executeQuery($sql);
			if($rs->next())
			   $idvenda = $rs->get(0);
            $rs->close();
            
            $sql = "SELECT Data FROM venda WHERE IdVenda = '$idvenda'";
			$rs = $con->executeQuery($sql);
            if($rs->next())
               $ultimacompra = substr($rs->get(0),8,2).'/'.substr($rs->get(0),5,2).'/'.substr($rs->get(0),0,4);
            $rs->close();
			
			echo $idcategoriacliente.'|'.
				$tipocliente.'|'.
				$cpf.'|'.
				$rg.'|'.
				$orgaoemissor.'|'.
				$datademissao.'|'.
				$datamemissao.'|'.
				$dataaemissao.'|'.
				$cnpj.'|'.
				$ie.'|'.
				$fantasia.'|'.
				$nome.'|'.
				$datadnasc.'|'.
				$datamnasc.'|'.
				$dataanasc.'|'.
				$natural.'|'.
				$sexo.'|'.
				$pai.'|'.
				$mae.'|'.
				$idprofissao.'|'.
				$estadocivil.'|'.
				$autorizado.'|'.
				$endereco.'|'.
				$numero.'|'.
				$bairro.'|'.
				$complemento.'|'.
				$cidade.'|'.
				$cep.'|'.
				$estado.'|'.
				$telefone1.'|'.
				$telefone2.'|'.
				$email.'|'.
				$cartaopreferencial.'|'.
				$limite.'|'.
				$observacao.'|'.
				$imagem.'|'.
				$conhecido1.'|'.
				$parentesco1.'|'.
				$telconhecido1.'|'.
				$conhecido2.'|'.
				$parentesco2.'|'.
				$telconhecido2.'|'.
				$enderecocob.'|'.
				$bairrocob.'|'.
				$cidadecob.'|'.
				$estadocob.'|'.
				$cepcob.'|'.
				$empresa.'|'.
				$telempresa.'|'.
				$tempo.'|'.
				$renda.'|'.
				$cepemp.'|'.
				$enderecoemp.'|'.
				$bairroemp.'|'.
				$estadoemp.'|'.
				$cidadeemp.'|'.
				$conhecidoc1.'|'.
				$telconhecidoc1.'|'.
				$conhecidoc2.'|'.
				$telconhecidoc2.'|'.
				$numeroalianca.'|'.
				$numeroanel.'|'.
				$dependente.'|'.
				$acaocliente.'|'.
				$idtipobloqueio.'|'.
				$nomeconjuge.'|'.
				$datadcasamento.'|'.
				$datamcasamento.'|'.
				$dataacasamento.'|'.
				$cpfconj.'|'.
				$datadnascconj.'|'.
				$datamnascconj.'|'.
				$dataanascconj.'|'.
				$telefoneconj.'|'.
				$rendaconj.'|'.
				$nomebanco.'|'.
				$numerobanco.'|'.
				$agenciabanco.'|'.
				$contabanco.'|'.
				$enderecobanco.'|'.
				$telefonebanco.'|'.
				$gerente.'|'.
				$contadesde.'|'.
				$perfilcliente.'|'.
				$datacadastro.'|'.
				$saldovale.'|'.
				$idloja.'|'.
				$ultimacompra;
		}
  
	
		
				
	
?>