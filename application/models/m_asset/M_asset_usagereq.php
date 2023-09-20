<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 11 April 2017
 * File Name	= M_asset_usagereq.php
 * Location		= -
*/
?>
<?php
class M_asset_usagereq extends CI_Model
{	
	function count_all_num_rows($PRJCODE) // OK
	{
		$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AU($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsAllAsset($PRJCODE, $StartDate, $EndDate) // OK
	{
		//$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate";
		$sql		= "tbl_asset_list";
		return $this->db->count_all($sql);
	}
	
	function viewAllIAsset($PRJCODE, $StartDate, $EndDate) // OK
	{
		//$sql		= "SELECT * FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STARTD >= $StartDate AND AUR_ENDD <= $EndDate ORDER BY AUR_DATE ASC";
		$sql		= "SELECT AS_CODE, AS_NAME, AS_DESC, AS_STAT FROM tbl_asset_list ORDER BY AS_NAME";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // HOLD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function count_all_num_rowsAllItem() // OK
	{
		return $this->db->count_all('tbl_item');
	}
	
	function viewAllItemMatBudget($PRJCODE) // OK
	{		
		$sql		= "SELECT DISTINCT Z.PRJCODE, Z.ITM_CODE, Z.ITM_CATEG, Z.ITM_NAME, Z.ITM_DESC, Z.ITM_TYPE, Z.ITM_UNIT, Z.UMCODE, Z.ITM_VOLM, Z.ITM_PRICE,
							B.Unit_Type_Name
						FROM tbl_item Z
							INNER JOIN tbl_unittype B ON B.unit_type_code = Z.UMCODE
						WHERE Z.PRJCODE = '$PRJCODE'
						ORDER BY Z.ITM_CODE";
		return $this->db->query($sql);
	}
	
	function add($InsAG) // OK
	{
		$this->db->insert('tbl_asset_usagereq', $InsAG);
	}
				
	function get_AU($AUR_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_asset_usagereq
				WHERE AUR_CODE = '$AUR_CODE'";
		return $this->db->query($sql);
	}
	
	function update($AUR_CODE, $UpdAU) // OK
	{
		$this->db->where('AUR_CODE', $AUR_CODE);
		$this->db->update('tbl_asset_usagereq', $UpdAU);
	}	
	
	function count_all_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project 
						WHERE 
							PRJCODE IN (SELECT AUR_PRJCODE FROM tbl_asset_usagereq 
											WHERE AUR_PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AUR_STAT = 2)";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_inb() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT AUR_PRJCODE FROM tbl_asset_usagereq 
												WHERE AUR_PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND AUR_STAT = 2)
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_inb($PRJCODE) // OK
	{
		$sql		= "tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STAT = 2";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AU_inb($PRJCODE) // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_usagereq WHERE AUR_PRJCODE = '$PRJCODE' AND AUR_STAT = 2";
		return $this->db->query($sql);
	}
}
?>