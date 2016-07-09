<script language="javascript1.2" type="text/javascript">
var identificador = "";
var url = "serverSide.php?";

function control(){	
	if (identificador > 0)
		clearInterval(identificador);		
	identificador = setInterval("hilo()", 2000);
	document.getElementById('content').innerHTML = "<img src='images/ajaxWait.gif'>"; 
}

function hilo(){
	
	http.open("GET", url + "tipo=control&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpResponse;
	http.send(null);
}

function peer(idPeer){
	document.getElementById('txtPeers').innerHTML = "<br><img src='images/ajaxWait.gif'> Cargando la informacion de la cuenta.";
	http.open("GET", url + "tipo=peer&idPeer=" + escape(idPeer) + "&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpRes;
	http.send(null);
}

function chkZap(){
	if (document.forms['frmPeers'].protocolo.value == "DAHDI"){
		document.forms['frmPeers'].protocolo.enable = true;		
	}else
		document.forms['frmPeers'].protocolo.enable = false;
}

function reZap(){
	document.forms['frmPeers'].protocolo.enable = false;
	if (document.forms['frmPeers'].userpeer.value > 599 && document.forms['frmPeers'].userpeer.value < 700)
		document.forms['frmPeers'].protocolo.value = "none";
}

function HttpResponse() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  document.getElementById('content').innerHTML = results;
	  }
	}
}

function HttpPeers() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;		  
		  document.getElementById('txtPeers').innerHTML = results;
	  }
	}
}

function HttpRes() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var result=http.responseText;
		  var valores = result.split("|");
		  document.forms['frmPeers'].userpeer.value = valores[0];
		  document.forms['frmPeers'].protocolo.value = valores[2];
		  document.forms['frmPeers'].dtmf.value = valores[3];
		  nPick = valores[4].split(",");
		  document.forms['frmPeers'].pickup.value = nPick[0];
		  document.forms['frmPeers'].pickup2.value = nPick[1];
		  document.forms['frmPeers'].pickup3.value = nPick[2];
		  document.forms['frmPeers'].email.value = valores[5];
		  document.forms['frmPeers'].callerid.value = valores[6];
		  document.forms['frmPeers'].pwdpeer.value = valores[1];
		  document.forms['frmPeers'].pwdpeer1.value = valores[1];
		  document.forms['frmPeers'].pwdpersonal.value	= "";
		  
		  var radioLength = document.forms['frmPeers'].aBuzon.length;
		  
		  for(var i = 0; i < radioLength; i++) {
				if(document.forms['frmPeers'].aBuzon[i].value == valores[7]) {
					document.forms['frmPeers'].aBuzon[i].checked = true;
				}
		  }
		  
		  document.getElementById('txtPeers').innerHTML = "";
	  }
	}
}

function limpiar(){
	  document.forms['frmPeers'].userpeer.value = "";
	  document.forms['frmPeers'].protocolo.value = "none";
	  document.forms['frmPeers'].dtmf.value = "rfc2833";
	  document.forms['frmPeers'].pickup.value = "1";
	  document.forms['frmPeers'].pickup2.value = "1";
	  document.forms['frmPeers'].pickup3.value = "1";
	  document.forms['frmPeers'].email.value = "";
	  document.forms['frmPeers'].callerid.value = "";
	  document.forms['frmPeers'].pwdpeer.value = "";
	  document.forms['frmPeers'].pwdpeer1.value = "";
	  document.forms['frmPeers'].pwdpersonal.value = "";
	  document.getElementById('txtPeers').innerHTML = "";
}

function eliminar(){
	var idPeer = document.forms['frmPeers'].userpeer.value;
	var prot = document.forms['frmPeers'].protocolo.value;
	if (idPeer != "" && confirm("¿Desea eliminar la extensi\xF3n " + idPeer + "?")){
		document.getElementById('txtPeers').innerHTML = "<br><img src='images/ajaxWait.gif'> Eliminado extensi&oacute;n.";
		http.open("GET", url + "tipo=peerDel&idPeer=" + escape(idPeer) + "&prot=" + escape(prot) + "&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpPeers;
		http.send(null);
	}
}

function IsNumeric(sText)

{
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
 }

function guardar(){

	if (document.forms['frmPeers'].pwdpeer.value == document.forms['frmPeers'].pwdpeer1.value && document.forms['frmPeers'].pwdpeer.value != ""){
		
		var user  = document.forms['frmPeers'].userpeer.value;
		var prot  = document.forms['frmPeers'].protocolo.value;
		var dtmf  = document.forms['frmPeers'].dtmf.value;
		var pick  = document.forms['frmPeers'].pickup.value + "," + document.forms['frmPeers'].pickup2.value + "," + document.forms['frmPeers'].pickup3.value;;
		var email = document.forms['frmPeers'].email.value;
		var call  = document.forms['frmPeers'].callerid.value;
		var pwd   = document.forms['frmPeers'].pwdpeer.value;
		var pPer  = document.forms['frmPeers'].pwdpersonal.value;
		
		var radioLength = document.forms['frmPeers'].aBuzon.length;
		for(i=0; i < radioLength; i++)
		{
			if (document.forms['frmPeers'].aBuzon[i].checked)
				voice = document.forms['frmPeers'].aBuzon[i].value;
		}
		
		//Validaciones de extensiones
		if (user != "" && prot != "none" && call != "" && IsNumeric(user) && IsNumeric(pwd)){
			if (email == "" && confirm("Desea administrar esta extensi\xF3n sin email?")){
				//alert("tipo=guardarExt&user=" + escape(user) + "&prot=" + escape(prot) + "&dtmf=" + escape(dtmf) + "&pick=" + escape(pick) + "&email=" + escape(email) + "&call=" + escape(call) + "&pwd=" + escape(pwd) + "&pPer="+ escape(pPer));
				document.getElementById('txtPeers').innerHTML = "<br><img src='images/ajaxWait.gif'> Almacenando informaci\xF3n.";				
				http.open("GET", url + "tipo=guardarExt&user=" + escape(user) + "&prot=" + escape(prot) + "&dtmf=" + escape(dtmf) + "&pick=" + escape(pick) + "&email=" + escape(email) + "&call=" + escape(call) + "&pwd=" + escape(pwd) + "&pPer="+ escape(pPer) + "&voice="+ escape(voice) + "&dummy=" + Math.random(), true);					
				http.onreadystatechange = HttpPeers;
				http.send(null);
			}else {
				if ((email.indexOf(".") > 0) && (email.indexOf("@") > 0)){
					document.getElementById('txtPeers').innerHTML = "<br><img src='images/ajaxWait.gif'> Almacenando informaci\xF3n.";
					http.open("GET", url + "tipo=guardarExt&user=" + escape(user) + "&prot=" + escape(prot) + "&dtmf=" + escape(dtmf) + "&pick=" + escape(pick) + "&email=" + escape(email) + "&call=" + escape(call) + "&pwd=" + escape(pwd) + "&pPer="+ escape(pPer) + "&voice="+ escape(voice) + "&dummy=" + Math.random(), true);
					http.onreadystatechange = HttpPeers;
					http.send(null);
				}else
					alert("Su correo electr\xF3nico no es v\xE1lido.");				
			}			
		}					
		else
			alert("Existen datos no validos\nRecuerde que el usuario y la contrase\xF1a deben ser numericos.");
	}else if (document.forms['frmPeers'].pwdpeer.value == "")
		alert("La contrase\xF1a no es v\xE1lida.");
	else if (document.forms['frmPeers'].pwdpeer.value != document.forms['frmPeers'].pwdpeer1.value)
		alert("La contrase\xF1a no coincide.");
}

function getHTTPObject() {
  var xmlhttp;

  if(window.XMLHttpRequest){
	xmlhttp = new XMLHttpRequest();
  }
  else if (window.ActiveXObject){
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	if (!xmlhttp){
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}		   
  }
  return xmlhttp;
}
var http = getHTTPObject();
</script>
<div align="center" style="background: url(images/bkg.jpg); border: 1px solid #9D9D59">
	<div id="content" style="margin: 5px 5px 5px 5px">
	
	</div>
	
	<?php if (isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?>
	<br />
	<a name="admin"><hr width="90%" /></a>
	<br />
	<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em; width:99%">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px; text-align:center" align="center">
			<strong>.:: Administrador de extensiones ::.</strong>			
		</div>
	</div>	
	<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em; width:99%">
		<div style="margin: 3px 3px 3px 3px; text-align:left">
		<form id="frmPeers" name="frmPeers" style="display:inline">
		<table border="0" cellpadding="2" cellspacing="2" width="100%">
				<tr>
					<td width="15%">Protocolo</td>
					<td width="20%">
						<select id="protocolo" name="protocolo" style="width:145px; font-family:Verdana; font-size:11px" onchange="chkZap()">
							<option value="none">Seleccione uno</option>
							<option value="IAX2">IAX</option>
							<option value="SIP">SIP</option>
							<option value="DAHDI" disabled="disabled">An&aacute;logo</option>
						</select>
					</td>
					<td rowspan="7" valign="middle">
					<div style="margin: 7px 7px 7px 7px; color:#333333; text-align:justify" align="left">					
					<strong>Contrase&ntilde;a:</strong><br />
					En esta secci&oacute;n podr&aacute;s establecer la contrase&ntilde;a tanto del usuario del protocolo (SIP o IAX) 
					y la contrase&ntilde;a del buzón de voz.<br /><br />
					
					Para acceder al buz&oacute;n de voz marque a la extensi&oacute;n 7 y 
					su n&uacute;mero de extensi&oacute;n. <br />
					&nbsp;&nbsp;&nbsp;Ej: <strong><font color="#D8070D">7</font>102</strong><br /><br />
					<table cellpadding="0" cellspacing="2" border="0" width="100%">
                        <tr>
                            <td>Contrase&ntilde;a</td>
                            <td><input type="password" id="pwdpeer" name="pwdpeer" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
                            <td colspan="2" rowspan="2">
								<input type="button" onclick="javascript:guardar()" style="font-family:Verdana; font-size:12px" value="Guardar"/>
								<input type="button" onclick="javascript:limpiar()" style="font-family:Verdana; font-size:12px" value="Borrar"/>
								<input type="button" onclick="javascript:eliminar()" style="font-family:Verdana; font-size:12px" value="Eliminar"/>
							</td>
                        </tr>
                        <tr>
                            <td>Repita la contrase&ntilde;a</td>
							<td><input type="password" id="pwdpeer1" name="pwdpeer1" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
                            </td>							
                       </tr>
					</table>	
					</div>		
					</td>
				</tr>
				<tr>
					<td width="15%">Usuario</td>
					<td width="20%"><input type="text" id="userpeer" name="userpeer" style="width:140px; font-family:Verdana; font-size:12px" onchange="reZap()" /></td>
				</tr>
				<tr>
					<td width="15%">Caller ID</td>
					<td width="20%"><input type="text" id="callerid" name="callerid" style="width:140px; font-family:Verdana; font-size:12px" /></td>
				</tr>
				<tr>
					<td width="15%">Grupo de extensi&oacute;n</td>
					<td width="20%">
					<select id="pickup" name="pickup" style="width:46px; font-family:Verdana; font-size:12px">
                    	<option value="21">Grupo Recepci&oacute;n</option>
						<?php
							for ($i=1; $i<10; $i++){
								if ($i==1)
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
								else
									echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>					
                        
					</select>
                    <select id="pickup2" name="pickup2" style="width:46px; font-family:Verdana; font-size:12px">
                    <option value=""></option>
                    	<option value="21">Grupo Recepci&oacute;n</option>
						<?php
							for ($i=1; $i<10; $i++){
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>					
                        
					</select>
                    <select id="pickup3" name="pickup3" style="width:46px; font-family:Verdana; font-size:12px">
                    	<option value=""></option>
                    	<option value="21">Grupo Recepci&oacute;n</option>
						<?php
							for ($i=1; $i<10; $i++){
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>					
                        
					</select>
					</td>
				</tr>
				<tr>
					<td width="15%">Tipo de DTMF</td>
					<td width="20%">
					<select id="dtmf" name="dtmf" style="width:145px; font-family:Verdana; font-size:12px">
						<option value="rfc2833">RFC2833</option>
						<option value="inband">INBAND</option>
						<option value="info">INFO</option>
					</select>
					</td>
				</tr>
                <tr>
                	<td width="15%">Buz&oacute;n de Voz</td>
					<td width="20%"><input type="radio" name="aBuzon" id="aBuzon" value="A" />Activar 
                    <input type="radio" name="aBuzon" id="aBuzon" value="I" checked="checked" />Desactivar 
                    </td>
                </tr>
				<tr>
					<td width="15%">Buz&oacute;n de Correo</td>
					<td width="20%"><input type="text" id="email" name="email" style="width:140px; font-family:Verdana; font-size:12px" /></td>
				</tr>
                <tr>
                	<td>Contrase&ntilde;a personal</td>
					<td><input type="text" id="pwdpersonal" name="pwdpersonal" style="width:140px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
                </tr>
			</table>
		</form>
		</div>
	</div>
	<div id="txtPeers">
	</div>
		
	<?php }?>
	<br>
	Mejor visto con Mozilla Firefox 3.0 | Internet Explorer 6.0<br><br>
	<script>
	control();
</script>
