<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 20 November 2017
 * File Name	= m_coa.php
 * Location		= -
*/

class M_coa extends CI_Model
{
	function getAppName() // G
	{
		$this->db->select('app_id, app_name');
		return $this->db->get('tappname');
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_coa_uphist A
				WHERE A.COAH_PRJCODE = '$PRJCODE'
					AND (A.COAH_DESC LIKE '%$search%' ESCAPE '!' OR A.COAH_FN LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_coa_uphist A
						WHERE A.COAH_PRJCODE = '$PRJCODE'
							AND (A.COAH_DESC LIKE '%$search%' ESCAPE '!' OR A.COAH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_coa_uphist A
						WHERE A.COAH_PRJCODE = '$PRJCODE'
							AND (A.COAH_DESC LIKE '%$search%' ESCAPE '!' OR A.COAH_FN LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_coa_uphist A
						WHERE A.COAH_PRJCODE = '$PRJCODE'
							AND (A.COAH_DESC LIKE '%$search%' ESCAPE '!' OR A.COAH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_coa_uphist A
						WHERE A.COAH_PRJCODE = '$PRJCODE'
							AND (A.COAH_DESC LIKE '%$search%' ESCAPE '!' OR A.COAH_FN LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_all_ofCOADef($collPRJ, $LinkAcc) // G
	{
		if($collPRJ == 'AllPRJ')
		{
			$sql		= "SELECT * FROM tbl_chartaccount WHERE Account_Category = $LinkAcc AND isHO != 2
							ORDER BY Account_Category, Account_Number ASC";
		}
		else
		{
			/*$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category = 1  AND isHO != 2
							ORDER BY Account_Category, Account_Number ASC A";*/
			/*$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category = 1
							ORDER BY Account_Category, Account_Number ASC";*/
			$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category = 1
							ORDER BY ORD_ID, Account_Category, Account_Number ASC";
		}
			
		return $this->db->query($sql);
	}
	
	function get_all_ofCOA($collPRJ, $LinkAcc) // G
	{
		if($LinkAcc == 9)
		{
			if($collPRJ == 'AllPRJ')
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE Account_Category IN (9,10) AND isHO != 2
								ORDER BY Account_Category, Account_Number ASC";
			}
			else
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category IN (9,10)
								ORDER BY Account_Category, Account_Number ASC";
			}
		}
		else
		{
			if($collPRJ == 'AllPRJ')
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE Account_Category IN (9,10) AND isHO != 2
								ORDER BY Account_Category, Account_Number ASC";
			}
			else
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category = $LinkAcc
								ORDER BY Account_Category, Account_Number ASC";
			}
		}
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($coaIns) // G
	{
		$this->db->insert('tbl_chartaccount', $coaIns);
	}
	
	function deleteCOADet($PRJCODE, $Account_Number)
	{
		$this->db->where('Acc_Number', $Account_Number);
		$this->db->where('PRJCODE1', $PRJCODE);
		$this->db->delete('tbl_coadetail');
	}
	
	function addCOADET($coaDet) // G
	{
		$this->db->insert('tbl_coadetail', $coaDet);
	}
	
	function get_coa_by_code($Acc_ID, $PRJCODE) // G
	{
		$sql	= "SELECT * FROM tbl_chartaccount
					WHERE Acc_ID = '$Acc_ID' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function add_importcoa($COAHist) // G
	{
		$this->db->insert('tbl_coa_uphist', $COAHist);
	}
	
	function globalSetting() // G
	{
		$this->db->select('Display_Rows,decFormat');
		return $this->db->get('tglobalsetting');
	}
	
	function update($Acc_ID, $coaUpd, $PRJCODE) // G
	{
		$this->db->where('Acc_ID', $Acc_ID);
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update('tbl_chartaccount', $coaUpd);
	}
	
	function updateNEW($Acc_ID, $coaUpd, $PRJCODE) // G
	{
		$this->db->where('Acc_ID', $Acc_ID);
		$this->db->update('tbl_chartaccount', $coaUpd);
	}
	
	function updateStat() // 
	{
		$sql = "UPDATE tbl_coa_uphist SET COAH_STAT = 0";
		$this->db->query($sql);
	}
	
	function count_acc_num($ACC_NUM) // OK
	{
		$sql	= "tbl_chartaccount WHERE Account_Number = '$ACC_NUM'";
		return $this->db->count_all($sql);
	}
}
?>