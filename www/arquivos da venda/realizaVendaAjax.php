<?
session_start();
$funcionario = $_SESSION[funcionario];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
  "http://www.w3.org/TR/html4/loose.dtd">
<?

include_once ("../../sistema/includes/connection.inc");
require_once ("../../sistema/includes/funcoes.php");
if(isset($_GET['teste']) && $_GET['teste'] != session_id()){
  $isErroSessao = true;
}
?>
<html>
<head>
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/titulos.css">
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/padroes.css">
  <link rel = "stylesheet" type = "text/css" href = "../../sistema/includes/links.css">
  <script type='text/javascript' src='../../js/jquery-1.8.3.min.js'></script>
  <title>Realiza Venda</title>
  <script language="JavaScript">
	$(document).ready(function(){
			
		function checkNumber(valor) {
			var regra = /^[0-9]+$/;
			if (valor.match(regra)) {
				return true;
			}else{
				return false;
			}
		}; 

		$("#spn_senha").hide();
		$("#numerovenda").focus();
		$("#idtabelavenda").val(1);

/* 		$.post("../../ajax/cliente/buscarClienteId.php",{
            idcliente : 1,
            acao : "busca_cliente",
        }, function (retorno){
            busca = retorno.split("|");
            $("#idcliente").val(busca[0]);
            $("#nomeconsulta").val(busca[1]);
            $("#valorcredito").val(busca[12]);
            $("#saldovale").val(busca[13]);
        }); */

		$("#idfuncionario").blur(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/funcionario/funcionario.php",{
						idfuncionario : $("#idfuncionario").val(),
						acao : "busca_funcionario",
					}, function (retorno){
						busca = retorno.split("|");
						$("#numerovenda").val(busca[15]);
						if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
							$("#abertura_venda").show();
							$("#abrirVenda").focus();
						}else if($("#idfuncionario").val() > 0){
							$("#idtabelavenda").focus();
						}
						if(busca[16])
							$("#msg").html(busca[16]);
						
						if($("#idvenda").val()){
							$.post("../../ajax/venda/alterarFuncionario.php",{
								idfuncionario : $("#idfuncionario").val(),
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val()
							}, function (retorno){
								busca = retorno.split("|");
								//alert(retorno);
								if(retorno){
									alert(busca[0]);
									$("#idfuncionario").val(busca[1]);
									$("#numerovenda").val(busca[2]);
								}
							});
						}
					});
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}
			});
		});

		if($("#idvendareabre").val() > 0){
		
			//$("#numerovenda").attr('readonly', true);
			//$("#idfuncionario").attr('disabled', true);
						
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				$.post("../../ajax/venda/buscaVenda.php",{
				idvenda : $("#idvendareabre").val(),
				}, function (retorno){
					busca = retorno.split("|");
					$("#idcliente").val(busca[1]);
					$("#idfuncionario").val(busca[2]);
					$("#crediario").val('0');
					$("#idtabelavenda").val('1');
					$("#fator").val(busca[5]);
					$.post("../../ajax/funcionario/funcionario.php",{
						idfuncionario : $("#idfuncionario").val(),
						acao : "busca_funcionario",
					}, function (retorno){
						busca = retorno.split("|");
						$("#numerovenda").val(busca[15]);
					});

					$.post("../../ajax/cliente/buscarClienteId.php",{
						idcliente : $("#idcliente").val(),
						acao : "busca_cliente",
					}, function (retorno){
						busca = retorno.split("|");
						$("#idcliente").val(busca[0]);
						$("#nomeconsulta").val(busca[1]);
						$("#valorcredito").val(busca[12]);
						$("#saldovale").val(busca[13]);
						$("#saldoconta").html(busca[16]);
					});
					if(busca[0]){
						$("#idvenda").val(busca[0]);
						$.post("../../ajax/venda/buscarProdutoVenda.php",{
							idvenda : $("#idvenda").val(),
							idcliente : $("#idcliente").val(),
							venda : '1',
							fator : '1',
						}, function (retorno){
							$("#tab_produto").html(retorno);
							$("#produto").show();
							$("#abertura_venda").hide();
						});
					}
				});
			});
		}


	/* 	$('input:checkbox').keypress(function(e){
    if((e.keyCode ? e.keyCode : e.which) == 13){
        $(this).trigger('click');
    }
}); */





		$("#numerovenda").change(function(){
			$("#msg").html('');
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/funcionario/funcionario.php",{
						numerovenda : $("#numerovenda").val(),
						acao : "busca_numerovenda",
					}, function (retorno){
						busca = retorno.split("|");
						$('#idfuncionario option[value='+busca[15]+']').attr('selected','selected');
						
						var idfuncionario = busca[15];
						
						if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
							$("#abertura_venda").show();
							$("#abrirVenda").focus();
						}else if($("#idfuncionario").val() > 0){
							$("#idtabelavenda").focus();
						}
						if(busca[16])
							$("#msg").html(busca[16]);
							
						if($("#idvenda").val()){
							$.post("../../ajax/venda/alterarFuncionario.php",{
								idfuncionario : idfuncionario,
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val()
							}, function (retorno){
								busca = retorno.split("|");
								//alert(retorno);
								if(retorno){
									alert(busca[0]);
									$("#idfuncionario").val(busca[1]);
									$("#numerovenda").val(busca[2]);
								}
							});
						}
					});
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}

			});
		});

		$("#idfuncionario").change(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/funcionario/funcionario.php",{
						idfuncionario : $("#idfuncionario").val(),
						acao : "busca_funcionario",
					}, function (retorno){
						busca = retorno.split("|");
						$("#numerovenda").val(busca[15]);
						if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
							$("#abertura_venda").show();
							$("#abrirVenda").focus();
						}else if($("#idfuncionario").val() > 0){
							$("#idtabelavenda").focus();
						}
						if(busca[16])
							$("#msg").html(busca[16]);
						
						if($("#idvenda").val()){
							$.post("../../ajax/venda/alterarFuncionario.php",{
								idfuncionario : $("#idfuncionario").val(),
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val()
							}, function (retorno){
								busca = retorno.split("|");
								//alert(retorno);
								if(retorno){
									alert(busca[0]);
									$("#idfuncionario").val(busca[1]);
									$("#numerovenda").val(busca[2]);
								}
							});
						}
					});
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}
			});
		});

		$("#idtabelavenda").change(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/funcionario/funcionario.php",{
						idfuncionario : $("#idfuncionario").val(),
						acao : "busca_funcionario",
					}, function (retorno){
						busca = retorno.split("|");
						$("#numerovenda").val(busca[15]);
						if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
							$("#abertura_venda").show();
							$("#abrirVenda").focus();
						}else if($("#idfuncionario").val() > 0){
							$("#idcliente").focus();
						}
						if(busca[16])
						$("#msg").html(busca[16]);
					});
					
					if($("#idvenda").val() > 0){
						$.post("../../ajax/venda/alterarTabelaVenda.php",{
							idtabelavenda : $("#idtabelavenda").val(),
							idvenda : $("#idvenda").val(),
						}, function (retorno){
							$.post("../../ajax/venda/buscarProdutoVenda.php",{
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val(),
								venda : '1',
								fator : '1',
							}, function (retorno){
								$("#tab_produto").html(retorno);
								$("#produto").show();
							});
						});
					}
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}
			});
		});

		$("#codigocliente").change(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){

				if(retorno){
					$.post("../../ajax/cliente/buscarClienteId.php",{
						idcliente : $("#codigocliente").val(),
						acao : "busca_cliente",
					}, function (retorno){
						busca = retorno.split("|");
						$("#idcliente").val(busca[0]);
						$("#nomeconsulta").val(busca[1]);
						$("#valorcredito").val(busca[12]);
						$("#saldovale").val(busca[13]);
						$("#saldoconta").html(busca[16]);

						if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
							$("#abertura_venda").show();
							$("#abrirVenda").focus();
						}

						if(busca[14])
							$("#msg").html(busca[14]);
					});
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}
			});
		});

/* $("#precovenda").change(function(){
  $("#quantidade").focus();
});
 */


		$("#idcliente").change(function(){
		
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){

				$.post("../../ajax/cliente/buscarClienteId.php",{
					idcliente : $("#idcliente").val(),
					acao : "busca_cliente",
				}, function (retorno){
					//alert(retorno);
					busca = retorno.split("|");
					$("#idcliente").val(busca[0]);
					$("#nomeconsulta").val(busca[1]);
					$("#valorcredito").val(busca[12]);
					$("#saldovale").val(busca[13]);
					$("#saldoconta").html(busca[16]);

					if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
						$("#abertura_venda").show();
						$("#abrirVenda").focus();
					}else if(!$("#idfuncionario").val()){
						$("#numerovenda").focus();
					}
					
					if($("#idvendareabre").val()){
						$.post("../../ajax/venda/verificarClienteReabre.php",{
							idvenda : $("#idvenda").val(),
							idcliente : $("#idcliente").val(),
							idtabelavenda : $("#idtabelavenda").val(),
							fator : '1',
						}, function (retorno){
						
							//alert(retorno);
							
								//$("#tab_produto").html(retorno);
						 	$.post("../../ajax/venda/buscarProdutoVenda.php",{
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val(),
								venda : '1',
								fator : '1',
							}, function (retorno){
								$("#tab_produto").html(retorno);
								$("#produto").show();
								$("#abertura_venda").hide();
							});
						});
					}
					
					if(busca[14])
						$("#msg").html(busca[14]);
				});
			});
		});

		$("#nomeconsulta").change(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				$.post("../../ajax/cliente/buscarClienteCmb.php",{
					nomeconsulta : $("#nomeconsulta").val(),
					acao : "busca_combo",
				}, function (retorno){
					busca = retorno.split("|");
					$("#cmb_cliente").html(busca[0]);

					if(busca[1])
						alert(busca[1]);
					else
						$("#codigocliente").focus();
				});
			});
		});

		$("#cpfconsulta").change(function(){
			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				$.post("../../ajax/cliente/buscarClienteCPF.php",{
					cpfcliente : $("#cpfconsulta").val(),
					acao : "busca_cliente",
				}, function (retorno){
					//alert(retorno);
					busca = retorno.split("|");
					$("#idcliente").val(busca[0]);
					$("#nomeconsulta").val(busca[1]);
					$("#valorcredito").val(busca[12]);
					$("#saldovale").val(busca[13]);

					if(($("#idfuncionario").val() > 0) && (busca[0] > 0) && ($("#idtabelavenda").val() > 0) && (!$("#idvenda").val())){
						$("#abertura_venda").show();
						$("#abrirVenda").focus();
					}else if(!$("#idfuncionario").val()){
						$("#numerovenda").focus();
					}
					if(busca[14])
						$("#msg").html(busca[14]);
				});
			});
		});

$("#referenciaconsulta").change(function(){
  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){
    $.post("../../ajax/buscaProduto.php",{
      codigo_fornecedor : $("#referenciaconsulta").val(),
      acao : "busca_codigo_fornecedor_ajax",
    }, function (retorno){
      busca = retorno.split("|");
      $("#cmb_idprodutoref").html(busca[0]);
      $("#novabusca").show();
      $("#idprodutobusca").focus();
      if(busca[1])
        alert(busca[1]);
    });
  });
});


	/* $("#defeito").change(function(){
		if($("#defeito").is(":checked"))
			$("#div_defeito").show();
		else
			$("#div_defeito").hide();
	}); */

$("#buscaProduto").click(function(){

	//var idsecao = $("#idsecao").val();
	//var idfamilia = $("#idfamilia").val();
	var idvenda = $("#idvenda").val();
	var fator = $("#fator").val();

	window.open('../../estoque/produto/cadastroProdutoValeriano.php?id='+idvenda+"&tela=venda&fator="+fator,'popup','scrollbars=yes, width=1020, height=600');

});


		$("#abandonar").click(function(){
			$("#abandonar").hide();
			$("#tab_produto").hide();
			$("#produto").hide();
			

			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/venda/cancelarVenda.php",{
						idvenda : $("#idvenda").val(),
						acao : 'abandonar',
					}, function (retorno){
						if(retorno){
							document.formRealizaVenda.submit();
						}else{
							alert('Erro ao abandonar a venda! Tente novamente, caso persista entrar em contato com o administrador!');
						}
					});
				}else{
					alert('Erro de sessão! Sair do sistema e logar novamente!');
				}
			});
		});

$("#novabusca").click(function(){
  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){
    $("#cmb_idprodutoref").html('');
    $("#referenciaconsulta").val('');
    $("#novabusca").hide('');
    $("#idproduto").val('');
    $("#idgrade").val('');
    $("#quantidade").val('');
    $("#precovendaorig").val('');
    $("#descontoProduto").val('');
    $("#precovenda").val('');
    $("#precovenda_aux").html('');
    $("#precocusto").val('');
    $("#precocustoreais").val('');
    $("#precoreal").val('');
    $("#defeito").val('');
    $("#codigobarra").val('');
    $("#descricaodefeito").val('');
    $("#cmb_codigobarra").html('');
    $("#descricao").html('');
    $("#siglaunidade").html('');
    $("#precoetiqueta").val('');
    $("#desconto").html('');
    $("#unitario").html('');
    $("#total").html('');
    $("#imagem").html('');
    $("#fator").val('');
    $("#peso").val('');
    $("#cotacao_aux").val('');
    $("#peso_aux").html('');

	$("#referenciaconsulta").focus();
  });
});

		$("#abrirVenda").click(function(){

			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){

				$.post("../../ajax/venda/abrirVenda.php",{
					idcliente : $("#idcliente").val(),
					idfuncionario : $("#idfuncionario").val(),
					crediario : $("#crediario").val(),
				}, function (retorno){
					busca = retorno.split("|");

					if(busca[0]){

						$("#idvenda").val(busca[0]);
						
						$("#idcliente").attr('readonly', true);
						$("#nomeconsulta").attr('readonly', true);
						//$("#numerovenda").attr('readonly', true);
						//$("#idfuncionario").attr('disabled', true);
						
						$.post("../../ajax/venda/alterarTabelaVenda.php",{
							idtabelavenda : $("#idtabelavenda").val(),
							idvenda : $("#idvenda").val(),
						}, function (retorno){
							$.post("../../ajax/venda/buscarProdutoVenda.php",{
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val(),
								venda : '1',
								fator : '1',
							}, function (retorno){
								$("#tab_produto").html(retorno);
								$("#produto").show();
							});
						});

						$.post("../../ajax/venda/buscarProdutoVenda.php",{
							idvenda : $("#idvenda").val(),
							idcliente : $("#idcliente").val(),
							venda : '1',
							fator : '1',
						}, function (retorno){
							$("#tab_produto").html(retorno);
						});

						$("#produto").show();
						$("#codigobarra").focus();
						$("#abertura_venda").hide();


						$("#idtabelavenda").keydown(function(e) {
						//alert('400');
							if(e.keyCode == 13){

								if(!$("#idcliente").val()){
									$("#idcliente").focus();
								}

							}
						});

						$("#devolucao").keydown(function(e) {
							if(e.keyCode == 13){

								if($("#devolucao").is(":checked")){
									$("#devolucao").attr("checked",false);
								}else{
									$("#devolucao").attr("checked",true);
								}
								$("#referenciaconsulta").focus();
							}
						});

						$("#referenciaconsulta").keydown(function(e) {
							if(e.keyCode == 13){

								if(!$("#referenciaconsulta").val()){
									$("#codigobarra").focus();
								}

							}
						});

						$("#referenciaconsulta").change(function(e) {

							if(!$("#referenciaconsulta").val()){
								$("#codigobarra").focus();
							}

						});

						$("#codigobarra").keydown(function(e) {
							if(e.keyCode == 13){

								$("#quantidade").focus();

							}
						});

						$("#codigobarra").change(function(e) {

							$("#quantidade").focus();

						});

					}

					if(busca[1])
						alert(busca[1]);
				});
			});
		});

$("#idprodutobusca").live('change', function(){

  $.post("../../ajax/venda/selecionarProduto.php",{
    //idprodutocombo : $("#idprodutobusca").val(),
    idgrade : $("#idprodutobusca").val(),
	idtabelavenda : $("#idtabelavenda").val(),
    fator : $("#fator").val(),
    idcliente : $("#idcliente").val(),
    acao : "cmb_referencia",
  }, function (retorno){
	//alert(retorno);
    busca = retorno.split("|");
    if(!busca[0])
      alert('Produto não existe ou peso = 0!!!!');
    $("#idproduto").val(busca[0]);
    $("#idgrade").val(busca[1]);
    $("#idunidade").val(busca[3]);
    $("#siglaunidade").html(busca[4]);
    $("#siglamoeda").html(busca[5]);
    $("#descontoProduto").val(busca[8]);
    $("#imagem").html(busca[9]);
    $("#descricao").html(busca[11]);
    $("#precocusto").val(busca[13]);
    $("#precocustoreais").val(busca[14]);
    $("#precoreal").val(busca[15]);
    $("#precovenda").val(busca[16]);
    $("#precovenda_aux").html(busca[16]);
    $("#unitario").html(busca[21]);
    $("#precoetiqueta").val(busca[17]);
    $("#peso").val(busca[17]);
    $("#precovendaorig").val(busca[18]);
    $("#codigobarra").val(busca[19]);

    if(busca[20]){
      $("#desconto_porc").html('<b>Desconto %</b>');
      $("#desconto").html(busca[20]);
    }

	$("#estoqueatual_aux").html(busca[25]);
	$("#estoqueatual").val(busca[25]);
	$("#cotacao_aux").val(busca[26]);

    $("#adicionaproduto").show();
  });
});

		$("#quantidade").change(function(){
			
			var isErroDecimal = false;
				
			if($("#idunidade").val() == '1'){
				if(!checkNumber($("#quantidade").val())){
					isErroDecimal = true;
					alert("Somente permitido números inteiros para Produtos 'PC'");
				}
			}
				
			if(!isErroDecimal){
				if($("#precovenda").val() != '0.00'){
					if($("#devolucao").is(":checked"))
						var devolucao = 1;
					else
						var devolucao = 0;

					$.post("../../ajax/venda/relacionarProduto.php",{
						idproduto : $("#idproduto").val(),
						idgrade : $("#idgrade").val(),
						idvenda : $("#idvenda").val(),
						idtabelavenda : $("#idtabelavenda").val(),
						quantidade : $("#quantidade").val(),
						precovendaorig : $("#precovendaorig").val(),
						descontoProduto : $("#descontoProduto").val(),
						precovenda : $("#precovenda").val(),
      					precocusto : $("#precocusto").val(),
						precocustoreais : $("#precocustoreais").val(),
						precoreal : $("#peso").val(),
						cotacao_aux : $("#cotacao_aux").val(),
						descricaodefeito : $("#descricaodefeito").val(),
						devolucao : devolucao,
					}, function (retorno){
						if(!retorno){
							$("#tab_produto").html('');
							$("#idproduto").val('');
							$("#idgrade").val('');
							$("#quantidade").val('');
							$("#precovendaorig").val('');
							$("#descontoProduto").val('');
							$("#precovenda").val('');
							$("#precovenda_aux").html('');
							$("#precocusto").val('');
							$("#precocustoreais").val('');
							$("#precoreal").val('');
							$("#codigobarra").val('');
							$("#descricaodefeito").val('');
							$("#referenciaconsulta").val('');
							$("#cmb_idprodutoref").html('');
							$("#cmb_codigobarra").html('');
							$("#descricao").html('');
							$("#siglaunidade").html('');
							$("#siglamoeda").html('');
							$("#precoetiqueta").val('');
							$("#cotacao_aux").val('');
							$("#desconto").html('');
							$("#unitario").html('');
							$("#total").html('');
							$("#imagem").html('');
							$("#estoqueatual_aux").html('');
							$("#estoqueatual").val('');

							$("#codigobarra").focus();

							$("#referenciaconsulta").keydown(function(e) {
								if(e.keyCode == 13){
									if(!$("#referenciaconsulta").val()){
										$("#codigobarra").focus();
									}
								}
							});

							$("#referenciaconsulta").change(function(e) {
								if(!$("#referenciaconsulta").val()){
									$("#codigobarra").focus();
								}
							});

							$("#codigobarra").keydown(function(e) {
								if(e.keyCode == 13){
									$("#quantidade").focus();
								}
							});

							$("#codigobarra").change(function(e) {
								$("#quantidade").focus();
							});

							$.post("../../ajax/venda/buscarProdutoVenda.php",{
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val(),
								venda : '1',
							}, function (retorno){
								$("#tab_produto").html(retorno);
							});
						}else{
							$("#msg").html(retorno);
							$("#spn_senha").show();
						}
					});
				}
			}
		});
		
		$("#liberasenha").click(function(){
		
			$("#msg").html('');
			
			var isErroDecimal = false;
				
			if($("#idunidade").val() == '1'){
				if(!checkNumber($("#quantidade").val())){
					isErroDecimal = true;
					alert("Somente permitido números inteiros para Produtos 'PC'");
				}
			}
				
			if(!isErroDecimal){
			
				if($("#precovenda").val() != '0.00'){
					if($("#devolucao").is(":checked"))
						var devolucao = 1;
					else
						var devolucao = 0;

					$.post("../../ajax/venda/relacionarProduto.php",{
						idproduto : $("#idproduto").val(),
						idgrade : $("#idgrade").val(),
						idvenda : $("#idvenda").val(),
						idtabelavenda : $("#idtabelavenda").val(),
						quantidade : $("#quantidade").val(),
						precovendaorig : $("#precovendaorig").val(),
						descontoProduto : $("#descontoProduto").val(),
						precovenda : $("#precovenda").val(),
      					precocusto : $("#precocusto").val(),
						precocustoreais : $("#precocustoreais").val(),
						precoreal : $("#peso").val(),
						cotacao_aux : $("#cotacao_aux").val(),
						descricaodefeito : $("#descricaodefeito").val(),
						senhaestoque : $("#senhaestoque").val(),
						devolucao : devolucao,
					}, function (retorno){
						if(!retorno){
							$("#tab_produto").html('');
							$("#idproduto").val('');
							$("#idgrade").val('');
							$("#quantidade").val('');
							$("#precovendaorig").val('');
							$("#descontoProduto").val('');
							$("#precovenda").val('');
							$("#precovenda_aux").html('');
							$("#precocusto").val('');
							$("#precocustoreais").val('');
							$("#precoreal").val('');
							$("#codigobarra").val('');
							$("#descricaodefeito").val('');
							$("#referenciaconsulta").val('');
							$("#cmb_idprodutoref").html('');
							$("#cmb_codigobarra").html('');
							$("#descricao").html('');
							$("#siglaunidade").html('');
							$("#siglamoeda").html('');
							$("#precoetiqueta").val('');
							$("#cotacao_aux").val('');
							$("#desconto").html('');
							$("#unitario").html('');
							$("#total").html('');
							$("#imagem").html('');
							$("#estoqueatual_aux").html('');
							$("#estoqueatual").val('');

							$("#codigobarra").focus();

							$("#referenciaconsulta").keydown(function(e) {
								if(e.keyCode == 13){
									if(!$("#referenciaconsulta").val()){
										$("#codigobarra").focus();
									}
								}
							});

							$("#referenciaconsulta").change(function(e) {
								if(!$("#referenciaconsulta").val()){
									$("#codigobarra").focus();
								}
							});

							$("#codigobarra").keydown(function(e) {
								if(e.keyCode == 13){
									$("#quantidade").focus();
								}
							});

							$("#codigobarra").change(function(e) {
								$("#quantidade").focus();
							});

							$.post("../../ajax/venda/buscarProdutoVenda.php",{
								idvenda : $("#idvenda").val(),
								idcliente : $("#idcliente").val(),
								venda : '1',
							}, function (retorno){
								$("#tab_produto").html(retorno);
							});
							
							$("#senhaestoque").val('');
							$("#spn_senha").hide();
						}else{
							$("#msg").html(retorno);
							$("#spn_senha").show();
							$("#senhaestoque").val('');
						}
					});
				}
			}
		});
		
		$("#limparproduto").click(function(){
		
			$("#msg").html('');
			$("#senhaestoque").val('');
			$("#spn_senha").hide();
						
			$("#idproduto").val('');
			$("#idgrade").val('');
			$("#quantidade").val('');
			$("#precovendaorig").val('');
			$("#descontoProduto").val('');
			$("#precovenda").val('');
			$("#precovenda_aux").html('');
			$("#precocusto").val('');
			$("#precocustoreais").val('');
			$("#precoreal").val('');
			$("#codigobarra").val('');
			$("#descricaodefeito").val('');
			$("#referenciaconsulta").val('');
			$("#cmb_idprodutoref").html('');
			$("#cmb_codigobarra").html('');
			$("#descricao").html('');
			$("#siglaunidade").html('');
			$("#siglamoeda").html('');
			$("#precoetiqueta").val('');
			$("#cotacao_aux").val('');
			$("#desconto").html('');
			$("#unitario").html('');
			$("#total").html('');
			$("#imagem").html('');
			$("#estoqueatual_aux").html('');
			$("#estoqueatual").val('');

			$("#codigobarra").focus();

			$("#referenciaconsulta").keydown(function(e) {
				if(e.keyCode == 13){
					if(!$("#referenciaconsulta").val()){
						$("#codigobarra").focus();
					}
				}
			});

			$("#referenciaconsulta").change(function(e) {
				if(!$("#referenciaconsulta").val()){
					$("#codigobarra").focus();
				}
			});

			$("#codigobarra").keydown(function(e) {
				if(e.keyCode == 13){
					$("#quantidade").focus();
				}
			});

			$("#codigobarra").change(function(e) {
				$("#quantidade").focus();
			});

		});

		$("#precovenda").change(function(){

			var isErroDecimal = false;
				
			if($("#idunidade").val() == '1'){
				if(!checkNumber($("#quantidade").val())){
					isErroDecimal = true;
					alert("Somente permitido números inteiros para Produtos 'PC'");
				}
			}
				
			if(!isErroDecimal){
				if($("#devolucao").is(":checked"))
					var devolucao = 1;
				else
					var devolucao = 0;

				$.post("../../ajax/venda/relacionarProduto.php",{
					idproduto : $("#idproduto").val(),
					idgrade : $("#idgrade").val(),
					idvenda : $("#idvenda").val(),
					idtabelavenda : $("#idtabelavenda").val(),
					quantidade : $("#quantidade").val(),
					precovendaorig : $("#precovendaorig").val(),
					descontoProduto : $("#descontoProduto").val(),
					precovenda : $("#precovenda").val(),
     				precocusto : $("#precocusto").val(),
					precocustoreais : $("#precocustoreais").val(),
					precoreal : $("#peso").val(),
					cotacao_aux : $("#cotacao_aux").val(),
					descricaodefeito : $("#descricaodefeito").val(),
					devolucao : devolucao,
				}, function (retorno){
					if(retorno)
						alert(retorno);

					if(!retorno){
						$("#tab_produto").html('');
						$("#idproduto").val('');
						$("#idgrade").val('');
						$("#quantidade").val('');
						$("#precovendaorig").val('');
						$("#descontoProduto").val('');
						$("#precovenda").val('');
						$("#precovenda_aux").html('');
						$("#precocusto").val('');
						$("#precocustoreais").val('');
						$("#precoreal").val('');
						$("#codigobarra").val('');
						$("#descricaodefeito").val('');
						$("#referenciaconsulta").val('');
						$("#cmb_idprodutoref").html('');
						$("#cmb_codigobarra").html('');
						$("#descricao").html('');
						$("#siglaunidade").html('');
						$("#siglamoeda").html('');
						$("#precoetiqueta").val('');
						$("#cotacao_aux").val('');
						$("#desconto").html('');
						$("#unitario").html('');
						$("#total").html('');
						$("#imagem").html('');
						$("#estoqueatual_aux").html('');
						$("#estoqueatual").val('');
						
						$("#codigobarra").focus();

						$("#referenciaconsulta").keydown(function(e) {
							if(e.keyCode == 13){
								if(!$("#referenciaconsulta").val()){
									$("#codigobarra").focus();
								}
							}
						});

						$("#referenciaconsulta").change(function(e) {
							if(!$("#referenciaconsulta").val()){
								$("#codigobarra").focus();
							}
						});

						$("#codigobarra").keydown(function(e) {
							if(e.keyCode == 13){
								$("#quantidade").focus();
							}
						});

						$("#codigobarra").change(function(e) {
							$("#quantidade").focus();
						});

						$.post("../../ajax/venda/buscarProdutoVenda.php",{
							idvenda : $("#idvenda").val(),
							idcliente : $("#idcliente").val(),
							venda : '1',
						}, function (retorno){
							$("#tab_produto").html(retorno);
						});
					}
				});
			}

			if(!$("#descontotela").val()){
				$("#precovenda").attr("readonly", false);
			}
		});
		
		$("#precoetiqueta").change(function(){

			var isErroDecimal = false;

			if($("#idunidade").val() == '1'){
				if(!checkNumber($("#quantidade").val())){
					isErroDecimal = true;
					alert("Somente permitido números inteiros para Produtos 'PC'");
				}
			}

			if(!isErroDecimal){
				if($("#devolucao").is(":checked"))
					var devolucao = 1;
				else
					var devolucao = 0;

                    var etiqueta = 1;

				$.post("../../ajax/venda/relacionarProduto.php",{
					idproduto : $("#idproduto").val(),
					idgrade : $("#idgrade").val(),
					idvenda : $("#idvenda").val(),
					idtabelavenda : $("#idtabelavenda").val(),
					quantidade : $("#quantidade").val(),
					precovendaorig : $("#precovendaorig").val(),
					descontoProduto : $("#descontoProduto").val(),
					precovenda : $("#precovenda").val(),
					precoetiqueta : $("#precoetiqueta").val(),
					cotacao_aux : $("#cotacao_aux").val(),
					precocusto : $("#precocusto").val(),
					precocustoreais : $("#precocustoreais").val(),
					precoreal : $("#peso").val(),
                    descricaodefeito : $("#descricaodefeito").val(),
					devolucao : devolucao,
					etiqueta : etiqueta,
				}, function (retorno){
					if(retorno)
						alert(retorno);

					if(!retorno){
						$("#tab_produto").html('');
						$("#idproduto").val('');
						$("#idgrade").val('');
						$("#quantidade").val('');
						$("#precovendaorig").val('');
						$("#descontoProduto").val('');
						$("#precovenda").val('');
						$("#precovenda_aux").html('');
						$("#precocusto").val('');
						$("#precocustoreais").val('');
						$("#precoreal").val('');
						$("#codigobarra").val('');
						$("#descricaodefeito").val('');
						$("#referenciaconsulta").val('');
						$("#cmb_idprodutoref").html('');
						$("#cmb_codigobarra").html('');
						$("#descricao").html('');
						$("#siglaunidade").html('');
						$("#siglamoeda").html('');
						$("#precoetiqueta").val('');
						$("#cotacao_aux").val('');
						$("#desconto").html('');
						$("#unitario").html('');
						$("#total").html('');
						$("#imagem").html('');
						$("#estoqueatual_aux").html('');
						$("#estoqueatual").val('');

						$("#codigobarra").focus();

						$("#referenciaconsulta").keydown(function(e) {
							if(e.keyCode == 13){
								if(!$("#referenciaconsulta").val()){
									$("#codigobarra").focus();
								}
							}
						});

						$("#referenciaconsulta").change(function(e) {
							if(!$("#referenciaconsulta").val()){
								$("#codigobarra").focus();
							}
						});

						$("#codigobarra").keydown(function(e) {
							if(e.keyCode == 13){
								$("#quantidade").focus();
							}
						});

						$("#codigobarra").change(function(e) {
							$("#quantidade").focus();
						});

						$.post("../../ajax/venda/buscarProdutoVenda.php",{
							idvenda : $("#idvenda").val(),
							idcliente : $("#idcliente").val(),
							venda : '1',
						}, function (retorno){
							$("#tab_produto").html(retorno);
						});
					}
				});
			}

			if(!$("#descontotela").val()){
				$("#precovenda").attr("readonly", false);
			}
		});

		$("#codigobarra").change(function(){

			if($("#devolucao").is(":checked"))
				var devolucao = 1;
			else
				var devolucao = 0;

			$.post("../../ajax/sessao/confirmaSessao.php",{
				idsessao : $("#idsessao").val(),
			}, function (retorno){
				if(retorno){
					$.post("../../ajax/venda/selecionarProduto.php",{
						codigobarra : $("#codigobarra").val(),
						idtabelavenda : $("#idtabelavenda").val(),
						fator : 1,
						idcliente : $("#idcliente").val(),
					}, function (retorno){
						//alert(retorno);
						//$("#msg").html(retorno);
						busca = retorno.split("|");
						if(!busca[0])
							alert('Produto não encontrado!!!!');
						$("#idproduto").val(busca[0]);
						$("#idgrade").val(busca[1]);
						$("#idunidade").val(busca[3]);
						$("#siglaunidade").html(busca[4]);
						$("#siglamoeda").html(busca[5]);
						$("#descontoProduto").val(busca[8]);
						$("#imagem").html(busca[9]);
						$("#descricao").html(busca[11]);
						$("#precocusto").val(busca[13]);
						$("#precocustoreais").val(busca[14]);
						$("#precoreal").val(busca[15]);
						$("#precovenda").val(busca[16]);
						$("#precovenda_aux").html(busca[16]);
						$("#unitario").html(busca[21]);
						$("#precoetiqueta").val(busca[17]);
						$("#precovendaorig").val(busca[18]);
						$("#codigobarra").val(busca[19]);
						$("#peso").val(busca[17]);
						$("#peso_aux").html(busca[22]);
						$("#estoqueatual_aux").html(busca[25]);
						$("#cotacao_aux").val(busca[26]);

						if(busca[20]){
							$("#desconto_porc").html('<b>Desconto %</b>');
							$("#desconto").html(busca[20]);
						}

						/*if(busca[16] == '0.00'){
							$("#precovenda").attr("readonly", false);
						}*/
						
						if(busca[17] == '0.00'){
							$("#precoetiqueta").attr("readonly", false);
						}
						
					});
				}
			});
		});
	});

	function submeterCmbCliente() {

		$.post("../../ajax/sessao/confirmaSessao.php",{
			idsessao : $("#idsessao").val(),
		}, function (retorno){

			$.post("../../ajax/cliente/buscarClienteId.php",{
				idcliente : $("#codigocliente").val(),
				acao : "busca_cliente",
			}, function (retorno){
				busca = retorno.split("|");

				$("#idcliente").val(busca[0]);
				$("#nomeconsulta").val(busca[1]);
				$("#valorcredito").val(busca[12]);
				$("#saldovale").val(busca[13]);
				$("#saldoconta").html(busca[16]);

				if(($("#idfuncionario").val() > 0) && ($("#idcliente").val() > 0) && ($("#idtabelavenda").val() > 0) && !$("#idvenda").val()){
					$("#abertura_venda").show();
					$("#abrirVenda").focus();
				}else if(!$("#idfuncionario").val()){
					$("#numerovenda").focus();
				}

				if(busca[15]){
					$("#msg").html(busca[15]);
				}else{
					$("#cmb_cliente").html('');
				}
			});
		});
	}

function submeterBuscaProduto() {

  var idprodutoref = $("#idprodutoref").val();

  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){

    $.post("../../ajax/buscaProduto.php",{
      idprodutocombo : idprodutoref,
      acao : "cmb_codigobarra",
    }, function (retorno){
      $("#cmb_codigobarra").html(retorno);
      $("#cmb_idprodutoref").html('');
      $("#novabusca").hide('');


      if(retorno){
        $.post("../../ajax/buscaProduto.php",{
          idprodutocombo : idprodutoref,
          acao : "busca_codigobarra",
        }, function (retorno){
              //alert(retorno);
              busca = retorno.split("|");

              $("#referenciaconsulta").val(busca[10]);
            });
      }
    });
  });
}

function submeterSelecaoProduto() {

  var idgrade_aux = $("#idgrade_aux").val();

  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){

    $.post("../../ajax/venda/selecionarProduto.php",{
      idgrade : idgrade_aux,
      idcliente : $("#idcliente").val(),
	idtabelavenda : $("#idtabelavenda").val(),
      acao : "cmb_codigobarra",
    }, function (retorno){
          //alert(retorno);
          busca = retorno.split("|");

          $("#idproduto").val(busca[0]);
          $("#idgrade").val(busca[1]);
		  $("#idunidade").val(busca[3]);
          $("#siglaunidade").html(busca[4]);
          $("#siglamoeda").html(busca[5]);
          $("#descontoProduto").val(busca[8]);
          $("#imagem").html(busca[9]);
          $("#descricao").html(busca[11]);
          $("#precocusto").val(busca[13]);
          $("#precocustoreais").val(busca[14]);
          $("#precoreal").val(busca[15]);
          $("#precovenda").val(busca[16]);
          $("#precovenda_aux").html(busca[16]);
          $("#unitario").html(busca[21]);
          $("#precoetiqueta").val(busca[17]);
          $("#precovendaorig").val(busca[18]);
          $("#codigobarra").val(busca[19]);


		$("#estoqueatual_aux").html(busca[25]);
		$("#estoqueatual").val(busca[25]);
		$("#cotacao_aux").val(busca[26]);

          $("#quantidade").focus();

          if(busca[20]){
            $("#desconto_porc").html('<b>Desconto %</b>');
            $("#desconto").html(busca[20]);
          }

        });
});
}

function submeterExclusaoProduto(id){

  var idvendaproduto = id;
//  if( event.keyCode==13 ) {
  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){

    $.post("../../ajax/venda/excluirProduto.php",{
      idvendaproduto : id,
    }, function (retorno){

      if(retorno){

        $("#tab_produto").html('');

        $.post("../../ajax/venda/buscarProdutoVenda.php",{
          idvenda : $("#idvenda").val(),
          idcliente : $("#idcliente").val(),
          venda : '1',
          fator : '1',
        }, function (retorno){
          $("#tab_produto").html(retorno);

        });
      }
    });
  });

 // }

}

	function focoCliente(){
		$("#idcliente").focus();
	}


function submeterSenha(){
  var senha = $("#senha").val();

  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){

    $.post("../../ajax/venda/submeterSenha.php",{
      senha : senha,
    }, function (retorno){
      if(retorno){

        $("#tab_produto").html('');

        $.post("../../ajax/venda/buscarProdutoVenda.php",{
          idvenda : $("#idvenda").val(),
          idcliente : $("#idcliente").val(),
          venda : '1',
          fator : '1',
          isSenha : '1',
        }, function (retorno){
          $("#tab_produto").html(retorno);

        });
      }
    });
  });
}
	function submeterListaProdutos(){
		var idvenda = $("#idvenda").val();
		var idcliente = $("#idcliente").val();
		var idsessao = $("#idsessao").val();

		$.post("../../ajax/sessao/confirmaSessao.php",{
			idsessao : $("#idsessao").val(),
		}, function (retorno){
			window.open("listarProduto.php?idvenda="+idvenda+"&idcliente="+idcliente+"&idsessao="+idsessao+"&fator=1");
		});
	}
	
	function submeterImpressaoLista(){
	
		var quantidade = prompt("Quantidade de vias.");
		var idvenda = $("#idvenda").val();
		
		$.post("../../ajax/sessao/confirmaSessao.php",{
			idsessao : $("#idsessao").val(),
		}, function (retorno){
			$.post("buscarImpressaoVenda.php",{
				idvenda : $("#idvenda").val(),
				idcliente : $("#idcliente").val(),
				quantidadevenda : quantidade,
			}, function (retorno){
				alert(retorno);
			}); 
		});
	}

function submeterFinalizacaoVenda(){
  var idvenda = $("#idvenda").val();
  var idsessao = $("#idsessao").val();

  $.post("../../ajax/sessao/confirmaSessao.php",{
    idsessao : $("#idsessao").val(),
  }, function (retorno){

    location.href="finalizaVenda.php?idvenda="+idvenda+"&fator=1&teste="+idsessao;
  });
}

		function buscaLista(){
			//alert();

            $.post("../../ajax/venda/buscarProdutoVenda.php",{
              idvenda : $("#idvenda").val(),
              idcliente : $("#idcliente").val(),
              venda : '1',
              fator : '1',
            }, function (retorno){
				//alert(retorno);
              $("#tab_produto").html(retorno);
              $("#produto").show();
              $("#abertura_venda").hide();
            });
		}

	//não deixa digitar letras
	function verifica(){
		if ((event.keyCode<44)||(event.keyCode>57)){
			if ((event.keyCode<96)||(event.keyCode>106)){
				alert("Somente números são permitidos");
				event.returnValue = false;
			}
			alert("Somente números são permitidos");
			event.returnValue = false;
		}
	}

	//mascaras
	function formatar_mascara(src, mascara) {
		var campo = src.value.length;
		var saida = mascara.substring(0,1);
		var texto = mascara.substring(campo);
		if(texto.substring(0,1) != saida) {
			src.value += texto.substring(0,1);
		}
	}

</script>
</head>

<body>
<?php
	if($funcionario[nivel] >= 1) {
	
		$idsessao = $_POST['idsessao'];
		if(!$idsessao)
			$idsessao = $_GET['teste'];
		
		$con = new Connection;
		$con->open();

		$sql = "SELECT LimiteDesconto, LimiteDescontoGer, DescontoRealizaVenda
				FROM parametros_venda";
		$rs = $con->executeQuery ($sql);
			//echo $sql;
		if($rs->next()) {
		  $descontopermitido = $rs->get("LimiteDesconto");
		  $descontopermitidoger = $rs->get("LimiteDescontoGer");
		  $descontotela = $rs->get("DescontoRealizaVenda");
		}
		$rs->close();
?>
    <form name="formRealizaVenda" action="realizaVendaAjax.php?teste=<? echo $idsessao; ?>" method="POST">
      <center>
        <font class="subtitulo"><b>Realiza Venda</b></font>

        <br>

        <font size="4" color="red"><span id="msg"></span></font>

        <table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <table width="800" align="center" cellpadding="2" cellspacing="2">
                <tr>
                  <td width = "100" align="right"><b>Vendedor</b></td>
                  <td width = "50" align = "center">
                    <input type = "text" name = "numerovenda" id = "numerovenda" size = "3" maxlength = "3" />
                  </td>
                  <td width="300" align = "left">
                    <select name="idfuncionario" id = "idfuncionario">
                      <option value="">Selecione o Vendedor</option>
                      <?
                      $sql = "SELECT funcionario.IdFuncionario, funcionario.Nome, funcionario.NumeroVenda, funcionario.IdEstabelecimento FROM funcionario, estabelecimento WHERE  funcionario.IdEstabelecimento = estabelecimento.IdEstabelecimento AND estabelecimento.IdLoja = $funcionario[idloja] AND funcionario.Ativo = 1";
                      $sql = "$sql ORDER BY funcionario.Nome";
                      $rs = $con->executeQuery ($sql);
                      while($rs->next()) {
                        ?>
                        <option value="<? echo $rs->get(0); ?>"><? echo $rs->get(1); ?></option>
                        <?
                      }
                      $rs->close();
                      ?>
                    </select>
                  </td>
                  <td width="350" align="center">
                    <b>Prazos</b>&nbsp;&nbsp;
                    <select name = "idtabelavenda" id = "idtabelavenda" onkeypress="focoCliente();" >
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
                    <input type = "hidden" name = "fator" value = "<? printf("%1.3f", $fator); ?>">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <br>
        <table border="1" width="800" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>
              <table width="800" align="center" cellpadding="2" cellspacing="2">
                <tr>
                  <td width = "100" align="right"><b>Cliente</b></td>
                  <td width = "700" align = "left">
                    <input type = "text" name = "idcliente" id = "idcliente" value ="<?= $idcliente?>" size = "10" maxlength = "10" >
                    &nbsp;&nbsp;&nbsp;
                    <input type="text" name="nomeconsulta" id="nomeconsulta" value ="<?= $nomeconsulta?>" size="50" maxlength="50" >
                    &nbsp;&nbsp;&nbsp;
                    <a href="#" onclick='window.open("../../caixa/cliente/incluiCliente.php", "Popup", "width=900, height=900, scrollbars=yes");'>Cadastrar</a>
                  </td>
                </tr>
                <tr>
                  <td width = "100" align="right"><b>CPF</b></td>
                  <td width = "700" align = "left">
                     <input type = "text" name = "cpfconsulta" id = "cpfconsulta" size = "15" maxlength="15" onkeypress="verifica(),formatar_mascara(this, '###.###.###-##');"/>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="center">
                    <div id="cmb_cliente">
                    </div>
                  </td>
                </tr>
                <tr>
                  <td width = "100" align="right">
                    <b>Limite Crédito</b>
                  </td>
                  <td width = "700" align = "left">
                    <input type = "text" name = "valorcredito" id = "valorcredito" readonly="yes" size="10"/>
                    &nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;
                    <b>Saldo Vale</b>&nbsp;
                    <input type = "text" name = "saldovale" id = "saldovale" readonly="yes"size="10"/>
                    &nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;
                    <b>Saldo Conta</b>&nbsp;
					<span id="saldoconta"></span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
        <div id="abertura_venda" style="display:none">
          <br/>
          <input type = "button" id = "abrirVenda" id = "abrirVenda" value = "Abrir Venda" />
        </div>

        <br>

	<div id="produto" style="display:none">
		<table border="1" width="900" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<table width="900" align="center" cellpadding="2" cellspacing="2">
						<tr>
							<td width = "900" align="left" colspan="9"><br/>
							  <input type="checkbox" name="devolucao" id="devolucao" />&nbsp;&nbsp;<b>Devolução</b></td>
							</td>
						</tr>
						<tr>
							<td width="700" colspan='7'> &nbsp;&nbsp;</td>
							<td width="200" align="left" rowspan="7" valign="center"><span id="imagem"></span></td>
						</tr>
						<tr>
							<td width="300" align="left" colspan="3"><b>Referência</b>&nbsp;&nbsp;</td>
							<td width="400" align="left" colspan="4"></td>
						</tr>
						<tr>
							<td width="300" align="left" colspan="3">
								<input type="text" name="referenciaconsulta" id="referenciaconsulta" size="50" maxlength="50" >
							</td>
							<td width="400" align="left" colspan="4">
								<span id="cmb_idprodutoref"></span>
								<span id="cmb_codigobarra"></span>
								<input type="button" id="novabusca" value="Nova Busca" style="display:none"/>
							</td>
						</tr>
						<tr>
							<td width="700" align="left" colspan="7"><b>Descrição</b></td>
						</tr>
						<tr>
							<td width="700" align="left" colspan="7">
								<span id="descricao"></span>
							</td>
						</tr>
						<tr>
							<td width="100" align="left"><b>Código</b></td>
							<td width="100" align="left"><b>Qtde&nbsp;&nbsp;<span id="siglaunidade"></span></b></td>
							<td width="100" align="left"><b>Etiqueta</b></td>
							<td width="100" align="left"><b>R$</b></td>
							<td width="100" align="left"><b>Unitário R$</b></td>
							<td width="100" align="left"></td>
							<td width="100" align="left"></td>
						</tr>
						<tr>
							<td width="100" align="left">
								<input type="text" name="codigobarra" id="codigobarra" size="13" maxlength="13" >
							</td>
							<td width="100" align="left">
								<input type = "text" name = "quantidade" id = "quantidade" size = "8" maxlength = "8" />
								<input type = "hidden" name = "idunidade" id = "idunidade" />
							</td>
							<td width="100" align="left">
								<input type = "text" name = "precoetiqueta" id="precoetiqueta" size = "8" maxlength = "8" <?php echo 'readonly="yes"'; ?>/>
								<span id="siglamoeda"></span>
							</td>
							<td width="100" align="left">
								<input type = "text" name = "precovenda" id = "precovenda" size = "8" maxlength = "8" <?php if(!$descontotela) echo 'readonly="yes"'; ?>/>
							</td>
							<td width="100" align="left">
								<span id="unitario"></span>
							</td>
							<td width="100" align="left">
								<span id="spn_total"></span>
							</td>
							<td width="100" align="left">
								<span id="desconto_porc"></span>
							</td>
						</tr>
						<tr>
							<td width="700" align="center" colspan="7">
								<span id="spn_senha">
									<b>Senha para Liberar produto estoque zero/negativo: </b>
									&nbsp;&nbsp;
									<input type="password" id="senhaestoque" size="6" maxlength="6"/>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" id="liberasenha" value="Enviar"/>
									&nbsp;&nbsp;
									<input type="button" id="limparproduto" value="Limpar Produto"/>
								</span>
							</td>
						</tr>
					</table>

					<p align="center"><input style="display:none;" type="button" name="adicionaproduto" id="adicionaproduto" value="Relacionar Produto" /><p>
                </td>
			</tr>
		</table>

		<br>

		<table width="800" align="center" cellpadding="0" cellspacing="0">
			<tr>
                <td>
					<table width="800" align="center" cellpadding="2" cellspacing="2">
						<tr>
							<td width="800" align="center">
								<input type="button" id="abandonar" name="abandonar" value="Abandonar Venda"/>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<br/>

	</div>


          <span id="tab_produto"></span>
          <input type="hidden"  name="idproduto" id="idproduto"/>
          <input type="hidden"  name="idgrade" id="idgrade"/>
          <input type="hidden"  name="descontoProduto" id="descontoProduto"/>
          <input type="hidden"  name="precovendaorig" id="precovendaorig"/>
          <input type="hidden"  name="precocusto" id="precocusto"/>
          <input type="hidden"  name="precocustoreais" id="precocustoreais"/>
          <input type="hidden"  name="precoreal" id="precoreal"/>
          <input type="hidden"  name="peso" id="peso"/>
          <input type="hidden"  name="cotacao_aux" id="cotacao_aux"/>
          <input type="hidden"  name="idvenda" id="idvenda"/>
          <input type="hidden"  name="crediario" id="crediario"/>
          <input type="hidden"  name="idsessao" id="idsessao" value="<?php echo $idsessao ?>"/>
          <input type="hidden"  name="idvendareabre" id="idvendareabre" value="<?php echo $_GET['idvenda'] ?>"/>
          <input type="hidden"  name="descontotela" id="descontotela" value="<?php echo $descontotela ?>"/>
          <center>
          </form>
          <?
        }
        else echo '<br><br><br><center><font class="titulo">Acesso Restrito!<p><br></font><font class="mensagem">O Usuário não tem permissão para executar esta funcionalidade!<font><p><a href="../../home.php"><input type="submit" name="submeterRetorno" value="Retornar"></a></center>';

        $con->close();
        ?>
      </body>
      </html>
