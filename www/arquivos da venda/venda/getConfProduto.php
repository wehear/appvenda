<?php
  session_start();
  $funcionario = $_SESSION["funcionario"];
  include ("../../sistema/includes/connection.inc");
  // abertura de conexão com o banco.
  $con = new Connection;
  $con->open();  
  //seleciona produto 
  $idVenda = $_POST["idVenda"];
  $codbarra = $_POST["codigobarra"];
  
  $sql = "
  SELECT 
    p.IdProduto,
    p.Descricao,
    vp.PrecoCusto,
    vp.PrecoVenda,
    g.IdGrade,
    uv.Sigla AS UnidadeVenda,
    ind.Sigla
  FROM
    venda_produto AS vp
        INNER JOIN
    produto AS p ON p.IdProduto = vp.IdProduto
        INNER JOIN
    unidade_venda AS uv ON uv.IdUnidadeVenda = p.IdUnidadeVenda
        INNER JOIN
    indexadores AS ind ON p.IdIndexador = ind.IdIndexador
        INNER JOIN
    grade AS g ON g.IdGrade = vp.IdGrade
  WHERE
    g.CodigoBarra = '$codbarra' AND
    vp.IdVenda = $idVenda";
  $exec = mysql_query($sql) or die(mysql_error());
  $product = mysql_fetch_array($exec);
  $data = array(
    "idproduto" => $product["IdProduto"],
    "precocusto" => $product["PrecoCusto"],
    "descricao" => $product["Descricao"],
    "precovenda" => $product["PrecoVenda"],
    "codigobarra" => $product["CodigoBarra"],
    "siglamoeda" => $product["Sigla"],
    "siglaunidade" => $product["UnidadeVenda"],
    "idgrade" => $product["IdGrade"]
  );
  if(!($_SESSION["idVenda"] == $idVenda)){
    $_SESSION["idVenda"] = $idVenda;
  }
  echo json_encode($data);