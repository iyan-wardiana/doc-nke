<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 26 Mei 2017
 * File Name	= m_spp.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 24 Agustus 2017
 * File Name	= m_office_room.php
 * Location		= -
*/
?>
<?php
class M_office_room extends CI_Model
{
	function count_all_num_rows($DefEmp_ID) // OK
	{
		$sql	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_room($DefEmp_ID) // OK
	{
		$sql = "SELECT * from tbl_office_room ORDER BY ROOM_CODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsMR($PRJCODE) // OK
	{
		$sql	= "tbl_spp_header A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	/*function get_last_mail($MG_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_mailgroup_header
				WHERE MG_CODE = '$MG_CODE'
				ORDER BY MG_CODE ASC";
		return $this->db->query($sql);
	}*/
	
	function count_all_num_rowsProject() // OK
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // OK
	{
		$sql = "SELECT PRJCODE, PRJNAME
				FROM tbl_project
				ORDER BY PRJNAME ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem() // OK
	{
		return $this->db->count_all('tbl_office_inventory');
	}
	
	function viewAllItemMatBudget() // OK
	{		
		$sql		= "select * from tbl_office_inventory ORDER BY inv_code";
		return $this->db->query($sql);
	}
	
	function add($projMatReqH) // OK
	{
		$this->db->insert('tbl_office_room', $projMatReqH);
	}
	
	function get_room_by_number($ROOM_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_office_room
				WHERE ROOM_CODE = '$ROOM_CODE'";
		return $this->db->query($sql);
	}
	
	function update($ROOM_CODE, $projMatReqH) // OK
	{
		$this->db->where('ROOM_CODE', $ROOM_CODE);
		$this->db->update('tbl_office_room', $projMatReqH);
	}
	
	function deleteDetail($ROOM_CODE) // OK
	{
		//$this->db->where('SPPNUM', $SPPNUM);
		$this->db->where('ROOM_CODE', $ROOM_CODE);
		$this->db->delete('tbl_office_room_detail');
	}
	
	/*function updateDashData($parameters) // OK
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
		//	$sql1 		= "$TBL_NAME WHERE $KEY_NAME = '$DOC_CODE' AND PRJCODE = '$PRJCODE'";
		//	$res1		= $this->db->count_all($sql1);
		//	if($res1 > 0)
		//	{
		//		$sqlSelC1 		= "SELECT $STAT_NAME AS DOCSTAT FROM $TBL_NAME WHERE $KEY_NAME = '$DOC_CODE' AND PRJCODE = '$PRJCODE'";
		//		$resSelC1		= $this->db->query($sqlSelC1)->result();
		//		foreach($resSelC1  as $rowSelC1) :
		//			$DOCSTAT1 	= $rowSelC1->DOCSTAT;
		//		endforeach;
		//		// SETTING FIELD_NAME LAMA
		//		if($DOCSTAT1 == 1)
		//			$FIELD_NAME1	= "TR_NEW";
		//		elseif($DOCSTAT1 == 2)
		//			$FIELD_NAME1	= "TR_CONFIRM";
		//		elseif($DOCSTAT1 == 3)
		//			$FIELD_NAME1	= "TR_APPROVED";
		//		elseif($DOCSTAT1 == 4)
		//			$FIELD_NAME1	= "TR_REVISE";
		//		elseif($DOCSTAT1 == 5)
		//			$FIELD_NAME1	= "TR_REJECT";
		//		echo "DOCSTAT1A = $DOCSTAT1 / $FIELD_NAME1<br>";
		//	}
			
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
		
		//$selDOC2 		= "$TBL_NAME WHERE PRJCODE = '$PRJCODE' AND $STAT_NAME = $DOCSTAT2"; // CONTOH : "tbl_sicertificate";
		//$resDOC2		= $this->db->count_all($selDOC2);
		
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
	}*/
	
	/*function count_all_num_rowsInbox($DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT D.PRJCODE FROM tbl_project A
						INNER JOIN	tbl_spp_header D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND D.APPROVE = 1";
		$resultProj = $this->db->query($sql)->result();
		$myCount	= 0;
		foreach($resultProj as $row) :
			$myCount = $myCount + 1;
		endforeach;
		return $myCount;
	}*/
	
	/*function count_all_num_rowsInbox_PNo_src($txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "tbl_project A
				INNER JOIN tbl_spp_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				AND A.PRJCODE LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rowsInbox_PNm_src($txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "tbl_project A
				INNER JOIN	tbl_spp_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				AND A.PRJNAME LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rows_projMatReq_srcMR($txtSearch)
	{
		$sql	= "tbl_spp_header A
					LEFT JOIN  	tbl_employee B ON A.TRXUSER = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.SPPCODE LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rows_projMatReq_srcPN($txtSearch)
	{
		$sql	= "tproject_mrheader A
					LEFT JOIN  	tbl_employee B ON A.MR_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
					WHERE C.proj_Name LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rowsMR_Inb($PRJCODE) // USED
	{
		$sql	= "tbl_spp_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND SPPSTAT = 2
						AND A.APPROVE = 1"; // Ony Confirm Stat (2)
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rowsMR_SrcInb_MR($PRJCODE, $txtSearch) // USED
	{
		$sql	= "tbl_spp_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND SPPSTAT = 2
						AND A.APPROVE = 1
						AND SPPNUM LIKE '%$txtSearch%'"; // Ony Confirm Stat (2)
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rowsMR_SrcInb_SPP($PRJCODE, $txtSearch) // USED
	{
		$sql	= "tbl_spp_header A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.SPPSTAT = 2
						AND A.APPROVE = 1
						AND A.SPPCODE LIKE '%$txtSearch%'"; // Ony Confirm Stat (2)
		return $this->db->count_all($sql);
	}*/
	
	/*function get_last_ten_projMatReqInb_MRNo($PRJCODE, $limit, $offset, $txtSearch) // USED
	{
		$sql = "SELECT A.SPPNUM, A.SPPCODE, A.TRXDATE, A.PRJCODE, A.TRXOPEN, A.TRXUSER, A.APPROVE, A.APPRUSR, A.JOBCODE, A.SPPNOTE, A.SPPSTAT, REVMEMO,
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_spp_header A
				INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				AND A.SPPSTAT = 2
				AND A.APPROVE = 1
				AND A.SPPNUM LIKE '%$txtSearch%'
				ORDER BY A.SPPCODE ASC";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projMatReqInb_SPP($PRJCODE, $limit, $offset, $txtSearch) // USED
	{
		$sql = "SELECT A.SPPNUM, A.SPPCODE, A.TRXDATE, A.PRJCODE, A.TRXOPEN, A.TRXUSER, A.APPROVE, A.APPRUSR, A.JOBCODE, A.SPPNOTE, A.SPPSTAT, REVMEMO,
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_spp_header A
				INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				AND A.SPPSTAT = 2
				AND A.APPROVE = 1
				AND A.SPPCODE LIKE '%$txtSearch%'
				ORDER BY A.SPPCODE ASC";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projMatReqInb($PRJCODE, $limit, $offset) // USED
	{
		$sql = "SELECT A.SPPNUM, A.SPPCODE, A.TRXDATE, A.PRJCODE, A.TRXOPEN, A.TRXUSER, A.APPROVE, A.APPRUSR, A.JOBCODE, A.SPPNOTE, A.SPPSTAT, REVMEMO,
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_spp_header A
				INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				AND A.SPPSTAT = 2
				AND A.APPROVE = 1
				ORDER BY A.SPPCODE ASC";
		return $this->db->query($sql);
	}*/
		
	/*function count_all_num_rows_PNo($txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "tbl_project A
				WHERE 
				A.PRJCODE LIKE '%$txtSearch%'
				AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		//$sql	= "tbl_project WHERE proj_Code LIKE '%$txtSearch%' AND proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}*/
	
	/*function count_all_num_rows_PNm($txtSearch, $DefEmp_ID)
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "tbl_project A
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		//$sql	= "tbl_project WHERE proj_Name LIKE '%$txtSearch%' AND proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}*/
	
	/*function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJCOST, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_project_PNm($limit, $offset, $txtSearch, $DefEmp_ID)
	{
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJCOST, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}*/
	
	/*function count_all_num_rows_byProjID($proj_Code)
	{		
		$this->db->where('proj_Code', $proj_Code);
		return $this->db->count_all('tproject_mrheader');
	}*/
	
	/*function get_last_ten_projMatReq_MRNo($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.MRH_ID, A.MR_Number, A.MR_Date, A.req_date, A.latest_date, A.MR_Class, A.MR_Type, A.proj_ID, A.proj_Code, A.MR_DepID, A.MR_EmpID, A.MR_Notes, A.MR_Status, 
				A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tproject_mrheader A
				LEFT JOIN  	tbl_employee B ON A.MR_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE
				A.MR_Number LIKE '%$txtSearch%'
				ORDER BY A.MR_Number ASC";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projMatReq_PNm($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.MRH_ID, A.MR_Number, A.MR_Date, A.req_date, A.latest_date, A.MR_Class, A.MR_Type, A.proj_ID, A.proj_Code, A.MR_DepID, A.MR_EmpID, A.MR_Notes, A.MR_Status, 
				A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tproject_mrheader A
				LEFT JOIN  	tbl_employee B ON A.MR_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE
				C.proj_Name LIKE '%$txtSearch%'
				ORDER BY A.MR_Number ASC";
		return $this->db->query($sql);
	}*/
	
	/*function count_all_num_rowsVend() // USED
	{
		return $this->db->count_all('tbl_supplier');
	}*/
	
	/*function viewvendor() // USED
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM tbl_supplier
				ORDER BY SPLDESC ASC";
		return $this->db->query($sql);
	}*/
	
	/*function count_all_num_rowsDept()
	{
		return $this->db->count_all('tdepartment');
	}*/
	
	/*function viewDepartment()
	{
		$this->db->select('Dept_ID, Dept_Name');
		$this->db->from('tdepartment');
		$this->db->order_by('Dept_Name', 'asc');
		return $this->db->get();
	}*/
	
	/*function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('tbl_employee');
	}*/
	
	/*function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}*/
	
	/*function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}*/
	
	/*function viewAllPR()
	{				
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tproject_mrheader A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.MR_Number";
		return $this->db->query($sql);
	}*/
	
	/*function update_inbox($SPPNUM, $projMatReqH) // USED
	{
		$this->db->where('SPPNUM', $SPPNUM);
		$this->db->update('tbl_spp_header', $projMatReqH);
	}*/
	
	/*function delete($MR_Number)
	{
		$this->db->where('MR_Number', $MR_Number);
		$this->db->delete($this->table);
	}*/
	
	// remarks by DH on March, 6 2014
	
	function viewAllItem()
	{
		$this->db->select('Item_Code, Item_Name, Item_Qty, Unit_Type_ID');
		$this->db->from('titem');
		$this->db->order_by('Item_Code', 'asc');
		return $this->db->get();
	}
	
	// add by DH on March, 6 2014
	/*function viewAllItem()
	{
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name, A.itemConvertion
				FROM titem A
				INNER JOIN tbl_unittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}*/
	
	/*function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}*/
	
	// Add by DH on March, 7 2014
	/*function count_all_num_rows_inbox()
	{
		//$sql	= 	"SELECT count(*)
		//			FROM TPO_Header
		//			WHERE Approval_Status NOT IN (3,4,5)";
		//return $this->db->count_all($sql);
		$this->db->where('Approval_Status', 0);
		$this->db->where('Approval_Status', 1);
		$this->db->where('Approval_Status', 2);
		return $this->db->count_all('TPO_Header');
	}*/
	
	/*function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.MR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.MR_Number";
		
		//$this->db->select('MR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		//$this->db->from('TPO_Header');
		//$this->db->order_by('PR_Date', 'asc');
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projectInbox($limit, $offset, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT FROM tbl_project A
							INNER JOIN	tbl_spp_header D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		//$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
		//		FROM tbl_project A
		//		INNER JOIN tbl_spp_header D ON A.PRJCODE = D.PRJCODE
		//		ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projectInbox_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_spp_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}*/
	
	/*function get_last_ten_projectInbox_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_spp_header D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}*/
	
	// Update Project Plan Material
	/*function updatePP($SPPNUM, $parameters) // USED
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
	}*/
}
?>