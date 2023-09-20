<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Agustus 2019
 * File Name	= M_budget_Mst.php
 * Location		= -
*/
?>
<?php
class M_budget_mst extends CI_Model
{
	var $table = 'tbl_project_budgm';
	var $table2 = 'project';
	
	function get_AllDataC($PRJCODEHO, $search) // GOOD
	{
		$sql = "tbl_project_budgm A 
				WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 1
					AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
					OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
					OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODEHO, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budgm A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 1
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budgm A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 1
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budgm A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 1
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budgm A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 1
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function add($projectheader) // U
	{
		$this->db->insert('tbl_project', $projectheader);
		$this->db->insert('tbl_project_budg', $projectheader);
		$this->db->insert('tbl_project_budgm', $projectheader);
	}
	
	function updatePict($PRJCODE, $nameFile) // U
	{
		$updatePict	= "UPDATE tbl_project_budgm SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($updatePict);
	}
	
	function count_all_num_rowsProj($PRJPERIOD) // U
	{	
		$sql	= "tbl_project_budgm WHERE PRJPERIOD = '$PRJPERIOD'";
		return $this->db->count_all($sql);
	}
				
	function get_PROJ_by_number($proj_Number)
	{
		$sql = "SELECT A.*
				FROM tbl_project_budgm A
				WHERE A.proj_Number = '$proj_Number'";
		return $this->db->query($sql);
	}
	
	function update($proj_Number, $projectheader)
	{
		$this->db->where('proj_Number', $proj_Number);
		$this->db->update('tbl_project', $projectheader);
		
		$this->db->where('proj_Number', $proj_Number);
		$this->db->update('tbl_project_budg', $projectheader);
		
		$this->db->where('proj_Number', $proj_Number);
		$this->db->update('tbl_project_budgm', $projectheader);
	}
	
	function count_all_schedule($PRJCODE, $PRJPERIOD) // OK
	{
		$sql = "tbl_joblist_detailm WHERE PRJCODE = '$PRJCODE' AND IS_LEVEL = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_joblist($PRJCODE, $PRJPERIOD) // OK
	{
		$sql = "SELECT * FROM tbl_joblist_detailm WHERE PRJCODE = '$PRJCODE' AND IS_LEVEL = 1
					ORDER BY JOBCODEID";
		return $this->db->query($sql);
	}
	
	function get_project_name($PRJCODE, $PRJPERIOD) // OK
	{
		$sql = "SELECT B.PRJNAME, A.PRJNAME AS BUDGNAME FROM tbl_project_budgm A
					INNER JOIN tbl_project B ON A.PRJCODE_HO = B.PRJCODE 
				WHERE A.PRJCODE = '$PRJCODE' AND A.PRJPERIOD = '$PRJPERIOD' AND A.BUDG_LEVEL = 1 LIMIT 1";			
		return $this->db->query($sql);
	}
	
	function get_all_ofCOADef($PRJCODE, $PRJPERIOD, $LinkAcc) // G
	{
		$sql	= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND BudgetGroup = '$PRJPERIOD' AND Account_Category = 1  AND isHO = 2
					ORDER BY Account_Category, Account_Number ASC";
		return $this->db->query($sql);
	}
	
	function get_joblist_by_code($PRJCODE, $JOBCODEID) // OK
	{
		if($JOBCODEID == '')
		{
			$sql = "SELECT * FROM tbl_boqlistm";
		}
		else
		{
			$sql = "SELECT * FROM tbl_boqlistm WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
		}
		return $this->db->query($sql);
	}
	
	function count_all_job1() // OK
	{
		return $this->db->count_all('tbl_boqlist');
	}
	
	function get_all_job1() // OK
	{		
		$sql = "SELECT * FROM tbl_boqlist WHERE JOBPARENT = '0'";
		return $this->db->query($sql);
	}
}
?>