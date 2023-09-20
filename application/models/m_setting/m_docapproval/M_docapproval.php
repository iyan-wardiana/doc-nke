<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Januari 2018
 * File Name	= M_docapproval.php
 * Location		= -
*/

class M_docapproval extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_docstepapp A 
				WHERE PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
					OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
					OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!' ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!' LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_AllDataGRPC($search, $MENU_CODE, $PRJCODE) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($MENU_CODE != '')
			$ADDQRY1 	= "AND A.MENU_CODE = '$MENU_CODE'";
		if($PRJCODE != '')
			$ADDQRY2 	= "AND A.PRJCODE = '$PRJCODE'";

		$sql = "tbl_docstepapp A 
				WHERE (PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
					OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
					OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!') $ADDQRY1 $ADDQRY2";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataGRPL($search, $MENU_CODE, $PRJCODE, $length, $start, $order, $dir) // GOOD
	{
		$ADDQRY1 	= "";
		$ADDQRY2 	= "";

		if($MENU_CODE != '')
			$ADDQRY1 	= "AND A.MENU_CODE = '$MENU_CODE'";
		if($PRJCODE != '')
			$ADDQRY2 	= "AND A.PRJCODE = '$PRJCODE'";

		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE (PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!') $ADDQRY1 $ADDQRY2 ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE (PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!') $ADDQRY1 $ADDQRY2";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE (PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!') $ADDQRY1 $ADDQRY2 ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_docstepapp A 
						WHERE (PRJCODE LIKE '%$search%' ESCAPE '!' OR POSCODE LIKE '%$search%' ESCAPE '!' 
							OR DOCAPP_NAME LIKE '%$search%' ESCAPE '!' OR APPROVER_1 LIKE '%$search%' ESCAPE '!'
							OR APPROVER_2 LIKE '%$search%' ESCAPE '!' OR APPROVER_3 LIKE '%$search%' ESCAPE '!') $ADDQRY1 $ADDQRY2 LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}

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