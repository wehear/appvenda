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
$('#gerarParcelas').click(function(){
$.post('http://localhost/AppVenda/app/gerarParcelas.php',{
	server : server,
	idvenda : idvenda,
	primeiracompra_status : $('#primeiracompra_status').val(),
	quantidadeparcela : $('#quantidadeparcela').val(),
	primeiraparcela : $('#primeiraparcela').val(),
	quantidadeparcelapermitida : $('#quantidadeparcelapermitida').val(),
	valortotal : $('#valortotal').val(),
	demaisparcelas : $('#demaisparcelas').val(),
	idloja : idloja
},function(retorno){
	$('#telaparcelas').html(retorno);
	
	$('#submeteEncerramento').click(function(){
		if(confirm('Deseja encerrar a venda?')){		
			var quantidadeparcela = $('#quantidadeparcela').val();
			var datavencimento = "";
			var valorparcela = "";
			var idformapagamentoparcela = "";
			
			for(var i = 1; i <= quantidadeparcela; i++){
				datavencimento = datavencimento+$("#datavencimento"+i).val()+"|";
				valorparcela = valorparcela+$("#valorparcela"+i).val()+"|";
				idformapagamentoparcela = idformapagamentoparcela+$("#idformapagamentoparcela"+i).val()+"|";
			} 
			
			$.post('http://localhost/AppVenda/app/encerrarVenda.php',{
				server : server,
				idvenda : idvenda,
				quantidadeparcela : quantidadeparcela,
				datavencimento : datavencimento,
				valorparcela : valorparcela,
				idformapagamentoparcela : idformapagamentoparcela,
				idloja : idloja,
				valortotal : $('#valortotal').val(),
				fator : $('#fator').val(),
				valordesconto : $('#valordesconto').val(),
				porcentagemdesconto : $('#porcentagemdesconto').val(),
				observacao : $('#observacao').val()
			},function(retorno){
				if(retorno == 'ok'){
					location = "sucesso.html?idfuncionario="+idfuncionario+"&funcionario="+funcionario+"&idloja="+idloja+"&codigo="+codigo+"&server="+server+"&idvenda="+idvenda;
				}
			})
		}
	})

})
})
);