<?php 
class system_encrypt
{
	function sys_decsrypt($srvURL, $appName, $app_notes, $TS_DESC)
	{
		$privatKey 	= "1stweb_dianhsystem";
		$collKey	= "$srvURL$appName$privatKey";
		$encKey		= md5($collKey);
		if($encKey == $app_notes) $logY = 1;
		else $logY = 0;
		
		if($logY == 1)
		{
			if($TS_DESC == $encKey) return $loginYes = $encKey;
			else return $loginYes = $encKey;
		}
		else 
		{
			return $loginYes = $encKey;
		}
	}
	
	function sys_decsryptxx($srvURL, $appName, $app_notes, $TS_DESC)
	{
		$privatKey 	= "1stweb_dianhsystem";
		$collKey	= "$srvURL$appName$privatKey";
		$encKey		= md5($collKey);
		if($encKey == $app_notes) $logY = 1;
		else $logY = 0;
		
		if($logY == 1)
		{
			if($TS_DESC == $encKey) return $loginYes = 1;
			else return $loginYes = 0;
		}
		else 
		{
			return $loginYes = 0;
		}
	}
	
	function sys_decsryptmail()
	{
		return $privatKey 	= "diannhermanto88@gmail.com";
	}
}
?>