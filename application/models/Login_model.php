<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Login_model extends CI_Model {
	// Inisialisasi nama tabel user
	var $table = 'tbl_employee';
	
	function check_user($username, $password)
	{
		$sqlUNC 	= "tbl_employee WHERE log_username = '$username' AND log_password = '$password' AND Emp_Status = 1";
		$resultUNC 	= $this->db->count_all($sqlUNC);
		
		if($resultUNC > 0)
		{
			$sqlUN 		= "SELECT log_username, log_password FROM tbl_employee WHERE log_username = '$username' AND log_password = '$password'";
			$resultUN 	= $this->db->query($sqlUN)->result();
			
			foreach($resultUN as $rowUN) :
				$log_username		= $rowUN->log_username;
				$log_password		= $rowUN->log_password;
			endforeach;
			if($log_username == $username && $log_password == $password)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	function addLogin($insLog) // USED
	{
		$this->db->insert('tbl_login_hist', $insLog);
	}
	
	function updateLogout($LOG_CODE, $updLog) // USED
	{
		$this->db->where('LOG_CODE', $LOG_CODE);
		$this->db->update('tbl_login_hist', $updLog);
	}
	
	function get_alltask($DefEmp_ID) // USED
	{
		$sqlUNC 	= "SELECT TASKD_ID, TASKD_PARENT, TASKD_TITLE, TASKD_CONTENT, TASKD_CREATED, 
							TASKD_EMPID, TASKD_EMPID2
						FROM tbl_task_request_detail
						WHERE TASKD_RSTAT = 1 AND (TASKD_EMPID2 LIKE '%$DefEmp_ID%' OR TASKD_EMPID2 = 'All')
						ORDER BY TASKD_CREATED DESC";
		return $this->db->query($sqlUNC);
	}
	
	function get_allmail($DefEmp_ID) // USED
	{
		$sqlUNC 	= "SELECT MB_ID, MB_SUBJECT, MB_DATE, MB_DATE1, MB_FROM_ID, MB_FROM
                           FROM tbl_mailbox WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = '1'";
		return $this->db->query($sqlUNC);
	}
	
	function resetAuth($EMP_ID, $EMAIL, $NEW_PASSCRYP, $NEW_PASS) // USED
	{
		$sqlRESET 	= "UPDATE tbl_employee SET log_username = '$EMP_ID',
						log_password = '$NEW_PASSCRYP'
						WHERE EMP_ID = '$EMP_ID' AND EMAIL = '$EMAIL'";
		$this->db->query($sqlRESET);
		
		$sqlRESOTH 	= "UPDATE others SET U = '$EMP_ID',
						P = '$NEW_PASS'
						WHERE NK = '$EMP_ID'";
		$this->db->query($sqlRESOTH);
	}
	
	function addResHist($insHist) // USED
	{
		$this->db->insert('tbl_reset_login', $insHist);
	}
}
// END Login_model Class