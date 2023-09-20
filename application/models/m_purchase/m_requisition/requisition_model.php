<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2014
 * File Name	= Requisition_model.css
 * Location		= ./system/application/models/m_purchase/m_requisition/Requisition_model.php
*/
?>
<?php
class Requisition_model extends Model
{
	function Requisition_model()
	{
		parent::Model();
	}
	
	function viewpurreq()
	{
		$query = $this->db->get('TPReq_Header');
		return $query->result(); 
	}
	
	var $table = 'TPReq_Header';
	
	function searchpurreq($konstSearch)
	{
		$selSearchType 	= $this->input->POST ('selSearchType');
		$txtSearch 		= $this->input->POST ('txtSearch');
		$selVendStatus 		= $this->input->POST ('selVendStatus');
		if($selSearchType == $konstSearch)
		{
			$this->db->like('PR_Number', $txtSearch);
		}
		else
		{
			$this->db->like('PR_Date', $txtSearch);
		}
		$this->db->where('PR_EmpID', $selVendStatus);
		$query = $this->db->get('TPReq_Header');
		return $query->result(); 
	}
	
	function get_PR_by_number($PR_Number)
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name, C.Dept_Name, D.Vend_Name
				FROM TPReq_Header A
				INNER JOIN  temployee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tdepartment C ON C.Dept_ID = A.PR_DepID
				INNER JOIN 	tvendor D ON D.Vend_Code = A.Vend_Code
				WHERE PR_Number = '$PR_Number'
				ORDER BY A.PR_Number";
		return $this->db->query($sql);
	}
	
	function add($purreq)
	{
		$this->db->insert($this->table, $purreq);
	}
	
	function update($PR_Number, $purreq)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->update($this->table, $purreq);
	}
	
	function updateDetail($PR_Number, $purreq)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->update($this->table, $purreq);
	}
	
	function deleteDetail($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->delete('tpreq_detail');
	}
	
	function deleteDetailNone($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->where('chk', '');
		$this->db->delete('tpreq_detail');
	}
	
	function delete($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->delete($this->table);
	}
	
	function count_all_num_rows()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function get_last_ten_purreq($limit, $offset)
	{
		/*$this->db->select('PR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPReq_Header');
		$this->db->order_by('PR_Date', 'asc');
		$this->db->limit($limit, $offset);
		return $this->db->get();*/
		$sql = "SELECT A.PR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name
				FROM TPReq_Header A
				INNER JOIN  temployee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				ORDER BY A.PR_Number";
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
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('temployee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('temployee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsVend()
	{
		return $this->db->count_all('tvendor');
	}
	
	function viewvendor()
	{
		$this->db->select('Vend_Code, Vend_Name, Vend_Address');
		$this->db->from('tvendor');
		$this->db->order_by('Vend_Name', 'asc');
		return $this->db->get();
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
		$sql = "SELECT A.Item_Code, A.serialNumber, A.Item_Name, A.Item_Qty, A.Item_Qty2, A.Unit_Type_ID1, A.Unit_Type_ID2, B.Unit_Type_Name
				FROM titem A
				INNER JOIN tunittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
		return $this->db->query($sql);
	}
	
	function getNumRowDocPat($MenuCode, $docPatternPosition)
	{
		$this->db->where('menu_code', $MenuCode);
		$this->db->where('Pattern_Position', $docPatternPosition);
		return $this->db->count_all_results('tdocpattern');
	}
	
	function getDataDocPat($MenuCode)
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	// Add by DH on March, 7 2014
	function count_all_num_rows_inbox()
	{
		/*return $this->db->count_all('TPReq_Header');*/
		
		$this->db->where('approval_status', 0);
		return $this->db->count_all_results('TPReq_Header');
	}
	
	function get_last_ten_PR_inbox($limit, $offset)
	{
		$sql = "SELECT A.PR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name,A.Patt_Year
				FROM TPReq_Header A
				INNER JOIN  temployee B ON A.PR_EmpID = B.Emp_ID
				AND A.approval_status = 0
				ORDER BY A.PR_Number";
		return $this->db->query($sql);
	}
	
	function updateInbox($PR_Number, $purreq)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->update($this->table, $purreq);
	}
}
?>