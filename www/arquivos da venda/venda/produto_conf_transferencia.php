<?php

  session_start();
  $funcionario = $_SESSION["funcionario"];
  $status = $_SESSION["status"];
  include ("../../sistema/includes/connection.inc");
  // abertura de conexão com o banco.
  $con = new Connection;
  $con->open();
  $status = "";
  function conferencia($pd_saida = array()){
    $idProduto = $pd_saida["idproduto"];
    $idGrade = $pd_saida["idgrade"];
    $quantidade = $pd_saida["quantidade"];
    $sql = "SELECT * FROM venda_produto WHERE IdProduto = $idProduto AND IdGrade = $idGrade AND Quantidade = '$quantidade' ";
    $query = mysql_query($sql) or die(mysql_error());
    $numrows = mysql_affected_rows();
    if ($numrows > 0) {
      $query = mysql_query("SELECT * FROM notaconferenciavenda_produto WHERE IdProduto = $idProduto AND IdGrade = $idGrade AND Quantidade = '$quantidade'");
      $rows = mysql_affected_rows();
      if($rows == 0){
        return true;
      } else {
        return false;
      }
    }
  }
  function checarProduto($pd_saida = array(),$loja){
    $idProduto = $pd_saida["idproduto"];
    $idGrade = $pd_saida["idgrade"];
    $sql = "SELECT * FROM estoque_grade WHERE IdLoja = $loja AND IdGrade = $idGrade ";
    $query = mysql_query($sql);
    $numrows = mysql_affected_rows();
    if ($numrows > 0) {
      $sql = "SELECT * FROM estoque WHERE IdLoja = $loja AND IdProduto = $idProduto";
      $query = mysql_query($sql);
      $numrows = mysql_affected_rows();
      if ($numrows > 0) {
        return true;
      } else {
        return "erro";
      }
    } else {
      return false;
    }

  }
  
  function totalMoeda($idNota) {
    $valor_total = 0;
    $sql = mysql_query("SELECT Quantidade, PrecoVenda FROM notaconferenciavenda_produto WHERE IdNotaConferenciaVenda = $idNota") or die(mysql_error());
    while($rs = mysql_fetch_array($sql)){
      $valor_total += $rs["Quantidade"] * $rs["PrecoVenda"]; 
    }
    return $valor_total;
  }

  $dados_produto = array(
    'idproduto' => $_POST["idproduto"],
    'idgrade' => $_POST["id_grade"],
    'precocusto' => $_POST["precocusto"] ,
    'precovenda' => $_POST["precovenda"],
    'quantidade' => $_POST["quantidade_"]
  );
  $data = date("Y-m-d");
  $hora = date("H:i:s");
  $loja = $funcionario["idloja"];
  $idVenda = $_SESSION["idVenda"];
  if($status == "terminado"){

  }else{
    if (conferencia($dados_produto)) {
      if(!isset($_SESSION["id_nota"])){
        echo "ai";
        $sql = "INSERT INTO nota_conferencia_venda(IdVenda,Data,Hora,Fechada) VALUES('$idVenda', '$data', '$hora', '0')";
        if ($con->executeUpdate($sql) == 1) {
          $IdNotaConferencia = mysql_insert_id();
          $_SESSION["id_nota"] = $IdNotaConferencia;
        }
      } else {
        $IdNotaConferencia = $_SESSION["id_nota"];
      }
      $sql = "INSERT INTO notaconferenciavenda_produto(IdNotaConferenciaVenda,IdProduto, IdGrade, PrecoCusto, PrecoVenda, Quantidade, Data, Hora)
      VALUES('".$IdNotaConferencia."','".$dados_produto["idproduto"]."','".$dados_produto["idgrade"]."','".$dados_produto["precocusto"]."','".$dados_produto["precovenda"]."','".$dados_produto["quantidade"]."','$data','$hora')";
      if($con->executeUpdate($sql) == 1){}
      $sql = "SELECT SUM(Quantidade) AS TotalQtd ,SUM(DISTINCT PrecoVenda) AS TotalCt FROM venda_produto WHERE IdVenda = $idVenda";
      $query = mysql_query($sql);
      $totalQtdOrigem = mysql_result($query,0,"TotalQtd");
      $totalCtOrigem = mysql_result($query,0,"TotalCt");
      $sql = "SELECT SUM(Quantidade) AS TotalQtd ,SUM(DISTINCT PrecoVenda) AS TotalCt FROM notaconferenciavenda_produto WHERE IdNotaConferenciaVenda = $IdNotaConferencia";
      $query = mysql_query($sql);
      $totalQtdDestino = mysql_result($query,0,"TotalQtd");
      $totalCtDestino = mysql_result($query,0,"TotalCt");
      if($totalQtdOrigem == $totalQtdDestino && $totalCtOrigem == $totalCtDestino){
        $_SESSION["status"] = "terminado";
      }else{
        $_SESSION["status"] = "andamento";
      }
    }else{
      $IdNotaConferencia = $_SESSION["id_nota"];
    }
  }
  $select_query = "
  SELECT 
    p.Descricao, p.Imagem, ncvp.PrecoVenda, ncvp.Quantidade, uv.Sigla AS unidadevenda, i.Sigla, g.CodigoBarra
  FROM
    produto AS p
  INNER JOIN
    notaconferenciavenda_produto AS ncvp ON ncvp.IdProduto = p.IdProduto
  INNER JOIN
    indexadores AS i ON p.IdMoeda = i.IdIndexador
  INNER JOIN
    unidade_venda AS uv ON p.IdUnidadeVenda = uv.IdUnidadeVenda
  INNER JOIN
    grade AS g ON g.IdGrade = ncvp.IdGrade
  WHERE ncvp.IdNotaConferenciaVenda =".$IdNotaConferencia;
  $exec = mysql_query($select_query) or die(mysql_error());
  $i = 1;

  $html = '
  <table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <table width="800" align="center" cellpadding="2" cellspacing="2">
          <tr bgcolor="#77777">
          <td width="800" height="25" align="center" colspan="8">
              <font class="titulo_tabela">Relação de Produtos</font>
            </td>
          </tr>
          <tr height="20">
            <td width="20" align="center">.</td>
            <td width="100" align="center"><b>Código</b></td>
            <td width="340" align="center"><b>Descrição</b></td>
            <td width="70" align="center"><b>Qtde</b></td>
            <td width="100" align="center"><b>Preço Venda</b></td>
            <td width="120" align="center"><b>Foto do Produto</b></td>
            <td width="50" align="center"><b>.</b></td>
          </tr>';
          while($rs = mysql_fetch_array($exec)) {
            $html .= '
            <tr>
              <td width="20" align="center">'.$i.'</td>
              <td width="100" align="center">'.$rs["CodigoBarra"].'</td>
              <td width="340" align="left">'.$rs["Descricao"].'</td>
              <td width="70" align="center">'.$rs["Quantidade"].' '. $rs["unidadevenda"].'</td>
              <td width="100" align="center">'.$rs["PrecoVenda"].' '.$rs["Sigla"].'</td>
              <td width="120" align="center"><img src="../../'.$rs["Imagem"].'" /></td>
              <td width="50" align="center">
                <a href="'.$rs["Quantidade"].'" id="" class="excluir" title="Excluir este produto da lista.">
                  <img src="../../sistema/images/lixo.gif" width="15" border="0">
                </a>
              </td>
            </tr>';
            $i++;
          } 
        $totalmoeda = totalMoeda($IdNotaConferencia);
        $html .= '
        </table>
        <br />
      <table align="center" cellpadding="2" cellspacing="2">
        <tr width="800" bgcolor="#777777">
            <td align="right">
              <b>Total:</b>'.$totalQtdDestino.' PC
              <input type = "hidden" name = "totalunidade" value = "">
            </td>
        </tr>
      </table>
      <br />
      <table align="center" cellpadding="2" cellspacing="2">
        <tr width="800" bgcolor="#777777">
          <td align="right">
            <b>Total Moeda:</b>'.$totalmoeda.' RR
            <input type = "hidden" name = "totalmoeda" value = "">
          </td>
        </tr>
      </table>
      </td>
    </tr>
  </table>
  <div id="final_transf" align="center">
  <input type="button" name="submeteImpressao" id="submeteImpressao" value="Imprimir">';
  if ($_SESSION["status"] == "terminado") { 
    $html .= '<input type="button" name="submeteFinalizacaoVenda" id="submeteFinalizacaoVenda" value="Finalizar Conferência">';  
  }
  $html .= '<input type="button" name="submeteNovaConsulta" value="Nova Conferência"><div id="exec_func"></div></div>';
  echo $html;