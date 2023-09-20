<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 20 Oktober 2018
 * File Name	= M_rejoborder.php
 * Location		= -
*/

class M_rejoborder extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_jo_header 
				WHERE PRJCODE = '$PRJCODE' AND ISREPROC = 1
					AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
						OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, CUST_CODE, CUST_DESC, JO_DESC, JO_AMOUNT, JO_NOTES,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND ISREPROC = 1
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, CUST_CODE, CUST_DESC, JO_DESC, JO_AMOUNT, JO_NOTES,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND ISREPROC = 1
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, CUST_CODE, CUST_DESC, JO_DESC, JO_AMOUNT, JO_NOTES,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND ISREPROC = 1
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT PRJCODE, JO_ID, JO_NUM, JO_CODE, JO_DATE, CUST_CODE, CUST_DESC, JO_DESC, JO_AMOUNT, JO_NOTES,
							STATDESC, STATCOL, CREATERNM, JO_STAT, MR_NUM, ISSELECT
						FROM tbl_jo_header
						WHERE PRJCODE = '$PRJCODE' AND ISREPROC = 1
							AND (JO_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' 
								OR JO_DESC LIKE '%$search%' ESCAPE '!' OR JO_DATE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_JO($PRJCODE, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1";
		}
		else
		{
			$sql = "tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND (A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.JO_DATE LIKE '%$key%' ESCAPE '!' OR A.JO_NOTES LIKE '%$key%' ESCAPE '!'
						OR A.SO_CODE LIKE '%$key%' ESCAPE '!' OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_JO($PRJCODE, $start, $end, $key) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.*, B.CUST_DESC
					FROM tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1 LIMIT $start, $end";
		}
		else
		{
			$sql = "SELECT A.*, B.CUST_DESC
					FROM tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND (A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.JO_DATE LIKE '%$key%' ESCAPE '!' OR A.JO_NOTES LIKE '%$key%' ESCAPE '!'
						OR A.SO_CODE LIKE '%$key%' ESCAPE '!' OR B.CUST_DESC LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
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
	
	function add($AddJO) // GOOD
	{
		$this->db->insert('tbl_jo_header', $AddJO);
	}
	
	function updateJOH($JO_NUM, $updJOH) // GOOD
	{
		$this->db->where('JO_NUM', $JO_NUM);
		$this->db->update('tbl_jo_header', $updJOH);
	}
	
	function updateDet($JO_NUM, $PRJCODE, $JO_DATE, $JO_PRODD) // GOOD
	{
		$sql = "UPDATE tbl_jo_detail SET JO_DATE = '$JO_DATE', JO_PRODD = '$JO_PRODD' WHERE JO_NUM = '$JO_NUM' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function updateDetPCost($JO_NUM, $PRJCODE, $GTOTIN_AMN) // GOOD
	{
		$sql = "UPDATE tbl_jo_detail SET PROD_COST = '$GTOTIN_AMN' WHERE JO_NUM = '$JO_NUM' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function updVOID($prm) // GOOD
	{
		$SO_NUM 	= $prm['SO_NUM'];		
		$ITM_CODE 	= $rm['ITM_CODE'];
		$ITM_QTY 	= $prm['ITM_QTY'];
		$ITM_TOTAL	= $prm['ITM_TOTAL'];
		$PRJCODE	= $prm['PRJCODE'];
		
		// Kembalikan di tabel SO
			$sqlSO	= "UPDATE tbl_so_detail SET PROD_VOLM = PROD_VOLM - $ITM_QTY, PROD_AMOUNT = PROD_AMOUNT - $ITM_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND SO_NUM = '$SO_NUM'";
			$this->db->query($sqlSO);
			
		// Kembalikan di tabel Item
			$sqlITM	= "UPDATE tbl_item SET PROD_VOLM = PROD_VOLM - $ITM_QTY, PROD_AMOUNT = PROD_AMOUNT - $ITM_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlITM);
	}
	
	function get_jo_by_number($JO_NUM) // GOOD
	{			
		$sql = "SELECT A.*, B.PRJCODE, B.PRJNAME
				FROM tbl_jo_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.JO_NUM = '$JO_NUM' AND A.ISREPROC = 1 LIMIT 1";
		return $this->db->query($sql);
	}
	
	function updateJO($JO_NUM, $updJO) // GOOD
	{
		$this->db->where('JO_NUM', $JO_NUM);
		$this->db->update('tbl_jo_header', $updJO);
	}
	
	function deleteJODetail($JO_NUM) // GOOD
	{
		$this->db->where('JO_NUM', $JO_NUM);
		$this->db->delete('tbl_jo_detail');
	}
	
	function deleteJOSTFDetail($JO_NUM) // GOOD
	{
		$this->db->where('JO_NUM', $JO_NUM);
		$this->db->delete('tbl_jo_stfdetail');
	}
	
	function count_all_JOInb($PRJCODE, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE  A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND A.JO_STAT IN (2,7)";
		}
		else
		{
			$sql = "tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE  A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND A.JO_STAT IN (2,7)
						AND (A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.JO_DATE LIKE '%$key%' ESCAPE '!' OR A.JO_NOTES LIKE '%$key%' ESCAPE '!'
						OR A.SO_CODE LIKE '%$key%' ESCAPE '!' OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->count_all($sql);
	}
	
	function get_all_JOInb($PRJCODE, $start, $end, $key, $DefEmp_ID) // GOOD
	{
		if($key == '')
		{
			$sql = "SELECT A.*, B.CUST_DESC
					FROM tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE  A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND A.JO_STAT IN (2,7)";
		}
		else
		{
			$sql = "SELECT A.*, B.CUST_DESC
					FROM tbl_jo_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE  A.PRJCODE = '$PRJCODE' AND A.ISREPROC = 1
						AND A.JO_STAT IN (2,7)
						AND (A.JO_NUM LIKE '%$key%' ESCAPE '!' OR A.JO_CODE LIKE '%$key%' ESCAPE '!' 
						OR A.JO_DATE LIKE '%$key%' ESCAPE '!' OR A.JO_NOTES LIKE '%$key%' ESCAPE '!'
						OR A.SO_CODE LIKE '%$key%' ESCAPE '!' OR B.CUST_DESC LIKE '%$key%' ESCAPE '!')";
		}
		return $this->db->query($sql);
	}
	
	function updSODET($prm) // GOOD
	{
		$SO_NUM 	= $prm['SO_NUM'];		
		$ITM_CODE 	= $prm['ITM_CODE'];
		$ITM_QTY 	= $prm['ITM_QTY'];
		$ITM_PRICE 	= $prm['ITM_PRICE'];
		$ITM_TOTAL	= $prm['ITM_TOTAL'];
		$PRJCODE	= $prm['PRJCODE'];
		
		// Kembalikan di tabel SO
			$sqlSO	= "UPDATE tbl_so_detail SET JO_VOLM = JO_VOLM + $ITM_QTY, JO_PRICE = $ITM_PRICE,
							JO_AMOUNT = PROD_AMOUNT + $ITM_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND SO_NUM = '$SO_NUM'";
			$this->db->query($sqlSO);
			
		// Kembalikan di tabel Item
			$sqlITM	= "UPDATE tbl_item SET JO_VOLM = JO_VOLM + $ITM_QTY, JO_AMOUNT = JO_AMOUNT + $ITM_TOTAL
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlITM);
	}
	
	function updateSOH($SO_NUM, $PRJCODE) // GOOD
	{
		$sqlSOH	= "UPDATE tbl_so_header SET JO_CREATED = 1
					WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sqlSOH);
	}
	
	function count_all_prim($PRJCODE, $SO_NUM, $PRODS_STEP) // GOOD
	{
		$CCAL_NUM 		= '';
		$sqlSOH 		= "SELECT CCAL_NUM FROM tbl_so_header WHERE SO_NUM = '$SO_NUM'";
		$resSOH 		= $this->db->query($sqlSOH)->result();
		foreach($resSOH as $rowSOH) :
			$CCAL_NUM 	= $rowSOH->CCAL_NUM;
		endforeach;
		
		// AMBIL DARI ITEM CALCULATION
		$sql		= "tbl_ccal_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.CCAL_NUM = '$CCAL_NUM'";	
		return $this->db->count_all($sql);
	}
	
	function view_all_prim($PRJCODE, $SO_NUM, $PRODS_STEP) // GOOD
	{
		$CCAL_NUM 		= '';
		$sqlSOH 		= "SELECT CCAL_NUM FROM tbl_so_header WHERE SO_NUM = '$SO_NUM'";
		$resSOH 		= $this->db->query($sqlSOH)->result();
		foreach($resSOH as $rowSOH) :
			$CCAL_NUM 	= $rowSOH->CCAL_NUM;
		endforeach;
		
		$sql		= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE,
							A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_QTY, A.ITM_TOTAL,
							A.MR_VOLM, A.MR_AMOUNT, A.IRM_VOLM, A.IRM_AMOUNT, A.USED_VOLM, A.USED_AMOUNT,
							B.ITM_TYPE, B.ITM_NAME, B.ITM_CODE_H, B.ITM_GROUP
						FROM tbl_ccal_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE' AND A.ITM_CATEG != 'OTH' AND A.CCAL_NUM = '$CCAL_NUM'";
		return $this->db->query($sql);
	}
	
	function count_all_subs($PRJCODE, $SO_NUM, $PRODS_STEP) // GOOD
	{
		$sql		= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')";
		return $this->db->count_all($sql);
	}
	
	function view_all_subs($PRJCODE, $SO_NUM, $PRODS_STEP) // GOOD
	{
		$sql	= "SELECT DISTINCT '' AS JOBCODEDET, '' AS JOBCODEID, '' AS JOBPARENT, '' AS JOBCODE, 
						PRJCODE, ITM_CODE, ITM_UNIT, ITM_PRICE, 0 AS ITM_QTY, 0 AS ITM_TOTAL,
						MR_VOLM, MR_AMOUNT, IR_VOLM AS IRM_VOLM, IR_AMOUNT AS IRM_AMOUNT, ITM_OUT AS USED_VOLM, ITM_OUTP AS USED_AMOUNT,
						ITM_TYPE, ITM_NAME, ITM_CODE_H, ITM_GROUP
					FROM tbl_item
						WHERE PRJCODE = '$PRJCODE' AND ITM_GROUP IN ('M','T')";
		return $this->db->query($sql);
	}
	
	function count_all_CUST($PRJCODE) // H
	{
		$sql 	= "tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function get_all_CUST($PRJCODE) // H
	{
		$sql	= "SELECT A.CUST_CODE, B.CUST_DESC, B.CUST_ADD1
					FROM tbl_so_header A
						INNER JOIN tbl_customer B ON A.CUST_CODE = B.CUST_CODE
					WHERE A.PRJCODE = '$PRJCODE' AND A.SO_STAT = 3";
		return $this->db->query($sql);
	}
	
	function count_all_item($PRJCODE) // OK
	{
		$sql	= "tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ISFG = 1";
		return $this->db->query($sql);
	}
	
	function get_Rate($SO_CURR) // OK
	{
		$RATE		= 1;
		$sqlRate 	= "SELECT RATE FROM tbl_currate WHERE CURR1 = '$SO_CURR' AND CURR2 = 'IDR'";
		$resRate	= $this->db->query($sqlRate)->result();
		foreach($resRate as $rowRate) :
			$RATE = $rowRate->RATE;		
		endforeach;
		return $RATE;
	}
	
	function updateVolBud($SO_NUM, $PRJCODE, $ITM_CODE) // HOLD
	{
		$SO_VOLM 	= 0;
		$SO_PRICE 	= 0;
		$sqlGetPO	= "SELECT SO_VOLM, SO_PRICE, PROD_VOLM, PROD_PRICE
						FROM tbl_so_detail
						WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SO_VOLM 		= $rowPO->SO_VOLM;
			$SO_PRICE 		= $rowPO->SO_PRICE;
			$SO_AMOUNT		= $SO_VOLM * $SO_PRICE;
			$PROD_VOLM 		= $rowPO->PROD_VOLM;
			$PROD_PRICE	 	= $rowPO->PROD_PRICE;
			$PROD_AMOUNT	= $PROD_VOLM * $PROD_PRICE;
		endforeach;
		
		$REM_SOVOLM		= $SO_VOLM - $PROD_VOLM;
		$REM_SOAMOUNT	= $SO_AMOUNT - $PROD_AMOUNT;
	}
	
	function get_ro_by_number($SO_NUM) // OK
	{			
		$sql = "SELECT A.SO_NUM, A.JO_CODE, A.SO_TYPE, A.SO_CAT, A.SO_DATE, A.SO_DUED, A.PRJCODE, A.SPLCODE, 
					A.PR_NUM, A.SO_CURR, A.SO_CURRATE, A.SO_PAYTYPE, A.SO_TENOR,
					A.SO_PLANIR, A.SO_NOTES, A.SO_NOTES1, A.SO_MEMO, A.SO_STAT, A.SO_TOTCOST, A.SO_TERM,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, A.SO_RECEIVLOC, A.SO_RECEIVCP, A.SO_SENTROLES, 
					A.SO_REFRENS, C.WO_CODE,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_jo_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					LEFT JOIN tbl_wo_header C ON A.PR_NUM = C.WO_NUM
				WHERE A.SO_NUM = '$SO_NUM' AND A.ISREPROC = 1";
		return $this->db->query($sql);
	}
	
	function updatePOInb($SO_NUM, $updPO) // OK
	{
		$this->db->where('SO_NUM', $SO_NUM);
		$this->db->update('tbl_jo_header', $updPO);
	}
	
	function updatePODet($SO_NUM, $PRJCODE, $PR_NUM, $ISDIRECT) // OK
	{				
		$sqlGetPO	= "SELECT SO_NUM, PR_NUM, JOBCODEDET, JOBCODEID, PRD_ID, ITM_CODE, SO_VOLM, SO_PRICE
						FROM tbl_SO_detail
						WHERE SO_NUM = '$SO_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPO	= $this->db->query($sqlGetPO)->result();
		foreach($resGetPO as $rowPO) :
			$SO_NUM 		= $rowPO->SO_NUM;
			$PR_NUM 		= $rowPO->PR_NUM;
			$JOBCODEDET		= $rowPO->JOBCODEDET;
			$JOBCODEID		= $rowPO->JOBCODEID;
			$PRD_ID			= $rowPO->PRD_ID;
			$ITM_CODE		= $rowPO->ITM_CODE;
			$SO_VOLM_NOW	= $rowPO->SO_VOLM;
			$SO_PRICE_NOW	= $rowPO->SO_PRICE;
			$SO_COST_NOW	= $SO_VOLM_NOW * $SO_PRICE_NOW;
			
			$ITM_CODE_H	= $ITM_CODE;
			$ITM_TYPE	= 'PRM';
			$sqlITYPE	= "SELECT ITM_CODE_H, ITM_TYPE FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resITYPE	= $this->db->query($sqlITYPE)->result();
			foreach($resITYPE as $rowITYPE) :
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			endforeach;
			if($ITM_TYPE == 'SUBS')
			{
				$ITM_CODE_H	= $rowITYPE->ITM_CODE_H;
				$ITM_TYPE 	= $rowITYPE->ITM_TYPE;
			}
			
			if($ISDIRECT == 0)
			{
				// UPDATE PR DETAIL
				$SO_VOLM	= 0;
				$SO_AMOUNT	= 0;			
				$sqlGetPRD	= "SELECT SO_VOLM, SO_AMOUNT FROM tbl_pr_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
									AND PR_NUM = '$PR_NUM' AND ID = $PRD_ID";									
				$resGetPRD	= $this->db->query($sqlGetPRD)->result();
				foreach($resGetPRD as $rowPRD) :
					$SO_VOLM 	= $rowPRD->SO_VOLM;
					$SO_AMOUNT 	= $rowPRD->SO_AMOUNT;
				endforeach;
				if($SO_VOLM == '')
					$SO_VOLM = 0;
				if($SO_AMOUNT == '')
					$SO_AMOUNT = 0;
				
				$totPOQty		= $SO_VOLM + $SO_VOLM_NOW;
				$totPOAmount	= $SO_AMOUNT + $SO_COST_NOW;
				/*$sqlUpd			= "UPDATE tbl_pr_detail SET SO_VOLM = $totPOQty, SO_AMOUNT = $totPOAmount
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE' 
										AND PR_NUM = '$PR_NUM'";*/
				$sqlUpd			= "UPDATE tbl_pr_detail SET SO_VOLM = $totPOQty, SO_AMOUNT = $totPOAmount
									WHERE ID = $PRD_ID";
				$this->db->query($sqlUpd);
				
				// UPDATE JOB HEADER
				$SO_VOLM	= 0;
				$SO_AMOUNT	= 0;
				$sqlGetJD	= "SELECT SO_VOLM, SO_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				$resGetJD	= $this->db->query($sqlGetJD)->result();
				foreach($resGetJD as $rowJD) :
					$SO_VOLM 	= $rowJD->SO_VOLM;
					$SO_AMOUNT	= $rowJD->SO_AMOUNT;
				endforeach;
				if($SO_VOLM == '')
					$SO_VOLM = 0;
				if($SO_AMOUNT == '')
					$SO_AMOUNT = 0;
				
				$totJDQty		= $SO_VOLM + $SO_VOLM_NOW;
				$totJDAmount	= $SO_AMOUNT + $SO_COST_NOW;
				$sqlUpdJD		= "UPDATE tbl_joblist_detail SET SO_VOLM = $totJDQty, SO_AMOUNT = $totJDAmount
									WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
				//$this->db->query($sqlUpdJD); HIDDEN BY DIAN 10 JULI 2018
				
				// UPDATE JOB DETAIL PER ITEM
				$SO_VOLM1	= 0;
				$SO_AMOUNT1	= 0;
				$sqlGetJD1	= "SELECT SO_VOLM, SO_AMOUNT FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H'";
				$resGetJD1	= $this->db->query($sqlGetJD1)->result();
				foreach($resGetJD1 as $rowJD1) :
					$SO_VOLM1 	= $rowJD1->SO_VOLM;
					$SO_AMOUNT1	= $rowJD1->SO_AMOUNT;
				endforeach;
				if($SO_VOLM1 == '')
					$SO_VOLM1 = 0;
				if($SO_AMOUNT1 == '')
					$SO_AMOUNT1 = 0;
				
				$totJDQty1		= $SO_VOLM1 + $SO_VOLM_NOW;
				$totJDAmount1	= $SO_AMOUNT1 + $SO_COST_NOW;
				$sqlUpdJD1		= "UPDATE tbl_joblist_detail SET SO_VOLM = $totJDQty1, SO_AMOUNT = $totJDAmount1
									WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE_H'";
				$this->db->query($sqlUpdJD1);
			}
			
			// UPDATE TBL_ITEM
			$SO_VOLM1		= 0;
			$SO_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT SO_VOLM, SO_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$SO_VOLM1 	= $rowJD1->SO_VOLM;
				$SO_AMOUNT1	= $rowJD1->SO_AMOUNT;
			endforeach;
			if($SO_VOLM1 == '')
				$SO_VOLM1 = 0;
			if($SO_AMOUNT1 == '')
				$SO_AMOUNT1 = 0;
				
			$totPOQty1	= $SO_VOLM1 + $SO_VOLM_NOW;
			$totPOAmn1	= $SO_AMOUNT1 + $SO_COST_NOW;
			$sqlUpd2	= "UPDATE tbl_item SET SO_VOLM = $totPOQty1, SO_AMOUNT = $totPOAmn1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
		
		if($ISDIRECT == 0)
		{
			// CEK TOTAL PR AND PO			
			$TOT_PRQTY 		= 0;
			$TOT_PRAMOUNT	= 0;
			$TOT_POQTY 		= 0;
			$TOT_POAMOUNT 	= 0;	
			$sqlGetPRCV		= "SELECT SUM(PR_VOLM) AS TOT_PRQTY, SUM(PR_TOTAL) AS TOT_PRAMOUNT, SUM(SO_VOLM) AS TOT_POQTY,
									SUM(SO_AMOUNT) AS TOT_POAMOUNT
								FROM tbl_pr_detail
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPRCV	= $this->db->query($sqlGetPRCV)->result();
			foreach($resGetPRCV as $rowPRCV) :
				$TOT_PRQTY 		= $rowPRCV->TOT_PRQTY;
				$TOT_PRAMOUNT	= $rowPRCV->TOT_PRAMOUNT;
				$TOT_POQTY 		= $rowPRCV->TOT_POQTY;
				$TOT_POAMOUNT 	= $rowPRCV->TOT_POAMOUNT;			
			endforeach;
			//if(($TOT_PRQTY == $TOT_POQTY) && $TOT_PRQTY > 0)
			if($TOT_POQTY >= $TOT_PRQTY)
			{
				$sqlUpdPR	= "UPDATE tbl_pr_header SET PR_STAT = 6, PR_ISCLOSE = 1
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPR);
			}
			//if(($TOT_PRAMOUNT == $TOT_POAMOUNT) && $TOT_PRAMOUNT > 0)
			if($TOT_POAMOUNT >= $TOT_PRAMOUNT)
			{
				$sqlUpdPR	= "UPDATE tbl_pr_header SET PR_STAT = 6, PR_ISCLOSE = 1
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpdPR);
			}
			
			$sqlGetPRDet	= "SELECT ITM_CODE, PR_VOLM, PR_VOLM * PR_PRICE AS TOT_PR_AMOUNT, SO_VOLM, SO_AMOUNT AS TOT_SO_AMOUNT 
								FROM tbl_pr_detail
								WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
			$resGetPRDet	= $this->db->query($sqlGetPRDet)->result();
			foreach($resGetPRDet as $rowPRDet) :
				$ITM_CODE 		= $rowPRDet->ITM_CODE;
				$PR_VOLM 		= $rowPRDet->PR_VOLM;
				$TOT_PRAMN		= $rowPRDet->TOT_PR_AMOUNT;
				$SO_VOLM 		= $rowPRDet->SO_VOLM;
				$TOT_POAMN		= $rowPRDet->TOT_SO_AMOUNT;
				if($PR_VOLM == $PR_VOLM)
				{
					$sqlUpdPRD	= "UPDATE tbl_pr_detail SET ISCLOSE = 1 WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
				if($TOT_PRAMN == $TOT_POAMN)
				{
					$sqlUpdPRD	= "UPDATE tbl_pr_detail SET ISCLOSE = 1 WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}			
			endforeach;			
			
			// SETING
			$TBL_NAME		= "tbl_pr_header";
			$STAT_NAME		= "PR_STAT";
			$FIELD_NM_A		= "TOT_REQ_A";
			$FIELD_NM_R		= "TOT_REQ_R";
			$FIELD_NM_RJ	= "TOT_REQ_RJ";
			$FIELD_NM_CL	= "TOT_REQ_CL";
			
			// SUM TOTAL APPROVE = 3
			$TOTAL_APP	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 3";
			$TOTAL_APP	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL REVISE = 4
			$TOTAL_REV	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 4";
			$TOTAL_REV	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL REJECT = 5
			$TOTAL_REJ	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 5";
			$TOTAL_REJ	= $this->db->count_all($selTOTA);
			
			// SUM TOTAL CLOSE = 6
			$TOTAL_CLO	= 0;
			$selTOTA 	= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = 6";
			$TOTAL_CLO	= $this->db->count_all($selTOTA);
			
			// UPDATE DASHBOARD
			$sqlUpd1Dash		= "UPDATE tbl_dash_transac SET $FIELD_NM_A = $TOTAL_APP, $FIELD_NM_R = $TOTAL_REV,
										$FIELD_NM_RJ = $TOTAL_REJ, $FIELD_NM_CL = $TOTAL_CLO
									WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($sqlUpd1Dash);
		}
		else // IF DIRECT
		{
			$sqlGetPRDet	= "SELECT ITM_CODE, ITM_VOLM, ITM_VOLM * ITM_PRICE AS TOT_AMOUNT, SO_VOLM, SO_AMOUNT FROM tbl_joblist_detail
								WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetPRDet	= $this->db->query($sqlGetPRDet)->result();
			foreach($resGetPRDet as $rowPRDet) :
				$ITM_CODE 		= $rowPRDet->ITM_CODE;
				$ITM_VOLM 		= $rowPRDet->ITM_VOLM;
				$TOT_AMOUNT		= $rowPRDet->TOT_AMOUNT;
				$SO_VOLM 		= $rowPRDet->SO_VOLM;
				$SO_AMOUNT		= $rowPRDet->SO_AMOUNT;
				if(($ITM_VOLM == $SO_VOLM) && $ITM_VOLM > 0)
				{
					$sqlUpdPRD	= "UPDATE tbl_joblist_detail SET ISCLOSE = 1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
				if(($TOT_AMOUNT == $SO_AMOUNT) && TOT_AMOUNT > 0)
				{
					$sqlUpdPRD	= "UPDATE tbl_joblist_detail SET ISCLOSE = 1 WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
					$this->db->query($sqlUpdPRD);
				}
			endforeach;
		}
	}
	
	function count_all_IR($SO_NUM) // OK
	{
		$sql	= "tbl_ir_header A
						INNER JOIN tbl_jo_header B ON A.SO_NUM = B.SO_NUM
							AND B.SO_STAT IN (3,6) AND B.ISREPROC = 1
						INNER JOIN tbl_customer C ON A.SPLCODE = C.SPLCODE
					WHERE A.SO_NUM = '$SO_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_IR($SO_NUM) // OK
	{
		$sql 	= "SELECT A.IR_NUM, A.IR_DATE, A.IR_DUEDATE, A.SPLCODE, C.SPLDESC
					FROM tbl_ir_header A
						INNER JOIN tbl_jo_header B ON A.SO_NUM = B.SO_NUM
							AND B.SO_STAT IN (3,6) AND B.ISREPROC = 1
						INNER JOIN tbl_customer C ON A.SPLCODE = C.SPLCODE
					WHERE A.SO_NUM = '$SO_NUM' AND A.IR_STAT IN (3,6)";
		return $this->db->query($sql);
	}
	
	function getMyMaxLimit($MenuCode, $DefEmp_ID)
	{
		$APPLIMIT_1		= 0;
		$APPLIMIT_2		= 0;
		$APPLIMIT_3		= 0;
		$APPLIMIT_4		= 0;
		$APPLIMIT_5		= 0;
		$sqlAPP	= "SELECT * FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode'";
		$resAPP	= $this->db->query($sqlAPP)->result();
		foreach($resAPP as $rowAPP) :
			$MAX_STEP		= $rowAPP->MAX_STEP;			
			$APPROVER_1		= $rowAPP->APPROVER_1;
			$APPROVER_2		= $rowAPP->APPROVER_2;
			$APPROVER_3		= $rowAPP->APPROVER_3;
			$APPROVER_4		= $rowAPP->APPROVER_4;
			$APPROVER_5		= $rowAPP->APPROVER_5;
			
			$APPLIMIT_1		= $rowAPP->APPLIMIT_1;
			$APPLIMIT_2		= $rowAPP->APPLIMIT_2;
			$APPLIMIT_3		= $rowAPP->APPLIMIT_3;
			$APPLIMIT_4		= $rowAPP->APPLIMIT_4;
			$APPLIMIT_5		= $rowAPP->APPLIMIT_5;
		endforeach;
		
		$myMaxLIMIT	= 0;
		$sqlCAPP_1	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'";
		$resCAPP_1	= $this->db->count_all($sqlCAPP_1);
		if($resCAPP_1 > 0)
		{
			$sqlAPP_1	= "SELECT APPLIMIT_1 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_1 = '$DefEmp_ID'";
			$resAPP_1	= $this->db->query($sqlAPP_1)->result();
			foreach($resAPP_1 as $rowAPP_1) :
				$APPLIMIT_1	= $rowAPP_1->APPLIMIT_1;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_1;
			$RangeApp_a	= 0;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_2	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_2 = '$DefEmp_ID'";
		$resCAPP_2	= $this->db->count_all($sqlCAPP_2);
		if($resCAPP_2 > 0)
		{
			$sqlAPP_2	= "SELECT APPLIMIT_2 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_2 = '$DefEmp_ID'";
			$resAPP_2	= $this->db->query($sqlAPP_2)->result();
			foreach($resAPP_2 as $rowAPP_2) :
				$APPLIMIT_2	= $rowAPP_2->APPLIMIT_2;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_2;
			$RangeApp_a	= $APPLIMIT_1;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_3	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_3 = '$DefEmp_ID'";
		$resCAPP_3	= $this->db->count_all($sqlCAPP_3);
		if($resCAPP_3 > 0)
		{
			$sqlAPP_3	= "SELECT APPLIMIT_3 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_3 = '$DefEmp_ID'";
			$resAPP_3	= $this->db->query($sqlAPP_3)->result();
			foreach($resAPP_3 as $rowAPP_3) :
				$APPLIMIT_3	= $rowAPP_3->APPLIMIT_3;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_3;
			$RangeApp_a	= $APPLIMIT_2;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_4	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_4 = '$DefEmp_ID'";
		$resCAPP_4	= $this->db->count_all($sqlCAPP_4);
		if($resCAPP_4 > 0)
		{
			$sqlAPP_4	= "SELECT APPLIMIT_4 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_4 = '$DefEmp_ID'";
			$resAPP_4	= $this->db->query($sqlAPP_4)->result();
			foreach($resAPP_4 as $rowAPP_4) :
				$APPLIMIT_4	= $rowAPP_4->APPLIMIT_4;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_4;
			$RangeApp_a	= $APPLIMIT_3;
			$RangeApp_b	= $myMaxLIMIT;
		}
		
		$sqlCAPP_5	= "tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_5 = '$DefEmp_ID'";
		$resCAPP_5	= $this->db->count_all($sqlCAPP_5);
		if($resCAPP_5 > 0)
		{
			$sqlAPP_5	= "SELECT APPLIMIT_5 FROM tbl_docstepapp WHERE MENU_CODE = '$MenuCode' AND APPROVER_5 = '$DefEmp_ID'";
			$resAPP_5	= $this->db->query($sqlAPP_5)->result();
			foreach($resAPP_5 as $rowAPP_5) :
				$APPLIMIT_5	= $rowAPP_5->APPLIMIT_5;
			endforeach;
			$myMaxLIMIT	= $APPLIMIT_5;
			$RangeApp_a	= $APPLIMIT_4;
			$RangeApp_b	= $myMaxLIMIT;
		}
		$RangeApp		= "$RangeApp_a~$RangeApp_b";
		
		return $RangeApp;
	}
}
?>