<FORM id="frmMails" name="frmMails" style="display:inline">
		<table width="100%" border="0" cellspacing="1" cellpadding="2" align="center" style="border: 1px solid #9D9D59; font-size:14px">
			<tbody><tr>
        		<td align="left" bgcolor="#2A2A2A">
				
					<input type="radio" name="periodo" value="Month" <?php  if (($Period=="Month") || !isset($Period)){ ?>checked="checked" <?php  } ?>> 
					<font face="verdana" color="#ffffff"><b>SELECCI&Oacute;N DEL MES</b></font>
				</td>
      			<td class="bar-search" align="left" bgcolor="#A2A2A2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#A2A2A2"><tr><td>
					&nbsp;&nbsp;Desde : <select name="fromstatsmonth">
					<?php 	$year_actual = date("Y");  	
						for ($i=$year_actual;$i >= $year_actual-1;$i--)
						{		   
							   $monthname = array( "Enero", "Febrero","Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre");
							   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
							   }else{
									$monthnumber=11;
							   }		   
							   for ($j=$monthnumber;$j>=0;$j--){	
										$month_formated = sprintf("%02d",$j+1);
							   			if ($fromstatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
										echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							   }
						}
					?>		
					</select>
					</td><td>&nbsp;&nbsp;Hasta : <select name="tostatsmonth" >
					<?php 	$year_actual = date("Y");  	
						for ($i=$year_actual;$i >= $year_actual-1;$i--)
						{		   
							   $monthname = array( "Enero", "Febrero","Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre");
							   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
							   }else{
									$monthnumber=11;
							   }		   
							   for ($j=$monthnumber;$j>=0;$j--){	
										$month_formated = sprintf("%02d",$j+1);
							   			if ($tostatsmonth=="$i-$month_formated"){$selected="selected";}else{$selected="";}
										echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							   }
						}
					?>
					</select>
					</td></tr></table>
	  			</td>
    		</tr>
			
			<tr>
        		<td align="left" bgcolor="#888662">
					<input type="radio" name="periodo" value="Day" <?php  if ($Period=="Day"){ ?>checked="checked" <?php  } ?>> 
					<font face="verdana" color="#ffffff"><b>SELECCI&Oacute;N DEL D&Iacute;A</b></font>
				</td>
      			<td align="left" bgcolor="#E6E8D2">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#E6E8D2"><tr><td>
	  				 &nbsp;&nbsp;Desde : 
					<select name="fromstatsday_sday">
						<?php  
							for ($i=1;$i<=31;$i++){
								if ($fromstatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
								echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
							}
						?>	
					</select>
				 	<select name="fromstatsmonth_sday">
					<?php 	$year_actual = date("Y");  	
						for ($i=$year_actual;$i >= $year_actual-1;$i--)
						{		   
							   $monthname = array( "Enero", "Febrero","Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre");
							   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
							   }else{
									$monthnumber=11;
							   }		   
							   for ($j=$monthnumber;$j>=0;$j--){	
										$month_formated = sprintf("%02d",$j+1);
							   			if ($fromstatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
										echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							   }
						}
					?>
					</select>
					</td><td>&nbsp;&nbsp;Hasta : 
					<select name="tostatsday_sday">
					<?php  
						for ($i=1;$i<=31;$i++){
							if ($tostatsday_sday==sprintf("%02d",$i)){$selected="selected";}else{$selected="";}
							echo '<option value="'.sprintf("%02d",$i)."\"$selected>".sprintf("%02d",$i).'</option>';
						}
					?>						
					</select>
				 	<select name="tostatsmonth_sday">
					<?php 	$year_actual = date("Y");  	
						for ($i=$year_actual;$i >= $year_actual-1;$i--)
						{		   
							   $monthname = array( "Enero", "Febrero","Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Deciembre");
							   if ($year_actual==$i){
									$monthnumber = date("n")-1; // Month number without lead 0.
							   }else{
									$monthnumber=11;
							   }		   
							   for ($j=$monthnumber;$j>=0;$j--){	
										$month_formated = sprintf("%02d",$j+1);
							   			if ($tostatsmonth_sday=="$i-$month_formated"){$selected="selected";}else{$selected="";}
										echo "<OPTION value=\"$i-$month_formated\" $selected> $monthname[$j]-$i </option>";				
							   }
						}
					?>
					</select>
					</td></tr>					
					</table>
	  			</td>
    		</tr>
            <tr>
				<td class="bar-search" align="left" bgcolor="#2A2A2A">				
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;TIEMPO EN ESPERA</b></font>
				</td>				
				<td class="bar-search" align="left" bgcolor="#A2A2A2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration1type" value="4" <?php if($duration1type==4){?>checked<?php }?>>&gt;</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration1type" value="5" <?php if($duration1type==5){?>checked<?php }?>>&gt; igual</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration1type" value="1" <?php if((!isset($duration1type))||($duration1type==1)){?>checked<?php }?>>igual</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration1type" value="2" <?php if($duration1type==2){?>checked<?php }?>>&lt; igual</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration1type" value="3" <?php if($duration1type==3){?>checked<?php }?>>&lt;</td>	
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="duration1" size="4" value="<?php echo $duration1?>"></td>
				<td width="5%" class="bar-search" align="center" bgcolor="#A2A2A2"></td>
							
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration2type" value="4" <?php if($duration2type==4){?>checked<?php }?>>&gt;</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration2type" value="5" <?php if($duration2type==5){?>checked<?php }?>>&gt; igual</td>								
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration2type" value="2" <?php if($duration2type==1){?>checked<?php }?>>&lt; igual</td>
				<td class="bar-search" align="center" bgcolor="#A2A2A2"><input type="radio" NAME="duration2type" value="3" <?php if($duration2type==3){?>checked<?php }?>>&lt;</td>	
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="duration2" size="4" value="<?php echo $duration2?>"></td>
				</tr></table>
				</td>
			</tr>
            <tr>
				<td class="bar-search" align="left" bgcolor="#888662">				
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;TIMEPO DE LLAMADA</b></font>
				</td>				
				<td class="bar-search" align="left" bgcolor="#E6E8D2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>				
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration3type" value="4" <?php if($duration3type==4){?>checked<?php }?>>&gt;</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration3type" value="5" <?php if($duration3type==5){?>checked<?php }?>>&gt; igual</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration3type" value="1" <?php if((!isset($duration3type))||($duration3type==1)){?>checked<?php }?>>igual</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration3type" value="2" <?php if($duration3type==2){?>checked<?php }?>>&lt; igual</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration3type" value="3" <?php if($duration3type==3){?>checked<?php }?>>&lt;</td>	
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="duration3" size="4" value="<?php echo $duration3?>"></td>
				<td width="5%" class="bar-search" align="center" bgcolor="#E6E8D2"></td>
				
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration4type" value="4" <?php if($duration4type==4){?>checked<?php }?>>&gt;</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration4type" value="5" <?php if($duration4type==5){?>checked<?php }?>>&gt; igual</td>								
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration4type" value="2" <?php if($duration4type==1){?>checked<?php }?>>&lt; igual</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="duration4type" value="3" <?php if($duration4type==3){?>checked<?php }?>>&lt;</td>	
                <td>&nbsp;&nbsp;<INPUT TYPE="text" NAME="duration4" size="4" value="<?php echo $duration4?>"></td>			
				</tr></table>
				</td>
			</tr>		
			<tr>
				<td class="bar-search" align="left" bgcolor="#2A2A2A">
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;ESTADO DE LLAMADA</b></font>
				</td>				
				<td class="bar-search" align="left" bgcolor="#A2A2A2">&nbsp;&nbsp;
                    <select id="estado">
                    	<option value="ENTERQUEUE">Entrante a Callcenter</option>
                    	<option value="COMPLETECALLER">Completa por Usuario</option>
                        <option value="COMPLETEAGENT">Completa por Agente</option>
                        <option value="ABANDON">Abandonadas</option>
                        <option value="AGENTLOGOFF">Deslogueo de agente</option>                        
                    </select>
                </td>
			</tr>
            <tr>
				<td class="bar-search" align="left" bgcolor="#888662">				
					<font face="verdana" size="1" color="#ffffff"><b>&nbsp;&nbsp;AGENTE / CANAL</b></font>
				</td>				
				<td class="bar-search" align="left" bgcolor="#E6E8D2">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                <td>&nbsp;&nbsp;&nbsp;<INPUT TYPE="text" NAME="dstchannel" value="<?php echo $dstchannel?>"></td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="dstchanneltype" value="1" <?php if((!isset($dstchanneltype))||($dstchanneltype==1)){?>checked<?php }?>>Exacto</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="dstchanneltype" value="2" <?php if($dstchanneltype==2){?>checked<?php }?>>Comenzando con</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="dstchanneltype" value="3" <?php if($dstchanneltype==3){?>checked<?php }?>>Que contenga</td>
				<td class="bar-search" align="center" bgcolor="#E6E8D2"><input type="radio" NAME="dstchanneltype" value="4" <?php if($dstchanneltype==4){?>checked<?php }?>>Terminado en</td>
				</tr></table></td>
			</tr>
			<tr>
        		<td class="bar-search" align="left" bgcolor="#2A2A2A"> </td>

				<td class="bar-search" align="center" bgcolor="#E6E8D2">
					<input type="image"  name="image16" align="top" border="0" src="images/button-search.gif" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					Resultado en : Minutos<input type="radio" NAME="resulttype" value="min" <?php if((!isset($resulttype))||($resulttype=="min")){?>checked<?php }?>> - Segundos <input type="radio" NAME="resulttype" value="sec" <?php if($resulttype=="sec"){?>checked<?php }?>>
	  			</td>
    		</tr>
			</table>
	</FORM>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #636338">
	<TR bgcolor="#ffffff"> 
          <TD bgColor=#989564 height=16 style="PADDING-LEFT: 5px; PADDING-RIGHT: 3px"> 
            <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
              <TBODY>
                <TR> 
                  <TD width="99%"><SPAN style="COLOR: #ffffff; FONT-SIZE: 11px"><B>REGISTRO DE LLAMADAS</B></SPAN></TD>
                  <TD align=right> <IMG alt="Back to Top" border=0 height=12 src="images/btn_top_12x12.gif" width=12> 
                  </TD>
                </TR>
              </TBODY>
            </TABLE></TD>
	</TR>
    <TR>
    	<td><div id="resQueue"></div>
    </TR>
</table>