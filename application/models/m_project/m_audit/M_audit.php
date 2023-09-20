<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 8 Agustus 2019
 * File Name	= m_audit.php
 * Location		= -
*/
?>
<?php
class M_audit extends CI_Model
{
	function count_allDoc($DefEmp_ID) // OK
	{
		$sql = "tbl_audit_repot WHERE IF( EXISTS(SELECT * FROM tbl_audit_emp WHERE Emp_ID = '$DefEmp_ID'), 1, 0) = 1";
		return $this->db->count_all($sql);
	}
	
	function get_allDoc($DefEmp_ID) // OK
	{		
		$sql = "SELECT AUI_NUM, AUI_CODE, PRJCODE, AUI_STEP, AUI_ORDNO, AUI_SUBJEK, AUI_LOC, AUI_DATE, AUI_DATE_NCR, AUI_AUDITOR,
					AUI_PROBLDESC, AUI_STAT, 1 AS TYPE
				FROM tbl_audit_repot WHERE IF( EXISTS(SELECT * FROM tbl_audit_emp WHERE Emp_ID = '$DefEmp_ID'), 1, 0) = 1
				UNION ALL
				SELECT AUN_NUM AS AUI_NUM, AUN_CODE AS AUI_CODE, PRJCODE, '' AS AUI_STEP, '' AS AUI_ORDNO, AUN_AUDITEE AS AUI_SUBJEK, '' AS AUI_LOC,
					AUN_DATE AS AUI_DATE, AUN_DATE AS AUI_DATE_NCR, AUN_AUDITOR AS AUI_AUDITOR, AUN_DESC AS AUI_PROBLDESC, AUN_STAT AS AUI_STAT, 2 AS TYPE
				FROM tbl_auditn_h WHERE IF( EXISTS(SELECT * FROM tbl_audit_emp WHERE Emp_ID = '$DefEmp_ID'), 1, 0) = 1";
		return $this->db->query($sql);
	}
	
	function count_allDocUsr($DefEmp_ID) // OK
	{
		$sql = "tbl_audit_repot WHERE AUI_SUBJEK = '$DefEmp_ID' AND AUI_STAT1 = 1";
		return $this->db->count_all($sql);
	}
	
	function get_allDocUsr($DefEmp_ID) // OK
	{
		$sql = "SELECT *, 1 AS TYPE FROM tbl_audit_repot WHERE AUI_SUBJEK = '$DefEmp_ID' AND AUI_STAT1 = 1";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($insAudit) // OK
	{
		$this->db->insert('tbl_audit_repot', $insAudit);
	}
	
	function get_dok_by_code($AUI_NUM) // OK
	{
		$sql = "SELECT * FROM tbl_audit_repot WHERE AUI_NUM = '$AUI_NUM'";
		return $this->db->query($sql);
	}
	
	function get_dok_by_code4($AUN_NUM) // OK
	{
		$sql = "SELECT * FROM tbl_auditn_h WHERE AUN_NUM = '$AUN_NUM'";
		return $this->db->query($sql);
	}
	
	function update($AUI_NUM, $updAudit) // OK
	{
		$this->db->where('AUI_NUM', $AUI_NUM);
		$this->db->update('tbl_audit_repot', $updAudit);
	}
	
	function addNAud($insNoteAud) // OK
	{
		$this->db->insert('tbl_auditn_h', $insNoteAud);
	}
	
	function updateNAud($AUN_NUM, $updNoteAud) // OK
	{
		$this->db->where('AUN_NUM', $AUN_NUM);
		$this->db->update('tbl_auditn_h', $updNoteAud);
	}
	
	function delDetNAud($AUN_NUM) // OK
	{
		$sql = "DELETE FROM tbl_auditn_d WHERE AUN_NUM = '$AUN_NUM'";
		return $this->db->query($sql);
	}

	function addUpload($insUpload)
	{
		$this->db->insert('tbl_audit_pic', $insUpload);
	}
}
?>