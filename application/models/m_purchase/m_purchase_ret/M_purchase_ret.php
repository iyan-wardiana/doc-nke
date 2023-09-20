<?php
/*  
 * Author		= Hendar Permana
 * Create Date	= 19 Maret 2019
 * Updated		= Dian Hermanto
 * File Name	= M_purchase_ret.php
 * Location		= -
*/

class M_purchase_ret extends CI_Model
{public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ret_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
					OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
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
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_ret_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)
					AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
					OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
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
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.SPLDESC
						FROM tbl_ret_header A
							INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)
							AND (A.RET_CODE LIKE '%$search%' ESCAPE '!' OR A.SPLCODE LIKE '%$search%' ESCAPE '!'
							OR A.IR_CODE LIKE '%$search%' ESCAPE '!' OR A.RET_NOTES LIKE '%$search%' ESCAPE '!'
							OR A.STATDESC LIKE '%$search%' ESCAPE '!' OR B.SPLDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_ret($PRJCODE, $key) // G
	{
		if($key == '')
		{
			$sql = "tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.RET_NUM LIKE '%$key%' ESCAPE '!' OR A.RET_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.RET_DATE LIKE '%$key%' ESCAPE '!' OR A.RET_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_ret($PRJCODE, $start, $end, $key) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.RET_NUM, A.RET_CODE, A.RET_DATE,  A.RET_TYPE, A.PRJCODE, A.SPLCODE, A.RET_TOTCOST, A.RET_CREATER, 
						A.RET_STAT, 
						A.IR_CODE, A.ISDIRECT, A.RET_NOTES, A.RET_MEMO, A.JOBCODEID, A.Patt_Year, A.Patt_Month, 
						A.Patt_Date, A.Patt_Number,
						B.SPLDESC
					FROM tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.RET_NUM, A.RET_CODE, A.RET_DATE,  A.RET_TYPE, A.PRJCODE, A.SPLCODE, A.RET_TOTCOST, A.RET_CREATER, 
						A.RET_STAT, 
						A.IR_CODE, A.ISDIRECT, A.RET_NOTES, A.RET_MEMO, A.JOBCODEID, A.Patt_Year, A.Patt_Month, 
						A.Patt_Date, A.Patt_Number,
						B.SPLDESC
					FROM tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.RET_NUM LIKE '%$key%' ESCAPE '!' OR A.RET_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.RET_DATE LIKE '%$key%' ESCAPE '!' OR A.RET_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVend($PRJCODE) // G
	{
		$sql = "tbl_ir_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				 WHERE A.TTK_CREATED = 0
				 	AND A.IR_STAT = 3
					AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function viewvendor($PRJCODE) // G
	{
		/*$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier
				 WHERE SPLCODE IN (SELECT SPLCODE
					  FROM tbl_ir_header
					 WHERE IR_STAT IN (3,6))";*/
		$sql = "SELECT A.SPLCODE, B.SPLDESC, B.SPLADD1
				FROM tbl_ir_header A
					INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
				 WHERE A.TTK_CREATED = 0
				 	AND A.IR_STAT = 3
					AND A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem($PRJCODE, $SPLCODE) // OK
	{
		//$sql	= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE'";
		$sql	= "tbl_ir_detail A
					INNER JOIN tbl_ir_header A1 ON A1.IR_NUM = A.IR_NUM
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND A.PRJCODE = B.PRJCODE
					WHERE B.ITM_GROUP IN ('M','T') AND A.PRJCODE = '$PRJCODE' AND A1.SPLCODE = '$SPLCODE'";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemMatBudget($PRJCODE, $SPLCODE) // OK
	{
		/*$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
						A.REQ_VOLM, A.REQ_AMOUNT, B.RET_VOLM, B.RET_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
						B.ITM_NAME
					FROM tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";*/
		$sql	= "SELECT A.IR_ID, A.PRJCODE, A.DEPCODE, A.IR_NUM, A.IR_CODE, A.IR_DATE, A.ACC_ID, A.PO_NUM, A.PO_CODE,A.JOBCODEID, A.WH_CODE, A.POD_ID,
						A.ITM_CODE, A.ITM_UNIT, A.ITM_GROUP, A.PO_VOLM, A.ITM_QTY, A.ITM_PRICE, A.ITM_TOTAL, A.ITM_DISC, A.RET_VOLM, A.RET_AMOUNT,
						B.ITM_NAME, B.ITM_VOLM
					FROM tbl_ir_detail A
					INNER JOIN tbl_ir_header A1 ON A1.IR_NUM = A.IR_NUM
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND A.PRJCODE = B.PRJCODE
					WHERE B.ITM_GROUP IN ('M','T') AND A.PRJCODE = '$PRJCODE' AND A1.SPLCODE = '$SPLCODE'";
		return $this->db->query($sql);
	}
	
	function add($AddRETH) // G
	{
		$this->db->insert('tbl_ret_header', $AddRETH);
	}
	
	function updateDet($RET_NUM, $PRJCODE, $RET_DATE) // G
	{
		$sql = "UPDATE tbl_ret_detail SET RET_DATE = '$RET_DATE' WHERE RET_NUM = '$RET_NUM'";
		return $this->db->query($sql);
	}
	
	function get_RET_by_number($RET_NUM) // G
	{
		$sql = "SELECT A.RET_NUM, A.RET_CODE, A.RET_DATE,  A.RET_TYPE, A.PRJCODE, A.SPLCODE, A.IR_NUM, A.IR_CODE, A.PO_NUM, A.PO_CODE, A.RET_NOTES, 
					A.RET_NOTES1, A.RET_STAT, A.JOBCODEID, A.RET_TOTCOST, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_ret_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.RET_NUM = '$RET_NUM'";
		return $this->db->query($sql);
	}
	
	function updateRET($RET_NUM, $updRETH) // G
	{
		$this->db->where('RET_NUM', $RET_NUM);
		$this->db->update('tbl_ret_header', $updRETH);
	}
	
	function deleteRETDetail($RET_NUM) // G
	{
		$this->db->where('RET_NUM', $RET_NUM);
		$this->db->delete('tbl_ret_detail');
	}
	
	function count_all_retInb($PRJCODE, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.RET_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND RET_STAT IN (2,7)
						AND (A.RET_NUM LIKE '%$key%' ESCAPE '!' OR A.RET_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.RET_DATE LIKE '%$key%' ESCAPE '!' OR A.RET_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_RETInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // G
	{
		if($key == '')
		{
			$sql = "SELECT A.RET_NUM, A.RET_CODE, A.RET_DATE,  A.RET_TYPE, A.PRJCODE, A.SPLCODE, A.RET_TOTCOST, A.RET_CREATER, A.RET_STAT, 
						A.IR_CODE, A.ISDIRECT, A.RET_NOTES, A.RET_MEMO, A.JOBCODEID, A.Patt_Year, A.Patt_Month, 
						A.Patt_Date, A.Patt_Number,
						B.SPLDESC
					FROM tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND RET_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.RET_NUM, A.RET_CODE, A.RET_DATE,  A.RET_TYPE, A.PRJCODE, A.SPLCODE, A.RET_TOTCOST, A.RET_CREATER, 
						A.RET_STAT, 
						A.IR_CODE, A.ISDIRECT, A.RET_NOTES, A.RET_MEMO, A.JOBCODEID, A.Patt_Year, A.Patt_Month, 
						A.Patt_Date, A.Patt_Number,
						B.SPLDESC
					FROM tbl_ret_header A
						INNER JOIN tbl_supplier B ON A.SPLCODE = B.SPLCODE
					WHERE PRJCODE = '$PRJCODE' AND RET_STAT IN (2,7)
						AND (A.RET_NUM LIKE '%$key%' ESCAPE '!' OR A.RET_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.RET_DATE LIKE '%$key%' ESCAPE '!' OR A.RET_NOTES LIKE '%$key%' ESCAPE '!'
						OR B.SPLDESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function updateITM_Min($parameters) // G
	{
		date_default_timezone_set("Asia/Jakarta");
		
    	$PRJCODE 	= $parameters['PRJCODE'];
    	$JOBCODEID 	= $parameters['JOBCODEID'];
		$RET_NUM 	= $parameters['IR_NUM'];
    	$RET_CODE 	= $parameters['IR_CODE'];
    	$ITM_CODE 	= $parameters['ITM_CODE'];
		$ITM_QTY 	= $parameters['ITM_QTY'];
		$ITM_PRICE 	= $parameters['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$ITM_CODE_H		= '';
		$ITM_VOLMBG		= 0;
		$StartVOL		= 0;
		$StartPRC		= 0;
		$ITM_REMQTY		= 0;
		$StartTPRC		= 0;
		$StartIN		= 0;
		$StartINP		= 0;
		$RET_VOLM		= 0;
		$RET_AMOUNT		= 0;
		$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_IN, ITM_INP,
								RET_VOLM, RET_AMOUNT
							FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$ITM_CODE_H 	= $rowSITM->ITM_CODE_H;
			$ITM_VOLMBG		= $rowSITM->ITM_VOLMBG;		// Budget on RAP
			$ITM_VOLMBGR	= $rowSITM->ITM_VOLMBGR;	// Remain of Budget
			$StartVOL 		= $rowSITM->ITM_VOLM; 		// like as Last Volume
			$StartPRC 		= $rowSITM->ITM_PRICE;		// like as Last Price Average
			$ITM_REMQTY		= $rowSITM->ITM_REMQTY;		// like as Remain Qty
			$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
			$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
			$StartIN 		= $rowSITM->ITM_IN;			// like as Last Total IN
			$StartINP 		= $rowSITM->ITM_INP;		// like as Last Total IN Price
			$RET_VOLM 		= $rowSITM->RET_VOLM;		// like as Last Total Return
			$RET_AMOUNT 	= $rowSITM->RET_AMOUNT;		// like as Last Total Return IN Price
		endforeach;
		
		$EndVOL			= $StartVOL - $ITM_QTY;			// New End Volume = Last Stock
		$EndTPRC		= $StartTPRC - $ITM_TOTALP;		// New End Amount
		$EndPRC			= $EndTPRC / $EndVOL;			// Last Price Average
		$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order
		
		$EndIN			= $StartIN - $ITM_QTY;
		$EndINP			= $StartINP - $ITM_TOTALP;
		$ITMVOLMBGR		= $ITM_VOLMBGR + $EndIN;		// Remain of Budget PLUS
		$ITMREMQTY		= $ITM_REMQTY - $ITM_QTY;
		
		$RET_VOLM		= $RET_VOLM + $ITM_QTY;
		$RET_AMOUNT		= $RET_AMOUNT + $ITM_TOTALP;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLMBGR = ITM_VOLMBGR + $ITM_QTY, ITM_VOLM = ITM_VOLM - $ITM_QTY, 
							ITM_PRICE = $EndPRC, ITM_REMQTY = ITM_REMQTY - $ITM_QTY, ITM_TOTALP = $EndTPRC, ITM_LASTP = $ITM_PRICE,
							RET_VOLM = RET_VOLM + $ITM_QTY, RET_AMOUNT = RET_AMOUNT + $RET_AMOUNT,
							ITM_IN = ITM_IN - $ITM_QTY, ITM_INP = ITM_INP - $ITM_TOTALP, LAST_TRXNO = '$RET_NUM'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
		$this->db->query($sqlUpDet);
		
		// UPDATE JOBD DETAIL
		$sqlJDITMC		= "tbl_joblist_detail WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resJDITMC		= $this->db->count_all($sqlJDITMC);
		
		$StartSTOCK		= 0;
		$EndSTOCK		= 0;
		$EndSTOCK_AM	= 0;
		$EndRET			= 0;
		$EndRET_AM		= 0;
		$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM, ITM_RET, ITM_RET_AM FROM tbl_joblist_detail 
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartJITM	= $this->db->query($sqlStartJITM)->result();
		foreach($resStartJITM as $rowSJITM) :
			$StartSTOCK		= $rowSJITM->ITM_STOCK;
			$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
			$StartRET		= $rowSJITM->ITM_RET;
			$StartRET_AM	= $rowSJITM->ITM_RET_AM;
		endforeach;
		
		//$EndSTOCK			= $StartSTOCK - $ITM_QTY;
		//$EndSTOCK_AM		= $StartSTOCK_AM - $ITM_TOTALP;
		//$EndRET				= $StartRET + $ITM_QTY;
		//$EndRET_AM			= $StartRET_AM + $ITM_TOTALP;
		
		if($resJDITMC > 0)
		{
			$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK - $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM - $ITM_TOTALP,
									ITM_RET = ITM_RET + $ITM_QTY, ITM_RET_AM = ITM_RET_AM + $ITM_TOTALP
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpDetJ);
		}
		else
		{
			$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK - $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM - $ITM_TOTALP,
									ITM_RET = ITM_RET + $ITM_QTY, ITM_RET_AM = ITM_RET_AM + $ITM_TOTALP
								WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE_H' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpDetJ);
		}
	}
	
	function count_all_IR($RET_NUM) // OK
	{
		$sql	= "tbl_ir_header A
						INNER JOIN tbl_ret_header B ON A.RET_NUM = B.RET_NUM
							AND B.RET_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.RET_NUM = '$RET_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_IR($RET_NUM) // OK
	{
		$sql 	= "SELECT A.IR_NUM, A.IR_DATE, A.IR_DUEDATE, A.SPLCODE, C.SPLDESC
					FROM tbl_ir_header A
						INNER JOIN tbl_ret_header B ON A.RET_NUM = B.RET_NUM
							AND B.RET_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.RET_NUM = '$RET_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->query($sql);
	}
}
?>