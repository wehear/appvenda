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
$("#quantidade").change(function(){
	$.post("http://localhost/AppVenda/app/addProduto.php",{
		server : server,
		codigobarra : $("#codigobarra").val(),
		quantidade : $("#quantidade").val(),
		idloja : idloja,
		idtabelavenda : $("#idtabelavenda").val(),
		idcliente : $("#idcliente").val(),
		idvenda : $("#idvenda").val(),
		fator : $("#fator").val(),
		senhaestoque : $("#senhaestoque").val()
	}, function (retorno){
		console.log(retorno);
		if(!retorno){
			$("#msg").html(retorno);
			
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
	});
})
);