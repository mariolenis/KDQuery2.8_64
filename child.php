<?php
define ("DATABASE", "kerberus");
define ("HOST", "localhost");
define ("USR", "kerberus");
define ("PWD", "aster1sk");

mysql_connect(HOST,USR,PWD);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Seleccione un Proveedor</title>
</head>
<html>
<head>

<script langauge="javascript">
	function post_value(proveedor){
		http.open("GET", "serverSide.php?tipo=loadTroncal&nombre="+proveedor+"&dummy=" + Math.random(), true);
		http.onreadystatechange = handleHttp;
		http.send(null);				
	}
	
	function del_value(proveedor){
		http.open("GET", "serverSide.php?tipo=delTroncal&nombre="+proveedor+"&dummy=" + Math.random(), true);
		http.onreadystatechange = handleHttps;
		http.send(null);	
	}
	
	function handleHttps() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			setTimeout("window.location.reload()",100);
		  }
		}
	}
	
	function handleHttp() {   
		if (http.readyState == 4) {
		  if(http.status==200) {
			var results=http.responseText.split("+");			
			opener.document.troncales.nombre.value = results[0];
			opener.document.troncales.host.value = results[1];
			opener.document.troncales.type.value = results[2];
			opener.document.troncales.protocolo.value = results[4];
			var pref = results[5].split("|");
			opener.document.troncales.pLocal.value = pref[0];
			opener.document.troncales.pNacional.value = pref[1];
			opener.document.troncales.pCelular.value = pref[2];
			opener.document.troncales.pInter.value = pref[3];
			
			opener.document.troncales.usuario.value = results[6];
			opener.document.troncales.pwd.value = results[7];
			opener.document.troncales.dtmf.value = results[8];
			opener.document.troncales.prioridad.value = results[10];
			opener.document.troncales.callerid.value = results[11];			
						
			for (var i=0;i<opener.document.troncales.codec.length;i++){
				opener.document.troncales.codec[i].checked = false;
			}
			
			for (var i=0;i<opener.document.troncales.contexto.length;i++){
				opener.document.troncales.contexto[i].checked = false;
			}
			
			var codecs = results[3].split("|");
			for (var i=0;i<opener.document.troncales.codec.length;i++){
				for (var j=0;j<codecs.length;j++){
					if (opener.document.troncales.codec[i].value==codecs[j]){
						opener.document.troncales.codec[i].checked = true;
					}
				}				
			}
			
			var contextos = results[9].split("|");
			for (var i=0;i<opener.document.troncales.contexto.length;i++){
				for (var j=0;j<contextos.length;j++){
					if (opener.document.troncales.contexto[i].value==contextos[j]){
						opener.document.troncales.contexto[i].checked = true;
					}
				}				
			}
			
			var perfiles = results[12].split("|");
			opener.document.troncales.p1.value = perfiles[0];
			opener.document.troncales.p2.value = perfiles[1];
			opener.document.troncales.p3.value = perfiles[2];
			
			self.close();
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
</head>
<body>

<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px">
	<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center">
		<strong>.: Proveedores disponibles :.</strong>
	</div>
</div>	
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; border: 1px solid #CCCCCC; margin-bottom:3px" >
	<div style="margin: 3px 3px 3px 3px; text-align:justify" >
		<table border="0" cellspacing="2" cellpadding="0" width="100%">
		<?php
		$sSQL = "SELECT * FROM troncal";
		$result = mysql_db_query(DATABASE,$sSQL);
		while($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			echo "
			<tr>
				<td>
				<img src=\"images/fleche-d.gif\" border='0'> <a href=\"#\" onclick=\"javascript:post_value('".$linea['nombre']."')\">".$linea['nombre']."</a>
				</td>
				<td>
				<a href=\"#\" onclick=\"javascript:del_value('".$linea['nombre']."')\" style='text-decoration:none; color:#003399'>
					<img src='images/borrar.png' border ='0'> Eliminar
				</a>
				</td>
			</tr>";
		}
		
		?>
		</table>
	</div>
</div>
</body>
</html>
