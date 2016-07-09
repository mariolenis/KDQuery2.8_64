<?php
if(isset($_GET['optc']) && $_GET['optc'] == 'pwdChanged'){
	if ($_POST['pasw1'] == $_POST['pasw2'] && trim($_POST['pasw2']) != "" && strlen($_POST['pasw2']) > 3 ){	  

		// Consulta a la db si el usuario y la pwd existen y si es correcto
		mysql_connect(HOST, USR, PWD);
		//mysql_connect('localhost','root','seraph');
		$sSQL = "SELECT password FROM usuario WHERE usuario='".$_POST['servicio']."'";
		$result = mysql_db_query(DATABASE, $sSQL);
		
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ( $linea["password"] == $_POST["pasw"] )
			{
				$sSQL = "UPDATE usuario SET password='".$_POST['pasw1']."' WHERE usuario='".$_POST['servicio']."'";
				mysql_db_query(DATABASE, $sSQL);
				
				$men = "<b><br>Su contrase&#241a ha sido cambiada con &#233xito!</b> <br>Usela la proxima vez que ingrese.<br><br>";
			}else
				$men = "<br><b>Su contrase&#241a actual no coincide con la almacenada en la base de datos.</b><br><br>";

			break;
		}
		
	} else
		$men ="<br><b>Su contrase&#241a no coincide, recuerde que debe ser mayor a 3 digitos. </b><br><br>"; 			
}
else if(isset($_GET['optc']) && $_GET['optc'] == 'ipChanged'){
				
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	$stringData = "cp /var/www/htdocs/cmds/rc.modules /etc/rc.d/rc.modules\n";
	fwrite($fh, $stringData);
	
	if ($_POST['ip'] != "" && $_POST['mascara'] != ""){
		$stringData = "ifconfig eth0 ".$_POST['ip']." netmask ".$_POST['mascara'];
		fwrite($fh, $stringData."\n");
		fwrite($fh, "echo \"".$stringData."\" >> /etc/rc.d/rc.modules\n");
	}
	if ($_POST['oldgw'] != ""){
		$stringData = "route del default gw ".$_POST['oldgw']."\n";
		fwrite($fh, $stringData);
	}
	if ($_POST['gw'] != ""){
		$stringData = "route add default gw ".$_POST['gw'];
		fwrite($fh, $stringData."\n");
		fwrite($fh, "echo \"".$stringData."\" >> /etc/rc.d/rc.modules\n");
	}
	if ($_POST['dns'] != ""){
		$stringData = "echo 'nameserver ".$_POST['dns']."' > /etc/resolv.conf\n";
		fwrite($fh, $stringData);
	}
	fclose($fh);
	
	// Cargar el nuevo link o IP
	$menIp = "<img src=\"images/wait.gif\" align=\"absbottom\" /> <br>Un momento por favor...";
	echo '<script>setTimeout("location.href=\'http://'.$_POST['ip'].'?s=6\'",5000);</script>';
}
else if(isset($_GET['optc']) && $_GET['optc'] == 'power'){
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	if ($_POST['power'] == "reload"){
		$stringData = "shutdown -r now\n";
		fwrite($fh, $stringData);
		$powerM = "<img src=\"images/wait.gif\" align=\"absbottom\" /> <br>Reiniciando el sistema... esto puede tomar hasta 2 minutos...";
		echo '<script>setTimeout("location.href=\'http://'.$_POST['ip'].'?s=0\'",60000);</script>';
	}else if ($_POST['power'] == "shut"){
		$stringData = "shutdown -h now\n";
		fwrite($fh, $stringData);
		$powerM = "<br>Apagando el sistema...";
	}
	fclose($fh);
}

else if(isset($_GET['optc']) && $_GET['optc'] == 'back'){	
	$backupFile = "/var/www/htdocs/cmds/".DATABASE.date("Y-m-d-H-i-s").'.zip';
	$command = "mysqldump --opt -h".HOST." -u".USR." -p".PWD." ".DATABASE." | zip > $backupFile";
	system($command);
	
	$archivo = $backupFile;
	header('Content-disposition: attachment; filename='.$archivo);
	header('Content-type: application/octet-stream');
	readfile($archivo);
}
else if(isset($_GET['optc']) && $_GET['optc'] == 'mtto'){

	exec("mysql -h".HOST." -u".USR." -p".PWD." -D".DATABASE." -e'show tables'", $varA);
	for ($i=1; $i < count($varA); $i++){
		$sSQL .= "CHECK TABLE $varA[$i]; ";
	}
	
	exec("mysql -h".HOST." -u".USR." -p".PWD." -D".DATABASE." -e'".$sSQL."' | grep error | grep Corrupt", $varB);
	//exec("mysql -h".HOST." -u".USR." -p".PWD." -D".DATABASE." -e'".$sSQL."' | grep OK", $varB);
	$sSQL = "";
	
	if (count($varB) > 0){ 
		for ($i=0; $i < count($varB); $i++){
			$vTemp = split("\t",$varB[$i]);
			$sSQL .= "REPAIR TABLE ".$vTemp[0]."; ";
			if ($vTemp[0] == DATABASE.".server")
				$sSQL .= "INSERT INTO `server` VALUES (200,'','')";
		}
		exec("mysql -h".HOST." -u".USR." -p".PWD." -D".DATABASE." -e'".$sSQL."'");
		$varT = "<br>Mantenimiento realizado con &eacute;xito."; 
		
	}else
		$varT = "<br>No hay tablas a las cuales sea necesario aplicar mantenimiento.";
}
?>
<script type="text/javascript" language="javascript1.2">
var url = "serverSide.php?";

function delRuta(ip, gw, mk){
	document.getElementById('divRutas').innerHTML = "<br><img src='images/ajaxWait.gif'> Eliminando ruta.";
	http.open("GET", url + "tipo=delRoute&host=" + escape(ip) + "&gw=" + escape(gw) + "&mk=" + escape(mk) + "&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpResponse;
	http.send(null);
}

function addRuta(){
	var ip = document.forms['frmRuta'].hostD.value;
	var gw = document.forms['frmRuta'].gw.value;
	var mk = document.forms['frmRuta'].mask.value;
	document.forms['frmRuta'].hostD.value = "";
	document.forms['frmRuta'].gw.value = "";
	document.forms['frmRuta'].mask.value = "";
	
	document.getElementById('divRutas').innerHTML = "<br><img src='images/ajaxWait.gif'> Agregando ruta.";
	http.open("GET", url + "tipo=addRoute&host=" + escape(ip) + "&gw=" + escape(gw) + "&mk=" + escape(mk) + "&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpResponse;
	http.send(null);
}

function updateGW(){
	var gw = document.forms['frmGWDNS'].gw.value;
	var oldgw = document.forms['frmGWDNS'].oldgw.value;
	var dns = document.forms['frmGWDNS'].dns.value;
	
	document.getElementById('resGW').innerHTML = "<br><img src='images/ajaxWait.gif'> Agregando ruta por defecto.";
	http.open("GET", url + "tipo=addRoute&gw=" + escape(gw) + "&oldgw=" + escape(oldgw) + "&dns=" + escape(dns) + "&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpRes;
	http.send(null);
	
}

function ifconfig(){
	var sel = document.forms['frmEth'].interfaz.value;
	if (sel != "none"){		
		document.getElementById('resGW').innerHTML = "<img src='images/ajaxWait.gif'> Consultando...";
		http.open("GET", url + "tipo=loadEth&sel=" + escape(sel) + "&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpRes1;
		http.send(null)
	}
}

function changeDate(){
	alert('Un Momento por favor ...');
	var fecha = document.forms['frmFecha'].btnFecha.value;
	http.open("GET", url + "tipo=changeDate&fecha=" + escape(fecha) + "&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpRes3;
	http.send(null)
}

function saveEth(){
	var sel = document.forms['frmEth'].interfaz.value;
	var ip = document.forms['frmEth'].ip.value;
	var mask = document.forms['frmEth'].mascara.value;
	
	if (sel != "none" && confirm("Recuerde que s\xED esta cambiando la direcci\xF3n IP de Kerberus,\ndeber\xE1 acceder a trav\xE9s de esa nueva direcci\xF3n\n\nDesea continuar?")){		
		document.getElementById('resGW').innerHTML = "<img src='images/ajaxWait.gif'> Modificando direcci&oacute;n.";
		http.open("GET", url + "tipo=saveEth&sel=" + escape(sel) + "&ip=" + escape(ip) + "&mask=" + escape(mask) + "&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpRes2;
		http.send(null)
	}
}

function HttpResponse() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  document.getElementById('divRutas').innerHTML = results;
		  setTimeout("window.location.reload()",700);
	  }
	}
}

function HttpRes() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  document.getElementById('resGW').innerHTML = results;
		  setTimeout("window.location.reload()",700);
	  }
	}
}

function HttpRes1() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  var valores = results.split("|");
		  document.forms['frmEth'].ip.value = valores[0];
		  document.forms['frmEth'].mascara.value = valores[1];
		  document.getElementById('resGW').innerHTML = "";
	  }
	}
}

function HttpRes2() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  document.getElementById('resGW').innerHTML = results;
	  }
	}
}

function HttpRes3() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
	  	alert('Hora Actualizada con \xE9xito');
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
var http = getHTTPObject();

</script>
<div style="background: #740003; border: 1px solid black; margin-top:2px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
			.:: Información General del Sistema ::.
		</strong>
</div></div>
<div align="left" style="background: url(images/bkg.jpg);">
<table width="100%" style="font-family:Verdana; font-size:12px" cellspacing="2">
    <tr>
      <td width="50%" height="50px" valign="top"><div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>.: Máxima Relevancia :.</strong></div></div>
		<div style="border: 1px solid">
			<table border="0" width="100%" style="font-family:Verdana; font-size:12px">
				<tr>
				<td>Nombre del Equipo</td>
				<td><strong><?php echo shell_exec('uname -n'); ?></strong></td>
				</tr>
				<tr>
				<td>Direcci&oacute;n IP</td>
				<td><strong><?php
					$tempVar = shell_exec('ifconfig | grep Bcast');
					$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
					echo $tempVar;					
				?></strong>
				</tr>
				<tr>
				<td>Versión del Kernel</td>
				<td><strong><?php echo shell_exec('uname -r'); ?></strong></td>
				</tr>
				<tr>
				<td>Tiempo en L&iacute;nea</td>
				<td width="230px"><strong><?php
					$data = shell_exec('uptime');
 					$uptime = explode(' up ', $data);
 					$uptime = explode(',', $uptime[1]);
					if (count($uptime) == 5)
						$uptimeE = str_replace(":"," Hora(s) ",$uptime[0])." Minuto(s)";
					else
						$uptimeE = $uptime[0].', '.str_replace(":"," Hora(s) ",$uptime[1])." Minuto(s)";
					$uptimeE = str_replace("days"," Días",$uptimeE);
					$uptimeE = str_replace("day"," Día",$uptimeE);
					$uptimeE = str_replace("min","",$uptimeE);
 					echo $uptimeE; 
				 ?></strong></td>
				</tr>
				<tr>
				<td>Carga Promedio del Sistema</td>
				<td><?php
				if (count($uptime) == 5)
					echo substr($uptime[2],strpos($uptime[2],":")+1). $uptime[3]. $uptime[4];
				else
					echo substr($uptime[3],strpos($uptime[3],":")+1). $uptime[4]. $uptime[5];?></td>
				</tr>				
				<tr>
				<td>Fecha y Hora del Sistema</td>
				<td><form id="frmFecha" name="frmFecha" style="display:inline">
				<input type="text" id="btnFecha" name="btnFecha" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px" value="<?php echo shell_exec('date +%F\ %H:%M'); ?>" /> 
				<input onclick="javascript:changeDate()" type="button" value="Actualizar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px" /></form></td>
				</tr>
			</table>
						
		</div>
	</td>
      	<td rowspan="4" valign="top">
		
		<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
		<div style="background: #740003;  color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Información General del Hardware :.</strong></div></div>
		<div style="border: 1px solid; ">
			<table border="0" width="100%" style="font-size:12px">
				<tr>
				<td width="150">Procesador</td>
				<td><?php
				 	$tempVar = shell_exec('cat /proc/cpuinfo | grep model\ name'); 
					$tempVar = str_replace("model name","",$tempVar);
					echo $tempVar = str_replace(":","",$tempVar);					
					
				?></td>
				</tr>
				<tr>
				<td>Frecuencia Real</td>
				<td><?php 
					$tempVar = shell_exec('cat /proc/cpuinfo | grep cpu\ MHz'); 
					$tempVar = str_replace("cpu MHz","",$tempVar);
					echo $tempVar = str_replace(":","",$tempVar). " Mhz";					
				?>
				</tr>
				<tr>
				<td>Memoria Cache</td>
				<td><?php
					$tempVar = shell_exec('cat /proc/cpuinfo | grep cache\ size'); 
					$tempVar = str_replace("cache size","",$tempVar);
					echo $tempVar = str_replace(":","",$tempVar);					
				?></td>
				</tr>
				<tr>
				<td>Memoria RAM</td>
				<td><?php
					$tempVar = shell_exec('cat /proc/meminfo | grep MemTotal:'); 
					$tempVar = str_replace("MemTotal","",$tempVar);
					$tempVar = str_replace("kB","",$tempVar);
					$tempVar = str_replace(" ","",$tempVar);
					$tempVar = str_replace(":","",$tempVar);
					echo $tempVar = ($tempVar/1024). " &nbsp;Mbytes"; 					
				?></td>
				</tr>
				<tr>
				<td valign="top">Interfases de red</td>
				<td><?php

					/*echo "Interfaz lo<br>";						
					$tmp = shell_exec('ifconfig lo | grep RX\ bytes')."<br>";
					echo $tmp = str_replace("TX bytes","<br>TX bytes",$tmp)."<br>";
					*/
					$tempVar = shell_exec('ifconfig -a | grep eth'); 
					$tempVar = str_replace("\n",",",$tempVar);
					$eth = explode(',', $tempVar);
					foreach($eth as $int){
						if (trim($int) != ""){
							$tempVar = shell_exec('ifconfig '.substr($int,0,4).' | grep inet\ add');
							$tempVar = str_replace("inet addr:","",$tempVar);						 
							$tempVar = str_replace("Bcast:","",$tempVar);
							$tempVar = str_replace("Mask:","",$tempVar);
							$tempVar = str_replace("  "," ",$tempVar);
							$tempVar = str_replace("  "," ",$tempVar);
							$tempVar = str_replace("  "," ",$tempVar);
							$tempVar = str_replace(" ",",",$tempVar);
							$tempVar = explode(',', $tempVar);
							echo "Interfaz ".substr($int,0,4)."<br>";
							echo "IP: ".$tempVar[2]."<br>";						
							echo "Mascara de red: ".$tempVar[4]."<br>";
							$tmp = shell_exec('ifconfig '.substr($int,0,4).' | grep RX\ bytes')."<br>";
							echo $tmp = str_replace("TX bytes","<br>TX bytes",$tmp)."<br>";
						}
					}
				?></td>
				</tr>				
			</table>
						
		</div>
		
		<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
						.:: Configuraciones de Red Local ::.
					</strong>
			</div></div>
			<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px"><center>
			<form id="frmEth" name="frmEth" style="display:inline">
			<table width="400" border="0" cellpadding="3" cellspacing="0" >
			  <tr>
			  	<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Interfaz de Red</td>
				<td align="right">
				<select id="interfaz" name="interfaz" style="width:124px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" onchange="javascript:ifconfig()">
					<option value="none">Seleccione una</option>
				<?php
					$tempVar = shell_exec('ifconfig -a | grep eth');
					$tempVar = explode("\n", $tempVar);
					for ($i=0; $i< count($tempVar)-1; $i++){
						echo "<option value='".trim(substr($tempVar[$i],0,10))."'>".trim(substr($tempVar[$i],0,10))."</option>\n";
					}
				?>
				</select>
				</td>
				<td>
				</td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Dirección IP Actual:</td>
				<td align="right"><input name="ip" type="text" style="width:120px; font-family:Verdana; font-size:12px" value="<?php
					$tempVar = shell_exec('ifconfig eth1 | grep Bcast');
					$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
					echo str_replace(" ","",$tempVar);					
				?>" /></td>
				<td>
				<input type="button" onclick="javascript:saveEth()" value="Actualizar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/>
				</td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">M&aacute;scara de Red</td>
				<td align="right"><input name="mascara" type="text" style="width:120px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value="<?php	
					$tempVar = shell_exec('ifconfig eth1 | grep Bcast');				
					$tempVar = substr($tempVar,strpos($tempVar,"Mask")+5);
					
					echo str_replace(" ","",$tempVar);
				?>" /></td>
				<td>
				<input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/>
				</td>
			  </tr>
			  </table>			  
			  </form>
			  <hr width="90%" />
			  <form id="frmGWDNS" name="frmGWDNS" style="display:inline">				  
			  <table width="400" border="0" cellpadding="3" cellspacing="0" >
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Puerta de Enlace</td>
				<td align="right"><input name="gw" type="text" style="width:120px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value="<?php	
					$tempVar = shell_exec('route -v | grep default');				
					$tempVar = substr($tempVar,16,15);
					echo str_replace(" ","",$tempVar);
				?>" />
				<input type="hidden" name="oldgw" value="<?php echo $tempVar; ?>" />
				</td>
				<td>
				<input type="button" onclick="javascript:updateGW()" value="Actualizar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/>
				</td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" valign="top">DNS</td>
				<td align="right" valign="top">
				<?php	
					$tempVar = shell_exec('cat /etc/resolv.conf | grep nameserver');
					$tempVar = substr($tempVar,strpos($tempVar,"nameserver")+11);
					$tempVar = str_replace(" ","",$tempVar);
				?>
				<input name="dns" type="text" style="width:120px; font-family:Verdana; font-size:12px" value="<?php echo $tempVar;?>" /></td>
				<td>
				<input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/>
				</td>
			  </tr>
			  <tr>
				<td colspan="3" align="center">
				<div id="resGW" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#666666; text-align:center">

				</div>
				</td>
			  </tr>
			</table>
			</center>
			</form>					
			</div>
			</div>	
			
			<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
						.:: Rutas Adicionales [ OPCIONAL ] ::.
					</strong>
			</div></div>
			<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px"><center>
			<form id="frmRuta" name="frmRuta" style="display:inline" method="post" action="?s=8&optc=addRoute">
			
			<table width="400" border="0" cellpadding="4" cellspacing="0">
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">DNS o IP destino</td>
				<td align="right"><input name="hostD" type="text" style="width:120px; font-family:Verdana; font-size:12px" value="" /></td>
				<td rowspan="2">
                <input type="button" onclick="javascript:addRuta()" value="Guardar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px; height:44px"/></td>
			  </tr>
              <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">M&aacute;scara de red</td>
				<td align="right"><input name="mask" type="mask" style="width:120px; font-family:Verdana; font-size:12px" value="" /></td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Puerta de Enlace</td>
				<td align="right"><input name="gw" type="text" style="width:120px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value="" /></td>
				<td><input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:90px"/></td>
			  </tr>
			  <tr>
			  	<td colspan="3" align="center">
					 
				<div id="divRutas" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#666666; text-align:center">
					
				</div>
				</td>
			  </tr>
			</table>
			</form>
			<hr width="90%" />
			<form id="frmDelRuta" name="frmDelRuta" style="display:inline" method="post" action="?s=8&optc=delRoute">
			<table border="0" cellpadding="5" cellspacing="0" width="90%">
			  <tr>
				<td colspan="2" align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
					<table cellpadding="0" cellspacing="3" border="0" width="100%">
					<tr>
						<td align='center'><strong>No.</strong></td>
						<td><strong>Destino</strong></td>
                        <td><strong>Mascara</strong></td>
						<td><strong>Puerta de Enlace</strong></td>
						<td></td>
					</tr>
					<?php 
					$rutas = shell_exec("route -v | grep UG");
					$rutas = explode("\n", $rutas);
					
					for ($i=0; $i < count($rutas)-1; $i++){
						if (strpos($rutas[$i], "ault") < 2)
						{
							$destino 	= trim(substr($rutas[$i],0,16));
							$mascara 	= trim(substr($rutas[$i],32,16));
							$gateway	= trim(substr($rutas[$i],16,16));
							echo "<tr>\n";
							echo "<td align='center'>".($i+1).".</td>\n<td>".$destino."</td>\n							
							<td>".$mascara."</td>
							<td align='left'>".$gateway."</td>\n<td>
							<a href=\"javascript:delRuta('".$destino."','".$gateway."', '".$mascara."')\" >Eliminar</a></td>\n";
							echo "<tr>\n";
						}
					}
					?>
					</table>
					</center>
				</td>
			  </tr>
			</table>
			</center>
			</form>					
			</div>
			</div>						
				
		<br><br><br><div align="right"><font size="1">Use esta información para mantener su sistema actualizado y agendar mantenimientos preventivos en cuestión de espacio de almacenamiento y Cargas del Sistema</font></div>
	</td>
    </tr>
	<tr>
		<td valign="top">
			<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
						<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
							.:: Power Options ::.
						</strong>
				</div></div>
				<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 7px 7px 7px 7px; color:#666666; text-align:justify" align="left">					
					Por seguridad, utilice esta herramienta para apagar y/o detener el sistema <strong>Kerberus&trade; IPBX</strong><br /><br />
					<center>
				<form style="display:inline" action="?s=8&optc=power" method="post">
					Operaci&oacute;n: 
					<select name="power" style="width:150px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
						<option value="reload">Reiniciar</option>
						<option value="shut">Apagar</option>
					</select>
					<input name="ip" type="hidden" style="width:120px; font-family:Verdana; font-size:12px" value="<?php
					$tempVar = shell_exec('ifconfig | grep Bcast');
					$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
					echo str_replace(" ","",$tempVar);					
				?>" />
				<input type="submit" value="Ejecutar Operaci&oacute;n" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" />
				</form>
				<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#B60000">
					<?php echo $powerM; ?>
				</div>
				</center><br />
					</div>
				</div>
				
		<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
		<div style="background: #740003;  color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Mantenimiento al Sistema :.</strong></div></div>
		<div style="border: 1px solid"><div style="margin: 5px 5px 10px 5px; text-align:justify">
			En esta secci&oacute;n usted podr&aacute; generar un backup de la estructura de la base de datos de <strong>Kerberus IPBX</strong>, y realizar mantenimiento a las tablas en caso de que sea necesario.<br /><br />
			<center>
			<form style="display:inline" method="post" action="?s=8&optc=back">
				<input type="submit" value="Generar Backup" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/>
			</form>
			<form style="display:inline" method="post" action="?s=8&optc=mtto">
				<input type="submit" value="Realizar Mantenimiento" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/>
			</form>
			<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009933">
					<?php echo $varT; ?>
				</div>
			</center>
		</div></div>
						
		<div style="background: #740003; border: 1px solid black; margin-top:3px; margin-bottom:3px">
						<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
							.:: Cambio de contrase&ntilde;a para usuarios ::.
						</strong>
				</div></div>
				<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px">
					<div style="margin: 3px 3px 3px 3px"><center>
				<form style="display:inline" action="?s=8&optc=pwdChanged" method="post">				
				<!--<input type="hidden" value="admin" id="servicio" name="servicio"/>-->
				<table width="300" border="0" cellspacing="0" cellpadding="5">				  
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Usuario</td>
					<td align="right">
					<select name="servicio" id="servicio" style="width:134px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px">
						<option value="admin">Administrador</option>
						<option value="auditor">Auditor</option>
					</select>
					</td>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Contrase&ntilde;a Actual</td>
					<td align="right"><input name="pasw" type="password" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
				  </tr>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Nueva contrase&ntilde;a </td>
					<td align="right"><input name="pasw1" type="password" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
				  </tr>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Repita la contrase&ntilde;a </td>
					<td align="right"><input name="pasw2" type="password" style=" width:130px;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
				  </tr>
				  <tr>
					<td colspan="2" align="right">
						<input name="" type="submit" value="Cambiar" style="font-family:Verdana; font-size:12px" />
						<input name="" type="reset" value="Limpiar" style="font-family:Verdana; font-size:12px" /> <br>				
					</td>
				  </tr>				  
				</table>	
				</form>	
				<hr />
						
				<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#B60000"><?php echo $men ?><br></div>
				</div>
				</center>
				</div>

		<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
		<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
			<strong>.: Dispositivos de Almacenamiento :.</strong></div></div>
		<div style="border: 1px solid; ">
			<table border="0" width="81%" style="font-family:Verdana; font-size:12px">
				<?php
					$hdUse = str_replace("  "," ",shell_exec('df -h | grep dev'));
					$hdUse = str_replace("  "," ",$hdUse);
					$hdUse = str_replace("  "," ",$hdUse);
					$hdUse = str_replace("  "," ",$hdUse);
					$hdUse = str_replace("  "," ",$hdUse); 
					$hdUse = str_replace("  "," ",$hdUse);
					$hdUse = str_replace("  "," ",$hdUse);
					$hdUse = str_replace(" ",",",$hdUse);									
					$hdUse = str_replace("\n","/,/",$hdUse);		
					$hdUse = explode(',', $hdUse);
					for ($i=0; $i< count($hdUse)-1; $i++){?>
						<TR>
							<TD align="left" colspan="2">Dispositivo <?php echo str_replace("//","/",$hdUse[$i]);?></TD>
      						</TR>
						<TR>
        						<TD align="right" width="230px">Capacidad</TD>
							<TD><?php echo $hdUse[$i+1];?></TD>
      						</TR>						
						<TR>
        						<TD align="right">Usado</TD>
							<TD><?php echo $hdUse[$i+2];?></TD>
      						</TR>
						<TR>
        						<TD align="right">Disponible</TD>
							<TD><?php echo $hdUse[$i+3];?></TD>
      						</TR>
						<TR>
        						<TD align="right">Porcentaje Usado</TD>
							<TD><?php echo $hdUse[$i+4];?></TD>
      						</TR>	<?php					
						$i=$i+5;
					}
					?>
			</table>
						<br />
		</div>
      </td>
    </tr>	
</table>
</div>