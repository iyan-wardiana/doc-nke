<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 30 Agustus 2017
	* File Name	= M_task_request.php
	* Location		= -
*/

class M_task_request extends CI_Model
{
	function count_all_task($DefEmp_ID) // OK
	{
		$sqlOpen		= "SELECT isHelper FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
		$sqlOpen		= $this->db->query($sqlOpen)->result();
		foreach($sqlOpen as $rowOpen) :
			$isHelper	= $rowOpen->isHelper;
		endforeach;
		
		/****if($isHelper == 1)
		{
			$sql = "tbl_task_request";
		}
		else
		{*/
			//$sql = "tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_TO LIKE '%DefEmp_ID%' OR TASK_TO = 'All')";
			$sql = "tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_AUTHOR LIKE '%$DefEmp_ID%' OR TASK_TO LIKE '%$DefEmp_ID%' OR TASK_TO = 'All') AND TASK_STAT != 99";
		//****}
		return $this->db->count_all($sql);
	}
	
	function view_all_task($DefEmp_ID) // OK
	{
		$sqlOpen		= "SELECT isHelper FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
		$sqlOpen		= $this->db->query($sqlOpen)->result();
		foreach($sqlOpen as $rowOpen) :
			$isHelper	= $rowOpen->isHelper;
		endforeach;
		
		/****if($isHelper == 1)
		{
			$sql = "SELECT * FROM tbl_task_request ORDER BY TASK_DATE DESC";
		}
		else
		{*/
			$sql = "SELECT * FROM tbl_task_request WHERE TASK_REQUESTER = '$DefEmp_ID' OR (TASK_AUTHOR LIKE '%$DefEmp_ID%' OR TASK_TO LIKE '%$DefEmp_ID%'
							OR TASK_TO = 'All') AND TASK_STAT != 99
						ORDER BY TASK_DATE DESC";
		//****}
		
		return $this->db->query($sql);
	}
	
	function get_AllDataC($DefEmp_ID, $search) // GOOD
	{
		$sql 	= "tbl_task_request A 
					WHERE A.TASK_REQUESTER = '$DefEmp_ID'
						AND (A.TASK_AUTHOR LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
						OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
						OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function getCount_menu() // OK
	{
		$DefID      = $this->session->userdata['Emp_ID'];
		$sql		= "tbl_menu WHERE level_menu = '1' AND isActive = 1
						AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefID')";
		return $this->db->count_all($sql);
	}

	function get_menu() // OK
	{
		$DefID  = $this->session->userdata['Emp_ID'];
		$sql 	= "SELECT * FROM tbl_menu WHERE level_menu = '1' AND isActive = 1
					AND menu_code IN (SELECT menu_code FROM tusermenu WHERE emp_id = '$DefID') ORDER BY no_urut";
		return $this->db->query($sql);
	}
	
	function add($InsTR) // OK
	{
		$this->db->insert('tbl_task_request', $InsTR);
	}
	
	function addDet($InsTRD) // OK
	{
		$this->db->insert('tbl_task_request_detail', $InsTRD);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function viewTaskDet($TASK_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_task_request  WHERE TASK_CODE = '$TASK_CODE'";
		return $this->db->query($sql);
	}
	
	function update($IK_CODE, $indic) // OK
	{
		$this->db->where('IK_CODE', $IK_CODE);
		$this->db->update('tbl_indikator ', $indic);
	}
					
	function UpdateOriginal($TASKD_ID) // OK
	{
		$sql = "UPDATE tbl_task_request_detail SET TASKD_RSTAT = '2' WHERE TASKD_ID = '$TASKD_ID'";
		return $this->db->query($sql);
	}
}
?>