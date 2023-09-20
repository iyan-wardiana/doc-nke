<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 7 Juli 2019
 * File Name	= M_comprof.php
 * Location		= -
*/

class M_comprof extends CI_Model
{
	function get_comprof()
	{
		$sql = "SELECT proj_Number, PRJCODE, PRJCNUM, PRJNAME, PRJLOCT, PRJADD, PRJTELP, PRJFAX, PRJMAIL,
					PRJOWN, PRJDATE, PRJDATE_CO, PRJEDAT,
					PRJCOST, PRJCATEG, PRJSCATEG, PRJLKOT, PRJCBNG, PRJCURR, CURRRATE, PRJSTAT, PRJNOTE, ISCHANGE, REFCHGNO, 
					PRJCOST2, PRJ_MNG, PRJBOQ, PRJRAP, CHGUSER, CHGSTAT, PRJPROG, QTY_SPYR, PRC_STRK, PRC_ARST, 
					PRC_MKNK, PRC_ELCT, PRJ_IMGNAME, isHO, Patt_Year
				FROM tbl_project
				WHERE isHO = 1 LIMIT 1";
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

		// SEHARUSNYA SEMUA BUDGET YANG MENGINDUK KE $PRJCODEBEF DIRUBAH KE TYPE BARU
			/*$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project', $projectheader);

			$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project_budg', $projectheader);
			
			$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project_budgm', $projectheader);*/
	}
	
	function update2($PRJCODEBEF, $projectheader2)
	{
		// SEHARUSNYA SEMUA BUDGET YANG MENGINDUK KE $PRJCODEBEF DIRUBAH KE TYPE BARU
			$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project', $projectheader2);

			$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project_budg', $projectheader2);
			
			$this->db->where('PRJCODE_HO', $PRJCODEBEF);
			$this->db->update('tbl_project_budgm', $projectheader2);
	}
	
	function updatePict($PRJCODE, $nameFile) // U
	{
		$updatePict	= "UPDATE tbl_project SET PRJ_IMGNAME = '$nameFile' WHERE PRJCODE = '$PRJCODE'";
		$this->db->query($updatePict);
	}
	
	function updDesc($CompD) // U
	{
		$updatePict	= "UPDATE tglobalsetting SET CompDesc = '$CompD'";
		$this->db->query($updatePict);
	}
}
?>