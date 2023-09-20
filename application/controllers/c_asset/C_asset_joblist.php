<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 10 April 2017
 * File Name	= C_asset_joblist.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_asset_joblist  extends CI_Controller  
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
		
		$url			= site_url('c_asset/c_asset_joblist/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_asset/m_asset_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 			= $appName;
			$data['h2_title']		= 'Asset Job List';
			$data['h3_title']		= 'asset management';
			$data['secAddURL'] 		= site_url('c_asset/c_asset_joblist/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 		= site_url('c_asset/c_asset_joblist/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			$num_rows 				= $this->m_asset_joblist->count_all_num_rows();
			$data["recordcount"] 	= $num_rows;
			$data["MenuCode"] 		= 'MN275';
			$data['vAssetGroup']	= $this->m_asset_joblist->get_last_ten_AG()->result();
			
			$this->load->view('v_asset/v_asset_joblist/asset_joblist', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function add() // OK
	{	
		$this->load->model('m_asset/m_asset_joblist', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 			= $appName;
			$docPatternPosition 	= 'Especially';	
			$data['title'] 			= $appName;
			$data['task'] 			= 'add';
			$data['h2_title']		= 'AddJob List';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_joblist/add_process');
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_joblist/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_joblist/');
			$MenuCode 				= 'MN275';
			$data["MenuCode"] 		= 'MN275';
			$data['viewDocPattern'] = $this->m_asset_joblist->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_asset/v_asset_joblist/asset_joblist_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_asset/m_asset_joblist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$JL_CODE	= $this->input->post('JL_CODE');
			$JL_MANCODE	= $this->input->post('JL_MANCODE');
			$JL_NAME	= $this->input->post('JL_NAME');
			$JL_PRJCODE	= $this->input->post('JL_PRJCODE');
			$JL_DESC	= $this->input->post('JL_DESC');
			$JL_STAT	= $this->input->post('JL_STAT');
			
			$InsAG 		= array('JL_CODE' 		=> $JL_CODE,
								'JL_MANCODE'	=> $JL_MANCODE,
								'JL_NAME'		=> $JL_NAME,
								'JL_PRJCODE'	=> $JL_PRJCODE,
								'JL_DESC'		=> $JL_DESC,
								'JL_STAT'		=> $JL_STAT);
												
			$this->m_asset_joblist->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_joblist/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_asset/m_asset_joblist', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$JL_CODE	= $_GET['id'];
			$JL_CODE	= $this->url_encryption_helper->decode_url($JL_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'AddJob List';
			$data['h3_title']		= 'asset management';
			$data['form_action']	= site_url('c_asset/c_asset_joblist/update_process');
			$data["MenuCode"] 		= 'MN275';
			//$data['link'] 			= array('link_back' => anchor('c_asset/c_asset_joblist/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 		= site_url('c_asset/c_asset_joblist/');
			$getAG 					= $this->m_asset_joblist->get_AG($JL_CODE)->row();
					
			$data['default']['JL_CODE'] 	= $getAG->JL_CODE;
			$data['default']['JL_MANCODE'] 	= $getAG->JL_MANCODE;
			$data['default']['JL_NAME'] 	= $getAG->JL_NAME;
			$data['default']['JL_PRJCODE'] 	= $getAG->JL_PRJCODE;
			$data['default']['JL_DESC'] 	= $getAG->JL_DESC;
			$data['default']['JL_STAT'] 	= $getAG->JL_STAT;
			
			$this->load->view('v_asset/v_asset_joblist/asset_joblist_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_asset/m_asset_joblist', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$JL_CODE	= $this->input->post('JL_CODE');
			$JL_MANCODE	= $this->input->post('JL_MANCODE');
			$JL_NAME	= $this->input->post('JL_NAME');
			$JL_PRJCODE	= $this->input->post('JL_PRJCODE');
			$JL_DESC	= $this->input->post('JL_DESC');
			$JL_STAT	= $this->input->post('JL_STAT');
			
			$UpdAG 		= array('JL_CODE' 		=> $JL_CODE,
								'JL_MANCODE'	=> $JL_MANCODE,
								'JL_NAME'		=> $JL_NAME,
								'JL_PRJCODE'	=> $JL_PRJCODE,
								'JL_DESC'		=> $JL_DESC,
								'JL_STAT'		=> $JL_STAT);
								
			$this->m_asset_joblist->update($JL_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('c_asset/c_asset_joblist/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}