<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 April 2017
 * File Name	= M_asset_usage.php
 * Location		= -
*/

class M_asset_usage extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_asset_usage A
					INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
				WHERE A.PRJCODE = '$PRJCODE'
					AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
					OR A.AU_CODE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE'
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataC_1n2($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_asset_usage A
					INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.AU_STAT IN (2,7)
					AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
					OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
					OR A.AU_CODE LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL_1n2($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.AU_STAT IN (2,7)
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.AU_STAT IN (2,7)
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.AU_STAT IN (2,7)
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*, B.AS_NAME
						FROM tbl_asset_usage A
							INNER JOIN tbl_asset_list B ON B.AS_CODE = A.AU_AS_CODE
						WHERE A.PRJCODE = '$PRJCODE' AND A.AU_STAT IN (2,7)
							AND (B.AS_NAME LIKE '%$search%' ESCAPE '!' OR A.AU_DATE LIKE '%$search%' ESCAPE '!' 
							OR A.AU_DESC LIKE '%$search%' ESCAPE '!' OR A.STATDESC LIKE '%$search%' ESCAPE '!'
							OR A.AU_CODE LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rows($PRJCODE) // OK
	{
		$sql		= "tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AU($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // HOLD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllAsset($PRJCODE, $StartDate, $EndDate) // OK
	{
		//$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate";
		//$sql		= "tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE'";
		$sql		= "tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' AND AS_STAT != 9";
		return $this->db->count_all($sql);
	}
	
	function viewAllIAsset($PRJCODE, $StartDate, $EndDate) // OK
	{
		//$sql		= "SELECT * FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate ORDER BY AUR_DATE ASC";
		//$sql		= "SELECT * FROM tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' ORDER BY AS_NAME";
		$sql		= "SELECT * FROM tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' AND AS_STAT != 9 ORDER BY AS_NAME";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsAllItem($PRJCODE) // OK
	{
		$sql		= "tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
							AND Z.ISRENT = 1 OR Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISWAGE = 1";
		return $this->db->count_all($sql);
	}
	
	function viewAllItemMatBudget($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_TYPE, Z.ITM_UNIT, Z.UMCODE, Z.ITM_VOLM, Z.ITM_PRICE,
							 Z.ITM_IN, Z.ITM_OUT, Z.ITM_TOTALP, Z.ISRENT, Z.ISPART, Z.ISFUEL, Z.ISLUBRIC, Z.ISFASTM, Z.ISWAGE, Z.ITM_KIND,
							B.Unit_Type_Name
						FROM tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
							AND (Z.ISRENT = 1 OR Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISWAGE = 1)
						ORDER BY Z.ITM_CODE";
		return $this->db->query($sql);
	}
	
	function add($InsAG) // OK
	{
		$this->db->insert('tbl_asset_usage', $InsAG);
	}
				
	function get_AU($AU_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_asset_usage
				WHERE AU_CODE = '$AU_CODE'";
		return $this->db->query($sql);
	}
	
	function update($AU_CODE, $UpdAU) // OK
	{
		$this->db->where('AU_CODE', $AU_CODE);
		return $this->db->update('tbl_asset_usage', $UpdAU);
	}
	
	function updateAST($AS_CODE, $UpdAS) // OK
	{
		$this->db->where('AS_CODE', $AS_CODE);
		$this->db->update('tbl_asset_list', $UpdAS);
	}
	
	function updateEXP($AU_CODE, $PRJCODE, $AP_HOPR, $AP_QTYOPR) // OK
	{
		$GITM_TOTAL		= 0;
		$sqlStartITM	= "SELECT ITM_CODE, ITM_QTY, ITM_PRICE, ITM_TOTAL FROM tbl_asset_usagedet WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$ITM_CODE 	= $rowSITM->ITM_CODE;
			$ITM_PRICE 	= $rowSITM->ITM_PRICE;
			$ITM_TOTAL 	= $rowSITM->ITM_TOTAL;
			$GITM_TOTAL	= $GITM_TOTAL + $ITM_TOTAL;
		endforeach;
		
		$AP_HEXP		= $GITM_TOTAL / $AP_HOPR;
		$AP_QTYEXP		= $GITM_TOTAL / $AP_QTYOPR;
		
		// UPDATE EXPENSES
		$sqlUpDet	= "UPDATE tbl_asset_usage SET AP_HEXP = $AP_HEXP, AP_QTYEXP = $AP_QTYEXP
						WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
		return $this->db->query($sqlUpDet);
	}
	
	function deleteDetail($AU_CODE, $PRJCODE) // OK
	{
		$this->db->where('AU_CODE', $AU_CODE);
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->delete('tbl_asset_usagedet');
	}
	
	function deleteJH($JournalH_Code) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		$this->db->delete('tbl_journalheader');
	}
	
	function deleteJD($JournalH_Code, $JournalType) // OK
	{
		$this->db->where('JournalH_Code', $JournalH_Code);
		//$this->db->where('JournalType', $JournalType);
		$this->db->delete('tbl_journaldetail');
	}
	
	function updateITM($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AU_CODE 	= $parameterss['AU_CODE'];
    	$ITM_CODE 	= $parameterss['ITM_CODE'];
		$ITM_QTY 	= $parameterss['ITM_QTY'];
		$ITM_PRICE 	= $parameterss['ITM_PRICE'];
		//$ITM_KIND 	= $parameterss['ITM_KIND'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$StartVOL		= 0;
		$StartPRC		= 0;
		$StartTPRC		= 0;
		$StartOUT		= 0;
		$StartOUTP		= 0;
		$sqlStartITM	= "SELECT ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_OUT, ITM_OUTP FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$StartVOL 	= $rowSITM->ITM_VOLM; 	// like as Last Volume
			$StartPRC 	= $rowSITM->ITM_PRICE;	// like as Last Price Average
			$StartTPRC 	= $rowSITM->ITM_TOTALP;	// like as Last Total Price
			$StartOUT 	= $rowSITM->ITM_OUT;	// like as Last Total OUT
			$StartOUTP 	= $rowSITM->ITM_OUTP;	// like as Last Total OUT Price
		endforeach;
		
		$EndVOL			= $StartVOL - $ITM_QTY;
		$EndTPRC		= $StartTPRC - $ITM_TOTALP;
		if($EndTPRC == 0)
		{
			$EndPRC	= $StartPRC;
		}
		else
		{
			if($EndVOL == 0)
				$EndVOL1 = 1;
			$EndPRC		= $EndTPRC / $EndVOL1;
		}
		
		$EndOUT			= $StartOUT + $ITM_QTY;
		$EndOUTP		= $StartOUTP + $ITM_TOTALP;
				
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = $EndVOL, ITM_PRICE = $EndPRC, ITM_TOTALP = $EndTPRC, ITM_OUT = $EndOUT, ITM_OUTP = $EndOUTP, LAST_TRXNO = '$AU_CODE'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";	
		return $this->db->query($sqlUpDet);
	}
	
	function updateAUKIND($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AU_CODE 	= $parameterss['AU_CODE'];
		$ITM_KIND 	= $parameterss['ITM_KIND'];
		
		//UPDATE TYPE EXPENSE KE HEADER
			if($ITM_KIND == 1)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTRENT, SUM(ITM_TOTAL) AS TOTRENTP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 1 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTRENT 	= $rowQtyKind->TOTRENT;
					$TOTRENTP 	= $rowQtyKind->TOTRENTP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISRENT = $TOTRENT, ISRENTP = $TOTRENTP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 2)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTPART, SUM(ITM_TOTAL) AS TOTPARTP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 2 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTPART 	= $rowQtyKind->TOTPART;
					$TOTPARTP 	= $rowQtyKind->TOTPARTP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISPART = $TOTPART, SET ISPARTP = $TOTPARTP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 3)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTFUEL, SUM(ITM_TOTAL) AS TOTFUELP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 3 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTFUEL 	= $rowQtyKind->TOTFUEL;
					$TOTFUELP 	= $rowQtyKind->TOTFUELP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISFUEL = $TOTFUEL, ISFUELP = $TOTFUELP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 4)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTLUBRIC, SUM(ITM_TOTAL) AS TOTLUBRICP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 4 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTLUBRIC 	= $rowQtyKind->TOTLUBRIC;
					$TOTLUBRICP	= $rowQtyKind->TOTLUBRICP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISLUBRIC = $TOTLUBRIC, ISLUBRICP = $TOTLUBRICP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 5)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTFASTM, SUM(ITM_TOTAL) AS TOTFASTMP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 5 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTFASTM 	= $rowQtyKind->TOTFASTM;
					$TOTFASTMP 	= $rowQtyKind->TOTFASTMP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISFASTM = $TOTFASTM, ISFASTMP = $TOTFASTMP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 6)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTWAGE, SUM(ITM_TOTAL) AS TOTWAGEP FROM tbl_asset_usagedet A
									INNER JOIN tbl_asset_usage B ON A.AU_CODE = B.AU_CODE
								WHERE A.ITM_KIND = 6 AND B.AU_CODE = '$AU_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTWAGE 	= $rowQtyKind->TOTWAGE;
					$TOTWAGEP 	= $rowQtyKind->TOTWAGEP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_usage SET ISWAGE = $TOTWAGE, ISWAGEP = $TOTWAGEP WHERE AU_CODE = '$AU_CODE' AND PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
	}
	
	function updateITMPLUS($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AU_CODE 	= $parameterss['AU_CODE'];
		$AU_CODE 	= "V$AU_CODE";
    	$ITM_CODE 	= $parameterss['ITM_CODE'];
		$ITM_QTY 	= $parameterss['ITM_QTY'];
		$ITM_PRICE 	= $parameterss['ITM_PRICE'];
		$ITM_TOTALP	= $ITM_QTY * $ITM_PRICE;
		
		// Mendapatkan Qty Awal
		$StartVOL		= 0;
		$StartPRC		= 0;
		$StartTPRC		= 0;
		$StartOUT		= 0;
		$StartOUTP		= 0;
		$sqlStartITM	= "SELECT ITM_VOLM, ITM_PRICE, ITM_TOTALP, ITM_OUT, ITM_OUTP FROM tbl_item WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";
		$resStartITM	= $this->db->query($sqlStartITM)->result();
		foreach($resStartITM as $rowSITM) :
			$StartVOL 	= $rowSITM->ITM_VOLM; 	// like as Last Volume
			$StartPRC 	= $rowSITM->ITM_PRICE;	// like as Last Price Average
			$StartTPRC 	= $rowSITM->ITM_TOTALP;	// like as Last Total Price
			$StartOUT 	= $rowSITM->ITM_OUT;	// like as Last Total OUT
			$StartOUTP 	= $rowSITM->ITM_OUTP;	// like as Last Total OUT Price
		endforeach;
		
		$EndVOL			= $StartVOL + $ITM_QTY;
		$EndTPRC		= $StartTPRC + $ITM_TOTALP;
		$EndPRC			= $EndTPRC / $EndVOL;
		
		$EndOUT			= $StartOUT - $ITM_QTY;
		$EndOUTP		= $StartOUTP - $ITM_TOTALP;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = $EndVOL, ITM_PRICE = $EndPRC, ITM_TOTALP = $EndTPRC, ITM_OUT = $EndOUT, ITM_OUTP = $EndOUTP, LAST_TRXNO = '$AU_CODE'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";	
		return $this->db->query($sqlUpDet);
	}
	
	function count_all_num_rowsAllAUR($PRJCODE) // OK
	{
		$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function viewAllIAUR($PRJCODE) // OK
	{		
		$sql		= "SELECT * FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STAT = 3
						ORDER BY AUR_DATE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project 
						WHERE 
							PRJCODE IN (SELECT PRJCODE FROM tbl_asset_usage 
											WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AU_STAT = 2)";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT PRJCODE FROM tbl_asset_usage 
												WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AU_STAT = 2)
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_inb($PRJCODE) // OK
	{
		$sql		= "tbl_asset_usage WHERE PRJCODE = '$PRJCODE' AND AU_STAT = 2";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AU_inb($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_usage WHERE PRJCODE = '$PRJCODE' AND AU_STAT = 2";
		return $this->db->query($sql);
	}
	
	function createJobReport($InsReport) // OK
	{
		$this->db->insert('tbl_asset_rjob', $InsReport);
	}
}
?>