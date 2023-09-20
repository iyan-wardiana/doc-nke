<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Desember 2017
 * File Name	= M_project_invoice_RealINV.php
 * Notes		= -
*/

class M_project_invoice_realINV extends CI_Model
{
	function count_all_ProjInvReal($PRJCODE) // OK
	{
		$sql	= "tbl_projinv_realh A
					INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_projinv_real($PRJCODE) // OK
	{
		$sql = "SELECT *
				FROM tbl_projinv_realh A
				INNER JOIN tbl_project C ON A.PRJCODE = C.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'
				ORDER BY A.PRINV_Number ASC";
		return $this->db->query($sql);
	}	
	
	function count_all_INV($PRJCODE) // OK
	{
		$sql = "tbl_projinv_header A
				INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE
				A.PINV_STAT = 3
				AND A.PRJCODE  = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function view_all_INV($PRJCODE) // OK
	{
		$sql = "SELECT A.*
				FROM tbl_projinv_header A
				INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE
				A.PINV_STAT = 3
				AND A.PRJCODE  = '$PRJCODE'
				ORDER BY A.PINV_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_Project() // OK
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
	
	function add($inpPRINV) // OK
	{
		$this->db->insert('tbl_projinv_realh', $inpPRINV);
	}
	
	function get_PRINV_by_number($PRINV_Number) // OK
	{
		$sql = "SELECT A.PRJCODE, A.OWN_CODE, A.PRINV_Number, A.PRINV_ManNo, A.PRINV_Date, A.PINV_Number, A.PINV_Date, A.PRINV_Deviation,
					A.PINV_Amount, A.PINV_AmountPPn,
					A.PINV_AmountPPh, A.PRINV_Amount, A.PRINV_AmountPPn, A.PRINV_AmountPPh, A.PRINV_AmountOTH, A.PRINV_Notes, A.PRINV_STAT, A.Patt_Number
				FROM tbl_projinv_realh A
				WHERE PRINV_Number = '$PRINV_Number'";
		return $this->db->query($sql);
	}
	
	function count_allOwner() // OK
	{
		return $this->db->count_all('tbl_owner');
	}
	
	function viewOwner() // OK
	{
		$sql = "SELECT * FROM tbl_owner ORDER BY own_Name";
		return $this->db->query($sql);
	}
	
	function update($PRINV_Number, $inpPRINV)
	{
		$this->db->where('PRINV_Number', $PRINV_Number);
		$this->db->update('tbl_projinv_realh', $inpPRINV);
	}
	
	function updareRealPRINV($PRINV_Number, $prohPINVH) // U
	{
		$this->db->where('PRINV_Number', $PRINV_Number);
		$this->db->update('tbl_projinv_realh', $prohPINVH);
	}
	
	function count_all_num_rowsEmpDept() // U
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept() // U
	{
		$this->db->select('Emp_ID, First_name, FlagUSER, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsInbox($DefEmp_ID)
	{
		$sql	= "tbl_projinv_realh A
					INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
					WHERE A.proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_projINV_srcINV($txtSearch, $proj_Code) //
	{		
		$sql	= "tbl_projinv_realh A
					LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
					WHERE A.PRINV_Number LIKE '%$txtSearch%' AND A.proj_Code = '$proj_Code'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_projMatReq_srcPN($txtSearch)
	{
		$sql	= "tbl_projinv_realh A
					LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
					WHERE C.proj_Name LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function count_all_ProjInvReal1($proj_Code)
	{
		$sql = "tbl_projinv_realh A
				LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.proj_Code = '$proj_Code'
				AND A.PRINV_Status = 2
				ORDER BY A.PRINV_Number ASC";
		return $this->db->count_all($sql);
	}
		
	function count_all_num_rows_PNo($txtSearch, $DefEmp_ID)  //
	{
		$sql	= "tbl_project WHERE proj_Code LIKE '%$txtSearch%' AND proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_PNm($txtSearch, $DefEmp_ID) //
	{
		$sql	= "tbl_project WHERE proj_Name LIKE '%$txtSearch%' AND proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) //
	{
		$sql = "SELECT A.proj_Number, A.proj_ID, A.proj_Code, A.proj_Name, A.proj_Date, A.proj_StartDate, A.proj_EndDate, A.proj_Type, A.proj_PM_EmpID, A.proj_CustCode, A.proj_Status,
				A.proj_Location,
				B.First_Name, B.Middle_Name, B.Last_Name, C.Cust_Name
				FROM tbl_project A
				LEFT JOIN  tbl_employee B ON A.proj_PM_EmpID = B.Emp_ID
				LEFT JOIN 	tcustomer C ON A.proj_CustCode = C.Cust_Code
				WHERE proj_Code LIKE '%$txtSearch%' AND A.proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.proj_Code
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_project_PNm($limit, $offset, $txtSearch, $DefEmp_ID) //
	{
		$sql = "SELECT A.proj_Number, A.proj_ID, A.proj_Code, A.proj_Name, A.proj_Date, A.proj_StartDate, A.proj_EndDate, A.proj_Type, A.proj_PM_EmpID, A.proj_CustCode, A.proj_Status,
				A.proj_Location,
				B.First_Name, B.Middle_Name, B.Last_Name, C.Cust_Name
				FROM tbl_project A
				LEFT JOIN  tbl_employee B ON A.proj_PM_EmpID = B.Emp_ID
				LEFT JOIN 	tcustomer C ON A.proj_CustCode = C.Cust_Code
				WHERE proj_Name LIKE '%$txtSearch%' AND A.proj_Code IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.proj_Code
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_byProjID($proj_Code)
	{		
		$this->db->where('proj_Code', $proj_Code);
		return $this->db->count_all('tbl_projinv_realh');
	}
	
	function get_last_ten_projinv_INVNo($limit, $offset, $txtSearch, $proj_Code) //
	{		
		$sql = "SELECT A.PRINV_ID, A.PRINV_Number, A.PRINV_Date, A.proj_Code, A.PRINV_Notes, 
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_realh A
				LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.PRINV_Number LIKE '%$txtSearch%' AND A.proj_Code = '$proj_Code'
				ORDER BY A.PRINV_Number ASC
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projinv_PNm($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.PRINV_ID, A.PRINV_Number, A.PRINV_Date, A.req_date, A.proj_ID, A.proj_Code, A.PRINV_Notes, 
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_realh A
				LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE
				C.proj_Name LIKE '%$txtSearch%'
				ORDER BY A.PRINV_Number ASC";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projinvInb($proj_Code, $limit, $offset)
	{
		$sql = "SELECT A.PRINV_ID, A.PRINV_Number, A.PRINV_Date, A.req_date, A.proj_ID, A.proj_Code, A.PRINV_Notes, 
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_realh A
				LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.proj_Code = '$proj_Code'
				AND A.PRINV_Status = 2
				ORDER BY A.PRINV_Number ASC";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projinvInb_MRNo($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.PRINV_ID, A.PRINV_Number, A.PRINV_Date, A.req_date, A.proj_ID, A.proj_Code, A.PRINV_Notes, 
				A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID,C.proj_Number, C.proj_Code, C.proj_Name
				FROM tbl_projinv_realh A
				LEFT JOIN  	tbl_employee B ON A.PRINV_EmpID = B.Emp_ID
				INNER JOIN 	tbl_project C ON A.proj_Code = C.proj_Code
				WHERE A.PRINV_Status = 2
				AND A.PRINV_Number LIKE '%$txtSearch%'
				ORDER BY A.PRINV_Number ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVend() //
	{
		return $this->db->count_all('tvendor_upd');
	}
	
	function viewvendor()
	{
		$sql = "SELECT SPLCODE AS Vend_Code, SPLDESC AS Vend_Name, SPLADD1 AS Vend_Address
				FROM tvendor_upd
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
		$sql = "SELECT A.PRINV_Number, A.PRINV_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tbl_projinv_realh A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.PRINV_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function delete($PRINV_Number)
	{
		$this->db->where('PRINV_Number', $PRINV_Number);
		$this->db->delete($this->table);
	}
	
	function deleteDetail($PRINV_Number)
	{
		$this->db->where('PRINV_Number', $PRINV_Number);
		$this->db->delete('tbl_projinv_header');
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
					WHERE Approval_Status NOT IN (3,4,5)";
		return $this->db->count_all($sql);*/
		$this->db->where('Approval_Status', 0);
		$this->db->where('Approval_Status', 1);
		$this->db->where('Approval_Status', 2);
		return $this->db->count_all('TPO_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.PRINV_Number, A.PR_Date, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.PRINV_Number";
		
		/*$this->db->select('PRINV_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
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
				INNER JOIN	tbl_projinv_realh D ON A.proj_Code = D.proj_Code
				ORDER BY A.proj_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
		
	}
	
	// Update Project Plan Material
	function updatePP($PO_Number, $parameters)
	{		
		$PRINV_Number = $parameters['PRINV_Number'];
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