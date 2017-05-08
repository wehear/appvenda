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
$("#logar").click(function(){
	if(!$("#codigo").val()){
		alert("O campo CÓDIGO está vazio!");
		$("#codigo").focus();
		return false;
	}
	if(!$("#loja").val()){
		alert("O campo LOJA está vazio!");
		$("#loja").focus();
		return false;
	}
	if(!$("#login").val()){
		alert("O campo LOGIN está vazio!");
		$("#login").focus();
		return false;
	}
	$.post("http://localhost/AppVenda/app/login.php",{
		codigo : $("#codigo").val(),
		loja : $("#loja").val(),
		login : $("#login").val(),
		senha : $("#senha").val()
	},function(retorno){
		$("#teste").html(retorno);
		dados = retorno.split("||");
		if(dados[0] == "ok"){
			idfuncionario = dados[1];
			funcionario = dados[2];
			idloja = dados[3];
			codigo = dados[4];
			server = dados[5];
			location = "venda.html?idfuncionario="+idfuncionario+"&funcionario="+funcionario+"&idloja="+idloja+"&codigo="+codigo+"&server="+server;
		} else if(dados[0] == "logininvalido"){
			alert("LOGIN ou SENHA Inválido!");
			$("#login").focus();
			return false;
		} else if(dados[0] == "codigoinvalido"){
			alert("CÓDIGO Inválido!");
			$("#codigo").focus();
			return false;
		}
	})
})
);