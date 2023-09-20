<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 Juni 2018
 * File Name	= M_asset_expense.php
 * Location		= -
*/
class M_asset_expense extends CI_Model
{	
	function count_all_asexp($PRJCODE) // G
	{
		$sql	= "tbl_asset_exph A
					INNER JOIN tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_asexp($PRJCODE) // G
	{
		$sql = "SELECT A.*,
					B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_asset_exph A
				INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // G
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_asset($PRJCODE, $JOBCODE) // G
	{
		$sql	= "tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE'";			
		return $this->db->count_all($sql);
	}
	
	function get_all_asset($PRJCODE, $JOBCODE) // G
	{
		$sql	= "SELECT * FROM tbl_asset_list WHERE AS_LASTPOS = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_item($PRJCODE, $JOBCODE) // G
	{
		$sql	= "tbl_joblist_detail A
					INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
						AND B.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND A.ISLAST = 1";			
		return $this->db->count_all($sql);
	}
	
	function get_all_item($PRJCODE, $JOBCODE) // G
	{
		$sql	= "SELECT DISTINCT A.JOBCODEDET, A.JOBCODEID, A.JOBCODE, A.JOBDESC, A.PRJCODE, 
						A.ITM_CODE, A.ITM_UNIT, A.ITM_PRICE, A.ITM_VOLM, 
						A.REQ_VOLM, A.REQ_AMOUNT, A.PO_VOLM, A.PO_AMOUNT, A.IR_VOLM, A.IR_AMOUNT,
						A.ITM_USED, A.ITM_USED_AM, A.ITM_STOCK, A.ITM_STOCK_AM, A.ITM_BUDG,
						B.ITM_NAME, B.ACC_ID_UM AS ACC_ID, B.ITM_GROUP, B.ITM_CATEG, A.ADD_VOLM, A.ADD_PRICE
						FROM tbl_joblist_detail A
						INNER JOIN tbl_item B ON A.ITM_CODE = B.ITM_CODE
							AND B.PRJCODE = '$PRJCODE'
						WHERE A.PRJCODE = '$PRJCODE'
							AND A.ISLAST = 1";
		return $this->db->query($sql);
	}
	
	function add($insASEXPH) // G
	{
		$this->db->insert('tbl_asset_exph', $insASEXPH);
	}
	
	function updateDet($ASEXP_NUM, $PRJCODE, $ASEXP_DATE) // G
	{
		$sql = "UPDATE tbl_asset_expd SET PRJCODE = '$PRJCODE', ASEXP_DATE = '$ASEXP_DATE' WHERE ASEXP_NUM = '$ASEXP_NUM'";
		return $this->db->query($sql);
	}
	
	function get_asexp_by_number($ASEXP_NUM) // G
	{
		$sql = "SELECT A.*, B.proj_Number, B.PRJCODE, B.PRJNAME
				FROM tbl_asset_exph A
					INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
				WHERE A.ASEXP_NUM = '$ASEXP_NUM'";
		return $this->db->query($sql);
	}
	
	function update($ASEXP_NUM, $updASEXPH) // G
	{
		$this->db->where('ASEXP_NUM', $ASEXP_NUM);
		$this->db->update('tbl_asset_exph', $updASEXPH);
	}
	
	function deleteDetail($ASEXP_NUM) // G
	{
		$this->db->where('ASEXP_NUM', $ASEXP_NUM);
		$this->db->delete('tbl_asset_expd');
	}
	
	function deletePO($PR_NUM) // OK
	{
		// 1. COPY TO tbl_asset_exph_trash
			//$sqlqg11	= "INSERT INTO tbl_asset_exph_trash SELECT * FROM tbl_asset_exph WHERE PR_NUM = '$PR_NUM'";
			//$this->db->query($sqlqg11);
			
			$sqlqg12	= "DELETE FROM tbl_asset_exph WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg12);
			
		// 2. COPY TO tbl_pr_detail_trash
			/*$sqlqg13	= "INSERT INTO tbl_pr_detail_trash SELECT * FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg13);
			
			$sqlqg14	= "DELETE FROM tbl_pr_detail WHERE PR_NUM = '$PR_NUM'";
			$this->db->query($sqlqg14);*/
	}
	
	function count_all_asexpInx($DefEmp_ID) // OK
	{
		$sql	= "tbl_asset_exph A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PR_STAT IN (2,7)"; // Only Confirm Stat (2)
		return $this->db->count_all($sql);
	}
	
	function get_all_asexpInb($DefEmp_ID) // OK
	{
		$sql 	= "SELECT A.PR_NUM, A.PR_CODE, A.PR_DATE, A.PRJCODE, A.SPLCODE, A.PR_DEPT, A.JOBCODE,
						A.PR_NOTE, A.PR_NOTE2, A.PR_STAT, PR_MEMO, A.PR_REFNO, A.PR_CREATER,
						A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
						B.proj_Number, B.PRJCODE, B.PRJNAME
					FROM tbl_asset_exph A
						INNER JOIN 	tbl_project B ON A.PRJCODE = B.PRJCODE
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						AND PR_STAT IN (2,7)
					ORDER BY A.PR_CODE ASC";
		return $this->db->query($sql);
	}
	
	function updateJobDet($PR_NUM, $PRJCODE) // OK
	{				
		$sqlGetPR	= "SELECT PR_NUM, JOBCODEDET, JOBCODEID, ITM_CODE, PR_VOLM, PR_PRICE
						FROM tbl_pr_detail
						WHERE PR_NUM = '$PR_NUM' AND PRJCODE = '$PRJCODE'";
		$resGetPR	= $this->db->query($sqlGetPR)->result();
		foreach($resGetPR as $rowRP) :
			$PR_NUM 	= $rowRP->PR_NUM;
			$JOBCODEDET	= $rowRP->JOBCODEDET;
			$JOBCODEID	= $rowRP->JOBCODEID;
			$ITM_CODE	= $rowRP->ITM_CODE;
			$PR_VOLM	= $rowRP->PR_VOLM;
			$PR_PRICE	= $rowRP->PR_PRICE;
			$PR_TOTAMN	= $PR_VOLM * $PR_PRICE;
			
			// UPDATE JOBDETAIL
			$REQ_VOLM	= 0;
			$REQ_AMOUNT	= 0;
			$sqlGetJD		= "SELECT REQ_VOLM, REQ_AMOUNT FROM tbl_joblist_detail
								WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$resGetJD		= $this->db->query($sqlGetJD)->result();
			foreach($resGetJD as $rowJD) :
				$REQ_VOLM 	= $rowJD->REQ_VOLM;
				$REQ_AMOUNT	= $rowJD->REQ_AMOUNT;
			endforeach;
			if($REQ_VOLM == '')
				$REQ_VOLM = 0;
			if($REQ_AMOUNT == '')
				$REQ_AMOUNT = 0;
				
			$totREQQty	= $REQ_VOLM + $PR_VOLM;
			$totREQAmn	= $REQ_AMOUNT + $PR_TOTAMN;
			$sqlUpd		= "UPDATE tbl_joblist_detail SET REQ_VOLM = $totREQQty, REQ_AMOUNT = $totREQAmn
							WHERE JOBCODEDET = '$JOBCODEDET' AND JOBCODEID = '$JOBCODEID' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpd);
			
			// UPDATE TBL_ITEM
			$PR_VOLM1		= 0;
			$PR_AMOUNT1		= 0;
			$sqlGetJD1		= "SELECT PR_VOLM, PR_AMOUNT FROM tbl_item WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$resGetJD1		= $this->db->query($sqlGetJD1)->result();
			foreach($resGetJD1 as $rowJD1) :
				$PR_VOLM1 	= $rowJD1->PR_VOLM;
				$PR_AMOUNT1	= $rowJD1->PR_AMOUNT;
			endforeach;
			if($PR_VOLM1 == '')
				$PR_VOLM1 = 0;
			if($PR_AMOUNT1 == '')
				$PR_AMOUNT1 = 0;
				
			$totPRQty	= $PR_VOLM1 + $PR_VOLM;
			$totPRAmn	= $PR_AMOUNT1 + $PR_TOTAMN;
			$sqlUpd2	= "UPDATE tbl_item SET PR_VOLM = $totPRQty, PR_AMOUNT = $totPRAmn WHERE PRJCODE = '$PRJCODE' AND ITM_CODE = '$ITM_CODE'";
			$this->db->query($sqlUpd2);
		endforeach;
	}
	
	function count_all_num_rowsVend() // USED
	{
		return $this->db->count_all('tbl_supplier');
	}
	
	function viewvendor() // USED
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
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
	
	function count_all_num_rowsEmpDept()
	{
		return $this->db->count_all('tbl_employee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('tbl_employee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function viewAllPR()
	{				
		$sql = "SELECT A.MR_Number, A.MR_Date, A.Vend_Code, A.PR_EmpID, A.isAsset, B.First_Name, B.Middle_Name, B.Last_Name, C.Vend_Name, C.Vend_Address, D.Dept_Name
				FROM tproject_mrheader A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				INNER JOIN 	tvendor C ON A.Vend_Code = C.Vend_Code
				INNER JOIN	tdepartment D ON A.PR_DepID = D.Dept_ID
				ORDER BY A.MR_Number";
		return $this->db->query($sql);
	}
	
	function update_inbox($SPPNUM, $projMatReqH) // USED
	{
		$this->db->where('SPPNUM', $SPPNUM);
		$this->db->update('tbl_asset_exph', $projMatReqH);
	}
	
	function delete($MR_Number)
	{
		$this->db->where('MR_Number', $MR_Number);
		$this->db->delete($this->table);
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
				INNER JOIN tbl_unittype B ON A.Unit_Type_ID1 = B.Unit_Type_ID
				ORDER BY A.Item_Name";
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
		$sql = "SELECT A.MR_Number, A.PR_Date, A.Approval_Status, A.PR_Status, A.Vend_Code, A.PR_Notes, A.PR_EmpID, B.First_Name, B.Middle_Name, B.Last_Name
				FROM TPO_Header A
				INNER JOIN  tbl_employee B ON A.PR_EmpID = B.Emp_ID
				ORDER BY A.MR_Number";
		
		/*$this->db->select('MR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox($limit, $offset, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql 		= "SELECT DISTINCT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT FROM tbl_project A
							INNER JOIN	tbl_asset_exph D ON A.PRJCODE = D.PRJCODE
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		/*$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_asset_exph D ON A.PRJCODE = D.PRJCODE
				ORDER BY A.PRJCODE";*/
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_asset_exph D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJCODE LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function get_all_asexpjInbox_PNm($limit, $offset, $txtSearch, $DefEmp_ID) // USED
	{
		// Hanya akan menampilkan project yang sudah ada MR nya
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJNAME, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
				FROM tbl_project A
				INNER JOIN tbl_asset_exph D ON A.PRJCODE = D.PRJCODE
				WHERE A.PRJNAME LIKE '%$txtSearch%' AND A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($SPPNUM, $parameters) // USED
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
	}
	
	function count_all_PO($PR_NUM) // OK
	{
		$sql	= "tbl_po_header A
						INNER JOIN tbl_asset_exph B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->count_all($sql);
	}
	
	function get_all_PO($PR_NUM) // OK
	{
		$sql 	= "SELECT A.PO_NUM, A.PO_DATE, A.PR_NUM, B.PR_DATE, A.PO_DUED, A.SPLCODE, A.PR_NUM, C.SPLDESC
					FROM tbl_po_header A
						INNER JOIN tbl_asset_exph B ON A.PR_NUM = B.PR_NUM
							AND B.PR_STAT IN (3,6)
						INNER JOIN tbl_supplier C ON A.SPLCODE = C.SPLCODE
					WHERE A.PR_NUM = '$PR_NUM' AND A.PO_STAT IN (3,6)";
		return $this->db->query($sql);
	}
}
?>