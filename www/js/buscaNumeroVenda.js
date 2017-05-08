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
$('#numerovenda').change(function(){
	$.post("http://localhost/AppVenda/ajax/funcionario/buscaNumeroVenda.php",{
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
})
);