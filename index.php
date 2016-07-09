<?php

session_start();

function cdrpage_getpost_ifset($test_vars)
{
	if (!is_array($test_vars)) {
		$test_vars = array($test_vars);
	}
	foreach($test_vars as $test_var) { 
		if (isset($_POST[$test_var])) { 
			global $$test_var;
			$$test_var = $_POST[$test_var]; 
		} elseif (isset($_GET[$test_var])) {
			global $$test_var; 
			$$test_var = $_GET[$test_var];
		}
	}
}


cdrpage_getpost_ifset(array('s', 't'));


$array = array ("INTRODUCIÓN", "REPORTES", "COMPARACIÓN DE LLAMADAS", "TRÁFICO MENSUAL","CARGA DIARIA", "CONTACTO");
$s = $s ? $s : 0;
$section="section$s$t";

$racine=$PHP_SELF;
$update = "03 January 2011";

$paypal="NOK"; //OK || NOK

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
<title>©2006 - 2011 Kerberus Query | Sistema de Administraci&oacute;n y Consulta Detalladas</title>
<link rel="stylesheet" type="text/css" media="print" href="/css/print.css">
<SCRIPT LANGUAGE="JavaScript" SRC="./encrypt.js"></SCRIPT>
<style type="text/css" media="screen">
	@import url("css/layout.css");
	@import url("css/content.css");
	@import url("css/docbook.css");
</style>
<meta name="MSSmartTagsPreventParsing" content="TRUE">
</head>
<body style="background: #566781">
<br />
<div align="center">
	<div style="width: 960px; background: white; border: 1px solid #000000">
		<div style="margin: 5px 5px 5px 5px; border: 1px solid white">

		<map name="main">
			<area shape="rect" coords="9,2,45,50" href="index.php">
		</map>
        
		<img src="images/top.jpg" border="0" alt="" usemap="#main">

		<?php if ($section=="section0"){?>

		<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #290505" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>				
			</tr>
		</table>
		</div>
		
		<?php 
		require_once("monitor.php");

		} elseif ($section=="section11" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>
        
        <div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #290505" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>				
			</tr>
		</table>
		</div>
        
		<?php
		require_once("queue.php");
		
		} elseif ($section=="section8" && isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?>

		<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #290505" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
			</tr>
		</table>
		</div>
		
		<?php 
		require_once("stats.php");
		
		} elseif ($section=="section1" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>
		
		<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #290505" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
			</tr>
		</table>
		</div>
		<div style="background: #740003; border: 1px solid #360000; margin-bottom:2px; margin-top:2px">
		<center>
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #8A0003" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;Registro de Llamadas&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=2" alt ="" class="subMenu">
					&nbsp;Comparación de Llamadas&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=3" alt ="" class="subMenu">&nbsp;Tráfico Mensual&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=4" alt ="" class="subMenu">&nbsp;Carga Diaria&nbsp;</a></b></td>
                
			</tr>
		</table>
		</center>
		</div>
		
		<?php require("call-log.php");
	 	
		} elseif ($section=="section2" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>
		<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #290505" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
			</tr>
		</table>
		</div>
		<div style="background: #740003; border: 1px solid #360000; margin-bottom:2px; margin-top:2px"><center>
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #8A0003" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;Registro de Llamadas&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=2" alt ="" class="subMenu">
					&nbsp;Comparación de Llamadas&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=3" alt ="" class="subMenu">&nbsp;Tráfico Mensual&nbsp;</a></b></td>
				<td style="background: #8A0003" valign="center"><b><a href="?s=4" alt ="" class="subMenu">&nbsp;Carga Diaria&nbsp;</a></b></td>
			</tr>
		</table></center>
		</div>
		
	<?php require("call-comp.php");
	
	} elseif ($section=="section3" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
	<div style="background: #740003; border: 1px solid #360000; margin-bottom:2px; margin-top:2px"><center>
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #8A0003" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;Registro de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=2" alt ="" class="subMenu">
				&nbsp;Comparación de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=3" alt ="" class="subMenu">&nbsp;Tráfico Mensual&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=4" alt ="" class="subMenu">&nbsp;Carga Diaria&nbsp;</a></b></td>
		</tr>
	</table></center>
	</div>
	
	<?php require("call-last-month.php");
	
	}elseif ($section=="section4" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
	<div style="background: #740003; border: 1px solid #360000; margin-bottom:2px; margin-top:2px"><center>
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #8A0003" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;Registro de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=2" alt ="" class="subMenu">
				&nbsp;Comparación de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=3" alt ="" class="subMenu">&nbsp;Tráfico Mensual&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=4" alt ="" class="subMenu">&nbsp;Carga Diaria&nbsp;</a></b></td>
		</tr>
	</table></center>
	</div>
		
	<?php require("call-daily-load.php");
	
	}elseif ($section=="section5" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){?>       		
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
	<?php require("licencia.php");
	
	}elseif ($section=="section6"){ ?>
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #290505" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
			</tr>
		</table>
		</div>
		
	<?php require("fax.php");
	
	}elseif ($section=="section7" && isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?> 
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
		
	<?php require("opciones-ipbx.php");
	
	} elseif ($section=="section10" && isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true"){?> 
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
            <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
	<div style="background: #740003; border: 1px solid #360000; margin-bottom:2px; margin-top:2px"><center>
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #8A0003" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;Registro de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=2" alt ="" class="subMenu">
				&nbsp;Comparación de Llamadas&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=3" alt ="" class="subMenu">&nbsp;Tráfico Mensual&nbsp;</a></b></td>
			<td style="background: #8A0003" valign="center"><b><a href="?s=4" alt ="" class="subMenu">&nbsp;Carga Diaria&nbsp;</a></b></td>
            <td style="background: #8A0003" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
		</tr>
	</table></center>
	</div>
	<?php require("queue-log.php");
	
	}elseif ($section=="section9" && ((isset($_SESSION["logged_user"]) && $_SESSION["logged_user"] == "true") || (isset($_SESSION["logged_norm"]) && $_SESSION["logged_norm"] == "true"))){
		session_destroy();
	?> 
	
	<div style="background: #292929; border: 0px solid #360000" align="right">
	<table border="0" cellpadding="2" cellspacing="3" summary="">
		<tr>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
			<td style="background: #290505" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
			<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
		</tr>
	</table>
	</div>
	<center>
	<div align="center" style="background: url(images/bkg.jpg); border: 1px solid #9D9D59">
			<div id="content" style="margin: 5px 5px 5px 5px">
	<table width="700">
		<tr>
			<td height="300" align="center" valign="middle" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px">
				Su sesi&oacute;n se ha cerrado exitosamente.				
			</td>
		</tr>
	</table>
			</div>
	</div>
	</center>
	<script language="javascript1.2" type="text/javascript">
		function delayer(){
			window.location = "?s=0"
		}
		setTimeout('delayer()', 2000);
	</script>
	
	<?php 	
	} else {
$men = "";
// P&aacute;gina para logeo	
	if (isset($_GET["optc"]) && $_GET['optc'] == 'logon'){
		
		mysql_connect(HOST, USR, PWD);
		
		if (!file_exists("cmds/sip.conf") && !file_exists("cmds/iax.conf")){
						
			$sSQL = "SELECT * FROM peer WHERE protocolo='SIP' ORDER BY usuario";
			$result = mysql_db_query(DATABASE,$sSQL);
			
			$sip_conf = fopen("cmds/sip.conf", 'w+');
			$sipdata = "[general]\nport = 5060\ncontext=kerberus\ndisallow = all\nallow = ulaw\nallow = alaw\nallow = gsm\nallow = g729\nallow = h264\nlanguage = es\nqualify=yes\nvideosupport=yes\nt38pt_udptl=no\nallowsubscribe=yes\nnotifyringing = yes\nnotifyhold = yes\nlimitonpeers = yes\ncall-limit=2\n\n#include sip_nat.conf\n";
			
			if (file_exists("cmds/sip_trunk.conf"))
				$sipdata .= "#include sip_trunk.conf\n\n";
		
			fwrite($sip_conf, $sipdata);
			$sipdata = "";
			while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)){
				$sipdata .= "[".$linea['usuario']."]\ntype=peer\ndefaultuser=".$linea['usuario']."\nsecret=".$linea['secret']."\nhost=dynamic\ncallerid=".$linea['callerid']." <".$linea['usuario'].">\ncallgroup=".$linea['pickup']."\npickupgroup=".$linea['pickup']."\ndtmfmode=".$linea['dtmf']."\nnat=yes\ncontext = kerberus\n\n";
			}
			
			fwrite($sip_conf, $sipdata);
			fclose($sip_conf);
			
			$sSQL = "SELECT * FROM peer WHERE protocolo='IAX2' ORDER BY usuario";
			$result = mysql_db_query(DATABASE,$sSQL);
			
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
			
			$myFile = "cmds/cmd.ker";
			$fh = fopen($myFile, 'w') or die("can't open file");			
			$stringData = "/etc/rc.d/rc.asterisk restart\n";
			fwrite($fh, $stringData);
			fclose($fh);			
			
			/*	Reload kerberus
			$socket = fsockopen("localhost","5038", $errno, $errstr, $timeout); 
			fputs($socket, "Action: Login\r\n"); 
			fputs($socket, "UserName: kerberus\r\n"); 
			fputs($socket, "Secret: kerberus\r\n\r\n"); 
			
			fputs($socket, "Action: Command\r\n"); 
			fputs($socket, "Command: reload\r\n\r\n"); 
			$wrets=fgets($socket,128);
			fclose($socket);
			*/
		}
		
		// Validar al usuario
		$usuario = 	strtolower(trim($_POST['usuario']));
		$pwd 	= 	trim($_POST['pasw']);		
		$done 	= 	false;	
		
		// Consulta a la db si el usuario y la pwd existen y si es correcto
		mysql_connect(HOST, USR, PWD);
		//mysql_connect('localhost','root','seraph');
		$sSQL = "SELECT * FROM usuario WHERE usuario='".$usuario."'";
		$result = mysql_db_query(DATABASE,$sSQL);
		
		while ($linea = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$done = true;			
			if ( $linea['usuario'] != "" && $linea['password'] == $pwd && $linea['estado'] == "A"){
				if ($linea['usuario'] == "auditor")
					$_SESSION['logged_norm'] = "true";
				else
					$_SESSION['logged_user'] = "true";
				$_SESSION['usuario'] = strtoupper($linea['usuario']);
				$_SESSION['idUsuario'] = $linea['id'];
				if ($_GET['destino'] != 10)
					header( 'refresh: 1; url=?s='.$_GET['destino'] ); 
				else
					header( 'refresh: 1; url=?s=0' ); 
				$men = "<img src='images/ajaxWait.gif' align='absmiddle'> Un momento por favor ...";
				break;
			} else if ( $linea['usuario'] != "" && $linea['password'] != $pwd && $linea['estado'] == "A"){
				$men = "Contrase&#241a equivocada. Intente de nuevo.";
				break;
			} else if ( $linea['usuario'] != "" && $linea['password'] == $pwd && $linea['estado'] == "I"){
				$men = "<b>SU CUENTA SE ENCUENTRA BLOQUEADA.<br>Comuniquese con su asesor comercial.</b>.";
			}
		}

		if ($done == false)
			$men = "&#201ste usuario no existe, intente de nuevo.";
	}
?>
		<div style="background: #292929; border: 0px solid #360000" align="right">
		<table border="0" cellpadding="2" cellspacing="3" summary="">
			<tr>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=0" alt ="" class="subMenu">&nbsp;EXTENSIONES&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=6" alt ="" class="subMenu">&nbsp;CONSOLA DE FAX&nbsp;</a></b></td>
                <td style="background: #1D1D1D" valign="center"><b><a href="?s=11" alt ="" class="subMenu">&nbsp;CALLCENTER&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=8" alt ="" class="subMenu">&nbsp;SISTEMA&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=1" alt ="" class="subMenu">&nbsp;REPORTES&nbsp;</a></b></td>
				<td style="background: #290505" valign="center"><b><a href="?s=7" alt ="" class="subMenu">&nbsp;OPCIONES DEL IP-PBX&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=5" alt ="" class="subMenu">&nbsp;SOPORTE&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=10" alt ="" class="subMenu">&nbsp;LOG IN&nbsp;</a></b></td>
				<td style="background: #1D1D1D" valign="center"><b><a href="?s=9" alt ="" class="subMenu">&nbsp;[X]&nbsp;</a></b></td>
			</tr>
		</table>
		</div>
		<table background="images/back.jpg" cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
		  <td align="center">		  
		<div align="center" style="background: url(images/bkg.jpg); border: 1px solid #9D9D59">
			<div id="content" style="margin: 5px 5px 5px 5px">
		<br /><br />
		<form name="logForm" action="?optc=logon&s=10&destino=<?php echo $_GET['s']; ?>" method="post">
		<table width="300" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #333333">		  
		  <tr>
			<td colspan="2" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#999999; background-color:#000033">				
				<strong>POR FAVOR DILIGENCIE EL FORMULARIO</strong>
			</td>
		  </tr>
		  <tr>
			<td colspan="2" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#B60000">				
				<strong><?php echo $men?></strong>
			</td>
		  </tr>
		  <tr>
			<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Usuario</td>
			<td><input name="usuario" type="text" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value=<?php echo $_GET['uurr'];?>></td>
		  </tr>
		  <tr>
			<td align="left" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px">Contrase&#241;a</td>
			<td><input name="pasw" type="password" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" value=<?php echo $_GET['ppww'];?>></td>
		  </tr>
		  <tr>
			<td colspan="2" align="center">
				<input name="" type="submit" value="Entrar" style="width:70px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px"/>
				<input name="" type="reset" value="Borrar" style="width:70px; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px"/>
			</td>
		  </tr>
		  
		</table>
		</form>	 
		<br><br><br>
		</div>
		</div>
		
		<?php 
			if ($_GET["post"] == true) {
				echo "<script language=\"JavaScript\">	\n		document.logForm.submit();		\n		</script>";
			}
		?>			
		</center>	
		</td>
	</td>
	</table>
	<?php 
	} ?>	
				
		<img src="images/down.jpg" alt="">
		
		</div>
	</div>
</div>
<br /><br />
</body>
</html>
