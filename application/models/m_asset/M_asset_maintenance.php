<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 April 2017
 * File Name	= M_asset_maintenance.php
 * Location		= -
*/

class M_asset_maintenance extends CI_Model
{
	function count_all_num_rows($PRJCODE) // OK
	{
		$sql		= "tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AU($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // HOLD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem($PRJCODE) // OK
	{
		$sql		= "tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
							AND Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISPART = 1";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rowsAllAsset($PRJCODE) // OK
	{
		//$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate";
		//$sql		= "tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE'";
		$sql		= "tbl_asset_list";
		return $this->db->count_all($sql);
	}
	
	function viewAllIAsset($PRJCODE) // OK
	{
		//$sql		= "SELECT * FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate ORDER BY AUR_DATE ASC";
		//$sql		= "SELECT * FROM tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE' ORDER BY AS_NAME";
		$sql		= "SELECT * FROM tbl_asset_list ORDER BY AS_NAME";
		return $this->db->query($sql);
	}
	
	function viewAllItemMatBudget($PRJCODE) // OK
	{
		$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_TYPE, Z.ITM_UNIT, Z.UMCODE, Z.ITM_VOLM, Z.ITM_PRICE, Z.ITM_KIND,
							Z.ITM_IN, Z.ITM_OUT, Z.ITM_TOTALP,
							B.Unit_Type_Name
						FROM tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
							AND Z.ISFUEL = 1 OR Z.ISLUBRIC = 1 OR Z.ISFASTM = 1 OR Z.ISPART = 1
						ORDER BY Z.ITM_CODE";
		return $this->db->query($sql);
	}
	
	function add($InsAG) // OK
	{
		$this->db->insert('tbl_asset_mainten', $InsAG);
	}
	
	function updateITM($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AM_CODE 	= $parameterss['AM_CODE'];
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
		
		$EndVOL			= $StartVOL - $ITM_QTY;
		$EndTPRC		= $StartTPRC - $ITM_TOTALP;
		$EndPRC			= $EndTPRC / $EndVOL;
		
		$EndOUT			= $StartOUT + $ITM_QTY;
		$EndOUTP		= $StartOUTP + $ITM_TOTALP;
		
		// UPDATE DETAIL
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = $EndVOL, ITM_PRICE = $EndPRC, ITM_TOTALP = $EndTPRC, ITM_OUT = $EndOUT, ITM_OUTP = $EndOUTP, LAST_TRXNO = '$AM_CODE'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";	
		return $this->db->query($sqlUpDet);
	}
	
	function updateAUKIND($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AM_CODE 	= $parameterss['AM_CODE'];
		$ITM_KIND 	= $parameterss['ITM_KIND'];
		
		//UPDATE TYPE EXPENSE KE HEADER
			if($ITM_KIND == 1)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTRENT, SUM(ITM_TOTAL) AS TOTRENTP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 1 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTRENT 	= $rowQtyKind->TOTRENT;
					$TOTRENTP 	= $rowQtyKind->TOTRENTP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISRENT = $TOTRENT, ISRENTP = $TOTRENTP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 2)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTPART, SUM(ITM_TOTAL) AS TOTPARTP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 2 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTPART 	= $rowQtyKind->TOTPART;
					$TOTPARTP 	= $rowQtyKind->TOTPARTP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISPART = $TOTPART, ISPARTP = $TOTPARTP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 3)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTFUEL, SUM(ITM_TOTAL) AS TOTFUELP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 3 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTFUEL 	= $rowQtyKind->TOTFUEL;
					$TOTFUELP 	= $rowQtyKind->TOTFUELP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISFUEL = $TOTFUEL, ISFUELP = $TOTFUELP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 4)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTLUBRIC, SUM(ITM_TOTAL) AS TOTLUBRICP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 4 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTLUBRIC 	= $rowQtyKind->TOTLUBRIC;
					$TOTLUBRICP	= $rowQtyKind->TOTLUBRICP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISLUBRIC = $TOTLUBRIC, ISLUBRICP = $TOTLUBRICP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 5)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTFASTM, SUM(ITM_TOTAL) AS TOTFASTMP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 5 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTFASTM 	= $rowQtyKind->TOTFASTM;
					$TOTFASTMP 	= $rowQtyKind->TOTFASTMP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISFASTM = $TOTFASTM, ISFASTMP = $TOTFASTMP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
			if($ITM_KIND == 6)
			{
				$sqlQtyKind	= "SELECT SUM(ITM_QTY) AS TOTWAGE, SUM(ITM_TOTAL) AS TOTWAGEP FROM tbl_asset_maintendet A
									INNER JOIN tbl_asset_mainten B ON A.AM_CODE = B.AM_CODE
								WHERE A.ITM_KIND = 6 AND B.AM_CODE = '$AM_CODE'";
				$resQtyKind	= $this->db->query($sqlQtyKind)->result();
				foreach($resQtyKind as $rowQtyKind) :
					$TOTWAGE 	= $rowQtyKind->TOTWAGE;
					$TOTWAGEP 	= $rowQtyKind->TOTWAGEP;
				endforeach;
				$sqlUpDetx	= "UPDATE tbl_asset_mainten SET ISWAGE = $TOTWAGE, ISWAGEP = $TOTWAGEP WHERE AM_CODE = '$AM_CODE' AND AM_PRJCODE = '$PRJCODE'";	
				return $this->db->query($sqlUpDetx);
			}
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
	
	function updateITMPLUS($parameterss) // OK
	{
    	$PRJCODE 	= $parameterss['PRJCODE'];
		$AM_CODE 	= $parameterss['AM_CODE'];
		$AM_CODE 	= "V$AM_CODE";
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
		$sqlUpDet	= "UPDATE tbl_item SET ITM_VOLM = $EndVOL, ITM_PRICE = $EndPRC, ITM_TOTALP = $EndTPRC, ITM_OUT = $EndOUT, ITM_OUTP = $EndOUTP, LAST_TRXNO = '$AM_CODE'
						WHERE ITM_CODE = '$ITM_CODE' AND PRJCODE = '$PRJCODE' AND STATUS = 1";	
		return $this->db->query($sqlUpDet);
	}
	
	function get_AU($AM_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_asset_mainten
				WHERE AM_CODE = '$AM_CODE'";
		return $this->db->query($sql);
	}
	
	function update($AM_CODE, $UpdAU) // 
	{
		$this->db->where('AM_CODE', $AM_CODE);
		$this->db->update('tbl_asset_mainten', $UpdAU);
	}
	
	function updateAST($AS_CODE, $UpdAS) // OK
	{
		$this->db->where('AS_CODE', $AS_CODE);
		$this->db->update('tbl_asset_list', $UpdAS);
	}
	
	function deleteDetail($AM_CODE, $AM_PRJCODE) // OK
	{
		$this->db->where('AM_CODE', $AM_CODE);
		$this->db->where('AM_PRJCODE', $AM_PRJCODE);
		$this->db->delete('tbl_asset_maintendet');
	}
	
	function count_all_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project 
						WHERE 
							PRJCODE IN (SELECT AM_PRJCODE FROM tbl_asset_mainten 
											WHERE AM_PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AM_STAT = 2)";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT AM_PRJCODE FROM tbl_asset_mainten 
												WHERE AM_PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AM_STAT = 2)
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_inb($PRJCODE) // OK
	{
		$sql		= "tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE' AND AM_STAT = 2";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AM_inb($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_mainten WHERE AM_PRJCODE = '$PRJCODE' AND AM_STAT = 2";
		return $this->db->query($sql);
	}
	
	function createCostReport($InsReport) // OK
	{
		$this->db->insert('tbl_asset_rcost', $InsReport);
	}
	
	function delMntDet($AM_CODE, $AM_PRJCODE) // OK
	{
		$this->db->where('RASTC_CODE', $AM_CODE);
		$this->db->where('RASTC_PRJCODE', $AM_PRJCODE);
		$this->db->delete('tbl_asset_rcost');
	}
}
?>