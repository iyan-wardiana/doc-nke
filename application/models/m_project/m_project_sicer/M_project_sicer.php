<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 Maret 2017
 * File Name	= M_project_sicer.php
 * Notes		= -
*/
?>
<?php
class M_project_sicer extends CI_Model
{
	var $table 	= 'tbl_mcheader';
	var $table1 = 'tbl_projinv_realh';
	
	function count_all_num_rows($DefEmp_ID)  // OK
	{
		$sql	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project($DefEmp_ID)  // OK
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
				A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projectSIC($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_sicertificate A
					LEFT JOIN  	tbl_employee B ON A.SIC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProjSIC($PRJCODE) // OK
	{
		$sql	= "tbl_sicertificate A
					LEFT JOIN  	tbl_employee B ON A.SIC_EMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllSI($PRJCODE) // OK
	{
		/*$sql	= "tbl_siheader A
						LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.SI_STAT IN (2,3) AND A.SI_APPSTAT IN (0,1) limit 2";*/
		$sql	= "tbl_siheader A
						LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.SI_STAT IN (3)";
		return $this->db->count_all($sql);
	}
	
	function viewAllSI($PRJCODE) // OK
	{
		/*$sql	= "SELECT * FROM tbl_siheader A
						LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.SI_STAT IN (2,3) AND A.SI_APPSTAT IN (0,1)";*/
		$sql	= "SELECT * FROM tbl_siheader A
						LEFT JOIN  	tbl_employee B ON A.SI_EMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.SI_STAT IN (3)";
		return $this->db->query($sql);
	}
	
	function add($dataSIC) // OK
	{
		$this->db->insert('tbl_sicertificate', $dataSIC);
	}
	
	function updateSI($SI_CODE, $SI_APPSTAT) // OK
	{
		$sql = "UPDATE tbl_siheader SET SI_STAT = 3, SI_APPSTAT = $SI_APPSTAT WHERE SI_CODE = '$SI_CODE'";
		return $this->db->query($sql);
	}
	
	function get_SIC_by_number($SIC_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_sicertificate
				WHERE SIC_CODE = '$SIC_CODE'";
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
	
	function update($SIC_CODE, $dataSIC) // OK
	{
		$this->db->where('SIC_CODE', $SIC_CODE);
		return $this->db->update('tbl_sicertificate', $dataSIC);
	}
	
	function deleteDetail($SIC_CODE) // OK
	{
		$this->db->where('SIC_CODE', $SIC_CODE);
		$this->db->delete('tbl_sicertificatedet');
	}
	
	function updateDashData($parameters) // OK
	{		
		$DOC_CODE 		= $parameters['DOC_CODE'];
		$PRJCODE 		= $parameters['PRJCODE'];
		$TR_TYPE		= $parameters['TR_TYPE'];
		$TBL_NAME 		= $parameters['TBL_NAME'];
		$KEY_NAME		= $parameters['KEY_NAME'];
		$STAT_NAME 		= $parameters['STAT_NAME'];
		$APPSTATDOC 	= $parameters['APPSTATDOC'];
		$APPSTATDOCBEF 	= $parameters['APPSTATDOCBEF'];
			if($APPSTATDOCBEF == 0)
				$APPSTATDOCBEF	= 1;
		$FIELD_NM_CONF	= $parameters['FIELD_NM_CONF'];
		$FIELD_NM_APP	= $parameters['FIELD_NM_APP'];
		$FIELD_NM_DASH1	= $parameters['FIELD_NM_DASH1'];
		$FIELD_NM_DASH2	= $parameters['FIELD_NM_DASH2'];		
		
		$DOCSTAT1	= $APPSTATDOCBEF;	// STATUS 1 (OLD)
		$DOCSTAT2	= $APPSTATDOC;		// STATUS 2 (NEW STATUS)
		
		// SETTING FIELD_NAME LAMA DEFAULT
		if($DOCSTAT1 == 1)
			$FIELD_NAME1	= "TR_NEW";
		elseif($DOCSTAT1 == 2)
			$FIELD_NAME1	= "TR_CONFIRM";
		elseif($DOCSTAT1 == 3)
			$FIELD_NAME1	= "TR_APPROVED";
		elseif($DOCSTAT1 == 4)
			$FIELD_NAME1	= "TR_REVISE";
		elseif($DOCSTAT1 == 5)
			$FIELD_NAME1	= "TR_REJECT";
				
		// GET DOCUMENT STAT BEFORE
			/*$sql1 		= "$TBL_NAME WHERE $KEY_NAME = '$DOC_CODE' AND PRJCODE = '$PRJCODE'";
			$res1		= $this->db->count_all($sql1);
			if($res1 > 0)
			{
				$sqlSelC1 		= "SELECT $STAT_NAME AS DOCSTAT FROM $TBL_NAME WHERE $KEY_NAME = '$DOC_CODE' AND PRJCODE = '$PRJCODE'";
				$resSelC1		= $this->db->query($sqlSelC1)->result();
				foreach($resSelC1  as $rowSelC1) :
					$DOCSTAT1 	= $rowSelC1->DOCSTAT;
				endforeach;
				// SETTING FIELD_NAME LAMA
				if($DOCSTAT1 == 1)
					$FIELD_NAME1	= "TR_NEW";
				elseif($DOCSTAT1 == 2)
					$FIELD_NAME1	= "TR_CONFIRM";
				elseif($DOCSTAT1 == 3)
					$FIELD_NAME1	= "TR_APPROVED";
				elseif($DOCSTAT1 == 4)
					$FIELD_NAME1	= "TR_REVISE";
				elseif($DOCSTAT1 == 5)
					$FIELD_NAME1	= "TR_REJECT";
				echo "DOCSTAT1A = $DOCSTAT1 / $FIELD_NAME1<br>";
			}*/
			
		// SETTING FIELD_NAME BARU
		if($APPSTATDOC == 1)
			$FIELD_NAME2	= "TR_NEW";
		elseif($APPSTATDOC == 2)
			$FIELD_NAME2	= "TR_CONFIRM";
		elseif($APPSTATDOC == 3)
			$FIELD_NAME2	= "TR_APPROVED";
		elseif($APPSTATDOC == 4)
			$FIELD_NAME2	= "TR_REVISE";
		elseif($APPSTATDOC == 5)
			$FIELD_NAME2	= "TR_REJECT";
		
		// GET COUNT STATUS SEBELUMNYA = $DOCSTAT1
		$selDOC1 		= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = $DOCSTAT1"; // CONTOH : "tbl_sicertificate";
		$resDOC1		= $this->db->count_all($selDOC1);
		
		$selDOC2 		= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = $DOCSTAT2"; // CONTOH : "tbl_sicertificate";
		$resDOC2		= $this->db->count_all($selDOC2);
		// CEK TYPE DI DASHBOARD
		$sqlAC 			= "tbl_trans_count WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
		$ressqlAC		= $this->db->count_all($sqlAC);
				
		if($ressqlAC > 0)
		{
			echo "hehehe $DOCSTAT1 == $DOCSTAT2";
			if($DOCSTAT1 == $DOCSTAT2) // IF STAT 1 = STAT NEW
			{
				// TIDAK ADA PERUBAHAN				
			}
			else
			{
				// UPDATE FIRST STATUS (-)
				$TOT_NEW1		= $resDOC1;				
				$sqlUpd1		= "UPDATE tbl_trans_count SET $FIELD_NAME1 = $TOT_NEW1 WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
				$this->db->query($sqlUpd1);
					
				// UPDATE NEW STATUS (-)
				$TOT_NEW2		= $resDOC2;				
				$sqlUp2			= "UPDATE tbl_trans_count SET $FIELD_NAME2 = $TOT_NEW2 WHERE PRJCODE = '$PRJCODE' AND TR_TYPE = '$TR_TYPE'";
				$this->db->query($sqlUp2);
			}
		}
		else
		{
			$sqlIns			= "INSERT INTO tbl_trans_count (PRJCODE, TR_TYPE, $FIELD_NAME1) VALUES ('$PRJCODE', '$TR_TYPE', 1)";
			$this->db->query($sqlIns);
		}
		
		// PROSE INPUT / UPDATE KE TABLE tbl_dash_data
		if($APPSTATDOC == 2 || $APPSTATDOC == 3)
		{
			// SUM TOTAL CONFIRM VALUE IN TABLE
			$TOTAL_CONFIRM	= 0;
			$selTOTA 		= "SELECT SUM($FIELD_NM_CONF) AS TOTAL_CONFIRM, SUM($FIELD_NM_APP) AS TOTAL_APPROVED 
								FROM $TBL_NAME WHERE PRJCODE = '$PRJCODE'"; 										// CONTOH : "tbl_sicertificate";
			$resTOTA		= $this->db->query($selTOTA)->result();
			foreach($resTOTA  as $rowTOTA) :
				$TOTAL_CONFIRM 	= $rowTOTA->TOTAL_CONFIRM;
				if($TOTAL_CONFIRM == '')
					$TOTAL_CONFIRM = 0;
				$TOTAL_APPROVED	= $rowTOTA->TOTAL_APPROVED;
				if($TOTAL_APPROVED == '')
					$TOTAL_APPROVED = 0;
			endforeach;
			
			// CHECK DATA
			$sqlDashC 		= "tbl_dash_data WHERE PRJ_CODE = '$PRJCODE'";
			$resDashC		= $this->db->count_all($sqlDashC);
			if($resDashC > 0)
			{
				$sqlUpd1		= "UPDATE tbl_dash_data SET $FIELD_NM_DASH1 = $TOTAL_CONFIRM, $FIELD_NM_DASH2 = $TOTAL_APPROVED WHERE PRJ_CODE = '$PRJCODE'";
				$this->db->query($sqlUpd1);
			}
			else
			{
				$sqlIns			= "INSERT INTO tbl_dash_data (PRJ_CODE, $FIELD_NM_DASH1, $FIELD_NM_DASH2) VALUES ('$PRJCODE', $TOTAL_CONFIRM, $TOTAL_APPROVED)";
				$this->db->query($sqlIns);
			}
		}
	}
}
?>