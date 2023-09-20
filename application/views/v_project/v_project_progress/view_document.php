<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 05 Agustus 2015
 * File Name	= v_document_log.php
*/
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $FileUpName . '"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');
	readfile($myPath);
?>