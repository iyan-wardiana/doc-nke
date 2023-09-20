<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= M_itemcal.php
 * Location		= -
*/

class M_itemcal extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ccal_header A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.CCAL_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
					OR A.CCAL_NAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_ccal_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.CCAL_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.CCAL_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_ccal_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.CCAL_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.CCAL_NAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_ccal_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.CCAL_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.CCAL_NAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_ccal_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.CCAL_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.CCAL_NAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_CCAL($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_ccal_header A
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_ccal_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (CCAL_CODE LIKE '%$key%' ESCAPE '!' OR BOM_CODE LIKE '%$key%' ESCAPE '!' 
						OR CUST_DESC LIKE '%$key%' ESCAPE '!' OR CCAL_NAME LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_CCAL($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.* FROM tbl_ccal_header A
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.* FROM tbl_ccal_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (CCAL_CODE LIKE '%$key%' ESCAPE '!' OR BOM_CODE LIKE '%$key%' ESCAPE '!' 
						OR CUST_DESC LIKE '%$key%' ESCAPE '!' OR CCAL_NAME LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}	
		return $this->db->query($sql);
	}
	
	function count_all_CUST() // GOOD
	{
		$sql = "tbl_customer A WHERE A.CUST_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST() // GOOD
	{
		$sql = "SELECT DISTINCT A.CUST_CODE, A.CUST_DESC
				FROM tbl_customer A WHERE A.CUST_STAT = '1'";
		return $this->db->query($sql);
	}
	
	function count_all_CUSTBOM() // GOOD
	{
		$sql = "tbl_bom_header A
				INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.BOM_STAT = '3'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUSTBOM() // GOOD
	{
		$sql = "SELECT DISTINCT B.CUST_CODE, B.CUST_DESC
				FROM tbl_bom_header A
				INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE WHERE A.BOM_STAT = '3'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_item($PRJCODE) // GOOD
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND (ISRM = 1 OR ISWIP = 1 OR ISFG = 1)";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE) // GOOD
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND (ISRM = 1 OR ISWIP = 1 OR ISFG = 1)";
		return $this->db->query($sql);
	}
	
	function count_all_othc($PRJCODE) // GOOD
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISCOST = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_othc($PRJCODE) // GOOD
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISCOST = 1";
		return $this->db->query($sql);
	}
	
	function add($AddCCAL) // GOOD
	{
		$this->db->insert('tbl_ccal_header', $AddCCAL);
	}
	
	function get_ccal_by_number($CCAL_NUM) // GOOD
	{			
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_ccal_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.CCAL_NUM = '$CCAL_NUM'";
		return $this->db->query($sql);
	}
	
	function deleteCCAL($CCAL_NUM) // GOOD
	{
		$this->db->where('CCAL_NUM', $CCAL_NUM);
		$this->db->delete('tbl_ccal_detail');
	}
	
	function updateCCAL($CCAL_NUM, $UpdCCAL) // GOOD
	{
		$this->db->where('CCAL_NUM', $CCAL_NUM);
		$this->db->update('tbl_ccal_header', $UpdCCAL);
	}
}
?>