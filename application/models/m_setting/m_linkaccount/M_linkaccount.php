<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 November 2018
 * File Name	= M_linkaccount.php
 * Location		= -
*/

class M_linkaccount extends CI_Model
{
	function get_all_accall($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
	
	function get_all_acc1($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND Account_Category = '1'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
	
	function get_all_acc2($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND Account_Category = '2'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
	
	function get_all_acc3($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND Account_Category = '3'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
	
	function get_all_acc4($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND Account_Category = '4'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
	
	function get_all_acc5($PRJCODE) // G
	{
		$sql		= "SELECT Acc_ID, Account_Number, Account_NameEn, Account_NameId, Acc_ParentList, Acc_DirParent
						FROM tbl_chartaccount WHERE Account_Level = '0'
							AND Account_Category = '5'
							AND PRJCODE = '$PRJCODE'
						ORDER BY Account_Number";
		return $this->db->query($sql);
	}
}
?>