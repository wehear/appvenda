<?
		session_start();
		$funcionario = $_SESSION[funcionario];
			
		include_once ("../../sistema/includes/connection.inc");
		
		$idconferencia = $_POST['idconferencia'];
		$idvenda = $_POST['idvenda'];
		$idcliente = $_POST['idcliente'];
  		$fator = $_POST['fator'];
		$isSenha = $_POST['isSenha'];
		$venda = $_POST['venda'];
	
		// Abertura da conexão.
		$con = new Connection;
		$con->open(); 
		 	
		$data = date("Y-m-d");
		$hora = date("H:i:s"); 
		 	
		$sql = "SELECT IdUnidadeVenda, Sigla FROM unidade_venda";
		$sql = "$sql WHERE Ativo = 1";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while($rs->next ()) {
			$idunidade_aux = $rs->get(0);
			$arr_unidade[$idunidade_aux]['idunidadevenda'] = $rs->get(0);
			$arr_unidade[$idunidade_aux]['siglaunidadevenda'] = $rs->get(1);
			$arr_unidade[$idunidade_aux]['somaitenstotal'] = "";
			$arr_unidade[$idunidade_aux]['somaitenstotaldev'] = "";
		}
		$rs->close();
				

		// Seleção das moedas vendas
		$sql = "SELECT indexadores.IdIndexador, Sigla FROM indexadores, indexadores_loja WHERE indexadores_loja.IdIndexador = indexadores.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]' AND indexadores.Ativo = 1";
		$rs = $con->executeQuery ($sql);
		//echo $sql;
		while($rs->next ()) {
			$idindexadorv_aux = $rs->get(0);
			$arr_indexadorv[$idindexadorv_aux]['idindexadorv'] = $rs->get(0);
			$arr_indexadorv[$idindexadorv_aux]['siglaindexadorv'] = $rs->get(1);
			$arr_indexadorv[$idindexadorv_aux]['totalindexadorv'] = "";
		}
		$rs->close();
							
		$sql = "SELECT IdNotaConferenciaVenda, notaconferenciavenda_produto.IdNotaConferenciaVendaProduto, notaconferenciavenda_produto.IdProduto, notaconferenciavenda_produto.IdGrade, CodigoBarra, Descricao,
				Quantidade, PrecoCusto, PrecoVenda, PrecoReal, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla AS SiglaUnidade, 
				indexadores.Sigla AS SiglaMoeda, produto.IdIndexador 
				FROM produto, grade, notaconferenciavenda_produto, unidade_venda, indexadores
				WHERE IdNotaConferenciaVenda = $idconferencia AND grade.IdProduto = produto.IdProduto AND notaconferenciavenda_produto.IdGrade = grade.IdGrade
				AND produto.IdProduto = notaconferenciavenda_produto.IdProduto AND produto.IdUnidadeVenda = unidade_venda.IdUnidadeVenda
				AND produto.IdIndexador = indexadores.IdIndexador 
				ORDER BY notaconferenciavenda_produto.IdNotaConferenciaVendaProduto DESC";
						
		if($venda)
			$sql = "$sql LIMIT 10";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while ($rs->next ()) {
			
			$idunidade_aux = $rs->get("IdUnidadeVenda");
			$idindexadorv_aux = $rs->get("IdIndexador");
			


			//Seleção do tipo de Index
			$sql = "SELECT  Margem FROM indexadores_cliente 
					WHERE IdIndexador = ".$rs->get("IdIndexador")."
					AND IdCliente = $idcliente 
					AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
			//echo $sql;
			$rs1 = $con->executeQuery ($sql);
			if($rs1->next()){
				$margemindex = $rs1->get("Margem");
			}else{
				//Seleção do tipo de Index
				$sql = "SELECT  Margem FROM indexadores, indexadores_loja 
						WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")." 
						AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
				//echo $sql;
				$rs2 = $con->executeQuery ($sql);
				if($rs2->next()){
					$margemindex = $rs2->get("Margem");
				}
				$rs2->close();
			}
			$rs1->close();
				   
			if($rs->get("IdUnidadeVenda") == 2){
				$precoetiqueta = $rs->get("PrecoReal") * $margemindex;
			}
			else{
				$precoetiqueta = $rs->get("PrecoReal");
			}
			
			$i++;	
				
			if($i % 2)
				$html .= "<tr bgcolor=\"#EEEEEE\" $title>";
			else
				$html .= "<tr $title>";
				
			$html .= '
					<td width="20" align="center">'.$i.'</td>
					<td width="70" align="center">
						'.$rs->get("CodigoBarra").'													
					</td>
					<td width="340" align="left">												
						'.$rs->get("Descricao").'	
					</td>
					<td width="70" align="center">
						'.sprintf("%1.2f", $rs->get("Quantidade")). " " . $rs->get("SiglaUnidade").'
					</td>
					<td width="80" align="center">
						'.sprintf("%1.2f", $rs->get("PrecoReal")). " " . $rs->get("SiglaMoeda").'
					</td>
					<td width="100" align="center">
						'.sprintf("%1.2f", $precoetiqueta).'
					</td>
					<td width="100" align="center">
						'.sprintf("%1.2f", $rs->get("PrecoVenda") * $fator).'
					</td>
					<td width="100" align="center">
						'.sprintf("%1.2f", $rs->get("PrecoVenda") * $fator * $rs->get("Quantidade")).'
					</td>
					<td width="20" align="center">
						<a href="#" onclick="submeterExclusaoProduto(\''.$rs->get("IdNotaConferenciaVendaProduto").'\');" title="Excluir este produto da lista.">
							<img src="../../sistema/images/lixo.gif" width="15" border="0">
						</a>
					</td>		
				</tr>';
			
					
			$somavalortotal += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
					
			if($rs->get("Quantidade") < 0){
				$somavalordev += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
				$arr_unidade[$idunidade_aux]['somaitenstotaldev'] += $rs->get("Quantidade") * (-1);
			}
						
			if($rs->get("Quantidade") > 0){
				$somavalor += sprintf("%1.2f", $rs->get("PrecoVenda") * $rs->get("Quantidade"));
				$arr_unidade[$idunidade_aux]['somaitenstotal'] += $rs->get("Quantidade");
			}
	
			if($somavalor > 0){	
				$somaporcentagem = ($somavalordev * 100) / $somavalor;
			}
			
			$arr_indexadorv[$idindexadorv_aux]['totalindexadorv'] += ($rs->get("PrecoReal")* $rs->get("Quantidade"));	 
		}	
		$rs->close();	
		
		if($html){
			
				$header = '
						<table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
		
										<table width="800" align="center" cellpadding="2" cellspacing="2">
											<tr bgcolor="#77777">
												<td width="800" height="25" align="center" colspan="9">
													<font class="titulo_tabela">
														Relação de Produtos
													</font>
												</td>
											</tr>
											<tr height="20">
												<td width="20" align="center">.</td>
												<td width="70" align="center"><b>Código</b></td>
												<td width="340" align="center"><b>Descrição</b></td>
                                                <td width="70" align="center"><b>Qtde</b></td>
                                                <td width="80" align="center"><b>Valor/Peso</b></td>
												<td width="80" align="center"><b>Etiqueta</b></td>
												<td width="100" align="center"><b>Unitário R$</b></td>
												<td width="100" align="center"><b>Total</b></td>
												<td width="20" align="center"><b>.</b></td>
											</tr>';
				
				$footer = '	
								</table>
										
								<table width="800" align="center" cellpadding="2" cellspacing="2">
									<tr height="25" bgcolor="#777777">
										<td width = "800" align = "center">
											<font class = "titulo_tabela">
												<b>Total Ítens Saída</b>&nbsp;&nbsp;';

										foreach($arr_unidade AS $value) {
											if($value['somaitenstotal'] != 0){
												$footer .= sprintf("%1.2f", $value['somaitenstotal']) . " " . $value['siglaunidadevenda'] . "    ";
											}
										}
										
							$footer .= '
											</font>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<font class = "titulo_tabela">
												<b>Total Ítens Devolvidos</b>&nbsp;&nbsp;';

                                        foreach($arr_unidade AS $value) {
											if($value['somaitenstotaldev'] != 0){
                                                $footer .= sprintf("%1.2f", $value['somaitenstotaldev']) . " " . $value['siglaunidadevenda'] . "    ";
											}
										}

							$footer .= '
											</font>
										</td>
									</tr>
									<tr height="25" bgcolor="#777777">
										<td width = "800" align = "center">
											<font class = "titulo_tabela">
												<b>Total </b>&nbsp;&nbsp;';

                                            foreach($arr_indexadorv AS $value) {
												if($value['totalindexadorv'] != 0){
                                                    $footer .= sprintf("%1.2f", $value['totalindexadorv']) . " " . $value['siglaindexadorv'] . "    ";
												}
											}
		
							$footer .= '
											</font>
										</td>
									</tr>
								</table>
								<table>
									<tr height="25" bgcolor="#777777">
										<td width = "270" align = "center">
											<font class = "titulo_tabela">
												<b>Valor Dev.</b>&nbsp;&nbsp;
													'.sprintf("%1.2f", $somavalordev * $fator).'&nbsp;&nbsp;.
													'.sprintf("%1.2f", $somaporcentagem).'%
											</font>
										</td>
										<td width = "260" align = "center">
											<font class = "titulo_tabela">
												<b>Valor </b>&nbsp;&nbsp;
													'.sprintf("%1.2f", $somavalor * $fator).'
											</font>
										</td>
										<td width = "270" align = "center">
											<font class = "titulo_tabela">
												<b>Valor Total</b>&nbsp;&nbsp;
													'.sprintf("%1.2f", $somavalortotal * $fator).'
											</font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
							
					<br>';
					
				$button	.= '<input type="button" name="submeteListaProduto" id="submeteListaProduto" value="Listar Produtos" onclick="submeterListaProdutos();">';
				$button	.= '<input type="button" name="submeteCancelamento" id="submeteCancelamento" value="Cancelar Conferência" onclick="submeterCancelamento();">';
				
				$sql = "SELECT IdVendaProduto
						FROM venda_produto
						WHERE IdVenda = $idvenda
						AND (QtdConferida != Quantidade OR QtdConferida IS NULL)";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if ($rs->next ()) {
					$isQuantidadeErro = true;
				}
				$rs->close();
			
				
				if(!$isQuantidadeErro){

					$button	.= '<input type="button" name="submeteFinalizacaoVenda" id="submeteFinalizacaoVenda" value="Finalizar Conferência" onclick="submeterFinalizacaoVenda();">';

				}else{

					$button	.= '<input type="button" name="submeteFinalizacaoVenda" id="submeteFinalizacaoVenda" value="Finalizar Conferência" onclick="submeterFinalizacaoVendaErro();">';
				}
				
		}
			
		echo utf8_encode($header.$html.$footer.$button);

?>
