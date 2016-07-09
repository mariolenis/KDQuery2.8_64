<div align="left">
<script type="text/javascript" src="simpletreemenu.js"></script>

<link rel="stylesheet" type="text/css" href="css/simpletree.css" />

<script language="javascript1.2" type="text/javascript">

	var url = "serverSide.php?"
	
	function showExt(ext){
		document.getElementById('res').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		http.open("GET", url + "tipo=showExt&ext=" + escape(ext), true);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
	}
	
	function reglasFunc(){
		var am = 		document.reglas.am1.value + "-" + document.reglas.am2.value;
		var pm = 		document.reglas.pm1.value + "-" + document.reglas.pm2.value;
		var diaM = 		document.reglas.diaAM.value;
		var diaP = 		document.reglas.diaPM.value;
		var destino = 	document.reglas.dest.value + document.reglas.ext.value;
		var ivrDes = 	document.reglas.ivrDes.value;
		
		document.getElementById('reglasFun').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		http.open("GET", url + "tipo=reglas&am=" + escape(am) + "&pm=" + escape(pm) + "&diaM=" + escape(diaM) + "&diaP=" + escape(diaP) + "&destino=" + escape(destino) + "&ivrDes=" + escape(ivrDes), true);
		http.onreadystatechange = handleHttps;
		http.send(null);
	}
	
	function ivroptions(){
		var exten =  document.frmIVR.exten0.value + "|" + document.frmIVR.exten1.value + "|" + document.frmIVR.exten2.value + "|" + document.frmIVR.exten3.value + "|" + document.frmIVR.exten4.value + "|" + document.frmIVR.exten5.value + "|" + document.frmIVR.exten6.value + "|" + document.frmIVR.exten7.value + "|" + document.frmIVR.exten8.value + "|" + document.frmIVR.exten9.value;
		
		document.getElementById('res').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		http.open("GET", url + "tipo=ivr&exten=" + escape(exten), true);
		http.onreadystatechange = handleHttpResponse;
		http.send(null);		
	}
	
	function maxtime(){
		var tiempo  = 		document.tt.tiempo.value;
		document.getElementById('res').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		http.open("GET", url + "tipo=maxtime&tiempo=" + escape(tiempo));
		http.onreadystatechange = handleHttpResponse;
		http.send(null);
	}
	
	function acTrunck(campos){
	
		document.getElementById('resTroncal').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
	
		var prefijos = document.troncales;
		var nombre = document.troncales.nombre.value;
		var host = document.troncales.host.value;
		var tipo = document.troncales.type.value;
		var usuario = document.troncales.usuario.value;
		var password = document.troncales.pwd.value;
		var protocolo = document.troncales.protocolo.value;
		var prefijo = prefijos.pLocal.value  + "|" + prefijos.pNacional.value + "|" + prefijos.pCelular.value + "|" + prefijos.pInter.value;
		var dtmf = document.troncales.dtmf.value;
		var prio = document.troncales.prioridad.value;
		var call = document.troncales.callerid.value;			
		var perf = prefijos.p1.value + "|" + prefijos.p2.value + "|" + prefijos.p3.value;
		
		var contexto = "";		
		for (j=0; j < document.troncales.contexto.length; j++){
			if (document.troncales.contexto[j].checked)
				contexto += document.troncales.contexto[j].value + "|";
		}
		
		
		var valSelected = "";
		for (j=0; j < campos.length; j++){
			if (campos[j].checked)
				valSelected += campos[j].value + "|";
		}
		http.open("GET", url + "tipo=updateTroncal&nombre=" + nombre + "&host="+ host + "&type="+ tipo + "&usuario="+ usuario + "&password="+ password + "&protocolo="+ protocolo + "&prefijo="+ prefijo + "&dtmf="+ dtmf + "&contexto="+ contexto + "&codec="+ valSelected + "&prio=" + prio + "&clid=" + call + "&perfil=" + perf + "&dummy=" + Math.random(), true);
		http.onreadystatechange = troncalResponse;
		http.send(null);
		
	}
	
	function cargarTipo2(){
		var opt = document.forms['frmDID'].tipoTree.value;
		if (opt == "ext"){
			<?php
				$strSQL = "SELECT * FROM fax";
				$res2 = mysql_db_query(DATABASE,$strSQL);
				$fila1 = mysql_fetch_array($res2, MYSQL_ASSOC);
				$extFAX = $fila1['exten'];
				
				$strSQL = "SELECT protocolo, usuario FROM peer ORDER BY usuario";
				$res2 = mysql_db_query(DATABASE,$strSQL);
				$cadRes  = "<select id='peerIVR' name='peerIVR' style='font-family:verdana; font-size:12px; width:100px'>";
				$cadRes .= "<option value=''></option>";
				
				while($fila1 = mysql_fetch_array($res2, MYSQL_ASSOC)){
					if ($fila1['usuario'] != $extFAX)
						$cadRes .= "<option value='".$fila1['protocolo']."/".$fila1['usuario']."'>".$fila1['usuario']."</option>";
				}				
				$cadRes .= "<option value='".$extFAX."'>FAX ".$extFAX."</option></select>";
			?>
			document.getElementById('tNombre').innerHTML = "Extensi\xF3n";
			document.getElementById('tArch').innerHTML = "<?php echo $cadRes; ?>";
		}else {
			document.getElementById('tNombre').innerHTML = "";
			document.getElementById('tArch').innerHTML = "<b>CALLCENTER</b>";
		}
	}
	
	function cargarTipo(){
		var opt = document.forms['creacionIVR'].tipoTree.value;
		if (opt == "ext"){
			<?php
				$strSQL = "SELECT * FROM fax";
				$res2 = mysql_db_query(DATABASE,$strSQL);
				$fila1 = mysql_fetch_array($res2, MYSQL_ASSOC);
				$extFAX = $fila1['exten'];
				
				$strSQL = "SELECT protocolo, usuario FROM peer ORDER BY usuario";
				$res2 = mysql_db_query(DATABASE,$strSQL);
				$cadRes  = "<select id='peerIVR' name='peerIVR' style='font-family:verdana; font-size:12px; width:100px'>";
				$cadRes .= "<option value=''></option>";
				
				while($fila1 = mysql_fetch_array($res2, MYSQL_ASSOC)){
					if ($fila1['usuario'] != $extFAX)
						$cadRes .= "<option value='".$fila1['protocolo']."/".$fila1['usuario']."'>".$fila1['usuario']."</option>";
				}				
				$cadRes .= "<option value='FAX'>FAX ".$extFAX."</option></select>";
			?>
			document.getElementById('tituloNombre').innerHTML = "Seleccione una extensi\xF3n";
			document.getElementById('nombreArch').innerHTML = "<?php echo $cadRes; ?>";
			document.getElementById('tituloArchivo').innerHTML = "";
			document.getElementById('arch').innerHTML = "";
		}
		else if (opt == "gExt"){
			<?php
				$cadRes  = "<select id='grupoIVR' name='grupoIVR' style='font-family:verdana; font-size:12px; width:100px'>";
				$cadRes .= "<option value=''></option>";
				for($i=1; $i < 10; $i++)
				{
					$cadRes .= "<option value='".$i."'>".$i."</option>";
				}
				$cadRes .= "<option value='21'>Grupo Recepcion</option>";
				$cadRes .= "</select>";
			?>
			document.getElementById('tituloNombre').innerHTML = "Seleccione un grupo de llamada";
			document.getElementById('nombreArch').innerHTML = "<?php echo $cadRes; ?>";
			document.getElementById('tituloArchivo').innerHTML = "";
			document.getElementById('arch').innerHTML = "";
		}
		else if (opt == "audio"){
			document.getElementById('tituloNombre').innerHTML = "Nombre del archivo";
			document.getElementById('nombreArch').innerHTML = "<input id='nContexto' name='nContexto' type='text' style='font-family:verdana; font-size:12px; width:190px'>";
			document.getElementById('tituloArchivo').innerHTML = "Archivo de Audio";
			document.getElementById('arch').innerHTML = "<input type=\"file\" id=\"aSonido\" name=\"aSonido\" style=\"font-family:verdana; font-size:12px; width:200px\"/>";
		}else {
			document.getElementById('tituloNombre').innerHTML = "Esta es una cola de CALLCENTER";
			document.getElementById('nombreArch').innerHTML = "<input type='hidden' value='cola' id='siCola' name='siCola'>";
			document.getElementById('tituloArchivo').innerHTML = "";
			document.getElementById('arch').innerHTML = "";
		}		
	}
	
	function saveConf(conf){
		var cont = document.getElementById('conf').value;
		var th = confirm("Esta seguro de guardar los cambios?");		
		document.getElementById('res').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		if (th){			
			http.open("GET", url + "tipo=saveConf&conf=" + escape(conf) + "&cont=" + escape(cont), true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
		}else
			document.getElementById('res').innerHTML = "<center><br><br>Operaci&oacute;n cancelada</center><br><br>";
		
	}
	
	function cargaroptIVR(val){
		document.forms['creacionIVR'].idIVR.value = escape(val);
		document.getElementById('delIVRopt').innerHTML = "<a href='#ivrTag' style='text-decoration:none; color: blue' onclick=\"eliminarOPT('"+ escape(val) +"')\"> <img src='images/borrar.png' border='0'> Eliminar seleccionado</a>";
	}
	
	function eliminarOPT(opcion){
		document.getElementById('IVRTree').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		document.getElementById('delIVRopt').innerHTML = "";
		http.open("GET", url + "tipo=eliminarOPT&idivr=" + opcion + "&dummy=" + Math.random(), true);
		http.onreadystatechange = handleHttp;
		http.send(null);	
	}
	
	function cargarIVR(){
		document.getElementById('IVRTree').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";
		http.open("GET", url + "tipo=ivrTree&dummy=" + Math.random(), true);
		http.onreadystatechange = handleHttp;
		http.send(null);
	}
	
	function definitivoIVR(){
		document.getElementById('IVRTree').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Publicando IVR...</center><br><br>";
		http.open("GET", url + "tipo=generarIVR&dummy=" + Math.random(), true);
		http.onreadystatechange = handleHttp;
		http.send(null);
	}
	
	function setDID(){
		var did = document.frmDID.did.value;
		var part = document.frmDID.particion.value;
		var dstDID = "";
		if (document.frmDID.tipoTree.value == "ext")
			dstDID = document.frmDID.peerIVR.value;
		else
			dstDID = document.frmDID.tipoTree.value;
		
		document.getElementById('didres').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Alamcenando DID's...</center><br><br>";
		http.open("GET", url + "tipo=setDID&did=" + did + "&particion=" + part + "&dstDID="+ dstDID +"&dummy=" + Math.random(), true);
		http.onreadystatechange = didResponse;
		http.send(null);
	}
	
	function delDID(numero){
		document.getElementById('didres').innerHTML = "<center><br><br><img src=\"images/ajaxWait.gif\"><br>Eliminando DID...</center><br><br>";
		http.open("GET", url + "tipo=delDID&numero=" + numero + "&dummy=" + Math.random(), true);
		http.onreadystatechange = didResponse;
		http.send(null);
	}
	
	function cagarPerfil()
	{
		document.perfiles.interno.checked = "";
		document.perfiles.local.checked = "";
		document.perfiles.nacional.checked = "";
		document.perfiles.celular.checked = "";
		document.perfiles.inter.checked = "";
		
		var perfil = document.perfiles.idPerfil.value;
		if (perfil > 0 )
		{
			http.open("GET", url + "tipo=cargarPerfil&id=" + perfil + "&dummy=" + Math.random(), true);
			http.onreadystatechange = perfilResponse;
			http.send(null);
		}
	}
	
	function guardarPerfil()
	{
		var perfil = document.perfiles.idPerfil.value;
		if (perfil > 0 )
		{
			var sel = "";
			if (document.perfiles.interno.checked)
				sel += "interno,";
			if (document.perfiles.local.checked) 
				sel += "local,";
			if (document.perfiles.nacional.checked) 
				sel += "nacional,";
			if (document.perfiles.celular.checked)
				sel += "celular,";
			if (document.perfiles.inter.checked)
				sel += "inter,";
			
			http.open("GET", url + "tipo=guardarPerfil&id=" + perfil + "&perfiles=" + sel + "&dummy=" + Math.random(), true);
			http.onreadystatechange = perfilResponseGuardar;
			http.send(null);
		}
	}
	
	function perfilResponseGuardar() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
				alert(results);
		  }
		}
	}
	
	function perfilResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  perf = results.split(",");
			  for (i=0; i < perf.length; i++)
			  {
				if (perf[i] == "interno")
					document.perfiles.interno.checked = "checked"; 
				else if (perf[i] == "local")
					document.perfiles.local.checked = "checked"; 
				else if (perf[i] == "nacional")
					document.perfiles.nacional.checked = "checked"; 
				else if (perf[i] == "celular")
					document.perfiles.celular.checked = "checked"; 
				else if (perf[i] == "inter")
					document.perfiles.inter.checked = "checked"; 
			  }
		  }
		}
	}
	
	function handleHttpResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('res').innerHTML = results;
		  }
		}
	}
	
	function handleHttps() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('reglasFun').innerHTML = results;
		  }
		}
	}
	
	
	function troncalResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('resTroncal').innerHTML = results;
		  }
		}
	}
	
	function didResponse() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('didres').innerHTML = results;
		  }
		}
	}
	
	function handleHttp() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			  var results=http.responseText;
			  document.getElementById('IVRTree').innerHTML = results;
			  document.getElementById('delIVRopt').innerHTML = "";
			  ddtreemenu.createTree("arbolIVR", false, 5);
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

<table width="100%" border="0" style="font-family:Verdana; font-size:12px; background: url(images/bkg.jpg); border: 1px solid #9D9D59">
    <tr>
      <td width="50%" height="50px" valign="top">
	    <div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
			<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Horario para la entrada de llamadas :.</strong></div>
		</div>
		<div style="border: 1px solid #CCCCCC">
		<div style="margin:10px 5px 10px 5px; font-family:verdana; font-size:12px;">
		<?php
				
		$sSQL = "SELECT * FROM opciones";
		$result = mysql_db_query(DATABASE, $sSQL);
		$linea = mysql_fetch_array($result);
		$am = split("-",$linea['am']);
		$pm = split("-",$linea['pm']);
		$tiempo = $linea['tiempo'];
		?>	
			<form id="reglas" name="reglas" style="display:inline">
			<table width="95%" border="0" cellpadding="2" cellspacing="0" style="font-family:verdana; font-size:13px;">
			  <tr>
				<td width="50%" align="center" valign="top">
					Seleccione los d&iacute;as<br />
					<select id="diaAM" name="diaAM" style="font-family:verdana; font-size:12px; width:150px">
						<option value="mon-fri" <?php if ($linea['diaAM'] == "mon-fri") echo 'selected="selected"'; ?>>Lunes - Viernes</option>
						<option value="mon-sat" <?php if ($linea['diaAM'] == "mon-sat") echo 'selected="selected"'; ?>>Lunes - S&aacute;bado</option>
						<option value="mon-sun" <?php if ($linea['diaAM'] == "mon-sun") echo 'selected="selected"'; ?>>Lunes - Domingo</option>
					</select>
				</td>
				<td width="15%" align="center" valign="top">
					Desde<br />
					<input type="text" id="am1" name="am1" style="font-family:verdana; font-size:12px; width:40px; text-align:center" maxlength="5" value="<?php echo $am[0]; ?>"/>
				</td>
				<td width="15%" align="center" valign="top">
					Hasta<br />
					<input type="text" id="am2" name="am2" style="font-family:verdana; font-size:12px; width:40px; text-align:center" maxlength="5" value="<?php echo $am[1]; ?>"/>
				</td>
				<td align="center" ><img src="images/am.jpg" border="0" /></td>			
			  </tr>			  
			</table>
			<center>
			<hr / width="96%">
			</center>
			<table width="95%" border="0" cellpadding="2" cellspacing="0" style="font-family:verdana; font-size:13px;">
			  <tr>
				<td width="50%" align="center" valign="top">
					Seleccione los d&iacute;as<br />
					<select id="diaPM" name="diaPM" style="font-family:verdana; font-size:12px; width:150px">
						<option value="mon-fri" <?php if ($linea['diaPM'] == "mon-fri") echo 'selected="selected"'; ?>>Lunes - Viernes</option>
						<option value="mon-sat" <?php if ($linea['diaPM'] == "mon-sat") echo 'selected="selected"'; ?>>Lunes - S&aacute;bado</option>
						<option value="mon-sun" <?php if ($linea['diaPM'] == "mon-sun") echo 'selected="selected"'; ?>>Lunes - Domingo</option>
					</select>
				</td>
				<td width="15%" align="center">
					Desde<br />
					<input type="text" id="pm1" name="pm1" style="font-family:verdana; font-size:12px; width:40px; text-align:center" maxlength="5" value="<?php echo $pm[0]; ?>"/>
				</td>
				<td width="15%" align="center">
					Hasta<br />
					<input type="text" id="pm2" name="pm2" style="font-family:verdana; font-size:12px; width:40px; text-align:center" maxlength="5" value="<?php echo $pm[1]; ?>"/>
				</td>
				<td align="center"><img src="images/pm.jpg" border="0" /></td>			
			  </tr>			  
			</table>
			
			<hr width="96%"/><center>
			<div style="width:370px; text-align:justify">
			<strong>Destino de las llamadas Entrantes</strong><br /> 
			<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#666666">
			Por favor defina la el destino de las llamadas en caso de que el usuario no presione ninguna opci&oacute;n del IVR principal.
			<br /><br /></div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="27%" style="font-size:12px"> Por defecto: </td>
					<td>
						<select id="ivrDes" name="ivrDes" style="font-family:verdana; font-size:12px; width:170px">
							<option value="rep" <?php if ($linea['ivr'] == "rep") echo 'selected="selected"'; ?>>Repetir Audiomensaje</option>
                            <option value="g21" <?php if ($linea['ivr'] == "g21") echo 'selected="selected"'; ?>>Grupo Recepci&oacute;n</option>
						<?php
							$strSQLA = "SELECT protocolo, usuario FROM peer ORDER BY usuario";
							$res2 = mysql_db_query(DATABASE,$strSQLA);
							while($fila1 = mysql_fetch_array($res2, MYSQL_ASSOC)){
								if ($linea['ivr'] == $fila1['usuario'])
									echo "<option value='".$fila1['usuario']."' selected='selected'>Extensi&oacute;n ".$fila1['usuario']."</option>";
								else
									echo "<option value='".$fila1['usuario']."'>Extensi&oacute;n ".$fila1['usuario']."</option>";
							}				
						?>
											
						</select>
					</td>
				</tr>				
			</table>				
			</div>
			<hr width="96%">
			<div style="width:370px; text-align:justify">	
			<strong>Si las llamadas no se originan dentro de &eacute;ste horario:</strong>
			<br />
			<br>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="27%" style="font-size:12px">Destino:</td>
					<td style="font-size:12px">
			<select id="dest" name="dest" style="font-family:verdana; font-size:12px; width:170px">
				<option value="e" <?php if (substr($linea['destino'],0,1) == "e") echo 'selected="selected"'; ?>>Marcar a la extensi&oacute;n...</option>
				<option value="u" <?php if (substr($linea['destino'],0,1) == "u") echo 'selected="selected"'; ?>>Directo al buz&oacute;n de..</option>
				<option value="n" <?php if (substr($linea['destino'],0,1) == "n") echo 'selected="selected"'; ?>>Finalizar llamada</option>
			</select>
			
			&nbsp;&nbsp;&nbsp;Ext. 
			<input type="text" id="ext" name="ext" style="font-family:verdana; font-size:12px; width:35px; text-align:center" maxlength="4" value="<?php echo substr($linea['destino'],1); ?>"/>	
					</td>
				</tr>
			</table>
			<br />
				<div align="right">
				<input type="button" value="Guardar Reglas" onclick="reglasFunc()"  />&nbsp;&nbsp;&nbsp;
				</div>
			</div>
			<div id="reglasFun" style="font-size:11px; color:#666666">
			
			</div>
			</form>
			</center>
		</div>		
		</div>	
		
		<div style="background: #740003; border: 1px solid black; margin: 5px 0px 3px 0px">
			<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
				<strong>.: Configuraci&oacute;n de Troncales Digitales:.</strong>
			</div>
		</div>	
		<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; border: 1px solid #CCCCCC; margin-bottom:3px" >
			<center>
			<form style="display:inline" name="troncales" id="troncales">
			<table border="0" width="96%" cellpadding="0" cellspacing="3" style="text-align:left">
			  <tr>
				<td>Nombre</td>
				<td>
				<input type="text" id="nombre" name="nombre" style="width:80px; font-family:Verdana; font-size:12px"/>
				<a href="javascript:void(0);" NAME="Seleccione un proveedor" title="Seleccione un proveedor" onClick=window.open("child.php","Ratting","width=250,height=170,left=150,top=200,toolbar=0,status=0,location=no,scrollbar=auto");>
				<img src="images/find.jpg" border="0" align="absbottom" /></a>
				</td>
                <td>Prioridad </td>
				<td><select id="prioridad" name="prioridad" style="width:95px; font-family:Verdana; font-size:12px">
					<?php for ($i=1; $i < 11; $i++)
					{
						echo "<option value=\"".$i."\">".$i."</option>\n";
					}
					?>
				</select></td>	
			  </tr>
			  <tr>
                <td>Tipo</td>
				<td><select id="type" name="type" style="width:115px; font-family:Verdana; font-size:12px">
					<option value="peer">Peer</option>
					<option value="friend">Friend</option>
				</select></td>
              	<td>Protocolo </td>
				<td><select id="protocolo" name="protocolo" style="width:95px; font-family:Verdana; font-size:12px">
					<option value="SIP">SIP</option>
					<option value="IAX2">IAX</option>
					<option value="dahdi">An&aacute;logo</option>
				</select></td>			  
              </tr>
              	<td>Host</td>
				<td><input type="text" id="host" name="host" style="width:110px; font-family:Verdana; font-size:12px"/></td>
                <td>Caller ID</td>
				<td><input type="text" id="callerid" name="callerid" style="width:90px; font-family:Verdana; font-size:12px"/></td>
              </tr>
			  <tr>
              	<td>Usuario</td>
				<td><input type="text" id="usuario" name="usuario" style="width:110px; font-family:Verdana; font-size:12px"/></td>
				<td>Contrase&ntilde;a</td>
				<td><input type="password" id="pwd" name="pwd" style="width:90px; font-family:Verdana; font-size:12px"/></td>
			  </tr>
              <tr>		
				<td>Perfiles</td>
				<td>
                <select id="p1" name="p1" style="width:36px; font-family:Verdana; font-size:12px">
					<option value=""></option>
					<option value="1">1</option>
					<option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="*">Todos</option>
				</select>
                
                <select id="p2" name="p2" style="width:36px; font-family:Verdana; font-size:12px">
					<option value=""></option>
					<option value="1">1</option>
					<option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
				</select>
                
                <select id="p3" name="p3" style="width:36px; font-family:Verdana; font-size:12px">
					<option value=""></option>
					<option value="1">1</option>
					<option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
				</select>
                </td>
                <td >Tipo de DTMF</td>
				<td><select id="dtmf" name="dtmf" style="width:95px; font-family:Verdana; font-size:12px">
					<option value="rfc2833">RFC2833</option>
					<option value="inband">INBAND</option>
					<option value="info">INFO</option>
				</select></td>
			  </tr>              									  
			  <tr>
				<td>Codec</td>
				<td colspan="3">
	                <input type="checkbox" name="codec" id="codec" value="g729"/> g729
					<input type="checkbox" name="codec" id="codec" value="alaw"/> aLaw
                    <input type="checkbox" name="codec" id="codec" value="ulaw" /> uLaw
					<input type="checkbox" name="codec" id="codec" value="gsm"/> gsm
				</td>				
			  </tr>			  
			  <tr>
				<td colspan="4" style="color:#666666">
				<hr width="98%" />
					Seleccione el/los contexto(s) y Prefijo(s) a usar para el cual desea que se aplique esta troncal de telefon&iacute;a.
				</td>				
			  </tr>
			  <tr>				
				<td colspan="4">
                <table width="100%">
                	<tr>
                    	<td>
					<input type="checkbox" name="contexto" id="contexto" value="local">
                    <input type="text" id="pLocal" name="pLocal" style="width:30px; font-family:Verdana; font-size:12px; 
                    margin-bottom:2px"/>
                    Local<br />
					<input type="checkbox" name="contexto" id="contexto" value="inter">
                    <input type="text" id="pInter" name="pInter" style="width:30px; font-family:Verdana; font-size:12px"/>
                    Internacional
                        </td>
                        <td >
					<input type="checkbox" name="contexto" id="contexto" value="nacional"> 
                    <input type="text" id="pNacional" name="pNacional" style="width:30px; font-family:Verdana; font-size:12px; 
                    margin-bottom:2px"/> 
                    Nacional<br />
					<input type="checkbox" name="contexto" id="contexto" value="celular"> 
                    <input type="text" id="pCelular" name="pCelular" style="width:30px; font-family:Verdana; font-size:12px"/> 
                    Celular
					
                        </td>	
                        <td align="right" valign="bottom">
				<input type="button" value="Actualizar" 
				onclick="acTrunck(document.troncales.codec)" style="font-family:Verdana; font-size:12px; width:90px; height:23px" /><br />
				<input type="reset" value="Borrar" style="font-family:Verdana; width:90px; font-size:12px; height:23px"/></td>
			  </tr>
              </table>
              </td>
              </tr>
			</table>
			</form>
			<div id="resTroncal">
			</div>
			<br />
			</center>
		</div>	
		
		<div style="background: #740003; border: 1px solid black; margin: 5px 0px 3px 0px">
			<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Configuraci&oacute;n de DID's :.</strong></div>
		</div>
		<div style="border: 1px solid #CCCCCC">
		<div style="margin:5px 5px 5px 5px; font-family:verdana; font-size:12px; text-align:justify">		
		<center>
		<form id="frmDID" name="frmDID" style="display:inline">
	  <table border="0" cellpadding="2" cellspacing="0" width="94%">
	  <tr>
		<td style="font-family:verdana; font-size:12px; color:#000000" align="left">DID</td>
		<td align="center"><input type="text" id="did" name="did" style="width:92px; font-family:Verdana; font-size:12px"/></td>
        <td style="font-family:verdana; font-size:12px; color:#000000" align="right">Op. por defecto</td>
		<td align="center">
        <select id="tipoTree" name="tipoTree" style="font-family:verdana; font-size:12px; width:100px" onchange="cargarTipo2()">
        <option value="ext">Extensi&oacute;n</option>
        <?php
        $tempVar = split("\n",shell_exec('cat cmds/queues.conf | grep ]'));												
        //$tempVar = str_replace("[","",$tempVar);
        //$tempVar = str_replace("]","",$tempVar);
                                    
        for ($i=0; $i < count($tempVar); $i++){
            if (substr($tempVar[$i],1,7) != "general" && trim($tempVar[$i]) != "")
                echo "\t<option value=\"".substr($tempVar[$i],1,(strpos($tempVar[$i],"]")-1))."\">".substr($tempVar[$i],1,(strpos($tempVar[$i],"]")-1))."</option>\n";

        }
        ?>							
    </select>
        
		</td>		        		        
	  </tr>
	  <tr>	
      	<td style="font-family:verdana; font-size:12px; color:#000000" align="left">Partici&oacute;n</td>
		<td align="center">
		<select id="particion" name="particion" style="width:95px; font-family:Verdana; font-size:12px; height:19px">
			<option value="s">Principal</option>
            <?php
				for ($i=701; $i < 720; $i++)
				{
					echo "<option value=\"".$i."\">".$i."</option>\n";
				}
			?>
		</select>
        </td>
        	<td align="right"><div style="font-family:verdana; font-size:12px" id="tNombre"></div></td>
			<td valign="bottom" align="center" style="font-family:verdana; font-size:12px"><div id="tArch"></div>
        </td>	        
	  </tr>
      <tr>
      	<td colspan="4" align="right">
        	<hr />
            <input type="reset" value="Borrar" style="font-family:Verdana; width:100px; font-size:12px; height:25px" />
			<input type="button" onclick="javascript:setDID()" value="Guardar DID" style="font-family:Verdana; width:100px; font-size:12px; height:25px" />             
		</td>
      </tr>
	  </table>
	  </form>
	  <hr width="95%" />
      <script>cargarTipo2()</script>
	  <div id="didres">      
	  <?php
	  	$sSQL = "SELECT * FROM did ORDER BY numero";
		$result = mysql_db_query(DATABASE, $sSQL);
		echo "<table border=0 cellspacing=0 cellpadding=2 width='90%'>
			<tr>
				<td align='center'><strong>No.</strong></td>			
				<td><strong>N&uacute;mero DID</strong></td>
				<td align='center'><strong>Destino</strong></td>
				<td align='center'><strong>Op. Defecto</strong></td>
				<td></td>
			</tr>
		";
		$cont=1;
		while($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			echo "<tr>
				<td align='center'>$cont</td>
				<td>".$linea['numero']."</td>
				<td align='center'>".$linea['particion']."</td>
				<td align='center'>".str_replace("/", " <img src='images/fleche-d.gif'> ", $linea['dst'])."</td>
				<td>
					<a href='#didTag' onclick=\"javascript:delDID('".$linea['numero']."')\" ><img src='images/delete.png' border=0 title='Eliminar este DID'></a>
				</td>
			</tr>";
			$cont++;
		}
		echo "</table><br>";
	  ?>
	  </div>
	  <a name="didTag"></a>
	  </center>
	  </div>
	  </div>	    
	  </td>
	  <td rowspan="5" valign="top">	  	
		<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>.: Audios para el IPBX :.</strong></div></div>
		<div style="border: 1px solid #CCCCCC; ">
			<div style="margin-bottom:10px; margin-left:10px; margin-right:10px; margin-top:10px; text-align:left">				
				
				<center>
				<form id="archivos" name="archivos" action="" method="post" style="display:inline" target="fileCont" enctype="multipart/form-data">
				<table border="0" cellspacing="1" cellpadding="0">								
				  <tr>
					<td colspan="3" height="19" style="font-size:12px; background:#E3E1D5; border:1px solid #CCCCCC" align="left">
						&nbsp;&nbsp;Audio <img src="images/fleche-d.gif" /> <strong>AUDIO ENTRANTE</strong></td>
				  </tr>
				  <tr>
					<td><input type="file" style="font-family:Verdana; font-size:13px" name="pbxintro" id="pbxintro" size="18"/></td>
					<td style="width:32px" align="left">
					<input type="image" src="images/ok.jpg" border="0" align="absmiddle" title="Subir nuevo archivo">
					</td>
					<td style="font-size:12px">
					<embed src='cmds/ivr/Entrante.wav' autostart=false volume='100' align="absmiddle" style='height: 16px; width: 100px'></embed></td>
				  </tr>
				  <tr>
				  	<td colspan="4">
					<hr width="97%"/>
					</td>
				  </tr>
				  <tr>
					<td colspan="3" height="19" style="font-size:12px; background:#E3E1D5; border:1px solid #CCCCCC" align="left">
						&nbsp;&nbsp;Audio <img src="images/fleche-d.gif" /> <strong>FUERA DE HORARIO</strong></td>
				  </tr>
				  <tr>
					<td><input type="file" style="font-family:Verdana; font-size:13px" name="noHorario" id="noHorario" size="18"/></td>
					<td style="width:32px" align="left">
					<input type="image" src="images/ok.jpg" border="0" align="absmiddle" title="Subir nuevo archivo">
					</td>
					<td style="font-size:12px">
					<embed src='cmds/ivr/noHorario.wav' autostart=false volume='100' align="absmiddle" style='height: 16px; width: 100px'></embed></td>
				  </tr>
				  <tr>
				  	<td colspan="4">
					<hr width="97%"/>
					</td>
				  </tr>
				  <tr>
					<td colspan="3" height="19" style="font-size:12px; background:#E3E1D5; border:1px solid #CCCCCC" align="left">
						&nbsp;&nbsp;Audio <img src="images/fleche-d.gif" /> <strong>MUSICA EN ESPERA</strong></td>
				  </tr>
				  <tr>
					<td><input type="file" style="font-family:Verdana; font-size:13px" name="espera" id="espera" size="18"/>
						<iframe name="fileCont" width="1" height="1" style="visibility:hidden"></iframe>
					</td>
					<td style="width:32px" align="left">
					<input type="image" src="images/ok.jpg" border="0" align="absmiddle" title="Subir nuevo archivo">
					</td>
					<td style="font-size:12px">
					<embed src='cmds/hold/wait.wav' autostart=false volume='100' align="absmiddle" style='height: 16px; width: 100px'></embed></td>
				  </tr>
				</table>
				</form>						
				</center>		
			</div>
		</div>
		
		<div style="background: #740003; border: 1px solid black; margin-top:5px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px; font-size:11px" align="center"><strong>.: Tiempos :.</strong></div></div>
		<div style="border: 1px solid #CCCCCC; margin: 3px 0px 3px 0px;">
			<div style="margin:10px 5px 10px 5px; text-align:center">
			<form id="tt" name="tt" style="display:inline">
			Tiempo de duraci&oacute;n m&aacute;xima (segundos)  
			<input type="text" id="tiempo" name="tiempo" style="font-family:verdana; font-size:12px; width:45px; text-align:center" maxlength="6" value="<?php echo $tiempo; ?>" />
			<a href="#" onclick="javascript:maxtime()">
			<img src="images/ok.jpg" align="absmiddle" border="0" title="Guardar regla">
			</a>
			</form>
			</div>
		</div>
        
        <div style="background: #740003; border: 1px solid black; margin-top:5px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px; font-size:11px" align="center"><strong>.: Perfiles & Permisos de llamada :.</strong></div></div>
        <div style="border: 1px solid #CCCCCC; margin: 3px 0px 3px 0px;">
			<div style="margin:10px 5px 10px 5px;"><center>
			<form id="perfiles" name="perfiles" style="display:inline">
				<table border="0" width="80%">
                	<tr>
                    	<td align="left">Seleccione un perfil 
                        <select onchange="cagarPerfil()" name="idPerfil" id="idPerfil">
                        	<option value="-1">...</option>
                        	<?php
								for ($i=1; $i < 10; $i++)
								{
									echo "<option value='".$i."'>".$i."</option>\n";
								}
							?>
                        </select>
                        <br /><br /><center>
                        <input type="button" value="Guardar Cambios" onclick="guardarPerfil()" /></center>
                        </td>
                        <td align="left" valign="top">
                        <input type="checkbox" id="interno" name="interno" value="interno" />Interno<br />
                        <input type="checkbox" id="local" name="local" value="local" />Local<br />
						<input type="checkbox" id="nacional" name="nacional" value="nacional" />Nacional
                        </td>
                        <td align="left" valign="top">
						<input type="checkbox" id="celular" name="celular" value="celular" />Celular<br />
						<input type="checkbox" id="inter" name="inter" value="inter" />Internacional
                        </td>
                    </tr>
                </table>
			</form></center>
			</div>
		</div>
        
        
	  
	  	<div style="background: #740003; border: 1px solid black; margin: 5px 0px 3px 0px">
			<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Personalizaci&oacute;n para el IVR :.</strong></div>
		</div>
		<div style="border: 1px solid #CCCCCC">
		<div style="margin:10px 5px 10px 5px; font-family:verdana; font-size:11px; color:#666666">				
		En esta secci&oacute;n podr&aacute; definir el redireccionamiento del IVR asociado al mensaje de bienvenida del IPBX y sus ramificaciones.		
		</div>
		
		<center>
		<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:95%; border: 1px solid #CCCCCC;">
			<div style="margin: 3px 3px 3px 3px">
			<form id="creacionIVR" name="creacionIVR" style="display:inline" method="post" action="" target="ivrCont" enctype="multipart/form-data">
				<table border="0" cellpadding="0" cellspacing="5" width="90%">
					<tr>
						<td align="left">Contexto</td>
						<td align="left">
						<input type="hidden" id="idIVR" name="idIVR" value="" />
						<select id="context" name="context" style="font-family:verdana; font-size:12px; width:100px">
							<option value="Entrante">Entrante</option>
						<?php 
							$sSQL = "SELECT destino FROM ivr WHERE destino LIKE 'Goto%' AND destino NOT LIKE 'Goto(FAX%' ORDER BY destino";
							$res1 = mysql_db_query(DATABASE,$sSQL);
							while ($fila = mysql_fetch_array($res1, MYSQL_ASSOC)){
								echo "<option value=\"".substr($fila['destino'],5,strpos($fila['destino'],",")-5)."\" >".substr($fila['destino'],5,strpos($fila['destino'],",")-5)."</option>";
							}
						?>
						</select>
						</td>
						<td align="left">Opci&oacute;n en IVR</td>
						<td align="left">
							<select id="optIVR" name="optIVR" style="font-family:verdana; font-size:12px; width:50px">
								<?php
								for ($i=0; $i<10; $i++){
									echo "<option value=".$i.">".$i."</option>";
								}
								?>
								<option value="701">701</option>
								<option value="702">702</option>
								<option value="703">703</option>
								<option value="704">704</option>
                                <option value="705">705</option>
								<option value="706">706</option>
							</select>
						</td>						
					</tr>
					<tr>
						<td align="left">Tipo de opci&oacute;n</td>
						<td align="left">
						<select id="tipoTree" name="tipoTree" style="font-family:verdana; font-size:12px; width:100px" onchange="cargarTipo()">
							<option value="ext">Extensi&oacute;n</option>
							<option value="gExt">Grupo</option>
							<option value="audio">Audio</option>							
							<?php
							$tempVar = split("\n",shell_exec('cat cmds/queues.conf | grep ]'));												
							//$tempVar = str_replace("[","",$tempVar);
							//$tempVar = str_replace("]","",$tempVar);
														
							for ($i=0; $i < count($tempVar); $i++){
								if (substr($tempVar[$i],1,7) != "general" && trim($tempVar[$i]) != "")
									echo "\t<option value=\"".substr($tempVar[$i],1,(strpos($tempVar[$i],"]")-1))."\">".substr($tempVar[$i],1,(strpos($tempVar[$i],"]")-1))."</option>\n";

							}
							?>							
						</select>
						</td>
						<td colspan="2">
							<iframe name="ivrCont" width="1" height="1" style="visibility:hidden"></iframe>
						</td>
					</tr>
					
					<tr>
						<td colspan="4"><center>
						<hr width=\"100%\">
							<table border="0" cellpadding="0" cellspacing="5">
								<tr>
									<td align="left"><div style="font-family:verdana; font-size:12px" id="tituloNombre"></div></td>
									<td valign="bottom" align="left" style="font-family:verdana; font-size:12px"><div id="nombreArch"></div></td>									
								</tr>
								<tr>
									<td align="left" style="font-family:verdana; font-size:12px"><div id="tituloArchivo"></div></td>
									<td align="left" style="font-family:verdana; font-size:12px"><div id="arch"></div></td>
								</tr>
							</table>
							<div id="ivrtipo" style="text-align:left; width:100%">
								<script>cargarTipo()</script>
							</div></center>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="right">
							<hr width=\"100%\">
							<input type="submit" value="Generar" /> <input type="reset" value="Borrar" /> 
							<input type="button" value="Publicar IVR" 
							onclick="javascript:definitivoIVR();document.getElementById('delIVRopt').innerHTML = '';parent.document.getElementById('res').innerHTML='';"/>
							<!-- onclick="window.location.reload() -->
						</td>
					</tr>
				</table>
			</form>
			<?php
				
				if($_FILES['pbxintro'] && $_FILES['pbxintro']['tmp_name'] != ""){	
                                        $ext = substr($_FILES['pbxintro']['name'], strripos($_FILES['pbxintro']['name'], "."));
					$tmp = $_FILES['pbxintro']['tmp_name'];
					$name = "cmds/ivr-tmp/Entrante".$ext; //$_FILES['archivo']['name'];				
					if(move_uploaded_file($tmp, $name)){
						echo '
						<script>
							parent.document.getElementById("res").innerHTML="<br><br><b><center><strong>Mensaje de PBX IVR subido correctamente...</strong></center><br><br>";	
							setTimeout("location.href=window.location.href+\'?s=7\'",1000);				
						</script>';
					}
					else{
					echo '
							<script>
								parent.document.getElementById("res").innerHTML="<br><br><b><center>El archivo no pudo subirse.</center>";													
							</script>';
					}
				}elseif ($_FILES['noHorario'] && $_FILES['noHorario']['tmp_name'] != ""){
                                        $ext = substr($_FILES['noHorario']['name'], strripos($_FILES['noHorario']['name'], "."));
					$tmp = $_FILES['noHorario']['tmp_name'];
					$name = "cmds/ivr-tmp/noHorario".$ext; //$_FILES['archivo']['name'];				
					if(move_uploaded_file($tmp, $name)){
						echo '
						<script>
							parent.document.getElementById("res").innerHTML="<br><br><b><center><strong>Audio del horario de atenci&oacute;n subido correctamente<br><br>.</strong></center>";	
							setTimeout("location.href=window.location.href+\'?s=7\'",1000);				
						</script>';
					}
					else{
					echo '
							<script>
								parent.document.getElementById("res").innerHTML="<br><br><b><center>El archivo no pudo subirse.</center>";					
							</script>';
					}
				}elseif ($_FILES['espera'] && $_FILES['espera']['tmp_name'] != ""){	
                                        $ext = substr($_FILES['espera']['name'], strripos($_FILES['espera']['name'], "."));
					$tmp = $_FILES['espera']['tmp_name'];
					$name = "cmds/ivr-tmp/wait".$ext; //$_FILES['archivo']['name'];				
					if(move_uploaded_file($tmp, $name)){
						echo '
						<script>
							parent.document.getElementById("res").innerHTML="<br><br><b><center><strong>Audio de espera subido</strong></center><br><br>";	
							setTimeout("location.href=window.location.href+\'?s=7\'",1000);				
						</script>';
					}
					else{
					echo '
							<script>
								parent.document.getElementById("res").innerHTML="<br><br><b><center>El archivo no pudo subirse.</center>";					
							</script>';
					}
				}
				elseif ($_FILES['aSonido'] && $_FILES['aSonido']['tmp_name'] != ""){
					//echo "here";	
					$tmp = $_FILES['aSonido']['tmp_name'];
                                        $ext = substr($_FILES['aSonido']['name'], strripos($_FILES['aSonido']['name'], "."));
					$name = "cmds/ivr-tmp/".$_POST['nContexto'].$ext;
					
					echo '<script>parent.document.getElementById("res").innerHTML="<center><br><br><img src=\"images/ajaxWait.gif\"><br>Un Momento por favor...</center><br><br>";</script>';
									
					if(move_uploaded_file($tmp, $name)){
						$ssSQL = "INSERT INTO ivr (opcion, contexto, destino) VALUES (".$_POST['optIVR'].", '".$_POST['context']."','Goto(".$_POST['nContexto'].",s,1)')";
						mysql_db_query(DATABASE,$ssSQL);
						echo '
						<script>							
							parent.document.getElementById("res").innerHTML="<br><br><b><center>Audio Cargado con &eacute;xito</center></b><br>";
							parent.document.getElementById("delIVRopt").innerHTML = "";	
							definitivoIVR();							
						</script>';
					}
					else{
					echo '
							<script>
								parent.document.getElementById("res").innerHTML="<br><br><center>El archivo no pudo subirse.</center>";					
							</script>';
					}
				}
				elseif (isset($_POST['peerIVR']) && $_POST['peerIVR'] != ""){
					if ($_POST['peerIVR'] == "FAX")
						$ssSQL = "INSERT INTO ivr (opcion, contexto, destino) VALUES (".$_POST['optIVR'].", '".$_POST['context']."','Goto(".$_POST['peerIVR'].",s,1)')";
					else																
					{
						$ssSQL = "INSERT INTO ivr (opcion, contexto, destino) VALUES (".$_POST['optIVR'].", '".$_POST['context']."','Macro(marcacionInterna,".substr($_POST['peerIVR'],strpos($_POST['peerIVR'],"/")+1).",R)')";
					}
				mysql_db_query(DATABASE,$ssSQL);
					echo '
						<script>							
							parent.document.getElementById("res").innerHTML="<br><br><b><center>Extensi&oacute;n agregada con &eacute;xito</center></b><br><br>";
							parent.document.getElementById("delIVRopt").innerHTML = "";	
							definitivoIVR();							
						</script>';
				}
				elseif (isset($_POST['grupoIVR']) && $_POST['grupoIVR'] != ""){	
					// Buscar todas las extensiones con ese grupo;

					$cadGrupo = "";
					$sSQLtemp = "SELECT protocolo, usuario FROM peer WHERE (pickup like '%".$_POST['grupoIVR'].",%' OR pickup LIKE '%,".$_POST['grupoIVR']."') AND pickup NOT LIKE '%21%' ORDER BY usuario";
					$rr = mysql_db_query(DATABASE,$sSQLtemp);
					while ($datos = mysql_fetch_array($rr)){
						$cadGrupo .= $datos["protocolo"]."/".$datos["usuario"]."&";
					}
					$cadGrupo = substr($cadGrupo,0,strlen($cadGrupo)-1);
					
					$ssSQL = "INSERT INTO ivr (opcion, contexto, destino) VALUES (".$_POST['optIVR'].", '".$_POST['context']."','Macro(marcacionGrupo,".str_replace(" ","",$cadGrupo).",Noop)')";
					mysql_db_query(DATABASE,$ssSQL);
					echo '
						<script>							
							parent.document.getElementById("res").innerHTML="<br><br><b><center>Grupo de llamada agregado con &eacute;xito</center></b><br><br>";
							parent.document.getElementById("delIVRopt").innerHTML = "";	
							definitivoIVR();							
						</script>';
				}
				elseif (isset($_POST['siCola']) && $_POST['siCola'] != ""){
					$ssSQL = "INSERT INTO ivr (opcion, contexto, destino) VALUES (".$_POST['optIVR'].", '".$_POST['context']."','Queue(".$_POST['tipoTree'].",tThH)')";
					mysql_db_query(DATABASE,$ssSQL);
					echo '
						<script>							
							parent.document.getElementById("res").innerHTML="<br><br><b><center>Cola de llamadas agregada con &eacute;xito</center></b><br><br>";
							parent.document.getElementById("delIVRopt").innerHTML = "";	
							definitivoIVR();							
						</script>';
				}
				
				?>
			</div>
		</div>
		
		<div id="IVRTree" name="IVRTree" style="font-family:Verdana; font-size:12px; border: 1px solid #CCCCCC; margin-top:5px; width:95%; text-align:left">
			<script>
				cargarIVR();
			</script>			
		<!-- aqui va el formulario -->
		</div>
		<a name="ivrTag"></a>
		<div id="delIVRopt" style="font-family:Verdana; font-size:12px; margin-top:5px; width:95%; text-align:right">
		
		</div>
		</center>
		<hr width="95%" />
		<div id="res">
		</div>
	</td>
	</tr>
</table>