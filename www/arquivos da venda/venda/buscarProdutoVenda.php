<?
	session_start();
	$funcionario = $_SESSION[funcionario];

	include_once ("../../sistema/includes/connection.inc");

	$idvenda = $_POST['idvenda'];
	$idcliente = $_POST['idcliente'];
	$venda = $_POST['venda'];
	$fator = $_POST['fator'];
	$isSenha = $_POST['isSenha'];
	$especial = $_POST['especial'];

	// Abertura da conexão.
	$con = new Connection;
	$con->open(); 

	$data = date("Y-m-d");
	$hora = date("H:i:s"); 

	$sql = "SELECT Pedra FROM parametros_venda";
	$rs = $con->executeQuery ($sql);
	if($rs->next()) {
		$pedra = $rs->get("Pedra");
	}
	$rs->close();

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

	$sql = "SELECT IdVenda, venda_produto.IdVendaProduto, venda_produto.IdProduto, venda_produto.IdGrade, CodigoBarra, Descricao, 
	Quantidade, PrecoCusto, PrecoVenda, PrecoReal, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla AS SiglaUnidade, 
	indexadores.Sigla AS SiglaMoeda, produto.IdIndexador, venda_produto.Fator, venda_produto.ValorPedra, venda_produto.FatorPedra,
	venda_produto.QuantidadePedra, venda_produto.Fator
	FROM produto, grade, venda_produto, unidade_venda, indexadores
	WHERE IdVenda = $idvenda AND grade.IdProduto = produto.IdProduto AND venda_produto.IdGrade = grade.IdGrade 
	AND produto.IdProduto = venda_produto.IdProduto AND produto.IdUnidadeVenda = unidade_venda.IdUnidadeVenda 
	AND produto.IdIndexador = indexadores.IdIndexador 
	ORDER BY venda_produto.IdVendaProduto DESC";
    //echo $sql;
	$rs = $con->executeQuery ($sql);
	while ($rs->next ()) {

		$idunidade_aux = $rs->get("IdUnidadeVenda");
		$idindexadorv_aux = $rs->get("IdIndexador");
		$fator_aux = '';

		if($rs->get("Fator") == 0)
			$fator_aux = $fator;
		else
			$fator_aux = $rs->get("Fator");

		if($especial){
			$somavalortotal += sprintf("%1.2f", (round($rs->get("PrecoReal") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
		}else{
			$somavalortotal += sprintf("%1.2f", (round($rs->get("PrecoVenda") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
		}

		if($rs->get("Quantidade") < 0){
			if($especial){
				$somavalordev += sprintf("%1.2f", (round($rs->get("PrecoReal") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
			}else{
				$somavalordev += sprintf("%1.2f", (round($rs->get("PrecoVenda") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
			}
			$arr_unidade[$idunidade_aux]['somaitenstotaldev'] += $rs->get("Quantidade") * (-1);
		}

		if($rs->get("Quantidade") > 0){
			if($especial){
				$somavalor += sprintf("%1.2f", (round($rs->get("PrecoReal") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
			}else{
				$somavalor += sprintf("%1.2f", (round($rs->get("PrecoVenda") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"));
			}
			$arr_unidade[$idunidade_aux]['somaitenstotal'] += $rs->get("Quantidade");
		}

		if($somavalor == '0' || $somavalor == "")
			$somavalor_aux = 100;
		else
			$somavalor_aux = $somavalor;

		$somaporcentagem = ($somavalordev * 100) / $somavalor_aux;

		$arr_indexadorv[$idindexadorv_aux]['totalindexadorv'] += (round($rs->get("PrecoReal") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade");
	}
	$rs->close();

	$sql = "SELECT IdVenda, venda_produto.IdVendaProduto, venda_produto.IdProduto, venda_produto.IdGrade, CodigoBarra, Descricao, 
	Quantidade, PrecoCusto, PrecoVenda, PrecoReal, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.Sigla AS SiglaUnidade, 
	indexadores.Sigla AS SiglaMoeda, produto.IdIndexador, venda_produto.Fator, produto.Imagem, venda_produto.ValorPedra,
	venda_produto.FatorPedra, venda_produto.QuantidadePedra
	FROM produto, grade, venda_produto, unidade_venda, indexadores
	WHERE IdVenda = $idvenda AND grade.IdProduto = produto.IdProduto AND venda_produto.IdGrade = grade.IdGrade 
	AND produto.IdProduto = venda_produto.IdProduto AND produto.IdUnidadeVenda = unidade_venda.IdUnidadeVenda 
	AND produto.IdIndexador = indexadores.IdIndexador 
	ORDER BY venda_produto.IdVendaProduto DESC";
	if($venda)
		$sql = "$sql LIMIT 10";
	//echo $sql;
	$rs = $con->executeQuery ($sql);
	while ($rs->next ()) {
	
		$fator_aux = '';
		if($rs->get("Fator") == 0)
			$fator_aux = $fator;
		else
			$fator_aux = $rs->get("Fator");

		// Inserindo o produto na tabela 'produto_defeito'.
		$sql = "SELECT Observacao FROM produto_defeito WHERE IdVendaProduto = ".$rs->get("IdVendaProduto");
		//echo $sql;
		$rs1 = $con->executeQuery ($sql);         
		if ($rs1->next ()) {
			$title = "title='Defeito: ".$rs1->get("IdVendaProduto")."'";
		}
		$rs1->close();

		//Seleção do tipo de Index
		$sql = "SELECT  Margem FROM indexadores_cliente WHERE IdIndexador = ".$rs->get("IdIndexador")." AND IdCliente = $idcliente AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
		//echo $sql;
		$rs1 = $con->executeQuery ($sql);
		if($rs1->next()){
			$margemindex = $rs1->get("Margem");
		}else{
      
			$sql = "SELECT  Margem FROM indexadores, indexadores_loja WHERE indexadores.IdIndexador = ".$rs->get("IdIndexador")." AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
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
		'.number_format($precoetiqueta,2,',','.'). " " . $rs->get("SiglaMoeda").'
		</td>';
		if($especial){
			$html .= '
			<td width="100" align="center">
			'.sprintf("%1.2f", $fator_aux).'
			</td>';
			$html .= '
			<td width="100" align="center">
			'.number_format(round($rs->get("PrecoReal") * $rs->get("Fator"), 2),2,',','.').'
			</td>';
		}else{
			$html .= '
			<td width="100" align="center">
			'.number_format(round($rs->get("PrecoVenda") * $rs->get("Fator"), 2),2,',','.').'
			</td>';
		}
		if($pedra && $especial){
			$html .= '

			<td width="70" align="center">
			'.sprintf("%1.2f", $rs->get("QuantidadePedra")).'
			</td>
			<td width="100" align="center">
			'.sprintf("%1.2f", $rs->get("ValorPedra")).'
			</td>
			<td width="80" align="center">
			'.sprintf("%1.3f", $rs->get("FatorPedra")).'
			</td>
			<td width="100" align="center">
			'.number_format((round($rs->get("PrecoReal") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"),2,',','.').'
			</td>';
		}else{
			$html .= '
				<td width="100" align="center">
				'.number_format((round($rs->get("PrecoVenda") * $rs->get("Fator"), 2) + (round($rs->get("ValorPedra") * $rs->get("FatorPedra") * $rs->get("QuantidadePedra"), 2))) * $rs->get("Quantidade"),2,',','.').'
				</td>';
		}
			$html .= '<td width="100" align="center">';
				if($rs->get("Imagem"))
					$html .= '<img src="../../'.$rs->get("Imagem").'" width="150" height="100">';
				else
					$html .= '<img src="../../sistema/images/imagem_indisponivel.png" width="150" height="100">';
					
			if($especial){
			$html .= '
			</td>
			<td width="20" align="center">
			<a href="#" onclick="submeterExclusaoProduto(\''.$rs->get("IdVendaProduto").'\');" title="Excluir este produto da lista.">
			<img src="../../sistema/images/lixo.gif" width="15" border="0">
			</a>
			</td>   
			</tr>';
			}else{
			$html .= '
			</td>
			<td width="20" align="center">
			<a href="#" onclick="submeterExclusaoProduto(\''.$rs->get("IdVendaProduto").'\');" title="Excluir este produto da lista.">
			<img src="../../sistema/images/lixo.gif" width="15" border="0">
			</a>
			</td>   
			</tr>';
			}
	} 
	$rs->close(); 

	if($html){

		$header = '
		<table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td>

		<table width="800" align="center" cellpadding="2" cellspacing="2">
		<tr bgcolor="#77777">
		<td width="800" height="25" align="center" colspan="13">
		<font class="titulo_tabela">
		Relação de Produtos
		</font>
		</td>
		</tr>
		<tr height="20">
		  <td width="20" align="center">.</td>
		  <td width="70" align="center"><b>Código</b></td>
		  <td width="340" align="center"><b>Descrição</b></td>
		  <td width="70" align="center"><b>Qtde</b></td>';
		  if($especial){
			  $header .= '
			  <td width="80" align="center"><b>Valor/Peso</b></td>
			  <td width="80" align="center"><b>Fator</b></td>';
		  }else{
			  $header .= '
			  <td width="80" align="center"><b>Preço Etiqueta</b></td>';
		  }
			$header .= '
		  <td width="100" align="center"><b>Valor</b></td>';
		  if($pedra && $especial){
		  $header .= '
		  <td width="100" align="center"><b>Qtde Pedra</b></td>
		  <td width="100" align="center"><b>Valor Pedra</b></td>
		  <td width="100" align="center"><b>Fator Pedra</b></td>';
		  }
		  $header .= '
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
      $footer .= number_format($value['totalindexadorv'],2,',','.') . " " . $value['siglaindexadorv'] . "    ";
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
  '.sprintf("%1.2f", $somavalordev).'&nbsp;&nbsp; -
  '.number_format($somaporcentagem,2,',','.').'%
  </font>
  </td>
  <td width = "260" align = "center">
  <font class = "titulo_tabela">
  <b>Valor </b>&nbsp;&nbsp;
  '.number_format($somavalor,2,',','.').'
  </font>
  </td>
  <td width = "270" align = "center">
  <font class = "titulo_tabela">
  <b>Valor Total</b>&nbsp;&nbsp;
  '.number_format($somavalortotal,2,',','.').'
  </font>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>

  <br>';
if($venda){
  $button .= '<input type="button" name="submeteListaProduto" id="submeteListaProduto" value="Listar Produtos" onclick="submeterListaProdutos();" class="noprint">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="submeteImpressaoLista" id="submeteImpressaoLista" value="Imprimir" onclick="submeterImpressaoLista();" class="noprint">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="submeteFinalizacaoVenda" value="Finalizar Venda" onclick="submeterFinalizacaoVenda();" class="noprint">';
}

}

echo utf8_encode($header.$html.$footer.$button);

?>
