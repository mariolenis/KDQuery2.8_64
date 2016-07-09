<script language="javascript1.2" type="text/javascript">
var url = "serverSide.php?";
var identificador = "";
	
	function saveFax(){		
		var exten = 		document.frmFAX.exten.value;
		var nombre = 		document.frmFAX.nombre.value;
		var email = 		document.frmFAX.email.value;
		var telefono = 		document.frmFAX.fax.value;
		
		if (email == "" && confirm("Desea administrar esta extensi\xF3n sin email?")){
			document.getElementById('divFax').innerHTML = "<center><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br>";
			http.open("GET", url + "tipo=fax&exten=" + escape(exten) + "&nombre=" + escape(nombre) + "&email=" + escape(email) + "&telefono=" + escape(telefono) + "&dummy=" + Math.random(), true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
		}else {
			if ((email.indexOf(".") > 0) && (email.indexOf("@") > 0)){
				document.getElementById('divFax').innerHTML = "<center><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br>";
				http.open("GET", url + "tipo=fax&exten=" + escape(exten) + "&nombre=" + escape(nombre) + "&email=" + escape(email) + "&telefono=" + escape(telefono) + "&dummy=" + Math.random(), true);
				http.onreadystatechange = handleHttpResponse;
				http.send(null);
			} else
				alert("El correo electr\xF3nico no es v\xE1lido.");
		}				
	}
	
	function control(){	
		if (identificador > 0)
			clearInterval(identificador);		
		identificador = setInterval("hilo()", 3000);
		document.getElementById('faxes').innerHTML = "<center><img src='images/ajaxWait.gif'></center>"; 
	}
	
	function hilo(){
		http.open("GET", url + "tipo=faxLoad&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpResponse;
		http.send(null);
	}
	
	function limpiarConsola(){
		http.open("GET", url + "tipo=limpiarConsola&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpResponse;
		http.send(null)
	}	
	
	function handleHttpResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('divFax').innerHTML = results;
		  }
		}
	}
	
	function HttpResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('faxes').innerHTML = results;
		  }
		}
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
	var http = getHTTPObject(); // We create the HTTP Object
	
</script>
<div align="left" style="background: url(images/bkg.jpg); position: relative; top: -0.1em; border: 1px solid #9D9D59">
<table width="100%" style="font-family:Verdana; font-size:12px">
    <tr>
      <td width="45%" height="50px" valign="top">
	  	<div style="background: #740003; border: 1px solid #666666; margin: 0px 0px 3px 0px">
			<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
				<strong>.: Configuraci&oacute;n :.</strong>
			</div>
	   	</div>
		
		<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px">
			<?php if (isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){ ?>
			<div style="text-align:justify; color:#666666; margin-left:10px; margin-top:7px; margin-right:10px; margin-bottom:5px">En esta secci&oacute;n usted prodr&aacute; configurar los diferentes par&aacute;metros para el FAX virtual, <strong>la extensi&oacute;n telef&oacute;nica NO debe existir en la consola de extensiones,</strong> por defecto se usa la extension 39 pero puede ser cambiada por una de tres o cuatro digitos.</div> 
			<?php } else {?>
				<div style="text-align:justify; color:#CC0000; margin-left:10px; margin-top:7px; margin-right:10px; margin-bottom:5px">Es necesario que se autentique como usuario administrador para poder editar estas propiedades; consulte su manual de usuario para m&aacute;s detalles. <img src="images/fleche-d.gif" /> <a href='?s=10'><strong>LOGIN</strong></a><br /><br /></div> 
			<?php }  ?>	
			<hr width="95%" />	
			<?php
				$sSQL = "SELECT nombre, email, faxExten, telefono FROM opciones";
				$result = mysql_db_query(DATABASE, $sSQL);
				$linea = mysql_fetch_array($result);
			?>	
			<form id="frmFAX" name="frmFAX" style="display:inline">
			<center>
			<table width="350" border="0" cellpadding="2" cellspacing="0">
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Nombre de la Compa&ntilde;&iacute;a</td>
				<td align="left" colspan="2">
				<?php if (!isset($_SESSION["logged_user"]) || $_SESSION["logged_user"] != "true")
						$readonly = "readonly=\"true\"";
				 ?>
				<input name="nombre" <?php echo $readonly; ?> type="text" style="width:170px; font-family:Verdana; font-size:12px" value="<?php echo $linea['nombre']; ?>" />
				</td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">E-mail de destino</td>
				<td align="left" colspan="2">
				<?php 
					if (isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){ ?>
					<input name="email" type="text" style="width:170px; font-family:Verdana; font-size:12px" value="<?php echo $linea['email']; ?>" />
					<?php } else { ?>
					<input name="email" readonly="true" type="text" style="width:170px; font-family:Verdana; font-size:12px" value="***************" />
					<?php } ?>
				</td>				
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">N&uacute;mero de FAX</td>
				<td align="left">								
				<input name="fax" type="text" <?php echo $readonly; ?> style="width:70px; font-family:Verdana; font-size:12px" value="<?php echo $linea['telefono']; ?>" />
				</td>
				<td>
				<?php 
					if (!isset($_SESSION["logged_user"]) || $_SESSION["logged_user"] != "true")
						$disponible = "disabled=\"disabled\"";
				?>
				<input type="button" onclick="saveFax()" <?php echo $disponible;?> value="Actualizar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/></td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Extensi&oacute;n Telef&oacute;nica</td>
				<td align="left">
				<?php if (isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?>
                <input id="exten" name="exten" type="text" <?php echo $readonly; ?> style="width:70px; font-family:Verdana; font-size:12px" 
                value="<?php echo $linea['faxExten']; ?>" />
				
				<?php } ?>
				</td>
				<td><input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/></td>
			  </tr>
			  <tr>
			  	<td colspan="3" align="center">
					 
				<div id="divFax" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#666666; text-align:center">
					
				</div>
				</td>
			  </tr>
			</table>
			</form></center>
			</div></div>
		</td>
		<td valign="top">
			<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px;">
						<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
							.:: Detalle de FAX recibidos ::.
						</strong>
				</div></div>				
				<div style="border: 1px solid #999999;">
				<?php if (isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?>
				<div align="right" style="margin-right:15px; margin-top:10px">
					<a href='#' onclick="limpiarConsola()" style="text-decoration:none"> <strong>Limpiar</strong>
					<img src="images/del1.jpg" border="0" align="absmiddle"/></a> 
				</div>
				<?php } ?>
				<div id="faxes" style="margin: 10px 10px 10px 10px">					
				
				</center></div></div>
				
      </td>
    </tr>	
</table>
</div>
<script>
	control();
</script>