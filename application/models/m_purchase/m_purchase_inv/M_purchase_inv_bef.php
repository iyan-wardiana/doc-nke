<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 November 2017
 * File Name	= M_purchase_inv.php
 * Location		= -
 * Notes		= 1. Apabila akan mengembalikan atau membatalkan PI dari IR/LPM tertentu, maka harus mengupdate tabel berikut
 					 a. Update status di header PI menjadi Reject/Canceled
					 b. Update status "isCanceled" di tabel tbl_journalheader
					 c. Update status "INVSTAT" di tabel tbl_op_header
					 d. Update status "INVSTAT" di tabel sd_lpm_header
*/

class M_purchase_inv extends CI_Model
{	
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function count_all_pinv($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.INV_NUM LIKE '%$key%' ESCAPE '!' OR A.INV_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.INV_DATE LIKE '%$key%' ESCAPE '!' OR A.INV_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_pinv($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.INV_ID, A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_DATE, A.INV_DUEDATE, A.PO_NUM, A.IR_NUM, 
						A.INV_AMOUNT, A.INV_TERM, A.INV_CATEG,
						A.SPLCODE, A.INV_STAT, A.INV_PAYSTAT, A.isVoid, A.VOID_REASON
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.INV_ID, A.INV_NUM, A.INV_CODE, A.INV_TYPE, A.INV_DATE, A.INV_DUEDATE, A.PO_NUM, A.IR_NUM, 
						A.INV_AMOUNT, A.INV_TERM, A.INV_CATEG,
						A.SPLCODE, A.INV_STAT, A.INV_PAYSTAT, A.isVoid, A.VOID_REASON
					FROM tbl_pinv_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.INV_NUM LIKE '%$key%' ESCAPE '!' OR A.INV_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.INV_DATE LIKE '%$key%' ESCAPE '!' OR A.INV_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_pinv_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
					OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_pinv_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.INV_NUM LIKE '%$search%' ESCAPE '!' OR A.INV_CODE LIKE '%$search%' ESCAPE '!' 
							OR A.INV_DATE LIKE '%$search%' ESCAPE '!' OR A.INV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataTTKC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ttk_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataTTKL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataCTTK($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql = "tbl_ttk_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.INV_CREATED = 0
					AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataLTTK($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.INV_CREATED = 0
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.INV_CREATED = 0
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.INV_CREATED = 0
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ttk_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.INV_CREATED = 0
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTK_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.TTK_NOTES LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataCTTKTAX($PRJCODE, $SPLCODE, $collTTK, $search) // GOOD
	{
		$sql = "tbl_ttk_tax A
				WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_NUM IN ('$collTTK')
					AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
					OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataLTTKTAX($PRJCODE, $SPLCODE, $collTTK, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_NUM IN ('$collTTK')
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_NUM IN ('$collTTK')
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
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_NUM IN ('$collTTK')
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_ttk_tax A
						WHERE A.PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_NUM IN ('$collTTK')
							AND (A.TTK_CODE LIKE '%$search%' ESCAPE '!' OR A.TTKT_TAXNO LIKE '%$search%' ESCAPE '!' 
							OR A.TTKT_TAXAMOUNT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_allVend($PRJCODE, $SPLCODE) // OK - TTK from IR
	{
		$sql	= "tbl_ir_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.APPROVE = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.TTK_CREATED = '0' OR A.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_allVend($PRJCODE, $SPLCODE) // OK - TTK from IR
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ir_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.APPROVE = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.TTK_CREATED = '0' OR A.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function count_allVendDir() // OK - TTK Direct
	{
		$sql	= "tbl_supplier WHERE SPLSTAT = '1'";
		return $this->db->count_all($sql);
	}
	
	function view_allvendDir() // OK - TTK Direct
	{
		$sql	= "SELECT DISTINCT SPLCODE, SPLDESC, SPLADD1 FROM tbl_supplier WHERE SPLSTAT = '1'";
		return $this->db->query($sql);
	}
	
	function count_all_vend($PRJCODE) // OK - INVOICING from TTK
	{
		/*$sql	= "tbl_ir_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.APPROVE = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.INVSTAT NOT IN ('FI')";*/
		/*$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.INV_CREATED = '0'
						AND A.TTK_CATEG != 'OTH'";*/
		$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.INV_CREATED = '0'";
		return $this->db->count_all($sql);
	}
	
	function view_all_vend($PRJCODE) // OK - INVOICING from TTK
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.INV_CREATED = '0'";
		return $this->db->query($sql);
	}
	
	function getSupplier($TTK_NUMX, $PRJCODE) // OK
	{
		$sql	= "SELECT DISTINCT A.SPLCODE
					FROM tbl_ttk_header A
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.TTK_NUM = '$TTK_NUMX' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function count_all_vendDir($PRJCODE, $SPLCODE) // OK - INVOICING Direct
	{
		$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_CATEG = 'OTH' AND A.TTK_STAT = '3'
						AND A.INV_CREATED = '0' OR A.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_vendDir($PRJCODE, $SPLCODE) // OK - INVOICING Direct
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_CATEG = 'OTH' AND A.TTK_STAT = '3'
						AND A.INV_CREATED = '0' OR A.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_vendUP($SPLCODE) // OK
	{
		$sql	= "tbl_ir_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.APPROVE = 3
						AND A.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_vendUP($SPLCODE) // OK
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ir_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.APPROVE = 3
						AND A.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_vendUPOPN($SPLCODE, $PRJCODE) // OK
	{
		$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.SPLCODE = '$SPLCODE'
						AND A.TTK_CATEG != 'OTH'";
		return $this->db->count_all($sql);
	}
	
	function view_all_vendUPOPN($SPLCODE, $PRJCODE) // OK
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.SPLCODE = '$SPLCODE'
						AND A.TTK_CATEG != 'OTH'";
		return $this->db->query($sql);
	}
	
	function count_all_vendUPOTH($SPLCODE, $PRJCODE) // OK
	{
		$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.SPLCODE = '$SPLCODE'
						AND A.TTK_CATEG = 'OTH'";
		return $this->db->count_all($sql);
	}
	
	function view_all_vendUPOTH($SPLCODE, $PRJCODE) // OK
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.TTK_STAT = 3
						AND A.PRJCODE = '$PRJCODE'
						AND A.SPLCODE = '$SPLCODE'
						AND A.TTK_CATEG = 'OTH'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_IR($SPLCODE, $PRJCODE) // OK
	{
		$sql = "tbl_ir_header A
					LEFT JOIN tbl_supplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.IR_STAT = 3
					AND A.SPLCODE = '$SPLCODE'
					AND A.INVSTAT NOT IN ('FI')
					AND A.PRJCODE  = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function viewAllIR($SPLCODE, $PRJCODE) // OK
	{
		$sql = "SELECT DISTINCT A.IR_NUM, A.IR_DATE, A.IR_DUEDATE, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, A.IR_NOTE, A.IR_NOTE2,
					B.SPLDESC
				FROM tbl_ir_header A
					LEFT JOIN	tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.IR_STAT = 3
					AND A.SPLCODE = '$SPLCODE'
					AND A.INVSTAT NOT IN ('FI')
					AND A.PRJCODE  = '$PRJCODE'
				ORDER BY B.SPLDESC ASC";
		return $this->db->query($sql);
	}
	
	function count_allTTK($SPLCODE, $PRJCODE) // OK
	{
		/*$sql	= "tbl_ttk_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_CATEG != 'OTH' AND A.TTK_STAT = '3' AND A.SPLCODE = '$SPLCODE'
						AND INV_CREATED = 0";
		return $this->db->count_all($sql);*/
		$sql	= "tbl_ttk_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_STAT = '3' AND A.SPLCODE = '$SPLCODE'
						AND INV_CREATED = 0";
		return $this->db->count_all($sql);
	}
	
	function view_allTTK($SPLCODE, $PRJCODE) // OK
	{
		$sql 	= "SELECT A.*, B.SPLDESC
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_STAT = '3' AND A.SPLCODE = '$SPLCODE'
						AND INV_CREATED = 0";
		return $this->db->query($sql);
	}
	
	function add($insertINV) // OK
	{
		$this->db->insert('tbl_pinv_header', $insertINV);
	}
	
	function updatePO_RR($INV_NUM, $parameters) // OK
	{
		$INV_STAT 	= $parameters['INV_STAT'];
		$PO_NUM 	= $parameters['PO_NUM'];
		$IR_NUM 	= $parameters['IR_NUM'];
		$PRJCODE 	= $parameters['PRJCODE'];
		
		// Cari total Amount Penerimaan di IR
		/*$IR_AMOUNT	= 0;
		$sqlA 		= "SELECT IR_AMOUNT
						FROM tbl_ir_header
						WHERE IR_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		$ressqlA 	= $this->db->query($sqlA)->result();
		foreach($ressqlA as $rowA) :
			$IR_AMOUNT 	= $rowA->IR_AMOUNT;
		endforeach;*/
		
		// Cari total Amount Penerimaan di TTK
		$TTK_AMOUNT		= 0;
		$TTK_AMOUNT_PPN	= 0;
		$sqlA 		= "SELECT TTK_AMOUNT, TTK_AMOUNT_PPN
						FROM tbl_ttk_header
						WHERE TTK_NUM = '$IR_NUM' AND PRJCODE = '$PRJCODE'";
		$ressqlA 	= $this->db->query($sqlA)->result();
		foreach($ressqlA as $rowA) :
			$TTK_AMOUNT 	= $rowA->TTK_AMOUNT;
			$TTK_AMOUNT_PPN	= $rowA->TTK_AMOUNT_PPN;
		endforeach;
		$TOT_TTK	= $TTK_AMOUNT + $TTK_AMOUNT_PPN;
		
		// Cari total Amount Invoice
		$INV_AMOUNT		= 0;
		$INV_AMOUNT_BASE= 0;
		$INV_LISTTAXVAL	= 0;
		$sqlB 		= "SELECT INV_AMOUNT, INV_AMOUNT_BASE, INV_LISTTAXVAL
						FROM tbl_pinv_header
						WHERE INV_NUM = '$INV_NUM' AND PRJCODE = '$PRJCODE' AND ISVOID = 0";
		$ressqlB 	= $this->db->query($sqlB)->result();
		foreach($ressqlB as $rowB) :
			$INV_AMOUNT 		= $rowB->INV_AMOUNT;
			$INV_AMOUNT_BASE 	= $rowB->INV_AMOUNT_BASE;
			$INV_LISTTAXVAL 	= $rowB->INV_LISTTAXVAL;
		endforeach;
		$TOT_INV	= $INV_AMOUNT + $INV_LISTTAXVAL;
		
		$sql1		= "UPDATE tbl_po_header SET PO_INVSTAT = 1 WHERE PO_NUM = '$PO_NUM'";
		$this->db->query($sql1);
		
		if($TOT_INV == 0)
		{
			//$sql2		= "UPDATE tbl_ir_header SET INVSTAT = 'NI' WHERE IR_NUM = '$IR_NUM'";
			$sql2		= "UPDATE tbl_ttk_header SET INV_STAT = 'NI' WHERE TTK_NUM = '$IR_NUM'";
			$this->db->query($sql2);				
		}
		elseif($TOT_INV >= $TOT_TTK)
		{
			//$sql2		= "UPDATE tbl_ir_header SET INVSTAT = 'FI' WHERE IR_NUM = '$IR_NUM'";
			$sql2		= "UPDATE tbl_ttk_header SET INV_STAT = 'FI', INV_CREATED = 1 WHERE TTK_NUM = '$IR_NUM'";
			$this->db->query($sql2);
		}
		elseif($TOT_INV < $TOT_TTK)
		{
			//$sql2		= "UPDATE tbl_ir_header SET INVSTAT = 'HI' WHERE IR_NUM = '$IR_NUM'";
			$sql2		= "UPDATE tbl_ttk_header SET INV_STAT = 'HI', INV_CREATED = 1 WHERE TTK_NUM = '$IR_NUM'";
			$this->db->query($sql2);
		}
	}
	
	function get_INV_by_number($INV_NUM) // OK
	{
		$sql		= "SELECT * FROM tbl_pinv_header WHERE INV_NUM = '$INV_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateINV($INV_NUM, $updINV)
	{
		$this->db->where('INV_NUM', $INV_NUM);
		$this->db->update('tbl_pinv_header', $updINV);
	}
	
	function deleteINVDet($INV_NUM)
	{
		$this->db->where('INV_NUM', $INV_NUM);
		$this->db->delete('tbl_pinv_detail');
	}
	
	function viewvendorCOEdit($DefProj_Code) // USE
	{
		$sql	= "tbl_lpm_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.APPROVE IN (3,4)
						AND A.PRJCODE = '$DefProj_Code'";
		return $this->db->count_all($sql);
	}
	
	function viewvendorEdit($DefProj_Code) // USE
	{
		$sql	= "SELECT DISTINCT A.SPLCODE, B.SPLDESC, B.SPLADD1
					FROM tbl_lpm_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.APPROVE IN (3,4)
						AND A.PRJCODE = '$DefProj_Code'";
		return $this->db->query($sql);
	}
	
	// ---------------- START : Pembuatan Journal Header ----------------
	// Create on 20 Januari 2016. by. : Dian Hermanto
	function createJournalH($INV_NUM, $parameters)	// USE
	{
		$INV_NUM 		= $parameters['INV_NUM'];
		$RRSource 			= $parameters['RRSource'];
		$Reference_Number	= $parameters['Reference_Number'];
		$proj_Code 			= $parameters['proj_Code'];
		$Transaction_Type 	= $parameters['Transaction_Type'];
		$GEJDate 			= $parameters['GEJDate'];
		$WHCODE 			= $parameters['wh_id'];
		
		// Save Journal Header
		$sqlGEJH = "INSERT INTO tbl_journalheader (JournalH_Code, JournalH_Date, Source, LastUpdate, KursAmount_tobase, Reference_Number, Reference_Type, proj_Code, WHCODE)
					VALUES ('$INV_NUM', '$GEJDate', '$RRSource', '$GEJDate', 1, '$Reference_Number', '$Transaction_Type', '$proj_Code', '$WHCODE')";
		$this->db->query($sqlGEJH);
	}
	// ---------------- END : Pembuatan Journal Header ----------------
	
	// ---------------- START : Pembuatan Journal Detail ----------------
	// Create on 20 Januari 2016. by. : Dian Hermanto
	function addJourDet($parameters)	// USE
	{
		$this->db->trans_begin();
		
		$INV_NUM		= $parameters['INV_NUM'];
    	$Item_code 		= $parameters['Item_code'];
		$proj_Code 		= $parameters['proj_Code'];
    	$Qty_RR 		= $parameters['Qty_RR'];
    	$Qty_RR2 		= $parameters['Qty_RR2'];
    	$Curr_ID 		= $parameters['Currency_ID'];
    	$UnitPrice 		= $parameters['UnitPrice'];
    	$BUnitPrice 	= $parameters['BUnitPrice'];
    	$Tax_Code1 		= $parameters['Tax_Code1'];
    	$Tax_Code2 		= $parameters['Tax_Code2'];
		
		$transacValue	= $Qty_RR * $UnitPrice;
		$transacValueB	= $Qty_RR * $BUnitPrice;

		$INV_DATE 		= date('Y');
		$sqlINV 		= "SELECT INV_DATE FROM tbl_pinv_header WHERE INV_NUM = '$INV_NUM'";
		$resINV			= $this->db->query($sqlINV)->result();
		foreach($resINV as $rowINVO):
			$INV_DATE	= $rowINVO->INV_DATE;
		endforeach;
		$accYr			= date('Y', strtotime($INV_DATE));
		
		$TaxValPPn1 	= 0;
		$TaxValPPn2 	= 0;
		$TaxValPPh1 	= 0;
		$TaxValPPh2 	= 0;
		$transacValue2	= 0;
		$transacValueB2	= 0;
		$inTAXPPn		= 0;
		$inTAXPPh		= 0;
		if($Tax_Code1 == 'Tax001')
		{
			$inTAXPPn	= $inTAXPPn + 1;
			$TaxValPPn1	= 0.1 * $transacValueB;
			$TaxValPPh1	= 0;
		}
		elseif($Tax_Code1 == 'Tax002')
		{
			$inTAXPPh	= $inTAXPPh + 1;
			$TaxValPPn1	= 0;
			$TaxValPPh1	= 0.03 * $transacValueB;
		}
		
		if($Tax_Code2 == 'Tax001')
		{
			$inTAXPPn	= $inTAXPPn + 1;
			$TaxValPPn2	= 0.1 * $transacValueB;
			$TaxValPPh2	= 0;
		}
		elseif($Tax_Code2 == 'Tax002')
		{
			$inTAXPPh	= $inTAXPPh + 1;
			$TaxValPPn2	= 0;
			$TaxValPPh2	= 0.03 * $transacValueB;
		}
		
		$totTaxValPPn	= $TaxValPPn1 + $TaxValPPn2;
		$totTaxValPPh	= $TaxValPPh1 + $TaxValPPh2;
		
		$curr_rate = 1; // Default IDR ke IDR
		
		/*	Jurnal yang terbentuk
		 		20180 - HUTANG USAHA YANG BELUM DIFAKTURKAN		xxxx
		 		20068 - PPN Masukan								xxxx
					20157 - HUTANG USAHA LOCAL - IDR					xxxx
		*/
		
		// SISI DEBIT
			// 1. JOURNAL - HUTANG USAHA YANG BELUM DIFAKTURKAN (20180)
					$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Debet, Base_Debet, 
									COA_Debet, CostCenter, curr_rate, isDirect)
								VALUES ('$INV_NUM', '20180', '$proj_Code', '$Curr_ID', $transacValue, $transacValue, 
									$transacValue, 'Default', $curr_rate, 0)";
					if(!$this->db->query($sqlGEJDD))
					{
						echo "Input HUTANG USAHA YANG BELUM DIFAKTURKAN Error broh";
						return false;
					}
			// 2. JOURNAL - Hitung PPN Masukan (20068)
					if($inTAXPPn > 0)
					{
						$sqlGEJDD = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, 
										JournalD_Debet, Base_Debet, COA_Debet, CostCenter, curr_rate, isDirect)
									VALUES ('$INV_NUM', '20068', '$proj_Code', '$Curr_ID', $totTaxValPPn, $totTaxValPPn, 
										$totTaxValPPn, 'Default', $curr_rate, 0)";
						if(!$this->db->query($sqlGEJDD))
						{
							echo "Input PPN Masukan Error broh";
							return false;
						}
					}
			// 3. COA - HUTANG USAHA YANG BELUM DIFAKTURKAN (20180)
					$sqlUpdCOAD1		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$transacValue,
												Base_Debet2 = Base_Debet2+$transacValueB, BaseD_$accYr = BaseD_$accYr+$transacValue
											WHERE Acc_ID = '20180'";
					$this->db->query($sqlUpdCOAD1);
				
			// 4. COA - PPN Masukan (20068)
					if($inTAXPPn > 0)
					{
						$sqlUpdCOAD1		= "UPDATE tbl_chartaccount SET Base_Debet = Base_Debet+$totTaxValPPn,
													Base_Debet2 = Base_Debet2+$totTaxValPPn, BaseD_$accYr = BaseD_$accYr+$totTaxValPPn
												WHERE Acc_ID = '20068'";
						$this->db->query($sqlUpdCOAD1);
					}
			
				
		// SISI KREDIT
			// 1. JOURNAL - HUTANG USAHA LOCAL - IDR (20157)
					// Ditambahkan dengan PPN jika ada
					$transacValue2	= $transacValue + $totTaxValPPn - $totTaxValPPh;
					$transacValueB2	= $transacValueB + $totTaxValPPn - $totTaxValPPh;
					$sqlGEJDK = "INSERT INTO tbl_journaldetail (JournalH_Code, Acc_Id, proj_Code, Currency_id, JournalD_Kredit, 
									Base_Kredit, COA_Kredit, CostCenter, curr_rate, isDirect)
								VALUES ('$INV_NUM', '20157', '$proj_Code', '$Curr_ID', $transacValue2, $transacValue2, 
									$transacValue2, 'Default', $curr_rate, 0)";
					$this->db->query($sqlGEJDK);
			// 2. COA - HUTANG USAHA LOCAL - IDR (20157)
					$sqlUpdCOAD		= "UPDATE tbl_chartaccount SET Base_Kredit = Base_Kredit+$transacValue2, 
											Base_Kredit2 = Base_Kredit2+$transacValueB2, BaseK_$accYr = BaseK_$accYr+$transacValue2
										WHERE Acc_ID = '20157'";
					$this->db->query($sqlUpdCOAD);
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}
	// ---------------- END : Pembuatan Journal Header ----------------	
	
	function count_all_num_rowsDept()
	{
		return $this->db->count_all('tdepartment');
	}
	
	function viewDepartment()
	{
		$this->db->select('Dept_ID, Dept_Name');
		$this->db->from('tdepartment');
		$this->db->order_by('Dept_Name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('thrmemployee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('thrmemployee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsVend()
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function searchpurreq($konstSearch)
	{
		$selSearchType 	= $this->input->POST ('selSearchType');
		$txtSearch 		= $this->input->POST ('txtSearch');
		$selVendStatus 		= $this->input->POST ('selVendStatus');
		if($selSearchType == $konstSearch)
		{
			$this->db->like('PR_Number', $txtSearch);
		}
		else
		{
			$this->db->like('PR_Date', $txtSearch);
		}
		$this->db->where('PR_EmpID', $selVendStatus);
		$query = $this->db->get('TPReq_Header');
		return $query->result(); 
	}
	
	function delete($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->delete($this->table);
	}
	
	function count_all_num_rowsAllItem()
	{
		return $this->db->count_all('titem');
	}
	
	// remarks by DH on March, 6 2014
	/*function viewAllItem()
	{
		$this->db->select('Item_Code, Item_Name, Item_Qty, Unit_Type_ID');
		$this->db->from('titem');
		$this->db->order_by('Item_Code', 'asc');
		return $this->db->get();
	}*/
	// add by DH on March, 6 2014
	function viewAllItem()
	{
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Unit_Type_ID1, B.Unit_Type_Name
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}
	
	// Add by DH on March, 7 2014
	function count_all_num_rows_inbox()
	{
		/*$sql	= 	"SELECT count(*)
					FROM TPReq_Header
					WHERE Approval_Status NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('APPROVE', 0);
		$this->db->where('APPROVE', 1);
		$this->db->where('APPROVE', 2);
		return $this->db->count_all('TPReq_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.PR_Number, A.PR_Date, A.APPROVE, A.PR_Status, A.SPLCODE, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPReq_Header A
				INNER JOIN  thrmemployee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.PR_Number";
		
		/*$this->db->select('PR_Number, PR_Date, APPROVE, PR_Status, SPLCODE, PR_Notes, PR_EmpID');
		$this->db->from('TPReq_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function count_all_IRTTK($SPLCODE, $PRJCODE, $TTK_CATEG) // OK
	{
		if($TTK_CATEG == 'IR')
		{
			$sql = "tbl_ir_header A
						LEFT JOIN tbl_supplier D ON A.SPLCODE = D.SPLCODE
					WHERE A.IR_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.INVSTAT NOT IN ('FI')
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'";
		}
		elseif($TTK_CATEG == 'OPN')
		{
			$sql = "tbl_opn_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.OPNH_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.OPNH_ISCLOSE = '0'
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'";
		}
		else
		{
			$sql = "tbl_fpa_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.FPA_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'";
		}
		return $this->db->count_all($sql);
	}
	
	function viewAllIRTTK($SPLCODE, $PRJCODE, $TTK_CATEG) // OK
	{
		if($TTK_CATEG == 'IR')
		{
			$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
						A.IR_NOTE, A.IR_NOTE2, 0 AS OPNH_RETAMN,
						B.SPLDESC, 0 AS OPNH_AMOUNTPPN
					FROM tbl_ir_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.IR_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.INVSTAT NOT IN ('FI')
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'
					ORDER BY B.SPLDESC ASC";
		}
		elseif($TTK_CATEG == 'OPN')
		{
			$sql = "SELECT DISTINCT A.OPNH_NUM AS IR_NUM, A.OPNH_CODE AS IR_CODE, A.OPNH_DATE AS IR_DATE, '' AS IR_DUEDATE,
						A.WO_NUM AS IR_REFER, A.SPLCODE, A.WO_NUM AS PO_NUM, A.OPNH_AMOUNT AS IR_AMOUNT, 
						A.OPNH_NOTE AS IR_NOTE, A.OPNH_NOTE2 AS IR_NOTE2, A.OPNH_RETAMN AS OPNH_RETAMN,
						B.SPLDESC, A.OPNH_AMOUNTPPN
					FROM tbl_opn_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.OPNH_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.OPNH_ISCLOSE = '0'
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'
					ORDER BY B.SPLDESC ASC";
		}
		else
		{
			$sql = "SELECT DISTINCT A.FPA_NUM AS IR_NUM, A.FPA_CODE AS IR_CODE, A.FPA_DATE AS IR_DATE, '' AS IR_DUEDATE,
						'' AS IR_REFER, A.SPLCODE, '' AS PO_NUM, A.FPA_VALUE AS IR_AMOUNT, A.FPA_NOTE AS IR_NOTE, A.FPA_NOTE2 AS IR_NOTE2,
						0 AS OPNH_RETAMN, B.SPLDESC, 0 AS OPNH_AMOUNTPPN
					FROM tbl_fpa_header A
						LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.FPA_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'
					ORDER BY B.SPLDESC ASC";
		}
		return $this->db->query($sql);
	}
	
	function count_all_ttk($PRJCODE, $key) // OK
	{
		if($key == '')
		{
			$sql = "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.TTK_NUM LIKE '%$key%' ESCAPE '!' OR A.TTK_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.TTK_DATE LIKE '%$key%' ESCAPE '!' OR A.TTK_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_ttk($PRJCODE, $start, $end, $key) // OK
	{
		if($key == '')
		{
			$sql = "SELECT A.*
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.TTK_NUM LIKE '%$key%' ESCAPE '!' OR A.TTK_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.TTK_DATE LIKE '%$key%' ESCAPE '!' OR A.TTK_NOTES LIKE '%$key%' ESCAPE '!' 
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function addTTK($insertTTK) // OK
	{
		$this->db->insert('tbl_ttk_header', $insertTTK);
	}
	
	function addTTKD($insertTTKD) // OK
	{
		$this->db->insert('tbl_ttk_detail', $insertTTKD);
	}
	
	function get_ttk_by_number($TTK_NUM) // OK
	{
		$sql		= "SELECT * FROM tbl_ttk_header WHERE TTK_NUM = '$TTK_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function count_ttkp_by_number($TTK_NUM)
	{
		$sql	= "tbl_ttk_print WHERE TTKP_NUM = '$TTK_NUM'";
		return $this->db->count_all($sql);
	}
	
	function get_ttkp_by_number($TTK_NUM) // OK
	{
		$sql		= "SELECT * FROM tbl_ttk_print WHERE TTKP_NUM = '$TTK_NUM' LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateTTK($TTK_NUM, $updateTTK)
	{
		$this->db->where('TTK_NUM', $TTK_NUM);
		$this->db->update('tbl_ttk_header', $updateTTK);
	}
	
	function updateTTKCLS($TTK_NUM, $updateTTK)
	{
		$this->db->where('TTK_NUM', $TTK_NUM);
		$this->db->update('tbl_ttk_header', $updateTTK);
		
		// GET CATEGORY AND REF. NO.
			$IR_AMOUNT	= 0;
			$sqlA 		= "SELECT B.TTK_CATEG, A.TTK_REF1_NUM 
							FROM tbl_ttk_detail A
								INNER JOIN tbl_ttk_header B ON A.TTK_NUM = B.TTK_NUM
							WHERE A.TTK_NUM = '$TTK_NUM'";
			$ressqlA 	= $this->db->query($sqlA)->result();
			foreach($ressqlA as $rowA) :
				$TTK_CATEG 	= $rowA->TTK_CATEG;
				$TTK_REF1 	= $rowA->TTK_REF1_NUM;
				if($TTK_CATEG == 'IR')
				{
					$sqlB 	= "UPDATE tbl_ir_header SET TTK_CREATED = 0 WHERE IR_NUM = '$TTK_REF1'";
					$this->db->query($sqlB);
				}
				elseif($TTK_CATEG == 'OPN')
				{
					$sqlB 	= "UPDATE tbl_opn_header SET TTK_CREATED = 0 WHERE OPNH_NUM = '$TTK_REF1'";
					$this->db->query($sqlB);
				}
				elseif($TTK_CATEG == 'DP')
				{
					$sqlB 	= "UPDATE tbl_dp_header SET TTK_CREATED = 0 WHERE DP_NUM = '$TTK_REF1'";
					$this->db->query($sqlB);
				}
			endforeach;
	}
	
	function deleteTTKDet($TTK_NUM)
	{
		$this->db->where('TTK_NUM', $TTK_NUM);
		$this->db->delete('tbl_ttk_detail');
	}
	
	function deleteTTTax($TTK_NUM)
	{
		$this->db->where('TTK_NUM', $TTK_NUM);
		$this->db->delete('tbl_ttk_tax');
	}
	
	function deleteTTKDet_dir($TTK_NUM)
	{
		$this->db->where('TTK_NUM', $TTK_NUM);
		$this->db->delete('tbl_ttk_detail_itm');
	}
	
	function updIR($IR_NUM, $updIR)
	{
		$this->db->where('IR_NUM', $IR_NUM);
		$this->db->update('tbl_ir_header', $updIR);
	}
	
	function updOPN($OPN_NUM, $updOPN)
	{
		$this->db->where('OPNH_NUM', $OPN_NUM);
		$this->db->update('tbl_opn_header', $updOPN);
	}
	
	function updOTH($FPA_NUM, $updOTH)
	{
		$this->db->where('FPA_NUM', $FPA_NUM);
		$this->db->update('tbl_fpa_header', $updOTH);
	}

	function updDP($DP_NUM, $updDP)
	{
		$this->db->where('DP_NUM', $DP_NUM);
		$this->db->update('tbl_dp_header', $updDP);
	}
	
	function count_allTTKdir($SPLCODE, $PRJCODE) // G
	{
		$sql	= "tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_CATEG = 'OTH' AND A.TTK_STAT = '3' AND A.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_allTTKdir($SPLCODE, $PRJCODE) // G
	{
		$sql 	= "SELECT A.*, B.SPLDESC
					FROM tbl_ttk_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.TTK_CATEG = 'OTH' AND A.TTK_STAT = '3' AND A.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function count_allDP($SPLCODE, $PRJCODE) // G
	{
		$sql	= "tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.DP_STAT = '3' AND DP_PAID = '2' AND A.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_allDP($SPLCODE, $PRJCODE) // G
	{
		$sql 	= "SELECT A.*, B.SPLDESC
					FROM tbl_dp_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.DP_STAT = '3' AND DP_PAID = '2' AND A.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function updateDP($DP_NUM, $updDP) // G
	{
		$this->db->where('DP_NUM', $DP_NUM);
		$this->db->update('tbl_dp_header', $updDP);
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A
				WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U','S','I','O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
					AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
				OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A 
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U','S','I','O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U','S','I','O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U','S','I','O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
							A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
							A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT, A.ADDM_VOLM, A.ADDM_JOBCOST,
							A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
							A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
						FROM tbl_joblist_detail A
							-- LEFT JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE AND A.PRJCODE = B.PRJCODE
						WHERE A.PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('U','S','I','O') AND A.ISLAST = 1 AND A.WBSD_STAT = 1 AND A.ITM_CODE != ''
							AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataIRC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_ir_header A
					WHERE A.IR_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.INVSTAT NOT IN ('FI')
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'
						AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
							OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataIRL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataIREXPC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_ir_detail A
						INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
					WHERE B.IR_STAT = 3
						AND B.SPLCODE = '$SPLCODE'
						AND B.INVSTAT NOT IN ('FI')
						AND A.PRJCODE  = '$PRJCODE'
						AND B.TTK_CREATED = '0'
						AND A.ISCOST = 1
						AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR B.SPLCODE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR B.IR_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataIREXPL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, B.IR_DUEDATE, B.IR_REFER, B.SPLCODE, A.PO_NUM, A.ITM_TOTAL AS IR_AMOUNT, 
							A.NOTES AS IR_NOTE, '' AS IR_NOTE2, B.SPLDESC, B.TAXCODE_PPN, B.TAXCODE_PPH
						FROM tbl_ir_detail A
							INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
						WHERE B.IR_STAT = 3
							AND B.SPLCODE = '$SPLCODE'
							AND B.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND B.TTK_CREATED = '0'
							AND A.ISCOST = 1
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR B.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR B.IR_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, B.IR_DUEDATE, B.IR_REFER, B.SPLCODE, A.PO_NUM, A.ITM_TOTAL AS IR_AMOUNT, 
							A.NOTES AS IR_NOTE, '' AS IR_NOTE2, B.SPLDESC, B.TAXCODE_PPN, B.TAXCODE_PPH
						FROM tbl_ir_detail A
							INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
						WHERE B.IR_STAT = 3
							AND B.SPLCODE = '$SPLCODE'
							AND B.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND B.TTK_CREATED = '0'
							AND A.ISCOST = 1
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR B.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR B.IR_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, B.IR_DUEDATE, B.IR_REFER, B.SPLCODE, A.PO_NUM, A.ITM_TOTAL AS IR_AMOUNT, 
							A.NOTES AS IR_NOTE, '' AS IR_NOTE2, B.SPLDESC, B.TAXCODE_PPN, B.TAXCODE_PPH
						FROM tbl_ir_detail A
							INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
						WHERE B.IR_STAT = 3
							AND B.SPLCODE = '$SPLCODE'
							AND B.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND B.TTK_CREATED = '0'
							AND A.ISCOST = 1
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR B.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR B.IR_NOTE LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.IR_NUM, A.IR_CODE, A.IR_DATE, B.IR_DUEDATE, B.IR_REFER, B.SPLCODE, A.PO_NUM, A.ITM_TOTAL AS IR_AMOUNT, 
							A.NOTES AS IR_NOTE, '' AS IR_NOTE2, B.SPLDESC, B.TAXCODE_PPN, B.TAXCODE_PPH
						FROM tbl_ir_detail A
							INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
						WHERE B.IR_STAT = 3
							AND B.SPLCODE = '$SPLCODE'
							AND B.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND B.TTK_CREATED = '0'
							AND A.ISCOST = 1
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR B.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR B.SPLDESC LIKE '%$search%' ESCAPE '!' OR B.IR_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataIRSJC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_ir_header A
					INNER JOIN tbl_ir_detail B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
					WHERE A.IR_STAT = 3
						AND A.SPLCODE = '$SPLCODE'
						AND A.INVSTAT NOT IN ('FI')
						AND A.PRJCODE  = '$PRJCODE'
						AND A.TTK_CREATED = '0'
						AND B.SJ_NUM != ''
						AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
							OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataIRSJL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.SJ_NUM, A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						INNER JOIN tbl_ir_detail B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND B.SJ_NUM != ''
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT B.SJ_NUM, A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						INNER JOIN tbl_ir_detail B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND B.SJ_NUM != ''
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT B.SJ_NUM, A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						INNER JOIN tbl_ir_detail B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND B.SJ_NUM != ''
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT B.SJ_NUM, A.IR_NUM, A.IR_CODE, A.IR_DATE, A.IR_DUEDATE, A.IR_REFER, A.SPLCODE, A.PO_NUM, A.IR_AMOUNT, 
							A.IR_NOTE, A.IR_NOTE2, A.SPLDESC, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_ir_header A
						INNER JOIN tbl_ir_detail B ON B.IR_NUM = A.IR_NUM AND B.PRJCODE = A.PRJCODE
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0'
							AND B.SJ_NUM != ''
							AND (A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!' 
								OR A.SPLDESC LIKE '%$search%' ESCAPE '!' OR A.IR_NOTE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataIREXPC_X($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_po_detail
					WHERE PO_NUM IN (SELECT DISTINCT A.PO_NUM FROM tbl_ir_header A 
						WHERE A.IR_STAT = 3
							AND A.SPLCODE = '$SPLCODE'
							AND A.INVSTAT NOT IN ('FI')
							AND A.PRJCODE  = '$PRJCODE'
							AND A.TTK_CREATED = '0')
						AND ISCOST = 1
						AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!' 
							OR PO_DESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataIREXPL_X($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT PO_NUM, PO_CODE, PO_DATE, PRJCODE, JOBCODEID, JOBPARDESC, ITM_CODE, PO_VOLM, PO_PRICE, PO_COST, PO_DESC
						FROM tbl_po_detail
						WHERE PO_NUM IN (SELECT DISTINCT A.PO_NUM FROM tbl_ir_header A 
							WHERE A.IR_STAT = 3
								AND A.SPLCODE = '$SPLCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.PRJCODE  = '$PRJCODE'
								AND A.TTK_CREATED = '0')
							AND ISCOST = 1
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!' 
								OR PO_DESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT PO_NUM, PO_CODE, PO_DATE, PRJCODE, JOBCODEID, JOBPARDESC, ITM_CODE, PO_VOLM, PO_PRICE, PO_COST, PO_DESC
						FROM tbl_po_detail
						WHERE PO_NUM IN (SELECT DISTINCT A.PO_NUM FROM tbl_ir_header A 
							WHERE A.IR_STAT = 3
								AND A.SPLCODE = '$SPLCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.PRJCODE  = '$PRJCODE'
								AND A.TTK_CREATED = '0')
							AND ISCOST = 1
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!' 
								OR PO_DESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT PO_NUM, PO_CODE, PO_DATE, PRJCODE, JOBCODEID, JOBPARDESC, ITM_CODE, PO_VOLM, PO_PRICE, PO_COST, PO_DESC
						FROM tbl_po_detail
						WHERE PO_NUM IN (SELECT DISTINCT A.PO_NUM FROM tbl_ir_header A 
							WHERE A.IR_STAT = 3
								AND A.SPLCODE = '$SPLCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.PRJCODE  = '$PRJCODE'
								AND A.TTK_CREATED = '0')
							AND ISCOST = 1
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!' 
								OR PO_DESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT PO_NUM, PO_CODE, PO_DATE, PRJCODE, JOBCODEID, JOBPARDESC, ITM_CODE, PO_VOLM, PO_PRICE, PO_COST, PO_DESC
						FROM tbl_po_detail
						WHERE PO_NUM IN (SELECT DISTINCT A.PO_NUM FROM tbl_ir_header A 
							WHERE A.IR_STAT = 3
								AND A.SPLCODE = '$SPLCODE'
								AND A.INVSTAT NOT IN ('FI')
								AND A.PRJCODE  = '$PRJCODE'
								AND A.TTK_CREATED = '0')
							AND ISCOST = 1
							AND (PO_CODE LIKE '%$search%' ESCAPE '!' OR PO_DATE LIKE '%$search%' ESCAPE '!' 
								OR PO_DESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataOPNC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql = "tbl_opn_header A
					LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.OPNH_STAT = 3
					AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '0'
					AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataOPNL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '0'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '0'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '0'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH, A.TAXCODE_PPN, A.TAXCODE_PPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '0'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataOPNRC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql = "tbl_opn_header A
					LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.OPNH_STAT = 3
					AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '1'
					AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
					OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataOPNRL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '1'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '1'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '1'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT DISTINCT A.OPNH_NUM, A.OPNH_CODE, A.OPNH_DATE, A.WO_NUM, A.WO_CODE, A.SPLCODE, A.OPNH_AMOUNT, 
							A.OPNH_NOTE, A.OPNH_NOTE2, A.OPNH_RETAMN, A.OPNH_DPVAL, A.OPNH_RETAMN, A.OPNH_POT,
							B.SPLDESC, A.OPNH_AMOUNTPPN, A.OPNH_AMOUNTPPH
						FROM tbl_opn_header A
							LEFT JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.OPNH_STAT = 3
							AND A.SPLCODE = '$SPLCODE' AND A.OPNH_ISCLOSE = '0' AND A.PRJCODE  = '$PRJCODE' AND A.TTK_CREATED = '0' AND A.OPNH_TYPE = '1'
							AND (A.OPNH_CODE LIKE '%$search%' ESCAPE '!' OR A.OPNH_NOTE LIKE '%$search%' ESCAPE '!' 
							OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataITMDPC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql = "tbl_dp_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0
					AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
					OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITMDPL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0
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
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_dp_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0
							AND (A.DP_CODE LIKE '%$search%' ESCAPE '!' OR A.DP_DESC LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataWOC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_wo_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0 
						AND A.WO_DPPER > 0 AND A.WO_DPSTAT = 0 AND WO_STAT = 3
						AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
						OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
						OR A.WO_VALUE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataWOL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0 
							AND A.WO_DPPER > 0 AND A.WO_DPSTAT = 0 AND WO_STAT = 3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_wo_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0 
							AND A.WO_DPPER > 0 AND A.WO_DPSTAT = 0 AND WO_STAT = 3
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
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0 
							AND A.WO_DPPER > 0 AND A.WO_DPSTAT = 0 AND WO_STAT = 3
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
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' AND A.TTK_CREATED = 0 
							AND A.WO_DPPER > 0 AND A.WO_DPSTAT = 0 AND WO_STAT = 3
							AND (A.WO_CODE LIKE '%$search%' ESCAPE '!' OR A.WO_NOTE LIKE '%$search%' ESCAPE '!'
							OR A.WO_STARTD LIKE '%$search%' ESCAPE '!' OR A.WO_ENDD LIKE '%$search%' ESCAPE '!'
							OR A.WO_VALUE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function get_AllDataPOC($PRJCODE, $SPLCODE, $search) // GOOD
	{
		$sql 	= "tbl_po_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' 
						AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 0 AND PO_STAT = 3
						AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
						OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
						OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPOL($PRJCODE, $SPLCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' 
							AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 0 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_po_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' 
							AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 0 AND PO_STAT = 3
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
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' 
							AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 0 AND PO_STAT = 3
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
						WHERE PRJCODE = '$PRJCODE' AND A.SPLCODE = '$SPLCODE' 
							AND A.PO_DPSTAT = 0 AND A.TTK_CREATED = 0 AND PO_STAT = 3
							AND (A.PO_CODE LIKE '%$search%' ESCAPE '!' OR A.PO_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.PO_DATE LIKE '%$search%' ESCAPE '!' OR A.PO_DUED LIKE '%$search%' ESCAPE '!'
							OR A.PO_TOTCOST LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>