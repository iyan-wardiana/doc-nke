<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 10 Mei 2017
 * File Name	= howtouse.php
 * Location		= -
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class howtouse  extends CI_Controller  
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
		
		$url			= site_url('howtouse/index1/?id='.$this->url_encryption_helper->encode_url($appName));
		redirect($url);
	}
	
	public function index1($offset=0)
	{
		$this->load->model('m_howtouse', '', TRUE);
		$DefEmp_ID 				= $this->session->userdata['Emp_ID'];
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
					
			$data['title'] 				= $appName;
			$data['h2_title']			= 'How To Use';
			$data['h3_title']			= 'Help';
			$data['secAddURL'] 			= site_url('howtouse/add/?id='.$this->url_encryption_helper->encode_url($appName));
			$data['srch_url'] 			= site_url('howtouse/get_last_ten_docpattern_src/?id='.$this->url_encryption_helper->encode_url($appName));
			
			
			$num_rows 					= $this->m_howtouse->count_all_num_rows();
			$data["recordcount"] 		= $num_rows;
	 		
			
			$data['vAssetGroup']		= $this->m_howtouse->get_last_ten_AG()->result();
			
			if($DefEmp_ID == 'D15040004221' or $DefEmp_ID == 'H17050004765')
			{
				$this->load->view('v_howtouse/v_howtouse_adm', $data);
			}
			else
			{
				$this->load->view('v_howtouse/v_howtouse', $data);
			} 
			
			//$this->load->view('v_howtouse/v_howtouse', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	// End
	
	function popupallitem()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->model('m_howtouse', '', TRUE);
			$sqlApp 		= "SELECT * FROM tappname";
			$resultaApp = $this->db->query($sqlApp)->result();
			foreach($resultaApp as $therow) :
				$appName = $therow->app_name;		
			endforeach;
			$PRJCODE	= $_GET['id'];
			$PRJCODE	= $this->url_encryption_helper->decode_url($PRJCODE);
			
			$data['title'] 				= $appName;
			$data['h2_title'] 			= 'Proceduree';
			$data['main_view'] 			= 'v_howtouse/v_howtouse';
			$data['form_action']		= site_url('HowToUse/update_process');
			$data['PRJCODE'] 			= $PRJCODE;
			$data['secShowAll']			= site_url('HowToUse/popupallitem/?id='.$this->url_encryption_helper->encode_url($PRJCODE));
			
			$data['recordcountAllItem'] = $this->m_howtouse->count_all_num_rowsAllItem();
			$data['viewAllItem'] 		= $this->m_howtouse->viewAllItemMatBudget()->result();
					
			$this->load->view('v_howtouse/v_howtouse_selectitem', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add() // OK
	{	
		$this->load->model('m_howtouse', '', TRUE);
		
		if ($this->session->userdata('login') == TRUE)
		{
			$idAppName	= $_GET['id'];
			$appName	= $this->url_encryption_helper->decode_url($idAppName);
			
			$data['title'] 				= $appName;
			
			$docPatternPosition 		= 'Especially';	
			$data['title'] 				= $appName;
			$data['task'] 				= 'add';
			$data['h2_title']			= 'Add Procerdure';
			$data['h3_title']			= 'Help';
			$data['form_action']		= site_url('howtouse/add_process');
			//$data['link'] 				= array('link_back' => anchor('howtouse/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('howtouse/');
			$MenuCode 					= 'MN057';
			$data['viewDocPattern'] 	= $this->m_howtouse->getDataDocPat($MenuCode)->result();
			
			$this->load->view('v_howtouse/v_howtouse_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function add_process() // OK
	{	
		$this->load->model('m_howtouse', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$InsAG 		= array('HTU_CODE' 			=> $this->input->post('HTU_CODE'),
								'HTU_TITLE' 		=> $this->input->post('HTU_TITLE'),
								'HTU_PROCEDURE' 	=> $this->input->post('HTU_PROCEDURE'));
//								'HTU_TYPE' 		=> $this->input->post('HTU_TYPE'),
//								'HTU_COLOR'		=> $this->input->post('HTU_COLOR'),
//								'HTU_STAT' 		=> $this->input->post('HTU_STAT'),
//								'HTU_NOTE'		=> $this->input->post('HTU_NOTE'));

			$this->m_howtouse->add($InsAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('howtouse/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update() // OK
	{	
		$this->load->model('m_howtouse', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HTU_CODE	= $_GET['id'];
			$HTU_CODE	= $this->url_encryption_helper->decode_url($HTU_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'edit';
			$data['h2_title']		= 'Update Procedure';
			$data['h3_title']		= 'Help';
			$data['form_action']	= site_url('howtouse/update_process');
			//$data['link'] 			= array('link_back' => anchor('howtouse/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Cancel" />', array('style' => 'text-decoration: none;')));
			$data['backURL'] 	= site_url('howtouse/');
			
			$getAG 					= $this->m_howtouse->get_AG($HTU_CODE)->row();
			
			$data['default']['HTU_CODE'] 		= $getAG->HTU_CODE;
			$data['default']['HTU_TITLE'] 		= $getAG->HTU_TITLE;
			$data['default']['HTU_PROCEDURE']	= $getAG->HTU_PROCEDURE;
//			$data['default']['HTU_TYPE'] 		= $getAG->HTU_TYPE;
//			$data['default']['HTU_COLOR'] 		= $getAG->HTU_COLOR;
//			$data['default']['HTU_STAT'] 		= $getAG->HTU_STAT;
//			$data['default']['HTU_NOTE'] 		= $getAG->HTU_NOTE;
			
			$this->load->view('v_howtouse/v_howtouse_form', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
		function update2() // OK
	{	
		$this->load->model('m_howtouse', '', TRUE);
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;
		
		if ($this->session->userdata('login') == TRUE)
		{
			$HTU_CODE	= $_GET['id'];
			$HTU_CODE	= $this->url_encryption_helper->decode_url($HTU_CODE);
			
			$data['title'] 			= $appName;
			$data['task'] 			= 'Help';
			$data['h2_title']		= 'Procedure';
			$data['h3_title']		= 'Help';
			$data['form_action']	= site_url('howtouse/update_process');
			$data['link'] 			= array('link_back' => anchor('howtouse/','<input type="button" class="btn btn-danger" id="btnCancel" class="button_css" value="Back" />', array('style' => 'text-decoration: none;')));
			
			
			$getAG 					= $this->m_howtouse->get_AG($HTU_CODE)->row();
			
			$data['default']['HTU_CODE'] 		= $getAG->HTU_CODE;
			$data['default']['HTU_TITLE'] 		= $getAG->HTU_TITLE;
			$data['default']['HTU_PROCEDURE']	= $getAG->HTU_PROCEDURE;
			
			$this->load->view('v_howtouse/v_howtouse_form2', $data);
		}
		else
		{
			redirect('Auth');
		}
	}
	
	function update_process()
	{	
		$this->load->model('m_howtouse', '', TRUE);
		
		$sqlApp 		= "SELECT * FROM tappname";
		$resultaApp = $this->db->query($sqlApp)->result();
		foreach($resultaApp as $therow) :
			$appName = $therow->app_name;		
		endforeach;	
		
		if ($this->session->userdata('login') == TRUE)
		{		
			$this->db->trans_begin();
			
			$HTU_CODE	= $this->input->post('HTU_CODE');
			
			$UpdAG 		= array('HTU_CODE' 			=> $this->input->post('HTU_CODE'),
								'HTU_TITLE' 		=> $this->input->post('HTU_TITLE'),
								'HTU_PROCEDURE'		=> $this->input->post('HTU_PROCEDURE'));
//								'HTU_TYPE' 		=> $this->input->post('HTU_TYPE'),
//								'HTU_COLOR'		=> $this->input->post('HTU_COLOR'),
//								'HTU_STAT' 		=> $this->input->post('HTU_STAT'),
//								'HTU_NOTE' 		=> $this->input->post('HTU_NOTE'));
								
			$this->m_howtouse->update($HTU_CODE, $UpdAG);
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			}
			else
			{
				$this->db->trans_commit();
			}
			
			$url			= site_url('howtouse/index1/?id='.$this->url_encryption_helper->encode_url($appName));
			redirect($url);
		}
		else
		{
			redirect('Auth');
		}
	}
}