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

	function __construct() // GOOD
	{
		parent::__construct();

		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "") 
		{
			$var1	= explode("</p>",$var);
			$var	= $var1[0];
			if (strlen ($var) < $len) 
			{ 
				return $var; 
			}
			if (preg_match ("/(.{1,$len})\s/", $var, $match)) 
			{
				return $match [1] . $txt_titik;
			}
			else
			{
				return substr ($var, 0, $len) . $txt_titik;
			}
		}

		// DEFAULT PROJECT
			$sqlISHO 		= "SELECT PRJCODE FROM tbl_project WHERE isHO = 1 AND PRJSTAT = 1";
			$resISHO		= $this->db->query($sqlISHO)->result();
			foreach($resISHO as $rowISHO):
				$PRJCODE	= $rowISHO->PRJCODE;
			endforeach;
			$this->data['PRJCODE']		= $PRJCODE;
			$this->data['PRJCODE_HO']	= $PRJCODE;
		
		// GET PROJECT SELECT
	}
	
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
			$data['MenuApp'] 		= 'MN381';
			
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
			$data['MenuApp'] 		= 'MN381';
			
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
			$data['MenuApp'] 		= 'MN381';
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
	
	function write_mail_process_230912() // U
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
			
			$MB_NO		= $this->input->post('MB_NO');				// 
			$MB_CODE	= $this->input->post('MB_CODE');				// 
			$MB_CLASS	= $this->input->post('MB_CLASS');				// 
			$MB_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MB_TYPE_X1	= $this->input->post('MB_TYPE_X');				// 
			$MB_TYPE_X1	= $this->input->post('MB_TYPE_X');				// 
			
			$pecah 	= explode("\n", $MB_TYPE_X1);
			// string kosong inisialisasi
			$text 	= "";
			$vgv	= count($pecah);
			// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
			// lalu menggabungnya menjadi satu string utuh $text
			for ($i=0; $i<=count($pecah)-1; $i++)
			{
				$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
				$text .= $part;
			}
			$MB_TYPE_X	= $text;
			
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
						
			// START: J203-S0001/NKE/07-17
				/* -------------- Sudah dibuatkan di form ----------------
				$NO_01		= "J";				// HOLD
				$NO_02		= $MB_DEPT;			// DEPARTMENT 	- J203
				$NO_03		= $MB_CLASS;		// M or S		- S
				$NO_04		= $MAIL_STEP;		// Pattern		- 0001
				$NO_05		= "NKE";			// NKE			- NKE
				$NO_06		= date('m');		// Month		- 07
				$NO_07		= date('y');		// Year			- 17
				
				$MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
				$MB_CODE	= $MAIL_NO;										// 
				--------------------- End ------------------------------------- */
			// START: J203-S0001/NKE/07-17

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
			
			$MB_ISGROUP	= "P";
			// FOR GROUPING RECEIVING BY PERSONAL
				$selStep	= 0;
				foreach ($MB_TO as $sel_mail)
				{
					$selStep	= $selStep + 1;
					$mail_to	= explode ("|",$sel_mail);
					$mail_ID	= $mail_to[0];
					$mail_ADD	= $mail_to[1];
					$coll_MID 	= $mail_ID;
					$coll_MADD 	= $mail_ADD;
					if($selStep > 1)
					{
						$coll_MID	= "$coll_MID;$mail_ID";
						$coll_MADD	= "$coll_MADD;$mail_ADD";
					}
					
					if($selStep > 1)
						$MB_ISGROUP = "G";
				}
			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 
			// FOR GROUPING RECEIVING BY GROUP
				// GETD ETAIL GROUP
				if($MB_TOG != '')
				{
					$MB_ISGROUP = "G";
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
			$DOC_STATUS	= $this->input->post('DOC_STATUS');				// 1. New, 2. Confirm, 3. Approve
			$MB_D		= date('d');
			$MB_M		= date('m');
			$MB_Y		= date('Y');
			$MB_H		= date('H');
			$MB_I		= date('i');
			$MB_S		= date('s');
			$MB_NO		= "$MB_Y$MB_M$MB_D$MB_H$MB_I$MB_S";
			$MB_PATTNO	= $resMBCN;
			
			// FOR MANY ATTACHMENT
			// ATTACHMENT 1
				$file1 		= $_FILES['attachment1'];
				$file_name1	= $file1['name'];			
				$MB_FN1		= $file_name1;
				
				if($file_name1 != '')
				{				
					$filename1 	= $_FILES["attachment1"]["name"];
					$source1 	= $_FILES["attachment1"]["tmp_name"];
					$type1 		= $_FILES["attachment1"]["type"];
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path = "mail_attachment/".$filename1;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Mail Attachment 1 Sent.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload attachment 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				
			// ATTACHMENT 2
				$file2 		= $_FILES['attachment2'];
				$file_name2	= $file2['name'];			
				$MB_FN2		= $file_name2;
				
				if($file_name2 != '')
				{				
					$filename2 	= $_FILES["attachment2"]["name"];
					$source2 	= $_FILES["attachment2"]["tmp_name"];
					$type2 		= $_FILES["attachment2"]["type"];
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path = "mail_attachment/".$filename2;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Mail Attachment 2 Sent.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload attachment 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				
			// ATTACHMENT 3
				$file3 		= $_FILES['attachment3'];
				$file_name3	= $file3['name'];			
				$MB_FN3		= $file_name3;
				
				if($file_name3 != '')
				{				
					$filename3 	= $_FILES["attachment3"]["name"];
					$source3 	= $_FILES["attachment3"]["tmp_name"];
					$type3 		= $_FILES["attachment3"]["type"];
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path = "mail_attachment/".$filename3;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Mail Attachment 3 Sent.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload attachment 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				
			// ATTACHMENT 4
				$file4 		= $_FILES['attachment4'];
				$file_name4	= $file4['name'];			
				$MB_FN4		= $file_name4;
				
				if($file_name4 != '')
				{				
					$filename4 	= $_FILES["attachment4"]["name"];
					$source4 	= $_FILES["attachment4"]["tmp_name"];
					$type4 		= $_FILES["attachment4"]["type"];
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path = "mail_attachment/".$filename4;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Mail Attachment 4 Sent.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload attachment 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
			// ATTACHMENT 5
				$file5 		= $_FILES['attachment5'];
				$file_name5	= $file5['name'];			
				$MB_FN5		= $file_name5;
				
				if($file_name5 != '')
				{				
					$filename5 	= $_FILES["attachment5"]["name"];
					$source5 	= $_FILES["attachment5"]["tmp_name"];
					$type5 		= $_FILES["attachment5"]["type"];
					
					$name5 		= explode(".", $filename5);
					$fileExt5	= $name5[1];
					
					$target_path = "mail_attachment/".$filename5;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename5";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source5, $target_path))
					{
						$message5 			= "Mail Attachment 5 Sent.";
						$data['message5'] 	= $message5;
					}
					else 
					{
						$message5			= "There was a problem with the upload attachment 5. Please try again.";
						$data['message5'] 	= $message5;
					}
				}
			
			// LOOPING INSERT BY MAIL
				// PARTITIONING RECEIVING BY PERSONAL
					foreach ($MB_TO as $sel_mail)
					{
						$mail_to	= explode ("|",$sel_mail);
						$mail_ID	= $mail_to[0];
						$mail_ADD	= $mail_to[1];
						$coll_MID1	= $mail_ID;
						$coll_MADD1	= $mail_ADD;
						
						// INSERT INTO INBOX
							$insMail = array(
											'MB_NO' 	=> $MB_NO,
											'MB_CLASS' 	=> $MB_CLASS,
											'MB_TYPE'	=> $MB_TYPE,
											'MB_TYPE_X'	=> $MB_TYPE_X,
											'MB_DEPT'	=> $MB_DEPT,
											'MB_CODE'	=> $MB_CODE,
											'MB_PARENTC'=> $MB_PARENTC,
											'MB_SUBJECT'=> $MB_SUBJECT,
											'MB_DATE'	=> $MB_DATE,
											'MB_DATE1'	=> $MB_DATE1,
											'MB_FROM_ID'=> $DefEmp_ID,
											'MB_FROM'	=> $MB_FROM,
											'MB_TO_ID'	=> $coll_MID1,
											'MB_TO'		=> $coll_MADD1,
											'MB_TO_IDG'	=> $coll_MID,
											'MB_TOG'	=> $coll_MADD,
											'MB_MESSAGE'=> $MB_MESSAGE,
											'MB_STATUS'	=> $MB_STATUS,
											'DOC_STATUS'=> $DOC_STATUS,
											'MB_FN1'	=> $file_name1,
											'MB_FN2'	=> $file_name2,
											'MB_FN3'	=> $file_name3,
											'MB_FN4'	=> $file_name4,
											'MB_FN5'	=> $file_name5,
											'MB_ISRUNNO'=> 'Y',
											'MB_ISGROUP'=> $MB_ISGROUP,
											'MB_D'		=> $MB_D,
											'MB_M'		=> $MB_M,
											'MB_Y'		=> $MB_Y,
											'MB_PATTNO'	=> $MB_PATTNO);
							$this->m_mailbox->add($insMail);
					}
					
				// PARTITION RECEIVING BY GROUP
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
								
								// INSERT INTO INBOX
									$insMail = array(
													'MB_NO' 	=> $MB_NO,
													'MB_CLASS' 	=> $MB_CLASS,
													'MB_TYPE'	=> $MB_TYPE,
													'MB_TYPE_X'	=> $MB_TYPE_X,
													'MB_DEPT'	=> $MB_DEPT,
													'MB_CODE'	=> $MB_CODE,
													'MB_PARENTC'=> $MB_PARENTC,
													'MB_SUBJECT'=> $MB_SUBJECT,
													'MB_DATE'	=> $MB_DATE,
													'MB_DATE1'	=> $MB_DATE1,
													'MB_FROM_ID'=> $DefEmp_ID,
													'MB_FROM'	=> $MB_FROM,
													'MB_TO_ID'	=> $Emp_IDM,
													'MB_TO'		=> $EmailM,
													'MB_TO_IDG'	=> $coll_MID,
													'MB_TOG'	=> $coll_MADD,
													'MB_MESSAGE'=> $MB_MESSAGE,
													'MB_STATUS'	=> $MB_STATUS,
													'DOC_STATUS'=> $DOC_STATUS,
													'MB_FN1'	=> $file_name1,
													'MB_FN2'	=> $file_name2,
													'MB_FN3'	=> $file_name3,
													'MB_FN4'	=> $file_name4,
													'MB_FN5'	=> $file_name5,
													'MB_ISRUNNO'=> 'Y',
													'MB_ISGROUP'=> $MB_ISGROUP,
													'MB_D'		=> $MB_D,
													'MB_M'		=> $MB_M,
													'MB_Y'		=> $MB_Y,
													'MB_PATTNO'	=> $MB_PATTNO);
									$this->m_mailbox->add($insMail);								
							endforeach;
						}
					}
						
				// WRITE TO SEND - ONLY ONE
					if($MB_STATUS == 1)
					{
						$insMailS = array(
										'MBS_NO'		=> $MB_NO,
										'MBS_CLASS' 	=> $MB_CLASS,
										'MBS_TYPE'		=> $MB_TYPE,
										'MBS_TYPE_X'	=> $MB_TYPE_X,
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
										'MBS_TO_IDG'	=> $coll_MID,
										'MBS_TOG'		=> $coll_MADD,
										'MBS_MESSAGE'	=> $MB_MESSAGE,
										'MBS_STATUS'	=> $MB_STATUS,
										'DOC_STATUS'	=> $DOC_STATUS,
										'MBS_FN1'		=> $file_name1,
										'MBS_FN2'		=> $file_name2,
										'MBS_FN3'		=> $file_name3,
										'MBS_FN4'		=> $file_name4,
										'MBS_FN5'		=> $file_name5,
										'MBS_ISRUNNO'	=> 'N',
										'MBS_ISGROUP'	=> $MB_ISGROUP,
										'MBS_D'			=> $MB_D,
										'MBS_M'			=> $MB_M,
										'MBS_Y'			=> $MB_Y,
										'MBS_PATTNO'	=> $MB_PATTNO);
						$this->m_mailbox->addSend($insMailS);
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
	
	function write_mail_process() // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	

		$DefEmp_ID 	= $this->session->userdata['Emp_ID']; // CREATER
		$CREATED 	= date('Y-m-d H:i:s');
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_NO		= $this->input->post('MB_NO');				// 
			$MB_CODE	= $this->input->post('MB_CODE');				// 
			$MB_CLASS	= $this->input->post('MB_CLASS');				// 
			$MB_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MB_TYPE_X1	= $this->input->post('MB_TYPE_X');				// 
			$MB_TYPE_X1	= $this->input->post('MB_TYPE_X');				// 
			
			$pecah 	= explode("\n", $MB_TYPE_X1);
			// string kosong inisialisasi
			$text 	= "";
			$vgv	= count($pecah);
			// untuk setiap substring hasil pecahan, sisipkan <p> di awal dan </p> di akhir
			// lalu menggabungnya menjadi satu string utuh $text
			for ($i=0; $i<=count($pecah)-1; $i++)
			{
				$part = str_replace($pecah[$i], "".$pecah[$i]."<br>", $pecah[$i]);
				$text .= $part;
			}
			$MB_TYPE_X	= $text;
			
			$MB_DEPT 	= $this->input->post('MB_DEPT');

			$MB_PARENTC	= "";											// 
			$MB_SUBJECT	= $this->input->post('MB_SUBJECT');				// 
			$MB_DATE	= date('Y-m-d');								// 
			$MB_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM
				$Email		= '';
				$Emp_ID		= $this->input->post('Emp_ID'); // MAIL From
				$sqlEmp		= "SELECT Email FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
				$sqlEmp		= $this->db->query($sqlEmp)->result();
				foreach($sqlEmp as $row) :
					$Email		= $row->Email;
				endforeach;
			
			$MB_FROM	= $Email;										// 
			$MB_TO_ID 	= $this->input->post('MB_TO_ID');					// 			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 			
			$MB_MESSAGE	= $this->input->post('MB_MESSAGE');
			$MB_STATUS	= $this->input->post('MB_STATUS');				// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
			$DOC_STATUS	= $this->input->post('DOC_STATUS');				// 1. New, 2. Confirm, 3. Approve
			$MB_D		= date('d');
			$MB_M		= date('m');
			$MB_Y		= date('Y');
			$MB_H		= date('H');
			$MB_I		= date('i');
			$MB_S		= date('s');
			$MB_NO		= "$MB_Y$MB_M$MB_D$MB_H$MB_I$MB_S";

			if(count($MB_TO_ID) > 1) $MB_ISGROUP = 'G';
			else $MB_ISGROUP = 'P';

			$MB_TO 		= "";
			$joinQEmp 	= implode("','", $MB_TO_ID);
			$s_Email 	= "SELECT Email FROM tbl_employee WHERE Emp_ID IN ('$joinQEmp') AND Email != ''";
			$r_Email 	= $this->db->query($s_Email);
			if($r_Email->num_rows() > 0)
			{
				foreach($r_Email->result() as $rw_Email):
					$Email_Emp[] = $rw_Email->Email;
				endforeach;
				$MB_TO 	= implode("|", $Email_Emp);
			}

			$MB_TO_ID 	= implode("|", $MB_TO_ID);
			
			// FOR MANY ATTACHMENT
			// ATTACHMENT 1
				$file1 		= $_FILES['attachment1'];
				$file_name1	= $file1['name'];			
				$MB_FN1		= $file_name1;
				
				if($file_name1 != '')
				{				
					$filename1 	= $_FILES["attachment1"]["name"];
					$source1 	= $_FILES["attachment1"]["tmp_name"];
					$type1 		= $_FILES["attachment1"]["type"];
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path = "mail_attachment/".$filename1;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Mail Attachment 1 Sent.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload attachment 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				
			// ATTACHMENT 2
				$file2 		= $_FILES['attachment2'];
				$file_name2	= $file2['name'];			
				$MB_FN2		= $file_name2;
				
				if($file_name2 != '')
				{				
					$filename2 	= $_FILES["attachment2"]["name"];
					$source2 	= $_FILES["attachment2"]["tmp_name"];
					$type2 		= $_FILES["attachment2"]["type"];
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path = "mail_attachment/".$filename2;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Mail Attachment 2 Sent.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload attachment 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				
			// ATTACHMENT 3
				$file3 		= $_FILES['attachment3'];
				$file_name3	= $file3['name'];			
				$MB_FN3		= $file_name3;
				
				if($file_name3 != '')
				{				
					$filename3 	= $_FILES["attachment3"]["name"];
					$source3 	= $_FILES["attachment3"]["tmp_name"];
					$type3 		= $_FILES["attachment3"]["type"];
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path = "mail_attachment/".$filename3;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Mail Attachment 3 Sent.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload attachment 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				
			// ATTACHMENT 4
				$file4 		= $_FILES['attachment4'];
				$file_name4	= $file4['name'];			
				$MB_FN4		= $file_name4;
				
				if($file_name4 != '')
				{				
					$filename4 	= $_FILES["attachment4"]["name"];
					$source4 	= $_FILES["attachment4"]["tmp_name"];
					$type4 		= $_FILES["attachment4"]["type"];
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path = "mail_attachment/".$filename4;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Mail Attachment 4 Sent.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload attachment 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
			// ATTACHMENT 5
				$file5 		= $_FILES['attachment5'];
				$file_name5	= $file5['name'];			
				$MB_FN5		= $file_name5;
				
				if($file_name5 != '')
				{				
					$filename5 	= $_FILES["attachment5"]["name"];
					$source5 	= $_FILES["attachment5"]["tmp_name"];
					$type5 		= $_FILES["attachment5"]["type"];
					
					$name5 		= explode(".", $filename5);
					$fileExt5	= $name5[1];
					
					$target_path = "mail_attachment/".$filename5;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename5";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source5, $target_path))
					{
						$message5 			= "Mail Attachment 5 Sent.";
						$data['message5'] 	= $message5;
					}
					else 
					{
						$message5			= "There was a problem with the upload attachment 5. Please try again.";
						$data['message5'] 	= $message5;
					}
				}

				if($MB_TOG != '')
				{
					$joinQMB_TOG 	= implode("','", $MB_TOG);
					$Emp_IDG 		= [];
					$s_TOG 			= "SELECT Emp_ID FROM tbl_mailgroup_detail WHERE MG_CODE IN ('$joinQMB_TOG') AND Emp_ID NOT IN ('$joinQEmp')";
					$r_TOG			= $this->db->query($s_TOG);
					if($r_TOG->num_rows() > 0)
					{
						foreach($r_TOG->result() as $rw_TOG):
							$Emp_IDG[] = $rw_TOG->Emp_IDG;
						endforeach;

						$joinQEmp 	= implode("','", $Emp_IDG);
						$s_Email 	= "SELECT Email FROM tbl_employee WHERE Emp_ID IN ('$joinQEmp') AND Email != ''";
						$r_Email 	= $this->db->query($s_Email);
						if($r_Email->num_rows() > 0)
						{
							foreach($r_Email->result() as $rw_Email):
								$Email_Emp[] = $rw_Email->Email;
							endforeach;
							$MB_TOG 	= implode("|", $Email_Emp);
							$MB_TO_IDG 	= implode("|", $Emp_IDG);
						}
					}
				}

				// INSERT INTO DRAFT => default (MB_STATUS = 3)
					$insMail = array(
									'MB_NO' 	=> $MB_NO,
									'MB_CLASS' 	=> $MB_CLASS,
									'MB_TYPE'	=> $MB_TYPE,
									'MB_TYPE_X'	=> $MB_TYPE_X,
									'MB_DEPT'	=> $MB_DEPT,
									'MB_CODE'	=> $MB_CODE,
									'MB_PARENTC'=> $MB_PARENTC,
									'MB_SUBJECT'=> $MB_SUBJECT,
									'MB_DATE'	=> $MB_DATE,
									'MB_DATE1'	=> $MB_DATE1,
									'MB_FROM_ID'=> $Emp_ID,
									'MB_FROM'	=> $MB_FROM,
									'MB_TO_ID'	=> $MB_TO_ID,
									'MB_TO'		=> $MB_TO,
									'MB_TO_IDG'	=> $MB_TO_IDG,
									'MB_TOG'	=> $MB_TOG,
									'MB_MESSAGE'=> $MB_MESSAGE,
									'MB_STATUS'	=> $MB_STATUS, // Default 3. Draft
									'DOC_STATUS'=> $DOC_STATUS, // DOC_STATUS = 1 OR DOC_STATUS = 2
									'MB_FN1'	=> $file_name1,
									'MB_FN2'	=> $file_name2,
									'MB_FN3'	=> $file_name3,
									'MB_FN4'	=> $file_name4,
									'MB_FN5'	=> $file_name5,
									'MB_ISRUNNO'=> 'Y',
									'MB_ISGROUP'=> $MB_ISGROUP,
									'MB_CREATER'=> $DefEmp_ID,
									'MB_CREATED'=> $CREATED,
									'MB_D'		=> $MB_D,
									'MB_M'		=> $MB_M,
									'MB_Y'		=> $MB_Y);
					$this->m_mailbox->add($insMail);								
				// INSERT INTO DRAFT
			
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
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_READD	= date('Y-m-d H:i:s');
			
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['action_reply']	= site_url('c_mailbox/c_mailbox/reply_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_forward']	= site_url('c_mailbox/c_mailbox/forward_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_trash']	= site_url('c_mailbox/c_mailbox/trash_mail_process/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			
			$this->m_mailbox->update_status($MB_ID, $MB_READD);
			
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
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
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
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
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
	
	function read_mail_S() // U
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
			//$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_READD	= date('Y-m-d H:i:s');
			
			$data['title'] 			= $appName;
			$data['task']			= 'read';
			$data['h2_title']		= 'sent mail';
			$data['MenuApp'] 		= 'MN381';
			//$data['action_reply']	= site_url('c_mailbox/c_mailbox/reply_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			//$data['action_forward']	= site_url('c_mailbox/c_mailbox/forward_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_trash']	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_S/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			
			//$this->m_mailbox->update_status($MB_ID, $MB_READD);
			
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
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
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
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/read_maillist_S', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function read_mail_T() // U
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
			//$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_READD	= date('Y-m-d H:i:s');
			
			$data['title'] 			= $appName;
			$data['task']			= 'read';
			$data['h2_title']		= 'sent mail';
			$data['MenuApp'] 		= 'MN381';
			//$data['action_reply']	= site_url('c_mailbox/c_mailbox/reply_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			//$data['action_forward']	= site_url('c_mailbox/c_mailbox/forward_mail/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			$data['action_trash']	= site_url('c_mailbox/c_mailbox/trash_mail_process_idx_T/?id='.$this->url_encryption_helper->encode_url($MB_ID));
			
			//$this->m_mailbox->update_status($MB_ID, $MB_READD);
			
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
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
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
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/read_maillist_S', $data);
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
			$MB_ID		= $_GET['id'];
			$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_READD	= date('Y-m-d H:i:s');
					
			$data['title'] 			= $appName;
			$data['task']			= 'add';
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['form_action']	= site_url('c_mailbox/c_mailbox/reply_mail_process');
			
			//$this->m_mailbox->update_status($MB_ID, $MB_READD);
			
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
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
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
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
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
			
			$MBX_D		= date('d');
			$MBX_M		= date('m');
			$MBX_Y		= date('Y');
			$MBX_H		= date('H');
			$MBX_I		= date('i');
			$MBX_S		= date('s');
			$MBR_NO		= "$MBX_Y$MBX_M$MBX_D$MBX_H$MBX_I$MBX_S";
			$MBR_NOP 	= $this->input->post('MBR_NO');					// 
			$MBR_CODE 	= $this->input->post('MBR_CODE');				// 
			$MBR_CLASS	= $this->input->post('MBR_CLASS');				// 
			$MBR_TYPE 	= $this->input->post('MBR_TYPE');				// 
			$MBR_TYPE_X	= $this->input->post('MBR_TYPE_X');				// 
			$MBR_DEPT 	= $this->input->post('MBR_DEPT');				//
			$MBR_SUBJECT= $this->input->post('MBR_SUBJECT');			// 
			$MBR_DATE	= date('Y-m-d');								// 
			$MBR_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM - SENDER
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
			
			$MBR_TO 	= $this->input->post('MBR_TO');					// 
			$MBR_ISGROUP= "P";
			// FOR GROUPING RECEIVING BY PERSONAL
				$selStep	= 0;
				foreach ($MBR_TO as $sel_mail)
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
					
					if($selStep > 1)
						$MBR_ISGROUP = "G";
				}
			
			$MBR_TOG 	= $this->input->post('MBR_TOG');				// 
			// GETD ETAIL GROUP
			if($MBR_TOG != '')
			{
				foreach ($MBR_TOG as $MGR_CODE)
				{
					$sql_MGD	= "SELECT Emp_ID, Email
									FROM tbl_mailgroup_detail WHERE MG_CODE = '$MGR_CODE'";
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
			
			// FOR MANY ATTACHMENT
			// ATTACHMENT 1
				$file1 		= $_FILES['attachment1'];
				$file_name1	= $file1['name'];			
				$MB_FN1		= $file_name1;
				
				if($file_name1 != '')
				{				
					$filename1 	= $_FILES["attachment1"]["name"];
					$source1 	= $_FILES["attachment1"]["tmp_name"];
					$type1 		= $_FILES["attachment1"]["type"];
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path = "mail_attachment/".$filename1;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Mail Attachment 1 Sent.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload attachment 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				
			// ATTACHMENT 2
				$file2 		= $_FILES['attachment2'];
				$file_name2	= $file2['name'];			
				$MB_FN2		= $file_name2;
				
				if($file_name2 != '')
				{				
					$filename2 	= $_FILES["attachment2"]["name"];
					$source2 	= $_FILES["attachment2"]["tmp_name"];
					$type2 		= $_FILES["attachment2"]["type"];
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path = "mail_attachment/".$filename2;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Mail Attachment 2 Sent.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload attachment 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				
			// ATTACHMENT 3
				$file3 		= $_FILES['attachment3'];
				$file_name3	= $file3['name'];			
				$MB_FN3		= $file_name3;
				
				if($file_name3 != '')
				{				
					$filename3 	= $_FILES["attachment3"]["name"];
					$source3 	= $_FILES["attachment3"]["tmp_name"];
					$type3 		= $_FILES["attachment3"]["type"];
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path = "mail_attachment/".$filename3;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Mail Attachment 3 Sent.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload attachment 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				
			// ATTACHMENT 4
				$file4 		= $_FILES['attachment4'];
				$file_name4	= $file4['name'];			
				$MB_FN4		= $file_name4;
				
				if($file_name4 != '')
				{				
					$filename4 	= $_FILES["attachment4"]["name"];
					$source4 	= $_FILES["attachment4"]["tmp_name"];
					$type4 		= $_FILES["attachment4"]["type"];
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path = "mail_attachment/".$filename4;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Mail Attachment 4 Sent.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload attachment 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
			// ATTACHMENT 5
				$file5 		= $_FILES['attachment5'];
				$file_name5	= $file5['name'];			
				$MB_FN5		= $file_name5;
				
				if($file_name5 != '')
				{				
					$filename5 	= $_FILES["attachment5"]["name"];
					$source5 	= $_FILES["attachment5"]["tmp_name"];
					$type5 		= $_FILES["attachment5"]["type"];
					
					$name5 		= explode(".", $filename5);
					$fileExt5	= $name5[1];
					
					$target_path = "mail_attachment/".$filename5;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename5";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source5, $target_path))
					{
						$message5 			= "Mail Attachment 5 Sent.";
						$data['message5'] 	= $message5;
					}
					else 
					{
						$message5			= "There was a problem with the upload attachment 5. Please try again.";
						$data['message5'] 	= $message5;
					}
				}
			
			// LOOPING INSERT BY MAIL
				// PARTITIONING RECEIVING BY PERSONAL
					foreach ($MBR_TO as $sel_mail)
					{
						$mail_to	= explode ("|",$sel_mail);
						$mail_ID	= $mail_to[0];
						$mail_ADD	= $mail_to[1];
						$coll_MID1	= $mail_ID;
						$coll_MADD1	= $mail_ADD;
						
						// INSERT INTO INBOX
							$insMail = array(
											'MB_NO' 	=> $MBR_NO,
											'MB_CLASS' 	=> $MBR_CLASS,
											'MB_TYPE'	=> $MBR_TYPE,
											'MB_TYPE_X'	=> $MBR_TYPE_X,
											'MB_DEPT'	=> $MBR_DEPT,
											'MB_CODE'	=> $MBR_CODE,
											'MB_PARENTC'=> $MBR_NOP,
											'MB_SUBJECT'=> $MBR_SUBJECT,
											'MB_DATE'	=> $MBR_DATE,
											'MB_DATE1'	=> $MBR_DATE1,
											'MB_FROM_ID'=> $Emp_ID,
											'MB_FROM'	=> $MBR_FROM,
											'MB_TO_ID'	=> $coll_MID1,
											'MB_TO'		=> $coll_MADD1,
											'MB_TO_IDG'	=> $coll_MID,
											'MB_TOG'	=> $coll_MADD,
											'MB_MESSAGE'=> $MBR_MESSAGE,
											'MB_STATUS'	=> $MBR_STATUS,
											'MB_FN1'	=> $file_name1,
											'MB_FN2'	=> $file_name2,
											'MB_FN3'	=> $file_name3,
											'MB_FN4'	=> $file_name4,
											'MB_FN5'	=> $file_name5,
											'MB_ISRUNNO'=> 'N',
											'MB_ISGROUP'=> $MBR_ISGROUP,
											'MB_D'		=> $MBR_D,
											'MB_M'		=> $MBR_M,
											'MB_Y'		=> $MBR_Y,
											'MB_PATTNO'	=> $MBR_PATTNO);
							$this->m_mailbox->add($insMail);
					}
					
				// PARTITION RECEIVING BY GROUP
					if($MBR_TOG != '')
					{
						foreach ($MBR_TOG as $MG_CODE)
						{
							$sql_MGD	= "SELECT Emp_ID, Email
											FROM tbl_mailgroup_detail WHERE MG_CODE = '$MG_CODE'";
							$res_MGD	= $this->db->query($sql_MGD)->result();
							foreach($res_MGD as $rowMGD) :
								$Emp_IDM	= $rowMGD->Emp_ID;
								$EmailM		= $rowMGD->Email;
								
								// INSERT INTO INBOX
									$insMail = array(
													'MB_NO' 	=> $MBR_NO,
													'MB_CLASS' 	=> $MBR_CLASS,
													'MB_TYPE'	=> $MBR_TYPE,
													'MB_TYPE_X'	=> $MBR_TYPE_X,
													'MB_DEPT'	=> $MBR_DEPT,
													'MB_CODE'	=> $MBR_CODE,
													'MB_PARENTC'=> $MBR_NOP,
													'MB_SUBJECT'=> $MBR_SUBJECT,
													'MB_DATE'	=> $MBR_DATE,
													'MB_DATE1'	=> $MBR_DATE1,
													'MB_FROM_ID'=> $Emp_ID,
													'MB_FROM'	=> $MBR_FROM,													
													'MB_TO_ID'	=> $Emp_IDM,
													'MB_TO'		=> $EmailM,
													'MB_TO_IDG'	=> $coll_MID,
													'MB_TOG'	=> $coll_MADD,
													'MB_MESSAGE'=> $MBR_MESSAGE,
													'MB_STATUS'	=> $MBR_STATUS,
													'MB_FN1'	=> $file_name1,
													'MB_FN2'	=> $file_name2,
													'MB_FN3'	=> $file_name3,
													'MB_FN4'	=> $file_name4,
													'MB_FN5'	=> $file_name5,
													'MB_ISRUNNO'=> 'N',
													'MB_ISGROUP'=> $MBR_ISGROUP,
													'MB_D'		=> $MBR_D,
													'MB_M'		=> $MBR_M,
													'MB_Y'		=> $MBR_Y,
													'MB_PATTNO'	=> $MBR_PATTNO);
									$this->m_mailbox->add($insMail);								
							endforeach;
						}
					}
						
			// WRITE TO REPLY - ONLY ONE
				if($MBR_STATUS == 1)
				{
					$insMailR = array(
									'MBR_NO' 		=> $MBR_NO,
									'MBR_CODE'		=> $MBR_CODE,
									'MBR_CLASS' 	=> $MBR_CLASS,
									'MBR_TYPE'		=> $MBR_TYPE,
									'MBR_TYPE_X'	=> $MBR_TYPE_X,
									'MBR_DEPT'		=> $MBR_DEPT,
									'MBR_SUBJECT'	=> $MBR_SUBJECT,
									'MBR_DATE'		=> $MBR_DATE,
									'MBR_DATE1'		=> $MBR_DATE1,
									'MBR_FROM_ID'	=> $Emp_ID,
									'MBR_FROM'		=> $MBR_FROM,
									'MBR_TO_ID'		=> $coll_MID,
									'MBR_TO'		=> $coll_MADD,
									'MBR_TO_IDG'	=> $coll_MID,
									'MBR_TOG'		=> $coll_MADD,
									'MBR_MESSAGE'	=> $MBR_MESSAGE,
									'MBR_STATUS'	=> $MBR_STATUS,
									'MBR_ISGROUP'	=> $MBR_ISGROUP,
									'MBR_FN1'		=> $file_name1,
									'MBR_FN2'		=> $file_name2,
									'MBR_FN3'		=> $file_name3,
									'MBR_FN4'		=> $file_name4,
									'MBR_FN5'		=> $file_name5,
									'MBR_D'			=> $MBR_D,
									'MBR_M'			=> $MBR_M,
									'MBR_Y'			=> $MBR_Y,
									'MBR_PATTNO'	=> $MBR_PATTNO);
					$this->m_mailbox->addMBR($insMailR);
				}
					
			// WRITE TO SEND - ONLY ONE
				if($MBR_STATUS == 1)
				{
					$insMailS = array(
									'MBS_NO'		=> $MBR_NO,
									'MBS_CLASS' 	=> $MBR_CLASS,
									'MBS_TYPE'		=> $MBR_TYPE,
									'MBS_TYPE_X'	=> $MBR_TYPE_X,
									'MBS_DEPT'		=> $MBR_DEPT,
									'MBS_CODE'		=> $MBR_CODE,
									'MBS_PARENTC'	=> $MBR_NOP,		// No Parent
									'MBS_SUBJECT'	=> $MBR_SUBJECT,
									'MBS_DATE'		=> $MBR_DATE,
									'MBS_DATE1'		=> $MBR_DATE1,
									'MBS_FROM_ID'	=> $Emp_ID,
									'MBS_FROM'		=> $MBR_FROM,
									'MBS_TO_ID'		=> $coll_MID,
									'MBS_TO'		=> $coll_MADD,
									'MBS_TO_IDG'	=> $coll_MID,
									'MBS_TOG'		=> $coll_MADD,
									'MBS_MESSAGE'	=> $MBR_MESSAGE,
									'MBS_STATUS'	=> $MBR_STATUS,
									'MBS_FN1'		=> $file_name1,
									'MBS_FN2'		=> $file_name2,
									'MBS_FN3'		=> $file_name3,
									'MBS_FN4'		=> $file_name4,
									'MBS_FN5'		=> $file_name5,
									'MBS_ISRUNNO'	=> 'N',
									'MBS_ISGROUP'	=> $MBR_ISGROUP,
									'MBS_D'			=> $MBR_D,
									'MBS_M'			=> $MBR_M,
									'MBS_Y'			=> $MBR_Y,
									'MBS_PATTNO'	=> $MBR_PATTNO);
					$this->m_mailbox->addSend($insMailS);
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
			$data['MenuApp'] 		= 'MN381';
			
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
			$data['MenuApp'] 		= 'MN381';
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
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
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
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
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
	
	function draft_mail_update_process() // U -- Same with new mail
	{	
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	

		$DefEmp_ID 	= $this->session->userdata['Emp_ID']; // CREATER
		$CREATED 	= date('Y-m-d H:i:s');
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$MB_NO		= $this->input->post('MB_NO');				// 
			$MB_CODE	= $this->input->post('MB_CODE');				// 
			$MB_CLASS	= $this->input->post('MB_CLASS');				// 
			$MB_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MB_TYPE_X 	= $this->input->post('MB_TYPE_X');				// 
			$MB_DEPT 	= $this->input->post('MB_DEPT');				// 
			$PRJCODE 	= $this->input->post('PRJCODE');				// 
			
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
			
			// $MAIL_NO	= "$NO_02-$NO_03$NO_04/$NO_05/$NO_06-$NO_07";
			// $MB_CODE	= $MAIL_NO;										// 
			$MB_PARENTC	= "";											// 
			$MB_SUBJECT	= $this->input->post('MB_SUBJECT');				// 
			$MB_DATE	= date('Y-m-d');								// 
			$MB_DATE1	= date('Y-m-d H:i:s');							// 
			
			// GET MAIL FROM
				$Email		= '';
				$Emp_ID		= $this->input->post('Emp_ID'); // MAIL From
				$sqlEmp		= "SELECT Email FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
				$sqlEmp		= $this->db->query($sqlEmp)->result();
				foreach($sqlEmp as $row) :
					$Email		= $row->Email;
				endforeach;
			
			$MB_FROM	= $Email;										// 
			$MB_TO_ID 	= $this->input->post('MB_TO_ID');						// 			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 
			
			if(count($MB_TO_ID) > 1) $MB_ISGROUP = 'G';
			else $MB_ISGROUP = 'P';

			$MB_TO 		= "";
			$joinQEmp 	= implode("','", $MB_TO_ID);
			$s_Email 	= "SELECT Email FROM tbl_employee WHERE Emp_ID IN ('$joinQEmp') AND Email != ''";
			$r_Email 	= $this->db->query($s_Email);
			if($r_Email->num_rows() > 0)
			{
				foreach($r_Email->result() as $rw_Email):
					$Email_Emp[] = $rw_Email->Email;
				endforeach;
				$MB_TO 	= implode("|", $Email_Emp);
			}

			$MB_TO_ID 	= implode("|", $MB_TO_ID);			
			
			$MB_MESSAGE	= $this->input->post('MB_MESSAGE');
			$MB_STATUS	= $this->input->post('MB_STATUS');				// 1. New/Unread, 2. Read, 3. Draft, 4. Junk, 5. Delete
			$DOC_STATUS	= $this->input->post('DOC_STATUS');				// 1. New, 2. Confirm, 3. Approve, 4. Revisi, 5. Reject 6. Close, 7. Awaiting, 9. Void
			$MB_D		= date('d');
			$MB_M		= date('m');
			$MB_Y		= date('Y');
			$MB_H		= date('H');
			$MB_I		= date('i');
			$MB_S		= date('s');
			// $MB_NO		= "$MB_Y$MB_M$MB_D$MB_H$MB_I$MB_S";
			// $MB_PATTNO	= $resMBCN;

			$AH_CODE		= $MB_NO;
			$AH_APPLEV		= $this->input->post('APP_LEVEL');
			$AH_APPROVER	= $DefEmp_ID;
			$AH_APPROVED	= date('Y-m-d H:i:s');
			// $AH_NOTES		= $this->input->post('MB_NOTES');
			$AH_ISLAST		= $this->input->post('IS_LAST');
			
			// FOR MANY ATTACHMENT
			// ATTACHMENT 1
				$file1 		= $_FILES['attachment1'];
				$file_name1	= $file1['name'];			
				$MB_FN1		= $file_name1;
				
				if($file_name1 != '')
				{				
					$filename1 	= $_FILES["attachment1"]["name"];
					$source1 	= $_FILES["attachment1"]["tmp_name"];
					$type1 		= $_FILES["attachment1"]["type"];
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path = "mail_attachment/".$filename1;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Mail Attachment 1 Sent.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload attachment 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				
			// ATTACHMENT 2
				$file2 		= $_FILES['attachment2'];
				$file_name2	= $file2['name'];			
				$MB_FN2		= $file_name2;
				
				if($file_name2 != '')
				{				
					$filename2 	= $_FILES["attachment2"]["name"];
					$source2 	= $_FILES["attachment2"]["tmp_name"];
					$type2 		= $_FILES["attachment2"]["type"];
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path = "mail_attachment/".$filename2;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Mail Attachment 2 Sent.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload attachment 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				
			// ATTACHMENT 3
				$file3 		= $_FILES['attachment3'];
				$file_name3	= $file3['name'];			
				$MB_FN3		= $file_name3;
				
				if($file_name3 != '')
				{				
					$filename3 	= $_FILES["attachment3"]["name"];
					$source3 	= $_FILES["attachment3"]["tmp_name"];
					$type3 		= $_FILES["attachment3"]["type"];
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path = "mail_attachment/".$filename3;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Mail Attachment 3 Sent.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload attachment 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				
			// ATTACHMENT 4
				$file4 		= $_FILES['attachment4'];
				$file_name4	= $file4['name'];			
				$MB_FN4		= $file_name4;
				
				if($file_name4 != '')
				{				
					$filename4 	= $_FILES["attachment4"]["name"];
					$source4 	= $_FILES["attachment4"]["tmp_name"];
					$type4 		= $_FILES["attachment4"]["type"];
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path = "mail_attachment/".$filename4;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Mail Attachment 4 Sent.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload attachment 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
			// ATTACHMENT 5
				$file5 		= $_FILES['attachment5'];
				$file_name5	= $file5['name'];			
				$MB_FN5		= $file_name5;
				
				if($file_name5 != '')
				{				
					$filename5 	= $_FILES["attachment5"]["name"];
					$source5 	= $_FILES["attachment5"]["tmp_name"];
					$type5 		= $_FILES["attachment5"]["type"];
					
					$name5 		= explode(".", $filename5);
					$fileExt5	= $name5[1];
					
					$target_path = "mail_attachment/".$filename5;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename5";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source5, $target_path))
					{
						$message5 			= "Mail Attachment 5 Sent.";
						$data['message5'] 	= $message5;
					}
					else 
					{
						$message5			= "There was a problem with the upload attachment 5. Please try again.";
						$data['message5'] 	= $message5;
					}
				}
			

			// 

			if($MB_TOG != '')
			{
				$joinQMB_TOG 	= implode("','", $MB_TOG);
				$Emp_IDG 		= [];
				$s_TOG 			= "SELECT Emp_ID FROM tbl_mailgroup_detail WHERE MG_CODE IN ('$joinQMB_TOG') AND Emp_ID NOT IN ('$joinQEmp')";
				$r_TOG			= $this->db->query($s_TOG);
				if($r_TOG->num_rows() > 0)
				{
					foreach($r_TOG->result() as $rw_TOG):
						$Emp_IDG[] = $rw_TOG->Emp_IDG;
					endforeach;

					$joinQEmp 	= implode("','", $Emp_IDG);
					$s_Email 	= "SELECT Email FROM tbl_employee WHERE Emp_ID IN ('$joinQEmp') AND Email != ''";
					$r_Email 	= $this->db->query($s_Email);
					if($r_Email->num_rows() > 0)
					{
						foreach($r_Email->result() as $rw_Email):
							$Email_Emp[] = $rw_Email->Email;
						endforeach;
						$MB_TOG 	= implode("|", $Email_Emp);
						$MB_TO_IDG 	= implode("|", $Emp_IDG);
					}
				}
			}	
				
						
			if($DOC_STATUS == 3)
			{
				// DEFAULT STATUS FOR APPROVE
					$updMail 		= array('DOC_STATUS'	=> 7);					
					$this->m_mailbox->update($MB_NO, $updMail);

				// START : UPDATE STATUS
					$completeName 	= $this->session->userdata['completeName'];
					$paramStat 		= array('PM_KEY' 		=> "MB_NO",
											'DOC_CODE' 		=> $MB_NO,
											'DOC_STAT' 		=> 7,
											'PRJCODE' 		=> $PRJCODE,
											'CREATERNM'		=> '',
											'TBLNAME'		=> "tbl_mailbox");
					$this->m_updash->updateStatus($paramStat);
				// END : UPDATE STATUS

				// START : SAVE APPROVE HISTORY
					$this->load->model('m_updash/m_updash', '', TRUE);
					
					$insAppHist 	= array('PRJCODE'		=> $PRJCODE,
											'AH_CODE'		=> $MB_NO,
											'AH_APPLEV'		=> $AH_APPLEV,
											'AH_APPROVER'	=> $AH_APPROVER,
											'AH_APPROVED'	=> $AH_APPROVED,
											// 'AH_NOTES'		=> $MB_NOTES,
											'AH_ISLAST'		=> $AH_ISLAST);										
					$this->m_updash->insAppHist($insAppHist);
				// END : SAVE APPROVE HISTORY

				if($AH_ISLAST == 1)
				{
					$updMail = array('MB_CLASS' 	=> $MB_CLASS,
									 'MB_TYPE'		=> $MB_TYPE,
									 'MB_TYPE_X'	=> $MB_TYPE_X,
									 'MB_DEPT'		=> $MB_DEPT,
									 'MB_CODE'		=> $MB_CODE,
									 'MB_PARENTC'	=> $MB_PARENTC,
									 'MB_SUBJECT'	=> $MB_SUBJECT,
									 'MB_DATE'		=> $MB_DATE,
									 'MB_DATE1'		=> $MB_DATE1,
									 'MB_FROM_ID'	=> $Emp_ID,
									 'MB_FROM'		=> $MB_FROM,
									 'MB_TO_ID'		=> $MB_TO_ID,
									 'MB_TO'		=> $MB_TO,
									 'MB_TO_IDG'	=> $MB_TO_IDG,
									 'MB_TOG'		=> $MB_TOG,
									 'MB_MESSAGE'	=> $MB_MESSAGE,
									 'MB_STATUS'	=> $MB_STATUS, // Default 3. Draft
									 'DOC_STATUS'	=> 3,
									 'MB_FN1'		=> $file_name1,
									 'MB_FN2'		=> $file_name2,
									 'MB_FN3'		=> $file_name3,
									 'MB_FN4'		=> $file_name4,
									 'MB_FN5'		=> $file_name5,
									 'MB_ISRUNNO'	=> 'Y',
									 'MB_ISGROUP'	=> $MB_ISGROUP,
									 'MB_CREATER'	=> $DefEmp_ID,
									 'MB_CREATED'	=> $CREATED,
									 'MB_D'			=> $MB_D,
									 'MB_M'			=> $MB_M,
									 'MB_Y'			=> $MB_Y);
					$this->m_mailbox->update($MB_NO, $updMail);

					// START : UPDATE STATUS
						$completeName 	= $this->session->userdata['completeName'];
						$paramStat 		= array('PM_KEY' 		=> "MB_NO",
												'DOC_CODE' 		=> $MB_NO,
												'DOC_STAT' 		=> 3,
												'PRJCODE' 		=> $PRJCODE,
												'CREATERNM'		=> '',
												'TBLNAME'		=> "tbl_mailbox");
						$this->m_updash->updateStatus($paramStat);
					// END : UPDATE STATUS
				}

			}
			else
			{
				$updMail = array('MB_CLASS' 	=> $MB_CLASS,
								 'MB_TYPE'		=> $MB_TYPE,
								 'MB_TYPE_X'	=> $MB_TYPE_X,
								 'MB_DEPT'		=> $MB_DEPT,
								 'MB_CODE'		=> $MB_CODE,
								 'MB_PARENTC'	=> $MB_PARENTC,
								 'MB_SUBJECT'	=> $MB_SUBJECT,
								 'MB_DATE'		=> $MB_DATE,
								 'MB_DATE1'		=> $MB_DATE1,
								 'MB_FROM_ID'	=> $Emp_ID,
								 'MB_FROM'		=> $MB_FROM,
								 'MB_TO_ID'		=> $MB_TO_ID,
								 'MB_TO'		=> $MB_TO,
								 'MB_TO_IDG'	=> $MB_TO_IDG,
								 'MB_TOG'		=> $MB_TOG,
								 'MB_MESSAGE'	=> $MB_MESSAGE,
								 'MB_STATUS'	=> $MB_STATUS,
								 'DOC_STATUS'	=> $DOC_STATUS,
								 'MB_FN1'		=> $file_name1,
								 'MB_FN2'		=> $file_name2,
								 'MB_FN3'		=> $file_name3,
								 'MB_FN4'		=> $file_name4,
								 'MB_FN5'		=> $file_name5,
								 'MB_ISRUNNO'	=> 'Y',
								 'MB_ISGROUP'	=> $MB_ISGROUP,
								 'MB_CREATER'	=> $DefEmp_ID,
								 'MB_CREATED'	=> $CREATED,
								 'MB_D'			=> $MB_D,
								 'MB_M'			=> $MB_M,
								 'MB_Y'			=> $MB_Y);
				$this->m_mailbox->update($MB_NO, $updMail);
			}
			
			// DELETE DRAFT ORIGINAL			
			// $this->m_mailbox->DeleteOriginalD($MB_ID);
			
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
	
	function draft_mail_update_process_230922() // U -- Same with new mail
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
			$MB_TYPE_X 	= $this->input->post('MB_TYPE_X');				// 
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
			
			$MB_ISGROUP	= "P";
			// FOR GROUPING RECEIVING BY PERSONAL
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
					
					if($selStep > 1)
						$MB_ISGROUP = "G";
				}
			
			$MB_TOG 	= $this->input->post('MB_TOG');					// 
			// FOR GROUPING RECEIVING BY GROUP
				// GETD ETAIL GROUP
				if($MB_TOG != '')
				{
					$MB_ISGROUP = "G";
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
			$MB_H		= date('H');
			$MB_I		= date('i');
			$MB_S		= date('s');
			$MB_NO		= "$MB_Y$MB_M$MB_D$MB_H$MB_I$MB_S";
			$MB_PATTNO	= $resMBCN;
			
			// FOR MANY ATTACHMENT
			// ATTACHMENT 1
				$file1 		= $_FILES['attachment1'];
				$file_name1	= $file1['name'];			
				$MB_FN1		= $file_name1;
				
				if($file_name1 != '')
				{				
					$filename1 	= $_FILES["attachment1"]["name"];
					$source1 	= $_FILES["attachment1"]["tmp_name"];
					$type1 		= $_FILES["attachment1"]["type"];
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path = "mail_attachment/".$filename1;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Mail Attachment 1 Sent.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload attachment 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				
			// ATTACHMENT 2
				$file2 		= $_FILES['attachment2'];
				$file_name2	= $file2['name'];			
				$MB_FN2		= $file_name2;
				
				if($file_name2 != '')
				{				
					$filename2 	= $_FILES["attachment2"]["name"];
					$source2 	= $_FILES["attachment2"]["tmp_name"];
					$type2 		= $_FILES["attachment2"]["type"];
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path = "mail_attachment/".$filename2;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Mail Attachment 2 Sent.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload attachment 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				
			// ATTACHMENT 3
				$file3 		= $_FILES['attachment3'];
				$file_name3	= $file3['name'];			
				$MB_FN3		= $file_name3;
				
				if($file_name3 != '')
				{				
					$filename3 	= $_FILES["attachment3"]["name"];
					$source3 	= $_FILES["attachment3"]["tmp_name"];
					$type3 		= $_FILES["attachment3"]["type"];
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path = "mail_attachment/".$filename3;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Mail Attachment 3 Sent.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload attachment 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				
			// ATTACHMENT 4
				$file4 		= $_FILES['attachment4'];
				$file_name4	= $file4['name'];			
				$MB_FN4		= $file_name4;
				
				if($file_name4 != '')
				{				
					$filename4 	= $_FILES["attachment4"]["name"];
					$source4 	= $_FILES["attachment4"]["tmp_name"];
					$type4 		= $_FILES["attachment4"]["type"];
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path = "mail_attachment/".$filename4;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Mail Attachment 4 Sent.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload attachment 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
			// ATTACHMENT 5
				$file5 		= $_FILES['attachment5'];
				$file_name5	= $file5['name'];			
				$MB_FN5		= $file_name5;
				
				if($file_name5 != '')
				{				
					$filename5 	= $_FILES["attachment5"]["name"];
					$source5 	= $_FILES["attachment5"]["tmp_name"];
					$type5 		= $_FILES["attachment5"]["type"];
					
					$name5 		= explode(".", $filename5);
					$fileExt5	= $name5[1];
					
					$target_path = "mail_attachment/".$filename5;  // change this to the correct site path
						
					$myPath 	= "mail_attachment/$filename5";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source5, $target_path))
					{
						$message5 			= "Mail Attachment 5 Sent.";
						$data['message5'] 	= $message5;
					}
					else 
					{
						$message5			= "There was a problem with the upload attachment 5. Please try again.";
						$data['message5'] 	= $message5;
					}
				}
			
			// LOOPING INSERT BY MAIL
				// PARTITIONING RECEIVING BY PERSONAL
					foreach ($MB_TO as $sel_mail)
					{
						$mail_to	= explode ("|",$sel_mail);
						$mail_ID	= $mail_to[0];
						$mail_ADD	= $mail_to[1];
						$coll_MID1	= $mail_ID;
						$coll_MADD1	= $mail_ADD;
						
						// INSERT INTO INBOX
							$insMail = array(
											'MB_NO' 	=> $MB_NO,
											'MB_CLASS' 	=> $MB_CLASS,
											'MB_TYPE'	=> $MB_TYPE,
											'MB_TYPE_X'	=> $MB_TYPE_X,
											'MB_DEPT'	=> $MB_DEPT,
											'MB_CODE'	=> $MB_CODE,
											'MB_PARENTC'=> $MB_PARENTC,
											'MB_SUBJECT'=> $MB_SUBJECT,
											'MB_DATE'	=> $MB_DATE,
											'MB_DATE1'	=> $MB_DATE1,
											'MB_FROM_ID'=> $DefEmp_ID,
											'MB_FROM'	=> $MB_FROM,
											'MB_TO_ID'	=> $coll_MID1,
											'MB_TO'		=> $coll_MADD1,
											'MB_TO_IDG'	=> $coll_MID,
											'MB_TOG'	=> $coll_MADD,
											'MB_MESSAGE'=> $MB_MESSAGE,
											'MB_STATUS'	=> $MB_STATUS,
											'MB_FN1'	=> $file_name1,
											'MB_FN2'	=> $file_name2,
											'MB_FN3'	=> $file_name3,
											'MB_FN4'	=> $file_name4,
											'MB_FN5'	=> $file_name5,
											'MB_ISRUNNO'=> 'Y',
											'MB_ISGROUP'=> $MB_ISGROUP,
											'MB_D'		=> $MB_D,
											'MB_M'		=> $MB_M,
											'MB_Y'		=> $MB_Y,
											'MB_PATTNO'	=> $MB_PATTNO);
							$this->m_mailbox->add($insMail);
					}
					
				// PARTITION RECEIVING BY GROUP
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
								
								// INSERT INTO INBOX
									$insMail = array(
													'MB_NO' 	=> $MB_NO,
													'MB_CLASS' 	=> $MB_CLASS,
													'MB_TYPE'	=> $MB_TYPE,
													'MB_TYPE_X'	=> $MB_TYPE_X,
													'MB_DEPT'	=> $MB_DEPT,
													'MB_CODE'	=> $MB_CODE,
													'MB_PARENTC'=> $MB_PARENTC,
													'MB_SUBJECT'=> $MB_SUBJECT,
													'MB_DATE'	=> $MB_DATE,
													'MB_DATE1'	=> $MB_DATE1,
													'MB_FROM_ID'=> $DefEmp_ID,
													'MB_FROM'	=> $MB_FROM,
													'MB_TO_ID'	=> $Emp_IDM,
													'MB_TO'		=> $EmailM,
													'MB_TO_IDG'	=> $coll_MID,
													'MB_TOG'	=> $coll_MADD,
													'MB_MESSAGE'=> $MB_MESSAGE,
													'MB_STATUS'	=> $MB_STATUS,
													'MB_FN1'	=> $file_name1,
													'MB_FN2'	=> $file_name2,
													'MB_FN3'	=> $file_name3,
													'MB_FN4'	=> $file_name4,
													'MB_FN5'	=> $file_name5,
													'MB_ISRUNNO'=> 'Y',
													'MB_ISGROUP'=> $MB_ISGROUP,
													'MB_D'		=> $MB_D,
													'MB_M'		=> $MB_M,
													'MB_Y'		=> $MB_Y,
													'MB_PATTNO'	=> $MB_PATTNO);
									$this->m_mailbox->add($insMail);								
							endforeach;
						}
					}
						
				// WRITE TO SEND - ONLY ONE
					if($MB_STATUS == 1)
					{
						$insMailS = array(
										'MBS_NO'		=> $MB_NO,
										'MBS_CLASS' 	=> $MB_CLASS,
										'MBS_TYPE'		=> $MB_TYPE,
										'MBS_TYPE_X'	=> $MB_TYPE_X,
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
										'MBS_TO_IDG'	=> $coll_MID,
										'MBS_TOG'		=> $coll_MADD,
										'MBS_MESSAGE'	=> $MB_MESSAGE,
										'MBS_STATUS'	=> $MB_STATUS,
										'MBS_FN1'		=> $file_name1,
										'MBS_FN2'		=> $file_name2,
										'MBS_FN3'		=> $file_name3,
										'MBS_FN4'		=> $file_name4,
										'MBS_FN5'		=> $file_name5,
										'MBS_ISRUNNO'	=> 'N',
										'MBS_ISGROUP'	=> $MB_ISGROUP,
										'MBS_D'			=> $MB_D,
										'MBS_M'			=> $MB_M,
										'MBS_Y'			=> $MB_Y,
										'MBS_PATTNO'	=> $MB_PATTNO);
						$this->m_mailbox->addSend($insMailS);
					}
			
			// DELETE DRAFT ORIGINAL			
			$this->m_mailbox->DeleteOriginalD($MB_ID);
			
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
			$data['MenuApp'] 		= 'MN381';
			
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
			$MBT_NO		= $this->input->post('MB_NO');					// 
			$MBT_CLASS	= $this->input->post('MB_CLASS');				// 
			$MBT_TYPE 	= $this->input->post('MB_TYPE');				// 
			$MBT_TYPE_X = $this->input->post('MB_TYPE_X');				// 
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
			$MBT_TO_IDG	= $this->input->post('MB_TO_IDG');				// 
			$MBT_TOG	= $this->input->post('MB_TOG');					// 					
			$MBT_MESSAGE= $this->input->post('MB_MESSAGE');				// 
			$MBT_STATUS	= $this->input->post('MB_STATUS');				//
			$MBT_FN1	= $this->input->post('MB_FN1');					//
			$MBT_FN2	= $this->input->post('MB_FN2');					//
			$MBT_FN3	= $this->input->post('MB_FN3');					//
			$MBT_FN4	= $this->input->post('MB_FN4');					//
			$MBT_FN5	= $this->input->post('MB_FN5');					//
			$MBT_ISRUNNO= $this->input->post('MB_ISRUNNO');				// 
			$MBT_D		= $this->input->post('MB_D');					// 
			$MBT_M		= $this->input->post('MB_M');					// 
			$MBT_Y		= $this->input->post('MB_Y');					// 
			$MBT_PATTNO	= $this->input->post('MB_PATTNO');				// 	
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			// INSERT INTO TRASH
				$insMail 	= array(
								'MBT_NO' 		=> $MBT_NO,
								'MBT_CLASS' 	=> $MBT_CLASS,
								'MBT_TYPE'		=> $MBT_TYPE,
								'MBT_TYPE_X'	=> $MBT_TYPE_X,
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
								'MBT_TO_IDG'	=> $MBT_TO_IDG,
								'MBT_TOG'		=> $MBT_TOG,
								'MBT_MESSAGE'	=> $MBT_MESSAGE,
								'MBT_STATUS'	=> $MBT_STATUS,
								'MBT_FN1'		=> $MBT_FN1,
								'MBT_FN2'		=> $MBT_FN2,
								'MBT_FN3'		=> $MBT_FN3,
								'MBT_FN4'		=> $MBT_FN4,
								'MBT_FN5'		=> $MBT_FN5,
								'MBT_ISRUNNO'	=> $MBT_ISRUNNO,
								'MBT_D'			=> $MBT_D,
								'MBT_M'			=> $MBT_M,
								'MBT_Y'			=> $MBT_Y,
								'MBT_PATTNO'	=> $MBT_PATTNO,
								'MBT_DEL_BY'	=> $DefEmp_ID);
				$this->m_mailbox->addTrash($insMail);
			
			// INSERT INTO TRASH EXT - PERMANENT
				$insMailExt = array(
								'MBT_NO' 	=> $MBT_NO,
								'MBT_CLASS' 	=> $MBT_CLASS,
								'MBT_TYPE'		=> $MBT_TYPE,
								'MBT_TYPE_X'	=> $MBT_TYPE_X,
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
								'MBT_TO_IDG'	=> $MBT_TO_IDG,
								'MBT_TOG'		=> $MBT_TOG,
								'MBT_MESSAGE'	=> $MBT_MESSAGE,
								'MBT_STATUS'	=> $MBT_STATUS,
								'MBT_FN1'		=> $MBT_FN1,
								'MBT_FN2'		=> $MBT_FN2,
								'MBT_FN3'		=> $MBT_FN3,
								'MBT_FN4'		=> $MBT_FN4,
								'MBT_FN5'		=> $MBT_FN5,
								'MBT_ISRUNNO'	=> $MBT_ISRUNNO,
								'MBT_D'			=> $MBT_D,
								'MBT_M'			=> $MBT_M,
								'MBT_Y'			=> $MBT_Y,
								'MBT_PATTNO'	=> $MBT_PATTNO,
								'MBT_DEL_BY'	=> $DefEmp_ID,
								'MBT_SOURCE'	=> 'I');
				$this->m_mailbox->addTrashExt($insMailExt);
			
			// DELETE DATA IS HOLDED, karena untuk kepentingan RUNNING NO, jadi don't delete, update only
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
	
	function trash_mail_process_idx_I() // U - delete from inbox list, 1 by 1
	{
		$MB_ID		= $_GET['id'];
		//$MB_ID		= $this->url_encryption_helper->decode_url($MB_ID);
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
			$MB_NO 		= $getMailDetail->MB_NO;
			$MB_CLASS 	= $getMailDetail->MB_CLASS;
			$MB_TYPE 	= $getMailDetail->MB_TYPE;
			$MB_TYPE_X 	= $getMailDetail->MB_TYPE_X;
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
			$MB_TO_IDG	= $getMailDetail->MB_TO_IDG;
			$MB_TOG		= $getMailDetail->MB_TOG;
			$MB_MESSAGE = $getMailDetail->MB_MESSAGE;
			$MB_STATUS 	= $getMailDetail->MB_STATUS;
			$MB_FN1		= $getMailDetail->MB_FN1;
			$MB_FN2		= $getMailDetail->MB_FN2;
			$MB_FN3		= $getMailDetail->MB_FN3;
			$MB_FN4		= $getMailDetail->MB_FN4;
			$MB_FN5		= $getMailDetail->MB_FN5;
			$MB_ISRUNNO	= $getMailDetail->MB_ISRUNNO;
			$MB_ISGROUP	= $getMailDetail->MB_ISGROUP;
			$MB_D		= $getMailDetail->MB_D;
			$MB_M		= $getMailDetail->MB_M;
			$MB_Y		= $getMailDetail->MB_Y;
			$MB_PATTNO	= $getMailDetail->MB_PATTNO;
			
			// INSERT INTO TRASH
				$insMail 	= array(
								'MBT_NO' 		=> $MB_NO,
								'MBT_CLASS' 	=> $MB_CLASS,
								'MBT_TYPE'		=> $MB_TYPE,
								'MBT_TYPE_x'	=> $MB_TYPE_x,
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
				$this->m_mailbox->addTrash($insMail);
			
			// INSERT INTO TRASH EXT - PERMANENT
				$insMailExt = array(
								'MBT_NO' 		=> $MB_NO,
								'MBT_CLASS' 	=> $MB_CLASS,
								'MBT_TYPE'		=> $MB_TYPE,
								'MBT_TYPE_x'	=> $MB_TYPE_x,
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
			
			return $this->m_mailbox->get_all_mail_inbox($DefEmp_ID)->result();
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function trash_mail_process_idx_S() // U - delete from send list, 1 by 1
	{
		$MBS_ID	= $_GET['id'];
		//$MBS_ID	= $this->url_encryption_helper->decode_url($MBS_ID);
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
			$MBS_NO 	= $getMailDetail->MBS_NO;
			$MBS_CLASS 	= $getMailDetail->MBS_CLASS;
			$MBS_TYPE 	= $getMailDetail->MBS_TYPE;
			$MBS_TYPE_X	= $getMailDetail->MBS_TYPE_X;
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
			$MBS_TO_IDG	= $getMailDetail->MBS_TO_IDG;
			$MBS_TOG	= $getMailDetail->MBS_TOG;
			$MBS_MESSAGE= $getMailDetail->MBS_MESSAGE;
			$MBS_STATUS = $getMailDetail->MBS_STATUS;
			$MBS_FN1	= $getMailDetail->MBS_FN1;
			$MBS_FN2	= $getMailDetail->MBS_FN2;
			$MBS_FN3	= $getMailDetail->MBS_FN3;
			$MBS_FN4	= $getMailDetail->MBS_FN4;
			$MBS_FN5	= $getMailDetail->MBS_FN5;
			$MBS_ISRUNNO= $getMailDetail->MBS_ISRUNNO;
			$MBS_ISGROUP= $getMailDetail->MBS_ISGROUP;
			$MBS_D		= $getMailDetail->MBS_D;
			$MBS_M		= $getMailDetail->MBS_M;
			$MBS_Y		= $getMailDetail->MBS_Y;
			$MBS_PATTNO	= $getMailDetail->MBS_PATTNO;
					
			$insMail 	= array(
							'MBT_NO' 		=> $MBS_NO,
							'MBT_CLASS' 	=> $MBS_CLASS,
							'MBT_TYPE'		=> $MBS_TYPE,
							'MBT_TYPE_X'	=> $MBS_TYPE_X,
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
							'MBT_TO_IDG'	=> $MBS_TO_IDG,
							'MBT_TOG'		=> $MBS_TOG,
							'MBT_MESSAGE'	=> $MBS_MESSAGE,
							'MBT_STATUS'	=> $MBS_STATUS,
							'MBT_FN1'		=> $MBS_FN1,
							'MBT_FN2'		=> $MBS_FN2,
							'MBT_FN3'		=> $MBS_FN3,
							'MBT_FN4'		=> $MBS_FN4,
							'MBT_FN5'		=> $MBS_FN5,
							'MBT_ISRUNNO'	=> $MBS_ISRUNNO,
							'MBT_ISGROUP'	=> $MBS_ISGROUP,
							'MBT_D'			=> $MBS_D,
							'MBT_M'			=> $MBS_M,
							'MBT_Y'			=> $MBS_Y,
							'MBT_PATTNO'	=> $MBS_PATTNO,
							'MBT_DEL_BY'	=> $DefEmp_ID);		
			$this->m_mailbox->addTrashS($insMail);
			
			// INSERT INTO TRASH EXT - PERMANENT
				$insMailExt = array(
							'MBT_NO' 		=> $MBS_NO,
							'MBT_CLASS' 	=> $MBS_CLASS,
							'MBT_TYPE'		=> $MBS_TYPE,
							'MBT_TYPE_X'	=> $MBS_TYPE_X,
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
							'MBT_TO_IDG'	=> $MBS_TO_IDG,
							'MBT_TOG'		=> $MBS_TOG,
							'MBT_MESSAGE'	=> $MBS_MESSAGE,
							'MBT_STATUS'	=> $MBS_STATUS,
							'MBT_FN1'		=> $MBS_FN1,
							'MBT_FN2'		=> $MBS_FN2,
							'MBT_FN3'		=> $MBS_FN3,
							'MBT_FN4'		=> $MBS_FN4,
							'MBT_FN5'		=> $MBS_FN5,
							'MBT_ISRUNNO'	=> $MBS_ISRUNNO,
							'MBT_ISGROUP'	=> $MBS_ISGROUP,
							'MBT_D'			=> $MBS_D,
							'MBT_M'			=> $MBS_M,
							'MBT_Y'			=> $MBS_Y,
							'MBT_PATTNO'	=> $MBS_PATTNO,
							'MBT_DEL_BY'	=> $DefEmp_ID,
							'MBT_SOURCE'	=> 'S');
				$this->m_mailbox->addTrashExt($insMailExt);
			
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
	
	function trash_mail_process_idx_D() // U - delete from draft list, 1 by 1
	{
		$MB_ID		= $_GET['id'];
		//$MB_ID	= $this->url_encryption_helper->decode_url($MB_ID);
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
			$MB_NO 		= $getMailDetail->MB_NO;
			$MB_CLASS 	= $getMailDetail->MB_CLASS;
			$MB_TYPE 	= $getMailDetail->MB_TYPE;
			$MB_TYPE_X 	= $getMailDetail->MB_TYPE_X;
			$MB_DEPT 	= $getMailDetail->MB_DEPT;
			$MB_CODE 	= $getMailDetail->MB_CODE;
			$MB_PARENTC	= $getMailDetail->MB_PARENTC;
			$MB_SUBJECT	= $getMailDetail->MB_SUBJECT;
			$MB_DATE 	= $getMailDetail->MB_DATE;
			$MB_DATE1 	= $getMailDetail->MB_DATE1;
			$MB_READD 	= $getMailDetail->MB_READD;
			$MB_FROM_ID	= $getMailDetail->MB_FROM_ID;
			$MB_FROM	= $getMailDetail->MB_FROM;
			$MB_TO_ID	= $getMailDetail->MB_TO_ID;
			$MB_TO		= $getMailDetail->MB_TO;
			$MB_TO_IDG	= $getMailDetail->MB_TO_IDG;
			$MB_TOG		= $getMailDetail->MB_TOG;
			$MB_MESSAGE	= $getMailDetail->MB_MESSAGE;
			$MB_STATUS 	= $getMailDetail->MB_STATUS;
			$MB_FN1		= $getMailDetail->MB_FN1;
			$MB_FN2		= $getMailDetail->MB_FN2;
			$MB_FN3		= $getMailDetail->MB_FN3;
			$MB_FN4		= $getMailDetail->MB_FN4;
			$MB_FN5		= $getMailDetail->MB_FN5;
			$MB_ISRUNNO	= $getMailDetail->MB_ISRUNNO;
			$MB_ISGROUP	= $getMailDetail->MB_ISGROUP;
			$MB_D		= $getMailDetail->MB_D;
			$MB_M		= $getMailDetail->MB_M;
			$MB_Y		= $getMailDetail->MB_Y;
			$MB_PATTNO	= $getMailDetail->MB_PATTNO;
					
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
								'MBT_FN3'		=> $MBS_FN3,
								'MBT_FN4'		=> $MB_FN4,
								'MBT_FN5'		=> $MB_FN5,
								'MBT_ISRUNNO'	=> $MB_ISRUNNO,
								'MBT_ISGROUP'	=> $MB_ISGROUP,
								'MBT_D'			=> $MB_D,
								'MBT_M'			=> $MB_M,
								'MBT_Y'			=> $MB_Y,
								'MBT_PATTNO'	=> $MB_PATTNO,
								'MBT_DEL_BY'	=> $DefEmp_ID);		
				$this->m_mailbox->addTrashS($insMail);
			
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
								'MBT_FN3'		=> $MBS_FN3,
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
				$this->m_mailbox->addTrashExt($insMailExt);
				
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
	
	function trash_mail_process_idx_T() // U - delete from trash list, 1 by 1
	{
		$MBT_ID		= $_GET['id'];
		//$MBT_ID	= $this->url_encryption_helper->decode_url($MBT_ID);
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
			
			$collID 	= $this->input->post('collID');
			$collIDC	= count(explode ("|",$collID));
			$mail_to	= explode ("|",$collID);
			for($i = 0; $i < $collIDC; $i++)
			{
				$mail_ID	= $mail_to[$i];
				$this->m_mailbox->DeleteOriginalI_All($mail_ID);
			}
			
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
			
			$collID 	= $this->input->post('collID');
			$collIDC	= count(explode ("|",$collID));
			$mail_to	= explode ("|",$collID);
			for($i = 0; $i < $collIDC; $i++)
			{
				$mail_ID	= $mail_to[$i];
				$this->m_mailbox->DeleteOriginalS_All($mail_ID);
			}
			
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
			
			$collID 	= $this->input->post('collID');
			$collIDC	= count(explode ("|",$collID));
			$mail_to	= explode ("|",$collID);
			for($i = 0; $i < $collIDC; $i++)
			{
				$mail_ID	= $mail_to[$i];
				$this->m_mailbox->DeleteOriginalD_All($mail_ID);
			}
			
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
	
	function downloadFile()
	{
		$FileUpName	= $_GET['id'];
		$FileUpName	= $this->url_encryption_helper->decode_url($FileUpName);
		
        header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);
	}
	
	function DL_mail_I() // U
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
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['h3_title']		= 'Print';
			$data['viewType']		= 1;
			
			$getMailDetail			= $this->m_mailbox->get_MailDetl($MB_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MB_ID;
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MB_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MB_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MB_SUBJECT;
			$data['MB_SUBJECT'] 			= $getMailDetail->MB_SUBJECT;
			
			$data['default']['MB_DATE'] 	= $getMailDetail->MB_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MB_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MB_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MB_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MB_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MB_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MB_TO;
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/print_mail_I', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function print_mail_I() // U
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
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['h3_title']		= 'Print';
			$data['viewType']		= 2;
			
			$getMailDetail			= $this->m_mailbox->get_MailDetl($MB_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MB_ID;
			$data['default']['MB_NO'] 		= $getMailDetail->MB_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MB_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MB_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MB_TYPE_X;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MB_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MB_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MB_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MB_SUBJECT;
			$data['MB_SUBJECT'] 			= $getMailDetail->MB_SUBJECT;
			
			$data['default']['MB_DATE'] 	= $getMailDetail->MB_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MB_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MB_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MB_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MB_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MB_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MB_TO;
			$data['default']['MB_TO_IDG']	= $getMailDetail->MB_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MB_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MB_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MB_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MB_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MB_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MB_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MB_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MB_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MB_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MB_D;
			$data['default']['MB_M']		= $getMailDetail->MB_M;
			$data['default']['MB_Y']		= $getMailDetail->MB_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MB_PATTNO;
			
			$this->load->view('v_mailbox/print_mail_I', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function DL_mail_S() // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MBS_ID		= $_GET['id'];
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['h3_title']		= 'Print';
			$data['viewType']		= 1;
			
			$getMailDetail			= $this->m_mailbox->get_MailDetlS($MBS_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MBS_ID;
			$data['default']['MB_NO'] 		= $getMailDetail->MBS_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MBS_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MBS_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MBS_TYPE_X;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MBS_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MBS_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MBS_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MBS_SUBJECT;
			$data['MB_SUBJECT'] 			= $getMailDetail->MBS_SUBJECT;
			
			$data['default']['MB_DATE'] 	= $getMailDetail->MBS_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MBS_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MBS_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MBS_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MBS_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MBS_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MBS_TO;
			$data['default']['MB_TO_IDG']	= $getMailDetail->MBS_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MBS_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MBS_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MBS_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MBS_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MBS_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MBS_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MBS_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MBS_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MBS_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MBS_D;
			$data['default']['MB_M']		= $getMailDetail->MBS_M;
			$data['default']['MB_Y']		= $getMailDetail->MBS_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MBS_PATTNO;
			
			$this->load->view('v_mailbox/print_mail_S', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function print_mail_S() // U
	{
		$this->load->model('m_mailbox/m_mailbox', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MBS_ID		= $_GET['id'];
			$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			
			date_default_timezone_set("Asia/Jakarta");
			
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Mailbox';
			$data['MenuApp'] 		= 'MN381';
			$data['h3_title']		= 'Print';
			$data['viewType']		= 2;
			
			$getMailDetail			= $this->m_mailbox->get_MailDetlS($MBS_ID)->row();
			
			$data['default']['MB_ID'] 		= $getMailDetail->MBS_ID;
			$data['default']['MB_NO'] 		= $getMailDetail->MBS_NO;
			$data['default']['MB_CLASS'] 	= $getMailDetail->MBS_CLASS;
			$data['default']['MB_TYPE'] 	= $getMailDetail->MBS_TYPE;
			$data['default']['MB_TYPE_X'] 	= $getMailDetail->MBS_TYPE_X;
			$data['default']['MB_DEPT'] 	= $getMailDetail->MBS_DEPT;
			$data['default']['MB_CODE'] 	= $getMailDetail->MBS_CODE;
			$data['default']['MB_PARENTC'] 	= $getMailDetail->MBS_PARENTC;
			$data['default']['MB_SUBJECT'] 	= $getMailDetail->MBS_SUBJECT;
			$data['MB_SUBJECT'] 			= $getMailDetail->MBS_SUBJECT;
			
			$data['default']['MB_DATE'] 	= $getMailDetail->MBS_DATE;
			$data['default']['MB_DATE1'] 	= $getMailDetail->MBS_DATE1;
			$data['default']['MB_READD'] 	= $getMailDetail->MBS_READD;
			$data['default']['MB_FROM_ID'] 	= $getMailDetail->MBS_FROM_ID;
			$data['default']['MB_FROM']		= $getMailDetail->MBS_FROM;
			$data['default']['MB_TO_ID']	= $getMailDetail->MBS_TO_ID;
			$data['default']['MB_TO']		= $getMailDetail->MBS_TO;
			$data['default']['MB_TO_IDG']	= $getMailDetail->MBS_TO_IDG;
			$data['default']['MB_TOG']		= $getMailDetail->MBS_TOG;
			$data['default']['MB_MESSAGE'] 	= $getMailDetail->MBS_MESSAGE;
			$data['default']['MB_STATUS'] 	= $getMailDetail->MBS_STATUS;	
			$data['default']['DOC_STATUS'] 	= $getMailDetail->DOC_STATUS;	
			$data['default']['MB_FN1']		= $getMailDetail->MBS_FN1;
			$data['default']['MB_FN2']		= $getMailDetail->MBS_FN2;
			$data['default']['MB_FN3']		= $getMailDetail->MBS_FN3;
			$data['default']['MB_FN4']		= $getMailDetail->MBS_FN4;
			$data['default']['MB_FN5']		= $getMailDetail->MBS_FN5;		
			$data['default']['MB_ISRUNNO']	= $getMailDetail->MBS_ISRUNNO;
			$data['default']['MB_D']		= $getMailDetail->MBS_D;
			$data['default']['MB_M']		= $getMailDetail->MBS_M;
			$data['default']['MB_Y']		= $getMailDetail->MBS_Y;
			$data['default']['MB_PATTNO']	= $getMailDetail->MBS_PATTNO;
			
			$this->load->view('v_mailbox/print_mail_I', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}