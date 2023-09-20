<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 Agustus 2017
 * File Name	= C_task_request
 * Function		= -
*/

class C_task_request extends CI_Controller
{
 	function index()
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_help/c_task_request/task_request_idx/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function task_request_idx()
	{
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 		= $appName;
			$data['h1_title']	= 'task';
			$data['h2_title']	= 'task request';
			$data['h3_title']	= 'Database';
			$data['secAddURL'] 	= site_url('c_help/c_task_request/add/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$data["countTask"] 	= $this->m_task_request->count_all_task($DefEmp_ID);	 
			$data['vwTask'] 	= $this->m_task_request->view_all_task($DefEmp_ID)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add() // OK
	{	
		$this->load->model('m_help/m_task_request', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Task';
			$data['h3_title']			= 'help';
			$data['form_action']		= site_url('c_help/c_task_request/add_process');
			$data['link'] 				= array('link_back' => anchor('c_help/c_task_request/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('c_help/c_task_request/');
			
			$MenuCode 					= 'MN208';
			$data['viewDocPattern'] 	= $this->m_task_request->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_help/v_task_request/v_task_request_form', $data);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_help/m_task_request', '', TRUE);
		
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
		
		$TASK_CODE		= $this->input->post('TASK_CODE');
		$TASK_MENU		= $this->input->post('TASK_MENU');
		$TASK_TYPE		= $this->input->post('TASK_TYPE');
		if($TASK_TYPE == '')
			$TASK_TYPE = 0;
		$TASK_AUTHOR	= $this->input->post('TASK_AUTHOR');
		$TASK_REQUESTER	= $this->input->post('TASK_REQUESTER');
		$TASK_FOR		= $this->input->post('TASK_FOR');
		
		$mnDesc			= 'none';
		$sqlmnD 		= "SELECT menu_name_$LangID AS menuName FROM tbl_menu WHERE menu_code = '$TASK_MENU'";
		$resmnD			= $this->db->query($sqlmnD)->result();
		foreach($resmnD as $rowmnD) :
			$mnDesc		= $rowmnD->menuName;
		endforeach;
		
		if($TASK_TYPE > 0) // this is concultant information for users
		{
			$TASK_AUTHOR	= $DefEmp_ID;
		}
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			// CREATE HEADER
			$InsTR 		= array('TASK_CODE' 	=> $this->input->post('TASK_CODE'),
								'TASK_DATE'		=> $TASK_DATE,
								'TASK_MENU'		=> $TASK_MENU,
								'TASK_MENUNM'	=> $mnDesc,
								'TASK_TITLE'	=> $this->input->post('TASK_TITLE'),
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
				$getC1	= "tbl_task_request_detail WHERE TASKD_PARENT = '$TASK_CODE' AND TASKD_EMPID != '$TASK_REQUESTER'";
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
				}
				$InsTRD		= array('TASKD_PARENT' 		=> $this->input->post('TASK_CODE'),
									'TASKD_TITLE'		=> $this->input->post('TASK_TITLE'),
									'TASKD_CONTENT'		=> $this->input->post('TASK_CONTENT'),
									'TASKD_DATE'		=> date('Y-m-d'),
									'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
									'TASKD_EMPID'		=> $DefEmp_ID,
									'TASKD_EMPID2'		=> $TASKD_EMPID2,
									'TASKD_EMPID'		=> $DefEmp_ID);
													
				$this->m_task_request->addDet($InsTRD);
			}
			else // this is concultant information for users
			{
				if($TASK_TYPE == 1)
				{
					$TASKD_EMPID2	= "All";
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
						}
						else
						{					
							$user_to		= explode ("|",$sel_users);
							$user_ID		= $user_to[0];
							$user_ADD		= $user_to[1];
							
							$TASKD_EMPID2	= "$TASKD_EMPID2;$user_ID";
							//$coll_MADD	= "$coll_MADD;$user_ADD";
						}
					}
				}
				
				$InsTRD		= array('TASKD_PARENT' 		=> $this->input->post('TASK_CODE'),
									'TASKD_TITLE'		=> $this->input->post('TASK_TITLE'),
									'TASKD_CONTENT'		=> $this->input->post('TASK_CONTENT'),
									'TASKD_DATE'		=> date('Y-m-d'),
									'TASKD_CREATED'		=> date('Y-m-d H:i:s'),
									'TASKD_EMPID'		=> $DefEmp_ID,
									'TASKD_EMPID2'		=> $TASKD_EMPID2,
									'TASKD_EMPID'		=> $DefEmp_ID);
													
				$this->m_task_request->addDet($InsTRD);
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
			
			$url			= site_url('c_help/c_task_request/index/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('__l1y');
		}
	}
	
	function task_read() // OK
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
			$TASK_CODE		= $this->url_encryption_helper->decode_url($TASK_CODE);
			
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Task View';
			$data['h3_title']	= 'help';
			$data['TASK_CODE'] 	= $TASK_CODE;
			$data['link'] 		= array('link_back' => anchor('c_help/c_task_request/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
					
			$this->load->view('v_help/v_task_request/v_task_request_read', $data);
		}
		else
		{
			redirect('__l1y');
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
			redirect('__l1y');
		}
	}
}
?>