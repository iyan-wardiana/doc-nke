<?php
/*  
 * Author		= Dian Hermanto
 * Create Date	= 25 Januari 2018
 * File Name	= C_docapproval.php
 * Location		= -
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_docapproval  extends CI_Controller
{	
	var $limit = 2;
	var $title = 'NKE ITSys';
	
 	// Start : Index tiap halaman
 	public function index()
	{		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_setting/C_docapproval/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Document Approval';
			$data["MenuCode"] 			= 'MN076';
			
			$num_rows 					= $this->m_docapproval->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
			
			// Start of Pagination
			$config 					= array();
			$config['base_url'] 		= site_url('c_setting/c_docapproval/get_last_ten_docapproval');	
			$config["total_rows"] 		= $num_rows;
			$config["per_page"] 		= 20;
			$config["uri_segment"]		= 4;
			$config['cur_page'] 		= $offset;			
			$config['full_tag_open'] 	= '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] 	= '</ul>';
			$config['prev_link'] 		= '&lt;';
			$config['prev_tag_open']	= '<li>';
			$config['prev_tag_close'] 	= '</li>';
			$config['next_link'] 		= '&gt;';
			$config['next_tag_open'] 	= '<li>';
			$config['next_tag_close'] 	= '</li>';
			$config['cur_tag_open'] 	= '<li class="current"><a href="#">';
			$config['cur_tag_close'] 	= '</a></li>';
			$config['num_tag_open'] 	= '<li>';
			$config['num_tag_close'] 	= '</li>';			
			$config['first_tag_open'] 	= '<li>';
			$config['first_tag_close'] 	= '</li>';
			$config['last_tag_open'] 	= '<li>';
			$config['last_tag_close'] 	= '</li>';			
			$config['first_link'] 		= '&lt;&lt;';
			$config['last_link'] 		= '&gt;&gt;';
			$this->pagination->initialize($config);
			// End of Pagination
	 
			$data['viewdocapproval'] 	= $this->m_docapproval->get_last_ten_docapproval($config["per_page"], $offset)->result();
			$data["pagination"] 		= $this->pagination->create_links();
			
			$this->load->view('v_setting/v_docapproval/docapproval', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End
	
	function add()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);

		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
				
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['form_action']	= site_url('c_setting/c_docapproval/add_process');
			$data['link'] 			= array('link_back' => anchor('c_setting/c_docapproval/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-primary" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN076';

			$data['countParent'] 	= $this->m_position_str->count_all();		
			$data['vwParent'] 		= $this->m_position_str->get_position_str_prn()->result();
			
			$this->load->view('v_setting/v_docapproval/docapproval_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$LangID 		= $this->session->userdata['LangID'];
			$MENU_NAME		= '';
			$MENU_CODE		= $this->input->post('MENU_CODE');
			$POSCODE		= $this->input->post('POSCODE');
			$sqlGetMENU		= "SELECT menu_name_$LangID AS menu_name FROM tbl_menu WHERE MENU_CODE = '$MENU_CODE'";
			$resGetMENU		= $this->db->query($sqlGetMENU)->result();
			foreach($resGetMENU as $rowMENU) :		
				$MENU_NAME	= $rowMENU->menu_name;
			endforeach;
			
			$APPROVER_1		= $this->input->post('APPROVER_1');
			$APPROVER_2		= $this->input->post('APPROVER_2');
			$APPROVER_3		= $this->input->post('APPROVER_3');
			$APPROVER_4		= $this->input->post('APPROVER_4');
			$APPROVER_5		= $this->input->post('APPROVER_5');
			$PRJCODE		= $this->input->post('PRJCODE');
			
			if($APPROVER_5 != '')
				$MAX_STEP = 5;
			elseif($APPROVER_4 != '')
				$MAX_STEP = 4;
			elseif($APPROVER_3 != '')
				$MAX_STEP = 3;
			elseif($APPROVER_2 != '')
				$MAX_STEP = 2;
			elseif($APPROVER_1 != '')
				$MAX_STEP = 1;
			
			$num_rows 	= $this->m_docapproval->count_all_num_rows();
			$DOCCODE	= $num_rows + 1;
			$InsDocApp 	= array('DOCCODE'		=> $DOCCODE,
								'PRJCODE' 		=> $PRJCODE,
								'DOCAPP_NAME' 	=> $MENU_NAME,
								'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
								'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
								'POSCODE' 		=> $this->input->post('POSCODE'),
								'APPROVER_1'	=> $this->input->post('APPROVER_1'),
								'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
								'APPROVER_2'	=> $this->input->post('APPROVER_2'),
								'APPLIMIT_2'	=> $this->input->post('APPLIMIT_2'),
								'APPROVER_3'	=> $this->input->post('APPROVER_3'),
								'APPLIMIT_3'	=> $this->input->post('APPLIMIT_3'),
								'APPROVER_4'	=> $this->input->post('APPROVER_4'),
								'APPLIMIT_4'	=> $this->input->post('APPLIMIT_4'),
								'APPROVER_5'	=> $this->input->post('APPROVER_5'),
								'APPLIMIT_5'	=> $this->input->post('APPLIMIT_5'),
								'MAX_STEP'		=> $MAX_STEP,
								'CREATED_BY'	=> $this->input->post('CREATED_BY'));	
			$this->m_docapproval->add($InsDocApp);
			
			// START : Create to detail
				if($APPROVER_1 != '')
				{
					$MAX_STEP		= 1;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $POSCODE,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_1'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
											'APP_STEP'		=> 1);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 1);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_2 != '')
				{
					$MAX_STEP		= 2;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_2'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_2'),
											'APP_STEP'		=> 2);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 2);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_3 != '')
				{
					$MAX_STEP		= 3;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_3'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_3'),
											'APP_STEP'		=> 3);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 3);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_4 != '')
				{
					$MAX_STEP		= 4;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_4'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_4'),
											'APP_STEP'		=> 4);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 4);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
				if($APPROVER_5 != '')
				{
					$MAX_STEP		= 5;
					$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
											'PRJCODE' 		=> $PRJCODE,
											'DOCAPP_NAME' 	=> $MENU_NAME,
											'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
											'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
											'POSCODE' 		=> $this->input->post('POSCODE'),
											'APPROVER_1'	=> $this->input->post('APPROVER_5'),
											'APPLIMIT_1'	=> $this->input->post('APPLIMIT_5'),
											'APP_STEP'		=> 5);	
					$this->m_docapproval->add1($InsDocAppDet);
					
					$UpdDocAppDet 	= array('MAX_STEP' 		=> 5);	
					$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
				}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_docapproval/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function update()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		$this->load->model('m_hr/m_organiz/m_position_str', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp 	= $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName 	= $therow->app_name;		
		endforeach;
		
		$DOCAPP_ID	= $_GET['id'];
		$DOCAPP_ID	= $this->url_encryption_helper->decode_url($DOCAPP_ID);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$data['title'] 				= $appName;
			$data['task'] 				= 'edit';
			$data['h2_title'] 			= 'Document Approval | Edit Document Approval';
			$data['form_action']		= site_url('c_setting/c_docapproval/update_process');
			$data['link'] 				= array('link_back' => anchor('c_setting/c_docapproval/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="btn btn-primary" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 			= 'MN076';

			$data['countParent'] 	= $this->m_position_str->count_all();		
			$data['vwParent'] 		= $this->m_position_str->get_position_str_prn()->result();
			
			$getdocapproval = $this->m_docapproval->get_docstep_by_code($DOCAPP_ID)->row();
	
			$data['default']['DOCAPP_ID'] 	= $getdocapproval->DOCAPP_ID;
			$data['default']['DOCCODE'] 	= $getdocapproval->DOCCODE;
			$data['default']['PRJCODE'] 	= $getdocapproval->PRJCODE;
			$data['default']['DOCAPP_TYPE'] = $getdocapproval->DOCAPP_TYPE;
			$data['default']['DOCAPP_NAME'] = $getdocapproval->DOCAPP_NAME;
			$data['default']['MENU_CODE'] 	= $getdocapproval->MENU_CODE;
			$data['default']['POSCODE'] 	= $getdocapproval->POSCODE;
			$data['default']['APPROVER_1'] 	= $getdocapproval->APPROVER_1;
			$data['default']['APPROVER_2'] 	= $getdocapproval->APPROVER_2;
			$data['default']['APPROVER_3'] 	= $getdocapproval->APPROVER_3;
			$data['default']['APPROVER_4'] 	= $getdocapproval->APPROVER_4;
			$data['default']['APPROVER_5'] 	= $getdocapproval->APPROVER_5; 
			$data['default']['APPLIMIT_1'] 	= $getdocapproval->APPLIMIT_1;
			$data['default']['APPLIMIT_2'] 	= $getdocapproval->APPLIMIT_2;
			$data['default']['APPLIMIT_3'] 	= $getdocapproval->APPLIMIT_3;
			$data['default']['APPLIMIT_4'] 	= $getdocapproval->APPLIMIT_4;
			$data['default']['APPLIMIT_5'] 	= $getdocapproval->APPLIMIT_5;
			$data['default']['CREATED_BY'] 	= $getdocapproval->CREATED_BY;
			
			$this->load->view('v_setting/v_docapproval/docapproval_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_setting/m_docapproval/m_docapproval', '', TRUE);
		
		$LangID 	= $this->session->userdata['LangID'];
		$DOCAPP_ID	= $this->input->post('DOCAPP_ID');
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$MENU_NAME		= '';
			$MENU_CODE		= $this->input->post('MENU_CODE');
			$POSCODE		= $this->input->post('POSCODE');
			$sqlGetMENU		= "SELECT menu_name_$LangID AS menu_name FROM tbl_menu WHERE MENU_CODE = '$MENU_CODE'";
			$resGetMENU		= $this->db->query($sqlGetMENU)->result();
			foreach($resGetMENU as $rowMENU) :		
				$MENU_NAME	= $rowMENU->menu_name;
			endforeach;
			
			$APPROVER_1		= $this->input->post('APPROVER_1');
			$APPROVER_2		= $this->input->post('APPROVER_2');
			$APPROVER_3		= $this->input->post('APPROVER_3');
			$APPROVER_4		= $this->input->post('APPROVER_4');
			$APPROVER_5		= $this->input->post('APPROVER_5');
			$DOCCODE		= $this->input->post('DOCCODE');
			$PRJCODE		= $this->input->post('PRJCODE');
			$statFORM		= $this->input->post('statFORM');

			if($statFORM == 1)
			{
				if($APPROVER_5 != '')
					$MAX_STEP = 5;
				elseif($APPROVER_4 != '')
					$MAX_STEP = 4;
				elseif($APPROVER_3 != '')
					$MAX_STEP = 3;
				elseif($APPROVER_2 != '')
					$MAX_STEP = 2;
				elseif($APPROVER_1 != '')
					$MAX_STEP = 1;
					
				$UpdDocApp 	= array('DOCCODE'		=> $DOCCODE,
									'PRJCODE' 		=> $PRJCODE,
									'DOCAPP_NAME' 	=> $MENU_NAME,
									'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
									'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
									'POSCODE' 		=> $this->input->post('POSCODE'),
									'APPROVER_1'	=> $this->input->post('APPROVER_1'),
									'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
									'APPROVER_2'	=> $this->input->post('APPROVER_2'),
									'APPLIMIT_2'	=> $this->input->post('APPLIMIT_2'),
									'APPROVER_3'	=> $this->input->post('APPROVER_3'),
									'APPLIMIT_3'	=> $this->input->post('APPLIMIT_3'),
									'APPROVER_4'	=> $this->input->post('APPROVER_4'),
									'APPLIMIT_4'	=> $this->input->post('APPLIMIT_4'),
									'APPROVER_5'	=> $this->input->post('APPROVER_5'),
									'APPLIMIT_5'	=> $this->input->post('APPLIMIT_5'),
									'MAX_STEP'		=> $MAX_STEP,
									'CREATED_BY'	=> $this->input->post('CREATED_BY'));
				$this->m_docapproval->update($DOCAPP_ID, $UpdDocApp);
				
				// DELETE DETAIL BY CODE
				$delSett		= "DELETE FROM tbl_docstepapp_det WHERE DOCCODE = '$DOCCODE'";
				$this->db->query($delSett);
				
				// START : Create to detail
					if($APPROVER_1 != '')
					{
						$MAX_STEP		= 1;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_1'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_1'),
												'APP_STEP'		=> 1);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 1);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_2 != '')
					{
						$MAX_STEP		= 2;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_2'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_2'),
												'APP_STEP'		=> 2);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 2);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_3 != '')
					{
						$MAX_STEP		= 3;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_3'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_3'),
												'APP_STEP'		=> 3);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 3);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_4 != '')
					{
						$MAX_STEP		= 4;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_4'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_4'),
												'APP_STEP'		=> 4);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 4);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
					if($APPROVER_5 != '')
					{
						$MAX_STEP		= 5;
						$InsDocAppDet 	= array('DOCCODE' 		=> $DOCCODE,
												'PRJCODE' 		=> $PRJCODE,
												'DOCAPP_NAME' 	=> $MENU_NAME,
												'DOCAPP_TYPE' 	=> $this->input->post('DOCAPP_TYPE'),
												'MENU_CODE' 	=> $this->input->post('MENU_CODE'),
												'POSCODE' 		=> $this->input->post('POSCODE'),
												'APPROVER_1'	=> $this->input->post('APPROVER_5'),
												'APPLIMIT_1'	=> $this->input->post('APPLIMIT_5'),
												'APP_STEP'		=> 5);	
						$this->m_docapproval->add1($InsDocAppDet);
						
						$UpdDocAppDet 	= array('MAX_STEP' 		=> 5);	
						$this->m_docapproval->updateDet($UpdDocAppDet, $DOCCODE);
					}
			}
			elseif($statFORM == 2)
			{
				$delSSTEP1	= "DELETE FROM tbl_docstepapp WHERE DOCAPP_ID = $DOCAPP_ID";
				$this->db->query($delSSTEP1);
				
				$delSSTEP2	= "DELETE FROM tbl_docstepapp_det WHERE DOCCODE = $DOCAPP_ID";
				$this->db->query($delSSTEP2);
			}			
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_setting/c_docapproval/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($ReqApproval_ID)
	{		
		redirect('c_setting/c_docapproval/');
	}
}