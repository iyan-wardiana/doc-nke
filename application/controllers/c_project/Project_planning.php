<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= Project_planning.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project_planning extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/project_planning/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0)
	{
		$this->load->model('m_project/M_project_planning/M_project_planning_sd', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			
			$num_rows 					= $this->m_project_planning->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_project_planning->get_last_ten_project()->result();	
			
			$this->load->view('v_project/v_joblist/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function get_last_ten_project($offset=0)
	{
		$this->load->model('m_project/M_project_planning/M_project_planning_sd', '', TRUE);
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'index');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'get_last_ten_project_src');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'get_last_ten_project_src');
			
			$data['title'] = $appName;
			$data['h2_title'] = 'Project Planning List';
			$data['main_view'] = 'v_project/v_project_planning/project_planning_sd';
			//$data['srch_url'] = site_url('c_project/project_planning_sd/get_last_ten_project_src');
			$data['moffset'] = $offset;
			$data['perpage'] = 20;
			$data['theoffset'] = 0;
			
			
			$num_rows = $this->M_project_planning_sd->count_all_num_rows();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning_sd/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 20;
			$config["uri_segment"] = 3;
			$config['cur_page'] = $offset;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
	 
			$data['vewproject'] = $this->M_project_planning_sd->get_last_ten_project($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function get_last_ten_project_src($offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['showIndex'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'index');
			$data['srch_url'] 		= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'get_last_ten_project_src');
			
			$data['title'] = $appName;
			$data['h2_title'] = 'Project Planning List';
			$data['main_view'] = 'v_project/v_project_planning/project_planning_sd';
			//$data['srch_url'] = site_url('c_project/project_planning_sd/get_last_ten_project_src');
			$data['moffset'] = $offset;
			
			$data['selSearchType'] = $this->input->post('selSearchType');
			$data['txtSearch'] = $this->input->post('txtSearch');		
			
			if (isset($_POST['submitSrch']))
			{
				$selSearchType	= $this->input->post('selSearchType');
				$txtSearch 		= $this->input->post('txtSearch');
				
				$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
				
				$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			}
			else
			{
				$selSearchType      = $this->session->userdata['dtSessSrc1']['selSearchType'];
				$txtSearch        	= $this->session->userdata['dtSessSrc1']['txtSearch'];
				
				$dataSessSrc = array(
					'selSearchType' => $this->session->userdata['dtSessSrc1']['selSearchType'],
					'txtSearch' => $this->session->userdata['dtSessSrc1']['txtSearch']);
					
				$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			}	
			
			if($selSearchType == 'ProjNumber')
			{
				$num_rows = $this->M_project_planning_sd->count_all_num_rows_PNo($txtSearch);
			}
			else
			{
				$num_rows = $this->M_project_planning_sd->count_all_num_rows_PNm($txtSearch);
			}
			
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning_sd/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 10;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 
			$this->pagination->initialize($config);
			
			if($selSearchType == 'ProjNumber')
			{
				$data['vewproject'] = $this->M_project_planning_sd->get_last_ten_project_PNo($config["per_page"], $offset, $txtSearch)->result();
			}
			else
			{
				$data['vewproject'] = $this->M_project_planning_sd->get_last_ten_project_PNm($config["per_page"], $offset, $txtSearch)->result();
			}
			
			$data["pagination"] = $this->pagination->create_links();	
			
			// // Start : Searching Function --- Untuk delete session
			$dataSessSrc = array(
					'selSearchType' => $this->input->post('selSearchType'),
					'txtSearch' => $this->input->post('txtSearch'));
			$this->session->set_userdata('dtSessSrc1', $dataSessSrc);
			$dataSessSrc   = $this->session->userdata('dtSessSrc1');
			// End : Searching Function	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project Planning List | Update Project Planning';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form';
			$data['form_action']	= site_url('c_project/project_planning_sd/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning_sd/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			//$data['recordcountCust'] 	= $this->M_project_planning_sd->count_all_num_rowsCust();
			//s$data['viewcustomer'] 		= $this->M_project_planning_sd->viewcustomer()->result();
			//$data['recordcountEmpDept']	= $this->M_project_planning_sd->count_all_num_rowsEmpDept();
			//$data['viewEmployeeDept'] 	= $this->M_project_planning_sd->viewEmployeeDept()->result();
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form';
			$data['form_action']	= site_url('c_project/project_planning_sd/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning_sd/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$proj_Status		= 2; // 1 = New, 2 = confirm, 3 = Close
			
			//setting Project Date
			$Proj_Date3		= $this->input->post('Proj_Date');
			$Patt_Year 		= date('Y',$Proj_Date3);
			
			//setting Project Start Date
			$ProjS_Date3		= $this->input->post('ProjStar_Date');
			
			//setting Project End Date
			$ProjE_Date3		= $this->input->post('ProjEnd_Date');
			
			$projectheader = array('proj_Number' 	=> $this->input->post('proj_Number'),
							'proj_Name'				=> $this->input->post('proj_Name'),
							'PRJCODE'				=> $this->input->post('PRJCODE'),
							'proj_Date'				=> $Proj_Date3,
							'proj_StartDate'		=> $ProjS_Date3,
							'proj_EndDate'			=> $ProjE_Date3,
							'proj_Category'			=> $this->input->post('proj_Category'),
							'proj_PM_EmpID'			=> $this->input->post('proj_PM_EmpID'), 
							'proj_CustCode'			=> $this->input->post('cust_code'),
							'proj_Currency'			=> $this->input->post('proj_Currency'),
							'proj_notes'			=> $this->input->post('proj_notes'),
							'proj_CurrencyRate'		=> 1,
							'proj_Status'			=> $proj_Status,
							'Patt_Year'				=> $Patt_Year,
							'Patt_Number'			=> $this->input->post('lastPatternNumb'));
							
			$this->M_project_planning_sd->update($PRJCODE, $projectheader);
			
			$this->session->set_flashdata('message', 'Data succesful to update.');
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			redirect('c_project/project_planning_sd/');
		}
		else
		{
			redirect('login');
		}
	}
	
    function inbox($offset=0)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $appName;
			$data['h2_title']	= 'Project List Inbox';
			$data['main_view'] 	= 'v_project/v_project_planning/project_planning_sd_inbox';

			/*$num_rows = $this->M_project_planning_sd->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/project_planning_sd/get_last_ten_project');
			$config["total_rows"] = $num_rows;
			$config["per_page"] = 2;
			$config["uri_segment"] = 3;
				
			$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
			$config['full_tag_close'] = '</ul>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="current"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';

			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['first_link'] = '&lt;&lt;';
			$config['last_link'] = '&gt;&gt;';
	 		
			$this->pagination->initialize($config);
	 		
			$data['viewpurord'] = $this->M_project_planning_sd->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();*/	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
	
	function getVendAddress($vendCode)
	{
		$data['myVendCode']		= "$vendCode";
		$sql = "SELECT Vend_Code, Vend_Name, Vend_Address FROM tvendor
					WHERE Vend_Code = '$vendCode'";
		$result1 = $this->db->query($sql)->result();
		foreach($result1 as $row) :
			$Vend_Name = $row->Vend_Address;
		endforeach;
		echo $Vend_Name;
	}
	
	// Start : Add on 24 April 2016
	// 1. Used for Add Material Budget Planning
	function addMaterialPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'addMaterialPlan_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['CTGCODE'] 		= 'MTRL';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Material Planning';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'MTRL';
		$ItemCat 				= 'MTRL';
		//$data['main_view'] 		= 'v_project/v_project_planning_sd/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning_sd->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning_sd->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_sd_Planreqselectitem', $data);
	}
	
	function addMaterialPlan_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form';
			$data['form_action']	= site_url('c_project/project_planning_sd/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning_sd/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Material Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjMatPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning_sd->updateMatPlan($ProjMatPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning_sd->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning_sd->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning_sd->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning_sd->viewEmployeeDept()->result();
						
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning_sd->deleteDetailMat($this->input->post('PRJCODE'));
		
			// to save detail Budget Material Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_material',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
	
	// Start : Add on 24 April 2016
	// 2. Used for Add Upah/Wage Budget Planning
	function addSalaryPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'addSalaryPlan_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Salary Planning';
			$data['CTGCODE'] 		= 'UPAH';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlanSal($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'SLR';
		$ItemCat				= 'SLR';
		//$data['main_view'] 		= 'v_project/v_project_planning_sd/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning_sd->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning_sd->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_Planreqselectitem', $data);
	}
	
	function addSalaryPlan_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form';
			$data['form_action']	= site_url('c_project/project_planning_sd/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning_sd/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Salary Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjSalPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning->updateSalPlan($ProjSalPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning->viewEmployeeDept()->result();
			
			
			$getproject = $this->M_project_planning->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning->deleteDetailSal($this->input->post('PRJCODE'));
		
			// to save detail Budget Salary Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_salary',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
	
	// Start : Add on 28 Maret 2016
	// 3. Used for Add Service Budget Planning
	function addSrvPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning'),'addSrv_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Service Planning';
			$data['CTGCODE'] 		= 'SRVC';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlanSrv($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'TLS';
		$ItemCat				= 'TLS';
		//$data['main_view'] 		= 'v_project/v_project_planning/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_Planreqselectitem', $data);
	}
	
	function addSrv_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_form';
			$data['form_action']	= site_url('c_project/project_planning/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Tools Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjSalPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning->updateToolsPlan($ProjSalPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning->viewEmployeeDept()->result();
			
			
			$getproject = $this->M_project_planning->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning->deleteDetailTools($this->input->post('PRJCODE'));
		
			// to save detail Budget Tools Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_Tools',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
	
	// Start : Add on 28 Maret 2016
	// 4, Used for Add Tools Budget Planning
	function addToolsPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning'),'addTools_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Tools Planning';
			$data['CTGCODE'] 		= 'TOOLS';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlanTools($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'TLS';
		$ItemCat				= 'TLS';
		//$data['main_view'] 		= 'v_project/v_project_planning/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_Planreqselectitem', $data);
	}
	
	function addTools_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_form';
			$data['form_action']	= site_url('c_project/project_planning/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Tools Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjSalPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning->updateToolsPlan($ProjSalPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning->viewEmployeeDept()->result();
			
			
			$getproject = $this->M_project_planning->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning->deleteDetailTools($this->input->post('PRJCODE'));
		
			// to save detail Budget Tools Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_Tools',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
	
	// Start : Add on 28 Maret 2016
	// 5. Used for Add Indorect Budget Planning
	function addIndirPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning'),'addIndir_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Indorect Planning';
			$data['CTGCODE'] 		= 'INDIR';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlanIndir($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'TLS';
		$ItemCat				= 'TLS';
		//$data['main_view'] 		= 'v_project/v_project_planning/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_Planreqselectitem', $data);
	}
	
	function addIndir_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_form';
			$data['form_action']	= site_url('c_project/project_planning/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Tools Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjSalPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning->updateToolsPlan($ProjSalPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning->viewEmployeeDept()->result();
			
			
			$getproject = $this->M_project_planning->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning->deleteDetailTools($this->input->post('PRJCODE'));
		
			// to save detail Budget Tools Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_Tools',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
	
	// Start : Add on 28 Maret 2016
	// 6. Used for Add Reimbrstment Budget Planning
	function addReimbPlan($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			// Secure URL
			$data['form_action'] 	= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning'),'addReimb_process');
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Edit';
			$data['h2_title']		= 'Project Planning List | Update Project Planning | Manage Indorect Planning';
			$data['CTGCODE'] 		= 'REIMB';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_sd_form_material';
			$url_back				= $this->mza_secureurl->setSecureUrl_encode(site_url('c_project/project_planning_sd'),'update', array('param' => $PRJCODE));
			$data['link'] 			= array('link_back' => anchor("$url_back",'<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			$getproject = $this->M_project_planning_sd->get_PROJ_by_number($PRJCODE)->row();
			
			$data['default']['proj_Number'] = $getproject->proj_Number;
			$data['default']['PRJCODE'] 	= $getproject->PRJCODE;
			$data['default']['PRJCNUM'] 	= $getproject->PRJCNUM;
			$data['default']['PRJNAME'] 	= $getproject->PRJNAME;
			$data['default']['PRJLOCT']		= $getproject->PRJLOCT;
			$data['default']['PRJOWN'] 		= $getproject->PRJOWN;
			$data['default']['PRJDATE'] 	= $getproject->PRJDATE;
			$data['default']['PRJEDAT'] 	= $getproject->PRJEDAT;
			$data['default']['PRJCOST']		= $getproject->PRJCOST;
			$data['default']['PRJCURR'] 	= $getproject->PRJCURR;
			$data['default']['PRJSTAT'] 	= $getproject->PRJSTAT;
			$data['default']['PRJNOTE'] 	= $getproject->PRJNOTE;
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function popupallitemPlanReimb($PRJCODE)
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$data['title'] 			= $appName;;
		$data['PRJCODE'] 		= $PRJCODE;
		$data['h2_title'] 		= 'Select Item';
		$data['ItemCat'] 		= 'TLS';
		$ItemCat				= 'TLS';
		//$data['main_view'] 		= 'v_project/v_project_planning/purchase_requisition_form';
		$data['form_action']	= site_url('c_purchase/purchase_requisition/update_process');
		
		$data['recordcountAllItem'] = $this->M_project_planning->count_all_num_rowsAllItem($ItemCat);
		$data['viewAllItemPlan'] 	= $this->M_project_planning->viewAllItemPlan($PRJCODE, $ItemCat)->result();
				
		$this->load->view('v_project/v_project_planning/project_Planreqselectitem', $data);
	}
	
	function addReimb_process()
	{
		$getAppName = $this->Menu_model->getAppName()->result();
		foreach($getAppName as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Project List | Update Project List';
			$data['main_view'] 		= 'v_project/v_project_planning/project_planning_form';
			$data['form_action']	= site_url('c_project/project_planning/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/project_planning/','<input type="button" name="btnCancel" id="btnCancel" value="Cancel" class="button_css" />', array('style' => 'text-decoration: none;')));
			
			// Save Data to Tools Plan
			$PRJCODE 		= $this->input->post('PRJCODE');
			$ProjSalPlan 	= array('PRJCODE' 	=> $this->input->post('PRJCODE'),
								'budgetCategory'	=> $this->input->post('budgetCategory'),
								'budgetCategory1'	=> $this->input->post('budgetCategory1'));
							
			$this->M_project_planning->updateToolsPlan($ProjSalPlan);
			
			
			$PRJCODE = $this->input->post('PRJCODE');
			
			$data['recordcountCust'] 	= $this->M_project_planning->count_all_num_rowsCust();
			$data['viewcustomer'] 	= $this->M_project_planning->viewcustomer()->result();
			$data['recordcountEmpDept'] 	= $this->M_project_planning->count_all_num_rowsEmpDept();
			$data['viewEmployeeDept'] 	= $this->M_project_planning->viewEmployeeDept()->result();
			
			
			$getproject = $this->M_project_planning->get_PROJ_by_number($PRJCODE)->row();
			
			$this->session->set_userdata('PRJCODE', $getproject->PRJCODE);
			
			$PRJCODE							= $getproject->PRJCODE;
			$data['default']['proj_ID'] 		= $getproject->proj_ID;
			$data['default']['proj_Number'] 	= $getproject->proj_Number;
			$data['default']['PRJCODE'] 		= $getproject->PRJCODE;
			$data['default']['proj_Name'] 		= $getproject->proj_Name;
			$data['default']['Proj_Date']		= $getproject->proj_Date;
			$data['default']['proj_StartDate'] 	= $getproject->proj_StartDate;
			$data['default']['proj_EndDate'] 	= $getproject->proj_EndDate;
			$data['default']['proj_Type'] 		= $getproject->proj_Type;
			$data['default']['proj_Category']	= $getproject->proj_Category;
			$data['default']['proj_PM_EmpID'] 	= $getproject->proj_PM_EmpID;
			$data['default']['proj_CustCode'] 	= $getproject->proj_CustCode;
			$data['default']['proj_Currency'] 	= $getproject->proj_Currency;
			$data['default']['proj_Status'] 	= $getproject->proj_Status;
			$data['default']['First_Name'] 		= $getproject->First_Name;
			$data['default']['Middle_Name'] 	= $getproject->Middle_Name;
			$data['default']['Last_Name'] 		= $getproject->Last_Name;
			$data['default']['Cust_Name'] 		= $getproject->Cust_Name;
			$data['default']['Patt_Year'] 		= $getproject->Patt_Year;
			$data['default']['proj_notes'] 		= $getproject->proj_notes;
			$data['default']['Patt_Number'] 	= $getproject->Patt_Number;
			
			// Delete Detail by Job Order ID
			$this->M_project_planning->deleteDetailTools($this->input->post('PRJCODE'));
		
			// to save detail Budget Tools Planning
			foreach($_POST['data'] as $d)
			{
				$PPMatQty = $d['PPMat_Qty'];
				if($PPMatQty > 0)
				{
					$this->db->insert('tprojplan_Tools',$d);
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
				
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
	}
	// End : Add on 24 April 2016
}