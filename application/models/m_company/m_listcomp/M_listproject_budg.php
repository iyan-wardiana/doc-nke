<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Agustus 2019
 * File Name	= M_listproject_budg.php
 * Location		= -
*/
?>
<?php
class M_listproject_budg extends CI_Model
{
	var $table = 'tbl_project';
	var $table2 = 'project';
	var $table3 = 'tbl_project_progres';
	
	function count_all_num_rows() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // U
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
					A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.PRJ_MNG,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.PRJPROG, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function add($projectheader) // U
	{
		$this->db->insert('tbl_project', $projectheader);
	}
	
	function addLR($projectLR) // U
	{
		$this->db->insert('tbl_profitloss', $projectLR);
	}
	
	function updatePict($PRJCODE, $nameFile) // U
	{
		$updatePict	= "UPDATE tbl_project SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($updatePict);
	}
	
	function addPL($insPL) // U
	{
		$this->db->insert('tbl_profitloss', $insPL);
	}
	
	function addUpdEDat($updateEndDate) // U
	{
		$this->db->insert('tbl_projhistory', $updateEndDate);
	}
	
	function count_all_num_rowsProj($PRJCODE) // U
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];	
		$sql	= "tbl_project WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rowsCust()
	{
		return $this->db->count_all('tbl_customer');
	}
	
	function viewcustomer()
	{
		$this->db->select('CUST_CODE, CUST_DESC, CUST_ADD1');
		$this->db->from('tbl_customer');
		$this->db->order_by('CUST_DESC', 'ASC');
		return $this->db->get();
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
		$sql = "SELECT Emp_ID, First_name, Middle_Name, Last_Name
				FROM tbl_employee
				WHERE emp_position = 1"; // sementara agar tdk tampil
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode)
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	/*function viewAllPR()
	{				
		$sql = "SELECT A.PR_Number, A.PR_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM TPReq_Header A
				INNER JOIN  temployee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.PR_Number";
		return $this->db->query($sql);
	}*/
				
	function get_PROJ_by_number($PRJCODE)
	{
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJDATE_CO, A.PRJEDAT,
					A.PRJCOST, A.PRJCATEG,
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.ISCHANGE, A.REFCHGNO, A.PRJCOST2, 
					A.PRJ_MNG, A.PRJBOQ, A.PRJRAP,
					A.CHGUSER, A.CHGSTAT, A.PRJPROG, A.QTY_SPYR, A.PRC_STRK, A.PRC_ARST, A.PRC_MKNK, A.PRC_ELCT, A.PRJ_IMGNAME, 
					A.Patt_Year, A.Patt_Number, A.isHO
				FROM tbl_project A
				WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function update($PRJCODE, $projectheader)
	{
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update($this->table, $projectheader);
	}
	
	function updateLR($PRJCODE, $projectLR)
	{
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update('tbl_project', $projectLR);
	}
	
	function updatePL($PRJCODE, $updPL)
	{
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update('tbl_profitloss', $updPL);
	}
	
	function update2($PRJCODE, $projectheader2)
	{
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update($this->table2, $projectheader2);
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
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Unit_Type_ID1, B.Unit_Type_Name
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all('tbl_docpattern');
	}
	
	function deleteProjDet($PRJCODE) // HOLD
	{
		$sql = "DELETE FROM tbl_project_progres WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function addInpProjDet($projectDet) // HOLD
	{
		$this->db->insert('tbl_project_progres', $projectDet);
	}
}
?>