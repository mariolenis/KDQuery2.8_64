<?php
define ("DATABASE", "kerberus");
define ("HOST", "localhost");
define ("USR", "kerberus");
define ("PWD", "aster1sk");

//define ("USR", "root");
//define ("PWD", "seraph");

mysql_connect(HOST,USR,PWD);

if ($_GET['tipo'] == "control"){
	
	$sSQL = "SELECT * FROM server";
	$result = mysql_db_query(DATABASE,$sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);

	if (count($linea) > 0){
		$contA = 0;
		$contZ = 0;
		$cTroncal = 0;
		$base = mktime(0, 0, 0, 6, 26, 1983);
		
		$vecTemp = split("<",$linea['agentes']);	
		$vecTemp1 = split("<",$linea['canales']);		
		$vecTemp2 = str_replace("/", "", $linea['des']);
		$vecTemp2 = str_replace("FORW", "", $vecTemp2);
		$vecTemp2 = split("<", $vecTemp2);
		for ($i = 0; $i < count($vecTemp); $i++){			
			
			$e = strpos($vecTemp[$i],"/");
			$h = $e;
			if ($h < 1){
				$h = strpos($vecTemp[$i]," ");
			}
					
			if ( is_numeric(substr($vecTemp[$i],0,$e)) && substr($vecTemp[$i],0,$e) > 24) {
				$vecAgentes[$contA][0] = substr($vecTemp[$i],0,$e);	
				$vecAgentes[$contA][2] = "<span style='font-size:9px; color:green'>00:00:00<br>-</span>";
				
				if (substr($vecTemp[$i],69,2) == "OK" || substr($vecTemp[$i],88,2) == "OK" || substr($vecTemp[$i],95,2) == "OK" || substr($vecTemp[$i],6,2) == "ON"){
					
					$vecAgentes[$contA][1] = "on.png"; // asignación por defecto													
					for ($k = 0; $k < count($vecTemp2); $k++)
					{
						$v = split(":", $vecTemp2[$k]);
						if (strpos($v[0], $vecAgentes[$contA][0]." ") > -1)
						{
							$vecAgentes[$contA][2] = "<span style='font-size:9px; color:#993300'>DESVIO<br>".$v[1]."</span>";
						}
					}
					
					for ($j = 0; $j < count($vecTemp1); $j++){
						if (beginsWith( trim($vecTemp1[$j]), "SIP/".$vecAgentes[$contA][0]."-" ) || beginsWith( trim($vecTemp1[$j]), "IAX2/".$vecAgentes[$contA][0]."-" ) || beginsWith( trim($vecTemp1[$j]), "DAHDI/".($vecAgentes[$contA][0]-600)."-" )){
							
							$vecCanal = split("!", $vecTemp1[$j]);
							if (strpos($vecCanal[6], "/") > -1 ) 
							{
								$vecCanal[6] = str_replace("/", "", substr($vecCanal[6], strrpos($vecCanal[6],"/")));
								$vecSS = split(",", $vecCanal[6]);
								$vecCanal[6] = $vecSS[0];
							}
                                                        else if ($vecCanal[4] == "Up" && $vecCanal[6] == "(Outgoing Line)")                                                            
                                                            $vecCanal[6] = "Entrante";
							else
                                                            $vecCanal[6] = "Procesando";
							
							if ($vecCanal[4] == "Ring" || $vecCanal[4] == "Ringing"){
								$vecAgentes[$contA][2] = "<span style='font-size:9px; color:green'>00:00:00<br>".$vecCanal[6]."</span>";
								$vecAgentes[$contA][1] = "ringing.png";								
							} else if ($vecCanal[4] == "Up") {																
								$vecAgentes[$contA][2] = "<span style='font-size:9px; color:black'>".date("H:i:s", ($base + $vecCanal[11] ))
								."<br>".$vecCanal[6]."<br>
								</span>";
								$vecAgentes[$contA][1] = "speaking.png";							
							}						
						}
					}
					//$vecAgentes[$contA][0] = $vecAgentes[$contA][0]."<br>".$vecAgentes[$contA][2];
				}else{
					$vecAgentes[$contA][1] = "off.png";
					$vecAgentes[$contA][2] ="<span style='font-size:9px; color:gray'>-</span>";
				}
				
				$contA++;
			} 
			else if ( !is_numeric(substr($vecTemp[$i],0,$h)) && !is_numeric(strpos($vecTemp[$i], "Name"))  &&  substr($vecTemp[$i],0,$h) != "" ) {	
			
				$vecTroncales[$cTroncal][0] = substr($vecTemp[$i],0,$h);
				
				if (substr($vecTemp[$i],69,2) == "OK" || substr($vecTemp[$i],88,2) == "OK" || substr($vecTemp[$i],6,2) == "ON" || substr($vecTemp[$i],95,2) == "OK")
				{
					$cont = 0;
					$vecTroncales[$cTroncal][1] = "troncalOn.png"; // asignación por defecto										
					for ($j = 0; $j < count($vecTemp1); $j++)
					{
						$vecCurso = split("!", $vecTemp1[$j]);
						if (strpos($vecCurso[0], $vecTroncales[$cTroncal][0]) > -1)
						{
							$cont++;
							$vecTroncalAdd[$cTroncal] .= $cont.". ".$vecCurso[0]." - ".$vecCurso[7]."<br>";
						}
					}
				} else
					$vecTroncales[$cTroncal][1] = "troncalOff.png";

				$cTroncal++;
			}
			else if ( is_numeric(substr($vecTemp[$i],0,$e)) && substr($vecTemp[$i],0,$e) <= 16) {
				$vecZAP[$contZ][0] = substr($vecTemp[$i],0,$e);
				$vecTroncalZAP = explode("/", $vecTemp[$i]);

				if (str_replace(" ", "", $vecTroncalZAP[1]) == "ON"){
					$vecZAP[$contZ][1] = "lineaon.png"; // asignación por defecto
					
					for ($j = 0; $j < count($vecTemp1); $j++){						
						if (beginsWith( $vecTemp1[$j], "DAHDI/".$vecZAP[$contZ][0]."-" )){							
							$nDahdi = explode("!", $vecTemp1[$j]);
							if (substr($nDahdi[4],0,2) == "Ri")
								$vecZAP[$contZ][1] = "lineabusy.png";
							else if (substr($nDahdi[4],0,2) == "Up")
								$vecZAP[$contZ][1] = "lineabusy.png";							
						}else {
							if (file_exists("/proc/dahdi"))
							{
								$zapAvilable = shell_exec("cat /proc/dahdi/* | grep \\ ".$vecZAP[$contZ][0]."\\ ");
								if (strpos($zapAvilable, "RED") > 0)
									$vecZAP[$contZ][1] = "notPlug.png";
							}
						}
					}
				
				}else
					$vecZAP[$contZ][1] = "lineaoff.png";
				$contZ++;
			}
		}
		
		sort($vecAgentes);
		if (isset($vecZAP))
			sort($vecZAP);
		echo "
		<strong>Disposici&oacute;n de las Extensiones Telef&oacute;nicas</strong><br>
		Mapa actual de todas las extensiones de la Compa&ntilde;&iacute;a<hr>
		<table width='100%' border=\"0\" style=\"border:1px solid #CCCCCC\">";
		for ($i=0; $i < count($vecAgentes); $i+=8){
			?>
	   <tr>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[$i][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:10px">
					<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[$i][0];?>')" style="text-decoration:none; color:#000000">
						<strong><?php echo $vecAgentes[$i][0]."<br>".$vecAgentes[$i][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+1)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:10px">
					<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+1)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+1)][0]."<br>".$vecAgentes[($i+1)][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+2)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:10px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+2)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+2)][0]."<br>".$vecAgentes[($i+2)][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+3)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:10px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+3)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+3)][0]."<br>".$vecAgentes[($i+3)][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+4)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:10px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+4)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+4)][0]."<br>".$vecAgentes[($i+4)][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+5)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:8px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+5)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+5)][0]."<br>".$vecAgentes[($i+5)][2];?></strong></a></div></td>
		<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+6)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:7px; margin-left:5px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+6)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+6)][0]."<br>".$vecAgentes[($i+6)][2];?></strong></a></div></td>
        <td width="117" align="center" valign="middle" style="font-family:verdana; font-size:12px">
			<div style="background:url(images/<?php echo $vecAgentes[($i+7)][1]; ?>);width:111px; height:55px; border:1px solid white">
				<div style="text-align:center; margin-top:6px; margin-left:10px">
				<a href="#admin" onclick="javascript:peer('<?php echo $vecAgentes[($i+7)][0];?>')" style="text-decoration:none; color:#000000">
					<strong><?php echo $vecAgentes[($i+7)][0]."<br>".$vecAgentes[($i+7)][2];?></strong></a></div></td>
	  </tr>
			<?php
		}	
		if (count($vecZAP) > 0){
			echo '<tr><td colspan=8 align=center><br><strong>L&iacute;neas Disponibles</strong></td></tr>';					
		?>
		<tr><td colspan="8" align="center">
			<table border="0">
		<?php for ($i=0; $i < count($vecZAP); $i++){ 
			if ($i == 0)
				echo "<tr>";
			if ($i == 8)
				echo "</tr><tr>";
			?>
			<td width="117" align="center" valign="middle" style="font-family:verdana; font-size:11px">
				<div style="background:url(images/<?php echo $vecZAP[$i][1]; ?>);width:110px; height:41px; border:1px solid white">
					<div style="text-align:center; margin-top:13px; margin-left:10px">
						<strong><?php if($vecZAP[$i][0] > 0) echo "Linea ".$vecZAP[$i][0];?></strong>
					</div>
				</div>
			</td>
		<?php } ?>
			</table>
		</td></tr>
		
		<?php
		}
		
		if (count($vecTroncales) > 0){
			echo '<tr><td colspan=8 align=center><br><strong>Troncales Digitales Disponibles</strong></td></tr>';					
		?>
		<tr><td colspan="8" align="center">
			<table border="0">
		<?php for ($i=0; $i < count($vecTroncales); $i++){ 
			if ($i == 0)
				echo "<tr>";
			if ($i == 8)
				echo "</tr><tr>";
			?>
			<td width="117" align="center" valign="top" style="font-family:verdana; font-size:11px">
				<div style="background:url(images/<?php echo $vecTroncales[$i][1]; ?>);width:143px; height:41px; border:1px solid white">
					<div style="text-align:center; margin-top:13px; margin-left:10px">
					<strong><?php echo $vecTroncales[$i][0];?></strong>
					</div>
				</div>
                <?php if ($vecTroncalAdd[$i] != "") {?>
                <div style="text-align:left; font-size:9px; margin-left:3px; margin-right:3px; 
                background-color:#F8F8F8; border:1px solid #DDD">
                	<div style="margin:3px; margin-left:6px;">
                	<strong><?php echo $vecTroncalAdd[$i]; ?></strong>
                    </div>
                </div>
                <?php } ?>
			</td>
		<?php } ?>
			</table>
		</td></tr>
		
		<?php
		}
		
		
		
		echo "</table>";
	}else {
		mysql_db_query(DATABASE,"REPAIR TABLE server");				
		$sSQL = "INSERT INTO `server` (agentes, canales, queue) VALUES ('','','')";
		mysql_db_query(DATABASE,$sSQL);
	}
}

elseif ($_GET['tipo'] == "logQueue")
{
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "cat /var/log/asterisk/messages | grep ABANDON > /var/www/htdocs/log.txt\n";
	fwrite($fh, $stringData);
	fclose($fh);	
	sleep(3);
	
	$handle = @fopen("log.txt", "r");
	if ($handle) {
		while (($buffer = fgets($handle, 4096)) !== false) {
			$datos = explode("|", $buffer);
			$sSQL .= "uniqueid = '".$datos[1]."' OR ";
		}
		
		$nSQL = "SELECT * FROM cdr WHERE ".substr($sSQL, 0, strlen($sSQL) - 2)." ORDER BY calldate";
		$result = mysql_db_query(DATABASE,$nSQL);
		if (mysql_num_rows($result) > 0)
		{
			echo "<table>";
			while($linea = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				echo "<tr>
					<td>".$linea['calldate']."</td>
					<td>".$linea['dcontext']."</td>
					<td>".$linea['src']."</td>
					<td>".$linea['clid']."</td>
					<td>".$linea['dst']."</td>
					<td>".$linea['duration']."</td>
					<td>".$linea['billsec']."</td>
					<td>".$linea['disposition']."</td>
					</tr>";
			}
			echo "</table>";
		}
		
		if (!feof($handle)) {
			echo "Error: unexpected fgets() fail\n";
		}
		fclose($handle);
	}
}

elseif ($_GET['tipo'] == "cargarPerfil"){
	$sSQL = "SELECT * FROM perfil WHERE idperfil = '".$_GET['id']."'";
	$result = mysql_db_query(DATABASE,$sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);
	echo $linea['contextos'];
}

elseif ($_GET['tipo'] == "guardarPerfil"){
	$sSQL = "UPDATE perfil SET contextos = '".$_GET['perfiles']."' WHERE idperfil = '".$_GET['id']."'";
	mysql_db_query(DATABASE,$sSQL) or die("Imposible realizar esta operación\n".mysql_error());
	echo "Cambios Realizados Correctamente.";
}

elseif ($_GET['tipo'] == "queueLogout"){
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "asterisk -rx'agent logoff Agent/".$_GET['agente']."'\n";
	fwrite($fh, $stringData);
	fclose($fh);	
	echo "Agente removido con exito.";
}

elseif ($_GET['tipo'] == "queueControl"){
	$sSQL = "SELECT queue FROM server";
	$result = mysql_db_query(DATABASE,$sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if (count($linea) > 0){
		$vecQQ = split("<<",$linea['queue']);
		
		for ($l=0; $l < count($vecQQ)-1; $l++)
		{
			$contA = 0;
			$contZ = 1;
			$contOn = 0;
			$contOff = 0;
			$contBusy = 0;
			$contTT = 0;
			$toPublish = "";

			$vecQ = str_replace("Agent", "",$vecQQ[$l]);
			$vecQ = str_replace("Local", "", $vecQ);
			$vecTemp = split("<",$vecQ);
			$vecStat = split(",",$vecTemp[0]);
			sort($vecTemp, SORT_REGULAR);	
			
			for ($i = 0; $i < count($vecTemp); $i++){						
				if (strpos($vecTemp[$i],"/") > -1){
					$contTT++;
					if (trim(strpos($vecTemp[$i],"Unavailable")) > 0 ) {
						if (substr($vecTemp[$i], 0, 4) == substr($vecTemp[$i+1], 0, 4))
						{						
							$i++;
							if (strpos($vecTemp[$i],"Not in use)") > -1) {
								$toPublish .= '
								<td width="171" align="center" valign="middle" style="font-family:verdana; font-size:12px">
								<div style="background:url(images/queue_on.jpg);width:171px; height:41px; border:1px solid white">
									<div style="text-align:center; margin-top:13px; margin-left:10px">			
									<a href="#" onclick="javascript:removeAgent(\''.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'\')" style="text-decoration:none; color:black">
									<strong>Agente '.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'</strong>						
									</a>
									</div>
								</div>
								</td>';
								$contOn++;
							}
							else if (strpos($vecTemp[$i],"Busy)") > 0 || strpos($vecTemp[$i],"In use)") > -1 ) {
								$toPublish .= '
								<td width="171" align="center" valign="middle" style="font-family:verdana; font-size:12px">
								<div style="background:url(images/queue_busy.jpg);width:171px; height:41px; border:1px solid white">
									<div style="text-align:center; margin-top:13px; margin-left:10px">
									<strong>Agente '.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'</strong>
									</div>
								</div>
								</td>';
								$contOn++;
								$contBusy++;
							}
						} else {
							$toPublish .= '
							<td width="171" align="center" valign="middle" style="font-family:verdana; font-size:12px">
							<div style="background:url(images/queue_off.jpg);width:171px; height:41px; border:1px solid white">
								<div style="text-align:center; margin-top:13px; margin-left:10px">
								<strong>Agente '.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'</strong>
								</div>
							</div>
							</td>';	
						}
						$contOff++;
					} 
					else if (strpos($vecTemp[$i],"Not in use)") > -1) {
						$toPublish .= '
						<td width="171" align="center" valign="middle" style="font-family:verdana; font-size:12px">
						<div style="background:url(images/queue_on.jpg);width:171px; height:41px; border:1px solid white">
							<div style="text-align:center; margin-top:13px; margin-left:10px">			
							<a href="#" onclick="javascript:removeAgent(\''.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'\')" style="text-decoration:none; color:black">
							<strong>Agente '.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'</strong>						
							</a>
							</div>
						</div>
						</td>';
						$contOn++;
					}
					else if (strpos($vecTemp[$i],"Busy)") > 0 || strpos($vecTemp[$i],"In use)") ) {
						$toPublish .= '
						<td width="171" align="center" valign="middle" style="font-family:verdana; font-size:12px">
						<div style="background:url(images/queue_busy.jpg);width:171px; height:41px; border:1px solid white">
							<div style="text-align:center; margin-top:13px; margin-left:10px">
							<strong>Agente '.substr($vecTemp[$i],strpos($vecTemp[$i],"/")+1,3).'</strong>
							</div>
						</div>
						</td>';
						$contOn++;
						$contBusy++;
					}
					if ($contZ%3 == 0 && $contZ > 0)
						$toPublish .= "</tr>\n\t<tr>";	
					$contZ++;
				}			
			}
			
			$estadistica .= "			
			<div style='margin-left:20px; margin-top:10px; margin-right:20px;'>
			<div style='background-color:#ECEBF3; border:1px solid #CCC; margin-bottom: 5px'>
				<div style='margin: 2px 3px 0px 10px'>
				<img src='images/stats.png' align='absmiddle'> <strong>Estad&iacute;sticas Generales 
					".substr($vecStat[0], 0, strpos($vecStat[0], " "))."</strong>
				</div>
			</div>
			<table border='0' width='100%'>
				<tr>
					<td  style='font-family:verdana; font-size:12px;'>Tiempo Promedio de Espera: </td>
					<td  style='font-family:verdana; font-size:12px;'><strong>".substr($vecStat[0],strpos($vecStat[0],"strategy")+10, (strpos($vecStat[0],"hold")-strpos($vecStat[0],"strategy")-10))."</strong></td>
				</tr>
				<tr>
					<td  style='font-family:verdana; font-size:12px;'>Llamadas Recibidas: </td>
					<td  style='font-family:verdana; font-size:12px;'>".substr($vecStat[2],3)."</td>
				</tr>
				<tr>
					<td style='font-family:verdana; font-size:12px'>Llamadas Abandonadas: </td>
					<td  style='font-family:verdana; font-size:12px;'>".substr($vecStat[3],3)."</td>
				</tr>						
			</table>
			<br>
			<div style='background-color:#ECEBF3; border:1px solid #CCC; margin-bottom: 2px'>
				<div style='margin: 2px 3px 1px 10px'>
				<img src='images/ag.png' align='absmiddle'> <strong>Agentes en Cola: ".$contTT."</strong>
				</div>
			</div>
			<table border='0' width='100%' >
				<tr>
					<td style='font-family:verdana; font-size:12px' align='left'>Agentes en L&iacute;nea</td>
					<td width='17%' style='font-family:verdana; font-size:12px' align='left'>".$contOn."</td>
				</tr>
				<tr>
					<td style='font-family:verdana; font-size:12px' align='left'>Agentes Ocupados</td>
					<td style='font-family:verdana; font-size:12px' align='left'>".$contBusy."</td>
				</tr>
			</table>
			<br>
			<div style='background-color:#ECEBF3; border:1px solid #CCC; margin-bottom: 5px'>
				<div style='margin: 3px 3px 3px 10px'>
				<img src='images/fleche-d.gif' align='bottom'> <strong>Llamadas en Cola</strong>
				</div>
			</div>		
			";	
			sort($vecTemp[$j]);	
			for ($i = 0; $i < count($vecTemp[$j]); $i++){
				if (strpos($vecTemp[$j][$i],"gent/") > 0)
					break;
					
				if (strpos($vecTemp[$j][$i],"(wait:")){
					$estadistica .= "<img src='images/incoming.png' align='absmiddle'> Llamada ".substr($vecTemp[$j][$i], 0, strpos($vecTemp[$j][$i],".")+1)." Tiempo en Espera: ".substr($vecTemp[$j][$i],strpos($vecTemp[$j][$i],"wait")+6, (strpos($vecTemp[$j][$i],", prio")-strpos($vecTemp[$j][$i],"wait")-6))."<br>";
					$contA++;
				}			
			}
			$estadistica .= "<br><strong>Llamadas Totales en Espera: ".$contA."</strong><br><br></div>";
	
			$agentes .= "			
			<table border=\"0\" cellpadding='1' cellspacing='2' style=\"border:1px solid #CCCCCC; margin-bottom:3px\">
				<tr>".$toPublish."
				</tr>
			</table>
			";
		}
		
		echo "<div style='background: #740003; border: 1px solid black; margin: 0px 0px 3px 0px; position: relative; top: -0.1em; width:100%'>
				<div style='background: #740003; color: #EFEC85; margin: 3px 3px 3px 3px; text-align:center' align='center'>
					<strong>.:: Consola para Sistema de CALLCENTER ::.</strong>			
				</div>
			</div>
		<table cellspacing='0' cellpadding='0' border='0' width='95%' style='margin-bottom:3px'>
			<tr>
				<td valign='top' style='width:330px'>".$estadistica."</td>
				<td valign='top'>".$agentes."</td>
			</tr>
		</table>";
	}
}

elseif ($_GET['tipo'] == "changeDate"){
	$fecha = trim(str_replace("-","",$_GET['fecha']));
	$fecha = explode(" ",$fecha);
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "date -s ".$fecha[0]."\n";
	$stringData .= "date -s ".$fecha[1]."\n";
	fwrite($fh, $stringData);
	fclose($fh);
}

elseif ($_GET['tipo'] == "fax"){
	$sSQL = "UPDATE opciones SET nombre='".$_GET['nombre']."', email='".$_GET['email']."', telefono='".$_GET['telefono']."', faxExten='".$_GET['exten']."'";	
	$result = mysql_db_query(DATABASE,$sSQL) or die('Imposible realizar este cambio.'.mysql_error());
	generarIVR();
	dialKerberus();
	echo "<br>Los cambios se han efectuado correctamente.";
}

elseif ($_GET['tipo'] == "limpiarConsola"){
	
	$sSQL = "DELETE FROM fax";
	mysql_db_query(DATABASE,$sSQL);
	echo "<br>Los cambios se veran reflejados en pocos segundos.";
}

elseif ($_GET['tipo'] == "faxLoad"){
	
	$sSQL = "SELECT * FROM fax ORDER BY fecha DESC";
	$result = mysql_db_query(DATABASE,$sSQL);
	
	echo "<center><table border='0' width='97%' cellspacing=0 cellpadding=4>
			<tr>
				<td style='font-size:11px'><strong>No.</strong></td>
				<td style='font-size:11px'><strong>Fecha</strong></td>
				<td style='font-size:11px'><strong>Remitente</strong></td>
				<td style='font-size:11px'><strong>P&aacute;ginas</strong></td>
				<td style='font-size:11px'><strong>Archivo</strong></td>
			  </tr>
				";
	$cont = 0;
	while ($linea = mysql_fetch_assoc($result)){
		
		$cont++;
		if ($cont%2 == 0){
		echo "<tr>
				<td style='font-size:11px'>".$cont.".</td>
				<td style='font-size:11px'>".$linea['fecha']."</td>
				<td style='font-size:11px'>".$linea['remitente']."</td>
				<td style='font-size:11px' align='center'>".$linea['pages']."</td>";
			
			if (file_exists("cmds/fax/".$linea['archivo']))
				echo "<td style='font-size:11px'><a href='cmds/fax/".$linea['archivo']."' target='_blank'>Descargar</td>";
			else
				echo "<td style='font-size:11px'>Fallido</td>";
			  echo "</tr>";
		} else {
		echo "<tr>
				<td style='font-size:11px; background-color:#E9EBD1'>".$cont.".</td>
				<td style='font-size:11px; background-color:#E9EBD1'>".$linea['fecha']."</td>
				<td style='font-size:11px; background-color:#E9EBD1'>".$linea['remitente']."</td>
				<td style='font-size:11px; background-color:#E9EBD1' align='center'>".$linea['pages']."</td>";				
			if (file_exists("cmds/fax/".$linea['archivo']))
				echo "<td style='font-size:11px; background-color:#E9EBD1'><a href='cmds/fax/".$linea['archivo']."' target='_blank'>Descargar</td>";
			else
				echo "<td style='font-size:11px; background-color:#E9EBD1'>Fallido</td>";
			  echo "</tr>";
		}
		
	}		
	echo "</table>";				
}

elseif ($_GET['tipo'] == "guardarExt"){
	//echo $_GET["user"].$_GET["prot"].$_GET["dtmf"].$_GET["pick"].$_GET["email"].$_GET["call"].$_GET["pwd"]
	$sSQL = "INSERT INTO peer (usuario, protocolo, dtmf, pickup, email, callerid, secret, voice) VALUES (".$_GET["user"].",'".$_GET["prot"]."','".$_GET["dtmf"]."', '".$_GET["pick"]."', '".$_GET["email"]."', '".$_GET["call"]."', ".$_GET["pwd"].", '".$_GET['voice']."') ON DUPLICATE KEY UPDATE protocolo=VALUES(protocolo), dtmf=VALUES(dtmf), pickup=VALUES(pickup), email=VALUES(email), callerid=VALUES(callerid), secret=VALUES(secret), voice=VALUES(voice)";
	mysql_db_query(DATABASE,$sSQL) or die('La operación no es v&aacute;lida\n'.$sSQL."\n".mysql_error());
	
	$sSQL = "SELECT * FROM peer WHERE protocolo='".$_GET["prot"]."' ORDER BY usuario";
	$result = mysql_db_query(DATABASE,$sSQL) or die($mysql_error());
	
	if ($_GET["prot"] == "SIP"){
		$sip_conf = fopen("cmds/sip.conf", 'w+');
		$sipdata = "[general]\nport = 5060\ncontext=kerberus\ndisallow = all\nallow = g729\nallow = gsm\nallow = alaw\nallow = ulaw\nallow = h264\nlanguage = es\nqualify=yes\nvideosupport=yes\nt38pt_udptl=no\nallowsubscribe=yes\nnotifyringing = yes\nnotifyhold = yes\nlimitonpeers = yes\n\n#include sip_nat.conf\n";
		
		if (file_exists("cmds/sip_trunk.conf"))
			$sipdata .= "#include sip_trunk.conf\n\n";
	
		fwrite($sip_conf, $sipdata);
		$sipdata = "";
		
		$tmpSQL = "SELECT destino, ivr FROM opciones";
		$rr = mysql_db_query(DATABASE, $tmpSQL);
		$opcionEntrante = mysql_fetch_array($rr);	
			
		if ( strlen($opcionEntrante['destino'] > 3) )
			$opcionEntrante['destino'] = substr($opcionEntrante['destino'], 1);
			
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			
			if ($opcionEntrante['destino'] == $linea['usuario'] || $opcionEntrante['ivr'] == $linea['usuario']) {
				$sipdata .= "[".$linea['usuario']."]\ntype=peer\ndefaultuser=".$linea['usuario']."\nfromuser=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nnat=yes\ncontext = kerberus\ncall-limit=6\n";
			} else {
			$sipdata .= "[".$linea['usuario']."]\ntype=peer\ndefaultuser=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup'].",30\ndtmfmode=".$linea['dtmf']."\nnat=yes\ncontext = kerberus\ncall-limit=2\n";
			}
			
			$sipdata .= "\n";
			$pickup[$linea['pickup']] .= $linea['usuario']."&";
		}
		
		fwrite($sip_conf, $sipdata);
		fclose($sip_conf);
		
		for ($i = 1; $i <= count($pickup); $i++)
		{
			if (strlen($pickup[$i]) > 2)
			$toWrite .= "exten => 8".$i.",1,Pickup(".substr($pickup[$i], 0, strlen($pickup[$i])-1).")\n";
		}
		
		$pick_conf = fopen("cmds/pick_group.conf", 'w+');
		fwrite($pick_conf, $toWrite);
		fclose($pick_conf);
	}
	else if ($_GET["prot"] == "IAX2"){
		
		$iax_conf = fopen("cmds/iax.conf", 'w+');
		$iaxdata = "[general]\nbandwidth=medium\ndisallow=all\nallow=ulaw,gsm,g729,g726\nautokills=yes\ncanreinvite = no\nlanguage = es\n\n";
		
		if (file_exists("cmds/iax_trunk.conf"))
			$iaxdata .= "#include iax_trunk.conf\n\n";
		
		fwrite($iax_conf, $iaxdata);
		$iaxdata = "";
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			$iaxdata .= "[".$linea['usuario']."]\ntype=friend\nusername=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncontext=kerberus\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nqualify = yes\nnat=yes\nrequirecalltoken=no\n\n";
		}
		
		fwrite($iax_conf, $iaxdata);
		fclose($iax_conf);
	}
	
	else if ($_GET["prot"] == "DAHDI"){
		$zap_conf = fopen("cmds/zap.conf", 'w+');		
		$zapdata = "";
		
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			$zapdata .= "context = kerberus\nsignalling = fxo_ks\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\nchannel=> ".($linea['usuario']-600)."\n\n";
		}
		
		fwrite($zap_conf, $zapdata);
		fclose($zap_conf);
	}
		
	// Voicemail_extra.conf
	$sSQL = "SELECT usuario,secret,callerid,email FROM peer ORDER BY usuario";
	$result = mysql_db_query(DATABASE,$sSQL);
	$voice = fopen("cmds/voicemail_extra.conf", 'w+');
	$stringData = "";

	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
		$stringData .= $linea['usuario']." => ".$linea['secret'].",".$linea['callerid'].",".$linea['email'].",,|nextaftercmd=yes\n";
	}
	
	// Re-crear el extensions_extra.conf con el tamaño de las extensiones
	createExtra();
	crearHint();
	
	fwrite($voice, $stringData);
	fclose($voice);
	
	//	Reload kerberus
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	
	if (trim($_GET['pPer']) != ""){
		fputs($socket, "Action: DBPut\r\n"); 
		fputs($socket, "Family: ".$_GET["user"]."\r\n");
		fputs($socket, "Key: PWD\r\n");
		fputs($socket, "Val: ".$_GET['pPer']."\r\n\r\n");
		$wrets=fgets($socket,128);
	}
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: voicemail reload\r\n\r\n"); 
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: iax2 reload\r\n\r\n"); 
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: sip reload\r\n\r\n");
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: dialplan reload\r\n\r\n");
	$wrets=fgets($socket,128);
	
	$sSQL = "SELECT * FROM peer WHERE pickup LIKE '%21%' order by usuario";
	$res	= mysql_db_query(DATABASE,$sSQL);

	while ($linea = mysql_fetch_array($res, MYSQL_ASSOC)){
		$sGrupo .= $linea['protocolo']."/".$linea['usuario']."&";
	}
	
	fputs($socket, "Action: DBPut\r\n"); 
	fputs($socket, "Family: dialgroup\r\n");
	fputs($socket, "Key: g21\r\n");
	fputs($socket, "Val: ".substr($sGrupo,0,(strlen($sGrupo)-1))."\r\n\r\n");
	$wrets=fgets($socket,128);
	sleep(1);
	
	fclose($socket);	
	echo "<font color=\"#009933\"<br>La extensi&oacute;n <strong>".$_GET['user']."</strong> Fue actualizada con &eacute;xito</font>";
}
elseif ($_GET['tipo'] == "ivrTree"){
	cargarArbol();	
}

elseif ($_GET['tipo'] == "peer"){
	$sSQL = "SELECT * FROM peer WHERE usuario = '".$_GET['idPeer']."'";
	$result = mysql_db_query(DATABASE,$sSQL);
	$cad = "";
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);
	
	if ($_GET['idPeer'] >= 600 && $_GET['idPeer'] < 700){
		$cad  = $_GET['idPeer']."|";
		$cad .= $linea['secret']."|";
		$cad .= "DAHDI|";
		
	}else{		
		$cad  = $linea['usuario']."|";
		$cad .= $linea['secret']."|";
		$cad .= $linea['protocolo']."|";
	}
	
	$cad .= $linea['dtmf']."|";
	$cad .= $linea['pickup']."|";
	$cad .= $linea['email']."|";
	$cad .= $linea['callerid']."|";
	$cad .= $linea['voice'];
	echo $cad;
	
}
elseif ($_GET['tipo'] == "peerDel"){
	$sSQL = "DELETE FROM peer WHERE usuario = ".$_GET['idPeer'];
	mysql_db_query(DATABASE,$sSQL) or die('La operación no es v&aacute;lida\n'.mysql_error());
	echo "<font color=\"#009933\"<br>La extensi&oacute;n <strong>".$_GET['user']."</strong> Fue eliminada con &eacute;xito</font>";
	
	$sSQL = "SELECT * FROM peer WHERE protocolo='".$_GET["prot"]."' ORDER BY usuario";
	$result = mysql_db_query(DATABASE,$sSQL);
	
	if ($_GET["prot"] == "SIP"){
		
		$sip_conf = fopen("cmds/sip.conf", 'w+');
		$sipdata = "[general]\nport = 5060\ncontext=kerberus\ndisallow = all\nallow = g729\nallow = gsm\nallow = alaw\nallow = ulaw\nallow = h264\nlanguage = es\nqualify=yes\nvideosupport=yes\nt38pt_udptl=no\nallowsubscribe=yes\nnotifyringing = yes\nnotifyhold = yes\nlimitonpeers = yes\n\n#include sip_nat.conf\n";
		
		if (file_exists("cmds/sip_trunk.conf"))
			$sipdata .= "#include sip_trunk.conf\n\n";
	
		fwrite($sip_conf, $sipdata);
		$sipdata = "";
		
		$tmpSQL = "SELECT destino, ivr FROM opciones";
		$rr = mysql_db_query(DATABASE, $tmpSQL);
		$opcionEntrante = mysql_fetch_array($rr);		
		if ( strlen($opcionEntrante['destino'] > 3) )
			$opcionEntrante['destino'] = substr($opcionEntrante['destino'], 1);
			
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			
			if ($opcionEntrante['destino'] == $linea['usuario'] || $opcionEntrante['ivr'] == $linea['usuario']) {
				$sipdata .= "[".$linea['usuario']."]\ntype=peer\ndefaultuser=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nnat=yes\ncontext = kerberus\ncall-limit=6\n\n";
			} else {
				$sipdata .= "[".$linea['usuario']."]\ntype=peer\ndefaultuser=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nnat=yes\ncontext = kerberus\ncall-limit=2\n\n";
			}
		}
		
		fwrite($sip_conf, $sipdata);
		fclose($sip_conf);
	}
	else if ($_GET["prot"] == "IAX2"){
		
		$iax_conf = fopen("cmds/iax.conf", 'w+');
		$iaxdata = "[general]\nbandwidth=medium\ndisallow=all\nallow=ulaw,gsm,g729,g726\nautokills=yes\ncanreinvite = no\nlanguage = es\n\n";
		
		if (file_exists("cmds/iax_trunk.conf"))
			$iaxdata .= "#include iax_trunk.conf\n\n";
		
		fwrite($iax_conf, $iaxdata);
		$iaxdata = "";
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			$iaxdata .= "[".$linea['usuario']."]\ntype=friend\nusername=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncontext=kerberus\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nqualify = yes\nnat=yes\nrequirecalltoken=no\n\n";
		}
		
		fwrite($iax_conf, $iaxdata);
		fclose($iax_conf);
	}
	else if ($_GET["prot"] == "DAHDI"){
		$zap_conf = fopen("cmds/zap.conf", 'w+');		
		$zapdata = "";
		
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
			$zapdata .= "context = kerberus\nsignalling = fxo_ks\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\nchannel=> ".($linea['usuario']-600)."\n\n";
		}
		
		fwrite($zap_conf, $zapdata);
		fclose($zap_conf);
	}
	
		
	// Voicemail_extra.conf
	$sSQL = "SELECT usuario,secret,callerid,email FROM peer ORDER BY usuario";
	$result = mysql_db_query(DATABASE,$sSQL);
	$voice = fopen("cmds/voicemail_extra.conf", 'w+');
	$stringData = "";

	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
		$stringData .= $linea['usuario']." => ".$linea['secret'].",".$linea['callerid'].",".$linea['email'].",,|nextaftercmd=yes\n";
	}	
	
	fwrite($voice, $stringData);
	fclose($voice);
	
	// Re-crear el extensions_extra.conf con el tamaño de las extensiones
	createExtra();
	crearHint();
	
	//	Reload kerberus
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: voicemail reload\r\n\r\n"); 

	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: sip reload\r\n\r\n"); 

	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: iax2 reload\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: dialplan reload\r\n\r\n"); 

	$sSQL = "SELECT * FROM peer WHERE pickup LIKE '%21%' order by usuario";
	$res	= mysql_db_query(DATABASE,$sSQL);

	while ($linea = mysql_fetch_array($res, MYSQL_ASSOC)){
		$sGrupo .= $linea['protocolo']."/".$linea['usuario']."&";
	}
	
	fputs($socket, "Action: DBPut\r\n"); 
	fputs($socket, "Family: dialgroup\r\n");
	fputs($socket, "Key: g21\r\n");
	fputs($socket, "Val: ".substr($sGrupo,0,(strlen($sGrupo)-1))."\r\n\r\n");
	$wrets=fgets($socket,128);
	
	sleep(1);
	fclose($socket);
}

elseif ($_GET['tipo'] == "showExt"){
	
	$tempVar = shell_exec('cat cmds/'.$_GET['ext'].'.conf'); 	
	echo "<center>		
		<textarea id='conf' name='conf' cols='62' rows='34' style='font-family: verdana; font-size:12px' width='100%'>".$tempVar."</textarea>
		 </center>
		 <div align='right'><a href=\"#\" onclick=\"javascript:saveConf('".$_GET['ext']."')\" style='text-decoration:none'>
			<img src='images/header-download.png' align='absmiddle' border='0' /><strong>Guardar Cambios</strong>
			</a>
		 </div>
		";
}

elseif ($_GET['tipo'] == "saveConf"){

	shell_exec("echo '".$_GET['cont']."' > cmds/".$_GET['conf'].".conf");

	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	if ($_GET['conf'] == "sip")
		fputs($socket, "Command: sip reload\r\n\r\n"); 
	else
		fputs($socket, "Command: iax2 reload\r\n\r\n");
	$wrets=fgets($socket,128);
	socket_close($socket);
	
	echo "<center><br><br><strong>Cambios efectuados y ejecutados</strong></center><br><br>";
}

elseif ($_GET['tipo'] == "reglas"){
	$sSQL = "UPDATE opciones SET am='".$_GET['am']."', pm='".$_GET['pm']."', diaAM='".$_GET['diaM']."', diaPM='".$_GET['diaP']."', destino='".$_GET['destino']."', ivr='".$_GET['ivrDes']."'";
	mysql_db_query(DATABASE,$sSQL) or die('<center><br><br><strong>LOS VALORES INGRESADOS NO SON CORRECTOS</strong></center>');
	echo "<center><hr width='%90'>Reglas actualizadas con &eacute;xito.<br><br></center>";
}

elseif ($_GET['tipo'] == "ivr"){
	$ivrs = explode("|",$_GET['exten']);
	for ($i=0; $i < count($ivrs); $i++){
		$sSQL = "UPDATE ivr SET output = '".$ivrs[$i]."' WHERE input = '".$i."'";
		mysql_db_query(DATABASE,$sSQL);
	}
	echo "<center><br><br>IVR actualizado con &eacute;xito.<br><br><br></center>";
}

elseif ($_GET['tipo'] == "eliminarOPT"){
	$sSQL = "DELETE FROM ivr WHERE idivr=".$_GET['idivr'];
	mysql_db_query(DATABASE,$sSQL);
	
	generarIVR();
	cargarArbol();
	dialKerberus();

	echo "<center><br><strong>Opci&oacute;n eliminada con &eacute;xito.</strong><br><br></center>";
}

elseif ($_GET['tipo'] == "maxtime"){
	$sSQL = "UPDATE opciones SET tiempo='".$_GET['tiempo']."'";
	mysql_db_query(DATABASE,$sSQL) or die('<center><br><br><strong>LOS VALORES INGRESADOS NO SON CORRECTOS</strong></center>');
	echo "<center><br><br>Duraci&oacute;n maxima de llamadas actualizada con &eacute;xito.<br><br></center>";
}

elseif ($_GET['tipo'] == "delTroncal"){
	$sSQL = "DELETE FROM troncal WHERE nombre='".$_GET['nombre']."'";
	mysql_db_query(DATABASE,$sSQL);
	echo "Troncal eliminada con &eacute;xito.";
}

elseif ($_GET['tipo'] == "loadTroncal"){

	$sSQL = "SELECT * FROM troncal WHERE nombre='".$_GET['nombre']."'";
	$result = mysql_db_query(DATABASE,$sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);

	$resultado = $linea['nombre']."+".$linea['host']."+".$linea['type']."+".$linea['codec']."+".$linea['protocolo']."+".$linea['prefijo']."+".$linea['usuario']."+".$linea['password']."+".$linea['dtmf']."+".$linea['contexto']."+".$linea['prioridad']."+".$linea['callerid']."+".$linea['perfil'];
	echo $resultado;
}

elseif ($_GET['tipo'] == "delRoute"){
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");

	if (substr($_GET['host'], strlen($_GET['host'])-1) == "0")
		$stringData = "route del -net ".$_GET["host"]." netmask ".$_GET['mk']." gw ".$_GET["gw"]."\nrm /etc/rc.d/rc.local\n";
	else
		$stringData = "route del -host ".$_GET["host"]." gw ".$_GET["gw"]."\nrm /etc/rc.d/rc.local\n";
	fwrite($fh, $stringData);
	fclose($fh);
	
	updateRutas();
	
	echo "<br>Ruta eliminada con &eacute;xito";
}

elseif ($_GET['tipo'] == "addRoute"){
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");
	
	if (isset($_GET["host"]) && trim($_GET["host"]) != "")
	{
		if (substr($_GET['host'], strlen($_GET['host'])-1) == "0")
			$stringData = "route add -net ".$_GET["host"]." netmask ".$_GET['mk']." gw ".$_GET["gw"]."\nrm /etc/rc.d/rc.local\n";
		else
			$stringData = "route add -host ".$_GET["host"]." gw ".$_GET["gw"]."\nrm /etc/rc.d/rc.local\n";
	}
	else{
		$stringData = "route del default gw ".trim($_GET["oldgw"])."\nroute add default gw ".trim($_GET["gw"])."\nrm /etc/rc.d/rc.local\n";
		if (trim($_GET['dns']) != "")
			$stringData .= "echo 'nameserver ".$_GET['dns']."' > /etc/resolv.conf\n";
		else
			$stringData .= "echo '' > /etc/resolv.conf\n";
	}
	fwrite($fh, $stringData);
	fclose($fh);
	
	updateRutas();
	
	echo "<br>Ruta agregada con &eacute;xito.";
}

elseif ($_GET['tipo'] == "loadEth"){
	$tempVar = shell_exec("ifconfig ".trim($_GET['sel'])." | grep Bcast");
	$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
	$respuesta = trim($tempVar);
	$tempVar = shell_exec("ifconfig ".trim($_GET['sel'])." | grep Bcast");				
	$tempVar = substr($tempVar,strpos($tempVar,"Mask")+5);	
	$respuesta .= "|".trim($tempVar);
	
	echo $respuesta;
}

elseif ($_GET['tipo'] == "saveEth"){
	if (trim($_GET["ip"]) != "" && trim($_GET["mask"]) != ""){
		$myFile = "cmds/cmd.ker";
		$fh = fopen($myFile, 'w') or die("can't open file");
		
		$stringData = "ifconfig ".$_GET['sel']." ".$_GET['ip']." netmask ".$_GET['mask']."\n";
	
		fwrite($fh, $stringData);
		fclose($fh);
		
		while(file_exists("cmds/cmd.ker")){sleep(1);}
		
		$myFile = "cmds/cmd.ker";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = "cp /var/www/htdocs/cmds/rc.modules /etc/rc.d/rc.modules\n";
		fwrite($fh, $stringData);
		$interfases = shell_exec("ifconfig | grep eth");
		$interfases = explode("\n",$interfases);
		
		foreach($interfases as $int){
			if (trim($int) != ""){
				$tempVar = shell_exec("ifconfig ".substr($int,0,4)." | grep Bcast");
				$tempVar = substr($tempVar,strpos($tempVar,":")+1,(strpos($tempVar,"B")-strpos($tempVar,":"))-2);
				$stringData = "ifconfig ".substr($int,0,4)." ".trim($tempVar);
				$tempVar = shell_exec("ifconfig ".substr($int,0,4)." | grep Bcast");				
				$tempVar = substr($tempVar,strpos($tempVar,"Mask")+5);	
				$stringData .= " netmask ".trim($tempVar);
				$stringData = "echo '".$stringData."' >> /etc/rc.d/rc.modules\n";
				fwrite($fh, $stringData);
			}
		}
				
		fclose($fh);
		echo "Cambios realizados con &eacute;xito<br>";
	}	
}

elseif ($_GET['tipo'] == "updateTroncal"){

	if (trim($_GET['nombre']) != ""){
		$sSQL = "INSERT INTO troncal (nombre,host,type,codec,protocolo,prefijo,usuario,password, dtmf, contexto, prioridad, callerid, perfil) VALUES 
		('".$_GET['nombre']."', '".$_GET['host']."',	'".$_GET['type']."','".$_GET['codec']."','".$_GET['protocolo']."','".$_GET['prefijo']."','".$_GET['usuario']."','".$_GET['password']."','".$_GET['dtmf']."', '".$_GET['contexto']."', ".$_GET['prio'].", '".$_GET['clid']."', '".$_GET['perfil']."') 
		ON DUPLICATE KEY UPDATE	host='".$_GET['host']."',type='".$_GET['type']."',codec='".$_GET['codec']."',protocolo='".$_GET['protocolo']."',prefijo='".$_GET['prefijo']."',usuario='".$_GET['usuario']."',password='".$_GET['password']."', dtmf='".$_GET['dtmf']."', contexto='".$_GET['contexto']."', prioridad='".$_GET['prio']."', callerid='".$_GET['clid']."', perfil='".$_GET['perfil']."'";
	
		mysql_db_query(DATABASE,$sSQL) or die(mysql_error());
		$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
		fputs($socket, "Action: Login\r\n"); 
		fputs($socket, "UserName: kerberus\r\n"); 
		fputs($socket, "Secret: kerberus\r\n\r\n"); 
		
		$contexto = explode("|",$_GET['contexto']);
	
		/*for ($i=0; $i < count($contexto); $i++){
		
			fputs($socket, "Action: DBPut\r\n"); 
			fputs($socket, "Family: tecnologia\r\n");
			fputs($socket, "Key: ".$_GET['nombre']."\r\n");
			fputs($socket, "Val: ".$_GET['protocolo']."\r\n\r\n");
			
			fputs($socket, "Action: DBPut\r\n"); 	
			fputs($socket, "Family: troncal\r\n");
			fputs($socket, "Key: ".$contexto[$i]."\r\n");
			fputs($socket, "Val: ".$_GET['nombre']."\r\n\r\n");	
			
			fputs($socket, "Action: DBPut\r\n"); 
			fputs($socket, "Family: prefijo\r\n");
			fputs($socket, "Key: ".$_GET['nombre']."\r\n");
			fputs($socket, "Val: ".$_GET['prefijo']."\r\n\r\n");
			
		}*/
		
		if ($_GET['protocolo'] != "DAHDI"){
		
			if ($_GET['protocolo'] == "SIP")
				$troncalFile= fopen("cmds/sip_trunk.conf", 'w+');
			else
				$troncalFile= fopen("cmds/iax_trunk.conf", 'w+');						
			
			// Toma solo troncales de un tipo.
			$sSQL = "SELECT * FROM troncal WHERE protocolo = '".$_GET['protocolo']."'";
			$result = mysql_db_query(DATABASE,$sSQL);
			
			while($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
				
				$data .= "[".$linea['nombre']."]\ntype=".$linea['type']."\n";
				
				if (trim($linea['usuario']) != "" && trim($linea['password']) != "" && $linea['host'] != "" && $linea['host'] != "dynamic"){
					$registros .= "register => ".$linea['usuario']."@".$linea['host'].":".$linea['password'].":".$linea['usuario']."@".$linea['host']."\n";					
					
					if ($_GET['protocolo'] == "SIP")
						$data .= "defaultuser=".$linea['usuario']."\nfromuser=".$linea['usuario']."\nsecret=".$linea['password']."\n";
					else
						$data .= "username=".$linea['usuario']."\nsecret=".$linea['password']."\n";
				}
				else if (trim($linea['usuario']) != "" && trim($linea['password']) == ""){
					$registros .= "register => ".$linea['usuario']."@".$linea['host']."\n";
					
					if ($_GET['protocolo'] == "SIP")
						$data .= "defaultuser=".$linea['usuario']."\n";
					else
						$data .= "username=".$linea['usuario']."\n";
				}
				
				$data .= "context=Entrante\ndisallow=all\nhost=".$linea['host']."\nallow=".str_replace("|",",",substr($linea['codec'],0,strlen($linea['codec'])-1))."\nqualify=yes\nnat=yes\ndtmfmode=".$linea['dtmf']."\ncanreinvite = no\ninsecure=port,invite\n";
								
				$data .= "\n";
			}
	
			if (isset($registros) && $registros != "")
				$data = $registros."\n".$data;			
			fwrite($troncalFile, $data);
			fclose($troncalFile);	
			
			fputs($socket, "Action: Command\r\n"); 
			fputs($socket, "Command: ".$_GET['protocolo']." reload\r\n\r\n"); 				
			sleep(1);
		}
		
		$wrets=fgets($socket,128);
		socket_close($socket);
		echo "<center><br><br><strong>Cambios en troncales ejecutados</strong></center><br>";
	}else
		echo "<center><br><br><strong>Faltan par&aacute;metros para configurar esta troncal</strong></center><br>";
}

elseif ($_GET['tipo'] == "delDID"){
	$sSQL = "DELETE FROM did WHERE numero = '".$_GET['numero']."'";
	mysql_db_query(DATABASE, $sSQL);
	
	$didfile= fopen("cmds/did.conf", 'w+');
	$sSQL = "SELECT * FROM did ORDER BY numero";
	$result = mysql_db_query(DATABASE, $sSQL);
	
	while($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
		if ($linea['particion'] != "626")
		{				
			$didata .= "exten => ".trim($linea['numero']).",1,Set(TIMEOUT(digit)=3)\nexten => ".trim($linea['numero']).",n,SET(Call-From=".$linea['particion'].")\nexten => ".trim($linea['numero']).",n,Goto(Entrante,".$linea['particion'].",1)\n\n";
		} else
			$didata .= "exten => ".trim($linea['numero']).",1,Goto(MOD_ALARM,s,1)\n\n";
	}
	
	fwrite($didfile, $didata);
	fclose($didfile);
	sleep(1);
	
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n");
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: reload\r\n\r\n"); 
	
	$wrets=fgets($socket,128);
	socket_close($socket);
	
	$sSQL = "SELECT * FROM did ORDER BY numero";
	$result = mysql_db_query(DATABASE, $sSQL);
	echo "<table border=0 cellspacing=0 cellpadding=2 width='80%'>
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
				<a href='#' onclick=\"javascript:delDID('".$linea['numero']."')\" ><img src='images/delete.png' border=0 title='Eliminar este DID'></a>
			</td>
		</tr>";
		$cont++;
	}
}

elseif ($_GET['tipo'] == "setDID"){

	$didfile= fopen("cmds/did.conf", 'w+');
	$sSQL = "INSERT INTO did (numero, particion, dst) VALUES ('".$_GET['did']."','".$_GET['particion']."', '".$_GET['dstDID']."') ON DUPLICATE KEY UPDATE particion='".$_GET['particion']."', dst='".$_GET['dstDID']."'";
	mysql_db_query(DATABASE, $sSQL);
	
	$sSQL = "SELECT * FROM did ORDER BY numero";
	$result = mysql_db_query(DATABASE, $sSQL);
	while($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
		if ($linea['particion'] != "626")
		{				
			$didata .= "exten => ".trim($linea['numero']).",1,Set(TIMEOUT(digit)=3)\nexten => ".trim($linea['numero']).",n,SET(Call-From=".$linea['particion'].")\nexten => ".trim($linea['numero']).",n,Goto(Entrante,".$linea['particion'].",1)\n\n";
		} else
			$didata .= "exten => ".trim($linea['numero']).",1,Goto(MOD_ALARM,s,1)\n\n";
	}
	
	fwrite($didfile, $didata);
	fclose($didfile);
	sleep(1);
	
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n");
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: dialplan reload\r\n\r\n"); 
	
	$wrets=fgets($socket,128);
	socket_close($socket);
	
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
}

elseif ($_GET['tipo'] == "generarIVR"){	

	generarIVR();
	cargarArbol();
	dialKerberus();
	
}

function generarIVR(){
	//FUNCION PARA CREAR EL IVR
	$sSQL = "SELECT faxExten FROM opciones";
	$result = mysql_db_query(DATABASE, $sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);
	if (trim($linea['faxExten']) != ""){
		$lineaFax = '
; Extension para FAX
exten => '.$linea['faxExten'].',1,Goto(FAX,s,1)';
	}
	
	// Función para sacar la longitud de las extensiones.
	$sSQL = "SELECT usuario FROM peer ORDER BY usuario DESC LIMIT 1";
	$result = mysql_db_query(DATABASE, $sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);	
	$sPad = strlen($linea['usuario'])-1;
	$extLen = str_pad("X",$sPad, "X");
	// Fin

	$cadIVR = ";Generado automaticamente por KERBERUS(TM) QUERY\n
[Entrante]

#include did.conf

exten => s,1,Set(CHANNEL(musicclass)=kerberus)
exten => s,n,MYSQL(Connect connid localhost MOD_SUPPORT support kerberus)
exten => s,n,MYSQL(Query resultid \${connid} SELECT am,pm,diaAM,diaPM,destino,ivr FROM opciones)
exten => s,n,MYSQL(Fetch fetchid \${resultid} AM PM DIAM DIAP DESTINO IVRDEST)
exten => s,n,MYSQL(Clear \${resultid})
exten => s,n,MYSQL(Disconnect \${connid})

exten => s,n,GotoIfTime(\${AM},\${DIAM},*,*?imensaje)
exten => s,n,GotoIfTime(\${PM},\${DIAP},*,*?imensaje)
exten => s,n,GotoIf($[\"\${DESTINO:0:1}\" == \"u\"]?mensaje)
exten => s,n,GotoIf($[\"\${DESTINO:0:1}\" == \"n\"]?nmensaje:extension)
exten => s,n(imensaje),Set(TIMEOUT(digit)=2)
exten => s,n,Background(ivr/Entrante)
exten => s,n(recepcion),GotoIf($[\"\${IVRDEST}\" == \"rep\"]?imensaje)
exten => s,n,GotoIf($[\"\${IVRDEST}\" == \"g21\"]?grupo)
exten => s,n,Goto(Entrante,\${IVRDEST},1)
exten => s,n,Hangup()

exten => s,n(mensaje),Background(ivr/noHorario)
exten => s,n,Background(dejeSumensaje)
exten => s,n,Voicemail(\${DESTINO:1},s)
exten => s,n,Hangup()

exten => s,n(nmensaje),Background(ivr/noHorario)
exten => s,n,Playtones(busy)
exten => s,n,wait(1)
exten => s,n,Hangup()

exten => s,n(extension),Set(TIMEOUT(digit)=1)
exten => s,n,Background(ivr/noHorario)
exten => s,n,Playback(advertencia)
exten => s,n,Goto(Entrante,\${DESTINO:1},1)
exten => s,n,Hangup()

exten => s,n(grupo),Set(gRecepcion=\${DB(dialgroup/g21)})
exten => s,n,Playback(advertencia)
exten => s,n,Macro(marcacionGrupo,\${gRecepcion})";

$vExten ="
; Direccionamiento dinámico
exten => _[1-5]XX,1,Playback(advertencia)
exten => _[1-5]XX,n,Macro(marcacionInterna,\${EXTEN})

exten => _[1-5]XXX,1,Playback(advertencia)
exten => _[1-5]XXX,n,Macro(marcacionInterna,\${EXTEN})

exten => _[1-5]XXXX,1,Playback(advertencia)
exten => _[1-5]XXXX,n,Macro(marcacionInterna,\${EXTEN})

exten => _6XX,1,Playback(advertencia)
exten => _6XX,n,Macro(marcacionInterna,MATH(${EXTEN}-600,i))

";
	
	$cadIVR .= $vExten.$lineaFax;	
	$sSQL = "SELECT destino, opcion FROM ivr WHERE destino LIKE 'Goto%' AND (destino NOT LIKE 'Goto(FAX%' AND destino NOT LIKE 'Goto(MOD%') ORDER BY idivr";
	$result = mysql_db_query(DATABASE, $sSQL);
	$cont = 0;
	$contextos[$cont] = "Entrante";	
	
	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$cont++;
		$contextos[$cont] = str_replace(",s,1)","",str_replace("Goto(","",$linea['destino']));
		$op[$cont] = $linea['opcion'];
	}
	
	for ($i=0; $i < count($contextos); $i++){
		
		$sSQL = "SELECT * FROM ivr WHERE contexto = '".$contextos[$i]."' ORDER BY opcion";
		$result1 = mysql_db_query(DATABASE, $sSQL);
		
		$sSQL = "SELECT * FROM did WHERE particion = '".substr($op[$i],0,3)."' LIMIT 1";
		$res = mysql_db_query(DATABASE, $sSQL);
		$didConfig = mysql_fetch_array($res, MYSQL_ASSOC);
		
		if (trim($didConfig['dst']) != "")
			$dstDefault = $didConfig['dst'];
		else
			$dstDefault = "\${IVRDEST}";
				
		if ($contextos[$i] != "Entrante"){
			$cadIVR .= "\n[".$contextos[$i]."]\nexten => s,1,SET(CALLERID(name)=".$contextos[$i].")\nexten => s,n,Background(ivr/".$contextos[$i].")\n";
			
			if (substr($op[$i],0,2) == "70"){ // Particiones
				$stringStart = strpos($dstDefault,"/") + 1;
				if (!is_numeric(substr($dstDefault,$stringStart)))
					$cadIVR .= "exten => s,n,Set(MONITOR_FILENAME=\${UNIQUEID})\nexten => s,n,Queue(".$dstDefault.",tThH)\n";
				else
					$cadIVR .= "exten => s,n(Frep),Goto(kerberus,".substr($dstDefault,$stringStart).",1)\n";
					
$cadIVR .= "\n; Direccionamiento dinámico
exten => _[1-5]XX,1,Playback(advertencia)
exten => _[1-5]XX,n,Macro(marcacionInterna,\${EXTEN})

exten => _[1-5]XXX,1,Playback(advertencia)
exten => _[1-5]XXX,n,Macro(marcacionInterna,\${EXTEN})

exten => _[1-5]XXXX,1,Playback(advertencia)
exten => _[1-5]XXXX,n,Macro(marcacionInterna,\${EXTEN})

exten => _6XX,1,Playback(advertencia)
exten => _6XX,n,Macro(marcacionInterna,MATH(\${EXTEN}-600,i))

";
			}else
				$cadIVR .= "exten => s,n,Goto(,s,1)\n";
			
		}else
			$cadIVR .= "\n\n";
		
		while ($fila = mysql_fetch_array($result1, MYSQL_ASSOC)) {
			$cc = 1;
			/*if (substr($fila['destino'],0,5) == "Macro"){
				$cadIVR .= "exten => ".$fila['opcion'].",".$cc.",Playback(advertencia)\n";
				$cc++;
			}
			else*/ if (substr($fila['destino'],0,5) == "Queue"){
				$cadIVR .= "exten => ".$fila['opcion'].",".$cc.",Answer()\n";
				$cc++;
				$cadIVR .= "exten => ".$fila['opcion'].",".$cc.",Set(MONITOR_FILENAME=\${UNIQUEID})\n";
				$cc++;
			}
			$cadIVR .= "exten => ".$fila['opcion'].",".$cc.",".$fila['destino']."\n";			
		}
		
		if (substr($op[$i],0,2) == "70"){
			$cadIVR .= "exten => 0,1,Goto(".$contextos[$i].",s,Frep)\n";
			$cadIVR .= "exten => *,1,Goto(".$contextos[$i].",s,1)\n";
			$cadIVR .= "exten => i,1,Playback(es/invalid)\n";
			$cadIVR .= "exten => i,n,Goto(".$contextos[$i].",s,1)\n";
		}else {
			$cadIVR .= "exten => 0,1,Goto(Entrante,s,recepcion)\n";
			$cadIVR .= "exten => *,1,Goto(Entrante,s,imensaje)\n";
			$cadIVR .= "exten => i,1,Playback(es/invalid)\n";
			$cadIVR .= "exten => i,n,Goto(,s,1)\n";
		}
	}
	
	/*$extenFile = fopen("cmds/exten_extra.conf", "w+");
	fwrite($extenFile, $vExten);
	fclose($extenFile);*/
	//createExtra();
	
	$ivrFile = fopen("cmds/ivr_extra.conf", 'w+');
	fwrite($ivrFile, $cadIVR);
	fclose($ivrFile);	
}

function crearHint(){
	$sSQL = "SELECT usuario, protocolo FROM peer ORDER BY usuario";
	$result = mysql_db_query(DATABASE, $sSQL);
	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
		$dHints .= "exten => ".$linea['usuario'].",hint,".$linea['protocolo']."/".$linea['usuario']."\n";	
	}
	
	$hintFile = fopen("cmds/extensions_hint.conf", 'w+');
	fwrite($hintFile, $dHints);
	fclose($hintFile);
}

function createExtra(){
	
	$sSQL = "SELECT faxExten FROM opciones";
	$result = mysql_db_query(DATABASE, $sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$faxLine = "exten => ".$linea['faxExten'].",1,Goto(FAX,s,1)";
	
	$sSQL = "SELECT usuario FROM peer ORDER BY usuario DESC LIMIT 1";
	$result = mysql_db_query(DATABASE, $sSQL);
	$linea = mysql_fetch_array($result, MYSQL_ASSOC);	
	$sPad = strlen($linea['usuario'])-1;
	$extLen = str_pad("X",$sPad, "X");
	
	$vExten ="
#include extensions_hint.conf

; Direccionamiento dinámico
".$faxLine."
exten => _[1-5]XX,1,Macro(marcacionInterna,\${EXTEN})
exten => _[1-5]XXX,1,Macro(marcacionInterna,\${EXTEN})
exten => _[1-5]XXXX,1,Macro(marcacionInterna,\${EXTEN})
exten => _6XX,1,Macro(marcacionInterna,MATH(${EXTEN}-600,i))
";

	$extenFile = fopen("cmds/exten_extra.conf", "w+");
	fwrite($extenFile, $vExten);
	fclose($extenFile);
}

function reloadKerberus(){
	//	Reload kerberus
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: reload\r\n\r\n"); 
	sleep(1);
	$wrets=fgets($socket,128);
	fclose($socket);	
}

function dialKerberus(){
	//	Reload kerberus
	$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
	fputs($socket, "Action: Login\r\n"); 
	fputs($socket, "UserName: kerberus\r\n"); 
	fputs($socket, "Secret: kerberus\r\n\r\n"); 
	
	fputs($socket, "Action: Command\r\n"); 
	fputs($socket, "Command: dialplan reload\r\n\r\n"); 
	sleep(2);
	
	$wrets=fgets($socket,128);
	fclose($socket);	
}

function beginsWith( $str, $sub ) {
   return ( substr( $str, 0, strlen( $sub ) ) === $sub );
}
function endsWith( $str, $sub ) {
   return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
}

function audioLoad($audio){
	return "<embed src='cmds/ivr/".$audio.".wav' autostart=false volume='100' align='absmiddle' style='height: 18px; width:49px'></embed>";
}

function updateRutas(){
	while (file_exists("cmds/cmd.ker")){sleep(1);}
		
	$myFile = "cmds/cmd.ker";
	$fh = fopen($myFile, 'w') or die("can't open file");		
	// Commandos para el /etc/rc.d/rc.local
	$rutas = shell_exec("route -v | grep UG");
	$rutas = explode("\n", $rutas);
	$stringData= "echo \"#!/bin/sh\" >> /etc/rc.d/rc.local\necho \"#\" >> /etc/rc.d/rc.local\necho \"# /etc/rc.d/rc.local:  Local system initialization script.\" >> /etc/rc.d/rc.local\n";
	
	for ($i=0; $i < count($rutas)-1; $i++){
		if (strpos($rutas[$i], "ault") < 2)
		{
			$destino 	= trim(substr($rutas[$i],0,16));
			$mascara 	= trim(substr($rutas[$i],32,16));
			$gateway	= trim(substr($rutas[$i],16,16));
			if (substr($destino, strlen($destino)-1) == "0")
				$stringData .= "echo \"route add -net ".$destino." netmask ".$mascara." gw ".$gateway."\" >> /etc/rc.d/rc.local\n";
			else
				$stringData .= "echo \"route add -host ".$destino." gw ".$gateway."\" >> /etc/rc.d/rc.local\n";
		}
	}
	
	$rutas = shell_exec("route -v | grep default");
	$rutas = trim(substr($rutas,16,15));
	
	$stringData .= "echo \"route add default gw ".$rutas."\" >> /etc/rc.d/rc.local\n";
	$stringData .= "chmod +x /etc/rc.d/rc.local\n";
	fwrite($fh, $stringData);		
	fclose($fh);
}

function cargarArbol(){
	echo "<ul id=\"arbolIVR\" class=\"treeview\">";		
	$sSQL = "SELECT * FROM ivr WHERE contexto='Entrante' ORDER BY opcion";
	$result = mysql_db_query(DATABASE, $sSQL);
	echo "<li> ".audioLoad('Entrante')." <b>Entrante</b><ul rel=\"open\">\n";
	while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
		if (beginsWith($linea['destino'], "Goto")){	// Audio
			if (beginsWith($linea['destino'], "Goto(FAX")){
			
				echo "<li>".$linea['opcion'].". <a href='#' onclick=\"cargaroptIVR('".$linea['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".substr($linea['destino'],5,strpos($linea['destino'],",")-5)."</a></b>\n";
			
			} else {
			
			echo "<li>".$linea['opcion'].". ".audioLoad(substr($linea['destino'],5,strpos($linea['destino'],",")-5))." 
			<a href='#' onclick=\"cargaroptIVR('".$linea['idivr']."')\" style=\"text-decoration:none; color:black\")>".substr($linea['destino'],5,strpos($linea['destino'],",")-5)."</a></b> <ul>\n";
			$sSQL1 = "SELECT * FROM ivr WHERE contexto='".substr($linea['destino'],5,strpos($linea['destino'],",")-5)."' ORDER BY opcion";
			$result1 = mysql_db_query(DATABASE, $sSQL1);
			while ($linea1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
			
				if (beginsWith($linea1['destino'], "Goto")){	// Audio
					if (beginsWith($linea['destino'], "Goto(FAX")){
			
						echo "<li>".$linea['opcion'].". <a href='#' onclick=\"cargaroptIVR('".$linea['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".substr($linea['destino'],5,strpos($linea['destino'],",")-5)."</a></b>\n";
					
					} else {
					
					echo "<li>".$linea1['opcion'].". ".audioLoad(substr($linea1['destino'],5,strpos($linea1['destino'],",")-5))." 
					<a href='#' onclick=\"cargaroptIVR('".$linea1['idivr']."')\" style=\"text-decoration:none; color:black\")>
					".substr($linea1['destino'],5,strpos($linea1['destino'],",")-5)."</b></a><ul>\n";
					
					$sSQL2 = "SELECT * FROM ivr WHERE contexto='".substr($linea1['destino'],5,strpos($linea1['destino'],",")-5)."' ORDER BY opcion";
					$result2 = mysql_db_query(DATABASE, $sSQL2);							
					while ($linea2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					
						if (beginsWith($linea2['destino'], "Goto")){
							if (beginsWith($linea['destino'], "Goto(FAX")){			
								echo "<li>".$linea['opcion'].". <a href='#' onclick=\"cargaroptIVR('".$linea['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".substr($linea['destino'],5,strpos($linea['destino'],",")-5)."</a></b>\n";
							
							} else {
							echo "<li>".$linea2['opcion'].". ".audioLoad(substr($linea2['destino'],5,strpos($linea2['destino'],",")-5))." 
					<a href='#' onclick=\"cargaroptIVR('".$linea2['idivr']."')\" style=\"text-decoration:none; color:black\")>
					".substr($linea2['destino'],5,strpos($linea2['destino'],",")-5)."</b></a></li>\n";
							}
						}else{
							$publicar = str_replace("Macro(marcacionInterna,","Extensi&oacute;n ",substr($linea2['destino'],0,strrpos($linea2['destino'],",")));
							$publicar = str_replace("Macro(marcacionGrupo,SIP/","Extensi&oacute;n ",$publicar);
							$publicar = str_replace("Macro(marcacionGrupo,IAX2/","Extensi&oacute;n ",$publicar);
							$publicar = str_replace("Queue(","Cola <img src='images/fleche-d.gif' border='0'> ",$publicar);
							$publicar = str_replace("IAX2","",$publicar);
							$publicar = str_replace("SIP","",$publicar);
							$publicar = str_replace("&/"," <img src='images/fleche-d.gif' border='0'> ",$publicar);
							echo "<li>".$linea2['opcion'].". 
							<a href='#' onclick=\"cargaroptIVR('".$linea2['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".$publicar."</a></li>\n";
						}
					}
					echo "</ul></li>\n";
					}
				}else{
					$publicar = str_replace("Macro(marcacionInterna,","Extensi&oacute;n ",substr($linea1['destino'],0,strrpos($linea1['destino'],",")));
					$publicar = str_replace("Macro(marcacionGrupo,SIP/","Extensi&oacute;n ",$publicar);
 					$publicar = str_replace("Macro(marcacionGrupo,IAX2/","Extensi&oacute;n ",$publicar);
					$publicar = str_replace("Queue(","Cola <img src='images/fleche-d.gif' border='0'> ",$publicar);
					$publicar = str_replace("IAX2","",$publicar);
					$publicar = str_replace("SIP","",$publicar);
					$publicar = str_replace("&/"," <img src='images/fleche-d.gif' border='0'> ",$publicar);
					echo "<li>".$linea1['opcion'].". 
					<a href='#' onclick=\"cargaroptIVR('".$linea1['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".$publicar."</a></li>\n";
				}
			}
			echo "</ul></li>\n";
			}
		}
		else{
			if (strpos($linea['destino'], "marcacionInterna") == "" && strpos($linea['destino'], "marcacionGrupo") == ""){
				$publicar = substr($linea['destino'],0,strpos($linea['destino'],","));
				$publicar = str_replace("DIAL(SIP/","Extensi&oacute;n ",$publicar);
				$publicar = str_replace("DIAL(IAX2/","Extensi&oacute;n ",$publicar);
			}else {
				$publicar = str_replace("Macro(marcacionInterna,","Extensi&oacute;n ",substr($linea['destino'],0,strrpos($linea['destino'],",")));
				$publicar = str_replace("Macro(marcacionGrupo,SIP/","Extensi&oacute;n ",$publicar);
				$publicar = str_replace("Macro(marcacionGrupo,IAX2/","Extensi&oacute;n ",$publicar);
			}
			$publicar = str_replace("Queue(","Cola <img src='images/fleche-d.gif' border='0'> ",$publicar);
			$publicar = str_replace("IAX2","",$publicar);
			$publicar = str_replace("SIP","",$publicar);
			$publicar = str_replace("&/"," <img src='images/fleche-d.gif' border='0'> ",$publicar);
			echo "<li>".$linea['opcion'].". <a href='#' onclick=\"cargaroptIVR('".$linea['idivr']."')\" style=\"text-decoration:none; color:#666666\")>".$publicar."</a></li>\n";				
		}
	}
		
	echo '</ul></li>
	</ul>
	</div>';	
}
$didata .= "exten => ".trim($linea['numero']).",1,Set(TIMEOUT(digit)=2)\nexten => ".trim($linea['numero']).",2,SET(CALLERID(name)=".$linea['nombre']."\nexten => ".trim($linea['numero']).",3,Goto(Entrante,".$linea['particion'].",1)\n\n";
?>
