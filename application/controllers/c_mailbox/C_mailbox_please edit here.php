<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= C_mailbox.php
 * Location		= -
*/

class C_mailbox  extends CI_Controller
{
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	function index() // U
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_mailbox/c_mailbox/inbox_mail/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function inbox_mail($offset=0) // U - index
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
			
			$this->load->view('v_mailbox/maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function sent_mail($offset=0) // U - index
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
			
			$this->load->view('v_mailbox/send_maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function write_mail($offset=0) // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
					
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['h2_title']		= 'Mailbox';
			$data['form_action']	= site_url('c_mailbox/c_mailbox/write_mail_process');
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
			
			$this->load->view('v_mailbox/write_mail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function write_mail_process() // U
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_CLASS	= $this->input->post('MB_CLASS');				// 
			$MB_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MB_DEPT 	= $this->input->post('MB_DEPT');				// 
			
			// MENGHITUNG ULANG RUNNING NO.
			//	------ START : PATTERN NUMBER ------
				$MB_M		= date('m');
				$MB_M1		= (int)$MB_M;
				$MB_Y		= date('Y');
				$sqlMBC		= "tbl_mailbox WHERE MB_M = '$MB_M1' AND MB_Y = '$MB_Y'";
				$resMBC		= $this->db->count_all($sqlMBC);
				
				$resMBCN	= $resMBC + 1;
				$len 		= strlen($resMBCN);
				$nol		= '';	
				$PattLength	= 4;
				if($PattLength==2)
				{
					if($len==1) $nol="0";
				}
				elseif($PattLength==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($PattLength==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($PattLength==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($PattLength==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				elseif($PattLength==7)
				{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
				}
				$MAIL_STEP	= $nol.$resMBCN;
			//	------ END : PATTERN NUMBER ------
						
			// J203-S0001/NKE/07-17
			$NO_01		= "J";				// HOLD
			$NO_02		= $MB_DEPT;			// DEPARTMENT 	- J203
			$NO_03		= $MB_CLASS;		// M or S		- S
			$NO_04		= $MAIL_STEP;		// Pattern		- 0001
			$NO_05		= "NKE";			// NKE			- NKE
			$NO_06		= date('m');		// Month		- 07
			$NO_07		= date('y');		// Year			- 17
			
			$MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
			$MB_CODE	= $MAIL_NO;										// 
			$MB_PARENTC	= "";											// 
			$MB_SUBJECT	= $this->input->post('MB_SUBJECT');				// 
			$MB_DATE	= date('Y-m-d');								// 
			$MB_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM
				$Email		= '';
				$Emp_ID		= $this->input->post('Emp_ID');
				$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				$sqlEmp		= "SELECT Email FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
				$sqlEmp		= $this->db->query($sqlEmp)->result();
				foreach($sqlEmp as $row) :
					$Email		= $row->Email;
				endforeach;
				if($Email == '')
					$Email	= $Emp_ID;
			
			$MB_FROM	= $Email;										// 
			$MB_TO 		= $this->input->post('MB_TO');					// 
			$MB_TOC		= count($MB_TO);
			$selStep	= 0;
			foreach ($MB_TO as $sel_mail)
			{
				$selStep	= $selStep + 1;
				if($selStep == 1)
				{
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					$coll_MID	= $mail_ID;
					$coll_MADD	= $mail_ADD;
				}
				else
				{					
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					
					$coll_MID	= "$coll_MID;$mail_ID";
					$coll_MADD	= "$coll_MADD;$mail_ADD";
				}
			}
			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 
			// GETD ETAIL GROUP
			if($MB_TOG != '')
			{
				foreach ($MB_TOG as $MG_CODE)
				{
					$sql_MGD	= "SELECT Emp_ID, Email
									FROM tbl_mailgroup_detail WHERE MG_CODE = '$MG_CODE'";
					$res_MGD	= $this->db->query($sql_MGD)->result();
					foreach($res_MGD as $rowMGD) :
						$Emp_IDM	= $rowMGD->Emp_ID;
						$EmailM		= $rowMGD->Email;
						$coll_MID	= "$coll_MID;$Emp_IDM";
						$coll_MADD	= "$coll_MADD;$EmailM";
					endforeach;
				}
			}
			
			$MB_MESSAGE	= $this->input->post('MB_MESSAGE');
			$MB_STATUS	= $this->input->post('MB_STATUS');				// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
			$MB_D		= date('d');
			$MB_M		= date('m');
			$MB_Y		= date('Y');
			$MB_PATTNO	= $resMBCN;
						
			$file 		= $_FILES['attachment'];
			$file_name 	= $file['name'];
			
			$MB_FN1		= $file_name;
			
			// --------------------- PERHATIKAN BAIK-BAIK, KALAU TIDAK, BAKAL PUSING ---------------------
			// If File 1 is Attachment
			if($file_name != '')
			{
				$isAttachF1 = "Y";
				$filename 	= $_FILES["attachment"]["name"];
				$source 	= $_FILES["attachment"]["tmp_name"];
				$type 		= $_FILES["attachment"]["type"];
				
				$name 		= explode(".", $filename);
				$fileExt	= $name[1];
				
				$target_path = "mail.attachment/".$filename;  // change this to the correct site path
					
				$myPath 	= "mail.attachment/$filename";
				
				if (file_exists($myPath) == true)
				{
					unlink($myPath);
				}
				
				if(move_uploaded_file($source, $target_path))
				{
					$message 			= "File 1 saved.";
					$data['message'] 	= $message;
				}
				else
				{
					$message = "File 1 is error.";
					$data['message'] 	= $message;
					return false;
				}				
			}
			else
			{
				$isAttachF1 = "N";
			}
				
			if($isAttachF1 == "Y")
			{
				$message 			= "Mail sent.";
				$data['message'] 	= $message;
				
				$insMail = array('MB_CLASS' => $MB_CLASS,
								'MB_TYPE'	=> $MB_TYPE,
								'MB_DEPT'	=> $MB_DEPT,
								'MB_CODE'	=> $MB_CODE,
								'MB_PARENTC'=> $MB_PARENTC,
								'MB_SUBJECT'=> $MB_SUBJECT,
								'MB_DATE'	=> $MB_DATE,
								'MB_DATE1'	=> $MB_DATE1,
								'MB_FROM_ID'=> $DefEmp_ID,
								'MB_FROM'	=> $MB_FROM,
								'MB_TO_ID'	=> $coll_MID,
								'MB_TO'		=> $coll_MADD,
								'MB_MESSAGE'=> $MB_MESSAGE,
								'MB_STATUS'	=> $MB_STATUS,
								'MB_FN1'	=> $filename,
								'MB_ISRUNNO'=> 'Y',
								'MB_D'		=> $MB_D,
								'MB_M'		=> $MB_M,
								'MB_Y'		=> $MB_Y,
								'MB_PATTNO'	=> $MB_PATTNO);
				$this->m_mailbox->add($insMail);
				
				// Write to Send Table if New / Not Draft
				if($MB_STATUS == 1)
				{
					$insMailS = array('MBS_CLASS' 	=> $MB_CLASS,
									'MBS_TYPE'		=> $MB_TYPE,
									'MBS_DEPT'		=> $MB_DEPT,
									'MBS_CODE'		=> $MB_CODE,
									'MBS_PARENTC'	=> $MB_PARENTC,
									'MBS_SUBJECT'	=> $MB_SUBJECT,
									'MBS_DATE'		=> $MB_DATE,
									'MBS_DATE1'		=> $MB_DATE1,
									'MBS_FROM_ID'	=> $DefEmp_ID,
									'MBS_FROM'		=> $MB_FROM,
									'MBS_TO_ID'		=> $coll_MID,
									'MBS_TO'		=> $coll_MADD,
									'MBS_MESSAGE'	=> $MB_MESSAGE,
									'MBS_STATUS'	=> $MB_STATUS,
									'MBS_FN1'		=> $filename,
									'MB_ISRUNNO'	=> 'N',
									'MBS_D'			=> $MB_D,
									'MBS_M'			=> $MB_M,
									'MBS_Y'			=> $MB_Y,
									'MBS_PATTNO'	=> $MB_PATTNO);		
					$this->m_mailbox->addSend($insMailS);
				}
			}
			else
			{
				$message 			= "Mail sent.";
				$data['message'] 	= $message;
				
				$insMail = array('MB_CLASS' => $MB_CLASS,
									'MB_TYPE'	=> $MB_TYPE,
									'MB_DEPT'	=> $MB_DEPT,
									'MB_CODE'	=> $MB_CODE,
									'MB_PARENTC'=> $MB_PARENTC,
									'MB_SUBJECT'=> $MB_SUBJECT,
									'MB_DATE'	=> $MB_DATE,
									'MB_DATE1'	=> $MB_DATE1,
									'MB_FROM_ID'=> $DefEmp_ID,
									'MB_FROM'	=> $MB_FROM,
									'MB_TO_ID'	=> $coll_MID,
									'MB_TO'		=> $coll_MADD,
									'MB_MESSAGE'=> $MB_MESSAGE,
									'MB_STATUS'	=> $MB_STATUS,
									//'MB_FN1'	=> $filename,
									'MB_D'		=> $MB_D,
									'MB_M'		=> $MB_M,
									'MB_Y'		=> $MB_Y,
									'MB_PATTNO'	=> $MB_PATTNO);
				$this->m_mailbox->add($insMail);
					
				// Write to Send Table
				if($MB_STATUS == 1)
				{
					$insMailS = array('MBS_CLASS' 	=> $MB_CLASS,
									'MBS_TYPE'		=> $MB_TYPE,
									'MBS_DEPT'		=> $MB_DEPT,
									'MBS_CODE'		=> $MB_CODE,
									'MBS_PARENTC'	=> $MB_PARENTC,
									'MBS_SUBJECT'	=> $MB_SUBJECT,
									'MBS_DATE'		=> $MB_DATE,
									'MBS_DATE1'		=> $MB_DATE1,
									'MBS_FROM_ID'	=> $DefEmp_ID,
									'MBS_FROM'		=> $MB_FROM,
									'MBS_TO_ID'		=> $coll_MID,
									'MBS_TO'		=> $coll_MADD,
									'MBS_MESSAGE'	=> $MB_MESSAGE,
									'MBS_STATUS'	=> $MB_STATUS,
									//'MBS_FN1'		=> $filename,
									'MBS_D'			=> $MB_D,
									'MBS_M'			=> $MB_M,
									'MBS_Y'			=> $MB_Y,
									'MBS_PATTNO'	=> $MB_PATTNO);	
					$this->m_mailbox->addSend($insMailS);
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			if($MB_STATUS == 1)
			{
				$url	= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
			}
			else
			{
				$url	= site_url('c_mailbox/c_mailbox/draft_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
			}
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function read_mail() // U
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MB_ID		= $_GET['id'];
			$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['h2_title']		= 'Mailbox';
			$data['action_reply']	= site_url('c_mailbox/c_mailbox/reply_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_forward']	= site_url('c_mailbox/c_mailbox/forward_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_trash']	= site_url('c_mailbox/c_mailbox/trash_mail_process/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			
			$this->m_mailbox->update_status($MB_ID);
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
			
			$getMailDetail			= $this->m_mailbox->get_MailDetl($MB_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MB_ID;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MB_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MB_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MB_SUBJECT;
			$data['default']['MB_DATE'] 	= $getMailDetail->MB_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MB_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MB_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MB_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MB_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MB_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MB_TO;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/read_maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function reply_mail($offset=0) // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MB_CODE	= $_GET['id'];
			$MB_CODE	= $this->url_encryption_helper->decode_url($MB_CODE);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
					
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['h2_title']		= 'Mailbox';
			$data['form_action']	= site_url('c_mailbox/c_mailbox/reply_mail_process');
			
			$this->m_mailbox->update_status($MB_CODE);
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
			
			$getMailDetail			= $this->m_mailbox->get_MailDetl($MB_CODE)->row();
			
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MB_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MB_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MB_SUBJECT;
			$data['default']['MB_DATE'] 	= $getMailDetail->MB_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MB_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MB_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MB_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MB_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MB_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MB_TO;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;			
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/reply_mail', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function reply_mail_process() // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MBR_CODE 	= $this->input->post('MBR_CODE');				// 
			$MBR_CLASS	= $this->input->post('MBR_CLASS');				// 
			$MBR_TYPE 	= $this->input->post('MBR_TYPE');				// 
			$MBR_DEPT 	= $this->input->post('MBR_DEPT');				//
			$MBR_SUBJECT= $this->input->post('MBR_SUBJECT');			// 
			$MBR_DATE	= date('Y-m-d');								// 
			$MBR_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM
				$Email		= '';
				$Emp_ID		= $this->input->post('Emp_ID');
				$sqlEmp		= "SELECT Email FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
				$sqlEmp		= $this->db->query($sqlEmp)->result();
				foreach($sqlEmp as $row) :
					$Email		= $row->Email;
				endforeach;
				if($Email == '')
					$Email	= $Emp_ID;
			
			$MBR_FROM	= $Email;										// 
			$selStep	= 0;
			foreach ($_POST['MBR_TO'] as $sel_mail)
			{
				$selStep	= $selStep + 1;
				if($selStep == 1)
				{
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					$coll_MID	= $mail_ID;
					$coll_MADD	= $mail_ADD;
				}
				else
				{					
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					
					$coll_MID	= "$coll_MID;$mail_ID";
					$coll_MADD	= "$coll_MADD;$mail_ADD";
				}
			}
			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 
			// GETD ETAIL GROUP
			if($MB_TOG != '')
			{
				foreach ($MB_TOG as $MG_CODE)
				{
					$sql_MGD	= "SELECT Emp_ID, Email
									FROM tbl_mailgroup_detail WHERE MG_CODE = '$MG_CODE'";
					$res_MGD	= $this->db->query($sql_MGD)->result();
					foreach($res_MGD as $rowMGD) :
						$Emp_IDM	= $rowMGD->Emp_ID;
						$EmailM		= $rowMGD->Email;
						$coll_MID	= "$coll_MID;$Emp_IDM";
						$coll_MADD	= "$coll_MADD;$EmailM";
					endforeach;
				}
			}
			
			$MBR_MESSAGE= $this->input->post('MBR_MESSAGE');			// 
			$MBR_STATUS	= $this->input->post('MBR_STATUS');				// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
			$MBR_D		= $this->input->post('MBR_D');					// 
			$MBR_M		= $this->input->post('MBR_M');					// 
			$MBR_Y		= $this->input->post('MBR_Y');					// 
			$MBR_PATTNO	= $this->input->post('MBR_PATTNO');				// 
						
			$file 		= $_FILES['attachment'];
			$file_name 	= $file['name'];
			
			$MBR_FN1	= $file_name;
			
			if($file_name != '')
			{				
				$filename 	= $_FILES["attachment"]["name"];
				$source 	= $_FILES["attachment"]["tmp_name"];
				$type 		= $_FILES["attachment"]["type"];
				
				$name 		= explode(".", $filename);
				$fileExt	= $name[1];
				
				$target_path = "mail.attachment/".$filename;  // change this to the correct site path
					
				$myPath 	= "mail.attachment/$filename";
				
				if (file_exists($myPath) == true)
				{
					unlink($myPath);
				}
				
				if(move_uploaded_file($source, $target_path))
				{
					$message 			= "Mail sent.";
					$data['message'] 	= $message;
					
					// SAVE TO REPLAY TABLE
					$insMail = array(
									'MBR_CODE'		=> $MBR_CODE,
									'MBR_CLASS' 	=> $MBR_CLASS,
									'MBR_TYPE'		=> $MBR_TYPE,
									'MBR_DEPT'		=> $MBR_DEPT,
									'MBR_SUBJECT'	=> $MBR_SUBJECT,
									'MBR_DATE'		=> $MBR_DATE,
									'MBR_DATE1'		=> $MBR_DATE1,
									'MBR_FROM_ID'	=> $Emp_ID,
									'MBR_FROM'		=> $MBR_FROM,
									'MBR_TO_ID'		=> $coll_MID,
									'MBR_TO'		=> $coll_MADD,
									'MBR_MESSAGE'	=> $MBR_MESSAGE,
									'MBR_STATUS'	=> $MBR_STATUS,
									'MBR_FN1'		=> $filename,
									//'MB_ISRUNNO'=> 'N',				// NOT USED
									'MBR_D'			=> $MBR_D,
									'MBR_M'			=> $MBR_M,
									'MBR_Y'			=> $MBR_Y,
									'MBR_PATTNO'	=> $MBR_PATTNO);
	
					$this->m_mailbox->addMBR($insMail);
					
					// COPY TO MAILBOX
					$insMailCpy = array('MB_CLASS' => $MBR_CLASS,
									'MB_TYPE'	=> $MBR_TYPE,
									'MB_DEPT'	=> $MBR_DEPT,
									'MB_CODE'	=> $MBR_CODE,
									'MB_PARENTC'=> $MBR_CODE,
									'MB_SUBJECT'=> $MBR_SUBJECT,
									'MB_DATE'	=> $MBR_DATE,
									'MB_DATE1'	=> $MBR_DATE1,
									'MB_FROM_ID'=> $Emp_ID,
									'MB_FROM'	=> $MBR_FROM,
									'MB_TO_ID'	=> $coll_MID,
									'MB_TO'		=> $coll_MADD,
									'MB_MESSAGE'=> $MBR_MESSAGE,
									'MB_STATUS'	=> $MBR_STATUS,
									'MB_FN1'	=> $filename,
									'MB_ISRUNNO'=> 'N',				// 'N' caused REPLY MESSAGE
									'MB_D'		=> $MBR_D,
									'MB_M'		=> $MBR_M,
									'MB_Y'		=> $MBR_Y,
									'MB_PATTNO'	=> $MBR_PATTNO);
	
					$this->m_mailbox->add($insMailCpy);
					
					// SAVE TO SEND TABLE
					if($MBR_STATUS == 1)
					{
						$insMailS = array('MBS_CLASS' 	=> $MBR_CLASS,
										'MBS_TYPE'		=> $MBR_TYPE,
										'MBS_DEPT'		=> $MBR_DEPT,
										'MBS_CODE'		=> $MBR_CODE,
										'MBS_PARENTC'	=> $MBR_PARENTC,
										'MBS_SUBJECT'	=> $MBR_SUBJECT,
										'MBS_DATE'		=> $MBR_DATE,
										'MBS_DATE1'		=> $MBR_DATE1,
										'MBS_FROM_ID'	=> $DefEmp_ID,
										'MBS_FROM'		=> $MBR_FROM,
										'MBS_TO_ID'		=> $coll_MID,
										'MBS_TO'		=> $coll_MADD,
										'MBS_MESSAGE'	=> $MBR_MESSAGE,
										'MBS_STATUS'	=> $MBR_STATUS,
										'MBS_FN1'		=> $filename,
										'MBS_ISRUNNO'	=> 'N',
										'MBS_D'			=> $MBR_D,
										'MBS_M'			=> $MBR_M,
										'MBS_Y'			=> $MBR_Y,
										'MBS_PATTNO'	=> $MBR_PATTNO);
		
						$this->m_mailbox->addSend($insMailS);
					}
				} 
				else 
				{
					$message = "There was a problem with the upload. Please try again.";
					$data['message'] 	= $message;
				}
			}
			else
			{
				$message 			= "Mail sent.";
				$data['message'] 	= $message;
				
				// SAVE TO REPLAY TABLE
				$insMail = array(
								'MBR_CODE'		=> $MBR_CODE,
								'MBR_CLASS' 	=> $MBR_CLASS,
								'MBR_TYPE'		=> $MBR_TYPE,
								'MBR_DEPT'		=> $MBR_DEPT,
								'MBR_SUBJECT'	=> $MBR_SUBJECT,
								'MBR_DATE'		=> $MBR_DATE,
								'MBR_DATE1'		=> $MBR_DATE1,
								'MBR_FROM_ID'	=> $Emp_ID,
								'MBR_FROM'		=> $MBR_FROM,
								'MBR_TO_ID'		=> $coll_MID,
								'MBR_TO'		=> $coll_MADD,
								'MBR_MESSAGE'	=> $MBR_MESSAGE,
								'MBR_STATUS'	=> $MBR_STATUS,
								//'MBR_FN1'		=> $filename,
								//'MB_ISRUNNO'=> 'N',				// NOT USED
								'MBR_D'			=> $MBR_D,
								'MBR_M'			=> $MBR_M,
								'MBR_Y'			=> $MBR_Y,
								'MBR_PATTNO'	=> $MBR_PATTNO);

				$this->m_mailbox->addMBR($insMail);
				
				// COPY TO MAILBOX
				$insMailCpy = array('MB_CLASS' => $MBR_CLASS,
								'MB_TYPE'	=> $MBR_TYPE,
								'MB_DEPT'	=> $MBR_DEPT,
								'MB_CODE'	=> $MBR_CODE,
								'MB_PARENTC'=> $MBR_CODE,
								'MB_SUBJECT'=> $MBR_SUBJECT,
								'MB_DATE'	=> $MBR_DATE,
								'MB_DATE1'	=> $MBR_DATE1,
								'MB_FROM_ID'=> $Emp_ID,
								'MB_FROM'	=> $MBR_FROM,
								'MB_TO_ID'	=> $coll_MID,
								'MB_TO'		=> $coll_MADD,
								'MB_MESSAGE'=> $MBR_MESSAGE,
								'MB_STATUS'	=> $MBR_STATUS,
								//'MB_FN1'	=> $filename,
								'MB_ISRUNNO'=> 'N',				// 'N' caused REPLY MESSAGE
								'MB_D'		=> $MBR_D,
								'MB_M'		=> $MBR_M,
								'MB_Y'		=> $MBR_Y,
								'MB_PATTNO'	=> $MBR_PATTNO);

				$this->m_mailbox->add($insMailCpy);
					
				// SAVE TO SEND TABLE
				if($MBR_STATUS == 1)
				{
					$insMailS = array('MBS_CLASS' 	=> $MBR_CLASS,
									'MBS_TYPE'		=> $MBR_TYPE,
									'MBS_DEPT'		=> $MBR_DEPT,
									'MBS_CODE'		=> $MBR_CODE,
									'MBS_PARENTC'	=> $MBR_PARENTC,
									'MBS_SUBJECT'	=> $MBR_SUBJECT,
									'MBS_DATE'		=> $MBR_DATE,
									'MBS_DATE1'		=> $MBR_DATE1,
									'MBS_FROM_ID'	=> $Emp_ID,
									'MBS_FROM'		=> $MBR_FROM,
									'MBS_TO_ID'		=> $coll_MID,
									'MBS_TO'		=> $coll_MADD,
									'MBS_MESSAGE'	=> $MBR_MESSAGE,
									'MBS_STATUS'	=> $MBR_STATUS,
									//'MBS_FN1'		=> $filename,
									'MBS_ISRUNNO'	=> 'N',
									'MBS_D'			=> $MBR_D,
									'MBS_M'			=> $MBR_M,
									'MBS_Y'			=> $MBR_Y,
									'MBS_PATTNO'	=> $MBR_PATTNO);
	
					$this->m_mailbox->addSend($insMailS);
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_mailbox/c_mailbox/sent_mail');
			$url			= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function draft_mail() // U - index
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_draft($DefEmp_ID)->result();
			
			$this->load->view('v_mailbox/draft_maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function draft_mail_update() // U - index
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MB_ID		= $_GET['id'];
			$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['task']			= 'edit';
			$data['h2_title']		= 'Mailbox';
			$data['form_action']	= site_url('c_mailbox/c_mailbox/draft_mail_update_process');
			$data['action_trash']	= site_url('c_mailbox/c_mailbox/trash_mail_process/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_draft($DefEmp_ID)->result();
			
			$getMailDetail			= $this->m_mailbox->get_MailDetl($MB_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MB_ID;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MB_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MB_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MB_SUBJECT;
			$data['default']['MB_DATE'] 	= $getMailDetail->MB_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MB_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MB_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MB_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MB_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MB_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MB_TO;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/update_maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function draft_mail_update_process() // U
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_ID		= $this->input->post('MB_ID');					// 
			$MB_CLASS	= $this->input->post('MB_CLASS');				// 
			$MB_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MB_DEPT 	= $this->input->post('MB_DEPT');				// 
			
			// MENGHITUNG ULANG RUNNING NO.
			//	------ START : PATTERN NUMBER ------
				$MB_M		= date('m');
				$MB_M1		= (int)$MB_M;
				$MB_Y		= date('Y');
				$sqlMBC		= "tbl_mailbox WHERE MB_M = '$MB_M1' AND MB_Y = '$MB_Y'";
				$resMBC		= $this->db->count_all($sqlMBC);
				
				$resMBCN	= $resMBC + 1;
				$len 		= strlen($resMBCN);
				$nol		= '';	
				$PattLength	= 4;
				if($PattLength==2)
				{
					if($len==1) $nol="0";
				}
				elseif($PattLength==3)
				{if($len==1) $nol="00";else if($len==2) $nol="0";
				}
				elseif($PattLength==4)
				{if($len==1) $nol="000";else if($len==2) $nol="00";else if($len==3) $nol="0";
				}
				elseif($PattLength==5)
				{if($len==1) $nol="0000";else if($len==2) $nol="000";else if($len==3) $nol="00";else if($len==4) $nol="0";
				}
				elseif($PattLength==6)
				{if($len==1) $nol="00000";else if($len==2) $nol="0000";else if($len==3) $nol="000";else if($len==4) $nol="00";else if($len==5) $nol="0";
				}
				elseif($PattLength==7)
				{if($len==1) $nol="000000";else if($len==2) $nol="00000";else if($len==3) $nol="0000";else if($len==4) $nol="000";else if($len==5) $nol="00";else if($len==6) $nol="0";
				}
				$MAIL_STEP	= $nol.$resMBCN;
			//	------ END : PATTERN NUMBER ------
						
			// J203-S0001/NKE/07-17
			$NO_01		= "J";				// HOLD
			$NO_02		= $MB_DEPT;			// DEPARTMENT 	- J203
			$NO_03		= $MB_CLASS;		// M or S		- S
			$NO_04		= $MAIL_STEP;		// Pattern		- 0001
			$NO_05		= "NKE";			// NKE			- NKE
			$NO_06		= date('m');		// Month		- 07
			$NO_07		= date('y');		// Year			- 17
			
			$MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
			$MB_CODE	= $MAIL_NO;										// 
			$MB_PARENTC	= "";											// 
			$MB_SUBJECT	= $this->input->post('MB_SUBJECT');				// 
			$MB_DATE	= date('Y-m-d');								// 
			$MB_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM
				$Email		= '';
				$Emp_ID		= $this->input->post('Emp_ID');
				$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
				$sqlEmp		= "SELECT Email FROM tbl_employee WHERE Emp_ID = '$DefEmp_ID'";
				$sqlEmp		= $this->db->query($sqlEmp)->result();
				foreach($sqlEmp as $row) :
					$Email		= $row->Email;
				endforeach;
				if($Email == '')
					$Email	= $Emp_ID;
			
			$MB_FROM	= $Email;										// 
			//$MB_TO 		= $this->input->post('MB_TO');				// 
			$selStep	= 0;
			foreach ($_POST['MB_TO'] as $sel_mail)
			{
				$selStep	= $selStep + 1;
				if($selStep == 1)
				{
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					$coll_MID	= $mail_ID;
					$coll_MADD	= $mail_ADD;
				}
				else
				{					
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					
					$coll_MID	= "$coll_MID;$mail_ID";
					$coll_MADD	= "$coll_MADD;$mail_ADD";
				}
			}
			
			$MB_MESSAGE	= $this->input->post('MB_MESSAGE');
			$MB_STATUS	= $this->input->post('MB_STATUS');				// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
			$MB_D		= date('d');
			$MB_M		= date('m');
			$MB_Y		= date('Y');
			$MB_PATTNO	= $resMBCN;
						
			$file 		= $_FILES['attachment'];
			$file_name 	= $file['name'];
			
			$MB_FN1		= $file_name;
			
			if($file_name != '')
			{				
				$filename 	= $_FILES["attachment"]["name"];
				$source 	= $_FILES["attachment"]["tmp_name"];
				$type 		= $_FILES["attachment"]["type"];
				
				$name 		= explode(".", $filename);
				$fileExt	= $name[1];
				
				$target_path = "mail.attachment/".$filename;  // change this to the correct site path
					
				$myPath 	= "mail.attachment/$filename";
				
				if (file_exists($myPath) == true)
				{
					unlink($myPath);
				}
				
				if(move_uploaded_file($source, $target_path))
				{
					$message 			= "Mail sent.";
					$data['message'] 	= $message;
					
					// Delete original data
					$this->m_mailbox->DeleteOriginal($MB_ID);
					
					// Insert like as a new data
					$insMail = array('MB_CLASS' => $MB_CLASS,
									'MB_TYPE'	=> $MB_TYPE,
									'MB_DEPT'	=> $MB_DEPT,
									'MB_CODE'	=> $MB_CODE,
									'MB_PARENTC'=> $MB_PARENTC,
									'MB_SUBJECT'=> $MB_SUBJECT,
									'MB_DATE'	=> $MB_DATE,
									'MB_DATE1'	=> $MB_DATE1,
									'MB_FROM_ID'=> $DefEmp_ID,
									'MB_FROM'	=> $MB_FROM,
									'MB_TO_ID'	=> $coll_MID,
									'MB_TO'		=> $coll_MADD,
									'MB_MESSAGE'=> $MB_MESSAGE,
									'MB_STATUS'	=> $MB_STATUS,
									'MB_FN1'	=> $filename,
									'MB_D'		=> $MB_D,
									'MB_M'		=> $MB_M,
									'MB_Y'		=> $MB_Y,
									'MB_PATTNO'	=> $MB_PATTNO);
					
					$this->m_mailbox->add($insMail);
					
					// Write to Send Table
					if($MB_STATUS == 1)
					{
						$insMailS = array('MBS_CLASS' 	=> $MB_CLASS,
										'MBS_TYPE'		=> $MB_TYPE,
										'MBS_DEPT'		=> $MB_DEPT,
										'MBS_CODE'		=> $MB_CODE,
										'MBS_PARENTC'	=> $MB_PARENTC,
										'MBS_SUBJECT'	=> $MB_SUBJECT,
										'MBS_DATE'		=> $MB_DATE,
										'MBS_DATE1'		=> $MB_DATE1,
										'MBS_FROM_ID'	=> $DefEmp_ID,
										'MBS_FROM'		=> $MB_FROM,
										'MBS_TO_ID'		=> $coll_MID,
										'MBS_TO'		=> $coll_MADD,
										'MBS_MESSAGE'	=> $MB_MESSAGE,
										'MBS_STATUS'	=> $MB_STATUS,
										'MBS_FN1'		=> $filename,
										'MBS_D'			=> $MB_D,
										'MBS_M'			=> $MB_M,
										'MBS_Y'			=> $MB_Y,
										'MBS_PATTNO'	=> $MB_PATTNO);
		
						$this->m_mailbox->addSend($insMailS);
					}
				} 
				else 
				{
					$message = "There was a problem with the upload. Please try again.";
					$data['message'] 	= $message;
				}
			}
			else
			{
				$message 			= "Mail sent.";
				$data['message'] 	= $message;
					
				// Delete original data
				$this->m_mailbox->DeleteOriginal($MB_ID);
				
				// Insert like as a new data
				$insMail = array('MB_CLASS' => $MB_CLASS,
								'MB_TYPE'	=> $MB_TYPE,
								'MB_DEPT'	=> $MB_DEPT,
								'MB_CODE'	=> $MB_CODE,
								'MB_PARENTC'=> $MB_PARENTC,
								'MB_SUBJECT'=> $MB_SUBJECT,
								'MB_DATE'	=> $MB_DATE,
								'MB_DATE1'	=> $MB_DATE1,
								'MB_FROM_ID'=> $DefEmp_ID,
								'MB_FROM'	=> $MB_FROM,
								'MB_TO_ID'	=> $coll_MID,
								'MB_TO'		=> $coll_MADD,
								'MB_MESSAGE'=> $MB_MESSAGE,
								'MB_STATUS'	=> $MB_STATUS,
								//'MB_FN1'	=> $filename,
								'MB_D'		=> $MB_D,
								'MB_M'		=> $MB_M,
								'MB_Y'		=> $MB_Y,
								'MB_PATTNO'	=> $MB_PATTNO);
				
				$this->m_mailbox->add($insMail);
					
				// Write to Send Table
				if($MB_STATUS == 1)
				{
					$insMailS = array('MBS_CLASS' 	=> $MB_CLASS,
									'MBS_TYPE'		=> $MB_TYPE,
									'MBS_DEPT'		=> $MB_DEPT,
									'MBS_CODE'		=> $MB_CODE,
									'MBS_PARENTC'	=> $MB_PARENTC,
									'MBS_SUBJECT'	=> $MB_SUBJECT,
									'MBS_DATE'		=> $MB_DATE,
									'MBS_DATE1'		=> $MB_DATE1,
									'MBS_FROM_ID'	=> $DefEmp_ID,
									'MBS_FROM'		=> $MB_FROM,
									'MBS_TO_ID'		=> $coll_MID,
									'MBS_TO'		=> $coll_MADD,
									'MBS_MESSAGE'	=> $MB_MESSAGE,
									'MBS_STATUS'	=> $MB_STATUS,
									//'MBS_FN1'		=> $filename,
									'MBS_D'			=> $MB_D,
									'MBS_M'			=> $MB_M,
									'MBS_Y'			=> $MB_Y,
									'MBS_PATTNO'	=> $MB_PATTNO);
	
					$this->m_mailbox->addSend($insMailS);
				}
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_mailbox/c_mailbox/sent_mail');
			$url			= site_url('c_mailbox/c_mailbox/sent_mail/?id='.$this->url_encryption_helper->encode_url($DefEmp_ID));
			redirect($url);
		}
		else
		{
			redirect('Auth');

		}
	}
	
	function trash_mail() // U - index
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$DefEmp_ID	= $_GET['id'];
			$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			
			$tot_Inbox 				= $this->m_mailbox->count_all_inbox($DefEmp_ID);
			$data["countInbox"] 	= $tot_Inbox;
			
			$tot_Inbox_ur			= $this->m_mailbox->count_all_inbox_ur($DefEmp_ID);	// Un Read
			$data["countInbox_ur"] 	= $tot_Inbox_ur;
			
			$tot_Sent 				= $this->m_mailbox->count_all_sent($DefEmp_ID);
			$data["countSent"] 		= $tot_Sent;
			
			$tot_Draft 				= $this->m_mailbox->count_all_draft($DefEmp_ID);
			$data["countDraft"] 	= $tot_Draft;
			
			$tot_Junk 				= $this->m_mailbox->count_all_Junk($DefEmp_ID);
			$data["countJunk"] 		= $tot_Junk;
			
			$tot_Trash 				= $this->m_mailbox->count_all_trash($DefEmp_ID);
			$data["countTrash"] 	= $tot_Trash;
	 
			$data['viewmail']		= $this->m_mailbox->get_all_mail_trash($DefEmp_ID)->result();
			
			$this->load->view('v_mailbox/trash_maillist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process() // U - delete from read
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MBT_ID		= $this->input->post('MB_ID');					// 
			$MBT_CLASS	= $this->input->post('MB_CLASS');				// 
			$MBT_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MBT_DEPT 	= $this->input->post('MB_DEPT');				//
			$MBT_CODE 	= $this->input->post('MB_CODE');				//
			$MBT_PARENTC= $this->input->post('MB_PARENTC');				// 
			$MBT_SUBJECT= $this->input->post('MB_SUBJECT');				// 	
			$MBT_DATE	= $this->input->post('MB_DATE');				// 
			$MBT_DATE1	= $this->input->post('MB_DATE1');				// 
			$MBT_READD	= $this->input->post('MB_READD');				// 
			$MBT_FROM_ID= $this->input->post('MB_FROM_ID');				// 
			$MBT_FROM	= $this->input->post('MB_FROM');				// 
			$MBT_TO_ID	= $this->input->post('MB_TO_ID');				// 
			$MBT_TO		= $this->input->post('MB_TO');					// 					
			$MBT_MESSAGE= $this->input->post('MB_MESSAGE');				// 
			$MBT_STATUS	= $this->input->post('MB_STATUS');				//
			$MBT_FN1	= $this->input->post('MB_FN1');					//
			$MBT_ISRUNNO= $this->input->post('MB_ISRUNNO');				// 
			$MBT_D		= $this->input->post('MB_D');					// 
			$MBT_M		= $this->input->post('MB_M');					// 
			$MBT_Y		= $this->input->post('MB_Y');					// 
			$MBT_PATTNO	= $this->input->post('MB_PATTNO');				// 	
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
					
			$insMail = array(
						'MBT_CLASS' 	=> $MBT_CLASS,
						'MBT_TYPE'		=> $MBT_TYPE,
						'MBT_DEPT'		=> $MBT_DEPT,
						'MBT_CODE'		=> $MBT_CODE,
						'MBT_PARENTC'	=> $MBT_PARENTC,
						'MBT_SUBJECT'	=> $MBT_SUBJECT,
						'MBT_DATE'		=> $MBT_DATE,
						'MBT_DATE1'		=> $MBT_DATE1,
						'MBT_READD'		=> $MBT_READD,
						'MBT_FROM_ID'	=> $MBT_FROM_ID,
						'MBT_FROM'		=> $MBT_FROM,
						'MBT_TO_ID'		=> $MBT_TO_ID,
						'MBT_TO'		=> $MBT_TO,
						'MBT_MESSAGE'	=> $MBT_MESSAGE,
						'MBT_STATUS'	=> $MBT_STATUS,
						'MBT_FN1'		=> $MBT_FN1,
						'MBT_ISRUNNO'	=> $MBT_ISRUNNO,
						'MBT_D'			=> $MBT_D,
						'MBT_M'			=> $MBT_M,
						'MBT_Y'			=> $MBT_Y,
						'MBT_PATTNO'	=> $MBT_PATTNO,
						'MBT_DEL_BY'	=> $DefEmp_ID);

			$this->m_mailbox->addTrash($insMail);
			// HOLD, karena untuk kepentingan RUNNING NO, jadi don't delete, update only
			//$this->m_mailbox->DeleteOriginal($MBT_ID);
			$this->m_mailbox->UpdateOriginal($MBT_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_mailbox/c_mailbox/?id='.$this->url_encryption_helper->encode_url($Emp_ID));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_I() // U - delete from inbox list
	{
		$MB_ID		= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$getMailDetail	= $this->m_mailbox->get_MailDetl($MB_ID)->row();
			
			$MB_ID 		= $getMailDetail->MB_ID;
			$MB_CLASS 	= $getMailDetail->MB_CLASS;
			$MB_TYPE 	= $getMailDetail->MB_TYPE;
			$MB_DEPT 	= $getMailDetail->MB_DEPT;
			$MB_CODE 	= $getMailDetail->MB_CODE;
			$MB_PARENTC = $getMailDetail->MB_PARENTC;
			$MB_SUBJECT = $getMailDetail->MB_SUBJECT;
			$MB_DATE 	= $getMailDetail->MB_DATE;
			$MB_DATE1 	= $getMailDetail->MB_DATE1;
			$MB_READD 	= $getMailDetail->MB_READD;
			$MB_FROM_ID = $getMailDetail->MB_FROM_ID;
			$MB_FROM	= $getMailDetail->MB_FROM;
			$MB_TO_ID	= $getMailDetail->MB_TO_ID;
			$MB_TO		= $getMailDetail->MB_TO;
			$MB_MESSAGE = $getMailDetail->MB_MESSAGE;
			$MB_STATUS 	= $getMailDetail->MB_STATUS;	
			$MB_FN1		= $getMailDetail->MB_FN1;		
			$MB_ISRUNNO	= $getMailDetail->MB_ISRUNNO;
			$MB_D		= $getMailDetail->MB_D;
			$MB_M		= $getMailDetail->MB_M;
			$MB_Y		= $getMailDetail->MB_Y;
			$MB_PATTNO	= $getMailDetail->MB_PATTNO;
					
			$insMail 	= array(
							'MBT_CLASS' 	=> $MB_CLASS,
							'MBT_TYPE'		=> $MB_TYPE,
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
							'MBT_MESSAGE'	=> $MB_MESSAGE,
							'MBT_STATUS'	=> $MB_STATUS,
							'MBT_FN1'		=> $MB_FN1,
							'MBT_ISRUNNO'	=> $MB_ISRUNNO,
							'MBT_D'			=> $MB_D,
							'MBT_M'			=> $MB_M,
							'MBT_Y'			=> $MB_Y,
							'MBT_PATTNO'	=> $MB_PATTNO,
							'MBT_DEL_BY'	=> $DefEmp_ID);
			$this->m_mailbox->addTrash($insMail);
					
			$insMailExt = array(
							'MBT_CLASS' 	=> $MB_CLASS,
							'MBT_TYPE'		=> $MB_TYPE,
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
							'MBT_MESSAGE'	=> $MB_MESSAGE,
							'MBT_STATUS'	=> $MB_STATUS,
							'MBT_FN1'		=> $MB_FN1,
							'MBT_ISRUNNO'	=> $MB_ISRUNNO,
							'MBT_D'			=> $MB_D,
							'MBT_M'			=> $MB_M,
							'MBT_Y'			=> $MB_Y,
							'MBT_PATTNO'	=> $MB_PATTNO,
							'MBT_DEL_BY'	=> $DefEmp_ID,
							'MBT_SOURCE'	=> 'I');
			$this->m_mailbox->addTrashExt($insMailExt);
			// HOLD, karena untuk kepentingan RUNNING NO, jadi don't delete, update only
			//$this->m_mailbox->DeleteOriginal($MBT_ID);
			$this->m_mailbox->UpdateOriginal($MB_ID);		// Why Update? We are not delete mail in inbox
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			//return $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_S() // U - delete from send list
	{
		$MBS_ID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$getMailDetail	= $this->m_mailbox->get_MailDetlS($MBS_ID)->row();
			
			$MBS_ID 	= $getMailDetail->MBS_ID;
			$MBS_CLASS 	= $getMailDetail->MBS_CLASS;
			$MBS_TYPE 	= $getMailDetail->MBS_TYPE;
			$MBS_DEPT 	= $getMailDetail->MBS_DEPT;
			$MBS_CODE 	= $getMailDetail->MBS_CODE;
			$MBS_PARENTC= $getMailDetail->MBS_PARENTC;
			$MBS_SUBJECT= $getMailDetail->MBS_SUBJECT;
			$MBS_DATE 	= $getMailDetail->MBS_DATE;
			$MBS_DATE1 	= $getMailDetail->MBS_DATE1;
			$MBS_READD 	= $getMailDetail->MBS_READD;
			$MBS_FROM_ID= $getMailDetail->MBS_FROM_ID;
			$MBS_FROM	= $getMailDetail->MBS_FROM;
			$MBS_TO_ID	= $getMailDetail->MBS_TO_ID;
			$MBS_TO		= $getMailDetail->MBS_TO;
			$MBS_MESSAGE= $getMailDetail->MBS_MESSAGE;
			$MBS_STATUS = $getMailDetail->MBS_STATUS;	
			$MBS_FN1	= $getMailDetail->MBS_FN1;		
			$MBS_ISRUNNO= $getMailDetail->MBS_ISRUNNO;
			$MBS_D		= $getMailDetail->MBS_D;
			$MBS_M		= $getMailDetail->MBS_M;
			$MBS_Y		= $getMailDetail->MBS_Y;
			$MBS_PATTNO	= $getMailDetail->MBS_PATTNO;
					
			$insMail = array(
						'MBT_CLASS' 	=> $MBS_CLASS,
						'MBT_TYPE'		=> $MBS_TYPE,
						'MBT_DEPT'		=> $MBS_DEPT,
						'MBT_CODE'		=> $MBS_CODE,
						'MBT_PARENTC'	=> $MBS_PARENTC,
						'MBT_SUBJECT'	=> $MBS_SUBJECT,
						'MBT_DATE'		=> $MBS_DATE,
						'MBT_DATE1'		=> $MBS_DATE1,
						'MBT_READD'		=> $MBS_READD,
						'MBT_FROM_ID'	=> $MBS_FROM_ID,
						'MBT_FROM'		=> $MBS_FROM,
						'MBT_TO_ID'		=> $MBS_TO_ID,
						'MBT_TO'		=> $MBS_TO,
						'MBT_MESSAGE'	=> $MBS_MESSAGE,
						'MBT_STATUS'	=> $MBS_STATUS,
						'MBT_FN1'		=> $MBS_FN1,
						'MBT_ISRUNNO'	=> $MBS_ISRUNNO,
						'MBT_D'			=> $MBS_D,
						'MBT_M'			=> $MBS_M,
						'MBT_Y'			=> $MBS_Y,
						'MBT_PATTNO'	=> $MBS_PATTNO,
						'MBT_DEL_BY'	=> $DefEmp_ID);		
			$this->m_mailbox->addTrashS($insMail);
			
			$this->m_mailbox->DeleteOriginalS($MBS_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_D() // U - delete from draft list
	{
		$MB_ID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalD($MB_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_draft($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_T() // U - delete from trash list
	{
		$MBT_ID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalT($MBT_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_trash($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_I_all() // U - delete all inbox
	{
		$DefEmp_ID	= $_GET['id'];
		$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalI_All($DefEmp_ID);
			$this->m_mailbox->DeleteOriginalS_All($DefEmp_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			return $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_S_all() // U - delete all sent
	{
		$DefEmp_ID	= $_GET['id'];
		$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalS_All($DefEmp_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_sent($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_D_all() // U - delete all draft
	{
		$DefEmp_ID	= $_GET['id'];
		$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalD_All($DefEmp_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_draft($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_T_all() // U - delete all trash
	{
		$DefEmp_ID	= $_GET['id'];
		$DefEmp_ID	= $this->url_encryption_helper->decode_url($DefEmp_ID);
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$this->m_mailbox->DeleteOriginalT_All($DefEmp_ID);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			return $this->m_mailbox->get_all_mail_trash($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
}