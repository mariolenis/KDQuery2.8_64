<script language="javascript1.2" type="text/javascript">
var identificador = "";
var url = "serverSide.php?";

function control(){	
	if (identificador > 0)
		clearInterval(identificador);		
	identificador = setInterval("hilo()", 900);
	document.getElementById('content').innerHTML = "<br><br><img src='images/ajaxWait.gif'><br>Un momento por favor...<br><br><br>"; 
}

function hilo(){
	http.open("GET", url + "tipo=queueControl&dummy=" + Math.random(), true);
	http.onreadystatechange = HttpResponse;
	http.send(null);
}

function removeAgent(agente){
	if (confirm('Desea desloguear al agente ' + agente)){
		http.open("GET", url + "tipo=queueLogout&agente="+ agente +"&dummy=" + Math.random(), true);
		http.onreadystatechange = HttpRes;
		http.send(null);
	}
}

function HttpRes() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  alert(results);
	  }
	}
}

function HttpResponse() {   
	if (http.readyState == 4) {
	  if(http.status==200) {
		  var results=http.responseText;
		  document.getElementById('content').innerHTML = results;
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
<div align="center" style="background: url(images/bkg.jpg); border: 1px solid #9D9D59">
	<div id="content" style="margin: 5px 5px 5px 5px">
	
	</div>
</div>
<br>
	Mejor visto con Mozilla Firefox 3.0 | Internet Explorer 6.0<br><br>
	<script>
	control();
</script>