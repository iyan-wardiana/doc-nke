<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 28 Januari 2019
 * File Name	= M_transfer.php
 * Location		= -
*/

class M_transfer extends CI_Model
{	
	function count_all_tsf($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_asset_tsfh A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_asset_tsfh A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (ASTSF_CODE LIKE '%$key%' ESCAPE '!' OR ASTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ASTSF_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_tsf($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (ASTSF_CODE LIKE '%$key%' ESCAPE '!' OR ASTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ASTSF_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_item($PRJCODE, $JOBCODE) // GOOD
	{
		$sql	= "tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' AND AS_VOLM > 0";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE, $JOBCODE) // GOOD
	{
		$sql	= "SELECT AS_LASTPOS AS PRJCODE, AS_CODE AS ITM_CODE, AS_CODE_M AS ITM_CODE_H, AG_CODE AS ITM_GROUP, AST_CODE AS ITM_CATEG, 
						'' AS JOBCODEID, AS_NAME AS ITM_NAME, AS_DESC AS ITM_DESC, AS_TYPECODE AS ITM_UNIT, AS_VOLM AS ITM_VOLM, 
						AS_PRICE AS ITM_PRICE
					FROM tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' AND AS_VOLM > 0";
		return $this->db->query($sql);
	}
	
	function add($insASEXPH) // GOOD
	{
		$this->db->insert('tbl_asset_tsfh', $insASEXPH);
	}
	
	function get_ASTSF_by_number($ASTSF_NUM) // GOOD
	{
		$sql = "SELECT A.*, B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_asset_tsfh A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.ASTSF_NUM = '$ASTSF_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function update($ASTSF_NUM, $updASTSFH) // GOOD
	{
		$this->db->where('ASTSF_NUM', $ASTSF_NUM);
		$this->db->update('tbl_asset_tsfh', $updASTSFH);
	}
	
	function deleteDetail($ASTSF_NUM) // GOOD
	{
		$this->db->where('ASTSF_NUM', $ASTSF_NUM);
		$this->db->delete('tbl_asset_tsfd');
	}
	
	function count_all_tsfInb($PRJCODE, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ASTSF_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ASTSF_STAT IN (2,7)
						AND (ASTSF_CODE LIKE '%$key%' ESCAPE '!' OR ASTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ASTSF_NOTE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_tsfInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ASTSF_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_asset_tsfh A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND ASTSF_STAT IN (2,7)
						AND (ASTSF_CODE LIKE '%$key%' ESCAPE '!' OR ASTSF_DATE LIKE '%$key%' ESCAPE '!' 
						OR ASTSF_NOTE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function updateAST($ASTSF_NUM, $ITM_CODE, $PRJCODE, $PRJCODE_DEST, $ASTSF_VOLM) // GOOD
	{
		/*$sqlUpdITM1	= "UPDATE tbl_asset_list SET AS_VOLM = AS_VOLM + $ASTSF_VOLM
						WHERE AS_CODE = '$ITM_CODE' AND AS_LASTPOS = '$PRJCODE_DEST";*/
		$sqlUpdITM1	= "UPDATE tbl_asset_list SET AS_LASTPOS = '$PRJCODE_DEST'
						WHERE AS_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpdITM1);
		
		$sqlUpdITM2	= "UPDATE tbl_asset_list SET AS_VOLM = AS_VOLM - $ASTSF_VOLM
						WHERE AS_CODE = '$ITM_CODE' AND AS_LASTPOS = '$PRJCODE";
		//$this->db->query($sqlUpdITM2);
	}
}
?>