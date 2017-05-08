/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var app = {
    // Application Constructor
    initialize: function() {
        document.addEventListener('deviceready', this.onDeviceReady.bind(this), false);
    },

    // deviceready Event Handler
    //
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function() {
        this.receivedEvent('deviceready');

    },

    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    }
};

app.initialize(
$("#abrirVenda").click(function(){
	$.post("http://localhost/AppVenda/app/abrirVenda.php",{
		idcliente : $("#idcliente").val(),
		idfuncionario : $("#idfuncionario").val(),
		server : server,
		crediario : $("#crediario").val(),
		idloja : idloja
	}, function (retorno){
		console.log(retorno);
		busca = retorno.split("|");
		if(busca[0]){
			$("#idvenda").val(busca[0]);
			$("#idcliente").attr('readonly', true);
			$("#nomeconsulta").attr('readonly', true);
			$.post("http://localhost/AppVenda/app/alterarTabelaVenda.php",{
				idtabelavenda : $("#idtabelavenda").val(),
				server : server,
				idvenda : $("#idvenda").val(),
			}, function (retorno){
				$.post("http://localhost/AppVenda/app/buscarProdutoVenda.php",{
					idvenda : $("#idvenda").val(),
					idcliente : $("#idcliente").val(),
					idloja : idloja,
					server : server,
					venda : '1',
					fator : '1',
				}, function (retorno){
					$("#tab_produto").html(retorno);
					$("#produto").show();
				
					$('#submeteFinalizacaoVenda').click(function(){
						if(confirm('Deja finalizar a venda?')){
							location = "finalizaVenda.html?idfuncionario="+idfuncionario+"&funcionario="+funcionario+"&idloja="+idloja+"&codigo="+codigo+"&server="+server+"&idvenda="+$("#idvenda").val()+"&fator=1&idtabelavenda="+$("#idtabelavenda").val()+"&somavalortotal="+$("#somavalortotal").val();
						}
					})
				});
			});
			$("#produto").show();
			$("#codigobarra").focus();
			$("#abertura_venda").hide();
			$("#idtabelavenda").keydown(function(e) {
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
	});
})
);