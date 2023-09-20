<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 8 November 2017
	* File Name		= M_updash.php
	* Notes			= -
*/

class M_updash extends CI_Model
{
	
	function createLR($PRJCODE, $PERIODED) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		//$PERIODE	= date('Y-m-d', strtotime('-1 month', strtotime($PERIODED)));
		$PERIODE	= date('Y-m-d', strtotime($PERIODED));
		$PERIODM	= date('m', strtotime($PERIODE));
		$PERIODY	= date('Y', strtotime($PERIODE));
		
		//$getLR	= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
		$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		$resLR		= $this->db->count_all($getLR);
		$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
		$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));
		
		if($resLR == 0)
		{
			$LR_CODE	= date('YmdHis');
			$PERIODE	= date('Y-m-d');
			$PRJNAME	= '';
			$PRJCOST	= 0;
			$PRJCOST_PPN= 0;
			$sqlPRJ 	= "SELECT PRJNAME, PRJCOST, PRJCOST_PPN FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME 	= $rowPRJ->PRJNAME;
				$PRJCOST 	= $rowPRJ->PRJCOST;
				$PRJCOST_PPN= $rowPRJ->PRJCOST_PPN;		
			endforeach;
		
			$LR_CREATER	= $this->session->userdata['Emp_ID'];
			$LR_CREATED	= date('Y-m-d H:i:s');

			$sqlInsLR	= "INSERT INTO tbl_profitloss (LR_CODE, PERIODE, PRJCODE, PRJNAME, PRJCOST, LR_CREATER, LR_CREATED)
							VALUES ('$LR_CODE', '$NEWPERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$LR_CREATER', '$LR_CREATED')";
			$this->db->query($sqlInsLR);
		}
	}

	function count_CODE($paramCODE) // OK
	{
		$DOC_NUM 		= $paramCODE['DOC_NUM'];
		$PRJCODE 		= $paramCODE['PRJCODE'];
		$TABLE_NAME		= $paramCODE['TABLE_NAME'];
		$FIELD_NAME		= $paramCODE['FIELD_NAME'];
		
		$sql	= "$TABLE_NAME WHERE $FIELD_NAME = '$DOC_NUM'";
		return $this->db->count_all($sql);
	}
	
	function get_menunm($MenuCode) // OK
	{
		$sql	= "SELECT menu_name_ENG, menu_name_IND FROM tbl_menu WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function get_NewCode($POSetting) // OK
	{										
		$PRJCODE 	= $POSetting['PRJCODE'];
		$TABLE_NAME	= $POSetting['TABLE_NAME'];
		$DOC_DATE	= $POSetting['DOC_DATE'];
		$PattCode	= $POSetting['PattCode'];
		$PattLength	= $POSetting['PattLength'];
		$useYear	= $POSetting['useYear'];
		$useMonth	= $POSetting['useMonth'];
		$useDate	= $POSetting['useDate'];
		
		$DOCDate	= date('Y-m-d',strtotime($DOC_DATE));
		$year		= date('Y',strtotime($DOC_DATE));
		$month 		= (int)date('m',strtotime($DOC_DATE));
		$date 		= (int)date('d',strtotime($DOC_DATE));
		
		$sql		= "$TABLE_NAME WHERE PRJCODE = '$PRJCODE' AND Patt_Year = $year";
		$myCount	= $this->db->count_all($sql);
		
		$sql	 = "SELECT MAX(Patt_Number) as maxNumber FROM $TABLE_NAME
					WHERE PRJCODE = '$PRJCODE' AND Patt_Year = $year";
		$result = $this->db->query($sql)->result();
		if($myCount>0)
		{
			$myMax	= 0;
			foreach($result as $row) :
				$myMax = $row->maxNumber;
				$myMax = $myMax+1;
			endforeach;
		}	
		else
		{
			$myMax = 1;
		}
		
		$thisMonth = $month;
	
		$lenMonth = strlen($thisMonth);
		if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
		$pattMonth = $nolMonth.$thisMonth;
		
		$thisDate = $date;
		$lenDate = strlen($thisDate);
		if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
		$pattDate = $nolDate.$thisDate;
		
		// group year, month and date
		$year = substr($year,2,2);
		if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$year$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$year$pattMonth";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$year$pattDate";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
			$groupPattern = "$pattMonth$pattDate";
		elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "$year";
		elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
			$groupPattern = "$pattMonth";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
			$groupPattern = "$pattDate";
		elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
			$groupPattern = "";
			
		$lastPatternNumb = $myMax;
		$lastPatternNumb1 = $myMax;
		$len = strlen($lastPatternNumb);
		
		if($PattLength==2)
		{
			if($len==1) $nol="0";
		}
		elseif($PattLength==3)
		{if($len==1) $nol="00";else if($len==2) $nol="0";
		}
		elseif($PattLength==4)
		{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
		}
		elseif($PattLength==5)
		{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
		}
		elseif($PattLength==6)
		{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
		}
		elseif($PattLength==7)
		{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
		}
		$lastPatternNumb	= $nol.$lastPatternNumb;
		$Pattern_Code		= "$PattCode"."D";
		$DocNumber 			= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
		$colDocNo			= "$DocNumber~$lastPatternNumb~$myMax";
		return $colDocNo;
	}
	
	function finTrack($paramFT) // OK
	{
		$DOC_NUM 		= $paramFT['DOC_NUM'];
		$DOC_DATE 		= $paramFT['DOC_DATE'];
		$DOC_EDATE 		= $paramFT['DOC_EDATE'];
		$PRJCODE 		= $paramFT['PRJCODE'];
		$FIELD_NAME1	= $paramFT['FIELD_NAME1'];
		$FIELD_NAME2	= $paramFT['FIELD_NAME2'];
		$TOT_AMOUNT		= $paramFT['TOT_AMOUNT'];
		
		// GET PROJECT DETAIL
			$PRJCOST	= 0;
			$PRJDATE	= '';
			$PRJEDAT	= '';
			$sqlPRJD	= "SELECT PRJCOST, PRJDATE, PRJEDAT FROM tbl_project where PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJD 	= $this->db->query($sqlPRJD)->result();
			foreach($resPRJD as $rowPRJD) :
				$PRJCOST 	= $rowPRJD->PRJCOST;
				$PRJDATE 	= $rowPRJD->PRJDATE;
				$PRJEDAT 	= $rowPRJD->PRJEDAT;
			endforeach;
		
		// GET PROJECT PROGRESS
		$MC_PROGAPP		= 0;
		$MC_PROGAPPVAL	= 0;
		$sqlMCD			= "SELECT MC_PROGAPP, MC_PROGAPPVAL FROM tbl_mcheader where PRJCODE = '$PRJCODE'
							AND MC_STEP = (SELECT max(MC_STEP) FROM tbl_mcheader WHERE PRJCODE = '$PRJCODE')";
		$resMCD 		= $this->db->query($sqlMCD)->result();
		foreach($resMCD as $rowMCD) :
			$MC_PROGAPP 	= $rowMCD->MC_PROGAPP;
			$MC_PROGAPPVAL 	= $rowMCD->MC_PROGAPPVAL;
		endforeach;
		
		// CEK PROJECT IN DASHBOARD - FINANCIAL TRACK
			$sqlPRJC		= "tbl_financial_track WHERE FT_PRJCODE = '$PRJCODE'";
			$resPRJC		= $this->db->count_all($sqlPRJC);
			if($resPRJC == 0)
			{			
				// INSERT TO FINANCIAL TRACK
				$sqlIns			= "INSERT INTO tbl_financial_track (FT_PRJCODE, FT_PRJSTARTD, FT_PRJENDD, FT_PRJCOST, $FIELD_NAME1, FT_PROG, FT_PROGAM)
									VALUES ('$PRJCODE', '$PRJDATE', '$PRJEDAT', '$PRJCOST', '$TOT_AMOUNT', '$MC_PROGAPP', '$MC_PROGAPPVAL')";
				$this->db->query($sqlIns);
			}
			else
			{
				$sqlUpd			= "UPDATE tbl_financial_track SET
										$FIELD_NAME1 = $FIELD_NAME1 + $TOT_AMOUNT 
									WHERE FT_PRJCODE = '$PRJCODE'";
				$this->db->query($sqlUpd);
			}
			
		// CEK PROJECT IN DASHBOARD - BUDGET MONITORING
			$sqlPRJC		= "tbl_financial_monitor WHERE FM_PRJCODE = '$PRJCODE' AND FM_TRANSD = '$DOC_DATE'";
			$resPRJC		= $this->db->count_all($sqlPRJC);
			if($resPRJC == 0)
			{
				// INSERT TO FINANCIAL MONITORING
				$sqlIns		= "INSERT INTO tbl_financial_monitor (FM_PRJCODE, FM_PRJSTARTD, FM_PRJENDD, FM_PRJCOST, FM_TRANSD, FM_TRANSED, $FIELD_NAME2,FM_PROG,FM_PROGAM)
								VALUES ('$PRJCODE', '$PRJDATE', '$PRJEDAT', '$PRJCOST', '$DOC_DATE', '$DOC_EDATE', '$TOT_AMOUNT', '$MC_PROGAPP', '$MC_PROGAPPVAL')";
				$this->db->query($sqlIns);
			}
			else
			{
				/*$sqlUpd			= "UPDATE tbl_financial_monitor SET FM_PRJCOST = $PRJCOST, FM_PRJSTARTD = '$PRJDATE', FM_PRJENDD = '$PRJEDAT', FM_TRANSED = '$DOC_EDATE',
										$FIELD_NAME2 = $FIELD_NAME2 + $TOT_AMOUNT 
									WHERE FM_PRJCODE = '$PRJCODE' AND FM_TRANSD = '$DOC_DATE'";*/
				$sqlUpd			= "UPDATE tbl_financial_monitor SET 
										$FIELD_NAME2 = $FIELD_NAME2 + $TOT_AMOUNT 
									WHERE FM_PRJCODE = '$PRJCODE' AND FM_TRANSD = '$DOC_DATE'";
				$this->db->query($sqlUpd);
			}
	}
	
	function VfinTrack($paramFT) // Void OK
	{
		$DOC_NUM 		= $paramFT['DOC_NUM'];
		$DOC_DATE 		= $paramFT['DOC_DATE'];
		$DOC_EDATE 		= $paramFT['DOC_EDATE'];
		$PRJCODE 		= $paramFT['PRJCODE'];
		$FIELD_NAME1	= $paramFT['FIELD_NAME1'];
		$FIELD_NAME2	= $paramFT['FIELD_NAME2'];
		$TOT_AMOUNT		= $paramFT['TOT_AMOUNT'];
		
		$sqlUpd			= "UPDATE tbl_financial_track SET
								$FIELD_NAME1 = $FIELD_NAME1 - $TOT_AMOUNT 
							WHERE FT_PRJCODE = '$PRJCODE'";
		$this->db->query($sqlUpd);
		
		$sqlUpd			= "UPDATE tbl_financial_monitor SET 
								$FIELD_NAME2 = $FIELD_NAME2 - $TOT_AMOUNT 
							WHERE FM_PRJCODE = '$PRJCODE' AND FM_TRANSD = '$DOC_DATE'";
		$this->db->query($sqlUpd);
	}
	
	function updateDashData($parameters) // OK
	{
		$DOC_CODE 		= $parameters['DOC_CODE'];
		$PRJCODE 		= $parameters['PRJCODE'];
		$TR_TYPE		= $parameters['TR_TYPE'];		// TRANSACTION TYPE. 	EX. "PR"
		$TBL_NAME 		= $parameters['TBL_NAME'];		// TABLE NAME.			EX. tbl_pr_header
		$KEY_NAME		= $parameters['KEY_NAME'];		// KEY OF THE TABLE		EX. PR170101-00001
		$STAT_NAME 		= $parameters['STAT_NAME'];		// NAMA FIELD STATUS	EX. PR_STAT (FIELD NAME STATUS)
		$STATDOCBEF 	= $parameters['STATDOCBEF'];	// TRANSACTION STATUS
		$STATDOC 		= $parameters['STATDOC'];		// TRANSACTION STATUS
		$FIELD_NM_ALL	= $parameters['FIELD_NM_ALL'];	// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
		$FIELD_NM_N		= $parameters['FIELD_NM_N'];	// TOTAL NEW TRANSACTION
		$FIELD_NM_C		= $parameters['FIELD_NM_C'];	// TOTAL CONFIRM TRANSACTION
		$FIELD_NM_A		= $parameters['FIELD_NM_A'];	// TOTAL APPROVE TRANSACTION
		$FIELD_NM_R		= $parameters['FIELD_NM_R'];	// TOTAL REVISE TRANSACTION
		$FIELD_NM_RJ	= $parameters['FIELD_NM_RJ'];	// TOTAL REJECT TRANSACTION
		$FIELD_NM_CL	= $parameters['FIELD_NM_CL'];	// TOTAL CLOSE TRANSACTION
				
		if($STATDOC == 9)
			$STATDOC	= 5;
			
		$STATDOCBEF		= $STATDOCBEF;					// STATUS 1 (OLD)
		$STATDOCNOW		= $STATDOC;						// STATUS 2 (NEW STATUS)
		
		// SETTING FIELD_NAME LAMA DEFAULT FOR tbl_trans_count. TR_ = Transaction
		if($STATDOCBEF == 1)
			$FIELD_NAME1	= "TR_NEW";
		elseif($STATDOCBEF == 2)
			$FIELD_NAME1	= "TR_CONFIRM";
		elseif($STATDOCBEF == 3)
			$FIELD_NAME1	= "TR_APPROVED";
		elseif($STATDOCBEF == 4)
			$FIELD_NAME1	= "TR_REVISE";
		elseif($STATDOCBEF == 5)
			$FIELD_NAME1	= "TR_REJECT";
		elseif($STATDOCBEF == 6)
			$FIELD_NAME1	= "TR_CLOSE1";
		elseif($STATDOCBEF == 7)
			$FIELD_NAME1	= "TR_CLOSE2";
		elseif($STATDOCBEF == 9)
			$FIELD_NAME1	= "TR_VOID";
			
		// SETTING FIELD_NAME BARU
		if($STATDOCNOW == 1)
			$FIELD_NAME2	= "TR_NEW";
		elseif($STATDOCNOW == 2)
			$FIELD_NAME2	= "TR_CONFIRM";
		elseif($STATDOCNOW == 3)
			$FIELD_NAME2	= "TR_APPROVED";
		elseif($STATDOCNOW == 4)
			$FIELD_NAME2	= "TR_REVISE";
		elseif($STATDOCNOW == 5)
			$FIELD_NAME2	= "TR_REJECT";
		elseif($STATDOCNOW == 6)
			$FIELD_NAME2	= "TR_CLOSE1";
		elseif($STATDOCNOW == 7)
			$FIELD_NAME2	= "TR_CLOSE2";	// AWAITING
		elseif($STATDOCNOW == 9)
			$FIELD_NAME2	= "TR_VOID";	// VOID
		
		$PRJFIELD 		= "PRJCODE";
		if($TBL_NAME == 'tbl_journalheader')
		{
			$PRJFIELD 	= "proj_Code";
		}

		// GET COUNT STATUS BEFORE AND AFTER (NEW)
		// BEFORE
		$selDOC1 		= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = $STATDOCBEF"; // EX. : "TBL_NAME = tbl_pr_header";
		$resDOC1		= $this->db->count_all($selDOC1);
		// AFTER (NEW)
		$selDOC2 		= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = $STATDOCNOW"; // EX. : "TBL_NAME = tbl_pr_header";
		$resDOC2		= $this->db->count_all($selDOC2);
		
		// CEK TYPE DI DASHBOARD
		$sqlAC 			= "tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
		$ressqlAC		= $this->db->count_all($sqlAC);
		
		// SUM TOTAL NEW = 1
		$TOTAL_NEW	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 1";
		$TOTAL_NEW	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL CONFIRM = 2
		$TOTAL_CON	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 2";
		$TOTAL_CON	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL APPROVE = 3
		$TOTAL_APP	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 3";
		$TOTAL_APP	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL REVISE = 4
		$TOTAL_REV	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 4";
		$TOTAL_REV	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL REJECT = 5
		$TOTAL_REJ	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 5";
		$TOTAL_REJ	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL CLOSE = 6
		$TOTAL_CLO	= 0;
		$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 6";
		$TOTAL_CLO	= $this->db->count_all($selTOTA);
		
		$GRAND_TOTAL	= $TOTAL_NEW + $TOTAL_CON + $TOTAL_APP + $TOTAL_REV + $TOTAL_REJ + $TOTAL_CLO;
		
		if($ressqlAC > 0)
		{
			// UPDATE FIRST STATUS (-)
			$TOT_NEW1	= $resDOC1;				
			$sqlUpd1	= "UPDATE tbl_trans_count SET $FIELD_NAME1 = $TOT_NEW1 WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
			$this->db->query($sqlUpd1);
				
			// UPDATE NEW STATUS (+)
			$TOT_NEW2	= $resDOC2;				
			$sqlUp2		= "UPDATE tbl_trans_count SET $FIELD_NAME2 = $TOT_NEW2 WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
			$this->db->query($sqlUp2);
		}
		else
		{
			$sqlIns			= "INSERT INTO tbl_trans_count (PRJCODE, TR_TYPE, $FIELD_NAME1) VALUES ('$PRJCODE', '$TR_TYPE', 1)";
			$this->db->query($sqlIns);
		}
	
		// CHECK DATA
		$sqlDashC 		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
		$resDashC		= $this->db->count_all($sqlDashC);
		if($resDashC > 0)
		{
			$sqlUpd1		= "UPDATE tbl_dash_transac SET $FIELD_NM_ALL = $GRAND_TOTAL, $FIELD_NM_N = $TOTAL_NEW,
									$FIELD_NM_C = $TOTAL_CON, $FIELD_NM_A = $TOTAL_APP, $FIELD_NM_R = $TOTAL_REV,
									$FIELD_NM_RJ = $TOTAL_REJ, $FIELD_NM_CL = $TOTAL_CLO
								WHERE PRJ_CODE = '$PRJCODE'";
			$this->db->query($sqlUpd1);
		}
		else
		{
			$sqlIns			= "INSERT INTO tbl_dash_transac (PRJ_CODE, $FIELD_NM_ALL, $FIELD_NM_N, $FIELD_NM_C, $FIELD_NM_A, $FIELD_NM_R, $FIELD_NM_RJ, $FIELD_NM_CL) 
								VALUES 
								('$PRJCODE', $GRAND_TOTAL, $TOTAL_NEW, $TOTAL_CON, $TOTAL_APP, $TOTAL_REV, $TOTAL_REJ, $TOTAL_CLO)";
			$this->db->query($sqlIns);
		}
	}
	
	function updateDashStatDoc($parameters) // OK. CREATED ON 21-03-29
	{
		$PRJCODE 		= $parameters['PRJCODE'];
		$TR_TYPE		= $parameters['TR_TYPE'];		// TRANSACTION TYPE. 	EX. "PR"
		$TBL_NAME 		= $parameters['TBL_NAME'];		// TABLE NAME.			EX. tbl_pr_header
		$STAT_NAME 		= $parameters['STAT_NAME'];		// NAMA FIELD STATUS	EX. PR_STAT (FIELD NAME STATUS)
		$FIELD_NM_ALL	= $parameters['FIELD_NM_ALL'];	// GRAND TOTAL TRANSACTION STATUS FOR tbl_dash_data
		$FIELD_NM_N		= $parameters['FIELD_NM_N'];	// TOTAL NEW TRANSACTION
		$FIELD_NM_C		= $parameters['FIELD_NM_C'];	// TOTAL CONFIRM TRANSACTION
		$FIELD_NM_A		= $parameters['FIELD_NM_A'];	// TOTAL APPROVE TRANSACTION
		$FIELD_NM_R		= $parameters['FIELD_NM_R'];	// TOTAL REVISE TRANSACTION
		$FIELD_NM_RJ	= $parameters['FIELD_NM_RJ'];	// TOTAL REJECT TRANSACTION
		$FIELD_NM_CL	= $parameters['FIELD_NM_CL'];	// TOTAL CLOSE TRANSACTION
		
		$PRJFIELD 		= "PRJCODE";
		if($TBL_NAME == 'tbl_journalheader')
		{
			$PRJFIELD 	= "proj_Code";
		}
			
		// SUM TOTAL NEW = 1
			$TOTAL_NEW	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 1";
			$TOTAL_NEW	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL CONFIRM = 2
			$TOTAL_CON	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 2";
			$TOTAL_CON	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL APPROVE = 3
			$TOTAL_APP	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 3";
			$TOTAL_APP	= $this->db->count_all($selTOTA);
			
		// SUM TOTAL REVISE = 4
			$TOTAL_REV	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 4";
			$TOTAL_REV	= $this->db->count_all($selTOTA);
			
		// SUM TOTAL REJECT = 5
			$TOTAL_REJ	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 5";
			$TOTAL_REJ	= $this->db->count_all($selTOTA);
		
		// SUM TOTAL CLOSE = 6
			$TOTAL_CLO	= 0;
			$selTOTA 	= "$TBL_NAME WHERE $PRJFIELD = '$PRJCODE' AND $STAT_NAME = 6";
			$TOTAL_CLO	= $this->db->count_all($selTOTA);
			
			$GRAND_TOTAL	= $TOTAL_NEW + $TOTAL_CON + $TOTAL_APP + $TOTAL_REV + $TOTAL_REJ + $TOTAL_CLO;

		
		// CEK TYPE DI DASHBOARD
			$sqlAC 			= "tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
			$ressqlAC		= $this->db->count_all($sqlAC);

		// UPDATE BY TYPE
			if($ressqlAC > 0)
			{
				$sqlUpd	= "UPDATE tbl_trans_count SET TR_NEW = $TOTAL_NEW, TR_CONFIRM = $TOTAL_CON,	TR_APPROVED = $TOTAL_APP,
									TR_REVISE = $TOTAL_REV,	TR_REJECT = $TOTAL_REJ,	TR_CLOSE1 = $TOTAL_CLO
								WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
				$this->db->query($sqlUpd);
			}
			else
			{
				$sqlIns	= "INSERT INTO tbl_trans_count (PRJCODE, TR_TYPE, TR_NEW, TR_CONFIRM, TR_APPROVED, TR_REVISE, TR_REJECT, TR_CLOSE1) 
							VALUES 
							('$PRJCODE', '$TR_TYPE', $TOTAL_NEW, $TOTAL_CON, $TOTAL_APP, $TOTAL_REV, $TOTAL_REJ, $TOTAL_CLO)";
				$this->db->query($sqlIns);
			}
	
		// CHECK DATA
			$sqlDashC 		= "tbl_dash_transac WHERE PRJ_CODE = '$PRJCODE'";
			$resDashC		= $this->db->count_all($sqlDashC);
			if($resDashC > 0)
			{
				$sqlUpd1		= "UPDATE tbl_dash_transac SET $FIELD_NM_ALL = $GRAND_TOTAL, $FIELD_NM_N = $TOTAL_NEW,
										$FIELD_NM_C = $TOTAL_CON, $FIELD_NM_A = $TOTAL_APP, $FIELD_NM_R = $TOTAL_REV,
										$FIELD_NM_RJ = $TOTAL_REJ, $FIELD_NM_CL = $TOTAL_CLO
									WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($sqlUpd1);
			}
			else
			{
				$sqlIns			= "INSERT INTO tbl_dash_transac (PRJ_CODE, $FIELD_NM_ALL, $FIELD_NM_N, $FIELD_NM_C, $FIELD_NM_A, $FIELD_NM_R,
										$FIELD_NM_RJ, $FIELD_NM_CL) 
									VALUES 
										('$PRJCODE', $GRAND_TOTAL, $TOTAL_NEW, $TOTAL_CON, $TOTAL_APP, $TOTAL_REV, $TOTAL_REJ, $TOTAL_CLO)";
				$this->db->query($sqlIns);
			}
	}
	
	function updateTrack($paramTrack) // OK
	{
		date_default_timezone_set("Asia/Jakarta");

		$TTR_EMPID 		= $paramTrack['TTR_EMPID'];
		$TTR_DATE 		= $paramTrack['TTR_DATE'];
		$TTR_MNCODE		= $paramTrack['TTR_MNCODE'];
		$TTR_CATEG		= $paramTrack['TTR_CATEG'];
		$TTR_PRJCODE	= $paramTrack['TTR_PRJCODE'];
		$TTR_REFDOC		= $paramTrack['TTR_REFDOC'];

		$host_name 	= gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $ipaddress	= '';
        if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	        $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'IP Tidak Dikenali';

	    $browser = '';
	    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape'))
	        $browser = 'Netscape';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
	        $browser = 'Firefox';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome'))
	        $browser = 'Chrome';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
	        $browser = 'Opera';
	    else if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
	        $browser = 'Internet Explorer';
	    else
	        $browser = 'Other';

		if(!empty($paramTrack['TTR_NOTES']))
    		$TTR_NOTES	= $paramTrack['TTR_NOTES']."~".$ipaddress."~".$host_name."~".$browser;
    	else
    		$TTR_NOTES 	= $ipaddress."~".$host_name."~".$browser;
		
		$TTR_LINK		= '';
		$TTR_TYPE		= '';
		$selMN1C 		= "tbl_menu WHERE menu_code = '$TTR_MNCODE'";
		$resMN1C		= $this->db->count_all($selMN1C);
		if($resMN1C > 0)
		{
			$selMN1 		= "SELECT link_alias AS TTR_LINK, menu_name_IND AS TTR_TYPE
								FROM tbl_menu WHERE menu_code = '$TTR_MNCODE'";
			$resMN1			= $this->db->query($selMN1)->result();
			foreach($resMN1 as $rowMN1):
				$TTR_LINK	= $rowMN1->TTR_LINK;
				$TTR_TYPE	= $rowMN1->TTR_TYPE;
			endforeach;
		}
		else
		{
			$TTR_LINK		= '';
			$TTR_TYPE		= '';
		}
		
		$sqlIns			= "INSERT INTO tbl_trail_tracker (TTR_EMPID, TTR_DATE, TTR_MNCODE, TTR_LINK, TTR_TYPE, TTR_CATEG, TTR_PRJCODE, TTR_REFDOC, TTR_NOTES)
							VALUES ('$TTR_EMPID', '$TTR_DATE', '$TTR_MNCODE', '$TTR_LINK', '$TTR_TYPE', '$TTR_CATEG', '$TTR_PRJCODE', '$TTR_REFDOC', '$TTR_NOTES')";
		$this->db->query($sqlIns);
		
		$ACT_DATE 		= date('Y-m-d');
		$selACT 		= "tbl_employee_activity WHERE ACT_EMPID = '$TTR_EMPID' AND ACT_DATE = '$ACT_DATE'";
		$resACT			= $this->db->count_all($selACT);
		if($resACT == 0)
		{
			$sqlIns		= "INSERT INTO tbl_employee_activity (ACT_DATE, ACT_EMPID, ACT_COUNT)
							VALUES ('$ACT_DATE', '$TTR_EMPID', 1)";
			$this->db->query($sqlIns);
		}
		else
		{
			$sqlUpd		= "UPDATE tbl_employee_activity SET ACT_COUNT = ACT_COUNT+1 WHERE ACT_EMPID = '$TTR_EMPID' AND ACT_DATE = '$ACT_DATE'";
			$this->db->query($sqlUpd);
		}
	}
	
	function insAppHist($insAppHist) // OK
	{
		$this->db->insert('tbl_approve_hist', $insAppHist);
	}
	
	function delAppHist($AH_CODE) // OK
	{
		$sqlDel	= "DELETE FROM tbl_approve_hist WHERE AH_CODE = '$AH_CODE'";
		$this->db->query($sqlDel);
	}
	
	function genCode($TRXCODE, $MenuCode, $PRJCODE, $TRXDATE, $TBL_NAME, $QUERY0, $QUERY1, $QUERY1A, $QUERY2, $QUERY3, $Manual_No) // OK
	{
		$sqlC 	= "$TBL_NAME $QUERY0";
		$sqlC 	= $this->db->count_all($sqlC);
		//echo "test $sqlC";
		//return $sqlC;
		//return false;
		if($sqlC > 0)
		{
			$sql	 = "SELECT Pattern_Code, Pattern_Position, Pattern_YearAktive, Pattern_MonthAktive, Pattern_DateAktive, Pattern_Length, 
							useYear, useMonth, useDate
						FROM tbl_docpattern
						WHERE menu_code = '$MenuCode'";
			$result = $this->db->query($sql)->result();
			
			foreach($result as $row) :
				$Pattern_Code = $row->Pattern_Code;
				$Pattern_Position = $row->Pattern_Position;
				$Pattern_YearAktive = $row->Pattern_YearAktive;
				$Pattern_MonthAktive = $row->Pattern_MonthAktive;
				$Pattern_DateAktive = $row->Pattern_DateAktive;
				$Pattern_Length = $row->Pattern_Length;
				$useYear = $row->useYear;
				$useMonth = $row->useMonth;
				$useDate = $row->useDate;
			endforeach;
			if($Pattern_Position == 'Especially')
			{
				$Pattern_YearAktive = date('Y');
				$Pattern_MonthAktive = date('m');
				$Pattern_DateAktive = date('d');
			}
			$yearC 	= (int)$Pattern_YearAktive;
			$year 	= substr($Pattern_YearAktive,2,2);
			$month 	= (int)$Pattern_MonthAktive;
			$date 	= (int)$Pattern_DateAktive;
			
			if($TBL_NAME == 'tbl_journalheader')
			{
				$sql 	= "$TBL_NAME $QUERY1 $QUERY3";
			}
			else
			{
				$sql 	= "$TBL_NAME $QUERY1A $QUERY2 $QUERY3";
			}
			$result = $this->db->count_all($sql);
			
			$myMax 	= $result+1;
	
			$thisMonth = $month;
	
			$lenMonth = strlen($thisMonth);
			if($lenMonth==1) $nolMonth="0";elseif($lenMonth==2) $nolMonth="";
			$pattMonth = $nolMonth.$thisMonth;
			
			$thisDate = $date;
			$lenDate = strlen($thisDate);
			if($lenDate==1) $nolDate="0";elseif($lenDate==2) $nolDate="";
			$pattDate = $nolDate.$thisDate;
	
			// group year, month and date
			if(($useYear == 1) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$year$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$year$pattMonth";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$year$pattDate";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 1))
				$groupPattern = "$pattMonth$pattDate";
			elseif(($useYear == 1) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "$year";
			elseif(($useYear == 0) && ($useMonth == 1) && ($useDate == 0))
				$groupPattern = "$pattMonth";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 1))
				$groupPattern = "$pattDate";
			elseif(($useYear == 0) && ($useMonth == 0) && ($useDate == 0))
				$groupPattern = "";
				
			$lastPatternNumb = $myMax;
			$lastPatternNumb1 = $myMax;
			$len = strlen($lastPatternNumb);
			
			if($Pattern_Length==2)
			{
				if($len==1) $nol="0";
			}
			elseif($Pattern_Length==3)
			{if($len==1) $nol="00";else if($len==2) $nol="0";
			}
			elseif($Pattern_Length==4)
			{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
			}
			elseif($Pattern_Length==5)
			{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
			}
			elseif($Pattern_Length==6)
			{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
			}
			elseif($Pattern_Length==7)
			{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
			}
			$lastPatternNumb = $nol.$lastPatternNumb;
			
			$lastPatternNumb	= $nol.$lastPatternNumb;
			$DocNumber 			= "$Pattern_Code$PRJCODE$groupPattern-$lastPatternNumb";
			return "$DocNumber~$lastPatternNumb";
		}
		else
		{
			$lastPatternNumb = $Manual_No;
			return "$TRXCODE~$lastPatternNumb";
		}
	}
	
	function clearHist($cllPar) // OK
	{
		$AH_CODE 		= $cllPar['AH_CODE'];
		$AH_APPROVER	= $cllPar['AH_APPROVER'];
		
		//$sql1	= "tbl_approve_hist WHERE AH_CODE = '$AH_CODE' AND AH_APPROVER = '$AH_APPROVER'";
		$sql1	= "tbl_approve_hist WHERE AH_CODE = '$AH_CODE'";
		$res1	= $this->db->count_all($sql1);
		if($res1 > 0)
		{
			//$sql	= "DELETE FROM tbl_approve_hist WHERE AH_CODE = '$AH_CODE' AND AH_APPROVER = '$AH_APPROVER'";
			$sql	= "DELETE FROM tbl_approve_hist WHERE AH_CODE = '$AH_CODE'";
			$this->db->query($sql);
		}
	}
	
	function createLR_211303($PRJCODE, $PERIODED) // OK
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		//$PERIODE	= date('Y-m-d', strtotime('-1 month', strtotime($PERIODED)));
		$PERIODE	= date('Y-m-d', strtotime($PERIODED));
		$PERIODM	= date('m', strtotime($PERIODE));
		$PERIODY	= date('Y', strtotime($PERIODE));
		
		$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
		$resLR		= $this->db->count_all($getLR);
		$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
		$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));
		
		if($resLR == 0)
		{
			$LR_CODE	= date('YmdHis');
			$PERIODE	= date('Y-m-d');
			$PRJNAME	= '';
			$PRJCOST	= 0;
			$PRJCOST_PPN= 0;
			$sqlPRJ 	= "SELECT PRJNAME, PRJCOST, PRJCOST_PPN FROM tbl_project WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $rowPRJ) :
				$PRJNAME 	= $rowPRJ->PRJNAME;
				$PRJCOST 	= $rowPRJ->PRJCOST;
				$PRJCOST_PPN= $rowPRJ->PRJCOST_PPN;		
			endforeach;
		
			$LR_CREATER	= $this->session->userdata['Emp_ID'];
			$LR_CREATED	= date('Y-m-d H:i:s');

			$sqlInsLR	= "INSERT INTO tbl_profitloss (LR_CODE, PERIODE, PRJCODE, PRJNAME, PRJCOST, LR_CREATER, LR_CREATED)
							VALUES ('$LR_CODE', '$NEWPERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$LR_CREATER', '$LR_CREATED')";
			$this->db->query($sqlInsLR);
		}
		else
		{
			$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE' ORDER BY PERIODE DESC LIMIT 1";
			$resGetLR	= $this->db->query($sqlGetLR)->result();
			foreach($resGetLR as $rowLR):
				$LR_CODE1		= date('YmdHis');
				$PERIODE 		= $NEWPERIODE;
				$PRJCODE 		= $rowLR->PRJCODE;
				$LR_CODE		= "$PRJCODE-$LR_CODE1";
				$PRJNAME 		= $rowLR->PRJNAME;
				$PRJCOST 		= $rowLR->PRJCOST;
				$PRJADD 		= $rowLR->PRJADD;
				$SIAPPVAL 		= $rowLR->SIAPPVAL;
				$PROG_PLAN 		= $rowLR->PROG_PLAN;
				$PROG_REAL 		= $rowLR->PROG_REAL;
				$INV_REAL 		= $rowLR->INV_REAL;
				$PROGMC 		= $rowLR->PROGMC;
				$PROGMC_PLAN 	= $rowLR->PROGMC_PLAN;
				$PROGMC_REAL	= $rowLR->PROGMC_REAL;
				$SI_PLAN 		= $rowLR->SI_PLAN;
				$SI_REAL 		= $rowLR->SI_REAL;
				$SI_PROYEKSI 	= $rowLR->SI_PROYEKSI;
				$MC_CAT_PLAN 	= $rowLR->MC_CAT_PLAN;
				$MC_CAT_REAL 	= $rowLR->MC_CAT_REAL;
				$MC_CAT_PROYEKSI = $rowLR->MC_CAT_PROYEKSI;
				$MC_OTH_PLAN	= $rowLR->MC_OTH_PLAN;
				$MC_OTH_REAL 	= $rowLR->MC_OTH_REAL;
				$MC_OTH_PROYEKSI= $rowLR->MC_OTH_PROYEKSI;
				$KURS_DEV_PLAN 	= $rowLR->KURS_DEV_PLAN;
				$KURS_DEV_REAL 	= $rowLR->KURS_DEV_REAL;
				$KURS_DEV_PROYEKSI = $rowLR->KURS_DEV_PROYEKSI;
				$ASSURAN_PLAN 	= $rowLR->ASSURAN_PLAN;
				$ASSURAN_REAL 	= $rowLR->ASSURAN_REAL;
				$ASSURAN_PROYEKSI = $rowLR->ASSURAN_REAL;
				$CASH_EXPENSE 	= $rowLR->CASH_EXPENSE;
				$BPP_MTR_PLAN 	= $rowLR->BPP_MTR_PLAN;
				$BPP_MTR_REAL 	= $rowLR->BPP_MTR_REAL;
				$BPP_MTR_PROYEKSI = $rowLR->BPP_MTR_PROYEKSI;
				$BPP_UPH_PLAN 	= $rowLR->BPP_UPH_PLAN;
				$BPP_UPH_REAL 	= $rowLR->BPP_UPH_REAL;
				$BPP_UPH_PROYEKSI = $rowLR->BPP_UPH_PROYEKSI;
				$BPP_ALAT_PLAN	= $rowLR->BPP_ALAT_PLAN;
				$BPP_ALAT_REAL 	= $rowLR->BPP_ALAT_REAL;
				$BPP_ALAT_PROYEKSI = $rowLR->BPP_ALAT_PROYEKSI;
				$BPP_SUBK_PLAN 	= $rowLR->BPP_SUBK_PLAN;
				$BPP_SUBK_REAL	= $rowLR->BPP_SUBK_REAL;
				$BPP_SUBK_PROYEKSI = $rowLR->BPP_SUBK_PROYEKSI;
				$BPP_BAU_PLAN 	= $rowLR->BPP_BAU_PLAN;
				$BPP_BAU_REAL 	= $rowLR->BPP_BAU_REAL;
				$BPP_BAU_PROYEKSI = $rowLR->BPP_BAU_PROYEKSI;
				$BPP_OTH_PLAN 	= $rowLR->BPP_OTH_PLAN;
				$BPP_OTH_REAL 	= $rowLR->BPP_OTH_REAL;
				$BPP_GAJI_PLAN 	= $rowLR->BPP_GAJI_PLAN;
				$BPP_GAJI_REAL 	= $rowLR->BPP_GAJI_REAL;
				$BPP_GAJI_PROYEKSI 	= $rowLR->BPP_GAJI_PROYEKSI;
				$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
				$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
				$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
				$BPP_OTH_PROYEKSI = $rowLR->BPP_OTH_PROYEKSI;
				$STOCK 			= $rowLR->STOCK;
				$STOCK_MTR 		= $rowLR->STOCK_MTR;
				$STOCK_BBM 		= $rowLR->STOCK_BBM;
				$STOCK_TOOLS 	= $rowLR->STOCK_TOOLS;
				$EXP_TOOLS 		= $rowLR->EXP_TOOLS;
				$EXP_BAU_HOCAB 	= $rowLR->EXP_BAU_HOCAB;
				$EXP_BUNGA 		= $rowLR->EXP_BUNGA;
				$EXP_PPH 		= $rowLR->EXP_PPH;
				$GRAND_PROFLOS 	= $rowLR->GRAND_PROFLOS;
				$LR_CREATER 	= $DefEmp_ID;
				$LR_CREATED 	= date('Y-m-d H:i:s');
			endforeach;
			
			// KARENA TIAP BULAN TIDAK TERKUNCI, MAKA SELURUH NILAI AWAL DI-NOLKAN SAJA
				/*$sqlInsLR	= "INSERT INTO tbl_profitloss (LR_CODE, PERIODE, PRJCODE, PRJNAME, PRJCOST, PRJADD, SIAPPVAL, PROG_PLAN,
									PROG_REAL, INV_REAL, PROGMC, PROGMC_PLAN, PROGMC_REAL, SI_PLAN, SI_REAL, 
									SI_PROYEKSI, MC_CAT_PLAN, MC_CAT_REAL, MC_CAT_PROYEKSI, MC_OTH_PLAN, MC_OTH_REAL,
									MC_OTH_PROYEKSI, KURS_DEV_PLAN, KURS_DEV_REAL, KURS_DEV_PROYEKSI, ASSURAN_PLAN,
									ASSURAN_REAL, ASSURAN_PROYEKSI, CASH_EXPENSE, BPP_MTR_PLAN, BPP_MTR_REAL, 
									BPP_MTR_PROYEKSI, BPP_UPH_PLAN, BPP_UPH_REAL, BPP_UPH_PROYEKSI, BPP_ALAT_PLAN,
									BPP_ALAT_REAL, BPP_ALAT_PROYEKSI, BPP_SUBK_PLAN, BPP_SUBK_REAL, BPP_SUBK_PROYEKSI, 
									BPP_BAU_PLAN, BPP_BAU_REAL, BPP_BAU_PROYEKSI, BPP_OTH_PLAN, BPP_OTH_REAL, 
									BPP_OTH_PROYEKSI, BPP_GAJI_PLAN, BPP_GAJI_REAL, BPP_GAJI_PROYEKSI, STOCK,
									STOCK_MTR, STOCK_BBM, STOCK_TOOLS, EXP_TOOLS, EXP_BAU_HOCAB, EXP_BUNGA, 
									EXP_PPH, GRAND_PROFLOS, LR_CREATER, LR_CREATED, LR_ISCREATED)
								VALUES ('$LR_CODE', '$PERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$PRJADD', '$SIAPPVAL', '$PROG_PLAN',
									'$PROG_REAL', '$INV_REAL', '$PROGMC', '$PROGMC_PLAN', '$PROGMC_REAL', '$SI_PLAN', '$SI_REAL', 
									'$SI_PROYEKSI', '$MC_CAT_PLAN', '$MC_CAT_REAL', '$MC_CAT_PROYEKSI', '$MC_OTH_PLAN', '$MC_OTH_REAL', 
									'$MC_OTH_PROYEKSI', '$KURS_DEV_PLAN', '$KURS_DEV_REAL',	'$KURS_DEV_PROYEKSI', '$ASSURAN_PLAN', 
									'$ASSURAN_REAL', '$ASSURAN_PROYEKSI', '$CASH_EXPENSE', '$BPP_MTR_PLAN', '$BPP_MTR_REAL', 
									'$BPP_MTR_PROYEKSI', '$BPP_UPH_PLAN', '$BPP_UPH_REAL', '$BPP_UPH_PROYEKSI', '$BPP_ALAT_PLAN',
									'$BPP_ALAT_REAL', '$BPP_ALAT_PROYEKSI', '$BPP_SUBK_PLAN', '$BPP_SUBK_REAL', '$BPP_SUBK_PROYEKSI', 
									'$BPP_BAU_PLAN', '$BPP_BAU_REAL', '$BPP_BAU_PROYEKSI', '$BPP_OTH_PLAN', '$BPP_OTH_REAL', 
									'$BPP_OTH_PROYEKSI', '$BPP_GAJI_PLAN', '$BPP_GAJI_REAL', '$BPP_GAJI_PROYEKSI', '$STOCK',
									'$STOCK_MTR', '$STOCK_BBM', '$STOCK_TOOLS', '$EXP_TOOLS', '$EXP_BAU_HOCAB', '$EXP_BUNGA', 
									'$EXP_PPH', '$GRAND_PROFLOS', '$LR_CREATER', '$LR_CREATED', 0)";
				$this->db->query($sqlInsLR);*/


				$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
				$resLR		= $this->db->count_all($getLR);
				if($resLR	== 0)
				{
					$sqlInsLR	= "INSERT INTO tbl_profitloss (LR_CODE, PERIODE, PRJCODE, PRJNAME, PRJCOST, LR_CREATER, LR_CREATED)
									VALUES ('$LR_CODE', '$NEWPERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$LR_CREATER', '$LR_CREATED')";
					$this->db->query($sqlInsLR);
				}
		}
	}
	
	function delDOC($CollID, $DefEmp_ID) // OK
	{
		$splitCode 	= explode("~", $CollID);
		$DOC_CATEG	= $splitCode[0];
		$DOC_NUM	= $splitCode[1];
		$PRJCODE	= $splitCode[2];
		
		if($DOC_CATEG == 'PR')
		{
			//  1. COPY TO tbl_header_trash
				$sqlCPYH	= "INSERT INTO tbl_pr_header_trash SELECT * FROM tbl_pr_header WHERE PR_NUM = '$DOC_NUM'";
				$this->db->query($sqlCPYH);
			
				$sqlH		= "DELETE FROM tbl_pr_header WHERE PR_NUM = '$DOC_NUM'";
				$this->db->query($sqlH);
			
			// 2. COPY TO tbl_detail_trash
				$sqlCPYD	= "INSERT INTO tbl_pr_detail_trash SELECT * FROM tbl_pr_detail WHERE PR_NUM = '$DOC_NUM'";
				$this->db->query($sqlCPYD) ;
			
				$sqlD		= "DELETE FROM tbl_pr_detail WHERE PR_NUM = '$DOC_NUM'";
				$this->db->query($sqlD);
				
			// 3. UPDATE ERASER
				$sqlUPDH	= "UPDATE tbl_pr_header_trash SET PR_ERASER = '$DefEmp_ID' WHERE PR_NUM = '$DOC_NUM'";
				$this->db->query($sqlUPDH);
			
			return site_url('c_purchase/c_pr180d0c/pRQ_l5t_x/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		}
		elseif($DOC_CATEG == 'IR')
		{
			//  1. COPY TO tbl_header_trash
				$sqlH		= "DELETE FROM tbl_ir_header WHERE IR_NUM = '$DOC_NUM'";
				$this->db->query($sqlH);
			
			// 2. COPY TO tbl_detail_trash			
				$sqlD		= "DELETE FROM tbl_ir_detail WHERE IR_NUM = '$DOC_NUM'";
				$this->db->query($sqlD);
				
			// 3. UPDATE ERASER
				$sqlUPDH	= "UPDATE tbl_ir_header_trash SET IR_ERASER = '$DefEmp_ID' WHERE IR_NUM = '$DOC_NUM'";
				$this->db->query($sqlUPDH);
			
			return site_url('c_inventory/c_ir180c15/gir180c15/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
		}
	}
	
	function updateStatus($paramStat) // OK
	{
		$PM_KEY 		= $paramStat['PM_KEY'];
		$DOC_CODE 		= $paramStat['DOC_CODE'];
		$DOC_STAT 		= $paramStat['DOC_STAT'];
		$PRJCODE 		= $paramStat['PRJCODE'];
		if(isset($paramStat['CREATERNM']))
		{
			$CREATERNM1		= $paramStat['CREATERNM'];
			$CREATERNM2		= strtolower($CREATERNM1);
			$CREATERNM		= ucwords($CREATERNM2);
		}
		else
		{
			$CREATERNM 	= '';
		}
		$TBLNAME		= $paramStat['TBLNAME'];
		
		if($DOC_STAT == 0)
		{
			$STATDESC 	= 'fake';
			$STATCOL	= 'danger';
		}
		elseif($DOC_STAT == 1)
		{
			$STATDESC 	= 'New';
			$STATCOL	= 'warning';
		}
		elseif($DOC_STAT == 2)
		{
			$STATDESC 	= 'Confirmed';
			$STATCOL	= 'primary';
		}
		elseif($DOC_STAT == 3)
		{
			$STATDESC 	= 'Approved';
			$STATCOL	= 'success';
			if($PM_KEY == 'TTK_NUM')
				$STATDESC 	= 'Complete';
		}
		elseif($DOC_STAT == 4)
		{
			$STATDESC 	= 'Revised';
			$STATCOL	= 'danger';
		}
		elseif($DOC_STAT == 5)
		{
			$STATDESC 	= 'Rejected';
			$STATCOL	= 'danger';
		}
		elseif($DOC_STAT == 6)
		{
			$STATDESC 	= 'Close';
			$STATCOL	= 'info';
		}
		elseif($DOC_STAT == 7)
		{
			$STATDESC 	= 'Awaiting';
			$STATCOL	= 'warning';
		}
		elseif($DOC_STAT == 9)
		{
			$STATDESC 	= 'void';
			$STATCOL	= 'danger';
		}
		else
		{
			$STATDESC 	= 'Not Range';
			$STATCOL	= 'danger';
		}
		
		if($TBLNAME == 'tbl_journalheader')
		{
			if($CREATERNM == '')
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL'
									WHERE proj_Code = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
			else
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL', CREATERNM = '$CREATERNM'
									WHERE proj_Code = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
			$upJHA			= "UPDATE tbl_journaldetail SET GEJ_STAT = '$DOC_STAT' WHERE JournalH_Code = '$DOC_CODE'";
			$this->db->query($upJHA);
		}
		elseif($TBLNAME == 'tbl_journalheader_pd')
		{
			if($CREATERNM == '')
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL'
									WHERE proj_Code = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
			else
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL', CREATERNM = '$CREATERNM'
									WHERE proj_Code = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
			$upJHA			= "UPDATE tbl_journaldetail SET GEJ_STAT = '$DOC_STAT' WHERE JournalH_Code = '$DOC_CODE'";
			$this->db->query($upJHA);
		}
		else
		{
			if($CREATERNM == '')
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL'
									WHERE PRJCODE = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
			else
			{
				$selDOC 		= "UPDATE $TBLNAME SET STATDESC = '$STATDESC', STATCOL = '$STATCOL', CREATERNM = '$CREATERNM'
									WHERE PRJCODE = '$PRJCODE' AND $PM_KEY = '$DOC_CODE'";
				$this->db->query($selDOC);
			}
		}
	}
	
	function get_PRJC($PRJCODE) // OK
	{
		//$sql		= "tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
		$sql		= "tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_PRJ($PRJCODE) // OK
	{
		$sql		= "SELECT PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
		/*$res		= $this->db->query($sql)->result();
		foreach($res as $row):
			$PRJCODE	= $row->PRJCODE;
		endforeach;
		return $PRJCODE;*/
	}
	
	function get_PRJPERIODE($PRJCODE) // OK
	{
		$sql		= "SELECT PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
		return $this->db->query($sql);
	}
	
	function get_PRJPER($PRJCODE) // OK
	{
		//$sql		= "SELECT PRJCODE, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1 AND BUDG_LEVEL = 2";
		$sql		= "SELECT PRJCODE, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND PRJSTAT = 1";
		return $this->db->query($sql);
	}
	
	function get_PRJHO($PRJCODE) // OK
	{
		$sql		= "SELECT PRJCODE_HO, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE' AND BUDG_LEVEL = 2";
		return $this->db->query($sql);
	}
	
	function updSTATJD($paramSTAT) // OK
	{
		$JournalHCode	= $paramSTAT['JournalHCode'];
		//$PRJCODE 		= $paramSTAT['PRJCODE'];
		//$PRJCODE_HO 	= $paramSTAT['PRJCODE_HO'];
		//$PRJPERIOD	= $paramSTAT['PRJPERIOD'];
		$APPROVED		= date('Y-m-d H:i:s');

		$JDSTAT		= 0;
		$sqlJDC		= "tbl_journalheader WHERE JournalH_Code = '$JournalHCode'";
		$resJDC 	= $this->db->count_all($sqlJDC);
		if($resJDC > 0)
		{
			$PRJCODE 	= '';
			$PRJCODE_HO = '';
			$sqlGetJD	= "SELECT GEJ_STAT, JournalH_Date, proj_Code FROM tbl_journalheader WHERE JournalH_Code = '$JournalHCode'";
			$resGetJD	= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowSTAT):
				$JDSTAT 	= $rowSTAT->GEJ_STAT;
				$JDDATE 	= $rowSTAT->JournalH_Date;
				$PRJCODE 	= $rowSTAT->proj_Code;
			endforeach;

			$sqlPRJ 	= "SELECT PRJCODE_HO, PRJPERIOD FROM tbl_project_budg WHERE PRJCODE = '$PRJCODE'";
			$resPRJ		= $this->db->query($sqlPRJ)->result();
			foreach($resPRJ as $row):
				$PRJCODE_HO	= $row->PRJCODE_HO;
				$PRJPERIOD	= $row->PRJPERIOD;
			endforeach;
			
			$updJD		= "UPDATE tbl_journaldetail SET GEJ_STAT = '$JDSTAT', proj_CodeHO = '$PRJCODE_HO', PRJPERIOD = '$PRJPERIOD', 
								LastUpdate = '$APPROVED', JournalH_Date = '$JDDATE'
							WHERE JournalH_Code = '$JournalHCode'";
			$this->db->query($updJD);
		}
	}
	
	function getItmType($PRJCODE, $ITM_CODE) // OK
	{
		$sqlITM	= "SELECT ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISRIB, ISCOST
					FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE'";
	    $resITM = $this->db->query($sqlITM)->result();
		foreach($resITM as $row) :
			$ISMTRL 	= $row->ISMTRL;
			$ISRENT 	= $row->ISRENT;
			$ISPART 	= $row->ISPART;
			$ISFUEL 	= $row->ISFUEL;
			$ISLUBRIC 	= $row->ISLUBRIC;
			$ISFASTM 	= $row->ISFASTM;
			$ISWAGE 	= $row->ISWAGE;
			$ISRM 		= $row->ISRM;
			$ISWIP 		= $row->ISWIP;
			$ISFG 		= $row->ISFG;
			$ISRIB 		= $row->ISRIB;
			$ISCOST 	= $row->ISCOST;

			/*if($ISMTRL == 1)
				$ITM_TYPE	= 1;*/
			if($ISRENT == 1)
				$ITM_TYPE	= 2;
			elseif($ISPART == 1)
				$ITM_TYPE	= 3;
			elseif($ISFUEL == 1)
				$ITM_TYPE	= 4;
			elseif($ISLUBRIC == 1)
				$ITM_TYPE	= 5;
			elseif($ISFASTM == 1)
				$ITM_TYPE	= 6;
			elseif($ISWAGE == 1)
				$ITM_TYPE	= 7;
			elseif($ISRM == 1)
				$ITM_TYPE	= 1;	// became as MTR = 1
			elseif($ISWIP == 1)
				$ITM_TYPE	= 9;
			elseif($ISFG == 1)
				$ITM_TYPE	= 10;
			elseif($ISRIB == 1)
				$ITM_TYPE	= 11;
			elseif($ISCOST == 1)
				$ITM_TYPE	= 12;
			else
				$ITM_TYPE	= 1;
		endforeach;
		return $ITM_TYPE;
	}
	
	function get_itmType($proj_Code, $ITM_CODE) // G
	{
		$ITM_TYPE	= 0;
		$sqlITM 	= "SELECT ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST FROM tbl_item
						WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $row):
			$ISMTRL	= $row->ISMTRL;
			$ISRENT	= $row->ISRENT;
			$ISPART	= $row->ISPART;
			$ISFUEL	= $row->ISFUEL;
			$ISLUB	= $row->ISLUBRIC;
			$ISFAST	= $row->ISFASTM;
			$ISWAGE	= $row->ISWAGE;
			$ISRM	= $row->ISRM;
			$ISWIP	= $row->ISWIP;
			$ISFG	= $row->ISFG;
			$ISCOST	= $row->ISCOST;
			
			// 1.ISMTRL, 2.ISRENT, 3.ISPART, 4.ISFULE, 5.ISLUBTICANT, 6. ISFASTM, 7.ISWAGE
			if($ISMTRL == 1)
				$ITM_TYPE	= 1;
			if($ISRENT == 1)
				$ITM_TYPE	= 2;
			if($ISPART == 1)
				$ITM_TYPE	= 3;
			if($ISFUEL == 1)
				$ITM_TYPE	= 4;
			if($ISLUB == 1)
				$ITM_TYPE	= 5;
			if($ISFAST == 1)
				$ITM_TYPE	= 6;
			if($ISWAGE == 1)
				$ITM_TYPE	= 7;
			if($ISRM == 1)
				$ITM_TYPE	= 8;
			if($ISWIP == 1)
				$ITM_TYPE	= 9;
			if($ISFG == 1)
				$ITM_TYPE	= 10;
			if($ISCOST == 1)
				$ITM_TYPE	= 11;

		endforeach;
		return $ITM_TYPE;
	}
	
	function get_itmAVG($proj_Code, $ITM_CODE) // G
	{
		$ITM_AVG	= 1;
		$sqlITM 	= "SELECT ITM_VOLM, ITM_TOTALP, ITM_IN, ITM_INP, ITM_OUT, ITM_OUTP FROM tbl_item
						WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $row):
			$ITM_VOLM	= $row->ITM_VOLM;
			$ITM_TOTALP	= $row->ITM_TOTALP;
			$ITM_IN		= $row->ITM_IN;
			$ITM_INP	= $row->ITM_INP;
			$ITM_OUT	= $row->ITM_OUT;
			$ITM_OUTP	= $row->ITM_OUTP;

			$ITM_AVG	= ($ITM_INP - $ITM_OUTP) / ($ITM_IN - $ITM_OUT);
		endforeach;

		return $ITM_AVG;
	}
	
	function get_itmGroup($proj_Code, $ITM_CODE) // G
	{
		$ITM_GROUP	= '';
		$ITM_TYPE	= 0;
		$sqlITM 	= "SELECT ITM_GROUP, ISMTRL, ISRENT, ISPART, ISFUEL, ISLUBRIC, ISFASTM, ISWAGE, ISRM, ISWIP, ISFG, ISCOST FROM tbl_item
						WHERE PRJCODE = '$proj_Code' AND ITM_CODE = '$ITM_CODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $row):
			$ITM_GROUP	= $row->ITM_GROUP;
			$ISMTRL		= $row->ISMTRL;
			$ISRENT		= $row->ISRENT;
			$ISPART		= $row->ISPART;
			$ISFUEL		= $row->ISFUEL;
			$ISLUB		= $row->ISLUBRIC;
			$ISFAST		= $row->ISFASTM;
			$ISWAGE		= $row->ISWAGE;
			$ISRM		= $row->ISRM;
			$ISWIP		= $row->ISWIP;
			$ISFG		= $row->ISFG;
			$ISCOST		= $row->ISCOST;

			if($ISMTRL == 1)
				$ITM_TYPE	= 1;
			if($ISRENT == 1)
				$ITM_TYPE	= 2;
			if($ISPART == 1)
				$ITM_TYPE	= 3;
			if($ISFUEL == 1)
				$ITM_TYPE	= 4;
			if($ISLUB == 1)
				$ITM_TYPE	= 5;
			if($ISFAST == 1)
				$ITM_TYPE	= 6;
			if($ISWAGE == 1)
				$ITM_TYPE	= 7;
			if($ISRM == 1)
				$ITM_TYPE	= 8;
			if($ISWIP == 1)
				$ITM_TYPE	= 9;
			if($ISFG == 1)
				$ITM_TYPE	= 10;
			if($ISCOST == 1)
				$ITM_TYPE	= 11;
		endforeach;

		$collData	= "$ITM_GROUP~$ITM_TYPE";
		return $collData;
	}
	
	function crtAlert($paramSTAT) // OK
	{
		$DEPCODE 		= $this->session->userdata['DEPCODE'];
		$ADDQRY			= '';
		if($DEPCODE != '')
			$ADDQRY		= "AND POSCODE = '$DEPCODE'";

		$PRJCODE		= $paramSTAT['PRJCODE'];
		$ALRT_MNCODE	= $paramSTAT['ALRT_MNCODE'];
		$ALRT_CATEG 	= $paramSTAT['ALRT_CATEG'];
		$ALRT_NUM 		= $paramSTAT['ALRT_NUM'];
		$ALRT_CODE 		= '';
		$ALRT_DATE 		= '';
		$ALRT_EMP 		= '';
		$ALRT_LEV 		= $paramSTAT['ALRT_LEV'];
		$ALRT_STAT		= 0;
		$ALRT_CREATED	= date('Y-m-d H:i:s');
		$ALRT_NOTE		= '';

		/*if($ALRT_LEV == 0) // IF BY CONFIRM
		{
			$NEXT_LEV			= $ALRT_LEV + 1;
			// GET FIRST APPROVER
				$sqlAPP1C		= "tbl_docstepapp_det WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = 1 $ADDQRY";
				$resAPP1C		= $this->db->count_all($sqlAPP1C);
				if($resAPP1C > 0)
				{
					$sqlAPP1		= "SELECT APPROVER_1 FROM tbl_docstepapp_det
										WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = 1 $ADDQRY";
					$resAPP1		= $this->db->query($sqlAPP1)->result();
					foreach($resAPP1 as $rowAPP1):
						$ALRT_EMP	= $rowAPP1->APPROVER_1;
					endforeach;
				}
				else
				{
					$sqlAPP1		= "SELECT APPROVER_1 FROM tbl_docstepapp_det
										WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = 1";
					$resAPP1		= $this->db->query($sqlAPP1)->result();
					foreach($resAPP1 as $rowAPP1):
						$ALRT_EMP	= $rowAPP1->APPROVER_1;
					endforeach;
				}
		}
		else
		{
			$NEXT_LEV			= $ALRT_LEV + 1;
			$MAX_STEP			= 0;

			// GET NEXT APPROVER
				$APPROVER_N		= '';
				$sqlAPP1C		= "tbl_docstepapp_det WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = $NEXT_LEV $ADDQRY";
				$resAPP1C		= $this->db->count_all($sqlAPP1C);
				if($resAPP1C > 0)
				{
					$sqlAPPN		= "SELECT APPROVER_1, MAX_STEP FROM tbl_docstepapp_det
										WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = $NEXT_LEV $ADDQRY";
					$resAPPN		= $this->db->query($sqlAPPN)->result();
					foreach($resAPPN as $rowAPPN):
						$APPROVER_N	= $rowAPPN->APPROVER_1;
						$MAX_STEP	= $rowAPPN->MAX_STEP;
					endforeach;
				}
				else
				{
					$sqlAPPN		= "SELECT APPROVER_1, MAX_STEP FROM tbl_docstepapp_det
										WHERE PRJCODE = '$PRJCODE' AND MENU_CODE = '$ALRT_MNCODE' AND APP_STEP = $NEXT_LEV";
					$resAPPN		= $this->db->query($sqlAPPN)->result();
					foreach($resAPPN as $rowAPPN):
						$APPROVER_N	= $rowAPPN->APPROVER_1;
						$MAX_STEP	= $rowAPPN->MAX_STEP;
					endforeach;
				}
				if($NEXT_LEV <= $MAX_STEP)
				{
					$ALRT_EMP 	= $APPROVER_N;
				}
		}

		$compName		= '';
		$PhoneNum		= '';
		$sqlEMPNM		= "SELECT First_Name, Last_Name, Mobile_Phone FROM tbl_employee WHERE Emp_ID = '$ALRT_EMP'";
		$resEMPNM		= $this->db->query($sqlEMPNM)->result();
		foreach($resEMPNM as $rowEMPNM):
			$First_Name	= $rowEMPNM->First_Name;
			$Last_Name	= $rowEMPNM->Last_Name;
			$compName	= $First_Name." ".$Last_Name;
			$PhoneNum	= $rowEMPNM->Mobile_Phone;
		endforeach;
		if($PhoneNum == '')
		{
			$PhoneNum	= '6285722980308';
		}

		// IF PR
			if($ALRT_CATEG == 'PR')
			{
				$CATEGDESC	= "Permintaan Pembelian";
				$sqlGDOC	= "SELECT PRJCODE, PR_CODE, PR_DATE, PR_NOTE FROM tbl_pr_header WHERE PR_NUM = '$ALRT_NUM'";
				$resGDOC	= $this->db->query($sqlGDOC)->result();
				foreach($resGDOC as $rowGDOC):
					$PRJCODE	= $rowGDOC->PRJCODE;
					$ALRT_CODE	= $rowGDOC->PR_CODE;
					$ALRT_DATE	= $rowGDOC->PR_DATE;
					$ALRT_NOTE	= $rowGDOC->PR_NOTE;
				endforeach;

				$CollCode	= "$PRJCODE~$ALRT_NUM~$ALRT_EMP";
				$secUpd		= site_url('__l1y4pp/update_inbbyWA/?id='.$this->url_encryption_helper->encode_url($CollCode));
			}

		// INSERT INTO
			$insDOC		= "INSERT INTO tbl_alert_list (PRJCODE, ALRT_MNCODE, ALRT_CATEG, ALRT_NUM, ALRT_CODE, ALRT_DATE, ALRT_EMP, ALRT_LEV, ALRT_STAT, ALRT_CREATED)
							VALUES
							('$PRJCODE', '$ALRT_MNCODE', '$ALRT_CATEG', '$ALRT_NUM', '$ALRT_CODE', '$ALRT_DATE', '$ALRT_EMP', $NEXT_LEV, $ALRT_STAT, '$ALRT_CREATED')";
			$this->db->query($insDOC);

            $JSON_DATA = '{"token":"a616c5f65ac99da765a13f8e229a85b492592462","priority ":0,"application":"2","sleep":0,"globalmessage":"","globalmedia":"","data":[{"number":"'.$PhoneNum.'","message":"Dear *'.$compName.'*,\nAda dokumen *'.$CATEGDESC.'* yang harus segera Anda setujui, yaitu nomor *'.$ALRT_CODE.'* dengan Catatan pembelian *'.$ALRT_NOTE.'*. \nSilahkan klik link di bawah ini untuk melakukan persetujuan \n'.$secUpd.'"}]}';


				//--CURL FUNCTION TO CALL THE API--
				$url = 'http://pickyassist.com/app/api/v2/push';
				//$url = 'https://pickyassist.com/app/api/v2';

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $JSON_DATA);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($JSON_DATA))
				);

				$result = curl_exec($ch);

				//--API RESPONSE--
				//print_r( json_decode($result,true) );*/
	}
        
	function autoLog($paramSTAT)
	{
		$AppEMP			= $paramSTAT['AppEMP'];
		$this->load->model('login_model', '', TRUE);
		
		$username 		= $this->input->post('username');
		
		$sqlUNC			= "SELECT Emp_ID, log_username, log_password FROM tbl_employee WHERE Emp_ID = '$AppEMP'";
		$resultUNC 		= $this->db->query($sqlUNC)->result();
		foreach($resultUNC as $rowUNC) :
			$EmpID				= $rowUNC->Emp_ID;
			$log_usernameX		= $rowUNC->log_username;
			$log_passwordX		= $rowUNC->log_password;
			$newPSW				= md5($log_passwordX);
			$sqlAppDesc	= "SELECT TS_DESC FROM tbl_trashsys";
			$resAppDesc = $this->db->query($sqlAppDesc)->result();
			foreach($resAppDesc as $rowDesc) :
				$TS_DESC 	= $rowDesc->TS_DESC;
			endforeach;
		endforeach;
		
		$username 	= $log_usernameX;
		$password 	= $log_passwordX;
  		$canLogIn 	= true;
		$resOn		= 1;
		if ($this->login_model->check_user($username, $password) == TRUE)
		{
			$sql		= "SELECT Emp_ID, EmpNoIdentity, First_Name, Middle_Name, Last_Name, Employee_status, Emp_DeptCode, 
								writeEMP, editEMP, readEMP, log_passHint, FlagUSER, FlagAppCheck, isSDBP, Emp_DeptCode AS DEPCODE
							FROM tbl_employee
							WHERE 
								log_username = '$username' 
								AND log_password = '$password'";
			$result		= $this->db->query($sql)->result();
			foreach($result as $therow) :
				$Emp_ID 		= $therow->Emp_ID;	
				$First_Name 	= $therow->First_Name;	
				$Middle_Name 	= $therow->Middle_Name;	
				$Last_Name 		= $therow->Last_Name;
				$log_passHint	= $therow->log_passHint;
				$FlagUSER 		= $therow->FlagUSER;
				$FlagAppCheck	= $therow->FlagAppCheck;
				$isSDBP 		= $therow->isSDBP;
				$Emp_DeptCode 	= $therow->Emp_DeptCode;
				$readEMP 		= $therow->readEMP;	
				$writeEMP 		= $therow->writeEMP;
				$editEMP 		= $therow->editEMP;
				$readEMP 		= $therow->readEMP;
				$DEPCODE 		= $therow->DEPCODE;
			endforeach;
			
			$updLog	= "UPDATE tbl_employee SET OLStat = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($updLog);
			
			$completeName 		= "$First_Name";
			if($Last_Name != '')
				$completeName 	= "$First_Name $Last_Name";
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;
				$comp_name	= $therow->comp_name;
				$comp_init	= $therow->comp_init;
				$app_notes	= $therow->app_notes;
				$maxLimit	= $therow->maxLimit;
				$nSELP 		= $therow->nSELP;
				$sysMode	= $therow->sysMode;
				$LastModeD	= $therow->LastModeD;
				$sysMnt		= $therow->sysMnt;
				$LastMntD	= $therow->LastMntD;
			endforeach;
			$LOG_DATE1 	= date('Y-m-d');
			$LOG_DATE	= date('Y-m-d', strtotime($LOG_DATE1));
			$LastModeD	= date('Y-m-d', strtotime($LastModeD));
			
			if($sysMode == 1 && $LastModeD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 2;
			}
			
			/*if($sysMnt == 1 && $LastMntD < $LOG_DATE)
			{
				$canLogIn 	= false;
				$resOn		= 3;
			}*/
			
			// Get session project firt time
			$EmpID 			= $Emp_ID;
			$getCount		= "tbl_employee_proj WHERE Emp_ID = '$EmpID'";
			$resGetCount	= $this->db->count_all($getCount);
			$collProject1	= '';
			$projCode		= '';
				
			if($resGetCount > 0)
			{
				$noU			= 0;
				$getData		= "SELECT A.Emp_ID, A.proj_Code, B.PRJNAME 
									FROM tbl_employee_proj A
									INNER JOIN tbl_project B ON B.PRJCODE = A.proj_Code
									WHERE A.Emp_ID = '$EmpID' GROUP BY proj_Code LIMIT 1";
				$resGetData 	= $this->db->query($getData)->result();
				foreach($resGetData as $rowData) :
					$projCode 	= $rowData->proj_Code;
					$PRJCODE 	= $rowData->proj_Code;
				endforeach;
			}
			
			if($projCode == '')
			{
				$updLog	= "UPDATE tbl_employee SET OLStat = 0 WHERE Emp_ID = '$Emp_ID'";
				$this->db->query($updLog);
			
				echo "This user is not setting for project. Please contact Administrators.";
				return false;
			}

			$dataSessSrc = array('sessTempProj' => $projCode);
			$this->session->set_userdata('SessTempProject', $dataSessSrc);
			
			$Lang_ID	= 'IND';
			$APPLEV		= 'PRJ';
			$sqlLANG	= "SELECT Lang_ID, APPLEV from tglobalsetting";
			$resLANG	= $this->db->query($sqlLANG)->result();
			foreach($resLANG as $rowLANG) :
				$Lang_ID 	= $rowLANG->Lang_ID;
				$APPLEV 	= $rowLANG->APPLEV;
			endforeach;
			
			date_default_timezone_set("Asia/Jakarta");
			$LOG_CODEY 	= date('Y');
			$LOG_CODEM 	= date('m');
			$LOG_CODED 	= date('d');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODEH 	= date('H-i-s');
			$LOG_CODE 	= "$Emp_ID$LOG_CODEY$LOG_CODEM$LOG_CODED-$LOG_CODEH";		
			$LOG_IND 	= date('Y-m-d H:i:s');
			$srvURL 	= $_SERVER['SERVER_ADDR'];
			$COMPANY_ID	= $comp_init;
			$sysLog		= $this->crypt180c1c->sys_decsrypt($srvURL, $appName, $app_notes, $TS_DESC);
			
			$data 		= array('username' => $username,'password' => $password, 'Emp_ID' => $Emp_ID, 'First_Name' => $First_Name, 'Last_Name' => $Last_Name, 'completeName' => $completeName, 'appName' => $appName, 'comp_name' => $comp_name, 'comp_init' => $comp_init, 'proj_Code' => $PRJCODE, 'Emp_DeptCode' => $Emp_DeptCode, 'writeEMP' => $writeEMP, 'editEMP' => $editEMP, 'readEMP' => $readEMP, 'log_passHint' => $log_passHint, 'isSDBP' => $isSDBP, 'FlagUSER' => $FlagUSER, 'FlagAppCheck' => $FlagAppCheck, 'LOG_CODE' => $LOG_CODE, 'LangID' => 'IND', 'COMPANY_ID' => $COMPANY_ID, 'login' => $sysLog, 'maxLimit' => $maxLimit, 'nSELP' => $nSELP, 'sysMode' => $sysMode, 'LastModeD' => $LastModeD, 'sysMnt' => $sysMnt, 'LastMntD' => $LastMntD, 'APPLEV' => $APPLEV, 'DEPCODE' => $DEPCODE);
			
			$this->session->set_userdata($data);
						
			$appName1	= $this->encrypt->encode($appName);
			$appName2	= $this->encrypt->decode($appName1);
			
			$insLog 	= array('LOG_CODE' 	=> $LOG_CODE,
								'LOG_EMP' 	=> $Emp_ID,
								'LOG_IND' 	=> $LOG_IND);
							
			$this->login_model->addLogin($insLog);
			
			$getLogC	= "tbl_login_hist WHERE LOG_EMP = '$Emp_ID'";
			$resLogC	= $this->db->count_all($getLogC);
			$resLogCT	= $resLogC + 1;
			$sqlUpdLogC	= "UPDATE tbl_employee SET Log_Count = $resLogCT, OLStat = 1 WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($sqlUpdLogC);
			
			$dt1 		= strtotime("2017/04/01");
			$dt2 		= strtotime(date('Y/m/d'));
			$diff 		= abs($dt2-$dt1);
			$DayTot 	= $diff/86400;
			$Log_AVG	= $resLogCT / $DayTot * 100;
			
			$UpdLogAVG	= "UPDATE tbl_employee SET Log_AVG = $Log_AVG WHERE Emp_ID = '$Emp_ID'";
			$this->db->query($UpdLogAVG);
			
			// PEMISAHAN KATEGORI
				$LogC_Nev	= "SELECT COUNT(*) AS totNev FROM tbl_employee WHERE Log_AVG = 0";
				$LogC_Nev	= $this->db->query($LogC_Nev)->result();
				foreach($LogC_Nev as $rowData) :
					$totNev 	= $rowData->totNev;
				endforeach;
				
				$LogC_SomeT	= "SELECT COUNT(*) AS totSomeT FROM tbl_employee WHERE Log_AVG > 0 AND Log_AVG < 25";
				$LogC_SomeT	= $this->db->query($LogC_SomeT)->result();
				foreach($LogC_SomeT as $rowData) :
					$totSomeT 	= $rowData->totSomeT;
				endforeach;
				
				$LogC_Often	= "SELECT COUNT(*) AS totOften FROM tbl_employee WHERE Log_AVG > 25 AND Log_AVG < 50";
				$LogC_Often	= $this->db->query($LogC_Often)->result();
				foreach($LogC_Often as $rowData) :
					$totOften 	= $rowData->totOften;
				endforeach;
				
				$LogC_Excl	= "SELECT COUNT(*) AS totExcl FROM tbl_employee WHERE Log_AVG > 50";
				$LogC_Excl	= $this->db->query($LogC_Excl)->result();
				foreach($LogC_Excl as $rowData) :
					$totExcl 	= $rowData->totExcl;
				endforeach;
				
				$sqlConc	= "tbl_login_concl";
				$resConc	= $this->db->count_all($sqlConc);
				if($resConc == 0)
				{
					$UpdLogCONCL	= "INSERT INTO tbl_login_concl (LCONC_NEVER, LCONC_SOMET, LCONC_OFTEN, LCONC_FANTASTIC)
										VALUES ($totNev, $totSomeT, $totOften, $totExcl)";
					$this->db->query($UpdLogCONCL);
				}
				else
				{
					$UpdLogCONCL	= "UPDATE tbl_login_concl SET LCONC_NEVER = '$totNev', LCONC_SOMET = '$totSomeT', 
										LCONC_OFTEN = '$totOften', LCONC_FANTASTIC = '$totExcl'";
					$this->db->query($UpdLogCONCL);
				}
			
			// START : UPDATE TO T-TRACK
				date_default_timezone_set("Asia/Jakarta");
				$DefEmp_ID 		= $Emp_ID;
				$TTR_PRJCODE	= '';
				$TTR_REFDOC		= '';
				$MenuCode 		= '';
				$TTR_CATEG		= 'ENTER';
				
				$this->load->model('m_updash/m_updash', '', TRUE);				
				$paramTrack 	= array('TTR_EMPID' 	=> $DefEmp_ID,
										'TTR_DATE' 		=> date('Y-m-d H:i:s'),
										'TTR_MNCODE'	=> $MenuCode,
										'TTR_CATEG'		=> $TTR_CATEG,
										'TTR_PRJCODE'	=> $TTR_PRJCODE,
										'TTR_REFDOC'	=> $TTR_REFDOC);
				$this->m_updash->updateTrack($paramTrack);
			// END : UPDATE TO T-TRACK
		}
		else
		{
			$canLogIn 	= false;
		}
	}

	function updateLR($PRJCODE)
	{
		// RESET PROFIT AND LOSS
        $sqlRESET   = "UPDATE tbl_profitloss SET BPP_MTR_REAL = 0, BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0,
                            BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_OTH_REAL = 0, BPP_BAU_REAL = 0
                        WHERE PRJCODE = '$PRJCODE'";
        $this->db->query($sqlRESET);

	    // SYNC TABLE
	        $sqlSYNCHD  = "UPDATE tbl_journaldetail A, tbl_journalheader B
	                            SET A.JournalH_Date = B.JournalH_Date, A.GEJ_STAT = B.GEJ_STAT,
	                                A.LastUpdate = B.LastUpdate
	                        WHERE A.JournalH_Code  = B.JournalH_Code";
	        $this->db->query($sqlSYNCHD);

	    // SYNC ITM_GROUP
	        $sqlSYNGRP  = "UPDATE tbl_journaldetail A, tbl_item B
	                            SET A.ITM_GROUP = B.ITM_GROUP
	                        WHERE A.ITM_CODE = B.ITM_CODE";
	        $this->db->query($sqlSYNGRP);


	    // SHOW ALL JOURNAL STAT 3
	        $sqlJOURNT1 = "SELECT
	                            A.JournalH_Code,
	                            A.JournalH_Date,
	                            A.Base_Debet,
	                            A.Base_Kredit,
	                            A.ITM_GROUP
	                        FROM
	                            tbl_journaldetail A
	                            INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
	                                AND B.JournalType != 'IR'
	                        WHERE
	                            B.GEJ_STAT = 3
	                            AND A.proj_Code = '$PRJCODE'
	                            AND ( ITM_GROUP != '' OR ! ISNULL(ITM_GROUP) )
	                            AND A.Base_Debet > 0";
	        $resJOURNT1 = $this->db->query($sqlJOURNT1)->result();
	        foreach($resJOURNT1 as $rowJ1) :
	            $journCode  = $rowJ1->JournalH_Code;
	            $JournDate  = $rowJ1->JournalH_Date;
	            $BaseDebet  = $rowJ1->Base_Debet;
	            $BaseKredit = $rowJ1->Base_Kredit;
	            $ITM_GROUP  = $rowJ1->ITM_GROUP;

	            $PERIODED   = $JournDate;
	            $PERIODM    = date('m', strtotime($PERIODED));
	            $PERIODY    = date('Y', strtotime($PERIODED));

	            if($ITM_GROUP == 'M')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'U')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'SC')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'T')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'I')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'O')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }
	            elseif($ITM_GROUP == 'GE')
	            {
	                $updLR  = "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$BaseDebet 
	                            WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
	                $this->db->query($updLR);
	            }

	            $sqlUpdChk  = "UPDATE tbl_journaldetail SET isCheckLR = 1 WHERE JournalH_Code = '$journCode'";
	        	$this->db->query($sqlUpdChk);
	        endforeach;
	}

	function updateLRMLTPRJ($COLPRJCODE)
	{
		// RESET PROFIT AND LOSS
	        /*$sqlRESET   = "UPDATE tbl_profitloss SET BPP_MTR_REAL = 0, BPP_UPH_REAL = 0, BPP_SUBK_REAL = 0,
	                            BPP_ALAT_REAL = 0, BPP_I_REAL = 0, BPP_OTH_REAL = 0, BPP_BAU_REAL = 0
	                        WHERE PRJCODE IN ('$COLPRJCODE')";
	        $this->db->query($sqlRESET);*/

	    // SYNC TABLE
	        $sqlSYNCHD  = "UPDATE tbl_journaldetail A, tbl_journalheader B
	                            SET A.JournalH_Date = B.JournalH_Date, A.GEJ_STAT = B.GEJ_STAT,
	                                A.LastUpdate = B.LastUpdate
	                        WHERE A.JournalH_Code  = B.JournalH_Code";
	        $this->db->query($sqlSYNCHD);

	    // SYNC ITM_GROUP
	        $sqlSYNGRP  = "UPDATE tbl_journaldetail A, tbl_item B
	                            SET A.ITM_GROUP = B.ITM_GROUP
	                        WHERE A.ITM_CODE = B.ITM_CODE AND A.proj_Code = B.PRJCODE";
	        $this->db->query($sqlSYNGRP);

	    // SHOW ALL JOURNAL STAT 3
	        $sqlJOURNT1 = "SELECT
	                            A.proj_Code,
	                            A.JournalH_Code,
	                            A.JournalH_Date,
	                            A.Base_Debet,
	                            A.Base_Kredit,
	                            A.ITM_GROUP
	                        FROM
	                            tbl_journaldetail A
	                            INNER JOIN tbl_journalheader B ON A.JournalH_Code = B.JournalH_Code
	                                AND B.JournalType != 'IR'
	                        WHERE
	                            B.GEJ_STAT = 3
	                            AND A.proj_Code IN ('$COLPRJCODE')
	                            AND ( ITM_GROUP != '' OR ! ISNULL(ITM_GROUP) )
	                            AND A.Base_Debet > 0 AND A.isCheckLR = 0";
	        $resJOURNT1 = $this->db->query($sqlJOURNT1)->result();
	        foreach($resJOURNT1 as $rowJ1) :
	            $journPrjc  = $rowJ1->proj_Code;
	            $journCode  = $rowJ1->JournalH_Code;
	            $JournDate  = $rowJ1->JournalH_Date;
	            $BaseDebet  = $rowJ1->Base_Debet;
	            $BaseKredit = $rowJ1->Base_Kredit;
	            $ITM_GROUP  = $rowJ1->ITM_GROUP;

	            $PERIODED   = $JournDate;
	            $PERIODM    = date('m', strtotime($PERIODED));
	            $PERIODY    = date('Y', strtotime($PERIODED));

	            // GET LAST VALUE FROM LAST SYNC
            		$BPP_MTR_REAL	= 0;
            		$BPP_UPH_REAL	= 0;
            		$BPP_SUBK_REAL	= 0;
            		$BPP_ALAT_REAL	= 0;
            		$BPP_I_REAL		= 0;
            		$BPP_OTH_REAL	= 0;
            		$BPP_BAU_REAL	= 0;

	            	$sqlLRS	= "SELECT * FROM tbl_lastsync_lr WHERE LRS_PRJC = '$journPrjc' AND MONTH(LRS_PRD) = '$PERIODM' AND YEAR(LRS_PRD) = '$PERIODY'";
	            	$resLRS	= $this->db->query($sqlLRS)->result();
	            	foreach($resLRS as $rowLRS) :
	            		$BPP_MTR_REAL	= $rowLRS->BPP_MTR_REAL;
	            		$BPP_UPH_REAL	= $rowLRS->BPP_UPH_REAL;
	            		$BPP_SUBK_REAL	= $rowLRS->BPP_SUBK_REAL;
	            		$BPP_ALAT_REAL	= $rowLRS->BPP_ALAT_REAL;
	            		$BPP_I_REAL		= $rowLRS->BPP_I_REAL;
	            		$BPP_OTH_REAL	= $rowLRS->BPP_OTH_REAL;
	            		$BPP_BAU_REAL	= $rowLRS->BPP_BAU_REAL;
	            	endforeach;
	            	if($BPP_MTR_REAL == '')
	            		$BPP_MTR_REAL	= 0;
	            	if($BPP_UPH_REAL == '')
	            		$BPP_UPH_REAL	= 0;
	            	if($BPP_SUBK_REAL == '')
	            		$BPP_SUBK_REAL	= 0;
	            	if($BPP_ALAT_REAL == '')
	            		$BPP_ALAT_REAL	= 0;
	            	if($BPP_I_REAL == '')
	            		$BPP_I_REAL		= 0;
	            	if($BPP_OTH_REAL == '')
	            		$BPP_OTH_REAL	= 0;
	            	if($BPP_BAU_REAL == '')
	            		$BPP_BAU_REAL	= 0;

		            if($ITM_GROUP == 'M')
		            {
		            	$LRS_FIELD	= "BPP_MTR_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = $BPP_MTR_REAL+$BaseDebet 
		                            	WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'U')
		            {
		            	$LRS_FIELD	= "BPP_UPH_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = $BPP_UPH_REAL+$BaseDebet 
		                            	WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'SC')
		            {
		            	$LRS_FIELD	= "BPP_SUBK_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = $BPP_SUBK_REAL+$BaseDebet 
		                            WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'T')
		            {
		            	$LRS_FIELD	= "BPP_ALAT_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = $BPP_ALAT_REAL+$BaseDebet 
		                            WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'I')
		            {
		            	$LRS_FIELD	= "BPP_I_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_I_REAL = $BPP_I_REAL+$BaseDebet 
		                            WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'O')
		            {
		            	$LRS_FIELD	= "BPP_OTH_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = $BPP_OTH_REAL+$BaseDebet 
		                            WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }
		            elseif($ITM_GROUP == 'GE')
		            {
		            	$LRS_FIELD	= "BPP_BAU_REAL";
		                $updLR  	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = $BPP_BAU_REAL+$BaseDebet 
		                            WHERE PRJCODE = '$journPrjc' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
		                $this->db->query($updLR);
		            }

		            $sqlUpdChk  = "UPDATE tbl_journaldetail SET isCheckLR = 1 WHERE JournalH_Code = '$journCode'";
		        	$this->db->query($sqlUpdChk);

		        	if(isset($LRS_FIELD) != '')
		        	{
	            		$sqlLRSC		= "tbl_lastsync_lr WHERE LRS_PRJC = '$journPrjc' AND MONTH(LRS_PRD) = '$PERIODM' AND YEAR(LRS_PRD) = '$PERIODY'";
	            		$resLRSC		= $this->db->count_all($sqlLRSC);
	            		if($resLRSC > 0)
	            		{
			            	$sqlLRS	= "UPDATE tbl_lastsync_lr SET $LRS_FIELD = $LRS_FIELD+$BaseDebet
			            				WHERE LRS_PRJC = '$journPrjc' AND MONTH(LRS_PRD) = '$PERIODM' AND YEAR(LRS_PRD) = '$PERIODY'";
			            	$this->db->query($sqlLRS);
	            		}
	            		else
	            		{
	            			$PERIODE	= date('Y-m-d', strtotime($PERIODED));
							$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
							$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));

			            	$sqlILRS	= "INSERT INTO tbl_lastsync_lr (LRS_PRJC, LRS_PRD, $LRS_FIELD) VALUES ('$journPrjc', '$NEWPERIODE', $BaseDebet)";
			            	$this->db->query($sqlILRS);
	            		}
	            	}
	        endforeach;
	}
	
	function updateQtyColl($parameters) // OK
	{
		$TR_TYPE		= $parameters['TR_TYPE'];
		$TR_DATE		= $parameters['TR_DATE'];
			$TR_DATEM	= date('m', strtotime($TR_DATE));
			$TR_DATEY	= date('Y', strtotime($TR_DATE));
		$PRJCODE		= $parameters['PRJCODE'];
		$PRJCODE_HO		= $this->data['PRJCODE_HO'];

		$sqlPRJ 	= "SELECT PRJCODE_HO FROM tbl_project WHERE PRJCODE = '$PRJCODE'";
		$resPRJ		= $this->db->query($sqlPRJ)->result();
		foreach($resPRJ as $rowPRJ):
			$PRJCODE_HO = $rowPRJ->PRJCODE_HO;
		endforeach;
		if($PRJCODE_HO == '') $PRJCODE_HO = $PRJCODE;

		$VOL_2			= 0;
		$AMN_2			= 0;
		$VOL_3			= 0;
		$AMN_3			= 0;

		// SO
			if($TR_TYPE == 'SO')
			{
				$sqlSOQTY2 	= "SELECT 	IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY,
										IF(ISNULL(SUM(SO_COST)), 0, SUM(SO_COST)) AS SOAMN
								FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									AND MONTH(B.SO_DATE) = '$TR_DATEM' AND YEAR(B.SO_DATE) = $TR_DATEY AND B.SO_STAT = 2";
				$resSOQTY2	= $this->db->query($sqlSOQTY2)->result();
				foreach($resSOQTY2 as $rowSOQTY2):
					$VOL_2 	= $rowSOQTY2->SOQTY;
					$AMN_2 	= $rowSOQTY2->SOAMN;
				endforeach;

				$sqlSOQTY3 	= "SELECT 	IF(ISNULL(SUM(SO_VOLM)), 0, SUM(SO_VOLM)) AS SOQTY,
										IF(ISNULL(SUM(SO_COST)), 0, SUM(SO_COST)) AS SOAMN
								FROM tbl_so_detail A INNER JOIN tbl_so_header B ON A.SO_NUM = B.SO_NUM
									AND MONTH(B.SO_DATE) = '$TR_DATEM' AND YEAR(B.SO_DATE) = $TR_DATEY AND B.SO_STAT = 3";
				$resSOQTY3	= $this->db->query($sqlSOQTY3)->result();
				foreach($resSOQTY3 as $rowSOQTY3):
					$VOL_3 	= $rowSOQTY3->SOQTY;
					$AMN_3 	= $rowSOQTY3->SOAMN;
				endforeach;

				$QTY2		= "SO_2_QTY";
				$AMN2		= "SO_2_AMN";
				$QTY3		= "SO_3_QTY";
				$AMN3		= "SO_3_AMN";
			}

		// JO
			if($TR_TYPE == 'JO')
			{
				$sqlJOQTY2 	= "SELECT 	IF(ISNULL(SUM(ITM_QTY)), 0, SUM(ITM_QTY)) AS JOQTY,
										IF(ISNULL(SUM(ITM_TOTAL)), 0, SUM(ITM_TOTAL)) AS JOAMN
								FROM tbl_jo_detail A INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
									AND MONTH(B.JO_DATE) = '$TR_DATEM' AND YEAR(B.JO_DATE) = $TR_DATEY AND B.JO_STAT = 2";
				$resJOQTY2	= $this->db->query($sqlJOQTY2)->result();
				foreach($resJOQTY2 as $rowJOQTY2):
					$VOL_2 	= $rowJOQTY2->JOQTY;
					$AMN_2 	= $rowJOQTY2->JOAMN;
				endforeach;

				$sqlJOQTY3 	= "SELECT 	IF(ISNULL(SUM(ITM_QTY)), 0, SUM(ITM_QTY)) AS JOQTY,
										IF(ISNULL(SUM(ITM_TOTAL)), 0, SUM(ITM_TOTAL)) AS JOAMN
								FROM tbl_jo_detail A INNER JOIN tbl_jo_header B ON A.JO_NUM = B.JO_NUM
									AND MONTH(B.JO_DATE) = '$TR_DATEM' AND YEAR(B.JO_DATE) = $TR_DATEY AND B.JO_STAT = 3";
				$resJOQTY3	= $this->db->query($sqlJOQTY3)->result();
				foreach($resJOQTY3 as $rowJOQTY3):
					$VOL_3 	= $rowJOQTY3->JOQTY;
					$AMN_3 	= $rowJOQTY3->JOAMN;
				endforeach;

				$QTY2		= "JO_2_QTY";
				$AMN2		= "JO_2_AMN";
				$QTY3		= "JO_3_QTY";
				$AMN3		= "JO_3_AMN";
			}

		// STF
			if($TR_TYPE == 'STF')
			{
				$sqlSTFQTY2 	= "SELECT 	IF(ISNULL(SUM(STF_VOLM)), 0, SUM(STF_VOLM)) AS STFQTY,
											IF(ISNULL(SUM(STF_TOTAL)), 0, SUM(STF_TOTAL)) AS STFAMN
									FROM tbl_stf_detail A INNER JOIN tbl_stf_header B ON A.STF_NUM = B.STF_NUM
										AND MONTH(B.STF_DATE) = '$TR_DATEM' AND YEAR(B.STF_DATE) = $TR_DATEY AND B.STF_STAT = 2 AND STF_ISLAST = 1";
				$resSTFQTY2	= $this->db->query($sqlSTFQTY2)->result();
				foreach($resSTFQTY2 as $rowSTFQTY2):
					$VOL_2 	= $rowSTFQTY2->STFQTY;
					$AMN_2 	= $rowSTFQTY2->STFAMN;
				endforeach;

				$sqlSTFQTY3 	= "SELECT 	IF(ISNULL(SUM(STF_VOLM)), 0, SUM(STF_VOLM)) AS STFQTY,
											IF(ISNULL(SUM(STF_TOTAL)), 0, SUM(STF_TOTAL)) AS STFAMN
									FROM tbl_stf_detail A INNER JOIN tbl_stf_header B ON A.STF_NUM = B.STF_NUM
										AND MONTH(B.STF_DATE) = '$TR_DATEM' AND YEAR(B.STF_DATE) = $TR_DATEY AND B.STF_STAT = 3 AND STF_ISLAST = 1";
				$resSTFQTY3	= $this->db->query($sqlSTFQTY3)->result();
				foreach($resSTFQTY3 as $rowSTFQTY3):
					$VOL_3 	= $rowSTFQTY3->STFQTY;
					$AMN_3 	= $rowSTFQTY3->STFAMN;
				endforeach;

				$QTY2		= "STF_2_QTY";
				$AMN2		= "STF_2_AMN";
				$QTY3		= "STF_3_QTY";
				$AMN3		= "STF_3_AMN";
			}

		// SN
			if($TR_TYPE == 'SN')
			{
				$sqlSNQTY2 	= "SELECT 	IF(ISNULL(SUM(SN_VOLM)), 0, SUM(SN_VOLM)) AS SNQTY,
											IF(ISNULL(SUM(SN_TOTAL)), 0, SUM(SN_TOTAL)) AS SNAMN
									FROM tbl_sn_detail A INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
										AND MONTH(B.SN_DATE) = '$TR_DATEM' AND YEAR(B.SN_DATE) = $TR_DATEY AND B.SN_STAT = 2";
				$resSNQTY2	= $this->db->query($sqlSNQTY2)->result();
				foreach($resSNQTY2 as $rowSNQTY2):
					$VOL_2 	= $rowSNQTY2->SNQTY;
					$AMN_2 	= $rowSNQTY2->SNAMN;
				endforeach;

				$sqlSNQTY3 	= "SELECT 	IF(ISNULL(SUM(SN_VOLM)), 0, SUM(SN_VOLM)) AS SNQTY,
											IF(ISNULL(SUM(SN_TOTAL)), 0, SUM(SN_TOTAL)) AS SNAMN
									FROM tbl_sn_detail A INNER JOIN tbl_sn_header B ON A.SN_NUM = B.SN_NUM
										AND MONTH(B.SN_DATE) = '$TR_DATEM' AND YEAR(B.SN_DATE) = $TR_DATEY AND B.SN_STAT = 3";
				$resSNQTY3	= $this->db->query($sqlSNQTY3)->result();
				foreach($resSNQTY3 as $rowSNQTY3):
					$VOL_3 	= $rowSNQTY3->SNQTY;
					$AMN_3 	= $rowSNQTY3->SNAMN;
				endforeach;

				$QTY2		= "SN_2_QTY";
				$AMN2		= "SN_2_AMN";
				$QTY3		= "SN_3_QTY";
				$AMN3		= "SN_3_AMN";
			}

		// PR
			if($TR_TYPE == 'PR')
			{
				$sqlPRQTY2 	= "SELECT 	IF(ISNULL(SUM(PR_VOLM)), 0, SUM(PR_VOLM)) AS PRQTY,
										IF(ISNULL(SUM(PR_TOTAL)), 0, SUM(PR_TOTAL)) AS PRAMN
								FROM tbl_pr_detail A INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
									AND MONTH(B.PR_DATE) = '$TR_DATEM' AND YEAR(B.PR_DATE) = $TR_DATEY AND B.PR_STAT = 2";
				$resPRQTY2	= $this->db->query($sqlPRQTY2)->result();
				foreach($resPRQTY2 as $rowPRQTY2):
					$VOL_2 	= $rowPRQTY2->PRQTY;
					$AMN_2 	= $rowPRQTY2->PRAMN;
				endforeach;

				$sqlPRQTY3 	= "SELECT 	IF(ISNULL(SUM(PR_VOLM)), 0, SUM(PR_VOLM)) AS PRQTY,
										IF(ISNULL(SUM(PR_TOTAL)), 0, SUM(PR_TOTAL)) AS PRAMN
								FROM tbl_pr_detail A INNER JOIN tbl_pr_header B ON A.PR_NUM = B.PR_NUM
									AND MONTH(B.PR_DATE) = '$TR_DATEM' AND YEAR(B.PR_DATE) = $TR_DATEY AND B.PR_STAT = 3";
				$resPRQTY3	= $this->db->query($sqlPRQTY3)->result();
				foreach($resPRQTY3 as $rowPRQTY3):
					$VOL_3 	= $rowPRQTY3->PRQTY;
					$AMN_3 	= $rowPRQTY3->PRAMN;
				endforeach;

				$QTY2		= "PR_2_QTY";
				$AMN2		= "PR_2_AMN";
				$QTY3		= "PR_3_QTY";
				$AMN3		= "PR_3_AMN";
			}

		// PO
			if($TR_TYPE == 'PO')
			{
				$sqlPOQTY2 	= "SELECT 	IF(ISNULL(SUM(PO_VOLM)), 0, SUM(PO_VOLM)) AS POQTY,
										IF(ISNULL(SUM(PO_COST)), 0, SUM(PO_COST)) AS POAMN
								FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
									AND MONTH(B.PO_DATE) = '$TR_DATEM' AND YEAR(B.PO_DATE) = $TR_DATEY AND B.PO_STAT = 2";
				$resPOQTY2	= $this->db->query($sqlPOQTY2)->result();
				foreach($resPOQTY2 as $rowPOQTY2):
					$VOL_2 	= $rowPOQTY2->POQTY;
					$AMN_2 	= $rowPOQTY2->POAMN;
				endforeach;

				$sqlPOQTY3 	= "SELECT 	IF(ISNULL(SUM(PO_VOLM)), 0, SUM(PO_VOLM)) AS POQTY,
										IF(ISNULL(SUM(PO_COST)), 0, SUM(PO_COST)) AS POAMN
								FROM tbl_po_detail A INNER JOIN tbl_po_header B ON A.PO_NUM = B.PO_NUM
									AND MONTH(B.PO_DATE) = '$TR_DATEM' AND YEAR(B.PO_DATE) = $TR_DATEY AND B.PO_STAT = 3";
				$resPOQTY3	= $this->db->query($sqlPOQTY3)->result();
				foreach($resPOQTY3 as $rowPOQTY3):
					$VOL_3 	= $rowPOQTY3->POQTY;
					$AMN_3 	= $rowPOQTY3->POAMN;
				endforeach;

				$QTY2		= "PO_2_QTY";
				$AMN2		= "PO_2_AMN";
				$QTY3		= "PO_3_QTY";
				$AMN3		= "PO_3_AMN";
			}

		// IR
			if($TR_TYPE == 'IR')
			{
				$sqlIRQTY2 	= "SELECT 	IF(ISNULL(SUM(ITM_QTY)), 0, SUM(ITM_QTY)) AS IRQTY,
										IF(ISNULL(SUM(ITM_TOTAL)), 0, SUM(ITM_TOTAL)) AS IRAMN
								FROM tbl_ir_detail A INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
									AND MONTH(B.IR_DATE) = '$TR_DATEM' AND YEAR(B.IR_DATE) = $TR_DATEY AND B.IR_STAT = 2";
				$resIRQTY2	= $this->db->query($sqlIRQTY2)->result();
				foreach($resIRQTY2 as $rowIRQTY2):
					$VOL_2 	= $rowIRQTY2->IRQTY;
					$AMN_2 	= $rowIRQTY2->IRAMN;
				endforeach;

				$sqlIRQTY3 	= "SELECT 	IF(ISNULL(SUM(ITM_QTY)), 0, SUM(ITM_QTY)) AS IRQTY,
										IF(ISNULL(SUM(ITM_TOTAL)), 0, SUM(ITM_TOTAL)) AS IRAMN
								FROM tbl_ir_detail A INNER JOIN tbl_ir_header B ON A.IR_NUM = B.IR_NUM
									AND MONTH(B.IR_DATE) = '$TR_DATEM' AND YEAR(B.IR_DATE) = $TR_DATEY AND B.IR_STAT = 3";
				$resIRQTY3	= $this->db->query($sqlIRQTY3)->result();
				foreach($resIRQTY3 as $rowIRQTY3):
					$VOL_3 	= $rowIRQTY3->IRQTY;
					$AMN_3 	= $rowIRQTY3->IRAMN;
				endforeach;

				$QTY2		= "IR_2_QTY";
				$AMN2		= "IR_2_AMN";
				$QTY3		= "IR_3_QTY";
				$AMN3		= "IR_3_AMN";
			}

		// WO
			if($TR_TYPE == 'WO')
			{
				$sqlWOQTY2 	= "SELECT 	IF(ISNULL(SUM(WO_VOLM)), 0, SUM(WO_VOLM)) AS WOQTY,
										IF(ISNULL(SUM(WO_TOTAL)), 0, SUM(WO_TOTAL)) AS WOAMN
								FROM tbl_wo_detail A INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND MONTH(B.WO_DATE) = '$TR_DATEM' AND YEAR(B.WO_DATE) = $TR_DATEY AND B.WO_STAT = 2";
				$resWOQTY2	= $this->db->query($sqlWOQTY2)->result();
				foreach($resWOQTY2 as $rowWOQTY2):
					$VOL_2 	= $rowWOQTY2->WOQTY;
					$AMN_2 	= $rowWOQTY2->WOAMN;
				endforeach;

				$sqlWOQTY3 	= "SELECT 	IF(ISNULL(SUM(WO_VOLM)), 0, SUM(WO_VOLM)) AS WOQTY,
										IF(ISNULL(SUM(WO_TOTAL)), 0, SUM(WO_TOTAL)) AS WOAMN
								FROM tbl_wo_detail A INNER JOIN tbl_wo_header B ON A.WO_NUM = B.WO_NUM
									AND MONTH(B.WO_DATE) = '$TR_DATEM' AND YEAR(B.WO_DATE) = $TR_DATEY AND B.WO_STAT = 3";
				$resWOQTY3	= $this->db->query($sqlWOQTY3)->result();
				foreach($resWOQTY3 as $rowWOQTY3):
					$VOL_3 	= $rowWOQTY3->WOQTY;
					$AMN_3 	= $rowWOQTY3->WOAMN;
				endforeach;

				$QTY2		= "WO_2_QTY";
				$AMN2		= "WO_2_AMN";
				$QTY3		= "WO_3_QTY";
				$AMN3		= "WO_3_AMN";
			}

		// OPN
			if($TR_TYPE == 'OPN')
			{
				$sqlOPNQTY2 = "SELECT 	IF(ISNULL(SUM(OPND_VOLM)), 0, SUM(OPND_VOLM)) AS OPNQTY,
										IF(ISNULL(SUM(OPND_ITMTOTAL)), 0, SUM(OPND_ITMTOTAL)) AS OPNAMN
								FROM tbl_opn_detail A INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
									AND MONTH(B.OPNH_DATE) = '$TR_DATEM' AND YEAR(B.OPNH_DATE) = $TR_DATEY AND B.OPNH_STAT = 2";
				$resOPNQTY2	= $this->db->query($sqlOPNQTY2)->result();
				foreach($resOPNQTY2 as $rowOPNQTY2):
					$VOL_2 	= $rowOPNQTY2->OPNQTY;
					$AMN_2 	= $rowOPNQTY2->OPNAMN;
				endforeach;

				$sqlOPNQTY3 	= "SELECT 	IF(ISNULL(SUM(OPND_VOLM)), 0, SUM(OPND_VOLM)) AS OPNQTY,
										IF(ISNULL(SUM(OPND_ITMTOTAL)), 0, SUM(OPND_ITMTOTAL)) AS OPNAMN
								FROM tbl_opn_detail A INNER JOIN tbl_opn_header B ON A.OPNH_NUM = B.OPNH_NUM
									AND MONTH(B.OPNH_DATE) = '$TR_DATEM' AND YEAR(B.OPNH_DATE) = $TR_DATEY AND B.OPNH_STAT = 3";
				$resOPNQTY3	= $this->db->query($sqlOPNQTY3)->result();
				foreach($resOPNQTY3 as $rowOPNQTY3):
					$VOL_3 	= $rowOPNQTY3->OPNQTY;
					$AMN_3 	= $rowOPNQTY3->OPNAMN;
				endforeach;

				$QTY2		= "WO_2_QTY";
				$AMN2		= "WO_2_AMN";
				$QTY3		= "WO_3_QTY";
				$AMN3		= "WO_3_AMN";
			}
		
		$sqlQTYC			= "tbl_qty_coll WHERE MONTH(PERIODE) = '$TR_DATEM' AND YEAR(PERIODE) = $TR_DATEY";
		$resQTYC			= $this->db->count_all($sqlQTYC);
		if($resQTYC == 0)
		{
			$sqlINST	= "INSERT INTO tbl_qty_coll (PRJCODE, PRJCODE_HO, PERIODE, $QTY2, $AMN2, $QTY3, $AMN3) VALUES
							('$PRJCODE', '$PRJCODE_HO', '$TR_DATE', $VOL_2, $AMN_2, $VOL_3, $AMN_3)";
			$this->db->query($sqlINST);
		}
		else
		{
			$sqlUPD		= "UPDATE tbl_qty_coll SET $QTY2 = $VOL_2, $AMN2 = $AMN_2, $QTY3 = $VOL_3, $AMN3 = $AMN_3
							WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$TR_DATEM' AND YEAR(PERIODE) = $TR_DATEY";
			$this->db->query($sqlUPD);
		}
	}

	function updateLR_NForm($PRJCODE, $parameters)
	{
		date_default_timezone_set("Asia/Jakarta");
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];

		$PERIODE	= date('Y-m-d', strtotime($parameters['PERIODED']));

		// GET PROJECT CUT OFF
			$PRJ_CO	= "";
			$sPRJCO	= "SELECT PRJDATE_CO FROM tbl_project WHERE isHO = 1 LIMIT 1";
			$rPRJCO	= $this->db->query($sPRJCO)->result();
			foreach($rPRJCO as $rwPRJCO) :
				$PRJ_CO = $rwPRJCO->PRJDATE_CO;
			endforeach;
			$DATECO	= date('d', strtotime($PRJ_CO));

		// JIKA TGL DOKUMEN LEBIH DARI TGL. CUT OFF, MAKA MASUK KE LR BULAN BERIKUTNYA
			if($PERIODE > $DATECO)
				$PERIODE	= date('Y-m-01', strtotime('+1 month', strtotime($PERIODE)));

		$PERIODD	= date('m', strtotime($PERIODE));
		$PERIODM	= date('m', strtotime($PERIODE));
		$PERIODY	= date('Y', strtotime($PERIODE));
		$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
		$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));
		$DATENOW	= date('Y-m-d');

		$FIELDNME 	= $parameters['FIELDNME'];
		if($FIELDNME == "")
		{
			$FIELDNME	= "NSET_LR";
		}

		$FIELDVOL 	= $parameters['FIELDVOL'];
		if($FIELDVOL == "")
		{
			$FIELDVOL	= 0;
		}
		$FIELDPRC 	= $parameters['FIELDPRC'];
		if($FIELDPRC == "")
		{
			$FIELDPRC	= 0;
		}
		$ADDTYPE 	= $parameters['ADDTYPE'];
		$ITM_CODE 	= $parameters['ITM_CODE'];
		$ITM_TYPE 	= $parameters['ITM_TYPE'];
		$FIELDVAL 	= $FIELDVOL * $FIELDPRC;

		$ITMGRP 	= "";
		$ITMCAT 	= "";
		$ITM_LR 	= "";
		$sqlITM		= "SELECT ITM_GROUP, ITM_CATEG, ITM_LR FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
		$resITM		= $this->db->query($sqlITM)->result();
		foreach($resITM as $rowITM):
			$ITMGRP = $rowITM->ITM_GROUP;
			$ITMCAT = $rowITM->ITM_CATEG;
			$ITM_LR = $rowITM->ITM_LR;
		endforeach;

		$ITM_GROUP 	= $ITMGRP;
		$ITM_CATEG 	= $ITMCAT;
		
		$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE'";
		$resLR		= $this->db->count_all($getLR);

		// GET DETAIL PROJECT
			$sqlGetLR	= "SELECT * FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE' LIMIT 1";
			$resGetLR	= $this->db->query($sqlGetLR)->result();
			foreach($resGetLR as $rowLR):
				$LR_CODE1		= date('YmdHis');
				$PERIODE 		= $NEWPERIODE;
				$PRJCODE 		= $rowLR->PRJCODE;
				$LR_CODE		= "$PRJCODE-$LR_CODE1";
				$PRJNAME 		= $rowLR->PRJNAME;
				$PRJCOST 		= $rowLR->PRJCOST;
				$LR_CREATER 	= $DefEmp_ID;
				$LR_CREATED 	= date('Y-m-d H:i:s');
			endforeach;

		// CEK KEBERADAAN LAPORAN LR PER PERIODE TERSEBUT
			$getLR		= "tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
			$resLR		= $this->db->count_all($getLR);
			if($resLR == 0)
			{
				$sqlInsLR	= "INSERT INTO tbl_profitloss (LR_CODE, PERIODE, PRJCODE, PRJNAME, PRJCOST, LR_CREATER, LR_CREATED)
								VALUES ('$LR_CODE', '$NEWPERIODE', '$PRJCODE', '$PRJNAME', '$PRJCOST', '$LR_CREATER', '$LR_CREATED')";
				$this->db->query($sqlInsLR);

				if($ADDTYPE == 'PLUS')
				{
			        /*$sqlRESET   = "UPDATE tbl_profitloss SET $FIELDNME = $FIELDNME+$FIELDVAL
			        				WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' A";
			        $this->db->query($sqlRESET);*/

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
			    }
			    else
			    {
			        /*$sqlRESET   = "UPDATE tbl_profitloss SET $FIELDNME = $FIELDNME-$FIELDVAL
			        				WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' B";
			        $this->db->query($sqlRESET);*/

					// L/R MANUFACTUR
						$ITMLRC		= strlen($ITM_LR);
						if($ITMLRC > 1)
						{
							$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						
					// L/R CONTRACTOR // ADDING COST OR EXPENS
						if($ITM_GROUP == 'M')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'ADM')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'GE')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'I')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'O')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$FIELDVAL
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'T')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
											AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
						elseif($ITM_GROUP == 'U')
						{
							$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$FIELDVAL 
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
								$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$FIELDVAL 
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$FIELDVAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 9)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$FIELDVAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
							elseif($ITM_TYPE == 10)
							{
								$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$FIELDVAL
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
								$this->db->query($updLR);
							}
						}
						elseif($ITM_GROUP == 'T')
						{
							$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
										WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$this->db->query($updLR);
						}
			    }
			}
			else
			{
				// CEK APAKAH LR MASIH TERBUKA ATAU SUDAH TERKUNCI
					$sqlGetLR	= "SELECT LR_STAT FROM tbl_profitloss WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
									AND YEAR(PERIODE) = '$PERIODY'";
					$resGetLR	= $this->db->query($sqlGetLR)->result();
					foreach($resGetLR as $rowLR):
						$LRSTAT = $rowLR->LR_STAT;
					endforeach;
					if($LRSTAT == 0)	// JIKA MASIH TERBUKA, GUNAKAN TANGGAL DOKUMEN
					{
						if($ADDTYPE == 'PLUS')
						{
					        /*$sqlRESET   = "UPDATE tbl_profitloss SET $FIELDNME = $FIELDNME+$FIELDVAL
					        				WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' A";
					        $this->db->query($sqlRESET);*/

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
					    }
					    else
					    {
					        /*$sqlRESET   = "UPDATE tbl_profitloss SET $FIELDNME = $FIELDNME-$FIELDVAL
					        				WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' B";
					        $this->db->query($sqlRESET);*/

							// L/R MANUFACTUR
								$ITMLRC		= strlen($ITM_LR);
								if($ITMLRC > 1)
								{
									$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								
							// L/R CONTRACTOR // ADDING COST OR EXPENS
								if($ITM_GROUP == 'M')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'ADM')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'GE')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'I')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'O')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$FIELDVAL
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
													AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
								elseif($ITM_GROUP == 'U')
								{
									$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$FIELDVAL 
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
										$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$FIELDVAL 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$FIELDVAL
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 9)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$FIELDVAL
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
									elseif($ITM_TYPE == 10)
									{
										$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$FIELDVAL
													WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
										$this->db->query($updLR);
									}
								}
								elseif($ITM_GROUP == 'T')
								{
									$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
												WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
									$this->db->query($updLR);
								}
					    }
					}
					else				// JIKA SUDAH TERKUNCI, GUNAKAN TANGGAL PROSES
					{
						$PERIODE	= date('Y-m-d', strtotime($DATENOW));
						$PERIODM	= date('m', strtotime($PERIODE));
						$PERIODY	= date('Y', strtotime($PERIODE));
						$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
						$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));

						// CEK APAKAH LR MASIH TERBUKA ATAU SUDAH TERKUNCI
							$sqlGetLR	= "SELECT LR_STAT FROM tbl_profitloss
											WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
							$resGetLR	= $this->db->query($sqlGetLR)->result();
							foreach($resGetLR as $rowLR):
								$LRSTAT = $rowLR->LR_STAT;
							endforeach;
							if($LRSTAT == 0)	// JIKA MASIH TERBUKA, GUNAKAN TANGGAL DOKUMEN
							{
								if($ADDTYPE == 'PLUS')
								{
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
							    }
							    elseif($ADDTYPE == 'MIN')
							    {
							    	// L/R MANUFACTUR
							    		$ITMLRC		= strlen($ITM_LR);
										if($ITMLRC > 1)
										{
											$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' CBD";
											$this->db->query($updLR);
										}
										
									// L/R CONTRACTOR // ADDING COST OR EXPENS
										if($ITM_GROUP == 'M')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'ADM')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'GE')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'I')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'O')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$FIELDVAL
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'U')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$FIELDVAL 
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
												$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$FIELDVAL 
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 9)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 10)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
							    }
							}
							else
							{
								$DATENOW1	= date('Y-m-d');
								$DATENOW	= date('Y-m-d', strtotime('+1 month', strtotime($DATENOW1)));
								$PERIODE	= date('Y-m-d', strtotime($DATENOW));
								$PERIODM	= date('m', strtotime($PERIODE));
								$PERIODY	= date('Y', strtotime($PERIODE));
								$NPERIODE1	= date('Y-m-t', strtotime($PERIODE));
								$NEWPERIODE	= date('Y-m-d', strtotime($NPERIODE1));

								if($ADDTYPE == 'PLUS')
								{
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
							    }
							    elseif($ADDTYPE == 'MIN')
							    {
							    	// L/R MANUFACTUR
							    		$ITMLRC		= strlen($ITM_LR);
										if($ITMLRC > 1)
										{
											$updLR	= "UPDATE tbl_profitloss SET $ITM_LR = $ITM_LR+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY' CXX";
											$this->db->query($updLR);
										}

									// L/R CONTRACTOR // ADDING COST OR EXPENS
										if($ITM_GROUP == 'M')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_MTR_REAL = BPP_MTR_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'ADM')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'GE')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_BAU_REAL = BPP_BAU_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'I')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_I_REAL = BPP_I_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'O')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_OTH_REAL = BPP_OTH_REAL+$FIELDVAL
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM'
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'SC' || $ITM_GROUP == 'SUB')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_SUBK_REAL = BPP_SUBK_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_ALAT_REAL = BPP_ALAT_REAL+$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' 
															AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
										elseif($ITM_GROUP == 'U')
										{
											$updLR	= "UPDATE tbl_profitloss SET BPP_UPH_REAL = BPP_UPH_REAL+$FIELDVAL 
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
												$updLR	= "UPDATE tbl_profitloss SET STOCK_MTR = STOCK_MTR-$FIELDVAL 
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 3 || $ITM_TYPE == 6)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 4 || $ITM_TYPE == 5)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_BBM = STOCK_BBM-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 9)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_WIP = STOCK_WIP-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
											elseif($ITM_TYPE == 10)
											{
												$updLR	= "UPDATE tbl_profitloss SET STOCK_FG = STOCK_FG-$FIELDVAL
															WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
												$this->db->query($updLR);
											}
										}
										elseif($ITM_GROUP == 'T')
										{
											$updLR	= "UPDATE tbl_profitloss SET STOCK_TOOLS = STOCK_TOOLS-$FIELDVAL 
														WHERE PRJCODE = '$PRJCODE' AND MONTH(PERIODE) = '$PERIODM' AND YEAR(PERIODE) = '$PERIODY'";
											$this->db->query($updLR);
										}
							    }
							}
					}
			}
	}
	
	function updateDocC($parameters) // OK
	{
		$DOC_CODE 	= $parameters['DOC_CODE'];
		$PRJCODE 	= $parameters['PRJCODE'];
		$DOC_TYPE 	= $parameters['DOC_TYPE'];
		$DOC_QTY 	= $parameters['DOC_QTY'];
		$DOC_VAL 	= $parameters['DOC_VAL'];
		$DOC_STAT 	= $parameters['DOC_STAT'];
		$CURR_DATE	= date('Y-m-d H:i:s');
		$CURR_DATE1	= date('Y-m-d');

		$TOT_DUM 	= 0;
		$TOT_VUM 	= 0;
		 // TANGGAL DISESUAIKAN DENGAN TANGGAL DOKUMEN
		if($DOC_TYPE == 'PR')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT PR_DATE AS CURR_DATE FROM tbl_pr_header WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_pr_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND PR_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_pr_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND PR_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.PR_TOTAL) AS TOT_DOCV FROM tbl_pr_detail A
								INNER JOIN tbl_pr_header B ON B.PR_NUM = A.PR_NUM AND B.PR_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'PO')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT PO_DATE AS CURR_DATE FROM tbl_po_header WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_po_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND PO_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_po_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND PO_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.PO_COST) AS TOT_DOCV FROM tbl_po_detail A
								INNER JOIN tbl_po_header B ON B.PO_NUM = A.PO_NUM AND B.PO_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'IR')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT IR_DATE AS CURR_DATE FROM tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND IR_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_ir_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND IR_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_ir_header WHERE PRJCODE = '$PRJCODE' AND IR_NUM = '$DOC_CODE' AND IR_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.ITM_TOTAL) AS TOT_DOCV FROM tbl_ir_detail A
								INNER JOIN tbl_ir_header B ON B.IR_NUM = A.IR_NUM AND B.IR_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'UM')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT UM_DATE AS CURR_DATE FROM tbl_um_header WHERE PRJCODE = '$PRJCODE' AND UM_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_um_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND UM_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_um_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND UM_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.ITM_TOTAL) AS TOT_DOCV FROM tbl_um_detail A
								INNER JOIN tbl_um_header B ON B.UM_NUM = A.UM_NUM AND B.UM_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'SO')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT SO_DATE AS CURR_DATE FROM tbl_so_header WHERE PRJCODE = '$PRJCODE' AND SO_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_so_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND SO_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_so_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND SO_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.SO_COST) AS TOT_DOCV FROM tbl_so_detail A
								INNER JOIN tbl_so_header B ON B.SO_NUM = A.SO_NUM AND B.SO_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'JO')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT JO_DATE AS CURR_DATE FROM tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_jo_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND JO_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_jo_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND JO_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				$TOT_DOCV	= 0;
				$sql_03 	= "SELECT SUM(A.ITM_TOTAL) AS TOT_DOCV FROM tbl_jo_detail A
								INNER JOIN tbl_jo_header B ON B.JO_NUM = A.JO_NUM AND B.JO_STAT IN (3,6)
								AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
				$res_03		= $this->db->query($sql_03)->result();
				foreach($res_03 as $row_03):
					$TOT_DOCV 	= $row_03->TOT_DOCV;
				endforeach;
				if($TOT_DOCV == "") $TOT_DOCV = 0;
		}
		elseif($DOC_TYPE == 'STF')
		{
			/*if($DOC_STAT == 'VOID')
			{*/
				$sql_00 	= "SELECT STF_DATE AS CURR_DATE FROM tbl_stf_header WHERE PRJCODE = '$PRJCODE' AND STF_NUM = '$DOC_CODE'";
				$res_00		= $this->db->query($sql_00)->result();
				foreach($res_00 as $row_00):
					$CURR_DATE 	= $row_00->CURR_DATE;
				endforeach;
				$CURR_DATE1	= date('Y-m-d', strtotime($CURR_DATE));
			//}

			// UPDATE DOC. DATE APPROVE
				$upd_01	= "UPDATE tbl_stf_header SET DOC_APPD = '$CURR_DATE' WHERE PRJCODE = '$PRJCODE' AND STF_NUM = '$DOC_CODE'";
				$this->db->query($upd_01);

			// COUNT TOTAL DOC
				$sql_02		= "tbl_stf_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND STF_STAT IN (3,6)";
				$TOT_DOCQ 	= $this->db->count_all($sql_02);

			// COUNT VALUE DOC
				// CATATAN PENTING : DALAM STF, DOC_QTY = BERAT PRODUKSI BUKAN BANYAKNYA DOKUMEN
					$TOT_DOCQ	= 0;
					$TOT_DOCV	= 0;
					$sql_03 	= "SELECT SUM(A.STF_VOLM) AS TOT_DOCQ, SUM(A.STF_TOTAL) AS TOT_DOCV
									FROM tbl_stf_detail A
										INNER JOIN tbl_stf_header B ON B.STF_NUM = A.STF_NUM AND B.STF_STAT IN (3,6)
										AND DATE(B.DOC_APPD) = '$CURR_DATE1'
									WHERE A.ITM_TYPE = 'OUT' AND A.ITM_CATEG = 'FG' AND A.STF_ISLAST = 1";
					$res_03		= $this->db->query($sql_03)->result();
					foreach($res_03 as $row_03):
						$TOT_DOCQ 	= $row_03->TOT_DOCQ;
						$TOT_DOCV 	= $row_03->TOT_DOCV;
					endforeach;
					if($TOT_DOCQ == "") $TOT_DOCQ = 0;
					if($TOT_DOCV == "") $TOT_DOCV = 0;

				// START ADDED TO UM DOC CONCLUSION
					// COUNT TOTAL DOC STF
						$sql_STF	= "tbl_stf_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND STF_STAT IN (3,6)";
						$TOT_DSTF 	= $this->db->count_all($sql_STF);

						$TOT_UMQ	= 0;
						$TOT_UMV	= 0;
						$sql_03a 	= "SELECT SUM(A.STF_VOLM) AS DOC_UMQ, SUM(A.STF_TOTAL) AS DOC_UMV
										FROM tbl_stf_detail A
											INNER JOIN tbl_stf_header B ON B.STF_NUM = A.STF_NUM AND B.STF_STAT IN (3,6)
											AND DATE(B.DOC_APPD) = '$CURR_DATE1'
										WHERE A.ITM_TYPE = 'IN' AND A.ITM_CATEG NOT IN ('WIP','FG')";
						$res_03a	= $this->db->query($sql_03a)->result();
						foreach($res_03a as $row_03a):
							$TOT_UMQ = $row_03a->DOC_UMQ;
							$TOT_UMV = $row_03a->DOC_UMV;
						endforeach;
						if($TOT_UMQ == "") $TOT_UMQ = 0;
						if($TOT_UMV == "") $TOT_UMV = 0;

					// PENGGUNAAN MATERIAL UM
						$sql_UM		= "tbl_um_header WHERE PRJCODE = '$PRJCODE' AND DATE(DOC_APPD) = '$CURR_DATE1' AND UM_STAT IN (3,6)";
						$TOT_DOCQUM = $this->db->count_all($sql_UM);

					// COUNT VALUE DOC
						$TOT_VOLUM	= 0;
						$sql_03UM 	= "SELECT SUM(A.ITM_TOTAL) AS TOT_VOLUM FROM tbl_um_detail A
										INNER JOIN tbl_um_header B ON B.UM_NUM = A.UM_NUM AND B.UM_STAT IN (3,6)
										AND DATE(B.DOC_APPD) = '$CURR_DATE1'";
						$res_03UM	= $this->db->query($sql_03UM)->result();
						foreach($res_03UM as $row_03UM):
							$TOT_VOLUM 	= $row_03UM->TOT_VOLUM;
						endforeach;
						if($TOT_VOLUM == "") $TOT_VOLUM = 0;

					// TOTAL UM QTY DOC AND TOT VALUE DOC
						$TOT_DUM 	= $TOT_DSTF + $TOT_DOCQUM;
						$TOT_VUM 	= $TOT_UMV + $TOT_VOLUM;
				// END ADDED TO UM DOC CONCLUSION
		}

		// CEK DOC CONCL.
			$sql_04		= "tbl_doc_concl WHERE PRJCODE = '$PRJCODE' AND DOC_DATE = '$CURR_DATE1'";
			$res_04		= $this->db->count_all($sql_04);

			if($res_04 == 0)
			{
				$sql_05	= "INSERT INTO tbl_doc_concl (PRJCODE, DOC_DATE, $DOC_QTY, $DOC_VAL)
							VALUES ('$PRJCODE', '$CURR_DATE1', '$TOT_DOCQ', '$TOT_DOCV')";
				$this->db->query($sql_05);

				if($DOC_TYPE == 'STF')
				{
					$sql_05	= "INSERT INTO tbl_doc_concl (PRJCODE, DOC_DATE, DOC_UMQ, DOC_UMV)
								VALUES ('$PRJCODE', '$CURR_DATE1', '$TOT_DUM', '$TOT_VUM')";
					$this->db->query($sql_05);
				}
			}
			else
			{
				$upd_05	= "UPDATE tbl_doc_concl SET $DOC_QTY = '$TOT_DOCQ', $DOC_VAL = '$TOT_DOCV'
							WHERE PRJCODE = '$PRJCODE' AND DOC_DATE = '$CURR_DATE1'";
				$this->db->query($upd_05);

				if($DOC_TYPE == 'STF')
				{
					$upd_05	= "UPDATE tbl_doc_concl SET DOC_UMQ = '$TOT_DUM', DOC_UMV = '$TOT_VUM'
								WHERE PRJCODE = '$PRJCODE' AND DOC_DATE = '$CURR_DATE1'";
					$this->db->query($upd_05);
				}
			}
	}
}
?>