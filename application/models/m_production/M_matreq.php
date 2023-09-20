<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 21 Oktober 2018
 * File Name	= M_matreq.php
 * Location		= -
*/
class M_matreq extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_mr_header 
				WHERE PRJCODE = '$PRJCODE'
					AND (MR_CODE LIKE '%$search%' ESCAPE '!' OR MR_NOTE LIKE '%$search%' ESCAPE '!' 
						OR CREATERNM LIKE '%$search%' ESCAPE '!' OR STATDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.MR_ID, A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
							A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
							B.ITM_CODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_mr_header A 
							INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MR_CODE LIKE '%$search%' ESCAPE '!' OR A.MR_NOTE LIKE '%$search%' ESCAPE '!' 
								OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.MR_ID, A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
							A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
							B.ITM_CODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_mr_header A 
							INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MR_CODE LIKE '%$search%' ESCAPE '!' OR A.MR_NOTE LIKE '%$search%' ESCAPE '!' 
								OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.MR_ID, A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
							A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
							B.ITM_CODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_mr_header A 
							INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MR_CODE LIKE '%$search%' ESCAPE '!' OR A.MR_NOTE LIKE '%$search%' ESCAPE '!' 
								OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.MR_ID, A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
							A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
							B.ITM_CODE, A.STATDESC, A.STATCOL, A.CREATERNM
						FROM tbl_mr_header A 
							INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.MR_CODE LIKE '%$search%' ESCAPE '!' OR A.MR_NOTE LIKE '%$search%' ESCAPE '!' 
								OR A.CREATERNM LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!')
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

	function count_all_PRM($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			$sql = "tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.MR_NUM LIKE '%$key%' ESCAPE '!' OR A.MR_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.MR_DATE LIKE '%$key%' ESCAPE '!' OR A.MR_NOTE LIKE '%$key%' ESCAPE '!'
						OR A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_PRM($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
						A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
						B.ITM_CODE
					FROM tbl_mr_header A 
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
						A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
						B.ITM_CODE
					FROM tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.MR_NUM LIKE '%$key%' ESCAPE '!' OR A.MR_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.MR_DATE LIKE '%$key%' ESCAPE '!' OR A.MR_NOTE LIKE '%$key%' ESCAPE '!'
						OR A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
	
	function count_all_JO($PRJCODE) // GOOD
	{
		$sql	= "tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 1";
		return $this->db->count_all($sql);
	}
	
	function view_all_JO($PRJCODE) // GOOD
	{
		$sql	= "SELECT JO_NUM, JO_CODE, JO_DATE, SO_CODE, CUST_DESC, JO_DESC, JO_AMOUNT, JO_NOTES
					FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND JO_STAT = 3 AND ISSELECT = 1";
		return $this->db->query($sql);
	}
	
	function count_all_prim($PRJCODE, $JOBCODE, $JO_NUM) // GOOD
	{
		$CCAL_NUM 		= '';
		$sqlJOH 		= "SELECT CCAL_NUM FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
		$resJOH 		= $this->db->query($sqlJOH)->result();
		foreach($resJOH as $rowJOH) :
			$CCAL_NUM 	= $rowJOH->CCAL_NUM;
		endforeach;
		
		//$sql		= "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBPARENT = '$JOBCODE'";
		
		// AMBIL DARI ITEM CALCULATION
		$sql		= "tbl_ccal_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						INNER JOIN tbl_jo_stfdetail C ON A.ITM_CODE = C.ITM_CODE
							AND C.PRJCODE = '$PRJCODE'
							AND C.JOSTF_TYPE = 'IN'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.CCAL_NUM = '$CCAL_NUM'";	
		return $this->db->count_all($sql);
	}
	
	function view_all_prim($PRJCODE, $JOBCODE, $JO_NUM) // GOOD
	{
		$CCAL_NUM 		= '';
		$sqlJOH 		= "SELECT CCAL_NUM FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM'";
		$resJOH 		= $this->db->query($sqlJOH)->result();
		foreach($resJOH as $rowJOH) :
			$CCAL_NUM 	= $rowJOH->CCAL_NUM;
		endforeach;
		
		// PATOKAN PERMNTAAN MATERIAL BUKAN LAGI DARI ITEM CAL, MELAINKAN DARI JO DETAIL
		/*$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE,
							A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM AS ITM_VOLMBG, A.ADD_VOLM, 
							A.ADD_PRICE, A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM,
							A.IR_AMOUNT, A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, 
							A.ITM_STOCK_AM, A.ITM_BUDG,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_CODE_H
						FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_GROUP IN ('M','T') AND A.JOBPARENT = '$JOBCODE'";*/
		/*$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, C.JO_NUM,
							A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_QTY, A.ITM_TOTAL,
							A.MR_VOLM, A.MR_AMOUNT, A.IRM_VOLM, A.IRM_AMOUNT, A.USED_VOLM, A.USED_AMOUNT,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_CODE_H, B.ITM_CATEG, C.ITM_QTY AS ITM_QTY2
						FROM tbl_ccal_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							INNER JOIN tbl_jo_stfdetail C ON A.ITM_CODE = C.ITM_CODE
								AND C.PRJCODE = '$PRJCODE'
								AND C.JOSTF_TYPE = 'IN'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.CCAL_NUM = '$CCAL_NUM' ORDER BY B.ITM_NAME ASC";*/
		/*$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, C.JO_NUM,
							A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_QTY, A.ITM_TOTAL,
							A.MR_VOLM, A.MR_AMOUNT, A.IRM_VOLM, A.IRM_AMOUNT, A.USED_VOLM, A.USED_AMOUNT,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_CODE_H, B.ITM_CATEG, C.ITM_QTY AS ITM_QTY2
						FROM tbl_ccal_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							INNER JOIN tbl_jo_stfdetail C ON A.ITM_CODE = C.ITM_CODE
								AND C.PRJCODE = '$PRJCODE'
								AND C.JOSTF_TYPE = 'IN'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.CCAL_NUM = '$CCAL_NUM' ORDER BY B.ITM_NAME ASC";*/
		$sql 		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, C.JO_NUM, A.PRJCODE,
							A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_QTY, A.ITM_QTY * A.ITM_PRICE AS ITM_TOTAL,
							A.MR_QTY, A.MR_PRICE, 0 AS IRM_VOLM, 0 AS IRM_AMOUNT, 0 AS USED_VOLM, 0 AS USED_AMOUNT,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_CODE_H, B.ITM_CATEG, C.ITM_QTY AS ITM_QTY2
						FROM tbl_jo_stfdetail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							INNER JOIN tbl_jo_stfdetail C ON A.ITM_CODE = C.ITM_CODE
								AND C.PRJCODE = '$PRJCODE'
								AND C.JOSTF_TYPE = 'IN'
						WHERE
							A.PRJCODE = '$PRJCODE'
							AND B.ISRM = 1
							AND A.JO_NUM = '$JO_NUM'
							ORDER BY B.ITM_NAME ASC";
		return $this->db->query($sql);
	}
	
	function count_all_subs($PRJCODE, $JOBCODE) // GOOD
	{
		$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')";
		return $this->db->count_all($sql);
	}
	
	function view_all_subs($PRJCODE, $JOBCODE) // GOOD
	{
		$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
						PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, 0 AS ITM_QTY, 0 AS ITM_TOTAL,
						MR_VOLM, MR_AMOUNT, IR_VOLM AS IRM_VOLM, IR_AMOUNT AS IRM_AMOUNT, ITM_OUT AS USED_VOLM, ITM_OUTP AS USED_AMOUNT,
						ITM_TYPE, ITM_NAME, ITM_CODE_H, ITM_CATEG
					FROM tbl_item
						WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')";
		return $this->db->query($sql);
	}
	
	function add($projMatReqH) // GOOD
	{
		$this->db->insert('tbl_mr_header', $projMatReqH);
	}
	
	function updateH($MR_NUM, $PRJCODE, $prmHeader) // GOOD
	{
		$MR_AMOUNT		= $prmHeader['MR_AMOUNT'];
		$sql = "UPDATE tbl_mr_header SET MR_AMOUNT = '$MR_AMOUNT'
					WHERE MR_NUM = '$MR_NUM' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function updateD($MR_NUM, $PRJCODE, $prmDetail) // GOOD
	{
		$MR_DATE		= $prmDetail['MR_DATE'];
		$MR_DATEU	= $prmDetail['MR_DATEU'];
		$JO_NUM			= $prmDetail['JO_NUM'];
		$JO_CODE		= $prmDetail['JO_CODE'];
		$sql 			= "UPDATE tbl_mr_detail SET PRJCODE = '$PRJCODE', MR_DATE = '$MR_DATE', MR_DATEU = '$MR_DATEU', 
								JO_NUM = '$JO_NUM', JO_CODE = '$JO_CODE'
							WHERE MR_NUM = '$MR_NUM'";
		return $this->db->query($sql);
	}
	
	function get_mr_by_number($MR_NUM) // GOOD
	{
		$sql = "SELECT A.*, B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_mr_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.MR_NUM = '$MR_NUM'";
		return $this->db->query($sql);
	}
	
	function update($MR_NUM, $projMatReqH) // GOOD
	{
		$this->db->where('MR_NUM', $MR_NUM);
		$this->db->update('tbl_mr_header', $projMatReqH);
	}
	
	function deleteDetail($MR_NUM) // GOOD
	{
		$this->db->where('MR_NUM', $MR_NUM);
		$this->db->delete('tbl_mr_detail');
	}
	
	function count_all_MRInb($PRJCODE, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND MR_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND MR_STAT IN (2,7)
						AND (A.MR_NUM LIKE '%$key%' ESCAPE '!' OR A.MR_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.MR_DATE LIKE '%$key%' ESCAPE '!' OR A.MR_NOTE LIKE '%$key%' ESCAPE '!'
						OR A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_MRInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
						A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
						B.ITM_CODE
					FROM tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND MR_STAT IN (2,7) LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.MR_NUM, A.MR_CODE, A.MR_DATE, A.MR_DATEU, A.PRJCODE, A.JO_CODE, A.MR_NOTE,
						A.MR_STAT, A.MR_REFNO, A.MR_CREATER, A.MR_ISCLOSE, A.MR_AMOUNT,
						B.ITM_CODE
					FROM tbl_mr_header A
						INNER JOIN tbl_jo_detail B ON A.JO_NUM = B.JO_NUM
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE' AND MR_STAT IN (2,7)
						AND (A.MR_NUM LIKE '%$key%' ESCAPE '!' OR A.MR_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.MR_DATE LIKE '%$key%' ESCAPE '!' OR A.MR_NOTE LIKE '%$key%' ESCAPE '!'
						OR A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
		}
		return $this->db->query($sql);
	}
	
	function updateRelDet($MR_NUM, $JO_NUM, $PRJCODE) // GOOD
	{
		$SO_NUM 		= '';
		$SO_CODE 		= '';
		$CCAL_NUM 		= '';
		$CCAL_CODE 		= '';
		$BOM_NUM 		= '';
		$BOM_CODE 		= '';
		$sqlSOH 		= "SELECT SO_NUM, SO_CODE, CCAL_NUM, CCAL_CODE, BOM_NUM, BOM_CODE
							FROM tbl_jo_header WHERE JO_NUM = '$JO_NUM' LIMIT 1";
		$resSOH 		= $this->db->query($sqlSOH)->result();
		foreach($resSOH as $rowSOH) :
			$SO_NUM 	= $rowSOH->SO_NUM;
			$SO_CODE 	= $rowSOH->SO_CODE;
			$CCAL_NUM 	= $rowSOH->CCAL_NUM;
			$CCAL_CODE 	= $rowSOH->CCAL_CODE;
			$BOM_NUM 	= $rowSOH->BOM_NUM;
			$BOM_CODE 	= $rowSOH->BOM_CODE;
		endforeach;
		
		$sqlGetMR	= "SELECT MR_NUM, PRJCODE, ITM_CODE, MR_VOLM, MR_PRICE
						FROM tbl_mr_detail
						WHERE MR_NUM = '$MR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetMR	= $this->db->query($sqlGetMR)->result();
		foreach($resGetMR as $rowMR) :
			$MR_NUM 	= $rowMR->MR_NUM;
			$PRJCODE	= $rowMR->PRJCODE;
			$ITM_CODE	= $rowMR->ITM_CODE;
			$MR_VOLM	= $rowMR->MR_VOLM;
			$MR_PRICE	= $rowMR->MR_PRICE;
			$MR_TOTAMN	= $MR_VOLM * $MR_PRICE;
				
			// UPDATE CCAL
				$sqlUCCAL	= "UPDATE tbl_ccal_detail SET MR_VOLM = MR_VOLM + $MR_VOLM, MR_AMOUNT = MR_AMOUNT + $MR_TOTAMN
								WHERE CCAL_NUM = '$CCAL_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUCCAL);
		endforeach;
	}
	
	function updateVolBud($MR_NUM, $PRJCODE, $ITM_CODE) // G
	{
		// GET TOTAL CEREATE PO
		$PO_VOLM 	= 0;
		$PO_AMOUNT 	= 0;
		$IR_VOLM 	= 0;			
		$sqlGetPR	= "SELECT JOBCODEID, MR_VOLM, MR_PRICE, MR_TOTAL, PO_VOLM, PO_AMOUNT, IR_VOLM, IR_AMOUNT 
						FROM tbl_mr_detail
						WHERE MR_NUM = '$MR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowPR) :
			$JOBCODEID 	= $rowPR->JOBCODEID;
			$MR_VOLM 	= $rowPR->MR_VOLM;
			$MR_TOTAL 	= $rowPR->MR_TOTAL;
			$PO_VOLM 	= $rowPR->PO_VOLM;
			$PO_AMOUNT 	= $rowPR->PO_AMOUNT;
			$IR_VOLM 	= $rowPR->IR_VOLM;
			$IR_AMOUNT 	= $rowPR->IR_AMOUNT;
		endforeach;
		
		$REM_PRVOLM		= $MR_VOLM - $PO_VOLM;
		$REM_PRAMOUNT	= $MR_TOTAL - $PO_AMOUNT;
		
		$sqlGetJDI		= "SELECT MR_VOLM, MR_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetJDI		= $this->db->query($sqlGetJDI)->result();
		foreach($resGetJDI as $rowJDI) :
			$MR_VOLMI 		= $rowJDI->MR_VOLM;
			$MR_AMOUNTIS	= $rowJDI->MR_AMOUNT;
		endforeach;
		if($MR_VOLMI == '')
			$MR_VOLMI = 0;
		if($MR_AMOUNTIS == '')
			$MR_AMOUNTIS = 0;
		
		$totREMQty	= $MR_VOLMI - $REM_PRVOLM;
		$totREMAmn	= $MR_AMOUNTIS - $REM_PRAMOUNT;
			
		
		// UPDATE TO TBL ITEM N JOB
		$sqlUpd		= "UPDATE tbl_item SET MR_VOLM = $totREMQty, MR_AMOUNT = $totREMAmn
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpd);
		
		$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = $totREMQty, REQ_AMOUNT = $totREMAmn
						WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$this->db->query($sqlUpd);
	}
	
	function deletePO($MR_NUM) // OK
	{
		// 1. COPY TO tbl_mr_header_trash
			//$sqlqg11	= "INSERT INTO tbl_mr_header_trash SELECT * FROM tbl_mr_header WHERE MR_NUM = '$MR_NUM'";
			//$this->db->query($sqlqg11);
			
			$sqlqg12	= "DELETE FROM tbl_mr_header WHERE MR_NUM = '$MR_NUM'";
			$this->db->query($sqlqg12);
			
		// 2. COPY TO tbl_mr_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_mr_detail_trash SELECT * FROM tbl_mr_detail WHERE MR_NUM = '$MR_NUM'";
			$this->db->query($sqlqg13);
			
			$sqlqg14	= "DELETE FROM tbl_mr_detail WHERE MR_NUM = '$MR_NUM'";
			$this->db->query($sqlqg14);*/
	}
	
	function count_all_num_rowsVend() // USED
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function viewvendor() // USED
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier
				ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}
	
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
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function viewAllPR()
	{				
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Vend_Code, A.MR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tproject_mrheader A
				INNER JOIN  tbl_employee B ON A.MR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.MR_DepID = D.Dept_ID
				ORDER BY A.MR_Number";
		return $this->db->query($sql);
	}
	
	function update_inbox($SPPNUM, $projMatReqH) // USED
	{
		$this->db->where('SPPNUM', $SPPNUM);
		$this->db->update('tbl_mr_header', $projMatReqH);
	}
	
	function delete($MR_Number)
	{
		$this->db->where('MR_Number', $MR_Number);
		$this->db->delete($this->table);
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
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name, A.itemConvertion
				FROM titem A
				INNER JOIN tbl_unittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
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
					FROM TPO_Header
					WHERE Approval_Status NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('Approval_Status', 0);
		$this->db->where('Approval_Status', 1);
		$this->db->where('Approval_Status', 2);
		return $this->db->count_all('TPO_Header');
	}
	
	function get_last_ten_MR_inbox($limit, $offset)
	{
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Approval_Status, A.MR_Status, A.Vend_Code, A.MR_Notes, A.MR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.MR_EmpID = B.Emp_ID
				ORDER BY A.MR_Number";
		
		/*$this->db->select('MR_Number, MR_Date, Approval_Status, MR_Status, Vend_Code, MR_Notes, MR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('MR_Date', 'asc');*/
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox($limit, $offset, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT FROM tbl_project A
							INNER JOIN	tbl_mr_header D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		/*$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_mr_header D ON A.PRJCODE = D.PRJCODE
				ORDER BY A.PRJCODE";*/
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_mr_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function get_all_prjInbox_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_mr_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($SPPNUM, $parameters) // USED
	{
		$PRJCODE 	= $parameters['PRJCODE'];
    	$SPPNUM 	= $parameters['SPPNUM'];
		$SPPCODE 	= $parameters['SPPCODE'];
		$CSTCODE 	= $parameters['CSTCODE'];
		$SPPVOLM 	= $parameters['SPPVOLM'];
				
		$sqlGet		= "SELECT A.request_qty, A.request_qty2
						FROM tbl_projplan_material A
						WHERE A.PRJCODE = '$PRJCODE' AND A.CSTCODE = '$CSTCODE'";
		$resREQPlan	= $this->db->query($sqlGet)->result();
		foreach($resREQPlan as $rowRP) :
			$request_qty 	= $rowRP->request_qty;
			$request_qty2 	= $rowRP->request_qty2;
		endforeach;
		$totMRQty1	= $request_qty + $SPPVOLM;
		$totMRQty2	= $request_qty2 + $SPPVOLM;
		$sqlUpd		= "UPDATE tbl_projplan_material SET request_qty = $totMRQty1, request_qty2 = $totMRQty2
						WHERE PRJCODE = '$PRJCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sqlUpd);
	}
	
	function count_all_PO($MR_NUM) // OK
	{
		$sql	= "tbl_po_header A
						INNER JOIN tbl_mr_header B ON A.MR_NUM = B.MR_NUM
							AND B.MR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.MR_NUM = '$MR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_PO($MR_NUM) // OK
	{
		$sql 	= "SELECT A.PO_NUM, A.PO_DATE, A.MR_NUM, B.MR_DATE, A.PO_DUED, A.SPLCODE, A.MR_NUM, C.SPLDESC
					FROM tbl_po_header A
						INNER JOIN tbl_mr_header B ON A.MR_NUM = B.MR_NUM
							AND B.MR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.MR_NUM = '$MR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->query($sql);
	}
	
	function updREJECT($MR_NUM, $PRJCODE) // G
	{
		$sqlPR	= "SELECT JOBCODEID, ITM_CODE, MR_VOLM, MR_TOTAL
					FROM tbl_mr_detail WHERE MR_NUM = '$MR_NUM' AND PRJCODE = '$PRJCODE'";
		$resPR	= $this->db->query($sqlPR)->result();
		foreach($resPR as $rowPR) :
			$JOBCODEID	= $rowPR->JOBCODEID;
			$ITM_CODE	= $rowPR->ITM_CODE;
			$MR_VOLM	= $rowPR->MR_VOLM;
			$MR_TOTAL	= $rowPR->MR_TOTAL;
			
			// Kembalikan di tabel PR
			$sqlPR	= "UPDATE tbl_mr_detail SET PO_VOLM = 0, PO_AMOUNT = 0, IR_VOLM = 0, IR_AMOUNT = 0
						WHERE MR_NUM = '$MR_NUM' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlPR);
			
			// Kembalikan di tabel JOBLIST
			$sqlJLD	= "UPDATE tbl_joblist_detail SET REQ_VOLM = REQ_VOLM - $MR_VOLM, REQ_AMOUNT = REQ_AMOUNT - $MR_TOTAL
						WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlJLD);
			
			// Kembalikan di tabel MASTER ITEM
			$sqlITM	= "UPDATE tbl_item SET MR_VOLM = MR_VOLM - $MR_VOLM, MR_AMOUNT = MR_AMOUNT - $MR_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlITM);
		endforeach;
	}
}
?>