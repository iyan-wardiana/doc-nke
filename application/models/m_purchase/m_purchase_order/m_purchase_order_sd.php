<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 26 April 2016
 * File Name	= m_purchase_order_sd.php
 * Location		= -
*/
?>
<?php
class M_purchase_order_sd extends Model
{
	function M_purchase_order_sd()
	{
		parent::Model();
	}
	
	var $table = 'sd_top_header';
	
	function count_all_num_rows($DefProjCode) // U
	{
		$sql = "sd_top_header WHERE PRJCODE = '$DefProjCode' ORDER BY OP_CODE";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_po($limit, $offset, $DefProjCode) // U
	{
		// SPPCODE = Permintaan Pembelian
		$sql = "SELECT A.PO_Number, A.OP_CODE, A.OP_CAT, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST, A.TRXUSER, 
					A.DP_CODE, A.DP_PPN_, A.DP_JUML, 
					A.APPROVE, A.APPRUSR, A.OP_STAT, A.REVMEMO, A.INVSTAT,
					B.SPLDESC
				FROM sd_top_header A
				LEFT JOIN 	sd_tsupplier B ON A.SPLCODE = B.SPLCODE
				WHERE PRJCODE LIKE '%$DefProjCode%'
				ORDER BY A.PO_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVend() // U
	{
		$sql = "sd_tsupplier WHERE SPLSTAT = 1";
		return $this->db->count_all($sql);
	}
	
	function viewvendor() // U
	{
		$sql = "SELECT SPLCODE, SPLDESC, SPLADD1
				FROM sd_tsupplier WHERE SPLSTAT = 1
				ORDER BY SPLDESC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('sd_tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllMR($PRJCODE) // U
	{
		$sql = "sd_tspp_header WHERE PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function viewAllMR($PRJCODE) // U
	{
		$sql = "SELECT A.SPPNUM, A.SPPCODE, A.TRXDATE, A.PRJCODE, A.TRXOPEN, A.TRXUSER, A.APPROVE, A.APPRUSR, A.JOBCODE, A.SPPNOTE, A.SPPSTAT, REVMEMO,
					A.Patt_Year, A.Patt_Month, A.Patt_Date, A.Patt_Number,
					B.First_Name, B.Middle_Name, B.Last_Name,
					C.proj_Number, C.PRJCODE, C.PRJNAME,
					D.SPLCODE, D.SPLDESC, D.SPLADD1
				FROM sd_tspp_header A
				INNER JOIN  thrmemployee B ON A.TRXUSER = B.Emp_ID
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.PRJCODE = '$PRJCODE' AND A.APPROVE = 3 
				AND A.SPPSTAT NOT IN (1,4,5)
				ORDER BY A.SPPNUM ASC";
		return $this->db->query($sql);
	}
	
	function get_PO_by_number($PO_Number) // U
	{
		$sql = "SELECT A.*,
				B.SPLDESC, B.SPLADD1
				FROM sd_top_header A
				LEFT JOIN sd_tsupplier B ON A.SPLCODE = B.SPLCODE
				WHERE PO_Number = '$PO_Number'";
		return $this->db->query($sql);
	}
	
	function updatePO($PO_Number, $inspo) // U
	{
		$this->db->where('PO_Number', $PO_Number);
		$this->db->update('sd_top_header', $inspo);
	}
	
	function deletePODetail($OP_CODE) // U
	{
		$this->db->where('OP_CODE', $OP_CODE);
		$this->db->delete('sd_top_detail');
	}
	
	function add($purchaseorder) // U
	{
		$this->db->insert('sd_top_header', $purchaseorder);
	}
	
	function updateMRByPO($SPPCODE, $parameters)
	{
		$PO_Number 		= $parameters['PO_Number'];
    	$OP_CODE 		= $parameters['OP_CODE'];
		$CSTCODE 		= $parameters['CSTCODE'];
		$OPVOLM 		= $parameters['OPVOLM'];
    	$CSTPUNT 		= $parameters['CSTPUNT'];
		
		// START : UPDATE PO QTY IN SPP TABLE
		$sql1			= "SELECT POVOLM FROM sd_tspp_detail WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$resSPPQty		= $this->db->query($sql1)->result();
		foreach($resSPPQty as $row) :
			$POQty 		= $row->POVOLM;
		endforeach;
		
		$totPOQty1	= $OPVOLM + $POQty;
		
		$sql2		= "UPDATE sd_tspp_detail SET POVOLM = $totPOQty1 WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sql2);
		
		// Check apakah Jumlah PO dengan MR sudah Sama		
		// Get Total PO Qty Per PO tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
		/*$sqlGetPOQty		= "SELECT SUM(A.Qty_PO) AS Qty_PO, SUM(A.Qty_PO2) AS Qty_PO2
								FROM tpo_detail A
								INNER JOIN TItem B ON A.Item_code = B.Item_code
								INNER JOIN sd_top_header C ON C.PO_Number = A.PO_Number
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
	
	function count_all_num_rows_inbox($DefEmp_ID) // U
	{
		$sql = "sd_top_header WHERE PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_PO_inbox($limit, $offset, $DefEmp_ID) // U
	{
		$sql = "SELECT A.PO_Number, A.OP_CODE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST, A.TRXUSER, A.DP_CODE, A.DP_PPN_, A.DP_JUML, 
				A.APPROVE, A.APPRUSR, A.OP_STAT, A.REVMEMO, A.INVSTAT,
				D.SPLDESC
				FROM sd_top_header A
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.OP_STAT = 2
				AND A.APPROVE NOT IN (3,4,5)
				AND A.PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')				
				ORDER BY A.PO_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_inbox_PON($PRJCODE, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "sd_top_header A
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.OP_STAT = 2
				AND A.APPROVE NOT IN (3,4,5)
				AND A.PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')
				AND A.PRJCODE = '$PRJCODE' AND A.PO_Number LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_inbox_VN($PRJCODE, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "sd_top_header A
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.OP_STAT = 2
				AND A.APPROVE NOT IN (3,4,5)
				AND A.PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')
				AND A.PRJCODE = '$PRJCODE' AND D.SPLDESC LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_PO_inbox_PON($limit, $offset, $PRJCODE, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "SELECT A.PO_Number, A.OP_CODE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST, A.TRXUSER, A.DP_CODE, A.DP_PPN_, A.DP_JUML, 
				A.APPROVE, A.APPRUSR, A.OP_STAT, A.REVMEMO, A.INVSTAT,
				D.SPLDESC
				FROM sd_top_header A
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.OP_STAT = 2
				AND A.APPROVE NOT IN (3,4,5)
				AND A.PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')	
				AND A.PO_Number LIKE '%$txtSearch%'
				ORDER BY A.PO_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_PO_inbox_VN($limit, $offset, $PRJCODE, $txtSearch, $DefEmp_ID) // U
	{
		$sql = "SELECT A.PO_Number, A.OP_CODE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OP_COST, A.TRXUSER, A.DP_CODE, A.DP_PPN_, A.DP_JUML, 
				A.APPROVE, A.APPRUSR, A.OP_STAT, A.REVMEMO, A.INVSTAT,
				D.SPLDESC
				FROM sd_top_header A
				INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
				LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE A.OP_STAT = 2
				AND A.APPROVE NOT IN (3,4,5)
				AND A.PRJCODE IN (SELECT proj_Code FROM thrmemployee_proj WHERE Emp_ID = '$DefEmp_ID')	
				AND D.SPLDESC LIKE '%$txtSearch%'
				ORDER BY A.PO_Number
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	// Update Project Plan Material
	function updatePP($PO_Number, $parameters) // U
	{
		$PO_Number 			= $parameters['PO_Number'];
    	$OP_CODE 			= $parameters['OP_CODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$CSTCODE 			= $parameters['CSTCODE'];
		$OPVOLM 			= $parameters['OPVOLM'];
    	$CSTPUNT 			= $parameters['CSTPUNT'];
    	$SPPCODE 			= $parameters['SPPCODE'];
		
		$sql1				= "SELECT PO_Qty, PO_Qty2, PO_QtyAmount FROM sd_tprojplan_material WHERE PRJCODE = '$PRJCODE' AND CSTCODE = '$CSTCODE'";
		$resPOQty			= $this->db->query($sql1)->result();
		foreach($resPOQty as $row) :
			$PO_Qtya 		= $row->PO_Qty;
			$PO_Qty2a 		= $row->PO_Qty2;
			$POQtyAmount 	= $row->PO_QtyAmount;
		endforeach;
		$totPOQty1	= $OPVOLM + $PO_Qtya;
		$totPOQty2	= $OPVOLM + $PO_Qty2a;
		$totPOQtyAm	= $CSTPUNT + $POQtyAmount;
		$totRRQtyAm	= $CSTCOST + $RRQty_Amount;
		
		$sql2		= "UPDATE sd_tprojplan_material SET PO_Qty = $totPOQty1, PO_Qty2 = $totPOQty2, PO_QtyAmount = $totPOQtyAm
						WHERE PRJCODE = '$PRJCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sql2);
	}
	
	function updateMR($SPPCODE, $parameters) // U
	{
		$PO_Number 			= $parameters['PO_Number'];
    	$OP_CODE 			= $parameters['OP_CODE'];
		$PRJCODE 			= $parameters['PRJCODE'];
		$CSTCODE 			= $parameters['CSTCODE'];
		$OPVOLM 			= $parameters['OPVOLM'];
    	$CSTPUNT 			= $parameters['CSTPUNT'];
    	$SPPCODE 			= $parameters['SPPCODE'];
		
		$sql1				= "SELECT OPVOLM FROM sd_tspp_detail WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$resPOQty			= $this->db->query($sql1)->result();
		foreach($resPOQty as $row) :
			$PO_QtyAppra	= $row->OPVOLM;
		endforeach;
		$totPOQty1			= $OPVOLM + $PO_QtyAppra;
		$totPOQty2			= $OPVOLM + $PO_QtyAppra;
		
		$sql2				= "UPDATE sd_tspp_detail SET OPVOLM = $totPOQty1 WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sql2);
		
		// Check apakah Jumlah PO dengan MR sudah Sama		
		// Get Total PO Qty Per PO tanpa ada penghitungan per item, tapi total Qty Item dalam setiap PO
		$sqlGetPOQty		= "SELECT SUM(A.OPVOLM) AS Qty_PO
								FROM sd_top_detail A
								INNER JOIN sd_tcost B ON A.CSTCODE = B.CSTCODE
								INNER JOIN sd_top_header C ON C.OP_CODE = A.OP_CODE
								WHERE
								C.SPPCODE = '$SPPCODE'
								AND C.PRJCODE = '$PRJCODE'";
		$resPOQty			= $this->db->query($sqlGetPOQty)->result();
		foreach($resPOQty as $row) :
			$QtyPO 			= $row->Qty_PO;
			$QtyPO2 		= $row->Qty_PO;
		endforeach;
		
		// Get MR Qty Per MR tanpa ada penghitungan per item, tapi total Qty Item dalam setiap MR
		$sqlGetMRQty		= "SELECT SUM(A.SPPVOLM) AS SPPVOLM
								FROM sd_tspp_detail A
								INNER JOIN sd_tcost B ON A.CSTCODE = B.CSTCODE
								INNER JOIN sd_tunittype C ON C.Unit_Type_Code = A.CSTUNIT
								INNER JOIN sd_tspp_header D ON D.SPPCODE = A.SPPCODE
								WHERE 
								A.SPPCODE = '$SPPCODE'
								AND D.PRJCODE = '$PRJCODE'";
		$resMRQty	= $this->db->query($sqlGetMRQty)->result();
		foreach($resMRQty as $row) :
			$request_qty1 = $row->SPPVOLM;
		endforeach;
		
		if($QtyPO == $request_qty1)
		{
			$sqlUpdMR		= "UPDATE sd_tspp_header SET SPPSTAT = 4
								WHERE SPPCODE = '$SPPCODE' AND PRJCODE = '$PRJCODE'";
			$this->db->query($sqlUpdMR);
		}
	}
	
	function count_all_num_rowsAllPO($DefProj_Code) // U
	{
		$sql = "sd_top_header WHERE PRJCODE = '$DefProj_Code' AND APPROVE = 3";
		return $this->db->count_all($sql);
	}
	
	function viewAllPO($DefProj_Code) // U
	{
		$sql = "SELECT A.PO_Number, A.OP_CODE, A.OP_TYPE, A.TRXDATE, A.PRJCODE, A.SPLCODE, A.SPPCODE, A.OPCURR, A.CURRATE, A.TAXCURR, A.TAXCRATE, A.OP_COST, A.TRXUSER,
					A.DP_CODE, A.DP_PPN_, A.DP_JUML, A.APPROVE, A.APPRUSR, A.OP_STAT, A.INVSTAT, A.OPNOTES, A.REVMEMO,
					B.First_Name, B.Middle_Name, B.Last_Name,
					D.SPLDESC, D.SPLADD1
				FROM sd_top_header A
					LEFT JOIN  	thrmemployee B ON A.TRXUSER = B.Emp_ID
					INNER JOIN 	sd_tproject C ON A.PRJCODE = C.PRJCODE
					LEFT JOIN	sd_tsupplier D ON A.SPLCODE = D.SPLCODE
				WHERE 
					A.APPROVE = 3 
					AND A.OP_STAT NOT IN (1,4,5)
					AND A.PRJCODE = '$DefProj_Code'
				ORDER BY A.PO_Number ASC";
		return $this->db->query($sql);
	}
	
	// Update Material Request 2
	function updatePR2($PO_Number, $parameters)
	{
		$PO_Number 		= $parameters['PO_Number'];
    	$OP_CODE 		= $parameters['OP_CODE'];
		$CSTCODE 		= $parameters['CSTCODE'];
		$OPVOLM 		= $parameters['OPVOLM'];
    	$CSTPUNT 		= $parameters['CSTPUNT'];
    	$SPPCODE 		= $parameters['SPPCODE'];
		
		// Dapatkan jumlah PO di MR sebelum update, dikhawatirkan akan ada perubahan Qty Pemesanan
		$sql0		= "SELECT SPPVOLM FROM sd_tspp_detail WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$resMRQty	= $this->db->query($sql0)->result();
		foreach($resMRQty as $rowMR) :
			$POQty1A 	= $rowMR->SPPVOLM;
			$POQty2A 	= $rowMR->SPPVOLM;
		endforeach;
		
		// Dapatkan jumlah PO sebelum update, dikhawatirkan akan ada perubahan Qty Pemesanan
		//$sql1C		= "sd_top_detail";
		//$sql1CR		= $this->db->count_all('sd_top_detail');
		
		$sql1		= "SELECT OPVOLM FROM sd_top_detail WHERE OP_CODE = '$OP_CODE' AND CSTCODE = '$CSTCODE'";
		$resPOQty	= $this->db->query($sql1)->result();
		foreach($resPOQty as $rowPO) :
			$Qty_POB 	= $rowPO->OPVOLM;
			$Qty_PO2B 	= $rowPO->OPVOLM;
		endforeach;
		
		// Cari selisi antara inputan saat Add dengan Update
		$endPOQty1	= $POQty1A + $OPVOLM - $Qty_POB;
		$endPOQty2	= $POQty2A + $OPVOLM - $Qty_PO2B;			
		
		$sqlUpDet	= "UPDATE sd_tspp_detail SET OPVOLM = $endPOQty1 WHERE SPPCODE = '$SPPCODE' AND CSTCODE = '$CSTCODE'";
		$this->db->query($sqlUpDet);
	}
	
	function count_all_num_rowsAllPR()
	{
		return $this->db->count_all('TPReq_Header');
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
	
	function update($PR_Number, $purreq)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->update($this->table, $purreq);
	}
	
	function delete($PR_Number)
	{
		$this->db->where('PR_Number', $PR_Number);
		$this->db->delete($this->table);
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
		return $this->db->count_all('sd_tdocpattern');
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
		return $this->db->count_all('thrmemployee');
	}
	
	function viewEmployeeDept()
	{
		$this->db->select('Emp_ID, First_name, Middle_Name, Last_Name');
		$this->db->from('thrmemployee');
		$this->db->order_by('First_name', 'asc');
		return $this->db->get();
	}
}
?>