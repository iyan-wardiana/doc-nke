<?php 
class Crypt180c1c
{
	function sys_decsrypt($srvURL, $appName, $app_notes, $TS_DESC)
	{
		if($srvURL == '192.168.2.163')
			$srvURL	= '::1';
			
		$privatKey 	= "SDBP-PLUS-NKES.22-0308";
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
		if($srvURL == '192.168.2.163')
			$srvURL	= '::1';
			
		$privatKey 	= "SDBP-PLUS-NKES.22-0308";
		$collKey	= "$srvURL$appName$privatKey";
		$encKey		= md5($collKey);
		if($encKey == $app_notes) $logY = 1;
		else $logY = 0;
		
		//echo "ge/oBNFNAn95lAh9vZR3UFZsvujwYIA0pCsMgO6Xvmsm8A5yG61wHXfw0ZPOj6TpzN3RoWIt+RueQSphF2rG3Sx0R4UXX123-1x-$collKey-1x-2x-$encKey == $app_notes-2x-ge/oBNFNAn95lAh9vZR3UFZsvujwYIA0pCsMgO6Xvmsm8A5yG61wHXfw0ZPOj6TpzN3RoWIt+RueQSphF2rG3Sx0R4Uge/oBNFNAn95lAh9vZR3UFZsvujwYIA0pCsMgO6Xvmsm8A5yG61wHXfw0ZPOj6TpzN3RoWIt+RueQSphF2rG3Sx0R4Uge/oBNFNAn95lAh9vZR3UFZsvujwYIA0pCsMgO6Xvmsm8A5yG61wHXfw0ZPOj6TpzN3RoWIt+RueQSphF2rG3Sx0R4U";
		//return false;
		
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