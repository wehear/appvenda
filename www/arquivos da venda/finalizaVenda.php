<?
session_start();
$funcionario = $_SESSION[funcionario];

include_once ("../../sistema/includes/connection.inc");
require_once ("../../sistema/includes/funcoes.php");
?>
<html>
<head>
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/titulos.css">
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/padroes.css">
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/links.css">
  <title>Finaliza Venda</title>

  <script language="JavaScript">
	
  function submeterDescontoPorcentagem() {
    document.formFinalizaVenda.submeterdescontoporcentagem.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterSenha() {
    document.formFinalizaVenda.submetersenha.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterSenha2() {
    document.formFinalizaVenda.submetersenha2.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterCotacao(linha) {
    document.formFinalizaVenda.calcularcotacao.value = linha;
    document.formFinalizaVenda.recalcular.value = linha;
    document.formFinalizaVenda.submit();
  }

  function submeterDescontoValor() {
    document.formFinalizaVenda.submeterdescontovalor.value = 1;
    document.formFinalizaVenda.submit();
  }
  function limparParcelas() {
    document.formFinalizaVenda.limparparcelas.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterFormaPagamento () {
    if(!document.formFinalizaVenda.idformapagamento.value) {
      alert ("Por favor, selecione a Forma de Pagamento.");
      return false;
    }
    document.formFinalizaVenda.buscarformapagamento.value = 1;
    document.formFinalizaVenda.submit();

  }

  function submeterPrazoPagamento (){
    if(!document.formFinalizaVenda.idtabelavenda.value){
      alert ("Por favor, selecione o Prazo de Pagamento.");
      return false;
    }
    document.formFinalizaVenda.buscarprazopagamento.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterGeracaoParcela() {

    /* if(document.formFinalizaVenda.crediario.value == 1 && document.formFinalizaVenda.idcliente.value == 1){
      alert ("Prazo não permitido para esse cliente.");
      focalizador();
      return false;
    } */
    if(!document.formFinalizaVenda.idtabelavenda.value){
      alert ("Por favor, selecione o Prazo de Pagamento.");
      focalizador();
      return false;
    }
    if(!document.formFinalizaVenda.quantidadeparcela.value || !verificaNumero(document.formFinalizaVenda.quantidadeparcela)) {
      alert ("Por favor, verifique o número de parcelas.");
      focalizador();
      return false;
    }
    if(!verificaNumero(document.formFinalizaVenda.primeiraparcela)) {
      alert ("Por favor, verifique o período da primeira parcela.");
      focalizador();
      return false;
    }
    if(!verificaNumero(document.formFinalizaVenda.primeiraparcela)) {
      alert ("Por favor, verifique o período das demais parcelas.");
      focalizador();
      return false;
    }
    document.formFinalizaVenda.gerarparcelas.value = 1;
    document.formFinalizaVenda.submit();
  }

  function submeterRecalculoParcela(linha) {
    document.formFinalizaVenda.calcularcotacao.value = linha;
    document.formFinalizaVenda.recalcular.value = linha;
    document.formFinalizaVenda.submit();
  }

  function submeterParcelas(linha){
    document.formFinalizaVenda.submeteparcelas.value = linha;
    document.formFinalizaVenda.calcularcotacao.value = linha;
    document.formFinalizaVenda.recalcular.value = linha;
    document.formFinalizaVenda.submit();
  }

  function submeterRecalculoParcelaDias(linha) {
    document.formFinalizaVenda.recalcularparceladias.value = linha;
    document.formFinalizaVenda.submit();
  }

  function submeterImpressaoConferencia() {
	var quantidadevenda = prompt("Quantidade de vias.");
    document.formFinalizaVenda.imprimirconferencia.value = 1;
    document.formFinalizaVenda.conferencia.value = 1;
    document.formFinalizaVenda.submit();
  }

	function submeterImpressaoCheque() {
		document.formFinalizaVenda.imprimircheque.value = 1;
		document.formFinalizaVenda.submit();
	}

  function submeterEncerramento() {
    if(confirm("Deseja imprimir a Venda?")) {
      document.formFinalizaVenda.imprimir.value = 1;
    }
    document.formFinalizaVenda.checardatas.value = 1;
        //document.formFinalizaVenda.finalizarvenda.value = 1;
        document.formFinalizaVenda.submit();
      }

      function submeterNovaVenda() {
        document.formRealizaVenda.submit();
      }

      function submeterCaixa() {
        document.formCaixa.submit();
      }
      function submeterAviso() {
        alert('Cliente Novo! Parcelamento somente com entrada!');
      }
      function submeterAvisoStatus() {
        alert('Cliente com Status Reabilitado! Parcelamento somente com entrada!');
      }

      function verificaNumero (campo) {
        for (i = 0; i < campo.value.length; i++) {
          if (campo.value.charAt(i) != "1" &&
            campo.value.charAt(i) != "2" &&
            campo.value.charAt(i) != "3" &&
            campo.value.charAt(i) != "4" &&
            campo.value.charAt(i) != "5" &&
            campo.value.charAt(i) != "6" &&
            campo.value.charAt(i) != "7" &&
            campo.value.charAt(i) != "8" &&
            campo.value.charAt(i) != "9" &&
            campo.value.charAt(i) != "0" )
            return false;
        }
        return true;
      }

      function verificaValor (campo) {
        for (i = 0; i < campo.value.length; i++) {
          if (campo.value.charAt(i) != "." &&
            campo.value.charAt(i) != "," &&
            campo.value.charAt(i) != "1" &&
            campo.value.charAt(i) != "2" &&
            campo.value.charAt(i) != "3" &&
            campo.value.charAt(i) != "4" &&
            campo.value.charAt(i) != "5" &&
            campo.value.charAt(i) != "6" &&
            campo.value.charAt(i) != "7" &&
            campo.value.charAt(i) != "8" &&
            campo.value.charAt(i) != "9" &&
            campo.value.charAt(i) != "0" )
            return false;
        }
        return true;
      }

	function focalizador () {
	  
		if(document.formFinalizaVenda.datavencimento1.value){
			var str = document.formFinalizaVenda.datavencimento1.value;
			var res = str.substr(0, 2);
		}else{
			var res = "";
		}
		//alert(res);
		if(!document.formFinalizaVenda.observacao.value && !document.formFinalizaVenda.idtabelavenda.value) {
			document.formFinalizaVenda.observacao.focus();
			return false;
        }else if(document.formFinalizaVenda.observacao.value && document.formFinalizaVenda.idtabelavenda.value && res != "" && res != "--") {
			document.formFinalizaVenda.submeteEncerramento.focus();
			return false;
        }else if(document.formFinalizaVenda.observacao.value && document.formFinalizaVenda.idtabelavenda.value && document.formFinalizaVenda.porcentagemdesconto.value) {
			document.formFinalizaVenda.quantidadeparcela.focus();
			return false;
        }else if(document.formFinalizaVenda.observacao.value && document.formFinalizaVenda.idtabelavenda.value) {
			document.formFinalizaVenda.porcentagemdesconto.focus();
			return false;
        }else if(document.formFinalizaVenda.idtabelavenda.value && !document.formFinalizaVenda.observacao.value) {
          document.formFinalizaVenda.porcentagemdesconto.focus();
          return false;
        }else{
          document.formFinalizaVenda.observacao.focus();
          return false;
        }
      }

      function submeterTodasPArcelas() {
        document.formFinalizaVenda.submetetodasparcelas.value = 1;
        document.formFinalizaVenda.submit();
      }

      function submeterPrcelas(linha) {
        document.formFinalizaVenda.submeteparcelas.value = linha;
        document.formFinalizaVenda.submit();
      }
      </script>
    </head>

    <body onLoad = "javascript: focalizador();">
      <?
    // especificação dos diretórios para geração das etiquetas..
	define('FPDF_FONTPATH','../../sistema/font/');
	require('../../sistema/includes/fpdf.php');

    //Tratamento das variáveis recebidas do primeiro form (Realiza Venda).
	$filtrar = $_POST['filtrar'];
	$submeterdescontoporcentagem = $_POST['submeterdescontoporcentagem'];
	$submeterdescontovalor = $_POST['submeterdescontovalor'];
	$submetersenha = $_POST['submetersenha'];
	$submetersenha2 = $_POST['submetersenha2'];
	$buscarformapagamento = $_POST['buscarformapagamento'];
	$buscarprazopagamento = $_POST['buscarprazopagamento'];
	$gerarsenha = $_POST['gerarsenha'];
	$checardatas = $_POST['checardatas'];
	$calcularcotacao = $_POST['calcularcotacao'];
	$limparparcelas = $_POST['limparparcelas'];
	
	$gerarparcelas = $_POST['gerarparcelas'];
	$recalcular = $_POST['recalcular'];
	$recalcularparcela = $_POST['recalcularparcela'];
	$recalcularparceladias = $_POST['recalcularparceladias'];
	$finalizarvenda = $_POST['finalizarvenda'];
	$imprimir = $_POST['imprimir'];
	$imprimirconferencia = $_POST['imprimirconferencia'];
	$conferencia = $_POST['conferencia'];
	$quantidadevenda = $_POST['quantidadevenda'];
	$enviar = $_POST['enviar'];
	$imprimircarne = $_POST['imprimircarne'];
	$imprimircontrato = $_POST['imprimircontrato'];
	$imprimircheque = $_POST['imprimircheque'];
	$cheque = $_POST['cheque'];
	$valorchequeimp = $_POST['valorchequeimp'];
	$diaprevistoimp = $_POST['diaprevistoimp'];
	$mesprevistoimp = $_POST['mesprevistoimp'];
	$anoprevistoimp = $_POST['anoprevistoimp'];

	$tipovenda = $_POST['tipovenda'];
	if(!$tipovenda)
		$tipovenda = "VENDA";
	$idsinal = $_POST['idsinal'];
	$sinal = $_POST['sinal'];

	$idfuncionario = $_POST['idfuncionario'];
	$idvenda = $_POST['idvenda'];
	if(!$idvenda){
		$idvenda = $_GET['idvenda'];
	}
	$var1 = $_POST['senhadesconto'];
	$numerovenda = $_POST['numerovenda'];
	$nomefuncionario = $_POST['nomefuncionario'];
	$idunidde = $_POST['idunidade'];
	$idformapagamento = $_POST['idformapagamento'];
	$idformapagamentoparcela = $_POST['idformapagamentoparcela'];
	$prazo = $_POST['prazo'];
	$fator = $_POST['fator'];
	if(!$fator){
		$fator = $_GET['fator'];
	}
	$nomeformapagamento = $_POST['nomeformapagamento'];
	$idtabelavenda = $_POST['idtabelavenda'];
	$formapagamentoespecial = $_POST['formapagamentoespecial'];
	$primeiraparcela = $_POST['primeiraparcela'];
	$demaisparcelas = $_POST['demaisparcelas'];
	$limite = $_POST['limite'];
	$limitea = $_POST['limitea'];
	$limiteb = $_POST['limiteb'];
	if(!$demaisparcelas){
		$demaisparcelas = 30;
    }
    $idcliente = $_POST['idcliente'];
    $idsecao =  $_POST['idsecao'];
    $nomecliente = $_POST['nomecliente'];
    $somaitenstotal = $_POST['somaitenstotal'];
    $somaitenstotaldev = $_POST['somaitenstotaldev'];
    $somavalortotal = str_replace(",", ".", $_POST['somavalortotal']);
    $somaparcela = str_replace(",", ".", $_POST['somaparcela']);
    $valorfinal = str_replace(",", ".", $_POST['valorfinal']);
    $valordesconto = str_replace(",", ".", $_POST['valordesconto']);
    $valorcalculado = str_replace(",", ".", $_POST['valorcalculado']);
    $porcentagemdesconto = str_replace(",", ".", $_POST['porcentagemdesconto']);
    $troco = $_POST['troco'];
    $recebido = $_POST['recebido'];
    $valor = $_POST['valor'];
    $dataacerto = $_POST['dataacerto'];
    $nomeformapagamentoparcela = $_POST['nomeformapagamentoparcela'];
    $somaporcentagem = $_POST['somaporcentagem'];
    $datacadastro = $_POST['datacadastro'];
    $idtipobloqueio = $_POST['idtipobloqueio'];
    $primeiracompra = $_POST['primeiracompra'];
    $primeiracompra_status = $_POST['primeiracompra_status'];
    $crediario = $_POST['crediario'];
    $submetetodasparcelas = $_POST['submetetodasparcelas'];
    $submeteparcelas = $_POST['submeteparcelas'];

    if(!$dataacerto)
		$dataacerto = date("Y-m-d", mktime(0,0,0, date("m"), date("d") + 7, date("Y")));
    $observacao = $_POST['observacao'];

    $idestabelecimento = $_POST['idestabelecimento'];
    $nome = $_POST['nome'];

    $limitepermitido = $_POST['limitepermitido'];
    $entradapermitido = $_POST['entradapermitido'];

    $quantidadelinha = $_POST['quantidadelinha'];
    for ($i = 1; $i <= $quantidadelinha; $i++) {
		${"idproduto$i"} = $_POST['idproduto'. $i];
		${"codigobarra$i"} = $_POST['codigobarra'. $i];
		${"descricao$i"} = $_POST['descricao'. $i];
		${"siglaunidade$i"} = $_POST['siglaunidade'. $i];
		${"siglamoeda$i"} = $_POST['siglamoeda'. $i];
		${"quantidade$i"} = $_POST['quantidade'. $i];
		${"idindexador$i"} = $_POST['idindexador'. $i];
		${"porcentagemproduto$i"} = str_replace(",", ".", $_POST['porcentagemproduto'. $i]);
		${"precocusto$i"} = str_replace(",", ".", $_POST['precocusto'. $i]);
		${"precoreal$i"} = str_replace(",", ".", $_POST['precoreal'. $i]);
		${"precovenda$i"} = str_replace(",", ".", $_POST['precovenda'. $i]);
		${"defeito$i"} = $_POST['defeito'. $i];
		${"descricaodefeito$i"} = $_POST['descricaodefeito'. $i];
    }

    $quantidadeparcelapermitida = $_POST['quantidadeparcelapermitida'];
    $quantidadeparcela = $_POST['quantidadeparcela'];
    for ($i = 1; $i <= $quantidadeparcela; $i++) {
		${"datavencimento$i"} = $_POST['anovencimento'. $i] . "-" . $_POST['mesvencimento'. $i] . "-" . $_POST['diavencimento'. $i];
		${"idformapagamentoparcela$i"} = $_POST['idformapagamentoparcela'. $i];
		${"nomeformapagamentoparcela$i"} = $_POST['nomeformapagamentoparcela'. $i];
		${"valorparcela$i"} =str_replace(",", ".", $_POST['valorparcela'. $i]);
		${"valorparcelacotacao$i"} =str_replace(",", ".", $_POST['valorparcelacotacao'. $i]);
		${"metal$i"} = $_POST['metal'. $i];
		${"idcotacaometal$i"} = $_POST['idcotacaometal'. $i];
    }

    $quantidadeunidade = $_POST['quantidadeunidade'];
    for ($j = 1; $j <= $quantidadeunidade; $j++) {
		${"idunidadevenda$j"} = $_POST['idunidadevenda'. $j];
		${"siglaunidadevenda$j"} = $_POST['siglaunidadevenda'. $j];
		${"somaitenstotal$j"} = $_POST['somaitenstotal'. $j];
		${"somaitenstotaldev$j"} = $_POST['somaitenstotaldev'. $j];
    }

    $quantidadeindexadores = $_POST['quantidadeindexadores'];
    for ($m = 1; $m <= $quantidadeindexadores; $m++) {
		${"idindexadorv$m"} = $_POST['idindexadorv'. $m];
		${"siglaindexadorv$m"} = $_POST['siglaindexadorv'. $m];
		${"totalindexadorv$m"} = $_POST['totalindexadorv'. $m];
    }

	if($limparparcelas){
	
		unset($datavencimento1);
	}
	// abertura de conexão com o banco.
    $con = new Connection;
    $con->open();

    //verifica se é receituario
    $sql = "SELECT IdReceituario
    FROM venda_receituario
    WHERE IdVenda = $idvenda";
    $rs = $con->executeQuery ($sql);
    //echo $sql;
    if($rs->next()) {
		$idreceituario = $rs->get("IdReceituario");
    }
    $rs->close();

    //verifica se é receituario
    $sql = "SELECT IdCliente
			FROM venda
			WHERE IdVenda = $idvenda";
    $rs = $con->executeQuery ($sql);
    //echo $sql;
    if($rs->next()) {
		$idcliente = $rs->get("IdCliente");
    }
    $rs->close();

    $sql = "SELECT Limite, Entrada, DescontoFinalizaVenda, LimiteDesconto, SenhaDesconto, LimiteDescontoGer FROM parametros_venda";
    $rs = $con->executeQuery ($sql);
    //echo $sql;
    if($rs->next()) {
		$limitepermitido = $rs->get(0);
		$entradapermitido = $rs->get(1);
		$descontotela = $rs->get(2);
		$limitedesconto = $rs->get(3) * -1;
		$senhadesconto = $rs->get(4);
		$limitedescontoger = $rs->get(5);
    }
    $rs->close();

    // Seleção dos dados da forma de pagamento.
    $sql = "SELECT  DataCadastro, IdTipoBloqueio FROM cliente";
    $sql = "$sql WHERE IdCliente = '$idcliente'";
    //echo $sql;
    $rs = $con->executeQuery ($sql);
    if($rs->next()) {
		$datacadastro = $rs->get(0);
		$idtipobloqueio = $rs->get(1);
    }
    $rs->close();

    if($idcliente) {

		// Seleção do Nome do cliente.
		$sql = "SELECT CPF, Naturalidade, DataNascimento, RG, Telefone1, Pai, Mae, Endereco, TipoCliente, IdTipoBloqueio FROM cliente";
		$sql = "$sql WHERE IdCliente = '$idcliente' AND Ativo = '1'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()) {
			$cpfcliente = $rs->get("CPF");
			$naturalidadecliente = $rs->get("Naturalidade");
			$datanascimentocliente = $rs->get("DataNascimento");
			$rgcliente = $rs->get("RG");
			$telcliente = $rs->get("Telefone1");
			$filiacaocliente = $rs->get("Pai").'- '.$rs->get("Mae");
			$enderecocliente = $rs->get("Endereco");
			$tipocliente = $rs->get("TipoCliente");
			$idtipobloqueio = $rs->get("IdTipoBloqueio");

			if($crediario == '1'){
				$sql = "SELECT Reabilitado FROM tipo_bloqueio WHERE IdTipoBloqueio = $idtipobloqueio";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()) {

					if($rs1->get("Reabilitado") == '1'){

						$sql = "SELECT DataAlteracao FROM cliente_status WHERE IdCliente = '$idcliente'
						ORDER BY DataAlteracao DESC";
						//echo $sql;
						$rs2 = $con->executeQuery ($sql);
						if($rs2->next()) {
							$dataaux = substr($rs2->get("DataAlteracao"),0,10);

							$sql = "SELECT finaliza_venda.IdVenda
							FROM finaliza_venda, venda
							WHERE venda.IdVenda = finaliza_venda.IdVenda
							AND venda.IdCliente = $idcliente
							AND finaliza_venda.Data > '$dataaux'";
							//echo $sql;
							$rs3 = $con->executeQuery ($sql);
							if(!$rs3->next()) {
								$primeiracompra_status = 1;
							}
							$rs3->close();
						}
						$rs2->close();

					}
				}
				$rs1->close();
			}
		}
		$rs->close();
    }

    $dataatual = date('Y-m-d');

    if($valorfinal < 0){
		$quantidadeparcela = 1;
	}
    // Verificação do prazos quantidade de parcelas alterado 09/04/07.
    if($buscarprazopagamento) {
	
		for($i=1;$i<=$quantidadeparcela;$i++){
			unset(${"datavencimento$i"});
			unset(${"idformapagamentoparcela$i"});
			unset(${"nomeformapagamentoparcela$i"});
			unset(${"valorparcela$i"});
			unset(${"valorparcelacotacao$i"});
			unset(${"metal$i"});
			unset(${"idcotacaometal$i"});
		}
      unset($quantidadeparcela);
      unset($quantidadelinha);
      unset($primeiraparcela);
      unset($demaisparcelas);
      unset($limite);
   //   unset($fator);
      unset($crediario);


      // Seleção dos dados da forma de pagamento.
      $sql = "SELECT  QuantidadeParcela, Fator, PrimeiraParcela, DemaisParcela, Limite, Crediario FROM tabela_venda";
      $sql = "$sql WHERE IdTabelaVenda = '$idtabelavenda' AND IdLoja = '$funcionario[idloja]'";
      //echo $sql;
      $rs = $con->executeQuery ($sql);
      if($rs->next()) {
        $quantidadeparcelapermitida = $rs->get(0);
        //$fator = 1;
        $primeiraparcela= $rs->get(2);
        $demaisparcelas= $rs->get(3);
        $limite= $rs->get(4);
        $crediario= $rs->get(5);

        if(($primeiracompra_status == 1) && ($primeiraparcela >0)){
          unset($quantidadeparcelapermitida);
          unset($primeiraparcela);
          unset($demaisparcelas);
          unset($limite);
          unset($fator);
          unset($crediario);
          unset($idtabelavenda);

          echo "<script>alert('Prazo de pagamento sem entrada. Não é permitido para cliente reabilitado!')</script>";
        }
      }
      else {
        unset($quantidadelinha);
        unset($quantidadeparcelapermitida);
        unset($primeiraparcela);
        unset($demaisparcelas);
        unset($limite);
        unset($fator);
        unset($crediario);
      }
      $rs->close();
	  
	  $sql = "SELECT Fator
				FROM tabela_venda 
				WHERE IdTabelaVenda = '$idtabelavenda'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		if($rs->next()){
			$fator_tabela = $rs->get("Fator");
		}
		$rs->close();
		
		$sql = "SELECT IdVendaProduto, PrecoVendaOrig, PrecoVenda
				FROM venda_produto 
				WHERE IdVenda = '$idvenda'";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while($rs->next()){
			
			$precovenda = $rs->get("PrecoVendaOrig") * $fator_tabela;
			
			$sql = "UPDATE venda_produto SET
					PrecoVenda = '$precovenda'
					WHERE IdVendaProduto = ".$rs->get("IdVendaProduto");
			$con->executeQuery ($sql);
		}
		$rs->close();

      if($quantidadeparcelapermitida == 1) {
        $quantidadeparcela = 1;
      //  $gerarparcelas = TRUE;
      }
    }
	
	if(!$quantidadelinha){
		
		$somavalortotal = 0;
		
		$sql = "SELECT IdUnidadeVenda, Sigla FROM unidade_venda";
		$sql = "$sql WHERE Ativo = 1";
		//echo $sql;
		$rs = $con->executeQuery ($sql);
		while($rs->next ()) {
			$quantidadeunidade++;
			${"idunidadevenda$quantidadeunidade"} = $rs->get(0);
			${"siglaunidadevenda$quantidadeunidade"} = $rs->get(1);
			${"somaitenstotal$quantidadeunidade"} = "";
			${"somaitenstotaldev$quantidadeunidade"} = "";
		}
		$rs->close();

		// Seleção das moedas vendas
		$sql = "SELECT indexadores.IdIndexador, Sigla FROM indexadores, indexadores_loja WHERE indexadores_loja.IdIndexador = 	indexadores.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]' AND indexadores.Ativo = 1";
		$rs = $con->executeQuery ($sql);
		//echo $sql;
		while($rs->next ()) {
		   $quantidadeindexadores++;
		   ${"idindexadorv$quantidadeindexadores"} = $rs->get(0);
		   ${"siglaindexadorv$quantidadeindexadores"} = $rs->get(1);
		   ${"totalindexadorv$quantidadeindexadores"} = "";
		}
		$rs->close();
	 
		$sql = "SELECT IdVenda, venda_produto.IdVendaProduto, venda_produto.IdProduto, venda_produto.IdGrade, CodigoBarra, Descricao, Quantidade, PrecoCusto, PrecoVenda, PrecoReal, produto.IdMoeda, produto.IdUnidadeVenda, unidade_venda.sigla, indexadores.sigla, produto.IdIndexador FROM produto, grade, venda_produto, unidade_venda, indexadores";
        $sql = "$sql WHERE IdVenda = $idvenda AND grade.IdProduto = produto.IdProduto AND venda_produto.IdGrade = grade.IdGrade AND produto.IdProduto = venda_produto.IdProduto AND produto.IdUnidadeVenda = unidade_venda.IdUnidadeVenda AND produto.IdIndexador = indexadores.IdIndexador ORDER BY grade.CodigoBarra";
        //echo $sql;
        $rs = $con->executeQuery ($sql);
        while ($rs->next ()) {
			$quantidadelinha++;                     
			${"idvendaproduto$quantidadelinha"} = $rs->get(1);        
			${"idproduto$quantidadelinha"} = $rs->get(2);
			${"idgrade$quantidadelinha"} = $rs->get(3);
			${"codigobarra$quantidadelinha"} = $rs->get(4);
			${"descricao$quantidadelinha"} = $rs->get(5);
			${"quantidade$quantidadelinha"} = $rs->get(6);
			${"precocusto$quantidadelinha"} = $rs->get(7);
			${"precovenda$quantidadelinha"} = $rs->get(8);
			${"precoreal$quantidadelinha"} = $rs->get(9);
			${"idmoeda$quantidadelinha"} = $rs->get(10);
			${"idunidade$quantidadelinha"} = $rs->get(11);
			${"siglaunidade$quantidadelinha"} = $rs->get(12);
			${"siglamoeda$quantidadelinha"} = $rs->get(13);
			${"idindexador$quantidadelinha"} = $rs->get(14);

            //Seleção do tipo de Index
			$sql = "SELECT  Margem FROM indexadores, indexadores_loja WHERE indexadores.IdIndexador = '${"idindexador$quantidadelinha"}' AND indexadores.IdIndexador = indexadores_loja.IdIndexador AND indexadores_loja.IdLoja = '$funcionario[idloja]'";
			//echo $sql;
			$rs1 = $con->executeQuery ($sql);
			if($rs1->next()){
				$margemindex = $rs1->get(0);
			}
			$rs1->close();

			if(${"idunidade$quantidadelinha"} == 2){
				${"precoetiqueta$quantidadelinha"} = ${"precoreal$quantidadelinha"} * $margemindex;
			}
			else{
				${"precoetiqueta$quantidadelinha"} = ${"precoreal$quantidadelinha"};
			}

			$somavalortotal += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"};
			if(${"quantidade$quantidadelinha"} < 0)
				$somavalordev += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"};
			if(${"quantidade$quantidadelinha"} > 0)
				$somavalor += ${"precovenda$quantidadelinha"} * ${"quantidade$quantidadelinha"};
			$somaporcentagem = ($somavalordev * 100) / $somavalor;
			for($j = 1; $j <= $quantidadeunidade; $j++){
				if(${"quantidade$quantidadelinha"} > 0){
					if(${"idunidadevenda$j"} == ${"idunidade$quantidadelinha"})
						${"somaitenstotal$j"} += ${"quantidade$quantidadelinha"};
				}
				else{
					if(${"idunidadevenda$j"} == ${"idunidade$quantidadelinha"})
					${"somaitenstotaldev$j"} += ${"quantidade$quantidadelinha"} * (-1);
				}
			}
			
			for($m = 1; $m <= $quantidadeindexadores; $m++){
				if(${"idindexadorv$m"} == ${"idindexador$quantidadelinha"})
				${"totalindexadorv$m"} += ${"precoreal$quantidadelinha"};
			}
		} 
		$rs->close();  
	}

   if($checardatas){

      //busca todas as formas de pagamento
    $sql = "SELECT IdFormaPagamento, Prazo FROM forma_pagamento WHERE Ativo = 1 ORDER BY IdFormaPagamento";
      //echo $sql;
    $rs = $con->executeQuery ($sql);
    while ($rs->next()) {
      $idformapagto = $rs->get(0);
      $prazo = $rs->get(1);
      $i = 1;

      for($j=1;$j<=$quantidadeparcela;$j++){

          //verifica as parcelas com a forma de pagamento
        if((int)${"idformapagamentoparcela$j"} == (int)$idformapagto){

            //atribui a data de vencimento a uma variavel aux
          ${"datacheck$i"} = ${"datavencimento$j"};
          if( (int) $i != 1){
              //variavel auxiliar para trazer a data anterior para checar
            $aux = $i - 1;

              //faz o calculo de qual seria a data correta da parcela
            if($demaisparcelas == 30)
              $datacheck = date("Y-m-d", mktime(0,0,0, substr(${"datacheck$aux"}, 5, 2) + 1, substr(${"datacheck$aux"}, 8, 2), substr(${"datacheck$aux"}, 0 , 4)));
            else
              $datacheck = date("Y-m-d", mktime(0,0,0, substr(${"datacheck$aux"}, 5, 2), substr(${"datacheck$aux"}, 8, 2) + $demaisparcelas, substr(${"datacheck$aux"}, 0 , 4)));
            }
            $i++;
          }
        }
      }
      $rs->close();


      if(!$isErroData){
        $finalizarvenda = true;
      }

    }

    if(($datacadastro == $dataatual) && ($idtipobloqueio != 8) && ($entradapermitido == 0)){
      $primeiracompra = 1;
    }

    if($submeterdescontoporcentagem) {
      $quantidadeparcela = "";
      $gerarsenha = "";
      if ($porcentagemdesconto > 100)
        $porcentagemdesconto = 100;
        

      if($somavalortotal < 0)
         $valordesconto = (($somavalortotal * $fator) * $porcentagemdesconto / 100.00) * (-1);
      else
         $valordesconto = (($somavalortotal * $fator) * $porcentagemdesconto / 100.00);

    }

		if($submetetodasparcelas){
			unset($cheque);
			
			for ($i = 1; $i <= $quantidadeparcela; $i++) {
				${"idformapagamentoparcela$i"} = ${"idformapagamentoparcela1"};
        
				$sql  = "SELECT Cheque FROM forma_pagamento WHERE IdFormaPagamento = ".${"idformapagamentoparcela1"};
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					if($rs1->get('Cheque'))
						$cheque = $rs1->get('Cheque');
				}
				$rs1->close();
				
				$sql  = "SELECT Prazo FROM forma_pagamento WHERE IdFormaPagamento = ".${"idformapagamentoparcela1"}." AND Prazo IS NOT NULL";
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					$prazo = $rs1->get(0);
		
					if($i == 1){
						$time = mktime(0, 0, 0, date("m"), date("d") + $prazo, date("Y"));
						${"datavencimento$i"} = strftime('%Y-%m-%d', $time);
						$database = ${"datavencimento$i"};
					}else{
						$dia = substr($database,8,2);
						$mes = substr($database,5,2);
						$ano = substr($database,0,4);
						
						$time = mktime(0, 0, 0, $mes, $dia + 30, $ano);
						${"datavencimento$i"} = strftime('%Y-%m-%d', $time);
						$database = ${"datavencimento$i"};
					}
				}
				$rs1->close(); 
        
				if($i < 2){

					$sql = "SELECT Metal FROM forma_pagamento WHERE Ativo = 1 AND IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
					//echo $sql;
					$rs = $con->executeQuery ($sql);
					while ($rs->next()) {
						${"metal$i"} = $rs->get(0);
					}
					$rs->close();
				}
			}
		}

		if($submeteparcelas){
			unset($cheque);
			
			for ($i = $submeteparcelas; $i <= $quantidadeparcela; $i++) {
				${"idformapagamentoparcela$i"} = ${"idformapagamentoparcela$submeteparcelas"};
        
				$sql  = "SELECT Cheque FROM forma_pagamento WHERE IdFormaPagamento = ".${"idformapagamentoparcela1"};
				//echo $sql;
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					if($rs1->get('Cheque'))
						$cheque = $rs1->get('Cheque');
				}
				$rs1->close();
			
				$sql  = "SELECT Prazo FROM forma_pagamento WHERE IdFormaPagamento = ".${"idformapagamentoparcela$submeteparcelas"}." AND Prazo IS NOT NULL";
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()){
					$prazo = $rs1->get(0);
					
			  
					if($i == $submeteparcelas){
						$time = mktime(0, 0, 0, date("m"), date("d") + $prazo, date("Y"));
						${"datavencimento$i"} = strftime('%Y-%m-%d', $time);
						$database = ${"datavencimento$i"};
					}else{
						$dia = substr(${"datavencimento$i"},8,2);
						$mes = substr(${"datavencimento$i"},5,2);
						$ano = substr(${"datavencimento$i"},0,4);
						
						$time = mktime(0, 0, 0, $mes, $dia + 30, $ano);
						${"datavencimento$i"} = strftime('%Y-%m-%d', $time);
						$database = ${"datavencimento$i"};
					}
				}
				$rs1->close(); 

				$sql = "SELECT Metal FROM forma_pagamento WHERE Ativo = 1 AND IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				while ($rs->next()) {
					${"metal$i"} = $rs->get(0);
				}
				$rs->close();
			}
		}
 
    if($submetersenha && (($porcentagemdesconto*-1) <= $limitedescontoger)) {
      if(md5($var1) == $senhadesconto){
        $gerarsenha = TRUE;
	  }else{
		$isErroSenha = '<br><font color="red">Senha incorreta e/ou desconto não permitido para gerente!</font>';
	  }
    }else if($submetersenha){
		$isErroSenha = '<br><font color="red">Senha incorreta e/ou desconto não permitido para gerente!</font>';
	}

    if($submeterdescontovalor) {
      $quantidadeparcela = "";
      $gerarsenha = "";
      // "if" para não permitir a divisão por "ZERO".
      if($somavalortotal > 0)
        $porcentagemdesconto = $valordesconto * 100.00 / ($fator * $somavalortotal);
      else if($somavalortotal < 0)
        $porcentagemdesconto = ($valordesconto * 100.00 / ($fator * $somavalortotal)) * (-1);

    }

    // Entra no "if" para gerar as parcelas da venda.
    if($gerarparcelas) {

		if(($primeiracompra_status == 1) && ($primeiraparcela > 0)){
			unset($quantidadeparcela);
			echo "<script>alert('Prazo de pagamento sem entrada. Não permitido para cliente reabilitado!')</script>";
		}

		if($quantidadeparcelapermitida < $quantidadeparcela) {
			$quantidadeparcela = $quantidadeparcelapermitida;
			$isAlteracaoQuantidadeParcela = TRUE;
		}

		$valoraux = 0.00;
		
		for($i = 1; $i <= $quantidadeparcela; $i++) {
			${"valorparcela$i"} = "";

			${"idformapagamentoparcela$i"} = 1;
			if($i == 1) {
				${"datavencimento$i"} = date("Y-m-d", mktime(0,0,0, date("m"), date("d") + $primeiraparcela, date("Y")));
				$database = ${"datavencimento$i"};

				$mes = substr($database, 5, 2);
				$dia = substr($database, 8, 2);
				$ano = substr($database, 0, 4);

				if($mes == 2 && $dia > 28){
					$dia = 28;
				}elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
					$dia = 30;
				}

				$database = $ano.'-'.$mes.'-'.$dia;
				${"datavencimento$i"} = $ano.'-'.$mes.'-'.$dia;
			}
			else {
				if($demaisparcelas == 30)
					${"datavencimento$i"} = date("Y-m-d", mktime(0,0,0, substr($database, 5, 2) + 1, substr($database, 8, 2), substr($database, 0 , 4)));
				else
					${"datavencimento$i"} = date("Y-m-d", mktime(0,0,0, substr($database, 5, 2), substr($database, 8, 2) + $demaisparcelas, substr($database, 0 , 4)));
          
				$database = ${"datavencimento$i"};

				$mes = substr($database, 5, 2);
				$dia = substr($database, 8, 2);
				$ano = substr($database, 0, 4);

				if($mes == 2 && $dia > 28){
					$dia = 28;
				}elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
					$dia = 30;
				}

				$database = $ano.'-'.$mes.'-'.$dia;
				${"datavencimento$i"} = $ano.'-'.$mes.'-'.$dia;
			}
			
			${"valorparcela$i"} = round($valorfinal / $quantidadeparcela,2);
			${"valorparcelacotacao$i"} = "";
			${"idcotacaometal$i"} = "";
			
			$valoraux += ${"valorparcela$i"};
		}
		
		//tratando arredondamento
		if(sprintf("%1.2f",$valorfinal) > sprintf("%1.2f",$valoraux)){
		
			$aux = $valorfinal - $valoraux;
				
			$valorparcela1 = $valorparcela1 + $aux;
				
		}elseif(sprintf("%1.2f",$valorfinal) < sprintf("%1.2f",$valoraux)){
		
			$aux = $valoraux - $valorfinal;
				
			${"valorparcela$quantidadeparcela"} = ${"valorparcela$quantidadeparcela"} - $aux;
				
		}
    }

    if($recalcularparceladias){
      for($i = 1; $i <= $quantidadeparcela; $i++) {
        if($i < $recalcularparceladias)
          ${"datavencimento$i"};
        else if ($i == $recalcularparceladias){
          $y = $i - 1;
          $datavencimentoant = ${"datavencimento$y"};
          $datavencimentoant =  date("Y-m-d", mktime(0,0,0, substr($database, 5, 2), substr($database, 8, 2) + $demaisparcelas, substr($database, 0 , 4)));
          if(${"datavencimento$i"} <= $datavencimentoant)
           ${"datavencimento$i"};
         else
           ${"datavencimento$i"} = $datavencimentoant;

         $mes = substr($datavencimentoant, 5, 2);
         $dia = substr($datavencimentoant, 8, 2);
         $ano = substr($datavencimentoant, 0, 4);

         if($mes == 2 AND $dia > 28){
          $dia = 28;
          $mes = 2;
        }else if($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
          $dia = 30;
        }

        $datavencimentoant = $ano.'-'.$mes.'-'.$dia;
        ${"datavencimento$i"} = $ano.'-'.$mes.'-'.$dia;
      }
      else{
        ${"datavencimento$i"} = date("Y-m-d", mktime(0,0,0, substr($database, 5, 2), substr($database, 8, 2) + $demaisparcelas, substr($database, 0 , 4)));
        $database = ${"datavencimento$i"};

        $mes = substr($database, 5, 2);
        $dia = substr($database, 8, 2);
        $ano = substr($database, 0, 4);

        if($mes == 2 AND $dia > 28){
          $dia = 28;
          $mes = 2;
        }else if($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
          $dia = 30;
        }

        $database = $ano.'-'.$mes.'-'.$dia;
        ${"datavencimento$i"} = $ano.'-'.$mes.'-'.$dia;
      }
    }
  }

  if($calcularcotacao){

    for ($i = 1; $i <= $quantidadeparcela; $i++) {

      if($i <= $calcularcotacao){

       // Seleção do Nome do cliente.
       $sql = "SELECT Valor FROM cotacao_metal";
       $sql = "$sql WHERE IdCotacaoMetal = '${"idcotacaometal$i"}' AND Ativo = '1'";
       $rs = $con->executeQuery ($sql);
       if($rs->next()) {
        $valorcotacao = $rs->get(0);
      }
      $rs->close();

      ${"valorparcelacotacao$i"} =  ${"valorparcela$i"} *  $valorcotacao;

    }
  }
}

    // Entra no "if" para recalcular as parcelas da venda.
if($recalcular) {
  for($i = 1; $i <= $quantidadeparcela; $i++) {
    if($i <= $recalcular){
      if(${"metal$i"} == 1)
        $valorcalculado += ${"valorparcelacotacao$i"};
      else
        $valorcalculado += ${"valorparcela$i"};
    }
    else
      ${"valorparcela$i"} = (($valorfinal - $valorcalculado) / ($quantidadeparcela - $recalcular));
  }
}

if($tipovenda == "SINAL" || $tipovenda == "VENDA" ) {
  $sql = "SELECT sinal.IdSinal, IdVenda, Sinal, Observacao FROM sinal, venda_sinal";
  $sql = "$sql WHERE IdVenda = '$idvenda' AND sinal.IdSinal = venda_sinal.IdSinal";
      //echo $sql;
  $rs = $con->executeQuery ($sql);
  if($rs->next()) {
    $idsinal = $rs->get(0);
    $sinal = $rs->get(2);
    $observacao = $rs->get(3);
  }
  else {
    $idsinal = "";
        //$sinal = "";
    $idfomapagamentosinal = "";
  }
  $rs->close();
}

		if($finalizarvenda) {
			$data = date("Y-m-d");
			$dataemissao = date("d/m/Y");
			$hora = date("H:i:s");

			$sql = "SELECT Limite, SaldoVale FROM cliente WHERE IdCliente = '$idcliente'";
			//echo $sql;
			$rs1 = $con->executeQuery ($sql);
			if($rs1->next()){
				$valorcredito = $rs1->get(0);
				$saldovale = $rs1->get(1);
			}
			$rs1->close();

			if($tipovenda == "VENDA") {

				for($i = 1; $i <= $quantidadeparcela; $i++) {

					$sql = "SELECT IdFormaPagamento, Crediario, Aberto FROM forma_pagamento WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
					//echo $sql;
					$rs = $con->executeQuery ($sql);
					if($rs->next()) {
						if($rs->get(1) == '1'){
							$valorcredito -= ${"valorparcela$i"};
						}
						if($rs->get(2) == '1'){
							$saldovale += ${"valorparcela$i"};
						}
					}
				}

				// if(($valorcredito >= '0' && $saldovale >= '0') OR ($limitepermitido == 1 && $saldovale >='0') OR ($somavalortotal <= '0')){
	
				// Verifica se a venda está fechada.
				$sql = "SELECT IdVenda FROM finaliza_venda WHERE IdVenda = '$idvenda'";
				//echo $sql;
				$rs = $con->executeQuery ($sql);
				if($rs->next()){
					$verificavenda = FALSE;
					unset($imprimir);
				}
				else
					$verificavenda = TRUE;
				$rs->close();

				if($verificavenda){
					if(!$valordesconto)
						$valordesconto = 0;

				$sql = "INSERT INTO finaliza_venda (IdVenda, IdTabelaVenda, Data, Hora, Valor, Desconto, PorcentagemDesc, Fator, Cotacao, Observacao, Ativo)";
				$sql = "$sql VALUES ('$idvenda', '$idtabelavenda', '$data', '$hora', '$somavalortotal', '$valordesconto', '$porcentagemdesconto', '$fator', 0.00, '$observacao', '1')";
				//  echo $sql;
				if ($con->executeUpdate($sql))
					$isFinalizaVenda = TRUE;

				if($valorfinal > 0) {

					//Incluindo dados tabela parcela
					for($i = 1; $i <= $quantidadeparcela; $i++) {

						$mes = substr(${"datavencimento$i"}, 5, 2);
						$dia = substr(${"datavencimento$i"}, 8, 2);
						$ano = substr(${"datavencimento$i"}, 0, 4);

						if($mes == 2 && $dia > 28){
							$dia = 28;
						}elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
							$dia = 30;
						}

						$datavencimento = $ano.'-'.$mes.'-'.$dia;

						//gerar código de barras
						$valor = sprintf("%d", ${"valorparcela$i"});
						$numerop = tratamentoString($i,2);
						$datacod = substr($data,2,2).substr($data,5,2).substr($data,8,2);
						$idloja = $funcionario[idloja];
						$codigobarra = $idvenda.'00'.$datacod.$idloja.$valor.$numerop;

						$sql = "INSERT INTO parcela (IdTipoVenda, IdFormaPagamento, IdLoja, NumeroParcela, CodigoBarra, Valor, ValorCotacao, DataPrevista, Renegociacao, 	Baixa, CartaAviso, CartaCobranca, SPC, HoraCadastro, DataCadastro, Ativo)";
						$sql = "$sql VALUES ('1', '${"idformapagamentoparcela$i"}', '$funcionario[idloja]', '$i', '$codigobarra', '${"valorparcela$i"}', '${"valorparcelacotacao$i"}', '$datavencimento', '0', '0', '0', '0', '0', '$hora', '$data', '1')";
						//echo $sql;
						if ($con->executeUpdate($sql))
							$isParcela = TRUE;

						// Cálculo do idparcela.
						$sql = "SELECT MAX(IdParcela) FROM parcela WHERE IdLoja = '$funcionario[idloja]' AND NumeroParcela = '$i' AND Valor = '${"valorparcela$i"}'";
						$rs = $con->executeQuery ($sql);
						if($rs->next())
							$idparcela = $rs->get(0);
						$rs->close();

						$sql = "INSERT INTO parcela_venda (IdParcela, IdVenda, DataCadastro, Ativo)";
						$sql = "$sql VALUES ('$idparcela', '$idvenda', '$data', '1')";
						//echo $sql;
						if ($con->executeUpdate($sql))
							$isParcelaVenda = TRUE;

						$sql = "SELECT IdFormaPagamento, Aberto FROM forma_pagamento WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						if($rs->next()) {
							if($rs->get(1) == '1'){
								$saldovaleaux += ${"valorparcela$i"};
							}
						}

						if($saldovaleaux){

							// Atualização da situacao consignado.
							$sql = "UPDATE cliente SET";
							$sql = "$sql SaldoVale = SaldoVale - $saldovaleaux;";
							$sql = "$sql WHERE IdCliente = '$idcliente'";
							//echo $sql;
							if ($con->executeUpdate($sql) == 1)
								$isUpdateVale = TRUE;
						}

						$sql = "SELECT Taxa FROM formapagamento_taxa WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}' AND IdLoja = '$funcionario[idloja]'";
						// echo $sql;
						$rs = $con->executeQuery ($sql);
						if($rs->next()){
							$juros = $rs->get(0);

							// Verificação da Conta Corrente para acumular valor para cartões.
							$sql = "SELECT IdContaCorrente, IdFormaPagamento FROM conta_corrente WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
							//echo $sql;
							$rs = $con->executeQuery ($sql);
							if($rs->next()){
								$idconta = $rs->get(0);
								$verificaConta = FALSE;
							}
							else {
								$verificaConta = TRUE;
							}
							$rs->close();

							if($verificaConta) {
								// Cálculo do idconta.
								$sql = "SELECT MAX(IdContaCorrente) FROM conta_corrente";
								$rs = $con->executeQuery ($sql);
								if($rs->next())
									$idconta = $rs->get(0) + 1;
								$rs->close();

								$lancamento =  ${"valorparcela$i"} - (($juros * ${"valorparcela$i"})/100);

								$sql = "INSERT INTO conta_corrente (IdContaCorrente, IdFormaPagamento, SaldoAtual, DataCadastro)";
								$sql = "$sql VALUES ('$idconta', '${"idformapagamentoparcela$i"}', '$lancamento', '$dataatual')";
								//echo $sql;
								if ($con->executeUpdate($sql))
									$isctacorrentelancamento = TRUE;

								// Cálculo do idlancamento.
								$sql = "SELECT MAX(IdCtaCorrenteLancamento) FROM ctacorrente_lancamento";
								$rs = $con->executeQuery ($sql);
								if($rs->next())
									$idlancamento = $rs->get(0) + 1;
								$rs->close();

								$sql = "INSERT INTO ctacorrente_lancamento (IdCtaCorrenteLancamento, IdContaCorrente, NumeroDocumento, NumeroParcela, IdLoja, Tipo, Valor, Lancamento, Saldo, Juros, DataPrevista, DataLancamento, HoraCadastro, DataCadastro, Baixa, Transferida, Ativo)";
								$sql = "$sql VALUES ('$idlancamento', '$idconta', '$idvenda', '$idparcela', '$funcionario[idloja]', 'V', '${"valorparcela$i"}',  '$lancamento', '$lancamento', '$juros', '${"datavencimento$i"}', '$data', '$hora', '$data', '0', '0', '1')";
								//echo $sql;
								if ($con->executeUpdate($sql))
								  $isLancamento = TRUE;
							}
							else{

								// Cálculo do idlancamento.
								$sql = "SELECT MAX(IdCtaCorrenteLancamento) FROM ctacorrente_lancamento";
								$rs = $con->executeQuery ($sql);
								if($rs->next())
									$idlancamento = $rs->get(0) + 1;
								$rs->close();

								$lancamento =  ${"valorparcela$i"} - (($juros * ${"valorparcela$i"})/100);

								$sql = "SELECT SaldoAtual FROM conta_corrente WHERE IdContaCorrente = '$idconta'";
								$rs = $con->executeQuery ($sql);
								if ($rs->next())
									$saldopag = $rs->get(0) + $lancamento;
								$rs->close();

								$sql = "INSERT INTO ctacorrente_lancamento (IdCtaCorrenteLancamento, IdContaCorrente, NumeroDocumento, NumeroParcela, IdLoja, Tipo, Valor, Lancamento, Saldo, Juros, DataPrevista, DataLancamento, HoraCadastro, DataCadastro, Baixa, Transferida, Ativo)";
								$sql = "$sql VALUES ('$idlancamento', '$idconta', '$idvenda', '$idparcela', '$funcionario[idloja]', 'V', '${"valorparcela$i"}', '$lancamento', '$saldopag', '$juros', '${"datavencimento$i"}', '$data', '$hora', '$data', '0', '0', '1')";
								//echo $sql;
								if ($con->executeUpdate($sql))
									$isLancamento = TRUE;

								$sql = "UPDATE conta_corrente SET";
								$sql = "$sql SaldoAtual = SaldoAtual + $lancamento";
								$sql = "$sql WHERE IdContaCorrente = '$idconta'";
								//echo $sql;
								if ($con->executeUpdate($sql) == 1)
									$isUpdateContaCorrente = TRUE;
							}
						}
						$rs->close();

						$sql = "SELECT IdFormaPagamento, Crediario FROM forma_pagamento WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
						$rs = $con->executeQuery ($sql);
						if($rs->next()) {
							if($rs->get(1) == '1'){

								$sql = "UPDATE limite_credito SET";
								$sql = "$sql Valor = Valor - '${"valorparcela$i"}'";
								$sql = "$sql WHERE IdCliente = '$idcliente' AND IdLoja = '$funcionario[idloja]'";
								//echo $sql;
								if ($con->executeUpdate($sql) == 1)
									$isUpdateCrediario = TRUE;
							}
						}
					}
					
					if($saldovaleaux){

						// Verificação do cliente  (se já existe na tabela 'ctacorrentecliente').
						$sql = "SELECT IdCtaCorrenteCliente, IdCliente, IdMoeda 
						FROM ctacorrente_cliente 
						WHERE IdCliente = '$idcliente' AND IdMoeda = '3'";
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						if($rs->next()){
					 		$idconta = $rs->get('IdCtaCorrenteCliente');
							
    						// Seleção do novo IdPagamento.
							$sql = "SELECT SaldoAtual FROM ctacorrente_cliente WHERE IdCtaCorrenteCliente = '$idconta'";
							$rs1 = $con->executeQuery ($sql);
							if ($rs1->next())
								$saldopag = $rs1->get('SaldoAtual') + $saldovaleaux;
							$rs1->close();
						}
						else {
							$sql = "SELECT MAX(IdCtaCorrenteCliente) FROM ctacorrente_cliente";
    						$rs = $con->executeQuery ($sql);
    						if($rs->next())
    							$idconta = $rs->get(0) + 1;
    						$rs->close();
							
						 	$sql = "INSERT INTO ctacorrente_cliente (IdCtaCorrenteCliente, IdCliente, IdMoeda, SaldoAtual, DataCadastro)";
    						$sql = "$sql VALUES ('$idconta', '$idcliente', '3', '$saldovaleaux', '".date("Y-m-d")."')";
    						//echo $sql;
    						$con->executeUpdate($sql);
							
							$saldopag = $saldovaleaux;
						}
						$rs->close();

						$sql = "INSERT INTO ctacliente_lancamento (IdCtaCorrenteCliente, NumeroDocumento, IdVenda, IdLoja, Lancamento, Saldo, DataLancamento, HoraCadastro, DataCadastro, Ativo)";
 			   			$sql = "$sql VALUES ('$idconta', '$idvenda', '$idvenda', '$funcionario[idloja]', '$saldovaleaux', '$saldopag', '".date("Y-m-d")."', '".date("H:i:s")."', '".date("Y-m-d")."', '1')";
    					//echo $sql;
    					$con->executeUpdate($sql);
							
						$sql = "UPDATE ctacorrente_cliente SET";
						$sql = "$sql SaldoAtual = $saldopag";
						$sql = "$sql WHERE IdCtaCorrenteCliente = '$idconta'";
						//echo $sql;
					  	$con->executeUpdate($sql);
					}
				}else {
					$valorparcela = $valorfinal;

					if($valorparcela < 0)
						$valor = $valorparcela * (-1);
					else
						$valor = $valorparcela;

					//gerar código de barras
					$valor = sprintf("%d", $valor);
					$numerop = tratamentoString($i,2);
					$datacod = substr($data,2,2).substr($data,5,2).substr($data,8,2);
					$idloja = $funcionario[idloja];
					$codigobarra = $idvenda.'00'.$datacod.$idloja.$valor.$numerop;

					 // Inserindo um recebimento de nota zerada ou negativa.
					for($i = 1; $i <= $quantidadeparcela; $i++) {
					
						$sql = "SELECT IdFormaPagamento, Aberto FROM forma_pagamento WHERE IdFormaPagamento = '${"idformapagamentoparcela$i"}'";
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						if($rs->next()) {
							if($rs->get(1) == '1'){
								$debitoconta += ${"valorparcela$i"};
							}
						}

						$mes = substr(${"datavencimento$i"}, 5, 2);
						$dia = substr(${"datavencimento$i"}, 8, 2);
						$ano = substr(${"datavencimento$i"}, 0, 4);

						if($mes == 2 && $dia > 28){
							$dia = 28;
						}elseif($mes == 4 || $mes == 6 || $mes == 9 || $mes == 10 AND $dia == 31){
							$dia = 30;
						}

						$datavencimento = $ano.'-'.$mes.'-'.$dia;
		
						$sql = "INSERT INTO parcela (IdTipoVenda, IdFormaPagamento, IdLoja, NumeroParcela, CodigoBarra, Valor, ValorCotacao, DataPrevista, Renegociacao, Baixa, CartaAviso, CartaCobranca, SPC, HoraCadastro, DataCadastro, Ativo)";
						$sql = "$sql VALUES ('1', '${"idformapagamentoparcela$i"}', '$funcionario[idloja]', '$i', '$codigobarra', '${"valorparcela$i"}', '${"valorparcelacotacao$i"}', '$datavencimento', '0', '0', '0', '0', '0', '$hora', '$data', '1')";
						//echo $sql;
						if ($con->executeUpdate($sql))
							$isParcela = TRUE;

						// Cálculo do idparcela.
						$sql = "SELECT MAX(IdParcela) FROM parcela WHERE IdLoja = '$funcionario[idloja]' AND NumeroParcela = '$i' AND Valor = '${"valorparcela$i"}'";
						$rs = $con->executeQuery ($sql);
						if($rs->next())
							$idparcela = $rs->get(0);
						$rs->close();

						$sql = "INSERT INTO parcela_venda (IdParcela, IdVenda, DataCadastro, Ativo)";
						$sql = "$sql VALUES ('$idparcela', '$idvenda', '$data', '1')";
						//echo $sql;
						if ($con->executeUpdate($sql))
							$isParcelaVenda = TRUE;
					}
					
					if($debitoconta){

						// Verificação do cliente  (se já existe na tabela 'ctacorrentecliente').
						$sql = "SELECT IdCtaCorrenteCliente, IdCliente, IdMoeda 
						FROM ctacorrente_cliente 
						WHERE IdCliente = '$idcliente' AND IdMoeda = '3'";
						//echo $sql;
						$rs = $con->executeQuery ($sql);
						if($rs->next()){
					 		$idconta = $rs->get('IdCtaCorrenteCliente');
							
    						// Seleção do novo IdPagamento.
							$sql = "SELECT SaldoAtual FROM ctacorrente_cliente WHERE IdCtaCorrenteCliente = '$idconta'";
							$rs1 = $con->executeQuery ($sql);
							if ($rs1->next())
								$saldopag = $rs1->get('SaldoAtual') - $debitoconta;
							$rs1->close();
						}
						else {
							$sql = "SELECT MAX(IdCtaCorrenteCliente) FROM ctacorrente_cliente";
    						$rs = $con->executeQuery ($sql);
    						if($rs->next())
    							$idconta = $rs->get(0) + 1;
    						$rs->close();
							
						 	$sql = "INSERT INTO ctacorrente_cliente (IdCtaCorrenteCliente, IdCliente, IdMoeda, SaldoAtual, DataCadastro)";
    						$sql = "$sql VALUES ('$idconta', '$idcliente', '3', '$debitoconta', '".date("Y-m-d")."')";
    						//echo $sql;
    						$con->executeUpdate($sql);
							
							$saldopag = $debitoconta;
						}
						$rs->close();

						$sql = "INSERT INTO ctacliente_lancamento (IdCtaCorrenteCliente, NumeroDocumento, IdVenda, IdLoja, Lancamento, Saldo, DataLancamento, HoraCadastro, DataCadastro, Ativo)";
 			   			$sql = "$sql VALUES ('$idconta', '$idvenda', '$idvenda', '$funcionario[idloja]', '$debitoconta', '$saldopag', '".date("Y-m-d")."', '".date("H:i:s")."', '".date("Y-m-d")."', '1')";
    					//echo $sql;
    					$con->executeUpdate($sql);
							
						$sql = "UPDATE ctacorrente_cliente SET";
						$sql = "$sql SaldoAtual = $saldopag";
						$sql = "$sql WHERE IdCtaCorrenteCliente = '$idconta'";
						//echo $sql;
					  	$con->executeUpdate($sql);
					}

					$saldovaleaux = $valorfinal * (-1);

					// Atualização da situacao consignado.
					$sql = "UPDATE cliente SET";
					$sql = "$sql SaldoVale = SaldoVale + $saldovaleaux";
					$sql = "$sql WHERE IdCliente = '$idcliente'";
					//echo $sql;
					if ($con->executeUpdate($sql) == 1)
						$isUpdateVale = TRUE;

					$sql = "INSERT INTO vale (IdFuncionario, IdVenda, IdCliente, IdLoja, Valor, Data, Hora, Baixa, Ativo)";
					$sql = "$sql VALUES ('$idfuncionario', '$idvenda', '$idcliente', '$funcionario[idloja]', '$saldovaleaux', '$data', '$hora', '0', '1')";
					//echo $sql;
					if ($con->executeUpdate($sql))
						$isValeControle = TRUE;

					// Cálculo do idlancamento.
					$sql = "SELECT MAX(IdCtaCorrenteLancamento) FROM ctacorrente_lancamento";
					$rs = $con->executeQuery ($sql);
					if($rs->next())
						$idlancamento = $rs->get(0) + 1;
					$rs->close();
				}

				// Atualização da situacao consignado.
				$sql = "UPDATE venda SET";
				$sql = "$sql Fechada = '1'";
				$sql = "$sql WHERE IdVenda = '$idvenda'";
				//echo $sql;
				if ($con->executeUpdate($sql) == 1)
					$isUpdateVendaSituacao = TRUE;

				// Atualização da situacao consignado.
				$sql = "UPDATE finaliza_venda, venda_produto, estoque SET";
				$sql = "$sql UltimaVenda = '".date("Y-m-d")."'";
				$sql = "$sql WHERE finaliza_venda.IdVenda = venda_produto.IdVenda";
				$sql = "$sql AND venda_produto.IdProduto = estoque.IdProduto";
				$sql = "$sql AND estoque.IdLoja = '$funcionario[idloja]'";
				$sql = "$sql AND finaliza_venda.IdVenda = '$idvenda'";
				//echo $sql;
				if ($con->executeUpdate($sql) == 1)
					$isUpdateVendaSituacao = TRUE;

				// Atualização da situacao consignado.
				$sql = "UPDATE finaliza_venda, venda_produto, estoque_grade SET";
				$sql = "$sql UltimaVenda = '".date("Y-m-d")."'";
				$sql = "$sql WHERE finaliza_venda.IdVenda = venda_produto.IdVenda";
				$sql = "$sql AND venda_produto.IdGrade = estoque_grade.IdGrade";
				$sql = "$sql AND estoque_grade.IdLoja = '$funcionario[idloja]'";
				$sql = "$sql AND finaliza_venda.IdVenda = '$idvenda'";
				//echo $sql;
				if ($con->executeUpdate($sql) == 1)
					$isUpdateVendaSituacao = TRUE;

				if($idreceituario){

					// Cálculo do idparcela.
					$sql = "SELECT MAX(IdFinalizaReceituario) FROM finaliza_receituario";
					$rs = $con->executeQuery ($sql);
					if($rs->next())
						$idfinalizareceituario = $rs->get(0) + 1;
					$rs->close();

					$sql = "INSERT INTO finaliza_receituario (IdReceituario, IdTabelaVenda, Data, Hora, Valor, Fator, Desconto, PorcentagemDesc, Sinal)";
					$sql = "$sql VALUES ('$idreceituario', '$idtabelavenda', '$data', '$hora', '$somavalortotal', '$fator', '$valordesconto', '$porcentagemdesconto', '$sinal')";
					//echo $sql;
					if ($con->executeUpdate($sql))
						$isFinalizaReceituario = TRUE;

					// Atualização da situacao consignado.
					$sql = "UPDATE receituario SET";
					$sql = "$sql Fechada = '1'";
					$sql = "$sql WHERE IdReceituario = '$idreceituario'";
					//echo $sql;
					if ($con->executeUpdate($sql) == 1)
						$isUpdateReceituarioSituacao = TRUE;  
				}

				$sql = "SELECT JetLoja, Solucao
				FROM parametro_cupomfiscal
				WHERE IdLoja = '$funcionario[idloja]'";
				$rs1 = $con->executeQuery ($sql);
				if($rs1->next()) {
					$jetloja = $rs1->get("JetLoja");
					$solucao = $rs1->get("Solucao");
				}
				$rs1->close();

				$vendafin = $idvenda.'.FIN';
				$arquivo = fopen("../../../../../ecf/$vendafin", "w");

				$semdesconto = ($somavalortotal * $fator);
				$semdesconto =  sprintf("%13.2f", $semdesconto);
				$desconto = $valordesconto * (-1);
				$desconto = sprintf("%12.2f", $desconto);
				$campo = '003';
				$campo2 = '005';
				$aspas = '"';

				if($jetloja == 1 && $solucao == 0)
					fwrite($arquivo, "$aspas$campo$aspas,$aspas$desconto$semdesconto$aspas\n");
				elseif($jetloja == 0 && $solucao == 1)
					fwrite($arquivo, "$campo$desconto$semdesconto\n");

				if($quantidadelinha) {
					for ($i = 1; $i <= $quantidadelinha; $i++) {
						if(${"quantidade$i"}<0){
							$idproduto = espacoVazioDireita(${"idproduto$i"}, 13);
							$descricao = espacoVazioDireita(substr(${"descricao$i"}, 0, 37), 38);
							$quantidade = espacoVazioEsquerda(sprintf("%6.3f",(${"quantidade$i"} * (-1))), 10);
							$precovenda = espacoVazioEsquerda(sprintf("%9.3f",${"precovenda$i"}), 13);
						  
							if($jetloja == 1 && $solucao == 0)
								fwrite($arquivo, "$aspas$campo2$aspas,$aspas$idproduto$descricao$quantidade$precovenda$aspas\n");
							elseif($jetloja == 0 && $solucao == 1)
								fwrite($arquivo, "$campo2$idproduto$descricao$quantidade$precovenda\n");
						}
					}
				} 
				fclose($arquivo);

				$vendaorc = $idvenda.'.ORC';

				$arquivo = fopen("../../../../../ecf/$vendaorc", "w");

				if($quantidadelinha) {

					for ($i = 1; $i <= $quantidadelinha; $i++) {
						if(${"quantidade$i"}>0){

							$sql = "SELECT ICMS, Sigla FROM icms, produto WHERE produto.IdProduto = '${"idproduto$i"}' AND icms.IdIcms = produto.IdIcms";
							//echo $sql;
							$rs = $con->executeQuery ($sql);
							if($rs->next()) {
								$icms = $rs->get(0);
							if($rs->get(1) == 'II')
								$cst = '040';
							else if($rs->get(1) == 'FF')
								$cst = '060';
							else if($rs->get(1) == 'NN')
								$cst = '041';
							else
								$cst = espacoVazioEsquerda($cst, 3);
							}
							$rs->close();
					
							$sql = "SELECT Codigo FROM classificacao, produto WHERE produto.IdProduto = '${"idproduto$i"}' AND classificacao.IdClassificacao = produto.IdClassificacao";
							//echo $sql;
							$rs = $con->executeQuery ($sql);
							if($rs->next()) {
								$ncm = $rs->get(0);
							}
							$rs->close();

							$idproduto = espacoVazioDireita(${"idproduto$i"}, 13);
							$descricao = espacoVazioDireita(substr(${"descricao$i"}, 0, 37), 38);
							$quantidade = espacoVazioEsquerda(sprintf("%5.3f",${"quantidade$i"}), 9);
							$precovenda = espacoVazioEsquerda(sprintf("%9.3f",${"precovenda$i"}), 13);
							if($icms == '0.00')
								$icms = espacoVazioEsquerda(sprintf("%2.2f",$icms), 5);
							else
								$icms = sprintf("%2.2f",$icms);
							$siglaunidade = ${"siglaunidade$i"};
							$aspas = '"';
					
							if($jetloja == 1 && $solucao == 0)
								fwrite($arquivo, "$aspas$idproduto$aspas,$quantidade,$precovenda,0.00,$aspas$descricao$aspas,$aspas$siglaunidade$aspas,$icms,0.00,$aspas$cst$aspas,0.00,0.000,$aspas M$aspas,$aspas $aspas,$aspas $aspas,$aspas$ncm$aspas\n");
							elseif($jetloja == 0 && $solucao == 1)
								fwrite($arquivo, "$idproduto$quantidade$precovenda     0.00$descricao$siglaunidade$icms 0.00$cst  0.00   0.000M                                  $ncm\n");

						}
					}
				}
				fclose($arquivo);
			}
 //}
			else {
				$isLimiteCredito = TRUE;
				$imprimir = "";	
			}
			
			}elseif($tipovenda == "SINAL"){
					 
				$sql = "SELECT MAX(IdSinal) FROM sinal";
				$rs = $con->executeQuery($sql);
				if($rs->next())
					$idsinal = $rs->get(0) + 1;
				$rs->close();

				$sql = "INSERT INTO sinal (IdSinal, Sinal, Data, Hora, Observacao, Baixa, Ativo)";
				$sql = "$sql VALUES ('$idsinal', '$sinal', '$data', '$hora', '$observacao', 0, 1)";
				//echo $sql;
				if($con->executeUpdate($sql) == 1) {
					$isSinal = TRUE;
				}

				$sql = "INSERT INTO sinal_receituario (IdSinal, IdReceituario)";
				$sql = "$sql VALUES ('$idsinal', '$idreceituario')";
				//echo $sql;
				if($con->executeUpdate($sql) == 1) {
					$isSinal = TRUE;
				}
			}
		}

		if($imprimir) {
			include("imprimir_venda_goldsystem.php");
		}

		if($imprimirconferencia) {
			include("imprimir_venda_goldsystem.php");
			//echo '<script language="javascript">window.open("../impressao/conferencia_finalizavenda.txt", "Popup",  "width=600, height=600");</script>';
		}

		if($imprimircheque) {
		//	echo '<font color="red">teste</font>';
			$dataprevistaimp = $anoprevistoimp.'-'.$mesprevistoimp.'-'.$diaprevistoimp;
			$valorchequeimp = $valorchequeimp;
			include_once('../../caixa/venda/imprimirCheque.php');
			
			unset($imprimircheque);
		}

if($funcionario[nivel] >= 1) {
  ?>
  <form name="formRealizaVenda" action="realizaVendaAjax.php" method="POST">
  </form>

  <form name="formCaixa" action="../../caixa/venda/recebeVenda.php" method="POST">
  </form>

  <form name="formFinalizaVenda" action="<? echo $PHP_SELF; ?>" method="POST">
    <center>

      <font class = "subtitulo"><b>Finaliza <? if($idreceituario) echo 'Receituário'; else echo 'Venda'; ?></b></font><p>
      <?
      if($isLimiteCredito)
        echo 'Atenção crédito do cliente menor que o total de venda parcelado!!!!';
      if($isFinalizaVenda && $isParcela && $isUpdateVendaSituacao){
        echo 'Número da Venda ' . $idvenda;
        echo '<font class="mensagem">Venda realizada com sucesso!</font><p><input type="button" name="submeteNovaVenda" value="Nova Venda" onClick="submeterNovaVenda();"><p> <p><input type="button" name="submeteCaxa" value="Recebe Venda" onClick="submeterCaixa();"><p>';

      }
      else {
        if($idreceituario){
          ?>
          <table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
            <tr height="25" bgcolor="#777777">
              <td>

                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <td width = "800" align = "center">
                      <input type="radio" name="tipovenda" value="VENDA" <? if($tipovenda == "VENDA") echo "Checked"; ?> onclick="submit();"><font class="titulo_tabela">Venda</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="radio" name="tipovenda" value="SINAL" <? if($tipovenda == "SINAL") echo "Checked"; ?> onclick="submit();"><font class="titulo_tabela">Sinal</font>
                    </td>
                  </tr>
                </table>

              </td>
            </tr>
          </table><p>
          <?
        }else{
          ?>
          <table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
            <tr height="25" bgcolor="#777777">
              <td>
                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <td width = "800" align = "center">
                      <font class="titulo_tabela">Venda</font>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table><p>
          <?
        }

        if($quantidadelinha && $tipovenda == 'VENDA') {
          ?>
          <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
            <tr height="30">
              <td>
                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr height="25" bgcolor="#777777">
                    <td width = "800" align = "center">
                      <font class = "titulo_tabela">
                        <b>Total Ítens Saída</b>&nbsp;&nbsp;
                        <?
                        for($j = 1; $j <= $quantidadeunidade; $j++) {
                          if(${"somaitenstotal$j"} != 0){
                            printf("%1.2f", ${"somaitenstotal$j"}); echo " " . ${"siglaunidadevenda$j"} . "    ";
                          }
                          ?>
                          <input type = "hidden" name = "somaitenstotal<? echo $j; ?>" value = "<? printf("%1.2f", ${"somaitenstotal$j"}); ?>">
                          <input type = "hidden" name = "idunidadevenda<? echo $j; ?>" value = "<? echo ${"idunidadevenda$j"}; ?>">
                          <input type = "hidden" name = "siglaunidadevenda<? echo $j; ?>" value = "<? echo ${"siglaunidadevenda$j"}; ?>">
                          <?
                        }
                        ?>
                      </font>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <font class = "titulo_tabela">
                        <b>Total Ítens Devolvidos</b>&nbsp;&nbsp;
                        <?
                        for($j = 1; $j <= $quantidadeunidade; $j++) {
                          if(${"somaitenstotaldev$j"} != 0){
                            printf("%1.2f", ${"somaitenstotaldev$j"}); echo " " . ${"siglaunidadevenda$j"} . "    ";
                          }
                          ?>
                          <input type = "hidden" name = "idunidadevenda<? echo $j; ?>" value = "<? echo ${"idunidadevenda$j"}; ?>">
                          <input type = "hidden" name = "siglaunidadevenda<? echo $j; ?>" value = "<? echo ${"siglaunidadevenda$j"}; ?>">
                          <input type = "hidden" name = "somaitenstotaldev<? echo $j; ?>" value = "<? printf("%1.2f", ${"somaitenstotaldev$j"}); ?>">
                          <?
                        }
                        ?>
                      </font>
                    </td>
                  </tr>
                  <tr height="25" bgcolor="#777777">
                    <td width = "800" align = "center">
                      <font class = "titulo_tabela">
                        <b>Valor Total</b>&nbsp;&nbsp;
                        <? printf("%1.2f", $somavalortotal * $fator); ?>
                      </font>
                      <input type = "hidden" name = "somavalortotal" value = "<? printf("%1.2f", $somavalortotal); ?>">

                      <? if($sinal){?>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <b>Sinal</b>&nbsp;&nbsp;
                      <font class = "submenu_vertical">
                        <? printf("%1.2f", $sinal); ?>
                      </font>
                      <?}?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table><p>

          <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
            <tr>
              <td>
                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <td width = "800" align = "center">
                      <b>Observação</b><br>
                      <textarea name="observacao" rows="5" cols="90"><? echo $observacao; ?></textarea>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table><p>
          <?          
        }
        if($quantidadelinha && $tipovenda == 'VENDA') {
          ?>
          <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
            <tr height="25">
              <td>
                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <td width="400" align="center">

                      <b>Prazos</b>&nbsp;&nbsp;
                      <select name = "idtabelavenda" onChange="submeterPrazoPagamento();">
                        <option value = "">Selecione o Prazo de Pagto</option>
                        <?
                        $sql = "SELECT IdTabelaVenda, Prazo, Fator FROM tabela_venda WHERE Ativo = 1 AND IdLoja = '$funcionario[idloja]' ORDER BY Prazo";
                        $rs = $con->executeQuery ($sql);
                        while($rs->next()){
                          ?>
                          <option value = "<? echo $rs->get(0); ?>" <? if ($idtabelavenda == $rs->get(0)) echo "selected"; ?>><? echo $rs->get(1); ?></option>
                          <?
                        }
                        $rs->close();
                        ?>
                      </select>
                      <input type = "hidden" name = "fator" value = "<? echo $fator; ?>">
                    </td>
                    <td width="400" align="center">

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table><p>
          <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
            <tr height="25">
              <td>
                <table width="800" align="center" cellpadding="2" cellspacing="2">
                  <tr>
                    <?
                    if($descontotela == '1'){
                      ?>
                      <td width="270" align="center">
                        <b>Desc/Acresc (%)</b>&nbsp;&nbsp;
                        <input type="text" name="porcentagemdesconto" value="<? if($porcentagemdesconto) printf("%1.2f", $porcentagemdesconto); ?>" size="6" maxlength="6" onChange="submeterDescontoPorcentagem();">
                      </td>
                      <td width="270" align="center">
                        <b>Desc/Acresc (R$)</b>&nbsp;&nbsp;
                        <input type = "text" name = "valordesconto" value = "<? if($valordesconto) printf("%1.2f", $valordesconto); ?>" size = "8" maxlength = "8" onChange = "submeterDescontoValor();">
                      </td>
                      <?
                    }
                    ?>
                    <td width = "260" align = "center">
                      <font class = "subtitulo">
                        <b>Valor Final</b>&nbsp;&nbsp;
                        <? printf("%1.2f", ($valordesconto +  ($somavalortotal * $fator))); ?>
                        <? $valorfinal = round (($valordesconto + ($somavalortotal * $fator)), 2); ?>
                        <input type = "hidden" name = "valorfinal" value = "<? printf("%1.2f", $valorfinal); ?>">
                      </font>
                    </td>
                  </tr>
                  <?
                  if($porcentagemdesconto < $limitedesconto && !$gerarsenha){
                    ?>
                    <tr>
                      <td width="800" align="center" colspan="3">
                        <font color="red" size="4">
                          <b>Senha</b>&nbsp;&nbsp;&nbsp;
                          <input type="password" name="senhadesconto" size="6" maxlength="6" title="Entre com a senha." onChange = "submeterSenha();">
						  <?php if($isErroSenha) echo $isErroSenha;?>
                        </td>
                      </tr>
                      <?
                    }
                    ?>
                  </table>
                </td>
              </tr>
            </table><p>

            <?
            if($idtabelavenda) {
              if($isAlteracaoQuantidadeParcela) echo '<font class="mensagem">Quantidade de Parcelas alterada conforme definição na forma de pagamento.</font>';
              ?>
              <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
                <tr align="center" height="25">
                  <td>

                    <table width="800" align="center" cellpadding="2" cellspacing="2">
                      <?
                      if($quantidadeparcelapermitida > 1) {
                        ?>
                        <tr>
                          <td width="190" align = "center">
                            <b>Qtde Parcelas</b>&nbsp;&nbsp;

                            <input type = "text" name = "quantidadeparcela" value = "<? if ($quantidadeparcela){ echo $quantidadeparcela;}else{echo $quantidadeparcelapermitida, $quantidadeparcela ;} ?>" size = "2" maxlength = "2" onChange="limparParcelas();">
                            <input type = "hidden" name = "quantidadeparcelapermitida" value = "<? echo $quantidadeparcelapermitida; ?>">
                          </td>
                          <td width = "250"></td>
                          <td width = "200" align = "center">
                            <b>1º Vencto</b>&nbsp;&nbsp;
                            <? if($primeiracompra_status == 1){?>
                            <input type = "text" name = "primeiraparcela" value = "00" size = "3" maxlength = "3" readonly="yes"  onclick="submeterAvisoStatus();">
                            <?}else if($primeiracompra == 1){?>
                            <input type = "text" name = "primeiraparcela" value = "00" size = "3" maxlength = "3" readonly="yes" onclick="submeterAviso();">
                            <?}else{?>
                            <input type = "text" name = "primeiraparcela" value = "<? if ($primeiraparcela){ echo $primeiraparcela;}else{echo 0, $primeiraparcela ;} ?>" size = "3" maxlength = "3">
                            <?}?>
                            <input type = "hidden" name = "limite" value = "<? echo $limite; ?>">
                            <input type = "hidden" name = "limitea" value = "<? echo $limitea; ?>">
                          </td>
                          <td width="250" align = "center">
                            <b>Demais Venctos</b>&nbsp;&nbsp;
                            <input type = "text" name = "demaisparcelas" value = "<? echo $demaisparcelas; ?>" size = "3" maxlength = "3" disabled>
                            <input type = "hidden" name = "demaisparcelas" value = "<? echo $demaisparcelas; ?>">
                            <input type = "hidden" name = "limiteb" value = "<? echo $limiteb; ?>">
                          </td>
                        </tr>
                        <?
                      }
                      else {
                        ?>
                        <tr>
                          <td width="190" align = "center">
                            <b>Qtde Parcelas</b>&nbsp;&nbsp;

                            <input type = "text" name = "quantidadeparcela" value = "<? if ($quantidadeparcela){ echo $quantidadeparcela;}else{echo $quantidadeparcelapermitida, $quantidadeparcela ;} ?>" size = "2" maxlength = "2"onChange="limparParcelas();">
                            <input type = "hidden" name = "quantidadeparcelapermitida" value = "<? echo $quantidadeparcelapermitida; ?>">
                          </td>
                          <td width = "250"></td>
                          <td width = "200" align = "center">
                            <b>1º Vencto</b>&nbsp;&nbsp;
                            <? if($primeiracompra_status == 1){?>
                            <input type = "text" name = "primeiraparcela" value = "00" size = "3" maxlength = "3" readonly="yes"  onclick="submeterAvisoStatus();">
                            <?}else if($primeiracompra == 1){?>
                            <input type = "text" name = "primeiraparcela" value = "00" size = "3" maxlength = "3" readonly="yes"  onclick="submeterAviso();">
                            <?}else{?>
                            <input type = "text" name = "primeiraparcela" value = "<? if ($primeiraparcela){ echo $primeiraparcela ;}else if($primeiracompra == 1){echo 1;}else{ echo $primeiraparcela;}?>" size = "3" maxlength = "3">
                            <?}?>
                            <input type = "hidden" name = "limite" value = "<? echo $limite; ?>">
                            <input type = "hidden" name = "limitea" value = "<? echo $limitea; ?>">
                            <input type = "hidden" name = "limiteb" value = "<? echo $limiteb; ?>">
                          </td>
                        </tr>
                        <?
                      }
                      ?>
                    </table>
                  </td>
                </tr>
              </table><p>
              <?

              If($porcentagemdesconto < -100.00)
              echo "Desconto não permitido!";
              else{
                if($porcentagemdesconto < $limitedesconto){
                  if($gerarsenha && $quantidadeparcelapermitida > 0) echo '<input type = "button" name = "submeteGeracaoParcela" value = "Gerar Parcela(s)" onClick = "submeterGeracaoParcela();"><p>';
                }
                else{
                  if($quantidadeparcelapermitida > 0) echo '<input type = "button" name = "submeteGeracaoParcela" value = "Gerar Parcela(s)" onClick = "submeterGeracaoParcela();"><p>';
                }
              }

              if($quantidadeparcela && (substr($datavencimento1, 0, 2) > 0)) {
                ?>
                <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>

                      <table width="800" align="center" cellpadding="2" cellspacing="2">
                        <tr height="25" bgcolor="#777777">
                          <td width = "80" align = "center"><font class="titulo_tabela">Parcela</font></td>
                          <td width = "250" align = "center"><font class="titulo_tabela">Vencimento</font></td>
                          <td width = "100" align = "center"><font class="titulo_tabela">Valor</font></td>
                          <td width = "200" align = "center"><font class="titulo_tabela">Forma Pagamento</font></td>
                        </tr>
                        <? for($i = 1; $i <= $quantidadeparcela; $i++) { ?>
                          <tr <? if(${"erro$i"}){?> bgcolor="#FF0000"<?}?>>
                            <? if($i == 1){ ?>
                              <td width = "80" align = "center"><? echo $i . "ª"; ?></td>
                              <td width = "250" align = "center">
                                <input type = "text" name = "diavencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 8, 2); ?>" size = "2" maxlength = "2" readonly="yes" />/
                                <input type = "text" name = "mesvencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 5, 2); ?>" size = "2" maxlength = "2" readonly="yes" />/
                                <input type = "text" name = "anovencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 0, 4); ?>" size = "4" maxlength = "4" readonly="yes" />
                              </td>
                              <? } else { ?>
                              <td width = "80" align = "center"><? echo $i . "ª"; ?></td>
                              <td width = "170" align = "center">
                                <input type = "text" name = "diavencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 8, 2); ?>" size = "2" maxlength = "2">/
                                <input type = "text" name = "mesvencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 5, 2); ?>" size = "2" maxlength = "2">/
                                <input type = "text" name = "anovencimento<? echo $i; ?>" value = "<? echo substr(${"datavencimento$i"}, 0, 4); ?>" size = "4" maxlength = "4">
                              </td>
                              <? } ?>
                            <td width = "100" align = "center">
                              <? if($i < $quantidadeparcela) { ?>
                                <input type = "text" name = "valorparcela<? echo $i; ?>" value = "<? printf("%1.2f", ${"valorparcela$i"}); ?>" size = "8" maxlength = "8" onChange = "submeterRecalculoParcela('<? echo $i; ?>');">
                              <? } else { ?>
                                <? printf("%1.2f", ${"valorparcela$i"}); ?>
                                <input type = "hidden" name = "valorparcela<? echo $i; ?>" value = "<? printf("%1.2f", ${"valorparcela$i"}); ?>">
                                <?
                              }
                              ?>
                            </td>
                            <td width="180" align = "left">
                              <? if($i == 1){ ?>
                              <select name = "idformapagamentoparcela<? echo $i; ?>" onchange="submeterTodasPArcelas();">
                                <? } else { ?>
                                <select name = "idformapagamentoparcela<? echo $i; ?>" onchange="submeterParcelas(<? echo $i; ?>);">

                                  <?  } ?>
                                  <?
                                  $sql = "SELECT IdFormaPagamento, Nome FROM forma_pagamento WHERE Ativo = 1";
                                  if(${"valorparcela$i"} < 0){
                                    $sql = "$sql AND Aberto = 1 OR Dinheiro = 1";
								  }
                                  if($i > 1)
                                    $sql = "$sql AND Metal = 0";
                                  $rs = $con->executeQuery ($sql);
                                  while ($rs->next()) {
                                    ?>
                                    <option value = "<? echo $rs->get(0); ?>" <? if(${"idformapagamentoparcela$i"} == $rs->get(0)) echo "selected"; ?>><? echo $rs->get(1); ?></option>
                                    <?
                                  }
                                  $rs->close();
                                  ?>
                                </select>
                              </td>
                              <td width="20" align = "center" title="Verifique as possíveis causas: &#13; - Limite de prazo entre uma parcela e outra; &#13; - Data inferior a data de hoje; &#13; - Dias 31 para os meses de fevereiro, abril, junho, setembro e novembro; &#13; - Dia 30 para o mês de fevereiro; &#13; - Dia 29 para o mês de fevereiro sendo um ano não bissexto.">
                                <font size="4" style="font-weight: bold;"><? if(${"erro$i"}){?> ? <?}?></font>
                              </td>
                            </tr>
                            <?php if(${"metal$i"} == 1){  ?>
                            <tr>
                             <td width = "330" align = "center" colspan = "2"></td>
                             <td width = "100" align = "center">
                              <input type = "text" name = "valorparcelacotacao<? echo $i; ?>" value = "<? printf("%1.2f", ${"valorparcelacotacao$i"}); ?>" size = "8" maxlength = "8" disabled>
                              <input type = "hidden" name = "valorparcelacotacao<? echo $i; ?>" value = "<? printf("%1.2f", ${"valorparcelacotacao$i"}); ?>">
                              <input type = "hidden" name = "metal<? echo $i; ?>" value = "<? echo ${"metal$i"}; ?>">
                            </td>
                            <td width="180" align = "left">
                             <select name = "idcotacaometal<? echo $i; ?>" onchange="submeterCotacao(<? echo $i; ?>);">
                               <option value = ""></option>
                               <?
                               $sql = "SELECT IdCotacaoMetal, Valor, Nome FROM cotacao_metal, moeda WHERE IdLoja = '$funcionario[idloja]' AND cotacao_metal.IdMoeda = moeda.IdMoeda AND IdLoja = '$funcionario[idloja]' AND cotacao_metal.Ativo = 1";
                               $rs = $con->executeQuery ($sql);
                               while ($rs->next()) {
                                ?>
                                <option value = "<? echo $rs->get(0); ?>" <? if(${"idcotacaometal$i"} == $rs->get(0)) echo "selected"; ?>><? echo $rs->get(2) . "-" . $rs->get(1); ?></option>
                                <?
                              }
                              $rs->close();
                              ?>
                            </select>
                          </td>

                        </tr>
                        <?
                      }
                    }
                    ?>
                  </table>
                </td>
              </tr>
            </table>
			
			<br/>
<?php
			if($cheque){
?>
			<table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
				<tr>
                    <td>
						<table width="800" align="center" cellpadding="2" cellspacing="2">
							<tr height="25" bgcolor="#777777">
								<td width = "800" align = "center" colspan="4"><font class="titulo_tabela">Imprimir Cheque</font></td>
							</tr>	
							<tr>
								<td width="100" align="right">
									<b>Valor Cheque</b>
								</td>
								<td width="300" align="left">
									<input type="text" name="valorchequeimp" value="<?php echo $valorchequeimp; ?>" size="10" maxlength="12"/>
								</td>
								<td width="100" align="right">
									<b>Data Prevista</b>
								</td>
								<td width="300" align="left">
									<input type="text" name="diaprevistoimp" value="<?php echo $diaprevistoimp?>" size="2" maxlength="2"/>/
									<input type="text" name="mesprevistoimp" value="<?php echo $mesprevistoimp?>" size="2" maxlength="2"/>/
									<input type="text" name="anoprevistoimp" value="<?php echo $anoprevistoimp?>" size="4" maxlength="4"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			
			<br />
			
			<input type="button" name="submeteImpressaoCheque" value="ImprimirCheque" onClick="submeterImpressaoCheque();">
			
			<br />
			<br />
<?php
			}
?>
            <?
            if(($quantidadeparcela && (substr($datavencimento1, 0, 2) > 0)) ) {
              ?>

              <input type="button" name="submeteEncerramento" value="Encerrar" onClick="submeterEncerramento();"><p>
              <?

            }
          }
        }
      }             
      else if($tipovenda == "SINAL") {
        ?>
        <table width="800" align="center" border="1" cellpadding="0" cellspacing="0">
          <tr height="25">
            <td>
              <table width="800" align="center" cellpadding="2" cellspacing="2">
                <tr>
                  <td width="800" align="left" colspan="4">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>Sinal</b>&nbsp;&nbsp;
                    <input type = "text" name="sinal" value="<? printf("%1.2f", $sinal); ?>" size="10" maxlength="10">

                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table><p>
        <?
        if(($tipovenda == "SINAL") && !$idsinal) {
          ?>
          <input type="button" name="submeteEncerramento" value="Encerrar Sinal" onClick="submeterEncerramento();">
          <?
        }
      }
?>
		&nbsp;&nbsp;<input type="button" name="submeteImpressao" value="Imprimir Conferência" onClick="submeterImpressaoConferencia();"><p>
<?php
    }

    ?>
    <input type = "hidden" name = "filtrar">
    <input type = "hidden" name = "submeterdescontoporcentagem">
    <input type = "hidden" name = "submeterdescontovalor">
    <input type = "hidden" name = "submetersenha">
    <input type = "hidden" name = "submetersenha2">
    <input type = "hidden" name = "buscarformapagamento">
    <input type = "hidden" name = "buscarprazopagamento">
    <input type = "hidden" name = "gerarparcelas">
    <input type = "hidden" name = "recalcular">
    <input type = "hidden" name = "recalcularparcela">
    <input type = "hidden" name = "recalcularparceladias">
    <input type = "hidden" name = "finalizarvenda">
    <input type = "hidden" name = "imprimir">
    <input type = "hidden" name = "imprimirconferencia">
    <input type = "hidden" name = "conferencia">
    <input type = "hidden" name = "quantidadevenda">
    <input type = "hidden" name = "enviar">
    <input type = "hidden" name = "isVenda">
    <input type = "hidden" name = "submetetodasparcelas">
    <input type = "hidden" name = "submeteparcelas">
    <input type = "hidden" name = "calcularcotacao">
    <input type = "hidden" name = "limparparcelas">
    <input type = "hidden" name = "idfuncionario" value = "<? echo $idfuncionario; ?>">
    <input type = "hidden" name = "numerovenda" value = "<? echo $numerovenda; ?>">
    <input type = "hidden" name = "nomefuncionario" value = "<? echo $nomefuncionario; ?>">
    <input type = "hidden" name = "idvenda" value = "<? echo $idvenda; ?>">
    <input type = "hidden" name = "idcliente" value = "<? echo $idcliente; ?>">
    <input type = "hidden" name = "idunidade" value = "<? echo $idunidade; ?>">
    <input type = "hidden" name = "gerarsenha" value = "<? echo $gerarsenha; ?>">
    <input type = "hidden" name = "quantidadelinha" value = "<? echo $quantidadelinha; ?>">
    <input type = "hidden" name = "quantidadeunidade" value = "<? echo $quantidadeunidade; ?>">
    <input type = "hidden" name = "quantidadeindexadores" value = "<? echo $quantidadeindexadores; ?>">
    <input type = "hidden" name = "somaporcentagem" value = "<? echo $somaporcentagem; ?>">
    <input type = "hidden" name = "datacadastro" value = "<? echo $datacadastro; ?>">
    <input type = "hidden" name = "idtipobloqueio" value = "<? echo $idtipobloqueio; ?>">
    <input type = "hidden" name = "primeiracompra" value = "<? echo $primeiracompra; ?>">
    <input type = "hidden" name = "primeiracompra_status" value = "<? echo $primeiracompra_status; ?>">
    <input type = "hidden" name = "crediario" value = "<? echo $crediario; ?>">
    <input type = "hidden" name = "limitepermitido" value = "<? echo $limitepermitido; ?>">
    <input type = "hidden" name = "entradapermitido" value = "<? echo $entradapermitido; ?>">
    <input type = "hidden" name = "datavencimento1" value = "<? echo $datavencimento1; ?>">
    <input type = "hidden" name = "cheque" value = "<? echo $cheque; ?>">
    <input type = "hidden" name = "imprimircheque" value = "<? echo $imprimircheque; ?>">
    <input type = "hidden" name = "checardatas">

    <?
    for($i = 1; $i <= $quantidadelinha; $i++) {
      ?>

      <input type="hidden" name="idproduto<? echo $i; ?>" value="<? echo ${"idproduto$i"}; ?>">
      <input type="hidden" name="codigobarra<? echo $i; ?>" value="<? echo ${"codigobarra$i"}; ?>">
      <input type="hidden" name="descricao<? echo $i; ?>" value="<? echo ${"descricao$i"}; ?>">
      <input type="hidden" name="porcentagemproduto<? echo $i; ?>" value="<? echo ${"porcentagemproduto$i"}; ?>">
      <input type="hidden" name="precocusto<? echo $i; ?>" value="<? echo ${"precocusto$i"}; ?>">
      <input type="hidden" name="precoreal<? echo $i; ?>" value="<? echo ${"precoreal$i"}; ?>">
      <input type="hidden" name="precovenda<? echo $i; ?>" value="<? echo ${"precovenda$i"}; ?>">
      <input type="hidden" name="siglaunidade<? echo $i; ?>" value="<? echo ${"siglaunidade$i"}; ?>">
      <input type="hidden" name="siglamoeda<? echo $i; ?>" value="<? echo ${"siglamoeda$i"}; ?>">
      <input type="hidden" name="idindexador<? echo $i; ?>" value="<? echo ${"idindexador$i"}; ?>">
      <input type="hidden" name="quantidade<? echo $i; ?>" value="<? echo ${"quantidade$i"}; ?>">
      <input type="hidden" name="defeito<? echo $i; ?>" value="<? echo ${"defeito$i"}; ?>">
      <input type="hidden" name="descricaodefeito<? echo $i; ?>" value="<? echo ${"descricaodefeito$i"};  ?>">
      <?
    }
    for($j = 1; $j <= $quantidadeunidade; $j++) {
      ?>
      <input type="hidden" name="idunidadevenda<? echo $j; ?>" value="<? echo ${"idunidadevenda$j"}; ?>">
      <input type="hidden" name="siglaunidadevenda<? echo $j; ?>" value="<? echo ${"siglaunidadevenda$j"}; ?>">
      <input type="hidden" name="somaitenstotal<? echo $j; ?>" value="<? echo ${"somaitenstotal$j"}; ?>">
      <input type="hidden" name="somaitenstotaldev<? echo $j; ?>" value="<? echo ${"somaitenstotaldev$j"}; ?>">
      <?
    }
    for($m = 1; $m <= $quantidadeindexadores; $m++) {
      ?>
      <input type="hidden" name="idindexadorv<? echo $m; ?>" value="<? echo ${"idindexadorv$m"}; ?>">
      <input type="hidden" name="siglaindexadorv<? echo $m; ?>" value="<? echo ${"siglaindexadorv$m"}; ?>">
      <input type="hidden" name="totalindexadorv<? echo $m; ?>" value="<? echo ${"totalindexadorv$m"}; ?>">
      <?
    }
    ?>
    <center>
    </form>
    <?
  }
  else echo '<br><br><br><center><font class="titulo">Acesso Restrito!<p><br></font><font class="mensagem">O Usuário não tem permissão para executar esta funcionalidade!<font><p><a href="../../home.php"><input type="submit" name="submeterRetorno" value="Retornar"></a></center>';

  $con->close();
  ?>
</body>
</html>


