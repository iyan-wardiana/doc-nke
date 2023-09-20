<?php
	/*
		* Author		= Dian Hermanto
		* Create Date	= 11 Desember 2017
		* File Name	= M_itemusage.php
		* Location		= -
	*/

	class m_itemusage extends CI_Model
	{
		public function __construct() // GOOD
		{
			parent::__construct();
			$this->load->database();
		}
				
		function get_AllDataC($PRJCODE, $search) // GOOD
		{
			$sql = "tbl_um_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
						OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
			
		function count_all_UM($PRJCODE, $key) // G
		{
			if($key == '')
			{
				$sql = "tbl_um_header WHERE PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql = "tbl_um_header WHERE PRJCODE = '$PRJCODE'
							AND (UM_CODE LIKE '%$key%' ESCAPE '!' OR UM_DATE LIKE '%$key%' ESCAPE '!' 
							OR UM_NOTE LIKE '%$key%' ESCAPE '!' OR UM_NOTE2 LIKE '%$key%' ESCAPE '!')";
			}
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_UM($PRJCODE, $start, $end, $key) // G
		{
			if($key == '')
			{
				$sql = "SELECT A.*,
							B.First_Name, B.Middle_Name, B.Last_Name
						FROM tbl_um_header A
							LEFT JOIN	tbl_employee B ON A.UM_CREATER = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'";
			}
			else
			{
				$sql = "SELECT A.*,
							B.First_Name, B.Middle_Name, B.Last_Name
						FROM tbl_um_header A
							LEFT JOIN	tbl_employee B ON A.UM_CREATER = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (UM_CODE LIKE '%$key%' ESCAPE '!' OR UM_DATE LIKE '%$key%' ESCAPE '!' 
							OR UM_NOTE LIKE '%$key%' ESCAPE '!' OR UM_NOTE2 LIKE '%$key%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		
		function count_all_num_rowsVend() // G
		{
			$sql = "tbl_supplier WHERE SPLSTAT = '1'";
			return $this->db->count_all($sql);
		}
		
		function viewvendor() // G
		{
			$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
					FROM tbl_supplier WHERE SPLSTAT = '1'";
			return $this->db->query($sql);
		}
		
		function getDataDocPat($MenuCode) // G
		{
			$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
			$this->db->from('tbl_docpattern');
			$this->db->where('menu_code', $MenuCode);
			return $this->db->get();
		}
		
		function add($insUM) // G
		{
			$this->db->insert('tbl_um_header', $insUM);
		}
		
		function get_um_by_number($UM_NUM) // G
		{		
			$sql = "SELECT A.*
					FROM tbl_um_header A
					WHERE 
						A.UM_NUM = '$UM_NUM' LIMIT 1";
			return $this->db->query($sql);
		}
		
		function updateUM($UM_NUM, $updUM) // G
		{
			$this->db->where('UM_NUM', $UM_NUM);
			$this->db->update('tbl_um_header', $updUM);
		}
		
		function deleteUMDetail($UM_NUM) // G
		{
			$this->db->where('UM_NUM', $UM_NUM);
			$this->db->delete('tbl_um_detail');
		}
		
		function count_allItem($PRJCODE) // G
		{
			$sql		= "tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND B.ITM_IN > B.ITM_OUT AND A.ITM_GROUP IN ('M','T')";
			return $this->db->count_all($sql);
		}
		
		function viewAllItem($PRJCODE) // G
		{
			/*$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_TYPE, Z.ITM_UNIT, Z.UMCODE, Z.ITM_VOLM,
								Z.ITM_PRICE, Z.ITM_TOTALP,
								Z.ITM_IN, Z.ITM_OUT, Z.ACC_ID,
								B.Unit_Type_Name
							FROM tbl_item Z
								INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
							WHERE Z.PRJCODE = '$PRJCODE'
								AND Z.ISPART = 1 OR Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISMTRL = 1
							ORDER BY Z.ITM_CODE";*/
			
			/*$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE, A.ITM_CODE, A.ITM_UNIT, 
								A.ITM_VOLM, A.ITM_PRICE, A.ITM_PRICE, B.ITM_LASTP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
								A.ITM_BUDG, B.ACC_ID,
								B.ITM_NAME, B.ITM_IN, B.ITM_OUT, B.ITM_AVGP
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.ITM_STOCK > 0";*/
			$sql		= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE, B.ITM_CODE, A.ITM_UNIT, 
								A.ITM_VOLM, A.ITM_PRICE, A.ITM_PRICE, B.ITM_LASTP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
								A.ITM_BUDG, B.ACC_ID,
								B.ITM_NAME, B.ITM_IN, B.ITM_OUT, B.ITM_AVGP
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND B.ITM_IN > B.ITM_OUT AND B.ITM_GROUP IN ('M','T')
							UNION ALL
							SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.PRJCODE, B.ITM_CODE, A.ITM_UNIT, 
								A.ITM_VOLM, A.ITM_PRICE, A.ITM_PRICE, B.ITM_LASTP,
								A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT, A.ITM_USED, A.ITM_STOCK, 
								A.ITM_BUDG, B.ACC_ID,
								B.ITM_NAME, B.ITM_IN, B.ITM_OUT, B.ITM_AVGP
							FROM tbl_joblist_detail A
							INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE_H
								AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND B.ITM_IN > B.ITM_OUT AND B.ITM_GROUP IN ('M','T') 
								AND B.ITM_CODE_H IS NOT NULL
								AND B.ITM_CODE_H != ''";
			return $this->db->query($sql);
		}
				
		function get_AllDataC_1n2($PRJCODE, $search) // GOOD
		{
			$sql = "tbl_um_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
						OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.UM_STAT IN (2,7)
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
				}
				else
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.UM_STAT IN (2,7)
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
				}
				return $this->db->query($sql);
			}
			else
			{
				if($order !=null)
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.UM_STAT IN (2,7)
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT A.UM_NUM, A.UM_CODE, A.UM_DATE, A.PRJCODE, A.JOBCODEID, B.JOBDESC, A.UM_NOTE, A.UM_STAT, A.REVMEMO,
								A.STATDESC, A.STATCOL, A.CREATERNM, A.ISVOID
							FROM tbl_um_header A
								LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
									AND B.PRJCODE = '$PRJCODE'
							WHERE A.PRJCODE = '$PRJCODE'
								AND A.UM_STAT IN (2,7)
								AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
								OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		
		function count_all_UM_OUT($PRJCODE, $key, $DefEmp_ID) // G
		{
			if($key == '')
			{
				$sql = "tbl_um_header WHERE PRJCODE = '$PRJCODE'
						AND UM_STAT IN (2,7)";
			}
			else
			{
				$sql = "tbl_um_header WHERE PRJCODE = '$PRJCODE'
						AND UM_STAT IN (2,7)
							AND (UM_CODE LIKE '%$key%' ESCAPE '!' OR UM_DATE LIKE '%$key%' ESCAPE '!' 
							OR UM_NOTE LIKE '%$key%' ESCAPE '!' OR UM_NOTE2 LIKE '%$key%' ESCAPE '!')";
			}
			return $this->db->count_all($sql);
		}
		
		function get_all_UM_OUT($PRJCODE, $start, $end, $key, $DefEmp_ID) // G
		{
			if($key == '')
			{
				$sql = "SELECT A.*,
							B.First_Name, B.Middle_Name, B.Last_Name
						FROM tbl_um_header A
							LEFT JOIN	tbl_employee B ON A.UM_CREATER = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.UM_STAT IN (2,7) LIMIT $start, $end";
			}
			else
			{
				$sql = "SELECT A.*,
							B.First_Name, B.Middle_Name, B.Last_Name
						FROM tbl_um_header A
							LEFT JOIN	tbl_employee B ON A.UM_CREATER = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.UM_STAT IN (2,7) LIMIT 
							AND (UM_CODE LIKE '%$key%' ESCAPE '!' OR UM_DATE LIKE '%$key%' ESCAPE '!' 
							OR UM_NOTE LIKE '%$key%' ESCAPE '!' OR UM_NOTE2 LIKE '%$key%' ESCAPE '!') LIMIT $start, $end";
			}
			return $this->db->query($sql);
		}
		
		function updateITM_Min($parameters) // G
		{
			date_default_timezone_set("Asia/Jakarta");
														
	    	$PRJCODE 	= $parameters['PRJCODE'];
	    	$WH_CODE 	= $parameters['WH_CODE'];
	    	$JOBCODEID 	= $parameters['JOBCODEID'];
			$UM_NUM 	= $parameters['UM_NUM'];
	    	$UM_CODE 	= $parameters['UM_CODE'];
	    	$ITM_CODE 	= $parameters['ITM_CODE'];
	    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
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
			$StartOUT		= 0;
			$StartOUTP		= 0;
			$RET_VOLM		= 0;
			$RET_AMOUNT		= 0;
			$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_OUT, ITM_OUTP
								FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
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
				$StartOUT 		= $rowSITM->ITM_OUT;		// like as Last Total IN
				$StartOUTP 		= $rowSITM->ITM_OUTP;		// like as Last Total IN Price
			endforeach;
			
			$EndVOL			= $StartVOL - $ITM_QTY;			// New End Volume = Last Stock
			if($EndVOL == 0 || $EndVOL == '')
				$EndVOL		= 1;
			$EndTPRC		= $StartTPRC - $ITM_TOTALP;		// New End Amount
			$EndPRC			= $EndTPRC / $EndVOL;			// Last Price Average
			$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order
			
			$EndOUT			= $StartOUT + $ITM_QTY;
			$EndOUTP		= $StartOUTP + $ITM_TOTALP;
			$ITMREMQTY		= $ITM_REMQTY - $ITM_QTY;
			
			// UPDATE DETAIL
			$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_PRICE = $EndPRC, ITM_TOTALP = ITM_TOTALP - $ITM_TOTALP,
								ITM_REMQTY = ITM_REMQTY - $ITM_QTY, ITM_LASTP = $ITM_PRICE,
								ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP, LAST_TRXNO = '$UM_NUM'
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpDet);
			
			// UPDATE WH_QTY
				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$resWHC	= $this->db->count_all($sqlWHC);
				
				if($resWHC > 0)
				{
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITM_QTY,
										ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
					$this->db->query($sqlUpWH);
				}
			
			// UPDATE JOBD DETAIL
			$sqlJDITMC		= "tbl_joblist_detail WHERE JOBCODEID  = 'JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$resJDITMC		= $this->db->count_all($sqlJDITMC);
			
			$StartSTOCK		= 0;
			$EndSTOCK		= 0;
			$EndSTOCK_AM	= 0;
			$EndRET			= 0;
			$EndRET_AM		= 0;
			$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM, ITM_RET, ITM_RET_AM, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail 
									WHERE JOBCODEID  = 'JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
			$resStartJITM	= $this->db->query($sqlStartJITM)->result();
			foreach($resStartJITM as $rowSJITM) :
				$StartSTOCK		= $rowSJITM->ITM_STOCK;
				$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
				$StartUM		= $rowSJITM->ITM_USED;
				$StartUM_AM		= $rowSJITM->ITM_USED_AM;
			endforeach;
			
			//$EndSTOCK			= $StartSTOCK - $ITM_QTY;
			//$EndSTOCK_AM		= $StartSTOCK_AM - $ITM_TOTALP;
			//$EndUM				= $StartUM + $ITM_QTY;
			//$EndUM_AM			= $StartUM_AM + $ITM_TOTALP;
			
			if($resJDITMC > 0)
			{
				$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK - $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM - $ITM_TOTALP,
										ITM_USED = ITM_USED + $ITM_QTY, ITM_USED_AM = ITM_USED_AM + $ITM_TOTALP
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
				$this->db->query($sqlUpDetJ);
			}
			else
			{
				$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK - $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM - $ITM_TOTALP,
										ITM_USED = ITM_USED + $ITM_QTY, ITM_USED_AM = ITM_USED_AM + $ITM_TOTALP
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE_H' AND PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpDetJ);
			}		
		}
		
		function updateITM_Plus($parameters) // G
		{
			date_default_timezone_set("Asia/Jakarta");
														
	    	$PRJCODE 	= $parameters['PRJCODE'];
	    	$WH_CODE 	= $parameters['WH_CODE'];
	    	$JOBCODEID 	= $parameters['JOBCODEID'];
			$UM_NUM 	= $parameters['UM_NUM'];
	    	$UM_CODE 	= $parameters['UM_CODE'];
	    	$ITM_CODE 	= $parameters['ITM_CODE'];
	    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
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
			$StartOUT		= 0;
			$StartOUTP		= 0;
			$RET_VOLM		= 0;
			$RET_AMOUNT		= 0;
			$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_OUT, ITM_OUTP
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
				$StartOUT 		= $rowSITM->ITM_OUT;		// like as Last Total IN
				$StartOUTP 		= $rowSITM->ITM_OUTP;		// like as Last Total IN Price
			endforeach;
			
			$EndVOL			= $StartVOL + $ITM_QTY;			// New End Volume = Last Stock
			if($EndVOL == 0 || $EndVOL == '')
				$EndVOL		= 1;
			$EndTPRC		= $StartTPRC + $ITM_TOTALP;		// New End Amount
			//$EndPRC			= $EndTPRC / $EndVOL;		// Last Price Average
			$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order
			$EndAVG			= $EndTPRC / $EndVOL;
			
			$EndOUT			= $StartOUT - $ITM_QTY;
			$EndOUTP		= $StartOUTP - $ITM_TOTALP;
			$ITMREMQTY		= $ITM_REMQTY + $ITM_QTY;
			
			// UPDATE DETAIL
			$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $EndPRC, ITM_TOTALP = ITM_TOTALP + $ITM_TOTALP,
								ITM_REMQTY = ITM_REMQTY + $ITM_QTY, ITM_LASTP = $ITM_PRICE, ITM_AVGP = $EndAVG,
								ITM_OUT = ITM_OUT - $ITM_QTY, ITM_OUTP = ITM_OUTP - $ITM_TOTALP, LAST_TRXNO = '$UM_NUM'
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpDet);
			
			// UPDATE WH_QTY
				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$resWHC	= $this->db->count_all($sqlWHC);
				
				if($resWHC > 0)
				{
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY,
										ITM_OUT = ITM_OUT - $ITM_QTY, ITM_OUTP = ITM_OUTP - $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
					$this->db->query($sqlUpWH);
				}
			
			// UPDATE JOBD DETAIL
				$sqlJDITMC		= "tbl_joblist_detail WHERE JOBCODEID  = 'JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resJDITMC		= $this->db->count_all($sqlJDITMC);
				
				$StartSTOCK		= 0;
				$EndSTOCK		= 0;
				$EndSTOCK_AM	= 0;
				$EndRET			= 0;
				$EndRET_AM		= 0;
				$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM, ITM_RET, ITM_RET_AM, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail 
										WHERE JOBCODEID  = 'JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resStartJITM	= $this->db->query($sqlStartJITM)->result();
				foreach($resStartJITM as $rowSJITM) :
					$StartSTOCK		= $rowSJITM->ITM_STOCK;
					$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
					$StartUM		= $rowSJITM->ITM_USED;
					$StartUM_AM		= $rowSJITM->ITM_USED_AM;
				endforeach;
				
				//$EndSTOCK			= $StartSTOCK - $ITM_QTY;
				//$EndSTOCK_AM		= $StartSTOCK_AM - $ITM_TOTALP;
				//$EndUM				= $StartUM + $ITM_QTY;
				//$EndUM_AM			= $StartUM_AM + $ITM_TOTALP;
				
				if($resJDITMC > 0)
				{
					$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
											ITM_USED = ITM_USED - $ITM_QTY, ITM_USED_AM = ITM_USED_AM - $ITM_TOTALP
										WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
					$this->db->query($sqlUpDetJ);
				}
				else
				{
					$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
											ITM_USED = ITM_USED - $ITM_QTY, ITM_USED_AM = ITM_USED_AM - $ITM_TOTALP
										WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE_H' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpDetJ);
				}		
		}
		
		function updateITM_PlusV($parameters) // G
		{
			date_default_timezone_set("Asia/Jakarta");

	    	$PRJCODE 	= $parameters['PRJCODE'];	
	    	$JD_Date 	= $parameters['JD_Date'];
	    	$WH_CODE 	= $parameters['WH_CODE'];
	    	$JOBCODEID 	= $parameters['JOBCODEID'];
			$UM_NUM 	= $parameters['UM_NUM'];
	    	$UM_CODE 	= $parameters['UM_CODE'];
			$ITM_TYPE 	= $parameters['ITM_TYPE'];
	    	$ITM_CODE 	= $parameters['ITM_CODE'];
	    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_QTY 	= $parameters['ITM_QTY'];
			$ITM_PRICE 	= $parameters['ITM_PRICE'];
			$JOURN_VAL 	= $parameters['JOURN_VAL'];
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
			$StartOUT		= 0;
			$StartOUTP		= 0;
			$RET_VOLM		= 0;
			$RET_AMOUNT		= 0;
			$ITM_CATEG 		= $ITM_GROUP;
			$ITM_LR 		= '';
			$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_OUT, ITM_OUTP,
									ITM_CATEG, ITM_LR
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
				$StartOUT 		= $rowSITM->ITM_OUT;		// like as Last Total IN
				$StartOUTP 		= $rowSITM->ITM_OUTP;		// like as Last Total IN Price
				$ITM_CATEG 		= $rowSITM->ITM_CATEG;
				$ITM_LR 		= $rowSITM->ITM_LR;
			endforeach;
			
			$EndVOL			= $StartVOL + $ITM_QTY;			// New End Volume = Last Stock
			if($EndVOL == 0 || $EndVOL == '')
				$EndVOL		= 1;
			$EndTPRC		= $StartTPRC + $ITM_TOTALP;		// New End Amount
			//$EndPRC			= $EndTPRC / $EndVOL;		// Last Price Average
			$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order
			$EndAVG			= $EndTPRC / $EndVOL;
			
			$EndOUT			= $StartOUT - $ITM_QTY;
			$EndOUTP		= $StartOUTP - $ITM_TOTALP;
			$ITMREMQTY		= $ITM_REMQTY + $ITM_QTY;
			
			// UPDATE DETAIL
				$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM + $ITM_QTY, ITM_PRICE = $EndPRC, ITM_TOTALP = ITM_TOTALP + $ITM_TOTALP,
									ITM_REMQTY = ITM_REMQTY + $ITM_QTY, ITM_LASTP = $ITM_PRICE, ITM_AVGP = $EndAVG,
									ITM_OUT = ITM_OUT - $ITM_QTY, ITM_OUTP = ITM_OUTP - $ITM_TOTALP, LAST_TRXNO = '$UM_NUM'
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
				$this->db->query($sqlUpDet);
				
			// UPDATE WH_QTY
				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$resWHC	= $this->db->count_all($sqlWHC);
				
				if($resWHC > 0)
				{
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM + $ITM_QTY,
										ITM_OUT = ITM_OUT - $ITM_QTY, ITM_OUTP = ITM_OUTP - $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
					$this->db->query($sqlUpWH);
				}
			
			// UPDATE JOBD DETAIL
				$sqlJDITMC		= "tbl_joblist_detail WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resJDITMC		= $this->db->count_all($sqlJDITMC);
				
				$StartSTOCK		= 0;
				$EndSTOCK		= 0;
				$EndSTOCK_AM	= 0;
				$EndRET			= 0;
				$EndRET_AM		= 0;
				$sqlStartJITM	= "SELECT ITM_STOCK, ITM_STOCK_AM, ITM_RET, ITM_RET_AM, ITM_USED, ITM_USED_AM FROM tbl_joblist_detail 
										WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
				$resStartJITM	= $this->db->query($sqlStartJITM)->result();
				foreach($resStartJITM as $rowSJITM) :
					$StartSTOCK		= $rowSJITM->ITM_STOCK;
					$StartSTOCK_AM	= $rowSJITM->ITM_STOCK_AM;
					$StartUM		= $rowSJITM->ITM_USED;
					$StartUM_AM		= $rowSJITM->ITM_USED_AM;
				endforeach;
				
				//$EndSTOCK			= $StartSTOCK - $ITM_QTY;
				//$EndSTOCK_AM		= $StartSTOCK_AM - $ITM_TOTALP;
				//$EndUM				= $StartUM + $ITM_QTY;
				//$EndUM_AM			= $StartUM_AM + $ITM_TOTALP;
				
				if($resJDITMC > 0)
				{
					$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
											ITM_USED = ITM_USED - $ITM_QTY, ITM_USED_AM = ITM_USED_AM - $ITM_TOTALP
										WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
					$this->db->query($sqlUpDetJ);
				}
				else
				{
					$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET ITM_STOCK = ITM_STOCK + $ITM_QTY, ITM_STOCK_AM = ITM_STOCK_AM + $ITM_TOTALP,
											ITM_USED = ITM_USED - $ITM_QTY, ITM_USED_AM = ITM_USED_AM - $ITM_TOTALP
										WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE_H' AND PRJCODE = '$PRJCODE'";
					$this->db->query($sqlUpDetJ);
				}		

			// START : UPDATE L/R
				$PERIODM		= date('m', strtotime($JD_Date));
				$PERIODY		= date('Y', strtotime($JD_Date));
				$transacValue	= $JOURN_VAL;
				$Company_ID		= $this->session->userdata('comp_init');
			
				// PEREKAMAN JEJAK KE tbl_itemhistory
					$sqlHist 		= "INSERT INTO tbl_itemhistory (JournalH_Code, proj_Code, Transaction_Date, Item_Code, Qty_Plus, Qty_Min, 
											QtyRR_Plus, QtyRR_Min, Transaction_Type, Transaction_Value, Company_ID, Currency_ID,
											JOBCODEID, GEJ_STAT, ItemPrice, ItemCategoryType, MEMO)
										VALUES ('$UM_NUM', '$PRJCODE', '$JD_Date', '$ITM_CODE', $ITM_QTY, 0, 
											0, 0, 'V-UM', $transacValue, '$Company_ID', 'IDR', 
											'$JOBCODEID', 9, '$ITM_PRICE', '$ITM_CATEG', 'Pembatalan penggunaan $ITM_CODE')";
					$this->db->query($sqlHist);

				$FIELDVAL 		= $transacValue;
		    	// L/R MANUFACTUR
					if($ITM_LR != '' XOR $ITM_LR != 0)
					{
						$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					
				// L/R CONTRACTOR // ADDING COST OR EXPENS
					if($ITM_GROUP == 'M')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'ADM')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'GE')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'I')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'O')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL-$FIELDVAL
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'T')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
					elseif($ITM_GROUP == 'U')
					{
						$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL-$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
										AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
		        
		        // MIN STOCK ON PROFIT LOSS
			        if($ITM_GROUP == 'M')
					{
						// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFUEL, 5.ISLUBTICANT, 6.ISFASTM, 7.ISWAGE, 8.ISRM, 9.ISWIP, 10.ISFG, 11.ISCOST
						if($ITM_TYPE == 1 || $ITM_TYPE == 8)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM+$FIELDVAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 9)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP+$FIELDVAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_TYPE == 10)
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG+$FIELDVAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
					}
					elseif($ITM_GROUP == 'T')
					{
						$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS+$FIELDVAL 
									WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
						$this->db->query($updLR);
					}
			// END : UPDATE L/R
		}
		
		function getProjName($myLove_the_an) // G
		{
			$sql	= "SELECT PRJNAME FROM tbl_project WHERE PRJCODE = '$myLove_the_an'";
			$ressql = $this->db->query($sql)->result();
			foreach($ressql as $row) :
				$proj_Name = $row->proj_Name;
			endforeach;
			return $proj_Name;
		}
		
		function get_totam($UM_NUM) // G
		{
			$GTOTAM		= 0;
			$sqlTOTAM	= "SELECT ITM_QTY, ITM_PRICE FROM tbl_um_detail WHERE UM_NUM = '$UM_NUM'";
			$resTOTAM	= $this->db->query($sqlTOTAM)->result();
			foreach($resTOTAM as $rowtotam) :
				$ITM_QTY 	= $rowtotam->ITM_QTY; 	// like as Last Volume
				$ITM_PRICE 	= $rowtotam->ITM_PRICE;	// like as Last Price Average
				$TOTAM		= $ITM_QTY * $ITM_PRICE;
				$GTOTAM		= $GTOTAM + $TOTAM;
			endforeach;
			return $GTOTAM;
		}
		
		function updateITM_MinWIP($parameters) // G
		{
			date_default_timezone_set("Asia/Jakarta");

	    	$PRJCODE 	= $parameters['PRJCODE'];
	    	$WH_CODE 	= $parameters['WH_CODE'];
	    	$JOBCODEID 	= $parameters['JOBCODEID'];
			$UM_NUM 	= $parameters['UM_NUM'];
	    	$UM_CODE 	= $parameters['UM_CODE'];
	    	$ITM_CODE 	= $parameters['ITM_CODE'];
	    	$ITM_GROUP 	= $parameters['ITM_GROUP'];
			$ITM_QTY 	= $parameters['ITM_QTY'];
			$ITM_PRICE 	= $parameters['ITM_PRICE'];
			$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
			
			// Mendapatkan Qty Awal dan harga, karena harga harus diambil rata2 total agar balance
			$ITM_CODE_H		= '';
			$ITM_VOLMBG		= 0;
			$StartVOL		= 0;
			$StartPRC		= 0;
			$ITM_REMQTY		= 0;
			$StartTPRC		= 0;
			$StartIN		= 0;
			$StartINP		= 0;
			$StartOUT		= 0;
			$StartOUTP		= 0;
			$RET_VOLM		= 0;
			$RET_AMOUNT		= 0;
			$AVG_PRICE		= 0;
			$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_OUT, ITM_OUTP
								FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
			$resStartITM	= $this->db->query($sqlStartITM)->result();
			foreach($resStartITM as $rowSITM) :
				$ITM_CODE_H 	= $rowSITM->ITM_CODE_H;
				$ITM_VOLMBG		= $rowSITM->ITM_VOLMBG;		// Budget on RAP
				$ITM_VOLMBGR	= $rowSITM->ITM_VOLMBGR;	// Remain of Budget
				$StartVOL 		= $rowSITM->ITM_VOLM; 		// like as Last Volume
				$StartPRC 		= $rowSITM->ITM_PRICE;		// like as Last Price Average
				$ITM_REMQTY		= $rowSITM->ITM_REMQTY;		// like as Remain Qty
				$StartTPRC 		= $rowSITM->ITM_TOTALP;		// like as Last Total Price
				$StartOUT 		= $rowSITM->ITM_OUT;		// like as Last Total IN
				$StartOUTP 		= $rowSITM->ITM_OUTP;		// like as Last Total IN Price

				// HARGA RATA-RATA
					$StartVOLP	= $StartVOL;
					if($StartVOL == 0 || $StartVOL == '') $StartVOLP = 1;

					$AVG_PRICE	= $StartTPRC / $StartVOLP;
					$ITM_PRICE	= $AVG_PRICE;
					$ITM_TOTALP	= $ITM_QTY * $AVG_PRICE;
			endforeach;
			
			$EndVOL			= $StartVOL - $ITM_QTY;			// New End Volume = Last Stock
			if($EndVOL == 0 || $EndVOL == '')
				$EndVOL		= 1;
			
			$EndTPRC		= $StartTPRC - $ITM_TOTALP;		// New End Amount
			$EndPRC			= $EndTPRC / $EndVOL;			// Last Price Average
			$EndPRC			= $AVG_PRICE;					// Last Price from Last Price Order
			
			$EndOUT			= $StartOUT + $ITM_QTY;
			$EndOUTP		= $StartOUTP + $ITM_TOTALP;
			$ITMREMQTY		= $ITM_REMQTY - $ITM_QTY;
			
			// UPDATE DETAIL
			$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_PRICE = $EndPRC, ITM_TOTALP = ITM_TOTALP - $ITM_TOTALP,
								ITM_REMQTY = ITM_REMQTY - $ITM_QTY, ITM_LASTP = $ITM_PRICE,
								ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP, LAST_TRXNO = '$UM_NUM'
							WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
			$this->db->query($sqlUpDet);
			
			// UPDATE WH_QTY
				$sqlWHC	= "tbl_item_whqty WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
				$resWHC	= $this->db->count_all($sqlWHC);
				
				if($resWHC > 0)
				{
					$sqlUpWH	= "UPDATE tbl_item_whqty SET ITM_VOLM = ITM_VOLM - $ITM_QTY,
										ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP
									WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND WH_CODE = '$WH_CODE'";
					$this->db->query($sqlUpWH);
				}		
		}
			
		function get_AllDataCxx($PRJCODE, $search) // GOOD
		{
			$sql = "tbl_um_header A
						LEFT JOIN tbl_joblist_detail B ON A.JOBCODEID = B.JOBCODEID
							AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND (A.UM_CODE LIKE '%$search%' ESCAPE '!' OR A.UM_DATE LIKE '%$search%' ESCAPE '!' 
						OR A.UM_NOTE LIKE '%$search%' ESCAPE '!' OR B.JOBDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
	
		function get_AllDataSRVC($PRJCODE, $search) // GOOD
		{
			$sql = "tbl_joblist_detail A
					WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
						AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR A.JOBDESC LIKE '%$search%' ESCAPE '!')";
			return $this->db->count_all($sql);
		}
		
		function get_AllDataSRVL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
		{
			if($length == -1)
			{
				if($order !=null)
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
								A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir";
				}
				else
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
								A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
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
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
								A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID, $order $dir
								LIMIT $start, $length";
				}
				else
				{
					$sql = "SELECT DISTINCT A.ORD_ID, A.JOBCODEDET, A.JOBCODEID, A.JOBPARENT, A.JOBCODE, A.JOBDESC,
								A.ITM_GROUP, A.ITM_CODE, A.ITM_UNIT, A.ITM_VOLM, A.ITM_PRICE, A.ITM_LASTP, A.ITM_BUDG,
								A.ADD_VOLM, A.ADD_PRICE, A.ADD_JOBCOST, A.REQ_VOLM, A.REQ_AMOUNT,
								A.WO_QTY,  A.WO_AMOUNT, A.OPN_QTY, A.OPN_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
								A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, 
								A.ITM_BUDG, A.IS_LEVEL, A.ISLAST
							FROM tbl_joblist_detail A
							WHERE A.PRJCODE = '$PRJCODE' AND A.ISLAST = 0
								AND (A.JOBCODEID LIKE '%$search%' ESCAPE '!' OR A.ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR A.JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY A.ORD_ID LIMIT $start, $length";
				}
				return $this->db->query($sql);
			}
		}
		
		function updateBUDG_Min($parameters) // G
		{
			date_default_timezone_set("Asia/Jakarta");

	    	$PRJCODE 	= $parameters['PRJCODE'];
	    	$JOBCODEID 	= $parameters['JOBCODEID'];
			$UM_NUM 	= $parameters['UM_NUM'];
	    	$UM_CODE 	= $parameters['UM_CODE'];
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
			$StartOUT		= 0;
			$StartOUTP		= 0;
			$RET_VOLM		= 0;
			$RET_AMOUNT		= 0;
			$sqlStartITM	= "SELECT ITM_CODE_H, ITM_VOLMBG, ITM_VOLMBGR, ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_REMQTY, ITM_OUT, ITM_OUTP
								FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1 LIMIT 1";
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
				$StartOUT 		= $rowSITM->ITM_OUT;		// like as Last Total IN
				$StartOUTP 		= $rowSITM->ITM_OUTP;		// like as Last Total IN Price
			endforeach;
			
			$EndVOL			= $StartVOL - $ITM_QTY;			// New End Volume = Last Stock
			if($EndVOL == 0 || $EndVOL == '')
				$EndVOL		= 1;
			$EndTPRC		= $StartTPRC - $ITM_TOTALP;		// New End Amount
			$EndPRC			= $EndTPRC / $EndVOL;			// Last Price Average
			$EndPRC			= $ITM_PRICE;					// Last Price from Last Price Order
			
			$EndOUT			= $StartOUT + $ITM_QTY;
			$EndOUTP		= $StartOUTP + $ITM_TOTALP;
			$ITMREMQTY		= $ITM_REMQTY - $ITM_QTY;
			
			// UPDATE MATERIAL
				$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = ITM_VOLM - $ITM_QTY, ITM_PRICE = $EndPRC, ITM_TOTALP = ITM_TOTALP - $ITM_TOTALP,
									ITM_REMQTY = ITM_REMQTY - $ITM_QTY, ITM_LASTP = $ITM_PRICE,
									ITM_OUT = ITM_OUT + $ITM_QTY, ITM_OUTP = ITM_OUTP + $ITM_TOTALP, LAST_TRXNO = '$UM_NUM'
								WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
				$this->db->query($sqlUpDet);
			
			// UPDATE JOBD DETAIL
				$sqlUpDetJ		= "UPDATE tbl_joblist_detail SET
										REQ_VOLM 	= REQ_VOLM + $ITM_QTY, 
										REQ_AMOUNT 	= REQ_AMOUNT + $ITM_TOTALP, 
										PO_VOLM 	= PO_VOLM + $ITM_QTY, 
										PO_AMOUNT 	= PO_AMOUNT + $ITM_TOTALP, 
										WO_QTY 		= WO_QTY + $ITM_QTY, 
										WO_AMOUNT 	= WO_AMOUNT + $ITM_TOTALP, 
										OPN_QTY 	= OPN_QTY + $ITM_QTY, 
										OPN_AMOUNT 	= OPN_AMOUNT + $ITM_TOTALP, 
										ITM_USED 	= ITM_USED + $ITM_QTY, 
										ITM_USED_AM = ITM_USED_AM + $ITM_TOTALP
									WHERE JOBCODEID = '$JOBCODEID' AND ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";	
				$this->db->query($sqlUpDetJ);
		}
	}
?>