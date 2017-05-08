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
$('#idtabelavenda').change(function(){
$.post('http://localhost/AppVenda/app/submeterPrazo.php',{
	server : server,
	idvenda : idvenda,
	idtabelavenda : $('#idtabelavenda').val(),
	idcliente : $('#idcliente').val(),
	primeiracompra_status : $('#primeiracompra_status').val(),
	idloja : idloja
},function(retorno){
	var str_retorno = retorno.split('||');
	var quantidadeparcelapermitida = str_retorno[0];
	var primeiraparcela = str_retorno[1];
	var demaisparcelas = str_retorno[2];
	var limite = str_retorno[3];
	var crediario = str_retorno[4];
	var fator_tabela = str_retorno[5];
	
	if(quantidadeparcelapermitida){
		$('#gerarparcelasdiv').show();
	}
	$('#quantidadeparcela').val(quantidadeparcelapermitida);
	$('#quantidadeparcelapermitida').val(quantidadeparcelapermitida);
	$('#demaisparcelas').val(demaisparcelas);
	$('#fator').val(fator_tabela);
	if(primeiraparcela){
		$('#primeiraparcela').val(primeiraparcela);
	} else {
		$('#primeiraparcela').val(0);
	}
	
})
})
);