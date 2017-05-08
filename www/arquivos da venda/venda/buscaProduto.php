<?
    session_start();
    $funcionario = $_SESSION[funcionario];
	
		include_once ("../../sistema/includes/connection.inc");
		
		// Abertura da conexão.
		$con = new Connection;
		$con->open();
		
		// Acrescenta zeros no incio do codigo.
		function validaCodigoBarra($codigo, $tamanho) {
            for($i = 1; $i <= $tamanho - strlen($codigo); $i++)
                $retorno .= 0;
            $retorno .= $codigo;
			return($retorno);
		}

		$codigo_fornecedor = $_POST['codigo_fornecedor'];
		$idfabricante = $_POST['idfabricante'];
		$idsecao = $_POST['idsecao'];
		$codigo_produto = $_POST['codigo_produto'];
		$codigobarra = $_POST['codigobarra'];
		$idprodutocombo = $_POST['idprodutocombo'];
		$idmarca = $_POST['idmarca'];
		$idfamilia = $_POST['idfamilia'];
		$referenciaconsulta = $_POST['referenciaconsulta'];
		$descricaoconsulta = $_POST['descricaoconsulta'];
		$idproduto = $_POST['idproduto'];
		$idgrade = $_POST['idgrade'];
		$acao = $_POST['acao'];
		$tela = trim($_POST['tela']);

		
		if(($codigobarra || $idprodutocombo) && $acao == "busca_codigobarra") {
		
			if($idprodutocombo)
                $codigobarra = "";
            else
                $idprodutocombo = "";

            if($codigobarra && strlen($codigobarra) < 7)
                $codigobarra = validaCodigoBarra($codigobarra, 7);
                
                
            // Seleção dos dados do produto.
           	$sql = "SELECT produto.IdProduto, grade.IdGrade, produto.Descricao, estoque.PrecoCusto, estoque.PrecoVenda, grade.CodigoBarra, 
					produto.IdMoeda, unidade_venda.Sigla AS SiglaUnidade, indexadores.Sigla AS SiglaMoeda, estoque.IdLoja, 
					produto.IdSecao, produto.CodigoFornecedor, produto.IdIndexador, estoque.Peso, estoque.Quantidade, produto.Observacao, 
					produto.Imagem					
					FROM produto, unidade_venda, grade, estoque, indexadores
					WHERE produto.IdProduto = grade.IdProduto AND produto.IdProduto = estoque.IdProduto 
					AND estoque.IdLoja = '$funcionario[idloja]' AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda 
					AND produto.Ativo = 1 AND produto.IdIndexador = indexadores.IdIndexador";
            if($idsecao)
				$sql = "$sql AND produto.IdSecao = '$idsecao'";
            if($idmarca)
				$sql = "$sql AND produto.IdMarca = '$idmarca'";
            if($idfamilia)
				$sql = "$sql AND produto.IdFamilia = '$idfamilia'";
            if($idprodutocombo)
				$sql = "$sql AND produto.IdProduto = '$idprodutocombo'";
			if($codigobarra)
				$sql = "$sql AND CodigoBarra = '$codigobarra'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next ()) {
                $idproduto = $rs->get("IdProduto");
                $idgrade = $rs->get("IdGrade");
                $descricao = $rs->get("Descricao");
				$descricaoconsulta = $rs->get("Descricao");
				$precocusto = $rs->get("PrecoCusto");
				$precovenda = $rs->get("PrecoVenda");
				$codigobarra = $rs->get("CodigoBarra");
				$idmoeda = $rs->get("IdMoeda");
				$siglaunidade = $rs->get("SiglaUnidade");
				$siglamoeda = $rs->get("SiglaMoeda");
				$codigofornecedor = $rs->get("CodigoFornecedor");
				$idindexador = $rs->get("IdIndexador");
				$peso = $rs->get("Peso");
				$estoqueatual = $rs->get("Quantidade");
				$observacao = $rs->get("Observacao");
				$imagem = "<img src='../../images/".$rs->get("Imagem")."' id='previsualizar' width=\"360\" height=\"200\">";
            }		
			$rs->close();	
			
			echo $idproduto."|".$idgrade."|".utf8_encode($descricao)."|".utf8_encode($descricaoconsulta)."|".$precocusto."|".$precovenda."|".$codigobarra."|".$idmoeda."|".$siglaunidade."|".$siglamoeda."|".utf8_encode($codigofornecedor)."|".$idindexador."|".$peso."|".$estoqueatual."|".utf8_encode($observacao)."|".$imagem;
		
		}
		if($codigo_fornecedor && $idsecao && $acao == "busca_codigo_fornecedor") {

		
						$html='<br>
								<select name="idprodutobusca" id="idprodutobusca" onchange="submeterBuscaSelecao();">
						<option value="">Selecione o Produto</option>';
						$sql = "SELECT CodigoFornecedor, produto.IdProduto, Descricao, PrecoCusto, SUM(Quantidade) AS Quantidade, DescricaoEtiqueta 
								FROM produto, estoque 
								WHERE produto.IdProduto = estoque.IdProduto AND IdFabricante = '$idfabricante' 
								AND IdSecao = '$idsecao' AND CodigoFornecedor LIKE '%$codigo_fornecedor%' 
								AND produto.Ativo = 1 GROUP BY produto.IdProduto";	
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						while($rs->next()) {													
							$html .= '<option value="'.$rs->get("IdProduto").'">'.$rs->get("IdProduto"). " - " .$rs->get("CodigoFornecedor")." - ".
							$rs->get("Descricao") . " - " . $rs->get("DescricaoEtiqueta") . " - " . $rs->get("PrecoCusto") . " - " . "Qtde ". $rs->get("Quantidade").'</option>';
						}
						$rs->close();
						
						$html .= '</select>&nbsp;&nbsp;';	

						echo $html;		
			
		}
		
		if($referenciaconsulta && $acao == "busca_referencia") {
		
			$html = '
				
					<br/>
					
			        <select id="idprodutoref" onchange="submeterBuscaProduto();">
						<option value="">Selecione o Produto</option>';
                           
							$sql = "SELECT IdProduto, CodigoFornecedor FROM produto WHERE  CodigoFornecedor LIKE '%$referenciaconsulta%' AND Ativo = 1";
							$sql = "$sql ORDER BY CodigoFornecedor";
							$rs = $con->executeQuery ($sql);
							while($rs->next()) {
                              
                                $html .= '<option value="'.$rs->get(0).'">'.$rs->get(1).'</option>';
                         
							}
							$rs->close();
                        
                    $html .= '</select>&nbsp;&nbsp;
							<input type="button" id="novabusca" value="Nova Busca" onclick="novaBusca();">';
			echo utf8_encode($html);	
			
						
		}
		
		if($descricaoconsulta && $acao == "busca_descricao") {
		
			$descricaoconsulta = strtoupper($descricaoconsulta);
	
			$html = '
				
				<br/>
					
				<select id="idprodutoref" onchange="submeterBuscaProduto();">
					<option value="">Selecione o Produto</option>';
                           
					$sql = "SELECT IdProduto, Descricao FROM produto WHERE Descricao LIKE '%$descricaoconsulta%' AND Ativo = 1";
					$sql = "$sql ORDER BY Descricao";
					$rs = $con->executeQuery ($sql);
					while($rs->next()) {
                              
						$html .= '<option value="'.$rs->get(0).'">'.$rs->get(1).'</option>';
                         
					}
					$rs->close();
                        
			$html .= '</select>&nbsp;&nbsp;
					<input type="button" id="novabusca" value="Nova Busca" onclick="novaBusca();">';
					
			echo utf8_encode($html);	
									
		}
		
		if($idproduto && $acao == "busca_combo_grade") {
			
			$sql = "SELECT Descricao FROM produto WHERE IdProduto = '$idproduto'";
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
                $descricao = $rs->get(0);
                    
			}
			$rs->close();
							
			$html = '
					<br />
					<br />
					
					<b>Código Barra</b>&nbsp;&nbsp;
                
					<select id="idgrade_cmb" onchange="submeterSelecaoProduto();">
						<option value="">Selecione o Produto</option>';
                       
							$sql = "SELECT IdGrade, CodigoBarra FROM grade WHERE IdProduto = '$idproduto' AND grade.Ativo = 1";
							$rs = $con->executeQuery ($sql);
							while($rs->next()) {
                         
								$html .= '<option value="'.$rs->get(0).'">Código Barra: (' . $rs->get(1) .")</option>";
                    
							}
							$rs->close();
                        
                    $html .= '</select>&nbsp;&nbsp;
                      </td>';
			echo utf8_encode($html).'|'.$descricao;	
			
						
		}
		
		if(($idgrade || $codigobarra) && $acao == "busca_combo_grade") {  
		
			if($tela && $tela != ""){
			
				$sql = "SELECT $tela
						FROM libera_estoque_loja
						WHERE IdLoja = '$funcionario[idloja]'"; 
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if($rs->next()) {
					$liberaestoque = $rs->get(0);
				}
				$rs->close();
			}
			
			if($codigobarra && strlen($codigobarra) < 7)
                $codigobarra = validaCodigoBarra($codigobarra, 7);

            // Seleção dos dados do produto.
			$sql = "SELECT produto.IdProduto, produto.Descricao, estoque_grade.Quantidade, estoque.PrecoCusto, estoque.PrecoVenda, 
					grade.CodigoBarra, produto.IdUnidadeVenda, produto.IdMoeda, unidade_venda.Sigla, indexadores.Sigla, estoque_grade.IdLoja, 
					produto.IdSecao, produto.CodigoFornecedor, produto.idindexador, grade.IdGrade, estoque_grade.Peso 
					FROM produto, unidade_venda, estoque, indexadores, grade, estoque_grade 
					WHERE produto.IdProduto = grade.IdProduto AND grade.IdGrade = estoque_grade.IdGrade 
					AND produto.IdIndexador = indexadores.IdIndexador AND estoque_grade.IdLoja = '$funcionario[idloja]' 
					AND produto.IdProduto = estoque.IdProduto AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda 
					AND estoque.IdLoja = '$funcionario[idloja]'";
			if($idprodutocombo)
				$sql = "$sql AND produto.IdProduto = '$idprodutocombo'";
			if($codigobarra)
				$sql = "$sql AND CodigoBarra LIKE '$codigobarra%'";
			if($idgrade)
				$sql = "$sql AND grade.IdGrade = '$idgrade'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
				$idproduto = $rs->get(0);
				$descricao = $rs->get(1);
				$descricaoconsulta = $rs->get(1);
				$quantidadeatual = $rs->get(2);
				$precocusto = $rs->get(3);
				$codigobarra = $rs->get(5);
				$idunidade = $rs->get(6);
				$idmoeda = $rs->get(7);
				$siglaunidade = $rs->get(8);
				$siglamoeda = $rs->get(9);
				$codigofornecedor = $rs->get(12);
				$referenciaconsulta = $rs ->get(12);
				$idindexador = $rs->get(13);
				$idgrade = $rs->get(14);
				//$quantidade = 1;

				if($idunidade == 2){
					$precovenda =  $rs->get(4) * $rs->get(15);
				}
				else{
					$precovenda = $rs->get(4);
				}
			}
			$rs->close();

			if(!$liberaestoque){
			
				if($quantidadeatual < 0.001){
				
					$isERRO = 'Produto não tem no estoque';
					
				}
			
			
			}
			
			echo $idproduto.'|'.
				utf8_encode($descricao).'|'.
				utf8_encode($descricaoconsulta).'|'.
				$quantidadeatual.'|'.
				$precocusto.'|'.
				$codigobarra.'|'.
				$idunidade.'|'.
				$idmoeda.'|'.
				$siglaunidade.'|'.
				$siglamoeda.'|'.
				utf8_encode($codigofornecedor).'|'.
				utf8_encode($referenciaconsulta).'|'.
				$idindexador.'|'.
				$idgrade.'|'.
				$precovenda.'|'.
				utf8_encode($isERRO);
		
		}
		
		if($acao == "busca_grade_recebe_transf") {  
		
			$lojasaida = $_POST['lojasaida'];
			$idnotatransferencia = $_POST['idnotatransferencia'];
			$idprodutocombo = $_POST['idprodutocombo'];
			$codigobarra = $_POST['codigobarra'];
			$idgrade = $_POST['idgrade'];
			
			if($codigobarra && strlen($codigobarra) < 7)
                $codigobarra = validaCodigoBarra($codigobarra, 7);

   	        // Seleção dos dados do produto.
			$sql = "SELECT DISTINCT(produto.IdProduto), produto.Descricao, PrecoCusto, PrecoVenda, grade.CodigoBarra, produto.IdMoeda, unidade_venda.Sigla, indexadores.Sigla, produto.IdSecao, produto.CodigoFornecedor, produto.IdIndexador, produto.IdUnidadeVenda, grade.IdGrade, estoque_grade.Peso FROM produto, unidade_venda, notatransferencia_produto, indexadores, grade, estoque_grade WHERE produto.IdIndexador = indexadores.IdIndexador AND produto.IdProduto = notatransferencia_produto.IdProduto AND unidade_venda.IdUnidadeVenda = produto.IdUnidadeVenda AND produto.IdProduto = grade.IdProduto AND grade.IdGrade = estoque_grade.IdGrade AND estoque_grade.IdLoja = '$lojasaida' AND IdNotaTransferencia = '$idnotatransferencia'";
            if($idprodutocombo)
			$sql = "$sql AND produto.IdProduto = '$idprodutocombo'";
			if($codigobarra)
			$sql = "$sql AND CodigoBarra LIKE '$codigobarra%'";
			if($idgrade)
			$sql = "$sql AND grade.IdGrade = '$idgrade'";
			//echo $sql;
			$rs = $con->executeQuery ($sql);
			if($rs->next()) {
                $idproduto = $rs->get(0);
                $descricao = $rs->get(1);
                $descricaoconsulta = $rs->get(1);
                $precocusto = $rs->get(2);
				$precovenda = $rs->get(3);
				$codigobarra = $rs->get(4);
                $idmoeda = $rs->get(5);
                $siglaunidade = $rs->get(6);
                $siglamoeda = $rs->get(7);
                $codigofornecedor = $rs->get(9);
                $referenciaconsulta = $rs ->get(9);
                $idindexador = $rs->get(10);
                $idunidade = $rs->get(11);
                $idgrade = $rs->get(12);
                $peso = $rs->get(13);

			}
			$rs->close();
			
			echo$idproduto.'|'.
                $descricao.'|'.
                $descricaoconsulta.'|'.
                $precocusto.'|'.
				$precovenda.'|'.
				$codigobarra.'|'.
                $idmoeda.'|'.
                $siglaunidade.'|'.
                $siglamoeda.'|'.
                $codigofornecedor.'|'.
                $referenciaconsulta.'|'.
                $idindexador.'|'.
                $idunidade.'|'.
                $idgrade.'|'.
                $peso;
		
		}
	
?>
