<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 10 Mei 2017
 * File Name	= C_office_inventory.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_office_inventory  extends CI_Controller  
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
		
		$url			= site_url('c_asset/c_office_inventory/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_asset/m_office_inventory', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Office Inventory';
			$data['h3_title']			= 'asset management';
			$data['secAddURL'] 			= site_url('c_asset/c_office_inventory/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_asset/c_office_inventory/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_office_inventory->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 		= 'MN286';
			
			$data['vAssetGroup']		= $this->m_office_inventory->get_last_ten_AG()->result();
			
			
			$this->load->view('v_asset/v_office_inventory/v_office_inventory', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_office_inventory', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add inventory';
			$data['h3_title']			= 'asset management';
			$data['form_action']		= site_url('c_asset/c_office_inventory/add_process');
			//$data['link'] 				= array('link_back' => anchor('c_asset/c_office_inventory/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_asset/c_office_inventory/');
			$MenuCode 					= 'MN276';
			$data["MenuCode"] 			= 'MN286';
			$data['viewDocPattern'] 	= $this->m_office_inventory->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_office_inventory/v_office_inventory_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_office_inventory', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$InsAG 		= array('INV_CODE' 		=> $this->input->post('INV_CODE'),
								'INV_NAME' 		=> $this->input->post('INV_NAME'),
								'INV_BRAND' 	=> $this->input->post('INV_BRAND'),
								'INV_TYPE' 		=> $this->input->post('INV_TYPE'),
								'INV_COLOR'		=> $this->input->post('INV_COLOR'),
								'INV_STAT' 		=> $this->input->post('INV_STAT'),
								'INV_NOTE'		=> $this->input->post('INV_NOTE'));

			$this->m_office_inventory->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_office_inventory/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_office_inventory', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$INV_CODE	= $_GET['id'];
			$INV_CODE	= $this->url_encryption_helper->decode_url($INV_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add Asset Group';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_office_inventory/update_process');
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_office_inventory/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_asset/c_office_inventory/');
			$data["MenuCode"] 		= 'MN286';
			
			$getAG 					= $this->m_office_inventory->get_AG($INV_CODE)->row();
			
			$data['default']['INV_CODE'] 		= $getAG->INV_CODE;
			$data['default']['INV_NAME'] 		= $getAG->INV_NAME;
			$data['default']['INV_BRAND'] 		= $getAG->INV_BRAND;
			$data['default']['INV_TYPE'] 		= $getAG->INV_TYPE;
			$data['default']['INV_COLOR'] 		= $getAG->INV_COLOR;
			$data['default']['INV_STAT'] 		= $getAG->INV_STAT;
			$data['default']['INV_NOTE'] 		= $getAG->INV_NOTE;
			
			$this->load->view('v_asset/v_office_inventory/v_office_inventory_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_office_inventory', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$INV_CODE	= $this->input->post('INV_CODE');
			
			$UpdAG 		= array('INV_CODE' 		=> $this->input->post('INV_CODE'),
								'INV_NAME' 		=> $this->input->post('INV_NAME'),
								'INV_BRAND'		=> $this->input->post('INV_BRAND'),
								'INV_TYPE' 		=> $this->input->post('INV_TYPE'),
								'INV_COLOR'		=> $this->input->post('INV_COLOR'),
								'INV_STAT' 		=> $this->input->post('INV_STAT'),
								'INV_NOTE' 		=> $this->input->post('INV_NOTE'));
								
			$this->m_office_inventory->update($INV_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_office_inventory/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}