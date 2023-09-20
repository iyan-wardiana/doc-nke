<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 24 Mei 2017
 * File Name	= C_office_position.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class c_office_position  extends CI_Controller  
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
		
		$url			= site_url('c_asset/c_office_position/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_asset/M_office_position', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'Office Position';
			$data['h3_title']			= 'asset management';
			$data['secAddURL'] 			= site_url('c_asset/c_office_position/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('c_asset/c_office_position/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->M_office_position->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		$data["MenuCode"] 		= 'MN285';
			
			$data['vAssetGroup']		= $this->M_office_position->get_last_ten_AG()->result();
			
			
			$this->load->view('v_asset/v_office_position/v_office_position', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_office_position', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Position';
			$data['h3_title']			= 'asset management';
			$data['form_action']		= site_url('c_asset/c_office_position/add_process');
			$data['link'] 				= array('link_back' => anchor('c_asset/c_office_position/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			
			$MenuCode 					= 'MN285';
			$data["MenuCode"] 			= 'MN285';
			$data['viewDocPattern'] 	= $this->m_office_position->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_office_position/v_office_position_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/M_office_position', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$InsAG 		= array('ROOM_CODE' 		=> $this->input->post('ROOM_CODE'),
								'INV_CODE' 		=> $this->input->post('INV_CODE'),
								'ROOM_CODE' 	=> $this->input->post('ROOM_CODE'),
								'INV_QTY'			=> $this->input->post('INV_QTY'));
								
			$this->M_office_position->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/C_office_position/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/M_office_position', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$COMM_CODE	= $_GET['id'];
			$COMM_CODE	= $this->url_encryption_helper->decode_url($COMM_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Add Asset Group';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/C_office_position/update_process');
			$data['link'] 			= array('link_back' => anchor('c_asset/C_office_position/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data["MenuCode"] 		= 'MN285';
			
			$getAG 					= $this->M_office_position->get_AG($COMM_CODE)->row();
			
			$data['default']['ROOM_CODE'] 		= $getAG->ROOM_CODE;
			$data['default']['INV_CODE'] 		= $getAG->INV_CODE;
			$data['default']['ROOM_CODE'] 		= $getAG->ROOM_CODE;
			$data['default']['INV_QTY']			= $getAG->INV_QTY;
			
			$this->load->view('v_asset/v_office_position/v_office_position_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/M_office_position', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$ROOM_CODE	= $this->input->post('ROOM_CODE');
			
			$UpdAG 		= array('ROOM_CODE' 		=> $this->input->post('ROOM_CODE'),
								'INV_CODE' 		=> $this->input->post('INV_CODE'),
								'ROOM_CODE'		=> $this->input->post('ROOM_CODE'),
								'INV_QTY'			=> $this->input->post('INV_QTY'));
								
			$this->M_office_position->update($ROOM_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/C_office_position/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}