<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 03 Juli 2018
 * File Name	= M_down_payment.php
 * Location		= -
*/
?>
<?php
class M_down_payment extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}

	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE PRJCODE = '$PRJCODE'
					AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE'
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataWOC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_wo_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND A.WO_DPPER > 0 
						AND A.WO_DPSTAT = 0 AND WO_STAT = 3 AND A.TTK_CREATED = 1
						AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
						OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataWOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.WO_DPPER > 0 
							AND A.WO_DPSTAT = 0 AND WO_STAT = 3 AND A.TTK_CREATED = 1
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.WO_DPPER > 0 
							AND A.WO_DPSTAT = 0 AND WO_STAT = 3 AND A.TTK_CREATED = 1
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.WO_DPPER > 0 
							AND A.WO_DPSTAT = 0 AND WO_STAT = 3 AND A.TTK_CREATED = 1
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.WO_DPPER > 0 
							AND A.WO_DPSTAT = 0 AND WO_STAT = 3 AND A.TTK_CREATED = 1
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataPOC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_po_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 1 AND PO_STAT = 3
						AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
						OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
						OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPOL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 1 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 1 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 1 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 1 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE PRJCODE = '$PRJCODE' AND A.DP_STAT IN (2,7)
					AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.DP_STAT IN (2,7)
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.DP_STAT IN (2,7)
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.DP_STAT IN (2,7)
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.DP_STAT IN (2,7)
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_dp($PRJCODE) // G
	{
		$sql = "tbl_dp_header WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_dp($PRJCODE) // G
	{
		$sql = "SELECT * FROM tbl_dp_header WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}

	//add by iyan created: 190708_1129
	function get_dpreport($PRJCODE, $SPLCODE)
	{
		if($SPLCODE == 'ALL'){
			return $this->db->get_where('tbl_dp_header', array('PRJCODE' => $PRJCODE));
		}else{
			return $this->db->get_where('tbl_dp_header', array('PRJCODE' => $PRJCODE, 'SPLCODE' => $SPLCODE));
		}
	}

	function sum_dp($PRJCODE, $SPLCODE)
	{
		if($SPLCODE == 'ALL'){
			return $this->db->select_sum('DP_AMOUNT')->get_where('tbl_dp_header', array('PRJCODE' => $PRJCODE));
		}else{
			return $this->db->select_sum('DP_AMOUNT')->get_where('tbl_dp_header', array('PRJCODE' => $PRJCODE, 'SPLCODE' => $SPLCODE));
		}
	}

	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsVend() // G
	{
		$sql = "tbl_supplier WHERE SPLSTAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewvendor() // G
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier WHERE SPLSTAT = '1'
				ORDER BY SPLDESC";
		return $this->db->query($sql);
	}
	
	function add($dpheader) // G
	{
		$this->db->insert('tbl_dp_header', $dpheader);
	}
	
	function get_dp_by_code($DP_NUM) // G
	{
		$sql = "SELECT * FROM tbl_dp_header WHERE DP_NUM = '$DP_NUM'";
		return $this->db->query($sql);
	}
	
	function update($DP_NUM, $dpheader) // G
	{
		$this->db->where('DP_NUM', $DP_NUM);
		$this->db->update('tbl_dp_header', $dpheader);
	}
	
	function count_all_dpinb($DefEmp_ID) // G
	{
		$sql	= "tbl_dp_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND DP_STAT IN (2,7)"; // Only Confirm Stat (2) AND
		return $this->db->count_all($sql);
	}
	
	function get_all_dpinb($DefEmp_ID) // G
	{
		$sql	= "SELECT * FROM tbl_dp_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND DP_STAT IN (2,7)"; // Only Confirm Stat (2) AND
		return $this->db->query($sql);
	}
	
	function count_all_WO($PRJCODE) // G
	{
		$sql	= "tbl_wo_header WHERE PRJCODE = '$PRJCODE' AND WO_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function view_all_WO($PRJCODE) // G
	{
		$sql	= "SELECT * FROM tbl_wo_header WHERE PRJCODE = '$PRJCODE' AND WO_STAT = 3";
		return $this->db->query($sql);
	}
	
	function count_all_PO($PRJCODE) // G
	{
		$sql	= "tbl_po_header A
						INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function view_all_PO($PRJCODE) // G
	{
		$sql	= "SELECT A.PO_NUM, A.PO_CODE, A.PRJCODE, A.PO_DATE, A.PO_DUED, A.PO_NOTES, A.PO_NOTES1, A.JOBCODE, B.SPLDESC 
					FROM tbl_po_header A
						INNER JOIN tbl_supplier B ON B.SPLCODE = A.SPLCODE 
					WHERE A.PRJCODE = '$PRJCODE' AND A.PO_STAT = 3";
		return $this->db->query($sql);
	}
	
	function get_AllDataCTTKTAX($PRJCODE, $TTK_NUM, $search) // GOOD
	{
		$sql = "tbl_ttk_tax A
				WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_NUM = '$TTK_NUM'
					AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
					OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataLTTKTAX($PRJCODE, $TTK_NUM, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_NUM = '$TTK_NUM'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_NUM = '$TTK_NUM'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_NUM = '$TTK_NUM'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_NUM = '$TTK_NUM'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>