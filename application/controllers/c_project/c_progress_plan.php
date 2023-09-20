<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 5 April 2017
 * File Name	= Item_list.php
 * Location		= -
*/

/* 
 * Author		= Hendar Permana
 * Create Date	= 23 November 2017
 * File Name	= c_progress_plan.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_progress_plan  extends CI_Controller  
{
 	function index() // OK
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_progress_plan/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0) // OK
	{
		$this->load->model('m_project/m_joblist/m_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			$data['h3_title']			= 'Progres Plan';
			
			$num_rows 					= $this->m_joblist->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_joblist->get_last_ten_project()->result();
			
			$this->load->view('v_project/v_progress_plan/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_last_ten_item() // OK
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE			= $_GET['id'];
			$PRJCODE			= $this->url_encryption_helper->decode_url($PRJCODE);
			$EmpID 				= $this->session->userdata('Emp_ID');
				
			$data['title'] 			= $appName;
			$data['h2_title'] 		= 'Progress Plan';
			$data['h3_title']		= 'Project';
			$data['main_view'] 		= 'v_project/v_progress_plan/v_progress_plan';
			
			$num_rows 				= $this->m_progress_plan->count_all_num_rows($PRJCODE);
			$data["recordcount"] 	= $num_rows;
			$data['PRJCODE'] 		= $PRJCODE;
			$data["MenuCode"] 		= 'MN188';
			$data['viewitemlist'] 	= $this->m_progress_plan->get_last_ten_item($PRJCODE)->result();
			
			$this->load->view('v_project/v_progress_plan/v_progress_plan', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			$LangID = $this->session->userdata['LangID'];
			
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Item';
			$data['h3_title']		= 'Project';
			$data['form_action']	= site_url('c_project/c_progress_plan/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_project/c_progress_plan/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_progress_plan/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recordcountUnit'] 	= $this->m_progress_plan->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_progress_plan->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_progress_plan->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_progress_plan->viewCateg()->result();
			$data['PRJCODE'] 			= $PRJCODE;
			
			$this->load->view('v_project/v_progress_plan/v_progress_plan_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function add_process() // OK
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$MCP_CODE 		= $this->input->post('MCP_CODE');
			$MCP_PRJCODE 	= $this->input->post('MCP_PRJCODE');
			$MCP_DATE 		= date('Y-m-d',strtotime($this->input->post('MCP_DATE')));
			$MCP_PROG 		= $this->input->post('MCP_PROG');
			$MCP_AMOUNT		= $this->input->post('MCP_AMOUNT');
			$MCR_DATE 		= date('Y-m-d',strtotime($this->input->post('MCR_DATE')));
						
			$itemPar 	= array('MCP_CODE' 		=> $MCP_CODE,
							'MCP_PRJCODE'		=> $MCP_PRJCODE,
							'MCP_DATE'			=> $MCP_DATE,
							'MCP_PROG'			=> $MCP_PROG,
							'MCP_AMOUNT'		=> $MCP_AMOUNT,
							'MCR_DATE'			=> $MCR_DATE);
							
			$this->m_progress_plan->add($itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_progress_plan/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($MCP_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update() // OK
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 	= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$MCP_CODE	= $_GET['id'];
			$MCP_CODE	= $this->url_encryption_helper->decode_url($MCP_CODE);
			
			//$MCP_CODEA	= explode("~", $MCP_CODE);
			//$PRJCODE	= $MCP_CODEA[0];
			//$MCP_CODE	= $MCP_CODEA[1];
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title'] 		= 'Edit Progress Plan';
			$data['h3_title']		= 'Project';
			$data['form_action']	= site_url('c_project/c_progress_plan/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_project/c_progress_plan/','<input type="button" name="btnCancel" id="btnCancel" class="btn btn-danger" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_project/c_progress_plan/');
			$data["MenuCode"] 		= 'MN188';
					
			$data['recordcountUnit'] 	= $this->m_progress_plan->count_all_num_rowsUnit();
			$data['viewUnit'] 			= $this->m_progress_plan->viewunit()->result();
			$data['recordcountCateg'] 	= $this->m_progress_plan->count_all_num_rowsCateg();
			$data['viewCateg'] 			= $this->m_progress_plan->viewCateg()->result();
			
			$geITEM = $this->m_progress_plan->get_Item_by_Code($MCP_CODE)->row();			
			
			$data['default']['MCP_CODE'] 		= $geITEM->MCP_CODE;
			$data['default']['MCP_PRJCODE'] 	= $geITEM->MCP_PRJCODE;
			$data['default']['MCP_DATE'] 		= $geITEM->MCP_DATE;
			$data['default']['MCP_PROG'] 		= $geITEM->MCP_PROG;
			$data['default']['MCP_AMOUNT'] 		= $geITEM->MCP_AMOUNT;
			$data['default']['MCR_DATE'] 		= $geITEM->MCR_DATE;
			//$data['PRJCODE'] 					= $geITEM->PRJCODE;
			
			$this->load->view('v_project/v_progress_plan/v_progress_plan_form', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
	function update_process()
	{
		$this->load->model('m_project/m_progress_plan/m_progress_plan', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$this->db->trans_begin();
			
			$MCP_PRJCODE 	= $this->input->post('MCP_PRJCODE');
			$MCP_CODE 		= $this->input->post('MCP_CODE');
			$MCP_DATE 		= date('Y-m-d',strtotime($this->input->post('MCP_DATE')));
			$MCP_PROG 		= $this->input->post('MCP_PROG');
			$MCP_AMOUNT		= $this->input->post('MCP_AMOUNT');
			$MCR_DATE 		= date('Y-m-d',strtotime($this->input->post('MCR_DATE')));

			//return false;
			
			$itemPar 	= array('MCP_PRJCODE' 	=> $MCP_PRJCODE,
							'MCP_CODE'			=> $MCP_CODE,
							'MCP_DATE'			=> $MCP_DATE,
							'MCP_PROG'			=> $MCP_PROG ,
							'MCP_AMOUNT'		=> $MCP_AMOUNT,
							'MCR_DATE'			=> $MCR_DATE);
							
			$this->m_progress_plan->update($MCP_CODE, $itemPar);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_progress_plan/get_last_ten_item/?id='.$this->url_encryption_helper->encode_url($MCP_PRJCODE));
			redirect($url);
		}
		else
		{
			redirect('login');
		}
	}
	
	function changeStatusItem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$CodeItem 			= $this->input->post('chkDetail');
			$gesd_tcost 		= $this->m_progress_plan->get_Item_by_Code($CodeItem)->row();
			
			$ItemStatus			= $gesd_tcost->Status;
			if($ItemStatus == 'Active')
			{
				$NItemStatus = 'InActive';
			}
			else
			{
				$NItemStatus = 'Active';
			}
							
			$this->m_progress_plan->updateStatus($CodeItem, $NItemStatus);
			
			redirect('c_project/c_progress_plan/');
		}
		else
		{
			redirect('login');
		}
	}
	
	function delete($Item_Code)
	{
		$this->m_progress_plan->delete($this->input->post('chkDetail'));
		$this->session->set_flashdata('message', 'Data successfull deleted');
		
		redirect('c_project/c_progress_plan/');
	}
	
	function popupallitem()
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 			= $MyAppName;;
			$data['h2_title'] 		= 'Select Item';
			$data['main_view'] 		= 'v_project/v_progress_plan/Itemlist_sd_form';
			$data['form_action']	= site_url('c_project/c_progress_plan/update_process');
			
			$data['recordcountAllItem'] = $this->m_progress_plan->count_all_num_rowsAllItem();
			$data['viewAllItem'] 	= $this->m_progress_plan->viewAllItem()->result();
					
			$this->load->view('v_project/v_progress_plan/purchase_reqselecsd_tcost_sd', $data);
		}
		else
		{
			redirect('login');
		}
	}
	
    function inbox($offset=0)
	{
		$MyAppName    	= $this->session->userdata['SessAppTitle']['app_title_name'];
		if ($this->session->userdata('login') == TRUE)
		{
			$data['title'] 		= $MyAppName;
			$data['h2_title']	= 'Item List Inbox';
			$data['main_view'] 	= 'v_project/v_progress_plan/Itemlist_inbox_sd';

			$num_rows = $this->m_progress_plan->count_all_num_rows_inbox();
			$data["recordcount"] = $num_rows;
			$config = array();
			$config['base_url'] = site_url('c_project/c_progress_plan/get_last_ten_item');
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
	 		
			$data['viewpurreq'] = $this->m_progress_plan->get_last_ten_PR_inbox($config["per_page"], $offset)->result();
			$data["pagination"] = $this->pagination->create_links();	
			
			$this->load->view('template', $data);
		}
		else
		{
			redirect('login');
		}
    }
}