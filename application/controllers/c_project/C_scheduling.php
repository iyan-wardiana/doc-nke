<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= Scheduling.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_scheduling extends CI_Controller  
{
 	public function index() // OK
	{
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		$url			= site_url('c_project/c_scheduling/listproject/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	function listproject($offset=0)
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Project List';
			
			$num_rows 					= $this->m_scheduling->count_all_project();
			$data["recordcount"] 		= $num_rows;	 
			$data['vewproject']			= $this->m_scheduling->get_last_ten_project()->result();	
			
			$this->load->view('v_project/v_scheduling/listproject', $data);
		}
		else
		{
			redirect('Auth');
		}
	}

	function get_all_schedule($offset=0) // OK
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{	
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE					= $_GET['id'];
			$PRJCODE					= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Vendor Category';
			$data['h3_title'] 			= 'purchase';
			$data['secAddURL'] 			= site_url('c_project/c_scheduling/add/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			$data["MenuCode"] 			= 'MN117';
			
			$num_rows 					= $this->m_scheduling->count_all_schedule();
			$data['countvendcat'] 		= $num_rows;
	 
			$data['viewvendcat'] 		= $this->m_scheduling->get_all_schedule()->result();
			
			$this->load->view('v_purchase/v_vendcat/vendor_category', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'Add Vendor Category';
			$data['h3_title'] 		= 'purchase';
			$data['form_action']	= site_url('c_project/c_scheduling/add_process');
			$data['link'] 			= array('link_back' => anchor('c_project/c_scheduling/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN117';
			
			$this->load->view('v_purchase/v_vendcat/vendor_category_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
		
	function getVENDCATCODE($cinta) // 
	{ 
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		$recordcountVCAT 	= $this->m_vendcat->count_all_scheduleVCAT($cinta);
		echo $recordcountVCAT;
	}
	
	function add_process() // 
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$vendcat = array('VendCat_Code' 	=> $this->input->post('VendCat_Code'),
							'VendCat_Name'		=> $this->input->post('VendCat_Name'),
							'VendCat_Desc'		=> $this->input->post('VendCat_Desc'));

			$this->m_vendcat->add($vendcat);
						
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_scheduling/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // 
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		$VendCat_Code	= $_GET['id'];
		$VendCat_Code	= $this->url_encryption_helper->decode_url($VendCat_Code);
		
		if ($this->session->userdata('login') == TRUE)
		{			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Edit Vendor Category';
			$data['h3_title'] 		= 'purchase';
			$data['form_action']	= site_url('c_project/c_scheduling/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/c_scheduling/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN117';
			
			$getvendcat 			= $this->m_vendcat->get_vendcat_by_code($VendCat_Code)->row();
			
			$data['default']['VendCat_Code'] = $getvendcat->VendCat_Code;
			$data['default']['VendCat_Name'] = $getvendcat->VendCat_Name;		
			$data['default']['VendCat_Desc'] = $getvendcat->VendCat_Desc;
			
			$this->load->view('v_purchase/v_vendcat/vendor_category_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process() // 
	{
		$this->load->model('m_project/m_scheduling/m_scheduling', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$VendCat_Code		= $this->input->post('VendCat_Code');
			$VendCat_Name		= $this->input->post('VendCat_Name');
			$VendCat_Desc		= $this->input->post('VendCat_Desc');
			
			$vendcat = array('VendCat_Code' 	=> $VendCat_Code,
							'VendCat_Name' 		=> $VendCat_Name,
							'VendCat_Desc'		=> $VendCat_Desc);
										
			$this->m_vendcat->update($VendCat_Code, $vendcat);
			
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			
			$url			= site_url('c_project/c_scheduling/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}