<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= M_bom.php
 * Location		= -
*/

class M_bom extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_bom_header A 
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_NAME LIKE '%$search%' ESCAPE '!' 
					OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.BOM_NUM, A.BOM_CODE, A.BOM_NAME, A.BOM_DESC, A.BOM_STAT, A.PRJCODE, A.CUST_CODE, A.CUST_DESC,
							A.BOM_CREATER, A.BOM_CREATED, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bom_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_NAME LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.BOM_NUM, A.BOM_CODE, A.BOM_NAME, A.BOM_DESC, A.BOM_STAT, A.PRJCODE, A.CUST_CODE, A.CUST_DESC,
							A.BOM_CREATER, A.BOM_CREATED, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bom_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_NAME LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.BOM_NUM, A.BOM_CODE, A.BOM_NAME, A.BOM_DESC, A.BOM_STAT, A.PRJCODE, A.CUST_CODE, A.CUST_DESC,
							A.BOM_CREATER, A.BOM_CREATED, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bom_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_NAME LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.BOM_NUM, A.BOM_CODE, A.BOM_NAME, A.BOM_DESC, A.BOM_STAT, A.PRJCODE, A.CUST_CODE, A.CUST_DESC,
							A.BOM_CREATER, A.BOM_CREATED, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_bom_header A 
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.BOM_CODE LIKE '%$search%' ESCAPE '!' OR A.BOM_NAME LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_BOM($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_bom_header A WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_bom_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (BOM_CODE LIKE '%$key%' ESCAPE '!' OR BOM_NAME LIKE '%$key%' ESCAPE '!' 
						OR BOM_DESC LIKE '%$key%' ESCAPE '!' OR CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_BOM($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.* FROM tbl_bom_header A WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "SELECT A.* FROM tbl_bom_header A
					WHERE A.PRJCODE = '$PRJCODE'
						AND (BOM_CODE LIKE '%$key%' ESCAPE '!' OR BOM_NAME LIKE '%$key%' ESCAPE '!' 
						OR BOM_DESC LIKE '%$key%' ESCAPE '!' OR CUST_DESC LIKE '%$key%' ESCAPE '!')";
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
	
	function count_all_itemFG($PRJCODE) // GOOD
	{
		$sql	= "tbl_item WHERE PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1) AND ISFG = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_itemFG($PRJCODE) // GOOD
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE IN (SELECT PRJCODE FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1)
					AND ISFG = 1";
		return $this->db->query($sql);
	}
	
	function count_all_item($PRJCODE) // GOOD
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISRM = 1 OR ISWIP = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE) // GOOD
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISRM = 1 OR ISWIP = 1";
		return $this->db->query($sql);
	}
	
	function add($AddBOM) // GOOD
	{
		$this->db->insert('tbl_bom_header', $AddBOM);
	}
	
	function get_bom_by_number($BOM_NUM) // GOOD
	{			
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_bom_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.BOM_NUM = '$BOM_NUM'";
		return $this->db->query($sql);
	}
	
	function updateBOM($BOM_NUM, $UpdBOM) // GOOD
	{
		$this->db->where('BOM_NUM', $BOM_NUM);
		$this->db->update('tbl_bom_header', $UpdBOM);
	}
	
	function deleteBOMD($BOM_NUM) // GOOD
	{
		$this->db->where('BOM_NUM', $BOM_NUM);
		$this->db->delete('tbl_bom_detail');
	}
	
	function deleteBOMSTDF($BOM_NUM) // GOOD
	{
		$this->db->where('BOM_NUM', $BOM_NUM);
		$this->db->delete('tbl_bom_stfdetail');
	}
	
	function count_all_CUST() // GOOD
	{
		$sql = "tbl_customer WHERE CUST_STAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST() // GOOD
	{
		$sql = "SELECT CUST_CODE, CUST_DESC, CUST_ADD1
				FROM tbl_customer WHERE CUST_STAT = '1'";
		return $this->db->query($sql);
	}
	
	function get_AllDataICOLLC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_item_collh A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICOLL_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICOLL_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICOLL_FG LIKE '%$search%' ESCAPE '!'
							OR A.ICOLL_REFNUM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataICOLLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_collh A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICOLL_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICOLL_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICOLL_FG LIKE '%$search%' ESCAPE '!'
							OR A.ICOLL_REFNUM LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_collh A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICOLL_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICOLL_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICOLL_FG LIKE '%$search%' ESCAPE '!'
							OR A.ICOLL_REFNUM LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_collh A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICOLL_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICOLL_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICOLL_FG LIKE '%$search%' ESCAPE '!'
							OR A.ICOLL_REFNUM LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_collh A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICOLL_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICOLL_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICOLL_FG LIKE '%$search%' ESCAPE '!'
							OR A.ICOLL_REFNUM LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_qrc($PRJCODE, $IR_CODE) // GOOD
	{
		$sql	= "tbl_qrc_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' AND B.ISRIB = 0
					WHERE A.PRJCODE = '$PRJCODE' AND A.QRC_STATU = '0'
					-- AND (IR_CODE LIKE '%$IR_CODE%' OR IR_CODE_REF LIKE '%$IR_CODE%')
					";
		return $this->db->count_all($sql);
	}
	
	function get_all_qrc($PRJCODE, $IR_CODE) // GOOD
	{
		$sql	= "SELECT DISTINCT A.* FROM tbl_qrc_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' AND B.ISRIB = 0
					WHERE A.PRJCODE = '$PRJCODE' AND A.QRC_STATU = 0
					-- AND (IR_CODE LIKE '%$IR_CODE%' OR IR_CODE_REF LIKE '%$IR_CODE%')
					";
		return $this->db->query($sql);
	}
	
	function count_all_qrcRIB($PRJCODE, $IR_CODE) // GOOD
	{
		/*$sql	= "tbl_qrc_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' AND B.ISRIB = 1
					WHERE A.PRJCODE = '$PRJCODE' AND A.QRC_STATU = '0'";*/
		$sql	= "tbl_item_cuth A
						INNER JOIN tbl_qrc_detail B ON A.ICUT_QRCN = B.QRC_NUM
							AND A.ITM_CODE = B.ITM_CODE
					WHERE A.PRJCODE = '$PRJCODE'
					-- AND B.IR_CODE = '$IR_CODE'
					";
		return $this->db->count_all($sql);
	}
	
	function get_all_qrcRIB($PRJCODE, $IR_CODE) // GOOD
	{
		/*$sql	= "SELECT DISTINCT A.* FROM tbl_qrc_detail A INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE' AND B.ISRIB = 1
					WHERE A.PRJCODE = '$PRJCODE' AND A.QRC_STATU = 0";*/

		// DARI HASIL POTONG RIB
		$sql	= "SELECT A.*, B.ITM_UNIT FROM tbl_item_cuth A
						INNER JOIN tbl_qrc_detail B ON A.ICUT_QRCN = B.QRC_NUM
							AND A.ITM_CODE = B.ITM_CODE
					WHERE A.PRJCODE = '$PRJCODE'
					-- AND B.IR_CODE = '$IR_CODE'
					";
		return $this->db->query($sql);
	}
	
	function addICOLL($AddICOLL) // GOOD
	{
		$this->db->insert('tbl_item_collh', $AddICOLL);
	}
	
	function get_icoll_by_number($ICOLL_NUM) // GOOD
	{			
		$sql = "SELECT A.*
				FROM tbl_item_collh A
				WHERE A.ICOLL_NUM = '$ICOLL_NUM'";
		return $this->db->query($sql);
	}
	
	function deleteIc0ll($ICOLL_NUM) // GOOD
	{
		$this->db->where('ICOLL_NUM', $ICOLL_NUM);
		$this->db->delete('tbl_item_colld');
	}
	
	function updICOLL($ICOLL_NUM, $updICOLL) // GOOD
	{
		$this->db->where('ICOLL_NUM', $ICOLL_NUM);
		$this->db->update('tbl_item_collh', $updICOLL);
	}
	
	function updQRCSTAT($QRC_NUM, $ICOLL_CODE, $JO_NUM, $JO_CODE) // GOOD
	{
		// UPDATE QRC_DETAIL
			$updQRC	= "UPDATE tbl_qrc_detail SET QRC_STATU = 1, GRP_CODE  = '$ICOLL_CODE',
						JO_CREATED = 1, JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE' WHERE QRC_NUM = '$QRC_NUM'";
			$this->db->query($updQRC);

		$SO_NUM		= '';
		$sqlSO 		= "SELECT SO_NUM FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
		$resSO		= $this->db->query($sqlSO)->result();
		foreach($resSO as $rowSO) :
			$SO_NUM = $rowSO->SO_NUM;		
		endforeach;

		// UPDATE
			$updGRP	= "UPDATE tbl_item_colld SET REF_NUM = '$SO_NUM', JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE' WHERE QRC_NUM = '$QRC_NUM'";
			$this->db->query($updGRP);
	}
	
	function get_AllDataICUTC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_item_cuth A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICUT_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICUT_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICUT_QRCN LIKE '%$search%' ESCAPE '!'
							OR A.ICUT_REFNUM LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataICUTL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_cuth A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICUT_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICUT_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICUT_QRCN LIKE '%$search%' ESCAPE '!'
							OR A.ICUT_REFNUM LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_cuth A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICUT_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICUT_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICUT_QRCN LIKE '%$search%' ESCAPE '!'
							OR A.ICUT_REFNUM LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_cuth A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICUT_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICUT_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICUT_QRCN LIKE '%$search%' ESCAPE '!'
							OR A.ICUT_REFNUM LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*,
							CONCAT(B.First_Name, ' ', B.Last_Name) AS CompName
						FROM tbl_item_cuth A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.ICUT_CREATER
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.ICUT_CODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE LIKE '%$search%' ESCAPE '!' 
							OR A.CUST_DESC LIKE '%$search%' ESCAPE '!' OR A.ICUT_QRCN LIKE '%$search%' ESCAPE '!'
							OR A.ICUT_REFNUM LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataISJ($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ir_header A
						INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
					WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'
						AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLDESC LIKE '%$search%' ESCAPE '!' 
						OR A.IR_REFER2 LIKE '%$search%' ESCAPE '!' OR B.WH_NAME LIKE '%$search%' ESCAPE '!'
						OR A.IR_LOC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataISJL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT IR_CODE, IR_DATE, SPLDESC, IR_REFER2, B.WH_NAME, IR_LOC
						FROM tbl_ir_header A
							INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
						WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR A.IR_REFER2 LIKE '%$search%' ESCAPE '!' OR B.WH_NAME LIKE '%$search%' ESCAPE '!'
							OR A.IR_LOC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT IR_CODE, IR_DATE, SPLDESC, IR_REFER2, B.WH_NAME, IR_LOC
						FROM tbl_ir_header A
							INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
						WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR A.IR_REFER2 LIKE '%$search%' ESCAPE '!' OR B.WH_NAME LIKE '%$search%' ESCAPE '!'
							OR A.IR_LOC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT IR_CODE, IR_DATE, SPLDESC, IR_REFER2, B.WH_NAME, IR_LOC
						FROM tbl_ir_header A
							INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
						WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR A.IR_REFER2 LIKE '%$search%' ESCAPE '!' OR B.WH_NAME LIKE '%$search%' ESCAPE '!'
							OR A.IR_LOC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT IR_CODE, IR_DATE, SPLDESC, IR_REFER2, B.WH_NAME, IR_LOC
						FROM tbl_ir_header A
							INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
						WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLDESC LIKE '%$search%' ESCAPE '!' 
							OR A.IR_REFER2 LIKE '%$search%' ESCAPE '!' OR B.WH_NAME LIKE '%$search%' ESCAPE '!'
							OR A.IR_LOC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_itemSJ($PRJCODE) // GOOD
	{
		$sql	= "";
		return $this->db->count_all($sql);
	}
	
	function get_all_itemSJ($PRJCODE) // GOOD
	{
		$sql	= "SELECT IR_CODE, IR_DATE, SPLDESC, IR_REFER2, B.WH_NAME, IR_LOC FROM tbl_ir_header A
						INNER JOIN tbl_warehouse B ON A.WH_CODE = B.WH_CODE
					WHERE IR_STAT IN (3,6) AND A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_itemRIB($PRJCODE, $CUST_SJNO) // GOOD
	{
		$sql	= "tbl_qrc_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = '$PRJCODE'
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM AND C.IR_REFER2 = '$CUST_SJNO'
					WHERE A.ITM_CODE IN (SELECT ITM_CODE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISRIB = 1 AND ISNULL(A.GRP_CODE))
						AND ISNULL(A.IR_CODE_REF)";
		return $this->db->count_all($sql);
	}
	
	function get_all_itemRIB($PRJCODE, $CUST_SJNO) // GOOD
	{
		$sql	= "SELECT A.*, B.ITM_NAME FROM tbl_qrc_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND B.PRJCODE = '$PRJCODE'
						INNER JOIN tbl_ir_header C ON A.IR_NUM = C.IR_NUM AND C.IR_REFER2 = '$CUST_SJNO'
					WHERE A.ITM_CODE IN (SELECT ITM_CODE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISRIB = 1 AND ISNULL(A.GRP_CODE))
						AND ISNULL(A.IR_CODE_REF)";
		return $this->db->query($sql);
	}
	
	function addCUT($AddCUT) // GOOD
	{
		$this->db->insert('tbl_item_cuth', $AddCUT);
	}
	
	function get_icut_by_number($ICOLL_NUM) // GOOD
	{			
		$sql = "SELECT A.*
				FROM tbl_item_cuth A
				WHERE A.ICUT_NUM = '$ICOLL_NUM'";
		return $this->db->query($sql);
	}
	
	function updICUT($ICUT_NUM, $updICUT) // GOOD
	{
		$this->db->where('ICUT_NUM', $ICUT_NUM);
		$this->db->update('tbl_item_cuth', $updICUT);
	}
}
?>