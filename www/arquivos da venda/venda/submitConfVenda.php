<?php
  session_start();
  include ("../../sistema/includes/connection.inc");
  // abertura de conex�o com o banco.
  $con = new Connection;
  $con->open();  
  
  $id_nota = $_SESSION["id_nota"];
  $id_venda = $_SESSION["idVenda"];
  $sql = "UPDATE nota_conferencia_venda SET Fechada = '1' WHERE IdNotaConferenciaVenda = '$id_nota'";
  $query = mysql_query($sql) or die(mysql_error());
  $sql = "UPDATE venda SET conferida = '1' WHERE IdVenda = '$id_venda'";
  $query = mysql_query($sql);
  
  unset($_SESSION["id_nota"]);
  unset($_SESSION["idVenda"]);
