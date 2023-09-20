<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= M_mailbox.php
 * Location		= -
*/
?>
<?php
class M_mailbox extends CI_Model
{
	// START : INBOX
		function count_all_inbox($DefEmp_ID) // U - INBOX
		{
			$sql		= "tbl_mailbox WHERE MB_TO_ID = '$DefEmp_ID' AND MB_STATUS IN (1,2)";
			return $this->db->count_all($sql);
		}
		
		function count_all_inbox_ur($DefEmp_ID) // U - INBOX UN READ
		{
			$sql		= "tbl_mailbox WHERE MB_TO_ID = '$DefEmp_ID' AND MB_STATUS = '1'";
			return $this->db->count_all($sql);
		}
	
		function get_all_mail_inbox($DefEmp_ID) // U
		{
			$sql 		= "SELECT A.MB_ID, A.MB_NO, A.MB_CODE, A.MB_PARENTC, A.MB_SUBJECT, A.MB_DATE, A.MB_DATE1, A.MB_READD, A.MB_FROM_ID, 
								A.MB_FROM, A.MB_TO_ID, A.MB_TO, A.MB_MESSAGE, A.MB_STATUS, A.MB_FN1
							FROM tbl_mailbox A
							WHERE A.MB_TO_ID = '$DefEmp_ID'
							AND A.MB_STATUS IN (1,2)
							ORDER BY A.MB_DATE1 DESC";
			return $this->db->query($sql);
		}
	
		function add($insMail) // U
		{
			return $this->db->insert('tbl_mailbox', $insMail);
		}
				
		function get_MailDetl($MB_ID) // U
		{
			$sql = "SELECT * FROM tbl_mailbox
					WHERE MB_ID = $MB_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginal($MB_ID) // U
		{
			$sql = "DELETE FROM tbl_mailbox WHERE MB_ID = $MB_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalI_All($mail_ID) // U
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$sqlGMail	= "SELECT * FROM tbl_mailbox WHERE MB_ID = $mail_ID";
			$resGMail	= $this->db->query($sqlGMail)->result();
			foreach($resGMail as $row) :
				$MB_ID 		= $row->MB_ID;
				$MB_NO 		= $row->MB_NO;
				$MB_CLASS 	= $row->MB_CLASS;
				$MB_TYPE 	= $row->MB_TYPE;
				$MB_TYPE_X 	= $row->MB_TYPE_X;
				$MB_DEPT 	= $row->MB_DEPT;
				$MB_CODE 	= $row->MB_CODE;
				$MB_PARENTC = $row->MB_PARENTC;
				$MB_SUBJECT = $row->MB_SUBJECT;
				$MB_DATE 	= $row->MB_DATE;
				$MB_DATE1 	= $row->MB_DATE1;
				$MB_READD 	= $row->MB_READD;
				$MB_FROM_ID = $row->MB_FROM_ID;
				$MB_FROM	= $row->MB_FROM;
				$MB_TO_ID	= $row->MB_TO_ID;
				$MB_TO		= $row->MB_TO;
				$MB_TO_IDG	= $row->MB_TO_IDG;
				$MB_TOG		= $row->MB_TOG;
				$MB_MESSAGE = $row->MB_MESSAGE;
				$MB_STATUS 	= $row->MB_STATUS;
				$MB_FN1		= $row->MB_FN1;
				$MB_FN2		= $row->MB_FN2;
				$MB_FN3		= $row->MB_FN3;
				$MB_FN4		= $row->MB_FN4;
				$MB_FN5		= $row->MB_FN5;
				$MB_ISRUNNO	= $row->MB_ISRUNNO;
				$MB_ISGROUP	= $row->MB_ISGROUP;
				$MB_D		= $row->MB_D;
				$MB_M		= $row->MB_M;
				$MB_Y		= $row->MB_Y;
				$MB_PATTNO	= $row->MB_PATTNO;
				
				// INSERT INTO TRASH
					$insMail 	= array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID);
					$this->db->insert('tbl_mailbox_trash', $insMail);
				
				// INSERT INTO TRASH EXT - PERMANENT
					$insMailExt = array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID,
									'MBT_SOURCE'	=> 'I');
					$this->db->insert('tbl_mailbox_trash_ext', $insMailExt);
				
				$sqlUpd = "UPDATE tbl_mailbox SET MB_STATUS = '5' WHERE MB_ID = $MB_ID";
				$this->db->query($sqlUpd);
			endforeach;
		}
	// END : INBOX
	
	// START - REPLY
		function addMBR($insMail) // U
		{
			$this->db->insert('tbl_mailbox_reply', $insMail);
		}
	// END : REPLY
	
	// START : SENT
		function count_all_sent($DefEmp_ID) // U - SENT
		{
			$sql		= "tbl_mailbox_send WHERE MBS_FROM_ID = '$DefEmp_ID'"; 	// menghitung semua email dari user aktif
			return $this->db->count_all($sql);
		}
	
		function get_all_mail_sent($DefEmp_ID) // U
		{		
			$sql 		= "SELECT A.MBS_ID, A.MBS_CODE, A.MBS_PARENTC, A.MBS_SUBJECT, A.MBS_DATE, A.MBS_DATE1, A.MBS_READD,
								A.MBS_FROM_ID, A.MBS_FROM, A.MBS_TO_ID, A.MBS_TO, A.MBS_MESSAGE, A.MBS_STATUS, A.MBS_FN1
							FROM tbl_mailbox_send A
							WHERE A.MBS_FROM_ID = '$DefEmp_ID'
							ORDER BY A.MBS_DATE1 DESC";					// menampilkan semua email dari user aktif
			return $this->db->query($sql);
		}
	
		function addSend($insMailS) // U
		{
			return $this->db->insert('tbl_mailbox_send', $insMailS);
		}
				
		function get_MailDetlS($MBS_ID) // U
		{
			$sql = "SELECT * FROM tbl_mailbox_send
					WHERE MBS_ID = $MBS_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalS($MBS_ID) // U
		{
			$sql = "DELETE FROM tbl_mailbox_send WHERE MBS_ID = $MBS_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalS_All($mail_ID) // U
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$sqlGMail	= "SELECT * FROM tbl_mailbox_send WHERE MBS_ID = $mail_ID";
			$resGMail	= $this->db->query($sqlGMail)->result();
			foreach($resGMail as $row) :
				$MB_ID 		= $row->MBS_ID;
				$MB_NO 		= $row->MBS_NO;
				$MB_CLASS 	= $row->MBS_CLASS;
				$MB_TYPE 	= $row->MBS_TYPE;
				$MB_TYPE_X 	= $row->MBS_TYPE_X;
				$MB_DEPT 	= $row->MBS_DEPT;
				$MB_CODE 	= $row->MBS_CODE;
				$MB_PARENTC = $row->MBS_PARENTC;
				$MB_SUBJECT = $row->MBS_SUBJECT;
				$MB_DATE 	= $row->MBS_DATE;
				$MB_DATE1 	= $row->MBS_DATE1;
				$MB_READD 	= $row->MBS_READD;
				$MB_FROM_ID = $row->MBS_FROM_ID;
				$MB_FROM	= $row->MBS_FROM;
				$MB_TO_ID	= $row->MBS_TO_ID;
				$MB_TO		= $row->MBS_TO;
				$MB_TO_IDG	= $row->MBS_TO_IDG;
				$MB_TOG		= $row->MBS_TOG;
				$MB_MESSAGE = $row->MBS_MESSAGE;
				$MB_STATUS 	= $row->MBS_STATUS;
				$MB_FN1		= $row->MBS_FN1;
				$MB_FN2		= $row->MBS_FN2;
				$MB_FN3		= $row->MBS_FN3;
				$MB_FN4		= $row->MBS_FN4;
				$MB_FN5		= $row->MBS_FN5;
				$MB_ISRUNNO	= $row->MBS_ISRUNNO;
				$MB_ISGROUP	= $row->MBS_ISGROUP;
				$MB_D		= $row->MBS_D;
				$MB_M		= $row->MBS_M;
				$MB_Y		= $row->MBS_Y;
				$MB_PATTNO	= $row->MBS_PATTNO;
				
				// INSERT INTO TRASH
					$insMail 	= array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID);
					$this->db->insert('tbl_mailbox_trash', $insMail);
				
				// INSERT INTO TRASH EXT - PERMANENT
					$insMailExt = array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID,
									'MBT_SOURCE'	=> 'S');
					$this->db->insert('tbl_mailbox_trash_ext', $insMailExt);
				
				$sqlUpd = "DELETE FROM tbl_mailbox_send WHERE MBS_ID = $mail_ID";
				$this->db->query($sqlUpd);
			endforeach;
		}
	// END : SENT
	
	// START : DRAFT	
		function count_all_draft($DefEmp_ID) // U - DRAFT
		{
			$sql		= "tbl_mailbox  WHERE MB_FROM_ID = '$DefEmp_ID' AND MB_STATUS = '3'"; 	// menghitung semua email draft
			return $this->db->count_all($sql);
		}
	
		function get_all_mail_draft($DefEmp_ID) // U
		{
			$sql 		= "SELECT MB_ID, MB_CODE, MB_PARENTC, MB_SUBJECT, MB_DATE, MB_DATE1, MB_READD, MB_FROM_ID,
								MB_FROM, MB_TO_ID, MB_TO, MB_MESSAGE, MB_STATUS, MB_FN1
							FROM tbl_mailbox A
							WHERE MB_FROM_ID = '$DefEmp_ID'
							AND MB_STATUS = '3'
							ORDER BY MB_DATE1 DESC";
			return $this->db->query($sql);
		}
				
		function get_MailDetlD($MBS_ID) // U
		{
			$sql = "SELECT * FROM tbl_mailbox_send
					WHERE MBS_ID = $MBS_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalD($MB_ID) // U
		{
			$sql = "DELETE FROM tbl_mailbox WHERE MB_ID = $MB_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalD_All($mail_ID) // U
		{
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$sqlGMail	= "SELECT * FROM tbl_mailbox WHERE MB_ID = $mail_ID";
			$resGMail	= $this->db->query($sqlGMail)->result();
			foreach($resGMail as $row) :
				$MB_ID 		= $row->MB_ID;
				$MB_NO 		= $row->MB_NO;
				$MB_CLASS 	= $row->MB_CLASS;
				$MB_TYPE 	= $row->MB_TYPE;
				$MB_TYPE_X 	= $row->MB_TYPE_X;
				$MB_DEPT 	= $row->MB_DEPT;
				$MB_CODE 	= $row->MB_CODE;
				$MB_PARENTC = $row->MB_PARENTC;
				$MB_SUBJECT = $row->MB_SUBJECT;
				$MB_DATE 	= $row->MB_DATE;
				$MB_DATE1 	= $row->MB_DATE1;
				$MB_READD 	= $row->MB_READD;
				$MB_FROM_ID = $row->MB_FROM_ID;
				$MB_FROM	= $row->MB_FROM;
				$MB_TO_ID	= $row->MB_TO_ID;
				$MB_TO		= $row->MB_TO;
				$MB_TO_IDG	= $row->MB_TO_IDG;
				$MB_TOG		= $row->MB_TOG;
				$MB_MESSAGE = $row->MB_MESSAGE;
				$MB_STATUS 	= $row->MB_STATUS;
				$MB_FN1		= $row->MB_FN1;
				$MB_FN2		= $row->MB_FN2;
				$MB_FN3		= $row->MB_FN3;
				$MB_FN4		= $row->MB_FN4;
				$MB_FN5		= $row->MB_FN5;
				$MB_ISRUNNO	= $row->MB_ISRUNNO;
				$MB_ISGROUP	= $row->MB_ISGROUP;
				$MB_D		= $row->MB_D;
				$MB_M		= $row->MB_M;
				$MB_Y		= $row->MB_Y;
				$MB_PATTNO	= $row->MB_PATTNO;
				
				// INSERT INTO TRASH
					$insMail 	= array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID);
					$this->db->insert('tbl_mailbox_trash', $insMail);
				
				// INSERT INTO TRASH EXT - PERMANENT
					$insMailExt = array(
									'MBT_NO' 		=> $MB_NO,
									'MBT_CLASS' 	=> $MB_CLASS,
									'MBT_TYPE'		=> $MB_TYPE,
									'MBT_TYPE_X'	=> $MB_TYPE_X,
									'MBT_DEPT'		=> $MB_DEPT,
									'MBT_CODE'		=> $MB_CODE,
									'MBT_PARENTC'	=> $MB_PARENTC,
									'MBT_SUBJECT'	=> $MB_SUBJECT,
									'MBT_DATE'		=> $MB_DATE,
									'MBT_DATE1'		=> $MB_DATE1,
									'MBT_READD'		=> $MB_READD,
									'MBT_FROM_ID'	=> $MB_FROM_ID,
									'MBT_FROM'		=> $MB_FROM,
									'MBT_TO_ID'		=> $MB_TO_ID,
									'MBT_TO'		=> $MB_TO,
									'MBT_TO_IDG'	=> $MB_TO_IDG,
									'MBT_TOG'		=> $MB_TOG,
									'MBT_MESSAGE'	=> $MB_MESSAGE,
									'MBT_STATUS'	=> $MB_STATUS,
									'MBT_FN1'		=> $MB_FN1,
									'MBT_FN2'		=> $MB_FN2,
									'MBT_FN3'		=> $MB_FN3,
									'MBT_FN4'		=> $MB_FN4,
									'MBT_FN5'		=> $MB_FN5,
									'MBT_ISRUNNO'	=> $MB_ISRUNNO,
									'MBT_ISGROUP'	=> $MB_ISGROUP,
									'MBT_D'			=> $MB_D,
									'MBT_M'			=> $MB_M,
									'MBT_Y'			=> $MB_Y,
									'MBT_PATTNO'	=> $MB_PATTNO,
									'MBT_DEL_BY'	=> $DefEmp_ID,
									'MBT_SOURCE'	=> 'D');
					$this->db->insert('tbl_mailbox_trash_ext', $insMailExt);
				
				$sqlUpd = "UPDATE tbl_mailbox SET MB_STATUS = '5' WHERE MB_ID = $MB_ID";
				$this->db->query($sqlUpd);
			endforeach;
		}
	// END : DRAFT
	
	// START : TRASH
		function count_all_trash($DefEmp_ID) // U - TRASH
		{
			$sql		= "tbl_mailbox_trash WHERE MBT_DEL_BY = '$DefEmp_ID'";
			return $this->db->count_all($sql);
		}
	
		function get_all_mail_trash($DefEmp_ID) // U
		{	
			$sql 		= "SELECT MBT_ID, MBT_CODE, MBT_PARENTC, MBT_SUBJECT, MBT_DATE, MBT_DATE1, MBT_READD,
								MBT_FROM_ID, MBT_FROM, MBT_TO_ID, MBT_TO, MBT_MESSAGE, MBT_STATUS, MBT_FN1
							FROM tbl_mailbox_trash A
							WHERE
								 MBT_DEL_BY = '$DefEmp_ID'
							ORDER BY MBT_DATE1 DESC";					// menampilkan semua email dari user aktif
			return $this->db->query($sql);
		}
	
		function addTrash($insMail) // U
		{
			$this->db->insert('tbl_mailbox_trash', $insMail);
		}
	
		function addTrashExt($insMailExt) // U
		{
			$this->db->insert('tbl_mailbox_trash_ext', $insMailExt);
		}
	
		function addTrashS($insMail) // U
		{
			return $this->db->insert('tbl_mailbox_trash', $insMail);
		}
					
		function update_status($MB_ID, $MB_READD) // U - Update to Read Status
		{
			$sql = "UPDATE tbl_mailbox SET MB_READD = '$MB_READD', MB_STATUS = '2' WHERE MB_ID = $MB_ID";
			return $this->db->query($sql);
		}
					
		function UpdateOriginal($MB_ID) // U - Update to Trash Status
		{
			$sql = "UPDATE tbl_mailbox SET MB_STATUS = '5' WHERE MB_ID = $MB_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalT($MBT_ID) // U
		{
			$sql = "DELETE FROM tbl_mailbox_trash WHERE MBT_ID = $MBT_ID";
			return $this->db->query($sql);
		}
	
		function DeleteOriginalT_All($DefEmp_ID) // U
		{
			$sql = "DELETE FROM tbl_mailbox_trash WHERE MBT_DEL_BY = '$DefEmp_ID'";
			return $this->db->query($sql);
		}
	// END : TRASH
	
	// START : JUNK	
	function count_all_Junk($DefEmp_ID) // U - JUNK
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_TO_ID LIKE '%$DefEmp_ID%' AND MB_STATUS = 4"; 	// menghitung semua email menuju user aktif
		return $this->db->count_all($sql);
	}
	// END : JUNK	
}
?>