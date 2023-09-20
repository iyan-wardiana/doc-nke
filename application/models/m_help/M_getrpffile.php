<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 24 Februari 2017
 * File Name	= M_getrpffile.php
 * Location		= -
*/
?>
<?php
class M_getrpffile extends CI_Model
{
	function hiddenFile($file) // U
	{
		$proj_CodeX		= '';
		$sqlGetRTFNmC 	= "SELECT PRJCODE, RTF_MOD FROM tbl_rtflistA WHERE RTF_NAME = '$file'";
		$resGetRTFNmC	= $this->db->query($sqlGetRTFNmC)->result();
		foreach($resGetRTFNmC as $rowGetRTFNmC) :
			$proj_CodeX	= $rowGetRTFNmC->PRJCODE;
			$RTF_MOD 	= $rowGetRTFNmC->RTF_MOD;
		endforeach;
		$RTF_DEL		= date("Y-m-d-H:i:s");
		$sqlINSFile 	= "INSERT INTO tbl_rtflistB (PRJCODE, RTF_NAME, RTF_SHOW, RTF_MOD, RTF_DEL) VALUES ('$proj_CodeX', '$file', 0, '$RTF_MOD', '$RTF_DEL')";
		$this->db->query($sqlINSFile);
		
		$sqlRTFUpNm		= "UPDATE tbl_rtflistA SET RTF_SHOW = 0, RTF_DEL = '$RTF_DEL' WHERE PRJCODE = '$proj_CodeX' AND RTF_NAME = '$file'";
		$resRTFUpNm		= $this->db->query($sqlRTFUpNm);
	}
}
?>