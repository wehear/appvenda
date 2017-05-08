function limparParcelas(){
	$('#datavencimento1').val('');
}

function submeterAviso() {
	alert('Cliente Novo! Parcelamento somente com entrada!');
}

function submeterAvisoStatus() {
	alert('Cliente com Status Reabilitado! Parcelamento somente com entrada!');
}

function submeterGeracaoParcela() {
	if($('#idtabelavenda').val() != ''){	
		alert ("Por favor, selecione o Prazo de Pagamento.");
		$('#idtabelavenda').focus();
		return false;
	}
	if($('#quantidadeparcela').val() != ''){	
		alert ("Por favor, selecione o Prazo de Pagamento.");
		$('#quantidadeparcela').focus();
		return false;
	}
	if($('#primeiraparcela').val() != ''){	
		alert ("Por favor, selecione o Prazo de Pagamento.");
		$('#primeiraparcela').focus();
		return false;
	}
	
	$.post('http://localhost/AppVenda/app/submeterGeracaoParcela.php',{
		
	}, function(retorno){
		console.log(retorno);
	})
}

function submeterExclusaoProduto(id_produto){
	if(id_produto){
		if(confirm('Deseja mesmo excluir este produto?')){
			$.post('http://localhost/AppVenda/app/submeterExclusaoProduto.php',{
				id_produto:id_produto,
				server : server,
				idloja : idloja
			}, function(retorno){
				console.log(retorno);
				buscarProdutoVenda();
			})
		}
	}
}


function buscarProdutoVenda(){
	$.post("http://localhost/AppVenda/app/buscarProdutoVenda.php",{
		idvenda : $("#idvenda").val(),
		idcliente : $("#idcliente").val(),
		idloja : idloja,
		server : server,
		venda : '1',
		fator : '1',
	}, function (retorno){
		$("#tab_produto").html(retorno);
		
		$('#submeteFinalizacaoVenda').click(function(){
			if(confirm('Deja finalizar a venda?')){
				location = "finalizaVenda.html?idfuncionario="+idfuncionario+"&funcionario="+funcionario+"&idloja="+idloja+"&codigo="+codigo+"&server="+server+"&idvenda="+$("#idvenda").val()+"&fator=1&idtabelavenda="+$("#idtabelavenda").val()+"&somavalortotal="+$("#somavalortotal").val();
			}
		})
	})
}			