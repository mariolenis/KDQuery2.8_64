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
}else if(isset($_GET['optc']) && $_GET['optc'] == 'ipChanged'){
				
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
}else if(isset($_GET['optc']) && $_GET['optc'] == 'gwChanged'){

	$myFile = "cmds/sip_trunk.conf";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "[GXW4104]\ntype=peer\ncontext=entrante\nhost=".$_POST['gxw']."\ninsecure=port\ncanreinvite=no";
	fwrite($fh, $stringData);
	fclose($fh);
	
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: sip reload\r\n\r\n"); 
	$wrets=fgets($socket,128);
	
	$mensajeGx = "<img src=\"images/ok.jpg\" align=\"absbottom\" />";
}
else if(isset($_GET['optc']) && $_GET['optc'] == 'power'){
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	if ($_POST['power'] == "reload"){
		$stringData = "shutdown -r now\n";
		fwrite($fh, $stringData);
		$powerM = "<img src=\"images/wait.gif\" align=\"absbottom\" /> <br>Reiniciando el sistema... esto puede tomar hasta 2 minutos...";
		echo '<script>setTimeout("location.href=\'http://'.$_POST['ip'].'?s=6\'",80000);</script>';
	}else if ($_POST['power'] == "shut"){
		$stringData = "shutdown -h now\n";
		fwrite($fh, $stringData);
		$powerM = "<br>Apagando el sistema...";
	}
	fclose($fh);
}

else if (isset($_GET['optc']) && $_GET['optc'] == 'mailboxes'){
	
	// Consulta a la db si el usuario y la pwd existen y si es correcto
	mysql_connect(HOST, USR, PWD);
	//mysql_connect('localhost','root','seraph');		
	if ($_GET['sub'] != 'load' && $_GET['sub'] != 'del'){
		$sSQL = "SELECT count(extension) as cantExt FROM boxes WHERE extension='".$_POST['ext']."'";
		$result = mysql_db_query(DATABASE, $sSQL);
		$linea = mysql_fetch_array($result, MYSQL_ASSOC);
		
		$nombreDst = $_POST['nombre'];
		$nombreDst = str_replace("á","a",$nombreDst);
		$nombreDst = str_replace("é","e",$nombreDst);
		$nombreDst = str_replace("í","i",$nombreDst);
		$nombreDst = str_replace("ó","o",$nombreDst);
		$nombreDst = str_replace("ú","u",$nombreDst);
		$nombreDst = str_replace("Á","A",$nombreDst);
		$nombreDst = str_replace("É","E",$nombreDst);
		$nombreDst = str_replace("Í","I",$nombreDst);
		$nombreDst = str_replace("Ó","O",$nombreDst);
		$nombreDst = str_replace("Ú","U",$nombreDst);
			
		if (trim($_POST['pwd']) != "" && strlen($_POST['pwd']) > 2 && $_POST['pwd'] == $_POST['pwd1'] && strlen($_POST['email']) > 5){
			// Consulta de creación.					
			if ($linea['cantExt'] == 0)// No existe buzón
				$sSQL = "INSERT INTO boxes (extension, clave, nombre, email) VALUES ('".$_POST['ext']."','".$_POST['pwd']."','".$nombreDst."','".$_POST['email']."')";
			else
				$sSQL = "UPDATE boxes set clave='".$_POST['pwd']."', nombre='".$nombreDst."', email='".$_POST['email']."' WHERE extension = '".$_POST['ext']."'";
			mysql_db_query(DATABASE, $sSQL) or die('Imposible');
		}else
			echo "<script>alert('Su clave no coincide o su correo es invalido.');</script>";			
	}else if ($_GET['sub'] == 'load' && $_GET['sub'] != 'del'){
		$sSQL = "SELECT * FROM boxes WHERE extension='".$_GET['ext']."'";
		$result = mysql_db_query(DATABASE, $sSQL);
		$linea = mysql_fetch_array($result, MYSQL_ASSOC);
		$nombre		= $linea['nombre'];
		$correo 	= $linea['email'];
		$extension 	= $linea['extension'];			
	}	
	else if ($_GET['sub'] != 'load' && $_GET['sub'] == 'del'){
		$sSQL = "DELETE FROM boxes WHERE extension='".$_GET['ext']."'";
		mysql_db_query(DATABASE, $sSQL);
	}		
}	
?> 
		<style>		
			body 		{ font-family: verdana; font-size: 13px }
			A:link		{ text-decoration: none; color: #0000FF; }
			A:visited	{ text-decoration: none; color: #333366; }
			A:hover    	{ text-decoration: none; color: #0000FF; }		
		</style>
		<script language="javascript1.2">
			function cargar(){
				ext = document.getElementById('ext').value;
				location.href='?s=6&optc=mailboxes&sub=load&ext=' + ext;
			}
			function delBox(ext){
				location.href='?s=6&optc=mailboxes&sub=del&ext=' + ext;
			}
		</script> 			
							
		<div align="center" style="background: url(images/bkg.jpg); border: 1px solid #9D9D59; width: 872px;">
		<table border="0" cellpadding="0" cellspacing="2" style="width: 872px;">
		<tr>
			<td width="50%" valign="top" align="center">
			<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
						<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
							.:: Power Options ::.
						</strong>
				</div></div>
				<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 7px 7px 7px 7px; color:#666666; text-align:justify" align="left">					
					Por seguridad, utilice esta herramienta para apagar y/o detener el sistema <strong>Kerberus&trade; IPBX</strong><br /><br />
					<center>
				<form style="display:inline" action="?s=6&optc=power" method="post">
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
				
				<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
						.:: Configuraciones de Red Local ::.
					</strong>
			</div></div>
			<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px">
			<form style="display:inline" method="post" action="?s=6&optc=ipChanged">
			<table width="300" border="0" cellpadding="5" cellspacing="0">
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Dirección IP Actual:</td>
				<td align="right"><input name="ip" type="text" style="width:120px; font-family:Verdana; font-size:12px" value="<?php
					$tempVar = shell_exec('ifconfig eth0 | grep Bcast');
					$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
					echo str_replace(" ","",$tempVar);					
				?>" /></td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">M&aacute;scara de Red</td>
				<td align="right"><input name="mascara" type="text" style="width:120px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value="<?php	
					$tempVar = shell_exec('ifconfig eth0 | grep Bcast');				
					$tempVar = substr($tempVar,strpos($tempVar,"Mask")+5);
					
					echo str_replace(" ","",$tempVar);
				?>" /></td>
			  </tr>
			  <tr>
				<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Puerta de Enlance</td>
				<td align="right"><input name="gw" type="text" style="width:120px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value="<?php	
					$tempVar = shell_exec('route -n | grep UG');				
					$tempVar = substr($tempVar,16,15);
					echo str_replace(" ","",$tempVar);
				?>" />
				<input type="hidden" name="oldgw" value="<?php echo $tempVar; ?>" />
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
			  </tr>
			  <tr>
				<td colspan="2" align="right">
				<input type="submit" value="Actualizar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/> <input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/><br />
				<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#B60000">
					<strong><?php echo $menIp ?></strong>
				</div>
				</td>
			  </tr>
			</table>
			</form>					
			</div>
			</div>
				
				<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
						<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
							.:: Cambio de contrase&ntilde;as ::.
						</strong>
				</div></div>
				<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px">
				<form style="display:inline" action="?s=6&optc=pwdChanged" method="post">
				<table width="300" border="0" cellspacing="0" cellpadding="5">
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Servicio</td>
					<td align="right">
						<select name="servicio" style="width:132px; height:20px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/>
							<option value="admin">Administrador</option>
							<option value="celular">Celular</option>
							<option value="nacional">Nacional</option>
							<option value="internacional">Internacional</option>
						</select>
					</td>			
				  </tr>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Contrase&#241a Actual</td>
					<td align="right"><input name="pasw" type="password" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
				  </tr>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Nueva contrase&#241a</td>
					<td align="right"><input name="pasw1" type="password" style="width:130px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/></td>
				  </tr>
				  <tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Repita la contrase&#241a</td>
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
				</div>
			</td>
			<td valign="top" align="center">			
			<div style="background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px" align="center"><strong>
						.:: Correo de Voz ::.
					</strong>
			</div></div>
			<div style="border: 1px solid #999999; margin: 0px 0px 3px 0px; position: relative; top: -0.1em;">
					<div style="margin: 3px 3px 3px 3px">
					<div style="margin: 7px 7px 7px 7px; color:#666666; text-align:justify" align="left">
						Use esta herramienta para crear y editar los buzones de su sistema de telefonía.<br />
					</div>
			<form name="mailbox" id="mailbox" style="display:inline" method="post" action="?s=6&optc=mailboxes">			
				<table width="300" border="0" cellpadding="2" cellspacing="1">
			  	<tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Extensión:</td>
					<td align="right" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
					<select name="ext" id="ext" style="width:125px; height:20px; font-family:Verdana; font-size:12px" onChange="javascript:cargar()">
					<?php
						$tempVar = shell_exec('cat /etc/asterisk/sip.conf | grep ]'); 
						$tempVar .= shell_exec('cat /etc/asterisk/iax.conf | grep ]'); 
						$tempVar = split("\n", $tempVar);
						sort($tempVar);
						for ($i=0; $i < count($tempVar)-1; $i++){
							$ext = str_replace("[","",$tempVar[$i]);
							$ext = str_replace("]","",$ext);
							if (is_numeric(trim($ext)) && trim($ext) != ""){
								if ($_GET['ext'] == trim($ext))
									echo '<option value="'.trim($ext).'" selected="selected">'.trim($ext).'</option>';
								else
									echo '<option value="'.trim($ext).'">'.trim($ext).'</option>';
							}
						}
					?>
					</select>
				</tr>
				<tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Nombre</td>
					<td align="right" >
					<input type="text" name="nombre" id="nombre" value="<?php echo $nombre ?>" style="width:120px; font-family:Verdana; font-size:12px"/></td>
				</tr>
				<tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Correo electrónico</td>
					<td align="right" >
					<input type="text" name="email" id="email" value="<?php echo $correo ?>" style="width:120px; font-family:Verdana; font-size:12px"/></td>
				</tr>
				<tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Constrase&ntilde;a</td>
					<td align="right" >
					<input type="password" name="pwd" id="pwd" style="width:120px; font-family:Verdana; font-size:12px"/></td>
				</tr>
				<tr>
					<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Repita la Constrase&ntilde;a	</td>
					<td align="right" >
					<input type="password" name="pwd1" id="pwd1" style="width:120px; font-family:Verdana; font-size:12px"/></td>
				</tr>
				<tr>
					<td align="right" colspan="2">
				<input type="submit" value="Guardar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/> 
				<input type="reset" value="Borrar" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px"/>
					</td>
				</table>
			
			</form>
			<hr />
			<br />
			<table width="400" border="0" cellpadding="2" cellspacing="1">
				<TR>
					<td align="center" colspan="4" style="border:1px solid gray; background-color:#666666; color:#FFFFFF; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
						<strong>BUZONES ACTIVOS</strong>
					</td>
				</TR>
			  	<tr>
					<td align="center" style="border:1px solid gray; background-color:#CCCCCC; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Ext.</td>
					<td align="center" style="border:1px solid gray; background-color:#CCCCCC; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Nombre</td>
					<td align="center" style="border:1px solid gray; background-color:#CCCCCC; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">E-mail</td>
					<td align="center" style="border:1px solid gray; background-color:#CCCCCC;"></td>
				</tr>
				<?php
					mysql_connect(HOST, USR, PWD);
					//mysql_connect('localhost','root','seraph');
					$sSQL = "SELECT * FROM boxes ORDER BY extension";
					$result = mysql_db_query(DATABASE, $sSQL);
					
					while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$stringData .= $linea['extension']." => ".$linea['clave'].",".$linea['nombre'].",".$linea['email'].",,|nextaftercmd=yes\n";
						echo '
						<tr>
							<td align="center" style="border:1px solid #E7E7E7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
							'.$linea['extension'].'</td>
							<td align="center" style="border:1px solid #E7E7E7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
							'.$linea['nombre'].'</td>
							<td align="center" style="border:1px solid #E7E7E7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
							'.$linea['email'].'</td>
							<td align="center" style="border:1px solid #E7E7E7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">
							<a href="#" onclick="javascript:delBox(\''.$linea['extension'].'\')"><img src="images/del1.jpg" border="0" /></a></td>
					  </tr>
						';
					}
					
					if (isset($_GET['optc']) && $_GET['optc'] == 'mailboxes'){ 
						$myFile = "cmds/voicemail_extra.conf";
						$fh = fopen($myFile, 'w') or die("can't open file");
						fwrite($fh, $stringData);
						fclose($fh);
						
						$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
						fputs($socket, "Action: Login\r\n"); 
						fputs($socket, "UserName: kerberus\r\n"); 
						fputs($socket, "Secret: kerberus\r\n\r\n"); 
						
						fputs($socket, "Action: Command\r\n"); 
						fputs($socket, "Command: reload\r\n\r\n"); 
						$wrets=fgets($socket,128);
					}
				?>				
			 </table><br />
					</div>
			</div>
			
			</td>
		</tr>			
		</table>	
		</div>
		</center>