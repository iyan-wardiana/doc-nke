<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= M_itemlist.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Edit Date	= 05 September 2017
 * File Name	= m_entry_other.php
 * Location		= -
*/

?>
<?php
class M_entry_other extends CI_Model
{	
	function count_all_num_rows($PRJCODE) // OK
	{
		$sql	= "tbl_profloss_man 
					WHERE FM_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_item($PRJCODE) // OK
	{
		$sql	= "SELECT * FROM tbl_profloss_man
					WHERE FM_PRJCODE = '$PRJCODE'
					ORDER BY FM_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsUnit() // OK
	{
		return $this->db->count_all('tbl_unittype');
	}
	
	function viewUnit() // OK
	{
		$this->db->select('Unit_Type_Code, UMCODE, Unit_Type_Name');
		$this->db->from('tbl_unittype');
		$this->db->order_by('UMCODE', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rowsCateg() // OK
	{
		return $this->db->count_all('tbl_itemcategory');
	}
	
	function viewCateg() // OK
	{
		$sql = "SELECT *
				FROM tbl_itemcategory";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProject() // OK
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // OK
	{		
		$sql = "SELECT A.proj_Number, A.proj_Code, A.proj_Name, A.proj_Date, A.proj_StartDate, A.proj_EndDate, A.proj_addDate, A.Proj_ownCode, A.proj_ContractNo, 
				A.proj_Status, A.proj_Location, A.proj_Type, A.proj_Category, A.proj_Currency, A.Patt_Year, A.proj_notes, A.Patt_Number, 
				A.proj_CurrencyRate, A.proj_amountUSD, A.proj_amountIDR, A.proj_MP, A.PRJLKOT, A.isActive,
				B.own_Code, B.own_Title, B.own_Name
				FROM tbl_project A
				LEFT JOIN  towner B ON A.proj_ownCode = B.own_Code";
		return $this->db->query($sql);
	}
	
	function add($itemPar) // OK
	{
		$this->db->insert('tbl_profloss_man', $itemPar);
	}
	
	function viewemployee()
	{
		$query = $this->db->get('tbl_cost');
		return $query->result(); 
	}
	
	var $table = 'tbl_cost';
	
	function searchitem($konstSearch)
	{
		$selSearchType 	= $this->input->POST ('selSearchType');
		$txtSearch 		= $this->input->POST ('txtSearch');
		if($selSearchType == $konstSearch)
		{
			$this->db->like('Item_Code', $txtSearch);
		}
		else
		{
			$this->db->like('ITM_DESC', $txtSearch);
		}
		$query = $this->db->get('tbl_cost');
		return $query->result(); 
	}
	
	function get_item_by_code($FM_CODE)
	{
		$sql	= "SELECT * FROM tbl_profloss_man
					WHERE FM_CODE = '$FM_CODE'
					ORDER BY FM_CODE ASC";
		return $this->db->query($sql);
	}
	
	function update($FM_CODE, $itemPar)
	{	
		$FM_CODE		= $itemPar['FM_CODE'];
		$FM_PERIODE		= $itemPar['FM_PERIODE'];
		$FM_PRJCODE		= $itemPar['FM_PRJCODE'];
		$FM_PROGRES		= $itemPar['FM_PROGRES'];
		$FM_PREDICTION	= $itemPar['FM_PREDICTION'];
		$FM_MCKATROLAN	= $itemPar['FM_MCKATROLAN'];
		$FM_STATUS		= $itemPar['FM_STATUS'];;
		$FM_NOTE		= $itemPar['FM_NOTE'];
		$FM_CREATED		= $itemPar['FM_CREATED'];
		$FM_CREATER		= $itemPar['FM_CREATER'];
		
		/*$ITM_CODE		= $itemPar['ITM_CODE'];
		$ITM_CATEG		= $itemPar['ITM_CATEG'];
		$ITM_NAME		= $itemPar['ITM_NAME'];
		$ITM_DESC		= $itemPar['ITM_DESC'];
		$ITM_TYPE		= $itemPar['ITM_TYPE'];
		$ITM_UNIT		= $itemPar['ITM_UNIT'];
		$UMCODE			= $itemPar['ITM_UNIT'];
		$ITM_CURRENCY	= $itemPar['ITM_CURRENCY'];
		$ITM_PRICE		= $itemPar['ITM_PRICE'];
		$ITM_VOLM		= $itemPar['ITM_VOLM'];
		$STATUS			= $itemPar['STATUS'];
		$ISRENT			= $itemPar['ISRENT'];
		$ISPART			= $itemPar['ISPART'];
		$ISFUEL			= $itemPar['ISFUEL'];
		$ISLUBRIC		= $itemPar['ISLUBRIC'];
		$ISFASTM		= $itemPar['ISFASTM'];
		$ISWAGE			= $itemPar['ISWAGE'];
		$ITM_KIND		= $itemPar['ITM_KIND'];*/
		
		$sqlUpd		= "UPDATE tbl_profloss_man SET 
						FM_CODE='$FM_CODE', FM_PERIODE='$FM_PERIODE', FM_PRJCODE='$FM_PRJCODE', FM_PROGRES='$FM_PROGRES', FM_PREDICTION='$FM_PREDICTION', FM_MCKATROLAN='$FM_MCKATROLAN', FM_NOTE='$FM_NOTE', FM_STATUS='$FM_STATUS', FM_CREATER='$FM_CREATER', FM_CREATED='$FM_CREATED'
						WHERE FM_CODE = '$FM_CODE'";
		return $this->db->query($sqlUpd);
	}
	
	function delete($Item_Code)
	{
		$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
		$this->db->where('PRJCODE', $PRJCODE);
		$this->db->where('Item_Code', $Item_Code);
		$this->db->update($this->table);
	}
	
	function updateStatus($CodeItem, $NItemStatus)
	{
		$PRJCODE = $this->session->userdata['dtSessSrc1']['selSearchproj_Code'];
		$sql2		= "UPDATE tbl_cost SET STATUS	= '$NItemStatus'
						WHERE Item_code = '$CodeItem' AND PRJCODE = '$PRJCODE'";
		$this->db->query($sql2);
	}
	
	// Searching Function
	function count_all_num_rows_src_ic($txtSearch, $selSearchproj_Code)
	{
		$sql	= "tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.Item_Code LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_src_in($txtSearch, $selSearchproj_Code)
	{		
		$sql	= "tbl_cost WHERE ITM_DESC LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_item_ic($limit, $offset, $txtSearch, $selSearchproj_Code)
	{
		$sql	= "SELECT A.PRJCODE, A.ITM_CODE, A.Item_Code, A.ITM_CATEG, A.ITM_DESC, A.ITM_TYPE, A.ITM_UNIT, A.UMCODE, A.ITM_PRICE, A.ITM_VOLM,  A.STATUS, A.LASTNO,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.Item_Code LIKE '%$txtSearch%' AND PRJCODE = '$selSearchproj_Code'
					ORDER BY ITM_DESC ASC
					LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_item_in($limit, $offset, $txtSearch, $selSearchproj_Code)
	{
		$sql	= "SELECT A.PRJCODE, A.ITM_CODE, A.Item_Code, A.ITM_CATEG, A.ITM_DESC, A.ITM_TYPE, A.ITM_UNIT, A.UMCODE, A.ITM_PRICE, A.ITM_VOLM,  A.STATUS, A.LASTNO,
					B.UMCODE, B.Unit_Type_Name
					FROM  tbl_cost A
					INNER JOIN tbl_unittype B ON A.UMCODE = B.UMCODE
					WHERE A.ITM_DESC LIKE '%$txtSearch%'
						AND A.PRJCODE = '$selSearchproj_Code'
					ORDER BY A.ITM_DESC ASC
					LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode)
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
}
?>