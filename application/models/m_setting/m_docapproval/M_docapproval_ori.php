<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Januari 2018
 * File Name	= M_docapproval.php
 * Location		= -
*/

class M_docapproval extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sqlDOCAPP	= "tbl_docstepapp";
		return $this->db->count_all($sqlDOCAPP);
	}
	
	function viewdocapproval() // OK
	{
		$query = $this->db->get('tbl_docstepapp');
		return $query->result(); 
	}
	
	function get_last_ten_docapproval($limit, $offset) // OK
	{
		$sqlDOCAPP	= "SELECT * FROM tbl_docstepapp ORDER BY DOCAPP_NAME";
		return $this->db->query($sqlDOCAPP);
	}
	
	function add($InsDocApp) // OK
	{
		$this->db->insert('tbl_docstepapp', $InsDocApp);
	}
	
	function add1($InsDocAppDet) // OK
	{
		$this->db->insert('tbl_docstepapp_det', $InsDocAppDet);
	}
	
	function updateDet($UpdDocAppDet, $DOCCODE) // OK
	{
		$this->db->where('DOCCODE', $DOCCODE);
		$this->db->update('tbl_docstepapp_det', $UpdDocAppDet);
	}
	
	function get_docstep_by_code($DOCAPP_ID) // OK
	{
		$sqlDOCAPP	= "SELECT * FROM tbl_docstepapp WHERE DOCAPP_ID = $DOCAPP_ID";
		return $this->db->query($sqlDOCAPP);
	}
	
	function update($DOCAPP_ID, $UpdDocApp) // OK
	{
		$this->db->where('DOCAPP_ID', $DOCAPP_ID);
		$this->db->update('tbl_docstepapp', $UpdDocApp);
	}
	
	var $table = 'tbl_docstepapp';
	
	function searchdocapproval($konstSearch)
	{
		$selSearchType 	= $this->input->POST ('selSearchType');
		$txtSearch 		= $this->input->POST ('txtSearch');
		if($selSearchType == $konstSearch)
		{
			$this->db->like('ReqApproval_ID', $txtSearch);
		}
		else
		{
			$this->db->like('ReqApproval_Name', $txtSearch);
		}
		$query = $this->db->get('tbl_docstepapp');
		return $query->result(); 
	}
	
	function delete($ReqApproval_ID)
	{
		// Customer can not be deleted. So, just change status
		$this->db->where('ReqApproval_ID', $ReqApproval_ID);
		$this->db->update($this->table);
	}
		
	function get_MenuToPattern()
	{
		$isNeedPattern = 1;
		$this->db->select('menu_id, menu_code, menu_name');
		$this->db->from('tbl_menu');
		$this->db->where('isNeedPattern', $isNeedPattern);
		$this->db->order_by('menu_name', 'asc');
		return $this->db->get();
	}
	
	function getCount_Approver()
	{
		$this->db->where('isLastPosition', 0);
		return $this->db->count_all('tposition');
	}		

	function getApprover()
	{
		$this->db->select('Position_ID, Position_Code, Position_NameId, Position_Parent, Position_Desc, isLastPosition');
		$this->db->where('isLastPosition', 0);
		return $this->db->get('tposition');
	}
}
?>