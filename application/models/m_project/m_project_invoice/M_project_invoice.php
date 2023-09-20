<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 Maret 2017
 * File Name	= M_project_invoice.php
 * Notes		= -
*/
?>
<?php
class M_project_invoice extends CI_Model
{
	var $table 	= 'tbl_projinv_header';
	var $table1 = 'tbl_projinv_realh';

	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.PINV_MANNO LIKE '%$search%' ESCAPE '!' OR A.PINV_NOTES LIKE '%$search%' ESCAPE '!' 
					OR A.MC_REF LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.PINV_CODE, A.PINV_MANNO, A.PINV_STEP, A.PINV_CAT, A.PINV_SOURCE, A.PRJCODE, A.PINV_OWNER, A.PINV_DATE, A.PINV_ENDDATE,
							A.PINV_NOTES, A.PINV_EMPID, A.PINV_STAT, A.PINV_STATD, A.MC_REF, A.PINV_PROG, A.PINV_PROGVAL, A.GPINV_TOTVAL, A.PINV_PAIDAM
						FROM tbl_projinv_header A
							LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PINV_MANNO LIKE '%$search%' ESCAPE '!' OR A.PINV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR A.MC_REF LIKE '%$search%' ESCAPE '!') ORDER BY A.PINV_STAT, $order $dir";
			}
			else
			{
				$sql = "SELECT A.PINV_CODE, A.PINV_MANNO, A.PINV_STEP, A.PINV_CAT, A.PINV_SOURCE, A.PRJCODE, A.PINV_OWNER, A.PINV_DATE, A.PINV_ENDDATE,
							A.PINV_NOTES, A.PINV_EMPID, A.PINV_STAT, A.PINV_STATD, A.MC_REF, A.PINV_PROG, A.PINV_PROGVAL, A.GPINV_TOTVAL, A.PINV_PAIDAM
						FROM tbl_projinv_header A
							LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PINV_MANNO LIKE '%$search%' ESCAPE '!' OR A.PINV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR A.MC_REF LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.PINV_CODE, A.PINV_MANNO, A.PINV_STEP, A.PINV_CAT, A.PINV_SOURCE, A.PRJCODE, A.PINV_OWNER, A.PINV_DATE, A.PINV_ENDDATE,
							A.PINV_NOTES, A.PINV_EMPID, A.PINV_STAT, A.PINV_STATD, A.MC_REF, A.PINV_PROG, A.PINV_PROGVAL, A.GPINV_TOTVAL, A.PINV_PAIDAM
						FROM tbl_projinv_header A
							LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PINV_MANNO LIKE '%$search%' ESCAPE '!' OR A.PINV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR A.MC_REF LIKE '%$search%' ESCAPE '!') ORDER BY A.PINV_STAT, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.PINV_CODE, A.PINV_MANNO, A.PINV_STEP, A.PINV_CAT, A.PINV_SOURCE, A.PRJCODE, A.PINV_OWNER, A.PINV_DATE, A.PINV_ENDDATE,
							A.PINV_NOTES, A.PINV_EMPID, A.PINV_STAT, A.PINV_STATD, A.MC_REF, A.PINV_PROG, A.PINV_PROGVAL, A.GPINV_TOTVAL, A.PINV_PAIDAM
						FROM tbl_projinv_header A
							LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PINV_MANNO LIKE '%$search%' ESCAPE '!' OR A.PINV_NOTES LIKE '%$search%' ESCAPE '!' 
							OR A.MC_REF LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataMCC($PRJCODE, $search) // GOOD
	{
		$sql 	= "tbl_mcheader A
					WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
						AND (A.MC_MANNO LIKE '%$search%' ESCAPE '!' OR A.MC_NOTES LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataMCL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql 	= "SELECT A.*
							FROM tbl_mcheader A
							WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
								AND (A.MC_MANNO LIKE '%$search%' ESCAPE '!' OR A.MC_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql 	= "SELECT A.*
							FROM tbl_mcheader A
							WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
								AND (A.MC_MANNO LIKE '%$search%' ESCAPE '!' OR A.MC_NOTES LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql 	= "SELECT A.*
							FROM tbl_mcheader A
							WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
								AND (A.MC_MANNO LIKE '%$search%' ESCAPE '!' OR A.MC_NOTES LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
								LIMIT $start, $length";
			}
			else
			{
				$sql 	= "SELECT A.*
							FROM tbl_mcheader A
							WHERE A.PRJCODE = '$PRJCODE' AND MC_STAT = 3 AND MC_ISINV = 0
								AND (A.MC_MANNO LIKE '%$search%' ESCAPE '!' OR A.MC_NOTES LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rows($DefEmp_ID)  // OK
	{
		$sql	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project($DefEmp_ID)  // OK
	{	
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
				A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProjInv($PRJCODE) // OK
	{
		$sql	= "tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projinv($PRJCODE) // OK
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_projinv_header A
				LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				ORDER BY A.PINV_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsOwner() // OK
	{
		return $this->db->count_all('tbl_owner');
	}
	
	function viewOwner() // OK
	{
		$sql = "SELECT * FROM tbl_owner ORDER BY own_Name";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsEmpDept() // OK
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept() // OK
	{
		$this->db->select('Emp_ID, First_name, FlagUSER, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsProject() // OK
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // OK
	{
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME
				FROM tbl_project
				ORDER BY PRJCODE ASC";
		return $this->db->query($sql);
	}
	
	function get_PINV_by_number($PINV_CODE) // OK
	{
		$sql = "SELECT A.*
				FROM tbl_projinv_header A
				WHERE PINV_CODE = '$PINV_CODE'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($prohPINVH) // OK
	{
		$this->db->insert('tbl_projinv_header', $prohPINVH);
	}
	
	function updateMC($MC_CODE, $MC_APPSTAT) // OK
	{
		// CEK DI MC NORMAL
		$sql 		= "UPDATE tbl_mcheader SET MC_STAT = 3, MC_APPSTAT = $MC_APPSTAT, MC_ISINV = 1
						WHERE MC_CODE = '$MC_CODE'";
		$this->db->query($sql);
		
		// CEK DI MC GROUP
		$sql 		= "UPDATE tbl_mcg_header SET MCH_ISINV = 1
						WHERE MCH_CODE = '$MC_CODE'";
		$this->db->query($sql);
	}
	
	function updateSIC($PINV_SOURCE, $MC_APPSTAT) // OK
	{
		$sql = "UPDATE tbl_sicertificate SET SIC_STAT = 3, SIC_APPSTAT = $MC_APPSTAT WHERE SIC_CODE = '$PINV_SOURCE'";
		return $this->db->query($sql);
	}
	
	function update($PINV_CODE, $prohPINVH) // OK
	{
		$this->db->where('PINV_CODE', $PINV_CODE);
		$this->db->update('tbl_projinv_header', $prohPINVH);
	}
		
	function count_all_num_rows_PNo($txtSearch, $DefEmp_ID)  // 
	{
		$sql	= "tbl_project WHERE PRJCODE LIKE '%$txtSearch%' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_PNm($txtSearch, $DefEmp_ID) // 
	{
		$sql	= "tbl_project WHERE PRJNAME LIKE '%$txtSearch%' AND PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // 
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJCODE LIKE '%$txtSearch%'
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_project_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // 
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
				A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT PRJCODE FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.PRJNAME LIKE '%$txtSearch%'
				ORDER BY A.PRJCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_projINV_srcINV($txtSearch, $PRJCODE) // 
	{		
		$sql	= "tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PINV_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_projMatReq_srcPN($txtSearch, $PRJCODE) // 
	{
		$sql	= "tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE C.PRJNAME LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projinv_INVNo($limit, $offset, $txtSearch, $PRJCODE) // 
	{		
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE 
					A.PINV_CODE LIKE '%$txtSearch%' AND A.PRJCODE = '$PRJCODE'
				ORDER BY A.PINV_CODE ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projinv_PNm($limit, $offset, $txtSearch) // 
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, C.proj_Number, C.PRJCODE, C.PRJNAME
				FROM tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE
					C.PRJNAME LIKE '%$txtSearch%'
				ORDER BY A.PINV_CODE ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsAllMC($PRJCODE) // 
	{
		$sql = "tbl_mcheader WHERE PRJCODE = '$PRJCODE' AND MC_STAT = 2"; // Approve only
		return $this->db->count_all($sql);
	}
	
	function updateHeader($PINV_CODE, $proj_Code, $PINV_CAT, $maxNumb, $PINV_StepM, $AchievAmount, $Owner_Code) // U
	{
		$this->db->select('Display_Rows,decFormat');
		$resGlobal = $this->db->get('tglobalsetting')->result();
		foreach($resGlobal as $row) :
			$Display_Rows = $row->Display_Rows;
			$decFormat = $row->decFormat;
		endforeach;
		
		$sql = "SELECT PINV_Step, PINV_Date, AchievPercent, AchievAmount FROM tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_CODE = '$PINV_CODE'";
		$resultProj = $this->db->query($sql)->result();
		$DefAddend	= 0;
		
		foreach($resultProj as $row) :
			$PINV_Step		= $row->PINV_Step;
			$PINV_Date 		= $row->PINV_Date;
			//$PINV_Datex 	= date('Y-m-d', strtotime('-1 days', strtotime($PINV_Date)));
			$PINV_Datex 	= $PINV_Date;
			$AchievPercent 	= $row->AchievPercent; 												// A. Nilai Persentasi Prestasi
			$AchievPercentX	= number_format($AchievPercent, $decFormat);
			echo "A. Nilai Prestasi (%) : $AchievPercent = $AchievPercentX <br>";
			
			$AchievAmount 	= $row->AchievAmount;												// A.1  Nilai Prestasi
			if($PINV_CAT == 2) // Apabila DP
			{
				$AchievAmount 	= 0;
			}
			$AchievAmountX	= number_format($AchievAmount, $decFormat);
			echo "A.1. Nilai Prestasi :$AchievAmountX <br>";
			
			// Mencari detail addendum
			if($PINV_CAT == 1)
			{
				$sqlD = "SELECT adend_Value2A FROM tbl_projinv_detail WHERE proj_Code = '$proj_Code' AND PINV_CODE = '$PINV_CODE'";
				$resultProjD = $this->db->query($sqlD)->result();
				foreach($resultProjD as $rowD) :
					$adend_Value2A 	= $rowD->adend_Value2A;
					$DefAddend		= $DefAddend + $adend_Value2A;								// B.  Total Nilai Pekerjaan Tambahan
				endforeach;
			}
			else
			{
				$DefAddend		= 0;
			}
			$DefAddendX	= number_format($DefAddend, $decFormat);
			echo "B. Nilai Pek. Tamb :$DefAddendX <br>";
			
			$TotAddend		= $AchievAmount + $DefAddend;										// C.  Nilai Termasuk Pekerjaan Tambahan (A + B)
			$TotAddendX	= number_format($TotAddend, $decFormat);
			echo "C. Nilai + PkeTamb :$TotAddendX <br>";
			
			$sqlGetRetC = "tbl_project_spkh WHERE proj_Code = '$proj_Code' AND proj_ownCode = '$Owner_Code'";
			$resGetRetC = $this->db->count_all($sqlGetRetC);
			
			if($resGetRetC > 0)
			{
				$sqlGetRet 		= "SELECT proj_Retention FROM tbl_project_spkh
									WHERE proj_Code = '$proj_Code' AND proj_ownCode = '$Owner_Code'";
				$resGetRet 		= $this->db->query($sqlGetRet)->result();
				foreach($resGetRet as $rowGetRet) :
					$proj_Retention	= $rowGetRet->proj_Retention;
				endforeach;	
			}
			else
			{
				$proj_Retention	= 10;
			}
			
			$RetenValue		= $proj_Retention * $TotAddend / 100;								// D.  Nilai Retensi (10% x C)
			$RetenValueX	= number_format($RetenValue, $decFormat);
			echo "D. Nilai Retensi :$RetenValueX <br>";
			
			// Mencari Nilai Uang Muka. Khusus uang muka (DP) didapatkan dari nilai yang diinput saat ini
			$sqlDPC 		= "tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_CAT = 2 AND PINV_Date <= '$PINV_Datex'";
			$resultProjDPC 	= $this->db->count_all($sqlDPC);
			if($resultProjDPC > 0)
			{
				$AchievAmountDP	= 0;				
				if($PINV_CAT != 2)
				{
					$sqlDP 			= "SELECT AchievPercent, AchievAmount FROM tbl_projinv_header 
										WHERE proj_Code = '$proj_Code' AND PINV_CAT = 2 AND PINV_Date <= '$PINV_Datex'";
					$resultProjDP 	= $this->db->query($sqlDP)->result();
					foreach($resultProjDP as $rowDP) :
						$AchievAmountDPx	= $rowDP->AchievAmount;
						$AchievAmountDP		= $AchievAmountDP + $AchievAmountDPx;
					endforeach;	
				}
				else
				{
					$sqlDP 			= "SELECT AchievPercent, AchievAmount FROM tbl_projinv_header 
										WHERE proj_Code = '$proj_Code' AND PINV_CAT = 2 AND PINV_StepM = $PINV_StepM";
					$resultProjDP 	= $this->db->query($sqlDP)->result();
					foreach($resultProjDP as $rowDP) :
						$AchievAmountDPx	= $rowDP->AchievAmount;
						$AchievAmountDP		= $AchievAmountDP + $AchievAmountDPx;
					endforeach;	
				}
			}
			else
			{
				if($PINV_CAT == 2)
				{
					$sqlDP 			= "SELECT AchievPercent, AchievAmount FROM tbl_projinv_header 
										WHERE proj_Code = '$proj_Code' AND PINV_CAT = 2 AND PINV_StepM = $PINV_StepM";
					$resultProjDP 	= $this->db->query($sqlDP)->result();
					foreach($resultProjDP as $rowDP) :
						$AchievAmountDPx	= $rowDP->AchievAmount;
						$AchievAmountDP		= $AchievAmountDP + $AchievAmountDPx;
					endforeach;	
				}
				else
				{
					$AchievAmountDP			= 0;
				}
			}
			
			$DPValue		= $AchievAmountDP;													// E.  Nilai Uang Muka 10%
			$DPValueX		= number_format($DPValue, $decFormat);
			echo "E. Nilai DP 10% : $DPValueX <br>";
			
			$PotDPValue		= $AchievPercent / 100 * $DPValue;									// F.  Nilai Pemotongan Uang Muka (A x E)
			$PotDPValueX	= number_format($PotDPValue, $decFormat);
			echo "F. Nilai Pot. DP 10% :$PotDPValueX <br>";
			
			if($PINV_CAT == 2)
			{
				$PotDPValue	= 0;
			}						
			$PrestValueAft	= $TotAddend + $DPValue - $RetenValue - $PotDPValue;				// G.  Nilai Prestasi setelah dikurangi Retensi dan DP
			$PrestValueAftX	= number_format($PrestValueAft, $decFormat);
			echo "G. Nilai Prestasi set - Ret n DP :$PrestValueAftX <br>";
			
			// Untuk mengetahui nilai pemb. sebelumnya harus mengetahui nilai $thisPayment pada step sebelumnya
			$thisStep		= $maxNumb;
			$stepBefore		= $maxNumb - 1;
			$sqlSBC 		= "tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step = $stepBefore"; // Step Before
			$resultSBC 		= $this->db->count_all($sqlSBC);
			if($resultSBC > 0)
			{				
				$sqlSB 		= "SELECT PINV_CAT, thisPayment FROM tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step = $stepBefore"; // Step Before
				$resultSB 	= $this->db->query($sqlSB)->result();
				foreach($resultSB as $rowSB) :
					$PINV_CATB	= $rowSB->PINV_CAT;
					$thisPaymentSB	= $rowSB->thisPayment;										// H.  Nilai Pembayaran Sebelumnya
					$thisPaymentSBA	= $rowSB->thisPayment;										// H.  Nilai Pembayaran Sebelumnya
				endforeach;
				if($PINV_CATB == 2)
				{									
					$sqlSB 			= "SELECT PINV_CAT, thisPayment FROM tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step < $PINV_Step 
										AND PINV_Date < '$PINV_Date'";
					$resultSB 		= $this->db->query($sqlSB)->result();
					foreach($resultSB as $rowSB) :
						$thisPaymentSB	= $rowSB->thisPayment;
					endforeach;
					/*if($resultProjDPC > 0)
					{
						$sqlSB 		= "SELECT thisPayment FROM tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_CAT = 2 AND PINV_Date <= '$PINV_Datex' ORDER BY PINV_Step desc"; // Step Before
						$resultSB 	= $this->db->query($sqlSB)->result();
						foreach($resultSB as $rowSB) :	
							$thisPaymentSB	= $rowSB->thisPayment;										// H.  Nilai Pembayaran Sebelumnya
						endforeach;
					}*/
				}
			}
			else
			{
				$thisPaymentSB	= 0;															// H.  Nilai Pembayaran Sebelumnya
			}
			if($PINV_CAT == 2)
			{
				$thisPaymentSB	= 0;															// H.  Nilai Pembayaran Sebelumnya
			}
			$thisPaymentSBX	= number_format($thisPaymentSB, $decFormat);
			echo "H. Nilai Pemb. Sebelumnya :$PINV_CAT = $thisPaymentSBX <br>";
			
			$thisPrestVal	= $PrestValueAft - $thisPaymentSB;									// I.  Nilai Prestasi saat ini (G - H)
			$thisPrestValX	= number_format($thisPrestVal, $decFormat);
			echo "I. Nilai Prestasi Skr :$thisPrestValX <br>";
			
			// START : Untuk menjumlahkan nilai pembayaran sebelumnya dengan saat ini agar bisa digunakan untuk mengetahui nilai sebelumnya di next step
			$sqlSBy 		= "SELECT thisPayment FROM tbl_projinv_header WHERE proj_Code = '$proj_Code' AND PINV_Step = $stepBefore"; // Step Before
			$resultSBy 		= $this->db->query($sqlSBy)->result();
			foreach($resultSBy as $rowSBy) :
				$thisPaymentSBef	= $rowSB->thisPayment;
			endforeach;				
			$thisPayment	= $thisPrestVal + $thisPaymentSBef;			// Digunakan untuk mengetahui nilai pembayaran sebelumnya di next data
			//$thisPayment	= 0;
			echo "wkwkwk1 $thisPayment = $thisPrestVal + $thisPaymentSBef<br>";
			
			$thisPPnVal		= (0.1) * $thisPrestVal;											// J.  Nilai PPn (10% x I)
			$thisPPnValX	= number_format($thisPPnVal, $decFormat);
			echo "J. Nilai PPn :$thisPPnValX <br>";
			
			$thisPropVal	= $thisPrestVal + $thisPPnVal;										// K.  Nilai yang diajukan (I + J)
			$thisPropValX	= number_format($thisPropVal, $decFormat);
			echo "K. Nilai Pengajuan :$thisPropValX <br>";
		endforeach;
		
		/*$sqlUPH 	= "UPDATE tbl_projinv_header SET PINV_Step = $maxNumb, thisPayment =  $thisPayment, PINV_KwitAm =  $thisPrestVal, PINV_KwitAmPPn =  $thisPPnVal,
						RetentionAm = $RetenValue, CuttingDPAm = $PotDPValue
						WHERE proj_Code = '$proj_Code' AND PINV_CODE = '$PINV_CODE'";*/
		$sqlUPH 	= "UPDATE tbl_projinv_header SET PINV_Step = $maxNumb, thisPayment =  $thisPayment, PINV_KwitAm =  $thisPrestVal, PINV_KwitAmPPn =  $thisPPnVal,
						RetentionAm = $RetenValue, CuttingDPAm = $PotDPValue
						WHERE proj_Code = '$proj_Code' AND PINV_CODE = '$PINV_CODE'";
		$resulUPH 	= $this->db->query($sqlUPH);
	}
	
	function count_all_num_rowsInbox($DefEmp_ID)
	{
		$sql	= "tbl_projinv_header A
					LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
					WHERE A.proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rowsProjInv1($proj_Code)
	{
		$sql = "tbl_projinv_header A
				LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.proj_Code = '$proj_Code'
				AND A.PINV_Status = 2
				ORDER BY A.PINV_CODE ASC";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_byProjID($proj_Code)
	{		
		$this->db->where('proj_Code', $proj_Code);
		return $this->db->count_all('tbl_projinv_header');
	}
	
	function get_last_ten_projinvInb($proj_Code, $limit, $offset)
	{
		$sql = "SELECT A.PINV_ID, A.PINV_CODE, A.PINV_Date, A.req_date, A.PINV_EndDate, A.PINV_Class, A.PINV_Type, A.proj_ID, A.proj_Code, A.Owner_Code, A.PINV_EmpID, A.PINV_Notes, A.PINV_Status, 
				A.PINV_STAT, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_header A
				LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.proj_Code = '$proj_Code'
				AND A.PINV_Status = 2
				ORDER BY A.PINV_CODE ASC";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projinvInb_MRNo($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.PINV_ID, A.PINV_CODE, A.PINV_Date, A.req_date, A.PINV_EndDate, A.PINV_Class, A.PINV_Type, A.proj_ID, A.proj_Code, A.Owner_Code, A.PINV_EmpID, A.PINV_Notes, A.PINV_Status, 
				A.PINV_STAT, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_header A
				LEFT JOIN  	tbl_employee B ON A.PINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.PINV_Status = 2
				AND A.PINV_CODE LIKE '%$txtSearch%'
				ORDER BY A.PINV_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVend() //
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function viewvendor()
	{
		$sql = "SELECT SPLCODE AS Vend_Code, SPLDESC AS Vend_Name, SPLADD1 AS Vend_Address
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
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function viewAllPR()
	{				
		$sql = "SELECT A.PINV_CODE, A.PINV_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tbl_projinv_header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.PINV_CODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function delete($PINV_CODE)
	{
		$this->db->where('PINV_CODE', $PINV_CODE);
		$this->db->delete($this->table);
	}
	
	function deleteDetail($PINV_CODE)
	{
		$this->db->where('PINV_CODE', $PINV_CODE);
		$this->db->delete('tbl_projinv_detail');
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
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name, A.itemConvertion
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function viewAllItemMatBudget($proj_Code)
	{		
		$sql		= "SELECT Z.Item_Code, Z.PPMat_Qty, Z.PPMat_Qty2, Z.request_qty, Z.request_qty2,
						A.ItemCodeH,A.ItemCodeCat,A.ItemCodeProj,A.ConstStep,
						A.ItemCodeMan,A.ItemCodeRN,A.lastNoItem, A.itemConvertion,
						A.Item_Name,A.Item_Qty,A.Item_Qty2,A.Unit_Type_ID1,A.Unit_Type_ID2,a.serialNumber,B.Unit_Type_Code,B.Unit_Type_Name
						FROM tprojplan_material Z
						INNER JOIN Titem A ON Z.Item_Code = A.Item_Code
						INNER JOIN tunittype B ON B.unit_type_id = A.unit_type_id1
						WHERE Z.proj_Code = '$proj_Code' 
						AND A.ItemCodeCat = 'MTRL' ORDER BY Item_Code";
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
					WHERE PINV_STAT NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('PINV_STAT', 0);
		$this->db->where('PINV_STAT', 1);
		$this->db->where('PINV_STAT', 2);
		return $this->db->count_all('TPO_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.PINV_CODE, A.PR_Date, A.PINV_STAT, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.PINV_CODE";
		
		/*$this->db->select('PINV_CODE, PR_Date, PINV_STAT, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function get_last_ten_projectInbox($limit, $offset) //
	{		
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT DISTINCT A.proj_ID, A.proj_Number, A.proj_Code, A.proj_Name, A.proj_Date, A.proj_StartDate, A.proj_EndDate, A.proj_Type, A.proj_PM_EmpID, 
				A.proj_CustCode, A.proj_Status,
				B.First_Name, B.Middle_Name, B.Last_Name, C.Cust_Name
				FROM tbl_project A
				LEFT JOIN  	tbl_employee B ON A.proj_PM_EmpID = B.Emp_ID
				LEFT JOIN 	tcustomer C ON A.proj_CustCode = C.Cust_Code
				INNER JOIN	tbl_projinv_header D ON A.proj_Code = D.PRJCODE
				ORDER BY A.proj_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
		
	}
	
	// Update Project Plan Material
	function updatePP($PO_Number, $parameters)
	{		
		$PINV_CODE = $parameters['PINV_CODE'];
    	$Item_code = $parameters['Item_code'];
		$proj_ID = $parameters['proj_ID'];
		$proj_Code = $parameters['proj_Code'];
    	$request_qty1 = $parameters['request_qty1'];
    	$request_qty2 = $parameters['request_qty2'];
		
		$sql1		= "SELECT request_qty, request_qty2 FROM tprojplan_material WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		$resMRQty	= $this->db->query($sql1)->result();
		foreach($resMRQty as $row) :
			$MR_Qtya = $row->request_qty;
			$MR_Qty2a = $row->request_qty2;
		endforeach;
		$totMRQty1	= $request_qty1 + $MR_Qtya;
		$totMRQty2	= $request_qty2 + $MR_Qty2a;
		
		$sql2		= "UPDATE tprojplan_material SET request_qty = $totMRQty1, request_qty2 = $totMRQty2
						WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		return $this->db->query($sql2);
	}
}
?>