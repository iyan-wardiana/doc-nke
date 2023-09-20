<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2018
 * File Name	= C_t180c2hr
 * Function		= -
*/

class C_t180c2hr extends CI_Controller
{
	function __construct() // GOOD
	{
		parent::__construct();
		
		$this->load->model('m_setting/m_menu/m_menu', '', TRUE);
		$this->load->model('m_updash/m_updash', '', TRUE);
	
		$this->data['UserID'] 		= $this->session->userdata['Emp_ID'];
		$this->data['appName'] 		= $this->session->userdata['appName'];
		$this->data['ISCREATE'] 	= $this->session->userdata['ISCREATE'];
		$this->data['ISAPPROVE'] 	= $this->session->userdata['ISAPPROVE'];
		$this->data['LangID']		= $this->session->userdata['LangID'];
		
		function cut_text2($var, $len = 200, $txt_titik = "-") 
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
	}
	
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_t180c2hr/ts180c2hdx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function ts180c2hdx()
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Permintaan';
				$data['h2_title']	= 'Bantuan';
				$data['h3_title']	= 'Database';
			}
			else
			{
				$data['h1_title']	= 'Request';
				$data['h2_title']	= 'Assistance';
				$data['h3_title']	= 'Database';
			}
			
			$data['secAddURL'] 	= site_url('c_help/c_t180c2hr/a180c2hdd/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data["countTask"] 	= $this->m_task_request->count_all_task($DefEmp_ID);	 
			$data['vwTask'] 	= $this->m_task_request->view_all_task($DefEmp_ID)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function a180c2hdd() // OK
	{	
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Buat Permintaan';
				$data['h2_title']	= 'Bantuan';
			}
			else
			{
				$data['h1_title']	= 'Add Request';
				$data['h2_title']	= 'Assistance';
			}
			
			$data['MenuParentC'] 	= $this->m_task_request->getCount_menu();		
			$data['MenuParent'] 	= $this->m_task_request->get_menu()->result();
			
			$data['form_action']	= site_url('c_help/c_t180c2hr/add_process');
			$data['link'] 			= array('link_back' => anchor('c_help/c_t180c2hr/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_help/c_t180c2hr/');
			
			$MenuCode 				= 'MN208';
			$data['viewDocPattern'] = $this->m_task_request->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request_form', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_help/m_task_request', '', TRUE);
		$comp_init	= $this->session->userdata('comp_init');
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		date_default_timezone_set("Asia/Jakarta");
		
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$TASK_DATE	= date('Y-m-d',strtotime($this->input->post('TASK_DATE')));
		$Patt_Year	= date('Y',strtotime($this->input->post('TASK_DATE')));
		$Patt_Month	= date('m',strtotime($this->input->post('TASK_DATE')));
		$Patt_Date	= date('d',strtotime($this->input->post('TASK_DATE')));
		
		$TASK_CODE		= $comp_init.".".date('YmdHis');
		$TASK_TYPE		= $this->input->post('TASK_TYPE');
		
		if($TASK_TYPE == '')
			$TASK_TYPE = 0;
			
		$TASK_AUTHOR	= $this->input->post('TASK_AUTHOR');
		$selStepA	= 0;
		foreach ($TASK_AUTHOR as $sel_usersA)
		{
			$selStepA	= $selStepA + 1;
			if($selStepA == 1)
			{
				$user_toA		= explode ("|",$sel_usersA);
				$user_IDA		= $user_toA[0];
				//$user_ADDA		= $user_toA[1];
				$TASKD_EMPID2A	= $user_IDA;
			}
			else
			{					
				$user_toA		= explode ("|",$sel_usersA);
				$user_IDA		= $user_toA[0];
				//$user_ADDA		= $user_toA[1];
				
				$TASKD_EMPID2A	= "$TASKD_EMPID2A;$user_IDA";
			}
		}
		$TASK_AUTHOR			= $TASKD_EMPID2A;
					
		$TASK_REQUESTER	= $this->input->post('TASK_REQUESTER');
		$TASK_FOR		= $this->input->post('TASK_FOR');
		
		if($TASK_TYPE > 0) // this is concultant information for users
		{
			$TASK_AUTHOR	= $DefEmp_ID;
		}
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$file 				= $_FILES['userfile'];
			$file_name 			= $file['name'];
			
			$fileName1	= str_replace(" ","_", $file_name);
			$fileName	= str_replace(" ","_", $fileName1);
			
			$TASK_MENU	= $this->input->post('TASK_MENU');
			$MENU_NAME	= 'none';
			$sqlMN 		= "SELECT menu_name_IND FROM tbl_menu WHERE menu_code = '$TASK_MENU'";
			$resMN 		= $this->db->query($sqlMN)->result();
			foreach($resMN as $rowMN) :
				$MENU_NAME = $rowMN->menu_name_IND;		
			endforeach;	
			
			// CREATE HEADER
			$InsTR 		= array('TASK_CODE' 	=> $this->input->post('TASK_CODE'),
								'TASK_DATE'		=> $TASK_DATE,
								'TASK_TITLE'	=> addslashes($this->input->post('TASK_TITLE')),
								'TASK_MENU'		=> $this->input->post('TASK_MENU'),
								'TASK_MENUNM'	=> $MENU_NAME,
								'TASK_TYPE'		=> $this->input->post('TASK_TYPE'),
								'TASK_AUTHOR'	=> $TASK_AUTHOR,
								'TASK_REQUESTER'=> $this->input->post('TASK_REQUESTER'),
								'TASK_STAT'		=> $this->input->post('TASK_STAT'),
								'TASK_CREATED'	=> date('Y-m-d H:i:s'),
								'Patt_Year'		=> $Patt_Year,
								'Patt_Month'	=> $Patt_Month,
								'Patt_Date'		=> $Patt_Date,
								'Patt_Number'	=> $this->input->post('Patt_Number'));												
			$this->m_task_request->add($InsTR);
			
			if($TASK_TYPE == 0)	// From user to author
			{
				// CREATE DETAIL
				// Karena $TASK_AUTHOR = "All", maka cari salah  satu author dari detail
				
				// ------------------ START : SEMENTARA DITUTUP AGAR TIDAK AUTO KE HELPER, AGAR BISA KE SEMUA TIM USER				
					/*$getC1	= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER'";
					$resC1	= $this->db->count_all($getC1);
					if($resC1 > 0)
					{
						$getID1		= "SELECT TASKD_EMPID
										FROM tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER' LIMIT 1";
						$resID1		= $this->db->query($getID1)->result();
						foreach($resID1 as $rowID1) :
							$TASKD_EMPID2 	= $rowID1->TASKD_EMPID;
						endforeach;
					}
					else
					{
						$myrow		= 0;
						$getAuthID	= "SELECT Emp_ID FROM tbl_employee WHERE isHelper = 1";
						$resAuthID	= $this->db->query($getAuthID)->result();
						foreach($resAuthID as $rowAuthID) :
							$myrow	= $myrow + 1;
							$Emp_ID 	= $rowAuthID->Emp_ID;
							if($myrow == 1)
							{
								$TASKD_EMPID2	= "$Emp_ID";
							}
							if($myrow > 1)
							{
								$TASKD_EMPID2	= "$TASKD_EMPID2;$Emp_ID";
							}
						endforeach;
					}*/
				// ------------------ END : SEMENTARA DITUTUP AGAR TIDAK AUTO KE HELPER, AGAR BISA KE SEMUA TIM USER
				
				$TASKD_EMPID2	= $TASK_AUTHOR;
				/*$InsTRD		= array('TASKD_PARENT' 		=> $this->input->post('TASK_CODE'),
									'TASKD_TITLE'		=> $this->input->post('TASK_TITLE'),
									'TASKD_CONTENT'		=> $this->input->post('TASK_CONTENT'),
									'TASKD_FILENAME'	=> $fileName,
									'TASKD_DATE'		=> date('Y-m-d'),
									'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
									'TASKD_EMPID'		=> $DefEmp_ID,
									'TASKD_EMPID2'		=> $TASKD_EMPID2,
									'TASKD_EMPID'		=> $DefEmp_ID);													
				$this->m_task_request->addDet($InsTRD);*/
			}
			elseif($TASK_TYPE == 1)	// From user to author
			{
				if($TASK_TYPE == 1)
				{
					$TASKD_EMPID2	= "All";
				}
			}
			elseif($TASK_TYPE == 2)
			{
				// FOR GROUPING RECEIVING BY PERSONAL
				$selStep	= 0;
				foreach ($TASK_FOR as $sel_users)
				{
					$selStep	= $selStep + 1;
					if($selStep == 1)
					{
						$user_to		= explode ("|",$sel_users);
						$user_ID		= $user_to[0];
						$user_ADD		= $user_to[1];
						$TASKD_EMPID2	= $user_ID;
						//$coll_MADD	= $user_ADD;
						//echo "1. TASKD_EMPID2 = $TASKD_EMPID2<br>";
					}
					else
					{					
						$user_to		= explode ("|",$sel_users);
						$user_ID		= $user_to[0];
						$user_ADD		= $user_to[1];
						
						$TASKD_EMPID2	= "$TASKD_EMPID2;$user_ID";
						//$coll_MADD	= "$coll_MADD;$user_ADD";
						//echo "2. TASKD_EMPID2 = $TASKD_EMPID2<br>";
					}
				}
				//$TASKD_EMPID2 = $TASK_AUTHOR;
			}
			
			$InsTRD		= array('TASKD_PARENT' 		=> $this->input->post('TASK_CODE'),
								'TASKD_TITLE'		=> addslashes($this->input->post('TASK_TITLE')),
								'TASKD_CONTENT'		=> addslashes($this->input->post('TASK_CONTENT')),
								'TASKD_FILENAME'	=> $fileName,
								'TASKD_DATE'		=> date('Y-m-d'),
								'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
								'TASKD_EMPID'		=> $DefEmp_ID,
								'TASKD_EMPID2'		=> $TASKD_EMPID2,
								'TASKD_EMPID'		=> $DefEmp_ID);
												
			$this->m_task_request->addDet($InsTRD);
			
			if($file_name != '')
			{
				$info 	= $_FILES['userfile']['name'];
				$ext 	= pathinfo($info, PATHINFO_EXTENSION);
				
				if($ext == 'rar' || $ext == 'zip')
				{
					$filename 	= $_FILES['userfile']['name'];
					$source 	= $_FILES['userfile']['tmp_name'];
					$type 		= $_FILES['userfile']['type'];
					
					$name 		= explode('.', $filename);
					$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
					foreach($accepted_types as $mime_type) 
					{
						if($mime_type == $type) 
						{
							$okay = true;
							break;
						} 
					}
					
					$continue = strtolower($name[1]) == 'zip' ? true : false;
					if(!$continue)
					{
						$message = "The file you are trying to upload is not a .zip file. Please try again.";
					}
					
					$target_path = "assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/".$filename;  // change this to the correct site path
					if(move_uploaded_file($source, $target_path))
					{
						$zip = new ZipArchive();
						$x = $zip->open($target_path);
						if ($x === true) 
						{
							$zip->extractTo("assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/");
							$zip->close();
					
							unlink($target_path);
						}
						//$message 	= "Your .zip file was uploaded and unpacked.";
						$success	= 1;
					} 
					else 
					{	
						$message 	= "There was a problem with the upload. Please try again.";
						$success	= 0;
					}
				}
				else
				{
					$file 						= $_FILES['userfile'];
					$file_name 					= $file['name'];
					$config['upload_path']   	= "assets/AdminLTE-2.0.5/doc_center/uploads/HelpDesk/"; 
					$config['allowed_types']	= 'pdf|gif|jpg|png'; 
					$config['overwrite'] 		= TRUE;
					//$config['max_size']     	= 1000000; 
					//$config['max_width']    	= 10024; 
					//$config['max_height']    	= 10000;  
					$config['file_name']       	= $file['name'];
			
					$this->load->library('upload', $config);
					
					$this->upload->do_upload('userfile');
				}
			}
			
			// UPDATE NEW
			$UPD_HD_A	= "UPDATE tbl_task_request SET TASK_TO = '$TASKD_EMPID2' WHERE TASK_CODE = '$TASK_CODE'";
			$this->db->query($UPD_HD_A);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_help/c_t180c2hr/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function t180c2htread() // OK
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_help/m_task_request', '', TRUE);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName 	= $therow->app_name;		
			endforeach;
			
			$TASK_CODE		= $_GET['id'];
			//$TASK_CODE		= $this->url_encryption_helper->decode_url($TASK_CODE);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Task View';
			$data['h3_title']	= 'help';
			
			$LangID 	= $this->session->userdata['LangID'];
			if($LangID == 'IND')
			{
				$data['h1_title']	= 'Tinjau Permintaan';
				$data['h2_title']	= 'Bantuan';
			}
			else
			{
				$data['h1_title']	= 'View Request';
				$data['h2_title']	= 'Assistance';
			}
			
			$data['TASK_CODE'] 	= $TASK_CODE;
			$data['link'] 		= array('link_back' => anchor('c_help/c_t180c2hr/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$this->load->view('v_help/v_task_request/v_task_request_read', $data);
		}
		else
		{
			redirect('__I1y');
		}
	}
	
	function downloadFile()
	{
		$this->load->helper('download');

		$collLink	= $_GET['id'];
		$collLink	= $this->url_encryption_helper->decode_url($collLink);
		$collLink1	= explode('~', $collLink);
		$theLink	= $collLink1[0];
		$FileUpName	= $collLink1[1];
		//echo $theLink;
		header("Content-Type: text/plain; charset=utf-8");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".$FileUpName);
	}
	
	function upd_readstat() // OK
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		$TASKD_ID	= $_GET['id'];
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$this->m_task_request->UpdateOriginal($TASKD_ID);
		}
		else
		{
			redirect('__I1y');
		}
	}
}
?>