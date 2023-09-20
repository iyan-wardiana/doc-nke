<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 25 Agustus 2017
 * File Name	= C_news.php
 * Location		= -
*/

class C_news extends CI_Controller
{
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/c_news/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
			$data['title'] 		= $appName;
			$data['h1_title']	= 'news';
			$data['h2_title']	= 'news';
			$data['h3_title']	= 'setting';			
			$data["countNews"]	= $this->m_news->count_all_news($Emp_ID);	 
			$data['vwNews'] 	= $this->m_news->get_all_news($Emp_ID)->result();	
			$data["MenuCode"] 	= 'MN307';
			
			$this->load->view('v_setting/v_news/v_news', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add()
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title'] 		= 'Add News';
			$data['main_view'] 		= 'v_setting/v_news/v_news_form';
			$data['form_action']	= site_url('c_setting/c_news/do_upload');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_news/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_news/');
			$data["MenuCode"] 		= 'MN307';
			
			$this->load->view('v_setting/v_news/v_news_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$NEWSH_CODE		= $this->input->post('NEWSH_CODE');
			$NEWSH_DATE		= date('Y-m-d',strtotime($this->input->post('NEWSH_DATE')));	
			$NEWSH_TITLE	= $this->input->post('NEWSH_TITLE');
			$NEWSH_CONTENT	= $this->input->post('NEWSH_CONTENT');
			$NEWSH_CREATER	= $this->input->post('NEWSH_CREATER');
			$NEWSH_CREATED	= date('Y-m-d H:i:s');
			$NEWSH_STAT		= $this->input->post('NEWSH_STAT');
			$NEWSH_PATTNO	= $this->input->post('NEWSH_PATTNO');
			$NEWSH_RECEIVERG= $this->input->post('NEWSH_RECEIVER');
			
			// CREATE HEADER
				$therow			= 0;
				$RECEIVER1		= '';
				foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER1)
				{
					$therow		= $therow + 1;
					if($therow == 1)
					{
						$RECEIVER1	= $NEWSH_RECEIVER1;
					}
					else
					{
						$RECEIVER1	= "$RECEIVER1|$NEWSH_RECEIVER1";
					}				
				}
				$NEWSH_RECEIVERGH	= "$RECEIVER1";
				$InsNewsH	= array('NEWSH_CODE' 	=> $NEWSH_CODE,
									'NEWSH_DATE'	=> $NEWSH_DATE,
									'NEWSH_RECEIVER'=> $NEWSH_RECEIVERGH,
									'NEWSH_TITLE'	=> $NEWSH_TITLE,
									'NEWSH_CONTENT'	=> $NEWSH_CONTENT,
									'NEWSH_CREATER'	=> $NEWSH_CREATER,
									'NEWSH_CREATED'	=> $NEWSH_CREATED,
									'NEWSH_STAT'	=> $NEWSH_STAT,
									'NEWSH_PATTNO'	=> $NEWSH_PATTNO);	
				$this->m_news->addH($InsNewsH);
			
			
			foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER)
			{
				$sql_MGD	= "SELECT Emp_ID, Email FROM tbl_mailgroup_detail WHERE MG_CODE = '$NEWSH_RECEIVER'";
				$res_MGD	= $this->db->query($sql_MGD)->result();
				foreach($res_MGD as $rowMGD) :
					$Emp_IDN	= $rowMGD->Emp_ID;			
					$InsNewsD	= array('NEWSD_CODE' 	=> $NEWSH_CODE,
										'NEWSD_DATE'	=> $NEWSH_DATE,
										'NEWSD_RECEIVER'=> $Emp_IDN,
										'NEWSD_TITLE'	=> $NEWSH_TITLE,
										'NEWSD_CONTENT'	=> $NEWSH_CONTENT,
										'NEWSD_CREATER'	=> $NEWSH_CREATER,
										'NEWSD_CREATED'	=> $NEWSH_CREATED,
										'NEWSD_STAT'	=> $NEWSH_STAT,
										'NEWSD_PATTNO'	=> $NEWSH_PATTNO);	
					$this->m_news->addD($InsNewsD);
				endforeach;
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_setting/c_news/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			
		
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function do_upload()
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			date_default_timezone_set("Asia/Jakarta");
			
			$NEWSH_CODE		= $this->input->post('NEWSH_CODE');
			$NEWSH_DATE		= date('Y-m-d',strtotime($this->input->post('NEWSH_DATE')));	
			$NEWS_RECTYPE	= $this->input->post('NEWS_RECTYPE');
			$NEWS_EXPIRED	= $this->input->post('NEWS_EXPIRED');
			$NEWSDT_EXPIRED	= date('Y-m-d',strtotime($this->input->post('NEWSDT_EXPIRED')));
			$NEWSH_TITLE	= $this->input->post('NEWSH_TITLE');
			$NEWSH_CONTENT	= $this->input->post('NEWSH_CONTENT');
			$NEWSD_URL		= $this->input->post('NEWSD_URL');
			$NEWSD_MAIL		= $this->input->post('NEWSD_MAIL');
			$NEWSD_TTD		= $this->input->post('NEWSD_TTD');
			$NEWSH_CREATER	= $this->input->post('NEWSH_CREATER');
			$NEWSH_CREATED	= date('Y-m-d H:i:s');
			$NEWSH_STAT		= $this->input->post('NEWSH_STAT');
			$NEWSH_PATTNO	= $this->input->post('NEWSH_PATTNO');
			$isTask			= $this->input->post('isTask');
			
			$file 			= $_FILES['userfile'];
			$file_name 		= $file['name'];
			
			$file1 			= $_FILES['userfile1'];
			$file_name1 	= $file1['name'];
			$file2 			= $_FILES['userfile2'];
			$file_name2 	= $file2['name'];
			$file3 			= $_FILES['userfile3'];
			$file_name3 	= $file3['name'];
			$file4 			= $_FILES['userfile4'];
			$file_name4 	= $file4['name'];
			
			if($isTask == "add")
			{
				// TITLE IMAGE
				if($file_name != '')
				{
					$file 						= $_FILES['userfile'];
					$file_name 					= $file['name'];
					$config['upload_path']   	= "assets/AdminLTE-2.0.5/dist/img/news"; 
					$config['allowed_types']	= 'gif|jpg|png'; 
					$config['overwrite'] 		= TRUE;
					$config['file_name']       	= $file['name'];
					$NEWSD_IMG					= $file_name;
			
					$this->load->library('upload', $config);
					
					if ( ! $this->upload->do_upload('userfile'))
					{
						echo "The pictures not uploaded.";
					}
					else 
					{
						$NEWSD_IMG	= $file_name;
					}
				}
				else
				{
					$NEWSD_IMG	= "defaultpict.jpg";
				}
					
				// IMAGE 1
				if($file_name1 != '')
				{
					$filename1 	= $_FILES["userfile1"]["name"];
					$source1 	= $_FILES["userfile1"]["tmp_name"];
					$type1 		= $_FILES["userfile1"]["type"];
					$NEWSD_IMG1	= $filename1;
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename1;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Image 1 uploaded.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload image 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
				else
				{
					$NEWSD_IMG1	= "no-image.png";
				}
					
				// IMAGE 2
				if($file_name2 != '')
				{
					$filename2 	= $_FILES["userfile2"]["name"];
					$source2 	= $_FILES["userfile2"]["tmp_name"];
					$type2 		= $_FILES["userfile2"]["type"];
					$NEWSD_IMG2	= $filename2;
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename2;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Image 2 uploaded.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload image 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
				else
				{
					$NEWSD_IMG2	= "no-image.png";
				}
					
				// IMAGE 3
				if($file_name3 != '')
				{
					$filename3 	= $_FILES["userfile3"]["name"];
					$source3 	= $_FILES["userfile3"]["tmp_name"];
					$type3 		= $_FILES["userfile3"]["type"];
					$NEWSD_IMG3	= $filename3;
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename3;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Image 3 uploaded.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload image 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
				else
				{
					$NEWSD_IMG3	= "no-image.png";
				}	
					
				// IMAGE 4		
				if($file_name4 != '')
				{
					$filename4 	= $_FILES["userfile4"]["name"];
					$source4 	= $_FILES["userfile4"]["tmp_name"];
					$type4 		= $_FILES["userfile4"]["type"];
					$NEWSD_IMG4	= $filename4;
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename4;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Image 4 uploaded.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload image 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				else
				{
					$NEWSD_IMG4	= "no-image.png";
				}
				
				// CREATE HEADER
					$therow			= 0;
					$RECEIVER1		= '';
					if($NEWS_RECTYPE == 1)
					{
						$RECEIVER1	= 'All';
					}
					elseif($NEWS_RECTYPE == 2)
					{
						$NEWSH_RECEIVERG= $this->input->post('NEWSH_RECEIVER');
						foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER1)
						{
							$therow		= $therow + 1;
							if($therow == 1)
							{
								$RECEIVER1	= $NEWSH_RECEIVER1;
							}
							else
							{
								$RECEIVER1	= "$RECEIVER1|$NEWSH_RECEIVER1";
							}				
						}
					}
					else
					{
						// FOR GROUPING RECEIVING BY PERSONAL
						$NEWSH_RECEIVERU 		= $this->input->post('NEWSH_RECEIVER');
						$selStep	= 0;
						foreach ($NEWSH_RECEIVERU as $sel_mail)
						{
							$selStep	= $selStep + 1;
							if($selStep == 1)
							{
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								$RECEIVER1	= $mail_ID;
								$RECEIVER2	= $mail_ADD;
							}
							else
							{					
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								
								$RECEIVER1	= "$RECEIVER1;$mail_ID";
								$RECEIVER2	= "$RECEIVER2;$mail_ADD";
							}
							
						}	
					}
					$str		 = 	$NEWSD_IMG;
					$NEWSD_IMG_R = str_replace(" ", "_", $str);
					$NEWSH_RECEIVERGH	= "$RECEIVER1";
					$InsNewsH	= array('NEWSH_CODE' 	=> $NEWSH_CODE,
										'NEWSH_DATE'	=> $NEWSH_DATE,
										'NEWS_RECTYPE'	=> $NEWS_RECTYPE,
										'NEWS_EXPIRED'	=> $NEWS_EXPIRED,
										'NEWSDT_EXPIRED'=> $NEWSDT_EXPIRED,
										'NEWSH_RECEIVER'=> $NEWSH_RECEIVERGH,
										'NEWSH_IMG'		=> $NEWSD_IMG_R,
										'NEWSH_TITLE'	=> $NEWSH_TITLE,
										'NEWSH_CONTENT'	=> $NEWSH_CONTENT,
										'NEWSH_CREATER'	=> $NEWSH_CREATER,
										'NEWSH_CREATED'	=> $NEWSH_CREATED,
										'NEWSH_STAT'	=> $NEWSH_STAT,
										'NEWSH_PATTNO'	=> $NEWSH_PATTNO);	
					$this->m_news->addH($InsNewsH);
				
				// CREATE DETAIL
					if($NEWS_RECTYPE == 1)
					{
						$RECEIVER1	= "All";		
						$InsNewsD	= array('NEWSD_CODE' 	=> $NEWSH_CODE,
											'NEWSD_DATE'	=> $NEWSH_DATE,
											'NEWSD_RECEIVER'=> $RECEIVER1,
											'NEWSD_TITLE'	=> $NEWSH_TITLE,
											'NEWSD_CONTENT'	=> $NEWSH_CONTENT,
											'NEWSD_CREATER'	=> $NEWSH_CREATER,
											'NEWSD_CREATED'	=> $NEWSH_CREATED,
											'NEWSD_STAT'	=> $NEWSH_STAT,
											'NEWSD_IMG'		=> $NEWSD_IMG,
											'NEWSD_IMG1'	=> $NEWSD_IMG1,
											'NEWSD_IMG2'	=> $NEWSD_IMG2,
											'NEWSD_IMG3'	=> $NEWSD_IMG3,
											'NEWSD_IMG4'	=> $NEWSD_IMG4,
											'NEWSD_PATTNO'	=> $NEWSH_PATTNO);	
						$this->m_news->addD($InsNewsD);
					}
					elseif($NEWS_RECTYPE == 2)
					{
						$NEWSH_RECEIVERG= $this->input->post('NEWSH_RECEIVER');
						foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER)
						{
							$sql_MGD	= "SELECT Emp_ID, Email FROM tbl_mailgroup_detail WHERE MG_CODE = '$NEWSH_RECEIVER'";
							$res_MGD	= $this->db->query($sql_MGD)->result();
							foreach($res_MGD as $rowMGD) :
								$Emp_IDN	= $rowMGD->Emp_ID;			
								$InsNewsD	= array('NEWSD_CODE' 	=> $NEWSH_CODE,
													'NEWSD_DATE'	=> $NEWSH_DATE,
													'NEWSD_RECEIVER'=> $Emp_IDN,
													'NEWSD_TITLE'	=> $NEWSH_TITLE,
													'NEWSD_CONTENT'	=> $NEWSH_CONTENT,
													'NEWSD_CREATER'	=> $NEWSH_CREATER,
													'NEWSD_CREATED'	=> $NEWSH_CREATED,
													'NEWSD_STAT'	=> $NEWSH_STAT,
													'NEWSD_IMG'		=> $NEWSD_IMG,
													'NEWSD_IMG1'	=> $NEWSD_IMG1,
													'NEWSD_IMG2'	=> $NEWSD_IMG2,
													'NEWSD_IMG3'	=> $NEWSD_IMG3,
													'NEWSD_IMG4'	=> $NEWSD_IMG4,
													'NEWSD_PATTNO'	=> $NEWSH_PATTNO);	
								$this->m_news->addD($InsNewsD);
							endforeach;
						}
					}
					else
					{
						$NEWSH_RECEIVERU 		= $this->input->post('NEWSH_RECEIVER');
						$selStep	= 0;
						foreach ($NEWSH_RECEIVERU as $sel_mail)
						{
							$selStep	= $selStep + 1;
							if($selStep == 1)
							{
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								$RECEIVER1	= $mail_ID;
								$RECEIVER2	= $mail_ADD;
							}
							else
							{					
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								
								$RECEIVER1	= "$RECEIVER1;$mail_ID";
								$RECEIVER2	= "$RECEIVER2;$mail_ADD";
							}
							
						}	
						$str		 = $NEWSD_IMG;
						$NEWSD_IMG_R  = str_replace(" ", "_", $str);
						
						$RECEIVERDT	= $RECEIVER1;	
						$InsNewsD	= array('NEWSD_CODE' 	=> $NEWSH_CODE,
											'NEWSD_DATE'	=> $NEWSH_DATE,
											'NEWSD_RECEIVER'=> $RECEIVERDT,
											'NEWSD_TITLE'	=> $NEWSH_TITLE,
											'NEWSD_CONTENT'	=> $NEWSH_CONTENT,
											'NEWSD_URL'		=> $NEWSD_URL,
											'NEWSD_MAIL'	=> $NEWSD_MAIL,
											'NEWSD_TTD'		=> $NEWSD_TTD,
											'NEWSD_CREATER'	=> $NEWSH_CREATER,
											'NEWSD_CREATED'	=> $NEWSH_CREATED,
											'NEWSD_STAT'	=> $NEWSH_STAT,
											'NEWSD_IMG'		=> $NEWSD_IMG_R,
											'NEWSD_IMG1'	=> $NEWSD_IMG1,
											'NEWSD_IMG2'	=> $NEWSD_IMG2,
											'NEWSD_IMG3'	=> $NEWSD_IMG3,
											'NEWSD_IMG4'	=> $NEWSD_IMG4,
											'NEWSD_PATTNO'	=> $NEWSH_PATTNO);	
						$this->m_news->addD($InsNewsD);
						
					}
			}
			else	// UPDATE
			{
				$NEWSD_IMG	= "defaultpict.jpg";
				$NEWSD_IMG1	= "no-image.png";
				$NEWSD_IMG2	= "no-image.png";
				$NEWSD_IMG3	= "no-image.png";
				$NEWSD_IMG4	= "no-image.png";
				
				$getIMG 	= $this->m_news->get_IMG($NEWSH_CODE)->row();			
				$NEWSD_IMG	= $getIMG->NEWSD_IMG;
				$NEWSD_IMG1	= $getIMG->NEWSD_IMG1;
				$NEWSD_IMG2	= $getIMG->NEWSD_IMG2;
				$NEWSD_IMG3	= $getIMG->NEWSD_IMG3;
				$NEWSD_IMG4	= $getIMG->NEWSD_IMG4;
					
				if($file_name != '')
				{
					$config['upload_path']   	= "assets/AdminLTE-2.0.5/dist/img/news"; 
					$config['allowed_types']	= 'gif|jpg|png'; 
					$config['overwrite'] 		= TRUE;
					$config['file_name']       	= $file['name'];
					$NEWSD_IMG					= $file_name;
			
					$this->load->library('upload', $config);
					
					if ( ! $this->upload->do_upload('userfile'))
					{
						echo "The pictures not uploaded.";
					}
					else 
					{
						$NEWSD_IMG	= $file_name;
					}
				}
					
				// IMAGE 1
				if($file_name1 != '')
				{
					$filename1 	= $_FILES["userfile1"]["name"];
					$source1 	= $_FILES["userfile1"]["tmp_name"];
					$type1 		= $_FILES["userfile1"]["type"];
					$NEWSD_IMG1	= $filename1;
					
					$name1 		= explode(".", $filename1);
					$fileExt1	= $name1[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename1;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename1";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source1, $target_path))
					{
						$message1 			= "Image 1 uploaded.";
						$data['message1'] 	= $message1;
					}
					else 
					{
						$message1			= "There was a problem with the upload image 1. Please try again.";
						$data['message1'] 	= $message1;
					}
				}
					
				// IMAGE 2
				if($file_name2 != '')
				{
					$filename2 	= $_FILES["userfile2"]["name"];
					$source2 	= $_FILES["userfile2"]["tmp_name"];
					$type2 		= $_FILES["userfile2"]["type"];
					$NEWSD_IMG2	= $filename2;
					
					$name2 		= explode(".", $filename2);
					$fileExt2	= $name2[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename2;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename2";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source2, $target_path))
					{
						$message2 			= "Image 2 uploaded.";
						$data['message2'] 	= $message2;
					}
					else 
					{
						$message2			= "There was a problem with the upload image 2. Please try again.";
						$data['message2'] 	= $message2;
					}
				}
					
				// IMAGE 3
				if($file_name3 != '')
				{
					$filename3 	= $_FILES["userfile3"]["name"];
					$source3 	= $_FILES["userfile3"]["tmp_name"];
					$type3 		= $_FILES["userfile3"]["type"];
					$NEWSD_IMG3	= $filename3;
					
					$name3 		= explode(".", $filename3);
					$fileExt3	= $name3[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename3;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename3";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source3, $target_path))
					{
						$message3 			= "Image 3 uploaded.";
						$data['message3'] 	= $message3;
					}
					else 
					{
						$message3			= "There was a problem with the upload image 3. Please try again.";
						$data['message3'] 	= $message3;
					}
				}
					
				// IMAGE 4		
				if($file_name4 != '')
				{
					$filename4 	= $_FILES["userfile4"]["name"];
					$source4 	= $_FILES["userfile4"]["tmp_name"];
					$type4 		= $_FILES["userfile4"]["type"];
					$NEWSD_IMG4	= $filename4;
					
					$name4 		= explode(".", $filename4);
					$fileExt4	= $name4[1];
					
					$target_path= "assets/AdminLTE-2.0.5/dist/img/news/".$filename4;  // change this to the correct site path
						
					$myPath 	= "assets/AdminLTE-2.0.5/dist/img/news/$filename4";
					
					if (file_exists($myPath) == true)
					{
						unlink($myPath);
					}
					
					if(move_uploaded_file($source4, $target_path))
					{
						$message4 			= "Image 4 uploaded.";
						$data['message4'] 	= $message4;
					}
					else 
					{
						$message4			= "There was a problem with the upload image 4. Please try again.";
						$data['message4'] 	= $message4;
					}
				}
				
				// UPDATE HEADER
					$therow			= 0;
					$RECEIVER1		= '';
					if($NEWS_RECTYPE == 1)
					{
						$RECEIVER1	= 'All';
					}
					elseif($NEWS_RECTYPE == 2)
					{
						foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER1)
						{
							$therow		= $therow + 1;
							if($therow == 1)
							{
								$RECEIVER1	= $NEWSH_RECEIVER1;
							}
							else
							{
								$RECEIVER1	= "$RECEIVER1|$NEWSH_RECEIVER1";
							}				
						}
					}
					else
					{
						$NEWSH_RECEIVERU 		= $this->input->post('NEWSH_RECEIVER');
						$selStep	= 0;
						foreach ($NEWSH_RECEIVERU as $sel_mail)
						{
							$selStep	= $selStep + 1;
							if($selStep == 1)
							{
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								$RECEIVER1	= $mail_ID;
								$RECEIVER2	= $mail_ADD;
							}
							else
							{					
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								
								$RECEIVER1	= "$RECEIVER1;$mail_ID";
								$RECEIVER2	= "$RECEIVER2;$mail_ADD";
							}
							
						}	
					}
					
					$str		 = $NEWSD_IMG;
					$NEWSD_IMG_R  = str_replace(" ", "_", $str);
					$NEWSH_RECEIVERGH	= "$RECEIVER1";
					$UpdNewsH	= array('NEWSH_CODE' 	=> $NEWSH_CODE,
										'NEWSH_DATE'	=> $NEWSH_DATE,
										'NEWS_EXPIRED'	=> $NEWS_EXPIRED,
										'NEWSDT_EXPIRED'=> $NEWSDT_EXPIRED,
										'NEWS_RECTYPE'	=> $NEWS_RECTYPE,
										'NEWSH_RECEIVER'=> $NEWSH_RECEIVERGH,
										'NEWSH_IMG'		=> $NEWSD_IMG_R,
										'NEWSH_TITLE'	=> $NEWSH_TITLE,
										'NEWSH_CONTENT'	=> $NEWSH_CONTENT,
										'NEWSH_CREATER'	=> $NEWSH_CREATER,
										'NEWSH_CREATED'	=> $NEWSH_CREATED,
										'NEWSH_STAT'	=> $NEWSH_STAT,
										'NEWSH_PATTNO'	=> $NEWSH_PATTNO);	
					$this->m_news->update($NEWSH_CODE, $UpdNewsH);
				
				// CREATE DETAIL
					// DELETE DETAIL
					$this->m_news->delDetail($NEWSH_CODE);
					
					// INSERT DETAIL AGAIN
					if($NEWS_RECTYPE == 1)
					{
						$RECEIVER1	= 'All';
					}
					elseif($NEWS_RECTYPE == 2)
					{
						$NEWSH_RECEIVERG= $this->input->post('NEWSH_RECEIVER');
						foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER)
						{
							$sql_MGD	= "SELECT Emp_ID, Email FROM tbl_mailgroup_detail WHERE MG_CODE = '$NEWSH_RECEIVER'";
							$res_MGD	= $this->db->query($sql_MGD)->result();
							foreach($res_MGD as $rowMGD) :
								$Emp_IDN	= $rowMGD->Emp_ID;
							endforeach;
						}
					}
					else
					{
						$NEWSH_RECEIVERU 		= $this->input->post('NEWSH_RECEIVER');
						$selStep	= 0;
						foreach ($NEWSH_RECEIVERU as $sel_mail)
						{
							$selStep	= $selStep + 1;
							if($selStep == 1)
							{
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								$RECEIVER1	= $mail_ID;
								$RECEIVER2	= $mail_ADD;
							}
							else
							{					
								$mail_to	= explode ("|",$sel_mail);
								$mail_ID	= $mail_to[0];
								$mail_ADD	= $mail_to[1];
								
								$RECEIVER1	= "$RECEIVER1;$mail_ID";
								$RECEIVER2	= "$RECEIVER2;$mail_ADD";
							}
							
						}	
					}
					
					$str		 = $NEWSD_IMG;
					$NEWSD_IMG_R   = str_replace(" ", "_", $str);
					
					$InsNewsD	= array('NEWSD_CODE' 	=> $NEWSH_CODE,
										'NEWSD_DATE'	=> $NEWSH_DATE,
										'NEWSD_RECEIVER'=> $RECEIVER1,
										'NEWSD_TITLE'	=> $NEWSH_TITLE,
										'NEWSD_CONTENT'	=> $NEWSH_CONTENT,
										'NEWSD_URL'		=> $NEWSD_URL,
										'NEWSD_MAIL'	=> $NEWSD_MAIL,
										'NEWSD_TTD'		=> $NEWSD_TTD,
										'NEWSD_CREATER'	=> $NEWSH_CREATER,
										'NEWSD_CREATED'	=> $NEWSH_CREATED,
										'NEWSD_STAT'	=> $NEWSH_STAT,
										'NEWSD_IMG'		=> $NEWSD_IMG_R,
										'NEWSD_IMG1'	=> $NEWSD_IMG1,
										'NEWSD_IMG2'	=> $NEWSD_IMG2,
										'NEWSD_IMG3'	=> $NEWSD_IMG3,
										'NEWSD_IMG4'	=> $NEWSD_IMG4,
										'NEWSD_PATTNO'	=> $NEWSH_PATTNO);	
					$this->m_news->addD($InsNewsD);
			}
			
			$url			= site_url('c_setting/c_news/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			
			//$url2			= site_url('c_setting/c_news/view_news/?id='.$this->url_encryption_helper->encode_url($NEWSH_CODE));
			$url2			= 'http://nke.co.id/';
			
			if($NEWS_RECTYPE == 1)
			{
				$RECEIVER1	= 'All';
				$sqlMail	= "SELECT Emp_ID, First_Name, Email FROM tbl_employee WHERE ENews = '1' ";
					$resMail = $this->db->query($sqlMail)->result();
					$selStep	= 0;
					foreach($resMail as $rowM) :
						$selStep		= $selStep + 1;
						$Emp_ID 		= $rowM->Emp_ID;
						$First_Name 	= $rowM->First_Name;
						$Email 			= $rowM->Email;
						if($selStep == 1)
						{
							$RECEIVER1	= $Emp_ID;
							$RECEIVER2	= $Email;
						}
						else
						{					
							$RECEIVER1	= "$RECEIVER1;$Emp_ID";
							$RECEIVER2	= "$RECEIVER2;$Email";
						}
					endforeach;
			}
			elseif($NEWS_RECTYPE == 2)
			{
				$NEWSH_RECEIVERG= $this->input->post('NEWSH_RECEIVER');
				foreach ($NEWSH_RECEIVERG as $NEWSH_RECEIVER1)
				{
					$therow		= $therow + 1;
					if($therow == 1)
					{
						$RECEIVER1	= $NEWSH_RECEIVER1;
					}
					else
					{
						$RECEIVER1	= "$RECEIVER1|$NEWSH_RECEIVER1";
					}				
				}
			}
			else
			{
				$NEWSH_RECEIVERU 		= $this->input->post('NEWSH_RECEIVER');
				$selStep	= 0;
				foreach ($NEWSH_RECEIVERU as $sel_mail)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$mail_to	= explode ("|",$sel_mail);
						$mail_ID	= $mail_to[0];
						$mail_ADD	= $mail_to[1];
						$RECEIVER1	= $mail_ID;
						$RECEIVER2	= $mail_ADD;
					}
					else
					{					
						$mail_to	= explode ("|",$sel_mail);
						$mail_ID	= $mail_to[0];
						$mail_ADD	= $mail_to[1];
						
						$RECEIVER1	= "$RECEIVER1;$mail_ID";
						$RECEIVER2	= "$RECEIVER2;$mail_ADD";
					}
					
				}
			}
			
			// SENT MAIL
				$toMail		= ''.$RECEIVER2.'';
				$headers 	= 'MIME-Version: 1.0' . "\r\n";
				$headers 	.= 'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
				$headers 	.= "From: Admin <admin.nke@nusakonstruksi.com>\r\n";
				$subject 	= "E-News";
				$output		= '';
				$output		.= '<table width="100%" border="0">
									<tr>
										<td colspan="3" style="text-align:center; text-decoration:underline">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3">'.$NEWSD_MAIL.'</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="89%" colspan="2">'.$url2.'</td>
									</tr>
									
									<tr>
										<td width="2%" style="vertical-align:top">&nbsp;</td>
										<td width="9%">&nbsp;</td>
										<td width="89%">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Demikian informasi ini kami sampaikan.</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">Hormat kami,</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">'.$NEWSD_TTD.'</td>
									</tr>
									<tr>
										<td style="vertical-align:top">&nbsp;</td>
										<td colspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="3" style="vertical-align:top">&nbsp;</td>
									</tr>';
				$output		.= '</table>';
				//echo $output;
				//return false;
				//send email
				@mail($toMail, $subject, $output, $headers);
				
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
					
			redirect($url);
			
				
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$NEWSH_CODE	= $_GET['id'];
		$NEWSH_CODE	= $this->url_encryption_helper->decode_url($NEWSH_CODE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Update News';
			$data['main_view'] 		= 'v_setting/v_news/v_news_form';
			$data['form_action']	= site_url('c_setting/c_news/do_upload');
			//$data['link'] 			= array('link_back' => anchor('c_setting/c_news/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_setting/c_news/');
			$data["MenuCode"] 		= 'MN307';
			
			$getdocapproval = $this->m_news->get_news($NEWSH_CODE)->row();
			
			$data['default']['NEWSH_CODE'] 		= $getdocapproval->NEWSH_CODE;
			$data['default']['NEWSH_DATE']		= $getdocapproval->NEWSH_DATE;
			$data['default']['NEWS_RECTYPE']	= $getdocapproval->NEWS_RECTYPE;
			$data['default']['NEWS_EXPIRED']	= $getdocapproval->NEWS_EXPIRED;
			$data['default']['NEWSDT_EXPIRED']	= $getdocapproval->NEWSDT_EXPIRED;
			$data['default']['NEWSH_RECEIVER'] 	= $getdocapproval->NEWSH_RECEIVER;
			$data['default']['NEWSH_TITLE'] 	= $getdocapproval->NEWSH_TITLE;
			$data['default']['NEWSH_CONTENT'] 	= $getdocapproval->NEWSH_CONTENT;
			$data['default']['NEWSD_MAIL'] 		= $getdocapproval->NEWSD_MAIL;
			$data['default']['NEWSD_URL'] 		= $getdocapproval->NEWSD_URL;
			$data['default']['NEWSD_TTD']		= $getdocapproval->NEWSD_TTD;;
			$data['default']['NEWSH_CREATER'] 	= $getdocapproval->NEWSH_CREATER;
			$data['default']['NEWSH_CREATED'] 	= $getdocapproval->NEWSH_CREATED;
			$data['default']['NEWSH_STAT'] 		= $getdocapproval->NEWSH_STAT;
			$data['default']['NEWSH_CONTENT'] 	= $getdocapproval->NEWSH_CONTENT;
			$data['default']['NEWSH_PATTNO'] 	= $getdocapproval->NEWSH_PATTNO;
			
			$this->load->view('v_setting/v_news/v_news_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function view_news()
	{
		//if ($this->session->userdata('login') == TRUE)
		//{
			$this->load->model('m_setting/m_news/m_news', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			
			$NEWSD_ID		= $_GET['id'];
			$NEWSD_ID		= $this->url_encryption_helper->decode_url($NEWSD_ID);
			
			$NEWSD_CODE		= $_GET['id'];
			$NEWSD_CODE		= $this->url_encryption_helper->decode_url($NEWSD_CODE);
						
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Topic';
			$data['h3_title'] 	= 'news';
			$data['NEWSD_ID'] 	= $NEWSD_ID;
			$data['NEWSD_CODE'] = $NEWSD_CODE;
			
			$data['viewNews'] 	= $this->m_news->viewNews($NEWSD_ID)->result();
			
			//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
			//if($DefEmp_ID == 'D15040004221' or $DefEmp_ID == 'H17050004765')
			//{
				$this->load->view('v_setting/v_news/v_news_read_adm', $data);
			//}
			//else
			//{
				//$this->load->view('v_setting/v_news/v_news_read', $data);
			//}
		//}
		//else
		//{
		//	redirect('Auth');
		//}
	}
	
	function list_news_all($offset=0)
	{
		$this->load->model('m_setting/m_news/m_news', '', TRUE);
		$Emp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
				
			$data['title'] 		= $appName;
			$data['h1_title']	= 'news';
			$data['h2_title']	= 'News List';
			$data['h3_title']	= 'News';			
			$data["countNews"]	= $this->m_news->count_allnews();	 
			$data['vwNewsAll'] 	= $this->m_news->get_allnews()->result();	
			
			$this->load->view('v_setting/v_news/v_news_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function list_view()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_setting/m_news/m_news', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			
			$NEWSH_CODE		= $_GET['id'];
			$NEWSH_CODE		= $this->url_encryption_helper->decode_url($NEWSH_CODE);
			
			$data['title'] 		= $appName;
			$data['h2_title'] 	= 'Topic';
			$data['h3_title'] 	= 'news';
			$data['NEWSH_CODE'] = $NEWSH_CODE;
			
			$data['viewNews'] 	= $this->m_news->viewNews_list($NEWSH_CODE)->result();
					
			$this->load->view('v_setting/v_news/v_news_read_list', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
}