<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 April 2015
 * File Name	= m_purchase_order.php
 * Location		= system\application\models\m_purchase\m_purchase_order\m_purchase_order.php
*/
?>
<?php
class M_purchase_order extends Model
{
	function M_purchase_order()
	{
		parent::Model();
	}
	
	var $table = 'TPO_Header';
	
	function count_all_num_rows($DefProjCode)
	{
		$sql = "TPO_Header WHERE proj_Code = '$DefProjCode' ORDER BY PO_Number";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_po($limit, $offset, $DefProjCode)
	{
		$sql = "SELECT A.PO_Number, A.PO_Date, A.Vend_Code, A.SO_NumCustomer, A.PO_DepID, A.PO_EmpID, A.Approval_Status, A.PO_Status, A.PO_Notes, A.Invoice_Status,
				B.First_Name, B.Middle_Name, B.Last_Name, C.SPLDESC AS Vend_Name
				FROM TPO_Header A
				LEFT JOIN  temployee B ON A.PORequestor_ID = B.Emp_ID
				LEFT JOIN 	tvendor_upd C ON A.Vend_Code = C.SPLCODE
				WHERE proj_Code LIKE '%$DefProjCode%'
				ORDER BY A.PO_Number";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVend()
	{
		$sql = "tvendor_upd WHERE SPL_Status = 1";
		return $this->db->count_all($sql);
	}
	
	function viewvendor()
	{
		/*$this->db->select('Vend_Code, Vend_Name, Vend_Address');
		$this->db->from('tvendor');
		$this->db->order_by('Vend_Name', 'asc');
		return $this->db->get();*/
		$sql = "SELECT SPLCODE AS Vend_Code, SPLDESC AS Vend_Name, SPLADD1 AS Vend_Address
				FROM tvendor_upd WHERE SPL_Status = 1
				ORDER BY Vend_Name";
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
	
	function getDataDocPat($MenuCode)
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
	}
	
	function count_all_num_rowsAllMR($proj_Code)
	{
		$sql = "tproject_mrheader WHERE proj_Code = '$proj_Code'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rowsAllPO($DefProj_Code)
	{
		$sql = "tpo_header WHERE proj_Code = '$DefProj_Code'";
		return $this->db->count_all($sql);
	}
	
	function viewAllMR($proj_Code)
	{
		$sql = "SELECT A.MRH_ID, A.MR_Number, A.MR_Date, A.req_date, A.latest_date, A.MR_Class, A.MR_Type, A.proj_ID, A.proj_Code, A.MR_DepID, A.MR_EmpID, A.MR_Notes,
				A.MR_Status, A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number, B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_ID, C.proj_Number, C.proj_Code, C.proj_Name, D.Dept_Name, E.SPLCODE AS Vend_Code, E.SPLDESC AS Vend_Name, E.SPLADD1 AS Vend_Address
				FROM tproject_mrheader A
				LEFT JOIN  	temployee B ON A.MR_EmpID = B.Emp_ID
				INNER JOIN 	tproject_header C ON A.proj_Code = C.proj_Code
				LEFT JOIN	tdepartment D ON A.MR_DepID = D.Dept_ID
				LEFT JOIN	tvendor_upd E ON A.Vend_Code = E.SPLCODE
				WHERE A.Approval_Status = 3 AND A.MR_Status NOT IN (1,4,5)
				AND A.proj_Code = '$proj_Code'
				ORDER BY A.MR_Number ASC";
		return $this->db->query($sql);
	}
	
	function viewAllPO($DefProj_Code)
	{
		$sql = "SELECT A.PO_ID, A.PO_Number, A.PO_Date, A.PO_DepID, A.PO_Type, A.proj_ID, A.proj_Code, A.PO_EmpID, A.PO_EmpID, A.PO_Notes, A.PO_Status, 
				A.Approval_Status, A.Approve_Date, A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
				B.First_Name, B.Middle_Name, B.Last_Name, 
				C.proj_Number, C.proj_Code, C.proj_Name, D.Dept_Name, E.SPLCODE AS Vend_Code, E.SPLDESC AS Vend_Name, E.SPLADD1 AS Vend_Address
				FROM tpo_header A
				LEFT JOIN  	temployee B ON A.PO_EmpID = B.Emp_ID
				INNER JOIN 	tproject_header C ON A.proj_Code = C.proj_Code
				LEFT JOIN	tdepartment D ON A.PO_DepID = D.Dept_ID
				LEFT JOIN	tvendor_upd E ON A.Vend_Code = E.SPLCODE
				WHERE A.Approval_Status = 3 AND A.PO_Status NOT IN (1,4,5)
				AND A.proj_Code = '$DefProj_Code'
				ORDER BY A.PO_Number ASC";
		return $this->db->query($sql);
	}
	
	function popupallItemforPO($PR_Number)
	{
		$sql = "SELECT A.Item_Code, B.Unit_Type_ID1, B.Item_Qty, B.serialNumber, B.Item_Name, C.Unit_Type_Name
				FROM tproject_mrdetail A
				INNER JOIN TItem B ON A.Item_code = B.Item_code
				INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id1
				WHERE A.MR_Number = '$PR_Number'
				ORDER BY A.MR_Number ASC";
		return $this->db->query($sql);
	}
	
	function get_PO_by_number($PO_Number)
	{
		$sql = "SELECT A.*,
				B.SPLDESC AS Vend_Name, B.SPLADD1 AS Vend_Address
				FROM TPO_Header A
				LEFT JOIN tvendor_upd B ON A.Vend_Code = B.SPLCODE
				WHERE PO_Number = '$PO_Number'";
		return $this->db->query($sql);
	}
	
	function add($purchaseorder)
	{
		$this->db->insert($this->table, $purchaseorder);
	}
	
	function update($PR_Number, $purreq)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->update($this->table, $purreq);
	}
	
	function updatePO($PO_Number, $inspo)
	{
		$this->db->where('PO_Number', $PO_Number);
		$this->db->update($this->table, $inspo);
	}
	
	function delete($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->delete($this->table);
	}
	
	function deletePODetail($PO_Number)
	{
		$this->db->where('PO_Number', $PO_Number);
		$this->db->delete('tpo_detail');
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
		return $this->db->count_all('tdocpattern');
	}
	
	// Add by DH on March, 7 2014
	function count_all_num_rows_inbox($DefEmp_ID)
	{
		$sql = "tpo_header WHERE proj_Code IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_PO_inbox($limit, $offset, $DefEmp_ID)
	{
		$sql = "SELECT A.PO_Number, A.PO_Date, A.Approval_Status, A.PO_Status, A.Vend_Code, A.PO_Notes, A.PO_EmpID, A.Invoice_Status, A.proj_ID, A.proj_Code,
				B.First_Name, B.Middle_Name, B.Last_Name, C.SPLCODE AS Vend_Code, C.SPLDESC AS Vend_Name, C.SPLADD1 AS Vend_Address
				FROM TPO_Header A
				LEFT JOIN  	temployee B ON A.PO_EmpID = B.Emp_ID
				LEFT JOIN	tvendor_upd C ON A.Vend_Code = C.SPLCODE
				WHERE A.PO_Status = 2 AND A.Approval_Status NOT IN (3,4,5)
				AND A.proj_Code IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PO_Number";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($PO_Number, $parameters)
	{		
		$PO_Number = $parameters['PO_Number'];
    	$Item_code = $parameters['Item_code'];
		$proj_ID = $parameters['proj_ID'];
		$proj_Code = $parameters['proj_Code'];
    	$Qty_PO = $parameters['Qty_PO'];
    	$Qty_PO2 = $parameters['Qty_PO2'];
    	$Base_UnitPrice = $parameters['Base_UnitPrice'];
		echo "Qty_PO = $Qty_PO";
		$sql1		= "SELECT PO_Qty, PO_Qty2, PO_QtyAmount FROM tprojplan_material WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		$resPOQty	= $this->db->query($sql1)->result();
		foreach($resPOQty as $row) :
			$PO_Qtya = $row->PO_Qty;
			$PO_Qty2a = $row->PO_Qty2;
			$POQtyAmount = $row->PO_QtyAmount;
		endforeach;
		$totPOQty1	= $Qty_PO + $PO_Qtya;
		$totPOQty2	= $Qty_PO2 + $PO_Qty2a;
		$totPOQtyAm	= $Base_UnitPrice + $POQtyAmount;
		
		$sql2		= "UPDATE tprojplan_material SET PO_Qty = $totPOQty1, PO_Qty2 = $totPOQty2, PO_QtyAmount = $totPOQtyAm
						WHERE proj_Code = '$proj_Code' AND Item_code = '$Item_code'";
		$this->db->query($sql2);
	}
	
	function updateMR($MR_Number, $parameters)
	{		
		$PO_Number = $parameters['PO_Number'];
    	$Item_code = $parameters['Item_code'];
		$proj_ID = $parameters['proj_ID'];
		$proj_Code = $parameters['proj_Code'];
    	$Qty_PO = $parameters['Qty_PO'];
    	$Qty_PO2 = $parameters['Qty_PO2'];
		
		$sql1		= "SELECT POQtyApproved2, POQtyApproved2 FROM tproject_mrdetail WHERE MR_Number = '$MR_Number' AND Item_code = '$Item_code'";
		$resPOQty	= $this->db->query($sql1)->result();
		foreach($resPOQty as $row) :
			$PO_QtyAppra 	= $row->POQtyApproved2;
			$PO_QtyAppr2a 	= $row->POQtyApproved2;
		endforeach;
		$totPOQty1	= $Qty_PO + $PO_QtyAppra;
		$totPOQty2	= $Qty_PO2 + $PO_QtyAppr2a;
		
		$sql2		= "UPDATE tproject_mrdetail SET POQtyApproved1 = $totPOQty1, POQtyApproved2 = $totPOQty2
						WHERE MR_Number = '$MR_Number' AND Item_code = '$Item_code'";
		$this->db->query($sql2);
		
		// Check apakah Jumlah PO dengan MR sudah Sama		
		// Get Total PO Qty Per PO tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
		$sqlGetPOQty		= "SELECT SUM(A.Qty_PO) AS Qty_PO, SUM(A.Qty_PO2) AS Qty_PO2
								FROM tpo_detail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN tpo_header C ON C.PO_Number = A.PO_Number
								WHERE
								C.PR_Number = '$MR_Number'
								AND C.proj_Code = '$proj_Code'";
		$resPOQty	= $this->db->query($sqlGetPOQty)->result();
		foreach($resPOQty as $row) :
			$QtyPO = $row->Qty_PO;
			$QtyPO2 = $row->Qty_PO2;
		endforeach;
		
		// Get MR Qty Per MR tanpa ada penghitungan per item, tapi total Qty Item dalam setiap MR
		$sqlGetMRQty		= "SELECT SUM(A.request_qty1) AS request_qty1, SUM(A.request_qty2) AS request_qty2
								FROM tproject_mrdetail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id1
								INNER JOIN tproject_mrheader D ON D.MR_Number = A.MR_Number
								WHERE 
								A.MR_Number = '$MR_Number'
								AND D.proj_Code = '$proj_Code'";
		$resMRQty	= $this->db->query($sqlGetMRQty)->result();
		foreach($resMRQty as $row) :
			$request_qty1 = $row->request_qty1;
			$request_qty2 = $row->request_qty2;
		endforeach;
		
		if($QtyPO == $request_qty1)
		{
			$sqlUpdMR		= "UPDATE tproject_mrheader SET MR_Status = 4
								WHERE MR_Number = '$MR_Number' AND proj_Code = '$proj_Code'";
			$this->db->query($sqlUpdMR);
		}
	}
	
	function updateMRByPO($MR_Number, $parameters)
	{		
		$PO_Number = $parameters['PO_Number'];
    	$Item_code = $parameters['Item_code'];
		$proj_ID = $parameters['proj_ID'];
		$proj_Code = $parameters['proj_Code'];
    	$Qty_PO = $parameters['Qty_PO'];
    	$Qty_PO2 = $parameters['Qty_PO2'];
		
		$sql1		= "SELECT POQty1, POQty2 FROM tproject_mrdetail WHERE MR_Number = '$MR_Number' AND Item_code = '$Item_code'";
		$resPOQty	= $this->db->query($sql1)->result();
		foreach($resPOQty as $row) :
			$PO_Qtya = $row->POQty1;
			$PO_Qty2a = $row->POQty2;
		endforeach;
		$totPOQty1	= $Qty_PO + $PO_Qtya;
		$totPOQty2	= $Qty_PO2 + $PO_Qty2a;
		
		$sql2		= "UPDATE tproject_mrdetail SET POQty1 = $totPOQty1, POQty2 = $totPOQty2
						WHERE MR_Number = '$MR_Number' AND Item_code = '$Item_code'";
		$this->db->query($sql2);
		
		// Check apakah Jumlah PO dengan MR sudah Sama		
		// Get Total PO Qty Per PO tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
		/*$sqlGetPOQty		= "SELECT SUM(A.Qty_PO) AS Qty_PO, SUM(A.Qty_PO2) AS Qty_PO2
								FROM tpo_detail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN tpo_header C ON C.PO_Number = A.PO_Number
								WHERE
								C.PR_Number = '$MR_Number'
								AND C.proj_Code = '$proj_Code'";
		$resPOQty	= $this->db->query($sqlGetPOQty)->result();
		foreach($resPOQty as $row) :
			$QtyPO = $row->Qty_PO;
			$QtyPO2 = $row->Qty_PO2;
		endforeach;*/
		
		// Get MR Qty Per MR tanpa ada penghitungan per item, tapi total Qty Item dalam setiap MR
		/*$sqlGetMRQty		= "SELECT SUM(A.request_qty1) AS request_qty1, SUM(A.request_qty2) AS request_qty2
								FROM tproject_mrdetail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN tunittype C ON C.unit_type_id = A.unit_type_id1
								INNER JOIN tproject_mrheader D ON D.MR_Number = A.MR_Number
								WHERE 
								A.MR_Number = '$MR_Number'
								AND D.proj_Code = '$proj_Code'";
		$resMRQty	= $this->db->query($sqlGetMRQty)->result();
		foreach($resMRQty as $row) :
			$request_qty1 = $row->request_qty1;
			$request_qty2 = $row->request_qty2;
		endforeach;*/
		
		/*if($QtyPO == $request_qty1)
		{
			$sqlUpdMR		= "UPDATE tproject_mrheader SET MR_Status = 4
								WHERE MR_Number = '$MR_Number' AND proj_Code = '$proj_Code'";
			$this->db->query($sqlUpdMR);
		}*/
	}
	
	// Update Material Request 2
	function updatePR2($PO_Number, $parameters)
	{		
		$MRNumber 	= $parameters['PRNumber'];
		$PO_Number = $parameters['PO_Number'];
    	$Item_code = $parameters['Item_code'];
		$proj_Code = $parameters['proj_Code'];
    	$Qty_POC = $parameters['Qty_PO'];
    	$Qty_PO2C = $parameters['Qty_PO2'];
		
		// Dapatkan jumlah PO di MR sebelum update, dikhawatirkan akan ada perubahan Qty Pemesanan
		$sql0		= "SELECT POQty1, POQty2 FROM tproject_mrdetail WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";
		$resMRQty	= $this->db->query($sql0)->result();
		foreach($resMRQty as $rowMR) :
			$POQty1A 	= $rowMR->POQty1;
			$POQty2A 	= $rowMR->POQty2;
		endforeach;
		
		// Dapatkan jumlah PO sebelum update, dikhawatirkan akan ada perubahan Qty Pemesanan
		$sql1C		= "tpo_detail";
		$sql1CR		= $this->db->count_all('tpo_detail');
		
		echo $sql1CR;
		return false;
		$sql1		= "SELECT Qty_PO, Qty_PO2 FROM tpo_detail WHERE PO_Number = '$PO_Number' AND Item_code = '$Item_code'";
		$resPOQty	= $this->db->query($sql1)->result();
		foreach($resPOQty as $rowPO) :
			$Qty_POB 	= $rowPO->Qty_PO;
			$Qty_PO2B 	= $rowPO->Qty_PO2;
		endforeach;
		
		// Cari selisi antara inputan saat Add dengan Update
		$endPOQty1	= $POQty1A + $Qty_POC - $Qty_POB;
		$endPOQty2	= $POQty2A + $Qty_PO2C - $Qty_PO2B;			
		
		$sqlUpDet	= "UPDATE tproject_mrdetail SET POQty1 = $endPOQty1, POQty2 = $endPOQty2
						WHERE MR_Number = '$MRNumber' AND Item_code = '$Item_code'";	
		$this->db->query($sqlUpDet);	
	}
	
	function count_all_num_rows_inbox_PON($proj_Code, $txtSearch)
	{
		$sql = "tpo_header A 
				INNER JOIN tproject_header B ON A.proj_Code = B.proj_Code
				WHERE A.proj_Code = '$proj_Code' AND A.PO_Number LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_inbox_VN($proj_Code, $txtSearch)
	{
		$sql = "tpo_header A 
				INNER JOIN tproject_header B ON A.proj_Code = B.proj_Code
				WHERE A.proj_Code = '$proj_Code' AND B.PO_Number LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_PO_inbox_PON($limit, $offset, $proj_Code)
	{
		$sql = "SELECT A.PO_Number, A.PO_Date, A.Approval_Status, A.PO_Status, A.Vend_Code, A.PO_Notes, A.PO_EmpID, A.Invoice_Status, A.proj_ID, A.proj_Code,
				B.First_Name, B.Middle_Name, B.Last_Name, C.SPLCODE AS Vend_Code, C.SPLDESC AS Vend_Name, C.SPLADD1 AS Vend_Address
				FROM TPO_Header A
				LEFT JOIN  temployee B ON A.PO_EmpID = B.Emp_ID
				LEFT JOIN	tvendor_upd C ON A.Vend_Code = C.SPLCODE
				WHERE A.PO_Status = 2 AND A.Approval_Status NOT IN (3,4,5)
				AND A.proj_Code = '$proj_Code'
				ORDER BY A.PO_Number";
		
		/*$this->db->select('PR_Number, PR_Date, Approval_Status, PR_Status, Vend_Code, PR_Notes, PR_EmpID');
		$this->db->from('TPO_Header');
		$this->db->order_by('PR_Date', 'asc');*/
		//$this->db->limit($limit, $offset);
		//return $this->db->get();
		return $this->db->query($sql);
	}
}
?>