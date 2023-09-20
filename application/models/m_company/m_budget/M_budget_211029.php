<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Agustus 2019
 * File Name	= M_budget.php
 * Location		= -
*/
?>
<?php
class M_budget extends CI_Model
{
	var $table = 'tbl_project_budg';
	var $table2 = 'project';
	
	function get_AllDataC($PRJCODEHO, $search) // GOOD
	{
		/*$sql = "tbl_project_budg A 
				WHERE A.PRJCODE_HO = '$PRJCODEHO' AND A.BUDG_LEVEL = 2
					AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
					OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
					OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!')";*/
		$sql = "tbl_project A 
				WHERE A.PRJCODE_HO = '$PRJCODEHO' -- AND A.PRJTYPE = 3
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
						FROM tbl_project A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' -- AND A.PRJTYPE = 3
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY PRJSTAT, $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' -- AND A.PRJTYPE = 3
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY PRJSTAT";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' -- AND A.PRJTYPE = 3
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY PRJSTAT, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project A
						WHERE A.PRJCODE_HO = '$PRJCODEHO' -- AND A.PRJTYPE = 3
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR PRJNAME LIKE '%$search%' ESCAPE '!' OR PRJCOST LIKE '%$search%' ESCAPE '!'
							OR PRJDATE LIKE '%$search%' ESCAPE '!' OR PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY PRJSTAT LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rows() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // U
	{
		$DefEmp_ID 			= $this->session->userdata['Emp_ID'];		
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJPERIOD, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
					A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR, A.PRJ_MNG,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.PRJPROG, A.Patt_Year, A.Patt_Number
				FROM tbl_project A 
				WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.BUDG_LEVEL = 2
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function add($projectheader) // U
	{
		$this->db->insert('tbl_project', $projectheader);
		$this->db->insert('tbl_project_budg', $projectheader);
		$this->db->insert('tbl_project_budgm', $projectheader);
	}
	
	function addPRJDoc($prjDoc) // U
	{
		$this->db->insert('tbl_project_doc', $prjDoc);
	}
	
	function addLR($projectLR) // U
	{
		$this->db->insert('tbl_profitloss', $projectLR);
	}
	
	function updatePict($PRJCODE, $nameFile) // U
	{
		$updatePict	= "UPDATE tbl_project SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($updatePict);

		$updatePict	= "UPDATE tbl_project_budg SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($updatePict);

		$updatePict	= "UPDATE tbl_project_budgm SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
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
	
	function count_all_num_rowsProj($PRJPERIOD) // U
	{	
		$sql	= "tbl_project WHERE PRJPERIOD = '$PRJPERIOD'";
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
				
	function get_PROJ_by_number($proj_Number)
	{
		$sql = "SELECT A.*
				FROM tbl_project A
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
	
	function updateLR($PRJCODE, $projectLR)
	{
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->update('tbl_project_budg', $projectLR);
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
		$sql = "DELETE FROM tbl_project_budg_progres WHERE PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function addInpProjDet($projectDet) // HOLD
	{
		$this->db->insert('tbl_project_budg_progres', $projectDet);
	}
	
	function count_all_schedule($PRJCODE, $PRJPERIOD) // OK
	{
		$QRY 		= "AND PRJPERIOD = '$PRJPERIOD'";
		if($PRJCODE == $PRJPERIOD || $PRJPERIOD == "")
			$QRY 	= "";

		$sql = "tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' $QRY AND IS_LEVEL = 1";
		return $this->db->count_all($sql);
	}
	
	function get_all_joblist($PRJCODE, $PRJPERIOD) // OK
	{
		$QRY 		= "AND PRJPERIOD = '$PRJPERIOD'";
		if($PRJCODE == $PRJPERIOD || $PRJPERIOD == "")
			$QRY 	= "";
		
		$sql = "SELECT * FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' $QRY AND IS_LEVEL = 1 
					ORDER BY JOBCODEID LIMIT 1";
		return $this->db->query($sql);
	}
	
	function get_project_name($PRJCODE, $PRJPERIOD) // OK
	{
		$QRY 		= "AND PRJPERIOD = '$PRJPERIOD'";
		if($PRJCODE == $PRJPERIOD || $PRJPERIOD == "")
			$QRY 	= "";
		
		/*$sql = "SELECT A.PRJNAME, A.PRJNAME AS BUDGNAME FROM tbl_project A
					INNER JOIN tbl_project B ON A.PRJCODE_HO = B.PRJCODE 
				WHERE A.PRJCODE = '$PRJCODE' $QRY LIMIT 1";*/
		
		$sql = "SELECT A.PRJNAME, A.PRJNAME AS BUDGNAME FROM tbl_project A
				WHERE A.PRJCODE = '$PRJCODE' $QRY LIMIT 1";
		return $this->db->query($sql);
	}
	
	function get_all_ofCOADef($PRJCODE, $PRJPERIOD, $LinkAcc) // G
	{
		$sql	= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$PRJCODE' AND Account_Category = 1 AND isHO = 2
					ORDER BY Account_Category, Account_Number ASC";
		return $this->db->query($sql);
	}
	
	function get_all_ofCOA($collPRJ, $PRJPERIOD, $LinkAcc) // G
	{
		if($LinkAcc == 9)
		{
			if($collPRJ == 'AllPRJ')
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE Account_Category IN (9,10) AND isHO != 2
								ORDER BY Account_Category, Account_Number ASC";
			}
			else
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ'
								AND Account_Category IN (9,10) AND isHO = 2 ORDER BY Account_Category, Account_Number ASC";
			}
		}
		else
		{
			if($collPRJ == 'AllPRJ')
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE Account_Category IN (9,10) AND isHO = 2
								ORDER BY Account_Category, Account_Number ASC";
			}
			else
			{
				$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ'
								AND Account_Category = $LinkAcc
								ORDER BY Account_Category, Account_Number ASC";
			}
		}
		return $this->db->query($sql);
	}
	
	function get_all_ofCOACAT($collPRJ, $PRJPERIOD, $LinkAcc) // G
	{
		if($LinkAcc == 9)
		{
			$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ'
							AND Account_Category IN (9,10) AND isHO = 2 ORDER BY Account_Category, Account_Number ASC";
		}
		else
		{
			$sql		= "SELECT * FROM tbl_chartaccount WHERE PRJCODE = '$collPRJ' AND Account_Category = $LinkAcc
							ORDER BY Account_Category, Account_Number ASC";
		}
		return $this->db->query($sql);
	}
	
	function get_joblist_by_code($PRJCODE, $JOBCODEID) // OK
	{
		if($JOBCODEID == '')
		{
			$sql = "SELECT * FROM tbl_joblist_detail";
		}
		else
		{
			//$sql = "SELECT * FROM tbl_joblist_detail WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
			$sql = "SELECT * FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBCODEID = '$JOBCODEID'";
		}
		return $this->db->query($sql);
	}
	
	function updateXXX($JOBCODEID, $joblist) // OK
	{
		$this->db->where('JOBCODEID', $JOBCODEID);
		$this->db->update('tbl_boqlist', $joblist);
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
	
	function get_coa_by_code($Acc_ID, $PRJCODE) // G
	{
		$sql	= "SELECT * FROM tbl_chartaccount
					WHERE Acc_ID = '$Acc_ID' AND PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function get_AllDataJLC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_joblist_detail A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.ORD_ID NOT IN (0, 9999999)
					AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
					OR JOBDESC LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataJLL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.ORD_ID NOT IN (0, 9999999)
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.ORD_ID NOT IN (0, 9999999)
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.ORD_ID NOT IN (0, 9999999)
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT ORD_ID, JOBCODEID, JOBDESC, IS_LEVEL, ITM_UNIT, ITM_GROUP, ITM_VOLM, ITM_PRICE, ITM_LASTP, ITM_BUDG,
							ADD_VOLM, ADD_PRICE, ADD_JOBCOST, ADDM_VOLM, ADDM_JOBCOST, ITM_USED, ITM_USED_AM, ISLAST
						FROM tbl_joblist_detail A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.ORD_ID NOT IN (0, 9999999)
							AND (JOBCODEID LIKE '%$search%' ESCAPE '!' OR ITM_UNIT LIKE '%$search%' ESCAPE '!'
							OR JOBDESC LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataHISTC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_boq_hist A
				WHERE A.BOQH_PRJCODE = '$PRJCODE'
					AND (A.BOQH_DESC LIKE '%$search%' ESCAPE '!' OR A.BOQH_FN LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataHISTL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_boq_hist A
						WHERE A.BOQH_PRJCODE = '$PRJCODE'
							AND (A.BOQH_DESC LIKE '%$search%' ESCAPE '!' OR A.BOQH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_boq_hist A
						WHERE A.BOQH_PRJCODE = '$PRJCODE'
							AND (A.BOQH_DESC LIKE '%$search%' ESCAPE '!' OR A.BOQH_FN LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_boq_hist A
						WHERE A.BOQH_PRJCODE = '$PRJCODE'
							AND (A.BOQH_DESC LIKE '%$search%' ESCAPE '!' OR A.BOQH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_boq_hist A
						WHERE A.BOQH_PRJCODE = '$PRJCODE'
							AND (A.BOQH_DESC LIKE '%$search%' ESCAPE '!' OR A.BOQH_FN LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataITMC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_item_uphist A
				WHERE A.ITMH_PRJCODE = '$PRJCODE'
					AND (A.ITMH_DESC LIKE '%$search%' ESCAPE '!' OR A.ITMH_FN LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataITML($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_item_uphist A
						WHERE A.ITMH_PRJCODE = '$PRJCODE'
							AND (A.ITMH_DESC LIKE '%$search%' ESCAPE '!' OR A.ITMH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_item_uphist A
						WHERE A.ITMH_PRJCODE = '$PRJCODE'
							AND (A.ITMH_DESC LIKE '%$search%' ESCAPE '!' OR A.ITMH_FN LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_item_uphist A
						WHERE A.ITMH_PRJCODE = '$PRJCODE'
							AND (A.ITMH_DESC LIKE '%$search%' ESCAPE '!' OR A.ITMH_FN LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_item_uphist A
						WHERE A.ITMH_PRJCODE = '$PRJCODE'
							AND (A.ITMH_DESC LIKE '%$search%' ESCAPE '!' OR A.ITMH_FN LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataCOAC($PRJCODE, $theCateg, $search) // GOOD
	{
		$sql = "tbl_chartaccount A 
				WHERE A.PRJCODE = '$PRJCODE' AND A.Account_Category = $theCateg
					AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR Account_Number LIKE '%$search%' ESCAPE '!'
					OR Account_NameId LIKE '%$search%' ESCAPE '!' OR Account_NameEn LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataCOAL($PRJCODE, $theCateg, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_chartaccount A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.Account_Category = $theCateg
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR Account_Number LIKE '%$search%' ESCAPE '!'
							OR Account_NameId LIKE '%$search%' ESCAPE '!' OR Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_chartaccount A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.Account_Category = $theCateg
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR Account_Number LIKE '%$search%' ESCAPE '!'
							OR Account_NameId LIKE '%$search%' ESCAPE '!' OR Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_chartaccount A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.Account_Category = $theCateg
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR Account_Number LIKE '%$search%' ESCAPE '!'
							OR Account_NameId LIKE '%$search%' ESCAPE '!' OR Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID, $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_chartaccount A 
						WHERE A.PRJCODE = '$PRJCODE' AND A.Account_Category = $theCateg
							AND (PRJCODE LIKE '%$search%' ESCAPE '!' OR Account_Number LIKE '%$search%' ESCAPE '!'
							OR Account_NameId LIKE '%$search%' ESCAPE '!' OR Account_NameEn LIKE '%$search%' ESCAPE '!') ORDER BY ORD_ID LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataPRJDOC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_project_doc A
				WHERE A.PRJCODE = '$PRJCODE'
					AND (A.PRJ_FDESC LIKE '%$search%' ESCAPE '!' OR A.PRJ_FNAME LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataPRJDOCL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_project_doc A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJ_FDESC LIKE '%$search%' ESCAPE '!' OR A.PRJ_FNAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_project_doc A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJ_FDESC LIKE '%$search%' ESCAPE '!' OR A.PRJ_FNAME LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.* FROM tbl_project_doc A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJ_FDESC LIKE '%$search%' ESCAPE '!' OR A.PRJ_FNAME LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
						LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.* FROM tbl_project_doc A
						WHERE A.PRJCODE = '$PRJCODE'
							AND (A.PRJ_FDESC LIKE '%$search%' ESCAPE '!' OR A.PRJ_FNAME LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>