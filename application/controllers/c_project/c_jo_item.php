<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 10 Mei 2017
 * File Name	= C_office_inventory.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 19 Oktober 2017
 * File Name	= c_jo_item.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_jo_item  extends CI_Controller  
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
		
		$url			= site_url('c_project/c_jo_item/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_project/m_jo_item/m_jo_item', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'J O Item';
			$data['h3_title']			= 'Project';
			$data['secAddURL'] 			= site_url('c_project/c_jo_item/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_project/c_jo_item/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_jo_item->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 		= 'MN286';
			
			$data['vAssetGroup']		= $this->m_jo_item->get_last_ten_AG()->result();
			
			
			$this->load->view('v_project/v_jo_item/v_jo_item', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_project/m_jo_item/m_jo_item', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add J O Item';
			$data['h3_title']			= 'J O Item';
			$data['form_action']		= site_url('c_project/c_jo_item/add_process');
			$data['link'] 				= array('link_back' => anchor('c_project/c_jo_item/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 			= site_url('c_project/c_jo_item/');
			$MenuCode 					= 'MN276';
			$data["MenuCode"] 			= 'MN286';
			$data['viewDocPattern'] 	= $this->m_jo_item->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_project/v_jo_item/v_jo_item_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_project/m_jo_item/m_jo_item', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$InsAG 		= array('JOI_CODE' 		=> $this->input->post('JOI_CODE'),
								'JOI_ITEM' 		=> $this->input->post('JOI_ITEM'),
								'JOI_TYPE' 		=> $this->input->post('JOI_TYPE'),
								//'JOI_METODE' 	=> $this->input->post('JOI_METODE'),
								'JOI_CREATER'	=> $this->input->post('JOI_CREATER'),
								'JOI_CREATED' 	=> date('Y-m-d',strtotime($this->input->post('JOI_CREATED'))));

			$this->m_jo_item->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_jo_item/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_project/m_jo_item/m_jo_item', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$JOI_CODE	= $_GET['id'];
			$JOI_CODE	= $this->url_encryption_helper->decode_url($JOI_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Update J O Item';
			$data['h3_title']		= 'J O Item';
			$data['form_action']	= site_url('c_project/c_jo_item/update_process');
			$data['link'] 			= array('link_back' => anchor('c_project/c_jo_item/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN286';
			$data['backURL'] 		= site_url('c_project/c_jo_item/');
			
			$getAG 					= $this->m_jo_item->get_AG($JOI_CODE)->row();
			
			$data['default']['JOI_CODE'] 		= $getAG->JOI_CODE;
			$data['default']['JOI_ITEM'] 		= $getAG->JOI_ITEM;
			$data['default']['JOI_TYPE'] 		= $getAG->JOI_TYPE;
			//$data['default']['JOI_METODE'] 		= $getAG->JOI_METODE;
			$data['default']['JOI_CREATER']		= $getAG->JOI_CREATER;
			$data['default']['JOI_CREATED']		= $getAG->JOI_CREATED;
			
			$this->load->view('v_project/v_jo_item/v_jo_item_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_project/m_jo_item/m_jo_item', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$JOI_CODE	= $this->input->post('JOI_CODE');
			
			$UpdAG 		= array('JOI_CODE' 		=> $this->input->post('JOI_CODE'),
								'JOI_ITEM' 		=> $this->input->post('JOI_ITEM'),
								'JOI_TYPE'		=> $this->input->post('JOI_TYPE'),
								//'JOI_METODE' 	=> $this->input->post('JOI_METODE'),
								'JOI_CREATER'	=> $this->input->post('JOI_CREATER'),
								'JOI_CREATED'	=> date('Y-m-d',strtotime($this->input->post('JOI_CREATED'))));
								
			$this->m_jo_item->update($JOI_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_project/c_jo_item/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}