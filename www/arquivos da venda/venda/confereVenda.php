<?php
  session_start();
  $funcionario = $_SESSION["funcionario"];
  include ("../../sistema/includes/connection.inc");
  // abertura de conexão com o banco.
  $con = new Connection;
  $con->open();

  $idVenda = $_POST["id"];
  $query_venda = mysql_query("SELECT * FROM venda WHERE IdVenda = $idVenda AND fechada = 1 AND conferida = 0") or die(mysql_error());
  $rows = mysql_affected_rows();
  if($rows > 0) {
    $status = "ok";
    $query_conf_venda = mysql_query("SELECT IdNotaConferenciaVenda FROM nota_conferencia_venda WHERE IdVenda = $idVenda");
    $rows = mysql_affected_rows();
    if($rows > 0 ){
      $status = "aberta";
      $idnota = mysql_result($query_conf_venda,0,0);
    }
  }else{
    $fechada = mysql_result($query_venda,0,"fechada");
    $conferida = mysql_result($query, 0, "conferida");
    if($fechada != 1 && $conferida != 0){
      $status = "Venda em aberto";
    } else if($fechada != 1 && $conferida != 1) {
      $status = "Venda já conferida";
    }
  }
  $arr = array("status" => $status, "idnotaconferencia" => $idnota);
  echo json_encode($arr);
